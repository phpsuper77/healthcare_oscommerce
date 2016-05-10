<?php
require('includes/application_top.php');
require('includes/functions/newsdesk_general.php');
require('includes/classes/split_page_results_old.php');

// set application wide parameters
// this query set is for NewsDesk

// calculate category path
if ($HTTP_GET_VARS['newsPath']) {
  $newsPath = $HTTP_GET_VARS['newsPath'];
} elseif ($HTTP_GET_VARS['newsdesk_id']) {
  $newsPath = newsdesk_get_product_path($HTTP_GET_VARS['newsdesk_id']);
} else {
  $newsPath = '';
}

if (strlen($newsPath) > 0) {
  $newsPath_array = newsdesk_parse_category_path($newsPath);
  $newsPath = implode('_', $newsPath_array);
  $current_news_id = $newsPath_array[(sizeof($newsPath_array)-1)];
} else {
  $current_news_id = 0;
}

if (isset($newsPath_array)) {
  $n = sizeof($newsPath_array);
  for ($i = 0; $i < $n; $i++) {
    $categories_query = tep_db_query("select categories_name from " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$newsPath_array[$i] 	. "' and language_id='" . (int)$languages_id . "'");
    if (tep_db_num_rows($categories_query) > 0) {
      $categories = tep_db_fetch_array($categories_query);
      $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', array_slice($newsPath_array, 0, ($i+1)))));
    } else {
      break;
    }
  }
}

$category_depth = 'nested';
//if ($newsPath) {
  $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK_TO_CATEGORIES . " where categories_id = '" . (int)$current_news_id . "'");

  $cateqories_products = tep_db_fetch_array($categories_products_query);
  if ($cateqories_products['total'] > 0) {
    $category_depth = 'products'; // display products
  } else {
    $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . (int)$current_news_id . "'");
    $category_parent = tep_db_fetch_array($category_parent_query);
    if ($category_parent['total'] > 0) {
      $category_depth = 'nested'; // navigate through the categories
    } else {
      $category_depth = 'products'; // category has no products, but display the 'no products' message
    }
  }
  
//}  // I lost track to what loop this is closing ... ugh I hate when this happens

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_INDEX);

$content = CONTENT_NEWSDESK_INDEX;

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
