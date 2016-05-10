<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  if (class_exists('shoppingCart') === false) {

    require_once(realpath(dirname(__FILE__) . '/../../../../classes/shopping_cart.php'));

  }

  //require_once(realpath(dirname(__FILE__) . '/../database_tables.php'));

  class PayPal_Shopping_Cart extends shoppingCart {

    var $orders_id,
        $txn_signature;

    function PayPal_Shopping_Cart()
    {
      parent::shoppingCart();

      $this->orders_id = 0;

      $this->txn_signature = '';
    }

    function checkStatus()
    {
      if (isset($this->orders_id) === true && empty($this->orders_id) === false) {

        $paypal_checkout_query = tep_db_query("select payment_id from " . TABLE_ORDERS . " where orders_id = " . (int)$this->orders_id);

        $paypal_checkout = tep_db_fetch_array($paypal_checkout_query);

        if ($paypal_checkout['payment_id'] > 0 ) {

          $this->resetShoppingCart();

          return TRUE;

        }

        return FALSE;

      }

      return FALSE;
    }

    function resetShoppingCart()
    {

      $this->reset(true,$this->orders_id);

      $this->orders_id = 0;

      $this->txn_signature = '';

      tep_session_unregister('sendto');

      tep_session_unregister('billto');

      tep_session_unregister('shipping');

      tep_session_unregister('payment');

      tep_session_unregister('comments');

      if ( class_exists('order_total') === false ) {

        require_once(realpath(dirname(__FILE__) . '/../../../../classes/order_total.php'));

      }

      if ( is_callable(array('order_total','clear_posts')) === true ) {

        if ( is_object($GLOBALS['order_total_modules']) === false )
          $order_total_modules = new order_total;

        $order_total_modules->clear_posts();

      }
    }

  }//end class
?>