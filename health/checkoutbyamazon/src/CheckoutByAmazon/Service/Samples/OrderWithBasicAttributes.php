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
 * OrderWithBasicAttributes class shows how we can set the basic Item attributes, 
 * which are  MerchantItemId, Title and UnitPriceAmount.
 * After setting the items , it executes completeOrder() which returns you the amazon Order ID.
 */

require_once('.config.inc.php');

//Enter the purchase contract ID here
$purchaseContractId= "<ADD PURCHASE CONTRACT ID HERE>";

//Create an Object of CreateOrder
 $lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

//Object to pass the list of items to setItems function for setting the items to be purchased
$itemList = new CheckoutByAmazon_Service_Model_ItemList();

//Create a new PurchaseItem
$itemObject = new CheckoutByAmazon_Service_Model_PurchaseItem();

//Call create Item or create Physical item to create each item.
//Input : createItem(MerchantItemID,Title,UnitPriceAmount)
$itemObject->createItem('Item1','Title1',1);


//Add all the item objects to ItemList.
$itemList->addItem($itemObject);

try
{
    //Call setItems to set the Items
    $setItemsStatus = $lib->setItems($purchaseContractId,$itemList);


    //Check whether the calls to setItems and setContractCharges where successful
    if($setItemsStatus == 1)
    {
        //Complete the Amazon transaction by using the below call. Pass the 
        //purchaseContractID to complete the order
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

