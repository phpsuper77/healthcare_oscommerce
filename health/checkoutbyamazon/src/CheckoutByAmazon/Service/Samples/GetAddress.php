<?php
/*******************************************************************************
 *  Copyright 2011 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *
 *  You may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 *  This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR
 *  CONDITIONS OF ANY KIND, either express or implied. See the License for the
 *  specific language governing permissions and limitations under the License.
 * *****************************************************************************
 */

/**
 * GetAddress class shows how we can retrieve the address selected by the buyer. 
 * Based on the buyer address you can calculate Taxes and Shipping charges 
 * to be applied to the order. 
 **/
require_once('.config.inc.php');

//Create an instance of CreaateOrder class
$lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

//Enter the purchase contract ID here

$contractID = $_POST['purchaseContractId'];
$purchaseContractId= $contractID;

try 
{
    //Call getAddress($purchaseContractId) to get the address details
    $addressList = $lib->getAddress($purchaseContractId);

    //Display the Address List
    foreach( $addressList as $address)
    {
		print $address->getAddressAsJson();
		//print json_encode($address);
		/*
        echo("<H4>  Address Selected by Buyer is: </H4>");
        echo("StateCode: ". $address->getStateOrProvinceCode());
        echo("<br> PostalCode: ".$address->getPostalCode());
        echo("<br> CountryCode : ". $address->getCountryCode());
        echo("<br> City : ".$address->getCity());		*/
    }
}
//Error with the request parameters passed by the merchant
catch (CheckoutByAmazon_Service_RequestException $rex)
{
        echo("Caught Request Exception: " . $rex->getMessage());
        echo("Response Status Code: " . $rex->getStatusCode());
        echo("Error Code: " . $rex->getErrorCode() );
        echo("Error Type: " . $rex->getErrorType() );
        echo("Request ID: " . $rex->getRequestId() . "\n");
        echo("XML: " . $rex->getXML() . "\n");
  
}

//Internal system error occured
catch (CheckoutByAmazon_Service_Exception $ex)
{

        echo("Caught Service Exception : " . $ex->getMessage());
        echo("Response Status Code: " . $ex->getStatusCode());
        echo("Error Code: " . $ex->getErrorCode() );
        echo("Error Type: " . $ex->getErrorType() );
        echo("Request ID: " . $ex->getRequestId() . "\n");
        echo("XML: " . $ex->getXML() . "\n");
}   

?>
