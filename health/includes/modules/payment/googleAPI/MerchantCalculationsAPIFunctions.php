<?php
/**
 * Copyright (C) 2006 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Please refer to the Google Checkout PHP Sample Code Documentation
 * for requirements and guidelines on how to use the sample code.
 *
 * "MerchantCalculationsAPIFunctions.php" contains a set of functions that
 * process <merchant-calculation-callback> message 
 * and build <merchant-calculation-result> message.
 */


/**
 * The ProcessMerchantCalculationCallback function handles a 
 * <merchant-calculation-callback> request and returns a 
 * <merchant-calculation-results> XML response. This function calls 
 * the CreateMerchantCalculationResults function, which constructs 
 * the <merchant-calculation-results> response. This function then 
 * prints the <merchant-calculation-results> response to return the 
 * <merchant-calculation-results> information to Google Checkout and logs the 
 * response as well.
 * 
 * @param  $dom_mc_callback_obj      <merchant-calculation-callback> XML
 */
function ProcessMerchantCalculationCallback($dom_mc_callback_obj) {

    /*
     * Process <merchant-calculation-callback> and create 
     * <merchant-calculation-results>
     */
    $xml_mc_results = CreateMerchantCalculationResults($dom_mc_callback_obj);

    // Respond with <merchant-calculation-results> XML
    echo $xml_mc_results;
    // Log <merchant-calculation-results>
    LogMessage($GLOBALS["logfile"], $xml_mc_results);
}

/**
 * The CreateMerchantCalculationResults function creates the XML DOM for 
 * a <merchant-calculation-results> XML response. This function receives 
 * the <merchant-calculation-callback> from the 
 * ProcessMerchantCalculationCallback function.
 *
 * This function calls the CreateMerchantCodeResults, GetShippingRate 
 * and GetTaxRate functions to calculate shipping costs, taxes and 
 * discounts that should be applied to the order total
 *
 * @param   $dom_mc_callback_obj   <merchant-calculation-callback> XML DOM
 * @return  <merchant-calculation-results> XML
 */
