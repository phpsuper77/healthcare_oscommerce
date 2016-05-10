<?php
/*
  $Id: vendor_sales.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

  OSC-vendor

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if ($HTTP_GET_VARS['acID'] > 0) {

    $vendor_sales_raw = "
      select asale.*, os.orders_status_name as orders_status, a.vendor_firstname, a.vendor_lastname from " . TABLE_VENDOR_SALES . " asale 
      left join " . TABLE_ORDERS . " o on (asale.vendor_orders_id = o.orders_id) 
      left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = " . $languages_id . ") 
      left join " . TABLE_VENDOR . " a on (a.vendor_id = asale.vendor_id) 
      where asale.vendor_id = '" . $HTTP_GET_VARS['acID'] . "' " . (tep_session_is_registered('login_vendor')?" and asale.vendor_id = '" . $login_id."'":''). " 
      order by vendor_date desc 
      ";
    $vendor_sales_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $vendor_sales_raw, $vendor_sales_numrows);

  } else {

    $vendor_sales_raw = "
      select asale.*, os.orders_status_name as orders_status, a.vendor_firstname, a.vendor_lastname from " . TABLE_VENDOR_SALES . " asale 
      left join " . TABLE_ORDERS . " o on (asale.vendor_orders_id = o.orders_id) 
      left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = " . $languages_id . ") 
      left join " . TABLE_VENDOR . " a  on (a.vendor_id = asale.vendor_id) 
       " . (tep_session_is_registered('login_vendor')?" where asale.vendor_id = '" . $login_id."'":''). " 
      order by vendor_date desc 
      ";
    $vendor_sales_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $vendor_sales_raw, $vendor_sales_numrows);
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="4">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_VALUE; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PERCENTAGE; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SALES; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
          </tr>
<?php
  if ($vendor_sales_numrows > 0) {
    $vendor_sales_values = tep_db_query($vendor_sales_raw);
    $number_of_sales = '0';
    while ($vendor_sales = tep_db_fetch_array($vendor_sales_values)) {
      $number_of_sales++;
      if (($number_of_sales / 2) == floor($number_of_sales / 2)) {
        echo '          <tr class="dataTableRowSelected">';
      } else {
        echo '          <tr class="dataTableRow">';
      }

      if (tep_session_is_registered('login_vendor')){
        $link_to = $vendor_sales['vendor_orders_id'];
      }else{
        $link_to = '<a href="orders.php?action=edit&oID=' . $vendor_sales['vendor_orders_id'] . '">' . $vendor_sales['vendor_orders_id'] . '</a>';
      }
?>
            <td class="dataTableContent"><?php echo $vendor_sales['vendor_firstname'] . " ". $vendor_sales['vendor_lastname']; ?></td>
            <td class="dataTableContent" align="center"><?php echo tep_date_short($vendor_sales['vendor_date']); ?></td>
            <td class="dataTableContent" align="right"><?php echo $link_to; ?></td>
            <td class="dataTableContent" align="right">&nbsp;&nbsp;<?php echo $currencies->display_price($vendor_sales['vendor_value'], ''); ?></td>
            <td class="dataTableContent" align="right"><?php echo $vendor_sales['vendor_percent'] . "%" ; ?></td>
            <td class="dataTableContent" align="right">&nbsp;&nbsp;<?php echo $currencies->display_price($vendor_sales['vendor_payment'], ''); ?></td>
            <td class="dataTableContent" align="center"><?php if ($vendor_sales['orders_status']) echo $vendor_sales['orders_status']; else echo TEXT_DELETED_ORDER_BY_ADMIN; ?></td>
<?php
    }
  } else {
?>
          <tr class="dataTableRowSelected">
            <td colspan="7" class="smallText"><?php echo TEXT_NO_SALES; ?></td>
          </tr>
<?php
  }
  if ($vendor_sales_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
          <tr>
            <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $vendor_sales_split->display_count($vendor_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SALES); ?></td>
                <td class="smallText" align="right"><?php echo $vendor_sales_split->display_links($vendor_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
      <tr>
      <?php 
  if ($HTTP_GET_VARS['acID'] > 0) {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_STATISTICS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  } else {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDOR_SUMMARY, '') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  }
?>
      </tr>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
