<?php
/*
  $Id: login.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require("includes/application_top.php");
include_once('controllers/front/FrontController.php');
$controller = new FrontController();
$canonical_tag = $controller->get_canonical_tag();

define('CHECKOUT_CTLPARAM_COMMON','style="width:250px;"');
  
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  tep_session_unregister("cot_gv");
  tep_session_unregister("credit_covers");
  tep_session_unregister("cc_id");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'opc_namespace.php');


  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $error = false;

    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id, groups_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_status = 1 " . (isset($affiliate_ref)?" and affiliate_id = '" . (int)$affiliate_ref . "'":''));
    if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
      if ( opc::is_temp_customer($check_customer['customers_id']) ){
        $error = true;
        $check_customer['customers_password'] = ' ';
        opc::remove_temp_customer( $check_customer['customers_id'] );
      };
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        if (CUSTOMERS_GROUPS_ENABLE == 'True') {
          $customer_groups_id = $check_customer['groups_id'];
        } else {
          $customer_groups_id = 0;
        }

        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');
        tep_session_register('customer_groups_id');

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();

        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
          //---PayPal WPP Modification START ---//
          //  tep_paypal_wpp_checkout_shipping_redirect($show_payment_page, $ec_enabled);
          //---PayPal WPP Modification END ---//

        }
      }
    }

    if ($error == true) {
      $messageStack->add('login', TEXT_LOGIN_ERROR);
    }
  }
  elseif (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'ec_page_checkout'))
  {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_TELEPHONE_NUMBER_ERROR);
    }
    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      if (tep_session_is_registered('customer_id')) {
        $check_email_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "' and affiliate_id = '" . ($affiliate_ref==''?0:$affiliate_ref) . "'");
        $total_c = 0;
        while ( $check_email = tep_db_fetch_array($check_email_query) ){
          if ( opc::is_temp_customer( $check_email['customers_id'] ) ) {
            opc::remove_temp_customer( $check_email['customers_id'] );
          }else{
            $total_c++;
          }
        }
        if ($total_c > 0) {
          $error = true;

          $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
        }
      }
    }
    $marketing_newsletter = 1;
    if (isset($HTTP_POST_VARS['newsletter'])) {
      $customers_newsletter = intval($HTTP_POST_VARS['newsletter']);
    } else {
      $customers_newsletter = 0;
    }
  }
  elseif (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout'))
  {
    if ( PAYPAL_WPP_DISABLE_DP=='true' ) {
      if ( MODULE_PAYMENT_PAYPAL_DP_BUTTON_PAYMENT_PAGE == 'Yes' && isset($HTTP_POST_VARS['payment']) && $HTTP_POST_VARS['payment']=='paypal_wpp' ) {
        // ops!
        tep_redirect( tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'action=express_checkout&return_to=' . FILENAME_CHECKOUT_PAYMENT, 'SSL') );
      }
    }

    $order = new order();

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['gender'])) {
        $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address_line1']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['street_address_line2']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
      if (is_int($HTTP_POST_VARS['state'])) {
        $zone_id = tep_db_prepare_input($HTTP_POST_VARS['state']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);

    $billto = tep_db_prepare_input($HTTP_POST_VARS['billto']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $gender = 'm';
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_FIRST_NAME_ERROR);
    }
	
    if (!tep_session_is_registered('customer_id') && defined('ONE_PAGE_CREATE_ACCOUNT') && (ONE_PAGE_CREATE_ACCOUNT=='pass' || ONE_PAGE_CREATE_ACCOUNT=='onebuy')) {
	    $new_password = tep_db_prepare_input($HTTP_POST_VARS['password_new']);
		$confirmation_new = tep_db_prepare_input($HTTP_POST_VARS['confirmation_new']); // DRF
		$check_for_error = ( ONE_PAGE_CREATE_ACCOUNT=='pass' ) || ( ONE_PAGE_CREATE_ACCOUNT=='onebuy' && isset($HTTP_POST_VARS['create_account']) && $HTTP_POST_VARS['create_account']==1 );

      if ( $check_for_error && ($new_password != $confirmation_new) ) {
        $error = true;
        $messageStack->add('one_page_checkout', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
      }
	    
	    if ( $check_for_error && strlen($new_password) < ENTRY_PASSWORD_MIN_LENGTH ){
        $error = true;
        $messageStack->add('one_page_checkout', ENTRY_PASSWORD_ERROR);  // DRF  
      }
    }
	
    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
        $error = true;
        $messageStack->add('one_page_checkout', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      if (!tep_session_is_registered('customer_id'))
      {
        $check_email_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and affiliate_id = '" . ($affiliate_ref==''?0:$affiliate_ref) . "'");
        $total_c = 0;
        while ( $check_email = tep_db_fetch_array($check_email_query) ){
          if ( opc::is_temp_customer( $check_email['customers_id'] ) ) {
            opc::remove_temp_customer( $check_email['customers_id'] );
          }else{
            $total_c++;
          }
        }        
        if ($total_c > 0) {
          $error = true;

          $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
        }
      }
      else
      {
        $check_email_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "' and affiliate_id = '" . ($affiliate_ref==''?0:$affiliate_ref) . "'");
        $total_c = 0;
        while ( $check_email = tep_db_fetch_array($check_email_query) ){
          if ( opc::is_temp_customer( $check_email['customers_id'] ) ) {
            opc::remove_temp_customer( $check_email['customers_id'] );
          }else{
            $total_c++;
          }
        }        
        if ($total_c > 0) {
          $error = true;

          $messageStack->add('one_page_checkout', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
        }
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_CITY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
			/*
          $error = true;
          $messageStack->add('one_page_checkout', ENTRY_STATE_ERROR_SELECT);
		  */
        }
      } else {
		/*  
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;
          $messageStack->add('one_page_checkout', ENTRY_STATE_ERROR);
        }*/
      }
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_COUNTRY_ERROR);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

    $sendto = tep_db_prepare_input($HTTP_POST_VARS['sendto'] ? $HTTP_POST_VARS['sendto'] : $HTTP_SESSION_VARS['sendto']);
