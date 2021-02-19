<?php
/**
 * REST Service Model
 * @package	RFF
 * @author	Serole Team
 * includes all BOOKIT SOAP API
*/
class Soapservices_model extends CI_Model 
{
	protected $apiUrl;
    protected $apiUserName;
    protected $apiPassword;
    protected $apiNameSpace;
    protected $service;
 
/*
 * ------------------------------------------------------
 *  SOAP Header creation for session cookie
 * ------------------------------------------------------
*/ 
    public function __construct() {

        //Api details from config
        $this->apiUrl = $this->config->item('api_url');
        $this->apiUserName = $this->config->item('api_user_name');
        $this->apiPassword = $this->config->item('api_password');
        $this->apiNameSpace = $this->config->item('api_namespace');
		
        $userwsdl = $this->apiUrl.'/userservice.asmx?wsdl'; // api service url
        
         $loginParams = array(
            "UserName"=> $this->apiUserName,
            "Password"=> $this->apiPassword
        );
        
        $apiHeader = $this->session->userdata('api_header');
		
		// Soap header for credentials
        if(!isset($apiHeader) || is_null($apiHeader))
        {
            $headers = new SoapHeader($this->apiNameSpace, 'Credentials', $loginParams);
            $this->session->set_userdata('api_header', $headers);
        }
        
        try{
			
			// Api call for the session cookie used in all subsequent calls
			$usersoapClient = new SoapClient($userwsdl);
			
			$apiCookie = $this->session->userdata('api_cookie');
			
			if(!isset($apiCookie) || is_null($apiCookie))
			{
				//$usersoapClient->__setSoapHeaders($this->session->userdata('api_header'));
				$authentication = $usersoapClient->__soapCall("LoginUser", array($loginParams));
				$loginCookie = $usersoapClient->_cookies;
				$this->session->set_userdata('api_cookie', $loginCookie['ASP.NET_SessionId'][0]);
			}
			else
			{
				//$usersoapClient->__setSoapHeaders($_SESSION['api_header']);
				$usersoapClient->__setCookie("ASP.NET_SessionId",$_SESSION['api_cookie']);
				$params = array();
				$isUserLogin = $usersoapClient->__soapCall("IsUserLoggedIn", array($params));
				if($isUserLogin->IsUserLoggedInResult->IsLoggedIn != 1)
				{
					$authentication = $usersoapClient->__soapCall("LoginUser", array($loginParams));
					$loginCookie = $usersoapClient->_cookies;
					$this->session->set_userdata('api_cookie', $loginCookie['ASP.NET_SessionId'][0]);
				}
			}
		}catch(Exception $e) {
            log_message('error', "Soap Service constructor:".$e->getMessage());
        }  
		
		parent::__construct(); 
    }
	
	
/*
 * ------------------------------------------------------
 *  Header creation for session cookie for all API calls
 * ------------------------------------------------------
*/ 
	private function headerSession($serviceName)
	{
		// Create Soap Object for Customer Account Service
		$this->service = new SoapClient($serviceName);
		// Get Api Header from Session
		//$apiHeader = $this->session->userdata('api_header');
		// Set Header in Customer Service
		//$this->service->__setSoapHeaders($apiHeader);
		// Get Api ASP.Net Cookie from Session
		$apiCookie = $this->session->userdata('api_cookie');
		// Set Cookie in Customer Service
		$this->service->__setCookie("ASP.NET_SessionId",$apiCookie);

		return $this->service;
	}
	
/*
 * ------------------------------------------------------
 *  Reset Api cookie
 * ------------------------------------------------------
*/ 

	public function resetCookie()
	{
		$userwsdl = $this->apiUrl.'/userservice.asmx?wsdl'; // api service url
        
         $loginParams = array(
            "UserName"=> $this->apiUserName,
            "Password"=> $this->apiPassword
        );
		
		if(isset($_SESSION['api_cookie']))
		{
			unset($_SESSION['api_cookie']);
		    $usersoapClient = new SoapClient($userwsdl);
			$authentication = $usersoapClient->__soapCall("LoginUser", array($loginParams));
			$loginCookie = $usersoapClient->_cookies;
			$this->session->set_userdata('api_cookie', $loginCookie['ASP.NET_SessionId'][0]);
		}
}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for retrieving definitions and other data needed later in the actual booking process.
 *  DefinitionsService will return complex objects.
 * ------------------------------------------------------
*/ 	

	public function getDefinitionsService()
	{
		$serviceName = $this->config->item('api_url').'/definitionsservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}

/*
 * ------------------------------------------------------
 *  This web service provides methods for general departure information.
 * ------------------------------------------------------
*/ 	

	public function getDepartureService()
	{
		$serviceName = $this->config->item('api_url').'/departureservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}

/*
 * ------------------------------------------------------
 *  This web service provides methods for general departure and return information with capacity.
 * ------------------------------------------------------
*/ 	

	public function getAvailabilityService()
	{
		$serviceName = $this->config->item('api_url').'/availabilityservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for retrieving information about prices.
 *  PriceService will return complex object.
 * ------------------------------------------------------
*/ 	
	public function getPriceService()
	{
		$serviceName = $this->config->item('api_url').'/priceservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}

/*
 * ------------------------------------------------------
 *  This web service provices methods for agent users.
 * ------------------------------------------------------
*/
	public function getAgentUserService()
	{
		$serviceName = $this->config->item('api_url').'/agentuserservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}

/*
 * ------------------------------------------------------
 *  This web service provides methods for customer accounts.
 * ------------------------------------------------------
*/
	public function getCustomerAccountService()
	{
		$serviceName = $this->config->item('api_url').'/customeraccountservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for accessing the current booking and later it will also contain methods for retrieving stored bookings to modify and cancel.
 *  BookingService will return complex.
 * ------------------------------------------------------
*/
	public function getBookingService()
	{
		$serviceName = $this->config->item('api_url').'/bookingservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for Accommodations services 
 *  BookingService will return complex.
 * ------------------------------------------------------
*/
	public function getAccommodationsService()
	{
		$serviceName = $this->config->item('api_url').'/accommodationservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for coupon services 
 *  .
 * ------------------------------------------------------
*/
	public function getCampaignService()
	{
		$serviceName = $this->config->item('api_url').'/campaignservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for coupon services 
 *  .
 * ------------------------------------------------------
*/
	public function getBookingSequenceService()
	{
		$serviceName = $this->config->item('api_url').'/bookingsequenceservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
/*
 * ------------------------------------------------------
 *  This web service contains methods for payment 
 *  .
 * ------------------------------------------------------
*/
	public function getPaymentService()
	{
		$serviceName = $this->config->item('api_url').'/paymentservice.asmx?wsdl';

		return $this->headerSession($serviceName);
	}
	
	
	
/*
 * ------------------------------------------------------
 *  Check if API return any error
 * ------------------------------------------------------
*/
	public function checkApiError($method, $result)
	{
		$returnkey = $method."Result";
		if($result[$returnkey]['HasError'] != '')
			{
				log_message('error', "Error in departure routes: ErrorCode".$result[$returnkey]['ErrorCode'].": Error Message".$result[$returnkey]['Message']);
			    $errorResponse = array();
                $errorResponse['isError'] = true;
                $errorResponse['errorCode'] = $result[$returnkey]['ErrorCode'];
				$errorResponse['message'] = "Error in getDeparture : ".$result[$returnkey]['Message'];
                return $errorResponse;
			}
			else
				return false;
	}
}