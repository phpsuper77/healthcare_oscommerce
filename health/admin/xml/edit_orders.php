<?php
  require('includes/application_top.php');
  require('includes/classes/http_client.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

  global $shipping;
  if(!strlen($shipping)>0) {
    $a = explode(';', MODULE_SHIPPING_INSTALLED);
    $shipping = $a[0];
  }
  if ($shipping) {
    require_once(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping($order->info['shipping_class']);
  }
  $currency_select = "select currency from " .  TABLE_ORDERS . " where  orders_id ='" . $HTTP_GET_VARS['oID'] . "'";
  $currency_res = tep_db_query($currency_select);
  if($currency_data = tep_db_fetch_array($currency_res)) {
    $currency = $currency_data['currency'];
  }

  if(strlen($currency) == 0)
    $currency = DEFAULT_CURRENCY;

  // New "Status History" table has different format.
  $OldNewStatusValues = (tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
  $CommentsWithStatus = tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
  
  $SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");

  // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)){
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'edit');

  if (tep_not_null($action)) {
    switch ($action) {
  // Update Order: shipping/payment methods, products qty, details, shipping/billing addresses, totals, status, comment.
      case 'update_order':
        // update invertory
        $order_id = $HTTP_GET_VARS['oID'];
        $update_products = $HTTP_POST_VARS['update_products'];
        if (is_array($update_products)){
          foreach($update_products as $id => $v){
            $uprid = tep_db_fetch_array(tep_db_query("select uprid from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id='" . $id . "' and orders_id='" . $order_id . "'"));
            if(tep_not_null($uprid['uprid'])){
              update_stock($uprid['uprid'], $v['old_qty'], $v['qty']);
            }
          }
        }
        //
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $order = new order($oID);
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        $payment_method = tep_db_prepare_input($HTTP_POST_VARS['payment']);
        if($payment_method != "") {
          require_once(DIR_WS_CLASSES . 'payment.php');
          $payment_modules = new payment($payment_method);
          /*
          if (is_array($payment_modules->modules)) {
            $payment_modules->pre_confirmation_check();
          }
          */
          tep_db_query(" update " . TABLE_ORDERS . " set payment_method ='" . $GLOBALS[$payment_method]->title . "', payment_class='" . $payment_method . "' where orders_id='" . $oID . "'");
        } else {
          tep_db_query(" update " . TABLE_ORDERS . " set payment_class='', payment_method='' where orders_id='" . $oID . "'");
        }

          // Update Products
          //debug($update_products);
          //die();
        if(is_array($update_products)) {
          foreach($update_products as $orders_products_id => $products_details) {
            // Update orders_products Table
            if(($products_details["qty"] > 0)) {
              $ar= split('_', $products_details["tax"]);

              $tax = tep_get_tax_rate_value_edit_order($ar[0], $ar[1]);
              tep_db_query("update " . TABLE_ORDERS_PRODUCTS . " set products_model = '" . tep_db_input($products_details["model"]) . "', products_name = '" . tep_db_input($products_details["name"]) . "', final_price = '" . $products_details["final_price"] . "', products_tax = '" . $tax . "', products_quantity = '" . $products_details["qty"] . "' where orders_products_id = '".intval($orders_products_id)."'");
              // Update Any Attributes
              if(is_array($products_details['attributes'])) {
                foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details) {
                  tep_db_query("update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set products_options = '" . tep_db_input(tep_db_prepare_input($attributes_details["option"])) . "', products_options_values = '" . tep_db_input(tep_db_prepare_input($attributes_details["value"])) . "' where orders_products_attributes_id = '" . $orders_products_attributes_id . "';");
                }
              }
            } elseif (($products_details["qty"] == 0) || ($products_details["qty"] == '')) {
              tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '" . $orders_products_id . "'");
              tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '" . $orders_products_id . "'");
            }
          }
        }

// Update shipping/billing addresses.
          $UpdateOrders = "update " . TABLE_ORDERS . " set ";
//        $UpdateOrders = "customers_name = '" . tep_db_input(stripslashes($update_customer_name)) . "', customers_company = '" . tep_db_input(stripslashes($update_customer_company)) . "', customers_street_address = '" . tep_db_input(stripslashes($update_customer_street_address)) . "', customers_suburb = '" . tep_db_input(stripslashes($update_customer_suburb)) . "', customers_city = '" . tep_db_input(stripslashes($update_customer_city)) . "', customers_state = '" . tep_db_input(stripslashes($update_customer_state)) . "', customers_postcode = '" . tep_db_input($update_customer_postcode) . "', customers_country = '" . tep_db_input(stripslashes($update_customer_country)) . "', customers_telephone = '" . tep_db_input($update_customer_telephone) . "', customers_email_address = '" . tep_db_input($update_customer_email_address) . "',";

          $UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_name'])) . "', billing_company = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_company'])) . "', billing_street_address = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_street_address'])) . "', billing_suburb = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_suburb'])) . "', billing_city = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_city'])) . "', billing_state = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_state'])) . "', billing_postcode = '" . tep_db_input($HTTP_POST_VARS['update_billing_postcode']) . "', billing_country = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_billing_country'])) . "',";
/*
          if($payment_method=="")
            $bb=tep_db_input($update_info_payment_method);
          else
            $bb=$payment_method_title;
*/
          $UpdateOrders .= "delivery_name = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_name'])) . "', delivery_company = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_company'])) . "', delivery_street_address = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_street_address'])) . "', delivery_suburb = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_suburb'])) . "', delivery_city = '" . tep_db_input(stripslashes($HTTP_POST_VARS['update_delivery_city'])) . "', delivery_state = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_state'])) . "', delivery_postcode = '" . tep_db_input($HTTP_POST_VARS['update_delivery_postcode']) . "', delivery_country = '" . tep_db_input(tep_db_prepare_input($HTTP_POST_VARS['update_delivery_country'])) . "', cc_type = '" . tep_db_input($update_info_cc_type) . "', cc_owner = '" . tep_db_input(tep_db_prepare_input($update_info_cc_owner)) . "', cc_number = '" . $HTTP_POST_VARS['update_info_cc_number'] . "', cc_expires = '" . $HTTP_POST_VARS['update_info_cc_expires'] . "',  cc_cvn = '" . $HTTP_POST_VARS['update_info_cc_cvn'] . "'";

          if(!$CommentsWithStatus) {
            $UpdateOrders .= ", comments = '" . tep_db_input($comments) . "'";
          }
          $UpdateOrders .= " where orders_id = '" . tep_db_input($oID) . "';";

          tep_db_query($UpdateOrders);
          $order_updated = true;

        $shipping = $HTTP_POST_VARS['shipping'];
        if ($shipping != ''){
          $cart = new shoppingCart($oID);
          $cart->calculate();
          $shipping_weight = $cart->show_weight();
          $total_weight = $shipping_weight;
          $total_count = $cart->count_contents();
          require_once(DIR_WS_CLASSES . 'shipping.php');
          $shipping_modules = new shipping();

          list($module, $method) = explode('_', $shipping);
          if ( is_object($$module) || ($shipping == 'free_free') ) {
            if ($shipping == 'free_free') {
              $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
              $quote[0]['methods'][0]['cost'] = '0';
            } else {
              $quote = $shipping_modules->quote($method, $module);
            }
            if (isset($quote['error'])) {
            } else {
              if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
                tep_db_query(" update " . TABLE_ORDERS . " set shipping_class='" . $shipping . "' where orders_id='" . $oID . "'");
                $result=tep_db_query("select  orders_total_id from " . TABLE_ORDERS_TOTAL . " where orders_id='" . $oID . "' and class ='ot_shipping'");

                if(tep_db_num_rows($result)>0) {
                  $sql_data_shipping_array = array('title' => $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')');
                  tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_shipping_array, 'update', ' orders_id="' . $oID . '" and class ="ot_shipping"');
                }  else {
                  $query="insert into " . TABLE_ORDERS_TOTAL . " (orders_id, title, text, value, class, sort_order) values ('" . $oID . "', '" . $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')' . "', '', '','ot_shipping','" . $sort_order . "')";
                  $result=tep_db_query($query);
                }

              }
            }
          }
        }
          $cart = new shoppingCart($oID);
          $cart->calculate();

          $order = new order($oID); ///update the addresses

        if ($HTTP_POST_VARS['calculate_totals']==1) {

// update totals
        $update_totals = $HTTP_POST_VARS['update_totals'];
        $shipping_fee = 0;
        if ($shipping != ''){
          $cart = new shoppingCart($oID);
          $cart->calculate();
          $shipping_weight = $cart->show_weight();
          $total_weight = $shipping_weight;
          $total_count = $cart->count_contents();
          require_once(DIR_WS_CLASSES . 'shipping.php');
          $shipping_modules = new shipping();

          list($module, $method) = explode('_', $shipping);
          if ( is_object($$module) || ($shipping == 'free_free') ) {
            if ($shipping == 'free_free') {
              $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
              $quote[0]['methods'][0]['cost'] = '0';
            } else {
              $quote = $shipping_modules->quote($method, $module);
            }
            if (isset($quote['error'])) {
            } else {
              if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
                tep_db_query(" update " . TABLE_ORDERS . " set shipping_class='" . $shipping . "' where orders_id='" . $oID . "'");
                $sql_data_shipping_array = array('value ' => $quote[0]['methods'][0]['cost'],
                                             'text' => $currencies->format($quote[0]['methods'][0]['cost'], true, $currency),
                                             'title' => $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . '):');
                $shipping_fee = $quote[0]['methods'][0]['cost'];
                tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_shipping_array, 'update', ' orders_id="' . $oID . '" and class="ot_shipping"');

                $order->info['shipping_method'] = $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')';
                $quote[0]['id'] = $shipping;
                $shipping = $quote[0];

              }
            }
          }
        }

          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            $order->info['subtotal'] = $order->info['total']; 
          }

          $order->info['shipping_cost'] = $shipping_fee;
          $order->info['total'] += $shipping_fee;

          require_once(DIR_WS_CLASSES . 'order_total.php');
          $order_total_modules = new order_total;
          $order_totals = $order_total_modules->process();

          tep_db_query("delete from ".TABLE_ORDERS_TOTAL." where orders_id='" . $oID . "'");
          for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
            $sql_data_array = array('orders_id' => $oID,
                                    'title' => $order_totals[$i]['title'],
                                    'text' => $order_totals[$i]['text'],
                                    'value' => $order_totals[$i]['value'],
                                    'class' => $order_totals[$i]['code'],
                                    'sort_order' => $order_totals[$i]['sort_order']);
            tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
          }
        } else {
          $update_totals = $HTTP_POST_VARS['update_totals'];

          foreach ($update_totals as $ot){
            if ($ot['value']!=0){
              if (in_array($ot['class'], array('ot_subtotal', 'ot_total', 'ot_tax'))){
//                if ($ot['class']=='ot_total') $ot_total_array = $ot;
              } else {
                if ($ot['total_id']>0){
                  tep_db_query("update " . TABLE_ORDERS_TOTAL . " set title = '" . $ot['title'] . "', text = '" . $currencies->format($ot['value']) . "', value = '" . $ot['value'] . "', sort_order = '" . $ot['sort_order'] . "' where orders_total_id = '" . $ot['total_id'] . "'");
                } else {
                  tep_db_query("insert into " . TABLE_ORDERS_TOTAL . " set title = '" . $ot['title'] . "', text = '" . $currencies->format($ot['value']) . "', value = '" . $ot['value'] . "', sort_order = '" . $ot['sort_order'] . "', class='" . $ot['class'] . "', orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
                }
              }
            } elseif ($ot['total_id'] == 0){
                tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '" . $ot['total_id'] . "'");
            }
          }
                  // update total in any case
          $otSkipSum = array('ot_total');
          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            $otSkipSum[]='ot_tax';
          }
          $res = tep_db_query("select sum(value) as s_value from " . TABLE_ORDERS_TOTAL . " where class NOT IN ('".implode("','", $otSkipSum)."') and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
          $td = tep_db_fetch_array($res);
          $res = tep_db_query("select orders_total_id from " . TABLE_ORDERS_TOTAL . " where class='ot_total' and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
          if ($d = tep_db_fetch_array($res)){
            tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value = '" . $td['s_value'] . "', text = '<b>" . $currencies->format($td['s_value'], false, $currency) . "</b>' where orders_total_id = '" . $d['orders_total_id'] . "' and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");

        }
        }

        if ($order_updated) {
          $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        }
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

// initialized for the email confirmation
        $products_ordered = '';
        for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
//------ select customer choosen option --------
          $attributes_exist = '0';
          $products_ordered_attributes = '';
          if (isset($order->products[$i]['attributes'])) {
            $attributes_exist = '1';
            for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
              $products_ordered_attributes .= "\n\t" . htmlspecialchars($order->products[$i]['attributes'][$j]['option']) . ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']);
            }
          }
//------customer choosen option eof ----

          $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
        }
