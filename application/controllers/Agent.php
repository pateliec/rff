<?php
/**
 * Agent Controller
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to Agent 
*/
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Agent extends Bookit 
{

/*
 * ------------------------------------------------------
 *  Load account model
 * ------------------------------------------------------
*/
	/* Defines Construction */
	public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
    }

/*
 * ------------------------------------------------------
 *  Load main account page (not used)
 * ------------------------------------------------------
*/
    /* Register User */
    public function index()
    {
    	$apiHeader = $this->session->userdata('api_header');
    	$apiCookie = $this->session->userdata('api_cookie');
        
        // $n = $this->config->item('cache_time'); // Set Cache Active Time

        // $this->output->cache($n); // Cache
    }
/*
 * ------------------------------------------------------
 *  Login  action
 * ------------------------------------------------------
*/
    public function loginPost($email, $password)
    {
        $loginData = $this->agent_model->loginAgent($email, $password);
        $obj = json_decode(json_encode($loginData), true);
        if($obj['LoginAgentUserResult']['HasError'] != '')
        {
            $this->session->set_flashdata('message_error', 'Ensure the credentials provided are correct and try again');
            return redirect(' ', 'refresh');

        } else{
            // Set Session of Current User
            $this->session->set_userdata('agent', $obj['LoginAgentUserResult']['AgentUser']);
            $this->session->set_flashdata('message_success', 'You are successfully logged in.');
        }          
    }
	
/*
 * ------------------------------------------------------
 *  Login  action
 * ------------------------------------------------------
*/
    public function login()
    {
        try {
            $form_data = $this->input->post();
            if($form_data)
            {
                $loginData = $this->loginPost($form_data['email'], $form_data['password']);
                return redirect('agent/myaccount','refresh');
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
 *  Logout  action
 * ------------------------------------------------------
*/
    public function logout()
    {
        try {
            $user = $this->session->userdata('agent');
            if($user)
            {
                $this->agent_model->logoutAgent();
                $this->session->set_flashdata('message_success', 'You have successfully logged out!');
                return redirect(' ','refresh');
            } else{
                $this->session->set_flashdata('message_error', 'Please login first.');
                return redirect(' ','refresh');
            }
        } catch(Exception $e) {
            log_message('error', "Error in Agent Logout:".$e->getMessage());
            return redirect(' ','refresh');
        }  
    }
/*
 * ------------------------------------------------------
 *  Account details of Agent
 * ------------------------------------------------------
*/
    public function myaccount()
    {
		$this->agent_model->getAgentBookings();
        try {
            $user = $this->session->userdata('agent');
            if($user)
            {
                $this->load->view('include/head');
                $this->load->view('include/header');
                $this->load->view('actions/agentview');
                $this->load->view('include/footer');
            } else{
                $this->session->set_flashdata('message_error', 'Please login first.');
                return redirect(' ','refresh');
            }
        } catch(Exception $e) {
            log_message('error', "Error in Agent Logout:".$e->getMessage());
            return redirect(' ','refresh');
        }  
    }
/*
 * ------------------------------------------------------
 *  Agent info
 * ------------------------------------------------------
*/
    public function view()
    {
        try {
           // $n = $this->config->item('cache_time'); 
            //$this->output->cache($n); //no of minutes

            $agent['agent'] = $this->session->userdata('agent');

            if($agent['agent'])
            {
                return $this->load->view('agent/view', $agent);

            }else{
                $this->session->set_flashdata('message_error', 'Please login first to open account dashboard.');
                return redirect(' ','refresh');
            }
        } catch (Exception $e) {
            log_message('error', "Error in Customer View:".$e->getMessage());
            return redirect(' ','refresh');
        }
    }
}