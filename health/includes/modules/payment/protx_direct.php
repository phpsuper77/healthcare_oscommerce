<?php
//
// +----------------------------------------------------------------------+
// + osCommerce, Open Source E-Commerce Solutions                         +
// +----------------------------------------------------------------------+
// | Copyright (c) 2006-2009 Tom Hodges-Hoyland                           |
// |                                                                      |
// | Portions Copyright (c) 2003 - 2007 osCommerce                        |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.gnu.org/copyleft/gpl.html.                                |
// +----------------------------------------------------------------------+
// | includes/modules/payment/protx_direct.php                            |
// | Released under GPL                                                   |
// | v2.22-v5.1 by Thomas Hodges-Hoyland (perfectpassion / tomh):         |
// |                             tom.hodges-hoyland@oscommerceproject.org |
// | Vladislav B. Malyshev vmalyshev@holbi.co.uk						  |
// | Alexander Ryabov artvizi@gmail.com									  |
// +----------------------------------------------------------------------+

class protx_direct {
  var $code = 'protx_direct';
  var $title = MODULE_PAYMENT_PROTX_DIRECT_TEXT_TITLE;
  var $public_title = MODULE_PAYMENT_PROTX_DIRECT_TEXT_PUBLIC_TITLE;
  var $description = MODULE_PAYMENT_PROTX_DIRECT_TEXT_DESCRIPTION;
  var $sort_order = MODULE_PAYMENT_PROTX_DIRECT_SORT_ORDER;
  var $protocol = '2.23';
  var $enabled;
  var $order_status;
  var $url;
  var $threeDurl;
  var $protx_id;
  var $form_action_url;
  var $supported_cc;
  var $cc_card_number;
        function protx_direct() {
                global $order;

                $this->enabled = ((MODULE_PAYMENT_PROTX_DIRECT_STATUS == 'True') ? true : false);

                if ((int)MODULE_PAYMENT_PROTX_DIRECT_ORDER_STATUS_ID > 0) {
                        $this->order_status = MODULE_PAYMENT_PROTX_DIRECT_ORDER_STATUS_ID;
                }


if (MODULE_PAYMENT_PROTX_DIRECT_USE_AMEX != 'False') { $this->supported_cc['AMEX'] = 'American Express'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_DC != 'False') { $this->supported_cc['DC'] = 'Diners Club'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_UKE != 'False') { $this->supported_cc['UKE'] = 'Electron'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_JCB != 'False') { $this->supported_cc['JCB'] = 'JCB'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_MC != 'False') { $this->supported_cc['MC'] = 'Mastercard'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_SOLO != 'False') { $this->supported_cc['SOLO'] = 'UK Solo'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_MAESTRO != 'False') { $this->supported_cc['MAESTRO'] = 'Maestro'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_VISA != 'False') { $this->supported_cc['VISA'] = 'Visa'; }
if (MODULE_PAYMENT_PROTX_DIRECT_USE_DELTA != 'False') { $this->supported_cc['DELTA'] = 'Visa'; }

    if (is_object($order)) $this->update_status(); // a zachem?

	  if (MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE == 'Test')
	  {
      $this->url = 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp';
      $this->threeDurl = 'https://test.sagepay.com/gateway/service/direct3dcallback.vsp';
    } elseif (MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE == 'Server IP Test') {
      $this->url = 'https://test.sagepay.com/showpost/showpost.asp';
      $this->threeDurl = 'https://test.sagepay.com/showpost/showpost.asp';
    } elseif (MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE == 'Simulator') {
	    $this->url = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp';
	    $this->threeDurl = 'https://test.sagepay.com/Simulator/VSPDirectCallback.asp';
      } else {
	    $this->url = 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp';
	    $this->threeDurl = 'https://live.sagepay.com/gateway/service/direct3dcallback.vsp';
      }

	  $this->form_action_url = tep_href_link(FILENAME_PROTX_PROCESS, 'nojs&action=process', 'SSL');
        }