if (($order->content_type != 'virtual') && ($order->content_type != 'virtual_weight') ) {
    $ship_firstname = tep_db_prepare_input($HTTP_POST_VARS['ship_firstname']);
    $ship_lastname = tep_db_prepare_input($HTTP_POST_VARS['ship_lastname']);
    if (ACCOUNT_COMPANY == 'true') $ship_company = tep_db_prepare_input($HTTP_POST_VARS['ship_company']);
    $ship_street_address = tep_db_prepare_input($HTTP_POST_VARS['ship_street_address_line1']);
    if (ACCOUNT_SUBURB == 'true') $ship_suburb = tep_db_prepare_input($HTTP_POST_VARS['ship_street_address_line2']);
    $ship_postcode = tep_db_prepare_input($HTTP_POST_VARS['ship_postcode']);
    $ship_city = tep_db_prepare_input($HTTP_POST_VARS['ship_city']);

    if (ACCOUNT_GENDER == 'true') {
      if (isset($HTTP_POST_VARS['shipping_gender'])) {
        $shipping_gender = tep_db_prepare_input($HTTP_POST_VARS['shipping_gender']);
      } else {
        $shipping_gender = false;
      }
    }

    if (ACCOUNT_STATE == 'true') {
      $ship_state = tep_db_prepare_input($HTTP_POST_VARS['ship_state']);
      if (is_int($HTTP_POST_VARS['ship_state'])) {
        $ship_zone_id = tep_db_prepare_input($HTTP_POST_VARS['ship_state']);
      } else {
        $ship_zone_id = false;
      }
    }
    $ship_country = tep_db_prepare_input($HTTP_POST_VARS['ship_country']);
    $sendto = tep_db_prepare_input($HTTP_POST_VARS['sendto']);

    if (ACCOUNT_GENDER == 'true') {
      if ( ($shipping_gender != 'm') && ($shipping_gender != 'f') ) {
        $shipping_gender = 'm';
      }
    }

    if (strlen($ship_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_FIRST_NAME_ERROR);
    }

    if (strlen($ship_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_LAST_NAME_ERROR);
    }

    if (strlen($ship_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_STREET_ADDRESS_ERROR);
    }

    if (strlen($ship_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_POST_CODE_ERROR);
    }

    if (strlen($ship_city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_CITY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $ship_zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$ship_country . "'");
      $check = tep_db_fetch_array($check_query);
      $ship_state_has_zones = ($check['total'] > 0);
      if ($ship_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$ship_country . "' and (zone_name like '" . tep_db_input($ship_state) . "%' or zone_code like '%" . tep_db_input($ship_state) . "%')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $ship_zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('one_page_checkout', SHIP_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($ship_state) < ENTRY_STATE_MIN_LENGTH) {
			/*
          $error = true;
          $messageStack->add('one_page_checkout', SHIP_STATE_ERROR);*/
        }
      }
    }
    if (is_numeric($ship_country) == false) {
      $error = true;

      $messageStack->add('one_page_checkout', SHIP_COUNTRY_ERROR);
    }

  }
}

  if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'ec_page_checkout'))
  {
    if (tep_session_is_registered('customer_id')) // New Customer
    {
       $sql_data_array = array( //'customers_email_address' => $email_address,
                                'customers_telephone' => $telephone,
                                //'affiliate_id' => $affiliate_ref,
                                //'customers_newsletter' => $customers_newsletter,
                               );

       tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '".(int)$customer_id."'");
    }
  }
  
  
  if ($_POST['checkout-type'] == "amazon") {
	  $error = false;  
	  unset($HTTP_POST_VARS['create_account']);
	  $opc_temp_account = 1;
  }
  
  

  if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout'))
  {
    if (!tep_session_is_registered('customer_id')) // New Customer
    {
      $opc_temp_account = 0;
      if (defined('ONE_PAGE_CREATE_ACCOUNT')) {
        if ( ONE_PAGE_CREATE_ACCOUNT=='false' || 
             ( ONE_PAGE_CREATE_ACCOUNT=='onebuy' && strlen($new_password)==0 )
        ) {
          //$new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
		  // Musaffar Patel - lowercase firstname
		  $new_password = strtolower($firstname);		  
        }
        if (ONE_PAGE_CREATE_ACCOUNT=='onebuy' && !isset( $HTTP_POST_VARS['create_account'] ) ) $opc_temp_account = 1;
      }else{
        //$new_password = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
	  // Musaffar Patel - lowercase firstname
	  $new_password = strtolower($firstname);		  
		
      }
	  
      
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'affiliate_id' => $affiliate_ref,
                              'groups_id' => (int)DEFAULT_USER_LOGIN_GROUP,
                              'opc_temp_account' => $opc_temp_account,
                              'customers_password' => tep_encrypt_password($new_password)
                              );

      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();
      $billto = $address_id;

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      if (CUSTOMERS_GROUPS_ENABLE == 'True') {
        $customer_groups_id = DEFAULT_USER_LOGIN_GROUP;
      } else {
        $customer_groups_id = 0;
      }

      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');
      tep_session_register('customer_groups_id');

      // restore cart contents
      $cart->restore_contents();

      if ( $opc_temp_account!=1 ) {
      // build the message content
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname . ' ' . $lastname) . EMAIL_TEXT0 . sprintf(EMAIL_LOGIN, $email_address) . sprintf(EMAIL_PASSWORD, $new_password) . EMAIL_TEXT1 . EMAIL_WARNING;
        tep_mail($firstname . ' ' . $lastname, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }

    }
    else // Existing Customer
    {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;

      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($billto)
      {
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      }
      else
      {
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
        $billto = tep_db_insert_id();
      }

// reset the session variables
      $customer_first_name = $firstname;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
    }

    if (($order->content_type != 'virtual') && ($order->content_type != 'virtual_weight') ) {
      // Shipping is not same as billing address
      if ( $ship_firstname != $firstname || $ship_firstname != $firstname ||
           $ship_street_address != $street_address || $ship_postcode != $postcode ||
           $ship_city != $city || $ship_company != $company || $ship_suburb != $suburb || 
           $ship_state != $state || $ship_zone_id != $zone_id || $ship_country != $country || $gender != $shipping_gender)
      {
        $sql_data_array = array('customers_id' => $customer_id,
                                'entry_firstname' => $ship_firstname,
                                'entry_lastname' => $ship_lastname,
                                'entry_street_address' => $ship_street_address,
                                'entry_postcode' => $ship_postcode,
                                'entry_city' => $ship_city,
                                'entry_country_id' => $ship_country);
  
        if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $shipping_gender;
        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $ship_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $ship_suburb;

        if (ACCOUNT_STATE == 'true') {
          if ($ship_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $ship_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $ship_state;
          }
        }
  
        if ($sendto && $sendto != $billto)
        {
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
        }
        else
        {
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
          $sendto = tep_db_insert_id();
        }
      }
      else
      {
        $sendto = $billto;
      }
    }

    tep_session_register('billto');
    tep_session_register('sendto');
    
    // Clear stored POST params in Session
    foreach ($HTTP_SESSION_VARS as $key => $val)
    {
      if (substr($key, 0, 18) == 'one_page_checkout_')
      {
        global $$key;
        tep_session_unregister($key);
        unset($HTTP_SESSION_VARS[$key]);
      }
    }

  } // if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout'))  
