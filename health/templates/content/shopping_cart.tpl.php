<link rel="stylesheet" type="text/css" href="/templates/Original/css/checkout.css">
<link rel="stylesheet" type="text/css" href="/java/plugins/modal/jquery-impromptu.css">
<script src="/java/plugins/modal/jquery-impromptu.js"></script>

<? 
$_SESSION['amazon_purchaseContractId'] = '';
?>

<div id="cart">
    <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>
	
		<h2>Your Basket</h2>
				
	
	
	
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="cart-contents">
	<? if ($cart->count_contents() > 0) { ?>
	<tr>
		<td>
			<?php
				$vat_exemption_array = array();
				//$vat_exemption_array[] = array('id'=> '', 'text' => '&nbsp;');
				$vat_exemption_array[] = array('id'=> '1', 'text' => TEXT_VAT_EXEMPT);
				$vat_exemption_array[] = array('id'=> '0', 'text' => TEXT_VAT_INC);

				$info_box_contents = array();
				$info_box_contents[0][] = array('align' => 'center',
						'params' => 'class="productListing-heading" style="width:1%"',
						'text' => TABLE_HEADING_REMOVE);

				$info_box_contents[0][] = array('params' => 'class="productListing-heading"  style="width:40%"',
						'text' => TABLE_HEADING_PRODUCTS);

				$info_box_contents[0][] = array('align' => 'center',
					'params' => 'class="productListing-heading"  style="width:20%"',
					'text' => TABLE_HEADING_QUANTITY);

				$products = $cart->get_products();

				// VAT exemption addon

				$arr_vat_prids = array();
				for ($i=0, $n=sizeof($products); $i<$n; $i++) {
					$arr_vat_prids[] = (int)$products[$i]['id'];
				}
				$arr_vat_exemption_flag = array();
				if(count($arr_vat_prids)>0)	{
					$query_vat_exempt_flag = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id in ('" . implode("', '", $arr_vat_prids) . "') and vat_exempt_flag = '1'");
					if(tep_db_num_rows($query_vat_exempt_flag)) {
						while($row_ve = tep_db_fetch_array($query_vat_exempt_flag))
						$arr_vat_exemption_flag[] = $row_ve['products_id'];
					}
				}

				if(count($arr_vat_exemption_flag)>0) {
					$info_box_contents[0][] = array('align' => 'center',
						'params' => 'class="productListing-heading"  style="width:20%"',
						'text' => TABLE_HEADING_VAT_EXEMPTION);
				}

				// end VAT exemption addon

				$info_box_contents[0][] = array('align' => 'right',
					'params' => 'class="productListing-heading"  style="width:10%"',
					'text' => TABLE_HEADING_TOTAL);

				$any_out_of_stock = 0;
				//$products = $cart->get_products();
				for ($i=0, $n=sizeof($products); $i<$n; $i++) {
				// {{ Products Bundle Sets
				if (PRODUCTS_BUNDLE_SETS == 'True')
  {
    global $customer_groups_id, $currency_id;
    $bundle_sets_query = tep_db_query("select p.products_id, sp.num_product from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.sets_id = '" . (int)$products[$i]['id'] . "' and p.products_status = '1' and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
    if (tep_db_num_rows($bundle_sets_query) > 0)
    {
      while ($bundle_sets = tep_db_fetch_array($bundle_sets_query))
      {
        $products[$i]['bundles'][$bundle_sets['products_id']] = $bundle_sets['num_product'] . '&nbsp;x&nbsp;<b>' . tep_get_products_name($bundle_sets['products_id']) . '</b>';
      }
    }
  }
// }}
  // Push all attributes information in an array
  if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
    while (list($option, $value) = each($products[$i]['attributes'])) {
      echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
      $option_arr = split('-', $option);
      $attributes = tep_db_query("select pa.products_id, pa.products_attributes_id, popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)($option_arr[1] > 0 ? $option_arr[1] : $products[$i]['id']) . "'
                                       and pa.options_id = '" . (int)$option_arr[0] . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . $languages_id . "'
                                       and poval.language_id = '" . $languages_id . "'");
      $attributes_values = tep_db_fetch_array($attributes);

// {{ Products Bundle Sets
      $products[$i][$option]['products_id'] = $attributes_values['products_id'];
// }}
      $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
      $products[$i][$option]['options_values_id'] = $value;
      $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
      $attributes_values['options_values_price'] = tep_get_options_values_price($attributes_values['products_attributes_id']);
      $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
      $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
    }
  }
}

for ($i=0, $n=sizeof($products); $i<$n; $i++) {
  if (($i/2) == floor($i/2)) {
    $info_box_contents[] = array('params' => 'class="productListing-even"');
  } else {
    $info_box_contents[] = array('params' => 'class="productListing-odd"');
  }

  $cur_row = sizeof($info_box_contents) - 1;
  if (AUCTION_BLOX_ENABLED == 'True'){
    //+++AUCTIONBLOX.COM
    if (isset($products[$i]['is_auction_item'])) {
      $info_box_contents[$cur_row][] = array('align' => 'center',
      'params' => 'class="productListing-data" valign="top"',
      'text' => '&nbsp;');
    } else {
      $info_box_contents[$cur_row][] = array('align' => 'center',
      'params' => 'class="productListing-data" valign="top"',
      'text' => (!$products[$i]['ga'] ? tep_draw_checkbox_field('cart_delete[]', $products[$i]['id'],'','id="cart_delete'.$i.'"') : '&nbsp;'));
    }

    $products_name = '';
    if (isset($products[$i]['is_auction_item'])) {
      $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
      ' <tr>' .
      ' <td class="productListing-data" align="center"><a target="_blank" href="' . $products[$i]['item_url'] . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
      ' <td class="productListing-data" valign="top"><a target="_blank" href="' . $products[$i]['item_url']. '"><b>' . $products[$i]['name'] . '</b></a>';
    } else {
      $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
      ' <tr>' .
      ' <td class="productListing-data" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
      ' <td class="productListing-data" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';

    }

    if (!isset($products[$i]['is_auction_item'])){
      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;
          $products_name .= $stock_check;
        }
      }
    }
    //+++AUCTIONBLOX.COM
  }else{
    $info_box_contents[$cur_row][] = array('align' => 'center',
    'params' => 'class="productListing-data" valign="top"',
    'text' => (!$products[$i]['ga'] ? tep_draw_checkbox_field('cart_delete[]', $products[$i]['id'],'','id="cart_delete'.$i.'"') : '&nbsp;'));

    $products_name = '';
    $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
    '  <tr>' .
    '    <td class="productListing-data product-image" align="center" width="' . SMALL_IMAGE_WIDTH . '">' . (!$products[$i]['ga'] ? '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' : '') . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . (!$products[$i]['ga'] ? '</a>' : '') . '</td>' .
    '    <td class="productListing-data product-name" valign="top">' . (!$products[$i]['ga'] ? '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' : '') . $products[$i]['name'] .  (!$products[$i]['ga'] ? '</a>' : '');

    if (STOCK_CHECK == 'true'){
      $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
      if (tep_not_null($stock_check)){
        $any_out_of_stock = 1;
        $products_name .= $stock_check;
      }
    }
  }

// {{ Products Bundle Sets
  $bundle_prods_options = array();
  $bundle_prods_options_string = '';
  if (isset($products[$i]['bundles']) && is_array($products[$i]['bundles']))
  {
    reset($products[$i]['bundles']);
    while (list($prid, $name) = each($products[$i]['bundles'])) {
      $bundle_prods_options_string .= '<br><small><i> - ' . $name . '</i></small>';
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes']))
      {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes']))
        {
          if ($products[$i][$option]['products_id'] == $prid)
          {
            $bundle_prods_options_string .= '<small><br><i>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;' . $products[$i][$option]['products_options_name'] . ': ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
            $bundle_prods_options[] = $option;
          }
        }
      }
    }
  }
// }}
  if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
    reset($products[$i]['attributes']);
    while (list($option, $value) = each($products[$i]['attributes'])) {
// {{ Products Bundle Sets
      if (in_array((string)$option, $bundle_prods_options)) continue;
// }}
      $products_name .= '<br><small><i> - ' . htmlspecialchars($products[$i][$option]['products_options_name']) . ': ' . htmlspecialchars($products[$i][$option]['products_options_values_name']) . '</i></small>';
    }
  }
