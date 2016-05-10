<link rel="stylesheet" type="text/css" href="/templates/Original/css/product.css">
<?
	/* Start: Load Helpers */
	include(DIR_WS_TEMPLATES."helpers/catalog/product/product.php");	
	$productReview = new ProductReviews();
	/* End: Load Helpers */	
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
  if ($product_check['total'] < 1) {
?>
      <tr>
        <td><?php new contentBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
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
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'class="main" align="right" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, if(length(pd1.products_name), pd1.products_name, 
										pd.products_name) as products_name, if(length(pd1.products_description), pd1.products_description, 
										pd.products_description) as products_description, if(length(pd1.products_features), 
										pd1.products_features, pd.products_features) as products_features, if(length(pd1.products_faq), 
										pd1.products_faq, pd.products_faq) as products_faq, p.products_model, 
										p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, 
										p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, 
										p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, 
										p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, 
										products_image_alt_1, products_image_alt_2, products_image_alt_3, products_image_alt_4, 
										products_image_alt_5, products_image_alt_6, if(length(pd1.products_url), pd1.products_url, 
										pd.products_url) as products_url, p.products_price, p.products_tax_class_id, p.products_date_added, 
										p.products_date_available, p.manufacturers_id, p.products_weight, p.vat_exempt_flag from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status >= 0   " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

	/*print "<pre>";
	print_r($product_info);
	print "</pre>";*/
	


     if((int)$product_info['vat_exempt_flag'] > 0)
     {
       $vat_exemption_array = array();
       //$vat_exemption_array[] = array('id'=> '', 'text' => '&nbsp;');
       $vat_exemption_array[] = array('id'=> '1', 'text' => TEXT_VAT_EXEMPT);
       $vat_exemption_array[] = array('id'=> '0', 'text' => TEXT_VAT_INC);
     }

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "' and affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span> <span class="productPriceSpecial">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
      $products_price_excl_vat = $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), 0);
    } else {
      $products_price = '<span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
      $products_price_excl_vat = $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), 0);
    }

    if (tep_not_null($product_info['products_model']) && false) {
      $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
	
    $reviews_check = tep_db_fetch_array(tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_info['products_id'] . "' and status=1"));
    $product_have_reviews = ((int)$reviews_check['count'] > 0);
	
	
