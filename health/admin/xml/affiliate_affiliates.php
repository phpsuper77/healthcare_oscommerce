<?php
/*
  $Id: affiliate_affiliates.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if (tep_session_is_registered('login_affiliate')){
    $HTTP_GET_VARS['acID'] = $login_id;
  }

  if ($HTTP_GET_VARS['action'] && ($HTTP_GET_VARS['action'] != 'update' && $HTTP_GET_VARS['action'] != 'edit') && tep_session_is_registered('login_affiliate')){
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('action'))));
  }

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'setflag':
        tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_isactive='".tep_db_input($HTTP_GET_VARS['flag'])."' where affiliate_id='" . tep_db_input($HTTP_GET_VARS['aID']) . "'");

        if ($HTTP_GET_VARS['flag'] == '1'){
          if (!@mkdir(DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'])){
            $messageStack->add(sprintf(AFFILIATE_DATA_DIRECTORY_NOT_CREATED, $counter), 'error');
          }else{
            $messageStack->add(sprintf(AFFILIATE_DATA_DIRECTORY_CREATED, $counter), 'success');
            @chmod(DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'], 0777);
            $languages = tep_get_languages();
            for ($i=0,$n=sizeof($languages);$i<$n;$i++){
              @mkdir(DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'] . '/' . $languages[$i]['directory']);
              @chmod(DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'] . '/' . $languages[$i]['directory'], 0777);
              @fopen(DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'] . '/' . $languages[$i]['directory'] . '/mainpage.php', 'w+');
            }
          }
        }

        $data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id='" . tep_db_input($HTTP_GET_VARS['aID']) . "'"));
        $target = '../../' . str_replace(DIR_WS_CATALOG, '', DIR_WS_TEMPLATES) . ($data['affiliate_template']?$data['affiliate_template']:DEFAULT_TEMPLATE) . '/images';
        $name = DIR_FS_AFFILATES . $HTTP_GET_VARS['aID'] . '/images';
        if (is_dir($name)){
          @exec("rm $name");
        }
        @exec("ln -sv $target $name");        
        /*$affiliate_query = tep_db_query("select affiliates_name, affiliates_website, affiliates_email, affiliates_owner from ".TABLE_AFFILIATES." where affiliates_id='" . tep_db_input($HTTP_GET_VARS['aID']) . "'");
        $affiliate = tep_db_fetch_array($affiliate_query);

        $email_subject = EMAIL_SUBJECT_BEGINNING;
        $email_subject .= ($HTTP_GET_VARS['flag'] == 1) ? TEXT_STATUS_ACTIVE : TEXT_STATUS_NOT_ACTIVE;
        
        $email_text = sprintf(EMAIL_GREET_NONE,$affiliate['affiliates_owner']);
        $email_text .= ($HTTP_GET_VARS['flag'] == 1) ? sprintf(EMAIL_TEXT_ACTIVE,$affiliate['affiliates_name'],$affiliate['affiliates_website']) : sprintf(EMAIL_TEXT_NOT_ACTIVE,$affiliate['affiliates_name'],$affiliate['affiliates_website']);
        $email_text .= EMAIL_CONTACT;
        tep_mail($affiliate['affiliates_owner'], $affiliate['affiliates_email'], $email_subject, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');*/
        
        tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('flag', 'action')))); 
        break;
      case 'update':
        $affiliate_id = tep_db_prepare_input($HTTP_GET_VARS['acID']);
        $affiliate_gender = tep_db_prepare_input($HTTP_POST_VARS['affiliate_gender']);
        $affiliate_firstname = tep_db_prepare_input($HTTP_POST_VARS['affiliate_firstname']);
        $affiliate_lastname = tep_db_prepare_input($HTTP_POST_VARS['affiliate_lastname']);
        $affiliate_dob = tep_db_prepare_input($HTTP_POST_VARS['affiliate_dob']);
        $affiliate_email_address = tep_db_prepare_input($HTTP_POST_VARS['affiliate_email_address']);
        $affiliate_email_from = tep_db_prepare_input($HTTP_POST_VARS['affiliate_email_from']);
        $affiliate_company = tep_db_prepare_input($HTTP_POST_VARS['affiliate_company']);
        $affiliate_company_taxid = tep_db_prepare_input($HTTP_POST_VARS['affiliate_company_taxid']);
        $affiliate_payment_check = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_check']);
        $affiliate_payment_paypal = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_paypal']);
        $affiliate_payment_bank_name = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_bank_name']);
        $affiliate_payment_bank_branch_number = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_bank_branch_number']);
        $affiliate_payment_bank_swift_code = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_bank_swift_code']);
        $affiliate_payment_bank_account_name = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_bank_account_name']);
        $affiliate_payment_bank_account_number = tep_db_prepare_input($HTTP_POST_VARS['affiliate_payment_bank_account_number']);
        $affiliate_street_address = tep_db_prepare_input($HTTP_POST_VARS['affiliate_street_address']);
        $affiliate_suburb = tep_db_prepare_input($HTTP_POST_VARS['affiliate_suburb']);
        $affiliate_postcode=tep_db_prepare_input($HTTP_POST_VARS['affiliate_postcode']);
        $affiliate_city = tep_db_prepare_input($HTTP_POST_VARS['affiliate_city']);
        $affiliate_country_id=tep_db_prepare_input($HTTP_POST_VARS['affiliate_country_id']);
        $affiliate_telephone=tep_db_prepare_input($HTTP_POST_VARS['affiliate_telephone']);
        $affiliate_fax=tep_db_prepare_input($HTTP_POST_VARS['affiliate_fax']);
        $affiliate_homepage=tep_db_prepare_input($HTTP_POST_VARS['affiliate_homepage']);
        $affiliate_state = tep_db_prepare_input($HTTP_POST_VARS['affiliate_state']);
        $affiliatey_zone_id = tep_db_prepare_input($HTTP_POST_VARS['affiliate_zone_id']);
        $affiliate_store_name = tep_db_prepare_input($HTTP_POST_VARS['affiliate_store_name']);
        $affiliate_commission_percent = tep_db_prepare_input($HTTP_POST_VARS['affiliate_commission_percent']);
        $affiliate_template = tep_db_prepare_input($HTTP_POST_VARS['affiliate_template']);
        $own_descriptions = tep_db_prepare_input($HTTP_POST_VARS['own_descriptions']);
        if ($affiliate_zone_id > 0) $affiliate_state = '';
        // If someone uses , instead of .
        $affiliate_commission_percent = str_replace (',' , '.' , $affiliate_commission_percent);
        $affiliate_isactive = tep_db_prepare_input($HTTP_POST_VARS['status']);

        $sql_data_array = array('affiliate_firstname' => $affiliate_firstname,
                                'affiliate_lastname' => $affiliate_lastname,
                                'affiliate_email_address' => $affiliate_email_address,
                                'affiliate_payment_check' => $affiliate_payment_check,
                                'affiliate_payment_paypal' => $affiliate_payment_paypal,
                                'affiliate_payment_bank_name' => $affiliate_payment_bank_name,
                                'affiliate_payment_bank_branch_number' => $affiliate_payment_bank_branch_number,
                                'affiliate_payment_bank_swift_code' => $affiliate_payment_bank_swift_code,
                                'affiliate_payment_bank_account_name' => $affiliate_payment_bank_account_name,
                                'affiliate_payment_bank_account_number' => $affiliate_payment_bank_account_number,
                                'affiliate_street_address' => $affiliate_street_address,
                                'affiliate_postcode' => $affiliate_postcode,
                                'affiliate_city' => $affiliate_city,
                                'affiliate_country_id' => $affiliate_country_id,
                                'affiliate_telephone' => $affiliate_telephone,
                                'affiliate_fax' => $affiliate_fax,
                                'affiliate_email_from' => $affiliate_email_from,
                                'affiliate_homepage' => $affiliate_homepage,
                                'affiliate_store_name' => $affiliate_store_name,
                                'affiliate_agb' => '1');


        if (!tep_session_is_registered('login_affiliate')){
          $sql_data_array['affiliate_template'] = $affiliate_template;
          $sql_data_array['own_descriptions'] = $own_descriptions;
          $sql_data_array['affiliate_manage_infobox'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_manage_infobox']);
          $sql_data_array['affiliate_own_product_info'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_own_product_info']);
          if ($sql_data_array['affiliate_manage_infobox'] == 'y'){
            $template_data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_TEMPLATE . " where template_name = '" . tep_db_input($affiliate_template). "'"));
            $gID = $template_data['template_id'];
            $query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where affiliate_id = '" . $affiliate_id . "' and template_id = '" . $gID . "'");
            if (!tep_db_num_rows($query)){
              $data_query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID. "' and affiliate_id = 0");
              while ($data = tep_db_fetch_array($data_query))
              {
                $str = "insert into " . TABLE_INFOBOX_CONFIGURATION . " set ";
                foreach($data as $key => $value){
                  if ($key == 'template_id'){
                    $str .= " template_id = '" . $gID . "', ";
                  }elseif ($key == 'infobox_id'){
                    $str .= " infobox_id = '', ";
                  }elseif ($key == 'affiliate_id'){
                    $str .= " affiliate_id = '" . $affiliate_id . "', ";
                  }else{
                    $str .= " " . $key . " = '" . tep_db_input($value) . "', ";
                  }
                }
                $str = substr($str, 0, strlen($str)-2);
                tep_db_query($str);
              }
            }
          }
          $sql_data_array['affiliate_manage_payments'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_manage_payments']);
          $sql_data_array['affiliate_manage_logo'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_manage_logo']);
          $sql_data_array['affiliate_manage_banners'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_manage_banners']);
          if ($HTTP_POST_VARS['affiliate_manage_banners'] == 'n'){
            tep_db_query("update " . TABLE_BANNERS . " set status = '0' where affiliate_id = '" . $affiliate_id . "'" );
          }
          $sql_data_array['affiliate_manage_stylesheet'] = tep_db_prepare_input($HTTP_POST_VARS['affiliate_manage_stylesheet']);
          $sql_data_array['affiliate_commission_percent'] = $affiliate_commission_percent;
          $sql_data_array['affiliate_isactive'] = $affiliate_isactive;
          if ($affiliate_isactive == '1' && !is_dir(DIR_FS_AFFILATES . $affiliate_id)){
            if (!@mkdir(DIR_FS_AFFILATES . $affiliate_id)){
              $messageStack->add_session(AFFILIATE_DATA_DIRECTORY_NOT_CREATED, 'error');
            }else{
              $messageStack->add_session(AFFILIATE_DATA_DIRECTORY_CREATED, 'success');
              @chmod(DIR_FS_AFFILATES . $affiliate_id, 0777);
              $languages = tep_get_languages();
              for ($i=0,$n=sizeof($languages);$i<$n;$i++){
                @mkdir(DIR_FS_AFFILATES . $affiliate_id . '/' . $languages[$i]['directory']);
                @chmod(DIR_FS_AFFILATES . $affiliate_id . '/' . $languages[$i]['directory'], 0777);
                @fopen(DIR_FS_AFFILATES . $affiliate_id . '/' . $languages[$i]['directory'] . '/mainpage.php', 'w+');
              }
            }
          }
        }
        if (isset($HTTP_POST_VARS['affiliate_continue_shopping_url'])) {
          $sql_data_array['affiliate_continue_shopping_url'] = (tep_not_null($HTTP_POST_VARS['affiliate_continue_shopping_url']) ? tep_db_prepare_input($HTTP_POST_VARS['affiliate_continue_shopping_url']) : '');
        }
        if (isset($HTTP_POST_VARS['affiliate_own_product_info_url'])) {
          $sql_data_array['affiliate_own_product_info_url'] = (tep_not_null($HTTP_POST_VARS['affiliate_own_product_info_url']) ? tep_db_prepare_input($HTTP_POST_VARS['affiliate_own_product_info_url']) : '');
        }
        if (isset($HTTP_POST_VARS['affiliate_directory_listing_url'])){
          $sql_data_array['affiliate_directory_listing_url'] = (tep_not_null($HTTP_POST_VARS['affiliate_directory_listing_url']) ? tep_db_prepare_input($HTTP_POST_VARS['affiliate_directory_listing_url']) : '');
        }

        $target = '../../' . str_replace(DIR_WS_CATALOG, '', DIR_WS_TEMPLATES) . ($affiliate_template?$affiliate_template:DEFAULT_TEMPLATE) . '/images';
        $name = DIR_FS_AFFILATES . $affiliate_id . '/images';
        if (is_dir($name)){
          @exec("rm $name");
        }
        @exec("ln -sv $target $name");

        if (($HTTP_POST_VARS['unlink_logo'] == 'yes')) {
          @unlink(DIR_FS_AFFILATES . $affiliate_id . '/' . $HTTP_POST_VARS['previous_affiliate_logo']);
          $sql_data_array['affiliate_logo'] = '';
        } else {
          $affiliate_logo_name = new upload('affiliate_logo');
          $affiliate_logo_name->set_destination(DIR_FS_AFFILATES . $affiliate_id . '/');
          $affiliate_logo_name->set_output_messages('session');
          if ($affiliate_logo_name->parse() && $affiliate_logo_name->save()) {
            $sql_data_array['affiliate_logo'] = $affiliate_logo_name->filename;
          } else {
            $sql_data_array['affiliate_logo'] = (isset($HTTP_POST_VARS['previous_affiliate_logo']) ? $HTTP_POST_VARS['previous_affiliate_logo'] : '');
          }
        }

        if (($HTTP_POST_VARS['unlink_stylesheet'] == 'yes')) {
          @unlink(DIR_FS_AFFILATES . $affiliate_id . '/' . $HTTP_POST_VARS['previous_affiliate_stylesheet']);
          $sql_data_array['affiliate_stylesheet'] = '';
        } else {
          $affiliate_stylesheet_name = new upload('affiliate_stylesheet');
          $affiliate_stylesheet_name->set_destination(DIR_FS_AFFILATES . $affiliate_id . '/');
          $affiliate_stylesheet_name->set_output_messages('session');
          if ($affiliate_stylesheet_name->parse() && $affiliate_stylesheet_name->save()) {
            $sql_data_array['affiliate_stylesheet'] = $affiliate_stylesheet_name->filename;
          } else {
            $sql_data_array['affiliate_stylesheet'] = (isset($HTTP_POST_VARS['previous_affiliate_stylesheet']) ? $HTTP_POST_VARS['previous_affiliate_stylesheet'] : '');
          }
        }

        if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = tep_date_raw($affiliate_dob);
        if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $affiliate_gender;
        if (ACCOUNT_COMPANY == 'true') {
          $sql_data_array['affiliate_company'] = $affiliate_company;
          $sql_data_array['affiliate_company_taxid'] =  $affiliate_company_taxid;
        }
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $affiliate_suburb;
        if (ACCOUNT_STATE == 'true') {
          $sql_data_array['affiliate_state'] = $affiliate_state;
          $sql_data_array['affiliate_zone_id'] = $affiliate_zone_id;
        }

        $sql_data_array['affiliate_date_account_last_modified'] = 'now()';

        tep_db_perform(TABLE_AFFILIATE, $sql_data_array, 'update', "affiliate_id = '" . tep_db_input($affiliate_id) . "'");
        
        tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate_id));
        break;
      case 'deleteconfirm':
        $affiliate_id = tep_db_prepare_input($HTTP_GET_VARS['acID']);

        affiliate_delete(tep_db_input($affiliate_id)); 

        tep_redirect(tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')))); 
        break;
    }
  }
