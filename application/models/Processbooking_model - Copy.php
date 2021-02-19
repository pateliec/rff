<?php
class Processbooking_model extends CI_Model 
{
	public function __construct() {
		parent::__construct(); 
		$this->load->model('soapservices_model');
	}
	
	public function bookingdetails()
	{
		$departureService = $this->soapservices_model->getDepartureService();

		try {
			$bookingDetails = $this->session->userdata('booking_details');

			if($bookingDetails)
			{
				if($bookingDetails['bookingType'] == 'oneway' || $bookingDetails['bookingType'] == 'return' )
				{
					if($bookingDetails['bookingType'] == 'return')
					{
						$dataDeparture = $this->getDeparture($bookingDetails['routeCode'], $bookingDetails['arriving'], $bookingDetails['returnDate']);

						$obj = json_decode(json_encode($dataDeparture), true);
			        	$departData['Return'] = $obj['GetDepartureResult']['Departures']['Departure'];
					}
					$dataDeparture = $this->getDeparture($bookingDetails['routeCode'], $bookingDetails['departing'], $bookingDetails['departureDate']);

					$obj = json_decode(json_encode($dataDeparture), true);
			        $departData['Arrival'] = $obj['GetDepartureResult']['Departures']['Departure'];

			        $newSession = array();
			        if($departData != '') {
						if(array_key_exists('Return', $departData))
						{
							foreach($departData['Return'] as $newData) {
								if($newData['Bookable'] == 1)
								{
									$bookable = 'true';
								} else {
									$bookable = 'false';
								}
								$newSession['Return'][] = array(
									"DepartureDate" => $newData['DepartureDate'],
									"DepartureTime" => $newData['DepartureTime'],
									"ArrivalDate" => $newData['ArrivalDate'],
									"ArrivalTime" => $newData['ArrivalTime'],
									"ShipCode" => $newData['ShipCode'],
									"Bookable" => $bookable,
								);
							}
						}
							
			            foreach($departData['Arrival'] as $newData) {
			            	if($newData['Bookable'] == 1)
			            	{
			            		$bookable = 'true';
			            	} else {
			            		$bookable = 'false';
			            	}

			                $newSession['Arrival'][] = array(
			                    "DepartureDate" => $newData['DepartureDate'],
			                    "DepartureTime" => $newData['DepartureTime'],
			                    "ArrivalDate" => $newData['ArrivalDate'],
			                    "ArrivalTime" => $newData['ArrivalTime'],
			                    "ShipCode" => $newData['ShipCode'],
			                    "Bookable" => $bookable,
			                );
			            }
			        }

			        $bookingDetails['bookingDetails'] = $newSession;
					$this->session->set_userdata('booking_details', $bookingDetails);

			        // Get Prices
			        $priceFun = $this->getPrice();
					$prices = json_decode(json_encode($priceFun), true);
					
			        $bookingSessionData = $this->session->userdata('booking_details');
					
			        if(array_key_exists('Return', $departData) && $departData['Return'])
			        {	
			        	$intervalPriceReturn = $prices['Return'];
			        	$sessionReturnData = $bookingSessionData['bookingDetails']['Return'];

			        	for($i = 0; $i < count($sessionReturnData); $i++)
			       		{
			       			if($intervalPriceReturn[$i]['GetPricesResult']['HasError'] != '1')
			       			{
			       				$sessionReturnData[$i]['Price'] = $intervalPriceReturn[$i]['GetPricesResult']['Prices']['IntervalPrice'];
			       			}
						}
						
						$bookingSessionData['bookingDetails']['Return'] = $sessionReturnData;
					}

					$intervalPriceArrival = $prices['Arrival'];
					$sessionArrivalData = $bookingSessionData['bookingDetails']['Arrival'];

					for($i = 0; $i < count($sessionArrivalData); $i++)
			       	{
			       		if($intervalPriceArrival[$i]['GetPricesResult']['HasError'] != '1')
			       		{
			       			$sessionArrivalData[$i]['Price'] = $intervalPriceArrival[$i]['GetPricesResult']['Prices']['IntervalPrice'];
			       		}
					}
					$bookingSessionData['bookingDetails']['Arrival'] = $sessionArrivalData;

					echo '<pre>'; print_r($bookingSessionData); die;
					
					$this->session->set_userdata('booking_details', $bookingSessionData);
				}
				if($bookingDetails['bookingType'] == 'cruises')
				{
					$dataDeparture = $this->getCruiseDeparture($bookingDetails['routeCode'], $bookingDetails['departureDate']);
					$obj = json_decode(json_encode($dataDeparture), true);
					echo '<pre>'; print_r($obj); die();
				}
				
				if($bookingDetails['bookingType'] == 'packages')
				{
					$dataDeparture = $this->getBookableResourcePackages($bookingDetails['routeCode']);
					$obj = json_decode(json_encode($dataDeparture), true);

					if($obj['GetBookableResourcePackagesResult']['HasError'] == '')
					{
						$packageDetails = $obj['GetBookableResourcePackagesResult']['PackageDescriptions']['Description'];
						
						$bookingDetails['PackageCode'] = $packageDetails['PackageCode'];
						$bookingDetails['Version'] = $packageDetails['Version'];
						$bookingDetails['Area'] = $packageDetails['Area'];
						$bookingDetails['Category'] = $packageDetails['Category'];

						$this->session->set_userdata('booking_details', $bookingDetails);
						$packageBooking = $this->session->userdata('booking_details');
						
						$packageResource = $this->getHolidayPackageDefinition($packageBooking['PackageCode'], $packageBooking['Version'], $packageBooking['departureDate']);
						$objPackage = json_decode(json_encode($packageResource), true);

						if($objPackage['GetHolidayPackageDefinitionResult']['HasError'] == '')
						{
							$packageBooking['PackageDefinition'] = $objPackage['GetHolidayPackageDefinitionResult']['PackageDefinition'];
							$this->session->set_userdata('booking_details', $packageBooking);
							$sessionBooking = $this->session->userdata('booking_details');
							$objBooking = json_decode(json_encode($sessionBooking), true);

							if($objBooking['PackageDefinition'] != '')
							{
								$priceParams = $objBooking['PackageDefinition']['Resources']['Resource'];
								// Package Price
								$packagePrice = $this->getHolidayPackagePrice($objBooking, $priceParams);
								echo '<pre>';print_r($packagePrice);die();
							}
						}
					}
				}
			}				

			return $this;
			
		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
    }

    public function getDeparture($routeCode, $startPortCode, $departureDate)
    {
		$departureService = $this->soapservices_model->getDepartureService();
		
    	try {
	    	$methodName = 'GetDeparture';

	    	$params = array(
	    		"DepartureQuery" => array(
	    			"RouteCode" => $routeCode,
	    			"StartPortCode" => $startPortCode,
	    			"DepartureDate" => $departureDate,
	    		)
	    	);

	    	return $departureService->__soapCall($methodName, array($params));
    	} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
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

    public function getBookableResource($supplier)
    {
    	
		$definitionService = $this->soapservices_model->getDefinitionsService();

		try {
			$methodName = 'GetBookableResources';

			$params = array(
				"Supplier" => $supplier,
				"View" => ""
			);

			return $definitionService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
	}
	
	public function getBookableResourcePackages($packageCode)
	{
		
		$definitionService = $this->soapservices_model->getDefinitionsService();

		try {
			$methodName = 'GetBookableResourcePackages';

			$params = array(
				"Query" => array(
					"PackageCode" => $packageCode
				)
			);

			return $definitionService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
	}

	public function getHolidayPackageDefinition($packageCode, $packageVersion, $travelDate)
	{
		
		$definitionService = $this->soapservices_model->getDefinitionsService();

		try {
			$methodName = 'GetHolidayPackageDefinition';

			$params = array(
				"Data" => array(
					"PackageCode" => $packageCode,
					"PackageVersion" => $packageVersion,
					"TravelDate" => $travelDate,
				)
			);

			return $definitionService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
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
	
	public function checkForReturn($type) {
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
}