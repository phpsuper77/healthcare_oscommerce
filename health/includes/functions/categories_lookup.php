<?php
/*
  Categories Functions
*/

////
// Return a product's catagory
// TABLES: products_to_catagories
  function tep_get_products_catagory_id($products_id) {
    global $languages_id;

    $the_products_catagory_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "'" . " order by products_id,categories_id");
    $the_products_catagory = tep_db_fetch_array($the_products_catagory_query);

    return $the_products_catagory['categories_id'];
  }

////
// WebMakers.com Added: Find a Categories Name
// TABLES: categories_description
  function tep_get_categories_name($who_am_i) {
    global $languages_id, $HTTP_SESSION_VARS;
    
    $product_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES_DESCRIPTION .  " cd1 on cd.categories_id = cd1.categories_id and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and cd1.language_id = '" . (int)$languages_id . "' where cd.categories_id = '" . (int)$who_am_i . "' and cd.language_id = '" . (int)$languages_id . "' and cd.affiliate_id = '0'");

    $the_categories_name = tep_db_fetch_array($the_categories_name_query);
    return $the_categories_name['categories_name'];
  }


////
// WebMakers.com Added: Find a Categories image
// TABLES: categories_image
  function tep_get_categories_image($what_am_i) {
    $the_categories_image_query= tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id= '" . (int)$what_am_i . "'");

    $the_categories_image = tep_db_fetch_array($the_categories_image_query);
    return $the_categories_image['categories_image'];
  }
?>
