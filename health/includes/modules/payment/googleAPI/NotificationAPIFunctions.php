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
 * "NotificationAPIFunctions.php" is a client library of functions
 * that enable merchants to systematically handle Google Checkout
 * notifications using the Notification API. You will need to modify these
 * functions to take the appropriate actions when you receive notifications.
 *
 */

/******** Functions for processing asynchronous notification messages *********/

/**
 * The ProcessNewOrderNotification function is a shell function for
 * handling a <new-order-notification>. You will need to modify this
 * function to transfer the information contained in a
 * <new-order-notification> to your internal systems that process that data.
 *
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessNewOrderNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * New order notifications inform you of new orders that have
     * been submitted through Google Checkout. A <new-order-notification>
     * message contains a list of the items in an order, the tax
     * assessed on the order, the shipping method selected for the
     * order and the shipping address for the order.
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <new-order-notification> to your internal systems that
     * process this order data.
     */
    // Get Google order ID and customer email from XML response
    $dom_data_root = $dom_response_obj->document_element();
    $google_order_number = $dom_data_root->get_elements_by_tagname("google-order-number");
    $number = $google_order_number[0]->get_content();
    $shop_order_id = $dom_data_root->get_elements_by_tagname("order-id");
    $order_id = $shop_order_id[0]->get_content();
    //echo PROCESS . "\n";
    //print_r($order_id);
    if($order_id > 0)
    {
      // Update order by Google order ID
      tep_db_query("update " . TABLE_ORDERS . " set google_orders_id='" . $number . "' where orders_id='" . $order_id . "'");
    }
    else
    {
      CreateNewOrder($dom_response_obj);
    }
    SendNotificationAcknowledgment();
}

/*
  Create new OSC order
*/

