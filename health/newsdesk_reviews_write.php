<?php

require('includes/application_top.php');

if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_REVIEWS_WRITE);

$error = false;

if ($HTTP_GET_VARS['action'] == 'process') {
  if (!isset($HTTP_POST_VARS['rating'])){
    $error = true;
    $messageStack->add('newsdesk_reviews_write', REVIEW_RATING_ERROR_NEWSDESK);
  }
  if (strlen($HTTP_POST_VARS['review_text']) < REVIEW_TEXT_MIN_LENGTH){
    $error = true;
    $messageStack->add('newsdesk_reviews_write', REVIEW_TEXT_ERROR_NEWSDESK);
  }
  if (!$error){  
  	$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  	$customer_values = tep_db_fetch_array($customer);
  
  	tep_db_query("insert into " . TABLE_NEWSDESK_REVIEWS . " (newsdesk_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['newsdesk_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer_values['customers_firstname']) . ' ' . tep_db_input($customer_values['customers_lastname']) . "', '" . (int)$HTTP_POST_VARS['rating'] . "', now())");
      $insert_id = tep_db_insert_id();
      $HTTP_POST_VARS['review_text'] = tep_db_prepare_input($HTTP_POST_VARS['review_text']);
      tep_db_query("insert into " . TABLE_NEWSDESK_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($HTTP_POST_VARS['review_text']) . "')");
    $messageStack->add_session('newsdesk_reviews_write', REVIEW_WRITE_MASSAGE);
  	tep_redirect(tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $HTTP_POST_VARS['get_params'], 'NONSSL'));
  }
}

// lets retrieve all $HTTP_GET_VARS keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
	$get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
	$get_params_back = $get_params;
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, $get_params, 'NONSSL'));

$product = tep_db_query("select p.newsdesk_id, pd.newsdesk_article_name, p.newsdesk_image from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' and pd.newsdesk_id = p.newsdesk_id and pd.language_id = '" . (int)$languages_id . "'");

$product_info_values = tep_db_fetch_array($product);

$customer = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
$customer_values = tep_db_fetch_array($customer);

if ($product_info_values['newsdesk_image'] != '') {
$insert_image = '<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $product_info_values['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], '', '') . '</a>
		</td>
	</tr>
</table>';
}

$javascript = "newsdesk_reviews_write.js.php";

$content = CONTENT_NEWSDESK_REVIEWS_WRITE;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