?>
<?php
  $property_block = '';
  if (PRODUCTS_PROPERTIES == 'True'){
//=====================================================
/*
<option value="0">Input, max length 255 symbols</option>
<option value="1">Textarea</option>
<option value="2">True/False</option>
<option value="3">Multiply choice</option>
<option selected="" value="4">Single choice</option>
<option value="5">File for download</option>
<option value="6">Image</option>
*/
function format_product_property(&$prop){
  $ret = '';
  if (is_null($prop['set_value']) || $prop['set_value']=='') return '';
  if (($prop['properties_type']=='4') && ($prop['set_value']=='0' ) ) return ''; //?
  $ret .= '<tr>
             <td align="left" width="30%" valign="top">'.$prop['properties_name'].(strlen($prop['properties_description'])>0?'<br><span class="smallText">'.$prop['properties_description'].'</span>':'').'</td>';
  $ret .= '  <td align="justify" width="70%" valign="top">';
  switch (intval($prop['properties_type'])) {
    case 2:
      $ret .= ($prop['set_value']=='true'?PROPERTY_TRUE:PROPERTY_FALSE);
    break;
    case 5:
    case 6:
      $filename = $prop['set_value'];
      if ( file_exists( DIR_FS_CATALOG.'/'.DIR_WS_IMAGES.'data/'.$filename ) ) {
        if ( intval($prop['properties_type'])==5 ) {
          $ret .= '<a href="'.tep_href_link(DIR_WS_IMAGES.'data/'.$filename,'','NONSSL',false).'" target="_blank" >'.tep_image(DIR_WS_ICONS.'propfile_save.gif',TEXT_PROPERTY_FILE_DOWNLOAD).'</a>';
        }else{
          $ret .= tep_image( DIR_WS_IMAGES.'data/'.$filename, $prop['properties_name'].($prop['use_addinfo']!='0'?' '.$prop['additional_info']:''), MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT );
        }
      }else{
        $ret = ''; return;
      }
    break;
    default:
     $ret .= nl2br(trim($prop['set_value']));
  }
  $ret .= ( ($prop['use_addinfo']!='0' && !empty($prop['additional_info']) )?
            '<div class="smallText">('.$prop['additional_info'].')</div>':
            '');
  $ret .= '</tr>'."\n";
  return $ret;
}
//=====================================================
    $property_block = '';
    $query = tep_db_query("select p2pc.categories_id, pcd.categories_name, pcd.categories_description, p2p.set_value, p2p.additional_info, p.additional_info as use_addinfo, p.properties_type, pd.properties_name, pd.properties_description from " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p, " . TABLE_PROPERTIES_DESCRIPTION . " pd, " . TABLE_PROPERTIES . " p left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p.properties_id = p2pc.properties_id left join ".TABLE_PROPERTIES_CATEGORIES_DESCRIPTION." pcd on p2pc.categories_id=pcd.categories_id and pcd.language_id = " . (int)$languages_id." where p2p.properties_id = p.properties_id and p.properties_id = pd.properties_id and p2p.products_id = " . (int)$HTTP_GET_VARS['products_id'] . " and p2p.language_id = " . (int)$languages_id." and pd.language_id = " . (int)$languages_id." order by p2pc.categories_id, p.sort_order");
    $propcat_row = 0;
    while ($data = tep_db_fetch_array($query)){
      if ($propcat_row!=0) $property_block .= '<tr><td colspan=3 class="lineH">'.tep_draw_separator('spacer.gif', '100%', '1').'</td></tr>';
      if ($propcat_row!=$data['categories_id']){
        $property_block .= '<tr><td colspan=3 class="main"><b>'.$data['categories_name'].'</b></td></tr>';
        $propcat_row=$data['categories_id'];
      }
      $property_block .= format_product_property($data);
    }
    if ( strlen($property_block)!=0 ) {
      $property_block = '<table width="100%" border="0" cellspacing="0" cellpadding="2">'.$property_block.'</table>';
    }
  } // if (PRODUCTS_PROPERTIES == 'True')
?>
</tr>

<div class="product-header">
	<h2><?=$products_name;?></h2>
	<? if ($productReview->getReviewCount($product_info['products_id']) > 0) : ?>
		<div class="review-summary">
			<img class="review-stars" src="/templates/Original/images/reviews/stars_<?=$productReview->getProductAverageRating($product_info['products_id']);?>.gif" />
			<span class="text">
				<?=$productReview->getProductAverageRating($product_info['products_id']);?> out of 5 based on 
				<?=$productReview->getReviewCount($product_info['products_id']);?> reviews
			</span>
		</div>
	<? endif; ?>
	
	<? // Display Free Shipping message if Item is over the threshold (£50.00) - Musaffar Patel?>
	<? 
		$products_priceOnly = $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id']));
		$products_priceOnly = str_replace("&pound;", "", $products_priceOnly);
		$products_priceOnly = str_replace(",", "", $products_priceOnly);
	?>		
	<? if ($product_check['products_status'] == 1) : ?>
		<? if ($products_priceOnly > MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) : ?>		
			<div class="free-shipping float-left">		
				<img src="/templates/Original/images/product/free-shipping.png" alt="Free UK Shipping on this item" />		
			</div>
		<? else: ?>		
			<div class="free-shipping float-left">		
				<img src="/templates/Original/images/product/free-shipping.png" alt="Free UK Shipping on this item" />		
				<!--
				<img src="/templates/Original/images/product/delivery-price.png" alt="£1.99 for delivery" />		
				-->
			</div>		
		<? endif; ?>			

		<div class="price float-right" <?=$price_width;?>>
			<div class="left">
				<? if ($product_info['vat_exempt_flag'] > 0) : ?>
					<input id="vat-exempt" type="checkbox" value="1" class="float-left" checked="checked" />
					<div style="display:none;">
						<?echo ($product_info['vat_exempt_flag'] > 0 ? tep_draw_pull_down_menu('vat_exemption1', $vat_exemption_array) : '' );?>		
					</div>
					<span class="float-left" style="width:110px;">
						buying for personal use for my long term condition
					</span>
				<? endif; ?>
				<div class="clear"></div>
			
				<span class="stock">
					<?  if ( tep_get_products_stock($product_info['products_id']) > 0) : ?>
						<span class="instock swatch-green">
							<span class="icon"></span> In Stock
						</span>
					<? else: ?>
						<span class="outstock swatch-green">
							<span class="icon"></span> Out of Stock
						</span>					
					<? endif ?>
				</span>						
			</div>
			
			<div class="right">
				<span class="price-exvat"><span style="font-size:26px;"><b><?=$products_price_excl_vat;?></b></span> (ex VAT)</span><br />
				<span class="price-incvat"><?=$products_price;?> (inc VAT)</span><br />
				<input type="submit" class="button-green" value="Add to Basket" />
				<? //tep_template_image_submit('button_in_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_IN_CART, 'class="transpng"')?>
			</div>
		</div>
		
		<div class="clear"></div>
		
		<? if ($productHelper->messages['cutoff_time'] != "") : ?>
			<span class="cutoff-time swatch-matt-blue">
				<?=$productHelper->messages['cutoff_time'];?>
			</span>
		<? endif; ?>
		
	<? else : ?>
		<div class="soldout relative">
			<img src="/templates/Original/images/product/soldout.png" alt="Sold out" class="badge absolute" />		
			<div class="text swatch-matt-blue absolute">
				This product is currently out of stock or has been discontinued.   
				Browse our <a href="/">site</a> for other similar products, or <a href="/contact_us.php">contact us</a> for 
				help in finding what you're looking for			
			</div>
		</div>
	<? endif; ?>		
</div>

<table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="main"><?php echo TEXT_BREADCRUMB_START.' '.$breadcrumb->trail(' &raquo; '); ?></td>
      </tr>
      <?php if (CELLPADDING_SUB < 5){ ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td>
<script>
var a=1;
var i=1;
function clickOn(a){
  var objTab = false;
  var objTabH = false;
	for (i=1;i<=6;i++){
    var objTab = document.getElementById("tab"+i);
    var objTabH = document.getElementById("headTab"+i);
    if ( objTabH && objTab ) {
      objTab.style.display = (a==i?"":"none");
      objTabH.className =  (a==i?"headTabActiv":"headTab");
    }
	}
}
</script>
<ul class="tabs">
	<li onclick="clickOn(1);" id="headTab1" class="headTabActiv"><span><?php echo TEXT_TAB_SPECIFICATION; ?></span></li>
	<?php if ( tep_not_null( $product_info['products_features'] ) ) { ?>
	<li onclick="clickOn(2);" id="headTab2" class="headTab"><span><?php echo TEXT_TAB_FEATURES; ?></span></li>
	<?php } ?>
	<?php if ( strlen($property_block)>0 ) { ?>
	<li onclick="clickOn(6);" id="headTab6" class="headTab"><span><?php echo TEXT_TAB_TECHNICAL_DATA;?></span></li>
	<?php } ?>
	<li onclick="clickOn(3);" id="headTab3" class="headTab"<?php echo (!$product_info['products_image_sm_1']?' style="display:none"':'')?>><span><?php echo TEXT_TAB_IMAGES; ?></span></li>
	<li onclick="clickOn(4);" id="headTab4" class="headTab"><span><?php echo TEXT_TAB_REVIEWS; ?></span></li>
	<?php if ( tep_not_null( $product_info['products_faq'] ) ) { ?>
	<li onclick="clickOn(5);" id="headTab5" class="headTab"><span><?php echo TEXT_TAB_FAQ; ?></span></li>
	<?php } ?>
</ul>
<div class="clear"></div>
<div class="top"><div class="right"><div class="bottom"><div class="left"><div class="topRight"><div class="bottomRight"><div class="bottomLeft">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody id="tab1">
	<tr>
		<td class="tab">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="<?php echo ((int)MEDIUM_IMAGE_WIDTH>0?'width:'.(MEDIUM_IMAGE_WIDTH+20).'px;padding-right:5px':'padding-right: 20px');?>" valign="top">
<?php

    if (tep_not_null($product_info['products_image'])) {

      if ($product_info['products_image_med']!='') {
        $new_image = $product_info['products_image_med'];
        $image_width = MEDIUM_IMAGE_WIDTH;
        $image_height = MEDIUM_IMAGE_HEIGHT;
      } else {
        $new_image = $product_info['products_image'];
        $image_width = MEDIUM_IMAGE_WIDTH;
        $image_height = MEDIUM_IMAGE_HEIGHT;
      }

      $image_big = $product_info['products_image_lrg'];
      if (!$image_big || !file_exists(DIR_WS_IMAGES . $image_big))
      $image_big = $product_info['products_image_med'];
      if (!$image_big || !file_exists(DIR_WS_IMAGES . $image_big))
      $image_big = $product_info['products_image'];

      if ($image_big && file_exists(DIR_WS_IMAGES . $image_big))
      {
        list($width, $height) = getimagesize(DIR_WS_IMAGES . $image_big);
        $width += 40;
        $height += 35;
      }
?>
<!--<a href="<?php echo DIR_WS_IMAGES . $image_big; ?>" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_name']); ?>" onclick="window.open('<?php echo addslashes(HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . $image_big); ?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>,screenX=150,screenY=150,top=150,left=150'); return false;"><?php echo tep_image(DIR_WS_IMAGES . $new_image, htmlspecialchars($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"'); ?></a>
-->
<a href="<?php echo DIR_WS_IMAGES . $image_big; ?>" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_name']); ?>">
	<?php echo tep_image(DIR_WS_IMAGES . $new_image, htmlspecialchars($product_info['products_name']), $image_width, $image_height, 'hspace="5" vspace="5"'); ?>
</a>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! //-->

<?php
    } echo ((int)$product_info['vat_exempt_flag']>0?'<a href="' . tep_href_link(FILENAME_INFORMATION, 'info_id=13') . '">' . tep_image(DIR_WS_IMAGES . 'banners/vat_free_banner.gif', TEXT_VAT_EXEMPTION_AVAILABLE) . '</a>':""); //TEXT_VAT_EXEMPTION_UNAVAILABLE
?>

            </td>
            <td width="100%" valign="top">

<?php
    echo stripslashes($product_info['products_description']);
?>

            </td>
          </tr>
        </table>

<table cellspacing="10" cellpadding="0" width="100%" border="0">
<?php

    if ($product_info['products_weight'] > 0 || $product_info['manufacturers_id'] > 0){
      if ($product_info['manufacturers_id']){
        $manufacturers_name_data = tep_db_fetch_array(tep_db_query("select  manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $product_info['manufacturers_id'] . "'"));
      }
?>
  <tr>
    <td>
		<?php echo $product_info['products_weight'] > 0 ?'<span class="bold">' . TEXT_PRODUCTS_WEIGHT . ':</span>&nbsp;' . $product_info['products_weight']  . 'g<br>':'';?><?php echo ($product_info['manufacturers_id']>0?'<span class="bold">' . TEXT_PRODUCTS_MANUFACTURER . ':</span>&nbsp;' . $manufacturers_name_data['manufacturers_name']:'');?><br />
		<? if ($product_info['products_model']) : ?>
			<b>Model:</b> <?=$product_info['products_model'];?>
		<? endif; ?>
	</td>
  </tr>
<?php
    }
?>
<?php
if (tep_not_null($product_info['products_url'])) {
?>
  <tr>
    <td><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
  </tr>
<?php
    }
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>
  <tr>
    <td align="center"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
  </tr>
<?php
    } else if ($product_info['products_date_available'] && $product_info['products_date_available'] != '0000-00-00 00:00:00') {
?>
  <tr>
    <td align="center"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
  </tr>
<?php
    }
?>
</table>


    </td>
	</tr>
	</tbody>
	<?php if ( tep_not_null( $product_info['products_features'] ) ) { ?>
	<tbody id="tab2">
	<tr>
		<td class="tab">
		  <?php echo $product_info['products_features']; ?>
    </td>
	</tr>
	</tbody>
	<?php } ?>
	<?php if ( strlen($property_block)>0 ) { ?>
	<tbody id="tab6">
	<tr>
		<td class="tab">
		  <?php echo $property_block; ?>
    </td>
	</tr>
	</tbody>
	<?php } ?>
	<tbody id="tab3">
	<tr>
		<td class="tab">

<?php
// BOF MaxiDVD: Modified For Ultimate Images Pack!
 if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
?>
    <table width="100%">
        <tr>
<?php
  $counter = 0;
  for ($i=1;$i<7;$i++) {

    $image_big = $product_info['products_image_xl_' . $i];
    if (!$image_big || !file_exists(DIR_WS_IMAGES . $image_big))
    $image_big = $product_info['products_image_sm_' . $i];

    if ($image_big && file_exists(DIR_WS_IMAGES . $image_big))
    {
      list($width, $height) = getimagesize(DIR_WS_IMAGES . $image_big);
      $width += 40;
      $height += 35;
    }

    if (($product_info['products_image_sm_' . $i] != '') && ($product_info['products_image_xl_' . $i] == '')) {
      $counter++;
?>
    <td align="center" class="smallText">
      <?php echo '<table cellspacing="10" cellpadding="0" height="100%" border="0" class="productBG"><tr><td bgColor=#ffffff>' . tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_' . $i], $product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name'], ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT) . '</td></tr></table>'; ?>
    </td>
<?php
    } elseif (($product_info['products_image_sm_' . $i] != '') && ($product_info['products_image_xl_' . $i] != '')) {
      $counter++;
?>
    <td align="center" class="smallText">
      <table cellspacing="10" cellpadding="0" height="100%" border="0" class="productBG"><tr><td bgColor=#ffffff><a href="<?php echo DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]; ?>" target="_blank" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" onclick="window.open('<?php echo addslashes(DIR_WS_CATALOG . DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]); ?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>,screenX=150,screenY=150,top=150,left=150'); return false;"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_' . $i], htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT); ?></a></td></tr></table><a href="<?php echo DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]; ?>" target="_blank" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" onclick="window.open('<?php echo addslashes(DIR_WS_CATALOG . DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]); ?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>,screenX=150,screenY=150,top=150,left=150'); return false;"><?php echo tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle" vspace="5"'); ?></a>
    </td>
<?php
    } elseif (($product_info['products_image_sm_' . $i] == '') && ($product_info['products_image_xl_' . $i] != '')) {
      $counter++;
?>
    <td align="center" class="smallText">
      <table cellspacing="10" cellpadding="0" height="100%" border="0" class="productBG"><tr><td bgColor=#ffffff><a href="<?php echo DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]; ?>" target="_blank" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" onclick="window.open('<?php echo addslashes(DIR_WS_CATALOG . DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]); ?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>,screenX=150,screenY=150,top=150,left=150'); return false;"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image_xl_' . $i], htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']), ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT); ?></a></td></tr></table><a href="<?php echo DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]; ?>" target="_blank" rel="lightbox[plants]" title="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" alt="<?php echo htmlspecialchars($product_info['products_image_alt_' . $i] != '' ? $product_info['products_image_alt_' . $i] : $product_info['products_name']); ?>" onclick="window.open('<?php echo addslashes(DIR_WS_CATALOG . DIR_WS_IMAGES . $product_info['products_image_xl_' . $i]); ?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=<?php echo $width; ?>,height=<?php echo $height; ?>,screenX=150,screenY=150,top=150,left=150'); return false;"><?php echo tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle" vspace="5"'); ?></a>
    </td>
<?php
    }
    if ($counter == 3) {
      echo '</tr><tr>';
    }
  }