// Update Status History & Email Customer if Necessary
        tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "' where orders_id = '" . tep_db_input($oID) . "'");
        if ($order->info['orders_status'] != $status) {
          // Notify Customer
          $customer_notified = '0';
          if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }
            // lets start with the email confirmation
            if (!tep_session_is_registered('noaccount')) {
              $email_order = //EMAIL_TEXT_HEADER. "\n" .
                             STORE_NAME . "\n" .
                             EMAIL_SEPARATOR . "\n" .
                             EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .
                             EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link(tep_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID)) . "\n" .
                             EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
              if ($order->info['comments']) {
                $email_order .= tep_db_output($order->info['comments']) . "\n\n";
              }
              $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                              EMAIL_SEPARATOR . "\n" .
                              $products_ordered .
                              EMAIL_SEPARATOR . "\n";
              } else {
              $email_order = STORE_NAME . "\n" .
                             EMAIL_SEPARATOR . "\n" .
                             EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .
                             EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
              if ($order->info['comments']) {
                $email_order .= tep_db_output($order->info['comments']) . "\n\n";
              }
              $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                              EMAIL_SEPARATOR . "\n" .
                              $products_ordered .
                              EMAIL_SEPARATOR . "\n";
              }

              $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
              $order->totals = array();
              while ($totals = tep_db_fetch_array($totals_query)) {
                $email_order .= strip_tags($totals['title']) . ' ' . strip_tags($totals['text']) . "\n";
              }


              if ($order->content_type != 'virtual') {
                $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                                EMAIL_SEPARATOR . "\n" .
                                tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', "\n") . "\n";
              }

              $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                              EMAIL_SEPARATOR . "\n" .
                              /*tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";*/
                              tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', "\n") . "\n\n";


              $query=" select payment_class from ".TABLE_ORDERS." where orders_id='".$oID."'";
              $result=tep_db_query($query);
              $array=tep_db_fetch_array($result);
              $payment_class=$array['payment_class'];
              if($payment_class != "") {
                $module=$payment_class;
                if (is_object($$payment)) {
                  $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                                  EMAIL_SEPARATOR . "\n";
                  $payment_class = $$payment;
                  $email_order .= $payment_class->title . "\n\n";
                  if ($payment_class->email_footer) {
                    $email_order .= $payment_class->email_footer . "\n\n";
                  }
                }
              }
              $email_order=str_replace("&nbsp;", " ", $email_order);

              tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
              // send emails to other people
              if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
                tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
              }

              $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
              $check_status = tep_db_fetch_array($check_status_query);

              $email = sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
              $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
              tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
              $customer_notified = '1';
          }
          if($CommentsWithStatus) {
            tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', now(), " . tep_db_input($customer_notified) . ", '" . tep_db_input($comments)  . "')");
          } else {
            if($OldNewStatusValues) {
              tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, new_value, old_value, date_added, customer_notified) values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', '" . $order->info['orders_status'] . "', now(), " . tep_db_input($customer_notified) . ")");
            } else {
              tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified)
              values ('" . tep_db_input($oID) . "', '" . tep_db_input($status) . "', now(), " . tep_db_input($customer_notified) . ")");
            }
          }
        }
        tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, "oID=" . $oID));
