<?php
/*
  $Id: orders.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_CATALOG.'ebay/core.php');
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

//---PayPal WPP Modification START ---//

  include(DIR_WS_CLASSES . 'order.php');
  
  include(DIR_WS_INCLUDES . 'paypal_wpp/paypal_wpp_include.php');
  $paypal_wpp = new paypal_wpp_admin;
//---PayPal WPP Modification END ---//


 /* ** GOOGLE CHECKOUT **/
  define('GC_STATE_NEW', 100);
  define('GC_STATE_PROCESSING', 101);
  define('GC_STATE_SHIPPED', 102);
  define('GC_STATE_REFUNDED', 103);
  define('GC_STATE_SHIPPED_REFUNDED', 104);
  define('GC_STATE_CANCELED', 105);
  function google_checkout_state_change($check_status, $status, $oID, 
                                              $cust_notify, $notify_comments) {
      global $db,$messageStack, $orders_statuses;

      define('API_CALLBACK_ERROR_LOG', 
                       DIR_FS_CATALOG. "/googlecheckout/logs/response_error.log");
      define('API_CALLBACK_MESSAGE_LOG',
                       DIR_FS_CATALOG . "/googlecheckout/logs/response_message.log");

      include_once(DIR_FS_CATALOG.'/includes/modules/payment/googlecheckout.php');
      include_once(DIR_FS_CATALOG.'/googlecheckout/library/googlerequest.php');

      $googlepayment = new googlecheckout();
      
      $Grequest = new GoogleRequest($googlepayment->merchantid, 
                                    $googlepayment->merchantkey, 
                                    MODULE_PAYMENT_GOOGLECHECKOUT_MODE==
                                      'https://sandbox.google.com/checkout/'
                                      ?"sandbox":"production",
                                    DEFAULT_CURRENCY);
      $Grequest->SetLogFiles(API_CALLBACK_ERROR_LOG, API_CALLBACK_MESSAGE_LOG);


      $google_answer = tep_db_fetch_array(tep_db_query("SELECT go.google_order_number,  go.order_amount, o.customers_email_address, gc.buyer_id, o.customers_id
                                      FROM " . $googlepayment->table_order . " go 
                                      inner join " . TABLE_ORDERS . " o on go.orders_id =  o.orders_id
                                      inner join " . $googlepayment->table_name . " gc on  gc.customers_id = o.customers_id
                                      WHERE go.orders_id = '" . (int)$oID ."'
                                      group by o.customers_id order by o.orders_id desc"));

      $google_order = $google_answer['google_order_number'];  
      $amount = $google_answer['order_amount'];  

    // If status update is from Google New -> Google Processing on the Admin UI
    // this invokes the processing-order and charge-order commands
    // 1->Google New, 2-> Google Processing
    if($check_status['orders_status'] == GC_STATE_NEW 
               && $status == GC_STATE_PROCESSING && $google_order != '') {
      list($curl_status,) = $Grequest->SendChargeOrder($google_order, $amount);
      if($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_CHARGE_ORDER, 'error');
      }
      else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_CHARGE_ORDER, 'success');           
      }
      list($curl_status,) = $Grequest->SendProcessOrder($google_order);
      if($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_PROCESS_ORDER, 'error');
      }
      else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_PROCESS_ORDER, 'success');           
      }
    } 
    
    // If status update is from Google Processing or Google Refunded -> Google Shipped on the  Admin UI
    // this invokes the deliver-order and archive-order commands
    // 2->Google Processing or Google Refunded, 3-> Google Shipped (refunded)
    else if(($check_status['orders_status'] == GC_STATE_PROCESSING 
            || $check_status['orders_status'] == GC_STATE_REFUNDED)
                 && ($status == GC_STATE_SHIPPED || $status == GC_STATE_SHIPPED_REFUNDED )
                 && $google_order != '') {
      $carrier = $tracking_no = "";
      // Add tracking Data
      if(isset($_POST['carrier_select']) &&  ($_POST['carrier_select'] != 'select') 
           && isset($_POST['tracking_number']) && !empty($_POST['tracking_number'])) {
        $carrier = $_POST['carrier_select'];
        $tracking_no = $_POST['tracking_number'];
        $comments = GOOGLECHECKOUT_STATE_STRING_TRACKING ."\n" .
                    GOOGLECHECKOUT_STATE_STRING_TRACKING_CARRIER . $_POST['carrier_select']  ."\n" .
                    GOOGLECHECKOUT_STATE_STRING_TRACKING_NUMBER . $_POST['tracking_number'] .  "";
        tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
                    (orders_id, orders_status_id, date_added, customer_notified, comments)
                    values ('" . (int)$oID . "',
                    '" . tep_db_input(($check_status['orders_status']==GC_STATE_REFUNDED
                                      ?GC_STATE_SHIPPED_REFUNDED:GC_STATE_SHIPPED)) . "',
                    now(),
                    '" . tep_db_input($cust_notify) . "',
                    '" . tep_db_input($comments)  . "')");
         
      }
      
      list($curl_status,) = $Grequest->SendDeliverOrder($google_order, $carrier,
                              $tracking_no, ($cust_notify==1)?"true":"false");
      if($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_DELIVER_ORDER, 'error');
      }
      else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_DELIVER_ORDER, 'success');           
      }
      list($curl_status,) = $Grequest->SendArchiveOrder($google_order);
      if($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_ARCHIVE_ORDER, 'error');
      }
      else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_ARCHIVE_ORDER, 'success');           
      }
    } 
    // If status update is to Google Canceled on the Admin UI
    // this invokes the cancel-order and archive-order commands
    else if($check_status['orders_status'] != GC_STATE_CANCELED &&
            $status == GC_STATE_CANCELED && $google_order != '') {
      if($check_status['orders_status'] != GC_STATE_NEW){
        list($curl_status,) = $Grequest->SendRefundOrder($google_order, 0,
                                        GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED
                                        );
        if($curl_status != 200) {
          $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_REFUND_ORDER, 'error');
        }
        else {
          $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_REFUND_ORDER, 'success');           
        }
      }
      else {
        // Tell google witch is the OSC's internal order Number        
        list($curl_status,) = $Grequest->SendMerchantOrderNumber($google_order, $oID);
        if($curl_status != 200) {
          $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_MERCHANT_ORDER_NUMBER, 'error');
        }
        else {
          $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_MERCHANT_ORDER_NUMBER,  'success');          
        }
      }
