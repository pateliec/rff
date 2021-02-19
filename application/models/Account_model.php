<?php
/**
 * Account Model
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to customer
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model 
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
 *  This method logs in a customer account to the API session.
 *  Input: email and password
 *  Output: The customer account results containing the customer account if the customer account was successfully logged in.
 * ------------------------------------------------------
*/
	
	public function loginCustomer($email, $password)
	{
		try {
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			//$checkCustomerLoginMethodName = 'IsCustomerAccountLoggedIn';

			//$check = $customerAccountService->__soapCall($checkCustomerLoginMethodName, array());

			$methodName = "LoginCustomerAccount";

			$params = array(
				"customerLoginParam" => array(
					"Email" => $email,
					"Password" => $password
				)
			);

			$registerCustomer = $customerAccountService->__soapCall($methodName, array($params));
			
			return $registerCustomer;
			
		} catch(Exception $e) {
            return log_message('error', "Error in Customer Login:".$e->getMessage());
        }  
			
	}

/*
 * ------------------------------------------------------
 *  This method will Add customer accounts.
 *  Input: customer related data
 *  Output: Result if the synch was successful.
 * ------------------------------------------------------
*/

	public function registerCustomer($data)
	{
		try 
		{
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "SynchronizeCustomerAccounts";
			
			$params = array(
				"customerAccountsParam" => array(
					"CustomerAccountParams" => array(
						"CustomerAccountParam" => array(
							"FirstName" => $data['firstname'],
							"LastName" => $data['lastname'],
							"Address" => $data['address'],
							"PostCode" => $data['postcode'],
							"City" => $data['city'],
							"County" => $data['country'],
							"MobilePhoneNumber" => $data['mobile_phone'],
							"Email" => $data['email'],
							"Password" => $data['password'],
							"Consent" => "1",
							"Gender" => "F",
							"DefaultProductCode" => "NORM",
							"DateOfBirth" => $data['dob'],
							"Gender" => $data['gender'],
							"Title" => $data['title']
						)
					)
				)
			);

			return $registerCustomer = (array)$customerAccountService->__soapCall($methodName, array($params));
			
		} catch(Exception $e) {
            return log_message('error', "Error in Customer registration:".$e->getMessage());
        }  
	}
/*
 * ------------------------------------------------------
 *  This method will update customer accounts.
 *  Input: customer related data
 *  Output: Result if the synch was successful.
 * ------------------------------------------------------
*/	
	public function editCustomer($data)
	{
		try 
		{
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "SynchronizeCustomerAccounts";
			
			$params = array(
				"customerAccountsParam" => array(
					"CustomerAccountParams" => array(
						"CustomerAccountParam" => array(
							"CustomerNumber" => $data['customernumber'],
							"FirstName" => $data['firstname'],
							"LastName" => $data['lastname'],
							"Address" => $data['address'],
							"PostCode" => $data['postcode'],
							"City" => $data['city'],
							"County" => $data['country'],
							"MobilePhoneNumber" => $data['mobile_phone'],
							"DefaultProductCode" => "NORM",
							"DateOfBirth" => $data['dob'],
							"Gender" => $data['gender'],
							"Title" => $data['title']
						)
					)
				)
			);

			return $registerCustomer = (array)$customerAccountService->__soapCall($methodName, array($params));
			
		} catch(Exception $e) {
            return log_message('error', "Error in edit customer:".$e->getMessage());
        }  
	}

/*
 * ------------------------------------------------------
 *  This method retrieves a customer account.
 *  Input: CustomerNumber, Email
 *  Output: The customer account result containing the customer account, if the query was successfully retrieved.
 * ------------------------------------------------------
*/		
	public function viewCustomer()
	{
		try 
		{
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "GetCustomerAccount";
			
			$user = $this->session->userdata('customer');
			$params = array(
				"queryParam" => array(
					"CustomerNumber" => $user['CustomerNumber'],
					"Email" => $user['Email']	
				)
			);

		    $customerDetails = (array)$customerAccountService->__soapCall($methodName, array($params));
			
			return $customerDetails;

		} catch(Exception $e) {
            return log_message('error', "Error in Customer view:".$e->getMessage());
        }  
	}

/*
 * ------------------------------------------------------
 *  This method logs out a customer account that is logged in to the API session.
 *  Output: Result indicating if the logout was successful or not.
 * ------------------------------------------------------
*/	

	public function logoutCustomer()
	{
		try {
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "LogoutCustomerAccount";

			// Unset session of current user
			$this->session->unset_userdata('customer');

			return $customerAccountService->__soapCall($methodName, array());

		} catch(Exception $e) {
            return log_message('error', "Error in Customer Logout:".$e->getMessage());
        }  
			
	}

/*
 * ------------------------------------------------------
 *  This method will create a reset token and send the e-mail.
 *  Input: ResetUrl, Email, QueryStringName
 *  Output: The customer account result containing the customer account, if the query was successfully retrieved.
 * ------------------------------------------------------
*/	

	public function forgotPassword($email)
	{
		try
		{
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "SendPasswordResetToken";

			$params = array(
				"pwdResetTokenParam" => array(
					"ResetUrl" => base_url().'account/reset',
					"Email" => $email,
					"QueryStringName" => "tokenId"
				)
			);
			
			$response = (array)$customerAccountService->__soapCall($methodName, array($params));
			
			return $response;
			
		} catch(Exception $e) {
            return log_message('error', "Error in forgot password:".$e->getMessage());
        }  
	}

/*
 * ------------------------------------------------------
 *  This method will reset the password for the customer account connected to the password reset token. Token is valid for 1 hour.
 *  Input: tokenId, password
 *  Output: Result if the reset was successful.
 * ------------------------------------------------------
*/	

	public function resetPassword($password, $tokenId)
	{
		try 
		{
			$customerAccountService = $this->soapservices_model->getCustomerAccountService();

			$methodName = "ResetPassword";

			$params = array(
					"tokenId" => $tokenId,
					"password" => $password
			);
			
			$response = (array)$customerAccountService->__soapCall($methodName, array($params));
			
			return json_decode(json_encode($response), true);
			
		} catch(Exception $e) {
            return log_message('error', "Error in Customer Login:".$e->getMessage());
        }  
	}
}