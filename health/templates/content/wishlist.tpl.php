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
  $infobox_contents[] = array(array('text' => HEADING_TITLE), array('params'=> 'align=right', 'text' => (is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . 'table_background_wishlist.gif')?tep_image(DIR_WS_IMAGES . 'table_background_wishlist.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT):'')));
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
  $wishlist_array = array();
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $wishlist_query_raw = "select w.* from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  where w.products_id = p.products_id and p.products_status = 1 and customers_id= '" . (int)$customer_id . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) order by products_name";
  }else{
    $wishlist_query_raw = "select w.* from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where w.products_id = p.products_id and p.products_status = 1 and customers_id= '" . (int)$customer_id . "'  order by products_name";
  }
  
  $wishlist_split = new splitPageResults($wishlist_query_raw, MAX_DISPLAY_WISHLIST_PRODUCTS);
  if (($wishlist_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {

?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
<!-- customer_wishlist //-->
<?php
  if ($wishlist_split->number_of_rows){
  $wishlist_query = tep_db_query($wishlist_split->sql_query);

    $info_box_contents = array();
  if (tep_db_num_rows($wishlist_query)) {
?>
      <tr>
        <td>
<?php
//ob_start();
?>        
            
<?php

    $product_ids = '';
    while ($wishlist = tep_db_fetch_array($wishlist_query)) {
	      $product_ids .= $wishlist['products_id'] . ',';
    }
    $product_ids = substr($product_ids, 0, -1);
?>

<?php
    $products_query = tep_db_query("select pd.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name , if(length(pd1.products_description), pd1.products_description, pd.products_description) as products_description, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_id in (" . $product_ids . ") and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 order by products_name");

    $row = 0;
    $list_box_contents[$row] = array('params' => 'class="productFirstRow"');
    $column = 0;
    while ($products = tep_db_fetch_array($products_query)) {
	    if ($new_price = tep_get_products_special_price($products['products_id'])) {
        $products_price = '<span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($products['products_id'], 1, $products['products_price']), tep_get_tax_rate($products['products_tax_class_id'])) . '</span> <span class="productPriceSpecial">' . $currencies->display_price($new_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
      } else {
        $products_price = '<span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($products['products_id'], 1, $products['products_price']), tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
      }
      
      
      $lc_text = '
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="productTable">
          <tr>
            <td class="productNameCell paddingLR"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $products['products_id'], 'NONSSL') . '">' . $products['products_name'] . '</a></td>
          </tr>
          <tr>
            <td class="productImageCell paddingLR"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $products['products_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>
          </tr>
          <tr>
            <td class="productPriceCell paddingLR">' . TEXT_PRICE_WHISHLIST . '&nbsp;' . $products_price . '</td>
          </tr>
          <tr>
            <td class="productButtonCell paddingLR"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $products['products_id'] . '&rfw=1', 'NONSSL') . '">' . TEXT_MOVE_TO_CART_WISHLIST . '</a>&nbsp;|&nbsp;<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=remove_wishlist&pid=' . $products['products_id'], 'NONSSL') . '">' . TEXT_DELETE_WISHLIST . '</a></td>
          </tr>
        </table>
          ';
          
      $list_box_contents[$row][$column] = array('params' => 'class="productColumnSell'.($column<1?' first':'').($column==LISTING_NUM_PRODUCTS_PER_ROW-1?' last':'').'"',
                                                'text'  => $lc_text);
                                                
      $column ++;
      if ($column >= LISTING_NUM_PRODUCTS_PER_ROW) {
        $row++;
        $list_box_contents[$row] = array('params' => ($row%2==0?'class="productEvenRow"':'class="productOddRow"'));
        $column = 0;
      }
    }
    while ($column>0 && $column < LISTING_NUM_PRODUCTS_PER_ROW){
      $list_box_contents[$row][$column] = array('params' => 'class="productColumnSell'.($column<1?' first':'').'" height=100%',
                                                'text'  => '<table border="0" cellpadding="0" cellspacing="0" class="productTable" width="100%"><tr><td height=100%>&nbsp;</td></tr></table>');
      $column++;
    } 
    
    new tableBox($list_box_contents, true)
?>
	      </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (($wishlist_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {

?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="smallText"><?php echo $wishlist_split->display_count(TEXT_DISPLAY_NUMBER_OF_WISHLIST); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
  }

  }
	} else { // Nothing in the customers wishlist
?>

      <tr>
        <td style="padding:20px;" align="center">
          <?php echo TEXT_NO_PRODUCTS_IN_WISHLIST; ?>
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
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="right" class="main" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?> 
        
        </td>
      </tr>

<?php
	}
?>

<!-- customer_wishlist_eof //-->
		</td>
      </tr>
    </table>