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
 * OrderWithShipment class shows how we can set Shipment charges.
 * After setting shipment charges , it executes completeOrder() 
 * which returns you the amazon Order ID.
 */

require_once('.config.inc.php');

//Enter the purchase contract ID here
$purchaseContractId= "<ADD PURCHASE CONTRACT ID HERE>";

//Checkout By Amazon Library
$lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

//Object to pass the list of items to setItems function for setting the items to be purchased
$itemList = new CheckoutByAmazon_Service_Model_ItemList();

//Create a new PurchaseItem
$physicalItemObject = new CheckoutByAmazon_Service_Model_PurchaseItem();

//Call createPhysicalItem to add a Physical object
//Signature is createPhysicalItem(MerchantItemID,Title,UnitPriceAmount,ShippingServiceLevel)
$physicalItemObject->createPhysicalItem('Item2','ItemTitle2',3,'Expedited');

//Set the optional parameters based on the requierement

//Set Shipping Charges for the item
$physicalItemObject->setItemShippingCharges(5);

//Add all the item objects to ItemList.
$itemList->addItem($physicalItemObject);

try
{
    //Call setItems to associate all the items purchased to Amazon purchase session. 
    //The parameters are Purchase Contract ID and Itemlist created in the previous step. 
    $setItemsStatus = $lib->setItems($purchaseContractId,$itemList);

    //Check if the call to setItems was successful
    if($setItemsStatus == 1)
    {
        //Complete the Amazon transaction by using the below call. Pass the purchaseContractID to complete the order
        $orderIdList = $lib->completeOrder($purchaseContractId);

        //Displays the orders generated
        if(!is_null($orderIdList))
        {
            echo("<br> Orders completed Successfully");
            foreach ($orderIdList as $orderId) 
            {
                 echo("<br> OrderId : ");
                echo($orderId);
            }
        }
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


//Internal error occured
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