//        tep_redirect(tep_href_link(FILENAME_ORDERS, "action=edit&oID=" . $oID));
      break;
      // Add a Product
      case 'add_product':
        if($step == 3) {
          // Get Order Info
          $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
          $qty = tep_db_prepare_input($HTTP_POST_VARS['add_product_quantity']);
          $order = new order($oID);
          $query = "select shipping_class from " . TABLE_ORDERS . " where orders_id='" . $HTTP_GET_VARS['oID'] . "'";
          $result=tep_db_query($query);
          if(tep_db_num_rows($result)>0) {
            $array=tep_db_fetch_array($result);
            $shipping_class=$array['shipping_class'];
            if(strlen(trim($shipping_class))>0) {
              $cart = new shoppingCart($HTTP_GET_VARS['oID']);
              $cart->calculate();
              $shipping_weight = $cart->show_weight();
              $total_weight = $shipping_weight;

              require_once(DIR_WS_CLASSES . 'shipping.php');
              $shipping = $order->info['shipping_class'];
              list($module, $method) = explode('_', $shipping);
              $shipping_modules = new shipping($module, $method);
            }
          }
          $AddedOptionsPrice = 0;
          $add_product_options = $HTTP_POST_VARS['add_product_options'];
          //print_r($add_product_options);
          $uprid = tep_get_uprid($add_product_products_id, $add_product_options);
          $uprid = normalize_id($uprid);
          // Get Product Attribute Info
          if(is_array($add_product_options)) {
            ksort($add_product_options);
            foreach($add_product_options as $option_id => $option_value_id) {
              $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id and po.language_id = '" . (int)$languages_id . "' LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id and pov.language_id = '" . (int)$languages_id . "' WHERE products_id='" . $add_product_products_id . "' and options_id=" . $option_id . " and options_values_id='" . $option_value_id . "'");
              $row = tep_db_fetch_array($result);
              extract($row, EXTR_PREFIX_ALL, "opt");
              $opt_options_values_price = tep_get_attributes_price_edit_order($opt_products_attributes_id, $currencies->currencies[$order->info['currency']]['id'], tep_get_customers_group($order->customer['customer_id']), 1, true);

              if ($opt_price_prefix == '+') {
                $AddedOptionsPrice += $opt_options_values_price;
              } else {
                $AddedOptionsPrice -= $opt_options_values_price;
              }
              $option_value_details[$option_id][$option_value_id] = array ("options_values_price" => $opt_options_values_price);
              $option_names[$option_id] = $opt_products_options_name;
              $option_values_names[$option_value_id] = $opt_products_options_values_name;
              $option_values_price_prefix[$option_value_id] = $opt_price_prefix;
            }
          }
          update_stock($uprid, $qty, 0, $add_product_options);

          // Get Product Info
          $InfoQuery = "select p.products_model, p.products_price, pd.products_name, p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 where p.products_id='$add_product_products_id'";
          $result = tep_db_query($InfoQuery);
          if(tep_db_num_rows($result)>0){
            $row = tep_db_fetch_array($result);
            extract($row, EXTR_PREFIX_ALL, "p");

            $ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $order->delivery['country']['id'], $order->delivery['zone_id']);
            if (tep_get_products_special_price_edit_order($add_product_products_id, $currencies->currencies[$order->info['currency']]['id'], tep_get_customers_group($order->customer['customer_id']))){
              $p_products_price = tep_get_products_special_price_edit_order($add_product_products_id, $currencies->currencies[$order->info['currency']]['id'], tep_get_customers_group($order->customer['customer_id']));
            }else{
              $p_products_price = tep_get_products_price_edit_order($add_product_products_id, $currencies->currencies[$order->info['currency']]['id'], tep_get_customers_group($order->customer['customer_id']), 1, true);
            }
            // inventory
            if (PRODUCTS_INVENTORY == 'True'){
              $r = tep_db_query("select products_model from " . TABLE_INVENTORY . " where products_id='" . $uprid . "'");
              if ($inventory = tep_db_fetch_array($r)) {
                if ($inventory['products_model']){
                  $p_products_model = $inventory['products_model'];
                }
              }
            }
            // inventory eof
            $Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set orders_id = " . $oID . ", products_id = '" . $add_product_products_id . "', products_model = '" . $p_products_model . "', products_name = '" . tep_db_input($p_products_name) . "', products_price = '" . $p_products_price . "', final_price = '" . ($p_products_price + $AddedOptionsPrice) . "', products_tax = '" . $ProductsTax . "', products_quantity = '" . $add_product_quantity . "', uprid='" . $uprid . "' ";
            tep_db_query($Query);
            $new_product_id = tep_db_insert_id();
            if(is_array($add_product_options)) {
              foreach($add_product_options as $option_id => $option_value_id) {
                $Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set orders_id = " . $oID . ", orders_products_id = " . $new_product_id . ", products_options = '" . tep_db_input($option_names[$option_id]) . "', products_options_values = '" . tep_db_input($option_values_names[$option_value_id]) . "', options_values_price = '" . $option_value_details[$option_id][$option_value_id]["options_values_price"] . "', price_prefix = '" . $option_values_price_prefix[$option_value_id] . "';";
                tep_db_query($Query);
              }
            }
          }
