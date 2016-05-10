<?php
/*
  $Id: admin.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal.php');

  include_once(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();

  $payment_statuses = array(
    array('id' =>'Completed',          'text' => 'Completed'),
    array('id' =>'Pending',            'text' => 'Pending'),
    array('id' =>'Failed',             'text' => 'Failed'),
    array('id' =>'Denied',             'text' => 'Denied'),
    array('id' =>'Refunded',           'text' => 'Refunded'),
    array('id' =>'Reversed',           'text' => 'Reversed'),
    array('id' =>'Canceled_Reversal',  'text' => 'Canceled_Reversal')
  );

?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_ADMIN_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo tep_draw_form('payment_status', FILENAME_PAYPAL, '', 'get') . HEADING_PAYMENT_STATUS . ' ' . tep_draw_pull_down_menu('payment_status', array_merge(array(array('id' => 'ALL', 'text' => TEXT_ALL_IPNS)), $payment_statuses), $HTTP_GET_VARS['payment_status'], 'onChange="this.form.submit();"').'</form>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYMENT_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_GROSS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_FEE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_NET_AMOUNT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

  $ipn_search = '';

  if(isset($HTTP_GET_VARS['payment_status']) && tep_not_null($HTTP_GET_VARS['payment_status']) && $HTTP_GET_VARS['payment_status'] != 'ALL')
    $ipn_search = "where p.payment_status = '" . tep_db_prepare_input($HTTP_GET_VARS['payment_status']) . "'";

  $ipn_query_raw = "select p.* from " . TABLE_PAYPAL . " as p $ipn_search order by p.paypal_id DESC";

  $ipn_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $ipn_query_raw, $ipn_query_numrows);

  $ipn_query = tep_db_query($ipn_query_raw);

  while($ipn_trans = tep_db_fetch_array($ipn_query)) {

    if ((isset($HTTP_GET_VARS['ipnId']) === false || (isset($HTTP_GET_VARS['ipnId']) === true && $HTTP_GET_VARS['ipnId'] == $ipn_trans['paypal_id'])) && isset($ipnInfo) === false)
      $ipnInfo = new objectInfo($ipn_trans);

    if (isset($ipnInfo) && is_object($ipnInfo) && ($ipn_trans['paypal_id'] === $ipnInfo->paypal_id) ) {

        $rArray = array('Refunded','Reversed','Canceled_Reversal');

        if (in_array($ipnInfo->payment_status,$rArray))
          $txn_id = $ipnInfo->parent_txn_id;
        else
          $txn_id = $ipnInfo->txn_id;


        $order_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS . " o left join " . TABLE_PAYPAL . " p on p.paypal_id = o.payment_id where p.txn_id = '" . tep_db_input($txn_id) . "'");

        $onClick = '';

        if(tep_db_num_rows($order_query)) {

          $order = tep_db_fetch_array($order_query);

          $ipnInfo->orders_id = $order['orders_id'];

          $onClick = "onclick=\"document.location.href='" . tep_href_link(FILENAME_ORDERS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $ipnInfo->orders_id . '&action=edit' . '&referer=ipn') . "'\"";

        }

      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" '. $onClick .'>' . "\n";

    } else {

      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAYPAL, 'page=' . $HTTP_GET_VARS['page'] . '&ipnId=' . $ipn_trans['paypal_id']) . '\'">' . "\n";

    }
?>
                <td class="dataTableContent"> <?php echo paypal::date($ipn_trans['payment_date']); ?> </td>
                <td class="dataTableContent"><?php echo $ipn_trans['payment_status']; ?></td>
                <td class="dataTableContent" align="right"><?php echo paypal::format($ipn_trans['mc_gross'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php echo paypal::format($ipn_trans['mc_fee'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php echo paypal::format($ipn_trans['mc_gross']-$ipn_trans['mc_fee'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($ipnInfo) && is_object($ipnInfo) && ($ipn_trans['paypal_id'] == $ipnInfo->paypal_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PAYPAL, 'page=' . $HTTP_GET_VARS['page'] . '&ipnId=' . $ipn_trans['paypal_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ipn_split->display_count($ipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_TRANSACTIONS); ?></td>
                    <td class="smallText" align="right"><?php echo $ipn_split->display_links($ipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php

  if (is_object($ipnInfo) === true) {

    $heading = array();
    $contents = array();

    $heading[] = array('text' => '<b>' . TEXT_INFO_PAYPAL_IPN_HEADING.' #' . $ipnInfo->paypal_id . '</b>');

    if(empty($ipnInfo->orders_id) === false)
      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('ipnId', 'oID', 'action')) . 'oID=' . $ipnInfo->orders_id .'&action=edit&referer=ipn') . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a>');
    elseif(empty($ipnInfo->txn_id) === false)
      $contents[] = array('align' => 'center', 'text' => '<a href="javascript:openWindow(\''.tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('ipnId', 'oID', 'action')) . 'action=details&info=' . $ipnInfo->txn_id ).'\');">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>');

      $contents[] = array('text' => '<br>' . TABLE_HEADING_DATE . ': ' . paypal::date($ipnInfo->payment_date));

    $str = '            <td width="25%" valign="top">' . "\n";

    $box = new box;

    $str .= $box->infoBox($heading, $contents);

    $str .= '            </td>' . "\n";

    echo $str;

   }

?>
          </tr>
        </table></td>
      </tr>
    </table>
