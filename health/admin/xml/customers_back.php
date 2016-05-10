<?php
/*
  $Id: customers.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;
  if (tep_session_is_registered("login_affiliate") && ($action == 'update' || $action == 'confirm' || $action == 'deleteconfirm')){
    tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))));
  }

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($HTTP_POST_VARS['customers_lastname']);
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);
        $vat_exemption_form_sent = tep_db_prepare_input($HTTP_POST_VARS['vat_exemption_form_sent']);
        $customers_telephone = tep_db_prepare_input($HTTP_POST_VARS['customers_telephone']);
        $customers_fax = tep_db_prepare_input($HTTP_POST_VARS['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($HTTP_POST_VARS['customers_newsletter']);
        $individual_id = tep_db_prepare_input($HTTP_POST_VARS['individual_id']);
        $customers_status = tep_db_prepare_input($HTTP_POST_VARS['customers_status']);
        $admin_id = 0;
        $res = tep_db_query(" select * from " . TABLE_ADMIN . " where individual_id like '" . tep_db_input($individual_id) . "'");
        if ($d = tep_db_fetch_array($res)){
          $admin_id = $d['admin_id'];
        }

        $customers_gender = tep_db_prepare_input($HTTP_POST_VARS['customers_gender']);
        $customers_dob = tep_db_prepare_input($HTTP_POST_VARS['customers_dob']);

        $default_address_id = tep_db_prepare_input($HTTP_POST_VARS['default_address_id']);
        $entry_street_address = tep_db_prepare_input($HTTP_POST_VARS['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($HTTP_POST_VARS['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($HTTP_POST_VARS['entry_postcode']);
        $entry_city = tep_db_prepare_input($HTTP_POST_VARS['entry_city']);
        $entry_country_id = tep_db_prepare_input($HTTP_POST_VARS['entry_country_id']);
        if (ACCOUNT_COMPANY_VAT_ID == 'true') $entry_company_vat = tep_db_prepare_input($HTTP_POST_VARS['entry_company_vat']);

        $entry_company = tep_db_prepare_input($HTTP_POST_VARS['entry_company']);
        $entry_state = tep_db_prepare_input($HTTP_POST_VARS['entry_state']);

        $groups_id = tep_db_prepare_input($HTTP_POST_VARS['groups_id']);

        if (isset($HTTP_POST_VARS['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($HTTP_POST_VARS['entry_zone_id']);

        if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_firstname_error = true;
        } else {
          $entry_firstname_error = false;
        }
        if (ACCOUNT_COMPANY_VAT_ID == 'true'){
       		if (!empty($entry_company_vat) and (!checkVAT($entry_company_vat))) {
            $error = true;
            $entry_company_vat_error = true;
          }
        }
        if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_lastname_error = true;
        } else {
          $entry_lastname_error = false;
        }

        if (ACCOUNT_DOB == 'true') {
          if (checkdate(substr(tep_date_raw($customers_dob), 4, 2), substr(tep_date_raw($customers_dob), 6, 2), substr(tep_date_raw($customers_dob), 0, 4))) {
            $entry_date_of_birth_error = false;
          } else {
            $error = true;
            $entry_date_of_birth_error = true;
          }
        }

        if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_email_address_error = true;
        } else {
          $entry_email_address_error = false;
        }

        if (!tep_validate_email($customers_email_address)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }
        if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_street_address_error = true;
        } else {
          $entry_street_address_error = false;
        }

        if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $entry_post_code_error = true;
        } else {
          $entry_post_code_error = false;
        }

        if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
          $error = true;
          $entry_city_error = true;
        } else {
          $entry_city_error = false;
        }

        if ($entry_country_id == false) {
          $error = true;
          $entry_country_error = true;
        } else {
          $entry_country_error = false;
        }

        if (ACCOUNT_STATE == 'true') {
          if ($entry_country_error == true) {
            $entry_state_error = true;
          } else {
            $zone_id = 0;
            $entry_state_error = false;
            $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "'");
            $check_value = tep_db_fetch_array($check_query);
            $entry_state_has_zones = ($check_value['total'] > 0);
            if ($entry_state_has_zones == true) {
              $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "' and (zone_name like '" . tep_db_input($entry_state) . "' or zone_code like '" . tep_db_input($entry_state) . "')");
              if (tep_db_num_rows($zone_query) == 1) {
                $zone_values = tep_db_fetch_array($zone_query);
                $entry_zone_id = $zone_values['zone_id'];
              } else {
                $error = true;
                $entry_state_error = true;
              }
            } else {
              if ($entry_state == false) {
                $error = true;
                $entry_state_error = true;
              }
            }
         }
      }

      if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
        $error = true;
        $entry_telephone_error = true;
      } else {
        $entry_telephone_error = false;
      }
      $data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'"));
      $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "' and customers_id != '" . (int)$customers_id . "' and affiliate_id = '" . $data['affiliate_id'] . "'");
      if (tep_db_num_rows($check_email)) {
        $error = true;
        $entry_email_address_exists = true;
      } else {
        $entry_email_address_exists = false;
      }
      if ($vat_exemption_form_sent != 1) {
        $vat_exemption_form_date = '';
      } else {
        $vat_exemption_form_date = $data['vat_exemption_form_date'];
      }

      if ($error == false) {

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_telephone' => $customers_telephone,
                                'vat_exemption_form_sent' => $vat_exemption_form_sent,
                                'vat_exemption_form_date' => $vat_exemption_form_date,
                                'customers_fax' => $customers_fax,
                                'groups_id' => $groups_id,
                                'admin_id' => $admin_id,
                                'customers_status' => $customers_status,
                                'customers_newsletter' => $customers_newsletter);
        ////////////// new account
        if (STRLEN($customers_password)>0) {
//          include(DIR_WS_FUNCTIONS . 'password_funcs.php');
          $sql_data_array['customers_password'] = tep_encrypt_password($customers_password);
        }
        ////////////// new account eof

        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        ////////////// new account
        if ($customers_id){
        ////////////// new account eof

          tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");
        ////////////// new account
        } else {
          tep_db_perform(TABLE_CUSTOMERS, array_merge($sql_data_array, array('customers_default_address_id' => 1)));
          $new_customers_id = tep_db_insert_id();
          $customers_id = $new_customers_id;
          $HTTP_GET_VARS['cID'] = $new_customers_id;
          tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_created = now(), customers_info_date_account_last_modified = now(), customers_info_id = '" . tep_db_input($new_customers_id) . "'");
        }
        ////////////// new account eof

        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

        if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;
        if (ACCOUNT_COMPANY_VAT_ID == 'true') $sql_data_array['entry_company_vat'] = $entry_company_vat;

        if (ACCOUNT_STATE == 'true') {
          if ($entry_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $entry_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $entry_state;
          }
        }

        ////////////// new account
        if (!$new_customers_id){
        ////////////// new account eof

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");

       ////////////// new account
        } else {
// old snapshot         
//          tep_db_perform(TABLE_ADDRESS_BOOK, array_merge($sql_data_array, array('customers_id' => $new_customers_id, 'address_book_id' => 1)));
          tep_db_perform(TABLE_ADDRESS_BOOK, array_merge($sql_data_array, array('customers_id' => $new_customers_id)));
          $new_customers_address_id = tep_db_insert_id();
          tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id='" . $new_customers_address_id . "' where customers_id = '" . (int)$new_customers_id . "'");
        }
        ////////////// new account eof
          switch ($HTTP_GET_VARS['redirect']) {
            case 'neworder':
              tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action', 'redirect')) . 'Customer=' . $customers_id . '&name=' . urlencode($customers_lastname)));
            break;
            default:
              tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))));
          }
          tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));

        } else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }

        break;
      case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        if (isset($HTTP_POST_VARS['delete_reviews']) && ($HTTP_POST_VARS['delete_reviews'] == 'on')) {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
          }

          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
        }

        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");

        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
        $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, c.customers_alt_email_address, c.groups_id, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, a.entry_company_vat, c.customers_telephone, c.vat_exemption_form_date, c.vat_exemption_form_sent, c.customers_alt_telephone, c.customers_cell, c.customers_status, c.customers_fax, c.customers_newsletter, c.customers_owc_member, c.customers_type_id, c.customers_bonus_points, c.customers_credit_avail, ad.individual_id, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id left join " . TABLE_ADMIN . " ad on ad.admin_id=c.admin_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$HTTP_GET_VARS['cID'] . "' " . (tep_session_is_registered("login_affiliate")?" and c.affiliate_id = '" . $login_id . "'":''));
        // new account changed
//        $customers = tep_db_fetch_array($customers_query);
        if ($customers = tep_db_fetch_array($customers_query)) {
          $cInfo = new objectInfo($customers);
				} else {
				  $cInfo = new objectInfo(array('entry_country_id' => STORE_COUNTRY));
				}
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
  if ($action == 'edit' || $action == 'update') {
?>
<script language="javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
  var customers_email_address = document.customers.customers_email_address.value;
  var entry_street_address = document.customers.entry_street_address.value;
  var entry_postcode = document.customers.entry_postcode.value;
  var entry_city = document.customers.entry_city.value;
  var customers_telephone = document.customers.customers_telephone.value;
<?php if (ACCOUNT_COMPANY_VAT_ID == 'true') echo 'var entry_company_vat = document.customers.entry_company_vat.value;'. "\n";?>  


<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.customers.customers_gender.type != 'hidden') {
    if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
    } else {
      error_message = error_message + "<?php echo JS_GENDER; ?>";
      error = 1;
    }
  }
<?php } ?>

  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?php if (ACCOUNT_DOB == 'true') { ?>
  if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

<?php 
if (ACCOUNT_COMPANY_VAT_ID == 'true'){
?>
 if (entry_company_vat != "" && entry_company_vat.length < <?php echo ENTRY_VAT_ID_MIN_LENGTH;?>){
    error_message = error_message + "<?php echo ENTRY_VAT_ID_ERROR;?>";
    error = 1;
 }
<?php
}
?>
  if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.customers.elements['entry_state'].type != "hidden") {
    if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  }
<?php
  }
?>

  if (document.customers.elements['entry_country_id'].type != "hidden") {
    if (document.customers.entry_country_id.value == 0) {
      error_message = error_message + "<?php echo JS_COUNTRY; ?>";
      error = 1;
    }
  }

  if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
  include(DIR_WS_INCLUDES . 'javascript/xml_used.js.php');
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $filters = array(array('id' => '', 'text' => TEXT_SHOW_ALL),
                   array('id' => '1', 'text' => TEXT_SHOW_ACTIVE),
                   array('id' => '0', 'text' => TEXT_SHOW_INACTIVE));
  $query = tep_db_query("select * from " . TABLE_GROUPS);
  $groups = array(array('id' => '', 'text' => TEXT_SHOW_ALL));
  while ($data = tep_db_fetch_array($query)){
    $groups[] = array('id' => $data['groups_id'], 'text' => $data['groups_name']);
  }
  $header_title_menu=BOX_HEADING_CUSTOMERS;
  $header_title_menu_link= tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('search', FILENAME_CUSTOMERS, '', 'get').HEADING_TITLE_SEARCH . ' ' .tep_draw_input_field('search'). '<br>' . (CUSTOMERS_GROUPS_ENABLE=='True'?TEXT_GROUPS . tep_draw_pull_down_menu('groups_filter', $groups, $HTTP_GET_VARS['groups_filter'], 'onchange="this.form.submit();"'):'') . TEXT_FILTER . tep_draw_pull_down_menu('filter', $filters, $HTTP_GET_VARS['filter'], 'onchange="this.form.submit();"') . '</form>';

?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
<?php
  if ($action == 'edit' || $action == 'update') {
    $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES),
                              array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
?>
       <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_gender_error == true) {
        echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('customers_gender');
      }
    } else {
      echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $cInfo->customers_firstname . tep_draw_hidden_field('customers_firstname');
    }
  } else {
    echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_lastname_error == true) {
      echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
    } else {
      echo $cInfo->customers_lastname . tep_draw_hidden_field('customers_lastname');
    }
  } else {
    echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; if ($error != true) echo '<br>' . DOB_FORMAT_STRING?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_date_of_birth_error == true) {
        echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
      } else {
        echo $cInfo->customers_dob . tep_draw_hidden_field('customers_dob');
      }
    } else {
      echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"', true);
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $customers_email_address . tep_draw_hidden_field('customers_email_address');
    }
  } else {
    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true);
  }
?></td>
          </tr>
        </table></td> 
      </tr>
<?php
    if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_company_error == true) {
        echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
      } else {
        echo $cInfo->entry_company . tep_draw_hidden_field('entry_company');
      }
    } else {
      echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
    }
?></td>
          </tr>
<?php
if (ACCOUNT_COMPANY_VAT_ID == 'true'){
?>
          <tr>
            <td class="main"><?php echo ENTRY_BUSINESS; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_company_vat_error == true) {
        echo tep_draw_input_field('entry_company_vat ', $cInfo->entry_company_vat , 'maxlength="32"') . '&nbsp;' . ENTRY_BUSINESS_COMPANY_ERROR;
      } else {
        echo $cInfo->entry_company_vat . tep_draw_hidden_field('entry_company_vat ');
      }
    } else {
      echo tep_draw_input_field('entry_company_vat', $cInfo->entry_company_vat, 'maxlength="32"');
    }
?></td>
          </tr>
<?php
}
?>
        </table></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_street_address_error == true) {
      echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
    } else {
      echo $cInfo->entry_street_address . tep_draw_hidden_field('entry_street_address');
    }
  } else {
    echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_suburb_error == true) {
        echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
      }
    } else {
      echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
    }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_post_code_error == true) {
      echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
    } else {
      echo $cInfo->entry_postcode . tep_draw_hidden_field('entry_postcode');
    }
  } else {
    echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_city_error == true) {
      echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
    } else {
      echo $cInfo->entry_city . tep_draw_hidden_field('entry_city');
    }
  } else {
    echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
    $entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
    if ($error == true) {
      if ($entry_state_error == true) {
        if ($entry_state_has_zones == true) {
          $zones_array = array();
          $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
          while ($zones_values = tep_db_fetch_array($zones_query)) {
            $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
          }
          echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
        } else {
          echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state),'',true) . '&nbsp;' . ENTRY_STATE_ERROR;
        }
      } else {
        echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
      }
    } else {
      echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state),'',true);
    }

?></td>
         </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_country_error == true) {
      echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
    } else {
      echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
    }
  } else {
    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea">
        <table border="0" cellspacing="2" cellpadding="2">
         <tr>
            <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_telephone_error == true) {
      echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
    } else {
      echo $cInfo->customers_telephone . tep_draw_hidden_field('customers_telephone');
    }
  } else {
    echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true);
  }
?></td>
         </tr>
         <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    echo $cInfo->customers_fax . tep_draw_hidden_field('customers_fax');
  } else {
    echo tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"');
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_ACTIVE; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('customers_status', '1', ($cInfo->customers_status?true:false)) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_status', '0', (!$cInfo->customers_status?true:false)) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    if ($cInfo->customers_newsletter == '1') {
      echo ENTRY_NEWSLETTER_YES;
    } else {
      echo ENTRY_NEWSLETTER_NO;
    }
    echo tep_draw_hidden_field('customers_newsletter');
  } else {
    echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0'));
  }
?></td>
         </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VAT_EXEMPTION_FORM; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    if ($cInfo->vat_exemption_form_sent == '1') {
      echo ENTRY_VAT_EXEMPTION_FORM_SENT_YES . '&nbsp;' . (tep_not_null($cInfo->vat_exemption_form_date)?tep_date_short($cInfo->vat_exemption_form_date):TEXT_NO_DATE);
    } else {
      echo ENTRY_VAT_EXEMPTION_FORM_SENT_NO;
    }
    echo tep_draw_hidden_field('vat_exemption_form_sent');
  } else {
    echo tep_draw_checkbox_field('vat_exemption_form_sent', '1', (($cInfo->vat_exemption_form_sent == '1') ? '1' : '0')) . '&nbsp;' . (tep_not_null($cInfo->vat_exemption_form_date)?tep_date_short($cInfo->vat_exemption_form_date):TEXT_NO_DATE);
  }
?></td>
         </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
if (CUSTOMERS_GROUPS_ENABLE == 'True'){
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_GROUPS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_GROUP; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    echo tep_get_user_group_name($cInfo->groups_id);
    echo tep_draw_hidden_field('groups_id');
  } else {
    echo tep_cfg_select_user_edit_group($cInfo->groups_id);
  }
?></td>
         </tr>
        </table></td>
      </tr>
<?php
}
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<?php 
    if ($HTTP_GET_VARS['cID']) {
    ?>
      <tr>
        <td align="right" class="main">
        
        <?php 
          if (!tep_session_is_registered("login_affiliate")){
            echo tep_image_submit('button_update.gif', IMAGE_UPDATE); 
          }
          echo ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
        ?></td>
      </tr>
<?php } else { ?>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
          switch ($HTTP_GET_VARS['redirect']) {
            case 'neworder':
              echo ' <a href="' . tep_href_link(FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action', 'redirect'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; 
            break;
            default:
              echo ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action', 'redirect'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; 
          }
          ?>
        </td>
      </tr>
<?php
      }      
?>
      </form>
<?php
  } else {
?>
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <?php if (XML_CUSTOMERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
                <td class="dataTableHeadingContent"  colspan=2 align="center"><?php echo TEXT_XML_DUMP; ?></td>
                <?php } ?>
                  
                <td class="dataTableHeadingContent">&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search_condition = " where (c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%')";// or ad.individual_id like '%" . $keywords . "%'";
    } else {
      $search_condition = " where 1 ";
    }
    $customers_query_raw = "select distinct(c.customers_id), c.last_xml_export, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_status, c.groups_id, a.entry_country_id, c.admin_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on  a.address_book_id = c.customers_default_address_id left join " . TABLE_ADMIN . " ad on ad.admin_id=c.admin_id" . $search_condition . " " . ($HTTP_GET_VARS['filter'] != ''?" and c.customers_status = '" . tep_db_input($HTTP_GET_VARS['filter']) . "'":'') . ((CUSTOMERS_GROUPS_ENABLE=='True' && $HTTP_GET_VARS['groups_filter'])?" and c.groups_id = '" . tep_db_input($HTTP_GET_VARS['groups_filter']) . "'":''). " " .(tep_session_is_registered("login_affiliate")?" and c.affiliate_id = '" .$login_id. "' ":'') . " order by c.customers_lastname, c.customers_firstname";
//c.customers_id = a.customers_id and
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows, 'c.customers_id');
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "' and language_id = '" . (int)$languages_id . "'");
        $country = tep_db_fetch_array($country_query);

        $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

        $customer_info = array_merge($country, $info, $reviews);

        $cInfo_array = array_merge($customers, $customer_info);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
        $on_click_effect = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'"';
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
      } else {
        $on_click_effect = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'"';
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
      }
?>
     <?php if (XML_CUSTOMERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
     <td class="dataTableHeadingContent"><input type="checkbox" id="<?php echo $customers['customers_id'];?>" onclick="javascript:setflagcookie('<?php echo $customers['customers_id'];?>','xml_customers','')"></td>
     <td class="dataTableHeadingContent" <?php echo $onclick_effect; ?>>
     <?php if (tep_not_null($customers["last_xml_export"])) {
                    echo tep_image(DIR_WS_IMAGES.'icons/success.gif',sprintf(TEXT_LAST_XML_DUMP,$customers["last_xml_export"]),10,10);
                  } else {
                    echo tep_image(DIR_WS_IMAGES.'icons/error.gif',TEXT_NEVER_EXPORTED,10,10);
                  }
     ?>
     </td>
     <?php }?>
     
     <td class="dataTableHeadingContent" <?php echo $on_click_effect; ?>>
     <?php
      if ($customers['groups_id']){
        $query = tep_db_query("select * from " . TABLE_GROUPS . " where groups_id = '" . $customers['groups_id'] . "'");
        $data = tep_db_fetch_array($query);
        if ($customers['customers_status']){
          if ($data['image_active'] != '' && is_file(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . 'icons/' . $data['image_active'])){
            echo '<img src="' . DIR_WS_CATALOG_IMAGES . 'icons/' . $data['image_active'] . '" border="0" width="24" height="24">';
          }else{
            echo tep_image(DIR_WS_IMAGES . 'return_client.gif', IMAGE_RETURN_CLIENT, 20, 24); 
          }
        }else{
          if ($data['image_inactive'] != '' && is_file(DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . 'icons/' . $data['image_inactive'])){
            echo '<img src="' . DIR_WS_CATALOG_IMAGES . 'icons/' . $data['image_inactive'] . '" border="0" width="24" height="24">';
          }else{
            echo tep_image(DIR_WS_IMAGES . 'return_client_na.gif', IMAGE_RETURN_CLIENT, 20, 24); 
          }
        }
      }else{ 
        if ($customers['customers_status']){
    			echo tep_image(DIR_WS_IMAGES . 'return_client.gif', IMAGE_RETURN_CLIENT, 20, 24); 
        }else{
          echo tep_image(DIR_WS_IMAGES . 'return_client_na.gif', IMAGE_RETURN_CLIENT, 20, 24); 
        }
      }
		?>
		</td>
                <td class="dataTableContent" <?php echo $on_click_effect; ?>><?php echo $customers['customers_lastname']; ?></td>
                <td class="dataTableContent" <?php echo $on_click_effect; ?>><?php echo $customers['customers_firstname']; ?></td>
                <td class="dataTableContent" <?php echo $on_click_effect; ?>><?php echo $customers['customers_email_address']; ?></td>
                <td class="dataTableContent" align="right" <?php echo $on_click_effect; ?>><?php echo tep_date_short($info['date_account_created']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="8"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
////////////// new account
?>
                  <tr>
                    <td align="right" colspan="2">
                    <?php
                      if (!tep_session_is_registered("login_affiliate")){
                    ?>
                    <?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action', 'type')) . '&action=edit&type=' . $type) . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a> '; ?>
                    <?php
                      }
                    ?>
                    </td>
                  </tr>
                   <?php if (XML_CUSTOMERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
                   <tr>
                    <td class="smallText" colspan=2 align="center"><?php echo $backup_to_xml;?></td>
                  </tr>
                  <?php if ($can_backup_xml) {?>
                   <tr>
                    <td class="smallText" colspan=2 align="center">
                      <a href="<?php echo tep_href_link(FILENAME_BACKUP_XML_DATA,'action=all&datatype=customers')?>"><?php echo TEXT_XML_ALL_CUSTOMERS;?></a> |
                      <a onclick="javascript:check_selected_datas('xml_customers','customers');" href="#"><?php echo TEXT_XML_SELECTED_CUSTOMERS;?></a>

                    </td>
                  </tr>
                  <? }?>
                   <tr>
                    <td class="smallText" colspan=2 align="center"><br><br></td>
                  </tr>
                  <?php }?>                  
<?php
////////////// new account eof
?>

                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ' . (!tep_session_is_registered('login_affiliate')?' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>':'') . ' <a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a> <a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($cInfo->date_account_created));
        //$contents[] = array('text' => '<br>' . ENTRY_ADMIN . ' ' . tep_get_admin($cInfo->admin_id));
        
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->date_account_last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($cInfo->date_last_logon));
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cInfo->number_of_logons);
        //$contents[] = array('text' => '<br>' . TEXT_INFO_OWC_MEMBER . ' ' . tep_get_customers_owc($cInfo->customers_id));
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' ' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cInfo->number_of_reviews);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<?php if (XML_CUSTOMERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
           <script language="Javascript">
             restoreBoxes("xml_customers","");
           </script>
<?php }?>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