///////////////////////////////////////
// update totals
          $shipping_fee = 0;
          $cart = new shoppingCart($oID);
          $cart->calculate();
          $shipping_weight = $cart->show_weight();
          $total_weight = $shipping_weight;
          $total_count = $cart->count_contents();
          require_once(DIR_WS_CLASSES . 'shipping.php');
          $shipping_modules = new shipping();

          $shipping = $order->info['shipping_class'];
          list($module, $method) = explode('_', $shipping);
          if ( is_object($$module) || ($shipping == 'free_free') ) {
            if ($shipping == 'free_free') {
              $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
              $quote[0]['methods'][0]['cost'] = '0';
            } else {
              $quote = $shipping_modules->quote($method, $module);
            }
            if (isset($quote['error'])) {
            } else {
              if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
                tep_db_query(" update " . TABLE_ORDERS . " set shipping_class='" . $shipping . "' where orders_id='" . $oID . "'");
                $sql_data_shipping_array = array('value ' => $quote[0]['methods'][0]['cost'],
                                             'text' => $currencies->format($quote[0]['methods'][0]['cost'], true, $currency),
                                             'title' => $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . '):');
                $shipping_fee = $quote[0]['methods'][0]['cost'];
                tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_shipping_array, 'update', ' orders_id="' . $oID . '" and class="ot_shipping"');

                $order->info['shipping_method'] = $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')';
                $quote[0]['id'] = $shipping;
                $shipping = $quote[0];

              }
            }
          }
          // recreate order in order to recalculate totals and update shipping costs
          $order = new order($oID);
          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            $order->info['subtotal'] = $order->info['total']; 
          }
          $order->info['shipping_cost'] = $shipping_fee;
          $order->info['total'] += $shipping_fee;
          require_once(DIR_WS_CLASSES . 'order_total.php');
          $order_total_modules = new order_total;
          $order_totals = $order_total_modules->process();
          for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
            $res = tep_db_query("select count(*) as total from " . TABLE_ORDERS_TOTAL . " where orders_id='" . $oID . "' and class='" . $order_totals[$i]['code'] . "'");
            $d = tep_db_fetch_array($res);
            $sql_data_array = array('title' => $order_totals[$i]['title'],
                                    'text' => $order_totals[$i]['text'],
                                    'value' => $order_totals[$i]['value'],
                                    'sort_order' => $order_totals[$i]['sort_order']);
            if ($d['total']==0){
              $sql_data_array['orders_id'] = $oID;
              $sql_data_array['class'] = $order_totals[$i]['code'];
              tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
            } else {
              tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array, 'update', "orders_id='" . $oID . "' and class='" . $order_totals[$i]['code'] . "'");
            }
            // update total in any case (as there are custom total field)
            $otSkipSum = array('ot_total');
            if (DISPLAY_PRICE_WITH_TAX == 'true') {
              $otSkipSum[]='ot_tax';
            }
            $res = tep_db_query("select sum(value) as s_value from " . TABLE_ORDERS_TOTAL . " where class NOT IN ('".implode("','", $otSkipSum)."') and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
            $td = tep_db_fetch_array($res);
            $res = tep_db_query("select orders_total_id from " . TABLE_ORDERS_TOTAL . " where class='ot_total' and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
            if ($d = tep_db_fetch_array($res)){
              tep_db_query("update " . TABLE_ORDERS_TOTAL . " set value = '" . $td['s_value'] . "' where orders_total_id = '" . $d['orders_total_id'] . "' and orders_id = '" . $HTTP_GET_VARS['oID'] . "'");
            }
          }
          tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, "oID=" . $oID));
        }
      break;
    }
  }

  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {
    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  // added by Art. Start
  $fields = array("cc_number", "cc_type", "cc_owner", "cc_expires", "cc_cvn");
  $js_arrs  = 'var fields = new Array("' . implode('", "', $fields) . '");' . "\n";
  foreach($fields as $field){
    $js_arrs .= 'var ' . $field . ' = new Array();' . "\n";
  }
  // added by Art. Stop
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js">
</script>
<!-- added by Art. Start -->
<script language="javascript">
      <?php echo $js_arrs;?>
      function select_cc(cc){
        var f = document.edit_order;
        f.update_info_cc_type.value = cc_type[cc];
        f.update_info_cc_owner.value = cc_owner[cc];
        f.update_info_cc_number.value = cc_number[cc];
        f.update_info_cc_expires.value = cc_expires[cc];
        f.update_info_cc_cvn.value = cc_cvn[cc];
      }
var selected_shipping;

function selectRowEffect_ship(object, buttonSelect) {
  if (!selected_shipping) {
    if (document.getElementById) {
      selected_shipping = document.getElementById('defaultSelected_ship');
    } else {
      selected_shipping = document.all['defaultSelected_ship'];
    }
  }

  if (selected_shipping) selected_shipping.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected_shipping = object;

// one button is not an array
  if (document.edit_order.shipping[0]) {
    document.edit_order.shipping[buttonSelect].checked=true;
  } else {
    document.edit_order.shipping.checked=true;
  }
}

var selected_payment;

function selectRowEffect_paym(object, buttonSelect) {
  if (!selected_payment) {
    if (document.getElementById) {
      selected_payment = document.getElementById('defaultSelected_paym');
    } else {
      selected_payment = document.all['defaultSelected_paym'];
    }
  }

  if (selected_payment) selected_payment.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected_payment = object;

// one button is not an array
  if (document.edit_order.payment[0]) {
    document.edit_order.payment[buttonSelect].checked=true;
  } else {
    document.edit_order.payment.checked=true;
  }
}

function rowOverEffect_paym(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect_paym(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function rowOverEffect_ship(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect_ship(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

</script>
<!-- added by Art. Stop -->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CUSTOMERS;
  $header_title_menu_link= tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
  if (($action == 'edit') && ($order_exists == true)) {
    $header_title_submenu=HEADING_TITLE."#".$oID;
  }
  if($action == "add_product") {
    $header_title_submenu=ADDING_TITLE."#".$oID;
  }
?>
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
          </table>
        </td>
        <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>

      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS,  'action=edit&oID=' . $oID) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>


<!-- Begin Addresses Block -->
      <tr><?php echo tep_draw_form('edit_order', "edit_orders.php", tep_get_all_get_params(array('action','paycc')) . 'action=update_order');
      $cart = new shoppingCart($oID);
      $cart->calculate();
      //echo '<pre>'; print_r($cart);echo '</pre>'; echo '<pre>'; print_r($order);echo '</pre>';
      ?>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_draw_separator();?></td>
          </tr>
          <tr>
            <!-- Customer Info Block -->
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan='2' class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
              </tr>
              <tr>
                <td colspan='2' class="main"><?php echo show_address_entry('update_customer_', $order->customer, true);?></td>
              </tr>
            </table></td>
            <!-- Billing Address Block -->
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan='2' class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td colspan='2' class="main"><?php echo show_address_entry('update_billing_', $order->billing);?></td>
              </tr>
            </table></td>
            <!-- Shipping Address Block -->
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main"><?php echo show_address_entry('update_delivery_', $order->delivery); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<!-- End Addresses Block -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
            <td class="main"><input name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
            <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
          </tr>
        </table></td>
      </tr>
<!-- End Phone/Email Block -->

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!--begin select shipping and payment Block-->
<?
  $include_modules = array();
  if (tep_not_null(MODULE_SHIPPING_INSTALLED)) {
    $cart = new shoppingCart($oID);
    $cart->calculate();
    $shipping_weight = $cart->show_weight();
    $total_weight = $shipping_weight;
    $total_count = $cart->count_contents();
    require_once(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping();
    $quotes = $shipping_modules->quote();
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
      if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
        $free_shipping = true;
        include_once(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
      }
    } else {
      $free_shipping = false;
    }
  }

      if (tep_not_null(MODULE_PAYMENT_INSTALLED)) {
        require_once(DIR_WS_CLASSES . 'payment.php');
        $payment_modules = new payment;
      }

?>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right">&nbsp;</td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } elseif ($free_shipping == false) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    }

    if ($free_shipping == true) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, 0)">
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        if (isset($quotes[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $order->info['shipping_class']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, ' . $radio_buttons . ')">' . "\n";
            } else {
              echo '                  <tr class="moduleRow" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, ' . $radio_buttons . ')">' . "\n";
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
<?php
            } else {
?>
                    <td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
<?php
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
            $radio_buttons++;
          }
        }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      }
    }
?>
            </table></td>
          </tr>
        </table>
</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">

<?php
  $selection = $payment_modules->selection();

  if (sizeof($selection) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right">&nbsp;</td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
              <tr id="<?php echo $selection[$i]['id']; ?>_payment">
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if ( ($selection[$i]['id'] == $order->info['payment_class']) || ($n == 1) ) {
      echo '                  <tr id="defaultSelected_paym" class="moduleRowSelected" onmouseover="rowOverEffect_paym(this)" onmouseout="rowOutEffect_paym(this)" onclick="selectRowEffect_paym(this, ' . $radio_buttons . ')">' . "\n";
    } else {
      echo '                  <tr class="moduleRow" onmouseover="rowOverEffect_paym(this)" onmouseout="rowOutEffect_paym(this)" onclick="selectRowEffect_paym(this, ' . $radio_buttons . ')">' . "\n";
    }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3" width=100%><b><?php echo $selection[$i]['module']; ?></b></td>
                    <td class="main" align="right">
<?php
    if (sizeof($selection) > 1) {
      echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $order->info['payment_class']));
    } else {
      echo tep_draw_hidden_field('payment', $selection[$i]['id']);
    }