//    Is the order is not archive, I do it
      if($check_status['orders_status'] != GC_STATE_SHIPPED 
         && $check_status['orders_status'] != GC_STATE_SHIPPED_REFUNDED){
        list($curl_status,) = $Grequest->SendArchiveOrder($google_order);
        if($curl_status != 200) {
          $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_ARCHIVE_ORDER, 'error');
        }
        else {
          $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_ARCHIVE_ORDER, 'success');           
        }
      }
//    Cancel the order
      list($curl_status,) = $Grequest->SendCancelOrder($google_order, 
                                      GOOGLECHECKOUT_STATE_STRING_ORDER_CANCELED,
                                      $notify_comments);
      if($curl_status != 200) {
        $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_CANCEL_ORDER, 'error');
      }
      else {
        $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_CANCEL_ORDER, 'success');           
      }
    }
    else if($google_order != '' 
            && $check_status['orders_status'] != $status){
      $statuses = array();
      foreach($orders_statuses as $status_array){
        $statuses[$status_array['id']] = $status_array['text'];
      }
      $messageStack->add_session( sprintf(GOOGLECHECKOUT_ERR_INVALID_STATE_TRANSITION,
                                  $statuses[$check_status['orders_status']],
                                  $statuses[$status],
                                  $statuses[$check_status['orders_status']]),
                                  'error');
    }
    
    // Send Buyer's message
    if($cust_notify==1 && isset($notify_comments) && !empty($notify_comments)) {
      $cust_notify_ok = '0';      
      if(!((strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH)
              && MODULE_PAYMENT_GOOGLECHECKOUT_USE_CART_MESSAGING=='True')){
    
        list($curl_status,) = $Grequest->sendBuyerMessage($google_order, 
                             $notify_comments, "true");
        if($curl_status != 200) {
          $messageStack->add_session(GOOGLECHECKOUT_ERR_SEND_MESSAGE_ORDER, 'error');
          $cust_notify_ok = '0';
        }
        else {
          $messageStack->add_session(GOOGLECHECKOUT_SUCCESS_SEND_MESSAGE_ORDER, 'success');           
          $cust_notify_ok = '1';
        }
        if(strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH) {
          $messageStack->add_session(
          sprintf(GOOGLECHECKOUT_WARNING_CHUNK_MESSAGE, GOOGLE_MESSAGE_LENGTH), 'warning');           
        }
      }
      // Cust notified
      return $cust_notify_ok;
    }
    // Cust notified
    return '0';
  }
  // ** END GOOGLE CHECKOUT ** 

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_session_is_registered('login_affiliate') && ($action == 'update_order' || $action == 'delete')){
    tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))));
  }
  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_order':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);

        $order_updated = false;
        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);
