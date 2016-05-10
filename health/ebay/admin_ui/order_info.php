<?php

function ebay_order_ico( $state, $tri=false ){
  if ( $tri ) {
    if ( (int)$state==0 ) {
      return tep_image(DIR_WS_ICONS . 'cross.gif', '');
    }else{
      return tep_image(DIR_WS_ICONS . ((int)$state == 1 ? 'unconfirmed':'tick' ) . '.gif', '');
    }
  }else{
    return tep_image(DIR_WS_ICONS . ((int)$state == 0 ? 'cross':'tick' ) . '.gif', '');
  }
}

function ebay_order_info( $orderID ){
  $eo_r = tep_db_query("select * from ".TABLE_EBAY_ORDERS." where orders_id='".$orderID."'");
  if( $eo = tep_db_fetch_array($eo_r) ) {
    $view_buyer = '&nbsp;';
    if ( class_exists('ebay_core') ) {
      $core = ebay_core::get();
      $reg = $core->get_registry();
      $reg->setConnectorId($eo['connector_id']);
      $url = $reg->getValue('ViewUserURL');
      if ( !empty($url) ) {
        $view_buyer = '<a href="'.$url.urlencode($eo['buyer_userID']).'" target="_blank">'.tep_image(DIR_WS_ICONS . 'external.png', '').'</a>';
      }
    }
   
?>
    <table style="border: 2px solid #EAEAEA" border="0" cellspacing="1" cellpadding="2">
      <tr class="dataTableRow">
        <td class="dataTableContent" style="width:250px">Ebay OrderID</td>
        <td class="dataTableContent"><?php echo $eo['ebay_orders_id'] ?></td>
        <td class="dataTableContent" align="center" style="width:50px">&nbsp;</td>
      </tr>

      <tr class="dataTableRow">
        <td class="dataTableContent">Checkout Status</td>
        <td class="dataTableContent"><?php echo $eo['checkout_status']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Complete Status</td>
        <td class="dataTableContent"><?php echo $eo['complete_status']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">eBay Payment Status</td>
        <td class="dataTableContent"><?php echo $eo['eBay_payment_status']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Paypal Transaction</td>
        <td class="dataTableContent"><?php echo $eo['paypal_txn_id']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Payment Hold Status</td>
        <td class="dataTableContent"><?php echo $eo['payment_hold_status']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Last Time Modified</td>
        <td class="dataTableContent"><?php echo tep_datetime_short($eo['last_time_modified']); ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Ebay User</td>
        <td class="dataTableContent"><?php echo $eo['buyer_userID'].' (' .$eo['buyer_feedback_score'].')'; ?></td>
        <td class="dataTableContent" align="center"><?php echo $view_buyer; ?></td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Buyer Status</td>
        <td class="dataTableContent"><?php echo $eo['buyer_status']; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Buyer Registration Date</td>
        <td class="dataTableContent"><?php echo tep_date_short($eo['buyer_registration_date'].' 00:00:00'); ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Order Created</td>
        <td class="dataTableContent"><?php echo !empty($eo['created_date'])?tep_datetime_short($eo['created_date']):' - '; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Order Downloaded</td>
        <td class="dataTableContent"><?php echo !empty($eo['download_date'])?tep_datetime_short($eo['download_date']):' - '; ?></td>
        <td class="dataTableContent">&nbsp;</td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Payment Date</td>
        <td class="dataTableContent"><?php echo !empty($eo['payment_date'])?tep_datetime_short($eo['payment_date']):' - '; ?></td>
        <td class="dataTableContent" align="center"><?php echo ebay_order_ico($eo['payment_received'], true); ?></td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Feedback Date</td>
        <td class="dataTableContent"><?php echo !empty($eo['feedback_date'])?tep_datetime_short($eo['feedback_date']):' - '; ?></td>
        <td class="dataTableContent" align="center"><?php echo ebay_order_ico($eo['feedback_sended'], true); ?></td>
      </tr>
      <tr class="dataTableRow">
        <td class="dataTableContent">Ship Date</td>
        <td class="dataTableContent"><?php echo !empty($eo['ship_date'])?tep_datetime_short($eo['ship_date']):' - '; ?></td>
        <td class="dataTableContent" align="center"><?php echo ebay_order_ico($eo['ship_sended'], true); ?></td>
      </tr>
    </table>
<?php
  }
}

function ebay_paypal_trans( $orderID ) {
  $ret = '';
  $data_r = tep_db_query("select * from ".'ebay_paypal'/*TABLE_EBAY_PAYPAL*/." where orders_id='".$orderID."' order by payment_date");
  while( $data = tep_db_fetch_array($data_r) ){
    $ret .= '<table cellspacing="1" cellpadding="1">';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>Payer</b></td><td class="dataTableContent" colspan="3">'.$data['payer'].'</td>';
    $ret .= '</tr>';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>Payer Status</b></td><td class="dataTableContent">'.$data['payer_status'].'</td>';
    $ret .=  '<td class="dataTableContent"><b>Payment Status</b></td><td class="dataTableContent">'.$data['payment_status'].'</td>';
    $ret .= '</tr>';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>transactionID</b></td><td class="dataTableContent">'.$data['transactionID'].'</td>';
    $ret .=  '<td class="dataTableContent"><b>Payment Date</b></td><td class="dataTableContent">'.$data['payment_date'].'</td>';
    $ret .= '</tr>';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>Gross Amount</b></td><td class="dataTableContent">'.$data['gross_amount'].' '.$data['gross_amount_currency'].'</td>';
    $ret .=  '<td class="dataTableContent"><b>Fee Amount</b></td><td class="dataTableContent">'.$data['fee_amount'].' '.$data['fee_amount_currency'].'</td>';
    $ret .= '</tr>';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>Transaction type</b></td><td class="dataTableContent">'.$data['transaction_type'].'</td>';
    $ret .=  '<td class="dataTableContent"><b>Payment type</b></td><td class="dataTableContent">'.$data['payment_type'].'</td>';
    $ret .= '</tr>';
    $ret .= '<tr>';
    $ret .=  '<td class="dataTableContent"><b>Pending Reason</b></td><td class="dataTableContent">'.$data['pending_reason'].'</td>';
    $ret .=  '<td class="dataTableContent"><b>Reason Code</b></td><td class="dataTableContent">'.$data['reason_code'].'</td>';
    $ret .= '</tr>';
    if ( !empty($data['item_list']) ) {
      $ret .= '<tr>';
      $ret .=  '<td class="dataTableContent" colspan="4">'.$data['item_list'].'</td>';
      $ret .= '</tr>';
    }
    $ret .= '</table>';
  }
  if ( !empty($ret) ) $ret = '<div style="border:1px solid #AFCEEB">'.$ret.'</div>';
  return $ret;
}

?>
