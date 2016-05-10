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
 * OrderWithItemCharges class shows how we can set all the Item attributes like  MerchantItemId, 
 * Title , UnitPriceAmount etc.
 * You can set the attributes based on your requirements.
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
$itemObject->createItem('Item1','ItemTitle1',1);

//Create an an PhysicalItem object
$physicalItemObject = new CheckoutByAmazon_Service_Model_PurchaseItem();

//Call createPhysicalItem to add a Physical object
//Input : createPhysicalItem(MerchantItemID,Title,UnitPriceAmount,ShippingServiceLevel)
$physicalItemObject->createPhysicalItem('Item2','ItemTitle2',3,'Expedited');

//Set the optional parameters based on the requierement as follows

//Setting quantity of the item
$itemObject->setQuantity(1);

//Setting Description for the item
$itemObject->setDescription('My Item Description');

//Setting SKU for the item
$itemObject->setSKU(1234);

//Setting URL for thr item
$itemObject->setURL('http://www.amazon.com');

//Setting Category for the item 
$itemObject->setCategory('Category');

//Setting Fulfillment Network for the item. It can be AMAZON_NA or MERCHANT
$itemObject->setFulfillmentNetwork('MERCHANT');

//Setting Custom Data for the Item
$itemObject->setItemCustomData('Custom Data');

//Setting Product Type
$itemObject->setProductType('PHYSICAL');

//Setting Condition for the item - Possible values are Any, Club, Collectible, New, Refurbished
$physicalItemObject->setCondition('New');

//Setting Shipping Label for the item
$physicalItemObject->setShippingLabel('Shipping Label');

//Setting Destination Name in case of split orders
$physicalItemObject->setDestinationName('#default');

//Set Shipping Custom Data for the Item
 $physicalItemObject->setShippingCustomData('Shipping Custom Data');

//Set weight for the item
$physicalItemObject->setWeight(2);

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
$itemList->addItem($itemObject);
$itemList->addItem($physicalItemObject);
try
{
    //Call setItems to associate all the items purchased to Amazon purchase session. The parameters are Purchase Contract ID and Itemlist 
    //created in the previous step. 
    $setItemsStatus = $lib->setItems($purchaseContractId,$itemList);

    //Check whether the calls to setItems was successful
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

