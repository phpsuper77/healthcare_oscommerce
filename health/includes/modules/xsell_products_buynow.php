<?php
/*
$Id: xsell_products.php, v1  2002/09/11

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/
if ($HTTP_GET_VARS['products_id']) {
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $listing_sql = "select distinct p.products_id, p.products_image, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" left join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where xp.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and xp.xsell_id = p.products_id   " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_status = 1 and pd.affiliate_id = 0 order by rand() asc ";
  }else{
    $listing_sql = "select distinct p.products_id, p.products_image, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where xp.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id   " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.language_id = '" . (int)$languages_id . "' and p.products_status = 1 and pd.affiliate_id = 0 order by rand() asc ";
  }
?> 
<!-- xsell_products //-->
<?php
$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION);
                         
asort($define_list);

    $column_list = array();
    reset($define_list);
    foreach ($define_list as $key => $value) {
      if ($value > 0) $column_list[] = $key;
    }
    
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_ALSO_PURCHASED, 'p.products_id');
  $product_listing = new product_listing();
  
  $product_listing->listing_split=$listing_split;


if ($listing_split->number_of_rows > 0){

$info_box_contents = array();
$info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
new contentBoxHeading($info_box_contents, true, true);

    $product_listing->setViewMode(true);
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

}
?>
<!-- xsell_products_eof //-->