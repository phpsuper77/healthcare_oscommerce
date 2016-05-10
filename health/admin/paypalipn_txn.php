<?php
/*
  $Id: paypalipn_txn.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Paypal IPN v0.981 for Milestone 2
  Copyright (c) 2003 Pablo Pasqualino
  pablo_osc@osmosisdc.com
  http://www.osmosisdc.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($HTTP_GET_VARS['action']) {
      case 'deleteconfirm':
        $paypalipn_txn_id = tep_db_prepare_input($HTTP_GET_VARS['txnID']);

        tep_db_query("delete from " . TABLE_PAYPALIPN_TXN . " where txn_id = '" . (int)$paypalipn_txn_id . "'");

        tep_redirect(tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] .'&action=view'));
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_PAYPALIPN_ADMIN;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=paypalipn');
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYPALIPN_TRANSACTIONS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYPALIPN_AMOUNT; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYPALIPN_RESULT; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYPALIPN_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $paypalipn_query_raw = "select * from " . TABLE_PAYPALIPN_TXN . " order by paypalipn_txn_id desc";
  $paypalipn_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $paypalipn_query_raw, $paypalipn_query_numrows);
  $paypalipn_query = tep_db_query($paypalipn_query_raw);
  while ($paypalipn = tep_db_fetch_array($paypalipn_query)) {
    if (((!$HTTP_GET_VARS['txnID']) || (@$HTTP_GET_VARS['txnID'] == $paypalipn['txn_id'])) && (!$txnInfo)) {
      $txnInfo_array = $paypalipn;
      $txnInfo = new objectInfo($txnInfo_array);
    }

    if ((is_object($txnInfo)) && ($paypalipn['txn_id'] == $txnInfo->txn_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $paypalipn['txn_id'] . '&action=view') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $paypalipn['txn_id'] . '&action=view') . '\'">' . "\n";
    }

		$paypalipn['payment_date'] = explode(',',$paypalipn['payment_date']);
?>
                <td class="dataTableContent"><?php echo $paypalipn['txn_id']; ?></td>
                <td class="dataTableContent"><?php echo $paypalipn['mc_currency'].' '.$paypalipn['mc_gross']; ?></td>
                <td class="dataTableContent"><?php echo substr($paypalipn['ipn_result'],0,5).'. '.substr($paypalipn['payment_status'],0,4); ?></td>
                <td class="dataTableContent"><?php echo $paypalipn['payment_date'][0]; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($txnInfo)) && ($paypalipn['txn_id'] == $txnInfo->txn_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $paypalipn['txn_id']) . '&action=view">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $paypalipn_split->display_count($paypalipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PAYPALIPN_TRANSACTIONS); ?></td>
                    <td class="smallText" align="right"><?php echo $paypalipn_split->display_links($paypalipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'view':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_VIEW_PAYPALIPN_TRANSACTIONS . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $txnInfo->txn_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
      $contents[] = array('text' => '<br><b>TXN ID:</b> '. $txnInfo->txn_id);
      $contents[] = array('text' => '<b>IPN Result:</b> <font color="#ff0000">'. $txnInfo->ipn_result . '</font>');
      $contents[] = array('text' => '<b>Receiver Email:</b> '. $txnInfo->receiver_email);
      $contents[] = array('text' => '<b>Business:</b> '. $txnInfo->business);
      $contents[] = array('text' => '<b>Item Name:</b> '. $txnInfo->item_name);
      $contents[] = array('text' => '<b>Item Number:</b> '. $txnInfo->item_number);
      $contents[] = array('text' => '<b>Quantity:</b> '. $txnInfo->quantity);
      $contents[] = array('text' => '<b>Invoice:</b> '. $txnInfo->invoice);
      $contents[] = array('text' => '<b>Custom:</b> '. $txnInfo->custom);
      $contents[] = array('text' => '<b>Option Name 1:</b> '. $txnInfo->option_name1);
      $contents[] = array('text' => '<b>Option Selection 1:</b> '. $txnInfo->option_selection1);
      $contents[] = array('text' => '<b>Option Name 2:</b> '. $txnInfo->option_name2);
      $contents[] = array('text' => '<b>Option Selection 2:</b> '. $txnInfo->option_selection2);
      $contents[] = array('text' => '<b>Num Cart Items:</b> '. $txnInfo->num_cart_items);
      $contents[] = array('text' => '<b>Payment Status:</b> <font color="#ff0000">'. $txnInfo->payment_status . '</font>');
      $contents[] = array('text' => '<b>Pending Reason:</b> <font color="#ff0000">'. $txnInfo->pending_reason . '</font>');
      $contents[] = array('text' => '<b>Payment Date:</b> '. $txnInfo->payment_date);
      $contents[] = array('text' => '<b>Settle Amount:</b> '. $txnInfo->settle_amount);
      $contents[] = array('text' => '<b>Settle Currency:</b> '. $txnInfo->settle_currency);
      $contents[] = array('text' => '<b>Exchange Rate:</b> '. $txnInfo->exchange_rate);
      $contents[] = array('text' => '<b>Payment Gross:</b> <font color="#ff0000">'. $txnInfo->payment_gross . '</font>');
      $contents[] = array('text' => '<b>Payment Fee:</b> '. $txnInfo->payment_fee);
      $contents[] = array('text' => '<b>MC Gross:</b> '. $txnInfo->mc_gross);
      $contents[] = array('text' => '<b>MC Fee:</b> '. $txnInfo->mc_fee);
      $contents[] = array('text' => '<b>MC Currency:</b> '. $txnInfo->mc_currency);
      $contents[] = array('text' => '<b>Tax:</b> '. $txnInfo->tax);
      $contents[] = array('text' => '<b>TXN Type:</b> '. $txnInfo->txn_type);
      $contents[] = array('text' => '<b>For Auction:</b> '. $txnInfo->for_auction);
      $contents[] = array('text' => '<b>Memo:</b> '. $txnInfo->memo);
      $contents[] = array('text' => '<b>First Name:</b> '. $txnInfo->first_name);
      $contents[] = array('text' => '<b>Last Name:</b> '. $txnInfo->last_name);
      $contents[] = array('text' => '<b>Address Street:</b> '. $txnInfo->address_street);
      $contents[] = array('text' => '<b>Address City:</b> '. $txnInfo->address_city);
      $contents[] = array('text' => '<b>Address State:</b> '. $txnInfo->address_state);
      $contents[] = array('text' => '<b>Address Zip:</b> '. $txnInfo->address_zip);
      $contents[] = array('text' => '<b>Address Country:</b> '. $txnInfo->address_country);
      $contents[] = array('text' => '<b>Address Status:</b> '. $txnInfo->address_status);
      $contents[] = array('text' => '<b>Payer Email:</b> '. $txnInfo->payer_email);
      $contents[] = array('text' => '<b>Payer ID:</b> '. $txnInfo->payer_id);
      $contents[] = array('text' => '<b>Payer Status:</b> '. $txnInfo->payer_status);
      $contents[] = array('text' => '<b>Payment Type:</b> '. $txnInfo->payment_type);
      $contents[] = array('text' => '<b>Notify Version:</b> '. $txnInfo->notify_version);
      $contents[] = array('text' => '<b>Verify Sign:</b> '. $txnInfo->verify_sign);
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_PAYPALIPN_TRANSACTIONS . '</b>');

      $contents = array('form' => tep_draw_form('paypalipn_txn', FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $txnInfo->txn_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>TXN ID: ' . $txnInfo->txn_id . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PAYPALIPN_TRANSACTIONS, 'page=' . $HTTP_GET_VARS['page'] . '&txnID=' . $txnInfo->txn_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
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
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