?>
        </tr>
      </table>
<?
 } // if (ULTIMATE_ADDITIONAL_IMAGES == 'enable')
 ?>

    </td>
	</tr>
	</tbody>
	<tbody id="tab4">
	<tr>
		<td class="tab">
<?php
// reviews TAB
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 500) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where status and r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";

  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);
  if ($reviews_split->number_of_rows > 0) {
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
     
?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><u><b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</b></u></a>'; ?></td>
                    <td class="smallText" align="right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td valign="top" class="main"><?php echo '<div style="text-align:justify">'.tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br>') . ((strlen($reviews['reviews_text']) >= 400) ? '..' : '') . '</div><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_TEMPLATE_IMAGES . 'reviews/stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?></td>
                        <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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
    if ( $reviews_split->number_of_pages>1 ) {
      echo '<tr><td><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('page')).'page=2') . '">' . tep_template_image_button('button_more_reviews.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_MORE_REVIEWS, 'class="transpng"') . '</a></td></tr>';
    }
    echo '</table>';
  } else {
?>
     <?php echo TEXT_NO_REVIEWS; ?>
<?php
  }
// reviews TAB
?>
     <div style="text-align:right;">
       <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>';?>
     </div>
    </td>
	</tr>
	</tbody>
	<?php if ( tep_not_null( $product_info['products_faq'] ) ) { ?>
	<tbody id="tab5">
	<tr>
		<td class="tab">
		  <?php echo $product_info['products_faq']; ?>
    </td>
	</tr>
	</tbody>
	<?php } ?>