// {{ Products Bundle Sets
  $products_name .= $bundle_prods_options_string;
// }}

  $products_name .= '    </td>' .
  '  </tr>' .
  '</table>';

  $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data product-data"',
  'text' => $products_name);  
  
  if (AUCTION_BLOX_ENABLED == 'True'){
    //+++AUCTIONBLOX.COM
    if (isset($products[$i]['is_auction_item'])) {
      $info_box_contents[$cur_row][] = array('align' => 'center',
      'params' => 'class="productListing-data" valign="top"',
      'text' => $products[$i]['quantity']);
    } else {
      $info_box_contents[$cur_row][] = array('align' => 'center',
      'params' => 'class="productListing-data" valign="top"',
      'text' => (!$products[$i]['ga'] ? tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') : '&nbsp;<b>' . $products[$i]['quantity'] . '</b>' . tep_draw_hidden_field('cart_quantity[]', $products[$i]['quantity'])) . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . tep_draw_hidden_field('ga[]', $products[$i]['ga']));
    }
    //+++AUCTIONBLOX.COM
  }else{
    $info_box_contents[$cur_row][] = array('align' => 'center',
    'params' => 'class="productListing-data" valign="top"',
    'text' => (!$products[$i]['ga'] ? '<a href="javascript:changeCartQty(\''.$i.'\',-1)" class="qty-minus"></a>'.tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="3" id="cart_qty'.$i.'"').'<a href="javascript:changeCartQty(\''.$i.'\',1)" class="qty-plus"></a>' : '&nbsp;<b>' . $products[$i]['quantity'] . '</b>' . tep_draw_hidden_field('cart_quantity[]', $products[$i]['quantity'])) . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . tep_draw_hidden_field('ga[]', $products[$i]['ga']));
  }
  
  // VAT Exemption addon
     if(count($arr_vat_exemption_flag)>0)
     {
      //$product_info = tep_db_fetch_array(tep_db_query("select vat_exempt_flag from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products[$i]['id'] . "'"));

      $vat_exmn = 0;
      if(count($vat_exemption_arr)>0)
      {
       foreach($vat_exemption_arr as $key => $value)
       {
         if((int)$key == (int)$products[$i]['id'] && (int)$value==1)
         {  
          $vat_exmn = 1;
          break;                                       
         }                                        
       }
      }

      $info_box_contents[$cur_row][] = array('params' => 'valign="top" class="productListing-data vat_exempt"',
                                             'text' => (in_array($products[$i]['id'], $arr_vat_exemption_flag)?tep_draw_pull_down_menu('vat_exemption_change[' . $products[$i]['id'] . ']', $vat_exemption_array, $vat_exmn, 'class="vat-dropdown" onchange="javascript:document.cart_quantity.submit();"'):"&nbsp;"));
     } 
  // end VAT Exemption addon  
  
  $info_box_contents[$cur_row][] = array('align' => 'right',
  'params' => 'class="productListing-data" valign="top"',
  'text' => '<b>' . $currencies->display_price($products[$i]['final_price'] * $products[$i]['quantity'], tep_get_tax_rate($products[$i]['tax_class_id'])) . '</b>');
}

new productListingBox($info_box_contents);
?>
        </td>
      </tr>
		<tr class="total">
			<td align="right" class="main">
				<span>Sub total</span>
				<?php echo $currencies->format($cart->show_total()); ?>
			</td>
		</tr>
	<? if ($cart->show_total() < MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) : ?>
		<?
			$spend_more = MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER - $cart->show_total();
			$spend_more = $currencies->format($spend_more); 
		?>
		<tr>
			<td class="main">
				<div class="panel-error">
					Spend <?=$spend_more;?> more for FREE Shipping (UK addresses only).
				</div>
			</td>
		</tr>		
	<? endif; ?>
	  
<?php
if ($any_out_of_stock == 1) {
  if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
      <tr>
        <td class="stockWarning" align="center"><div class="stockWarn"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td class="stockWarning" align="center"><div class="stockWarn"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div></td>
      </tr>
<?php
  }
}
?>

<?php 
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>

	<tr>
        <td>
			<?
				$continue_shopping_url = get_affiliate_continue_shopping_url();
				if (tep_not_null($continue_shopping_url)) {
					$continue_shopping_url = str_replace('{SID}', tep_session_name() . '=' . tep_session_id(), $continue_shopping_url);
					$but = '<a href="' . $continue_shopping_url . '">' . tep_template_image_button('button_continue_shopping.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE_SHOPPING, 'class="transpng"') . '</a>';
				}
				else {
					$back = sizeof($navigation->path)-2;
					if (isset($navigation->path[$back])) {
						$but = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_continue_shopping.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE_SHOPPING, 'class="transpng"') . '</a>';
						$continue_shopping_url = tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']);
					} else {
						$but = '<a href="/">'.tep_template_image_button('button_continue_shopping.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE_SHOPPING, 'class="transpng"') . '</a>';
						$continue_shopping_url = "/";
					}
				}
			?>		
			<div class="cart-actions">
				<!--
				<a href="<?=tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');?>" title="checkout" class="button-checkout inline-block absolute">
				checkout
				</a>-->			
				<a href="<?=tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'payment_option=sagepay_form', 'SSL');?>" title="checkout" class="button-checkout float-right">Proceed</a>									
				<a href="<?=$continue_shopping_url;?>" class="link-continue-shopping float-right">Continue Shopping</a>
				<div class="clear"></div>
				<? tep_paypal_wpp_ep_button(FILENAME_SHOPPING_CART);?>
				<div class="clear"></div>
				<a href="/checkout.php?payment_option=moneyorder" class="checkout-cheque inline-block">Pay by cheque</a>
			</div>
		
			<?php
				$info_box_contents = array();
				$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
					array('params' => 'class="main"', 'text' => tep_template_image_submit('button_update_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_UPDATE_CART, 'class="transpng"')),
					array('params' => 'class="main"', 'text' => $but),
					//array('params' => 'class="main" align=right', 'text' => '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_template_image_button('button_checkout.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CHECKOUT, 'class="transpng"') . '</a>'),
					array('text' => tep_draw_separator('pixel_trans.gif', '2', '1')));
					//new buttonBox($info_box_contents);
			?>
        </form>
        </td>
		<tr>
			<td class="relative" style="height:20px;">
				
				<? if (ADD_BASKET_HOLIDAY_MESSAGE != "") :?>
					<script>
						$(document).ready(function() {
							$(".button-checkout, a.checkout-paypal").click(function() {
								var sender = this;
								$.prompt("", {
									title: "<?=ADD_BASKET_HOLIDAY_MESSAGE;?>",
									buttons: { "OK, add to basket": true, "Cancel": false },
									submit: function(e,v,m,f){
										if (v) {
											document.location = $(sender).attr("href");
											return(true);											
										}
									}
								});
								return(false);				
							});
						});					
					</script>
				<? else: ?>
				<? endif; ?>				
			</td>
		</tr>
      </tr>
		<tr>
			<td class="main">
			</td>
		</tr>	  
		
<?php if ((defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS') && MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True') || 
          (defined('MODULE_PAYMENT_PAYPAL_DP_STATUS') && MODULE_PAYMENT_PAYPAL_DP_STATUS == 'True') ) { ?>
		<!--		
      <tr>
        <td class="main" ><div style="padding-top:0.7em;float:right; width:180px; text-align: center;"><? echo TEXT_OR_USE?></div></td>
      </tr>
	  -->
<?php } ?>
				<!-- Start: Amazon Checkout Button -->
				<tr>
					<? 
						if ($_GET['amazontest'] == "1") {
							$_SESSION['amazontest'] = "1";
						}
						
						if ($_SESSION['amazontest'] == "1") $display="block;"; else $display = "none";
					?>
						 
					<td align="right" style="display:<?=$display;?>">
						<? if ($_SESSION['amazon_purchaseContractId'] != '') : ?>
							<a href="https://www.healthcare4all.co.uk/checkout.php?purchaseContractId=<?=$_SESSION['amazon_purchaseContractId'];?>"><img src="https://payments-sandbox.amazon.co.uk/gp/cba/button?cartOwnerId=AJO56SWKSCWXN&size=large&color=orange&background=white&type=inlineCheckout" style="cursor: pointer;"/></a>
						<? else: ?>
							<!--
							<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js'>
							</script>
							<div id="AmazonInlineWidget"><img src="https://payments-sandbox.amazon.co.uk/gp/cba/button?cartOwnerId=AJO56SWKSCWXN&size=large&color=orange&background=white&type=inlineCheckout" style="cursor: pointer;"/></div>
							-->							
							<!-- For Sandbox Uncomment the lines above and comment the lines below -->							
							<!-- For Switching to Production, comment out all the lines above and uncomment the lines below -->
							
							<!-- Commented out as Amazon is causing problems with local jQuery
							<script type='text/javascript' src='https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js'>
							</script>
							<div id="AmazonInlineWidget"><img src="https://payments.amazon.co.uk/gp/cba/button?cartOwnerId=AJO56SWKSCWXN&size=large&color=orange&background=white&type=inlineCheckout" style="cursor: pointer;"/></div>
							
							<script type='text/javascript' >
								new CBA.Widgets.InlineCheckoutWidget({
									merchantId: 'AJO56SWKSCWXN',
										// The onAuthorize callback function is invoked after the user is successfully authenticated.
										// The callback function is passed the widget reference and widget.getPurchaseContractId() 
										// returns the PurchaseContractId, which can be used to retrieve the address details
										// by calling CheckoutByAmazon service or completing the order. 
									onAuthorize: function(widget) {
											window.location = 'https://www.healthcare4all.co.uk/checkout.php?purchaseContractId=' + widget.getPurchaseContractId() ;	
										}
									}).render("AmazonInlineWidget");
							</script>
							-->
						<? endif; ?>
						
					</td>
				</tr>
				<!-- End: Amazon Checkout Button -->

      <tr>
        <td>
<?php
    // ** GOOGLE CHECKOUT **
    // Checks if the Google Checkout payment module has been enabled and if so 
    // includes gcheckout.php to add the Checkout button to the page 
    if (defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS') && MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True') {
      include_once( DIR_FS_CATALOG . (substr(DIR_FS_CATALOG,-1)=='/'?'':'/'). 'googlecheckout/gcheckout.php');
    }
    // ** END GOOGLE CHECKOUT **
?>

        </td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_GIVEAWAY_PRODUCTS); ?></td>
      </tr>
<?php
// WebMakers.com Added: Shipping Estimator
if (SHOW_SHIPPING_ESTIMATOR=='true') {
  // always show shipping table
?>
      <tr>
        <td>
			<?php require(DIR_WS_MODULES . 'shipping_estimator.php'); ?>
			
			<!-- Start: Shipping Estimator -->
			<?php if ($cart->haveVatExempt() && 0 == 1 ) { ?>
			<div id="vat-form" class="vat_info_warning">
				<span class='swatch-red red-text'><strong>Please <a href='/vatform.php?iorigin=1' class='swatch-red'>click here</a> to complete the VAT Exemption form.</strong></span>
				<?php 
					 $checked_flag = false;
					  if (!tep_session_is_registered('customer_id')) {
						if (tep_check_vat_form(false)) {
						  echo TEXT_VAT_FORM_DESCRIPTION_FOUND;
						  $checked_flag = true;
						}else{
						  echo sprintf(TEXT_VAT_FORM_DESCRIPTION, tep_href_link(FILENAME_LOGIN, 'rd=' . FILENAME_SHOPPING_CART, 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, 'rd=' . FILENAME_SHOPPING_CART, 'SSL'));
						}
					  } elseif (!tep_check_vat_form($customer_id)) {
						echo TEXT_VAT_FORM_DESCRIPTION_LOGGED_IN;
					  } else {
						echo TEXT_VAT_FORM_DESCRIPTION_FOUND;
						$checked_flag = true;
					  }
					  ?>
					<br /> 
					<!--
					<input type="checkbox" onclick="document.getElementById('veForm').style.display=(this.checked?'':'none');" <?php echo ($checked_flag || count($vat_exemption_arr)>0?'checked':'')?>>
					-->
					<?php echo TEXT_VAT_EXEMPT_TEXT; ?>
						<span <?if(count($vat_exemption_arr)==0){?>style="display:none" <?}?>id="veForm"><a href="<?php echo tep_href_link(FILENAME_VATFORM, 'iorigin=1');?>"><span class="swatch-red"><?php echo TEXT_FILL_VAT_FORM; ?></span></span>
					<?php if ( false ){ ?> 
						<span style="display:none" id="veForm"><a href="vat_exempt_form.pdf" target="_blank"><?php echo TEXT_DOWNLOAD_VAT_FORM; ?></span>
					<?php } ?>
			</div>
			<?php } ?>
			<!-- End: Shipping Estimator -->
			
		</td>
      </tr>
<?php
}

?>
<?php
// {{ Up-Selling
if (SUPPLEMENT_STATUS == 'True') {
  $current_products = $current_categories = $current_upsell_products = $current_upsell_categories = $final_upsell_products = array();
  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
    $prid = tep_get_prid($products[$i]['id']);
    if (!in_array($prid, $current_products)) {
      $current_products[] = $prid;
      $product_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$prid . "'");
      while ($product_categories = tep_db_fetch_array($product_categories_query)) {
        if (!in_array($product_categories['categories_id'], $current_categories)) {
          $current_categories[] = $product_categories['categories_id'];
        }
      }
    }
  }
  foreach ($current_categories as $current_cid) {
    $upsell_categories_query = tep_db_query("select upsell_id from " . TABLE_CATEGORIES_UPSELL . " where categories_id = '" . (int)$current_cid . "' order by sort_order asc");
    while ($upsell_categories = tep_db_fetch_array($upsell_categories_query)) {
      if (!in_array($upsell_categories['upsell_id'], $current_upsell_categories)) {
        $current_upsell_categories[] = $upsell_categories['upsell_id'];
      }
    }
  }
  foreach ($current_products as $current_pid) {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $upsell_products_query = tep_db_query("select p.upsell_id from " . TABLE_PRODUCTS_UPSELL . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.upsell_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_id = '" . (int)$current_pid . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " order by p.sort_order asc");
    }else{
      $upsell_products_query = tep_db_query("select p.upsell_id from " . TABLE_PRODUCTS_UPSELL . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.upsell_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_id = '" . (int)$current_pid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " order by p.sort_order asc");
    }
    while ($upsell_products = tep_db_fetch_array($upsell_products_query)) {
      if ( !in_array($upsell_products['upsell_id'], $current_upsell_products) ) {
        $current_upsell_products[] = $upsell_products['upsell_id'];
      }
    }
  }
  foreach ($current_categories as $current_cid) {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $upsell_cat_products_query = tep_db_query("select p.upsell_products_id from " . TABLE_CATS_PRODUCTS_UPSELL . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.upsell_products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.upsell_products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where categories_id = '" . (int)$current_cid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) order by sort_order asc");
    }else{
      $upsell_cat_products_query = tep_db_query("select p.upsell_products_id from " . TABLE_CATS_PRODUCTS_UPSELL . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.upsell_products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.categories_id = '" . (int)$current_cid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.sort_order asc");
    }
    while ($upsell_cat_products = tep_db_fetch_array($upsell_cat_products_query)) {
      if (!in_array($upsell_cat_products['upsell_products_id'], $current_upsell_products)) {
        $current_upsell_products[] = $upsell_cat_products['upsell_products_id'];
      }
    }
  }
  foreach ($current_upsell_categories as $current_cid) {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $category_products_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p2c.categories_id = '" . (int)$current_cid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) order by sort_order asc");
    }else{
      $category_products_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p2c.categories_id = '" . (int)$current_cid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.sort_order asc");
    }
    while ($category_products = tep_db_fetch_array($category_products_query)) {
      if (!in_array($category_products['products_id'], $current_upsell_products)) {
        $current_upsell_products[] = $category_products['products_id'];
      }
    }
  }
  foreach ($current_upsell_products as $current_pid) {
    if (!in_array($current_pid, $current_products)) {
      $final_upsell_products[] = $current_pid;
    }
  }
  $counter = 0;
  if (count($final_upsell_products) > 0) {
    $info_box_contents = array();
    $col = 0;
    $row = 0;
    foreach ($final_upsell_products as $final_pid) {
      $product_info_query = tep_db_query("select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_model, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 and p.products_id = '" . (int)$final_pid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.affiliate_id = 0 and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($product_info_query)) {
        $product_info = tep_db_fetch_array($product_info_query);
        $info_box_contents[$row][$col] = array('align' => 'center',
        'params' => 'class="productColumnSell" width="33%"',
        'text' => tep_output_product_table_sell($product_info, true));   
        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }      
        $counter++;
        if ($counter >= UPSELL_PRODUCTS_MAX_DISPLAY)   {
          break;
        }
      }
    }
if (sizeof($info_box_contents)){
  echo '      <tr>' . "\n";
  echo '        <td class="main">';
  $info_box_contents_heading = array();
  $info_box_contents_heading[] = array('align' => 'left', 'text' => TEXT_WE_ALSO_RECOMMEND);
  new contentBoxHeading($info_box_contents_heading, false, false, tep_href_link(FILENAME_SPECIALS));

  new contentBox($info_box_contents);
  echo '        </td>' . "\n";
  echo '      </tr>' . "\n";
}

  }
}
// }} Up-Selling
?>
    </table>
<?php
} else {
?>
      <tr>
        <td align="center" class="main"><?php echo TEXT_CART_EMPTY; ?></td>
      </tr>
<?php 
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
$info_box_contents = array();
$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
array('params' => 'class="main" align="right"', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
new buttonBox($info_box_contents);

?>
        </td>
      </tr>
     </table></form>
<?php
}
?>
</div>
<? require_once(DIR_WS_TEMPLATES."/java/checkout/shopping_cart.phtml"); ?>
    