// {{
  if (tep_session_is_registered('customer_id'))
  {
    // user is logged in
    if (!$billto && !isset($HTTP_POST_VARS['billto'])) $billto = $customer_default_address_id;
    if (!$sendto && !isset($HTTP_POST_VARS['sendto'])) $sendto = $customer_default_address_id;

    // the order class (uses the sendto !)
    $order = new order();

    if (!isset($HTTP_POST_VARS['country'])) $country = $order->billing['country']['id'];
    if (!isset($HTTP_POST_VARS['ship_country'])) $ship_country = $order->delivery['country']['id'];

    if (isset($HTTP_POST_VARS['ship_country']))
    {
      // country is selected
      $country_info = tep_get_countries($HTTP_POST_VARS['ship_country'], true);
      $order->delivery['postcode'] = $HTTP_POST_VARS['ship_postcode'];
      $order->delivery['country'] = array('id' => $HTTP_POST_VARS['ship_country'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']);
      $order->delivery['country_id'] = $HTTP_POST_VARS['ship_country'];
      $order->delivery['format_id'] = tep_get_address_format_id($HTTP_POST_VARS['ship_country']);
    }
	
  }
  else
  {
    $order = new order();

// user not logged in !
    $country = isset($HTTP_POST_VARS['country'])?intval($HTTP_POST_VARS['country']):STORE_COUNTRY;
    $ship_country = isset($HTTP_POST_VARS['ship_country'])?intval($HTTP_POST_VARS['ship_country']):STORE_COUNTRY;
    $ship_zone = isset($HTTP_POST_VARS['ship_state'])?intval($ship_zone_id):STORE_ZONE;
// WebMakers.com Added: changes
// changed from STORE_ORIGIN_ZIP to SHIPPING_ORIGIN_ZIP
    $country_info = tep_get_countries($ship_country, true);
    $order->delivery['postcode'] = SHIPPING_ORIGIN_ZIP;
    $order->delivery['country'] = array('id' => $ship_country, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']);
    $order->delivery['country_id'] = $ship_country;
    $order->delivery['format_id'] = tep_get_address_format_id($ship_country);
    $order->delivery['zone_id'] = $ship_zone;

    $country_info = tep_get_countries($country, true);
    $order->billing['country'] = array('id' => $country, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']);
  }

  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
// ICW CREDIT CLASS GV AMENDE LINE BELOW
//  if ($order->content_type == 'virtual') {
  if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    $shipping = false;
    $sendto = $customer_default_address_id;
  }
  // weight and count needed for shipping !
  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

//echo '<pre>';print_r( $HTTP_POST_VARS );echo '</pre>';
//echo '<pre>';print_r( $order );echo '</pre>';

// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'http_client.php');
  require(DIR_WS_CLASSES . 'shipping.php');
if ($shipping !== false) {
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
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;

      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
// process the selected shipping method
  //if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout')) {
  if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout' || $HTTP_GET_VARS['action'] == 'ec_page_checkout') ) {
    if (!tep_session_is_registered('comments')) tep_session_register('comments');
    if (tep_not_null($HTTP_POST_VARS['comments'])) {
      $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);
    }

    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($HTTP_POST_VARS['shipping'])) && (strpos($HTTP_POST_VARS['shipping'], '_')) ) {
        $shipping = $HTTP_POST_VARS['shipping'];
        list($module, $method) = explode('_', $shipping);
        if ( (is_object($$module) && $$module->enabled) || ($shipping == 'free_free') ) {
          if ($shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote[0]['error'])) {
            tep_session_unregister('shipping');
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $shipping = array('id' => $shipping,
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);
            }
          }
        } else {
          tep_session_unregister('shipping');
        }
      }else{
        $shipping = false;
        tep_session_unregister('shipping');
      }
    } else {
      $shipping = false;
    }
  }