</table>
<script language="javascript" type="text/javascript"><!--
clickOn(1);
//--></script>
</div></div></div></div></div></div></div>
        </td>
      </tr>
      <tr>
        <td>
        <?php echo tep_draw_separator('pixel_trans.gif',1,10); ?>

<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>
          <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td colspan="2"><b><?php echo TEXT_PRODUCT_OPTIONS; ?></b></td>
            </tr>
<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pa.products_attributes_id, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options['options_values_price'] = tep_get_options_values_price($products_options['products_attributes_id']);
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }

        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
?>
            <tr>
              <td class="main"><?php echo htmlspecialchars($products_options_name['products_options_name']) . ':'; ?></td>
              <td class="main"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?></td>
            </tr>
<?php
      }
?>
          </table>
<?php
    }
    
?>
        </td>
      </tr>
<?php
if (PRODUCTS_BUNDLE_SETS == 'True') {
  $bundle_sets_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_tax_class_id, sp.num_product, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . $languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', "  . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.product_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and sp.sets_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
  if (tep_db_num_rows($bundle_sets_query) > 0)
  {
    $list_box_contents = array();
    $list_box_contents[0][] = array('align' => 'left',
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;');
    $list_box_contents[0][] = array('align' => 'left',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TEXT_BUNDLE_PRODUCTS);
    $list_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . TEXT_QTY . '&nbsp;');
    $list_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TEXT_REGULAR_PRICE);
?>
      <tr>
        <td class="main">
<?php
    $rows = 0;
    while ($bundle_sets = tep_db_fetch_array($bundle_sets_query))
    {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      if (($bnd_new_price = tep_get_products_special_price($bundle_sets['products_id'], $bundle_sets['num_product']))) {
        $bnd_products_price = '<span class="product-price"><s class="productPriceOld">' .  $currencies->display_price($bundle_sets['num_product'] * tep_get_products_price($bundle_sets['products_id'], $bundle_sets['num_product']), tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</s><br><span class="productPriceSpecial">' . $currencies->display_price($bundle_sets['num_product'] * $bnd_new_price, tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</span></span>';
      } else {
        $bnd_products_price = '<span class="product-price">' . $currencies->display_price($bundle_sets['num_product'] * tep_get_products_price($bundle_sets['products_id'], $bundle_sets['num_product']), tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</span>';
      }

      $attributes_selection = '';
      $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$bundle_sets['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
      $products_attributes = tep_db_fetch_array($products_attributes_query);
      if ($products_attributes['total'] > 0)
      {
        $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$bundle_sets['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
        while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
          $products_options_array = array();
          $products_options_query = tep_db_query("select pa.products_attributes_id, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$bundle_sets['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
          while ($products_options = tep_db_fetch_array($products_options_query)) {
            $products_options['options_values_price'] = tep_get_options_values_price($products_options['products_attributes_id']);
            $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);

            if ($products_options['options_values_price'] != '0') {
              $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
            }
          }
          if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id'] . '-' . $bundle_sets['products_id']])) {
            $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id'] . '-' . $bundle_sets['products_id']];
          } else {
            $selected_attribute = false;
          }
          $attributes_selection .= '<br>' . tep_draw_separator('pixel_trans.gif', '1', '2') . '<br>' . $products_options_name['products_options_name'] . ':&nbsp;' . tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . '-' . $bundle_sets['products_id'] . ']', $products_options_array, $selected_attribute, 'id="id[' . $products_options_name['products_options_id'] . '-' . $bundle_sets['products_id'] . ']"');
        }
      }

      $cpath = tep_get_product_path($bundle_sets['products_id']);
      $list_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'width="' . (SMALL_IMAGE_WIDTH + 5) . '" class="productListing-data"',
                                             'text'  => (tep_not_null($cpath) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bundle_sets['products_id']) . '">' : '') . tep_image(DIR_WS_IMAGES . $bundle_sets['products_image'], $bundle_sets['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . (tep_not_null($cpath) ? '</a>' : ''));
      $list_box_contents[$cur_row][] = array('align' => 'left',
                                             'params' => 'class="productListing-data"',
                                             'text'  => (tep_not_null($cpath) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bundle_sets['products_id']) . '">' : '') .  $bundle_sets['products_name'] . (tep_not_null($cpath) ? '</a>' : '') . $attributes_selection);
      $list_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data"',
                                             'text'  => '<b>' . $bundle_sets['num_product'] . '</b>');
      $list_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data"',
                                             'text'  => $bnd_products_price);
    }
    new productListingBox($list_box_contents);