function CreateMerchantCalculationResults($dom_mc_callback_obj) {
  global $country_code, $post_code, $state_code, $quotes, $free_shipping, $shipping_available, $shipping_array, $orders_total;
  global $google_countries_id, $google_zone_id, $shipping_tax_array;
  global $total_tax;

  global $cart;
  if ( !is_object($cart) || count($cart->contents)==0 ) {
    $sess_cart = $dom_mc_callback_obj->get_elements_by_tagname("sess_cart");
    $sess_cart = $sess_cart[0]->get_content();
    $saved_cart = unserialize( base64_decode($sess_cart) );
    if ( $saved_cart!==false ) $cart = $saved_cart;
    $cart->calculate();
  }

    // Create an empty XMLDOM
    $dom_mc_results_obj = domxml_new_doc("1.0");

    // Create root tag for <merchant-calculation-results> response
    $dom_mc_results = $dom_mc_results_obj->append_child(
        $dom_mc_results_obj->
            create_element("merchant-calculation-results"));
    $dom_mc_results->set_attribute("xmlns", $GLOBALS["schema_url"]);

    // Create child element <results>
    $dom_results = 
        $dom_mc_results->append_child(
            $dom_mc_results_obj->create_element("results"));

    $dom_data = $dom_mc_callback_obj->document_element();

    /*
     * Retrieve Boolean value indicating whether merchant calculates
     * tax for the order.
     *     e.g. <tax>true</tax>
     * If you do not use custom calculations to calculate tax, you 
     * may ignore the next two lines of code.
     */
    $dom_tax_list = $dom_data->get_elements_by_tagname("tax");
    $calc_tax = $dom_tax_list[0]->get_content();

    /*
     * Retrieve the names of the shipping methods available for the
     * order. These shipping methods will have been communicated to Google 
     * Checkout in a CheckoutAPIRequest. Note: The 
     * <merchant-calculated-callback> will only contain 
     * <merchant-calculated-shipping> options from the Checkout API request.
     */
    $dom_method_list = $dom_data->get_elements_by_tagname("method");

    /*
     * Retrieve shipping addresses from the <merchant-calculated-callback>
     * response. These shipping addresses are anonymous, meaning they 
     * only include the city, region (state), postal code and country
     * code for the address
     */
    $dom_anonymous_address_list = 
        $dom_data->get_elements_by_tagname("anonymous-address");

    /*
     * Retrieve a list of coupon and gift certificate codes that 
     * should be applied to the order total. Note: The
     * <merchant-calculated-callback> can only contain these codes if
     * the <accept-merchant-coupons> or <accept-gift-certificates> tag
     * in the corresponding Checkout API request has a value of "true".
     */
    $dom_merchant_code_list = 
        $dom_data->get_elements_by_tagname("merchant-code-string");

    // Loop through address IDs to build <result> elements
    foreach ($dom_anonymous_address_list as $dom_anonymous_address) {

        // Retrieve the address ID
        $address_id = $dom_anonymous_address->get_attribute("id");
        $country_node = $dom_anonymous_address->get_elements_by_tagname("country-code");
        $country_code = $country_node[0]->get_content();
        $post_code_node = $dom_anonymous_address->get_elements_by_tagname("postal-code");
        $post_code = $post_code_node[0]->get_content();
        $state_code_node = $dom_anonymous_address->get_elements_by_tagname("region");
        $state_code = $state_code_node[0]->get_content();
        // 
        InitOscShippings();
        /*
         * If there are merchant-calculated shipping methods, build 
         * a <result> element for each shipping method-address ID 
         * combination. If there are no shipping methods, build a
         * <result> element that contains calculations for each address ID.
         */
        if (sizeof($dom_method_list) > 0) {

            // Loop for each merchant-calulated shipping method
            foreach ($dom_method_list as $dom_method) {

                // Retrieve the name of the shipping method
                $shipping_name = $dom_method->get_attribute("name");
//echo $shipping_name;
                /*
                 * Create a <result> element in the response with
                 * shipping-name and address-id attributes
                 */
                $dom_result = $dom_results->append_child($dom_mc_results_obj->create_element("result"));
                $dom_result->set_attribute("shipping-name", $shipping_name);
                $dom_result->set_attribute("address-id", $address_id);

                $total_tax = GetTaxRate($dom_mc_callback_obj, $address_id);// + $shipping_tax_array[$shipping_name];
_mydump( '$total_tax='. var_export($total_tax, true) );                
                /*
                 * If there are coupon or gift certificate codes, call
                 * the CreateMerchantCodeResults function to verify those
                 * codes and to create <coupon-result> or 
                 * <gift-certificate-result> elements to be included in
                 * the <merchant-calculation-response>.
                 */
                if (sizeof($dom_merchant_code_list) > 0) {
//
                    global $order;
                    $order->info['shipping_cost'] = GetShippingRate($dom_mc_callback_obj, $address_id, $shipping_name);
                    $order->info['tax'] = $total_tax;
// TODO 'NYS Sales Tax' -- retrive correct group
                    $order->info['tax_groups'] = array('Taxable Goods'=>$total_tax/*-$shipping_tax_array[$shipping_name]*/);

  if ((DISPLAY_PRICE_WITH_TAX == 'true')) {
    $order->info['total'] = $order->info['subtotal'] + $order->info['shipping_cost'];
  } else {
    $order->info['total'] = $order->info['subtotal'] + $order->info['tax'] + $order->info['shipping_cost'];
  }
//                 
                    $dom_merchant_code_results = 
                        CreateMerchantCodeResults($dom_mc_callback_obj, 
                            $dom_merchant_code_list, $address_id);
                    $dom_merchant_code_results_root = 
                        $dom_merchant_code_results->document_element();
                    $dom_result->append_child(
                        $dom_merchant_code_results_root->clone_node(true));
                }

                /*
                 * If the <tax> tag in the <merchant-calculation-callback>
                 * has a value of "true", call the GetTaxRate function
                 * to calculate taxes for the order.
                 */
                
                if ($calc_tax == "true") {
                    $dom_total_tax = $dom_result->append_child(
                        $dom_mc_results_obj->create_element("total-tax"));
                    $dom_total_tax->set_attribute(
                        "currency", $GLOBALS["currency"]);
                    //$total_tax = GetTaxRate($dom_mc_callback_obj, $address_id) + $shipping_tax_array[$shipping_name];
                    $total_tax+=$shipping_tax_array[$shipping_name];
                    $total_tax = number_format($total_tax,2,'.','');
                    $dom_total_tax->append_child(
                        $dom_mc_results_obj->create_text_node($total_tax));
                }

                /*
                 * Call the GetShippingRate function to calculate the
                 * shipping cost for the shipping method-address ID 
                 * combination.
                 */
                $dom_shipping_rate = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("shipping-rate"));
                $dom_shipping_rate->set_attribute(
                    "currency", $GLOBALS["currency"]);
                $shipping_rate = GetShippingRate($dom_mc_callback_obj, $address_id, $shipping_name);
                $dom_shipping_rate->append_child(
                    $dom_mc_results_obj->create_text_node($shipping_rate));

                // Verify that the order can be shipped to the address
                $shippable = 
                    VerifyShippable($dom_mc_callback_obj, $address_id, $shipping_name);
                $dom_shippable = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("shippable"));
                $dom_shippable->append_child(
                    $dom_mc_results_obj->create_text_node($shippable));
            }

        // This block executes if no shipping methods are specified
        } else {

            /*
             * Create a <result> element in the response with
             * address-id attribute
             */
            $dom_result = $dom_results->append_child(
                $dom_mc_results_obj->create_element("result"));
            $dom_result->set_attribute("address-id", $address_id);

            /*
             * If the <tax> tag in the <merchant-calculation-callback>
             * has a value of "true", call the GetTaxRate function
             * to calculate taxes for the order.
             */
            if ($calc_tax == "true") {
                $dom_total_tax = $dom_result->append_child(
                    $dom_mc_results_obj->create_element("total-tax"));
                $dom_total_tax->set_attribute(
                    "currency", $GLOBALS["currency"]);
                $total_tax = GetTaxRate($dom_mc_callback_obj, $address_id);
                $dom_total_tax->append_child(
                    $dom_mc_results_obj->create_text_node($total_tax));
            }

            /*
             * If there are coupon or gift certificate codes, call
             * the CreateMerchantCodeResults function to verify those
             * codes and to create <coupon-result> or 
             * <gift-certificate-result> elements to be included in
             * the <merchant-calculation-response>.
             */
            if (sizeof($dom_merchant_code_list) > 0) {

                $dom_merchant_code_results = 
                    CreateMerchantCodeResults($dom_mc_callback_obj, 
                        $dom_merchant_code_list, $address_id);

                $dom_merchant_code_results_root = 
                    $dom_merchant_code_results->document_element();
                $dom_result->append_child(
                    $dom_merchant_code_results_root->clone_node(true));
            }
        }
    }
    // Return <merchant-calculation-results> XML
    return $dom_mc_results_obj->dump_mem();
}

