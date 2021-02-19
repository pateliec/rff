<?php
/**
 * Process Booking Controller
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to booking process
*/
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Processbooking extends Bookit 
{

/*
 * ------------------------------------------------------
 *  Load processbooking model
 * ------------------------------------------------------
*/

	public function __construct() {
        parent::__construct();
        $this->load->model('processbooking_model');
    }

/*
 * ------------------------------------------------------
 *  Load main departure selection page
 * ------------------------------------------------------
*/
    public function index()
    {
        $this->load->view('include/head');
        $this->load->view('include/header');

        $this->processbooking_model->bookingdetails();
        $data = $this->session->userdata('booking_details');
        $obj['booking'] = json_decode(json_encode($data), true);

        $this->load->view('bookingdetails', $obj);
        $this->load->view('include/footer');

        return $this;
    }

/*
 * ------------------------------------------------------
 *  Load sidebar
 * ------------------------------------------------------
*/
    public function sidebar()
    {
        $data = array();
		$data['currentBooking'] = $this->processbooking_model->getCurrentBooking();
		$data['sessionData'] = $bookingDetails = $this->session->userdata('booking_details');
        $this->load->view('include/sidebar',$data);
    }

/*
 * ------------------------------------------------------
 *  Load main departure selection page section in case of return
 * ------------------------------------------------------
*/
    public function returnBooking()
    {
		$this->load->view('include/head');
        $this->load->view('include/header');
		
        $data = $this->input->get();
        if($data) {
			if($data['route'] == "HILROT")
				$data['returnRoute'] = "ROTHIL";
			if($data['route'] == "ROTHIL")
				$data['returnRoute'] = "HILROT";
			if($data['dept-date'] == $data['ret-date'])
				$ticketType = "HRD";
			else
				$ticketType = "HRE";
			
            $sessionData = array();
            $sessionData = array(
                "bookingType" => $data['tickettype'],
                "departing" => $data['outward'],
                "arriving" => $data['arrival'],
                "departureDate" => date("Y-m-d", strtotime(str_replace("/","-",$data['dept-date']))),
                "returnDate" => date("Y-m-d", strtotime(str_replace("/","-",$data['ret-date']))),
                "routeCode" => $data['route'],
                "ticketType" => $ticketType,
                "returnRouteCode" => $data['returnRoute'],
                "passengers" => array(
                    "A" => $data['adults'],
                    "C" => $data['child'],
                    "T" => $data['toddler'],
                    "I" => $data['infants'],
                    "ST" => $data['students'],
                    "SN" => $data['seniors'],
                    "FP" => $data['family']
                ),
				"passengerType" => array(
                    "A" => "Adult",
                    "C" => "Child",
                    "T" => "Toddler",
                    "I" => "Infants",
                    "ST" => "Students",
                    "SN" => "Seniors",
                    "FP" => "Family Pass"
                ),
				"totalPassengerCount" => $data['adults'] + $data['child'] + $data['toddler'] + $data['infants'] + $data['students'] + $data['seniors'] + ($data['family']*4)
            );
            $this->session->set_userdata('booking_details', $sessionData); 
			
			
			$bookingProcess = $this->processbooking_model->returnBookingRoutes();
			$currentBooking = $this->processbooking_model->getCurrentBooking();
			if(is_array($bookingProcess))
			{
				$routeDetails['routeDetails'] = $bookingProcess;
				$routeDetails['currentBooking'] = $currentBooking;
				$routeDetails['passengerType'] = $sessionData['passengerType'];
				$this->load->view('return_routedetails', $routeDetails);
			}
			else
			{
				$routeDetails['routeError'] = $bookingProcess;
				$this->load->view('return_routedetails', $routeDetails);
			}
			
			//$this->load->view('return_routedetails');
            $this->load->view('include/footer');
			//exit;
        } else {
            $this->session->set_flashdata('message_error', 'Please fill all details first!');
            return redirect(' ','refresh');
        }
    }

/*
 * ------------------------------------------------------
 *  Load main departure selection page section in case of return through ajax
 * ------------------------------------------------------
*/
	
	 public function returnBookingAjax()
    {   
	    $data = $this->input->get();
		if(isset($data['bookingDate']))
			$bookingDate = $data['bookingDate'];
		else
			$bookingDate = NULL;
		if(isset($data['isReturn']))
			$isReturn = $data['isReturn'];
		else
			$isReturn = false;
		
		$bookingProcess = $this->processbooking_model->returnBookingRoutes($bookingDate, $isReturn);
		if(is_array($bookingProcess))
		{
			$routeDetails['routeDetails'] = $bookingProcess;
			return $this->load->view('return_booking', $routeDetails);
		}
		else
		{
			$routeDetails['routeError'] = $bookingProcess;
			return $this->load->view('return_booking', $routeDetails);
		}
    }
	
/*
 * ------------------------------------------------------
 *  Load main departure selection page section in case of return
 * ------------------------------------------------------
*/
    public function onewayBooking()
    {
		$this->load->view('include/head');
        $this->load->view('include/header');
		
        $data = $this->input->get();
		
        if($data) {
			
            $sessionData = array();
			
            $sessionData = array(
                "bookingType" => $data['tickettype'],
                "departing" => $data['outward'],
                "arriving" => $data['arrival'],
				"departureDate" => date("Y-m-d", strtotime(str_replace("/","-",$data['dept-date']))),
                "returnDate" => "",
                "routeCode" => $data['route'],
                "returnRouteCode" => "",
                "passengers" => array(
                    "A" => $data['adults'],
                    "C" => $data['child'],
                    "T" => $data['toddler'],
                    "I" => $data['infants'],
                    "ST" => $data['students'],
                    "SN" => $data['seniors'],
                    "FP" => $data['family']
                ),
				"totalPassengerCount" => $data['adults'] + $data['child'] + $data['toddler'] + $data['infants'] + $data['students'] + $data['seniors'] + ($data['family']*4)
            );

            $this->session->set_userdata('booking_details', $sessionData); 
			
			$this->load->view('oneway_routedetails');
            $this->load->view('include/footer');
			//exit;
        } else {
            $this->session->set_flashdata('message_error', 'Please fill all details first!');
            return redirect(' ','refresh');
        }
    }

/*
 * ------------------------------------------------------
 *  Load main departure selection page section in case of return through ajax
 * ------------------------------------------------------
*/
	
	 public function onewayBookingAjax()
    {   
	    $data = $this->input->get();
		if(isset($data['bookingDate']))
			$bookingDate = $data['bookingDate'];
		else
			$bookingDate = NULL;
		
		$bookingProcess = $this->processbooking_model->onewayBookingRoutes($bookingDate);
		
		if(is_array($bookingProcess))
		{
			$routeDetails['routeDetails'] = $bookingProcess;
			return $this->load->view('oneway_booking', $routeDetails);
		}
		else
		{
			$routeDetails['routeError'] = $bookingProcess;
			return $this->load->view('oneway_booking', $routeDetails);
		}
    }

/*
 * ------------------------------------------------------
 *  update booking row through ajax
 *  update the time and ticket type of given row id
 * ------------------------------------------------------
*/
	
	public function updateBookingRowAjax()
    { 
	    $data = $this->input->get();
		
		$updateData = explode("-",$data['updateData']);
		
		$requestData = array();
		$requestData['RowId'] = $updateData[0];
		$requestData['DepartureTime'] = $updateData[1];
		$requestData['TicketType'] = $updateData[2];
		
		$bookingProcess = $this->processbooking_model->updateBookingRows($requestData);
		
		//in case of family pass update family pass row
		if(isset($updateData[3]) && isset($updateData[4]) && $updateData[0] != $updateData[3])
		{
			$requestData['RowId'] = $updateData[3];
		    $requestData['DepartureTime'] = $updateData[1];
		    $requestData['TicketType'] = $updateData[4];
			$bookingProcess = $this->processbooking_model->updateBookingRows($requestData);
		}
		echo json_encode($bookingProcess);
    }
	
	/*
 * ------------------------------------------------------
 *  Load package details
 * ------------------------------------------------------
*/
	
	public function packageBooking()
    { 
	   $this->load->view('include/head');
       $this->load->view('include/header');
       $data = $this->input->get();
		
        if($data) {
			$data['family'] = 0;
			
            $sessionData = array();
            $sessionData = array(
                "bookingType" => $data['tickettype'],
				"departureDate" => date("Y-m-d", strtotime(str_replace("/","-",$data['dept-date']))),
                "packageCode" => $data['packagecode'],
				"routeCode" => "HILROT",
                "returnRouteCode" => "ROTHIL",
                "passengers" => array(
                    "A" => $data['adults'],
                    "C" => $data['child'],
                    "T" => $data['toddler'],
                    "I" => $data['infants'],
                    "ST" => $data['students'],
                    "SN" => $data['seniors']
                ),
				"totalPassengerCount" => $data['adults'] + $data['child'] + $data['toddler'] + $data['infants'] + $data['students'] + $data['seniors']
            );

            $this->session->set_userdata('booking_details', $sessionData); 
			
			$this->load->view('package_routedetails');
            $this->load->view('include/footer');
			//exit;
        } else {
            $this->session->set_flashdata('message_error', 'Please fill all details first!');
            return redirect(' ','refresh');
        }
    }
	
	/*
 * ------------------------------------------------------
 *  Load main departure selection page section in case of return through ajax
 * ------------------------------------------------------
*/
	
	public function packageBookingAjax()
    {   
	    $data = $this->input->get();
		
		if(isset($data['departDate']))
			$departDate = $data['departDate'];
		else
			$departDate = NULL;
		
		$bookingProcess = $this->processbooking_model->getHolidayPackageDefinition($departDate);
		
		if(is_array($bookingProcess))
		{
			if(isset($bookingProcess['isError']) && $bookingProcess['isError']!='')
			{
				$routeDetails['routeError'] = $bookingProcess['message'];
			    return $this->load->view('package_booking', $routeDetails);
			}
			
			$routeDetails['routeDetails'] = $bookingProcess;
			return $this->load->view('package_booking', $routeDetails);
		}
		else
		{
			$routeDetails['routeError'] = $bookingProcess;
			return $this->load->view('package_booking', $routeDetails);
		}
    }
	
/*
 * ------------------------------------------------------
 *  update booking row through ajax for package
 *  update the time
 * ------------------------------------------------------
*/
	
	public function updateBookingPackageRowAjax()
    { 
	    $data = $this->input->get();
		
		$updateData = explode("-",$data['updateData']);
		
		$requestData = array();
		$requestData['routeCode'] = $updateData[0];
		$requestData['departureTime'] = $updateData[1];
		
		$bookingProcess = $this->processbooking_model->updatePackageBookingRows($requestData);
		
		echo json_encode($bookingProcess);
    }
	
/*
 * ------------------------------------------------------
 *  Load main extras section through booking
 * ------------------------------------------------------
*/
	// Commented By Himani
	// public function extraAjax()
 //    {   
	    
	// 	$extasProcess = $this->processbooking_model->extrasDetails();
		
	// 	$routeDetails = array();
	// 	$bookingDetails = $this->session->userdata('booking_details');
	// 	if(is_array($extasProcess) && !isset($extasProcess['isError']))
	// 	{
	// 		$routeDetails['extraDetails'] = $extasProcess;
	// 		$routeDetails['bookingDetails'] = $bookingDetails;
	// 		return $this->load->view('extras', $routeDetails);
	// 	}
	// 	else
	// 	{
	// 		$routeDetails['extraError'] = $extasProcess;
	// 		$routeDetails['bookingDetails'] = array();
	// 		return $this->load->view('extras', $routeDetails);
	// 	}
 //    }
	
/*
 * ------------------------------------------------------
 *  Add/Update freight, luggage and courtesy coaches resources to booking
 * ------------------------------------------------------
*/
	
	 public function addUpdateLuggageFreightAjax()
    {   
	    
		$requestData = array();
		
		$data = $this->input->get();

		// get booking_details session
		$bookingDetails = $this->session->userdata('booking_details');
		
		//echo "<pre>";
		//print_r($bookingDetails);
		//exit;
		//current booking

		$currentBooing = $this->processbooking_model->getCurrentBooking();
	
		if(isset($currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'][1]))
			$currentBookingRows = $currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
		else
			$currentBookingRows[] = $currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
		
		foreach($currentBookingRows as $bookingRow)
			{
				if($bookingRow['ResourceCode'] == "PAX" && $bookingDetails['routeCode'] == $bookingRow['SupplierCode'])
				{
					$departTime = $bookingRow['StartTime'];
				}
				
				if($bookingRow['ResourceCode'] == "PAX" && $bookingDetails['returnRouteCode'] == $bookingRow['SupplierCode'])
				{
					$returnDepartTime = $bookingRow['StartTime'];
				}
			}

		if($data['status'] == "delete" || $data['qty'] == 0)
		    $requestData['delete'] = true;
		else
			$requestData['delete'] = false;
		
		if(isset($data['bus_location']))
			$requestData['bus_location'] = $data['bus_location'];
		
		$requestData['resources'] = $data['resources'];
		$requestData['routeCode'] = $bookingDetails['routeCode'];
		$requestData['departureDate'] = $bookingDetails['departureDate'];
		$requestData['startTime'] = $departTime;
		$requestData['amount'] = $data['qty'];
        $requestData['TicketType'] =  $bookingDetails['ticketType'];
        $requestData['resource_type'] =  $data['resource_type'];
		
		$response = $this->processbooking_model->addUpdateResources($requestData);
		
		if(isset($bookingDetails['returnRouteCode']) && $bookingDetails['returnRouteCode'] !='')
		{
			if(isset($data['bus_location']))
			{
				$busLocation = explode("@",$data['bus_location']);
				if(isset($busLocation[1]))
					$requestData['bus_location'] = trim($busLocation[1]);
			    else
					$requestData['bus_location'] = trim($busLocation[0]);
				
			}
			$requestData['routeCode'] = $bookingDetails['returnRouteCode'];
			$requestData['departureDate'] = $bookingDetails['returnDate'];
			$requestData['startTime'] = $returnDepartTime;
			$response = $this->processbooking_model->addUpdateResources($requestData);
		}
		
        echo json_encode($response);
		
    }
	
/*
 * ------------------------------------------------------
 *  Add/Update freight, luggage and courtesy coaches resources to booking
 * ------------------------------------------------------
*/
	
	public function addUpdateExtrasAjax()
    {   
	    
		$requestData = array();
		
		$data = $this->input->get();

		// get booking_details session
		$bookingDetails = $this->session->userdata('booking_details');
		
		//echo "<pre>";
		//print_r($bookingDetails);
		//exit;
		//current booking

		$currentBooing = $this->processbooking_model->getCurrentBooking();
	
		if(isset($currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'][1]))
			$currentBookingRows = $currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
		else
			$currentBookingRows[] = $currentBooing['GetBookingResult']['Booking']['BookingRows']['BookingRow'];
		
		foreach($currentBookingRows as $bookingRow)
			{
				if($bookingRow['ResourceCode'] == "PAX" && ($bookingRow['SupplierCode'] == "HILROT" || $bookingRow['SupplierCode'] == "ROTHIL"))
				{
					$departTime = $bookingRow['StartTime'];
				}
			}

		if($data['status'] == "delete" || $data['qty'] == 0)
		    $requestData['delete'] = true;
		else
			$requestData['delete'] = false;
		
		
		$requestData['resources'] = $data['resources'];
		$requestData['routeCode'] = $bookingDetails['routeCode'];
		$requestData['supplierCode'] = $data['supplierCode'];
		$requestData['departureDate'] = $bookingDetails['departureDate'];
		$requestData['startTime'] = $departTime;
		$requestData['amount'] = $data['qty'];
        $requestData['TicketType'] =  $bookingDetails['ticketType'];
       
		$response = $this->processbooking_model->addUpdateResources($requestData);
		
		
        echo json_encode($response);
		
    }
	
/*
 * ------------------------------------------------------
 *  Add/Update nameList to booking
 * ------------------------------------------------------
*/
	
	public function addUpdateNameListAjax()
    {   
	    
		$requestData = array();
		
		$data = $this->input->get();

		// get booking_details session
		$bookingDetails = $this->session->userdata('booking_details');
		
		echo "<pre>";

		$requestData['nameListId'] = "P1";
		$requestData['firstName'] = "Dhananjay";
		$requestData['lastName'] = "Kumar";
		$requestData['gender'] = "M";
		$requestData['countryCode'] = "AUS";
		$requestData['passengerType'] = "A";
		$requestData['email'] = "patel.iec@gmail.com";
		
		$response = $this->processbooking_model->updateNameList($requestData);
		
        echo json_encode($response);
	}
	
/*
 * ------------------------------------------------------
 *  confirm booking
 * ------------------------------------------------------
*/
	
	public function confirmBooking()
    {   
	
		$response = $this->processbooking_model->editFinishBooking();
		if(!$response['isError'])
		   $response = $this->processbooking_model->confirmBooking();
	   
	   echo "<pre>";
	   print_r($response);
		
        echo json_encode($response);
	}
	
/*
 * ------------------------------------------------------
 *  check coupon code
 * ------------------------------------------------------
*/
	
	public function checkCouponCodeAjax()
    {   
		$data = $this->input->get();
		$response = $this->processbooking_model->checkCouponCode($data['couponCode']);
        echo json_encode($response);
	}
	
/*
 * ------------------------------------------------------
 *  Add coupon code
 * ------------------------------------------------------
*/
	
	public function addCouponCodeAjax()
    {     
		$data = $this->input->get();
		$response = $this->processbooking_model->addCouponCode($data['couponCode']);
        echo json_encode($response);
	}
	
/*
 * ------------------------------------------------------
 * Redirect to payment express
 * ------------------------------------------------------
*/
	
	public function pxPayRedirect()
    {     
		$url = $this->processbooking_model->pxpay_request();
        header("Location: ".$url);
	}

/*
 * ------------------------------------------------------
 * Payment express Success URL
 * ------------------------------------------------------
*/
	
	public function paymentSussess()
    {     
		$response = $this->processbooking_model->pxpay_response();
		$this->confirmBooking();
        //header("Location: ".$url);
	}

/*
 * ------------------------------------------------------
 * Select Extras
 * By Himani
 * ------------------------------------------------------
*/
	public function selectExtras()
	{
		$this->load->view('include/head');
        $this->load->view('include/header');
        $extasProcess = $this->processbooking_model->extrasDetails();
		
		$routeDetails = array();
		$bookingDetails = $this->session->userdata('booking_details');
		if(is_array($extasProcess) && !isset($extasProcess['isError']))
		{
			$routeDetails['extraDetails'] = $extasProcess;
			$routeDetails['bookingDetails'] = $bookingDetails;
			$this->load->view('luggage_extras', $routeDetails);
		}
		else
		{
			$routeDetails['extraError'] = $extasProcess;
			$routeDetails['bookingDetails'] = array();
			$this->load->view('luggage_extras', $routeDetails);
		}
		$this->load->view('include/footer');
		
		return $this;
	}
	
}