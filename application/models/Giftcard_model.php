<?php
class Giftcard_model extends CI_Model 
{
	public function __construct() {
		parent::__construct();
		$this->load->model('soapservices_model'); 
	}
	
	public function giftCardList()
	{
		try 
		{
			$definitionService = $this->soapservices_model->getDefinitionsService();

			$methodName = "GetResources";
			$params = array(
				"ResourceQuery" => array(
					"Suppliers" => array(
						"Supplier" => "CARD"
					)
				)
			);
			
			return $definitionService->__soapCall($methodName, array($params));

		} catch(Exception $e) {
            return log_message('error', "Error in Class Giftcard_model Method giftCardList:".$e->getMessage());
        }  
	}
}