        function update_status() {
                global $order;

      // Check if a zone is specified for Protx Direct, if so if not current zone then disable module
                if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PROTX_DIRECT_ZONE > 0) ) {
                        $check_flag = false;
                        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" .  MODULE_PAYMENT_PROTX_DIRECT_ZONE . "' and zone_country_id = '" .  $order->billing['country']['id'] . "' order by zone_id");
                        while ($check = tep_db_fetch_array($check_query)) {
                                if ($check['zone_id'] < 1) {
                                        $check_flag = true;
                                        break;
                                } elseif ($check['zone_id'] == $order->billing['zone_id']) {
                                        $check_flag = true;
                                        break;
                                }
                        }

                        if ($check_flag == false) {
                                $this->enabled = false;
                        }
                }
        }

        function javascript_validation() {
	
                $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
     '    var protx_direct_cc_owner_js = document.checkout_payment.protx_direct_cc_owner.value;' . "\n" .
                '  var protx_direct_cc_number =
document.checkout_payment.protx_direct_cc_number.value;' . "\n" .
    '    if (protx_direct_cc_owner_js == "" || protx_direct_cc_owner_js.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
    '      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
    '      error = 1;' . "\n" .
    '    }' . "\n" .
'  if (protx_direct_cc_number == "" ||
protx_direct_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' .
"\n" .
'    error_message = error_message + "' .
MODULE_PAYMENT_PROTX_DIRECT_TEXT_JS_CC_NUMBER . '";' . "\n" .
'    error = 1;' . "\n" .
'  }' . "\n" .
'}' . "\n";

return $js;
        }

        function selection() {
                global $order;

// Expiry date array for drop down list
      $expires_month[] = array('id' => '', 'text' => '');  // Add a blank month or year to expiry & start to prompt people to select
                for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
                }

                $today = getdate();
      $expires_year[] = array('id' => '', 'text' => '');
                for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%y',mktime(0,0,0,1,1,$i)));
                }

// Start Date Arrays for drop down list
      $start_month[] = array('id' => '', 'text' => '');
      for ($i=1; $i < 13; $i++) {
        $start_month[] = array('id' => sprintf('%02d', $i), 'text' => sprintf('%02d', $i));
      }

      $today = getdate();
      $start_year[] = array('id' => '', 'text' => '');
      for ($i=$today['year']-4; $i <= $today['year']; $i++) {
        $start_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%y',mktime(0,0,0,1,1,$i)));
      }