/**
 * The CreateMerchantCodeResults function creates the <merchant-code-results>
 * XML DOM for a Merchant Calculations API response. This function calls 
 * the GetMerchantCodeInfo function, which you will need to modify, to 
 * retrieve information about each coupon or gift certificate code.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $dom_merchant_code_list    
 *                                  collection of merchant-code-string codes
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @return  $dom_mc_results_obj     <merchant-code-results> XMLDOM
 */
function CreateMerchantCodeResults($dom_mc_callback_obj, 
    $dom_merchant_code_list, $address_id) {

    // Create an empty XMLDOM
    $dom_code_results_obj = domxml_new_doc("1.0");
    $dom_merchant_code_results = $dom_code_results_obj->append_child(
        $dom_code_results_obj->create_element("merchant-code-results"));
    
    foreach ($dom_merchant_code_list as $dom_merchant_code) {
        
        $code = $dom_merchant_code->get_attribute("code");

        $dom_merchant_code_result_obj = 
            GetMerchantCodeInfo($dom_mc_callback_obj, $code, $address_id);

        $dom_merchant_code_result_root = 
            $dom_merchant_code_result_obj->document_element();
        $dom_merchant_code_results->append_child(
            $dom_merchant_code_result_root->clone_node(true));
    }
    //clean up
    if (tep_session_is_registered('cc_id')) tep_session_unregister('cc_id');
    return $dom_code_results_obj;
}

/**
 * The GetMerchantCodeInfo function retrieves information about a coupon 
 * or gift certificate code provided by the customer. You will need to 
 * modify this function to retrieve information about the code. The 
 * changes you will need to make are discussed in the comments in the 
 * function. After retrieving this information, this function calls and 
 * returns the value of the CreateMerchantCodeResult function.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $code                   A coupon or gift certificate code.
 * @param   $address_id             An ID the corresponds to the address 
 *                                      to which an order should be shipped.
 * @return  merchant-calculated shipping rate
 */
