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
  $new = tep_db_query("select p.products_id, p.products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "', " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . (int)$customer_groups_id . "' and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ) and s.products_id = p.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and s.status = 1 and p.products_id = pd.products_id and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by s.specials_date_added DESC limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
}else{
  $new = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " , " . TABLE_SPECIALS . " s where p.products_status = 1 and s.products_id = p.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and s.status = 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0 order by s.specials_date_added DESC limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
}
if (tep_db_num_rows($new)>0) {
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));

  new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_SPECIALS));
      
   $info_box_contents = array();
    $row = 0;
    $col = 0;
    while ($default_specials = tep_db_fetch_array($new)) {
      //$default_specials['products_name'] = tep_get_products_name($default_specials['products_id']);
      $info_box_contents[$row][$col] = array('align' => 'center',
                                             'params' => 'class="smallText" width="33%" valign="top" height=100%',
                                             'text' => '<table border="0" cellpadding="0" cellspacing="0" height="100%"><tr><td align="center" height="100%" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $default_specials['products_image'], $default_specials['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr><tr><td valign="bottom" class="smallText" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials['products_id']) . '">' . $default_specials['products_name'] . '</a></td></tr><tr><td valign="bottom" class="smallText" align="center"><s class="productPriceOld">' . $currencies->display_price(tep_get_products_price($default_specials['products_id']), tep_get_tax_rate($default_specials['products_tax_class_id'])) . '</s></td></tr></td></tr><tr><td valign="bottom" class="smallText" align="center"><span class="productPriceSpecial">' . $currencies->display_price(tep_get_products_special_price($default_specials['products_id']), tep_get_tax_rate($default_specials['products_tax_class_id'])) . '</span></td></tr></table>');
      $col ++;
      if ($col > 2) {
        $col = 0;
        $row ++;
      }
    }
    new contentBox($info_box_contents);
}

  if (MAIN_TABLE_BORDER == 'yes'){
  $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
    new infoboxFooter($info_box_contents, true, true);
  }
?>

<!-- default_specials_eof //-->
