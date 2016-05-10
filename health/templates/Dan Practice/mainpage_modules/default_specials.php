<?php
/*
$Id: default_specials.php,v 2.0 2003/06/13

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
?>
<!-- default_specials //-->
<?php
if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
  $listing_sql = "select p.products_id, p.products_price, p.products_weight, p.products_quantity, p.products_model,p.products_tax_class_id, p.products_image, s.specials_new_products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "', " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . (int)$customer_groups_id . "' and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ) and s.products_id = p.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and s.status = 1 and p.products_id = pd.products_id and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by s.specials_date_added DESC";
}else{
  $listing_sql = "select p.products_id, p.products_price, p.products_weight, p.products_quantity, p.products_model, p.products_tax_class_id, p.products_image, s.specials_new_products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.products_status = 1 and s.products_id = p.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and s.status = 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0 order by s.specials_date_added DESC";
}

unset($listing_split);
unset($product_listing);

$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
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
    
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SPECIAL_PRODUCTS, 'p.products_id');
  $product_listing = new product_listing();
  
  $product_listing->listing_split=$listing_split;

  if($listing_split->number_of_rows > 0) { 
  	
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));
  new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));

  $list_box_contents = $product_listing->process_col();
  
  new contentBox($list_box_contents);
  
  if (MAIN_TABLE_BORDER == 'yes'){
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
  );
  new infoboxFooter($info_box_contents, true, true);
  }
  
  }
 
?>
<!-- default_specials_eof //-->