function GetMerchantCodeInfo($dom_mc_callback_obj, $code, $address_id) {
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to retrieve information about
     * a coupon or gift certificate code provided by the customer. This
     * function needs to retrieve the following information about the code:
     *     1. The code's type. The code type may be either "coupon" or
     *         "gift-certificate". 
     *     2. A flag that indicates whether the code is valid. The value
     *         of this flag must be either "true" or "false".
     *     3. The calculated amount of the code. If the code is valid,
     *         you need to quantify the amount of the code discount.
     *         This data is optional.
     *     4. A message that should be displayed with the code. This
     *         data is optional.
     * This function returns the result from the CreateMerchantCodeResult 
     * function, which is a <coupon-result> or a <gift-certificate-result>, 
     * to the CreateMerchantCodeResults function, which adds the XML 
     * block to the response.
     */
    global $HTTP_POST_VARS, $orders_total, $order,$cart, $_SESSION,$ot_coupon,$total_tax;
    if (!is_object($ot_coupon)) {
      require_once(DIR_WS_CLASSES . 'order_total.php');
      $orders_total = new order_total;
    }     
_mydump( var_export($orders_total, true) );
    $code = trim($code);    
    //if ( $code!='' && defined('MODULE_ORDER_TOTAL_INSTALLED') && strpos(MODULE_ORDER_TOTAL_INSTALLED,'ot_coupon')!==false ) {
    if ( $code!='' && $ot_coupon->enabled=='true' ) {
      global $last_copon_code;
      if (tep_not_null($last_copon_code) && $last_copon_code!=$code) {
        $code_type = "coupon"; // ??? def values
        $valid = "false";
        $calculated_amount = '0.00';
        $message = "Only one coupon allowed";      
      }else{
        $last_copon_code = $code;

        $save_shipping = $order->info['shipping_cost'];
  	    $save_tax_group = $order->info['tax_groups']; 
  	    $save_total = $order->info['total'];
  	    $save_tax = $order->info['tax'];
        
        $result = $ot_coupon->gc_calc($code);

        $total_tax = $order->info['tax'];
        $order->info['shipping_cost'] = $save_shipping;
  		  $order->info['tax_groups'] = $save_tax_group;
  		  $order->info['total'] = $save_total;
  		  $order->info['tax'] = $save_tax;

        $code_type = $result['type'];
        $valid = $result['valid'];
        $calculated_amount = $result['value'];
        $message = $result['message'];
      }      
    }else{
      $code_type = "coupon"; // ??? def values
      $valid = "false";
      $calculated_amount = "0.00";
      $message = "";      
    }

    return CreateMerchantCodeResult($code_type, $valid, $code, 
        $calculated_amount, $message);
}

/**
 * The CreateMerchantCodeResult function creates the XML DOM for a
 * <coupon-result> or <gift-certificate-result> in a Merchant
 * Calculations API response.
 *
 * @param    $code_type            The type of code provided by the
 *                                     customer. Valid values are "coupon"
 *                                     and "gift-certificate".
 * @param    $valid                Indicates whether the code is valid.
 *                                     Valid values are "true" and "false".
 * @param    $code                 The code entered by the user
 * @param    $calculated_amount    The amount to deduct from the total
 * @param    $message              A message to display in regard to the code.
 * @return   $dom_code_result_obj  <coupon-result> or 
 *                                     <gift-certificate-result> XML DOM
 */
function CreateMerchantCodeResult($code_type, $valid, $code,
    $calculated_amount="", $message="") {

    // Create an empty XMLDOM
    $dom_code_result_obj = domxml_new_doc("1.0");

    // Create root tag for <coupon-result> or <gift-certificate-result>
    $dom_merchant_code_result = $dom_code_result_obj->append_child(
        $dom_code_result_obj->create_element($code_type . "-result"));

    // Create <valid> tag, which will indicate whether the code is valid
    $dom_valid = $dom_merchant_code_result->append_child(
        $dom_code_result_obj->create_element("valid"));

    // Add value for <valid> tag
    $dom_valid->append_child($dom_code_result_obj->create_text_node($valid));

    // Add the coupon or gift certificate code in a <code> tag
    $dom_code = $dom_merchant_code_result->append_child(
        $dom_code_result_obj->create_element("code"));
    $dom_code->append_child($dom_code_result_obj->create_text_node($code));

    /*
     * Add the <calculated-amount> tag if there is a value for the
     * $calculated_amount parameter. You could omit this tag if the
     * code is invalid.
     */
    if ($calculated_amount) {
        $dom_calculated_amount = $dom_merchant_code_result->append_child(
            $dom_code_result_obj->create_element("calculated-amount"));
        $dom_calculated_amount->set_attribute(
            "currency", $GLOBALS["currency"]);
        $dom_calculated_amount->append_child(
            $dom_code_result_obj->create_text_node($calculated_amount));
    }

    // Add a <message> tag if the $message parameter has a value
    if ($message) {
        $dom_message = $dom_merchant_code_result->append_child(
            $dom_code_result_obj->create_element("message"));
        $dom_message->append_child(
            $dom_code_result_obj->create_text_node($message));
    }

    return $dom_code_result_obj;
}


