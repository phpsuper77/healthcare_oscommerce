    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
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
}else{
$header_text =  HEADING_TITLE;
}
?>

<?php
  $products_new_array = array();

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $listing_sql = "select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id) where p.products_status = 1 and pd.affiliate_id = 0 and p.products_id = pd.products_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, products_name";
  }else{
    $listing_sql = "select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . $languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id) where p.products_status = 1 and pd.affiliate_id = 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 order by p.products_date_added DESC, pd.products_name";
  }

unset($listing_split);
unset($product_listing);

  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         //'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         //'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION);
                         asort($define_list);

    $column_list = array();
    reset($define_list);
    foreach ($define_list as $key => $value) {
      if ($value > 0) $column_list[] = $key;
    }
?>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">
            <tr>
	            <td>
<?php 
		$TEXT_DISPLAY_NUMBER_OF_ITEM = TEXT_DISPLAY_NUMBER_OF_FEATURED;
		
		if (PRODUCT_LISTING_MODE == 'Column'){
          include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_COL); 
        }else{
          include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); 
        }
?>
            	
	            </td>
      		</tr>
        </table></td>
      </tr>
        </table></td>
      </tr>
    </table>

