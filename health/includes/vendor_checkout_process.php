<?php
/*
  $Id: vendor_checkout_process.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

// fetch the net total of an order
  $vendor_total = array();
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $check = tep_db_query("select vendor_id from " . TABLE_PRODUCTS . " where products_id = '" . $order->products[$i]['id'] . "'");
    $data = tep_db_fetch_array($check);
    if ($data['vendor_id'] != '0'){
      if (isset($vendor_total[$data['vendor_id']])){
        $vendor_total[$data['vendor_id']] += $order->products[$i]['final_price'] * $order->products[$i]['qty'];
      }else{
        $vendor_total[$data['vendor_id']] = $order->products[$i]['final_price'] * $order->products[$i]['qty'];
      }
    }
  }

  foreach ($vendor_total as $key => $value){
    $vendor_percent = 0;
    if ($key > 0){
      if (VENDOR_INDIVIDUAL_PERCENTAGE == 'true') {
        $vendor_commission_query = tep_db_query ("select vendor_commission_percent from " . TABLE_VENDOR . " where vendor_id = '" . (int)$key . "'");
        $vendor_commission = tep_db_fetch_array($vendor_commission_query);
        $vendor_percent = $vendor_commission['vendor_commission_percent'];
        if ($vendor_percent == 0){
          $vendor_percent = VENDOR_PERCENT;
        }
      }else{
        $vendor_percent = VENDOR_PERCENT;
      }
      $vendor_payment = tep_round(($value * $vendor_percent / 100), 2);
      $sql_data_array = array('vendor_id' => $key,
                              'vendor_date' => 'now()',
                              'vendor_value' => $value,
                              'vendor_payment' => $vendor_payment,
                              'vendor_orders_id' =>$insert_id,
                              'vendor_percent' => $vendor_percent
                              );
      tep_db_perform(TABLE_VENDOR_SALES, $sql_data_array);
    }
  }
// Check for individual commission
?>