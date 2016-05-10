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
 * 
 * OrderWithContractCharges class shows how you can set the contract charges for the whole Order. 
 * The Contract charges are Shipment charges and Promotions for the whole Order.
 *
 */
 
//require_once('checkoutbyamazon/src/CheckoutByAmazon/Service/Samples/.config.inc.php'); 


$cwd = getcwd();
$order_total_modules = new order_total;
$order_totals = $order_total_modules->process();

/*require_once(DIR_WS_CLASSES . 'order.php');
require_once(DIR_WS_CLASSES . 'order_total.php');*/
chdir("checkoutbyamazon/src/CheckoutByAmazon/Service/Samples/");
/*
include_once("includes/application_top.php");
include_once(DIR_WS_CLASSES . 'order.php');
require(DIR_WS_CLASSES . 'order_total.php');

$order = new order;
$order_total_modules = new order_total;
$order_total_modules->process();*/


$contractID = $_POST['amazon_purchaseContractId'];
$purchaseContractId = $contractID;

//chdir($cwd);
require_once('.config.inc.php');
//Create an Object of CreateOrder
$lib = new CheckoutByAmazon_Service_CBAPurchaseContract();

//Object to pass the list of items to setItems function for setting the items to be purchased
$itemList = new CheckoutByAmazon_Service_Model_ItemList();

//Create a new PurchaseItem


//Call create Item or create Physical item to create each item.
//Input : createItem(MerchantItemID,Title,UnitPriceAmount)

/*print "<pre>";
print_r($order->products);
die;*/

if (count($order->products) > 0) {
	foreach($order->products as $key=>$product) {		
		$price = (($product['tax'] / 100) * $product['price']) + $product['price'];
		$price = number_format((float)$price, 2, '.', '');		
		//$price = "0.01";
		$itemObject = new CheckoutByAmazon_Service_Model_PurchaseItem();
		$itemObject->createItem($product['id'],$product['name'],$price);
		$itemObject->setItemTax(2.00);
		$itemObject->setSKU($product['model']);
		$itemObject->setQuantity($product['qty']);
		//$itemObject->setItemCustomData($product['shipping_method']);
		//$itemObject->setShippingLabel($product['shipping_method']);
		$itemObject->setItemCustomData($order->info['shipping_method']);
		$itemList->addItem($itemObject);
	}
}

	foreach ($order_totals as $value) {
		if ($value['code'] == "ot_coupon") {
			$coupon_title = $value['title'];
			$coupon_value = $value['value'];
		}
	}
	
	
	/*print "<pre>";
	print_r($order);
	print_r($itemList);
	print "</pre>";
	
	die;*/
	
try
{
    //Call setItems to associate all the items purchased to Amazon purchase session. The parameters are Purchase Contract ID and Itemlist 
    //created in the previous step. 
    $setItemsStatus = $lib->setItems($purchaseContractId,$itemList);

    //Create a promotion object to add Order Level promotions
    $promotion = new CheckoutByAmazon_Service_Model_Promotion();
      
    //Create the promotion
    //The arguments are: PromotionID,Promtion Description and Discount Amount
    //$promotion->createPromotion('Promotion1','PromoDesc',0);
	
	if ($coupon_value != "") {
		$promotion->createPromotion($coupon_title,$coupon_title,$coupon_value);
	}
	
      
    //Add the promotion to the promotion list. Currently only one promotion is suported per Order
    $promotionListObject = new CheckoutByAmazon_Service_Model_PromotionList();
    $promotionListObject->addPromotion($promotion);
      
    /*
     * If only Shipping is provided at the Order level, then Promotions 
     * at item level, if present, will be applicable.
     * 
     *Similarly, if only Promotions are specified at the Contract level, the 
     *Shipping at item level, if present, will be applicable.
     *
     *If Promotions are specified at both Contract and Item levels then Contract 
     * level promotions take precedence,irrespective of the order in which they were specified.
     */

    //Create a charges object to add contract charges for the whole Order
    $charges = new CheckoutByAmazon_Service_Model_ContractCharges();


    //Set Promotion at Order Level
    $charges->setContractPromotions($promotionListObject);

    //Set Shipping Charges at Order Level
    $charges->setContractShippingCharges($order->info['shipping_cost']);

    //Call setContractCharges to associate all the Chrages Oject to Amazon Order
    $setContractChargesStatus = $lib->setContractCharges($purchaseContractId,$charges);
	
    //Check whether the calls to setItems and setContractCharges where successful
    if((($setContractChargesStatus == 1) || is_null($setContractChargesStatus)) && ($setItemsStatus == 1))
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

//Internal system error occured
catch (CheckoutByAmazon_Service_Exception $ex)
{
    echo("Caught Exception: " . $ex->getMessage());
    echo("Response Status Code: " . $ex->getStatusCode());
    echo("Error Code: " . $ex->getErrorCode() );
    echo("Error Type: " . $ex->getErrorType() );
    echo("Request ID: " . $ex->getRequestId() . "\n");
    echo("XML: " . $ex->getXML() . "\n");
}

?>