// create card detail entry form
    
                $selection = array('id' => $this->code,
                          'module' => tep_image(DIR_WS_IMAGES . 'icons/small_cc.gif',MODULE_PAYMENT_PROTX_DIRECT_TEXT_TITLE,'','','style="vertical-align:middle;"').' '.MODULE_PAYMENT_PROTX_DIRECT_TEXT_TITLE,
                          'fields' => array(array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_OWNER,
                                                   'field' => tep_draw_input_field('protx_direct_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'], 'maxlength="50"')),
                                             array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_NUMBER,
                                                   'field' => tep_draw_input_field('protx_direct_cc_number','',' autocomplete="off" maxlength="20"')),
                                             array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CVV,
                                                   'field' => tep_draw_input_field('protx_direct_cc_cvv_nh-dns','','size="4" maxlength="4" autocomplete="off"') . '&nbsp;' . TEXT_CVV_WHAT_THIS),
                                             array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_EXPIRES,
                                                   'field' => tep_draw_pull_down_menu('protx_direct_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('protx_direct_cc_expires_year', $expires_year)),
                                             array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_START_DATE,
                                                   'field' => tep_draw_pull_down_menu('protx_direct_cc_start_month', $start_month, '', 'class="protx_hidden"') . '&nbsp;' . tep_draw_pull_down_menu('protx_direct_cc_start_year', $start_year, '', 'class="protx_hidden"')),
                                             array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_ISSUE_NUMBER,
                                                   'field' => tep_draw_input_field('protx_direct_cc_issue', '', 'size="2" maxlength="2" autocomplete="off" class="protx_hidden"'))));

	return $selection;

    }

        function pre_confirmation_check() {

                global $HTTP_POST_VARS;

                include(DIR_WS_CLASSES . 'protx_cc_validation.php');

                $protx_cc_validation = new protx_cc_validation();
                $result = $protx_cc_validation->validate($HTTP_POST_VARS['protx_direct_cc_number'], $HTTP_POST_VARS['protx_direct_cc_expires_month'], $HTTP_POST_VARS['protx_direct_cc_expires_year'], $this->supported_cc);
                $error = '';
                
                switch ($result) {
                        case -1:
                                $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($protx_cc_validation->cc_number, 0, 4));
                                break;
                        case -2:
                        case -3:
                        case -4:
                                $error = TEXT_CCVAL_ERROR_INVALID_DATE;
                                break;
                        case false:
                                $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
                                break;
                }

                $this->cc_card_type = $protx_cc_validation->cc_type;
                $this->cc_card_number = $protx_cc_validation->cc_number;
                $this->cc_expiry_month = $protx_cc_validation->cc_expiry_month;
                $this->cc_expiry_year = $protx_cc_validation->cc_expiry_year;

			    $this->cc_cvv = $HTTP_POST_VARS['protx_direct_cc_cvv_nh-dns'];
			    $this->protx_direct_cc_start_month = $HTTP_POST_VARS['protx_direct_cc_start_month'];
    $this->protx_direct_cc_start_year = $HTTP_POST_VARS['protx_direct_cc_start_year'];
    $this->protx_direct_cc_issue = $HTTP_POST_VARS['protx_direct_cc_issue'];

                if ( ($result == false) || ($result < 1) ) {
                        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&protx_direct_cc_owner=' . urlencode($HTTP_POST_VARS['protx_direct_cc_owner']) . '&protx_direct_cc_expires_month=' . $this->cc_expiry_month . '&protx_direct_cc_expires_year=' . $this->cc_expiry_year . '&protx_direct_cc_star_month=' . $this->protx_direct_cc_start_month . '&protx_direct_cc_start_year=' . $this->protx_direct_cc_start_year . '&protx_direct_cc_issue=' . $this->protx_direct_cc_issue;
                        tep_redirect(tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), $payment_error_return, 'SSL', true, false));
        }

  if ( defined('ONE_PAGE_POST_PAYMENT') ) {
    foreach ($this->supported_cc as $key => $value) { 
      if($value==$this->cc_card_type) $this->cc_card_type_code = $key;
        }
    $_repost = array(
      'protx_direct_cc_type' => $this->cc_card_type,
      'protx_direct_cc_owner' => $HTTP_POST_VARS['protx_direct_cc_owner'],
      'protx_direct_cc_number' => $this->cc_card_number,
      'cc_card_type_code' => $this->cc_card_type_code,
	    'protx_direct_cc_expires_month' => $this->cc_expiry_month,
	    'protx_direct_cc_expires_year', substr($this->cc_expiry_year, 2, 2),
	  );
	  if ($this->cc_cvv) {
	    $_repost['protx_direct_cc_cvv_nh-dns'] = $this->cc_cvv;
	  }
	  if ($this->protx_direct_cc_start_month && $this->protx_direct_cc_start_year) {
	    $_repost['protx_direct_cc_start_month'] = $this->protx_direct_cc_start_month;
	    $_repost['protx_direct_cc_start_year'] = $this->protx_direct_cc_start_year;
	  }
	  if ($this->protx_direct_cc_issue) {
	    $_repost['protx_direct_cc_issue'] = $this->protx_direct_cc_issue;
	  }
	  foreach( $_repost as $k=>$v ) {
	    $_POST[$k] = $v;
	    $HTTP_POST_VARS[$k] = $v;
    }
  }


}

        function confirmation() {
      global $order, $HTTP_POST_VARS;

    $confirmation = array('id' => $this->code,
                          'module' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_TITLE,
                          'fields' => array(array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_TYPE,
                                                  'field' => $this->cc_card_type),
                array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_NUMBER,
                'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_EXPIRES,
                                                   'field' => $HTTP_POST_VARS['protx_direct_cc_expires_month'] . '&nbsp;20' . $HTTP_POST_VARS['protx_direct_cc_expires_year']),
             ));


                return $confirmation;
        }

        function process_button() {
	global $HTTP_POST_VARS;
  if ( defined('ONE_PAGE_POST_PAYMENT') ) { return ''; }

	foreach ($this->supported_cc as $key => $value) { 
		if($value==$this->cc_card_type) $this->cc_card_type_code = $key;
	}
	
	$process_button_string =  
	tep_draw_hidden_field('protx_direct_cc_type', $this->cc_card_type) .
	tep_draw_hidden_field('protx_direct_cc_owner', $HTTP_POST_VARS['protx_direct_cc_owner']) .
	tep_draw_hidden_field('protx_direct_cc_number', $this->cc_card_number) .
	tep_draw_hidden_field('cc_card_type_code', $this->cc_card_type_code) .
	($this->cc_cvv?
	tep_draw_hidden_field('protx_direct_cc_cvv_nh-dns', $this->cc_cvv):''
	) .
	tep_draw_hidden_field('protx_direct_cc_expires_month', $this->cc_expiry_month) .
	tep_draw_hidden_field('protx_direct_cc_expires_year', substr($this->cc_expiry_year, 2, 2)) .

	($this->protx_direct_cc_start_month && $this->protx_direct_cc_start_year?
	tep_draw_hidden_field('protx_direct_cc_start_month', $this->protx_direct_cc_start_month) .
	tep_draw_hidden_field('protx_direct_cc_start_year', $this->protx_direct_cc_start_year) :''
	) .
	($this->protx_direct_cc_issue?
	tep_draw_hidden_field('protx_direct_cc_issue', $this->protx_direct_cc_issue):''
	);

                return $process_button_string;

    }

function before_process()
{
  // Payment should be complete by this stage - if not abort order
  if (tep_session_is_registered('protx_id') && $_SESSION['protx_id'] > 0)
  {
  	$this->protx_id = (int)$_SESSION['protx_id'];
  	tep_session_unregister('protx_id');
                                }
  else
  {
    tep_redirect(str_replace('&amp','&', tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), 'payment_error=protx_direct&error='.urlencode('Sorry, your order could not be processed as no payment transaction was found (ID Missing). Please try again or contact the store owner.'), 'SSL')));
                                }
  $trans_query = tep_db_query("SELECT status FROM ".TABLE_PROTX_DIRECT." WHERE id='".$this->protx_id."' AND (status='OK' OR status='REGISTERED' OR status='AUTHENTICATED')");
  if (tep_db_num_rows($trans_query) == 0)
  {
    tep_redirect(str_replace('&amp','&', tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), 'payment_error=protx_direct&error='.urlencode('Sorry, your order could not be processed as no payment transaction was found (No record). Please try again or contact the store owner.'), 'SSL')));
                        }
                }

