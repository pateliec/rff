<?php
/**
 * Agent Model
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to agent
*/
class Agent_model extends CI_Model 
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
 *  This method logs in a agent user to the API session.
 *  Input: email and password
 *  Output: Result containing the agent user if the agent user was successfully logged in.
 * ------------------------------------------------------
*/	
	public function loginAgent($email, $password)
	{
		try {
			$agentUserService = $this->soapservices_model->getAgentUserService();

			//$checkAgentLoginMethodName = 'IsAgentUserLoggedIn';

			//$check = $agentUserService->__soapCall($checkAgentLoginMethodName, array());

			$methodName = "LoginAgentUser";

			$params = array(
				"userName" => $email,
				"password" => $password
			);

			return $registerCustomer = $agentUserService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
			
	}

/*
 * ------------------------------------------------------
 *  This method logs out a agent user that is logged in to the API session.
 * ------------------------------------------------------
*/
	public function logoutAgent()
	{
		try {
			$agentUserService = $this->soapservices_model->getAgentUserService();

			$methodName = "LogoutAgentUser";

			// Unset session of current user
			$this->session->unset_userdata('agent');

			return $agentUserService->__soapCall($methodName, array());
			
		} catch(Exception $e) {
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
			
	}

	/*
 * ------------------------------------------------------
 *  This method will returns Agent all bookings.
 * ------------------------------------------------------
*/
	public function getAgentBookings()
	{

		try {
			$agentUserService = $this->soapservices_model->getBookingService();

			$methodName = "SearchBookings";
			
			$params = array(
				      "searchParams" => array(
					     "FromStartDate" => "2017-01-01T00:00:00.000Z",
					     "ToStartDate" => "2017-01-30T00:00:00.000Z"
					  )
			);

			$bookings = $agentUserService->__soapCall($methodName, array($params));
			//echo "<pre>";
			//print_r($bookings);
			//echo "</pre>";
			
		} catch(Exception $e) {
			echo "<pre>";
			print_r($e);
			echo "</pre>";
            return log_message('error', "Error in Agent Login:".$e->getMessage());
        }  
			
	}



}