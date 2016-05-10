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
 if(FEATURED_PRODUCTS_DISPLAY == true)
 {
  $featured_products_category_id = $current_category_id;
  $info_box_contents = array();

  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    $add_sql = " and (f.affiliate_id = '" . $affiliate_ref . "' or f.affiliate_id = 0) ";
    if ($HTTP_SESSION_VARS['affiliate_ref'] > 0){
      $add_from = " LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ";
      $add_sql .= " and p2a.affiliate_id is not null ";
    }
  }else{
    $add_sql = " and f.affiliate_id = 0 ";
  }    
/*   
  $featured_products_category_id = $new_products_category_id;
  $cat_name_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = cd.categories_id and cd1.language_id='" . $languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where cd.categories_id = '" . $featured_products_category_id . "' and cd.affiliate_id = 0 limit 1");
  $cat_name_fetch = tep_db_fetch_array($cat_name_query);
  $cat_name = $cat_name_fetch['categories_name'];
  $info_box_contents = array();

  if (tep_session_is_registered('affiliate_ref')&& $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    $add_sql = " and f.affiliate_id = '" . $affiliate_ref . "' ";
  }
*/  
  if ( (!isset($featured_products_category_id)) || ($featured_products_category_id == '0') ) {
    $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $featured_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . $customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  where p.products_status = 1 and f.status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) " . $add_sql . " order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
    }else{
      $featured_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = 1 and f.status = '1' " . $add_sql . " order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
    }

  } else {
    $cat_name = tep_get_categories_name($featured_products_category_id);    
    $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY, $cat_name));
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $featured_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_FEATURED . " f on p.products_id = f.products_id  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . $customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' and pp.products_group_price != -1 where p.products_id = p2c.products_id and pp.products_group_price is not null and p2c.categories_id = c.categories_id and c.parent_id = '" . $featured_products_category_id . "' and p.products_status = 1 and f.status = 1 and c.categories_status = 1 " . $add_sql . " order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
    }else{
      $featured_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $featured_products_category_id . "' and p.products_status = 1 and f.status = 1 and c.categories_status = 1 " . $add_sql . " order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
    }

  }

  $row = 0;
  $col = 0; 
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $num ++; if ($num == 1) { new contentBoxHeading($info_box_contents, true, true, tep_href_link(FILENAME_FEATURED_PRODUCTS));}
    $featured_products['products_name'] = tep_get_products_name($featured_products['products_id']);
    $s_price = tep_get_products_special_price($featured_products['products_id']);
    if($s_price !== false) {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br><s class="productPriceOld">' . $currencies->display_price(tep_get_products_price($featured_products['products_id']), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s><br><span class="productPriceSpecial">' . 
                                           $currencies->display_price(tep_get_products_special_price($featured_products['products_id']), tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</span>');
    } else {
      $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br>' . $currencies->display_price(tep_get_products_price($featured_products['products_id'], 1, $featured_products['products_price']), tep_get_tax_rate($featured_products['products_tax_class_id'])));
    }    
    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  if($num) {
      
      new contentBox($info_box_contents);
if (MAIN_TABLE_BORDER == 'yes'){
$info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents, true, true);
}
  }
 } else // If it's disabled, then include the original New Products box
 {
   include (DIR_WS_MODULES . FILENAME_NEW_PRODUCTS);
 }
?>
<!-- featured_products_eof //-->
