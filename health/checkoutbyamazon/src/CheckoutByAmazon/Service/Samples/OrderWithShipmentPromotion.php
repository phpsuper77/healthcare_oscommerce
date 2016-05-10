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
 * OrderWithShipmentPromotion class shows how we can set shipment charges and promotions.
 * After setting the shipping charges and promotions , it executes completeOrder() which returns you the amazon Order ID.
 */

require_once('.config.inc.php');

//Enter the purchase contract ID here
$purchaseContractId= "<ADD PURCHASE CONTRACT ID HERE>";

//Checkout By Amazon Library
$lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

//Object to pass the list of items to setItems function for setting the items to be purchased
$itemList = new CheckoutByAmazon_Service_Model_ItemList();

//Call create PhysicalItem or create Physical item to create each item.
//Signature is createItem(MerchantItemID,Title,UnitPriceAmount)
$physicalItemObject = new CheckoutByAmazon_Service_Model_PurchaseItem();

//Call createPhysicalItem to add a Physical object
//Signature is createPhysicalItem(MerchantItemID,Title,UnitPriceAmount,ShippingServiceLevel)
$physicalItemObject->createPhysicalItem('Item2','ItemTitle',3,'Expedited');

//Set the optional parameters based on the requierement

//Set Shipping Charges for the item
$physicalItemObject->setItemShippingCharges(5);

//Create a promotion object for setting the promotions. 
$itemPromotion = new CheckoutByAmazon_Service_Model_Promotion();
  
//Create the promotion
//The arguments are: PromotionID,Promtion Description and Discount Amount
$itemPromotion->createPromotion('ItemPromotion1','ItemPromoDesc',1);
  
//Create a promotion object list for setting the promotions.
$itemPromotionListObject = new CheckoutByAmazon_Service_Model_PromotionList();

//Add the promotion for adding the promotion.
//Currently only one promotion per order is supported
$itemPromotionListObject->addPromotion($itemPromotion);

//Set promotions for the Item
$physicalItemObject->setItemPromotions($itemPromotionListObject);

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
//Some error with the request passed by the merchant
catch (CheckoutByAmazon_Service_RequestException $rex)
{
    echo("Caught Request Exception: " . $rex->getMessage());
    echo("Response Status Code: " . $rex->getStatusCode());
    echo("Error Code: " . $rex->getErrorCode() );
    echo("Error Type: " . $rex->getErrorType() );
    echo("Request ID: " . $rex->getRequestId() . "\n");
    echo("XML: " . $rex->getXML() . "\n");

}

//Some internal error occured
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