// BOF: WebMakers.com Added: Downloads Controller
// always update date and time on order_status
// original        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
                   if ( ($check_status['orders_status'] != $status) || $comments != '' || ($status ==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
        $check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status2 = tep_db_fetch_array($check_status_query2);
      if ( $check_status2['orders_status']==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS') . "', download_count = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT') . "' where orders_id = '" . (int)$oID . "'");
      }
// EOF: WebMakers.com Added: Downloads Controller

// ** GOOGLE CHECKOUT **
          chdir("./..");
          require_once(DIR_WS_LANGUAGES . $language . '/modules/payment/googlecheckout.php');
          $payment_value= MODULE_PAYMENT_GOOGLECHECKOUT_TEXT_TITLE;
          $num_rows = tep_db_num_rows(tep_db_query("select google_order_number from  google_orders where orders_id= ". (int)$oID));

          if($num_rows != 0) {
            $customer_notified = google_checkout_state_change($check_status, $status, $oID, 
                               (@$_POST['notify']=='on'?1:0), 
                               (@$_POST['notify_comments']=='on'?$comments:''));
          }
          $customer_notified = isset($customer_notified)?$customer_notified:'0';
// ** END GOOGLE CHECKOUT **

          $customer_notified = '0';
          if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
            $notify_comments = '';
// BOF: WebMakers.com Added: Downloads Controller - Only tell of comments if there are comments
            if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }
// EOF: WebMakers.com Added: Downloads Controller

// ** GOOGLE CHECKOUT **
            $force_email = false;
            if($num_rows != 0 && (strlen(htmlentities(strip_tags($notify_comments))) > GOOGLE_MESSAGE_LENGTH && MODULE_PAYMENT_GOOGLECHECKOUT_USE_CART_MESSAGING == 'True')) {
              $force_email = true;
              $messageStack->add_session(GOOGLECHECKOUT_WARNING_SYSTEM_EMAIL_SENT, 'warning');          
            }

            if($num_rows == 0 || $force_email) {

            $check_affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE_SALES . " where affiliate_orders_id = '" . $oID . "'");
            if (tep_db_num_rows($check_affiliate_query)){
              $check_affiliate_data = tep_db_fetch_array($check_affiliate_query);
              $query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $check_affiliate_data['affiliate_id'] . "'");
              $data = tep_db_fetch_array($query);
              $eMail_store = ($data['affiliate_store_name']!=''?$data['affiliate_store_name']:STORE_NAME);
              $eMail_address = $data['affiliate_email_from'];
              $eMail_store_owner = $data['affiliate_firstname'] . ' ' . $data['affiliate_lastname'];
            }else{
              $eMail_store = STORE_NAME;
              $eMail_address = STORE_OWNER_EMAIL_ADDRESS;
              $eMail_store_owner = STORE_OWNER;
            }

            $email = $eMail_store . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_get_clickable_link(tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL')) . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, $eMail_address);

            $customer_notified = '1';
          }
          }

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }
        if ($order_updated == true) {
          if (AUCTION_BLOX_ENABLED == 'True'){
          //+++AUCTIONBLOX.COM
            //Magic # 3 == Delivered order status: Set auction as completed.
            if($status == 3) {
              include(DIR_FS_CATALOG_MODULES . 'auctionblox/includes/classes/abxManager.php');
              $abxManager = new abxManager;
              $abxManager->updateStatusByOrder($abxManager->COMPLETED, $oID);
            }
          //+++AUCTIONBLOX.COM
          }else{      
            $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
          }
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        tep_remove_order($oID, $HTTP_POST_VARS['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
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
// BOF: WebMakers.com Added: Additional info for Orders
// Look up things in orders
$the_extra_query= tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
$the_extra= tep_db_fetch_array($the_extra_query);
$the_customers_id= $the_extra['customers_id'];
// Look up things in customers
$the_extra_query= tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $the_customers_id . "'");
$the_extra= tep_db_fetch_array($the_extra_query);
$the_customers_fax= $the_extra['customers_fax'];
// EOF: WebMakers.com Added: Additional info for Orders

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}

function invoiceSwap( stateCtrl ){
  var newstate = stateCtrl.checked;
  var chList = document.getElementsByName('invoice_print[]');
  if ( chList.length>0 ) for(var i=0; i<chList.length; i++){
    if ( typeof chList[i].checked != 'undefined' ) chList[i].checked = newstate;
  }
}
function invoiceTick( checkCtrl ){

}
function btnInvoice(){
  var pass=false;
  var chList = document.getElementsByName('invoice_print[]');
  if ( chList.length>0 ) for(var i=0; i<chList.length; i++){
    if ( typeof chList[i].checked != 'undefined' && chList[i].checked ) {
      pass=true;
    }
  }
  if ( pass ) {
    document.batch_form.submit();
  }
  return false;
}

//--></script>
<?php
include(DIR_WS_INCLUDES . 'javascript/xml_used.js.php');
?>

<?php 
  //---PayPal WPP Modification START ---//
  $paypal_wpp->add_javascript();
  //---PayPal WPP Modification END ---//
?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CUSTOMERS;
  $header_title_menu_link= tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
  $header_title_submenu=HEADING_TITLE . '<br>' . TEXT_CURRENT_SERVER_TIME . ' ' . date(PHP_DATE_TIME_FORMAT);
  $header_title_additional=tep_draw_form('orders', FILENAME_ORDERS, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit').'</form>';
  $header_title_additional.= '<br>' . tep_draw_form('status', FILENAME_ORDERS, '', 'get').HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"').'</form>';
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
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<td class="pageHeading" align="right">
<?php
  if (/*($order->info['orders_status']==DEFAULT_ORDERS_STATUS_ID) &&*/ ($order->info['transaction_id']==0) && !tep_session_is_registered("login_affiliate")) echo '<a href="' . tep_href_link("edit_orders.php", tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> &nbsp; '; ?>

<?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
<?php
// BOF: WebMakers.com Added: Downloads Controller - Extra order info
?>
              <tr>
                <td class="main"><b><?php echo 'FAX #:'; ?></b></td>
                <td class="main"><?php echo $the_customers_fax; ?></td>
              </tr>
<?php
// EOF: WebMakers.com Added: Downloads Controller
?>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
// BOF: WebMakers.com Added: Show Order Info
?>
<!-- add Order # // -->
<tr>
<td class="main"><b>Order # </b></td>
<td class="main"><?php echo tep_db_input($oID); ?></td>
</tr>
<!-- add date/time // -->
<tr>
  <td class="main"><b>Order Date & Time</b></td>
  <td class="main"><?php echo tep_datetime_short($order->info['date_purchased']); ?></td>
</tr>
<!-- add date/time // -->
<tr>
  <td class="main"><b><?php echo TEXT_ADMIN?></b></td>
  <td class="main"><?php echo $order->info['order_admin']; ?></td>
</tr>
<tr>
  <td class="main"><b><?php echo TEXT_CUSTOMER_ADMIN?></b></td>
  <td class="main"><?php echo $order->customer['admin']; ?></td>
</tr>
<?php
// EOF: WebMakers.com Added: Show Order Info
?>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
          <tr>
            <td class="main">
            <?php
              if($order->info['payment_class']=='cnet' && !tep_session_is_registered("login_affiliate")){
                $data = tep_db_fetch_array(tep_db_query('select capture_date, settlement_date, transaction_id, approval_code from ' . TABLE_ORDERS . ' where orders_id=' . tep_db_input($oID)));
                if(!tep_not_null($data['approval_code'])){
                  $process_button_string =
                  tep_draw_hidden_field('merchantId', MODULE_PAYMENT_CNET_MERCHANT_ID) .
                  tep_draw_hidden_field('terminalId', MODULE_PAYMENT_CNET_TERMINAL_ID) .
                  tep_draw_hidden_field('amount', number_format(tep_round($order->info['total'], 2), 2)) .
                  tep_draw_hidden_field('tax', number_format(tep_round($order->info['tax'], 2), 0)) .
                  tep_draw_hidden_field('cardNum', $CardNumber) .
                  tep_draw_hidden_field('expDate', date ("my", mktime(0,0,0,$this->cc_expires_month,1,$order->info['cc_expires']))) .
                  tep_draw_hidden_field('verificationCode', $order->info['cc_cvv']) .
                  tep_draw_hidden_field('transactionNum', $order->customer['customer_id'] . date('i')) .
                  tep_draw_hidden_field('requestType', MODULE_PAYMENT_CNET_REQUEST_TYPE) .
                  tep_draw_hidden_field('avsAddress', $order->billing['street_address'] . ' ' . $order->billing['suburb'] . ' ' . $order->billing['city'] ) .
                  tep_draw_hidden_field('avsZip', $order->billing['postcode']) .
                  tep_draw_hidden_field('cc_card_type', $order->info['cc_type']) .
                  tep_draw_hidden_field('cc_fullname', $order->info['cc_owner']) .
                  tep_draw_hidden_field('osCsid', tep_session_id());
                  ?>
                    <form action="<?php echo tep_href_link('orders_void.php', tep_get_all_get_params()); ?>" method="post">
                      <?php echo $process_button_string; ?><input type="Submit" value=" Capture ">
                    </form>
                  <?php
                } else {
                  echo AUTORIZATION_DATE. tep_datetime_short($data['capture_date']) . '<br>';
                  echo APPROVAL_CODE . $data['approval_code'] . '<br>';
                  if (tep_not_null($data['settlement_date']))
                    echo SETTLEMENT_DATE . tep_datetime_short($data['settlement_date']) . '<br>';
                }
              }
            ?>
            </td>
          </tr>
<?php
    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CVN_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_cvn']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
<?php
if( preg_match('/ebay/i',$order->info['payment_method']) ){
  echo '<tr><td colspan="2" class="main">'.ebay_paypal_trans( (int)$oID ).'</td></tr>';
}

?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;&nbsp;<i> - ' . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($order->products[$i]['attributes'][$j]['option'])) . ($order->products[$i]['attributes'][$j]['value'] ? ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) : '');
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value'], true) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'] * $order->products[$i]['qty'], $order->products[$i]['tax']) , (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
$query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " where orders_id = '" . $oID . "'");
if (tep_db_num_rows($query)){

?>
      <tr>
        <td class="main">
          <table border="0" cellpadding="5" cellspacing="0">
<?php
  while ($data = tep_db_fetch_array($query)){
    echo '<tr><td class="main">' . $data['orders_products_name'] . '</td>';
    echo '<td class="main">' . $data['orders_products_filename'] . ' ' . $data['download_count_1'] . ' ' . TEXT_DOWNLOAD . '</td></tr>';
  }
?>          
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
}
?>
<?php 
  //---PayPal WPP Modification START ---//
  if ($order->info['payment_class']=='paypal_wpp') {
    $paypal_wpp->display_buttons($oID);
  }
  //---PayPal WPP Modification END ---//
?>

      <tr>
        <td class="main">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td valign="top" width="50%">
      <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      <?php //---PayPal WPP Modification START ---// ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_TRANSACTION_INFO; ?></b></td>
      <?php //---PayPal WPP Modification END ---// ?>
          </tr>
<?php
//---PayPal WPP Modification START ---//
    $orders_history_query = tep_db_query("select orders_status_history_id, orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$oID . "' order by date_added");
//---PayPal WPP Modification END ---//
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
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
//---PayPal WPP Modification START ---//
             '            <td class="smallText">' . $paypal_wpp->get_transaction_info($orders_history['orders_status_history_id']) . '&nbsp;</td>' . "\n" .
//---PayPal WPP Modification END ---//
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
<?php
  if (!tep_session_is_registered('login_affiliate')){
?>
      <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
<!-- googlecheckout Tracking Number -->
<?php 
// orders_status == STATE_PROCESSING -> Processing before delivery

  if(strpos($order->info['payment_method'], 'Google')!= -1 &&  $order->info['orders_status'] == GC_STATE_PROCESSING){
      echo '<td><table border="0" cellpadding="3" cellspacing="0"  width="100%">   
        <tbody>
          <tr>  
            <td style="border-top: 2px solid rgb(255,  255, 255); border-right: 2px solid rgb(255, 255, 255);" nowrap="nowrap" colspan="2">
                <b>Shipping Information</b>  
            </td>  
          </tr>
          <tr>  
            <td nowrap="nowrap" valign="middle"  width="1%">  
              <font size="2">  
                <b>Tracking:</b>  
              </font>  
            </td>  
            <td style="border-right: 2px solid rgb(255,  255, 255); border-bottom: 2px solid rgb(255, 255, 255);" nowrap="nowrap">   
              <input name="tracking_number"  style="color: rgb(0, 0, 0);" id="trackingBox" size="20" type="text">   
            </td>  
          </tr>  
          <tr>  
            <td nowrap="nowrap" valign="middle"  width="1%">  
              <font size="2">  
                <b>Carrier:</b>  
              </font>  
            </td>  
            <td style="border-right: 2px solid rgb(255,  255, 255);" nowrap="nowrap">  
              <select name="carrier_select"  style="color: rgb(0, 0, 0);" id="carrierSelect">  
                <option value="select"  selected="selected">
                 Select ...  
                </option>   
                <option value="USPS">
                 USPS  
                </option>   
                <option value="DHL">
                 DHL  
                </option>   
                <option value="UPS">
                 UPS  
                </option>   
                <option value="Other">
                 Other  
                </option>   
                <option value="FedEx">
                 FedEx  
                </option>   
              </select>  
            </td>  
          </tr>     
        </tbody> 
      </table></td>';
    
  }
?>
<!-- end googlecheckout Tracking Number -->
          </tr>
        </table></td>
      </form></tr>
<?php
  }
?>
      </table>
      </td>
      <td valign="top" width="50%">
          <!-- ebay extra info -->
          <?php ebay_order_info(intval($_GET['oID'])) ?>
          <!-- ebay extra info -->
      </td>
      </tr>
         </table>
       </td>
      </tr>
      
           <tr>
        <td colspan="2" align="right"><?php echo '<a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $HTTP_GET_VARS['oID']) . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_PACKINGSLIP) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $HTTP_GET_VARS['oID']) . '\')">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
     </tr>
<?php
  } else {
?>
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php echo tep_draw_form('batch_form',FILENAME_ORDERS_INVOICE, '', 'post', 'target="_blank"'); ?>
              <tr class="dataTableHeadingRow">
                <?php
                if (XML_ORDERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True"){
                ?>
                <td class="dataTableHeadingContent"  colspan=2><?php echo TEXT_XML_DUMP; ?></td>
                <?php
                }
                ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center" width="40"><?php echo TABLE_HEADING_INVOICE; ?></td>
                <td class="dataTableHeadingContent" align="right"  width="50"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if (isset($HTTP_GET_VARS['cID'])) {
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      $orders_query_raw = "select o.settlement_date, o.approval_code, o.last_xml_export, o.transaction_id, o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS_STATUS . " s, ".(tep_session_is_registered('login_affiliate')?TABLE_AFFILIATE_SALES . ' asales, ':'') . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' " . (tep_session_is_registered('login_affiliate')?" and asales.affiliate_orders_id = o.orders_id and asales.affiliate_id = '" .$login_id . "'":'')." and ot.class = 'ot_total' order by orders_id DESC";
    } elseif (tep_not_null($HTTP_GET_VARS['status'])) {
      $status = tep_db_prepare_input($HTTP_GET_VARS['status']);
      $orders_query_raw = "select o.settlement_date, o.approval_code, o.last_xml_export, o.transaction_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS_STATUS . " s, ".(tep_session_is_registered('login_affiliate')?TABLE_AFFILIATE_SALES . ' asales, ':'') . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' " . (tep_session_is_registered('login_affiliate')?" and asales.affiliate_orders_id = o.orders_id and asales.affiliate_id = '" .$login_id . "'":'')." and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.settlement_date, o.approval_code, o.last_xml_export, o.transaction_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS_STATUS . " s, ". (tep_session_is_registered('login_affiliate')?TABLE_AFFILIATE_SALES . ' asales, ':'') . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' " . (tep_session_is_registered('login_affiliate')?" and asales.affiliate_orders_id = o.orders_id and asales.affiliate_id = '" .$login_id . "'":'')." and ot.class = 'ot_total' order by o.orders_id DESC";
    }
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
        $on_click_effect = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'"';
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
      } else {
        $on_click_effect = 'onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '\'"';
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
      }
?>
                 <?php if (XML_ORDERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
                 <td class="dataTableHeadingContent"><input type="checkbox" id="<?php echo $orders['orders_id'];?>" onclick="javascript:setflagcookie('<?php echo $orders['orders_id'];?>','xml_orders','')"></td>
                 <td class="dataTableHeadingContent"  <?php echo $onclick_effect;?>>
                 <?php if (tep_not_null($orders["last_xml_export"])) {
                          echo tep_image(DIR_WS_IMAGES.'icons/success.gif',sprintf(TEXT_LAST_XML_DUMP,$orders["last_xml_export"]),10,10);
                        } else {
                          echo tep_image(DIR_WS_IMAGES.'icons/error.gif',TEXT_NEVER_EXPORTED,10,10);
                        }
                 ?>
                 </td>
                 <?php }?>
                <td class="dataTableContent" <?php echo $on_click_effect;?>><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                <td class="dataTableContent" align="right" <?php echo $on_click_effect;?>><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent" align="center" <?php echo $on_click_effect;?>><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="right" <?php echo $on_click_effect;?>><?php echo $orders['orders_status_name']; ?></td>
                <td class="dataTableContent" align="center"><input type="checkbox" name="invoice_print[]" id="inv_<?php echo $orders['orders_id'];?>" value="<?php echo $orders['orders_id'];?>" onclick="invoiceTick(this);"></td>
                <td class="dataTableContent" align="right" <?php echo $on_click_effect;?>><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
               <tr>
                 <?php if (XML_ORDERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
                 <td colspan="2">&nbsp;</td>
                 <?php } ?>
                 <td colspan="4" align="right">&nbsp;<?php echo tep_image_submit('button_batch_pdf_invoice.gif', IMAGE_BATCH_PDF_INVOICE, 'onclick="return btnInvoice();"'); ?></td>
                 <td align="center" class="smallText" colspan="2" valign="top"><input type="checkbox" onclick="invoiceSwap(this);"><?php echo LABEL_UNTICK_ALL;?></td>
               </tr>
              </form>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                   <?php if (XML_ORDERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
                   <tr>
                    <td class="smallText" colspan=2 align="center"><?php echo $backup_to_xml;?></td>
                  </tr>
                  <?php if ($can_backup_xml) {?>
                   <tr>
                    <td class="smallText" colspan=2 align="center">
                      <a href="<?php echo tep_href_link(FILENAME_BACKUP_XML_DATA,'action=all&datatype=orders')?>"><?php echo TEXT_XML_ALL_ORDERS;?></a> |
                      <a onclick="javascript:check_selected_datas('xml_orders','orders');" href="#"><?php echo TEXT_XML_SELECTED_ORDERS;?></a>

                    </td>
                  </tr>
                  <? }?>
                   <tr>
                    <td class="smallText" colspan=2 align="center"><br><br></td>
                  </tr>
                  <?php }?>                  
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>');
      $contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');
      $contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;


    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

//        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');



//        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));


        if (!tep_not_null($oInfo->approval_code) && !tep_not_null($oInfo->transaction_id)) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ' . (!tep_session_is_registered('login_affiliate')?' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oInfo->orders_id). '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>':''));
        }

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);

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
<?php if (XML_ORDERS_DUMP_ENABLE == "True" && XML_DUMP_ENABLE == "True") {?>
           <script language="Javascript">
             restoreBoxes("xml_orders","");
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
