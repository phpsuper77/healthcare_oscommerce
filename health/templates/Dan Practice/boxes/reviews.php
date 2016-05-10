<?php
/*
  $Id: reviews.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reviews //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents, tep_href_link(FILENAME_REVIEWS));
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_REVIEWS);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false, tep_href_link(FILENAME_REVIEWS));
    }else{
      new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_REVIEWS));
    }
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, substring(rd.reviews_text, 1, 60) as reviews_text from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where status and p.products_status = 1 and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 and p.products_id = pd.products_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and pd.language_id = '" . (int)$languages_id . "'";
  }else{
    $random_select = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, substring(rd.reviews_text, 1, 60) as reviews_text from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where status and p.products_status = 1 and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id = pd.products_id and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "'";
  }
  if (isset($HTTP_GET_VARS['products_id'])) {
    $random_select .= " and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'";
  }
  $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
  $random_product = tep_random_select($random_select);

  $info_box_contents = array();

  if ($random_product) {
// display random review box
    $info_box_contents[] = array('params' => 'align="center"', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&amp;reviews_id=' . $random_product['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>');
    $info_box_contents[] = array('params' => 'align="center"', 'text' => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&amp;reviews_id=' . $random_product['reviews_id']) . '">' . tep_break_string(tep_output_string_protected($random_product['reviews_text']), 15, '-<br>') . ' ..</a>' );
    $info_box_contents[] = array('params' => 'align="center"', 'text' => tep_draw_separator('pixel_trans.gif',1,5) );
    $info_box_contents[] = array('params' => 'align="center"', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'reviews/stars_' . $random_product['reviews_rating'] . '.png' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating']), '', '', 'class="transpng"') );

  } elseif (isset($HTTP_GET_VARS['products_id']) && tep_check_product((int)$HTTP_GET_VARS['products_id'])) {
// display 'write a review' box
    $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td><a class="infoBoxLink" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icons/reviews.png', IMAGE_BUTTON_WRITE_REVIEW, '', '', 'class="transpng"') . '</a></td><td ><a class="infoBoxLink" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $HTTP_GET_VARS['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a></td></tr></table>');
  } else {
    $info_box_contents[] = array('text' => BOX_REVIEWS_NO_REVIEWS, 'params' => 'align="center"');
  }

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- reviews_eof //-->