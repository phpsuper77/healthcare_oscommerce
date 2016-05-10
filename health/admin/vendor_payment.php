<?php
/*
  $Id: vendor_payment.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

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

  $payments_statuses = array();
  $payments_status_array = array();
  $payments_status_query = tep_db_query("select vendor_payment_status_id, vendor_payment_status_name from " . TABLE_VENDOR_PAYMENT_STATUS . " where vendor_language_id = '" . $languages_id . "' ");
  while ($payments_status = tep_db_fetch_array($payments_status_query)) {
    $payments_statuses[] = array('id' => $payments_status['vendor_payment_status_id'],
                                 'text' => $payments_status['vendor_payment_status_name']);
    $payments_status_array[$payments_status['vendor_payment_status_id']] = $payments_status['vendor_payment_status_name'];
  }

  switch ($HTTP_GET_VARS['action']) {
    case 'start_billing':
// Billing can be a lengthy process
      tep_set_time_limit(0);
// We are only billing orders which are AFFILIATE_BILLING_TIME days old
      $time = mktime(1, 1, 1, date("m"), date("d") - VENDOR_BILLING_TIME, date("Y"));
      $oldday = date("Y-m-d", $time);
// Select all affiliates who earned enough money since last payment
      $sql="
        SELECT a.vendor_id, sum(a.vendor_payment) 
          FROM " . TABLE_VENDOR_SALES . " a, " . TABLE_ORDERS . " o 
          WHERE a.vendor_billing_status != 1 and a.vendor_orders_id = o.orders_id and o.orders_status in (" . VENDOR_PAYMENT_ORDER_MIN_STATUS . ") and a.vendor_date <= '" . $oldday . "' 
          GROUP by a.vendor_id 
          having sum(a.vendor_payment) >= '" . VENDOR_THRESHOLD . "'
        ";
      $vendor_payment_query = tep_db_query($sql);

// Start Billing:
      while ($vendor_payment = tep_db_fetch_array($vendor_payment_query)) {

// mysql does not support joins in update (planned in 4.x)

// Get all orders which are AFFILIATE_BILLING_TIME days old
        $sql="
        SELECT a.vendor_orders_id 
          FROM " . TABLE_VENDOR_SALES . " a, " . TABLE_ORDERS . " o 
          WHERE a.vendor_billing_status!=1 and a.vendor_orders_id=o.orders_id and o.orders_status in (" . VENDOR_PAYMENT_ORDER_MIN_STATUS . ") and a.vendor_id='" . $vendor_payment['vendor_id'] . "' and a.vendor_date <= '" . $oldday . "'
        ";
        $vendor_orders_query=tep_db_query ($sql);
        $orders_id ="(";
        while ($vendor_orders = tep_db_fetch_array($vendor_orders_query)) {
          $orders_id .= $vendor_orders['vendor_orders_id'] . ",";
        }
        $orders_id = substr($orders_id, 0, -1) .")";

// Set the Sales to Temp State (it may happen that an order happend while billing)
        $sql="UPDATE " . TABLE_VENDOR_SALES . " 
        set vendor_billing_status=99 
          where vendor_id='" .  $vendor_payment['vendor_id'] . "' 
          and vendor_orders_id in " . $orders_id . " 
        ";
        tep_db_query ($sql);

// Get Sum of payment (Could have changed since last selects);
        $sql="
        SELECT sum(vendor_payment) as vendor_payment
          FROM " . TABLE_VENDOR_SALES . " 
          WHERE vendor_id='" .  $vendor_payment['vendor_id'] . "' and  vendor_billing_status=99 
        ";
        $vendor_billing_query = tep_db_query ($sql);
        $vendor_billing = tep_db_fetch_array($vendor_billing_query);
// Get affiliate Informations
        $sql="
        SELECT a.*, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id 
          from " . TABLE_VENDOR . " a 
          left join " . TABLE_ZONES . " z on (a.vendor_zone_id  = z.zone_id) 
          left join " . TABLE_COUNTRIES . " c on (a.vendor_country_id = c.countries_id)
          WHERE vendor_id = '" . $vendor_payment['vendor_id'] . "' and c.language_id = '" . (int)$languages_id . "' 
        ";
        $vendor_query=tep_db_query ($sql);
        $vendor = tep_db_fetch_array($vendor_query);

// Get need tax informations for the affiliate
        //$vendor_tax_rate = tep_get_affiliate_tax_rate(AFFILIATE_TAX_ID, $vendor['vendor_country_id'], $vendor['vendor_zone_id']);
        $vendor_tax = 0;//tep_round(($vendor_billing['vendor_payment'] * $vendor_tax_rate / 100), 2); // Netto-Provision
        $vendor_payment_total = $vendor_billing['vendor_payment'] + $vendor_tax;
// Bill the order
        $vendor['vendor_state'] = tep_get_zone_code($vendor['vendor_country_id'], $vendor['vendor_zone_id'], $vendor['vendor_state']);
        $sql_data_array = array('vendor_id' => $vendor_payment['vendor_id'],
                                'vendor_payment' => $vendor_billing['vendor_payment'],
                                'vendor_payment_tax' => $vendor_tax,
                                'vendor_payment_total' => $vendor_payment_total,
                                'vendor_payment_date' => 'now()',
                                'vendor_payment_status' => '0',
                                'vendor_firstname' => $vendor['vendor_firstname'],
                                'vendor_lastname' => $vendor['vendor_lastname'],
                                'vendor_street_address' => $vendor['vendor_street_address'],
                                'vendor_suburb' => $vendor['vendor_suburb'],
                                'vendor_city' => $vendor['vendor_city'],
                                'vendor_country' => $vendor['countries_name'],
                                'vendor_postcode' => $vendor['vendor_postcode'],
                                'vendor_company' => $vendor['vendor_company'],
                                'vendor_state' => $vendor['vendor_state'],
                                'vendor_address_format_id' => $vendor['address_format_id']);
        tep_db_perform(TABLE_VENDOR_PAYMENT, $sql_data_array);
        $insert_id = tep_db_insert_id(); 
// Set the Sales to Final State 
        tep_db_query("update " . TABLE_VENDOR_SALES . " set vendor_payment_id = '" . $insert_id . "', vendor_billing_status = 1, vendor_payment_date = now() where vendor_id = '" . $vendor_payment['vendor_id'] . "' and vendor_billing_status = 99");

// Notify Affiliate
        if (VENDOR_NOTIFY_AFTER_BILLING == 'true') {
          $check_status_query = tep_db_query("select af.vendor_email_address, ap.vendor_lastname, ap.vendor_firstname, ap.vendor_payment_status, ap.vendor_payment_date, ap.vendor_payment_date from " . TABLE_VENDOR_PAYMENT . " ap, " . TABLE_VENDOR . " af where vendor_payment_id  = '" . $insert_id . "' and af.vendor_id = ap.vendor_id ");
          $check_status = tep_db_fetch_array($check_status_query);
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_VENDOR_PAYMENT_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link(tep_catalog_href_link(FILENAME_CATALOG_VENDOR_PAYMENT_INFO, 'payment_id=' . $insert_id, 'SSL')) . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['vendor_payment_date']) . "\n\n" . EMAIL_TEXT_NEW_PAYMENT;
          tep_mail($check_status['vendor_firstname'] . ' ' . $check_status['vendor_lastname'], $check_status['vendor_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, EMAIL_FROM);
        }
      }
      $messageStack->add_session(SUCCESS_BILLING, 'success');

      tep_redirect(tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'update_payment':
      $pID = tep_db_prepare_input($HTTP_GET_VARS['pID']);
      $status = tep_db_prepare_input($HTTP_POST_VARS['status']);

      $payment_updated = false;
      $check_status_query = tep_db_query("select af.vendor_email_address, ap.vendor_lastname, ap.vendor_firstname, ap.vendor_payment_status, ap.vendor_payment_date, ap.vendor_payment_date from " . TABLE_VENDOR_PAYMENT . " ap, " . TABLE_VENDOR . " af where vendor_payment_id = '" . tep_db_input($pID) . "' and af.vendor_id = ap.vendor_id ");
      $check_status = tep_db_fetch_array($check_status_query);
      if ($check_status['vendor_payment_status'] != $status) {
        tep_db_query("update " . TABLE_VENDOR_PAYMENT . " set vendor_payment_status = '" . tep_db_input($status) . "', vendor_last_modified = now() where vendor_payment_id = '" . tep_db_input($pID) . "'");
        $vendor_notified = '0';
// Notify Affiliate
        if ($HTTP_POST_VARS['notify'] == 'on') {
          $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_VENDOR_PAYMENT_NUMBER . ' ' . $pID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link(tep_catalog_href_link(FILENAME_CATALOG_VENDOR_PAYMENT_INFO, 'payment_id=' . $pID, 'SSL')) . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . tep_date_long($check_status['vendor_payment_date']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $payments_status_array[$status]);
          tep_mail($check_status['vendor_firstname'] . ' ' . $check_status['vendor_lastname'], $check_status['vendor_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, EMAIL_FROM);
          $vendor_notified = '1';
        }

        tep_db_query("insert into " . TABLE_VENDOR_PAYMENT_STATUS_HISTORY . " (vendor_payment_id, vendor_new_value, vendor_old_value, vendor_date_added, vendor_notified) values ('" . tep_db_input($pID) . "', '" . tep_db_input($status) . "', '" . $check_status['vendor_payment_status'] . "', now(), '" . $vendor_notified . "')");
        $order_updated = true;
      }

      if ($order_updated) {
       $messageStack->add_session(SUCCESS_PAYMENT_UPDATED, 'success');
      }

      tep_redirect(tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $pID = tep_db_prepare_input($HTTP_GET_VARS['pID']);

      tep_db_query("delete from " . TABLE_VENDOR_PAYMENT . " where vendor_payment_id = '" . tep_db_input($pID) . "'");
      tep_db_query("delete from " . TABLE_VENDOR_PAYMENT_STATUS_HISTORY . " where vendor_payment_id = '" . tep_db_input($pID) . "'");

      tep_redirect(tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action'))));
      break;
  }

  if ( ($HTTP_GET_VARS['action'] == 'edit') && tep_not_null($HTTP_GET_VARS['pID']) ) {
    $pID = tep_db_prepare_input($HTTP_GET_VARS['pID']);
    $payments_query = tep_db_query("select p.*,  a.vendor_payment_check, a.vendor_payment_paypal, a.vendor_payment_bank_name, a.vendor_payment_bank_branch_number, a.vendor_payment_bank_swift_code, a.vendor_payment_bank_account_name, a.vendor_payment_bank_account_number from " .  TABLE_VENDOR_PAYMENT . " p, " . TABLE_VENDOR . " a where vendor_payment_id = '" . tep_db_input($pID) . "' and a.vendor_id = p.vendor_id");
    $payments_exists = true;
    if (!$payments = tep_db_fetch_array($payments_query)) {
      $payments_exists = false;
      $messageStack->add(sprintf(ERROR_PAYMENT_DOES_NOT_EXIST, $pID), 'error');
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_VENDOR;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=vendor');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('orders', FILENAME_VENDOR_PAYMENT, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('sID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit').'</form><br>';
  $header_title_additional.=tep_draw_form('status', FILENAME_VENDOR_PAYMENT, '', 'get').HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PAYMENTS)), $payments_statuses), '', 'onChange="this.form.submit();"').'</form>';
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
<?php
  if (tep_session_is_registered('login_vendor')){
   if (is_numeric($HTTP_GET_VARS['status'])) {
     $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $vendor_payment_raw = "
      select p.* , s.vendor_payment_status_name 
             from " . TABLE_VENDOR_PAYMENT . " p, " . TABLE_VENDOR_PAYMENT_STATUS . " s 
             where p.vendor_payment_status = s.vendor_payment_status_id 
             and s.vendor_payment_status_id = '" . tep_db_input($status) . "' 
             and s.vendor_language_id = '" . $languages_id . "' 
             and p.vendor_id =  '" . $login_id . "' 
             order by p.vendor_payment_id DESC
             ";
  }else{
    $vendor_payment_raw = "
    select p.* , s.vendor_payment_status_name 
           from " . TABLE_VENDOR_PAYMENT . " p, " . TABLE_VENDOR_PAYMENT_STATUS . " s 
           where p.vendor_payment_status = s.vendor_payment_status_id 
           and s.vendor_language_id = '" . $languages_id . "' 
           and p.vendor_id =  '" . $login_id . "' 
           order by p.vendor_payment_id DESC
           ";
  }

    $vendor_payment_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $vendor_payment_raw, $vendor_payment_numrows);
?>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
//$products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
  
    $vendor_payment_values = tep_db_query($vendor_payment_raw );
  if (tep_db_num_rows($vendor_payment_values) > 0) {
    $number_of_payment = 0;
?>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_PAYMENT_ID; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
<?php
    while ($vendor_payment = tep_db_fetch_array($vendor_payment_values)) {
?>
            <tr class="dataTableRow">
            <td class="smallText" align="right"><?php echo $vendor_payment['vendor_payment_id']; ?></td>
            <td class="smallText" align="center"><?php echo tep_date_short($vendor_payment['vendor_payment_date']); ?></td>
            <td class="smallText" align="right"><?php echo $currencies->display_price($vendor_payment['vendor_payment_total'], ''); ?></td>
            <td class="smallText" align="right"><?php echo $vendor_payment['vendor_payment_status_name']; ?></td>
          </tr>
<?php
    }
  } else {
?>
          <tr class="dataTableHeadingRow">
            <td colspan="4" class="dataTableHeadingContent" align="center"><?php echo TEXT_NO_PAYMENTS; ?></td>
          </tr>
<?php
  }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $vendor_payment_split->display_count($vendor_payment_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?></td>
                    <td class="smallText" align="right"><?php echo $vendor_payment_split->display_links($vendor_payment_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'pID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>

<?php 
  $vendor_payment_values = tep_db_query("select sum(vendor_payment_total) as total from " . TABLE_VENDOR_PAYMENT . " where vendor_id = '" . $login_id . "'");
  $vendor_payment = tep_db_fetch_array($vendor_payment_values);
?>
          <tr>
            <td class="smallText" colspan="2"><?php echo TEXT_VENDOR_HEADER . ' ' . tep_db_num_rows(tep_db_query($vendor_payment_raw)); ?></td>
            <td class="smalltext" colspan="2" align="center"><b><?php echo $currencies->display_price($vendor_payment['total'], ''); ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php
  }else if ( ($HTTP_GET_VARS['action'] == 'edit') && ($payments_exists) ) {
    $vendor_address['firstname'] = $payments['vendor_firstname'];
    $vendor_address['lastname'] = $payments['vendor_lastname'];
    $vendor_address['street_address'] = $payments['vendor_street_address'];
    $vendor_address['suburb'] = $payments['vendor_suburb'];
    $vendor_address['city'] = $payments['vendor_city'];
    $vendor_address['state'] = $payments['vendor_state'];
    $vendor_address['country'] = $payments['vendor_country'];
    $vendor_address['postcode'] = $payments['vendor_postcode'];

?>
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
      <!--      <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>-->
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_VENDOR; ?></b></td>
                <td class="main"><?php echo tep_address_format($payments['vendor_address_format_id'], $vendor_address, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_VENDOR_PAYMENT; ?></b></td>
                <td class="main">&nbsp;<?php echo $currencies->format($payments['vendor_payment_total']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_VENDOR_BILLED; ?></b></td>
                <td class="main">&nbsp;<?php echo tep_date_short($payments['vendor_payment_date']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_VENDOR_PAYING_POSSIBILITIES; ?></b></td>
                <td class="main"><table border="1" cellspacing="0" cellpadding="5">
                  <tr>
<?php
  if (VENDOR_USE_BANK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_VENDOR_PAYMENT_BANK_TRANSFER . '</b><br><br>' . TEXT_VENDOR_PAYMENT_BANK_NAME . ' ' . $payments['vendor_payment_bank_name'] . '<br>' . TEXT_VENDOR_PAYMENT_BANK_BRANCH_NUMBER . ' ' . $payments['vendor_payment_bank_branch_number'] . '<br>' . TEXT_VENDOR_PAYMENT_BANK_SWIFT_CODE . ' ' . $payments['vendor_payment_bank_swift_code'] . '<br>' . TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NAME . ' ' . $payments['vendor_payment_bank_account_name'] . '<br>' . TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER . ' ' . $payments['vendor_payment_bank_account_number'] . '<br>'; ?></td>
<?php
  }
  if (VENDOR_USE_PAYPAL == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_VENDOR_PAYMENT_PAYPAL . '</b><br><br>' . TEXT_VENDOR_PAYMENT_PAYPAL_EMAIL . '<br>' . $payments['vendor_payment_paypal'] . '<br>'; ?></td>
<?php
  }
  if (VENDOR_USE_CHECK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_VENDOR_PAYMENT_CHECK . '</b><br><br>' . TEXT_VENDOR_PAYMENT_CHECK_PAYEE . '<br>' . $payments['vendor_payment_check'] . '<br>'; ?></td>
<?php
  }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr valign="top">
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
<?php echo tep_draw_form('status', FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('action')) . 'action=update_payment'); ?>
        <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo PAYMENT_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $payments_statuses, $payments['vendor_payment_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo PAYMENT_NOTIFY_VENDOR; ?></b><?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_NEW_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_OLD_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_VENDOR_NOTIFIED; ?></b></td>
          </tr>
<?php
    $vendor_history_query = tep_db_query("select vendor_new_value, vendor_old_value, vendor_date_added, vendor_notified from " . TABLE_VENDOR_PAYMENT_STATUS_HISTORY . " where vendor_payment_id = '" . tep_db_input($pID) . "' order by vendor_status_history_id desc");
    if (tep_db_num_rows($vendor_history_query)) {
      while ($vendor_history = tep_db_fetch_array($vendor_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText">' . $payments_status_array[$vendor_history['vendor_new_value']] . '</td>' . "\n" .
             '            <td class="smallText">' . (tep_not_null($vendor_history['vendor_old_value']) ? $payments_status_array[$vendor_history['vendor_old_value']] : '&nbsp;') . '</td>' . "\n" .
             '            <td class="smallText" align="center">' . tep_date_short($vendor_history['vendor_date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($vendor_history['vendor_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
        }
        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="4">' . TEXT_NO_PAYMENT_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_INVOICE, 'pID=' . $HTTP_GET_VARS['pID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
      <tr>
        <td height="100%"><?php echo tep_draw_separator('pixel_trans.gif', '1', '100%'); ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NET_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_BILLED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($HTTP_GET_VARS['sID']) {
      // Search only payment_id by now
      $sID = tep_db_prepare_input($HTTP_GET_VARS['sID']);
      $payments_query_raw = "select p.* , s.vendor_payment_status_name from " . TABLE_VENDOR_PAYMENT . " p , " . TABLE_VENDOR_PAYMENT_STATUS . " s where p.vendor_payment_id = '" . tep_db_input($sID) . "' and p.vendor_payment_status = s.vendor_payment_status_id and s.vendor_language_id = '" . $languages_id . "' " . ($login_vendor == 1?" and p.vendor_id = '" . $login_id . "'":'') . " order by p.vendor_payment_id DESC";
    } elseif (is_numeric($HTTP_GET_VARS['status'])) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $payments_query_raw = "select p.* , s.vendor_payment_status_name from " . TABLE_VENDOR_PAYMENT . " p , " . TABLE_VENDOR_PAYMENT_STATUS . " s where s.vendor_payment_status_id = '" . tep_db_input($status) . "' and p.vendor_payment_status = s.vendor_payment_status_id and s.vendor_language_id = '" . $languages_id . "' " . ($login_vendor == 1?" and p.vendor_id = '" . $login_id . "'":'') . " order by p.vendor_payment_id DESC";
    } else {
      $payments_query_raw = "select p.* , s.vendor_payment_status_name from " . TABLE_VENDOR_PAYMENT . " p , " . TABLE_VENDOR_PAYMENT_STATUS . " s where p.vendor_payment_status = s.vendor_payment_status_id and s.vendor_language_id = '" . $languages_id . "' " . ($login_vendor == 1?" and p.vendor_id = '" . $login_id . "'":'') . " order by p.vendor_payment_id DESC";
    }
    $payments_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $payments_query_raw, $payments_query_numrows);
    $payments_query = tep_db_query($payments_query_raw);
    while ($payments = tep_db_fetch_array($payments_query)) {
      if (((!$HTTP_GET_VARS['pID']) || ($HTTP_GET_VARS['pID'] == $payments['vendor_payment_id'])) && (!$pInfo)) {
        $pInfo = new objectInfo($payments);
      }

      if ( (is_object($pInfo)) && ($payments['vendor_payment_id'] == $pInfo->vendor_payment_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['vendor_payment_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $payments['vendor_firstname'] . ' ' . $payments['vendor_lastname']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['vendor_payment'])); ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['vendor_payment'] + $payments['vendor_payment_tax'])); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($payments['vendor_payment_date']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $payments['vendor_payment_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($pInfo)) && ( $payments['vendor_payment_id'] == $pInfo->vendor_payment_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID')) . 'pID=' . $payments['vendor_payment_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $payments_split->display_count($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?></td>
                    <td class="smallText" align="right"><?php echo $payments_split->display_links($payments_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'pID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
              <?php
              if (!tep_session_is_registered('login_vendor')){
              ?>
              <tr>
                <td align=right colspan=6><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, 'pID=' . $pInfo->vendor_payment_id. '&action=start_billing' ) . '">' . tep_image_button('button_vendor_billing.gif', IMAGE_VENDOR_BILLING) . '</a>'; ?></td>
              </tr>
              <?php
              }
              ?>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PAYMENT . '</b>');

      $contents = array('form' => tep_draw_form('payment', FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id. '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($pInfo)) {
        $heading[] = array('text' => '<b>[' . $pInfo->vendor_payment_id . ']&nbsp;&nbsp;' . tep_datetime_short($pInfo->vendor_payment_date) . '</b>');

        if (!tep_session_is_registered('login_vendor')){
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_VENDOR_PAYMENT, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->vendor_payment_id  . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        }

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_VENDOR_INVOICE, 'pID=' . $pInfo->vendor_payment_id ) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> ');
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
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
