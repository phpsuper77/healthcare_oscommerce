
<? if ($_GET['amazon_purchaseContractId'] != '') : ?>
	<style>
		.delivery-address,
		.billing-address {
			display:none;
		}
	</style>
<? endif; ?>

<?php
  if (!tep_session_is_registered('last_order_products')) tep_session_register('last_order_products');
  if (!tep_session_is_registered('last_order_total')) tep_session_register('last_order_total');
  $last_order_products = $order->products;
  $last_order_total = $order->info['total'];

  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
  if ($_GET['amazon_purchaseContractId'] != '') { 
		$form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }
  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post', (defined('ONE_PAGE_POST_PAYMENT') ? 'onsubmit="return send_form(this);"' : '')) . tep_draw_hidden_field('payment', $payment);
?>
	<input type="hidden" name="amazon_purchaseContractId" value="<?=$_GET['amazon_purchaseContractId']; ?>" />
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>        
        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
 
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
//debug($order);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => DELIVERY_ADDRESS_TITLE);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
<?php
  if ($sendto != false) {
?>
            <td width="30%" valign="top">
			<? if ($_GET['amazon_purchaseContractId'] != '') : ?>
				<!--
				<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js'>
				</script>
				-->
				<!-- For Switching to Sandbox, uncomment out the lines above and comment the lines below -->
				
				<!-- For Switching to Production, comment out the lines above and uncomment the lines below -->
				<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js'>
				</script>
				
				<div id="AmazonAddressWidget"></div>
				<script type='text/javascript' >
					new CBA.Widgets.AddressWidget({
						merchantId: 'AJO56SWKSCWXN',
						displayMode: 'Read',
						design:{size: { width:'200', height:'290' } }
					}).render("AmazonAddressWidget") ;
				</script>			
			<? endif; ?>
			
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="delivery-address">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '#shipping"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
              </tr>
<?php
    if ($order->info['shipping_method']) {
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '#shipping_method"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                  </tr>
<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
         '            <td class="main" valign="top">' . $order->products[$i]['name'];

    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br><nobr><small>&nbsp;<i> - ' . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($order->products[$i]['attributes'][$j]['option'])) . ($order->products[$i]['attributes'][$j]['value'] ? ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) : '') . '</i></small></nobr>';
      }
    }

    echo '</td>' . "\n";

    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'] * $order->products[$i]['qty'], $order->products[$i]['tax']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
        </table></td>
      </tr>

<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 

      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_BILLING_INFORMATION);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
            <tr>
              <td>
			  	<? if ($_GET['amazon_purchaseContractId'] != '') : ?>
					<!--
					<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js'>
					</script>-->
					
					<!-- For Switching to Sandbox, uncomment out the lines above and comment the lines below -->
					<!-- For Switching to Production, comment out the lines above and uncomment the lines below -->
					
					<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js'>
					</script>
					
					<div id="AmazonWalletWidget"></div>
					<script type='text/javascript' >
						new CBA.Widgets.WalletWidget({
							merchantId: 'AJO56SWKSCWXN',
							displayMode: 'Read',
							design:{size: { width:'400', height:'290' } }
							}).render("AmazonWalletWidget");
					</script>
				<? endif; ?>
                <table border="0" cellspacing="2" cellpadding="2" width="100%" height="100%" class="billing-address"> 
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '#billing"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '#payment"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['payment_method']; ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2" class="contentBoxContents" width="100%">
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
  }
?>
            <tr style="height:100%">
              <td colspan="2" style="height:100%"></td>
            </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
                    </td>
            </tr> </table>


<?php
// BOF: Lango modified for print order mod
if ($payment) $selection = $$payment->selection();
if ( !defined('ONE_PAGE_POST_PAYMENT') || (defined('ONE_PAGE_POST_PAYMENT') && !is_array($selection['fields'])) ) {
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $payment_info = $confirmation['title'];
      if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
// EOF: Lango modified for print order mod
?>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 

      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_PAYMENT_INFORMATION);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
}else{
  // post payment IF credit card enter info here
?>  
<?php
  if (CELLPADDING_SUB < 5){
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo $error['title']; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><?php echo $error['error']; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
  }
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_PAYMENT_INFORMATION);

  new contentBoxHeading($info_box_contents, false, false);
?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%">
<?php
      for ($j=0, $n2=sizeof($selection['fields']); $j<$n2; $j++) {
?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
<?php
      }
?>
              </table></td>
            </td>
          </tr>
         </table>
        </td>
      </tr>
<?php  
}  
?>
<?php
  if (CELLPADDING_SUB < 5){
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
  if (tep_not_null($order->info['comments'])) {
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '#comments"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>');

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
      <tr>
        <td>


<?php
/*
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','onSubmit="return ch_cond();"');
*/
  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }
?>
<?php
if ($_GET['amazon_purchaseContractId'] != '') { ?>
	<input type="hidden" name="amazon_purchaseContractId" value="<?=$_GET['amazon_purchaseContractId'];?>" />
	<input type="hidden" name="amazon_action" value="complete_order" />
	
	<input type="image" src="templates/Original/images/buttons/english/button_confirm_order.png" alt="Confirm Order" title=" Confirm Order " class="transpng" style="border:0px;width:103px;height:20px;">
<? } else {
	/*
	require('checkoutbyamazon/src/CheckoutByAmazon/Service/Samples/OrderWithContractCharges.php');  
	print "amazon";
	die;*/ 

	  $info_box_contents = array();
	  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
								   array('params' => 'align="left" class="main" width=100%', 'text' => '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE),
								   array('params' => 'align=right', 'text' => tep_template_image_submit('button_confirm_order.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONFIRM_ORDER, 'class="transpng"') . '' . "\n"),
								   array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
	  new buttonBox($info_box_contents);
	}
?>

        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
<?php //---PayPal WPP Modification START ---//-- ?>
<?php if ($show_payment_page || !$ec_enabled) { ?>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
<?php } ?>
<?php //---PayPal WPP Modification END ---//-- ?>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '#shipping" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
<?php //---PayPal WPP Modification START ---//-- ?>
<?php if ($show_payment_page || !$ec_enabled) { ?>
		<td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
<?php } ?>
<?php //---PayPal WPP Modification END ---//-- ?>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
</form>