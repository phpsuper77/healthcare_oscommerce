<? 
	if ($_GET['purchaseContractId'] == '') $_GET['purchaseContractId'] = $_SESSION['amazon_purchaseContractId']; 
	
?>

<? if ($_GET['purchaseContractId'] != "" || $_SESSION['amazon_purchaseContractId'] != "") : ?>
	<style>
		.new-customer,
		.billing-address,
		.shipping,
		.payment-method,
		.contact-information {
			display:none;
		}
		
		.ctl_state, 
	</style>
	<script>
		var AmazonPaymentMethodSelected = false;
		$(document).ready(function() {
			$(".button-continue").click(function() {
				if (AmazonPaymentMethodSelected == false) {
					alert("Please select a payment method");
					return(false);
				} else {
					return(true);
				}				
			});
		});
		
		<? if ($_SESSION['amazon_purchaseContractId'] != ""): ?>
			AmazonPaymentMethodSelected = true;
		<? endif; ?>
	</script>
<? endif; ?>
	
	<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php if(defined('ONE_PAGE_SHOW_CART') && ONE_PAGE_SHOW_CART=='true') { ?>
<!-- show cart -->
      <!--tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr-->
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_CART_HEAD;?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td id="div_cart_contents">
<?php
   echo opc::cart();
?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><b><?php echo SUB_TITLE_SUB_TOTAL; ?> <span id="cart_subtotal"><?php echo $currencies->format($cart->show_total()); ?></span></b></td>
      </tr>
      <!-- /show cart -->
      <!--tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr-->
<?php } //ONE_PAGE_SHOW_CART ?>
<?php //---PayPal WPP Modification START ---//-- ?>
<?php if (!$ec_enabled || isset($_GET['ec_cancel']) || (!tep_session_is_registered('paypal_ec_payer_id') && !tep_session_is_registered('paypal_ec_payer_info'))) { ?>
<?php //---PayPal WPP Modification END ---//-- ?>
<?php
if (!tep_session_is_registered('customer_id'))
{
?>
      <tbody id="loginLink" style="display:none;">
      <tr>
        <td style="padding-bottom:10px;"><a href="javascript:displayLogin();" class="checkoutLogin"><?php echo TEXT_CLICK_FOR_LOGIN;?></a></td>
      </tr>
      </tbody>
      <tbody id="loginFrom">
      <tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<script language="javascript">
<!--
function displayLogin(){
  var cObj = document.getElementById("loginFrom");
  if ( cObj ) {  
    if (cObj.style.display == 'none'){ 
      cObj.style.display = ''; 
      cObj = document.getElementById("loginLink");
      cObj.style.display = 'none';
    }else{ 
      cObj.style.display = 'none';
    }
  }
}
<?php if ( $messageStack->size('login') == 0) {?>
var cObj = document.getElementById("loginFrom");
if (cObj) cObj.style.display = 'none';
cObj = document.getElementById("loginLink");
if (cObj) cObj.style.display = '';
<?php } ?>
-->
</script>
    <?php echo tep_draw_form('login', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'action=process', 'SSL')); ?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <!--tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_RETURNING_CUSTOMER; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr-->
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_RETURNING_CUSTOMER;
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
<?php
  if ($messageStack->size('login') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('login'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top">
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_RETURNING_CUSTOMER_LOGIN);
  new contentBoxHeading($contents);
?>   
          </td>
          </tr>  
          <tr>
            <td >
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                    <td class="main" width="100"><?php echo tep_draw_input_field('email_address'); ?></td>
                    <td class="main" align="right"><b><?php echo ENTRY_PASSWORD; ?></b></td>
                    <td class="main" width="100"><?php echo tep_draw_password_field('password'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" colspan="3"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></td>
                    <td align="right"><?php echo tep_template_image_submit('button_login.png', IMAGE_BUTTON_LOGIN, 'class="transpng"'); ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr></form>
<?php
  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
    </table></td></tr></tbody>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD

} // end if (!tep_session_is_registered('customer_id'))
?>
<?php 
//---PayPal WPP Modification START ---//
}
//---PayPal WPP Modification END ---//--
?>
</table>
<? if ($_GET['purchaseContractId'] != "") : ?>
	<!-- Start: Amazon Inline Checkout -->

	<? require_once(DIR_WS_TEMPLATES."/java/checkout/checkoutbyamazon.phtml"); ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<!--
	<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js'>	
	</script>
	-->
	<!-- For Switching to Sandbox, uncomment out the lines above and comment the lines below -->
	<!-- For Switching to Production, comment out the lines above and uncomment the lines below -->
	<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js'>
	</script>	
	
	<div id="AmazonAddressWidget"></div>
	<div id="AmazonWalletWidget"></div>
	
	<script type='text/javascript' >
	
		function payments() {
			new CBA.Widgets.WalletWidget({
				merchantId: 'AJO56SWKSCWXN',
				displayMode: 'Edit',
				design:{size: { width:'600', height:'228' } },
					onPaymentSelect: function(widget) {
						// Replace the following code with the action you want to perform 
						// after the payment is selected.
						// widget.getPurchaseContractId() returns the
						// PurchaseContractId.
						$("input[name='checkout_method']").val("amazon");
						$("input[name='email_address']").val("amazonuser@nowhere.com");
						$("input[name='telephone']").val("000");
						//document.getElementById("Continue").disabled = false; 
						$(".button-continue").show();
						AmazonPaymentMethodSelected = true;
					}
			}).render("AmazonWalletWidget");			
		}	
	
		new CBA.Widgets.AddressWidget({
			merchantId: 'AJO56SWKSCWXN',
			displayMode: 'Edit',
			design:{size: { width:'600', height:'228' } },
			onAddressSelect: function(widget) { 
				// Replace the following code with the action you want to perform 
				// after the address is selected.
				// widget.getPurchaseContractId() returns the
				// PurchaseContractId, which can be used to retrieve the address details
				// by calling CheckoutByAmazon service. 
				
				var checkoutAmazon = new CheckoutAmazonClass();	
				checkoutAmazon.get_address("<?=$_GET['purchaseContractId'];?>");				
				payments();
				//document.getElementById("Continue").disabled = false; 				
			}
		}).render("AmazonAddressWidget") ;
		
	</script>
<? endif; ?>


<?php //echo tep_draw_form('one_page_checkout', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'action=one_page_checkout', 'SSL'), 'post', 'onsubmit="return check_addresses(this);"'); 
//---PayPal WPP Modification START ---// 
if (tep_paypal_wpp_in_ec()) {
  echo tep_draw_form('one_page_checkout', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'action=ec_page_checkout', 'SSL'), 'post', 'onsubmit="return check_addresses2(this);"');
}else{
  echo tep_draw_form('one_page_checkout', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'action=one_page_checkout', 'SSL'), 'post', 'onsubmit="return check_addresses(this);"'); 
}
//---PayPal WPP Modification END ---//
?>

