<?php
/**
 * Routes Model
 * @package	RFF
 * @author	Serole Team
 * includes method related to routes, package and cruise
*/
class Routes_model extends CI_Model {
	
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
 *  This method returns all routes and cruse.
 * ------------------------------------------------------
*/
	
	public function getRoutes()
	{
		
		if(isset($_SESSION['api_cookie']) || isset($_SESSION['api_accessToken']))
		{

			try{
				 $routesData = $this->restservices_model->getRoutes();
				 if($routesData['returnStatusCode'] == 200)
					 return $routesData['responseData'];
				 else
					 log_message('error', "Error in getRoutes:".$routesData['responseData']);
			}
			catch(Exception $e)
			{
				log_message('error', "Error in getRoutes:".$e->getMessage());
			}
		}
	}
	
/*
 * ------------------------------------------------------
 *  This method returns all packages.
 * ------------------------------------------------------
*/

	public function getPackageRoutes()
	{
		if(isset($_SESSION['api_cookie']))
		{
			$definitionService = $this->soapservices_model->getDefinitionsService();
            $data = date("Y-m-d");
			try{
				$methodName = 'GetBookableHolidayPackages';
				$params = array(
					"Query" => array(
						"ProductCode" => "PACK",
						"StartDate" => $data."T00:00:00.000Z"
					)
				);
				$routes = $definitionService->__soapCall($methodName, array($params));
				
				$getBookableHolidayPackages = $routes->GetBookableHolidayPackagesResult->PackageDescriptions;
				
				return json_decode(json_encode($getBookableHolidayPackages), true);
				//return $getBookableHolidayPackages;
			}
			catch(Exception $e)
			{
				log_message('error', "Error in getRoutes:".$e->getMessage());
			}
		}
	}	

}