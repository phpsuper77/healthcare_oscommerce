<?php
/*
$Id: create_account.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

$process = false;
if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
  $process = true;
  if (ENABLE_CUSTOMER_GROUP_CHOOSE == 'True'){
    $group = (int)$HTTP_POST_VARS['group'];
  }else{
    if (!defined("DEFAULT_USER_LOGIN_GROUP")){
      if (isset($HTTP_GET_VARS['group']) && $HTTP_GET_VARS['group'] != ''){
        $group = (int)$HTTP_GET_VARS['group'];
      }else{
        $group = 0;
      }
    }else{
      if (isset($HTTP_GET_VARS['group']) && $HTTP_GET_VARS['group'] != ''){
        $group = (int)$HTTP_GET_VARS['group'];
      }else{
        $group = DEFAULT_USER_LOGIN_GROUP;
      }
    }
  }

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
  if (ACCOUNT_COMPANY_VAT_ID == 'true') $entry_company_vat = tep_db_prepare_input($HTTP_POST_VARS['entry_company_vat']);

  if (ACCOUNT_STATE == 'true') {
    $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
    if (isset($HTTP_POST_VARS['zone_id'])) {
      $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
    } else {
      $zone_id = 0;
    }
  }
  $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
  $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
  $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
  if (isset($HTTP_POST_VARS['newsletter'])) {
    $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
  } else {
    $newsletter = false;
  }
  $password = strtolower(tep_db_prepare_input($HTTP_POST_VARS['password']));
  $confirmation = tep_db_prepare_input($HTTP_POST_VARS['confirmation']);

  $error = false;

  if (ACCOUNT_COMPANY_VAT_ID == 'true'){
    if (!empty($entry_company_vat) and (!checkVAT($entry_company_vat))) {
      $error = true;
      $messageStack->add('create_account', ENTRY_BUSINESS_ERROR);
    }
    if (check_customer_groups($group, 'groups_is_reseller') && empty($entry_company_vat)){
      $error = true;
      $messageStack->add('create_account', ENTRY_BUSINESS_COMPANY_ERROR);
    }
  }

  if (ACCOUNT_GENDER == 'true') {
    if ( ($gender != 'm') && ($gender != 'f') ) {
      $error = true;

      $messageStack->add('create_account', ENTRY_GENDER_ERROR);
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (tep_validate_email($email_address) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  } else {
    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and affiliate_id = '" . (int)$affiliate_ref. "'");
    $check_email = tep_db_fetch_array($check_email_query);
//---PayPal WPP Modification START ---//
    if ($check_email['total'] > 0 && tep_paypal_wpp_create_account_check($email_address)) {
//---PayPal WPP Modification END ---//
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_CITY_ERROR);
  }

  if (is_numeric($country) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
  }

  if (ACCOUNT_STATE == 'true') {
    //$zone_id = 0;
    $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
    $check = tep_db_fetch_array($check_query);
    $entry_state_has_zones = ($check['total'] > 0);
    if ($entry_state_has_zones == true) {
      if ($zone_id > 0){
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_id = '" . tep_db_input($zone_id) . "')");
      }else{
        $zone_id = 0;
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%') order by zone_name");
      }
      if (tep_db_num_rows($zone_query) == 1) {
        $zone = tep_db_fetch_array($zone_query);
        $zone_id = $zone['zone_id'];
      } else {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
      }
    } else {
      if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR);
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
  }


  if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
  } elseif (strtolower($password) != strtolower($confirmation)) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
  }

  if ($error == false) {
    $login = true;
    if ($group != 0 && check_customer_groups($group, 'new_approve')){
      $login = false;
    }
    $sql_data_array = array('customers_firstname' => $firstname,
    'customers_lastname' => $lastname,
    'customers_email_address' => $email_address,
    'customers_telephone' => $telephone,
    'customers_fax' => $fax,
    'customers_newsletter' => $newsletter,
    'affiliate_id' => (int)$affiliate_ref,
    'groups_id' => $group,
    'customers_status' => ($login?1:0),
    'customers_password' => tep_encrypt_password($password));

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

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
    if (ACCOUNT_COMPANY_VAT_ID == 'true') $sql_data_array['entry_company_vat'] = $entry_company_vat;
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

    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    if (SESSION_RECREATE == 'True') {
      tep_session_recreate();
    }

    $customer_first_name = $firstname;
    $customer_default_address_id = $address_id;
    $customer_country_id = $country;
    $customer_zone_id = $zone_id;
    if (!defined('DEFAULT_USER_LOGIN_GROUP'))
    define(DEFAULT_USER_LOGIN_GROUP, 0);
    $customer_groups_id = DEFAULT_USER_LOGIN_GROUP;

    if ($login){
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');
      tep_session_register('customer_groups_id');

      // restore cart contents
      $cart->restore_contents();
    }

    // build the message content
    $name = $firstname . ' ' . $lastname;

    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $email_text = sprintf(EMAIL_GREET_MR, $lastname);
      } else {
        $email_text = sprintf(EMAIL_GREET_MS, $lastname);
      }
    } else {
      $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
    }

    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;

    // ICW - CREDIT CLASS CODE BLOCK ADDED  ******************************************************* BEGIN
    if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
      $coupon_code = create_coupon_code();
      $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
      $insert_id = tep_db_insert_id($insert_query);
      $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . tep_db_input($email_address) . "', now() )");

      $email_text .= sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
      sprintf(EMAIL_GV_REDEEM, $coupon_code) . "\n\n" .
      EMAIL_GV_LINK . tep_get_clickable_link(tep_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code,'NONSSL', false)) .
      "\n\n";
    }
    if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
      $coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
      $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'");
      if (tep_db_num_rows($coupon_query)){
        $coupon = tep_db_fetch_array($coupon_query);
        $coupon_id = $coupon['coupon_id'];
        $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$languages_id . "'");
        $coupon_desc = tep_db_fetch_array($coupon_desc_query);
        $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . tep_db_input($email_address) . "', now() )");
        $email_text .= EMAIL_COUPON_INCENTIVE_HEADER .  "\n" .
        sprintf("%s", $coupon_desc['coupon_description']) ."\n\n" .
        sprintf(EMAIL_COUPON_REDEEM, $coupon['coupon_code']) . "\n\n" .
        "\n\n";
      }
    }
    tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    if (!$login){
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
  }
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

$content = CONTENT_CREATE_ACCOUNT;
$javascript = 'form_check.js.php';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