function start_transaction() {
	global $order, $cart, $currency, $currencies, $customer_id;

  $last_order_id_query = tep_db_query("SHOW TABLE STATUS from `" . DB_DATABASE . "` like '" . TABLE_ORDERS . "'");
  $last_order_id = tep_db_fetch_array($last_order_id_query);
  $new_order_id = $last_order_id['Auto_increment'];

  $order_description = 'Order Number: ' . $new_order_id;

// DATA PREPARATION SECTION
  unset($submit_data);  // Cleans out any previous data stored in the variable

  // Populate an array that contains all of the data to be sent to Protx

// Cart details (adapted from Mike Jackson's code for Protx Form)
  $basketlist='';
  if (MODULE_PAYMENT_PROTX_DIRECT_SHOPCART == 'True')
  {
    if (tep_not_null($order->info['shipping_cost']))
    {
      $Shipping = $order->info['shipping_cost'];
}
    else
    {
      $Shipping='---';
  }
    $products = $cart->get_products();
    $No_lines = sizeof($products);
    $No_lines = $No_lines + 1;  // Don't forget the shipping as an item!
    $cart_string = $No_lines;
    for ($i=0, $n=sizeof($products); $i<$n; $i++)
    {
    	$Description = $products[$i]['name'];
    	$Description  = str_replace(":", "", $Description); // Make sure that there are no colons (:) since we are producing a colon delimited list
    	$Qty = $products[$i]['quantity'];
    	$Price = number_format($products[$i]['price'] + $cart->attributes_price($products[$i]['id']), 2, '.', '');
    	$Tax = number_format($products[$i]['price'] / 100 *  tep_get_tax_rate($products[$i]['tax_class_id']), 2, '.', '');
    	$Tax = number_format($Tax, 2, '.', '');
    	$final_price = $Price + $Tax;
    	$final_price = number_format($final_price, 2, '.', '');
    	$Line_Total = $Qty * $final_price;
    	$Line_Total = number_format($Line_Total, 2, '.', '');
    	$cart_string .= ":".$Description.":".$Qty.":".$Price.":".$Tax.":".$final_price.":".$Line_Total;
  }
    $cart_string .= ":Shipping:1:".$Shipping.":----:".$Shipping.":".$Shipping;

    // Remove any newlines and carrige returns - PROTX protocol does not allow these in the shopping basket.
    $cart_string = str_replace("\n", "", $cart_string);
    $cart_string = str_replace("\r", "", $cart_string);
    $cart_string = str_replace ("&", "and", $cart_string);
    $basketlist = substr($cart_string, 0, 7500); // just in case someone orders a lot!
  }

  if (constant('MODULE_PAYMENT_PROTX_DIRECT_USE_'.$_POST['cc_card_type_code']) == 'True - with 3D-Secure')
  {
    $use_3D_Secure = 0;
  }
  else
  {
    $use_3D_Secure = 2;
  }

  // if no postcode use a dummy (i.e. some international orders)
	if (!tep_not_null($order->billing['postcode'])) {
		$bil_postcode = '0000';
  } else {
		$bil_postcode = $order->billing['postcode'];
  }
	if (!tep_not_null($order->delivery['postcode'])) {
		$del_postcode = '0000';
  } else {
		$del_postcode = $order->delivery['postcode'];
  }

	$del_state = '';
	$bil_state = '';
	if (ACCOUNT_STATE == 'true' && $order->delivery['country']['iso_code_2'] == 'US' ) {
	  $state_code_query = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM ".TABLE_ZONES." WHERE zone_id=".(int)$order->delivery['zone_id']));
	  $del_state = $state_code_query['zone_code'];
  }
	if (ACCOUNT_STATE == 'true' && $order->billing['country']['iso_code_2'] == 'US' ) {
	  $state_code_query = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM ".TABLE_ZONES." WHERE zone_id=".(int)$order->billing['zone_id']));
	  $bil_state = $state_code_query['zone_code'];
}

  // create a random id for the transaction
  $uid = tep_create_random_value(32, 'digits');
  $VendorTxCode = substr($new_order_id . '-' . $uid, 0, 40);
  $submit_data = array(
      'VPSProtocol' => $this->protocol,
      'TxType' => MODULE_PAYMENT_PROTX_DIRECT_AUTHORIZATION_TYPE, // Transaction Type
      'Vendor' => MODULE_PAYMENT_PROTX_DIRECT_VENDOR_NAME, // Vendor Login ID
      'VendorTxCode' => $VendorTxCode,  // Unique Transaction ID
      'Amount' => $this->format_raw($order->info['total']),
      'Currency' => $order->info['currency'],
      'Description' => $order_description,
      'CardHolder' => substr($_POST['protx_direct_cc_owner'],0, 50),
      'CardNumber' => $_POST['protx_direct_cc_number'],
      'StartDate' => (tep_not_null($_POST['protx_direct_cc_start_month']) ? $_POST['protx_direct_cc_start_month'] . $_POST['protx_direct_cc_start_year'] : ''),
      'ExpiryDate' => $_POST['protx_direct_cc_expires_month'] . $_POST['protx_direct_cc_expires_year'],
      'IssueNumber' => (tep_not_null($_POST['protx_direct_cc_issue']) ? $_POST['protx_direct_cc_issue'] : ''),
      'CV2' => $_POST['protx_direct_cc_cvv_nh-dns'],
      'CardType' => $_POST['cc_card_type_code'],
      'BillingSurname' => substr($order->billing['lastname'], 0, 20),
      'BillingFirstnames' => substr($order->billing['firstname'], 0, 20),
      'BillingAddress1' => substr($order->billing['street_address'], 0, 100),
      'BillingAddress2' => (ACCOUNT_SUBURB == 'true') ? $order->billing['suburb'] : '',
      'BillingCity' => $order->billing['city'],
	    'BillingState' => $bil_state,
      'BillingPostCode' => substr($bil_postcode, 0, 10),
      'BillingCountry' => $order->billing['country']['iso_code_2'],
      'BillingPhone' => substr($order->customer['telephone'], 0, 20),
 	    'DeliverySurname' => substr($order->delivery['lastname'], 0, 20),
	    'DeliveryFirstnames' => substr($order->delivery['firstname'], 0, 20),
	    'DeliveryAddress1' => substr($order->delivery['street_address'], 0, 100),
	    'DeliveryAddress2' => (ACCOUNT_SUBURB == 'true') ? $order->delivery['suburb'] : '',
	    'DeliveryCity' => $order->delivery['city'],
	    'DeliveryState' => $del_state,
	    'DeliveryPostCode' => substr($del_postcode, 0, 10),
	    'DeliveryCountry' => $order->delivery['country']['iso_code_2'],
	    'DeliveryPhone' => substr($order->customer['telephone'], 0, 20),
      'CustomerEMail' => substr($order->customer['email_address'], 0, 255),
      'ClientIPAddress' => tep_get_ip_address(),
      'Basket' => $basketlist,
      'AccountType' => MODULE_PAYMENT_PROTX_DIRECT_MERCHANT_ACCOUNT,
      'Apply3DSecure' => $use_3D_Secure
    );

	  $data = '';
    // concatenate the submission data and put into variable $data
	  while(list($key, $value) = each($submit_data)) {
	    $data .= $key . '=' . urlencode($value) . '&';
}

    // Strip final &
    $data = substr($data, 0, -1);

    $responses = $this->do_curl($data, $this->url);

    $data = Array('customer_id' => $customer_id,
                  'order_id' => $new_order_id,
                  'vendortxcode' => $VendorTxCode,
                  'txtype' => MODULE_PAYMENT_PROTX_DIRECT_AUTHORIZATION_TYPE,
                  'value' => $this->format_raw($order->info['total']),
                  'vpstxid' => $responses['VPSTxId'],
                  'status' => $responses['Status'],
                  'statusdetail' => $responses['StatusDetail'],
                  'txauthno' => $responses['TxAuthNo'],
                  'securitykey' => $responses['SecurityKey'],
                  'avscv2' => $responses['AVSCV2'],
                  'address_result' => $responses['AddressResult'],
                  'postcode_result' => $responses['PostCodeResult'],
                  'CV2_result' => $responses['CV2Result'],
                  '3DSecureStatus' => $responses['3DSecureStatus'],
                  'CAVV' => $responses['CAVV'],
                  'txtime' => date('Y-m-d H:i:s')
                 );

    tep_db_perform(TABLE_PROTX_DIRECT, $data);
    $this->protx_id = tep_db_insert_id();

	  return $this->interpreteResponse($responses);
}

