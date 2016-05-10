<?php
/*
  $Id: whats_new.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002-2003 osCommerce
  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Author : Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Released under the GNU General Public License
*/
if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
  $random_product = tep_random_select("select p.products_id, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status=1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) order by p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);
}else{
  $random_product = tep_random_select("select products_id, products_image, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where products_status= 1 order by products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);
}
  
if ($random_product)
{
  $random_product['products_name'] = tep_get_products_name($random_product['products_id']);
  $random_product['specials_new_products_price'] = tep_get_products_special_price($random_product['products_id']);
    

  $whats_new_price =  $currencies->display_price(tep_get_products_price($random_product['products_id'], 1, $random_product['products_price']), tep_get_tax_rate($random_product['products_tax_class_id']));
  

  if ($random_product['specials_new_products_price'])
    {

      $whats_new_price_special = $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id']));
    }

}

print '    <item>' . "\n";

print '      <title>' . htmlspecialchars($random_product['products_name']) . '</title>'. "\n";

print '      <price>' . $whats_new_price . '</price>'. "\n";

if ($random_product['specials_new_products_price'])
{
  print '      <specialprice>' . $whats_new_price_special . '</specialprice>' . "\n";
}

print '      <link>' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id'], NONSSL) . '</link>' . "\n";

print '      <img>' . HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES . $random_product['products_image'] . '</img>' . "\n";
			  

print '    </item>' . "\n";

?>