?>
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    $radio_buttons++;
  }
?>
                </table></td>
              </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<!-- End Payment Block -->
    <!-- Begin Credit Card Info Block -->
      <tr>
        <td><table cellpadding=0 cellspacing=0 border=0>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><input name='update_info_cc_type' size='10' value='<?php echo $order->info['cc_type']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><input name='update_info_cc_owner' size='20' value='<?php echo $order->info['cc_owner']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><input name='update_info_cc_number' size='20' value='<?php echo $order->info['cc_number']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_CVN; ?></td>
            <td class="main"><input name='update_info_cc_cvn' size='4' value='<?php echo $order->info['cc_cvn']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><input name='update_info_cc_expires' size='4' value='<?php echo $order->info['cc_expires']; ?>'></td>
          </tr>
        </table></td>
      </tr>
    <!-- End Credit Card Info Block -->

      <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></td>
    </tr>

  <!-- Begin Products Listings Block -->
  <?
        // Override order.php Class's Field Limitations
    $index = 0;
    $order->products = array();
    $orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
    while ($orders_products = tep_db_fetch_array($orders_products_query)) {
      $order->products[$index] = array('qty' => $orders_products['products_quantity'],
      'name' => str_replace("'", "&#39;", $orders_products['products_name']),
      'model' => $orders_products['products_model'],
      'tax' => $orders_products['products_tax'],
      'price' => $orders_products['products_price'],
      'final_price' => $orders_products['final_price'],
      'orders_products_id' => $orders_products['orders_products_id']);

      $subindex = 0;
      $attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'";
      $attributes_query = tep_db_query($attributes_query_string);

      if (tep_db_num_rows($attributes_query)) {
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $order->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
          'value' => $attributes['products_options_values'],
          'prefix' => $attributes['price_prefix'],
          'price' => $attributes['options_values_price'],
          'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);
          $subindex++;
        }
      }
      $index++;
    }

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_zone_id, sum(tax_rate) as rate,  tax_description from " . TABLE_TAX_RATES . " group by tax_class_id, tax_zone_id order by tax_description");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $query = tep_db_query("select * from " . TABLE_TAX_RATES . " where tax_class_id = '" . $tax_class['tax_class_id'] . "' and tax_zone_id = '" . $tax_class['tax_zone_id'] . "'");
      if (tep_db_num_rows($query) > 1){
        $str = '';
        while ($data = tep_db_fetch_array($query)){
          if ($str == ''){
            $str .= $data['tax_description'];
          }else{
            $str .= " + " . $data['tax_description'];
          }
        }
        $tax_class['tax_description'] = $str;
      }
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'] . '_' . $tax_class['tax_zone_id'],
                                 'text' => $tax_class['tax_description'] . ' ' . $tax_class['rate'],
                                 'rate' => $tax_class['rate']);
    }