?>
        </td>
      </tr>
<?php
  } // end if (tep_db_num_rows($bundle_sets_query) > 0)
} // end if (PRODUCTS_BUNDLE_SETS == 'True')
?>

<?php
/*
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and status");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
?>
      <tr>
        <td><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
<?php
    }

*/
?>
	<? if ($product_check['products_status'] == "1") : ?>
	<!-- Start: Price Box -->
      <tr>	  
        <td class="priceBox">
			<?php
			  $wishlist_id_query = tep_db_query('select products_id as wPID from ' . TABLE_WISHLIST . ' where products_id= ' . $product_info['products_id'] . ' and customers_id = ' . (int)$customer_id . ' order by products_name');
			  $wishlist_Pid = tep_db_fetch_array($wishlist_id_query);
			  $wishlist = '';
			  if ( (!tep_not_null($wishlist_Pid[wPID])) && (tep_session_is_registered('customer_id')) ) {
				$wishlist = '<td>' . tep_template_image_submit('button_add_wishlist.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_ADD_WISHLIST, 'class="transpng" name="add_to_whishlist"') . '</td>';
			  }
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="productPrice"><?php echo TEXT_PRICE . ': ' . $products_price_excl_vat . ' ' . TEXT_EXCL_VAT . '<br><font style="font-size:13px;"><b>' . $products_price . ' (' . TEXT_INCL_VAT . ')</b></font>'; //echo TEXT_PRICE . ': ' . $products_price?></td>
				<td align="right" width="150"><?php echo tep_draw_hidden_field('products_id', $product_info['products_id'])  . '<table cellspacing="0" cellpadding="1" border="0"><tr><td>' . TEXT_PRODUCTS_QUANTITY .  '</td><td>' . tep_draw_input_field('qty', isset($HTTP_GET_VARS['qty'])?$HTTP_GET_VARS['qty']:1, 'style="WIDTH:35px;text-align:center;" class="inp"')  . '</td><td>
				
				
				<table cellspacing="0" cellpadding="0" border="0">
				 <tr>
				  <td>' . ($product_info['vat_exempt_flag'] > 0?tep_draw_pull_down_menu('vat_exemption', $vat_exemption_array):'') . '</td><td>&nbsp;</td>
				  <td>' . tep_template_image_submit('button_in_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_IN_CART, 'class="transpng" align="absmiddle"') . '</td>
				 </tr>
				</table>
				
				</td>' . $wishlist . '</tr></table>';
				//' . tep_template_image_submit('button_in_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_IN_CART, 'class="transpng" align="absmiddle"') . '?></td>
			  </tr>
			<tr>
				<td>
					<? // Display Free Shipping message if Item is over the threshold (£50.00) - Musaffar Patel?>
					<? if ($products_priceOnly > MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) : ?>
						<div class="panel-error">
							Free UK Shipping on this item
						</div>
					<? endif; ?>
				</td>
			</tr>
			</table>
        </td>		
      </tr>
	  <!-- End: Price Box -->
	  <? endif; ?>
	  
