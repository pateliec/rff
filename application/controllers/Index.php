<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Index extends Bookit {
    
    public function __construct() {
        parent::__construct();  
		$this->load->model('routes_model');
    }

    /**
    Home Page method
     */
    public function index() {
 
        $this->load->view('include/head');
        $this->load->view('include/header');
		$routes = array();
		$routes['routes'] = $this->routes_model->getRoutes();
		$routes['pkgroutes'] = $this->routes_model->getPackageRoutes();
        $this->load->view('index', $routes);
        $this->load->view('index_script');
        $this->load->view('include/footer');
    }

}
