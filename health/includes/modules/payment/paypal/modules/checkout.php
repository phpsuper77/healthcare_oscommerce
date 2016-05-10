<?php
/*
  $Id: checkout.php,v 1.1.1.1 2005/12/03 21:36:12 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (class_exists('PayPal_Address_Book') === false) {

    require_once(realpath(dirname(__FILE__) . '/../classes/address_book.php'));

  }

  class PayPal_Checkout {

    function process(&$paypal)
    {
      global $order, $currencies, $cart,
             $customer_id, $sendto, $billto, $payment, $currency, $language, $languages_id,
             $order_total_modules, $order_totals;

      if (class_exists('order_total') === false) {

        require_once(DIR_WS_CLASSES . 'order_total.php');

      }

      if (is_object($order_total_modules) === false)
        $order_total_modules = new order_total;

      if (is_array($order_totals) === false)
        $order_totals = $order_total_modules->process();

      $sql_data_array = array(
        'customers_id'                => $customer_id,
        'customers_name'              => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
        'customers_company'           => $order->customer['company'],
        'customers_street_address'    => $order->customer['street_address'],
        'customers_suburb'            => $order->customer['suburb'],
        'customers_city'              => $order->customer['city'],
        'customers_postcode'          => $order->customer['postcode'],
        'customers_state'             => $order->customer['state'],
        'customers_country'           => $order->customer['country']['title'],
        'customers_telephone'         => $order->customer['telephone'],
        'customers_email_address'     => $order->customer['email_address'],
        'customers_address_format_id' => $order->customer['format_id'],
        'delivery_name'               => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
        'delivery_company'            => $order->delivery['company'],
        'delivery_street_address'     => $order->delivery['street_address'],
        'delivery_suburb'             => $order->delivery['suburb'],
        'delivery_city'               => $order->delivery['city'],
        'delivery_postcode'           => $order->delivery['postcode'],
        'delivery_state'              => $order->delivery['state'],
        'delivery_country'            => $order->delivery['country']['title'],
        'delivery_address_format_id'  => $order->delivery['format_id'],
        'billing_name'                => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
        'billing_company'             => $order->billing['company'],
        'billing_street_address'      => $order->billing['street_address'],
        'billing_suburb'              => $order->billing['suburb'],
        'billing_city'                => $order->billing['city'],
        'billing_postcode'            => $order->billing['postcode'],
        'billing_state'               => $order->billing['state'],
        'billing_country'             => $order->billing['country']['title'],
        'billing_address_format_id'   => $order->billing['format_id'],
        'payment_method'              => $paypal->codeTitle,
        'cc_type'                     => $order->info['cc_type'],
        'cc_owner'                    => $order->info['cc_owner'],
        'cc_number'                   => $order->info['cc_number'],
        'cc_expires'                  => $order->info['cc_expires'],
        'date_purchased'              => 'now()',
        'orders_status'               => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
        'currency'                    => $order->info['currency'],
        'currency_value'              => $order->info['currency_value']
      );

      $checkoutSessionExists = false;

      if (isset($cart->txn_signature) === true && empty($cart->txn_signature) === false) {

        $checkout_session_query = tep_db_query("select osi.orders_id, o.payment_id from " . TABLE_PAYPAL_CHECKOUT . " osi left join " . TABLE_ORDERS . " o using (orders_id) where osi.txn_signature = '" . tep_db_input($cart->txn_signature) . "'");

        $orders_check = tep_db_fetch_array($checkout_session_query);

        //Now check to see whether order session info exists AND that this order
        //does not currently have an IPN.

        if ($orders_check['orders_id'] > 0 &&  $orders_check['payment_id'] == '0' ) {

          $checkoutSessionExists = true;

          $paypal->orders_id = $orders_check['orders_id'];

        }

      }

      if ($checkoutSessionExists === true) {

        tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$paypal->orders_id);

      } else {

        $sql_data_array['date_purchased'] = 'now()';

        tep_db_perform(TABLE_ORDERS, $sql_data_array);

        $paypal->orders_id = tep_db_insert_id();

      }

      if ($checkoutSessionExists === true)
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = " . (int)$paypal->orders_id);

      for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {

        $sql_data_array = array(
          'orders_id'  => (int)$paypal->orders_id,
          'title'      => $order_totals[$i]['title'],
          'text'       => $order_totals[$i]['text'],
          'value'      => $order_totals[$i]['value'],
          'class'      => $order_totals[$i]['code'],
          'sort_order' => $order_totals[$i]['sort_order']
        );

        tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

      }

      $sql_data_array = array(
        'orders_status_id'  => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
        'date_added'        => 'now()',
        'customer_notified' => 0,
        'comments'          => $order->info['comments']
      );

      if($checkoutSessionExists === true) {

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', "orders_id = " . (int)$paypal->orders_id);

        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = " . (int)$paypal->orders_id);

        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = " . (int)$paypal->orders_id);

      } else {

        $sql_data_array['orders_id'] = $paypal->orders_id;

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

      }

      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

        $sql_data_array = array(
          'orders_id'         => (int)$paypal->orders_id,
          'products_id'       => tep_get_prid($order->products[$i]['id']),
          'products_model'    => $order->products[$i]['model'],
          'products_name'     => $order->products[$i]['name'],
          'products_price'    => $order->products[$i]['price'],
          'final_price'       => $order->products[$i]['final_price'],
          'products_tax'      => $order->products[$i]['tax'],
          'products_quantity' => $order->products[$i]['qty']
        );

        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

        $order_products_id = tep_db_insert_id();

        if (is_callable(array($order_total_modules, 'update_credit_account')) === true) {

          $GLOBALS['insert_id'] = $paypal->orders_id;

          $order_total_modules->update_credit_account($i);

        }

        $attributes_exist = '0';

        if (isset($order->products[$i]['attributes'])) {

          $attributes_exist = '1';

          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

            $attributes = $this->queryProductsAttributes($j,$order->products[$i],$languages_id);

            $attributes_values = tep_db_fetch_array($attributes);

            $sql_data_array = array(
              'orders_id'                  => (int)$paypal->orders_id,
              'orders_products_id'         => $order_products_id,
              'products_options_id'        => $order->products[$i]['attributes'][$j]['option_id'],
              'products_options'           => $attributes_values['products_options_name'],
              'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id'],
              'products_options_values'    => $attributes_values['products_options_values_name'],
              'options_values_price'       => $attributes_values['options_values_price'],
              'price_prefix'               => $attributes_values['price_prefix']
            );

            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

          }//for each product attribute

        }//if product attributes

      }//for each product

      // store the session info for notification update
      $sql_data_array = array(
        'orders_id'            => (int)$paypal->orders_id,
        'customer_id'          => $customer_id,
        'sendto'               => $sendto,
        'billto'               => $billto,
        'firstname'            => $order->billing['firstname'],
        'lastname'             => $order->billing['lastname'],
        'payment'              => $payment,
        'payment_title'        => $paypal->codeTitle,
        'payment_amount'       => $paypal->grossPaymentAmount($paypal->currency()),
        'payment_currency'     => $paypal->currency(),
        'payment_currency_val' => $currencies->get_value($paypal->currency()),
        'language'             => $language,
        'language_id'          => $languages_id,
        'currency'             => $currency,
        'currency_value'       => $currencies->get_value($currency),
        'content_type'         => $order->content_type,
        'txn_signature'        => $paypal->setTransactionId()
      );

      if (tep_session_is_registered('affiliate_ref') && tep_not_null($GLOBALS['affiliate_ref'])) {

        $sql_data_array['affiliate_id']               = $GLOBALS['affiliate_ref'];
        $sql_data_array['affiliate_clickthroughs_id'] = $GLOBALS['affiliate_clickthroughs_id'];
        $sql_data_array['affiliate_date']             = $GLOBALS['affiliate_clientdate'];
        $sql_data_array['affiliate_browser']          = $GLOBALS['affiliate_clientbrowser'];
        $sql_data_array['affiliate_ipaddress']        = $GLOBALS['affiliate_clientip'];

      }

      //AuctionBlox
      if (isset($cart->basket_ids) === true && empty($cart->basket_ids) === false && is_array($cart->basket_ids) === true)
        $sql_data_array['abx_basket_ids'] = implode(',',$cart->basket_ids);

      if($checkoutSessionExists === true) {

        tep_db_perform(TABLE_PAYPAL_CHECKOUT,array('txn_signature' => $sql_data_array['txn_signature'],'checkout_info' => serialize($sql_data_array)),'update','orders_id = ' . (int)$paypal->orders_id);

        $cart->txn_signature = $paypal->digest;

      } else {

        tep_db_perform(TABLE_PAYPAL_CHECKOUT,array('orders_id' => $sql_data_array['orders_id'],'txn_signature' => $sql_data_array['txn_signature'],'checkout_info' => serialize($sql_data_array)));

        $cart->orders_id = $paypal->orders_id;

        $cart->txn_signature = $paypal->digest;

      }

      if(is_callable(array($order_total_modules, 'apply_credit')) === true)
        $order_total_modules->apply_credit();
    }

    function queryProductsAttributes($j,&$product,$language_id)
    {
      $attributes_query  = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix";

      $attributes_query .= (DOWNLOAD_ENABLED == 'true')
                           ? ", pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename "
                           : ' ';

      $attributes_query .= "from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa ";

      $attributes_query .= (DOWNLOAD_ENABLED == 'true')
                           ? "left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pa.products_attributes_id=pad.products_attributes_id "
                           : '';

      $attributes_query .= "where pa.products_id     = " . (int)$product['id'] . " ".
                           "and pa.options_id        = " . (int)$product['attributes'][$j]['option_id'] . " ".
                           "and pa.options_id        = popt.products_options_id ".
                           "and pa.options_values_id = " . (int)$product['attributes'][$j]['value_id'] . " ".
                           "and pa.options_values_id = poval.products_options_values_id ".
                           "and popt.language_id     = " . (int)$language_id . " ".
                           "and poval.language_id    = " . (int)$language_id;

      return tep_db_query($attributes_query);
    }

    function update(&$order)
    {
      $this->checkoutProducts($order);

      $this->notifyCustomer($order);

      $affiliate_ref              = $this->vars['affiliate_id'];

      $affiliate_clickthroughs_id = $this->vars['affiliate_clickthroughs_id'];

      $affiliate_clientdate       = $this->vars['affiliate_date'];

      $affiliate_clientbrowser    = $this->vars['affiliate_browser'];

      $affiliate_clientip         = $this->vars['affiliate_ipaddress'];

      if (empty($affiliate_ref) === false) {

        define('MODULE_PAYMENT_PAYPAL_SHOPPING_IPN_AFFILIATE','True');

        $insert_id = $this->vars['orders_id'];

        require_once(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');

      }

      $sql_data_array = array(
        'orders_status' => MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID,
        'last_modified' => 'now()'
      );

      tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$this->vars['orders_id']);


      tep_db_query("delete from " . TABLE_PAYPAL_CHECKOUT . " where orders_id = " . (int)$this->vars['orders_id']);
    }

    function catalogCheckoutUpdate(&$order)
    {
      $this->accountHistoryInfoURL       = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $this->vars['orders_id'], 'SSL', false);

      $this->checkoutProcessLanguageFile = DIR_WS_LANGUAGES . $this->vars['language'] . '/' . FILENAME_CHECKOUT_PROCESS;

      $this->update($order);
    }

    function adminCheckoutUpdate(&$order)
    {
      $this->accountHistoryInfoURL       = tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $this->vars['orders_id'], 'SSL', false);

      $this->checkoutProcessLanguageFile = DIR_FS_CATALOG_LANGUAGES . $this->vars['language'] . '/' . 'checkout_process.php';

      $this->update($order);
    }

    function loadFromSessionByTransactionSignature($txn_sign)
    {
      $txn_signature        = tep_db_prepare_input($txn_sign);

      $orders_session_query = tep_db_query("select orders_id, txn_signature, checkout_info from " . TABLE_PAYPAL_CHECKOUT . " where txn_signature ='" . tep_db_input($txn_signature) . "' limit 1");

      if (tep_db_num_rows($orders_session_query)) {

        $orders_session = tep_db_fetch_array($orders_session_query);

        $this->vars     = unserialize($orders_session['checkout_info']);

      }
    }

    function loadFromSessionByOrderId($orders_id)
    {
      $orders_session_query = tep_db_query("select orders_id, txn_signature, checkout_info from " . TABLE_PAYPAL_CHECKOUT . " where orders_id = " . (int)$orders_id . " limit 1");

      if (tep_db_num_rows($orders_session_query)) {

        $orders_session = tep_db_fetch_array($orders_session_query);

        $this->vars     = unserialize($orders_session['checkout_info']);

      }
    }

    function updateOrderStatusAndHistoryByPaymentId($paypal_id,$status)
    {
      //Orders
      $sql_data_array = array(
        'orders_status' => $status,
        'last_modified' => 'now()'
      );

      tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'payment_id = ' . (int)$paypal_id );

      //Orders Status History
      $sql_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_id = " . (int)$paypal_id);

      $sql_result = tep_db_fetch_array($sql_query);

      $sql_data_array = array(
        'orders_id'        => $sql_result['orders_id'],
        'orders_status_id' => $status,
        'date_added'       => 'now()'
      );

      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    }

    function checkoutDownloadProducts($orders_id,&$product,&$attributes_values)
    {
      //if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {
      if (DOWNLOAD_ENABLED == 'true' && strlen($attributes_values['products_attributes_filename']) > 0) {
        $sql_data_array = array(
          'orders_id'                => $orders_id,
          'orders_products_id'       => $product['orders_products_id'],
          'orders_products_filename' => $attributes_values['products_attributes_filename'],
          'download_maxdays'         => $attributes_values['products_attributes_maxdays'],
          'download_count'           => $attributes_values['products_attributes_maxcount']
        );

        tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);

      }
    }

    function checkoutProducts(&$order)
    {
      // initialized for the email confirmation
      $this->products_ordered = '';

      $subtotal = 0;

      $total_tax = 0;

      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

        // Stock Update - Joao Correia

        if (STOCK_LIMITED == 'true') {

          if (DOWNLOAD_ENABLED == 'true') {

            $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename ".
                               "FROM " . TABLE_PRODUCTS . " p ".
                               "LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa ".
                               "ON p.products_id=pa.products_id ".
                               "LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ".
                               "ON pa.products_attributes_id=pad.products_attributes_id ".
                               "WHERE p.products_id = " . (int)tep_get_prid($order->products[$i]['id']);

            // Will work with only one option for downloadable products
            // otherwise, we have to build the query dynamically with a loop
            $products_attributes = $order->products[$i]['attributes'];

            if (is_array($products_attributes)) {

              $stock_query_raw .= " AND pa.options_id = " . (int)$products_attributes[0]['option_id'] . " AND pa.options_values_id = " . (int)$products_attributes[0]['value_id'];

            }

            $stock_query = tep_db_query($stock_query_raw);

          } else {

            $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = " . (int)tep_get_prid($order->products[$i]['id']));

          }

          if (tep_db_num_rows($stock_query) > 0) {

            $stock_values = tep_db_fetch_array($stock_query);

            // do not decrement quantities if products_attributes_filename exists
            //if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
            if ((DOWNLOAD_ENABLED != 'true') || (DOWNLOAD_ENABLED == 'true' && strlen($stock_values['products_attributes_filename']) < 1)) {

              $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];

            } else {

              $stock_left = $stock_values['products_quantity'];

            }

            tep_db_perform(TABLE_PRODUCTS, array('products_quantity' => $stock_left), 'update', 'products_id = ' . (int)tep_get_prid($order->products[$i]['id']));

            //if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            if (STOCK_ALLOW_CHECKOUT == 'false' && $stock_left < 1) {

              tep_db_perform(TABLE_PRODUCTS, array('products_status' => '0'), 'update', 'products_id = ' . (int)tep_get_prid($order->products[$i]['id']));

            }

          }//if product exists

        }//if limited stock

        // Update products_ordered (for bestsellers list)
        tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = " . (int)tep_get_prid($order->products[$i]['id']));

        //------insert customer choosen option to order--------
        $attributes_exist = '0';

        $products_ordered_attributes = '';

        if (isset($order->products[$i]['attributes'])) {

          $attributes_exist = '1';

          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

            $attributes = $this->queryProductsAttributes($j,$order->products[$i],$this->vars['language_id']);

            $attributes_values = tep_db_fetch_array($attributes);

            $this->checkoutDownloadProducts($this->vars['orders_id'],$order->products[$i],$attributes_values);

            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];

          }//foreach product attribute

        }//if product has attributes
        //------insert customer choosen option eof ----

        //Commented out since unused
        //$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
        //$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
        //$total_cost += $total_products_price;

        //$currency_price = $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
        $products_ordered_price = $this->displayPrice($order->products[$i]['final_price'],$order->products[$i]['tax'],$order->products[$i]['qty']);

        $this->products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $products_ordered_price . $products_ordered_attributes . "\n";

      }//for each product
    }

    function displayPrice($amount, $tax, $qty = 1)
    {
      global $currencies;

      if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) )
        return $currencies->format(tep_add_tax($amount, $tax) * $qty, true, $this->vars['currency'], $this->vars['currency_value']);

      return $currencies->format($amount * $qty, true, $this->vars['currency'], $this->vars['currency_value']);
    }

    function notifyCustomer(&$order)
    {
      // lets start with the email confirmation
      require_once($this->checkoutProcessLanguageFile);

      $email_order = STORE_NAME . "\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $this->vars['orders_id'] . "\n" .
                     EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link($this->accountHistoryInfoURL) . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

      $customerComments = $this->getCustomerComments();

      if ($customerComments)
        $email_order .= tep_db_output($customerComments) . "\n\n";

      $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                      EMAIL_SEPARATOR . "\n" .
                      $this->products_ordered .
                      EMAIL_SEPARATOR . "\n";

      for ($i=0, $n=sizeof($order->totals); $i<$n; $i++)
        $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";


      if ($this->vars['content_type'] != 'virtual')
        $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .
                        EMAIL_SEPARATOR . "\n" .
                        PayPal_Address_Book::addressLabel($order->customer['id'], $this->vars['sendto'], 0, '', "\n") . "\n";


      $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                      EMAIL_SEPARATOR . "\n" .
                      PayPal_Address_Book::addressLabel($order->customer['id'], $this->vars['billto'], 0, '', "\n") . "\n\n";

      $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                      EMAIL_SEPARATOR . "\n";
      $email_order .= $this->vars['payment_title'] . "\n\n";

      tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '')
        tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      //Update Order Status History with notification status
      $sql_data_array = array(
        'orders_id'         => (int)$this->vars['orders_id'],
        'orders_status_id'  => MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID,
        'date_added'        => 'now()',
        'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0'
      );

      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    }

    function getCustomerComments()
    {
      $orders_history_query = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = " . (int)$this->vars['orders_id'] . " order by date_added limit 1");

      if (tep_db_num_rows($orders_history_query)) {

        $orders_history = tep_db_fetch_array($orders_history_query);

        return $orders_history['comments'];

      }

      return false;
    }

    function setOrderPaymentId($payment_id)
    {
      tep_db_perform(TABLE_ORDERS, array('payment_id' => (int)$payment_id), 'update', 'orders_id = ' . (int)$this->vars['orders_id'] );
    }

    function resetCustomersBasket($customer_id)
    {
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = " . (int)$customer_id);

      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = " . (int)$customer_id);
    }

    function &display()
    {
      $page = paypal::newPage();

      $page->setTitle(STORE_NAME);

      $page->setMetaTitle('');

      $page->setContentFile('checkout_process');

      $page->setTemplate('checkout_process');

      return $page;
    }

  }//end class
?>
