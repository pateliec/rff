<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Giftcard extends Bookit 
{
	/* Defines Construction */
	public function __construct() {
        parent::__construct();
        $this->load->model('giftcard_model');
    }

    /* Register User */
    public function index()
    {
    	try 
    	{
    		$apiHeader = $this->session->userdata('api_header');
    		$apiCookie = $this->session->userdata('api_cookie');

    		$n = $this->config->item('cache_time'); // Set Cache Active Time
 
    		// $this->output->cache($n); // Cache
    		$this->load->helper('url'); // Get Current Url
    		$data = $this->giftcard_model->giftCardList();
    		$obj = json_decode(json_encode($data), true);
    		if($obj['GetResourcesResult']['Resources'])
    		{
    			$this->load->view('include/head');
		        $this->load->view('include/header');
		        $this->load->view('giftcard', $obj);
		        $this->load->view('include/footer');
    			return $this;

    		} else{
    			$this->session->set_flashdata('message_error', 'Invalid request!');
                return redirect(' ','refresh');
    		}

	    } catch(Exception $e) {
            log_message('error', "Error in Create an Customer Acccount:".$e->getMessage());
        }
		    	
    }
}