function do3Dreturn() {

  $data = 'MD=' . $_POST['MD'] .'&' . 'PARes=' . $_POST['PaRes'];

  $responses = $this->do_curl($data, $this->threeDurl);

  !isset($responses['CAVV']) ? $responses['CAVV'] = "" : null;
  $data = Array('vpstxid' => $responses['VPSTxId'],
                'status' => $responses['Status'],
                'statusdetail' => $responses['StatusDetail'],
                'txauthno' => $responses['TxAuthNo'],
                'securitykey' => $responses['SecurityKey'],
                'avscv2' => $responses['AVSCV2'],
                'address_result' => $responses['AddressResult'],
                'postcode_result' => $responses['PostCodeResult'],
                'CV2_result' => $responses['CV2Result'],
                '3DSecureStatus' => $responses['3DSecureStatus'],
                'CAVV' => $responses['CAVV'],
                'txtime' => date('Y-m-d H:i:s')
               );

  tep_db_perform(TABLE_PROTX_DIRECT, $data, 'update', 'id='.(int)$this->protx_id);

  return $this->interpreteResponse($responses);
}

function interpreteResponse($responses)
{
  // Check response and proceed appropriately
  $response_code = substr($responses['StatusDetail'], 0, 4);
  $authorised = FALSE;  // Default to transaction failed
  $detail = '';

  if ( isset($responses['debug']) && ($responses['debug'] === true) )
  {
  	$detail = $responses['debug_text'];
}
                        	else
  {
	  switch ($responses['Status']) {
	    case "3DAUTH":
		    // Redirect to card issuing bank for 3D-Secure authorisation
		    $authorised = '3DAUTH';
		    $detail = $responses;
		    break;

	    case "OK":
		  case "REGISTERED":
		  case "AUTHENTICATED":
		    // OK to proceed
        $authorised = TRUE;
		    break;

		  case "REJECTED":
		  case "NOTAUTHED":
		  case "MALFORMED":
		  case "INVALID":
		  case "ERROR":
		    $detail = $this->response_error_msg($response_code, $responses['AVSCV2'], $responses['StatusDetail']);
		  break;

		  default:
		    // Just in case we haven't caught any other response, assume failed
        $detail = MODULE_PAYMENT_PROTX_DIRECT_TEXT_PROTX_ERROR . ' (' . $responses['StatusDetail'] . ')';
		  break;
        }
                        }

  if (MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE != 'Live' && !is_array($detail))
  {
	  $detail = $responses['Status'] . ' - ' . $detail;
    }

  return array('authorised' => $authorised, 'detail' => $detail);
}

        function after_process() {
                global $insert_id;

  tep_db_query("UPDATE " . TABLE_PROTX_DIRECT . " SET order_id = " . (int)$insert_id . " WHERE id = " . $this->protx_id);
        }

        function get_error() {

	$error = array('title' => MODULE_PAYMENT_PROTX_DIRECT_TEXT_ERROR,
                 'error' => stripslashes(urldecode($_GET['error'])));
  return $error;
                }

        function check() {
                if (!isset($this->_check)) {
                        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PROTX_DIRECT_STATUS'");
                        $this->_check = tep_db_num_rows($check_query);
                }
                return $this->_check;
        }

        function install() {
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Protx Direct Module', 'MODULE_PAYMENT_PROTX_DIRECT_STATUS', 'True', 'Do you want to accept Protx payments via the Direct Method?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Vendor Name', 'MODULE_PAYMENT_PROTX_DIRECT_VENDOR_NAME', 'testvendor', 'The login vendor name for the Protx service.', '6', '0', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE', 'Test', 'Transaction mode used for processing orders.<br /><br /><strong>Server IP Test (Showpost)</strong> is used to gain the IP address of your server which Protx will need to get you live.', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Server IP Test\', \'Simulator\', \'Production\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Authorisation Type', 'MODULE_PAYMENT_PROTX_DIRECT_AUTHORIZATION_TYPE', 'PAYMENT', 'Do you want submitted credit card transactions to us authenticate & authorise, deferred, or immediately charged? - Contact Protx for explanation.', '6', '0', 'tep_cfg_select_option(array(\'PAYMENT\', \'DEFERRED\', \'AUTHENTICATE\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Merchant Account', 'MODULE_PAYMENT_PROTX_DIRECT_MERCHANT_ACCOUNT', 'E', 'Which merchant account is to be used?<br />E = E-commerce<br />C = Continuous Authority<br />M = Mail Order / Telephone Order', '6', '0', 'tep_cfg_select_option(array(\'E\', \'C\', \'M\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Send Shopping Cart', 'MODULE_PAYMENT_PROTX_DIRECT_SHOPCART', 'True', 'Do you want details of the customer\'s cart to be sent to Protx?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug', 'MODULE_PAYMENT_PROTX_DIRECT_DEBUG', 'False', '<strong>Do Not</strong> enable this unless instructed to do so.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable cURL SSL check?', 'MODULE_PAYMENT_PROTX_DIRECT_CURL_SSL', 'False', 'Only disable if having connection problems', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PROTX_DIRECT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
                tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PROTX_DIRECT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Mastercard', 'MODULE_PAYMENT_PROTX_DIRECT_USE_MC', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Visa', 'MODULE_PAYMENT_PROTX_DIRECT_USE_VISA', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Solo', 'MODULE_PAYMENT_PROTX_DIRECT_USE_SOLO', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Electron', 'MODULE_PAYMENT_PROTX_DIRECT_USE_UKE', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Visa Delta', 'MODULE_PAYMENT_PROTX_DIRECT_USE_DELTA', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Switch/Maestro', 'MODULE_PAYMENT_PROTX_DIRECT_USE_MAESTRO', 'True - with 3D-Secure', '', '6', '0', 'tep_cfg_select_option(array(\'True - with 3D-Secure\',\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow AMEX', 'MODULE_PAYMENT_PROTX_DIRECT_USE_AMEX', 'False', '', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow Diners', 'MODULE_PAYMENT_PROTX_DIRECT_USE_DC', 'False', '', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow JCB', 'MODULE_PAYMENT_PROTX_DIRECT_USE_JCB', 'False', '', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PROTX_DIRECT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

  tep_db_query("CREATE TABLE IF NOT EXISTS `protx_direct` (
                  `id` int(11) unsigned NOT NULL auto_increment,
                  `customer_id` int(11) NOT NULL default '0',
                  `order_id` int(11) NOT NULL default '0',
                  `vendortxcode` varchar(40) default NULL,
                  `txtype` varchar(16) default NULL,
                  `value` decimal(15,4) default NULL,
                  `vpstxid` varchar(50) default NULL,
                  `status` varchar(15) default NULL,
                  `statusdetail` varchar(100) default NULL,
                  `txauthno` varchar(10) default NULL,
                  `securitykey` varchar(10) default NULL,
                  `avscv2` varchar(50) default NULL,
                  `address_result` varchar(20) default NULL,
                  `postcode_result` varchar(20) default NULL,
                  `CV2_result` varchar(20) default NULL,
                  `3DSecureStatus` varchar(12) default NULL,
                  `CAVV` varchar(32) default NULL,
                  `txtime` timestamp NOT NULL,
                  UNIQUE KEY `id` (`id`)
                ) TYPE=MyISAM AUTO_INCREMENT=1;");
        }

        function remove() {
                tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
        }

        function keys() {
    return array(
      'MODULE_PAYMENT_PROTX_DIRECT_STATUS',
      'MODULE_PAYMENT_PROTX_DIRECT_CURL_SSL',
      'MODULE_PAYMENT_PROTX_DIRECT_VENDOR_NAME',
      'MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE',
      'MODULE_PAYMENT_PROTX_DIRECT_AUTHORIZATION_TYPE',
      'MODULE_PAYMENT_PROTX_DIRECT_MERCHANT_ACCOUNT',
      'MODULE_PAYMENT_PROTX_DIRECT_SHOPCART',
      'MODULE_PAYMENT_PROTX_DIRECT_DEBUG',
      'MODULE_PAYMENT_PROTX_DIRECT_SORT_ORDER',
      'MODULE_PAYMENT_PROTX_DIRECT_ZONE',
      'MODULE_PAYMENT_PROTX_DIRECT_ORDER_STATUS_ID',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_MC',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_VISA',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_SOLO',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_DELTA',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_UKE',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_JCB',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_AMEX',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_MAESTRO',
      'MODULE_PAYMENT_PROTX_DIRECT_USE_DC');
        }

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code)) {
        $currency_code = $currency;
}

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
        }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
      }

