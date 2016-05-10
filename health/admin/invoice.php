<?php
/*
  $Id: invoice.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  include(DIR_WS_CLASSES . 'order.php');
  define('RELATIVE_PATH', '../'.DIR_WS_CLASSES.'/html2pdf/');
  define('FPDF_FONTPATH', '../'.DIR_WS_CLASSES.'/html2pdf/font/');
  include_once('../'.DIR_WS_CLASSES . '/html2pdf/html2fpdf.php');
  class invoicePDF extends HTML2FPDF{
    function invoicePDF(){
      $this->HTML2FPDF();
      //$this->SetTopMargin(1); // header images
      $this->UseCSS(true);
      $this->SetFontSize(10);
    }
    function Footer(){}
    function getInvCSS(){
      return 
'<style>
.storeAddress{
  color:#555555;
  font-size:14px;
}
.productsTable{
  border:solid 1px;
}
.dataTableHeadingContent{
  font-size:11px;
  background: #CC00CC;
}
.dataTableContent{
  font-size:9px;
}
.normal{
  color:#000000;
  font-size:10px;
}
.small{
  font-size:8px;
}
</style>';
    }
  }

function getAddText(){
  global $languages_id;
  static $text = false;
  if ( $text===false ) {
    $info_id = 11;
    $sql = tep_db_query("select if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, if(length(i1.description), i1.description, i.description) as description, i.information_id from " . TABLE_INFORMATION . " i LEFT JOIN " . TABLE_INFORMATION . " i1 on i.information_id = i1.information_id  and i1.languages_id = '" . (int)$languages_id . "' and i1.affiliate_id = '" . 0 . "' where i.information_id = '" . (int)$info_id . "' and i.languages_id = '" . (int)$languages_id . "' and i.visible = 1 and i.affiliate_id = 0");
    if ( $row=tep_db_fetch_array($sql) ){
      $text = $row['description'];
    }else{
     $text='';
    }
  }
  return $text;
}

  $orders_print = array();
  if ( isset($HTTP_GET_VARS['oID']) && (int)$HTTP_GET_VARS['oID']>0 ) {
    $orders_print[] = (int)$HTTP_GET_VARS['oID'];
  }elseif( isset( $HTTP_POST_VARS['invoice_print'] ) && is_array($HTTP_POST_VARS['invoice_print']) ) {
    foreach( $HTTP_POST_VARS['invoice_print'] as $oID ) {
      if ( (int)$oID>0 ) $orders_print[] = (int)$oID; 
    }
  }
  if ( count($orders_print)==0 ) {
    echo '<script>
      window.close();
    </script>';
    die;
  }else{
    $pdf = new invoicePDF();
    $f_page = true;
    foreach( $orders_print as $oID ) {
      ob_start();
      invoiceOrder($oID);
      $all_invoices = ob_get_contents();
      ob_end_clean();
      if ( $f_page ) $all_invoices = $pdf->getInvCSS()."\n".$all_invoices;
      $pdf->AddPage();
      $pdf->WriteHTML($all_invoices);
      $f_page=false;
    }
    $pdf->Output();
  }


//----------- help func ----------

function invoiceOrder( $oID ) {
  global $currencies;
  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". (int)$oID . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];
  
  $order_comment = '';
  $orders_status_history = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$oID . "' order by orders_status_history_id limit 1");
  if ($orders_res = tep_db_fetch_array($orders_status_history)){
    $order_comment = $orders_res['comments'];
    if ( preg_match('/^Google Checkout/i', $order_comment ) ) $order_comment='';
  };
   
  $order = new order($oID);
?>
  <table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
    <tr> 
      <td class="storeAddress"><?php echo preg_replace('/\n/','',nl2br(STORE_NAME_ADDRESS)); ?></td>
      <td align="right"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . '' . 'logo_invoice.jpg'/*STORE_LOGO*/, STORE_NAME, '300', '122'); ?></td>
   </tr>
  </table>
  <hr>
  <?php //echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?>
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr> 
      <td width="50%"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
      <td width="50%"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
    </tr>
    <tr> 
      <td><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
      <td><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?></td>
    </tr>
    <tr><td colspan="2" height="5"> </td></tr> 
    <tr> 
      <td valign="top"><?php
      echo '<br>'.$order->customer['telephone'].'';
      echo '<br><a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; 
      ?></td>
      <td valign="top"><?php
      if( defined('INVOICE_RETURN_ADDRESS') && INVOICE_RETURN_ADDRESS!='' ) {
        echo '<br><b>'. TEXT_RETURN_ADDRESS.'</b> '. INVOICE_RETURN_ADDRESS.'';
      }
      if ( !empty($order_comment) ) {
        echo '<br><b>'. TEXT_ORDER_COMMENTS.'</b> '. $order_comment.'';
      } 
      ?></td>
    </tr>
  </table>
  <p>
    <b><?php echo LABEL_ORDER_NUMBER . $oID; ?></b>
  </p>
  <p>
    <b><?php echo ENTRY_PAYMENT_METHOD; ?></b> <?php echo $order->info['payment_method']; ?><br><b><?php echo LABEL_ORDER_DATE; ?></b> <?php echo tep_datetime_short($order->info['date_purchased']); ?>
  </p>

  <table border="1" width="100%" cellspacing="0" cellpadding="2" class="productsTable">
    <tr bgcolor="#D9EDF6">
      <td class="dataTableHeadingContent" align="center"><?php echo 'Qty';//TABLE_HEADING_PRODUCTS; ?></td> 
      <td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_PRODUCTS; ?></td>
      <td class="dataTableHeadingContent" width="85"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
      <td class="dataTableHeadingContent" align="right" width="60"><?php echo TABLE_HEADING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right" width="65"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right" width="65"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right" width="65"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
    </tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr>' . "\n" .
           '        <td class="dataTableContent" valign="top" width="40" align="center">' . $order->products[$i]['qty'] . '</td>' . "\n" .
           '        <td class="dataTableContent" valign="top" width="40%">' . $order->products[$i]['name'];

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><span class="small">&nbsp; - ' . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($order->products[$i]['attributes'][$j]['option'])) . ($order->products[$i]['attributes'][$j]['value'] ? ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) : '') . '</span>';
      }
    }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
  </table>
  
  <? // Start: Modify the totals displayed - Musaffar Patel - Original commented out below */ ?>
	<?
		$invoiceOrderTotals = $order->totals;	
		
		/* Suib total Excluding VAT */		
		if ($order->info['tax'] > 0) {
			$invoiceOrderTotals[0]['title'] =  "Sub-total excluding VAT";
			$invoiceOrderTotals[0]['value'] =  $invoiceOrderTotals[0]['value'] - $order->info['tax'];
			$invoiceOrderTotals[0]['text'] =  $currencies->format($invoiceOrderTotals[0]['value'], (USE_MARKET_PRICES == 'True'?false:true), $order->info['currency'], $order->info['currency_value']);	
			$invoiceOrderTotals[1]['title'] =  "VAT at 20%";	
		}
	?>
	
  <? // End: Modify the totals displayed - Musaffar Patel - Original commented out below */ ?>
  <table border="0" cellspacing="0" cellpadding="2" width="100%" align="right">
	<?php
		for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
		echo '          <tr>' . "\n" .
		'            <td align="right" class="normal" width="91%">' . $invoiceOrderTotals[$i]['title'] . '</td>' . "\n" .
		'            <td align="right" class="normal">' . $invoiceOrderTotals[$i]['text'] . '</td>' . "\n" .
		'          </tr>' . "\n";
	}
	?>
  </table>
  
  <!--
  Original OS Commerce Code Below - Musaffar Patel -->
  <table border="0" cellspacing="0" cellpadding="2" width="100%" align="right">
              <?php/*
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="normal" width="91%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="normal">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }*/
?>
<!--
  </table>
  -->
<?php
  $text = getAddText();
  if (!empty($text)){
    echo '<p class="normal">'.$text.'</p>';
  }
}


require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