//echo "<pre>"; print_r($order->products);echo "</pre>";
  for ($i=0; $i<sizeof($order->products); $i++) {
    $orders_products_id = $order->products[$i]['orders_products_id'];

    $RowStyle = "dataTableContent";

    echo '    <tr class="dataTableRow">' . "\n" .
       '      <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" . tep_draw_hidden_field('update_products[' . $orders_products_id . '][old_qty]', $order->products[$i]['qty']) .
       '      <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='25' value='" . $order->products[$i]['name'] . "'>";

    // Has Attributes?
    if (sizeof($order->products[$i]['attributes']) > 0) {
      for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
        $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
        echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value=\"" . htmlspecialchars($order->products[$i]['attributes'][$j]['option']) . "\">" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value=\"" . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) . "\">";
        echo '</i></small></nobr>' . tep_draw_hidden_field('update_products[' . $orders_products_id . '][attrib][' . $orders_products_attributes_id . ']', $orders_products_attributes_id);
      }
    }

    echo '      </td>' . "\n" .
         '      <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
         '      <td class="' . $RowStyle . '" align="center" valign="top">';
    $selected_tax = false;
    for ($l=0,$n=sizeof($tax_class_array);$l<$n;$l++){
      if ($tax_class_array[$l]['rate'] == $order->products[$i]['tax']){
        $selected_tax = $tax_class_array[$l]['id'];
        break;
      }
    }
      //$tax_data = tep_db_fetch_array(tep_db_query("select tax_class_id from " . TABLE_TAX_RATES . " where  tax_rate = " . $order->products[$i]['tax']));
    echo tep_draw_pull_down_menu("update_products[$orders_products_id][tax]", $tax_class_array, $selected_tax) . '</td>' . "\n";
    //}
         // . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . tep_display_tax_value($order->products[$i]['tax']) . "'>" . '%</td>' . "\n" .
   echo       '      <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>' . "\n" .
         '      <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
         '    </tr>' . "\n";
  }
  ?>
  <!-- End Products Listings Block -->

  <!-- Begin Order Total Block -->

    <tr>
      <td align="right" colspan="6"><table border="0" cellspacing="0" cellpadding="2" width="100%">
        <tr>
          <td align='center' valign='top' class=main><br><a href="<? echo tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oID . '&action=add_product&step=1'); ?>"><u><b><?php echo TEXT_ADD_A_NEW_PRODUCT; ?></b></u></a></td>
          <td align='right'><table border="0" cellspacing="0" cellpadding="2">
<?php
// Override order.php Class's Field Limitations
  $sort_orders = array();

  require_once(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order->info['shipping_method'] = $order->info['shipping_class'];
  $shipping = array('id' => $order->info['shipping_class']);
  $data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = 'ot_shipping' order by sort_order"));
  $order->info['shipping_cost'] = number_format($data['value'], 2, '.', '');
  $order->info['total'] += $order->info['shipping_cost'];

  $order->totals = array();
  $TotalsArray = array();
  if (is_array($order_total_modules->modules)) {
    reset($order_total_modules->modules);
    while (list(, $value) = each($order_total_modules->modules)) {
      $class = substr($value, 0, strrpos($value, '.'));
      if ($GLOBALS[$class]->enabled) {
        $GLOBALS[$class]->process();

        if (sizeof($GLOBALS[$class]->output)){
          for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
            if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
              $order->totals[] = array('class' => $GLOBALS[$class]->code,
                                       'title' => $GLOBALS[$class]->output[$i]['title'],
                                       'text' => $GLOBALS[$class]->output[$i]['text'],
                                       'value' => $GLOBALS[$class]->output[$i]['value'],
                                       'sort_order' => $GLOBALS[$class]->sort_order);
              $sort_orders[] = $GLOBALS[$class]->sort_order;
              $TotalsArray[] = array("Name" => $GLOBALS[$class]->output[$i]['title'],
                "Price" => number_format($GLOBALS[$class]->output[$i]['value'], 2, '.', ''),
                "Class" => $GLOBALS[$class]->code,
                "sort_order" => $GLOBALS[$class]->sort_order,
                "TotalID" => $GLOBALS[$class]->sort_order);

            }

            $query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = '" . $GLOBALS[$class]->code . "' order by sort_order");
            if (tep_db_num_rows($query)){
              while ($data = tep_db_fetch_array($query)){
                $order->totals[sizeof($order->totals) - 1]['orders_total_id'] = $data['orders_total_id'];
                $TotalsArray[sizeof($TotalsArray) - 1]['TotalID'] = $data['orders_total_id'];
                if ($GLOBALS[$class]->code != 'ot_tax')
                  $TotalsArray[sizeof($TotalsArray) - 1]['Name'] = $data['title'];
              }
            }
          }
        }else{
          $order->totals[] = array('class' => $GLOBALS[$class]->code,
          'title' => $GLOBALS[$class]->title . ':',
          'text' => '',
          'value' => '',
          'sort_order' => $GLOBALS[$class]->sort_order);
          $sort_orders[] = $GLOBALS[$class]->sort_order;
          $TotalsArray[] = array("Name" => $GLOBALS[$class]->title . ':',
          "Price" => '',
          "Class" => $GLOBALS[$class]->code,
          "sort_order" => $GLOBALS[$class]->sort_order);
          $query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' and class = '" . $GLOBALS[$class]->code . "' order by sort_order");
          if (tep_db_num_rows($query)){
            $data = tep_db_fetch_array($query);
            $order->totals[sizeof($order->totals) - 1]['orders_total_id'] = $data['orders_total_id'];
            $order->totals[sizeof($order->totals) - 1]['value'] = number_format($totals['value'], 2, '.', '');
            $TotalsArray[sizeof($TotalsArray) - 1]['TotalID'] = $data['orders_total_id'];
            $TotalsArray[sizeof($TotalsArray) - 1]['Name'] = $data['title'];
            $TotalsArray[sizeof($TotalsArray) - 1]['Price'] = number_format($data['value'], 2, '.', '');
          }

        }

      }
    }
  }