function CreateNewOrder($dom_response_obj)
{
  global $insert_id, $customer_id, $cart, $sendto, $billto, $payment, $shipping, $order, $cc_id, $shipping_to_class, $languages_id, $currencies;
  $payment = 'googlecheckout';
  // Init all data
  $dom_data_root = $dom_response_obj->document_element();
  $gift_adjustment = $dom_data_root->get_elements_by_tagname("gift-certificate-adjustment");
  $coupon_adjustment = $dom_data_root->get_elements_by_tagname("coupon-adjustment");
  if ( count($coupon_adjustment)==0 ) $coupon_adjustment = $gift_adjustment;
  // coupons type "free shipping" pass as gift-certificate - GC feature - coupons apply before shipping & tax, gift - after
  // 2007.03.26 - coupons have type gift-certificate in any case. Module not allow enter gv used only coupons
  if ( count($coupon_adjustment)>0 ) {
    $code_node = $coupon_adjustment[0]->get_elements_by_tagname("code");
    $coupon_code = $code_node[0]->get_content();
           $coupon_query=tep_db_query("select coupon_id from " . TABLE_COUPONS . " where coupon_type<>'G' AND coupon_code='".tep_db_input($coupon_code)."' and coupon_active='Y'");
           if ( tep_db_num_rows($coupon_query) ) {
             $coupon_result=tep_db_fetch_array($coupon_query);
             if (!tep_session_is_registered('cc_id')) tep_session_register('cc_id');
      $cc_id = $coupon_result['coupon_id'];
    }
  }
  //
  $shipping_data = $dom_data_root->get_elements_by_tagname("shipping-name");
  $xml_shipping = $shipping['title'] = $shipping_data[0]->get_content();
  $shipping_data = $dom_data_root->get_elements_by_tagname("shipping-cost");
  $shipping['cost'] = $shipping_data[0]->get_content();
  $totaltax_data = $dom_data_root->get_elements_by_tagname("total-tax");
  $xml_tax = $totaltax_data[0]->get_content();
/*
$shipping['title'] $shipping['cost'] for correct new order creation
*/

  // get customers data and check them
  $ship = array();
  $customer_data = $dom_data_root->get_elements_by_tagname("buyer-shipping-address");
  $shipping_root = $customer_data[0];

  $data = $customer_data[0]->get_elements_by_tagname("email");
  $ship['email'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("address1");
  $ship['address1'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("address2");
  $ship['address2'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("company-name");
  $ship['company-name'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("contact-name");
  $ship['contact-name'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("phone");
  $ship['phone'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("fax");
  $ship['fax'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("country-code");
  $ship['country-code'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("city");
  $ship['city'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("region");
  $ship['region'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("postal-code");
  $ship['postal-code'] = $data[0]->get_content();
  $billing = array();
  unset($customer_data);
  $customer_data = $dom_data_root->get_elements_by_tagname("buyer-billing-address");
  $billing_root = $customer_data[0];

  $data = $customer_data[0]->get_elements_by_tagname("email");
  $billing['email'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("address1");
  $billing['address1'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("address2");
  $billing['address2'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("company-name");
  $billing['company-name'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("contact-name");
  $billing['contact-name'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("phone");
  $billing['phone'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("fax");
  $billing['fax'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("country-code");
  $billing['country-code'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("city");
  $billing['city'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("region");
  $billing['region'] = $data[0]->get_content();
  $data = $customer_data[0]->get_elements_by_tagname("postal-code");
  $billing['postal-code'] = $data[0]->get_content();
  // check customer
if (defined('GOOGLE_FORCE_TO_USA')) {
  $ship['country-code'] = 'GB';
  $billing['country-code'] = 'GB';
}
  $check = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address='" . $ship['email'] . "' or customers_email_address='" . $billing['email'] . "'");
  list($firstname_ship, $lastname_ship) = split(' ', $ship['contact-name'], 2);
  // get country id and state id for shipping
  $country = tep_db_fetch_array(tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where countries_iso_code_2='" . $ship['country-code'] . "'"));
  $state = tep_db_fetch_array(tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id='" . $country['countries_id'] . "' and  zone_code='" . $ship['region'] . "'"));
  $ship['countries_id'] = $country['countries_id'];
  $ship['zone_id'] = (int)$state['zone_id'];
  // get country id and state id for billing
  $country = tep_db_fetch_array(tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where countries_iso_code_2='" . $billing['country-code'] . "'"));
  $state = tep_db_fetch_array(tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id='" . $country['countries_id'] . "' and  zone_code='" . $billing['region'] . "'"));
  $billing['countries_id'] = $country['countries_id'];
  $billing['zone_id'] = (int)$state['zone_id'];

  list($firstname_bill, $lastname_bill) = split(' ', $billing['contact-name'], 2);
  if(tep_db_num_rows($check) > 0) {
    $customer_id = tep_db_fetch_array($check);
    $customer_id = $customer_id['customers_id'];
    // check addresses and init $billto and $sendto
    $check_sendto = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where customers_id='" . (int)$customer_id . "' and entry_company='" . tep_db_input($ship['company-name']) . "' and entry_firstname='" . tep_db_input($firstname_ship) . "' and entry_lastname='" . tep_db_input($lastname_ship) . "' and entry_street_address='" . tep_db_input($ship['address1']) . ' ' . tep_db_input($ship['address2']) . "' and entry_postcode='" . tep_db_input($ship['postal-code']) . "' and entry_city='" . tep_db_input($ship['city']) . "' and entry_state='" . tep_db_input($ship['region']) . "' and entry_zone_id='" . intval($ship['zone_id']) . "' and entry_country_id='" . intval($ship['countries_id']) . "'");
    if(tep_db_num_rows($check_sendto) > 0) {
      $sendto = tep_db_fetch_array($check_sendto);
      $sendto = $sendto['address_book_id'];
    } else {
      // insert new shipping address
      $sql_data_array = array('customers_id' => (int)$customer_id,
                              'entry_firstname' => $firstname_ship,
                              'entry_lastname' => $lastname_ship,
                              'entry_street_address' => $ship['address1'] . ' ' . $ship['address2'],
                              'entry_city' => $ship['city'],
                              'entry_postcode' => $ship['postal-code'],
                              'entry_company' => $ship['company-name'],
                              'entry_state' => $ship['region'],
                              'entry_zone_id' => (int)$ship['zone_id'],
                              'entry_country_id' => (int)$ship['countries_id']);
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
      $sendto = tep_db_insert_id();
    }
    $check_billto = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where customers_id='" . (int)$customer_id . "' and entry_company='" . tep_db_input($billing['company-name']) . "' and entry_firstname='" . tep_db_input($firstname_bill) . "' and entry_lastname='" . tep_db_input($lastname_bill) . "' and entry_street_address='" . tep_db_input($billing['address1']) . ' ' . tep_db_input($billing['address2']) . "' and entry_postcode='" . tep_db_input($billing['postal-code']) . "' and entry_city='" . tep_db_input($billing['city']) . "' and entry_state='" . tep_db_input($billing['region']) . "' and entry_zone_id='" . (int)$billing['zone_id'] . "' and entry_country_id='" . (int)$billing['countries_id'] . "'");
    if(tep_db_num_rows($check_billto) > 0) {
      $billto = tep_db_fetch_array($check_billto);
      $billto = $billto['address_book_id'];
    } else {
      $sql_data_array = array('customers_id' => (int)$customer_id,
                              'entry_firstname' => $firstname_bill,
                              'entry_lastname' => $lastname_bill,
                              'entry_street_address' => $billing['address1'] . ' ' . $billing['address2'],
                              'entry_city' => $billing['city'],
                              'entry_postcode' => $billing['postal-code'],
                              'entry_company' => $billing['company-name'],
                              'entry_state' => $billing['region'],
                              'entry_zone_id' => (int)$billing['zone_id'],
                              'entry_country_id' => (int)$billing['countries_id']);
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
      $billto = tep_db_insert_id();
    }
  } else { // create new customer
    $sql_data_array = array('customers_firstname' => $firstname_ship,
                            'customers_lastname' => $lastname_ship,
                            'customers_email_address' => $ship['email'],
                            'customers_telephone' => $ship['phone'],
                            'customers_fax' => $ship['fax']);
    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
    $customer_id = tep_db_insert_id();
    // insert new shipping address
    $sql_data_array = array('customers_id' => $customer_id,
                            'entry_firstname' => $firstname_ship,
                            'entry_lastname' => $lastname_ship,
                            'entry_street_address' => $ship['address1'] . ' ' . $ship['address2'],
                            'entry_city' => $ship['city'],
                            'entry_postcode' => $ship['postal-code'],
                            'entry_company' => $ship['company-name'],
                            'entry_state' => $ship['region'],
                            'entry_zone_id' => $ship['zone_id'],
                            'entry_country_id' => $ship['countries_id']);
    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
    $address_id = tep_db_insert_id();
    $sendto = $address_id;
    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . $address_id . "' where customers_id = '" . $customer_id . "'");
    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");
    // insert new billing address
    $sql_data_array = array('customers_id' => $customer_id,
                            'entry_firstname' => $firstname_bill,
                            'entry_lastname' => $lastname_bill,
                            'entry_street_address' => $billing['address1'] . ' ' . $billing['address2'],
                            'entry_city' => $billing['city'],
                            'entry_postcode' => $billing['postal-code'],
                            'entry_company' => $billing['company-name'],
                            'entry_state' => $billing['region'],
                            'entry_zone_id' => $billing['zone_id'],
                            'entry_country_id' => $billing['countries_id']);
    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
    $address_id = tep_db_insert_id();
    $billto = $address_id;
  }
/* start search engines statistics */
   $search_engines_id = 0;
   $search_words_id = 0;
   $affiliate_ref = 0;
   $search_engines_id_dom = $dom_data_root->get_elements_by_tagname("search_engines_id");
   if ( is_array($search_engines_id_dom) && isset($search_engines_id_dom[0]) && is_object($search_engines_id_dom[0]) ) $search_engines_id = intval($search_engines_id_dom[0]->get_content());
   
   $search_words_id_dom = $dom_data_root->get_elements_by_tagname("search_words_id");
   if ( is_array($search_words_id_dom) && isset($search_words_id_dom[0]) && is_object($search_words_id_dom[0]) ) $search_words_id = intval($search_words_id_dom[0]->get_content());

   $affiliate_ref_dom = $dom_data_root->get_elements_by_tagname("affiliate_ref");
   if ( is_array($affiliate_ref_dom) && isset($affiliate_ref_dom[0]) && is_object($affiliate_ref_dom[0]) ) $affiliate_ref = intval($affiliate_ref_dom[0]->get_content());

/* end search engines statistics*/

  // create order
  $google_order_number = $dom_data_root->get_elements_by_tagname("google-order-number");
  $number = $google_order_number[0]->get_content();
  // recreate shopping cart
  $sess_cart = $dom_data_root->get_elements_by_tagname("sess_cart");
  $sess_cart = $sess_cart[0]->get_content();
  $saved_cart = unserialize( base64_decode($sess_cart) );
  if ( $saved_cart!==false ) {
    $cart = new shoppingCart;
    $cart = $saved_cart;
  }else{
    $items_list = $dom_data_root->get_elements_by_tagname("osc-item");
    $currencies = new currencies();
    $cart = new shoppingCart;
    $post_process = array();
    foreach($items_list as $item)
    {
      $uprid = $item->get_attribute('item_id');
      $qty = $item->get_content();
      if ( preg_match('/^ga/',$uprid) ) {
        $post_process[] = $uprid;
      }elseif(preg_match('/^(\d+)\{used_(\d+)\}/',$uprid,$usedm)) {
        $u_pid = $usedm[1];
        $u_uid = $usedm[2];
        $cart->add_used($u_uid, $qty);
      }else{
        $attr = get_attributes($uprid);
        $cart->add_cart($uprid, $qty, $attr);
      }
    }
    foreach($post_process as $give_away) {
      $give = explode('|',$give_away);
      if ( count($give)==3 ) {
        $attr = get_attributes($give[1]);
        $cart->add_giveaway( $give[2], (int)$give[1], $attr );
      }
    }
  }
  $cart->calculate();

//
global $country_code, $state_code;

$country_code = $ship['country-code'];
$state_code = $ship['region'];
$shipping_ = $shipping;
unset($shipping);
InitOscShippings();
if (isset($shipping_to_class[$xml_shipping])){
  $shipping = $shipping_to_class[$xml_shipping];
  $GLOBALS['shipping']['id'] = $shipping['id'];
}else{
  $shipping = $shipping_;
}
//

  require_once(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  require_once(DIR_WS_CLASSES . 'order_total.php');

  $order_total_modules = new order_total;
  $order_totals = $order_total_modules->process();
// fake tax or warmup shipping from shipping name & recalc all corect
//mydump(var_export($order,true));
//mydump(var_export($order_totals,true));
/*
foreach( $order_totals as $idx=>$ot_module ) {
  if ( $ot_module['code']=='ot_tax' ) {
    $order_totals[$idx]['value'] = $xml_tax;
    $order_totals[$idx]['text'] = $currencies->format($xml_tax, true, $order->info['currency'], $order->info['currency_value']);
    break;
  }
}*/
//\fake tax
  //print_r($order);
  //print_r($order_totals);
  if (defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS')) $order->info['order_status'] = MODULE_PAYMENT_GOOGLECHECKOUT_STATUS;

  $sql_data_array = array('customers_id' => $customer_id,
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          //{{ BEGIN FISTNAME
                          'customers_firstname' => $order->customer['firstname'],
                          'customers_lastname' => $order->customer['lastname'],
                          //}} END FIRSTNAME
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          //{{ BEGIN FISTNAME
                          'delivery_firstname' => $order->delivery['firstname'],
                          'delivery_lastname' => $order->delivery['lastname'],
                          //}} END FIRSTNAME
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                          //{{ BEGIN FISTNAME
                          'billing_firstname' => $order->billing['firstname'],
                          'billing_lastname' => $order->billing['lastname'],
                          //}} END FIRSTNAME
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'],
                          'billing_suburb' => $order->billing['suburb'],
                          'billing_city' => $order->billing['city'],
                          'billing_postcode' => $order->billing['postcode'],
                          'billing_state' => $order->billing['state'],
                          'billing_country' => $order->billing['country']['title'],
                          'billing_address_format_id' => $order->billing['format_id'],
                          'payment_method' => $order->info['payment_method'],
                          'payment_class' => 'googlecheckout',
                          'payment_info' => $GLOBALS['payment_info'], //???
                          'google_orders_id' => $number,
                          'shipping_method' => $order->info['shipping_method'],
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
                          'keep_date' => 'now()',
                          'language_id' => (int)$languages_id,
                          'payment_class' => $order->info['payment_class'],
                          'shipping_class' => $order->info['shipping_class'],
                          'date_purchased' => 'now()',
                          'last_modified' => 'now()',
/* start search engines statistics */
                          'search_engines_id' => $search_engines_id,
                          'search_words_id' => $search_words_id,
/* end search engines statistics*/

                      ///    'affiliate_id' => (int)$affiliate_ref,
                         // 'comments' => 'AFF_DOM' . var_export($affiliate_ref_dom[0]->get_content(), true),

                        //  'how_did_you_find' => $order->info['how_did_you_find'],
                          //'how_did_you_find_text' => $order->info['how_did_you_find_text'],
                          
                          'orders_status' => $order->info['order_status'],
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value']);
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }
  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => '0',
                          'comments' => 'Order created by Google Checkout');
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
  /*
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT products_quantity, products_attributes_filename
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
      $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
    }
    if (tep_db_num_rows($stock_query) > 0) {
      $stock_values = tep_db_fetch_array($stock_query);
      // do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || ((!$stock_values['products_attributes_filename']) && $order->products[$i]['products_file'])) {
        $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
      } else {
        $stock_left = $stock_values['products_quantity'];
      }
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
    }
   */
    // Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
    $sql_data_array = array('orders_id' => $insert_id,
                            'products_id' => tep_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
                            'final_price' => $order->products[$i]['final_price'],
                            'products_purchase_price' => tep_get_products_purchase_price(normalize_id($order->products[$i]['id']), $order->products[$i]['final_price']),
                            'products_tax' => $order->products[$i]['tax'],
                            'used' => $order->products[$i]['used'],


                            'is_give_away'=>((isset($order->products[$i]['is_give_away']) && $order->products[$i]['is_give_away']==1)?'1':'0'),
                         // addon - gives as products   'gives' => (is_array($order->products[$i]['gives'])?serialize($order->products[$i]['gives']):''),
                            'products_quantity' => $order->products[$i]['qty'],
                            'products_status' => ((tep_get_products_stock($order->products[$i]['id']) - $order->products[$i]['qty'])>-1?1:0),
                            'uprid' => normalize_id($order->products[$i]['id']));
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();
    $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
    //------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';

    if ((DOWNLOAD_ENABLED == 'true') && tep_not_null($order->products[$i]['products_file'])) {
      $sql_data_array = array('orders_id' => $insert_id,
      'orders_products_id' => $order_products_id,
      'orders_products_name' => $order->products[$i]['name'],
      'orders_products_filename' => $order->products[$i]['products_file'],
      'download_maxdays' => DOWNLOAD_MAX_DAYS,
      'download_count' => DOWNLOAD_MAX_COUNT);
      tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
    }


  //// GiveAway multiplay by senia 2007-02-23 <admin@webs.in.ua> ////
  /*
   $ordered_gives = '';

   if (is_array($order->products[$i]['gives']) && sizeof($order->products[$i]['gives'])>0) {
     $ordered_gives .= "\n\t" . TEXT_GIVEAWAY . ':';
     foreach($order->products[$i]['gives'] as $gdata) {
       $ordered_gives .= "\n\t" . ' - ' . $gdata['fullname'];
       update_stock($gdata['uprid'], 0, $order->products[$i]['qty']);
     }
   }
   */

   $ordered_gives = '';

   if (is_array($order->products[$i]['gives']) && sizeof($order->products[$i]['gives'])>0) {
     $ordered_gives .= "\n\t" . TEXT_GIVEAWAY . ':';
     $dec_price = 0;
     foreach($order->products[$i]['gives'] as $gdata) {
       $ordered_gives .= "\n\t" . ' - ' . $gdata['fullname'] . ' (+' . $currencies->display_price($gdata['price'], $gdata['tax']) . ')';
           $dec_price += $gdata['price'];
           $sql_data_array = array('orders_id' => $insert_id,
                                   'products_id' => tep_get_prid($gdata['id']),
                                   'parent_giv_id' => normalize_id($order->products[$i]['id']),
                                   'products_model' => $gdata['model'],
                                   'products_name' => $gdata['name'] . ' (Giveaway '.$order->products[$i]['model'].')',
                                   'products_price' => $gdata['price'],
                                   'final_price' => $gdata['price'],
                                   'products_tax' => $gdata['tax'],

                                  

                                   'products_quantity' => $order->products[$i]['qty'],
                                   'products_status' => ((tep_get_products_stock($gdata['uprid']) - $order->products[$i]['qty'])>-1?1:0),
                                   'uprid' => normalize_id($gdata['uprid']));
           tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
           $order_gives_id = tep_db_insert_id();

           if (is_array($gdata['attributes']) && sizeof($gdata['attributes'])>0) {
             foreach ($gdata['attributes'] as $gdata_attr) {

                     $sql_data_array = array('orders_id' => $insert_id,
                                             'orders_products_id' => $order_gives_id,
                                             'products_options_id' => $gdata_attr['opt_id'],
                                             'products_options' => $gdata_attr['opt_name'],
                                             'products_options_values_id' => $gdata_attr['opt_val_id'],
                                             'products_options_values' => $gdata_attr['tep_values_name'],
                                             'options_values_price' => '0',
                                             'price_prefix' => '+');
                     tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

             }
           }

       update_stock($gdata['uprid'], 0, $order->products[$i]['qty']);
     }

     tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set products_price=(products_price-'".floatval($dec_price)."'),  final_price=(final_price-'".floatval($dec_price)."') where orders_products_id='".intval($order_products_id)."'");
   }
  //// GiveAway multiplay by senia 2007-02-23 <admin@webs.in.ua> off ////




    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select pa.products_attributes_id, popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_maxdays, pa.products_attributes_maxcount , pa.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               where pa.products_id = '" . $order->products[$i]['id'] . "'
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                and pa.options_values_id = poval.products_options_values_id
                                and popt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select pa.products_attributes_id, popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }

          $attributes_values = tep_db_fetch_array($attributes);
          $attributes_values['options_values_price'] = tep_get_options_values_price($attributes_values['products_attributes_id']);

        $sql_data_array = array('orders_id' => $insert_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $attributes_values['products_options_values_name'],
                                'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id,
                                  'orders_products_id' => $order_products_id,
                                  'orders_products_name' => $order->products[$i]['name'],
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
      }



    }

  // update inventory
if (PRODUCTS_INVENTORY == 'True'){
  update_stock($order->products[$i]['id'], 0, $order->products[$i]['qty']);
}
//

  }

  //$insert_id, $customer_id, $REMOTE_ADDR, $cc_id;
  $order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM

  
  tep_calculate_order_revenue($insert_id); 
  
}

/**
 * TheProcessOrderStateChangeNotification function is a shell function
 * for handling a <order-state-change-notification>. You will need to
 * modify this function to transfer the information contained in a
 * <order-state-change-notification> to your internal systems that
 * process that data.
 *
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessOrderStateChangeNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * Order state change notifications signal an update to an order's
     * financial status or its fulfillment status. An
     * <order-state-change-notification> identifies the new financial
     * and fulfillment statuses for an order. It also identifies the
     * previous statuses for the order. Google Checkout will send an
     * <order-state-change-notification> to confirm status changes that
     * you trigger by using the Order Processing API requests. For
     * example, if you send Google Checkout a <cancel-order> request,
     * Google Checkout will respond through the Notification API to inform
     * you that the order's status has been changed to "canceled".
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <order-state-change-notification> to your internal systems that
     * process financial or fulfillment status information.
     */
     // Now we process only DELIVERED state from Google, all another states are marked as Google Checkout Processing
    $dom_data_root = $dom_response_obj->document_element();
    $google_order_number = $dom_data_root->get_elements_by_tagname("google-order-number");
    $number = $google_order_number[0]->get_content();
    $order_status1 = $dom_data_root->get_elements_by_tagname("new-fulfillment-order-state");
    $order_status2 = $dom_data_root->get_elements_by_tagname("new-financial-order-state");
    $status1 = $order_status1[0]->get_content();
    $status2 = $order_status2[0]->get_content();
    $status = '';
    if ($status1=='NEW') $status = 'NEW';
    if ($status1=='PROCESSING' && $status2=='CHARGED') $status = 'CHARGED';
    if ($status1=='DELIVERED') $status = 'DELIVERED';
    if ($status2=='CANCELLED') $status = 'CANCELLED';

    $sql_data_array = false;
    $order_id = tep_db_fetch_array(tep_db_query("select orders_id from " . TABLE_ORDERS . " where google_orders_id='" . $number . "'"));
    if ( (int)$order_id['orders_id']!=0  ) {
      $sql_data_array = array('orders_id' => (int)$order_id['orders_id'],
                              'orders_status_id' => 0,
                              'date_added' => 'now()',
                              'customer_notified' => '0',
                              'comments' => 'Google Checkout status change');
    }

    switch($status)
    {
      case 'CHARGED':
        // update order status, 3 - Shipped status in shop system
        tep_db_query("update " . TABLE_ORDERS . " set orders_status='".(int)MODULE_PAYMENT_GOOGLECHECKOUT_STATUS."' where google_orders_id='" . $number . "'");
        if ( is_array($sql_data_array) ) {
          $sql_data_array['orders_status_id'] = (int)MODULE_PAYMENT_GOOGLECHECKOUT_STATUS;
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
      break;
      case 'DELIVERED':
        // update order status, 3 - Shipped status in shop system
        tep_db_query("update " . TABLE_ORDERS . " set orders_status='".(int)MODULE_PAYMENT_GOOGLECHECKOUT_DELIVERED_STATUS_ID."' where google_orders_id='" . $number . "'");
        if ( is_array($sql_data_array) ) {
          $sql_data_array['orders_status_id'] = (int)MODULE_PAYMENT_GOOGLECHECKOUT_DELIVERED_STATUS_ID;
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
      break;
      case 'CANCELLED':
        // update order status, 3 - Shipped status in shop system
        tep_db_query("update " . TABLE_ORDERS . " set orders_status='".(int)MODULE_PAYMENT_GOOGLECHECKOUT_CANCELLED_STATUS_ID."' where google_orders_id='" . $number . "'");
        if ( is_array($sql_data_array) ) {
          $sql_data_array['orders_status_id'] = (int)MODULE_PAYMENT_GOOGLECHECKOUT_CANCELLED_STATUS_ID;
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
      break;
    }
    SendNotificationAcknowledgment();
}

/**
 * The ProcessChargeAmountNotification function is a shell function for
 * handling a <charge-amount-notification>. You will need to modify this
 * function to relay the information in the <charge-amount-notification>
 * to your internal systems that process that data.
 *
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessChargeAmountNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * Charge amount notifications inform you that a customer has been
     * charged for either the full amount or a partial amount of an
     * order. A <charge-amount-notification> contains the order number
     * that Google assigned to the order, the value of the most recent
     * charge to the customer and the total amount that has been
     * charged to the customer for the order. Google Checkout will send a
     * <charge-amount-notification> after charging the customer.
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <charge-amount-notification> to your internal systems that
     * process this order data.
     */
    SendNotificationAcknowledgment();
}

/**
 * The ProcessChargebackAmountNotification function is a shell function
 * for handling a <chargeback-amount-notification>. You will need to
 * modify this function to transfer the information contained in a
 * <chargeback-amount-notification> to your internal systems that
 * process that data.
 *
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessChargebackAmountNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * Chargeback amount notifications inform you that a customer
     * has initiated a chargeback against an order and that Google Checkout
     * has approved the chargeback. A <chargeback-amount-notification>
     * contains the order number that Google assigned to the order,
     * the value of the most recent chargeback against the order
     * and the total amount that has been charged back against the
     * order. Google Checkout will send a <chargeback-amount-notification>
     * after approving the chargeback.
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <chargeback-amount-notification> to your internal systems that
     * process this order data.
     */
    SendNotificationAcknowledgment();
}

/**
 * The ProcessRefundAmountNotification function is a shell function for
 * handling a <refund-amount-notification>. You will need to modify this
 * function to transfer the information contained in a
 * <refund-amount-notification> to your internal systems that handle that data.
 *
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessRefundAmountNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * Refund amount notifications inform you that a customer has been
     * refunded either the full amount or a partial amount of an order
     * total. A <refund-amount-notification> contains the order number
     * that Google assigned to the order, the value of the most recent
     * refund to the customer and the total amount that has been
     * refunded to the customer for the order. Google Checkout will send a
     * <refund-amount-notification> after refunding the customer.
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <refund-amount-notification> to your internal systems that
     * process this order data.
     */
    SendNotificationAcknowledgment();
}

/**
 * TheProcessRiskInformationNotification function is a shell function for
 * handling a <risk-information-notification>. You will need to modify this
 * function to transfer the information contained in a
 * <risk-information-notification> to your internal systems that process
 * that data.
 * @param    $dom_response_obj    asynchronous notification XML DOM
 */
function ProcessRiskInformationNotification($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * Risk information notifications provide financial information about
     * a transaction to help you ensure that an order is not fraudulent.
     * A <risk-information-notification> includes the customer's billing
     * address, a partial credit card number and other values to help you
     * verify that an order is not fraudulent. Google Checkout will send you a
     * <risk-information-notification> message after completing its
     * risk analysis on a new order.
     *
     * If you are implementing the Notification API, you need to
     * modify this function to relay the information in the
     * <risk-information-notification> to your internal systems that
     * process this order data.
     */
     /*
        Google AVS constants
        Y - Full AVS match (address and postal code)
        P - Partial AVS match (postal code only)
        A - Partial AVS match (address only)
        N - No AVS match
        U - AVS not supported by issuer
        Google CVN constants
        M - CVN match
        N - No CVN match
        U - CVN not available
        E - CVN error
     */
    $dom_data_root = $dom_response_obj->document_element();
    $google_order_number = $dom_data_root->get_elements_by_tagname("google-order-number");
    $number = $google_order_number[0]->get_content();
    $avs_data = $dom_data_root->get_elements_by_tagname("avs-response");
    $avs = $avs_data[0]->get_content();
    $cvn_data = $dom_data_root->get_elements_by_tagname("cvn-response");
    $cvn = $cvn_data[0]->get_content();
    // decode risk information
    $avs_string = '';
    switch($avs)
    {
      case 'Y': $avs_string = 'Full AVS match (address and postal code)'; break;
      case 'P': $avs_string = 'Partial AVS match (postal code only)'; break;
      case 'A': $avs_string = 'Partial AVS match (address only)'; break;
      case 'N': $avs_string = 'No AVS match'; break;
      case 'U': $avs_string = 'AVS not supported by issuer'; break;
    }
    $cvn_string = '';
    switch($cvn)
    {
      case 'M': $cvn_string = 'CVN match'; break;
      case 'N': $cvn_string = 'No CVN match'; break;
      case 'U': $cvn_string = 'CVN not available'; break;
      case 'E': $cvn_string = 'CVN error'; break;
    }
    $message = "Risk Information:\n" . $avs_string . "\n" . $cvn_string;
    // put results to the order status history
    $order = tep_db_fetch_array(tep_db_query("select orders_id, orders_status from " . TABLE_ORDERS . " where google_orders_id='" . $number . "'"));
    tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, comments) values('" . $order['orders_id'] . "', '" . $order['orders_status'] . "', now(), '" . $message . "')");
    SendNotificationAcknowledgment();
}

/**
 * The SendNotificationAcknowledgment function responds to a Google Checkout
 * notification with a <notification-acknowledgment> message. If you do
 * not send a <notification-acknowledgment> in response to a Google Checkout
 * notification, Google Checkout will resend the notification multiple times.
 */
function SendNotificationAcknowledgment() {
    $acknowledgment = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
        "<notification-acknowledgment xmlns=\"" .
        $GLOBALS["schema_url"] . "\"/>";

    echo $acknowledgment;

    // Log <notification-acknowledgment>
    LogMessage($GLOBALS["logfile"], $acknowledgment);
}

/** End of file **/

?>