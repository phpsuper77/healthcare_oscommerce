<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_INFO);

$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
    $get_params_back = $get_params;
}

if (strlen($faqPath) > 0) {
	$faqPath_array = faqdesk_parse_category_path($faqPath);
	$faqPath = implode('_', $faqPath_array);
	$current_faq_id = $faqPath_array[(sizeof($faqPath_array)-1)];
} else {
	$current_faq_id = 0;
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));

if (isset($faqPath_array)) {
	$n = sizeof($faqPath_array);
	for ($i = 0; $i < $n; $i++) {
		$categories_query = tep_db_query("select categories_name from " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . $faqPath_array[$i] . "' and language_id='" . $languages_id . "'");
		if (tep_db_num_rows($categories_query) > 0) {
			$categories = tep_db_fetch_array($categories_query);
			$breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath=' 
			. implode('_', array_slice($faqPath_array, 0, ($i+1)))));
		} else {
			break;
		}
	}
}

$product_info = tep_db_query("select p.faqdesk_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, pd.faqdesk_extra_url, pd.faqdesk_extra_viewed, p.faqdesk_date_added, p.faqdesk_date_available, pd.faqdesk_image_text, pd.faqdesk_image_text_two, pd.faqdesk_image_text_three from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . (int)$HTTP_GET_VARS['faqdesk_id'] . "' and pd.faqdesk_id = p.faqdesk_id and pd.language_id = '" . $languages_id . "'");
$product_info_values = tep_db_fetch_array($product_info);
if (tep_db_num_rows($product_info)){
  $breadcrumb->add($product_info_values['faqdesk_question'], tep_href_link(FILENAME_FAQDESK_INFO, tep_get_all_get_params(), 'NONSSL'));
}


$content = CONTENT_FAQDESK_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>