/*
  $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
  $order->totals = array();
  while ($totals = tep_db_fetch_array($totals_query)) {
    $order->totals[] = array('title' => $totals['title'],
                             'text' => $totals['text'],
                             'class' => $totals['class'],
                             'value' => $totals['value'],
                             'orders_total_id' => $totals['orders_total_id']);
    $sort_orders[] = $totals['sort_order'];
    $TotalsArray[] = array("Name" => $totals['title'],
                           "Price" => number_format($totals['value'], 2, '.', ''),
                           "Class" => $totals['class'],
                           "sort_order" => $totals['sort_order'],
                           "TotalID" => $totals['orders_total_id']);
  }
*/
  //echo '<pre>'; print_r($TotalsArray); echo '</pre>';
  rsort($sort_orders);
  if (($sort_orders[0]-1)<=$sort_orders[1]){
    $new_sort_order = $sort_orders[0];
    $TotalsArray[count($TotalsArray)-1]["sort_order"] = $new_sort_order + 1;
  } else {
    $new_sort_order = $sort_orders[1]+1;
  }
  $TotalsArray[] = array("Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0", 'sort_order' => $new_sort_order);
  foreach($TotalsArray as $TotalIndex => $TotalDetails) {
    $TotalStyle = "smallText";
    if(($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total")) {
      echo  '       <tr>' . "\n" .
        '   <td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
        '   <td class="main"><b>' . $TotalDetails["Price"] .
            "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" .
            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
        '       </tr>' . "\n";
      echo tep_draw_hidden_field('update_totals[' . $TotalIndex . '][sort_order]', $TotalDetails['sort_order']);
    } elseif($TotalDetails["Class"] == "ot_tax") {
      echo  '       <tr>' . "\n" .
        '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim($TotalDetails["Name"])) . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
        '   <td class="main"><b>' . $TotalDetails["Price"] .
            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
        '       </tr>' . "\n";
      echo tep_draw_hidden_field('update_totals[' . $TotalIndex . '][sort_order]', $TotalDetails['sort_order']);
    } else {
      echo  '       <tr>' . "\n" .
        '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim($TotalDetails["Name"])) . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
        '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
            "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" .
            '</td>' . "\n" .
        '       </tr>' . "\n";
      echo tep_draw_hidden_field('update_totals[' . $TotalIndex . '][sort_order]', $TotalDetails['sort_order']);
    }
  }
?>
            <tr>
              <td class=main align="right"><?php echo TEXT_CALCULATE_TOTALS; ?></td>
              <td class=main><?php echo tep_draw_checkbox_field('calculate_totals', '1', true); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  <!-- End Order Total Block -->

  </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <? if($CommentsWithStatus) { ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
            <? } ?>
          </tr>
<?php
    $orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";

        if($CommentsWithStatus) {
        echo '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
        }

        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr><?php// echo tep_draw_form('update_status', "edit_orders.php", tep_get_all_get_params(array('action','paycc')) . 'action=update_status'); ?>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main">
        <?
        if($CommentsWithStatus) {
          echo tep_draw_textarea_field('comments', 'soft', '60', '5');
        }
        else
        {
          echo tep_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']);
        }
        ?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b>
            <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
          </tr>
          <? if($CommentsWithStatus) { ?>
          <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
          </tr>
          <? } ?>
        </table></td>
      </tr>

      <tr>
        <td align='center' valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>
<?php
  }
if($action == "add_product") {
?>
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'step') )) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border='0' cellpadding=2 cellspacing=0>
          <tr>
            <td class="main">
              <?php // Step 1: Choose Product
                echo tep_draw_form('search', FILENAME_EDIT_ORDERS, '', 'get') . tep_draw_hidden_field(tep_session_name(), tep_session_id()) . tep_draw_hidden_field('action', $HTTP_GET_VARS['action']) . tep_draw_hidden_field('oID', $oID) ;
                echo HEADING_TITLE_SEARCH_PRODUCTS;
              ?>
            </td>
            <td class="main" colspan=2><?php echo tep_draw_input_field('search');?></td>
            <td class="main"><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH);?></td>
          </tr>
          </form>
              <?php // Step 1: Choose Product
                echo tep_draw_form('search', FILENAME_EDIT_ORDERS, '', 'get') . tep_draw_hidden_field(tep_session_name(), tep_session_id()) . tep_draw_hidden_field('action', $HTTP_GET_VARS['action']) . tep_draw_hidden_field('oID', $oID) . tep_draw_hidden_field('search', tep_output_string(stripslashes($HTTP_GET_VARS['search'])));
                //echo HEADING_TITLE_SEARCH_PRODUCTS;
              ?>

<?php
  $order = new order($oID);
  $group_id = tep_get_customers_group($order->customer['customer_id']);
  if (strlen($HTTP_GET_VARS['search'])){
    $products_array = array();
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status =1 and p.products_id = pd.products_id and (pd.products_name like '%" . $HTTP_GET_VARS['search']. "%' or p.products_model like '%" . $HTTP_GET_VARS['search']. "%' or p.products_price like '%" . $HTTP_GET_VARS['search']. "%' ) and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products['products_price'] = tep_get_products_price_edit_order($products['products_id'], $currencies->currencies[$order->info['currency']]['id'], $group_id, 1, true);
      $products_array[] = array('id' => $products['products_id'], 'text' => $products['products_name'] . ' (' . $currencies->format(tep_get_products_price_edit_order($products['products_id'], $currencies->currencies[$order->info['currency']]['id'], $group_id, 1, true), (USE_MARKET_PRICES != 'True'), $order->info['currency']) . ')');
    }
  } else {
    $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p");
    $d = tep_db_fetch_array($products_query);
    $products_array = array();
    if ($d['total'] <= MAX_PRODUCTS_PULLDOWN_WO_FILTER){

      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where  p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 order by products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'], 'text' => $products['products_name'] . ' (' . $currencies->format(tep_get_products_price_edit_order($products['products_id'],  $currencies->currencies[$order->info['currency']]['id'], $group_id, 1, true), (USE_MARKET_PRICES != 'True'), $order->info['currency']) . ')');
      }
    } else {
      $products_array[] = array('id' => 0, 'text' => TEXT_APPLY_FILTER);
    }
  }
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT; ?></td>
            <td class="main" colspan=2><?php
              if (count($products_array)>0){
                echo tep_draw_pull_down_menu('add_product_products_id', $products_array);
                echo tep_draw_hidden_field('step','2');
              }else{
                echo TEXT_NO_PRODUCTS_FOUND;
              }
              ?>
            </td>
            <td class="main"><?php
              echo tep_image_submit('button_select.gif', IMAGE_SELECT, (count($products_array)>0?'name="select"':''));?>
            </td>
          </form>
          </tr>
        </td>
      </tr>
      <tr>
<?php
    if (isset($HTTP_GET_VARS['select_x']) || isset($HTTP_GET_VARS['select_y'])) {
      echo tep_draw_form('add_product', FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('step')));
      // Get Options for Products
      $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id and po.language_id = '" . (int)$languages_id . "' LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id and pov.language_id = '" . (int)$languages_id . "' WHERE products_id='$add_product_products_id'");
      if(tep_db_num_rows($result) != 0) {
        ?>
        <td class="main"><?php echo TEXT_OPTIONS; ?></td>
        <?php
        while($row = tep_db_fetch_array($result)) {
          extract($row, EXTR_PREFIX_ALL, "db");
          $Options[$db_products_options_id] = htmlspecialchars($db_products_options_name);
          $ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = htmlspecialchars($db_products_options_values_name);
        }
        foreach($ProductOptionValues as $OptionID => $OptionValues) {
?>
          <td class=main><b><?php echo $Options[$OptionID];?>:</b></td>
          <td><select name='add_product_options[<?php echo $OptionID?>]' >
<?php
              foreach($OptionValues as $OptionValueID => $OptionValueName) {
                echo "<option value='$OptionValueID'>" . $OptionValueName . "</option>\n";
              }
?>
            </select>
          </td>
          <td class=main></td>
        </tr>
        <tr>
          <td class=main></td>
          <?
        }
      }
      //if (count($products_array) == 1){
      ?>
        </tr>
        <tr>
          <td class="main" valign='top'><?php echo TEXT_QUANTITY?></td>
          <td class="main" valign='top' colspan=2><?php echo tep_draw_input_field('add_product_quantity', '1', 'size="2"'); ?></td>
          <td class="main" valign='top'><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . tep_draw_hidden_field('add_product_products_id', $HTTP_GET_VARS['add_product_products_id']) . tep_draw_hidden_field('step', 3) ; ?></td>

      </tr>
      <?php
      //}
      ?>
      </form>
