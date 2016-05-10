<?php
/*
$Id: product_reviews_info.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

	require('includes/application_top.php');

	include_once('controllers/front/ProductReviewsController.php');
	$controller = new ProductReviewsController();
	$canonical_tag = $controller->get_canonical_tag($_GET['products_id'], $_GET['reviews_id']);


if (isset($HTTP_GET_VARS['reviews_id']) && tep_not_null($HTTP_GET_VARS['reviews_id']) && isset($HTTP_GET_VARS['products_id']) && tep_not_null($HTTP_GET_VARS['products_id'])) {
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_REVIEWS . " r  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on r.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on r.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
  }else{
    $review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_REVIEWS . " r " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on r.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' and r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
  }
  $review_check = tep_db_fetch_array($review_check_query);

  if ($review_check['total'] < 1) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
  }
} else {
  tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))));
}

tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "'");

$review_query = tep_db_query("select rd.reviews_text, r.reviews_rating, r.reviews_id, r.customers_name, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where r.status = 1 and r.reviews_id = '" . (int)$HTTP_GET_VARS['reviews_id'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.products_id = p.products_id and pd.affiliate_id = 0 and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = '". (int)$languages_id . "'");
$review_content = tep_db_fetch_array($review_query);

if ($new_price = tep_get_products_special_price($review_content['products_id'])) {
  $products_price = '<span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($review_content['products_id'], 1, $review_content['products_price']), tep_get_tax_rate($review_content['products_tax_class_id'])) . '</span> <span class="productPriceSpecial">' . $currencies->display_price($new_price, tep_get_tax_rate($review_content['products_tax_class_id'])) . '</span>';
} else {
  $products_price = '<span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($review_content['products_id'], 1, $review_content['products_price']), tep_get_tax_rate($review_content['products_tax_class_id'])) . '</span>';
}

if (tep_not_null($review_content['products_model'])) {
  $products_name = $review_content['products_name'] . '&nbsp;&nbsp;<span class="smallText">[' . $review_content['products_model'] . ']</span>';
} else {
  $products_name = $review_content['products_name'];
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

$content = CONTENT_PRODUCT_REVIEWS_INFO;
$javascript = 'popup_window.js';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