<?php
if (PRODUCTS_BUNDLE_SETS == 'True') {
  $bundle_sets_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . $languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', "  . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SETS_PRODUCTS . " sp where sp.sets_id = p.products_id and sp.sets_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and sp.product_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
  if (tep_db_num_rows($bundle_sets_query) > 0)
  {
    $list_box_contents = array();
    $list_box_contents[0][] = array('align' => 'left',
                                    'params' => 'height="27" class="productListing-heading"',
                                    'text' => '&nbsp;');
    $list_box_contents[0][] = array('align' => 'left',
                                    'params' => 'height="27" class="productListing-heading"',
                                    'text' => '&nbsp;' . TEXT_ALSO_AVAILABLE_IN_SETS . '&nbsp;');
    $list_box_contents[0][] = array('align' => 'center',
                                    'params' => 'height="27" class="productListing-heading"',
                                    'text' => '&nbsp;' . TEXT_PRICE . '&nbsp;');
?>
      <tr>
        <td class="main">
<?php
    $rows = 0;
    while ($bundle_sets = tep_db_fetch_array($bundle_sets_query))
    {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      if (($new_price = tep_get_products_special_price($bundle_sets['products_id']))) {
        $bnd_products_price = '<span class="product-price"><s class="productPriceOld">' .  $currencies->display_price(tep_get_products_price($bundle_sets['products_id']), tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</s><br><span class="productPriceSpecial">' . $currencies->display_price($new_price, tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</span></span>';
      } else {
        $bnd_products_price = '<span class="product-price">' . $currencies->display_price(tep_get_products_price($bundle_sets['products_id']), tep_get_tax_rate($bundle_sets['products_tax_class_id'])) . '</span>';
      }

      $list_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'width="' . (SMALL_IMAGE_WIDTH + 5) . '" class="productListing-data"',
                                             'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bundle_sets['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $bundle_sets['products_image'], $bundle_sets['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>');
      $list_box_contents[$cur_row][] = array('align' => 'left',
                                             'params' => 'class="productListing-data"',
                                             'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $bundle_sets['products_id']) . '">' .  $bundle_sets['products_name'] . '</a>');
      $list_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'width="65" class="productListing-data"',
                                             'text'  => $bnd_products_price);
    }
    new productListingBox($list_box_contents);
?>
        </td>
      </tr>
<?php
  } // end if (tep_db_num_rows($bundle_sets_query) > 0)
} // end if (PRODUCTS_BUNDLE_SETS == 'True')
?>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>      <tr>
        <td>
<?php

   if ( (USE_CACHE == 'true') && !SID) { 
     echo tep_cache_also_purchased(3600); 
     if (SUPPLEMENT_STATUS == 'True'){
       include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
     }else{
        include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
     }
   } else { 
       if (SUPPLEMENT_STATUS == 'True'){
         include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
       }else{
          include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
       }
      echo tep_draw_separator('pixel_trans.gif', '100%', '10');
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
   }
?>
        </td>
      </tr>
    </table>
</form>
<link rel="stylesheet" type="text/css" href="/java/plugins/prettyphoto/css/prettyPhoto.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script src="/java/plugins/prettyphoto/js/jquery.prettyPhoto.js"></script>
<style>
	.pp_expand { display:none !important; }
</style>
<script>
	$(document).ready(function() {
		$("a[rel^='lightbox']").prettyPhoto({
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 400,
			horizontal_padding: 20,
			deeplinking:false,
			social_tools:''			
		});
		
		$("input#vat-exempt").change(function() {
			if ($(this).is(":checked")) $("select[name='vat_exemption1']").val("1");
			else $("select[name='vat_exemption1']").val("0");
		});
	});
</script>




