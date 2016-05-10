<?php
/*
$Id: xsell_cat_products.php, v1  2002/09/11

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

$xsell_products = array();
if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
  $xsell_cat_products_query = tep_db_query("select xp.xsell_products_id from " . TABLE_CATS_PRODUCTS_XSELL . " xp  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on xp.xsell_products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on xp.xsell_products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where categories_id = '" . (int)$current_category_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) order by sort_order asc");
}else{
  $xsell_cat_products_query = tep_db_query("select p.xsell_products_id from " . TABLE_CATS_PRODUCTS_XSELL . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.xsell_products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.categories_id = '" . (int)$current_category_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.sort_order asc");
}
while ($xsell_cat_products = tep_db_fetch_array($xsell_cat_products_query)) {
  if (!in_array($xsell_cat_products['xsell_products_id'], $xsell_products)) {
    $xsell_products[] = $xsell_cat_products['xsell_products_id'];
  }
}

$products_name_list = array();
$products_full_name_list = array();
$products_model_list = array();
$products_price_list = array();
if (count($xsell_products) > 0) {
?> 
<!-- xsell_cat_products //-->
<?php
echo '      <tr><td>' . "\n";
$info_box_contents_heading = array();
$info_box_contents_heading[] = array('align' => 'left', 'text' => TEXT_WE_ALSO_RECOMMEND);
new contentBoxHeading($info_box_contents_heading, false, false);

$info_box_contents = array();

$row = 0;
$col = 0;
foreach ($xsell_products as $final_pid) {
  $product_info_query = tep_db_query("select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_model, p.products_image, p.products_price, p.products_tax_class_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.products_status = 1 and p.products_id = '" . (int)$final_pid . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.affiliate_id = 0 and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (tep_db_num_rows($product_info_query)) {
    $product_info = tep_db_fetch_array($product_info_query);
    $info_box_contents[$row][$col] = array(
    'params' => 'class="productColumnSell" width="33%"',
    'text' => tep_output_product_table_sell($product_info, true));

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

}
if (sizeof($info_box_contents)){
  new contentBox($info_box_contents);
}

echo '        </td>' . "\n";
echo '      </tr>' . "\n";
?>
<!-- xsell_cat_products_eof //-->
<?php
}
?>