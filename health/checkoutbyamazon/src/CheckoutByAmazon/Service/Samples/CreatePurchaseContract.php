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
require_once('.config.inc.php');

/**
 *CreatePurchaseContract Class shows how to call the  
 *createPurchaseContract API which returns the PurchaseContract ID.
 *If you use this API, you must pass the Purchase Contract ID as input to 
 *the InlineCheckoutWidget.
 *If no Purchase Contract ID is passed to the InlineCheckoutWidget, the 
 *widget will always create
 *and return a new Purchase Contract ID.For most cases, you don't need to 
 *use this API.
 *A Purchase Contract ID will be returned to you from the 
 *InlineCheckoutWidget, which you can then
 *use with the other APIs.
 **/

//Checkout By Amazon Library
$lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

try
{
    //Call createPurchaseContract() to create PurchaseContract
    $purchaseContractId= $lib->createPurchaseContract();

    echo("Amazon Purchase Contract Id is : ".$purchaseContractId);
}
//CheckoutByAmazon_Service_RequestException comes when there is some problem 
//with the inputs which the merchant passed
catch (CheckoutByAmazon_Service_RequestException $rex)
{
    echo("Caught Request Exception: " . $rex->getMessage());
    echo("Response Status Code: " . $rex->getStatusCode());
    echo("Error Code: " . $rex->getErrorCode() );
    echo("Error Type: " . $rex->getErrorType() );
    echo("Request ID: " . $rex->getRequestId() . "\n");
    echo("XML: " . $rex->getXML() . "\n");
  
}

//Some internal error happened
catch (CheckoutByAmazon_Service_Exception $ex)
{
    echo("Caught Service Exception: " . $ex->getMessage());
    echo("Response Status Code: " . $ex->getStatusCode());
    echo("Error Code: " . $ex->getErrorCode() );
    echo("Error Type: " . $ex->getErrorType() );
    echo("Request ID: " . $ex->getRequestId() . "\n");
    echo("XML: " . $ex->getXML() . "\n");
}

?>
