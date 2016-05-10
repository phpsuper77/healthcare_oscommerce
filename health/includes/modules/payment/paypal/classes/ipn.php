<?php
/*
  $Id: ipn.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  if (class_exists('PayPal_Client') === false) {

    require_once(dirname(__FILE__) . '/client.php');

  }

  class PayPal_IPN extends PayPal_Client {

    function PayPal_IPN(&$post_vars)
    {
      global $debug;

      if (in_array($post_vars['payment_type'],array('instant','echeck')) === false) {

        if ($debug->enabled)
          $debug->add(UNKNOWN_TXN_TYPE,sprintf(UNKNOWN_TXN_TYPE_MSG,$post_vars['payment_type']));

      } else if(strlen($post_vars['txn_id']) == 17) {

        $this->init($post_vars); //Looks like a PayPal transaction

      } else {

        if ($debug->enabled)
          $debug->add(UNKNOWN_POST,sprintf(UNKNOWN_POST_MSG,$_SERVER['REMOTE_ADDR']));

      }

      $this->setTestMode('Off');

      @register_shutdown_function(array($this,'_PayPal_IPN'));
    }

    function _PayPal_IPN()
    {
      global $debug;

      if ($debug->enabled === true)
        $debug->sendEmail();
    }

    function init($post_vars)
    {
      global $debug;

      $debug_string = '';

      $this->key = array();

      $this->response_string = 'cmd=_notify-validate';

      reset($post_vars);

      foreach ($post_vars as $var => $val) {

        $var = urldecode($var); $val = stripslashes(urldecode($val));

        if ($debug->enabled === true)
          $debug_string .= $var . '=' . $val .'&';

        if (!strcasecmp($var,'cmd') || !eregi("^[_0-9a-z-]{1,32}$",$var)) {

          unset($var); unset($val);

        } elseif (empty($var) === false) {

          if($var === 'custom')
            $this->setTxnSignature($val);
          else
            $this->key[$var] = $val;

          $this->response_string .= '&' . urlencode($var) . '=' . urlencode($val);

        }

      }//foreach post var

      if ($debug->enabled === true)
        $debug->init($debug_string);

      unset($post_vars, $debug_string);
    }

    function insert($paypal_id = '')
    {
      global $debug;

      $key_vars = array(
        'txn_type', 'reason_code', 'payment_type',
        'payment_status', 'pending_reason', 'invoice',
        'mc_currency', 'first_name', 'last_name',
        'payer_business_name', 'address_name', 'address_street',
        'address_city', 'address_state', 'address_zip',
        'address_country', 'address_status', 'payer_email',
        'payer_id', 'payer_status', 'business',
        'receiver_email', 'receiver_id', 'txn_id',
        'parent_txn_id', 'mc_gross', 'mc_fee',
        'payment_gross', 'payment_fee', 'settle_amount',
        'settle_currency', 'exchange_rate', 'for_auction',
        'auction_buyer_id', 'auction_multi_item',
        'quantity', 'tax', 'notify_version',
        'verify_sign', 'memo'
      );

      $sql_data_array                         = $this->setArray($key_vars);

      $sql_data_array['num_cart_items']       = $this->txnType('cart') ? $this->key['num_cart_items'] : '1';

      $sql_data_array['payment_date']         = $this->datetime_to_sql_format($this->key['payment_date']);

      $sql_data_array['payment_time_zone']    = $this->paymentTimeZone($this->key['payment_date']);

      $sql_data_array['auction_closing_date'] = $this->datetime_to_sql_format($this->key['auction_closing_date']);

      $sql_data_array['date_added']           = 'now()';

      tep_db_perform(TABLE_PAYPAL, $sql_data_array);

      $this->ipnId = tep_db_insert_id();

      $this->updatePaymentStatusHistory( !empty($paypal_id) ? $paypal_id : $this->ipnId );

      if($this->isAuction())
        $this->processAuction($this->ipnId);

      if($debug->enabled === true)
        $debug->add(IPN_TXN_INSERT,sprintf(IPN_TXN_INSERT_MSG,$this->ipnId));

      return $this->ipnId;
    }

    function updatePaymentStatusHistory($paypal_id)
    {
      $sql_data_array               = $this->setArray(array('payment_status', 'pending_reason', 'reason'));

      $sql_data_array['paypal_id']  = $paypal_id;

      $sql_data_array['date_added'] = 'now()';

      tep_db_perform(TABLE_PAYPAL_PAYMENT_STATUS_HISTORY, $sql_data_array);
    }

    function updateStatus($paypal_id)
    {

      $key_vars = array(
        'first_name', 'last_name', 'payer_business_name',
        'address_name', 'address_street', 'address_city',
        'address_state','address_zip', 'address_country',
        'address_status',
        'mc_gross', 'mc_currency', 'mc_fee',
        'settle_amount', 'settle_currency',
        'exchange_rate',
        'payment_status'
      );

      $sql_data_array                  = $this->setArray($key_vars);
      $sql_data_array['last_modified'] = 'now()';

      tep_db_perform(TABLE_PAYPAL, $sql_data_array, 'update', "paypal_id = " . (int)$paypal_id);

      $this->updatePaymentStatusHistory($paypal_id);
    }

    function queryTxnID($txn_id)
    {
      $sql_query = tep_db_query("select paypal_id from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($txn_id) . "' limit 1");

      return tep_db_fetch_array($sql_query);
    }

    function queryPendingStatus($txn_id)
    {
      $sql_query = tep_db_query("select paypal_id, payment_status, pending_reason from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($txn_id) . "' limit 1");

      return tep_db_fetch_array($sql_query);
    }

    function authenticate($domain, $success = 'VERIFIED')
    {
      $paypal_response = $this->getResponse($domain);

      $paypal_verification = $this->getVerificationResponse($paypal_response);

      return strstr($paypal_verification,$success);
    }

    function uniqueTxnId()
    {
      global $debug;

      if (isset($this->uniqueTxnId)) {

        return $this->uniqueTxnId;

      } else {

        $txn_id_query = tep_db_query("select txn_id from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($this->txnId()) . "' limit 1");

        if (!tep_db_num_rows($txn_id_query)) { //txn_id doesn't exist

          $this->uniqueTxnId = true;

          return $this->uniqueTxnId;

        } else {

          if($debug->enabled === true)
            $debug->add(TXN_DUPLICATE,sprintf(TXN_DUPLICATE_MSG,$this->txnId()));

          $this->uniqueTxnId = false;

          return $this->uniqueTxnId;

        }

      }
    }

    function validPayment($amount,$currency)
    {
      if (MODULE_PAYMENT_PAYPAL_IPN_CART_TEST == 'Off')
        return true;

      return parent::validPayment($amount,$currency);
    }

    //returns TABLE_PAYPAL.paypal_id
    function Id()
    {
      return $this->ipnId;
    }

    function txnId()
    {
      return $this->key['txn_id'];
    }

    //returns the transaction type (paypal.txn_type)
    function txnType($txnTypeName = '')
    {
      if(empty($txnTypeName) === false)
        return ($this->key['txn_type'] === $txnTypeName);

      return $this->key['txn_type'];
    }

    function paymentStatus($statusName = '')
    {
      if(empty($statusName) === false)
        return ($this->key['payment_status'] == $statusName);

      return $this->key['payment_status'];
    }

    function isCartPayment()
    {
      return in_array($this->key['txn_type'],array('web_accept','cart'));
    }

    function isReversal()
    {
      return in_array($this->key['payment_status'],array('Refunded','Reversed','Canceled_Reversal'));
    }

    function reversalType()
    {
      return $this->key['payment_status'];
    }

    function datetime_to_sql_format($paypalDateTime)
    {
      $months = array('Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05',  'Jun' => '06',  'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12');

      $array  = explode(" ",$paypalDateTime);

      $time   = explode(":",$array[0]);

      $hour   = $time[0];

      $minute = $time[1];

      $second = $time[2];

      $month  = $months[$array[1]];

      $day    = substr_replace($array[2],'',-1,1);

      $year   = $array[3];

      return ($year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute . ":" . $second);
    }

    function paymentTimeZone($paypalDateTime)
    {
      $array = explode(" ",$paypalDateTime);

      return $array[4];
    }

    function setArray($varnames)
    {
      $sql_data_array = array();

      $nVars = count($varnames);

      for($i=0; $i<$nVars; $i++)
        if(isset($this->key[$varnames[$i]]) && !empty($this->key[$varnames[$i]]) && trim($this->key[$varnames[$i]]) != '')
          $sql_data_array[$varnames[$i]] = $this->key[$varnames[$i]];

      return $sql_data_array;
    }

    function isAuction()
    {
      return (isset($this->key['for_auction']) && $this->key['for_auction'] === 'true');
    }

    function processAuction($paypal_id)
    {
      if($this->isAuction() && strlen($this->key['item_number']) > 0) {

        $tableQuery = tep_db_query("show tables like '" . TABLE_PAYPAL_AUCTION . "'");

        if(tep_db_num_rows($tableQuery)) {

          $items = explode(',', $this->key['item_number']);

          foreach($items as $key => $item_id) {

            // Save the auction IDs for correlation later
            $txn_check = tep_db_query("select auction_buyer_id from " . TABLE_PAYPAL_AUCTION . " where paypal_id = " . (int)$paypal_id . " and item_number = '" . tep_db_input($item_id) . "'");

            if (!tep_db_num_rows($txn_check)) {

              $sql_data_array                = $this->setArray(array('auction_buyer_id','auction_multi_item'));

              $sql_data_array['paypal_id']   = (int)$paypal_id;

              $sql_data_array['item_number'] = $item_id;

              tep_db_perform(TABLE_PAYPAL_AUCTION, $sql_data_array);

            }//if auction item does not exist

          }//foreach auction item

        }//if table paypal_auctions exists

      }//if is auction transaction
    }

    function setTxnSignature($txnSignature = '')
    {
      if(isset($this->txnSignature) === false)
        $this->txnSignature = $txnSignature;
    }

    function txnSignature()
    {
      return $this->txnSignature;
    }

  }//end class
?>