/**
 * The VerifyShippable function determines whether an order can be 
 * shipped to the specified address using the specified shipping method. 
 * You will need to modify this function to return a Boolean value 
 * indicating whether the order is shippable using the given shipping method.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XML DOM
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @param   $shipping_method        A shipping option for an order
 * @return  Boolean value indicating whether items can be shipped to
 *          specified address
 */
function VerifyShippable($dom_mc_callback_obj, $address_id, $shipping_method) {
  global $shipping_available, $shipping_array;
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to return a Boolean (true/false)
     * value that indicates whether the order can be shipped to the 
     * specified address ($address_id) using the specified shipping
     * method ($shipping_method).
     */
    //print_r($shipping_array);
    if($shipping_available && isset($shipping_array[$shipping_method]))
    {
      return 'true';
    }
    return 'false';
}


/**
 * The <b>GetShippingRate</b> function determines the cost of shipping 
 * the order to the specified address using the specified shipping method. 
 * You will need to modify this function to calculate and return this cost.
 *
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XMLDOM
 * @param   $address_id             An ID the corresponds to the address 
 *                                      to which an order should be shipped.
 * @param   $shipping_method        A shipping option for an order
 * @return  merchant-calculated shipping rate
 */
function GetShippingRate($dom_mc_callback_obj, $address_id, $shipping_method) {
  global $shipping_array;
    /*
     * +++ CHANGE ME +++
     * You need to modify this function to return the cost of 
     * shipping an order to the specified address using the specified
     * shipping method. 
     */
//     print_r($shipping_array);
    if(isset($shipping_array[$shipping_method]))
    {
      return $shipping_array[$shipping_method];
    }
    return '0.00';
}

/*
  Function init OSC shippings
*/
function _mydump($text){
return;
    $log = DIR_FS_CATALOG."/temp/oscship.log"; 
    if (!$log_file = fopen($log, "a")) {
        $errstr = "Cannot open '" . $log . "' file.";
        trigger_error($errstr, E_USER_ERROR);
    }
    fwrite($log_file, sprintf("\r\n\r\n%s", date("r", time())));
    fwrite($log_file, sprintf("\r\n%s", $text));
    fclose($log_file);
} 