function do_curl($data, $url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 90);

  if (MODULE_PAYMENT_PROTX_DIRECT_CURL_SSL == 'True')
  {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  }

  $response = curl_exec($ch);
  $curl_error = curl_error($ch);

  curl_close ($ch);

    // Begin Debug Section
  if (MODULE_PAYMENT_PROTX_DIRECT_TRANSACTION_MODE == 'Server IP Test')
  {
    $responses['debug'] = TRUE;
    $responses['debug_text'] = $response . "\r\n\r\n". $data;
  }
  else
  {
    // parse Protx response string
    $responses = Array();
    $response_array = explode("\r\n", $response);
    for ($i=0; $i < sizeof($response_array); $i++)
    {
      $key = substr($response_array[$i], 0, strpos($response_array[$i], '='));
      $responses[$key] = substr(strstr($response_array[$i], '='), 1);
    }
    // Begin Debug Section
    if (MODULE_PAYMENT_PROTX_DIRECT_DEBUG == 'True')
    {
      $responses['debug'] = TRUE;
      $responses['debug_text'] = '<pre>Request URL=' . $this->url . "\r\n" .
                                 'Data string sent=' . $data . "\r\n" .
                                 'Protx response=' . $response . "\r\n" .
                                 'Response array='. print_r($responses,true) ."\r\n".
                                 'curl_error= ' . $curl_error . '</pre>';
    }
  }

  return $responses;
}

