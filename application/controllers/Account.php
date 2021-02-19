<?php
/**
 * Account Controller
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to customer
*/
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Account extends Bookit 
{

/*
 * ------------------------------------------------------
 *  Load account model
 * ------------------------------------------------------
*/
	public function __construct() {
		parent::__construct();
        $this->load->model('account_model');
    }

/*
 * ------------------------------------------------------
 *  Load main account page (not used)
 * ------------------------------------------------------
*/
    public function index()
    {
    	$apiHeader = $this->session->userdata('api_header');
    	$apiCookie = $this->session->userdata('api_cookie');
    }
/*
 * ------------------------------------------------------
 *  Load registration form
 * ------------------------------------------------------
*/
    public function create()
    {
		$n = $this->config->item('cache_time'); 
        $this->output->cache($n); //no of minutes
    	if ( ! file_exists(APPPATH.'views/account/create.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $this->load->view('include/head');
        $this->load->view('include/header');
        $this->load->view('account/create');
        $this->load->view('include/footer');
    }
/*
 * ------------------------------------------------------
 *  Load edit form for customer information
 * ------------------------------------------------------
*/	
	public function edit()
    {
    	if ( ! file_exists(APPPATH.'views/account/edit.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }
		$user = $this->session->userdata('customer');
		if(isset($user['CustomerNumber']))
		{
			$customer['customerData'] = $user;
			$customer['countries'] = $this->config->item('countries');
			$this->load->view('include/head');
			$this->load->view('include/header');
			$this->load->view('account/edit', $customer);
			$this->load->view('include/footer');
		}
		else
		{
			return redirect(' ','refresh');
		}
    }
/*
 * ------------------------------------------------------
 *  Login form Action method
 *  Input: email and password
 *  Output: customer session creation
 * ------------------------------------------------------
*/
    public function loginPost($email, $password)
    {
        $loginData = $this->account_model->loginCustomer($email, $password);
        $obj = json_decode(json_encode($loginData), true);
        if($obj['LoginCustomerAccountResult']['HasError'] != '')
        {
            $this->session->set_flashdata('message_error', 'Ensure the credentials provided are correct and try again');
			return redirect(' ','refresh');

        } else{
            // Set Session of Current User
            $this->session->set_userdata('customer', $obj['LoginCustomerAccountResult']['CustomerAccount']);
            $this->session->set_flashdata('message_success', 'You are successfully logged in.');
			return redirect('account/myaccount','refresh');
        }          
    }
/*
 * ------------------------------------------------------
 *  Login form Action method
 *  Input: email and password
 * ------------------------------------------------------
*/
    public function login()
    {
        try {
            $form_data = $this->input->post();
            if($form_data)
            {
                $loginData = $this->loginPost($form_data['email'], $form_data['password']);
               
            } else {
                $this->session->set_flashdata('message_error', 'Invalid request!');
                return redirect(' ','refresh');
            }        	

        } catch(Exception $e) {
            log_message('error', "Error in Customer Login:".$e->getMessage());
            return redirect(' ','refresh');
        }  
    }
/*
 * ------------------------------------------------------
 *  Registration form Action method
 *  Input: customer data
 *  Output: after registration calling login method
 * ------------------------------------------------------
*/
    public function register()
    {
        try{
            $form_data = $this->input->post();
            if($form_data)
            {

                $day = $form_data['day'];
                if($day)
                {
                    $dayLen = strlen((string)$day);
                    if($dayLen == 1)
                    {
                        $day = '0'.$day;
                    }
                }

                $month = $form_data['month'];
                if($month)
                {
                    $monthLen = strlen((string)$month);
                    if($monthLen == 1)
                    {
                        $month = '0'.$month;
                    }
                }
                $data = array(
                    "firstname"=> $form_data['firstname'], 
                    "lastname" => $form_data['lastname'], 
                    "address" => $form_data['address'], 
                    "postcode" => $form_data['postcode'], 
                    "city" => $form_data['city'], 
                    "country" => $form_data['country'], 
                    "mobile_phone" => $form_data['mobile_phone'], 
                    "email" => $form_data['email'], 
                    "password" => $form_data['password'],
                    "dob" => "".$form_data['year']."-".$month."-".$day."T00:00:00Z",
                    "gender" => $form_data['gender'],
                    "title" => $form_data['title']
                );
				
                $response = $this->account_model->registerCustomer($data);
				
				if($response['SynchronizeCustomerAccountsResult']['HasError'] != 1)
				{
					$this->loginPost($form_data['email'], $form_data['password']);
					$this->session->set_flashdata('message_success', 'You have successfully create an account!');
					return redirect('account/myaccount');
				}
				else
				{
					$this->session->set_flashdata('message_error', $response['SynchronizeCustomerAccountsResult']['Message']);
					 return redirect(' ','refresh');
				}
                
            } else{
                $this->session->set_flashdata('message_error', 'Invalid request!');
                return redirect(' ','refresh');
            }

        } catch(Exception $e) {
            log_message('error', "Error in Create an Customer Acccount:".$e->getMessage());
        }
    }
/*
 * ------------------------------------------------------
 *  Edit form Action method
 *  Input: customer data
 *  Output: After successful edit, customer session update 
 * ------------------------------------------------------
*/
	 public function editPost()
    {
        try{
            $form_data = $this->input->post();
            if($form_data)
            {

                $day = $form_data['day'];
                if($day)
                {
                    $dayLen = strlen((string)$day);
                    if($dayLen == 1)
                    {
                        $day = '0'.$day;
                    }
                }

                $month = $form_data['month'];
                if($month)
                {
                    $monthLen = strlen((string)$month);
                    if($monthLen == 1)
                    {
                        $month = '0'.$month;
                    }
                }
                $data = array(
                    "customernumber"=> $form_data['customernumber'], 
                    "firstname"=> $form_data['firstname'], 
                    "lastname" => $form_data['lastname'], 
                    "address" => $form_data['address'], 
                    "postcode" => $form_data['postcode'], 
                    "city" => $form_data['city'], 
                    "country" => $form_data['country'], 
                    "mobile_phone" => $form_data['mobile_phone'], 
                    "dob" => "".$form_data['year']."-".$month."-".$day."T00:00:00Z",
                    "gender" => $form_data['gender'],
                    "title" => $form_data['title']
                );
				
                $response = $this->account_model->editCustomer($data);
				
				if($response['SynchronizeCustomerAccountsResult']['HasError'] != 1)
				{
					$customerdata = $this->account_model->viewCustomer();
					
					$this->session->unset_userdata('customer');
					
					$this->session->set_userdata('customer', $customerdata['GetCustomerAccountResult']['CustomerAccount']);

					$this->session->set_flashdata('message_success', 'You information has been update!');
					
					$this->output->delete_cache('/account/view');

					return redirect('account/myaccount');
				}
				else
				{
					$this->session->set_flashdata('message_error', $response['SynchronizeCustomerAccountsResult']['Message']);
					 return redirect(' ','refresh');
				}
				
            } else{
                $this->session->set_flashdata('message_error', 'Invalid request!');
                return redirect(' ','refresh');
            }

        } catch(Exception $e) {
            log_message('error', "Error in edit an Customer information:".$e->getMessage());
        }
    }

/*
 * ------------------------------------------------------
 *  Logout Action
 * ------------------------------------------------------
*/
    public function logout()
    {
        try {
            $user = $this->session->userdata('customer');
            if($user)
            {
                $this->account_model->logoutCustomer();
                $this->session->set_flashdata('message_success', 'You have successfully logged out!');
                return redirect(' ','refresh');
            } else{
                $this->session->set_flashdata('message_error', 'Please login first.');
                return redirect(' ','refresh');
            }
        } catch(Exception $e) {
            log_message('error', "Error in Customer Logout:".$e->getMessage());
            return redirect(' ','refresh');
        }  
    }
/*
 * ------------------------------------------------------
 *  Load customer information view
 *  Input: customer session
 *  Output: customer data
 * ------------------------------------------------------
*/
    public function myaccount()
    {
		$this->account_model->viewCustomer();
        try {
            $user = $this->session->userdata('customer');
            if($user)
            {
                $this->load->view('include/head');
                $this->load->view('include/header');
                $this->load->view('actions/customerview');
                $this->load->view('include/footer');
            } else{
                $this->session->set_flashdata('message_error', 'Please login first.');
                return redirect(' ','refresh');
            }
                
        } catch(Exception $e) {
            log_message('error', "Error in Customer Dashboard:".$e->getMessage());
            return redirect(' ','refresh');
        }  
    }
/*
 * ------------------------------------------------------
 *  Load customer information view through Ajax used in myaccount view
 * ------------------------------------------------------
*/
    public function view()
    {
        try {
            $n = $this->config->item('cache_time'); 
            $this->output->cache($n); //no of minutes

            $user['user'] = $this->session->userdata('customer');
            if($user['user'])
            {
                if ( ! file_exists(APPPATH.'views/account/view.php'))
                {
                    // Whoops, we don't have a page for that!
                    show_404();
                }
                $customerNumber = $user['user']['CustomerNumber'];
                $email = $user['user']['Email'];
                if(!array_key_exists("Title",$user['user']))
                {
                    $user['user']['Title'] = 'Mr.';
                }
                
                return $this->load->view('account/view', $user);

            }else{
                $this->session->set_flashdata('message_error', 'Please login first to open account dashboard.');
                return redirect(' ','refresh');
            }
        } catch (Exception $e) {
            log_message('error', "Error in Customer View:".$e->getMessage());
            return redirect(' ','refresh');
        }
    }
/*
 * ------------------------------------------------------
 *  Load forgot password form
 * ------------------------------------------------------
*/
    public function forgotpassword()
    {
		$n = $this->config->item('cache_time'); 
        $this->output->cache($n); //no of minutes
        $this->load->view('include/head');
        $this->load->view('include/header');
        $this->load->view('account/forgotpassword');
        $this->load->view('include/footer');
    }
/*
 * ------------------------------------------------------
 *  Forgot password action
 * ------------------------------------------------------
*/
    public function forgotAction()
    {
        try {
            $form_data = $this->input->post();
            if($form_data)
            {
                $email = $form_data['email'];

                // Reset Password Model
                $response = $this->account_model->forgotPassword($email); 

				if($response['SendPasswordResetTokenResult']['HasError'] == 1)
				{
					$this->session->set_flashdata('message_error', $response['SendPasswordResetTokenResult']['Message']);
					return redirect('account/forgotpassword');
				}
				else
				{
					$this->session->set_flashdata('message_success', "Please check your email to reset password.");
					return redirect(' ','refresh');
				}
                
            }

        } catch (Exception $e) {
            log_message('error', "Error in Customer Foregt Password:".$e->getMessage());
            return redirect(' ','refresh');
        }
    }
/*
 * ------------------------------------------------------
 *  Load reset password form
 * ------------------------------------------------------
*/
    public function reset()
    {
        $this->load->view('include/head');
        $this->load->view('include/header');
        $this->load->view('account/reset');
        $this->load->view('include/footer');
    }
/*
 * ------------------------------------------------------
 *  Reset password action
 * ------------------------------------------------------
*/
    public function resetAction()
    {
        try {
            $form_data = $this->input->post();
            $response = $this->account_model->resetPassword($form_data['password'], $form_data['token_id']); 
			if($response['ResetPasswordResult']['HasError'] == 1)
			{
				$this->session->set_flashdata('message_error', $response['ResetPasswordResult']['Message']);
                return redirect(' ','refresh');
			}
			else
			{
				$this->session->set_flashdata('message_success', "Your password has been reset successfully");
                return redirect(' ','refresh');
			}

        } catch (Exception $e) {
            log_message('error', "Error in Customer View:".$e->getMessage());
            return redirect(' ','refresh');
        }
    }
}