function InitOscShippings()
{
  global $country_code, $post_code, $state_code, $quotes, $free_shipping, $shipping_available, $cart, $order, $total_count, $shipping_weight;
  global $shipping_array, $shipping_tax_array, $orders_total, $google_countries_id, $google_zone_id, $total_weight, $total_count;
  global $shipping_to_class;
_mydump( var_export($country_code, true) );  
  $free_shipping = false;
  $shipping_available = false;
  if ( defined('GOOGLE_FORCE_TO_USA') ) { $country_code='GB'; }

  $country_q = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_iso_code_2='" . tep_db_input($country_code) . "'");
  if ($country_r = tep_db_fetch_array($country_q)) {
    $state_id = tep_db_fetch_array(tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id='" . $country_r['countries_id'] . "' and zone_code='" . tep_db_input($state_code) . "'"));
//    $state_id = tep_db_fetch_array(tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id='" . $country_r['countries_id'] . "'"));
    // for order...
_mydump( var_export($state_id, true) );
    $google_countries_id = $country_r['countries_id'];
    $google_zone_id = $state_id['zone_id'];
    require_once(DIR_WS_CLASSES . 'order.php');
    $order = new order;
    $order->delivery['zone_id'] = $state_id['zone_id'];
    $order->delivery['postcode'] = $post_code;
    $order->delivery['country'] = Array(
                    'id' => $country_r['countries_id'],
                    'title' => $country_r['countries_name'],
                    'iso_code_2' => $country_r['countries_iso_code_2'],
                    'iso_code_3' =>  $country_r['countries_iso_code_3']);
    $order->delivery['country_id'] = $country_r['countries_id'];
  }
  else
  {
    return false;
  }
  $total_weight = $cart->show_weight();
//  echo QWEQWEQ . ' ' . $total_weight . ' QWEWQEQWE';
  $total_count = $cart->count_contents();
_mydump( var_export($order, true) );
  // load all enabled shipping modules
  require_once(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
  
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;

    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }

    $free_shipping = false;
    // bred ???
    //if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
    //if ( ($pass == true) && ($cart->show_total() >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
    //  $free_shipping = true;
    //  require_once(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php'); // ??? ask Vlad
    //}
  } else {
    $free_shipping = false;
  }
  // get all available shipping quotes
  $quotes = $shipping_modules->quote();    
_mydump( 'quotes='. var_export($quotes, true) );
  //print_r($quotes);
  if(is_array($quotes) && (sizeof($quotes) > 0))
  {
    $shipping_available = true;
    // put all shipping options into simpler data structure
    $shipping_array = array();
    $shipping_tax_array = array();
    for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
      if($quotes[$i]['id'] == 'freeshipper') continue;
      /*if($quotes[$i]['id'] == 'table')
      {
        for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
          $shipping_array[$zone_name . $quotes[$i]['methods'][$j]['title']] = number_format($quotes[$i]['methods'][$j]['cost'], 2, '.', '');
          $shipping_tax_array[$zone_name . $quotes[$i]['methods'][$j]['title']] = number_format($quotes[$i]['methods'][$j]['cost'] * ($quotes[$i]['tax']/100), 2, '.', '');
        }
      }
      else
      {*/
        for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
          $shipping_array[strip_tags($quotes[$i]['module'].' ('.$quotes[$i]['methods'][$j]['title'].')')] = number_format($quotes[$i]['methods'][$j]['cost'], 2, '.', '');
          $shipping_tax_array[strip_tags($quotes[$i]['module'].' ('.$quotes[$i]['methods'][$j]['title'].')')] = number_format($quotes[$i]['methods'][$j]['cost'] * ($quotes[$i]['tax']/100), 2, '.', '');
          $shipping_to_class[strip_tags($quotes[$i]['module'].' ('.$quotes[$i]['methods'][$j]['title'].')')] = array(
            'id' => $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'],
            'class' => $quotes[$i]['id'],
            'method' => $quotes[$i]['methods'][$j]['id'],
            'title' => strip_tags($quotes[$i]['module'].' ('.$quotes[$i]['methods'][$j]['title'].')'),
            'cost' => $shipping_array[strip_tags($quotes[$i]['methods'][$j]['title'])]
          );
        }
      //}
    }
  }
  if (false && MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') {  
    $free_shipping_title = strip_tags(MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE.' (' . MODULE_SHIPPING_FREESHIPPER_TEXT_WAY.')');
    if(($cart->show_total() >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)){
      $shipping_array[$free_shipping_title] = 0;
      $shipping_tax_array[$free_shipping_title] = 0;
    }
  }
  
_mydump( '$shipping_array='. var_export($shipping_array, true) );  
}

/**
 * The GetTaxRate function returns the total tax that should be applied to
 * the order if it is shipped to the specified address. You will need to
 * modify this function to return the calculated tax amount.
 * 
 * @param   $dom_mc_callback_obj    <merchant-calculation-callback> XMLDOM
 * @param   $address_id             An ID the corresponds to the address
 *                                      to which an order should be shipped.
 * @return  merchant-calculated total tax
 */
function GetTaxRate($dom_mc_callback_obj, $address_id) {
  global $google_countries_id, $google_zone_id, $cart;
  $tax = 0;
  $price = 0;
  // get default tax
  //$tax = tep_db_fetch_array(tep_db_query("select tax_rate from " . TABLE_TAX_RATES . " limit 1"));
  //$tax_rate = tep_get_tax_rate(1, $google_countries_id, $google_zone_id);
  //$price = $cart->show_total();
  //$tax = tep_calculate_tax($price, $tax_rate);
  $products = $cart->get_products();
_mydump( '$session='. var_export($_SESSION, true) );  
_mydump( '$session='. var_export($HTTP_SESSION_VARS, true) );
_mydump( '$products='. var_export($products, true) );
  foreach($products as $product)
  {
_mydump( '$TAX_RATE='. var_export(tep_get_tax_rate($product['tax_class_id'], $google_countries_id, $google_zone_id), true) );

    //$tax+= tep_calculate_tax($product['price'] * $product['quantity'], tep_get_tax_rate($product['tax_class_id'], $google_countries_id, $google_zone_id));
    $tax_rate = tep_get_tax_rate($product['tax_class_id'], $google_countries_id, $google_zone_id);
    $tax+= $product['final_price'] * $product['quantity']*$tax_rate/100;    
  }
  return number_format($tax, 4, '.', '');
}

?>