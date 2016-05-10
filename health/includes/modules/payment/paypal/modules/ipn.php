<?php
/*
  $Id: ipn.php,v 1.1.1.1 2005/12/03 21:36:12 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  paypal::includeLanguageFile('paypal','english');

  $debug = paypal::newDebug();

  $ipn = paypal::newIPN($_POST);

  //post back to PayPal to validate
  if ($ipn->authenticate(MODULE_PAYMENT_PAYPAL_DOMAIN) === false && $ipn->testMode('Off') === true)
    $ipn->dienice('500');

  //Check both the receiver_email and business ID fields match
  if ($ipn->validateReceiverEmails(MODULE_PAYMENT_PAYPAL_ID,MODULE_PAYMENT_PAYPAL_BUSINESS_ID,MODULE_PAYMENT_PAYPAL_BUSINESS_IDS) === false)
    $ipn->dienice('500');

  if ($ipn->uniqueTxnId() && $ipn->isReversal() && strlen($ipn->key['parent_txn_id']) == 17) {

   //parent_txn_id is the txn_id of the original transaction
   $txn = $ipn->queryTxnID($ipn->key['parent_txn_id']);

   if (empty($txn) === false) {

      $ipn->insert($txn['paypal_id']);

      // update the order's status
      switch ($ipn->reversalType()) {

        case 'Canceled_Reversal':

          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;

          break;

        case 'Reversed':

          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID;

          break;

        case 'Refunded':

          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_REFUNDED_STATUS_ID;

          break;

      }

      $paypal->updateOrderStatusAndHistoryByPaymentId($txn['paypal_id'],$order_status);

    }

  } elseif ($ipn->isCartPayment() && empty($paypal->vars['orders_id']) === false) {

    require_once(DIR_WS_CLASSES . 'order.php');

    $order = new order($paypal->vars['orders_id']);

    //Check that txn_id has not been previously processed
    if ($ipn->uniqueTxnId() === true) {

      //Payment is either Completed, Pending or Failed

      $ipn->insert();

      $paypal->setOrderPaymentId($ipn->Id());

      $paypal->resetCustomersBasket($order->customer['id']);

      switch ($ipn->paymentStatus()) {

        case 'Completed':

          if ($ipn->validPayment($paypal->vars['payment_amount'],$paypal->vars['payment_currency']))

            $paypal->catalogCheckoutUpdate($order);

          else

            $paypal->updateOrderStatusAndHistoryByPaymentId($ipn->Id(),MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);

          break;

        case 'Failed':

          $paypal->updateOrderStatusAndHistoryByPaymentId($ipn->Id(),MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);

          break;

        case 'Pending':

          //Assumed to do nothing since the order is initially in a Pending ORDER Status

          break;

      }//end switch

      if (empty($paypal->vars['abx_basket_ids']) === false) {

        require_once(DIR_WS_MODULES . 'auctionblox/includes/classes/abxManager.php');

        $abxManager = new abxManager;

        $abxManager->updateStatusAndSetOrderId($paypal->vars['orders_id'],explode(',',$paypal->vars['abx_basket_ids']));

      }

    } else { // not a unique transaction => Pending Payment

      //Assumes there is only one previous IPN transaction

      $pendingTxn = $ipn->queryPendingStatus( $ipn->txnId() );

      if ($pendingTxn['payment_status'] === 'Pending') {

        $ipn->updateStatus($pendingTxn['paypal_id']);

        switch ($ipn->paymentStatus()) {

          case 'Completed':

           if ($ipn->validPayment($paypal->vars['payment_amount'],$paypal->vars['payment_currency']))

            $paypal->catalogCheckoutUpdate($order);

           else

            $paypal->updateOrderStatusAndHistoryByPaymentId($pendingTxn['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);

           break;

          case 'Denied':

            $paypal->updateOrderStatusAndHistoryByPaymentId($pendingTxn['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);

            break;

        }//end switch

      }//end if Pending Payment

    }//else pending payment

  } elseif ($ipn->isAuction() === true) {

    if ($ipn->uniqueTxnId() === true)
      $ipn->insert();

    if ($debug->enabled === true)
      $debug->add(PAYPAL_AUCTION,PAYPAL_AUCTION_MSG);

  } elseif ($ipn->txnType('send_money') === true) {

    if ($ipn->uniqueTxnId() === true)
      $ipn->insert();

    if ($debug->enabled === true)
      $debug->add(PAYMENT_SEND_MONEY_DESCRIPTION,sprintf(PAYMENT_SEND_MONEY_DESCRIPTION_MSG,number_format($ipn->key['mc_gross'],2),$ipn->key['mc_currency']));

  } elseif ($debug->enabled === true && $ipn->testMode('On') === true) {

    $debug->error(TEST_INCOMPLETE,TEST_INCOMPLETE_MSG);

  }

  if ($ipn->testMode('On') === true &&  $ipn->validDigest() === true) {

    $page = $debug->display();

    require_once( $page->template() );

  }
?>