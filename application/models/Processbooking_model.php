<?php
/**
 * Booking Process Model
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to booking process
*/
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'third_party/paymentexpress/PxPay_Curl.inc.php';
class Processbooking_model extends CI_Model 
{

/*
 * ------------------------------------------------------
 *  Load Soap and REST service model
 * ------------------------------------------------------
*/
	public function __construct() {
		parent::__construct(); 
		$this->load->model('soapservices_model');
		$this->load->model('restservices_model');
	}

/*
 * ------------------------------------------------------
 *  To check whether return is applicable for ticket type
 *  input: ticket type
 *  output: boolean
 * ------------------------------------------------------
*/	
	protected function checkForReturn($type) {
	  $isReturn = false;
	  switch($type){
		case "HRH":
		  $isReturn = false;
		  break;
		case "HRD":
		  $isReturn = true;
		  break;
		case "HRE":
		  $isReturn = true;
		  break;
		case "OW":
		  $isReturn = false;
		  break;
		default :
		  break;
	  }
	  return $isReturn;
	}
/*
 * ------------------------------------------------------
 *  To check ticket type n case of Agent
 *  input: ticket type
 *  output: Agent ticket type
 * ------------------------------------------------------
*/		
	protected function workTicketCheck($ticket) {
	  switch ($ticket) {
		case 'HRH':
		  return 'LOW';
		case 'HRD':
		  return 'LOC';
		case 'HRE':
		  return 'LOC';
		case 'OW':
		  return 'LOW';
		default:
		  return 'LOW';
	  }
	}

/*
 * ------------------------------------------------------
 *  To check resource type for different type of passengers
 *  input: $type(work or normal), $familyPass and $hasOnlyFamilyPass
 *  output: resource code
 * ------------------------------------------------------
*/
	protected function searchPassengerResources($type, $familyPass, $hasOnlyFamilyPass){

		  $defaultResource = 'PAX';
		  $familyResource = 'PXF';

		  if ($type === 'work') {
			$defaultResource = 'PXL';
		  }

		  if ($familyPass) {

			//$passengerResource = $hasOnlyFamilyPass ? $familyResource : "$defaultResource|$familyResource";
			$passengerResource = $hasOnlyFamilyPass ? $familyResource : $defaultResource;

			return $passengerResource;
		  }

		  return $defaultResource;
    }

/*
 * ------------------------------------------------------
 *  To check get all departures and return with prices
 *  input: $bookingDate, and $return
 *  output: departures 
 * ------------------------------------------------------
*/
	public function returnBookingRoutes($bookingDate=NULL, $return = false)
	{ 
	   try{
		    
		    //$currentBooking = $this->getCurrentBooking();
			
			/* echo "<pre>";
				print_r($currentBooking);
			echo "</pre>"; 
			exit;  */
		   
			
			$bookingService = $this->soapservices_model->resetCookie(); // remove existing booking row and create new booking session
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			if(!empty($bookingDate))
			{
				if($return == "true")
				{
					$bookingDetails['returnDate'] = $bookingDate;
				}
				else
				{
					$bookingDetails['departureDate'] = $bookingDate;
					if(strtotime($bookingDetails['returnDate']) < strtotime($bookingDate)) // return date can not be less than departure
						$bookingDetails['returnDate'] = $bookingDate;
				}
			}
			
			$departData = array();
			
			$returnRouteData = array();
			
			$hasOnlyFamilyPass = 0;
			
			if($bookingDetails['passengers']['A'] == 0 && 
    		   $bookingDetails['passengers']['C'] == 0 && 
			   $bookingDetails['passengers']['T'] == 0 && 
			   $bookingDetails['passengers']['I'] == 0 && 
			   $bookingDetails['passengers']['ST'] == 0 &&
			   $bookingDetails['passengers']['SN'] == 0 &&
			   $bookingDetails['passengers']['FP'] > 0
			)
			{
				$hasOnlyFamilyPass = 1;
			}
			
			$regularPassengers = $bookingDetails['passengers']['A'] + $bookingDetails['passengers']['C'] + $bookingDetails['passengers']['T'] +$bookingDetails['passengers']['I'] +$bookingDetails['passengers']['ST'] +$bookingDetails['passengers']['SN'];
			
			
			
			if($bookingDetails['departureDate'] == $bookingDetails['returnDate'])
			{
				$tickettype = "HRD";
			}
			else
			{
				$tickettype = "HRE";
			}
			
			$productCode = "NORM";  //for Agent it will be "WORK" need to check session here
			
			if ($productCode === 'WORK') {
				
				$resourceCode = $this->searchPassengerResources('work', $bookingDetails['passengers']['FP'], $hasOnlyFamilyPass);
				//$resourceCode = "PXL";
				$current_ticket = $this->workTicketCheck($tickettype);

			} else {

				$resourceCode = $this->searchPassengerResources('normal', $bookingDetails['passengers']['FP'], $hasOnlyFamilyPass);
				//$resourceCode = "PAX";
				$current_ticket = $tickettype;
			}
	
            //get all departures
			$dataDeparture = $this->getDeparture($bookingDetails['routeCode'], $bookingDetails['departing'], $bookingDetails['departureDate']);
			
			
			if(isset($dataDeparture['isError']))
				return $dataDeparture['message'];
	
			$departData['depart'] = $dataDeparture['GetDepartureResult']['Departures']['Departure'];
			
			
			//get all returns
			$dataReturn = $this->getDeparture($bookingDetails['returnRouteCode'], $bookingDetails['departing'], $bookingDetails['returnDate']);
			
			
			if(isset($dataReturn['isError']))
				return $dataReturn['message'];
			
			$departData['return'] = $dataReturn['GetDepartureResult']['Departures']['Departure']; 
			
			
			$updateBookingRowsParams = array();  // array for adding default departure row to the booking to get rowid and adm fee
			$updateBookingRowsReturnParams = array(); // array for adding default return row to the booking
			
			$bookingDetails['ResourceCode'] = $resourceCode;
			$bookingDetails['TicketType'] = $tickettype;
			
			if(isset($departData['depart'][1]['DepartureTime'])) // to check if there are more than one departures
				$bookingDetails['DepartureTime'] = $departData['depart'][0]['DepartureTime'];
			elseif(isset($departData['depart']['DepartureTime']))
				$bookingDetails['DepartureTime'] = $departData['depart']['DepartureTime'];
			else
				$bookingDetails['DepartureTime'] = '';
			
			if(isset($departData['return'][1]['DepartureTime'])) // to check if there are more than one departures
				$bookingDetails['returnDepartureTime'] = $departData['return'][count($departData['return'])-1]['DepartureTime'];
			elseif(isset($departData['return']['DepartureTime']))
				$bookingDetails['returnDepartureTime'] = $departData['return']['DepartureTime'];
			else
				$bookingDetails['returnDepartureTime'] = '';
			
			// update booking_details session
			$this->session->set_userdata('booking_details', $bookingDetails);
			

			// preparing parameters for UpdateBookingRows
			
			$rowRemarkParams = array();
			$vehicleDialogParams = array();
			$cabinDialogParams = array();
			$passengerDialogParams = array();
			$cargoDialogParams = array();
			$luggageDialogParams = array();
			$busDialogParams = array();
			$onboardResourceDialogParams = array();
			$bookingRowParams = array();
			$basicBookingRowParams = array(
												"ResourceCode" => "",
												"SupplierCode" => "",
												"StartDate" => $bookingDetails['departureDate']."T00:00:00.000Z",
												"EndDate" => $bookingDetails['departureDate']."T00:00:00.000Z",
												"StartTime" => "",
												"Amount" => "",
												"PriceList" => "",
												"AllotmentId" => "",
												"TicketType" => $bookingDetails['TicketType'],
												"RequestStatus" => "",
												"WaitList" => false,
												"OverBook" => false,
												"RowRemarkParams" => $rowRemarkParams,
												"VehicleDialogParams" => $vehicleDialogParams,
												"CabinDialogParams" => $cabinDialogParams,
												"PassengerDialogParams" => $passengerDialogParams,
												"CargoDialogParams" => $cargoDialogParams,
												"LuggageDialogParams" => $luggageDialogParams,
												"BusDialogParams" => $busDialogParams,
												"OnboardResourceDialogParams" => $onboardResourceDialogParams
											);
											
			// if there is any family pass, we need to add separate row as resource code is different								
			if($bookingDetails['passengers']['FP'] > 0)
			{
				foreach($bookingDetails['passengers'] as $passengerType=>$passengerCount)
				{
					$passenger = array();
					if($passengerCount > 0 && $passengerType == "FP")
					{
						for($p =0; $p<$passengerCount; $p++)
						{
							$passenger['PassengerType'] = $passengerType;
							$passengerDialogParams[] = $passenger;
						}
					}
					
				}
				//departure row
				$basicBookingRowParams['ResourceCode'] = "PXF";
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['routeCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['DepartureTime'];
				$basicBookingRowParams['Amount'] = $bookingDetails['passengers']['FP'];
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
				
				//return row
				$basicBookingRowParams['ResourceCode'] = "PXF";
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['returnRouteCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['returnDepartureTime'];
				$basicBookingRowParams['Amount'] = $bookingDetails['passengers']['FP'];
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
			}
			
			// adding row for regular passengers
			if($regularPassengers > 0)
			{
				$passengerDialogParams = array();
				foreach($bookingDetails['passengers'] as $passengerType=>$passengerCount)
				{
					$passenger = array();
					if($passengerCount > 0 && $passengerType != "FP")
					{
						for($p =0; $p<$passengerCount; $p++)
						{
							$passenger['PassengerType'] = $passengerType;
							$passengerDialogParams[] = $passenger;
						}
					}
					
				}
				//departure row
				$basicBookingRowParams['ResourceCode'] = $resourceCode;
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['routeCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['DepartureTime'];
				$basicBookingRowParams['Amount'] = $regularPassengers;
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
				
				//return row
				$basicBookingRowParams['ResourceCode'] = $resourceCode;
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['returnRouteCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['returnDepartureTime'];
				$basicBookingRowParams['Amount'] = $regularPassengers;
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
			}
			
			
			if(isset($departRow['isError']))
				return $departRow['message'];

			
			$currentBooking = $this->getCurrentBooking();
			
			if(isset($currentBooking['isError']))
				return $currentBooking['message'];
			
			/* echo "<pre>";
				print_r($currentBooking);
			echo "</pre>";  */
			//exit; 
			
			
			
			if(!empty($departData['depart']))
			{
				//loop through all departures and return to find out best price
				foreach($departData as $routeType=>$routesData) 
				{
					foreach($routesData as $indx=>$routeData)
					{
						if($routeData['Bookable'] == 1)
						{
							$bookable = 'true';
						} else {
							$bookable = 'false';
						}
						
						if($routeData['RouteCode'] == "HILROT")
							$routeData['ReturnRouteCode'] = "ROTHIL";
						if($routeData['RouteCode'] == "ROTHIL")
							$routeData['ReturnRouteCode'] = "HILROT";
						
						// loop trough to get adm fee and family price(it will use below if there are mix customer(family pass + regular))
						foreach($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'] as $bookingRow)
						{
							if($bookingRow['SupplierCode'] == $routeData['RouteCode'] && $bookingRow['ResourceCode'] != "ADM")
							{
								   $routeData['RowId'] = $bookingRow['RowId'];
								   
							    if($bookingRow['ResourceCode'] == "PXF")
								{
								   $routeData['familyRowId'] = $bookingRow['RowId'];
								   $routeData['familyNetPrice'] = $bookingRow['NetPrice'];
								   $routeData['familyTicketType'] = $bookingRow['TicketType'];
								}
							}
							if($bookingRow['ResourceCode'] == "ADM")
							{
								if($routeType == "depart")
									$routeData['admFee'] = $bookingRow['NetPrice'];
								else
									$routeData['admFee'] = 0.00;
							}
						}
						
						$routeData['ticketType'] = $current_ticket;
						$routeData['resourceCode'] = $resourceCode;
						
						$requestData = array(
							"DepartureDate" => $routeData['DepartureDate'],
							"DepartureTime" => $routeData['DepartureTime'],
							"ArrivalDate" => $routeData['ArrivalDate'],
							"ArrivalTime" => $routeData['ArrivalTime'],
							"RouteCode" => $routeData['RouteCode'],
							"ReturnRouteCode" => $routeData['ReturnRouteCode'],
							"StartPortCode" => $routeData['StartPortCode'],
							"EndPortCode" => $routeData['EndPortCode'],
							"StartPortDesc" => $routeData['StartPortDesc'],
							"EndPortDesc" => $routeData['EndPortDesc'],
							"ShipCode" => $routeData['ShipCode'],
							"productCode" => $productCode,
							"resourceCode" => $resourceCode,
							"ticketType" => $current_ticket,
							"Bookable" => $bookable,
							"totalPassengerCount" => $bookingDetails['totalPassengerCount']
						);
						
						//Get best prices for route
						$routePrice =  $this->getBestPrice($requestData);
						
						if(isset($routePrice['isError']))
				            return $routePrice['message'];
						
						/* echo "<pre>";
						//print_r($requestData);
						print_r($routePrice);
						echo "</pre>"; */
						//exit; 
						
						if($routePrice['GetAvailableBestPricesMinAllocationResult']['HasError'] == '')
						{
							$capacity = 0;
							//to check if there are more than one price type
							if(isset($routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'][1]))
							{
								foreach($routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'] as $indxKey=>$priceGroup)
								{
									if($priceGroup['Resources']['Resource']['Capacity'] >= $regularPassengers)
									{
								         //to calculate discounted price for Same day return
										if($current_ticket == "HRD" && $priceGroup['Description']== "Day Group")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														//$bookingDetails['passengers'][$differentPrice['Type'] == count of passenger type
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['discounted'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['discounted'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['discounted'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['discounted'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											
											/* as we are not calling separate getBestPrice API for PXF(family) when there 
											   are mix passengers(family+ regular) then using family price from default booking row
											*/
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['discounted']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['discounted']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
											}
										}
										
										//to calculate discounted price for extended return
										if($current_ticket == "HRE" && $priceGroup['Description']== "Extended group")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['discounted'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['discounted'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['discounted'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['discounted'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['discounted']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['discounted']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
											}
										}
										
										//to calculate regular price for Same day return
										if($current_ticket == "HRD" && trim($priceGroup['Description']) == "Everyday")
										{ 
									        $routeData['everydayCost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['regular'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['regular'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['regular'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['regular'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['regular']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['regular']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['everydayCost'] = $routeData['everydayCost'] + $routeData['familyNetPrice'];
											}
										}
										
										//to calculate regular price for extended return
										if($current_ticket == "HRE" && trim($priceGroup['Description']) == "Everyday Extended")
										{
											$routeData['everydayCost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['regular'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['regular'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['regular'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['regular'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['regular']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['regular']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['everydayCost'] = $routeData['everydayCost'] + $routeData['familyNetPrice'];
											}
										}
										
									}
									
								}
							}
							else
							{
								    $priceGroup = $routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'];
								    if($priceGroup['Resources']['Resource']['Capacity'] >= $regularPassengers)
									{
										if($current_ticket == "HRD" && $priceGroup['Description']== "Day Group")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['discounted'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['discounted'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['regular'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['regular'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['regular']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['regular']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
											}
										}
										
										if($current_ticket == "HRE" && $priceGroup['Description']== "Extended group")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
														
														$routeData['discounted'][$differentPrice['Type']]['count'] =  $bookingDetails['passengers'][$differentPrice['Type']];
														$routeData['discounted'][$differentPrice['Type']]['total'] =  $bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice'];
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												
												$routeData['regular'][$type]['count'] =  $bookingDetails['passengers'][$type];
												$routeData['regular'][$type]['total'] =  $bookingDetails['passengers'][$type]*$netPrice;
											}
											
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
											{
												$routeData['regular']['FP']['count'] = $bookingDetails['passengers']['FP'];
												$routeData['regular']['FP']['total'] = $routeData['familyNetPrice'];
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
											}
										}
										$routeData['everydayCost'] = $routeData['cost'];
										
									}
							}
								
						}
						else
						{
							log_message('error', "Error in price:".$routePrice['GetAvailableBestPricesMinAllocationResult']['Message']);
						}
						
						$departData[$routeType][$indx] = $routeData;
						
					}
					
					
				}
			}
			
						/* echo "<pre>";
						   print_r($departData);	
                        echo "</pre>";	 */					   
            if(!$capacity)
				return "Requested Capacity not available.";
			
			return $departData;
			
			
	   }catch(Exception $e) {
            log_message('error', "Error in return booking routes:".$e->getMessage());
			return "Error in return booking routes:".$e->getMessage();
        }  
	   
	}
	
/*
 * ------------------------------------------------------
 *  To check get all departures  with prices
 *  input: $bookingDate
 *  output: departures 
 * ------------------------------------------------------
*/
	public function onewayBookingRoutes($bookingDate=NULL)
	{ 
	   try{
		    
		   // $currentBooking = $this->getCurrentBooking();
			
			/* echo "<pre>";
				print_r($currentBooking);
			echo "</pre>"; 
			exit;  */
		   
			
			$bookingService = $this->soapservices_model->resetCookie();
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			if(!empty($bookingDate))
			{
				$bookingDetails['departureDate'] = $bookingDate;
			}
			
			$departData = array();
			
			$returnRouteData = array();
			
			$hasOnlyFamilyPass = 0;
			
			if($bookingDetails['passengers']['A'] == 0 && 
    		   $bookingDetails['passengers']['C'] == 0 && 
			   $bookingDetails['passengers']['T'] == 0 && 
			   $bookingDetails['passengers']['I'] == 0 && 
			   $bookingDetails['passengers']['ST'] == 0 &&
			   $bookingDetails['passengers']['SN'] == 0 &&
			   $bookingDetails['passengers']['FP'] > 0
			)
			{
				$hasOnlyFamilyPass = 1;
			}
			
			$regularPassengers = $bookingDetails['passengers']['A'] + $bookingDetails['passengers']['C'] + $bookingDetails['passengers']['T'] +$bookingDetails['passengers']['I'] +$bookingDetails['passengers']['ST'] +$bookingDetails['passengers']['SN'];
			

			$tickettype = "OW";
			
			
			$productCode = "NORM";  //for Agent it will be "WORK" need to check session here
			
			if ($productCode === 'WORK') {
				
				$resourceCode = $this->searchPassengerResources('work', $bookingDetails['passengers']['FP'], $hasOnlyFamilyPass);
				//$resourceCode = "PXL";
				$current_ticket = $this->workTicketCheck($tickettype);

			} else {

				$resourceCode = $this->searchPassengerResources('normal', $bookingDetails['passengers']['FP'], $hasOnlyFamilyPass);
				//$resourceCode = "PAX";
				$current_ticket = $tickettype;
			}
	

			$dataDeparture = $this->getDeparture($bookingDetails['routeCode'], $bookingDetails['departing'], $bookingDetails['departureDate']);
			
			 /*echo "<pre>";
			print_r($dataDeparture);
			exit; */
			
			if(isset($dataDeparture['isError']))
				return $dataDeparture['message'];
			
			if(!isset($dataDeparture['GetDepartureResult']['Departures']))
			{
				return "No records found. Please try another dates";
			}
	
			$departDataArray = $dataDeparture['GetDepartureResult']['Departures']['Departure'];
			
			if(!isset($departDataArray[1]['DepartureTime']))
                $departData['depart'][0] = $dataDeparture['GetDepartureResult']['Departures']['Departure'];
			else
				$departData['depart'] = $departDataArray;
			
			$bookingDetails['ResourceCode'] = $resourceCode;
			$bookingDetails['TicketType'] = $tickettype;
			
			if(isset($departData['depart'][0]['DepartureTime']))
				$bookingDetails['DepartureTime'] = $departData['depart'][0]['DepartureTime'];
			else
				$bookingDetails['DepartureTime'] = '';
			
			
			$this->session->set_userdata('booking_details', $bookingDetails);
			
			//echo "<pre>";
			//print_r($bookingDetails);
			//exit;
			
			// preparing parameters for UpdateBookingRows
			
			$rowRemarkParams = array();
			$vehicleDialogParams = array();
			$cabinDialogParams = array();
			$passengerDialogParams = array();
			$cargoDialogParams = array();
			$luggageDialogParams = array();
			$busDialogParams = array();
			$onboardResourceDialogParams = array();
			$bookingRowParams = array();
			$basicBookingRowParams = array(
												"ResourceCode" => "",
												"SupplierCode" => "",
												"StartDate" => $bookingDetails['departureDate']."T00:00:00.000Z",
												"EndDate" => $bookingDetails['departureDate']."T00:00:00.000Z",
												"StartTime" => "",
												"Amount" => "",
												"PriceList" => "",
												"AllotmentId" => "",
												"TicketType" => $bookingDetails['TicketType'],
												"RequestStatus" => "",
												"WaitList" => false,
												"OverBook" => false,
												"RowRemarkParams" => $rowRemarkParams,
												"VehicleDialogParams" => $vehicleDialogParams,
												"CabinDialogParams" => $cabinDialogParams,
												"PassengerDialogParams" => $passengerDialogParams,
												"CargoDialogParams" => $cargoDialogParams,
												"LuggageDialogParams" => $luggageDialogParams,
												"BusDialogParams" => $busDialogParams,
												"OnboardResourceDialogParams" => $onboardResourceDialogParams
											);
											
			if($bookingDetails['passengers']['FP'] > 0)
			{
				foreach($bookingDetails['passengers'] as $passengerType=>$passengerCount)
				{
					$passenger = array();
					if($passengerCount > 0 && $passengerType == "FP")
					{
						for($p =0; $p<$passengerCount; $p++)
						{
							$passenger['PassengerType'] = $passengerType;
							$passengerDialogParams[] = $passenger;
						}
					}
					
				}
				//departure row
				$basicBookingRowParams['ResourceCode'] = "PXF";
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['routeCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['DepartureTime'];
				$basicBookingRowParams['Amount'] = $bookingDetails['passengers']['FP'];
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
				
				if(isset($departRow['isError']))
					return $departRow['message'];
				
			}
			
			if($regularPassengers > 0)
			{
				$passengerDialogParams = array();
				foreach($bookingDetails['passengers'] as $passengerType=>$passengerCount)
				{
					$passenger = array();
					if($passengerCount > 0 && $passengerType != "FP")
					{
						for($p =0; $p<$passengerCount; $p++)
						{
							$passenger['PassengerType'] = $passengerType;
							$passengerDialogParams[] = $passenger;
						}
					}
					
				}
				//departure row
				$basicBookingRowParams['ResourceCode'] = $resourceCode;
				$basicBookingRowParams['SupplierCode'] = $bookingDetails['routeCode'];
				$basicBookingRowParams['StartTime'] = $bookingDetails['DepartureTime'];
				$basicBookingRowParams['Amount'] = $regularPassengers;
				$basicBookingRowParams['PassengerDialogParams'] = $passengerDialogParams;
				
				$bookingRowParams[] = $basicBookingRowParams;
				
				$departRow = $this->addBookingRows($basicBookingRowParams, "passenger");
				
				if(isset($departRow['isError']))
					return $departRow['message'];

			}

			$currentBooking = $this->getCurrentBooking();
			
			if(isset($currentBooking['isError']))
				return $currentBooking['message'];
			
			/* echo "<pre>";
				print_r($currentBooking);
				print_r($departData);
			echo "</pre>"; 
			exit; 	 */
			
			if(!empty($departData['depart']))
			{
				foreach($departData as $routeType=>$routesData)
				{
					foreach($routesData as $indx=>$routeData)
					{
						if($routeData['Bookable'] == 1)
						{
							$bookable = 'true';
						} else {
							$bookable = 'false';
						}
						if($routeData['RouteCode'] == "HILROT")
							$routeData['ReturnRouteCode'] = "ROTHIL";
						if($routeData['RouteCode'] == "ROTHIL")
							$routeData['ReturnRouteCode'] = "HILROT";
						
						$routeData['admFee'] = 0.00;
						
						if(isset($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'][1]))
						{
							foreach($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'] as $bookingRow)
							{
								if($bookingRow['SupplierCode'] == $routeData['RouteCode'] && $bookingRow['ResourceCode'] != "ADM")
								{
									   $routeData['RowId'] = $bookingRow['RowId'];
									   
									if($bookingRow['ResourceCode'] == "PXF")
									{
									   $routeData['familyRowId'] = $bookingRow['RowId'];
									   $routeData['familyNetPrice'] = $bookingRow['NetPrice'];
									   $routeData['familyTicketType'] = $bookingRow['TicketType'];
									}
								}
								if($bookingRow['ResourceCode'] == "ADM")
								{
									if($routeType == "depart")
										$routeData['admFee'] = $bookingRow['NetPrice'];
									else
										$routeData['admFee'] = 0.00;
								}
							}
						}
						else
						{
							$bookingRow = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
							if($bookingRow['SupplierCode'] == $routeData['RouteCode'] && $bookingRow['ResourceCode'] != "ADM")
								{
									   $routeData['RowId'] = $bookingRow['RowId'];
									   
									if($bookingRow['ResourceCode'] == "PXF")
									{
									   $routeData['familyRowId'] = $bookingRow['RowId'];
									   $routeData['familyNetPrice'] = $bookingRow['NetPrice'];
									   $routeData['familyTicketType'] = $bookingRow['TicketType'];
									}
								}
								if($bookingRow['ResourceCode'] == "ADM")
								{
									if($routeType == "depart")
										$routeData['admFee'] = $bookingRow['NetPrice'];
									else
										$routeData['admFee'] = 0.00;
								}
						}
						
						$routeData['ticketType'] = $current_ticket;
						$routeData['resourceCode'] = $resourceCode;
						
						$requestData = array(
							"DepartureDate" => $routeData['DepartureDate'],
							"DepartureTime" => $routeData['DepartureTime'],
							"ArrivalDate" => $routeData['ArrivalDate'],
							"ArrivalTime" => $routeData['ArrivalTime'],
							"RouteCode" => $routeData['RouteCode'],
							"ReturnRouteCode" =>"",
							"StartPortCode" => $routeData['StartPortCode'],
							"EndPortCode" => $routeData['EndPortCode'],
							"StartPortDesc" => $routeData['StartPortDesc'],
							"EndPortDesc" => $routeData['EndPortDesc'],
							"ShipCode" => $routeData['ShipCode'],
							"productCode" => $productCode,
							"resourceCode" => $resourceCode,
							"ticketType" => $current_ticket,
							"Bookable" => $bookable,
							"totalPassengerCount" => $bookingDetails['totalPassengerCount']
						);
						
						$routePrice =  $this->getBestPrice($requestData);
						
						if(isset($routePrice['isError']))
				            return $routePrice['message'];
						
						/* echo "<pre>";
						print_r($requestData);
						print_r($routePrice);
						exit;  */
						
						if($routePrice['GetAvailableBestPricesMinAllocationResult']['HasError'] == '')
						{
							if(isset($routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'][1]))
							{
								foreach($routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'] as $indxKey=>$priceGroup)
								{
									if($priceGroup['Resources']['Resource']['Capacity'] >= $regularPassengers)
									{
										if($current_ticket == "OW" && $priceGroup['Description']== "OneWay")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
											}
											
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
										}
										if($current_ticket == "OW" && $priceGroup['Description']== "Everyday OneWay")
										{
											$capacity = 1;
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['everydayCost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
													}
												}
											}
											else
											{
												$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
												$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
												$routeData['everydayCost'] = $routeData['everydayCost']+ ($bookingDetails['passengers'][$type]*$netPrice);
											}
											
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
												$routeData['everydayCost'] = $routeData['everydayCost'] + $routeData['familyNetPrice'];
										}
										
									}
									
								}
							}
							else
							{
								    $priceGroup = $routePrice['GetAvailableBestPricesMinAllocationResult']['OutwardDepartures']['Departure']['TicketGroups']['TicketGroup'];
								    if($priceGroup['Resources']['Resource']['Capacity'] >= $regularPassengers)
									{
										if($current_ticket == "OW" && $priceGroup['Description']== "OneWay")
										{
											$routeData['ticketType'] = $priceGroup['Resources']['Resource']['TicketType'];
											$routeData['cost'] = 0;
											if(isset($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'][1]))
											{
												foreach($priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice'] as $differentPrice)
												{
													if(isset($bookingDetails['passengers'][$differentPrice['Type']]))
													{
														$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$differentPrice['Type']]*$differentPrice['NetPrice']);
													}
												}
											}
											else
												{
													$type = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['Type'];
													$netPrice = $priceGroup['Resources']['Resource']['AllPrices']['ResourcePrice']['NetPrice'];
													$routeData['cost'] = $routeData['cost']+ ($bookingDetails['passengers'][$type]*$netPrice);
												}
												
											if(isset($routeData['familyNetPrice']) && !$hasOnlyFamilyPass)
												$routeData['cost'] = $routeData['cost'] + $routeData['familyNetPrice'];
										}
										$routeData['everydayCost'] = $routeData['cost'];
									}
							}
								
						}
						else
						{
							log_message('error', "Error in price:".$routePrice['GetAvailableBestPricesMinAllocationResult']['Message']);
						}
						
						$departData[$routeType][$indx] = $routeData;
						
					}
					
					
				}
			}
			
						/* echo "<pre>";
						   print_r($departData);	
                        echo "</pre>";	 */					   
 
			
			return $departData;
			
			
	   }catch(Exception $e) {
            log_message('error', "Error in return booking routes:".$e->getMessage());
			return "Error in return booking routes:".$e->getMessage();
        }  
	   
	}

/*
 * ------------------------------------------------------
 *  To get current bookings
 * ------------------------------------------------------
*/	
	public function getCurrentBooking()
	{
		$bookingService = $this->soapservices_model->getBookingService();
		$errorResponse = array();
    	try {
	    	$methodName = 'GetBooking';
			$params = array();
			$response = $bookingService->__soapCall($methodName, array($params));
			$responseArray = json_decode(json_encode($response), true);
			if($responseArray['GetBookingResult']['HasError'] == '')
			{
				return $responseArray;
			}
			else
			{
				log_message('error', "Error in departure routes: ErrorCode ".$responseArray['GetBookingResult']['ErrorCode'].": Error Message ".$responseArray['GetBookingResult']['Message']);
				//$this->session->set_flashdata('message_error', $responseArray['GetBookingResult']['Message']);
				$errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getCurrentBooking : ".$responseArray['GetBookingResult']['Message'];
                return $errorResponse;
			}
		}catch(Exception $e) {
				log_message('error', "Error in getCurrentBooking :".$e->getMessage());
				//$this->session->set_flashdata('message_error', 'Error in getting Booking details. Please try again.');
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getCurrentBooking : ".$e->getMessage();
                return $errorResponse;
        }
	}
	
/*
 * ------------------------------------------------------
 *  To get current bookings
 * ------------------------------------------------------
*/	
	public function removeBookingRows()
	{
		$bookingService = $this->soapservices_model->getBookingService();
		$errorResponse = array();
    	try {
	    	$methodName = 'RemoveAllBookingRows';
			$params = array();
			$response = $bookingService->__soapCall($methodName, array($params));
			$responseArray = json_decode(json_encode($response), true);
			
			if($responseArray['RemoveAllBookingRowsResult']['HasError'] == '')
			{
				return $responseArray;
			}
			else{
			    log_message('error', "Error in departure routes: ErrorCode:".$responseArray['GetBookingResult']['ErrorCode'].": Error Message: ".$responseArray['GetBookingResult']['Message']);
				//$this->session->set_flashdata('message_error', 'Error in getting Booking details. Please try again.');
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in removeBookingRows :".$responseArray['GetBookingResult']['Message'];
                return $errorResponse;
			}
			
		}catch(Exception $e) {
				log_message('error', "Error in removeBookingRows :".$e->getMessage());
				//$this->session->set_flashdata('message_error', 'Error in getting Booking details. Please try again.');
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in removeBookingRows :".$e->getMessage();
                return $errorResponse;
        }
	}

/*
 * ------------------------------------------------------
 *  To add booking row
 * ------------------------------------------------------
*/	
    public function addBookingRows($requestData, $type, $return=false)
    {

		$bookingService = $this->soapservices_model->getBookingService();
	
		$errorResponse = array();
    	try {
	    	$methodName = 'UpdateBookingRows';

	    	$params = array(
			                "bookingRowsParam" => 
					        array(
							     "BookingRowParams" =>
					             array(
								      "BookingRowParam" => $requestData
									)
								)
	    	                );
//echo "<pre>";						
//print_r($params);
//exit;
	    	$response = $bookingService->__soapCall($methodName, array($params));
			$responseArray = json_decode(json_encode($response), true);
//print_r($responseArray);
//exit;
			if($responseArray['UpdateBookingRowsResult']['HasError'] == '')
			{
				return $responseArray;
			}
			else
			{
				log_message('error', "Error in updateBookingRows: ErrorCode ".$responseArray['UpdateBookingRowsResult']['ErrorCode'].": Error Message ".$responseArray['UpdateBookingRowsResult']['Message']);
				//$this->session->set_flashdata('message_error', $responseArray['UpdateBookingRowsResult']['Message']);
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in UpdateBookingRows : ".$responseArray['UpdateBookingRowsResult']['Message'];
                return $errorResponse;
			}
    	} catch(Exception $e) {
            log_message('error', "Error in updateBookingRows:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while adding booking row:'.$e->getMessage());
            $errorResponse['isError'] = true;
			$errorResponse['message'] = "Error in UpdateBookingRows : ".$e->getMessage();
			return $errorResponse;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  To add booking row
 * ------------------------------------------------------
*/	
    public function deleteBookingRows()
    {

    	try {
			
			$currentBooking = $this->getCurrentBooking();
			
			$bookingService = $this->soapservices_model->getBookingService();
		
			$errorResponse = array();
	    	$methodName = 'UpdateBookingRows';
            if(isset($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow']))
			{
				if(isset($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow']['RowId']))
					$bookingRows[] = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
				else
					$bookingRows = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
				foreach($bookingRows as $bookingRow)
				{
					$requestData['RowId'] = $bookingRow['RowId'];
					$requestData['Delete'] = true;
					$params = array(
									"bookingRowsParam" => 
									array(
										 "BookingRowParams" =>
										 array(
											  "BookingRowParam" => $requestData
											)
										)
									);
					$response = $bookingService->__soapCall($methodName, array($params));
					$responseArray = json_decode(json_encode($response), true);

					if($responseArray['UpdateBookingRowsResult']['HasError'] != '')
					{
						log_message('error', "Error in updateBookingRows: ErrorCode ".$responseArray['UpdateBookingRowsResult']['ErrorCode'].": Error Message ".$responseArray['UpdateBookingRowsResult']['Message']);
						//$this->session->set_flashdata('message_error', $responseArray['UpdateBookingRowsResult']['Message']);
						$errorResponse['isError'] = true;
						echo $errorResponse['message'] = "Error in DeleteBookingRows : ".$responseArray['UpdateBookingRowsResult']['Message'];
						//exit;
					}
				}
			}
    	} catch(Exception $e) {
            log_message('error', "Error in updateBookingRows:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while adding booking row:'.$e->getMessage());
            $errorResponse['isError'] = true;
			echo $errorResponse['message'] = "Error in DeleteBookingRows exception : ".$e->getMessage();
			exit;
        } 
	}
/*
 * ------------------------------------------------------
 *  To update booking row
 * ------------------------------------------------------
*/	
	public function updateBookingRows($requestData)
    {
		
		$bookingService = $this->soapservices_model->getBookingService();
		
		$errorResponse = array();
    	try {
	    	$methodName = 'UpdateBookingRows';

	    	$params = array(
			                "bookingRowsParam" => 
					        array(
							     "BookingRowParams" =>
					             array(
								      "BookingRowParam" =>
									  array(
												"RowId" => $requestData['RowId'],
												"StartTime" => $requestData['DepartureTime'],
												"TicketType" => $requestData['TicketType']
											)
										)
								)
           	    	        );

	    	$response = $bookingService->__soapCall($methodName, array($params));
			$responseArray = json_decode(json_encode($response), true);
			$currentBooking =array();
			if($responseArray['UpdateBookingRowsResult']['HasError'] == '')
			{
				$currentBooking['isError'] = false;
				$currentBooking['message'] = "";
				$currentBooking['result'] = $this->getCurrentBooking();
				return $currentBooking;
			}
			else
			{
				log_message('error', "Error in updateBookingRows: ErrorCode ".$responseArray['UpdateBookingRowsResult']['ErrorCode'].": Error Message ".$responseArray['UpdateBookingRowsResult']['Message']);
				//$this->session->set_flashdata('message_error', $responseArray['UpdateBookingRowsResult']['Message']);
                $currentBooking['isError'] = true;
				$currentBooking['message'] = "Error in UpdateBookingRows : ".$responseArray['UpdateBookingRowsResult']['Message'];
                return $currentBooking;
			}
    	} catch(Exception $e) {
            log_message('error', "Error in updateBookingRows:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while adding booking row:'.$e->getMessage());
            $currentBooking['isError'] = true;
			$currentBooking['message'] = "Error in UpdateBookingRows : ".$e->getMessage();
			return $currentBooking;
        } 
	}
	

	
/*
 * ------------------------------------------------------
 *  To get departures for given route
 * ------------------------------------------------------
*/

    public function getDeparture($routeCode, $startPortCode, $departureDate)
    {
		$departureService = $this->soapservices_model->getDepartureService();
		$errorResponse = array();
    	try {
	    	$methodName = 'GetDeparture';

	    	$params = array(
	    		"DepartureQuery" => array(
	    			"RouteCode" => $routeCode,
	    			"StartPortCode" => $startPortCode,
	    			"DepartureDate" => $departureDate,
	    		)
	    	);

	    	$departureResponse = $departureService->__soapCall($methodName, array($params));
			$departureResponseArray = json_decode(json_encode($departureResponse), true);
			if($departureResponseArray['GetDepartureResult']['HasError'] == '')
			{
				return $departureResponseArray;
			}
			else
			{
				log_message('error', "Error in departure routes: ErrorCode".$departureResponseArray['GetDepartureResult']['ErrorCode'].": Error Message".$departureResponseArray['GetDepartureResult']['Message']);
				//$this->session->set_flashdata('message_error', $departureResponseArray['GetDepartureResult']['Message']);
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getDeparture : ".$departureResponseArray['GetDepartureResult']['Message'];
                return $errorResponse;
			}
			
    	} catch(Exception $e) {
				log_message('error', "Error in departure routes:".$e->getMessage());
				//$this->session->set_flashdata('message_error', $e->getMessage());
				$errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getDeparture : ".$e->getMessage();
                return $errorResponse;
        } 
	}

/*
 * ------------------------------------------------------
 *  To get departures for given route with price
 * ------------------------------------------------------
*/
	public function getAvailableDeparture($routeCode, $startPortCode, $departureDate)
    {
		$departureService = $this->soapservices_model->getAvailabilityService();
		
    	try {
	    	$methodName = 'GetAvailableDepartureBased';

	    	$params = array(
	    		"Query" => array(
	    			"DepartureRoute" => $routeCode,
	    			"ReturnRoute" => "ROTHIL",
	    			"DepartureDate" => $departureDate,
	    			"ReturnDate" => $departureDate,
	    			"DepartureResources" => "PAX",
	    			"ReturnResources" => "PAX",
					"DepartureYields" =>array("string"=>"A"),
	    			"Days" => 1,
	    			"IncludePrices" => true
	    		)
	    	);

	    	$avilableDepartureObj = $departureService->__soapCall($methodName, array($params));
			$avilableDeparture = json_decode(json_encode($avilableDepartureObj), true);
			/* echo "<pre>";
			print_r($params);
			print_r($avilableDeparture); */
	    	
    	} catch(Exception $e) {
            return log_message('error', "Error in departure routes:".$e->getMessage());
        } 
	}
	
	public function getCruiseDeparture($routeCode, $departureDate)
	{
		$departureService = $this->soapservices_model->getDepartureService();
		
    	try {
	    	$methodName = 'GetCruiseDepartures';

	    	$params = array(
	    		"CruiseDepartureQuery" => array(
	    			"RouteCode" => $routeCode,
	    			"ToDepartureDate" => $departureDate,
	    			"DepartureDate" => $departureDate,
	    		)
	    	);

	    	return $departureService->__soapCall($methodName, array($params));
    	} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        } 
	}

    public function getPrice()
    {
		$priceService = $this->soapservices_model->getPriceService();

		try {
			$methodName = 'GetPrices';
			$bookingSession = $this->session->userdata('booking_details');
			$priceSessionValues = json_decode(json_encode($bookingSession), true);

	    	// Get Resource
		    $bookableResource = $this->getBookableResource($priceSessionValues['routeCode']);

		    $bookingResource = json_decode(json_encode($bookableResource), true);

		    if($bookingResource['GetBookableResourcesResult']['ErrorCode'] != 1)
		    {
		    	$resourceArray = $bookingResource['GetBookableResourcesResult']['BookableResource'];

		    	$resourceCode = array();
		    	foreach ($resourceArray as $resourceValue) {
		    		if(array_key_exists('AddResource', $resourceValue))
		    		{
		    			$resourceCode[] = array(
			    			"ResourceCode" => $resourceValue['ResourceCode'],
			    			"Description" => $resourceValue['Description'],
			   				"Group" => $resourceValue['Group'],
			   				"SubGroup" => $resourceValue['SubGroup'],
			   				"Category" => $resourceValue['Category'],
			   				"DialogAmount" => $resourceValue['DialogAmount'],
		    				"AllotmentModel" => $resourceValue['AllotmentModel'],
		    				"LoadCoefficient" => $resourceValue['LoadCoefficient'],
		    				"TypeOfDialog" => $resourceValue['TypeOfDialog'],
			    			"AddResource" => array(
			    				"ResourceCode" => $resourceValue['AddResource']['ResourceCode'],
			    				"Description" => $resourceValue['AddResource']['Description'],
			    				"AllotmentModel" => $resourceValue['AddResource']['AllotmentModel'],
			    				"Amount" => $resourceValue['AddResource']['Amount'],
			    				"DialogAmount" => $resourceValue['AddResource']['DialogAmount'],
			    				"ExtraDialogAmount" => $resourceValue['AddResource']['ExtraDialogAmount'],
			    				"Required" => $resourceValue['AddResource']['Required']
			    			)
			    		);
		    		} else{
		    			$resourceCode[] = array(
			    			"ResourceCode" => $resourceValue['ResourceCode'],
			    			"Description" => $resourceValue['Description'],
			    			"Group" => $resourceValue['Group'],
			    			"SubGroup" => $resourceValue['SubGroup'],
			    			"Category" => $resourceValue['Category'],
			    			"DialogAmount" => $resourceValue['DialogAmount'],
			    			"AllotmentModel" => $resourceValue['AllotmentModel'],
			    			"LoadCoefficient" => $resourceValue['LoadCoefficient'],
			    			"TypeOfDialog" => $resourceValue['TypeOfDialog']
			    		);
		    		}
		    	}

		    	$bookingSession['resourceDetails'] = $resourceCode;
		    	$this->session->set_userdata('booking_details', $bookingSession);

		    	$bookingDetails = $this->session->userdata('booking_details');

		    	foreach ($bookingDetails['resourceDetails'] as $arrayResource) 
		    	{	
		    		$resourceCodeValue[] = $arrayResource['ResourceCode'];
		    	}
				$resourceCodeValue = $resourceCodeValue[0];
				
				if($bookingDetails['bookingType'] == 'return')
				{
					foreach ($priceSessionValues['bookingDetails']['Return'] as $returnValue) {
						$params[] = array(
				    		"Theme" => "NORM",
				    		"DepartureDate" => $returnValue['DepartureDate'],
				    		"DepartureTime" => $returnValue['DepartureTime'],
				    		"Route" => $priceSessionValues['routeCode'],
				    		"Resource" => $resourceCodeValue,
				    		"PassengerType" => "T",
				    		"Currency" => "AUD"
				    	);	
					}
					$priceArray = array();
					foreach ($params as $param) {
						$priceArray['Return'][] = $priceService->__soapCall($methodName, array($param));
					}
				}
					
				foreach ($priceSessionValues['bookingDetails']['Arrival'] as $arrivalValue) {
					$params[] = array(
				    	"Theme" => "NORM",
				    	"DepartureDate" => $arrivalValue['DepartureDate'],
				    	"DepartureTime" => $arrivalValue['DepartureTime'],
				    	"Route" => $priceSessionValues['routeCode'],
				    	"Resource" => $resourceCodeValue,
				    	"PassengerType" => "T",
				    	"Currency" => "AUD"
				    );	
				}
		    }
		    foreach ($params as $param) {
		    	$priceArray['Arrival'][] = $priceService->__soapCall($methodName, array($param));
		    }
		    
	    	return $priceArray;

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
    }
	
	public function getBestPrice($requestData)
    {
		$errorResponse = array();
		try {
			$priceService = $this->soapservices_model->getPriceService();
			$param = array(
				    	"ProductCode" => "NORM",
				    	"DepartureDate" => $requestData['DepartureDate'],
				    	"DepartureStartTime" => $requestData['DepartureTime'],
				    	"DepartureStopTime" => $requestData['DepartureTime'],
				    	"DepartureRoute" => $requestData['RouteCode'],
				    	"DepartureResource" => $requestData['resourceCode'],
				    	"DepartureMinAllocation" => $requestData['totalPassengerCount'],
				    	"ReturnDate" => $requestData['DepartureDate'],
				    	"ReturnStartTime" => $requestData['DepartureTime'],
				    	"ReturnStopTime" => $requestData['DepartureTime'],
				    	"ReturnRoute" => $requestData['ReturnRouteCode'],
				    	"ReturnResource" => $requestData['resourceCode'],
				    	"ReturnMinAllocation" => $requestData['totalPassengerCount'],
				    	"Currency" => "AUD",
				    	"Detailed" => true
				    );	
			$methodName = 'GetAvailableBestPricesMinAllocation';
			//echo "<pre>";
			//print_r($param);
			$priceResponse = $priceService->__soapCall($methodName, array($param));
			$priceArray = json_decode(json_encode($priceResponse), true);
			//print_r($priceArray);
			if($priceArray['GetAvailableBestPricesMinAllocationResult']['HasError'] == '')
			{
				return $priceArray;
			}
			else
			{
				log_message('error', "Error in departure routes: ErrorCode".$priceArray['GetAvailableBestPricesMinAllocationResult']['ErrorCode'].": Error Message".$priceArray['GetAvailableBestPricesMinAllocationResult']['Message']);
				//$this->session->set_flashdata('message_error', $priceArray['GetAvailableBestPricesMinAllocationResult']['Message']);
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getBestPrice : ".$priceArray['UpdateBookingRowsResult']['Message'];
                return $errorResponse;
			}
			
		} catch(Exception $e) {
            log_message('error', "Error in getBestPrice:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while getting the price. Please try again.');
            $errorResponse['isError'] = true;
			$errorResponse['message'] = "Error in getBestPrice : ".$e->getMessage();
			return $errorResponse;
        }  
    }
	
	public function getBestAvailablePrice($requestData)
    {
		$errorResponse = array();
		try {
			$priceService = $this->soapservices_model->getPriceService();
			$param = array(
				    	"ProductCode" => "NORM",
				    	"DepartureDate" => $requestData['DepartureDate'],
				    	"DepartureStartTime" => $requestData['DepartureStartTime'],
				    	"DepartureStopTime" => $requestData['DepartureStopTime'],
				    	"DepartureRoute" => $requestData['DepartureRoute'],
				    	"DepartureResource" => $requestData['DepartureResource'],
				    	"ReturnDate" => $requestData['ReturnDate'],
				    	"ReturnStartTime" => $requestData['ReturnStartTime'],
				    	"ReturnStopTime" => $requestData['ReturnStopTime'],
				    	"ReturnRoute" => $requestData['ReturnRoute'],
				    	"ReturnResource" => $requestData['ReturnResource'],
				    	"Currency" => "AUD",
				    	"Detailed" => true
				    );	
			$methodName = 'GetAvailableBestPrices';
			//echo "<pre>";
			//print_r($param);
			$priceResponse = $priceService->__soapCall($methodName, array($param));
			$priceArray = json_decode(json_encode($priceResponse), true);
			//print_r($priceArray);
			if($priceArray['GetAvailableBestPricesResult']['HasError'] == '')
			{
				return $priceArray;
			}
			else
			{
				log_message('error', "Error in departure routes: ErrorCode".$priceArray['GetAvailableBestPricesResult']['ErrorCode'].": Error Message".$priceArray['GetAvailableBestPricesResult']['Message']);
				//$this->session->set_flashdata('message_error', $priceArray['GetAvailableBestPricesResult']['Message']);
                $errorResponse['isError'] = true;
				$errorResponse['message'] = "Error in getBestPrice : ".$priceArray['UpdateBookingRowsResult']['Message'];
                return $errorResponse;
			}
			
		} catch(Exception $e) {
            log_message('error', "Error in getBestPrice:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while getting the price. Please try again.');
            $errorResponse['isError'] = true;
			$errorResponse['message'] = "Error in getBestPrice : ".$e->getMessage();
			return $errorResponse;
        }  
    }
	
	public function getResouceGroup()
	{
		$definitionService = $this->soapservices_model->getDefinitionsService();
		
    	try {
	    	$methodName = 'GetResourceGroups';
			$params = array();
			$response = $definitionService->__soapCall($methodName, array($params));
			print_r($response);
		}catch(Exception $e) {
            return log_message('error', "Error in getResouceGroup :".$e->getMessage());
        }
	}

	public function getResources($resourceCode, $supplier, $resourceGroup)
    {
    	
		$definitionService = $this->soapservices_model->getDefinitionsService();

		try {
			$methodName = 'GetResources';

			$params['ResourceQuery']['ResourceCodes']['ResourceCode'][] = $resourceCode;
			$params['ResourceQuery']['Suppliers']['Supplier'][] = $supplier;
			$params['ResourceQuery']['ResourceGroups']['ResourceGroup'][] = $resourceGroup;

			return $definitionService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in getResources:".$e->getMessage());
        }  
	}

	public function getHolidayPackageDefinition($departureDate=NULL)
	{
		$this->soapservices_model->resetCookie();
			
		$bookingDetails = $this->session->userdata('booking_details');
		
		$definitionService = $this->soapservices_model->getDefinitionsService();
		
		if($departureDate == NULL)
			$departureDate = $bookingDetails['departureDate'];

		try {
			$methodName = 'GetHolidayPackageDefinition';

			$params = array(
				"Data" => array(
					"PackageCode" => $bookingDetails['packageCode'],
					"PackageVersion" => "",
					"TravelDate" => $departureDate."T00:00:00.000Z",
				)
			);

			$getHolidayPackageDefinition  = $definitionService->__soapCall($methodName, array($params));
			$result = json_decode(json_encode($getHolidayPackageDefinition), true);
			
			$hasError = $this->soapservices_model->checkApiError($methodName, $result);
			
			if($hasError)
				return $hasError;
			
			/* echo "<pre>";
			print_r($result);
			exit; */
			$availableResource = $result['GetHolidayPackageDefinitionResult']['PackageDefinition']['Resources']['Resource'];
			//print_r($availableResource);
			if(isset($availableResource) && count($availableResource) > 0)
			{
				$packageItems = array();
				$avilableDepartReturnData = array();
				$r = 0;
				foreach($availableResource as $key=>$resource)
				{
					$packageItems['PackageItem'][$r]['StartDate'] = $departureDate;
					$packageItems['PackageItem'][$r]['EndDate'] = $departureDate;
					if(isset($resource['TicketTypes']['TicketType']['TicketType']))
						$packageItems['PackageItem'][$r]['TicketType'] = $resource['TicketTypes']['TicketType']['TicketType'];
					else
						$packageItems['PackageItem'][$r]['TicketType'] = "";
					$packageItems['PackageItem'][$r]['ResourceCode'] = $resource['ResourceCode'];
					$packageItems['PackageItem'][$r]['SupplierCode'] = $resource['SupplierCode'];
					if($resource['ResourceCode'] == "PAX")
					{
                        $paxAmountarray = array();
						foreach($bookingDetails['passengers'] as $type=>$value)
						{
							$paxAmountarray[] = $type."-".$value;
						}
						
						$paxAmount = implode("|",$paxAmountarray);
						$packageItems['PackageItem'][$r]['Amount'] = $paxAmount;
					}
					else
					$packageItems['PackageItem'][$r]['Amount'] = $bookingDetails['totalPassengerCount'];
				
					$packageItems['PackageItem'][$r]['Time'] = "";
				
				    
						
					if(isset($resource['DepartureTimes']['DepartureTime']) && $resource['ResourceCode'] == "PAX")
					{
						
						sort($resource['DepartureTimes']['DepartureTime']);
						
						$departurestarttime = $resource['DepartureTimes']['DepartureTime'][0];
						
						$resourceCount = count($resource['DepartureTimes']['DepartureTime']);
						$departurestoptime = $resource['DepartureTimes']['DepartureTime'][$resourceCount-1];
						
						if($resource['SupplierCode'] == "HILROT" || $resource['SupplierCode'] == "ROTHIL")
							$departureroute =  $resource['SupplierCode'];
						else
							$departureroute =  "";
							
						
					  if($resource['SupplierCode'] == "HILROT")
							$returnroute = "ROTHIL";
					  elseif($resource['SupplierCode'] == "ROTHIL")
						  $returnroute = "HILROT";
					  else
						  $returnroute = '';
					  
						$departureresource = $resource['ResourceCode'];
						  
						$requestData = array();

				    	$requestData['DepartureDate'] = $departureDate;
				    	$requestData['DepartureStartTime'] = $departurestarttime;
				    	$requestData['DepartureStopTime'] = $departurestoptime;
				    	$requestData['DepartureRoute'] = $departureroute;
				    	$requestData['DepartureResource'] = $departureresource;
				    	$requestData['ReturnDate'] = $departureDate;
				    	$requestData['ReturnStartTime']  = $departurestarttime;
				    	$requestData['ReturnStopTime'] = $departurestoptime;
				    	$requestData['ReturnRoute'] = $returnroute;
				    	$requestData['ReturnResource'] = $departureresource;
						
						
						//print_r($requestData);
						  

                        $price = $this->getBestAvailablePrice($requestData);
						
						//print_r($price);
					    
						if($price['GetAvailableBestPricesResult']['HasError'] == '')
						{
							if(isset($price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure']) && count($price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure']) >0)
							{
								$outwardDepart = array();
								
								if(isset($price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'][1]))
									$outwardDepart = $price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'];
								else
									$outwardDepart[0] = $price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'];
								
								$d = 0;
								foreach($outwardDepart as $departData)
								{
									
									if($resource['SupplierCode'] == "HILROT")
									{
										$key = "depart";
										$startPortDesc = "Hillarys";
										$endPortDesc = "Rottnest Island";
									}
									else
									{
										$key = "return";
										$startPortDesc = "Rottnest Island";
										$endPortDesc = "Hillarys";
									}
										$avilableDepartReturnData[$key][$d]['departureDate'] = $departureDate;
										$avilableDepartReturnData[$key][$d]['startPortDesc'] = $startPortDesc;
										$avilableDepartReturnData[$key][$d]['endPortDesc'] = $endPortDesc;
										$avilableDepartReturnData[$key][$d]['departureTime'] = $departData['DepartureTime'];
										$avilableDepartReturnData[$key][$d]['arrivalTime'] = $departData['ArrivalTime'];
										$avilableDepartReturnData[$key][$d]['routeCode'] = $departData['RouteCode'];
										$avilableDepartReturnData[$key][$d]['isLocked'] = $departData['IsLocked'];
										$avilableDepartReturnData[$key][$d]['bookable'] = $departData['Bookable'];
										
										if(isset($departData['TicketGroups']['TicketGroup'][1]))
										{
											foreach($departData['TicketGroups']['TicketGroup'] as $priceGroup)
											{
												if($priceGroup['Description'] == "Everyday")
												{
													
													if($priceGroup['Resources']['Resource']['Capacity'] > $bookingDetails['totalPassengerCount'])
													{
														$avilableDepartReturnData[$key][$d]['capacity'] = 1;
													}
													else
													{
														$avilableDepartReturnData[$key][$d]['capacity'] = 0;
													}
												}
												
											}
										}
										else{
											if($departData['TicketGroups']['TicketGroup']['Resources']['Resource']['Capacity'] > $bookingDetails['totalPassengerCount'])
											{
												$avilableDepartReturnData[$key][$d]['capacity'] = 1;
											}
											else
											{
												$avilableDepartReturnData[$key][$d]['capacity'] = 0;
											}
										}
								    $d++;
								}
							}
						}
					}
					$r++;
					
				}
				//echo "<pre>";
				//print_r($packageItems);
				//print_r($avilableDepartReturnData);
				
				// add default time in package items
				
				foreach($packageItems['PackageItem'] as $pk=>$pi)
				{
					if($pi['SupplierCode'] == 'HILROT')
						$pi['Time'] = $avilableDepartReturnData['depart'][0]['departureTime'];
					if($pi['SupplierCode'] == 'ROTHIL')
						$pi['Time'] = $avilableDepartReturnData['return'][0]['departureTime'];
					
					$packageItems['PackageItem'][$pk] = $pi;
				}
				
				$bookingDetails['PackageItems'] = $packageItems;
				$this->session->set_userdata('booking_details', $bookingDetails); 
				
				$methodName = 'BookHolidayPackage';

				
				$params = array(
					"Data" => array(
						"PackageCode" => $bookingDetails['packageCode'],
						"PackageAmount" => $bookingDetails['totalPassengerCount'],
						"TravelDate" => $departureDate."T00:00:00.000Z",
						"PackageItems" => $packageItems
					)
				);
				
				//print_r($params);
				$this->setProductCode("PACK");
				
				
                $bookingService = $this->soapservices_model->getBookingService();
				$getBookHolidayPackage  = $bookingService->__soapCall($methodName, array($params));
				$result = json_decode(json_encode($getBookHolidayPackage), true);
				
				$hasError = $this->soapservices_model->checkApiError($methodName, $result);
			
				if($hasError)
					return $hasError;
				
				$currentBooking = $this->getCurrentBooking();
				
				foreach($avilableDepartReturnData as $dataKey=>$dataValues)
				{
					foreach($dataValues as $dk=>$dataValue)
					{
						$dataValue['total'] = $currentBooking['GetBookingResult']['Booking']['AmountToPay'];
						
						foreach($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'] as $bookingrow)
						{
							if(($bookingrow['ResourceCode'] == "PAX" || $bookingrow['ResourceCode'] == "ADM")&& $bookingrow['SupplierCode']  == $dataValue['routeCode'])
							{
								if($bookingrow['ResourceCode'] == "PAX")
								{
									$dataValue['cost'] = $bookingrow['NetPrice'];
									$dataValue['rowId'] = $bookingrow['RowId'];
								}
								if($bookingrow['ResourceCode'] == "ADM" && $bookingrow['SupplierCode']  == "HILROT")
								{
									$dataValue['admFee'] = $bookingrow['NetPrice'];
								}
								else
								{
									$dataValue['admFee'] = 0.00;
								}
							}
							
						}
						$dataValues[$dk] = $dataValue;
					}
					$avilableDepartReturnData[$dataKey] = $dataValues;
				}
				
				return $avilableDepartReturnData;
				
				}
				else{
					log_message('error', $bookingDetails['packageCode']." Package details are unavailable");
					$errorResponse = array();
					$errorResponse['isError'] = true;
					$errorResponse['message'] = $bookingDetails['packageCode']." Package details are unavailable";
					return $errorResponse;
				}
			

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
	}
	
/*
 * ------------------------------------------------------
 *  To update booking row for package
 * ------------------------------------------------------
*/	
	public function updatePackageBookingRows($requestData)
    {

    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			$packageItems = $bookingDetails['PackageItems'];
			
			foreach($packageItems['PackageItem'] as $packagekey=>$packageItem)
			{
				if($packageItem['SupplierCode'] == $requestData['routeCode'])
				   $packageItem['Time'] = $requestData['departureTime'];
			   
			   $packageItems['PackageItem'][$packagekey] = $packageItem;
			}
			
	    	$methodName = 'BookHolidayPackage';

				
				$params = array(
					"Data" => array(
						"PackageCode" => $bookingDetails['packageCode'],
						"PackageAmount" => $bookingDetails['totalPassengerCount'],
						"TravelDate" => $packageItems['PackageItem'][0]['StartDate']."T00:00:00.000Z",
						"PackageItems" => $packageItems
					)
				);
				
                $bookingService = $this->soapservices_model->getBookingService();
				$getBookHolidayPackage  = $bookingService->__soapCall($methodName, array($params));
				$result = json_decode(json_encode($getBookHolidayPackage), true);
				
			$currentBooking =array();
			//print_r($result);
			if($result['BookHolidayPackageResult']['HasError'] == '')
				{
					$currentBooking['isError'] = false;
					$currentBooking['message'] = "";
					$currentBooking['result'] = $this->getCurrentBooking();
					return $currentBooking;
				}
				else
				{
					log_message('error', "Error in updateBookingRows: ErrorCode ".$result['BookHolidayPackageResult']['ErrorCode'].": Error Message ".$result['BookHolidayPackageResult']['Message']);
					$currentBooking['isError'] = true;
					$currentBooking['message'] = "Error in UpdateBookingRows : ".$result['BookHolidayPackageResult']['Message'];
					return $currentBooking;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in updateBookingRows:".$e->getMessage());
			//$this->session->set_flashdata('message_error', 'Error while adding booking row:'.$e->getMessage());
            $currentBooking['isError'] = true;
			$currentBooking['message'] = "Error in UpdateBookingRows : ".$e->getMessage();
			return $currentBooking;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  Assign product code
 *  input: $bookingDate
 *  output: booking 
 * ------------------------------------------------------
*/
	public function setProductCode($prodCode)
	{ 
	   try{
		   $params = array(
					"bookingParam" => array(
						"ProductCode" => $prodCode
					)
				);
			
			$methodName = "UpdateBooking";
			$bookingService = $this->soapservices_model->getBookingService();
			$updateBooking  = $bookingService->__soapCall($methodName, array($params));
			
	   }catch(Exception $e) {
            return log_message('error', "Error in UpdateBooking while setting the product code in booking :".$e->getMessage());
        }  
	}
	
/*

*/
    public function extrasDetails()
	{
		$bookingDetails = $this->session->userdata('booking_details');
		return $extraDetails = $this->getBookableResource($bookingDetails);
		
	}
	
/**

**/

    public function getBookableResource($bookingDetails)
    {
		
		$returnData = array();	
		
		try {

			// get current booking details
			$currentBooking = $this->getCurrentBooking();
			
			/* echo "<pre>";
			print_r($currentBooking);
			echo "</pre>"; */
			//exit;
			if(isset($currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'][1]))
				$currentBookingRows = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
			else
				$currentBookingRows[] = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
			
			foreach($currentBookingRows as $bookingRow)
			{
				if($bookingRow['ResourceCode'] == "PAX" && $bookingRow['SupplierCode'] == $bookingDetails['routeCode'])
				{
					$departTime = $bookingRow['StartTime'];
					break;
				}
			}
			
			$definitionService = $this->soapservices_model->getDefinitionsService();
			$supplier = $bookingDetails['routeCode'];
			
			$methodName = 'GetBookableResources';

			$params = array(
				"Supplier" => $supplier,
				"View" => "W"
			);

			$bookableResources =  $definitionService->__soapCall($methodName, array($params));
			
			$result = json_decode(json_encode($bookableResources), true);
			
			/* echo "<pre>";
				print_r($params);
				print_r($result);
			echo "</pre>";
			exit; */
			
			$resourceGroup = array("S", "L", "B");

			if($result['GetBookableResourcesResult']['HasError'] != '')
			{
				$returnData['isError'] = true;
				$returnData['message'] = "Error in GetBookableResourcesResult. Error:".$result['GetBookableResourcesResult']['Message'];
				return $returnData;
			}
			$availableResources = array();
			if(isset($result['GetBookableResourcesResult']['BookableResource']))
			{
				foreach($result['GetBookableResourcesResult']['BookableResource'] as $bookableResource)
				{
					if(in_array($bookableResource['Group'],$resourceGroup))
					{
						$requestData['DepartureDate'] = $bookingDetails['departureDate'];
						$requestData['DepartureStartTime'] = $departTime;
						$requestData['DepartureStopTime'] = $departTime;
						$requestData['DepartureRoute'] = $bookingDetails['routeCode'];
						$requestData['DepartureResource'] = $bookableResource['ResourceCode'];
						$requestData['ReturnDate'] = $bookingDetails['departureDate'];
						$requestData['ReturnStartTime'] = $departTime;
						$requestData['ReturnStopTime'] = $departTime;
						$requestData['ReturnRoute'] = $bookingDetails['returnRouteCode'];
						$requestData['ReturnResource'] = $bookableResource['ResourceCode'];
						
						$price = $this->getBestAvailablePrice($requestData);
						//echo "<pre>";
						//print_r($bookingDetails);
						//print_r($requestData);
						//print_r($price);
						
						$bookableResource['departureDate'] = $bookingDetails['departureDate'];
						
						if(isset($price['isError']) && $price['isError'])
						{
							$bookableResource['capacity'] = 0;
							$bookableResource['price'] = array();
							$bookableResource['bookable'] = 0;
							
							$availableResources[] = $bookableResource;
							continue;
						}
						$outwardDepart = array();
									
						if(isset($price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'][1]))
						{
							$outwardDepart = $price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'];
						}
						else
						{
							$outwardDepart[0] = $price['GetAvailableBestPricesResult']['OutwardDepartures']['Departure'];
						}
						
						foreach($outwardDepart as $departData)
						{
								
							if(isset($departData['TicketGroups']['TicketGroup'][1]))
							{
								foreach($departData['TicketGroups']['TicketGroup'] as $priceGroup)
								{
									if($priceGroup['Description'] == "Everyday" && $bookingDetails['TicketType'] == "HRD")
									{
										if($priceGroup['Resources']['Resource']['Capacity'] > 0)
										{
											$bookableResource['capacity'] = $priceGroup['Resources']['Resource']['Capacity'];
											$bookableResource['price'] = $priceGroup['Resources']['Resource']['AllPrices'];
											$bookableResource['bookable'] = 1;
										}
										else
										{
											$bookableResource['capacity'] = 0;
											$bookableResource['price'] = array();
											$bookableResource['bookable'] = 0;
										}
									}
									if($priceGroup['Description'] == "Everyday Extended" && $bookingDetails['TicketType'] == "HRE")
									{
										if($priceGroup['Resources']['Resource']['Capacity'] > 0)
										{
											$bookableResource['capacity'] = $priceGroup['Resources']['Resource']['Capacity'];
											$bookableResource['price'] = $priceGroup['Resources']['Resource']['AllPrices'];
											$bookableResource['bookable'] = 1;
										}
										else
										{
											$bookableResource['capacity'] = 0;
											$bookableResource['price'] = array();
											$bookableResource['bookable'] = 0;
										}
									}
									if($priceGroup['Description'] == "Everyday OneWay" && $bookingDetails['TicketType'] == "OW")
									{
										
										if($priceGroup['Resources']['Resource']['Capacity'] > 0)
										{
											$bookableResource['capacity'] = $priceGroup['Resources']['Resource']['Capacity'];
											$bookableResource['price'] = $priceGroup['Resources']['Resource']['AllPrices'];
											$bookableResource['bookable'] = 1;
										}
										else
										{
											$bookableResource['capacity'] = 0;
											$bookableResource['price'] = array();
											$bookableResource['bookable'] = 0;
										}
									}
									
								}
							}
							else{
								if($departData['TicketGroups']['TicketGroup']['Resources']['Resource']['Capacity'] > 0)
								{
									$bookableResource['capacity'] = $departData['TicketGroups']['TicketGroup']['Resources']['Resource']['Capacity'];
									$bookableResource['price'] = $departData['TicketGroups']['TicketGroup']['Resources']['Resource']['AllPrices'];
									$bookableResource['bookable'] = 1;
								}
								else
								{
									$bookableResource['capacity'] = 0;
									$bookableResource['price'] = array();
									$bookableResource['bookable'] = 0;
								}
							}
						}
						$availableResources[] = $bookableResource;
					}
				}
		    }
			
			//echo "<pre>";
			//print_r($availableResources);
			//echo "</pre>";
			//exit;
			
			$courtesy_coach = 0;
			
			if(!empty($availableResources))
			{
				foreach($availableResources as $availableResource)
				{
					if($availableResource['capacity'] > 0 && $availableResource['ResourceCode'] == "HIG" && $availableResource['bookable'] == 1)
					{
						$courtesy_coach = 1; // to display courtesy coach section
					}
					else if(($bookingDetails['TicketType'] == "OW" OR $bookingDetails['TicketType'] == "HRE") && $availableResource['capacity'] > 0 && $availableResource['ResourceCode'] == "LUG" && $availableResource['bookable'] == 1 && isset($availableResource['price']['ResourcePrice']))
					{
						$returnData['luggage'] = $availableResource;
					}
					else if($availableResource['capacity'] > 0 && $availableResource['bookable'] == 1)
						$returnData['freight'][] = $availableResource;
					else
						continue;
				}
			}
			
			$returnData['courtesy_coach'] = $courtesy_coach ;
			
			//print_r($returnData);
			//exit;
			//Accommodations
			
			$accommodationsRequestData =array();
			
			$accommodationsRequestData['StartDate'] = $bookingDetails['departureDate'];
			if(isset($bookingDetails['returnDate']) && $bookingDetails['returnDate'] != '')
				$accommodationsRequestData['EndDate'] = $bookingDetails['returnDate'];
			else
				$accommodationsRequestData['EndDate'] = $bookingDetails['departureDate'];
			
			//$accommodationsRequestData['Supplier'] = "RFFB";
			/* echo "<pre>";
			print_r($accommodationsRequestData);
			echo "</pre>"; */
			
			$accommodationsResponseData = $this->getAccommodations($accommodationsRequestData);
			if(isset($accommodationsResponseData['isError']))
				$returnData['extras'] = 0;
			else
				$returnData['extras'] = $accommodationsResponseData;
			
			return $returnData;

		} catch(Exception $e) {
			log_message('error', "Error in GetBookableResourcesResult:".$e->getMessage());
			$returnData['isError'] = true;
			$returnData['message'] = "Error in GetBookableResourcesResult. Error:".$e->getMessage();
			return $returnData;
        }  
	}
	
/* get Available Accommodations
*/

    public function getAccommodations($requestData)
	{
		
		$accommodationService = $this->soapservices_model->getAccommodationsService();

		try {
			$methodName = 'GetAvailableAccommodations';

			$params = array(
				"StartDate" =>  $requestData['StartDate'],
				"EndDate" => $requestData['EndDate'],
				"Supplier" => "*",
				"Resource" => "*",
				"Area" => "*",
				"Groups" => "*",
				"Categories" => "*",
				"Language" => "E",
				"PriceList" => "*",
				"AllotmentId" => "*",
				"AdjustDate" => false,
				"Page" => "*",
				"Currency" => "AUD",
				"ApplyFilter" => false,
				"SearchType" => ""
			);
       
			$accommodationData = $accommodationService->__soapCall($methodName, array($params));
			
			$result = json_decode(json_encode($accommodationData), true);
			
			/* echo "--------------------------------<pre>";
			print_r($params);
			print_r($result);
			echo "+++++++++++++++++++++++++++++++</pre>"; */
			
			$accommodationResponse = array();
			
			if($result['GetAvailableAccommodationsResult']['HasError'] != '')
			{
				log_message('error', "Error in accommodation: ErrorCode ".$result['GetAvailableAccommodationsResult']['ErrorCode'].": Error Message ".$result['GetAvailableAccommodationsResult']['Message']);
				$accommodationResponse['isError'] = true;
				$accommodationResponse['message'] = "Error in accommodation : ".$result['GetAvailableAccommodationsResult']['Message'];
				return $accommodationResponse;
			}
			else
			{
				$accomodatinSupplier = $result['GetAvailableAccommodationsResult']['DatePeriods']['DatePeriod']['Suppliers']['AccomodatinSupplier'];
				if(isset($accomodatinSupplier[1]))
					$accomodatinSupplierData = $accomodatinSupplier;
				else
					$accomodatinSupplierData[0] = $accomodatinSupplier;
				
				
				foreach($accomodatinSupplierData as $aSD)
				{
					$accommodationResponse[$aSD['SupplierCode']] = array();
					foreach($aSD['Resources']['AccomodationResource'] as $accomodationResource)
					{
						//if($accomodationResource['Capacity'] > 0)
						//{
							$accommodationResponse[$aSD['SupplierCode']][] = $accomodationResource;
						//}
					}
				}
				return $accommodationResponse;
			}		

		} catch(Exception $e) {
				$accommodationResponse = array();
				log_message('error', "Error in accommodation: ErrorCode ".$e->getMessage());
				$accommodationResponse['isError'] = true;
				$accommodationResponse['message'] = "Error in accommodation : ".$e->getMessage();
				return $accommodationResponse;
        }  
	}

	public function GetHolidayPackagePrice($sessionData, $packageData)
	{
		
		$priceService = $this->soapservices_model->getPriceService();

		try {
			$methodName = 'GetHolidayPackagePrice';

			$packageItem = array();
			foreach ($packageData as $packageArray) 
			{
				if(array_key_exists("TicketTypes",$packageArray))
				{
					foreach($packageArray['DepartureTimes']['DepartureTime'] as $packageTime)
					{
						$packageItem[] = array(
							"SupplierCode" => $packageArray['SupplierCode'],
							"ResourceCode" => $packageArray['ResourceCode'],
							"TicketType" => $packageArray['TicketTypes']['TicketType']['TicketType'],
							"StartDate" => $sessionData['departureDate'],
							"EndDate" => $sessionData['returnDate'],
							"Time" => $packageTime,
							"Amount" => $sessionData['passengers']['totalpassengers']
						);	
					}
				}
			}

			$params = array(
				"PackageData" => array(
					"PackageCode" => $sessionData['PackageDefinition']['PackageCode'],
					"PackageAmount" => $sessionData['passengers']['totalpassengers'],
					"TravelDate" =>  $sessionData['departureDate'],
					"PackageItems" => array(
						"PackageItem" => $packageItem
					)
				),
				"ProductCode" =>  $sessionData['PackageDefinition']['ProductCodes']['ProductCode'],
				"AgentNumber" => "",
				"Currency" => "AUD"
			);

			return $priceService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
	}
	
/*
 * ------------------------------------------------------
 *  To add/update freight, luggage and extras in booking
 * ------------------------------------------------------
*/	
    public function addUpdateResources($requestData)
    {
		    $apiResponse = array();
			
			// preparing parameters for UpdateBookingRows
			$rowRemarkParams = array();
			$vehicleDialogParams = array();
			$cabinDialogParams = array();
			$passengerDialogParams = array();
			$cargoDialogParams = array();
			$luggageDialogParams = array();
			$busDialogParams = array();
			$onboardResourceDialogParams = array();
			
			if($requestData['resources'] == "LUG")
			{
				   $luggage = array();
					if($requestData['amount'] > 0)
					{
						for($l =0; $l<$requestData['amount']; $l++)
						{
							$luggage['LuggageType'] = $requestData['resource_type'];
							$luggageDialogParams[] = $luggage;
						}
					}
			}
			
			if(isset($requestData['supplierCode']))
				$supplierCode = $requestData['supplierCode'];
			else
				$supplierCode = $requestData['routeCode'];
			
			$basicBookingRowParams = array(
												"ResourceCode" => $requestData['resources'],
												"SupplierCode" => $supplierCode,
												"StartDate" => $requestData['departureDate']."T00:00:00.000Z",
												"EndDate" => $requestData['departureDate']."T00:00:00.000Z",
												"StartTime" => $requestData['startTime'],
												"Amount" => $requestData['amount'],
												"PriceList" => "",
												"AllotmentId" => "",
												"TicketType" => $requestData['TicketType'],
												"RequestStatus" => "",
												"WaitList" => false,
												"OverBook" => false,
												"RowRemarkParams" => $rowRemarkParams,
												"VehicleDialogParams" => $vehicleDialogParams,
												"CabinDialogParams" => $cabinDialogParams,
												"PassengerDialogParams" => $passengerDialogParams,
												"CargoDialogParams" => $cargoDialogParams,
												"LuggageDialogParams" => $luggageDialogParams,
												"BusDialogParams" => $busDialogParams,
												"OnboardResourceDialogParams" => $onboardResourceDialogParams
											);
			
			if($requestData['resources'] == "HIG")
			{
				$rowRemark = array();
				$currentBooking = $this->getCurrentBooking();
				$bookingRows = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
				$rowRemarkIdExist = 0;
				foreach($bookingRows as $bookingRow)
				{
					if($bookingRow['ResourceCode'] == "HIG" && $bookingRow['SupplierCode'] == $requestData['routeCode'])
					{
						if(isset($bookingRow['RowRemarks']))
						{
							$rowRemarkIdExist = 1;
							$rowRemark['RowRemarkId'] = $bookingRow['RowRemarks']['RowRemark']['RowRemarkId'];
							
							$basicBookingRowParams['RowId'] = $bookingRow['RowId'];
							
							if($requestData['bus_location'] == "NA")
								$basicBookingRowParams['Delete'] = true;
						}
						
					}
				}
				
				if($requestData['bus_location'] == "NA" && !$rowRemarkIdExist)
				{
					$apiResponse['isError'] = false;
					$apiResponse['message'] = "";
					$apiResponse['result'] = $this->getCurrentBooking();
					return $apiResponse;
				}
				else
				{
					$rowRemark['Message'] = $requestData['bus_location'];
					$rowRemark['Type'] = 1;
					$rowRemarkParams[] = $rowRemark;
					
					$basicBookingRowParams['RowRemarkParams'] = $rowRemarkParams;
				}
					
			}
			
			if(isset($requestData['supplierCode']))
			{
				$currentBooking = $this->getCurrentBooking();
				$bookingRows = $currentBooking['GetBookingResult']['Booking']['BookingRows']['BookingRow'];

				foreach($bookingRows as $bookingRow)
				{
					if($bookingRow['SupplierCode'] == $requestData['supplierCode'] && $bookingRow['ResourceCode'] == $requestData['resources'])
					{
						$basicBookingRowParams['RowId'] = $bookingRow['RowId'];
						
						if($requestData['amount'] == 0)
							$basicBookingRowParams['Delete'] = true;
					}
				}
			}

		$bookingService = $this->soapservices_model->getBookingService();
	
	
    	try {
	    	$methodName = 'UpdateBookingRows';

	    	$params = array(
			                "bookingRowsParam" => 
					        array(
							     "BookingRowParams" =>
					             array(
								      "BookingRowParam" => $basicBookingRowParams
									)
								)
	    	                );
//echo "<pre>";
//print_r($params);

	    	$response = $bookingService->__soapCall($methodName, array($params));
			
			$responseArray = json_decode(json_encode($response), true);
			
//print_r($responseArray);
//echo "</pre>";


			if($responseArray['UpdateBookingRowsResult']['HasError'] == '')
			{
				$apiResponse['isError'] = false;
				$apiResponse['message'] = "";
				$apiResponse['result'] = $this->getCurrentBooking();
				return $apiResponse;
			}
			else
			{
				log_message('error', "Error in addLuggage: ErrorCode ".$responseArray['UpdateBookingRowsResult']['ErrorCode'].": Error Message ".$responseArray['UpdateBookingRowsResult']['Message']);
                $apiResponse['isError'] = true;
				$apiResponse['message'] = "Error in add booking row1 : ".$responseArray['UpdateBookingRowsResult']['Message'];
                return $apiResponse;
			}
    	} catch(Exception $e) {
            log_message('error', "Error in updateBookingRows:".$e->getMessage());
            $apiResponse['isError'] = true;
			$apiResponse['message'] = "Error in add booking row2 : ".$e->getMessage();
			return $errorResponse;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  To update Name List for booking
 * ------------------------------------------------------
*/	
	public function updateNameList($requestData)
    {

    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			
	    	$methodName = 'UpdateBooking';

				
				$params = array(
					"bookingParam" => array( 
					    "NameListItemParams" => array(
							"NameListItemParam" => array(
								"NameListId" => $requestData['nameListId'],
								"Firstname" => $requestData['firstName'],
								"Lastname" => $requestData['lastName'],
								"Gender" => $requestData['gender'],
								"CountryCode" => $requestData['countryCode'],
								"Email" => $requestData['email'],
								"PassengerType" => $requestData['passengerType']
							)
						)
					)
				);

				
                $bookingService = $this->soapservices_model->getBookingService();
				$getUpdaredNameList  = $bookingService->__soapCall($methodName, array($params));
				$result = json_decode(json_encode($getUpdaredNameList), true);
				
			$currentBooking =array();
			
			if($result['UpdateBookingResult']['HasError'] == '')
				{
					$currentBooking['isError'] = false;
					$currentBooking['message'] = "";
					$currentBooking['result'] = $this->getCurrentBooking();
					return $currentBooking;
				}
				else
				{
					log_message('error', "Error in updating nameList: ErrorCode ".$result['UpdateBookingResult']['ErrorCode'].": Error Message ".$result['UpdateBookingResult']['Message']);
					$currentBooking['isError'] = true;
					$currentBooking['message'] = "Error in updating nameList : ".$result['UpdateBookingResult']['Message'];
					return $currentBooking;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in updating nameList:".$e->getMessage());
            $currentBooking['isError'] = true;
			$currentBooking['message'] = "Error in updating nameList : ".$e->getMessage();
			return $currentBooking;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  To validate the booking befor confirmation
 * ------------------------------------------------------
*/	
	public function editFinishBooking()
    {

    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			
	    	$methodName = 'EditFinished';

				
				$params = array(
					"BookingComplete" => true
				);
				
                $bookingService = $this->soapservices_model->getBookingService();
				$editFinishBooking  = $bookingService->__soapCall($methodName, array($params));
				$result = json_decode(json_encode($editFinishBooking), true);
				
			$currentBooking =array();
			
			if($result['EditFinishedResult']['HasError'] == '')
				{
					$currentBooking['isError'] = false;
					$currentBooking['message'] = "";
					$currentBooking['result'] = $this->getCurrentBooking();
					return $currentBooking;
				}
				else
				{
					log_message('error', "Error in EditFinished: ErrorCode ".$result['EditFinishedResult']['ErrorCode'].": Error Message ".$result['EditFinishedResult']['Message']);
					$currentBooking['isError'] = true;
					$currentBooking['message'] = "Error in EditFinished : ".$result['EditFinishedResult']['Message'];
					return $currentBooking;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in EditFinished:".$e->getMessage());
            $currentBooking['isError'] = true;
			$currentBooking['message'] = "Error in EditFinished: ".$e->getMessage();
			return $currentBooking;
        } 
	}
	
	/*
 * ------------------------------------------------------
 *  to confirm the booking. Booking will save and real booking no will return
 * ------------------------------------------------------
*/	
	public function confirmBooking()
    {

    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
			
	    	$methodName = 'ConfirmBooking';

				
				$params = array(
					"markAsIncomplete" => true
				);

				
                $bookingService = $this->soapservices_model->getBookingService();
				$confirmBooking  = $bookingService->__soapCall($methodName, array($params));
				$result = json_decode(json_encode($confirmBooking), true);
			
			$currentBooking =array();
			
			if($result['ConfirmBookingResult']['HasError'] == '')
				{
					$currentBooking['isError'] = false;
					$currentBooking['message'] = "";
					$currentBooking['result'] = $this->getCurrentBooking();
					return $currentBooking;
				}
				else
				{
					log_message('error', "Error in ConfirmBooking: ErrorCode ".$result['ConfirmBookingResult']['ErrorCode'].": Error Message ".$result['ConfirmBookingResult']['Message']);
					$currentBooking['isError'] = true;
					$currentBooking['message'] = "Error in ConfirmBooking : ".$result['ConfirmBookingResult']['Message'];
					return $currentBooking;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in ConfirmBooking:".$e->getMessage());
            $currentBooking['isError'] = true;
			$currentBooking['message'] = "Error in ConfirmBooking: ".$e->getMessage();
			return $currentBooking;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  to check the coupon.
 * ------------------------------------------------------
*/	
	public function checkCouponCode($couponCode)
    {
    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
	    	$methodName = 'GetCoupons';
	
				$params = array(
					"couponQuery" => array(
						"CouponNumber" => $couponCode
					)
				);
    			
            $campaignService = $this->soapservices_model->getCampaignService();
		    $copuonDetails  = $campaignService->__soapCall($methodName, array($params));
			$result = json_decode(json_encode($copuonDetails), true);
		
			$couponResponse =array();
			
			if($result['GetCouponsResult']['HasError'] == '')
				{
					$couponResponse['isError'] = false;
					$couponResponse['message'] = "";
					return $couponResponse;
				}
				else
				{
					log_message('error', "Error in GetCouponsResult: ErrorCode ".$result['GetCouponsResult']['ErrorCode'].": Error Message ".$result['GetCouponsResult']['Message']);
					$couponResponse['isError'] = true;
					$couponResponse['message'] = "Error in GetCouponsResult: ".$result['GetCouponsResult']['Message'];
					return $couponResponse;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in GetCouponsResult:".$e->getMessage());
            $couponResponse['isError'] = true;
			$couponResponse['message'] = "Error in GetCouponsResult : ".$e->getMessage();
			return $couponResponse;
        } 
	}
	
	
/*
 * ------------------------------------------------------
 *  to check the coupon.
 * ------------------------------------------------------
*/	
	public function addCouponCode($couponCode)
    {
    	try {
			
			$bookingDetails = $this->session->userdata('booking_details');
			
	    	$methodName = 'UpdateBooking';
	
				$params = array(
					"bookingParam" => array(
						"CouponNumberParams" => array(
							"CouponNumberParam" => array(
								"CouponNumber" =>$couponCode
							)
						)
					)
				);
			
            $bookingService = $this->soapservices_model->getBookingService();
		    $copuonDetails  = $bookingService->__soapCall($methodName, array($params));
			$result = json_decode(json_encode($copuonDetails), true);
	
			$couponResponse =array();
			
			if($result['UpdateBookingResult']['HasError'] == '')
				{
					$couponResponse['isError'] = false;
					$couponResponse['message'] = "";
					$couponResponse['result'] = $this->getCurrentBooking();
					return $couponResponse;
				}
				else
				{
					log_message('error', "Error in addCouponCode: ErrorCode ".$result['UpdateBookingResult']['ErrorCode'].": Error Message ".$result['UpdateBookingResult']['Message']);
					$couponResponse['isError'] = true;
					$couponResponse['message'] = "Error in addCouponCode: ".$result['UpdateBookingResult']['Message'];
					return $couponResponse;
				}
    	} catch(Exception $e) {
            log_message('error', "Error in addCouponCode:".$e->getMessage());
            $couponResponse['isError'] = true;
			$couponResponse['message'] = "Error in addCouponCode : ".$e->getMessage();
			return $couponResponse;
        } 
	}
	
/*
 * ------------------------------------------------------
 *  Redirect to payment gateway.
 * ------------------------------------------------------
*/	
	function pxpay_request()
	{
		$currentBooking = $this->getCurrentBooking();
		
		/* echo "<pre>";
		print_r($currentBooking);
		exit; */
		
		$paymentStatus = trim($currentBooking['GetBookingResult']['Booking']['PaymentStatus']);
		$amountToPay = trim($currentBooking['GetBookingResult']['Booking']['AmountToPay']);
		$bookingNumber = trim($currentBooking['GetBookingResult']['Booking']['BookingNumber']);
		
		if($paymentStatus == "Unpaid")
		{
			$PxPay_Url    =  $this->config->item('pxpayUrl');
			$PxPay_Userid =  $this->config->item('pxuser');
			$PxPay_Key    =  $this->config->item('pxpass');
			$baseUrl    	=  $this->config->item('base_url');


			$pxpay = new PxPay_Curl( $PxPay_Url, $PxPay_Userid, $PxPay_Key );

			$request = new PxPayRequest();


			$paymentSusscessUrl = $baseUrl."/processbooking/paymentSussess";
			$paymentFailedUrl = $baseUrl."/processbooking/paymentFailed";


			$MerchantReference = $bookingNumber;  
			

			#Calculate AmountInput
			$AmountInput = trim($amountToPay + round(($amountToPay* .015), 2));

			#Generate a unique identifier for the transaction
			
			$TxnId = uniqid("RFF");

			#Set PxPay properties
			$request->setMerchantReference($MerchantReference);
			$request->setAmountInput($AmountInput);
			$request->setTxnType("Purchase");
			$request->setCurrencyInput("AUD");
			$request->setUrlFail($paymentFailedUrl);			# can be a dedicated failure page
			$request->setUrlSuccess($paymentSusscessUrl);			# can be a dedicated success page
			$request->setTxnId($TxnId);  


			#Call makeRequest function to obtain input XML
			$request_string = $pxpay->makeRequest($request);

			#Obtain output XML
			$response = new MifMessage($request_string);

			#Parse output XML
			$url = $response->get_element_text("URI");
			$valid = $response->get_attribute("valid");

			return $url;
		}
	}
/*
 * ------------------------------------------------------
 *  Process payment gateway response.
 * ------------------------------------------------------
*/	
	function pxpay_response()
	{
		
		$currentBooking = $this->getCurrentBooking();
		
		echo "<pre>";
		print_r($currentBooking);
		exit;
		
		$paymentStatus = trim($currentBooking['GetBookingResult']['Booking']['PaymentStatus']);
		$amountToPay = trim($currentBooking['GetBookingResult']['Booking']['AmountToPay']);
		$bookingNumber = trim($currentBooking['GetBookingResult']['Booking']['BookingNumber']);
		
		$PxPay_Url    = $this->apiUrl = $this->config->item('pxpayUrl');
		$PxPay_Userid = $this->apiUrl = $this->config->item('pxuser');
		$PxPay_Key    = $this->apiUrl = $this->config->item('pxpass');
		$baseUrl    = $this->apiUrl = $this->config->item('base_url');
		
		$pxpay = new PxPay_Curl( $PxPay_Url, $PxPay_Userid, $PxPay_Key );

		$enc_hex = $_REQUEST["result"];
		#getResponse method in PxPay object returns PxPayResponse object
		#which encapsulates all the response data
		$rsp = $pxpay->getResponse($enc_hex);

		# the following are the fields available in the PxPayResponse object
		$Success           = $rsp->getSuccess();   # =1 when request succeeds
		$AmountSettlement  = $rsp->getAmountSettlement();
		$AuthCode          = $rsp->getAuthCode();  # from bank
		$CardName          = $rsp->getCardName();  # e.g. "Visa"
		$CardNumber        = $rsp->getCardNumber(); # Truncated card number
		$DateExpiry        = $rsp->getDateExpiry(); # in mmyy format
		$DpsBillingId      = $rsp->getDpsBillingId();
		$BillingId    	   = $rsp->getBillingId();
		$CardHolderName    = $rsp->getCardHolderName();
		$DpsTxnRef	       = $rsp->getDpsTxnRef();
		$TxnType           = $rsp->getTxnType();
		$TxnData1          = $rsp->getTxnData1();
		$TxnData2          = $rsp->getTxnData2();
		$TxnData3          = $rsp->getTxnData3();
		$CurrencySettlement= $rsp->getCurrencySettlement();
		$ClientInfo        = $rsp->getClientInfo(); # The IP address of the user who submitted the transaction
		$TxnId             = $rsp->getTxnId();
		$CurrencyInput     = $rsp->getCurrencyInput();
		$EmailAddress      = $rsp->getEmailAddress();
		$MerchantReference = $rsp->getMerchantReference();
		$ResponseText	   = $rsp->getResponseText();
		$TxnMac            = $rsp->getTxnMac(); # An indication as to the uniqueness of a card used in relation to others

		if ($rsp->getSuccess() == "1")
		{
			if($paymentStatus == "Unpaid")
			{
				try {
					$methodName = 'ProcessPayments';
			
						$params = array(
							"payments" => array(
								"ProcessType" =>"Direct",
								"PaymentParameters" => array(
									"PaymentParameter" => array(
										"BookingNumber" =>$MerchantReference,
										"ProcessType" =>"Direct",
										"PaymentTerm" =>"CC",
										"Amount" =>$AmountSettlement,
										"Currency" =>"AUD",
										"Info" =>"APPROVED",
										"TransactionNumber" =>$AuthCode,
										"ProviderSpecificData" =>$DpsTxnRef
									)
								)
							)
						);
				
					$paymentService = $this->soapservices_model->getPaymentService();
					$paymentDetails  = $paymentService->__soapCall($methodName, array($params));
					$result = json_decode(json_encode($paymentDetails), true);
				
					$paymentResponse =array();
					
					if($result['ProcessPaymentsResult']['HasError'] == '')
						{
							$paymentResponse['isError'] = false;
							$paymentResponse['message'] = "";
							$paymentResponse['result'] = $this->getCurrentBooking();
							return $paymentResponse;
						}
						else
						{
							log_message('error', "Error in ProcessPayments: ErrorCode ".$result['ProcessPaymentsResult']['ErrorCode'].": Error Message ".$result['ProcessPaymentsResult']['Message']);
							$paymentResponse['isError'] = true;
							$paymentResponse['message'] = "Error in ProcessPayments: ".$result['ProcessPaymentsResult']['Message'];
							return $paymentResponse;
					}
				}catch(Exception $e) {
					log_message('error', "Error in ProcessPayments:".$e->getMessage());
					$paymentResponse =array();
					$paymentResponse['isError'] = true;
					$paymentResponse['message'] = "Error in ProcessPayments : ".$e->getMessage();
					return $paymentResponse;
				}
			}
		}
		else
		{
		$result = "The transaction was declined.";
		}

	}

}