// get all available shipping quotes
  $quotes = $shipping_modules->quote();
}
// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) && ($shipping_modules->cheapest() != false)) $shipping = $shipping_modules->cheapest();

  //shipping error
  // on this stage shipping must be array in any case (free or real)  
  if ( 
      (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout')) &&
      (($order->content_type != 'virtual') && ($order->content_type != 'virtual_weight')) &&
      ($shipping === false || !is_array($shipping)) 
      ){
    $messageStack->add('one_page_checkout', TEXT_CHOOSE_SHIPPING_METHOD);
    $error=true;
    if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
    unset($shipping);    
  }
  //\shipping error
  require(DIR_WS_CLASSES . 'payment.php');
  //$payment_modules = new payment(); // $payment_modules - for selected country (was update_status)
  //---PayPal WPP Modification START ---//
  if ( $ec_checkout ) { //$show_payment_page
    $payment_modules = new payment( $payment );
  }else{
    $payment_modules = new payment();
  }
  //---PayPal WPP Modification END ---//

  require(DIR_WS_CLASSES . 'order_total.php');
  $order = new order(); // init correct shipping here <--

  $payment_modules->update_status(); //???
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules = new order_total();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->collect_posts();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->pre_confirmation_check();

  //if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout')){
  if (($error == false) && isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'one_page_checkout' || $HTTP_GET_VARS['action'] == 'ec_page_checkout' )){
    foreach ($HTTP_POST_VARS as $key => $val){
      if ( !in_array ($key, array ('firstname', 'lastname', 'email_address', 'street_address', 'postcode', 'city', 'state', 'country', 'telephone', 'ship_firstname', 'ship_lastname', 'ship_street_address', 'ship_postcode', 'ship_city', 'ship_state', 'ship_country', 'billto', 'sendto', 'gender', 'shipping_gender', 'condition','street_address_line1','street_address_line2', 'ship_street_address_line1', 'ship_street_address_line2','company','ship_company','dob')) )
      {
        global ${'one_page_checkout_' . $key};
        ${'one_page_checkout_' . $key} = $val;
        tep_session_register('one_page_checkout_' . $key);
      }
    } 	
	
	if ($_POST['amazon_purchaseContractId'] != '') {
		$_SESSION['amazon_purchaseContractId'] = $_POST['amazon_purchaseContractId'];
		print $_SESSION['amazon_purchaseContractId'];
	}


	/* Check if VAT Products in cart */
	if ($cart->haveVatExempt()) {
		tep_redirect(tep_href_link("/vatform.php", 'iorigin=1&amazon_purchaseContractId='.$_POST['amazon_purchaseContractId'], 'SSL'));
	} else {
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'amazon_purchaseContractId='.$_POST['amazon_purchaseContractId'], 'SSL'));
	}

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'amazon_purchaseContractId='.$_POST['amazon_purchaseContractId'], 'SSL'));
  }

// }}

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  $content = CONTENT_CHECKOUT_PAYMENT;
  $javascript = $content . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
