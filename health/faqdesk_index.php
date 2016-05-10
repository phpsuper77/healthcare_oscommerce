<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');
//require('includes/classes/split_page_results_old.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_INDEX);

// calculate category path
if ($HTTP_GET_VARS['faqPath']) {
	$faqPath = $HTTP_GET_VARS['faqPath'];
} elseif ($HTTP_GET_VARS['faqdesk_id']) {
	$faqPath = faqdesk_get_product_path($HTTP_GET_VARS['faqdesk_id']);
} else {
	$faqPath = '';
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

// the following faqPath references come from application_top.php
$category_depth = 'top';
//if ($faqPath) {
	$categories_products_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where categories_id = '" . $current_faq_id . "'");

	$cateqories_products = tep_db_fetch_array($categories_products_query);
	if ($cateqories_products['total'] > 0) {
		$category_depth = 'products'; // display products
	} else {
	  $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . $current_faq_id . "'");

	  $category_parent = tep_db_fetch_array($category_parent_query);
		if ($category_parent['total'] > 0) {
			$category_depth = 'nested'; // navigate through the categories
		} else {
			$category_depth = 'products'; // category has no products, but display the 'no products' message
		}
	}
//}

function faqdesk_show_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {

$field = '<select name="' . $name . '"';
if ($params) $field .= ' ' . $params;
	$field .= '>';
	for ($i=0; $i<sizeof($values); $i++) {
		$field .= '<option value="' . $values[$i]['id'] . '"';
		if ( ($GLOBALS[$name] == $values[$i]['id']) || ($default == $values[$i]['id']) ) {
			$field .= ' SELECTED';
		}
		$field .= '>' . $values[$i]['text'] . '</option>';
	}
	$field .= '</select>';
	$field .= tep_hide_session_id();

	if ($required) $field .= FAQ_TEXT_FIELD_REQUIRED;

return $field;
}

//$javascript = "support.js";

$content = CONTENT_FAQDESK_INDEX;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');

?>