function response_error_msg($statusCode, $AVSCV2, $statusDetail)
{

  $codeArray = Array(
    '0000' => 'The Authorisation was Successful.',
    '2000' => 'The Authorisation was Declined by the bank.',
    '2001' => 'The Authorisation was Rejected by the vendor rule-base.',
    '2002' => 'The Authorisation timed out.',
    '2004' => 'The Release was Successful.',
    '2005' => 'The Void was Successful.',
    '2006' => 'The Abort was Successful.',
    '2007' => 'Please redirect your customer to the ACSURL, passing the MD and PaReq.',
    '2010' => 'The Authentication was Successful.',
    '2011' => 'The Transaction has been Registered.',
    '2012' => 'The Cancel was Successful.',
    '2015' => 'The server encountered an unexpected condition which prevented it from fulfilling the request.',
    '3007' => 'The RelatedSecurityKey format invalid.',
    '3014' => 'The TxType or PaymentType is invalid.',
    '3017' => 'The RelatedTxAuthNo format is invalid.',
    '3025' => 'The Delivery PostCode is too long.',
    '3031' => 'The Amount value is required',
    '3035' => 'The VendorTxCode format is invalid',
    '3040' => 'The RelatedTxAuth number is required.',
    '3048' => 'The CardNumber length is invalid.',
    '3068' => 'The PaymentSystem invalid. The value was DECLINED.',
    '3069' => 'The PaymentSystem is not supported on the account.',
    '3078' => 'The CustomerEMail format is invalid.',
    '3090' => 'The BillingPostCode is required.',
    '3099' => 'The AccountType is not setup on this account.',
    '4006' => 'The TxType requested is not supported on this account.',
    '4008' => 'The Currency is not supported on this account.',
    '4020' => 'Information received from an Invalid IP address.',
    '4021' => 'The Card Range not supported by the system.',
    '4022' => 'The Card Type selected does not match card number.',
    '4022' => 'The Card Type selected does not match card number.',
    '4026' => '3D-Authentication failed. This vendor\'s rules require a successful 3D-Authentication.',
    '4028' => 'The RelatedVPSTxId cannot be found.',
    '4029' => 'The RelatedVendorTxCode does not match the original transaction.',
    '4032' => 'The original transaction was carried out by a different Vendor.',
    '4035' => 'This Refund would exceed the amount of the original transaction.',
    '4042' => 'The VendorTxCode has been used before for another transaction.',
    '4046' => '3D-Authentication required. Cannot authorise this card.',
    '6000' => 'Data Access Error.'
  );

  switch ($statusCode)
  {
  	case '5015':
      switch (TRUE)
      {
      	case strpos(strtolower($statusDetail), 'expired'):
      		$msg = 'Card validation failure. The card has expired.';
      		break;

      	case strpos(strtolower($statusDetail), 'card issue number'):
      		$msg = 'Card validation failure. The Card Issue Number length is invalid.';
      		break;

      	case strpos(strtolower($statusDetail), 'range'):
      		$msg = 'Card validation failure. The Card Range not supported by the system';
      		break;

      	case strpos(strtolower($statusDetail), 'cardholder'):
      		$msg = 'Card validation failure. The CardHolder value is too long.';
      		break;

      	case strpos(strtolower($statusDetail), 'luhn'):
      		$msg = 'Card validation failure. The check digit invalid. Card failed the LUHN check.  Check the card number and resubmit.';
      		break;

      	case strpos(strtolower($statusDetail), 'security code is not'):
      		$msg = 'Card validation failure. The Security Code is not a number.';
      		break;

      	case strpos(strtolower($statusDetail), 'length is invalid'):
      		$msg = 'Card validation failure. The Security Code length is invalid.';
      		break;

      	case strpos(strtolower($statusDetail), 'future'):
      		$msg = 'Card validation failure. The StartDate is in the future. The card is not yet valid.';
      		break;

      	default:
      	  $msg = 'Card validation failure.';
      		break;
      }
  		break;

  	case '2001':
      switch (TRUE)
      {
      	case strpos(strtolower($AVSCV2), 'no data matches') !== FALSE:
      		$msg = 'There has been an error with the security checks. Please check your billing address matches your card statement address and the security code (CVV) is correct and try again.';
      		break;

      	case strpos(strtolower($AVSCV2), 'security code match only') !== FALSE:
      	  $msg = 'The billing address given does not match that of your card. Please correct this and try again.';
      	  break;

      	case strpos(strtolower($AVSCV2), 'address match only') !== FALSE:
      	  $msg = 'The security code given does not match that of your card. Please correct this and try again.';
      	  break;

      	default:
      	  $msg = 'Your credit card could not be authorised. Please correct any information and try again or contact us for further assistance. (' . $statusDetail . ')';
      		break;
      }
      break;

  	default:
      if ( (false === is_numeric($statusCode)) || (false === isset($codeArray[$statusCode])) )
{
  	  	$msg = $statusDetail;
  	  }
  	  else
  {
  	   $msg = $codeArray[$statusCode];
    }
  		break;
  }

  return $msg;
}

}  //end class
?>