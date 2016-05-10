<?php
/*
  $Id: transaction.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  if (class_exists('PayPal_Page') === false) {

    require_once(dirname(__FILE__) . '/page.php');

  }

  paypal::includeLanguageFile('paypal',$GLOBALS['language']);

  class PayPal_Transaction extends paypal {

    function PayPal_Transaction($paypal_id = '')
    {
      if (empty($paypal_id) === false)
        $this->summaryQuery($paypal_id);
    }

    function setArray(&$srcArray,$varNames)
    {
      $array = array();

      for ($i=0, $nVars = count($varNames); $i<$nVars; $i++)
        $array[$varNames[$i]] = trim(stripslashes($srcArray[$varNames[$i]]));

      return $array;
    }

    function summaryQuery($paypal_id)
    {
      $info = array('payment_status','txn_id','date_added','payment_date');

      $txn = array('mc_currency','mc_gross','mc_fee');

      $ipn_query = tep_db_query("select " . implode(',',array_merge($info,$txn)) . " from " . TABLE_PAYPAL . " where paypal_id = " . (int)$paypal_id);

      if (tep_db_num_rows($ipn_query)) {

        $ipn = tep_db_fetch_array($ipn_query);

        $this->info = $this->setArray($ipn,$info);

        $this->txn = $this->setArray($ipn,$txn);

      }
    }

    function transactionDetailsQuery($txn_id)
    {
      $transaction_id = tep_db_prepare_input($txn_id);

      $info = array('txn_type','reason_code','payment_type','payment_status','pending_reason','invoice','payment_date','payment_time_zone','business','receiver_email','receiver_id','txn_id','parent_txn_id','notify_version','last_modified','date_added','for_auction','auction_closing_date');

      $txn = array('num_cart_items','mc_currency','mc_gross','mc_fee','payment_gross','payment_fee','settle_amount','settle_currency','exchange_rate');

      $customer = array('first_name','last_name','payer_business_name','address_name','address_street','address_city','address_state','address_zip','address_country','address_status','payer_email','payer_id','auction_buyer_id','payer_status','memo');

      $ipn_query = tep_db_query("select " . implode(',',array_merge($info,$txn,$customer)) . " from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($transaction_id) . "'");

      if (tep_db_num_rows($ipn_query)) {

        $ipn = tep_db_fetch_array($ipn_query);

        $this->info = $this->setArray($ipn,$info);

        $this->txn = $this->setArray($ipn,$txn);

        $this->customer = $this->setArray($ipn,$customer);

      }
    }

    function isPending()
    {
      return ($this->info['payment_status'] === 'Pending');
    }

    function isReversal()
    {
      return in_array($this->info['payment_status'],array('Refunded','Reversed'));
    }

    function displayPaymentType()
    {
      $array = array('instant' => 'Instant', 'echeck' => 'eCheck');

      return $array[$this->info['payment_type']];
    }

    function transactionSignature($order_id)
    {
      $txn_signature_query = tep_db_query("select txn_signature from " . TABLE_PAYPAL_CHECKOUT . " where orders_id = " . (int)$order_id . " limit 1");

      if (tep_db_num_rows($txn_signature_query)) {

        $txn_signature = tep_db_fetch_array($txn_signature_query);

        return $txn_signature['txn_signature'];

      }
    }

    function transactionSummaryLogs(&$order)
    {
      $str = '<table border="0" cellspacing="0" cellpadding="2">'."\n".
             ' <tr valign="top">'."\n".
             '   <td style="vertical-align: middle;">' . tep_image(DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/paypal.png','PayPal') . '</td>'."\n".
             '   <td class="main">'."\n".
             '     <style type="text/css">.Txns{font-family: Verdana;font-size: 10px;color: #000000;background-color: #aaaaaa;}.Txns td {padding: 2px 4px;}.TxnsTitle td {color: #000000;font-weight: bold;font-size: 13px;}.TxnsSTitle td{background-color: #ccddee;color: #000000;font-weight: bold;}</style>'."\n".
             '     <script language="javascript" type="text/javascript">function openPayPalWindow(url,name,args) {if (url == null || url == \'\') exit;if (name == null || name == \'\') name = \'paypalWindow\';if (args == null || args == \'\') args = \'toolbar,status,scrollbars,resizable,width=640,height=480,left=50,top=50\';paypalWindow = window.open(url,name,args);paypalWindow.focus();}</script>'."\n".
             '     <table cellspacing="1" cellpadding="1" border="0" class="Txns">'."\n".
             '       <tr>'."\n".
             '         <td colspan="7" bgcolor="#EEEEEE">&nbsp;<b>' . TABLE_HEADING_TXN_ACTIVITY . '</b></td>'."\n".
             '       </tr>'."\n".
             '       <tr class="TxnsSTitle">'."\n".
             '         <td nowrap>&nbsp;' . TABLE_HEADING_DATE . '&nbsp;</td>'."\n".
             '         <td nowrap>&nbsp;' . TABLE_HEADING_PAYMENT_STATUS . '&nbsp;</td>'."\n".
             '         <td nowrap>&nbsp;' . TABLE_HEADING_DETAILS . '&nbsp;</td>'."\n".
             '         <td nowrap>&nbsp;' . TABLE_HEADING_ACTION . '&nbsp;</td>'."\n".
             '         <td nowrap align="right">&nbsp;' . TABLE_HEADING_PAYMENT_GROSS . '&nbsp;</td>'."\n".
             '         <td nowrap align="right">&nbsp;' . TABLE_HEADING_PAYMENT_FEE . '&nbsp;</td>'."\n".
             '         <td nowrap align="right">&nbsp;' . TABLE_HEADING_PAYMENT_NET_AMOUNT . '&nbsp;</td>'."\n".
             '       </tr>'."\n";

      if (empty($this->info['txn_id']) === false) {

        $paypal_history_query = tep_db_query("select txn_id, payment_status, mc_gross, mc_fee, mc_currency, date_added, payment_date from " . TABLE_PAYPAL . " where parent_txn_id = '" . tep_db_input($this->info['txn_id']) . "' order by date_added desc");

        if (tep_db_num_rows($paypal_history_query)) {

          $trOdd = '#FFFFFF'; $trEven = '#EEEEEE'; $trColor = $trOdd;

          while ($paypal_history = tep_db_fetch_array($paypal_history_query)) {

            $trColor = ($trColor == $trOdd)  ? $trEven : $trOdd;

            $str .= '          <tr bgcolor="'.$trColor.'">' . "\n" .
                    '            <td nowrap>&nbsp;' . $this->date($paypal_history['payment_date']) . '&nbsp;</td>' . "\n".
                    '            <td nowrap>&nbsp;' . $paypal_history['payment_status'] . '&nbsp;</td>' . "\n" .
                    '            <td nowrap>&nbsp;'. PayPal_Page::javascriptLink(TABLE_HEADING_DETAILS,'action=details&info='.$paypal_history['txn_id']).'&nbsp;</td>' . "\n" .
                    '            <td nowrap>&nbsp;</td>' . "\n" . //Action
                    '            <td nowrap align="right">&nbsp;'. $this->format($paypal_history['mc_gross'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .
                    '            <td nowrap align="right">&nbsp;'. $this->format($paypal_history['mc_fee'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .
                    '            <td nowrap align="right">&nbsp;'. $this->format($paypal_history['mc_gross']-$paypal_history['mc_fee'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .
                    '          </tr>' . "\n";

          }

        }

        //Now determine whether the order is on hold
        if($order->info['orders_status'] === MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID) {

          $ppImgAccept = tep_image(DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/act_accept.gif',IMAGE_BUTTON_TXN_ACCEPT);

          $ppAction = '<a href="'.tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('action')).'action=accept_order&digest='.$this->digest()).'">'.$ppImgAccept.'</a>';

        } else {

          $ppAction = '';

        }

        $str .= '          <tr bgcolor="#FFFFFF">' . "\n" .
                '           <td nowrap>&nbsp;' . $this->date($this->info['payment_date']) . '&nbsp;</td>' . "\n" .
                '           <td nowrap>&nbsp;' . $this->info['payment_status'] . '&nbsp;</td>' . "\n" .
                '           <td nowrap>&nbsp;' . PayPal_Page::javascriptLink(TABLE_HEADING_DETAILS,'action=details&info='.$this->info['txn_id']) . '&nbsp;</td>' . "\n" .
                '           <td nowrap>&nbsp;' . $ppAction . '&nbsp;</td>' . "\n" .
                '           <td align="right" nowrap>&nbsp;' . $this->format($this->txn['mc_gross'],$this->txn['mc_currency']) . '&nbsp;</td>' . "\n" .
                '           <td align="right" nowrap>&nbsp;' . $this->format($this->txn['mc_fee'],$this->txn['mc_currency']) . '&nbsp;</td>' . "\n" .
                '           <td align="right" nowrap>&nbsp;' . $this->format($this->txn['mc_gross']-$this->txn['mc_fee'],$this->txn['mc_currency']) . '&nbsp;</td>' . "\n" .
                '         </tr>' . "\n";

      } else {

        $str .= '          <tr bgcolor="#FFFFFF">' . "\n" .
                '           <td colspan="7" nowrap>&nbsp;' . sprintf(TEXT_NO_IPN_HISTORY,$this->transactionSignature($_GET['oID'])) . '&nbsp;</td>' . "\n" .
                '         </tr>' . "\n";
      }

      $str .= '       </table>' . "\n" .
              '     </td>' . "\n" .
              ' </tr>' . "\n".
              '</table>' . "\n";

      return $str;
    }

  }//end class

  class PayPal_Transaction_Details extends PayPal_Transaction {

    function PayPal_Transaction_Details($txn_id)
    {
      parent::PayPal_Transaction();

      $this->transactionDetailsQuery($txn_id);
    }

  }//end class
?>
