<?php
/*
  $Id: vendor_vendors.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  OSC-Vendors

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();


  if (tep_session_is_registered('login_vendor')){
    $HTTP_GET_VARS['acID'] = $login_id;
  }

  if ($HTTP_GET_VARS['action'] && ($HTTP_GET_VARS['action'] != 'update' && $HTTP_GET_VARS['action'] != 'edit') && tep_session_is_registered('login_vendor')){
    tep_redirect(tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('action'))));
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'setflag':
        
        tep_db_query("update " . TABLE_VENDOR . " set vendor_status='".tep_db_input($HTTP_GET_VARS['flag'])."' where vendor_id='" . tep_db_input($HTTP_GET_VARS['aID']) . "'");

        $vendor_query = tep_db_query("select vendor_firstname, vendor_email_address from ".TABLE_VENDOR." where vendor_id='" . tep_db_input($HTTP_GET_VARS['aID']) . "'");
        $vendor = tep_db_fetch_array($vendor_query);

        $email_subject = EMAIL_SUBJECT_BEGINNING;
        $email_subject .= ($HTTP_GET_VARS['flag'] == 1) ? TEXT_STATUS_ACTIVE : TEXT_STATUS_NOT_ACTIVE;
        
        $email_text = sprintf(EMAIL_GREET_NONE,$vendor['vendor_firstname']);
        $email_text .= ($HTTP_GET_VARS['flag'] == 1) ? sprintf(EMAIL_TEXT_ACTIVE,$vendor['vendor_firstname'],'') : sprintf(EMAIL_TEXT_NOT_ACTIVE,$vendor['vendor_firstname'],'');
        $email_text .= EMAIL_CONTACT;
        tep_mail($vendor['vendor_firstname'], $vendor['vendor_email_address'], $email_subject, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
        
        tep_redirect(tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('flag', 'action')))); 
        break;
      case 'update':
        $vendor_id = tep_db_prepare_input($HTTP_GET_VARS['acID']);
        $vendor_gender = tep_db_prepare_input($HTTP_POST_VARS['vendor_gender']);
        $vendor_firstname = tep_db_prepare_input($HTTP_POST_VARS['vendor_firstname']);
        $vendor_lastname = tep_db_prepare_input($HTTP_POST_VARS['vendor_lastname']);
        $vendor_dob = tep_db_prepare_input($HTTP_POST_VARS['vendor_dob']);
        $vendor_email_address = tep_db_prepare_input($HTTP_POST_VARS['vendor_email_address']);
        $vendor_company = tep_db_prepare_input($HTTP_POST_VARS['vendor_company']);
        $vendor_company_taxid = tep_db_prepare_input($HTTP_POST_VARS['vendor_company_taxid']);
        $vendor_payment_check = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_check']);
        $vendor_payment_paypal = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_paypal']);
        $vendor_payment_bank_name = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_bank_name']);
        $vendor_payment_bank_branch_number = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_bank_branch_number']);
        $vendor_payment_bank_swift_code = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_bank_swift_code']);
        $vendor_payment_bank_account_name = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_bank_account_name']);
        $vendor_payment_bank_account_number = tep_db_prepare_input($HTTP_POST_VARS['vendor_payment_bank_account_number']);
        $vendor_street_address = tep_db_prepare_input($HTTP_POST_VARS['vendor_street_address']);
        $vendor_suburb = tep_db_prepare_input($HTTP_POST_VARS['vendor_suburb']);
        $vendor_postcode=tep_db_prepare_input($HTTP_POST_VARS['vendor_postcode']);
        $vendor_city = tep_db_prepare_input($HTTP_POST_VARS['vendor_city']);
        $vendor_country_id=tep_db_prepare_input($HTTP_POST_VARS['vendor_country_id']);
        $vendor_telephone=tep_db_prepare_input($HTTP_POST_VARS['vendor_telephone']);
        $vendor_fax=tep_db_prepare_input($HTTP_POST_VARS['vendor_fax']);
        $vendor_homepage=tep_db_prepare_input($HTTP_POST_VARS['vendor_homepage']);
        $vendor_state = tep_db_prepare_input($HTTP_POST_VARS['vendor_state']);
        $vendor_zone_id = tep_db_prepare_input($HTTP_POST_VARS['vendor_zone_id']);
        $vendor_commission_percent = tep_db_prepare_input($HTTP_POST_VARS['vendor_commission_percent']);

        if ($vendor_zone_id > 0) $vendor_state = '';
        // If someone uses , instead of .
        $vendor_commission_percent = str_replace (',' , '.' , $vendor_commission_percent);
        $vendor_status = tep_db_prepare_input($HTTP_POST_VARS['status']);

        $sql_data_array = array('vendor_firstname' => $vendor_firstname,
                                'vendor_lastname' => $vendor_lastname,
                                'vendor_email_address' => $vendor_email_address,
                                'vendor_payment_check' => $vendor_payment_check,
                                'vendor_payment_paypal' => $vendor_payment_paypal,
                                'vendor_payment_bank_name' => $vendor_payment_bank_name,
                                'vendor_payment_bank_branch_number' => $vendor_payment_bank_branch_number,
                                'vendor_payment_bank_swift_code' => $vendor_payment_bank_swift_code,
                                'vendor_payment_bank_account_name' => $vendor_payment_bank_account_name,
                                'vendor_payment_bank_account_number' => $vendor_payment_bank_account_number,
                                'vendor_street_address' => $vendor_street_address,
                                'vendor_postcode' => $vendor_postcode,
                                'vendor_city' => $vendor_city,
                                'vendor_country_id' => $vendor_country_id,
                                'vendor_telephone' => $vendor_telephone,
                                'vendor_commission_percent' => $vendor_commission_percent,
                                'vendor_fax' => $vendor_fax,
                                'vendor_agb' => '1');

        if (ACCOUNT_DOB == 'true') $sql_data_array['vendor_dob'] = tep_date_raw($vendor_dob);
        if (ACCOUNT_GENDER == 'true') $sql_data_array['vendor_gender'] = $vendor_gender;
        if (ACCOUNT_COMPANY == 'true') {
          $sql_data_array['vendor_company'] = $vendor_company;
          $sql_data_array['vendor_company_taxid'] =  $vendor_company_taxid;
        }
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['vendor_suburb'] = $vendor_suburb;
        if (ACCOUNT_STATE == 'true') {
          $sql_data_array['vendor_state'] = $vendor_state;
          $sql_data_array['vendor_zone_id'] = $vendor_zone_id;
        }

        $sql_data_array['vendor_date_account_last_modified'] = 'now()';

        tep_db_perform(TABLE_VENDOR, $sql_data_array, 'update', "vendor_id = '" . tep_db_input($vendor_id) . "'");

        tep_redirect(tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $vendor_id));
        break;
      case 'deleteconfirm':
        $vendor_id = tep_db_prepare_input($HTTP_GET_VARS['acID']);

        tep_db_query("DELETE FROM " . TABLE_VENDOR . "  WHERE vendor_id = " . tep_db_input($vendor_id) );

        tep_redirect(tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')))); 
        break;
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
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
<script language="javascript"><!--
function resetStateText(theForm) {
  theForm.vendor_state.value = '';
  if (theForm.vendor_zone_id.options.length > 1) {
    theForm.vendor_state.value = '<?php echo JS_STATE_SELECT; ?>';
  }
}

function resetZoneSelected(theForm) {
  if (theForm.vendor_state.value != '') {
    theForm.vendor_zone_id.selectedIndex = '0';
    if (theForm.vendor_zone_id.options.length > 1) {
      theForm.vendor_state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.vendor_zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.vendor_zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.vendor_country_id.options[theForm.vendor_country_id.selectedIndex].value;

<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'vendor_zone_id'); ?>

  resetStateText(theForm);
}

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var vendor_firstname = document.vendor.vendor_firstname.value;
  var vendor_lastname = document.vendor.vendor_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var vendor_company = document.vendor.vendor_company.value;' . "\n"; ?>
  var vendor_email_address = document.vendor.vendor_email_address.value;  
  var vendor_street_address = document.vendor.vendor_street_address.value;
  var vendor_postcode = document.vendor.vendor_postcode.value;
  var vendor_city = document.vendor.vendor_city.value;
  var vendor_telephone = document.vendor.vendor_telephone.value;


<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.vendor.vendor_gender[0].checked || document.vendor.vendor_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (vendor_firstname = "" || vendor_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (vendor_lastname = "" || vendor_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }
 <?php if (ACCOUNT_DOB == 'true'){
 ?>
  var vendor_dob = document.vendor.vendor_dob.value;
  if (vendor_dob = "" || vendor_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + '<?php echo ENTRY_DATE_OF_BIRTH_ERROR; ?>';
    error = 1;
  }
 <?php
 }
 ?>
  if (vendor_email_address = "" || vendor_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }


  if (vendor_street_address = "" || vendor_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (vendor_postcode = "" || vendor_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (vendor_city = "" || vendor_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php if (ACCOUNT_STATE == 'true') { ?>
  if (document.vendor.vendor_zone_id.options.length <= 1) {
    if (document.vendor.vendor_state.value == "" || document.vendor.vendor_state.length < 4 ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.vendor.vendor_state.value = '';
    if (document.vendor.vendor_zone_id.selectedIndex == 0) {
       error_message = error_message + "<?php echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?php } ?>

  if (document.vendor.vendor_country_id.value == 0) {
    error_message = error_message + "<?php echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (vendor_telephone = "" || vendor_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
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
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_VENDOR;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=vendor');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('search', FILENAME_VENDOR, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search').'</form>';
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
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $vendor_query = tep_db_query("select * from " . TABLE_VENDOR . " where vendor_id = '" . $HTTP_GET_VARS['acID'] . "'");
    $vendor = tep_db_fetch_array($vendor_query);
    $aInfo = new objectInfo($vendor);
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('vendor', FILENAME_VENDOR, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();" enctype="multipart/form-data"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('vendor_gender', 'm', false, $aInfo->vendor_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('vendor_gender', 'f', false, $aInfo->vendor_gender) . '&nbsp;&nbsp;' . FEMALE; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_firstname', $aInfo->vendor_firstname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_lastname', $aInfo->vendor_lastname, 'maxlength="32"', true); ?></td>
          </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_dob', tep_date_short($aInfo->vendor_dob), 'maxlength="32"', true) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT_VENDOR) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT_VENDOR . '</span>': ''); ?></td>
          </tr>
<?php
  }
?>
          
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_email_address', $aInfo->vendor_email_address, 'maxlength="96"', true); ?></td>
          </tr>

<?php
 if (!tep_session_is_registered('login_vendor')){
?>          
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('status', '1', ($aInfo->vendor_status==1)) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('status', '0',  ($aInfo->vendor_status==0)) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>

<?php
}
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
 if (!tep_session_is_registered('login_vendor')){
   if (VENDOR_INDIVIDUAL_PERCENTAGE == 'true') {
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMMISSION; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_COMMISSION; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_commission_percent', $aInfo->vendor_commission_percent, 'maxlength="5"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }
  }
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_company', $aInfo->vendor_company, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_COMPANY_TAXID; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_company_taxid', $aInfo->vendor_company_taxid, 'maxlength="64"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (VENDOR_USE_CHECK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_CHECK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_check', $aInfo->vendor_payment_check, 'maxlength="100"'); ?></td>
          </tr>
<?php
  }
  if (VENDOR_USE_PAYPAL == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_PAYPAL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_paypal', $aInfo->vendor_payment_paypal, 'maxlength="64"'); ?></td>
          </tr>
<?php
  }
  if (VENDOR_USE_BANK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_BANK_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_bank_name', $aInfo->vendor_payment_bank_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_bank_branch_number', $aInfo->vendor_payment_bank_branch_number, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_bank_swift_code', $aInfo->vendor_payment_bank_swift_code, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_bank_account_name', $aInfo->vendor_payment_bank_account_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_payment_bank_account_number', $aInfo->vendor_payment_bank_account_number, 'maxlength="64"'); ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
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
            <td class="main"><?php echo tep_draw_input_field('vendor_street_address', $aInfo->vendor_street_address, 'maxlength="64"', true); ?></td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_suburb', $aInfo->vendor_suburb, 'maxlength="64"', false); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_city', $aInfo->vendor_city, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_postcode', $aInfo->vendor_postcode, 'maxlength="8"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('vendor_country_id', tep_get_countries(), $aInfo->vendor_country_id, 'onChange="update_zone(this.form);"'); ?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('vendor_zone_id', tep_prepare_country_zones_pull_down($aInfo->vendor_country_id), $aInfo->vendor_zone_id, 'onChange="resetStateText(this.form);"'); ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('vendor_state', $aInfo->vendor_state, 'maxlength="32" onChange="resetZoneSelected(this.form);"'); ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_telephone', $aInfo->vendor_telephone, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('vendor_fax', $aInfo->vendor_fax, 'maxlength="32"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = " where vendor_id like '" . $keywords . "' or vendor_firstname like '" . $keywords . "' or vendor_lastname like '" . $keywords . "' or vendor_email_address like '" . $keywords . "'";
    }
    if (tep_session_is_registered('login_vendor')){
      if ($search == ''){
        $search .= " where vendor_id = '" . $login_id. "'";
      }else{
        $search .= " and vendor_id = '" . $login_id. "'";
      }
    }
    $vendor_query_raw = "select * from " . TABLE_VENDOR . $search . " order by vendor_lastname";
    $vendor_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, 
    $vendor_query_raw, $vendor_query_numrows);
    $vendor_query = tep_db_query($vendor_query_raw);
    while ($vendor = tep_db_fetch_array($vendor_query)) {
      $info_query = tep_db_query("select vendor_commission_percent, vendor_date_account_created as date_account_created, vendor_date_account_last_modified as date_account_last_modified, vendor_date_of_last_logon as date_last_logon, vendor_number_of_logons as number_of_logons from " . TABLE_VENDOR . " where vendor_id = '" . $vendor['vendor_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['acID']) || (@$HTTP_GET_VARS['acID'] == $vendor['vendor_id'])) && (!$aInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $vendor['vendor_country_id'] . "' and language_id = '" . (int)$languages_id . "'");
        $country = tep_db_fetch_array($country_query);

        $vendor_info = array_merge($country, $info);

        $aInfo_array = array_merge($vendor, $vendor_info);
        $aInfo = new objectInfo($aInfo_array);
      }

      if ( (is_object($aInfo)) && ($vendor['vendor_id'] == $aInfo->vendor_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->vendor_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID')) . 'acID=' . $vendor['vendor_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $vendor['vendor_id']; ?></td>        
                <td class="dataTableContent"><?php echo $vendor['vendor_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $vendor['vendor_firstname']; ?></td>
                <td class="dataTableContent" align="right"><?php if($vendor['vendor_commission_percent'] > VENDOR_PERCENT || $vendor['vendor_commission_percent'] > 0) echo $vendor['vendor_commission_percent']; else echo  VENDOR_PERCENT; ?> %</td>
                <td class="dataTableContent">
<?php
      if ($vendor['vendor_status'] == '1') {
        if (!tep_session_is_registered('login_vendor')){
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_VENDOR, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $vendor['vendor_id'] . '&action=setflag&flag=0') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
        }else{
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) ;
        }
      } else {
        if (!tep_session_is_registered('login_vendor')){
          echo '<a href="' . tep_href_link(FILENAME_VENDOR, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $vendor['vendor_id'] . '&action=setflag&flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
        }else{
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
        }
      }
?></td>
                <td class="dataTableContent" align="right"><?php echo (!tep_session_is_registered('login_vendor')?'<a href="' . tep_href_link(FILENAME_VENDOR_STATISTICS, tep_get_all_get_params(array('acID')) . 'acID=' . $vendor['vendor_id']) . '">' . tep_image(DIR_WS_ICONS . 'statistics.gif', ICON_STATISTICS) . '</a>&nbsp;':''); if ( (is_object($aInfo)) && ($vendor['vendor_id'] == $aInfo->vendor_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID')) . 'acID=' . $vendor['vendor_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $vendor_split->display_count($vendor_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_VENDORS); ?></td>
                    <td class="smallText" align="right"><?php echo $vendor_split->display_links($vendor_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'acID'))); ?></td>
                  </tr>
<?php
    if (tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('vendor', FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->vendor_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $aInfo->vendor_firstname . ' ' . $aInfo->vendor_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->vendor_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($aInfo)) {
        $heading[] = array('text' => '<b>' . $aInfo->vendor_firstname . ' ' . $aInfo->vendor_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->vendor_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ' . (!tep_session_is_registered('login_vendor')?' <a href="' . tep_href_link(FILENAME_VENDOR, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->vendor_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_VENDOR_CONTACT, 'selected_box=vendor&vendor=' . $aInfo->vendor_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>':''));

        $vendor_sales_raw = "select count(*) as count, sum(vendor_value) as total, sum(vendor_payment) as payment from " . TABLE_VENDOR_SALES . " a left join " . TABLE_ORDERS . " o on (a.vendor_orders_id=o.orders_id) where o.orders_status in (" . VENDOR_PAYMENT_ORDER_MIN_STATUS . ") and  vendor_id = '" . $aInfo->vendor_id . "'";
        $vendor_sales_values = tep_db_query($vendor_sales_raw);
        $vendor_sales = tep_db_fetch_array($vendor_sales_values);

        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($aInfo->date_account_created));
        $contents[] = array('text' => '' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($aInfo->date_account_last_modified));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($aInfo->date_last_logon));
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $aInfo->number_of_logons);
        $contents[] = array('text' => '' . TEXT_INFO_COMMISSION . ' ' . $aInfo->vendor_commission_percent . ' %');
        $contents[] = array('text' => '' . TEXT_INFO_COUNTRY . ' ' . $aInfo->countries_name);
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_SALES . ' ' . $vendor_sales['count'],'');
        $contents[] = array('text' => '' . TEXT_INFO_SALES_TOTAL . ' ' . $currencies->display_price($vendor_sales['total'],''));
        $contents[] = array('text' => '' . TEXT_INFO_VENDOR_TOTAL . ' ' . $currencies->display_price($vendor_sales['payment'],''));
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
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
