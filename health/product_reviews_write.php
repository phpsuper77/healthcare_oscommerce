<?php
/*
$Id: product_reviews_write.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

/*if (!tep_session_is_registered('customer_id')) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}*/
if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0");
}else{
  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0");
}
if (!tep_db_num_rows($product_info_query)) {
  tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
} else {
  $product_info = tep_db_fetch_array($product_info_query);
}

	if (tep_session_is_registered('customer_id'))
  {
$customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
$customer = tep_db_fetch_array($customer_query);
  }
	else
	{
	 $customer = tep_db_prepare_input($HTTP_POST_VARS['customer']);
	} 

if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
  $rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
  $review = tep_db_prepare_input(strip_tags($HTTP_POST_VARS['review']));

  $error = false;
  if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
    $error = true;

    $messageStack->add('review', TEXT_REVIEW_TEXT_ERROR);
  }

  if (($rating < 1) || ($rating > 5)) {
    $error = true;

      $messageStack->add('review', JS_REVIEW_RATING);
  }

    if (!tep_session_is_registered('customer_id'))
    {
      if(strlen($customer)<1)
      {
       $error = true;

       //$messageStack->add('review', TEXT_REVIEW_RATING_ERROR);
       $messageStack->add('review', JS_REVIEW_CUSTOMER_FULL_NAME);
      }
  }
  
// {{
    if (ANTI_SPAM_ROBOT == 'True')
    {
      if ( strlen($HTTP_SESSION_VARS['random']) == 0 || strcasecmp($HTTP_POST_VARS['robot'], $HTTP_SESSION_VARS['random']) != 0 )
      {
        $error = true;
        $robot = '';
        $messageStack->add('review', ENTRY_ROBOT_ERROR);
      }
      tep_session_unregister('random'); unset($random); unset($HTTP_SESSION_VARS['random']);
    }
// }}  

  if ($error == false) {
   if (tep_session_is_registered('customer_id'))
   {
    tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
   }
	 else
	 {
	  tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '0', '" . tep_db_input($customer) . "', '" . tep_db_input($rating) . "', now())");
	 }
    $insert_id = tep_db_insert_id();

    tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");
    $messageStack->add_session('review', REVIEW_ADDED, 'success');
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  }
}

if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
  $products_price = '<span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span> <span class="productPriceSpecial">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
} else {
  $products_price = '<span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($product_info['products_id'], 1, $product_info['products_price']), tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
}

if (tep_not_null($product_info['products_model'])) {
  $products_name = $product_info['products_name'] . '&nbsp;&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
} else {
  $products_name = $product_info['products_name'];
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

$content = CONTENT_PRODUCT_REVIEWS_WRITE;
$javascript = $content . '.js';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
