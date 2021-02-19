<?php
/**
 * Account Controller
 * @package	RFF
 * @author	Serole Team
 * includes all methods related to home page search data
*/
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Home extends Bookit {
	
/*
 * ------------------------------------------------------
 *  Load routes model
 * ------------------------------------------------------
*/
    
    public function __construct() {
        parent::__construct();  
		$this->load->model('routes_model');
    }

/*
 * ------------------------------------------------------
 *  Load home page
 * ------------------------------------------------------
*/
    public function index() {
        $n = $this->config->item('cache_time'); 
        $this->output->cache($n); //no of minutes
		$routes['routes'] = $this->routes_model->getRoutes();
        $routes['pkgroutes'] = $this->routes_model->getPackageRoutes();

        return $this->load->view('home', $routes);   
    }
}
