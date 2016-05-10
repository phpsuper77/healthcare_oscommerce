<?php
/*
  $Id: checkout_process.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');
  
  
	if ($_POST['amazon_purchaseContractId'] != '' && $_POST['amazon_action'] == "complete_order") {
		require(DIR_WS_CLASSES . 'shipping.php');		
		require(DIR_WS_CLASSES . 'order.php');
		require(DIR_WS_CLASSES . 'order_total.php');
		$order = new order;		
		require('checkoutbyamazon/src/CheckoutByAmazon/Service/Samples/OrderWithContractCharges.php');		
		$cart->reset(true);
		$_SESSION['amazon_purchaseContractId'] = "";  //clear amazon PurchaseContractID
		tep_redirect("http://www.healthcare4all.co.uk/info/Amazon+Thankyou+Page.html", '', 'SSL');		
		die;
	}
  

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (!tep_session_is_registered('sendto')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
 }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($payment);

  if ( defined('ONE_PAGE_POST_PAYMENT') && ereg(FILENAME_CHECKOUT_CONFIRMATION,$_SERVER['HTTP_REFERER'])){
    if ( $payment!='protx_direct' ) {
      if (is_array($payment_modules->modules)) {
        $payment_modules->pre_confirmation_check();
      }
    }
  }

// load the selected shipping module
  if (AUCTION_BLOX_ENABLED == 'True'){
    //+++AUCTIONBLOX.COM
    require(DIR_WS_MODULES . 'auctionblox/includes/classes/abxShipping.php');
    $shipping_modules = new abxShipping;
  }else{
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping($shipping);
  }
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;


  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();


// load the before_process function from the payment modules
  $payment_modules->before_process();
  //************************************************************
  // Authorizenet ADC Direct Connection
  // Make sure the /catalog/includes/class/order.php is included
  // and $order object is created before this!!!
  if(MODULE_PAYMENT_AUTHORIZENET_STATUS) {
   include(DIR_WS_MODULES . 'authorizenet_direct.php');
  }
  //************************************************************


// BOF: WebMakers.com Added: Downloads Controller
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
                          'payment_method' => strip_tags($order->info['payment_method']),
// BOF: Lango Added for print order mod
                          'payment_info' => $GLOBALS['payment_info'],
// EOF: Lango Added for print order mod
                          'cc_type' => $order->info['cc_type'],
                          'cc_owner' => $order->info['cc_owner'],
                          'cc_number' => $order->info['cc_number'],
                          'cc_expires' => $order->info['cc_expires'],
                          'language_id' => (int)$languages_id,
                          'payment_class' => strip_tags($order->info['payment_class']),
                          'shipping_class' => $order->info['shipping_class'],
                          'date_purchased' => 'now()',
                          'last_modified' => 'now()',
/* start search engines statistics */
                          'search_engines_id' => $search_engines_id,
                          'search_words_id' => $search_words_id,
/* end search engines statistics*/
                          'orders_status' => $order->info['order_status'],
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value']);
// EOF: WebMakers.com Added: Downloads Controller
//  tep_session_unregister('shipping');
//  tep_session_unregister('payment');
 
 
 //if ($_SESSION['amazonsandbox'] == "1" && $_POST['checkout_method'] == "amazon") {
	if ($_SESSION['amazonsandbox'] == "1" && $_POST['payment'] == "moneyorder") {	  
	  //print "<pre>";
	  //print_r($order);
	  print $_POST['amazon_purchaseContractId'];
	  include("checkoutbyamazon/src/CheckoutByAmazon/Service/Samples/OrderWithContractCharges.php");
	  die;
  }	
 

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

  $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $order->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification,
                          'comments' => $order->info['comments']);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    if (STOCK_LIMITED == 'true') {
      update_stock($order->products[$i]['id'], 0, $order->products[$i]['qty']);
    }

// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

    $sql_data_array = array('orders_id' => $insert_id,
                            'products_id' => tep_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
                            'final_price' => $order->products[$i]['final_price'],
                            'products_tax' => $order->products[$i]['tax'],
                            'products_quantity' => $order->products[$i]['qty'],
                            'is_giveaway' => $order->products[$i]['ga'],
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

// {{ Products Bundle Sets
    $sets_array = array();
    if (PRODUCTS_BUNDLE_SETS == 'True')
    {
      global $customer_groups_id, $currency_id;
      $bundle_sets_query = tep_db_query("select p.products_id, sp.num_product from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.sets_id = '" . (int)$order->products[$i]['id'] . "' and p.products_status = '1' and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
    }
    if (PRODUCTS_BUNDLE_SETS == 'True' && tep_db_num_rows($bundle_sets_query) > 0)
    {
      if ($order->products[$i]['attributes'])
      {
        reset($order->products[$i]['attributes']);
        while (list($option, $value) = each($order->products[$i]['attributes']))
        {
          if ($value['option_id'] != 0 && $value['value_id'] != 0) continue;

          $sets_array[$option]['id'] = $value['products_id'];
          $sets_array[$option]['qty'] = $value['products_qty'];
          $sets_array[$option]['model'] = $value['products_model'];
          $sets_array[$option]['name'] = $value['products_name'];
          $sets_array[$option]['price'] = $value['products_price'];
          $sets_array[$option]['weight'] = $value['products_weight'];

          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'products_options' => $value['products_option'] . ($value['products_model'] ? '&nbsp;(' . $value['products_model'] . ')' : '') . $value['products_attributes'],
                                  'products_options_values' => '', 
                                  'options_values_price' => '', 
                                  'price_prefix' => '');
          tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

          $products_ordered_attributes .= "\n&nbsp;&nbsp;&nbsp;-&nbsp;" . $value['products_option'] . ($value['products_model'] ? '&nbsp;(' . $value['products_model'] . ')' : '') . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($value['products_attributes']));
        }
      }
    }
// }}
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
// {{ Products Bundle Sets
        if ($order->products[$i]['attributes'][$j]['option_id'] == 0 && $order->products[$i]['attributes'][$j]['value_id'] == 0) continue;
// }}
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
                                  'download_maxdays' => ($attributes_values['products_attributes_maxdays']?$attributes_values['products_attributes_maxdays']:DOWNLOAD_MAX_DAYS),
                                  'download_count' => ($attributes_values['products_attributes_maxcount']?$attributes_values['products_attributes_maxcount']:DOWNLOAD_MAX_COUNT));
          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . htmlspecialchars($attributes_values['products_options_name']) . ': ' . htmlspecialchars($attributes_values['products_options_values_name']);
      }
    }

// {{ Products Bundle Sets
    if (count($sets_array) > 0)
    {
      tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set sets_array = '" . tep_db_input(serialize($sets_array)) . "' where orders_products_id = '" . (int)$order_products_id . "'");
    }
// }}

//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . (($order->products[$i]['model']!='')?' (' . $order->products[$i]['model'] . ')':'') . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }

  $order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM
// lets start with the email confirmation
  $email_order = STORE_NAME . "\n" .
                 EMAIL_SEPARATOR . "\n" .
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link(tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false)) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($order->info['comments']) {
    $email_order .= tep_db_output($order->info['comments']) . "\n\n";
  }
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  $products_ordered .
                  EMAIL_SEPARATOR . "\n";

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    tep_address_label($customer_id, $sendto, 0, '', "\n") . "\n";
  }

  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
  if (is_object($$payment)) {
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                    EMAIL_SEPARATOR . "\n";
    $payment_class = $$payment;
    $email_order .= $payment_class->title . "\n\n";
    if ($payment_class->email_footer) {
      $email_order .= $payment_class->email_footer . "\n\n";
    }
  }
  tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

// Begin Affiliate Program - Sales Tracking
$orders_total=$currencies->format($cart->show_total()- $total_tax); 
tep_session_register('orders_total'); 
$orders_id=$order_products_id; 
tep_session_register('orders_id'); 
// End Affiliate Program - Sales Tracking
  
  // Include OSC-AFFILIATE
  require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');

  if (VENDOR_ENABLED == 'true'){
    require(DIR_WS_INCLUDES . 'vendor_checkout_process.php');
  }

// load the after_process function from the payment modules
  $payment_modules->after_process();
  if (AUCTION_BLOX_ENABLED == 'True'){
    $cart->reset(true, $insert_id);
  }else{
    $cart->reset(true);
  }

// unregister session variables used during checkout
  tep_session_unregister('sendto');
  tep_session_unregister('billto');
  tep_session_unregister('shipping');
  tep_session_unregister('payment');
  tep_session_unregister('comments');
  if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
  $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
// BOF: Lango added for print order mod
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id, 'SSL'));
// EOF: Lango added for print order mod
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