<?php
    }
      ?>
        </table></td>
      </tr>
<?php
}
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?
  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_country_id
  //
  // Arguments   : country_name   country name string
  //
  // Return      : country_id
  //
  // Description : Function to retrieve the country_id based on the country's name
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_country_id($country_name) {

    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($country_name) . "'");

    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_get_country_iso_code_2
  //
  // Arguments   : country_id   country id number
  //
  // Return      : country_iso_code_2
  //
  // Description : Function to retrieve the country_iso_code_2 based on the country's id
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_get_country_iso_code_2($country_id) {

    $country_iso_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'");

    if (!tep_db_num_rows($country_iso_query)) {
      return 0;
    }
    else {
      $country_iso_row = tep_db_fetch_array($country_iso_query);
      return $country_iso_row['countries_iso_code_2'];
    }
  }


  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_field_exists
  //
  // Arguments   : table  table name
  //               field  field name
  //
  // Return      : true/false
  //
  // Description : Function to check the existence of a database field
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_field_exists($table,$field) {

    $describe_query = tep_db_query("describe $table");
    while($d_row = tep_db_fetch_array($describe_query))
    {
      if ($d_row["Field"] == "$field")
      return true;
    }

    return false;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_html_quotes
  //
  // Arguments   : string any string
  //
  // Return      : string with single quotes converted to html equivalent
  //
  // Description : Function to change quotes to HTML equivalents for form inputs.
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////
  //
  // Function    : tep_html_unquote
  //
  // Arguments   : string any string
  //
  // Return      : string with html equivalent converted back to single quotes
  //
  // Description : Function to change HTML equivalents back to quotes
  //
  ////////////////////////////////////////////////////////////////////////////////////////////////
  function tep_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }

require(DIR_WS_INCLUDES . 'application_bottom.php');

function show_address_entry($prefix, $entry, $hidden=false){
  Global $languages_id;
  if ($hidden){
    $str = '';
    $str .= '        <table width="100%" border="0">';
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_NAME . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['name']) . tep_draw_hidden_field($prefix . 'name', $entry['name']) . '</td></tr>';
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_COMPANY . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['company']) . tep_draw_hidden_field($prefix . 'company', $entry['company']) . '</td></tr>';
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_STREET_ADDRESS . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['street_address']) . tep_draw_hidden_field($prefix . 'street_address', $entry['street_address']) . '</td></tr>';
if (ACCOUNT_SUBURB == 'true') {
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_SUBURB . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['suburb']) . tep_draw_hidden_field($prefix . 'suburb', $entry['suburb']) . '</td></tr>';
}
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_CITY . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['city']) . tep_draw_hidden_field($prefix . 'city', $entry['city']) . '</td></tr>';
if (ACCOUNT_STATE == 'true') {
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_STATE . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['state']) . tep_draw_hidden_field($prefix . 'state', $entry['state']) . '</td></tr>';
}
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_POST_CODE . '</td>';
    $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['postcode']) . tep_draw_hidden_field($prefix . 'postcode', $entry['postcode']) . '</td></tr>';
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_COUNTRY . '</td>';
    if (is_array($entry['country'])){
      $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['country']['title']) . tep_draw_hidden_field($prefix . 'country', $entry['country']['title']) . '</td></tr>';
    } else {
      $str .= '          <td width="50%" class="main">' . tep_html_quotes($entry['country']) . tep_draw_hidden_field($prefix . 'country', $entry['country']) . '</td></tr>';
    }
    $str .= '          </table>';
  } else {
    $str = '';
    $str .= '        <table width="100%" border="0">';
    $str .= '          <tr><td width="50%" class="main">' . ENTRY_NAME . '</td>';
    $str .= '            <td width="50%" class="main">' . tep_draw_input_field($prefix . 'name', $entry['name'],  'size="25"') . '</td></tr>';
    $str .= '          <tr><td class="main">' . ENTRY_COMPANY . '</td>';
    $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'company', $entry['company'],  'size="25"') . '</td></tr>';

    $str .= '          <tr><td class="main">' . ENTRY_STREET_ADDRESS . '</td>';
    $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'street_address', $entry['street_address'],  'size="25"') . '</td></tr>';
if (ACCOUNT_SUBURB == 'true') {
    $str .= '          <tr><td class="main">' . ENTRY_SUBURB . '</td>';
    $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'suburb', $entry['suburb'],  'size="25"') . '</td></tr>';
}
    $str .= '          <tr><td class="main">' . ENTRY_CITY . '</td>';
    $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'city', $entry['city'],  'size="25"') . '</td></tr>';
if (ACCOUNT_STATE == 'true') {
    $str .= '          <tr><td class="main">' . ENTRY_STATE . '</td>';
}
    if (is_array($entry['country'])){
      $res = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_id = '" . $entry['country']['id'] . "' and language_id = '" . (int)$languages_id . "'");
    } else {
      $res = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name like '" . tep_db_input($entry['country']) . "' and language_id = '" . (int)$languages_id . "'");
    }
    $d = tep_db_fetch_array($res);
if (ACCOUNT_STATE == 'true') {
    $res = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id='" . $d['countries_id'] . "'");
    $check = tep_db_fetch_array($res);
    if ($check['total']>0){
      $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $d['countries_id'] . "' order by zone_name");
      $zones_array = array();
      while ($zones = tep_db_fetch_array($zones_query)) {
        $zones_array[] = array('id' => $zones['zone_name'],
                               'text' => $zones['zone_name']);
      }
      $str .= '          <td class="main">' . tep_draw_pull_down_menu($prefix . 'state', $zones_array, $entry['state'],  'style="width:165px"') . '</td></tr>';
    } else {
      $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'state', $entry['state'],  'size="25"') . '</td></tr>';
    }
}
    $str .= '          <tr><td class="main">' . ENTRY_POST_CODE . '</td>';
    $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'postcode', $entry['postcode'],  'size="25"') . '</td></tr>';
    $str .= '          <tr><td class="main">' . ENTRY_COUNTRY . '</td>';

    if ($d['countries_id']){
      $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where language_id = '" . (int)$languages_id . "' order by countries_name");
      $countries_array = array();
      while ($countries = tep_db_fetch_array($countries_query)) {
        $countries_array[] = array('id' => $countries['countries_name'],
                                   'text' => $countries['countries_name']);
      }
      $str .= '          <td class="main">' . tep_draw_pull_down_menu($prefix . 'country', $countries_array, $d['countries_name'], 'style="width:165px"') . '</td></tr>';
    } else {
      $str .= '          <td class="main">' . tep_draw_input_field($prefix . 'country', $entry['country'],  'style="width:100px"') . '</td></tr>';
    }
    $str .= '          </table>';
  }
  return $str;
}
?>