<input type="hidden" name="checkout_method" value="" />
<input type="hidden" name="amazon_purchaseContractId" value="<?=$_GET['purchaseContractId'];?>" />

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
<?php
//---PayPal WPP Modification START ---//
tep_paypal_wpp_checkout_payment_error_display();
//---PayPal WPP Modification END ---//
?>
<?php
if ($cart->count_contents() > 0)
{
?>
<!-- New Customer -->
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr class="new-customer">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo (!tep_session_is_registered('customer_id') ? HEADING_NEW_CUSTOMER : HEADING_ORDER_INFORMATION); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = (!tep_session_is_registered('customer_id') ? HEADING_NEW_CUSTOMER : HEADING_ORDER_INFORMATION);
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

  if ($messageStack->size('one_page_checkout') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('one_page_checkout'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
      </tr>
      <tr>
        <td><table class="checkoutBlock billing-address" border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top">
				<?php
				  $contents = array();
				  $contents[] = array('text' => '<a name="billing"></a>' . HEADING_BILLING_ADDRESS);
				  new contentBoxHeading($contents);
				?>             
            </td>
          </tr>  
          <tr>
            <td >
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
<?php //---PayPal WPP Modification START ---//-- ?>
<?php if ( !tep_paypal_wpp_in_ec() ) { ?>
<?php //---PayPal WPP Modification END ---//-- ?>
          <tr>
            <td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">

<?php
  if (tep_session_is_registered('customer_id'))
  {
    $addresses_array = array();
    $addresses_array[] = array('id' => '', 'text' => TEXT_NEW_ADDRESS);
    if ($shipping !== false){
      $js_body = "this.form.ship_as_bill.checked=false; if(this.value==''){if(this.form.company)this.form.company.value=''; if(this.form.firstname)this.form.firstname.value=''; if(this.form.lastname)this.form.lastname.value=''; if(this.form.street_address_line1)this.form.street_address_line1.value=''; if(this.form.street_address_line2)this.form.street_address_line2.value=''; if(this.form.postcode)this.form.postcode.value=''; if(this.form.city)this.form.city.value=''; if(this.form.state) bill_state_reset(''); if(this.form.zone_id)this.form.zone_id.value=''; if(this.form.country)this.form.country.value='';} ";
    }else{
      $js_body = "if(this.value==''){if(this.form.company)this.form.company.value=''; if(this.form.firstname)this.form.firstname.value=''; if(this.form.lastname)this.form.lastname.value=''; if(this.form.street_address_line1)this.form.street_address_line1.value=''; if(this.form.street_address_line2)this.form.street_address_line2.value=''; if(this.form.postcode)this.form.postcode.value=''; if(this.form.city)this.form.city.value=''; if(this.form.state) bill_state_reset(''); if(this.form.zone_id)this.form.zone_id.value=''; if(this.form.country)this.form.country.value='';} ";
    }

    $cust_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" .(int)$customer_id. "'");
    $cust_data = tep_db_fetch_array($cust_query);
    $addresses_query = tep_db_query("select address_book_id, entry_gender, entry_company, entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street, entry_suburb as suburb, entry_postcode as postcode, entry_city as city, entry_postcode as postcode, if(length(zone_name),zone_name,entry_state) as state, entry_zone_id as zone_id, entry_country_id as country_id, zone_name from " . TABLE_ADDRESS_BOOK . " left JOIN " . TABLE_ZONES . " z on z.zone_id = entry_zone_id and z.zone_country_id = entry_country_id where customers_id = '" . $customer_id . "'");
    while ($addresses = tep_db_fetch_array($addresses_query))
    {
      if ($billto == $addresses['address_book_id']){
        $p_gender = $addresses['entry_gender'];
      }
      $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));

      $js_body .= "if(this.value=='" . $addresses['address_book_id'] . "'){if(this.form.company)this.form.company.value='" . addslashes($addresses['entry_company']) . "'; if(this.form.firstname)this.form.firstname.value='" . addslashes($addresses['firstname']) . "'; if(this.form.lastname)this.form.lastname.value='" . addslashes($addresses['lastname']) . "'; if(this.form.street_address_line1)this.form.street_address_line1.value='" . addslashes($addresses['street']) . "'; if(this.form.street_address_line2)this.form.street_address_line2.value='" . addslashes($addresses['suburb']) . "'; if(this.form.postcode)this.form.postcode.value='" . addslashes($addresses['postcode']) . "'; if(this.form.city)this.form.city.value='" . addslashes($addresses['city']) . "'; if(this.form.state) bill_state_reset('" . addslashes($addresses['state']) . "'); if(this.form.country)this.form.country.value='" . $addresses['country_id'] . "';".((ACCOUNT_GENDER == 'true')?"if(this.form.gender && this.form.gender[0])this.form.gender[0].checked = " . (($addresses['entry_gender'] == 'm')?'true':'false') . ";if(this.form.gender && this.form.gender[1])this.form.gender[1].checked = " . (($addresses['entry_gender']=='m')?'false':'true').";":'')."} ";
    }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_ADDRESS_BOOK; ?></td>
                    <td class="main"><?php echo tep_draw_pull_down_menu('billto', $addresses_array, $billto, 'onchange="' . $js_body . ';getbillstate();"'); ?></td>
                  </tr>
<?php
  }
?>
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_GENDER; ?></td>
                    <td class="main"><?php echo tep_draw_radio_field('gender', 'm', ($p_gender == 'm')) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', ($p_gender == 'f')) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>

                  <tr>
                    <td class="main" width="25%"><?php echo ENTRY_FIRST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('firstname', $order->billing['firstname'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('lastname', $order->billing['lastname'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_DOB == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                <td class="main"><?php echo tep_draw_input_field('dob', (isset($HTTP_POST_VARS['dob'])?$HTTP_POST_VARS['dob']:(isset($cust_data['customers_dob'])?tep_date_short($cust_data['customers_dob']):$one_page_checkout_dob)), CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>                  
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('company', $order->billing['company'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('street_address_line1', $order->billing['street_address'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('street_address_line2', $order->billing['suburb'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('postcode', $order->billing['postcode'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_CITY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('city', $order->billing['city'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_STATE; ?></td>
                    <td class="main"><span id="ctl_state"><?php

    if ($entry_state_has_zones == true) {
      $zones_array = array();
      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . ($HTTP_POST_VARS['country'] ? $HTTP_POST_VARS['country'] : $order->billing['country']['id']) . "' order by zone_name");
      $zones_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
      while ($zones_values = tep_db_fetch_array($zones_query)) {
        $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
      }
      echo tep_draw_pull_down_menu('state', $zones_array, ($HTTP_POST_VARS['state'] ? $HTTP_POST_VARS['state'] : $order->billing['state']), CHECKOUT_CTLPARAM_COMMON.'');
    } else {
      echo tep_draw_input_field('state', $order->billing['state'], CHECKOUT_CTLPARAM_COMMON.'');
    }
    echo '</span>';
    //if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?>
                    </td>
                  </tr>
<?php
  }
?>
<?php
/*	
  $country_query = tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where countries_iso_code_3 = 'usa' order by countries_name");
  $country = tep_db_fetch_array($country_query);
  */
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
                    <td class="main">
						<?php echo tep_get_country_list('country', ($HTTP_POST_VARS['country'] ? $HTTP_POST_VARS['country'] : $order->billing['country']['id']),'onchange="getbillstate();"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
					</td>
                  </tr>
                </table></td>
              </tr>
<?php 
//---PayPal WPP Modification START ---//
  } else {
    tep_paypal_wpp_switch_checkout_method(FILENAME_CHECKOUT_PAYMENT);
  }
//---PayPal WPP Modification END ---//
?>
            </table></td>
          </tr>
<?php
//if ($shipping !== false){
if (($order->content_type != 'virtual') && ($order->content_type != 'virtual_weight') ) {
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
           <td class="main" valign="top">		   
<?php
  $contents = array();
  $contents[] = array('text' => '<a name="shipping"></a>' . HEADING_SHIPPING_ADDRESS);
  new contentBoxHeading($contents);
?>               
            </td>
          </tr>  
		  
		<table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox shipping">  
		<tr>
			<td class="main" colspan="2"><?php echo sprintf(TEXT_IF_SHIPPING_IS_SAME_AS_BILLING, '<input name="ship_as_bill" type="checkbox" onclick="copy_shipping(this);" align="absmiddle">'); ?></td>
		</tr>
		</table>
		  
          <tr>
            <td >
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="shipping-address contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
            <?php
            //---PayPal WPP Modification START ---//
            if ( !tep_paypal_wpp_in_ec() ) {
            //---PayPal WPP Modification END ---// 
            ?>
<?php
  if (tep_session_is_registered('customer_id'))
  {
    $addresses_array = array();
    $addresses_array[] = array('id' => '', 'text' => TEXT_NEW_ADDRESS);
    $js_body = "this.form.ship_as_bill.checked=false; if(this.value==''){if(this.form.ship_company)this.form.ship_company.value=''; if(this.form.ship_firstname)this.form.ship_firstname.value=''; if(this.form.ship_lastname)this.form.ship_lastname.value=''; if(this.form.ship_street_address_line1)this.form.ship_street_address_line1.value=''; if(this.form.ship_street_address_line2)this.form.ship_street_address_line2.value=''; if(this.form.ship_postcode)this.form.ship_postcode.value=''; if(this.form.ship_city)this.form.ship_city.value=''; if(this.form.ship_state) ship_state_reset(''); if(this.form.ship_zone_id)this.form.ship_zone_id.value=''; if(this.form.ship_country)this.form.ship_country.value='';} ";

    $addresses_query = tep_db_query("select address_book_id, entry_gender, entry_company, entry_firstname as firstname, entry_lastname as lastname, entry_street_address as street, entry_suburb as suburb, entry_postcode as postcode, entry_city as city, entry_postcode as postcode, if(length(zone_name),zone_name,entry_state) as state, entry_zone_id as zone_id, entry_country_id as country_id, zone_name from " . TABLE_ADDRESS_BOOK . " left JOIN " . TABLE_ZONES . " z on z.zone_id = entry_zone_id and z.zone_country_id = entry_country_id where customers_id = '" . $customer_id . "'");
    while ($addresses = tep_db_fetch_array($addresses_query))
    {
      if ($addresses['address_book_id'] == $sendto){
        $s_gender = $addresses['entry_gender'];
      }
      $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));

      $js_body .= "if(this.value=='" . addslashes($addresses['address_book_id']) . "'){if(this.form.ship_company)this.form.ship_company.value='" . addslashes($addresses['entry_company']) . "';if(this.form.ship_firstname)this.form.ship_firstname.value='" . addslashes($addresses['firstname']) . "'; if(this.form.ship_lastname)this.form.ship_lastname.value='" . addslashes($addresses['lastname']) . "'; if(this.form.ship_street_address_line1)this.form.ship_street_address_line1.value='" . addslashes($addresses['street']) . "'; if(this.form.ship_street_address_line2)this.form.ship_street_address_line2.value='" . addslashes($addresses['suburb']) . "'; if(this.form.ship_postcode)this.form.ship_postcode.value='" . addslashes($addresses['postcode']) . "'; if(this.form.ship_city)this.form.ship_city.value='" . addslashes($addresses['city']) . "'; if(this.form.ship_state) ship_state_reset('" . addslashes($addresses['state']) . "'); if(this.form.ship_zone_id)this.form.ship_zone_id.value='" . $addresses['zone_id'] . "'; if(this.form.ship_country)this.form.ship_country.value='" . $addresses['country_id'] . "';".((ACCOUNT_GENDER == 'true')?"if(this.form.shipping_gender[0])this.form.shipping_gender[0].checked = " . (($addresses['entry_gender'] == 'm')?'true':'false') . ";if(this.form.shipping_gender[1])this.form.shipping_gender[1].checked = " . (($addresses['entry_gender']=='m')?'false':'true')  . ";":'')."} ";
    }
?>  
                  <tr>
                    <td class="main"><?php echo ENTRY_ADDRESS_BOOK; ?></td>
                    <td class="main"><?php echo tep_draw_pull_down_menu('sendto', $addresses_array, $sendto, 'onchange="' . $js_body . ';DoFSCommand(2);"'); ?></td>
                  </tr>
<?php
  }
?>
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
              <tr>
                <td class="main"><?php echo ENTRY_GENDER; ?></td>
                <td class="main"><?php echo tep_draw_radio_field('shipping_gender', 'm', ($s_gender == 'm')) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('shipping_gender', 'f', ($s_gender == 'f')) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  }
?>                  <tr>
                    <td class="main" width="25%"><?php echo ENTRY_FIRST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_firstname', $order->delivery['firstname'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_lastname', $order->delivery['lastname'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_company', $order->delivery['company'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_street_address_line1', $order->delivery['street_address'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_street_address_line2', $order->delivery['suburb'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  }
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_postcode', (tep_session_is_registered('customer_id') ? $order->delivery['postcode'] : ''), CHECKOUT_CTLPARAM_COMMON.' onchange="DoFSCommand();"') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_CITY; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('ship_city', $order->delivery['city'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_STATE; ?></td>
                    <td class="main"><span id="ctl_ship_state"><?php
    if ($ship_state_has_zones == true) {
      $zones_array = array();
      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . ($HTTP_POST_VARS['ship_country'] ? $HTTP_POST_VARS['ship_country'] : $order->delivery['country']['id']) . "' order by zone_name");
      $zones_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
      while ($zones_values = tep_db_fetch_array($zones_query)) {
        $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
      }
      echo tep_draw_pull_down_menu('ship_state', $zones_array, ($HTTP_POST_VARS['ship_state'] ? $HTTP_POST_VARS['ship_state'] : $order->delivery['state']), CHECKOUT_CTLPARAM_COMMON.' onchange="DoFSCommand();"');
    } else {
      echo tep_draw_input_field('ship_state', $order->delivery['state'], CHECKOUT_CTLPARAM_COMMON.' onchnage="DoFSCommand();"');
    }
    echo '</span>';
    //if (tep_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;
?>
                    </td>
                  </tr>
<?php
  }
?>
<?php
/*	
  $country_query = tep_db_query("select countries_id from " . TABLE_COUNTRIES . " where countries_iso_code_3 = 'usa' order by countries_name");
  $country = tep_db_fetch_array($country_query);
  */
?>
                  <tr>
                    <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
                    <td class="main"><?php echo tep_get_country_list('ship_country', ($HTTP_POST_VARS['ship_country'] ? $HTTP_POST_VARS['ship_country'] : $order->delivery['country']['id']), 'onchange="DoFSCommand(2);"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></td>
                  </tr>
            <?php 
            //---PayPal WPP Modification START ---//
            }else{ 
              $address_label = tep_address_label($customer_id, $sendto, true, ' ', '<br>');
              // hide vars for coupon calc
              echo
              tep_draw_hidden_field('state',$order->billing['state']) .
              tep_draw_hidden_field('country',$order->billing['country']['id']).
              tep_draw_hidden_field('ship_postcode',$order->delivery['postcode']) .
              tep_draw_hidden_field('ship_state',$order->delivery['state']) .
              tep_draw_hidden_field('ship_country',$order->delivery['country']['id']);
            ?>
                  <tr>
                    <td class="main" valign="top"><?php /*if (allow_shipping_address_change()) echo '<a href="' . tep_href_link(FILENAME_EC_PROCESS, 'clearSess=1', 'SSL') . '">' . tep_template_image_button('button_change_address.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CHANGE_ADDRESS, 'class="transpng"') . '</a>';*/ ?>&nbsp;</td>
                    <td class="main"><?php echo $address_label; ?></td>
                  </tr>
            <?php
            }
            //---PayPal WPP Modification END ---//
            ?>
                </table></td>
              </tr>
            </table></td>
          </tr>
<?php 
//  if ($shipping !== false) {
  if (($order->content_type != 'virtual') && ($order->content_type != 'virtual_weight') ) {
?>          
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top">
<?php
  $contents = array();
  $contents[] = array('text' => '<a name="shipping_method"></a>' . TABLE_HEADING_SHIPPING_METHOD);
  new contentBoxHeading($contents);
?>               
            </td>
          </tr>  
          <tr>
            <td >
       <div id="shipping_div" name="shipping_div">
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">

<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } elseif ($free_shipping == false) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    }

    if ($free_shipping == true) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php echo $quotes[$i]['icon']; ?> <b><?php echo FREE_SHIPPING_TITLE; ?></b></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <tr id="defaultSelected_ship" class="moduleRowSelected" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, 0)">
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?> <b><?php echo $quotes[$i]['module']; ?></b></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        if (isset($quotes[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '                  <tr id="defaultSelected_ship" class="moduleRowSelected" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, ' . $radio_buttons . ')">' . "\n";
            } else {
              echo '                  <tr class="moduleRow" onmouseover="rowOverEffect_ship(this)" onmouseout="rowOutEffect_ship(this)" onclick="selectRowEffect_ship(this, ' . $radio_buttons . ')">' . "\n";
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <td class="main"><?php 
                    echo ( 
                      (is_numeric($quotes[$i]['methods'][$j]['cost']) && (float)$quotes[$i]['methods'][$j]['cost']==0)?
                        TEXT_SHIP_FREE_COST:
                        $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)))
                    ); 
                    ?></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
<?php
            } else {
?>
                    <td class="main" align="right" colspan="2"><?php 
                    echo ( 
                      (is_numeric($quotes[$i]['methods'][$j]['cost']) && (float)$quotes[$i]['methods'][$j]['cost']==0)?
                        TEXT_SHIP_FREE_COST:
                        $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']))
                    );
                    echo tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
<?php
            }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
            $radio_buttons++;
          }
        }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      }
    }
?>
                </table></td>
              </tr>
            </table>
            </div>
            </td>
          </tr>
<?php
  }
}
?>          
            <?php
            //---PayPal WPP Modification START ---//
            if ( !tep_paypal_wpp_in_ec() ) {
            //---PayPal WPP Modification END ---// 
            ?>

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr class="payment-method payment-method-<?=$_GET['payment_option'];?>">
            <td class="main" valign="top">			
<?php
  $contents = array();
  $contents[] = array('text' => '<a name="payment"></a>' . TABLE_HEADING_PAYMENT_METHOD);
  new contentBoxHeading($contents);
?>               
            </td>
          </tr>  
          <tr class="payment-method payment-method-<?=$_GET['payment_option'];?>">
            <td >
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td>
			<table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents payment-options payment-type-<?=$_GET['payment_option'];?>">

<?php
  $selection = $payment_modules->selection( true /*opc*/ );
//echo '<pre>'; var_dump($selection); echo '</pre>';
  if (sizeof($selection) > 1) {
?>
<!--<?php /* ?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php */ ?>-->
<?php
  } else {
?>
              <tr id="none_payment"<?php echo (sizeof($selection) > 1)?'':''; ?>>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
    $payment_show = true;
    if ( isset($selection[$i]['module_status']) ) $payment_show = $selection[$i]['module_status'];
    $opc_post_payment = (isset($GLOBALS[ $selection[$i]['id'] ]->opc_post_payment) && $GLOBALS[ $selection[$i]['id'] ]->opc_post_payment);
    if (!tep_not_null($payment) && $payment_show) { $payment=$selection[$i]['id'];} 
//echo '<pre>'; var_dump($opc_post_payment); echo '</pre>';
?>
             <tr id="<?php echo $selection[$i]['id']; ?>_payment"<?php echo ($payment_show)?'':' style="display:none;"'; ?>>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2" width="100%"><table id="checkout_payments" border="0" width="100%" cellspacing="0" cellpadding="2">
					<?php
						if ( $payment_show && ($selection[$i]['id'] == $payment) || ($n == 1) ) {
						  echo '<tr id="defaultSelected_paym" class="moduleRowSelected payment-option payment-option-'.$selection[$i]['id'].'" onmouseover="rowOverEffect_paym(this)" onmouseout="rowOutEffect_paym(this)" onclick="selectRowEffect_paym(this, ' . $radio_buttons . ')">' . "\n";
						} else {
						  echo '<tr class="moduleRow payment-option payment-option-'.$selection[$i]['id'].'" onmouseover="rowOverEffect_paym(this)" onmouseout="rowOutEffect_paym(this)" onclick="selectRowEffect_paym(this, ' . $radio_buttons . ')">' . "\n";
						}
					?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3" width=100%>
						<b><?php echo $selection[$i]['module']; ?></b>
					</td>
                    <td class="main" align="right">
						<? 
							if ($_GET['payment_option'] != "") {
								if ($_GET['payment_option'] == $selection[$i]['id']) $checked = "checked";
								else $checked = "";
							} else {
								if ($i == 2) $checked = "checked"; else $checked = "";
							}
						?>
						<input type="radio" name="payment" value="<?=$selection[$i]['id'];?>" <?=$checked;?> />						
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    } elseif  
      ( /* (!defined('ONE_PAGE_POST_PAYMENT') || 
        ( defined('ONE_PAGE_POST_PAYMENT') && ONE_PAGE_POST_PAYMENT=='false') ||
        ( defined('ONE_PAGE_POST_PAYMENT') && ONE_PAGE_POST_PAYMENT=='true' && !$opc_post_payment ) )  
      && isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) */
        !defined('ONE_PAGE_POST_PAYMENT') && isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])
       ) {
    // if defined('ONE_PAGE_POST_PAYMENT') all add info, like cc info entered on confirmation
    // modify payment need:
    // 1. Route all error to module get_error handler
    // 2. change pre_confirm for emulate process button
    // 3. if external modlue (form_action_url!=FILENAME_CHEKOUT_PROCESS) - modify post fields to gate compatibile 
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
<?php
      }
?>
                    </table></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
    $radio_buttons++;
  }
?>
              <tr id="none_payment"<?php echo (count($selection) > 0)?' style="display:none;"':''; ?>>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php //echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>

                </table></td>
              </tr>
            </table></td>
          </tr>
            <?php
            //---PayPal WPP Modification START ---//
            }else{ //!tep_paypal_wpp_in_ec() )
              echo tep_draw_hidden_field('payment');
            }
            //---PayPal WPP Modification END ---// 
            ?>

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr class="contact-information">
            <td class="main" valign="top">
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_CONTACT_INFO);
  new contentBoxHeading($contents);
?>              
            </td>
          </tr>  
          <tr class="contact-information">
            <td >
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                    <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('email_address', $order->customer['email_address'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('telephone', $order->customer['telephone'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></td>
                  </tr>
<?php if ( defined('ONE_PAGE_CREATE_ACCOUNT') && ONE_PAGE_CREATE_ACCOUNT!='false' && !tep_session_is_registered('customer_id')) { ?>
                  <tr><td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
                  <?php if (ONE_PAGE_CREATE_ACCOUNT=='onebuy') { ?>
                  <tr>
                    <td class="main"><?php echo ENTRY_CREATE_ACCOUNT; ?></td>
                    <td class="main"><?php echo tep_draw_checkbox_field('create_account', '1', isset($HTTP_POST_VARS['create_account']) ); ?><small><? echo TEXT_CREATE_ACCOUNT ?></small></td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td class="main" width="150"><?php echo ENTRY_PASSWORD; ?></td>
                    <td class="main" align="left">
	                        <?php echo tep_draw_password_field('password_new', $HTTP_POST_VARS['password_new'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . ((false && tep_not_null(ENTRY_PASSWORD_TEXT)) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?>
	                        <span class="inputRequirement">*</span>
                   </td>
                  </tr>
                  <tr>
                    <td class="main" width="150"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main" align="left">
	                        <?php echo tep_draw_password_field('confirmation_new', $HTTP_POST_VARS['confirmation_new'], CHECKOUT_CTLPARAM_COMMON) . '&nbsp;' . ((false && tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT)) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?>
	                    <span class="inputRequirement">*</span>
                   </td>
                  </tr>

<?php } ?>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
  echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top">
<?php
  $contents = array();
  $contents[] = array('text' => '<a name="comments"></a>' . TABLE_HEADING_COMMENTS);
  new contentBoxHeading($contents);
?>              
            </td>
          </tr>  
          <tr>
            <td height="100%">
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                    <td><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php
//-- TheMedia Begin check if display conditions on checkout page is true
  if (GERMAN_SITE == 'True') {
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
  $contents = array();
  $contents[] = array('text' => HEADING_CONDITIONS_INFORMATION);
  new contentBoxHeading($contents);
?>
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                <td colspan="3"><div style="width:100%;height:90;overflow:auto;background:white">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php')){
    require(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php');
  }else{
  	$agbtxt=strip_tags(TEXT_AGBS);
	  echo TEXT_AGBS;
  }
?>
                </div></td>
              </tr>
              <tr>
                <td class="main"><input type="checkbox" name="conditions" id="1"><?php echo TEXT_CONDITIONS_CONFIRM; ?></td>
                <td class="main" align="right">
<?php echo '<a href="' . tep_href_link(FILENAME_CONDITIONS_DOWNLOAD, '', 'SSL') . '" target="_blank"><b>' . TEXT_CONDITIONS_DOWNLOAD . '</b></a>'; ?>
&nbsp;
		</td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
//-- TheMedia End check if display conditions on checkout page is true
?>
<?php 
//------------------------------------------------------------------------
  if(defined('ONE_PAGE_SHOW_TOTALS') && ONE_PAGE_SHOW_TOTALS=='true') {
?>
      <tr id="ot_table" style="display:none;">
        <td>
<?php
  $contents = array();
  $contents[] = array('text' => TEXT_PURCHASE_TOTAL);
  new contentBoxHeading($contents);
?>
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
<?php
      if (defined('MODULE_ORDER_TOTAL_INSTALLED') && tep_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
        $sot_modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);
        reset($sot_modules);
        while (list(, $value) = each($sot_modules)) {
          $sot_class = substr($value, 0, strrpos($value, '.'));          
?>
                  <tr id="<?php echo $sot_class; ?>">
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" nowrap="nowrap" style="text-align:right;width:100%" id="<?php echo $sot_class; ?>_text"><?php echo $$sot_class->title; ?></td>
                    <td class="main" nowrap="nowrap" style="width:90px;font-weight:bold;" id="<?php echo $sot_class; ?>_cost" align="right"></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php          
        }
      }
?>

            </table></td>
          </tr>
        </table></td>
      </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php } //if(defined('ONE_PAGE_SHOW_TOTALS')) ?>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="left" class="main" width=100%', 'text' => '<b>'. TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE),
                               array('params' => 'align=right', 'text' => tep_template_image_submit('button_continue.png', IMAGE_BUTTON_CONTINUE, 'class="transpng button-continue"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>            


			<? if ($_GET['purchaseContractId'] != "") : ?>
				<input name="checkout-type" value="amazon" type="hidden" />
			<? endif; ?>
            
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD

} // end if ($cart->count_contents() > 0)
?>
    </table>
</form>
<script language="javascript"><!--
DoFSCommand();
<?php if ( false && ACCOUNT_STATE == 'true') { ?>
getbillstate();
<?php } ?>
-->
</script>


<? require_once(DIR_WS_TEMPLATES."/java/checkout/checkout.phtml"); ?>
<style>
	.payment-method-moneyorder { display:none; }
	.payment-method-sagepay_form { display:none; }
</style>
