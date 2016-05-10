<?php
/*
  $Id: whats_new.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $random_product = tep_random_select("select p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);
  }else{
    $random_product = tep_random_select("select p.products_id, p.products_image, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . "  p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and pd.affiliate_id = 0  order by p.products_date_added desc limit " . MAX_RANDOM_SELECT_NEW);
  }
  if ($random_product) {
?>
<!-- whats_new //-->
          <tr>
            <td class="infoBoxCell">
<?php

  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents, tep_href_link(FILENAME_PRODUCTS_NEW));
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_WHATS_NEW);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));
    }else{
      new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));
    }    
  }

    $info_box_contents = array();
    $info_box_contents[] = array( 'params' => 'class="productColumnSell"',
                                  'text' => tep_output_product_table_sell($random_product));

    if (class_exists($infobox_class)){
      new $infobox_class($info_box_contents);
    }else{
      new infoBox($info_box_contents);
    }
?>
            </td>
          </tr>
<!-- whats_new_eof //-->
<?php
  }
?>
