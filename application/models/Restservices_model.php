<?php
/**
 * REST Service Model
 * @package	RFF
 * @author	Serole Team
 * includes all BOOKIT REST API
*/
class Restservices_model extends CI_Model 
{
	protected $apiUrl;
    protected $apiUserName;
    protected $apiPassword;
    protected $apiNameSpace;
    protected $service;
    private $token;

/*
 * ------------------------------------------------------
 *  Access Token session creation
 * ------------------------------------------------------
*/
    public function __construct() {

        //Api details from config
        $this->apiUrl = $this->config->item('api_url');
        $this->apiUserName = $this->config->item('api_user_name');
        $this->apiPassword = $this->config->item('api_password');
        $this->apiNameSpace = $this->config->item('api_namespace');
		
		$accessToken = $this->session->userdata('api_accessToken');
		$refreshToken = $this->session->userdata('api_refreshToken');
		
		if(!isset($accessToken) || is_null($accessToken))
        {
            $tokenData = $this->getToken();
			if(isset($tokenData['returnStatusCode']) && $tokenData['returnStatusCode'] == 200)
			{
			  $this->token = $tokenData['responseData']['accessToken'];
              $this->session->set_userdata('api_accessToken', $tokenData['responseData']['accessToken']);
              $this->session->set_userdata('api_refreshToken', $tokenData['responseData']['refreshToken']);
			}
        }
		else
		{
			$this->token = $this->session->userdata('api_accessToken');
			$validate = $this->validateToken($accessToken);
			
			if(isset($validate['returnStatusCode']) && $validate['returnStatusCode'] == 401)
			{
               $refreshToken = $this->refreshToken($accessToken,$refreshToken);
			   
			   if(isset($refreshToken['returnStatusCode']) && $refreshToken['returnStatusCode'] == 200)
				{
				  $this->token = $validate['responseData']['accessToken'];
				  $this->session->set_userdata('api_accessToken', $validate['responseData']['accessToken']);
				  $this->session->set_userdata('api_refreshToken', $validate['responseData']['refreshToken']);
				}
				else
				{
					$tokenData = $this->getToken();
					if(isset($tokenData['returnStatusCode']) && $tokenData['returnStatusCode'] == 200)
					{
					  $this->token = $tokenData['responseData']['accessToken'];
					  $this->session->set_userdata('api_accessToken', $tokenData['responseData']['accessToken']);
					  $this->session->set_userdata('api_refreshToken', $tokenData['responseData']['refreshToken']);
					}
				}
			}
		}
		
		
		parent::__construct(); 
    }

/*
 * ------------------------------------------------------
 *  Method to call BOOKIT api
 * ------------------------------------------------------
*/	
	private function curlServiceRequest($serviceURL, $requestData=array(), $token=NULL, $type)
	{
		try {

			$ch = curl_init($serviceURL);
			
			if($type == "post")
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
			}
			
			//curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			
		    $headers = array();
			$headers[] = 'Content-Type: application/json';
			
			if($token != NULL)
			{
				$authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
				$headers[] = $authorization;
			}
			
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$response = curl_exec($ch);
			
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			$responseData = json_decode($response, true);
			
			$returnData = array();
			$returnData['returnStatusCode'] = $httpcode;
			$returnData['responseData'] = $responseData;
			return $returnData;
		}
		catch(Exception $e)
		{
			print_r($e);
		}
		
	}

/*
 * ------------------------------------------------------
 *  Crate new Access token for BOOIT
 * ------------------------------------------------------
*/	
	public function getToken()
	{
		
		$userServiceURL = $this->apiUrl.'/api/authorize/user'; // api service url
		
		// set post fields
		$requestData = [
			'userId' => $this->apiUserName,
			'password' => $this->apiPassword,
			'deviceId'   => "web-widget",
			'clientId'   => ""
		];
		
        return $this->curlServiceRequest($userServiceURL, $requestData, $token=NULL, "post");

	}
	
/*
 * ------------------------------------------------------
 *  Refresh Access token for BOOIT
 * ------------------------------------------------------
*/	
	public function refreshToken($accessToken,$refreshToken)
	{
		
		$userServiceURL = $this->apiUrl.'/api/authorize/user/refresh'; // api service url
		
		// set post fields
		$requestData = [
			'AccessToken' => $this->apiUserName,
			'RefreshToken' => $this->apiPassword
		];
		
        return $this->curlServiceRequest($userServiceURL, $requestData, $token=NULL, "post");

	}

/*
 * ------------------------------------------------------
 *  Validate Access token for BOOIT by call one of the method
 * ------------------------------------------------------
*/	
	public function validateToken()
	{
		$token = $this->token;
		$userServiceURL = $this->apiUrl.'/api/route'; // api service url
		$requestData = array();
        return $this->curlServiceRequest($userServiceURL, $requestData, $token, "get");

	}

/*
 * ------------------------------------------------------
 *  This Api returns all the routes
 *  Input: empty array
 *  Output: array of routes
 * ------------------------------------------------------
*/		
	public function getRoutes()
	{
		$token = $this->token;
		$userServiceURL = $this->apiUrl.'/api/route'; // api service url
		$requestData = array();
        return $this->curlServiceRequest($userServiceURL, $requestData, $token, "get");

	}
	
	
}