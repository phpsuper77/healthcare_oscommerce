<?php
/*
  $Id: best_sellers.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($current_category_id) && ($current_category_id > 0)) {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $best_sellers_query = tep_db_query("select distinct p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, pd.products_description_short, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "'  and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) and c.categories_status = 1 and pd.affiliate_id =0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.products_ordered desc, products_name limit " . MAX_DISPLAY_BESTSELLERS);
    }else{
      $best_sellers_query = tep_db_query("select distinct p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, pd.products_description_short, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . $languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) and c.categories_status = 1 and pd.affiliate_id =0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.products_ordered desc, products_name limit " . MAX_DISPLAY_BESTSELLERS);
    }
  } else {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $best_sellers_query = tep_db_query("select distinct p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, pd.products_description_short, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 and  if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'  and pd.affiliate_id =0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  order by p.products_ordered desc, products_name limit " . MAX_DISPLAY_BESTSELLERS);
    }else{
      $best_sellers_query = tep_db_query("select distinct p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, pd.products_description_short, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'  and pd.affiliate_id =0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " order by p.products_ordered desc, products_name limit " . MAX_DISPLAY_BESTSELLERS);
    }
  }

  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>
<!-- best_sellers //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_BESTSELLERS);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }    

  }
  $rows=0;
  $info_box_contents = array();    
  while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
    $rows++;
    $special_price = tep_get_products_special_price($best_sellers['products_id']);

    $description = str_replace('&nbsp;',' ',$best_sellers['products_description_short']);
    $length_description = 30;
    if (strlen($description)>$length_description) {
      if (strpos($description, ' ', $length_description)){
        $description = substr ($description, 0, strpos($description, ' ', $length_description));
        if (substr($description, -1) != '.') $description .= '...';
      }
    }
    

    $info_box_contents[] = array('text' => '
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr valign="top">
    <td class="bestsellersImg" valign="top">' . tep_image(DIR_WS_IMAGES . $best_sellers['products_image'], $best_sellers['products_name'], 50, SMALL_IMAGE_HEIGHT) . '</td>
    <td>
      <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a><br>
      ' . $description . '<br>
      ' . ($special_price?'
      <span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($best_sellers['products_id'], 1, $best_sellers['products_price']), tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span><br><span class="productPriceSpecial">' . $currencies->display_price($special_price, tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span>
      ':'
      <span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($best_sellers['products_id'], 1, $best_sellers['products_price']), tep_get_tax_rate($best_sellers['products_tax_class_id'])) . '</span>
      ') . '
    </td>
  </tr>
</table>
', 'params' => 'class="bestsellers' . ($rows==1?' bestsellersFirst':'') . '"');
  }

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- best_sellers_eof //-->
<?php
  }
?>
