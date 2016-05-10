<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
    $notify_string = 'action=notify&';
    $notify = $HTTP_POST_VARS['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);

  $orders_query = tep_db_query("select orders_id, orders_status from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
  $orders = tep_db_fetch_array($orders_query);

  $paypalipn_query = tep_db_query("select ipn_result, payment_status from " . TABLE_PAYPALIPN_TXN . " where item_number = '" . (int)$orders['orders_id'] . "'");
  $paypalipn = tep_db_fetch_array($paypalipn_query);

  if ($paypalipn['ipn_result']=='VERIFIED') {
    if ($paypalipn['payment_status']=='Completed') {
      $NAVBAR_TITLE_2 = PAYPAL_NAVBAR_TITLE_2_OK;
      $HEADING_TITLE = PAYPAL_HEADING_TITLE_OK;
      $TEXT_SUCCESS = PAYPAL_TEXT_SUCCESS_OK;
    } else if ($paypalipn['payment_status']=='Pending') {
      $NAVBAR_TITLE_2 = PAYPAL_NAVBAR_TITLE_2_PENDING;
      $HEADING_TITLE = PAYPAL_HEADING_TITLE_PENDING;
      $TEXT_SUCCESS = PAYPAL_TEXT_SUCCESS_PENDING;
    };
    $cart->reset(TRUE);
  } else if ($paypalipn['ipn_result']=='INVALID') {
    $NAVBAR_TITLE_2 = PAYPAL_NAVBAR_TITLE_2_FAILED;
    $HEADING_TITLE = PAYPAL_HEADING_TITLE_FAILED;
    $TEXT_SUCCESS = PAYPAL_TEXT_SUCCESS_FAILED;
  } else if ($orders['orders_status']==99999) {
      $NAVBAR_TITLE_2 = PAYPAL_NAVBAR_TITLE_2_PENDING;
      $HEADING_TITLE = PAYPAL_HEADING_TITLE_PENDING;
      $TEXT_SUCCESS = PAYPAL_TEXT_SUCCESS_PENDING;
  } else {
    $NAVBAR_TITLE_2 = NAVBAR_TITLE_2;
    $HEADING_TITLE = HEADING_TITLE;
    $TEXT_SUCCESS = TEXT_SUCCESS;
  };
  $breadcrumb->add($NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }

  $content = CONTENT_CHECKOUT_SUCCESS;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
