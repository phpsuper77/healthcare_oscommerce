<?php
// Generate a path to categories
function newsdesk_get_path($current_news_id = '') {
  global $newsPath_array;

  if ($current_news_id) {
    $cp_size = sizeof($newsPath_array);
    if ($cp_size == 0) {
      $newsPath_new = $current_news_id;
    } else {
      $newsPath_new = '';
      $last_category_query = tep_db_query("select parent_id from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . (int)$newsPath_array[($cp_size-1)] . "'");
      $last_category = tep_db_fetch_array($last_category_query);
      $current_category_query = tep_db_query("select parent_id from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . (int)$current_news_id . "'");
      $current_category = tep_db_fetch_array($current_category_query);
      if ($last_category['parent_id'] == $current_category['parent_id']) {
        for ($i=0; $i<($cp_size-1); $i++) {
          $newsPath_new .= '_' . $newsPath_array[$i];
        }
      } else {
        for ($i=0; $i<$cp_size; $i++) {
          $newsPath_new .= '_' . $newsPath_array[$i];
        }
      }
      $newsPath_new .= '_' . $current_news_id;
      if (substr($newsPath_new, 0, 1) == '_') {
        $newsPath_new = substr($newsPath_new, 1);
      }
    }
  } else {
    $newsPath_new = implode('_', $newsPath_array);
  }

  return 'newsPath=' . $newsPath_new;

}

// Parse and secure the newsPath parameter values
function newsdesk_parse_category_path($newsPath) {
  // make sure the category IDs are integers
  $newsPath_array = array_map('tep_string_to_int', explode('_', $newsPath));

  // make sure no duplicate category IDs exist which could lock the server in a loop
  $tmp_array = array();
  $n = sizeof($newsPath_array);
  for ($i=0; $i<$n; $i++) {
    if (!in_array($newsPath_array[$i], $tmp_array)) {
      $tmp_array[] = $newsPath_array[$i];
    }
  }

  return $tmp_array;

}

// Return true if the category has subcategories
// TABLES: categories
function newsdesk_has_category_subcategories($category_id) {
  $child_category_query = tep_db_query("select count(*) as count from " . TABLE_NEWSDESK_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
  $child_category = tep_db_fetch_array($child_category_query);

  if ($child_category['count'] > 0) {
    return true;
  } else {
    return false;
  }

}

// Construct a category path to the product
// TABLES: products_to_categories
function newsdesk_get_product_path($newsdesk_id) {
  $newsPath = '';

  $cat_count_sql = tep_db_query("select count(*) as count from " . TABLE_NEWSDESK_TO_CATEGORIES . " where newsdesk_id = '" . (int)$newsdesk_id . "'");
  $cat_count_data = tep_db_fetch_array($cat_count_sql);

  if ($cat_count_data['count'] == 1) {
    $categories = array();

    $cat_id_sql = tep_db_query("select categories_id from " . TABLE_NEWSDESK_TO_CATEGORIES . " where newsdesk_id = '" . (int)$newsdesk_id . "'");
    $cat_id_data = tep_db_fetch_array($cat_id_sql);
    newsdesk_get_parent_categories($categories, $cat_id_data['categories_id']);

    $size = sizeof($categories)-1;
    for ($i = $size; $i >= 0; $i--) {
      if ($newsPath != '') $newsPath .= '_';
      $newsPath .= $categories[$i];
    }
    if ($newsPath != '') $newsPath .= '_';
    $newsPath .= $cat_id_data['categories_id'];
  }

  return $newsPath;

}

// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
function newsdesk_get_parent_categories(&$categories, $categories_id) {
  $parent_categories_query = tep_db_query("select parent_id from " . TABLE_NEWSDESK_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");

  while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
    if ($parent_categories['parent_id'] == 0) return true;
    $categories[sizeof($categories)] = $parent_categories['parent_id'];
    if ($parent_categories['parent_id'] != $categories_id) {
      newsdesk_get_parent_categories($categories, $parent_categories['parent_id']);
    }
  }
}
?>