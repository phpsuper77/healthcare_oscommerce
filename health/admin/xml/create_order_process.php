<?php
  $default_shipping_method = 'Flat Rate';

  require('includes/application_top.php');

  if (!isset($HTTP_POST_VARS['customers_id']) || !($HTTP_POST_VARS['customers_id']>0)) {
    $messageStack->add_session(TEXT_CUSTOMER_IS_NOT_SELECTED, 'warning');
    tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, ''));
  }
  include(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $individual_id = tep_db_prepare_input($HTTP_POST_VARS['individual_id']);
  // added by Art. Start
  $orders_type = tep_db_prepare_input($HTTP_POST_VARS['orders_type']);
  // added by Art. Stop
  $admin_id = 0;
  $res = tep_db_query(" select * from " . TABLE_ADMIN . " where individual_id like '" . tep_db_input($individual_id) . "'");
  if ($d = tep_db_fetch_array($res)){
    $admin_id = $d['admin_id'];
  }

  $customer_id = tep_db_prepare_input($HTTP_POST_VARS['customers_id']);
  $res = tep_db_query("select c.*, ab.*, z.zone_name, cr.address_format_id, cr.countries_name from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on z.zone_id=ab.entry_zone_id , " . TABLE_COUNTRIES . " cr where cr.countries_id=ab.entry_country_id and ab.customers_id='" . (int)$customer_id  . "' and c.customers_id='" . (int)$customer_id  . "' and c.customers_default_address_id=ab.address_book_id  and cr.language_id = '" . (int)$languages_id . "' ");
  $c_info = tep_db_fetch_array($res);
  if ((ACCOUNT_STATE == 'true') && tep_not_null($c_info['zone_name'])){
    $c_info['entry_state'] = $c_info['zone_name'];
  }
///// billing address
  $gender = tep_db_prepare_input($HTTP_POST_VARS['entry_gender']);
  $firstname = tep_db_prepare_input($HTTP_POST_VARS['entry_firstname']);
  $lastname = tep_db_prepare_input($HTTP_POST_VARS['entry_lastname']);
  $company = tep_db_prepare_input($HTTP_POST_VARS['entry_company']);
  $street_address = tep_db_prepare_input($HTTP_POST_VARS['entry_street_address']);

  if (ACCOUNT_SUBURB == 'true')  $suburb = tep_db_prepare_input($HTTP_POST_VARS['entry_suburb']);
  $postcode = tep_db_prepare_input($HTTP_POST_VARS['entry_postcode']);
  $city = tep_db_prepare_input($HTTP_POST_VARS['entry_city']);
  $state = tep_db_prepare_input($HTTP_POST_VARS['entry_state']);

  $res = tep_db_query("select address_format_id, countries_name from " . TABLE_COUNTRIES . " where countries_id='" . $HTTP_POST_VARS['entry_country_id'] . "'  and language_id = '" . (int)$languages_id . "'");
  $country_info = tep_db_fetch_array($res);
  $format_id = $country_info['address_format_id'];
  $country = tep_db_prepare_input($country_info['countries_name']);
if (ACCOUNT_STATE == 'true') {
  $res = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id='" . $HTTP_POST_VARS['entry_country_id'] . "'");
  $check = tep_db_fetch_array($res);

  if ($check['total']>0){
    $res = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id='" . $HTTP_POST_VARS['entry_country_id'] . "' and (zone_name like '" . tep_db_input($state) . "' or zone_code like '" . tep_db_input($state) . "')");
    if ($d = tep_db_fetch_array($res)){
      $zone_id = tep_db_prepare_input($d['zone_id']);
      $state = $d['zone_name'];
    } else {
      $messageStack->add_session(sprintf(WARNING_ORDER_INCORRECT_ZONE, $state), 'warning');
      $state = '';
      $zone_id = 0;
    }
  } else {
    $zone_id = 0;
  }
}
////////////// shipping address
  
  $s_gender = tep_db_prepare_input($HTTP_POST_VARS['s_entry_gender']);
  $s_firstname = tep_db_prepare_input($HTTP_POST_VARS['s_entry_firstname']);
  $s_lastname = tep_db_prepare_input($HTTP_POST_VARS['s_entry_lastname']);
  $s_company = tep_db_prepare_input($HTTP_POST_VARS['s_entry_company']);
  $s_street_address = tep_db_prepare_input($HTTP_POST_VARS['s_entry_street_address']);
  if (ACCOUNT_SUBURB == 'true')  $s_suburb = tep_db_prepare_input($HTTP_POST_VARS['s_entry_suburb']);
  $s_postcode = tep_db_prepare_input($HTTP_POST_VARS['s_entry_postcode']);
  $s_city = tep_db_prepare_input($HTTP_POST_VARS['s_entry_city']);
  $s_state = tep_db_prepare_input($HTTP_POST_VARS['s_entry_state']);

  $res = tep_db_query("select address_format_id, countries_name from " . TABLE_COUNTRIES . " where countries_id='" . $HTTP_POST_VARS['s_entry_country_id'] . "'  and language_id = '" . (int)$languages_id . "'");
  $s_country_info = tep_db_fetch_array($res);
  $s_format_id = $s_country_info['address_format_id'];
  $s_country = tep_db_prepare_input($s_country_info['countries_name']);
if (ACCOUNT_STATE == 'true') {
  $res = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id='" . $HTTP_POST_VARS['s_entry_country_id'] . "'");
  $check = tep_db_fetch_array($res);
  if ($check['total']>0){
    $res = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id='" . $HTTP_POST_VARS['s_entry_country_id'] . "' and (zone_name like '" . tep_db_input($s_state) . "' or zone_code like '" . tep_db_input($s_state) . "')");
    if ($d = tep_db_fetch_array($res)){
      $s_zone_id = tep_db_prepare_input($d['zone_id']);
      $s_state = $d['zone_name'];
    } else {
      $messageStack->add_session(sprintf(WARNING_ORDER_INCORRECT_SHIPPING_ZONE, $s_state), 'warning');
      $s_state = '';
      $s_zone_id = 0;
    }
  } else {
    $s_zone_id = 0;
  }
}

  $size = "1";
  $payment_method = "Change";
  $new_value = DEFAULT_ORDERS_STATUS_ID;
  $temp_amount = "0";
  $temp_amount = number_format($currency, 2, '.', '');
  $currency = DEFAULT_CURRENCY;
  $currency_value = "1";
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);

  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);

    $sql_data_array = array('customers_id' => $customer_id,
              'customers_name' => $c_info['customers_firstname'] . ' ' . $c_info['customers_lastname'],
              'customers_firstname' =>$c_info['customers_firstname'],
              'customers_lastname' =>$c_info['customers_lastname'],
              'customers_company' => $c_info['entry_company'],
              'customers_street_address' => $c_info['entry_street_address'],
              'customers_suburb' => ((ACCOUNT_SUBURB == 'true')?$c_info['entry_suburb']:''),
              'customers_city' => $c_info['entry_city'],
              'customers_postcode' => $c_info['entry_postcode'],
              'customers_state' => (!empty($c_info['entry_state'])?$c_info['entry_state']:''),
              'customers_country' => $c_info['countries_name'],
              'customers_telephone' => $c_info['customers_telephone'],
              'customers_email_address' => $c_info['customers_email_address'],
              'customers_address_format_id' => $c_info['address_format_id'],
              'delivery_name' => $s_firstname . ' ' . $s_lastname,
              'delivery_company' => $s_company,
              'delivery_street_address' => $s_street_address,
              'delivery_suburb' => ((ACCOUNT_SUBURB == 'true')?$s_suburb:''),
              'delivery_city' => $s_city,
              'delivery_postcode' => $s_postcode,
              'delivery_state' => (!empty($s_state)?$s_state:''),
              'delivery_country' => $s_country,
              'delivery_address_format_id' => $s_format_id,
              'billing_name' => $firstname . ' ' . $lastname,
              'billing_company' => $company,
              'billing_street_address' => $street_address,
              'billing_suburb' => ((ACCOUNT_SUBURB == 'true')?$suburb:''),
              'billing_city' => $city,
              'billing_postcode' => $postcode,
              'billing_state' => (!empty($state)?$state:''),
              'billing_country' => $country,
              'billing_address_format_id' => $format_id,
              'date_purchased' => 'now()', 
              'orders_status' => DEFAULT_ORDERS_STATUS_ID,
              // added by Art. Start
              'orders_type' => $orders_type,
              // added by Art. Stop
              'currency' => $currency,
              'admin_id' => $admin_id,
              'payment_method' => 'Credit Card',
              'payment_class' => 'cc',
              'shipping_class' => 'flat',
              'shipping_method' => $default_shipping_method,
              'currency_value' => $currency_value,
              'language_id' => $languages_id);
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();

  $sql_data_array = array('orders_id' => $insert_id,
              //Comment out line you don't need
              //'new_value' => $new_value,  //for 2.2
              'orders_status_id' => $new_value, //for MS1 or MS2
              'date_added' => 'now()');
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

  $order = new order($insert_id);
  $cart = new shoppingCart($insert_id);
  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_totals = array();
  if (is_array($order_total_modules->modules)) {
    reset($order_total_modules->modules);
    while (list(, $value) = each($order_total_modules->modules)) {
      $class = substr($value, 0, strrpos($value, '.'));
      if ($GLOBALS[$class]->enabled) {
        $GLOBALS[$class]->process();
        if (count($GLOBALS[$class]->output)>0) {
          
          for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
            if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
              $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                     'title' => $GLOBALS[$class]->output[$i]['title'],
                                     'text' => $GLOBALS[$class]->output[$i]['text'],
                                     'value' => $GLOBALS[$class]->output[$i]['value'],
                                     'sort_order' => $GLOBALS[$class]->sort_order);
            } 
          }
        } else {
          $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                   'title' => $GLOBALS[$class]->title,
                                   'text' => $currencies->format(0),
                                   'value' => 0,
                                   'sort_order' => $GLOBALS[$class]->sort_order);
        }
      }
    }
  }

  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }
  
  /*$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id, 
                          'new_value' => DEFAULT_ORDERS_STATUS_ID, 
                          'date_added' => 'now()', 
                          'customer_notified' => $customer_notification);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/

  tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $insert_id, 'SSL'));

  //tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $insert_id.'&action=add_product&step=1', 'SSL'));

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>