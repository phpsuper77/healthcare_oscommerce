<?php
/*
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License

Featured Products V1.1
Displays a list of featured products, selected from admin
For use as an Infobox instead of the "New Products" Infobox
*/
?>

<!-- featured_products //-->
<?php
if(FEATURED_PRODUCTS_DISPLAY == true) {
  $featured_products_category_id = $current_category_id;
  $info_box_contents = array();
  $add_from = '';
  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    $add_sql = " and (f.affiliate_id = '" . $affiliate_ref . "' or f.affiliate_id = 0) ";
    if ($HTTP_SESSION_VARS['affiliate_ref'] > 0){
      $add_from = " LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ";
      $add_sql .= " and p2a.affiliate_id is not null ";
    }
  }else{
    $add_sql = " and f.affiliate_id = 0 ";
  }

  if ( (!isset($featured_products_category_id)) || ($featured_products_category_id == '0') ) {
    $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $listing_sql = "select p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $add_from . "  where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and f.status = '1' " . $add_sql . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by rand() DESC";
    }else{
      $listing_sql = "select p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id " . $add_from . " where p.products_status = 1 and f.status = 1 " . $add_sql . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by rand() DESC";
    }
  } else {
    $cat_name = tep_get_categories_name($featured_products_category_id);
    $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY, $cat_name));
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $listing_sql = "select distinct p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $add_from . " where p.products_id = p2c.products_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$featured_products_category_id . "' and p.products_status = 1 and f.status = 1 and c.categories_status = 1 " . $add_sql . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by rand() DESC";
    }else{
      $listing_sql = "select distinct p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id " . $add_from . "  where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$featured_products_category_id . "' and p.products_status = 1 and f.status = 1 and c.categories_status = 1 " . $add_sql . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by rand() DESC";
    }
  }
  
unset($listing_split);
unset($product_listing);

$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         //'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         //'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION);
    asort($define_list);

    $column_list = array();
    reset($define_list);
    foreach ($define_list as $key => $value) {
      if ($value > 0) $column_list[] = $key;
    }

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_FEATURED_PRODUCTS, 'p.products_id');
  $product_listing = new product_listing();
  
  $product_listing->listing_split=$listing_split;


if ($listing_split->number_of_rows > 0){

    //new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_FEATURED_PRODUCTS));

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
<!-- featured_products_eof //-->