$query = tep_db_query("select * from " . TABLE_TEMPLATE . " order by template_name");
$templates = array();
while ($data = tep_db_fetch_array($query)){
  $templates[] = array('id' => $data['template_name'], 'text' => $data['template_name']);
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
  theForm.affiliate_state.value = '';
  if (theForm.affiliate_zone_id.options.length > 1) {
    theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
  }
}

function resetZoneSelected(theForm) {
  if (theForm.affiliate_state.value != '') {
    theForm.affiliate_zone_id.selectedIndex = '0';
    if (theForm.affiliate_zone_id.options.length > 1) {
      theForm.affiliate_state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.affiliate_zone_id.options.length;
  var SelectedCountry = '';

  while(NumState > 0) {
    NumState--;
    theForm.affiliate_zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.affiliate_country_id.options[theForm.affiliate_country_id.selectedIndex].value;

<?php echo tep_js_zone_list('SelectedCountry', 'theForm', 'affiliate_zone_id'); ?>

  resetStateText(theForm);
}

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var affiliate_firstname = document.affiliate.affiliate_firstname.value;
  var affiliate_lastname = document.affiliate.affiliate_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var affiliate_company = document.affiliate.affiliate_company.value;' . "\n"; ?>
  var affiliate_email_address = document.affiliate.affiliate_email_address.value;  
  var affiliate_email_from = document.affiliate.affiliate_email_from.value;  
  var affiliate_street_address = document.affiliate.affiliate_street_address.value;
  var affiliate_postcode = document.affiliate.affiliate_postcode.value;
  var affiliate_city = document.affiliate.affiliate_city.value;
  var affiliate_telephone = document.affiliate.affiliate_telephone.value;

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.affiliate.affiliate_gender[0].checked || document.affiliate.affiliate_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (affiliate_firstname = "" || affiliate_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (affiliate_lastname = "" || affiliate_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

  if (affiliate_email_address = "" || affiliate_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (affiliate_email_from = "" || affiliate_email_from.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_FROM; ?>";
    error = 1;
  }

  if (affiliate_street_address = "" || affiliate_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (affiliate_postcode = "" || affiliate_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (affiliate_city = "" || affiliate_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php if (ACCOUNT_STATE == 'true') { ?>
  if (document.affiliate.affiliate_zone_id.options.length <= 1) {
    if (document.affiliate.affiliate_state.value == "" || document.affiliate.affiliate_state.length < 4 ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  } else {
    document.affiliate.affiliate_state.value = '';
    if (document.affiliate.affiliate_zone_id.selectedIndex == 0) {
       error_message = error_message + "<?php echo JS_ZONE; ?>";
       error = 1;
    }
  }
<?php } ?>

  if (document.affiliate.affiliate_country_id.value == 0) {
    error_message = error_message + "<?php echo JS_COUNTRY; ?>";
    error = 1;
  }

  if (affiliate_telephone = "" || affiliate_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
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
  $header_title_menu=BOX_HEADING_AFFILIATE;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=affiliate');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('search', FILENAME_AFFILIATE, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search').'</form>';
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
    $affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $HTTP_GET_VARS['acID'] . "'");
    $affiliate = tep_db_fetch_array($affiliate_query);
    $aInfo = new objectInfo($affiliate);
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
      <tr><?php echo tep_draw_form('affiliate', FILENAME_AFFILIATE, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();" enctype="multipart/form-data"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('affiliate_gender', 'm', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_gender', 'f', false, $aInfo->affiliate_gender) . '&nbsp;&nbsp;' . FEMALE; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_firstname', $aInfo->affiliate_firstname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_lastname', $aInfo->affiliate_lastname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_email_address', $aInfo->affiliate_email_address, 'maxlength="96"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_FROM; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_email_from', $aInfo->affiliate_email_from, 'maxlength="96"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_STORE_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_store_name', $aInfo->affiliate_store_name, 'maxlength="96"'); ?></td>
          </tr>
<?php
 if (!tep_session_is_registered('login_affiliate')){
?>        
          <tr>
            <td class="main"><?php echo ENTRY_OWN_DESCRIPTIONS?></td>
            <td class="main"><?php echo tep_draw_checkbox_field('own_descriptions', '1', $aInfo->own_descriptions);?></td>
          </tr>  
          <tr>
            <td class="main"><?php echo ENTRY_TEMPLATE; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('affiliate_template', $templates, ($aInfo->affiliate_template!=''?$aInfo->affiliate_template:DEFAULT_TEMPLATE)); ?></td>
          </tr>          
<?php
  }
?>

<?php
  if ($aInfo->affiliate_manage_logo == 'y'){
  echo tep_draw_hidden_field('previous_affiliate_logo', $aInfo->affiliate_logo);
?>
          <tr>
            <td class="main"><?php echo ENTRY_LOGO; ?></td>
            <td class="main"><?php echo tep_draw_file_field('affiliate_logo') . '<br><input type="checkbox" name="unlink_logo" value="yes">' . TEXT_REMOVE_LOGO . '&nbsp;&nbsp;' . $aInfo->affiliate_logo; ?></td>
          </tr>          
<?php
  }
?>
<?php
  if ($aInfo->affiliate_manage_stylesheet == 'y'){
    echo tep_draw_hidden_field('previous_affiliate_stylesheet', $aInfo->affiliate_stylesheet);
?>
          
          <tr>
            <td class="main"><?php echo ENTRY_STYLESHEET; ?></td>
            <td class="main"><?php echo tep_draw_file_field('affiliate_stylesheet') . '<br><input type="checkbox" name="unlink_stylesheet" value="yes">' . TEXT_REMOVE_STYLESHEET . $aInfo->affiliate_stylesheet; ?></td>
          </tr>
<?php
  }
  if ($aInfo->affiliate_own_product_info == 'y') {
?>
          <tr>
            <td class="main" valign="top"><?php echo ENTRY_CONTINUE_SHOPPING_URL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_continue_shopping_url', $aInfo->affiliate_continue_shopping_url, 'style="width:325px" maxlength="255"'); ?><br><?php echo TEXT_CONTINUE_SHOPPING_URL_HELP; ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo ENTRY_DIRECTORY_LISTING_URL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_directory_listing_url', $aInfo->affiliate_directory_listing_url, 'style="width:325px" maxlength="255"'); ?><br><?php echo TEXT_DIRECTORY_LISTING_URL_HELP; ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo ENTRY_PRODUCT_INFO_URL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_own_product_info_url', $aInfo->affiliate_own_product_info_url, 'style="width:325px" maxlength="255"'); ?><br><?php echo TEXT_PRODUCT_INFO_URL_HELP; ?></td>
          </tr>
<?php
  }  
?>

<?php
 if (!tep_session_is_registered('login_affiliate')){
?>          
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('status', '1', ($aInfo->affiliate_isactive==1)) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('status', '0',  ($aInfo->affiliate_isactive==0)) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_MANAGE_PAYMENTS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_manage_payments', 'y', ($aInfo->affiliate_manage_payments=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_manage_payments', 'n',  ($aInfo->affiliate_manage_payments=='n')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_MANAGE_INFOBOX; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_manage_infobox', 'y', ($aInfo->affiliate_manage_infobox=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_manage_infobox', 'n',  ($aInfo->affiliate_manage_infobox=='n')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_MANAGE_LOGO; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_manage_logo', 'y', ($aInfo->affiliate_manage_logo=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_manage_logo', 'n',  ($aInfo->affiliate_manage_logo=='n')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_MANAGE_STYLESHEET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_manage_stylesheet', 'y', ($aInfo->affiliate_manage_stylesheet=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_manage_stylesheet', 'n',  ($aInfo->affiliate_manage_stylesheet=='n')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_MANAGE_BANNERS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_manage_banners', 'y', ($aInfo->affiliate_manage_banners=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_manage_banners', 'n',  ($aInfo->affiliate_manage_banners=='n')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;<?php echo TEXT_OWN_PRODUCT_INFO; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' ?>
            <?php echo tep_draw_radio_field('affiliate_own_product_info', 'y', ($aInfo->affiliate_own_product_info=='y')) . '&nbsp;&nbsp;' . TEXT_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('affiliate_own_product_info', 'n',  ($aInfo->affiliate_own_product_info!='y')) . '&nbsp;&nbsp;' . TEXT_NOT_ACTIVE; ?></td>
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
 if (!tep_session_is_registered('login_affiliate')){
   if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMMISSION; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_COMMISSION; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_commission_percent', $aInfo->affiliate_commission_percent, 'maxlength="5"'); ?></td>
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
<?php 
  if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_company', $aInfo->affiliate_company, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_COMPANY_TAXID; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_company_taxid', $aInfo->affiliate_company_taxid, 'maxlength="64"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php 
  }
?>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_PAYMENT_DETAILS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
  if (AFFILIATE_USE_CHECK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_CHECK; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_check', $aInfo->affiliate_payment_check, 'maxlength="100"'); ?></td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_PAYPAL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_paypal', $aInfo->affiliate_payment_paypal, 'maxlength="64"'); ?></td>
          </tr>
<?php
  }
  if (AFFILIATE_USE_BANK == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_name', $aInfo->affiliate_payment_bank_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_branch_number', $aInfo->affiliate_payment_bank_branch_number, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_swift_code', $aInfo->affiliate_payment_bank_swift_code, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_account_name', $aInfo->affiliate_payment_bank_account_name, 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_payment_bank_account_number', $aInfo->affiliate_payment_bank_account_number, 'maxlength="64"'); ?></td>
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
            <td class="main"><?php echo tep_draw_input_field('affiliate_street_address', $aInfo->affiliate_street_address, 'maxlength="64"', true); ?></td>
          </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_suburb', $aInfo->affiliate_suburb, 'maxlength="64"', false); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_city', $aInfo->affiliate_city, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_postcode', $aInfo->affiliate_postcode, 'maxlength="8"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('affiliate_country_id', tep_get_countries(), $aInfo->affiliate_country_id, 'onChange="update_zone(this.form);"'); ?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('affiliate_zone_id', tep_prepare_country_zones_pull_down($aInfo->affiliate_country_id), $aInfo->affiliate_zone_id, 'onChange="resetStateText(this.form);"'); ?></td>
          </tr>
          <tr>
            <td class="main">&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_state', $aInfo->affiliate_state, 'maxlength="32" onChange="resetZoneSelected(this.form);"'); ?></td>
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
            <td class="main"><?php echo tep_draw_input_field('affiliate_telephone', $aInfo->affiliate_telephone, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_fax', $aInfo->affiliate_fax, 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AFFILIATE_HOMEPAGE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_homepage', $aInfo->affiliate_homepage, 'maxlength="64"', true); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_USERHOMEPAGE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = " where affiliate_id like '" . $keywords . "' or affiliate_firstname like '" . $keywords . "' or affiliate_lastname like '" . $keywords . "' or affiliate_email_address like '" . $keywords . "'";
    }
    if (tep_session_is_registered('login_affiliate')){
      if ($search == ''){
        $search .= " where affiliate_id = '" . $login_id. "'";
      }else{
        $search .= " and affiliate_id = '" . $login_id. "'";
      }
    }
    $affiliate_query_raw = "select * from " . TABLE_AFFILIATE . $search . " order by affiliate_lastname";
    $affiliate_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, 
    $affiliate_query_raw, $affiliate_query_numrows);
    $affiliate_query = tep_db_query($affiliate_query_raw);
    while ($affiliate = tep_db_fetch_array($affiliate_query)) {
      $info_query = tep_db_query("select affiliate_commission_percent, affiliate_date_account_created as date_account_created, affiliate_date_account_last_modified as date_account_last_modified, affiliate_date_of_last_logon as date_last_logon, affiliate_number_of_logons as number_of_logons from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate['affiliate_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['acID']) || (@$HTTP_GET_VARS['acID'] == $affiliate['affiliate_id'])) && (!$aInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $affiliate['affiliate_country_id'] . "' and language_id = '" . (int)$languages_id . "'");
        $country = tep_db_fetch_array($country_query);

        $affiliate_info = array_merge($country, $info);

        $aInfo_array = array_merge($affiliate, $affiliate_info);
        $aInfo = new objectInfo($aInfo_array);
      }

      if ( (is_object($aInfo)) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '\'">' . "\n";
      }
      if (substr($affiliate['affiliate_homepage'],0,7) != "http://") $affiliate['affiliate_homepage']="http://".$affiliate['affiliate_homepage'];
?>
                <td class="dataTableContent"><?php echo $affiliate['affiliate_id']; ?></td>        
                <td class="dataTableContent"><?php echo $affiliate['affiliate_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $affiliate['affiliate_firstname']; ?></td>
                <td class="dataTableContent" align="right"><?php if($affiliate['affiliate_commission_percent'] > AFFILIATE_PERCENT || $affiliate['affiliate_commission_percent'] > 0) echo $affiliate['affiliate_commission_percent']; else echo  AFFILIATE_PERCENT; ?> %</td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $affiliate['affiliate_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>'; echo '<a href="' . $affiliate['affiliate_homepage'] . '" target="_blank">' . $affiliate['affiliate_homepage'] . '</a>'; ?></td>
                <td class="dataTableContent">
<?php
      if ($affiliate['affiliate_isactive'] == '1') {
        if (!tep_session_is_registered('login_affiliate')){
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $affiliate['affiliate_id'] . '&action=setflag&flag=0') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
        }else{
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) ;
        }
      } else {
        if (!tep_session_is_registered('login_affiliate')){
          echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, 'page=' . $HTTP_GET_VARS['page'] . '&aID=' . $affiliate['affiliate_id'] . '&action=setflag&flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
        }else{
          echo tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
        }
      }
?></td>
                <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . tep_image(DIR_WS_ICONS . 'statistics.gif', ICON_STATISTICS) . '</a>&nbsp;'; if ( (is_object($aInfo)) && ($affiliate['affiliate_id'] == $aInfo->affiliate_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID')) . 'acID=' . $affiliate['affiliate_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_split->display_count($affiliate_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_AFFILIATES); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_split->display_links($affiliate_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'acID'))); ?></td>
                  </tr>
<?php
    if (tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
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

      $contents = array('form' => tep_draw_form('affiliate', FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($aInfo)) {
        $heading[] = array('text' => '<b>' . $aInfo->affiliate_firstname . ' ' . $aInfo->affiliate_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ' . (!tep_session_is_registered('login_affiliate')?' <a href="' . tep_href_link(FILENAME_AFFILIATE, tep_get_all_get_params(array('acID', 'action')) . 'acID=' . $aInfo->affiliate_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, 'selected_box=affiliate&affiliate=' . $aInfo->affiliate_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>':''));

        $affiliate_sales_raw = "select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) where o.orders_status in (" . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . ") and a.affiliate_id = '" . $aInfo->affiliate_id . "'";
        $affiliate_sales_values = tep_db_query($affiliate_sales_raw);
        $affiliate_sales = tep_db_fetch_array($affiliate_sales_values);

        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . tep_date_short($aInfo->date_account_created));
        $contents[] = array('text' => '' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . tep_date_short($aInfo->date_account_last_modified));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_LAST_LOGON . ' '  . tep_date_short($aInfo->date_last_logon));
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $aInfo->number_of_logons);
        $contents[] = array('text' => '' . TEXT_INFO_COMMISSION . ' ' . $aInfo->affiliate_commission_percent . ' %');
        $contents[] = array('text' => '' . TEXT_INFO_COUNTRY . ' ' . $aInfo->countries_name);
        $contents[] = array('text' => '' . TEXT_INFO_NUMBER_OF_SALES . ' ' . $affiliate_sales['count'],'');
        $contents[] = array('text' => '' . TEXT_INFO_SALES_TOTAL . ' ' . $currencies->display_price($affiliate_sales['total'],''));
        $contents[] = array('text' => '' . TEXT_INFO_AFFILIATE_TOTAL . ' ' . $currencies->display_price($affiliate_sales['payment'],''));
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
