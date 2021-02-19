<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."controllers/Bookit.php");

class Index extends Bookit 
{
	/* Defines Construction */
	public function __construct() {
        parent::__construct();  
    }

    /* Register User */
    public function index()
    {
    	$apiHeader = $this->session->userdata('api_header');
    	$apiCookie = $this->session->userdata('api_cookie');
        echo $apiHeader;

        $n = $this->config->item('cache_time'); // Set Cache Active Time

        $this->output->cache($n); // Cache
        $this->load->helper('url'); // Get Current Url

        // $email = $_POST['email'];
        // $password = $_POST['password'];
        // $mobile_phone = $_POST['mobile_phone'];
        // $firstname = $_POST['firstname'];
        // $lastname = $_POST['lastname'];
        // $address = $_POST['address'];
        // $state = $_POST['state'];
        // $city = $_POST['city'];
        // $postcode = $_POST['postcode'];
        // $country = $_POST['country'];

        // try {
        //     $userwsdl = 'http://bookitapitest.rottnestfastferries.local/userservice.asmx?wsdl';
        //     $customerWsdl = 'http://bookitapidev.rottnestfastferries.local/CustomerAccountService.asmx?wsdl';
        //     $credentials = array(
        //         "UserName"=>"webbooking",
        //         "Password"=>"B1rdBra1n"
        //     );
        //     $credentialsWithCookie = array(
        //         "UserName"=>"webbooking",
        //         "Password"=>"B1rdBra1n",
        //         ""
        //     );

        //     $loginParams = array(
        //         "UserName"=> "webbooking",
        //         "Password"=> "B1rdBra1n"
        //     );

        //     $namespace = 'http://hfs.hogia.fi/webservices/';
        //     $headers = new SoapHeader($namespace, 'Credentials', $credentials);
            
        //     $customerSoap = new SoapClient($customerWsdl);
        //     $usersoapClient = new SoapClient($userwsdl);
        //     $customerSoap->__setSoapHeaders($headers);
        //     $usersoapClient->__setSoapHeaders($headers);

        //     $loginData = $usersoapClient->__soapCall("Login", array($loginParams));
        //     $loginCookie = $usersoapClient->_cookies;

        //     $customerSoap->__setCookie("ASP.NET_SessionId",$loginCookie['ASP.NET_SessionId'][0]);
            
        //     $registerParams = array(
        //         "SynchronizeCustomerAccounts" => array(
        //             "customerAccountsParam" => array(
        //                 "CustomerAccountParams" => array(
        //                     "CustomerAccountParam" => array(
        //                         "CustomerNumber" => "",
        //                         "NewCustomerNumber" => "",
        //                         "FirstName" => "$firstname",
        //                         "LastName" => "$lastname",
        //                         "Address" => "$address",
        //                         "PostCode" => "$postcode",
        //                         "City" => "$city",
        //                         "CountryCode" => "",
        //                         "County" => "$country",
        //                         "WorkPhoneNumber" => "",
        //                         "MobilePhoneNumber" => "$mobile_phone",
        //                         "HomePhoneNumber" => "",
        //                         "Email" => "$email",
        //                         "DefaultProductCode" => "",
        //                         "Contact" => "",
        //                         "Iban" => "",
        //                         "Title" => "",
        //                         "Gender" => "",
        //                         "DateOfBirth" => "",
        //                         "DateOfBirthText" => "",
        //                         "AcceptsContact" => "",
        //                         "Language" => "",
        //                         "PaymentMethod" => "",
        //                         "PaymentType" => "",
        //                         "GroupCode" => "",
        //                         "SecurityNumber" => "",
        //                         "DefaultRouteCode" => "",
        //                         "DataSource" => "",
        //                         "ExternalId" => "",
        //                         "ExternalDate" => "",
        //                         "Active" => "1",
        //                         "Password" => "$password",
        //                         "Consent" => "",
        //                         "Restricted" => "",
        //                         "PassengerType" => ""
        //                     )
        //                 )
        //             )
        //         )
        //     );
        //     echo '<pre>'; print_r($registerParams);
        //     $regsterCustomer = $customerSoap->__soapCall("SynchronizeCustomerAccounts", array($registerParams));
        // } catch (Exception $e) {
        //     echo 'Caught exception: ',  $e->getMessage(), "\n";
        // }
    	
    }
}