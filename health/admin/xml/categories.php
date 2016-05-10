<?php
/*
$Id: categories.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
require('../ebay/core.php');

require_once(DIR_WS_CLASSES . 'currencies.php');
require(DIR_WS_FUNCTIONS . FILENAME_IMAGE_RESIZE);
$currencies = new currencies();

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

if (tep_session_is_registered('login_vendor') && isset($HTTP_GET_VARS['pID'])){
  $data = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where vendor_id = '" . $login_id . "' and products_id = '" . $HTTP_GET_VARS['pID'] . "'"));
  if ($data['total'] == 0){
    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath']));
  }
  if (isset($HTTP_GET_VARS['action']) && isset($HTTP_GET_VARS['cID'])){
    tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) ));
  }
}
if (tep_session_is_registered('login_vendor')){
  $actions = array('new_category', 'edit_category', 'insert_category', 'update_category', 'setflag', 'empty_database', 'delete_category_confirm', 'move_category_confirm', 'create_copy_product_attributes', 'create_copy_product_attributes_categories', 'copy_to_confirm');
  if (in_array($action, $actions)){
    tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) ));
  }
}
if (tep_not_null($action)) {
  switch ($action) {
    case 'empty_database':
      if ($HTTP_POST_VARS['products']){
        $query = tep_db_query("select * from " . TABLE_CATEGORIES);
        while ($data = tep_db_fetch_array($query)){
          @unlink(DIR_FS_CATALOG_IMAGES . $data['categories_image']);
        }
        tep_db_query("delete from " . TABLE_CATEGORIES);
        tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION);
        $query = tep_db_query("select * from " . TABLE_PRODUCTS);
        while ($data = tep_db_fetch_array($query)){
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_med']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_lrg']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_1']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_1']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_2']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_2']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_3']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_3']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_4']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_4']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_5']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_5']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_sm_6']);
          @unlink(DIR_FS_CATALOG_IMAGES . $data['products_image_xl_6']);
        }
        tep_db_query("delete from " . TABLE_PRODUCTS);
        tep_db_query("delete from " . TABLE_PRODUCTS_PRICES);
        tep_db_query("delete from " . TABLE_SPECIALS);
        tep_db_query("delete from " . TABLE_SPECIALS_PRICES);
        tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION);
        tep_db_query("delete from " . TABLE_PRODUCTS_XSELL);
        tep_db_query("delete from " . TABLE_PRODUCTS_TO_AFFILIATES);
        
        if (SUPPLEMENT_STATUS == 'True'){
          tep_db_query("delete from " . TABLE_CATS_PRODUCTS_XSELL);
          tep_db_query("delete from " . TABLE_PRODUCTS_UPSELL);
          tep_db_query("delete from " . TABLE_CATEGORIES_UPSELL);
          tep_db_query("delete from " . TABLE_CATS_PRODUCTS_UPSELL);
        }
    
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES);
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD);
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES);
        tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS);
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS);
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES);
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS);
        tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES);
        tep_db_query("delete from " . TABLE_REVIEWS);
        tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION);
        $query = tep_db_query("select * from " . TABLE_MANUFACTURERS);
        while ($data = tep_db_fetch_array($query)){
          @unlink(DIR_FS_CATALOG_IMAGES . $data['manufacturers_image']);
        }
        tep_db_query("delete from " . TABLE_MANUFACTURERS);
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO);
        if (PRODUCTS_PROPERTIES == 'True') {
          tep_db_query("delete from " . TABLE_PROPERTIES_CATEGORIES);
          tep_db_query("delete from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION);
          tep_db_query("delete from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES);
          tep_db_query("delete from " . TABLE_PROPERTIES);
          tep_db_query("delete from " . TABLE_PROPERTIES_DESCRIPTION);
          tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS);
        }

      }

      if ($HTTP_POST_VARS['orders'] == 1){
        tep_db_query("delete from " . TABLE_ORDERS);
        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS);
        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES);
        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD);
        tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY);
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL);
      }


      if ($HTTP_POST_VARS['customers'] == 1){
        tep_db_query("delete from " . TABLE_CUSTOMERS);
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET);
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES);
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO);
        tep_db_query("delete from " . TABLE_ADDRESS_BOOK);
      }

  
      tep_redirect(tep_href_link(FILENAME_CATEGORIES));
    break;
    case 'setflag':
    if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
      if (isset($HTTP_GET_VARS['pID'])) {
        tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
      }
      if ($HTTP_GET_VARS['cID']) {
        tep_set_categories_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
    }

    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&cID=' . $HTTP_GET_VARS['cID']));
    break;

    //Added for Categories Description 1.5
    case 'new_category':
    case 'edit_category':
    if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
    $HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
    break;
    //End Categories Description 1.5

    case 'insert_category':
    case 'update_category':

    //Added for Categories Description 1.5
    if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
      $HTTP_GET_VARS['action'] = 'edit_category_ACD';
    } else {
      //End Categories Description 1.5

      if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

      if ($categories_id == '') {
        $categories_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
      }

      $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);
      $categories_status = tep_db_prepare_input($HTTP_POST_VARS['categories_status']);

      $sql_data_array = array('sort_order' => $sort_order);

      if ($action == 'insert_category') {
        $insert_sql_data = array('parent_id' => $current_category_id,
        'categories_status' => $categories_status,
        'date_added' => 'now()');

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

        $categories_id = tep_db_insert_id();
      } elseif ($action == 'update_category') {
        
        $update_sql_data = array('last_modified' => 'now()');
        tep_set_categories_status($categories_id, $categories_status);

        $sql_data_array = array_merge($sql_data_array, $update_sql_data);

        tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
      }

      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $categories_name_array = $HTTP_POST_VARS['categories_name'];

        $language_id = $languages[$i]['id'];

        $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]));
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
          $sql_data_array = array('categories_name' => tep_db_prepare_input($HTTP_POST_VARS['categories_name'][$language_id]),
          'categories_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['categories_heading_title'][$language_id]),
          'categories_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_title_tag'][$language_id]),
          'categories_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_desc_tag'][$language_id]),
          'categories_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_keywords_tag'][$language_id]),
          'categories_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_description'][$language_id]),
          'direct_url' => tep_db_prepare_input($HTTP_POST_VARS['direct_url'][$language_id]));
        }

        $check_category = tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $categories_id . "' and language_id = '" .  $languages[$i]['id'] . "' and affiliate_id = 0");
        if ($action == 'insert_category' || !tep_db_num_rows($check_category)) {
          $insert_sql_data = array('categories_id' => $categories_id,
          'language_id' => $languages[$i]['id']);

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
        } elseif ($action == 'update_category') {
          tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "' and affiliate_id = 0");
        }
        
        $affiliates = tep_get_affiliates();
        if (count($affiliates) > 0){
          for ($j=0;$j<count($affiliates);$j++){
            $sql_data_array = array('categories_name' => tep_db_prepare_input($HTTP_POST_VARS['categories_name_affiliate'][$language_id][$affiliates[$j]['id']]));
            if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => tep_db_prepare_input($HTTP_POST_VARS['categories_name_affiliate'][$language_id][$affiliates[$j]['id']]),
              'categories_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['categories_heading_title_affiliate'][$language_id][$affiliates[$j]['id']]),
              'categories_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_title_tag_affiliate'][$language_id][$affiliates[$j]['id']]),
              'categories_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_desc_tag_affiliate'][$language_id][$affiliates[$j]['id']]),
              'categories_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_keywords_tag_affiliate'][$language_id][$affiliates[$j]['id']]),
              'categories_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_description_affiliate'][$language_id][$affiliates[$j]['id']]),
              'direct_url' => tep_db_prepare_input($HTTP_POST_VARS['direct_url_affiliate'][$language_id][$affiliates[$j]['id']]));
            }            
            $check_category = tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $categories_id . "' and language_id = '" .  $languages[$i]['id'] . "' and affiliate_id = '" . $affiliates[$j]['id'] . "'");

            if ($action == 'insert_category' || !tep_db_num_rows($check_category)) {
              $insert_sql_data = array('categories_id' => $categories_id,
                                       'language_id' => $languages[$i]['id'],
                                       'affiliate_id' => $affiliates[$j]['id']);
    
              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
    
              tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_category') {
  
              tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "' and affiliate_id = '" . $affiliates[$j]['id'] . "'");
            }            
          }
        }
      }


      if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
        tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $HTTP_POST_VARS['categories_image'] . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
        $categories_image = '';
      } else {
        if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
        }
      }

      if (SUPPLEMENT_STATUS == 'True') {
        tep_db_query("delete from " . TABLE_CATS_PRODUCTS_XSELL . " where categories_id = '" . (int)$categories_id . "'");
        if (is_array($HTTP_POST_VARS['xsell_product_id'])){
          foreach ($HTTP_POST_VARS['xsell_product_id'] as $key => $value){
            tep_db_query("insert into " . TABLE_CATS_PRODUCTS_XSELL . " (categories_id, xsell_products_id, sort_order) values ('" . tep_db_input($categories_id) . "', '" . tep_db_input($value) . "', '" . tep_db_input($HTTP_POST_VARS['xsell_products_sort_order'][$key]). "')");
          }
        }
        tep_db_query("delete from " . TABLE_CATS_PRODUCTS_UPSELL . " where categories_id = '" . (int)$categories_id . "'");
        if (is_array($HTTP_POST_VARS['upsell_product_id'])){
          foreach ($HTTP_POST_VARS['upsell_product_id'] as $key => $value){
            tep_db_query("insert into " . TABLE_CATS_PRODUCTS_UPSELL . " (categories_id, upsell_products_id, sort_order) values ('" . tep_db_input($categories_id) . "', '" . tep_db_input($value) . "', '" . tep_db_input($HTTP_POST_VARS['upsell_products_sort_order'][$key]). "')");
          }
        }

        tep_db_query("delete from " . TABLE_CATEGORIES_UPSELL . " where categories_id = '" . (int)$categories_id . "'");
        if (is_array($HTTP_POST_VARS['upsell_category_id'])){
          foreach ($HTTP_POST_VARS['upsell_category_id'] as $key => $value){
            tep_db_query("insert into " . TABLE_CATEGORIES_UPSELL . " (categories_id, upsell_id, sort_order) values ('" . tep_db_input($categories_id) . "', '" . tep_db_input($value) . "', '" . tep_db_input($HTTP_POST_VARS['upsell_category_sort_order'][$key]). "')");
          }
        }

      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

      tep_update_categories();

      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));

    }
    //End Categories Description 1.5

    break;
    case 'delete_category_confirm':
    if (isset($HTTP_POST_VARS['categories_id'])) {
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

      $categories = tep_get_category_tree($categories_id, '', '0', '', true);
      $products = array();
      $products_delete = array();

      for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
        $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

        while ($product_ids = tep_db_fetch_array($product_ids_query)) {
          $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
        }
      }

      reset($products);
      while (list($key, $value) = each($products)) {
        $category_ids = '';

        for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
          $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
        }
        $category_ids = substr($category_ids, 0, -2);

        $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
        $check = tep_db_fetch_array($check_query);
        if ($check['total'] < '1') {
          $products_delete[$key] = $key;
        }
      }

      // removing categories can be a lengthy process
      tep_set_time_limit(0);
      for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
        tep_remove_category($categories[$i]['id']);
      }

      reset($products_delete);
      while (list($key) = each($products_delete)) {
        tep_remove_product($key);
      }
    }

    if (USE_CACHE == 'true') {
      tep_reset_cache_block('categories');
      tep_reset_cache_block('also_purchased');
    }
    tep_update_categories();

    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
    break;
    case 'delete_product_confirm':
    if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
      $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
      $product_categories = $HTTP_POST_VARS['product_categories'];

      for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
        tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
      }

      $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
      $product_categories = tep_db_fetch_array($product_categories_query);

      if ($product_categories['total'] == '0') {
        tep_remove_product($product_id);
      }
    }

    if (USE_CACHE == 'true') {
      tep_reset_cache_block('categories');
      tep_reset_cache_block('also_purchased');
    }

    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
    break;
    case 'move_category_confirm':
    if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
      $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

      $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

      if (in_array($categories_id, $path)) {
        $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
      } else {
        tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
        tep_update_categories();

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
      }
    }

    break;
    case 'move_product_confirm':
    $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
    $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

    $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
    $duplicate_check = tep_db_fetch_array($duplicate_check_query);
    if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

    if (USE_CACHE == 'true') {
      tep_reset_cache_block('categories');
      tep_reset_cache_block('also_purchased');
    }

    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
    break;
    ///////////////////////////////////////////////////////////////////////////////////////
    // BOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product

    case 'create_copy_product_attributes':
    // $products_id_to= $copy_to_products_id;
    // $products_id_from = $pID;
    tep_copy_products_attributes($pID,$copy_to_products_id);
    break;
    // EOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    // WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in a Category
    case 'create_copy_product_attributes_categories':
    // $products_id_to= $categories_products_copying['products_id'];
    // $products_id_from = $make_copy_from_products_id;
    //  echo 'Copy from products_id# ' . $make_copy_from_products_id . ' Copy to all products in category: ' . $cID . '<br>';
    $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
    while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
      // process all products in category
      tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
    }
    break;
    // EOF: WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in a Category
    ///////////////////////////////////////////////////////////////////////////////////////
    case 'insert_product':
    case 'update_product':
    if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
      $action = 'new_product';
    } else {

      // BOF MaxiDVD: Modified For Ultimate Images Pack!
      if ($HTTP_POST_VARS['delete_image'] == 'yes') {
        @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
      }
      if ($HTTP_POST_VARS['delete_image_med'] == 'yes') {
        @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
      }
      if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes') {
        @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
      }
      for ($i=1;$i<7;$i++){
        if ($HTTP_POST_VARS['delete_image_sm_' . $i] == 'yes') {
          @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_' . $i]);
        }
        if ($HTTP_POST_VARS['delete_image_xl_' . $i] == 'yes') {
          @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_' . $i]);
        }
      }
      if ($HTTP_POST_VARS['delete_products_file'] == 'yes'){
        @unlink(DIR_FS_DOWNLOAD . $HTTP_POST_VARS['products_previous_file']);
      }
      if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
      $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

      $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

      $sql_data_array = array('products_quantity' => tep_db_prepare_input($HTTP_POST_VARS['products_quantity']),
      'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
      'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['sort_order']),
      'vat_exempt_flag' => (int)$HTTP_POST_VARS['vat_exempt_flag'],
      'products_seo_page_name' => tep_db_prepare_input($HTTP_POST_VARS['products_seo_page_name']),
      'products_date_available' => $products_date_available,
      'products_weight' => tep_db_prepare_input($HTTP_POST_VARS['products_weight']),
      'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
      'products_image_alt_1' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_1']),
      'products_image_alt_2' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_2']),
      'products_image_alt_3' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_3']),
      'products_image_alt_4' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_4']),
      'products_image_alt_5' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_5']),
      'products_image_alt_6' => tep_db_prepare_input($HTTP_POST_VARS['products_image_alt_6']),
      'products_mpn' => tep_db_prepare_input($HTTP_POST_VARS['products_mpn']),
      'products_upc' => tep_db_prepare_input($HTTP_POST_VARS['products_upc']),
      'products_ean' => tep_db_prepare_input($HTTP_POST_VARS['products_ean']),
      'manufacturers_id' => tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']));

      if (PRODUCTS_BUNDLE_SETS == 'True') {
        $sql_data_array['products_sets_discount'] = tep_db_prepare_input($HTTP_POST_VARS['products_sets_discount']);
      }

      if (tep_session_is_registered('login_vendor')){
        $sql_data_array['vendor_id'] = $login_id;
        if ($action == 'insert_product'){
          $sql_data_array['products_status'] = 0;
        }
      }else{
        $sql_data_array['products_status'] = tep_db_prepare_input($HTTP_POST_VARS['products_status']);
        $sql_data_array['vendor_id'] = tep_db_prepare_input($HTTP_POST_VARS['vendor_id']);
      }
      if ( ($HTTP_POST_VARS['delete_products_file'] == 'yes')) {
        $sql_data_array['products_file'] = '';
      } else {
        if (isset($HTTP_POST_VARS['products_file']) && tep_not_null($HTTP_POST_VARS['products_file']) && ($HTTP_POST_VARS['products_file'] != 'none')) {
          $sql_data_array['products_file'] = tep_db_prepare_input($HTTP_POST_VARS['products_file']);
        }
      }

      // BOF MaxiDVD: Modified For Ultimate Images Pack!
      if (($HTTP_POST_VARS['unlink_image'] == 'yes') || ($HTTP_POST_VARS['delete_image'] == 'yes')) {
        $sql_data_array['products_image'] = '';
      } else {
        if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
          $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
        }
      }
      if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') || ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $sql_data_array['products_image_med'] = '';
      } else {
        if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
          $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
        }
      }
      if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') || ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
        $sql_data_array['products_image_lrg'] = '';
      } else {
        if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
          $sql_data_array['products_image_lrg'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_lrg']);
        }
      }

      for ($i=0;$i<7;$i++) {
        if (($HTTP_POST_VARS['unlink_image_sm_' . $i] == 'yes') || ($HTTP_POST_VARS['delete_image_sm_' . $i] == 'yes')) {
          $sql_data_array['products_image_sm_' . $i] = '';
        } else {
          if (isset($HTTP_POST_VARS['products_image_sm_' . $i]) && tep_not_null($HTTP_POST_VARS['products_image_sm_' . $i]) && ($HTTP_POST_VARS['products_image_sm_' . $i] != 'none')) {
            $sql_data_array['products_image_sm_' . $i] = tep_db_prepare_input($HTTP_POST_VARS['products_image_sm_' . $i]);
          }
        }
        if (($HTTP_POST_VARS['unlink_image_xl_' . $i] == 'yes') || ($HTTP_POST_VARS['delete_image_xl_' . $i] == 'yes')) {
          $sql_data_array['products_image_xl_' . $i] = '';
        } else {
          if (isset($HTTP_POST_VARS['products_image_xl_' . $i]) && tep_not_null($HTTP_POST_VARS['products_image_xl_' . $i]) && ($HTTP_POST_VARS['products_image_xl_' . $i] != 'none')) {
            $sql_data_array['products_image_xl_' . $i] = tep_db_prepare_input($HTTP_POST_VARS['products_image_xl_' . $i]);
          }
        }
      }

      if ($action == 'insert_product') {
        $insert_sql_data = array('products_date_added' => 'now()');

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

       tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
        $products_id = tep_db_insert_id();

        if ($bm == 1)
        {
          $categories_id = $HTTP_POST_VARS['categories_id'];
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
        } else {
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
        }
      } elseif ($action == 'update_product') {
        $update_sql_data = array('products_last_modified' => 'now()');

        $sql_data_array = array_merge($sql_data_array, $update_sql_data);

        tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");

        if ($bm == 1)
        {
          $categories_id = $HTTP_POST_VARS['categories_id'];
          tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$categories_id . "' where products_id = '" . (int)$products_id . "'");
        }
      }

      if (PRODUCTS_BUNDLE_SETS == 'True') {
        tep_db_query("delete from " . TABLE_SETS_PRODUCTS . " where sets_id = '" . (int)$products_id . "'");

        for ($i=0; $i < count($HTTP_POST_VARS['sets_product_name']); $i++)
        {
          $sql_data_array_sets =
              array('sets_id' => $products_id,
                    'product_id' => tep_db_prepare_input($HTTP_POST_VARS['sets_product_id'][$i]),
                    'num_product' => tep_db_prepare_input($HTTP_POST_VARS['sets_sets_number'][$i]),
                    'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['sets_sort_order'][$i]));
          tep_db_perform(TABLE_SETS_PRODUCTS, $sql_data_array_sets);
        }
      }

      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];
        if (MSEARCH_ENABLE == "true") {
          $products_name_soudnex = '';
          $products_name_keywords = split ('[[:space:]]+', strip_tags($HTTP_POST_VARS['products_name'][$language_id]));

          $pares = array();
          if (sizeof($products_name_keywords)>1) {
            for ($j=0;$j<sizeof($products_name_keywords);$j++) {
              for ($k=0;$k<sizeof($products_name_keywords);$k++) {
                if ($j!=$k) {
                  if (strlen($products_name_keywords[$j])>=MSEARCH_WORD_LENGTH && strlen($products_name_keywords[$k])>=MSEARCH_WORD_LENGTH) {
                    $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_name_keywords[$j]." ".$products_name_keywords[$k])."') as sx"));
                    $pares[] = $ks_hash["sx"];
                  }
                }
              }

            }
          } else {
            if (strlen($products_name_keywords[0])>=MSEARCH_WORD_LENGTH) {
              $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_name_keywords[0])."') as sx"));
              $pares[] = $ks_hash["sx"];
            } else {
              $pares[] = '';
            }
          }

          $pares = array_unique($pares);
          $products_name_soudnex = join(",",$pares);

          $products_description_soudnex = '';
          $products_description_keywords = split ('[[:space:]]+', strip_tags($HTTP_POST_VARS['products_description'][$language_id]));
          $pares = array();
          if (sizeof($products_description_keywords)>1) {
            for ($j=0;$j<sizeof($products_description_keywords);$j++) {
              for ($k=0;$k<sizeof($products_description_keywords);$k++) {
                if ($j!=$k) {
                  if (strlen($products_description_keywords[$j])>=MSEARCH_WORD_LENGTH && strlen($products_description_keywords[$k])>=MSEARCH_WORD_LENGTH) {
                    $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_description_keywords[$j]." ".$products_description_keywords[$k])."') as sx"));
                    $pares[] = $ks_hash["sx"];
                  }
                }
              }

            }
          } else {
            if (strlen($products_description_keywords[0])>=MSEARCH_WORD_LENGTH) {
              $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_description_keywords[0])."') as sx"));
              $pares[] = $ks_hash["sx"];
            } else {
              $pares[] = '';
            }
          }
          $pares = array_unique($pares);
          $products_description_soudnex = join(",",$pares);

          $soundex_sql_data = array('products_name_soundex' => $products_name_soudnex,
          'products_description_soundex' => $products_description_soudnex);
        }
		
		/* Start: Generate direct URL and Create redirect rule when updating a existing direct url - Musaffar Patel */
		if ($_POST['direct_url'][1] != "" && $HTTP_GET_VARS['pID'] != '') {
			$sql = "SELECT COUNT(*) AS total_count, direct_url FROM ".TABLE_PRODUCTS_DESCRIPTION." 
					WHERE direct_url <> '".$_POST['direct_url'][1]."'
					AND products_id = ".$HTTP_GET_VARS['pID'];
			$data = tep_db_fetch_array(tep_db_query($sql));
			if ($data['total_count'] > 0 && $data['direct_url'] <> '' ) {
				$sql = "DELETE FROM products_redirects WHERE old_url LIKE '".$data['direct_url']."' 
						OR new_url LIKE '".$data['direct_url']."'";
				tep_db_query($sql);
				
				$redirects_sql_array = array(	
											'old_url' => tep_db_prepare_input($data['direct_url']),
											'new_url' => tep_db_prepare_input($_POST['direct_url'][1])
										);				
				tep_db_perform('products_redirects', $redirects_sql_array);
			}			
		}
		/* End: Generate direct URL and Create redirect rule when updating a existing direct url - Musaffar Patel */
		

        $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
        'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
        'products_ebay_description' => tep_db_prepare_input($HTTP_POST_VARS['products_ebay_description'][$language_id]),
        'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
        'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag'][$language_id]),
        'products_description_short' => tep_db_prepare_input($HTTP_POST_VARS['products_description_short'][$language_id]),
        'products_features' => tep_db_prepare_input($HTTP_POST_VARS['products_features'][$language_id]),
        'products_faq' => tep_db_prepare_input($HTTP_POST_VARS['products_faq'][$language_id]),
        'direct_url' => tep_db_prepare_input($HTTP_POST_VARS['direct_url'][$language_id]),
        'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag'][$language_id]),
        'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag'][$language_id]));

        if (MSEARCH_ENABLE == "true") {
          $sql_data_array = array_merge($sql_data_array, $soundex_sql_data);
        }

        if ($action == 'insert_product') {
          $insert_sql_data = array('products_id' => $products_id,
          'language_id' => $language_id);

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
        } elseif ($action == 'update_product') {
          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = 0");
        }	
		
        
        $affiliates = tep_get_affiliates();
        if (count($affiliates) > 0){
          for ($j=0;$j<count($affiliates);$j++){
            if (MSEARCH_ENABLE == "true") {
              $products_name_soudnex = '';
              $products_name_keywords = split ('[[:space:]]+', strip_tags($HTTP_POST_VARS['products_name_affiliate'][$language_id][$affiliates[$j]['id']]));
    
              $pares = array();
              if (sizeof($products_name_keywords)>1) {
                for ($j=0;$j<sizeof($products_name_keywords);$j++) {
                  for ($k=0;$k<sizeof($products_name_keywords);$k++) {
                    if ($j!=$k) {
                      if (strlen($products_name_keywords[$j])>=MSEARCH_WORD_LENGTH && strlen($products_name_keywords[$k])>=MSEARCH_WORD_LENGTH) {
                        $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_name_keywords[$j]." ".$products_name_keywords[$k])."') as sx"));
                        $pares[] = $ks_hash["sx"];
                      }
                    }
                  }
    
                }
              } else {
                if (strlen($products_name_keywords[0])>=MSEARCH_WORD_LENGTH) {
                  $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_name_keywords[0])."') as sx"));
                  $pares[] = $ks_hash["sx"];
                } else {
                  $pares[] = '';
                }
              }
    
              $pares = array_unique($pares);
              $products_name_soudnex = join(",",$pares);
    
              $products_description_soudnex = '';
              $products_description_keywords = split ('[[:space:]]+', strip_tags($HTTP_POST_VARS['products_description_affiliate'][$language_id][$affiliates[$j]['id']]));
              $pares = array();
              if (sizeof($products_description_keywords)>1) {
                for ($j=0;$j<sizeof($products_description_keywords);$j++) {
                  for ($k=0;$k<sizeof($products_description_keywords);$k++) {
                    if ($j!=$k) {
                      if (strlen($products_description_keywords[$j])>=MSEARCH_WORD_LENGTH && strlen($products_description_keywords[$k])>=MSEARCH_WORD_LENGTH) {
                        $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_description_keywords[$j]." ".$products_description_keywords[$k])."') as sx"));
                        $pares[] = $ks_hash["sx"];
                      }
                    }
                  }
    
                }
              } else {
                if (strlen($products_description_keywords[0])>=MSEARCH_WORD_LENGTH) {
                  $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($products_description_keywords[0])."') as sx"));
                  $pares[] = $ks_hash["sx"];
                } else {
                  $pares[] = '';
                }
              }
              $pares = array_unique($pares);
              $products_description_soudnex = join(",",$pares);
    
              $soundex_sql_data = array('products_name_soundex' => $products_name_soudnex,
              'products_description_soundex' => $products_description_soudnex);
            }            
            $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_description_short' => tep_db_prepare_input($HTTP_POST_VARS['products_description_short_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_features' => tep_db_prepare_input($HTTP_POST_VARS['products_features_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_faq' => tep_db_prepare_input($HTTP_POST_VARS['products_faq_affiliate'][$language_id][$affiliates[$j]['id']]),
            'direct_url' => tep_db_prepare_input($HTTP_POST_VARS['direct_url_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag_affiliate'][$language_id][$affiliates[$j]['id']]),
            'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag_affiliate'][$language_id][$affiliates[$j]['id']]));
  
            if (MSEARCH_ENABLE == "true") {
              $sql_data_array = array_merge($sql_data_array, $soundex_sql_data);
            }
            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
              'language_id' => $language_id, 
              'affiliate_id' => $affiliates[$j]['id']);
    
              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
    
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_product') {
              $check = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliates[$j]['id'] . "'");
              if (tep_db_num_rows($check)){
                tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliates[$j]['id'] . "'");
              }else{
                $insert_sql_data = array('products_id' => $products_id,
                'language_id' => $language_id, 
                'affiliate_id' => $affiliates[$j]['id']);
      
                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
      
                tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
              }
            }            
          }
        }
      }
      //$affiliates = tep_get_affiliates(false);
      tep_db_query("delete from " . TABLE_PRODUCTS_TO_AFFILIATES . " where products_id = '" . (int)$products_id . "'");
      if (sizeof($HTTP_POST_VARS['products_to_affiliates']) > 0){
        for ($j=0;$j<sizeof($HTTP_POST_VARS['products_to_affiliates']);$j++){
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_AFFILIATES . " set products_id = '" . (int)$products_id . "', affiliate_id = '" . $HTTP_POST_VARS['products_to_affiliates'][$j] . "'");
        }
      }
      

      // [[ Market prices

      tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "'");
      if (USE_MARKET_PRICES == 'True') {
        $def_cur_price = 0;
        if ($HTTP_POST_VARS['products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] != '')
        {
          $def_cur_price = $HTTP_POST_VARS['products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']];
        }
        else
        {
          foreach($currencies->currencies as $key => $value)
          {
            if ($HTTP_POST_VARS['products_price'][$value['id']] != '')
            {
              $def_cur_price = $HTTP_POST_VARS['products_price'][$value['id']] / $value['value'];
              $HTTP_POST_VARS['products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] = $def_cur_price;
              break;
            }
          }
        }
        if ($def_cur_price != 0)
        {
          foreach($currencies->currencies as $key => $value)
          {
            if ($HTTP_POST_VARS['products_price'][$value['id']] == '')
            {
              $HTTP_POST_VARS['products_price'][$value['id']] = $def_cur_price * $value['value'];
            }
          }
        }


        tep_db_query("update " . TABLE_PRODUCTS . " set products_price = '" . $HTTP_POST_VARS['products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] . "', products_price_discount = '" . $_POST['products_price_discount'][$currencies->currencies[DEFAULT_CURRENCY]['id']] . "' where products_id = '" . $products_id . "'");
        foreach($currencies->currencies as $key => $value)
        {
          $sql_data_array = array('products_id' => $products_id,
                                  'products_group_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price'][$currencies->currencies[$key]['id']]), 
                                  'products_group_discount_price' => tep_db_prepare_input($_POST['products_price_discount'][$currencies->currencies[$key]['id']]), 
                                  'groups_id' => '0', 
                                  'currencies_id' => $currencies->currencies[$key]['id']);
          tep_db_perform(TABLE_PRODUCTS_PRICES, $sql_data_array);
          
          $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
          while ($data = tep_db_fetch_array($data_query))
          {
            $sql_data_array = array('products_id' => $products_id,
                                    'products_group_price' => $HTTP_POST_VARS['products_groups_prices_' . $data['groups_id']][$currencies->currencies[$key]['id']]?tep_db_prepare_input($HTTP_POST_VARS['products_groups_prices_' . $data['groups_id']][$currencies->currencies[$key]['id']]):'-2', 
                                    'products_group_discount_price' => tep_db_prepare_input($_POST['products_price_discount_' . $data['groups_id']][$currencies->currencies[$key]['id']]), 
                                    'groups_id' => $data['groups_id'], 
                                    'currencies_id' => $currencies->currencies[$key]['id']);
            tep_db_perform(TABLE_PRODUCTS_PRICES, $sql_data_array);
          }          
         
        }
      }else{
        $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
        $query  = tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "'");
        while ($data = tep_db_fetch_array($data_query))
        {
          tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " set products_id = '" . $products_id . "', groups_id = '" . $data['groups_id'] . "', products_group_price = '" .tep_db_prepare_input($HTTP_POST_VARS['products_groups_prices_' . $data['groups_id']]) . "', products_group_discount_price = '" . tep_db_prepare_input($HTTP_POST_VARS['products_group_discount_price_' . $data['groups_id']]) . "'");
          
        }
        tep_db_query("update " . TABLE_PRODUCTS . " set products_price = '" . tep_db_prepare_input($HTTP_POST_VARS['products_price']) . "', products_price_discount = '" . tep_db_prepare_input($HTTP_POST_VARS['products_price_discount']) . "' where  products_id = '" . $products_id . "'");       
      }
      // ]] Market prices

      // [[ Properties
      if (PRODUCTS_PROPERTIES == 'True') {
        tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
        $languages = tep_get_languages();
        $properties_query = tep_db_query("select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "', " . TABLE_PROPERTIES_DESCRIPTION . " prd where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");

        //          $data_query = tep_db_query("select properties_id from " . TABLE_PROPERTIES . " order by sort_order");
        while ($properties_array = tep_db_fetch_array($properties_query))
        {
          switch ($properties_array['properties_type']){
            case '1':
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if (tep_not_null($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']])){
                tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
              }
            }
            break;
            case '2':
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if (tep_not_null($HTTP_POST_VARS['set_value'][$properties_array['properties_id']])){
                tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
              }
            }
            break;
            case '3':
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if (tep_not_null($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']])){

                $data_query = tep_db_query("select pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES . " pr, " . TABLE_PROPERTIES_DESCRIPTION . " prd where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages[$i]['id'] . "' and pr.properties_id = " . (int)$properties_array['properties_id']);
                $data = tep_db_fetch_array($data_query);
                $possible_values = explode("\n", $data['possible_values']);
                $set_values = '';
                foreach ($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']] as $key => $value){
                  $set_values .= "\n" . $possible_values[$key];
                }
                tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($set_values) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
              }
            }
            break;
            case '4':
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if (tep_not_null($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']])){
                tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
              }
            }
            break;
            case '5': case '6':
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if ($HTTP_POST_VARS['unlink'][$properties_array['properties_id']][$languages[$i]['id']] == 'yes'){
                @unlink(DIR_FS_CATALOG_IMAGES . 'data/' .  $HTTP_POST_VARS['set_value_previous'][$properties_array['properties_id']][$languages[$i]['id']]);
                tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" .(int)$products_id. "' and properties_id='" .$properties_array['properties_id']. "' and language_id = '" . $languages[$i]['id'] . "'");
              }else{
                if (tep_not_null($HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages[$i]['id']])){
                  tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages[$i]['id']]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
                }elseif(tep_not_null($HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages_id])){
                  tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages_id]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
                }
              }
            }
            break;
            case '0': default:
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              if (tep_not_null($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']])){
                tep_db_query("insert into " . TABLE_PROPERTIES_TO_PRODUCTS . " (products_id, properties_id, language_id, set_value, additional_info) values ('" . (int)$products_id . "', '" . (int)$properties_array['properties_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input_mc($HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']]) . "', '" . tep_db_input_mc($HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']]) . "')" );
              }
            }
          }
        }
      }
      // ]] Properties

      // Update Product Attributes
      $attributes_array = array();
      if (isset($_POST['price_prefix']) && !empty($_POST['price_prefix']) && isset($_POST['price_prefix'])) {
        foreach ($_POST['price_prefix'] as $groups => $attributes) {
          foreach ($_POST['price_prefix'][$groups] as $key => $value) {
            if (isset($_POST['price_prefix'][$groups][$key])) {
              $attributes_array[] = $groups . '-' . $key;

              $Qcheck = tep_db_query("select products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" .$products_id. "' and options_id = '" . $groups . "' and options_values_id = '" . $key . "'");
              if (tep_db_num_rows($Qcheck)){
                $Qdata = tep_db_fetch_array($Qcheck);
                $products_attributes_id = $Qdata['products_attributes_id'];
                tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $HTTP_POST_VARS['products_attributes_price'][$groups][$key][0] . "', price_prefix = '" . $HTTP_POST_VARS['price_prefix'][$groups][$key] . "', products_options_sort_order = '" . $HTTP_POST_VARS['products_options_sort_order'][$groups][$key] . "', product_attributes_one_time = '" . $HTTP_POST_VARS['product_attributes_one_time'][$groups][$key] . "', products_attributes_weight = '" . $HTTP_POST_VARS['products_attributes_weight'][$groups][$key] . "', products_attributes_weight_prefix = '" . $HTTP_POST_VARS['products_attributes_weight_prefix'][$groups][$key] . "', products_attributes_units = '" . $HTTP_POST_VARS['products_attributes_units'][$groups][$key] . "', products_attributes_units_price = '" . $HTTP_POST_VARS['products_attributes_units_price'][$groups][$key] . "', products_attributes_discount_price = '" . $HTTP_POST_VARS['products_attributes_discount_price'][$groups][$key][0] . "', products_attributes_filename = '" . $HTTP_POST_VARS['products_attributes_filename_name'][$groups][$key] . "', products_attributes_maxdays = '" . $HTTP_POST_VARS['products_attributes_maxdays'][$groups][$key] . "', products_attributes_maxcount = '" . $HTTP_POST_VARS['products_attributes_maxcount'][$groups][$key] . "' where products_id = '" .$products_id. "' and options_id = '" . $groups . "' and options_values_id = '" . $key . "'");
              }else{
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order, product_attributes_one_time, products_attributes_weight, products_attributes_weight_prefix, products_attributes_units, products_attributes_units_price, products_attributes_discount_price, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) values ('', '" . $products_id . "', '" . $groups . "', '" . $key . "', '" . $HTTP_POST_VARS['products_attributes_price'][$groups][$key][0] . "', '" . $HTTP_POST_VARS['price_prefix'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_options_sort_order'][$groups][$key] . "', '" . $HTTP_POST_VARS['product_attributes_one_time'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_weight'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_weight_prefix'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_units'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_units_price'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_discount_price'][$groups][$key][0]. "', '" . $HTTP_POST_VARS['products_attributes_filename_name'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_maxdays'][$groups][$key] . "', '" . $HTTP_POST_VARS['products_attributes_maxcount'][$groups][$key] . "' )");
                $products_attributes_id = tep_db_insert_id();
              }

              if (USE_MARKET_PRICES == 'True'){
                tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = " . $products_attributes_id);
                tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . tep_db_prepare_input($HTTP_POST_VARS['products_attributes_price'][$groups][$key][0][$currencies->currencies[DEFAULT_CURRENCY]['id']]) . "', products_attributes_discount_price = '" . tep_db_prepare_input($_POST['products_attributes_discount_price'][$groups][$key][0][$currencies->currencies[DEFAULT_CURRENCY]['id']]) . "' where products_attributes_id = '" . $products_attributes_id . "'");
                foreach($currencies->currencies as $cur => $value)
                {
                  $sql_data_array = array('attributes_group_price' => tep_db_prepare_input($HTTP_POST_VARS['products_attributes_price'][$groups][$key][0][$currencies->currencies[$cur]['id']]),
                                          'groups_id' => 0,
                                          'products_attributes_id' => $products_attributes_id,
                                          'currencies_id' => $currencies->currencies[$cur]['id'],
                                          'attributes_group_discount_price' => tep_db_prepare_input($_POST['products_attributes_discount_price'][$groups][$key][0][$currencies->currencies[$cur]['id']]));
                  tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_PRICES, $sql_data_array);
                  $data_query_groups = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                  while ($data_groups = tep_db_fetch_array($data_query_groups))
                  {
                    tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " (products_attributes_id, groups_id, attributes_group_price, attributes_group_discount_price, currencies_id) values ('" . $products_attributes_id . "', '" . $data_groups['groups_id'] . "', '" . ($HTTP_POST_VARS['products_attributes_price'][$groups][$key][$data_groups['groups_id']][$currencies->currencies[$cur]['id']]?tep_db_input($HTTP_POST_VARS['products_attributes_price'][$groups][$key][$data_groups['groups_id']][$currencies->currencies[$cur]['id']]):-2) . "', '" . tep_db_input($HTTP_POST_VARS['products_attributes_discount_price'][$groups][$key][$data_groups['groups_id']][$currencies->currencies[$cur]['id']]) . "', '" . $currencies->currencies[$cur]['id'] . "')");
                  }                  
                }
              }else{
                tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "'");
                $data_query_groups = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                while ($data_groups = tep_db_fetch_array($data_query_groups))
                {
                  tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " (products_attributes_id, groups_id, attributes_group_price, attributes_group_discount_price, currencies_id) values ('" . $products_attributes_id . "', '" . $data_groups['groups_id'] . "', '" . tep_db_input($HTTP_POST_VARS['products_attributes_price'][$groups][$key][$data_groups['groups_id']]) . "', '" . tep_db_input($HTTP_POST_VARS['products_attributes_discount_price'][$groups][$key][$data_groups['groups_id']]) . "', 0)");
                }
              }

            }
          }
        }
      }
     // Update Product INVENTORY
      if ( defined('PRODUCTS_INVENTORY') && PRODUCTS_INVENTORY == 'True') {
        $attributes_query = tep_db_query("select distinct options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' order by options_id, options_values_id");
        if ( tep_db_num_rows($attributes_query) ) {
          $options = array();
          while ( $_attributes = tep_db_fetch_array($attributes_query) ) {
            $groups = $_attributes['options_id'];
            $key = $_attributes['options_values_id'];
            if (isset($options[$groups])){
              $options[$groups][] = $key;
            }else{
              $options[$groups] = array();
              $options[$groups][] = $key;
            }
          }

          ksort($options);
          reset($options);
          $i=0;
          $idx = 0;
          foreach ($options as $key => $value){
            if ($i==0){
              $idx=$key;
              $i=1;
            }
            asort($options[$key]);
          }
          $inventory_options = get_inventory_uprid($options, $idx);
          $batch_remove = array();
          $inv_check_r = tep_db_query("select inventory_id, products_id from " . TABLE_INVENTORY . " where prid='" . (int)$products_id . "'");
          while( $inv_check = tep_db_fetch_array($inv_check_r) ){
            $_search_val = preg_replace('/^\d*/','',$inv_check['products_id']);
            if ( array_search($_search_val, $inventory_options)===false ) $batch_remove[] = $inv_check['inventory_id'];
          }
          if ( count($batch_remove)>0 ) {
            tep_db_query("delete from " . TABLE_INVENTORY . " where inventory_id in ('" . implode("','", $batch_remove) . "')");
          }
          // insert & update
          foreach( $inventory_options as $partial_uprid ) {
            $real_uprid = (int)$products_id.$partial_uprid;
            $post_uprid = ($action == 'insert_product'?'0':(int)$products_id).$partial_uprid;
            $inv_model = tep_db_prepare_input($HTTP_POST_VARS['inventorymodel_'.$post_uprid]);
            //if ( empty($inv_model) ) $inv_model = make_inventory_model($real_uprid);
            $qty_delta = (int)$HTTP_POST_VARS['inventoryqty_'.$post_uprid];

            //check
            $inv_check = tep_db_fetch_array(tep_db_query("select count(inventory_id) as chk from " . TABLE_INVENTORY . " where prid='" . (int)$products_id . "' and products_id='".tep_db_input($real_uprid)."'"));
            if ( (int)$inv_check['chk']>0 ) {
              tep_db_query("update ".TABLE_INVENTORY." set products_name='".tep_db_input( make_inventory_name($real_uprid) )."', products_model='".tep_db_input($inv_model)."', products_quantity = products_quantity ".(($qty_delta>=0?'+':'').$qty_delta)." where prid='" . (int)$products_id . "' and products_id='".tep_db_input($real_uprid)."'");
            } else {
              tep_db_query("insert into ".TABLE_INVENTORY." set products_name='".tep_db_input( make_inventory_name($real_uprid) )."', products_model='".tep_db_input($inv_model)."', products_quantity = ".$qty_delta.", prid='" . (int)$products_id . "', products_id='".tep_db_input($real_uprid)."'");
            }
          }
          $stock = tep_db_fetch_array(tep_db_query("select sum(products_quantity) as sum_products_quantity from " . TABLE_INVENTORY . " where prid = '" . (int)$products_id . "'"));
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '".(int)$stock['sum_products_quantity']."' where products_id = '" . (int)$products_id . "'");
        } else {
          $post_uprid = ($action == 'insert_product'?'0':(int)$products_id).$partial_uprid;
          // product w/o attributes
          tep_db_query("delete from " . TABLE_INVENTORY . " where prid='" . (int)$products_id . "' and products_id!='".(int)$products_id."'");
          $inv_check = tep_db_fetch_array(tep_db_query("select count(inventory_id) as chk from " . TABLE_INVENTORY . " where prid='" . (int)$products_id . "' and products_id='".(int)$products_id."'"));
          $qty_delta = intval($HTTP_POST_VARS['inventoryqty_'.$post_uprid]);
          $inv_name = tep_db_prepare_input($HTTP_POST_VARS['products_name'][$languages_id]);
          $inv_model =  tep_db_prepare_input($HTTP_POST_VARS['inventorymodel_'.$post_uprid]);
          if ( empty($inv_model) ) $inv_model = tep_db_prepare_input($HTTP_POST_VARS['products_model']);

          if ( (int)$inv_check['chk']>0 ) {
            tep_db_query("update ".TABLE_INVENTORY." set products_name='".tep_db_input( $inv_name )."', products_model='".tep_db_input($inv_model)."', products_quantity = products_quantity ".(($qty_delta>=0?'+':'').$qty_delta)." where prid='" . (int)$products_id . "' and products_id='".(int)$products_id."'");
          } else {
            tep_db_query("insert into ".TABLE_INVENTORY." set products_name='".tep_db_input( $inv_name )."', products_model='".tep_db_input($inv_model)."', products_quantity = ".$qty_delta.", prid='" . (int)$products_id . "', products_id='".(int)$products_id."'");
          }
        }
        $total_have = tep_db_fetch_array(tep_db_query("select sum(products_quantity) as total from  " . TABLE_INVENTORY . " where prid='" . (int)$products_id . "'"));
        tep_db_query("update ".TABLE_PRODUCTS." set products_quantity = '".(int)$total_have['total']."' where products_id='" . (int)$products_id . "'");
      }
      //\ Update Product INVENTORY

      $Qcheck = tep_db_query("select products_attributes_id, products_attributes_filename from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and concat(options_id, '-', options_values_id) not in ('" . implode("', '", $attributes_array) . "')");
      if (tep_db_num_rows($Qcheck)){
        while ($data = tep_db_fetch_array($Qcheck)){
          tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $data['products_attributes_id']. "'");
          if (DOWNLOAD_ENABLED == true || $data['products_attributes_filename'] != ''){
            @unlink(DIR_FS_DOWNLOAD . $data['products_attributes_filename']);
          }
          tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $data['products_attributes_id'] . "'");
          if (USE_MARKET_PRICES == 'True'){
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $data['products_attributes_id'] . "'");
          }
        }
      }

      if (SUPPLEMENT_STATUS == 'True'){
        tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id  = '" . (int)$products_id . "'");
        if (is_array($HTTP_POST_VARS['xsell_product_id'])){
          foreach ($HTTP_POST_VARS['xsell_product_id'] as $key => $value){
            tep_db_query("insert into " . TABLE_PRODUCTS_XSELL . " (products_id, xsell_id, sort_order) values ('" . tep_db_input($products_id) . "', '" . tep_db_input($value) . "', '" . tep_db_input($HTTP_POST_VARS['xsell_products_sort_order'][$key]). "')");
          }
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_UPSELL . " where products_id = '" . (int)$products_id . "'");
        if (is_array($HTTP_POST_VARS['upsell_product_id'])){
          foreach ($HTTP_POST_VARS['upsell_product_id'] as $key => $value){
            tep_db_query("insert into " . TABLE_PRODUCTS_UPSELL . " (products_id, upsell_id, sort_order) values ('" . tep_db_input($products_id) . "', '" . tep_db_input($value) . "', '" . tep_db_input($HTTP_POST_VARS['upsell_products_sort_order'][$key]). "')");
          }
        }
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

      if (tep_session_is_registered('login_vendor')){
        $sql_data_array['vendor_id'] = $login_id;
        if ($action == 'insert_product'){
          tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, ADMIN_EMAIL_SUBJECT_NEW_PRODUCT, sprintf(ADMIN_EMAIL_TEXT, $login_first_name, $products_id, $HTTP_POST_VARS['products_name'][$languages_id], $HTTP_POST_VARS['products_description'][$languages_id]), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
      }

      if ($bm == 1) {
        tep_redirect(tep_href_link(FILENAME_BRAND_MANAGER, 'mID=' . $mID));
      } else {
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
      }
    }
    break;
    case 'copy_to_confirm':
    if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
      $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

      if ($HTTP_POST_VARS['copy_as'] == 'link') {
        if ($categories_id != $current_category_id) {
          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
          }
        } else {
          $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
        }
      } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
        // BOF MaxiDVD: Modified For Ultimate Images Pack!
        $product_query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
        $product = tep_db_fetch_array($product_query);
        
        $str = "insert into " . TABLE_PRODUCTS . " set ";
        foreach($product as $key => $value){
          if ($key != 'products_id'){
            if ($key == 'products_status') $value = 0;
            if ( is_null($value) ) {
              $str .= " " . $key . " = NULL, ";
            }else{
              $str .= " " . $key . " = '" . tep_db_input($value) . "', ";
            }            
          }
        }
        $str = substr($str, 0, strlen($str)-2);
        tep_db_query($str);

        $dup_products_id = tep_db_insert_id();

        $description_query = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
        while ($description = tep_db_fetch_array($description_query)) {
          $str = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " set ";
          foreach($description as $key => $value){
            if ($key != 'products_id'){
              $str .= " " . $key . " = '" . tep_db_input($value) . "', ";
            }else{
              $str .= " products_id = '" . $dup_products_id . "', ";
            }
          }          
          $str = substr($str, 0, strlen($str)-2);
          tep_db_query($str);
        }

        tep_db_query("delete from " . TABLE_PRODUCTS_TO_AFFILIATES . " where products_id = '" . (int)$dup_products_id . "'");
        $aff = tep_db_query("select * from " . TABLE_PRODUCTS_TO_AFFILIATES . " where products_id = '" . (int)$products_id . "'");
        while ($aff_data = tep_db_fetch_array($aff)){
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_AFFILIATES . " (products_id, affiliate_id) values ('" . (int)$dup_products_id . "', '" . $aff_data['affiliate_id'] . "')");
        }

        tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
        $data_query = tep_db_query("select * from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . tep_db_input($products_id) . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $dup_products_id . "'");
        while ($data = tep_db_fetch_array($data_query))
        {
          tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " (products_id, groups_id, currencies_id, products_group_price, products_group_discount_price) values ('" . $dup_products_id . "', '" . $data['groups_id'] . "', '" . $data['currencies_id'] . "', '" . $data['products_group_price'] . "', '" . $data['products_group_discount_price'] . "')");
        }

        // [[ Properties
        if (PRODUCTS_PROPERTIES == 'True'){
          $properties_query = tep_db_query("select * from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" .tep_db_input($products_id). "'" );
          while ($properties = tep_db_fetch_array($properties_query)){
            tep_db_query("insert into " .TABLE_PROPERTIES_TO_PRODUCTS. " (products_id, properties_id, language_id, set_value, additional_info) values ('".$dup_products_id."', '".$properties['properties_id']."', '".$properties['language_id']."', '".$properties['set_value']."', '".$properties['additional_info']."')");
          }
        }
        // ]]

        if (PRODUCTS_BUNDLE_SETS == 'True') {
          $bundle_sets_query = tep_db_query("select * from " . TABLE_SETS_PRODUCTS . " where sets_id = '" . tep_db_input($products_id) . "'");
          while ($bundle_sets = tep_db_fetch_array($bundle_sets_query)) {
            tep_db_query("insert into " . TABLE_SETS_PRODUCTS . " (sets_id, product_id, num_product, sort_order) values ('" . (int)$dup_products_id . "', '" . (int)$bundle_sets['product_id'] . "', '" . (int)$bundle_sets['num_product'] . "', '" . (int)$bundle_sets['sort_order']. "')");
          }
        }

        // [[ SUPPLEMENT_STATUS
        if (SUPPLEMENT_STATUS == 'True'){
          $query = tep_db_query("select * from " . TABLE_PRODUCTS_UPSELL . " where products_id = '" . (int)$products_id . "'");
          while ($data = tep_db_fetch_array($query)){
            tep_db_query("insert into " . TABLE_PRODUCTS_UPSELL . " (products_id, upsell_id, sort_order) values ('" . $dup_products_id . "', '" . $data['upsell_id'] . "', '" . $data['sort_order'] . "')");
          }
          
          $query = tep_db_query("select * from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . (int)$products_id . "'" );
          while ($data = tep_db_fetch_array($query)){
            tep_db_query("insert into " . TABLE_PRODUCTS_XSELL . " (products_id, xsell_id, sort_order) values ('" . $dup_products_id . "', '" . $data['xsell_id'] . "', '" . $data['sort_order'] . "')");
          }
        }
        // ]]
        

        // BOF: WebMakers.com Added: Attributes Copy on non-linked
        $products_id_from=tep_db_input($products_id);
        $products_id_to= $dup_products_id;
        $products_id = $dup_products_id;
        if ( $HTTP_POST_VARS['copy_attributes']=='copy_attributes_yes' and $HTTP_POST_VARS['copy_as'] == 'duplicate' ) {
          // WebMakers.com Added: Copy attributes to duplicate product
          // $products_id_to= $copy_to_products_id;
          // $products_id_from = $pID;
          $copy_attributes_delete_first='1';
          $copy_attributes_duplicates_skipped='1';
          $copy_attributes_duplicates_overwrite='0';

          if (DOWNLOAD_ENABLED == 'true') {
            $copy_attributes_include_downloads='1';
            $copy_attributes_include_filename='1';
          } else {
            $copy_attributes_include_downloads='0';
            $copy_attributes_include_filename='0';
          }
          tep_copy_products_attributes($products_id_from,$products_id_to);
          // EOF: WebMakers.com Added: Attributes Copy on non-linked
        }
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
    }

    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
    break;
    // BOF MaxiDVD: Modified For Ultimate Images Pack!
    case 'new_product_preview':
    // copy image only if modified
    if (DOWNLOAD_ENABLED == true) {
      if (isset($_POST['price_prefix']) && !empty($_POST['price_prefix']) && isset($_POST['price_prefix'])) {
        foreach ($_POST['price_prefix'] as $groups => $attributes) {
          foreach ($_POST['price_prefix'][$groups] as $key => $value) {
            if (isset($_POST['price_prefix'][$groups][$key])) {
              $file = new upload('products_attributes_filename_' . $groups . '_' . $key);
              $file->set_destination(DIR_FS_DOWNLOAD);
              if ($file->parse() && $file->save()) {
                $HTTP_POST_VARS['products_attributes_filename_name'][$groups][$key] = $file->filename;
              }
            }
          }
        }
      }
    }

    if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
      $products_image = '';
      $products_image_name = '';
      if ($HTTP_POST_VARS['delete_image'] == 'yes'){
        if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image'])) {
          @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image']);
        }
      }
    } else{
      $products_image_name = $_POST['products_image'];
      $products_image = new upload('products_image_new');
      $products_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['products_image_location']);
      if ($products_image->parse() && $products_image->save()) {
        $products_image_name = (!empty($_POST['products_image_location']) ? $_POST['products_image_location'] . '/' : '') . $products_image->filename;
      } else {
        $products_image_name = (isset($HTTP_POST_VARS['products_image']) ? $HTTP_POST_VARS['products_image'] : '');
      }
    }
    if ( ($HTTP_POST_VARS['delete_products_file'] == 'yes')) {
      $products_file = '';
      $products_file_name = '';
    }else{
      $products_file = new upload('products_file');
      $products_file->set_destination(DIR_FS_DOWNLOAD);
      if ($products_file->parse() && $products_file->save()) {
        $products_file_name = $products_file->filename;
      } else {
        $products_file_name = (isset($HTTP_POST_VARS['products_previous_file']) ? $HTTP_POST_VARS['products_previous_file'] : '');
      }
    }

    if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
      $products_image_med = '';
      $products_image_med_name = '';
      if ($HTTP_POST_VARS['delete_image_med'] == 'yes'){
        if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image_med'])) {
          @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image_med']);
        }
      }      
    } else {
      $products_image_med = new upload('products_image_med_new');
      $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['products_image_med_location']);
      if ($products_image_med->parse() && $products_image_med->save()) {
        $products_image_med_name = (!empty($_POST['products_image_med_location']) ? $_POST['products_image_med_location'] . '/' : '') . $products_image_med->filename;
      } else {
        $products_image_med_name = (isset($HTTP_POST_VARS['products_image_med']) ? $HTTP_POST_VARS['products_image_med'] : '');
      }
    }
    if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
      $products_image_lrg = '';
      $products_image_lrg_name = '';
      if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes'){
        if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image_lrg'])) {
          @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_image_lrg']);
        }
      }       
    } else {
      $products_image_lrg = new upload('products_image_lrg_new');
      $products_image_lrg->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['products_image_lrg_location']);
      if ($products_image_lrg->parse() && $products_image_lrg->save()) {
        $products_image_lrg_name = (!empty($_POST['products_image_lrg_location']) ? $_POST['products_image_lrg_location'] . '/' : '') . $products_image_lrg->filename;
      } else {
        $products_image_lrg_name = (isset($HTTP_POST_VARS['products_image_lrg']) ? $HTTP_POST_VARS['products_image_lrg'] : '');
      }
    }
    // TODO resize small product image
    if ($HTTP_POST_VARS['resize_sm'] == 'yes' && $products_image_lrg_name != '') {
      $_spl = explode(".", $products_image_lrg_name);
      $image_sm_name = tep_db_prepare_input($_spl[0] . "_sm." . $_spl[1]);
      if (tep_image_resize(DIR_FS_CATALOG_IMAGES . $products_image_lrg_name, DIR_FS_CATALOG_IMAGES . $image_sm_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT)) {
        $products_image_name = $image_sm_name;
      }
    }
    // TODO resize medium product image
    if ($HTTP_POST_VARS['resize_med'] == 'yes' && $products_image_lrg_name != '') {
      $_spl = explode(".", $products_image_lrg_name);
      $image_sm_name = tep_db_prepare_input($_spl[0] . "_med." . $_spl[1]);
      if (tep_image_resize(DIR_FS_CATALOG_IMAGES . $products_image_lrg_name, DIR_FS_CATALOG_IMAGES . $image_sm_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT)) {
        $products_image_med_name = $image_sm_name;
      }
    }
    for ($i=1;$i<7;$i++) {
      $image_sm_var = 'products_image_sm_' . $i;
      $image_sm_var_name = 'products_image_sm_' . $i . '_name';
      if (($HTTP_POST_VARS['unlink_image_sm_' . $i] == 'yes') or ($HTTP_POST_VARS['delete_image_sm_' . $i] == 'yes')) {
        $$image_sm_var = '';
        $$image_sm_var_name = '';
        if ($HTTP_POST_VARS['delete_image_sm_' . $i] == 'yes'){
          if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_' . $i])) {
            @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_sm_' . $i]);
          }
        }         
      } else {
        $$image_sm_var = new upload('products_image_sm_' . $i);
        $$image_sm_var->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($$image_sm_var->parse() && $$image_sm_var->save()) {
          $$image_sm_var_name = $$image_sm_var->filename;
        } else {
          $$image_sm_var_name = (isset($HTTP_POST_VARS['products_previous_image_sm_' . $i]) ? $HTTP_POST_VARS['products_previous_image_sm_' . $i] : '');
        }
      }
      $image_xl_var = 'products_image_xl_' . $i;
      $image_xl_var_name = 'products_image_xl_' . $i . '_name';

      if (($HTTP_POST_VARS['unlink_image_xl_' . $i] == 'yes') or ($HTTP_POST_VARS['delete_image_xl_' . $i] == 'yes')) {
        $$image_xl_var = '';
        $$image_xl_var_name = '';
        if ($HTTP_POST_VARS['delete_image_xl_' . $i] == 'yes'){
          if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_' . $i])) {
            @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_xl_' . $i]);
          }
        }        
      } else {
        $$image_xl_var = new upload('products_image_xl_' . $i);
        $$image_xl_var->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($$image_xl_var->parse() && $$image_xl_var->save()) {
          $$image_xl_var_name = $$image_xl_var->filename;
        } else {
          $$image_xl_var_name = (isset($HTTP_POST_VARS['products_previous_image_xl_' . $i]) ? $HTTP_POST_VARS['products_previous_image_xl_' . $i] : '');
        }
      }
      // TODO resize small addition images
      if ($HTTP_POST_VARS['resize_xl' . $i] == 'yes' && $$image_xl_var_name != '') {
        $_spl = explode(".", $$image_xl_var_name);
        $image_sm_name = tep_db_prepare_input($_spl[0] . "_sm." . $_spl[1]);
        if (tep_image_resize(DIR_FS_CATALOG_IMAGES . $$image_xl_var_name, DIR_FS_CATALOG_IMAGES . $image_sm_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT)) {
          $$image_sm_var_name = $image_sm_name;
        }
      }
    }

    // [[ Properties
    if (PRODUCTS_PROPERTIES == 'True') {
      $languages = tep_get_languages();
      $properties_query = tep_db_query("select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' , " . TABLE_PROPERTIES_DESCRIPTION . " prd where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");
      if (tep_db_num_rows($properties_query) > 0){
        while ($properties_array = tep_db_fetch_array($properties_query)){
          if ($properties_array['properties_type'] == 5 || $properties_array['properties_type'] == 6){
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              $txt = 'properties_data_' . $properties_array['properties_id'] . '_' . $languages[$i]['id'];
              $txt_name = 'properties_data_' . $properties_array['properties_id'] . '_' . $languages[$i]['id'] . '_name';
              if ($HTTP_POST_VARS['unlink'][$properties_array['properties_id']][$languages[$i]['id']] == 'yes'){
                $$txt = '';
                $$txt_name = '';
              }else{
                $$txt = new upload('set_value_'.$properties_array['properties_id']. '_'.$languages[$i]['id']);
                $$txt->set_destination(DIR_FS_CATALOG_IMAGES . 'data/');
                if ($$txt->parse() && $$txt->save()){
                  $$txt_name = $$txt->filename;
                }else{
                  $$txt_name = (isset($HTTP_POST_VARS['set_value_previous'][$properties_array['properties_id']][$languages[$i]['id']])?$HTTP_POST_VARS['set_value_previous'][$properties_array['properties_id']][$languages[$i]['id']]:'');
                }
              }
            }
          }
        }
      }
    }
    // ]] Properties
    break;
  }
}


// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
  if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
  $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
?>
<?php
// WebMakers.com Added: Display Order
switch (true) {
  case (CATEGORIES_SORT_ORDER=="products_name"):
  $order_it_by = "pd.products_name";
  break;
  case (CATEGORIES_SORT_ORDER=="products_name-desc"):
  $order_it_by = "pd.products_name DESC";
  break;
  case (CATEGORIES_SORT_ORDER=="model"):
  $order_it_by = "p.products_model";
  break;
  case (CATEGORIES_SORT_ORDER=="model-desc"):
  $order_it_by = "p.products_model DESC";
  break;
  default:
  $order_it_by = "pd.products_name";
  break;
}
?>

<?php
$go_back_to=$REQUEST_URI;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<?php
include(DIR_WS_INCLUDES . 'javascript/xml_used.js.php');
// WebMakers.com Added: Java Scripts - popup window
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
//onload="SetFocus();"
?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" >
<!-- header //-->
<?
echo tep_init_calendar();

if(true)
{
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
  if ($HTTP_GET_VARS['cPath'] != ''){
    $ar = split("_", $HTTP_GET_VARS['cPath']);
    $header_additional = '';
    $cPath_add = '';
    for ($i=0,$n=sizeof($ar);$i<$n;$i++){
      if ($header_additional != ''){
        $header_additional .= '&nbsp;::&nbsp;';
      }
      if ($cPath_add != ''){
        $cPath_add .= '_';
      }
      $cPath_add .= $ar[$i];
      $header_additional .= '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath_add) . '">' . tep_get_category_name($ar[$i], $languages_id) . '</a>';
    }
  }
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('search', FILENAME_CATEGORIES, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search').'</form><br>';
  $header_title_additional.=tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get').HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"').'</form>';
}
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
<tr>
<td width=100% height=25 colspan=2>
<table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
<tr>
<td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
<td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
</tr>
</table>
</td>
</tr>
</tr>
<tr>
<td valign=top>
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
</table>
</td>
<td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
</tr>
</table></td>
<!-- body_text //-->
<td width="100%" valign="top" height="100%">
<?php   //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
if ($HTTP_GET_VARS['action'] == 'new_category_ACD' || $HTTP_GET_VARS['action'] == 'edit_category_ACD') {
  require('pages/categories_edit.php');
  //----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
} elseif ($HTTP_GET_VARS['action'] == 'new_category_preview') {
  if ($HTTP_POST_VARS) {

    if (($HTTP_POST_VARS['unlink_categories_image'] == 'yes') or ($HTTP_POST_VARS['delete_categories_image'] == 'yes')) {
      if ($HTTP_POST_VARS['delete_categories_image'] == 'yes'){
        $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
        $duplicate_image = tep_db_fetch_array($duplicate_image_query);
        if ($duplicate_image['total'] < 2) {
          if (file_exists(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['categories_previous_image'])) {
            @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['categories_previous_image']);
          }
        }
      }
      $categories_image = '';
      $categories_image_name = '';
    } else {    
      $categories_image = new upload('categories_image');
      $categories_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['categories_image_location']);
      if ($categories_image->parse() && $categories_image->save()) {
        $categories_image_name = (!empty($_POST['categories_image_location']) ? $_POST['categories_image_location'] . '/' : '') . $categories_image->filename;
      } else {
        $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
      }
    }
    $cInfo = new objectInfo($HTTP_POST_VARS);
    $categories_name = $HTTP_POST_VARS['categories_name'];
    $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
    $categories_description = $HTTP_POST_VARS['categories_description'];
    $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
    $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
    $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];

  } else {
    $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $HTTP_GET_VARS['cID'] . "'");
    $category = tep_db_fetch_array($category_query);
    $cInfo = new objectInfo($category);
    $categories_image_name = $cInfo->categories_image;
  }

  $form_action = ($HTTP_GET_VARS['cID']) ? 'update_category' : 'insert_category';

  echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
  ?>

  <table valign=top border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
  <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
  </tr>
  <?
  $languages = tep_get_languages();
  for ($i=0; $i<sizeof($languages); $i++) {
    if ($HTTP_GET_VARS['read'] == 'only') {
      $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
      $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
      $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
    } else {
      $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
      $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
      $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
    }
    ?>

    <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
    <td class="pageHeading"><?php
    $d_text = '';
    if (!empty($cInfo->categories_name) and ($cInfo->categories_name!='')) $d_text .= TEXT_EDIT_CATEGORIES_NAME.' '.$cInfo->categories_name;
    if (!empty($cInfo->categories_heading_title) and ($cInfo->categories_heading_title!='')) $d_text .= '<br>'.TEXT_EDIT_CATEGORIES_HEADING_TITLE.' '.$cInfo->categories_heading_title;
    echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $d_text; ?></td>
    </tr>
    </table></td>
    </tr>
    <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
    <td class="main"><?php if (!empty($cInfo->categories_image)){ 
      echo tep_image(DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->categories_description;
    } ?></td>
    </tr>
    <?php
  }
  if ($HTTP_GET_VARS['read'] == 'only') {
    if ($HTTP_GET_VARS['origin']) {
      $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
      if ($pos_params != false) {
        $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
        $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
      } else {
        $back_url = $HTTP_GET_VARS['origin'];
        $back_url_params = '';
      }
    } else {
      $back_url = FILENAME_CATEGORIES;
      $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
    }
    ?>
    <tr>
    <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
    </tr>

    <?php
  } else {
    ?>
    <tr>
    <td align="right" class="smallText">
    <?php
    /* Re-Post all POST'ed variables */
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if (!is_array($HTTP_POST_VARS[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      echo tep_draw_hidden_field('categories_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_name[$languages[$i]['id']])));
      echo tep_draw_hidden_field('categories_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_heading_title[$languages[$i]['id']])));
      echo tep_draw_hidden_field('categories_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
      echo tep_draw_hidden_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_head_title_tag[$languages[$i]['id']])));
      echo tep_draw_hidden_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_head_desc_tag[$languages[$i]['id']])));
      echo tep_draw_hidden_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_head_keywords_tag[$languages[$i]['id']])));

    }
    echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name));
    echo tep_draw_hidden_field('categories_image', stripslashes($categories_image_name));

    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if (!is_array($HTTP_POST_VARS[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      } else {
        while (list($k, $v) = each($value)) {
          if (!is_array($HTTP_POST_VARS[$key][$k])){
            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
          }else{
            while (list($k1, $v1) = each($v)){
              if (!is_array($HTTP_POST_VARS[$key][$k][$k1])){
                echo tep_draw_hidden_field($key . '[' . $k . '][' .$k1. ']', htmlspecialchars(stripslashes($v1)));
              }else{
                while (list($k2, $v2) = each($v1)){
                  echo tep_draw_hidden_field($key. '[' . $k . '][' .$k1. '][' . $k2 . ']', htmlspecialchars(stripslashes($v2)));
                }
              }
            }
          }
        }
      }
    }

    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

    if ($HTTP_GET_VARS['cID']) {
      echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
    } else {
      echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
    }
    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    ?></td>
    </form></tr>
    <?php
  }
  ?>
  </table>
  <?
} elseif ($action == 'new_product') {
  require('pages/product_edit.php');
} elseif ($action == 'new_product_preview') {
  if (tep_not_null($HTTP_POST_VARS)) {
    $HTTP_POST_VARS['products_date_available'] = tep_calendar_rawdate($HTTP_POST_VARS['products_date_available']);
    $pInfo = new objectInfo($HTTP_POST_VARS);
    $products_name = $HTTP_POST_VARS['products_name'];
    $products_description = $HTTP_POST_VARS['products_description'];
    $products_ebay_description = $HTTP_POST_VARS['products_ebay_description'];
    $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
    $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
    $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
    $products_url = $HTTP_POST_VARS['products_url'];
    $products_price = $HTTP_POST_VARS['products_price'];
  } else {
    // BOF MaxiDVD: Modified For Ultimate Images Pack!
    $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_ebay_description, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_quantity, p.products_model, p.sort_order, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
    // EOF MaxiDVD: Modified For Ultimate Images Pack!
    $product = tep_db_fetch_array($product_query);

    $pInfo = new objectInfo($product);
    $products_image_name = $pInfo->products_image;
    $products_image_med_name = $pInfo->products_image_med;
    $products_image_lrg_name = $pInfo->products_image_lrg;
    $products_image_sm_1_name = $pInfo->products_image_sm_1;
    $products_image_sm_2_name = $pInfo->products_image_sm_2;
    $products_image_sm_3_name = $pInfo->products_image_sm_3;
    $products_image_sm_4_name = $pInfo->products_image_sm_4;
    $products_image_sm_5_name = $pInfo->products_image_sm_5;
    $products_image_sm_6_name = $pInfo->products_image_sm_6;
    $products_image_xl_1_name = $pInfo->products_image_xl_1;
    $products_image_xl_2_name = $pInfo->products_image_xl_2;
    $products_image_xl_3_name = $pInfo->products_image_xl_3;
    $products_image_xl_4_name = $pInfo->products_image_xl_4;
    $products_image_xl_5_name = $pInfo->products_image_xl_5;
    $products_image_xl_6_name = $pInfo->products_image_xl_6;
    $products_file_name = $pInfo->products_file;
  }

  $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

  echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . (isset($HTTP_GET_VARS['mID']) ? '&mID=' . $HTTP_GET_VARS['mID'] : '') . (isset($HTTP_GET_VARS['bm']) ? '&bm=' . $HTTP_GET_VARS['bm'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
  <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
  </tr>
  <?
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
      $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
      $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
      $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
      $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
      $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      if (USE_MARKET_PRICES == 'True'){
        $pInfo->products_price =  tep_get_products_price($pInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id']);
      }
    } else {
      $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
      $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
      $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
      $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
      $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
      $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      if (USE_MARKET_PRICES == 'True'){
        $pInfo->products_price =  tep_db_prepare_input($products_price[$currencies->currencies[DEFAULT_CURRENCY]['id']]);
      }
    }
    ?>
    <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
    <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
    <td class="pageHeading" align="right"><?php

    echo $currencies->format($pInfo->products_price);
    ?></td>
    </tr>
    </table></td>
    </tr>
    <!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! // -->
    <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <tr>
    <td class="main">
    <?php if ($products_image_med_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_med_name, $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); } elseif ($products_image_lrg_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');}; ?>
    <?php echo $pInfo->products_description . '<br><br><center>'; ?>
    <?php if ($products_image_xl_1_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_1_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_1_name, $products_image_sm_1_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_1_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_1_name, $products_image_sm_1_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
    <?php if ($products_image_xl_2_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_2_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_2_name, $products_image_sm_2_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_2_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_2_name, $products_image_sm_2_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
    <?php if ($products_image_xl_3_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_3_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_3_name, $products_image_sm_3_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_3_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_3_name, $products_image_sm_3_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
    <?php if ($products_image_xl_4_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_4_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_4_name, $products_image_sm_4_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_4_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_4_name, $products_image_sm_4_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
    <?php if ($products_image_xl_5_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_5_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_5_name, $products_image_sm_5_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_5_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_5_name, $products_image_sm_5_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
    <?php if ($products_image_xl_6_name) { ?>
    <script language="javascript"><!--
    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_6_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_6_name, $products_image_sm_6_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"') . '</a>'; ?>');
    //--></script>
    <?php } elseif ($products_image_sm_6_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_sm_6_name, $products_image_sm_6_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"'); }; ?>
    </td>
    </tr>
    <!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! // -->

    <?php
    if ($pInfo->products_url) {
      ?>
      <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
      <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
      <?php
    }
    ?>
    <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <?php
    if ($pInfo->products_date_available > date('Y-m-d')) {
      ?>
      <tr>
      <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
      <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
      </tr>
      <?php
    }
    ?>
    <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
    </tr>
    <?php
  }

  if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
    if (isset($HTTP_GET_VARS['origin'])) {
      $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
      if ($pos_params != false) {
        $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
        $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
      } else {
        $back_url = $HTTP_GET_VARS['origin'];
        $back_url_params = '';
      }
    } else {
      $back_url = FILENAME_CATEGORIES;
      $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
    }
    ?>
    <tr>
    <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
    </tr>
    <?php
  } else {
    ?>
    <tr>
    <td align="right" class="smallText">
    <?php
    /////////////////////////////////////////////////////////////////////
    // BOF: WebMakers.com Added: Preview Back
    /* Re-Post all POST'ed variables */
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if (!is_array($HTTP_POST_VARS[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      } else {
        while (list($k, $v) = each($value)) {
          if (!is_array($HTTP_POST_VARS[$key][$k])){
            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
          }else{
            while (list($k1, $v1) = each($v)){
              if (!is_array($HTTP_POST_VARS[$key][$k][$k1])){
                echo tep_draw_hidden_field($key . '[' . $k . '][' .$k1. ']', htmlspecialchars(stripslashes($v1)));
              }else{
                while (list($k2, $v2) = each($v1)){
                  if (!is_array($HTTP_POST_VARS[$key][$k][$k1][$k2])){
                  echo tep_draw_hidden_field($key. '[' . $k . '][' .$k1. '][' . $k2 . ']', htmlspecialchars(stripslashes($v2)));
                  }else{
                    while (list($k3, $v3) = each($v2)){
                      echo tep_draw_hidden_field($key. '[' . $k . '][' .$k1. '][' . $k2 . '][' . $k3 . ']', htmlspecialchars(stripslashes($v3)));
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
      echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
      echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
      echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
      echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
      echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
    }
    // EOF: WebMakers.com Added: Preview Back
    /////////////////////////////////////////////////////////////////////
    ?>

    <?php
    /////////////////////////////////////////////////////////////////////
    echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
    // BOF MaxiDVD: Added For Ultimate Images Pack!
    echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_name));
    echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_name));
    echo tep_draw_hidden_field('products_image_sm_1', stripslashes($products_image_sm_1_name));
    echo tep_draw_hidden_field('products_image_xl_1', stripslashes($products_image_xl_1_name));
    echo tep_draw_hidden_field('products_image_sm_2', stripslashes($products_image_sm_2_name));
    echo tep_draw_hidden_field('products_image_xl_2', stripslashes($products_image_xl_2_name));
    echo tep_draw_hidden_field('products_image_sm_3', stripslashes($products_image_sm_3_name));
    echo tep_draw_hidden_field('products_image_xl_3', stripslashes($products_image_xl_3_name));
    echo tep_draw_hidden_field('products_image_sm_4', stripslashes($products_image_sm_4_name));
    echo tep_draw_hidden_field('products_image_xl_4', stripslashes($products_image_xl_4_name));
    echo tep_draw_hidden_field('products_image_sm_5', stripslashes($products_image_sm_5_name));
    echo tep_draw_hidden_field('products_image_xl_5', stripslashes($products_image_xl_5_name));
    echo tep_draw_hidden_field('products_image_sm_6', stripslashes($products_image_sm_6_name));
    echo tep_draw_hidden_field('products_image_xl_6', stripslashes($products_image_xl_6_name));
    echo tep_draw_hidden_field('products_file', stripslashes($products_file_name));
    // EOF MaxiDVD: Added For Ultimate Images Pack!

    // [[ Properties
    if (PRODUCTS_PROPERTIES == 'True'){
      $properties_query = tep_db_query("select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "', " . TABLE_PROPERTIES_DESCRIPTION . " prd where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");
      if (tep_db_num_rows($properties_query) > 0){
        while ($properties_array = tep_db_fetch_array($properties_query)){
          if ($properties_array['properties_type'] == 5 || $properties_array['properties_type'] == 6){
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              $txt_name = 'properties_data_' . $properties_array['properties_id'] . '_' . $languages[$i]['id'] . '_name';
              echo tep_draw_hidden_field('properties_data['.$properties_array['properties_id'].']['.$languages[$i]['id'].']', stripslashes($$txt_name));
            }
          }
        }
      }
    }
    // ]] Properties

    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

    if (isset($HTTP_GET_VARS['pID'])) {
      echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
    } else {
      echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
    }
    if ($bm == 1) {
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'mID=' . $mID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    } else {
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    }
    ?></td>
    </tr>
    </form>
    <?php
  }
  ?>
  </table>
  <?
} else {
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%" valign=top>
  <tr valign=top >
  <td><table border="0" width="100%" cellspacing="0" cellpadding="0" height=100%>
  <tr>
  <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="dataTableHeadingRow">
  <?php if (XML_DUMP_ENABLE == "True") {?>
  <td class="dataTableHeadingContent" colspan=2 align="center"><?php echo TEXT_XML_DUMP; ?></td>
  <?php }?>  
  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
  <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
  </tr>
  <?php
  $categories_count = 0;
  $rows = 0;
  if (isset($HTTP_GET_VARS['search'])) {
    $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.last_xml_export from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.affiliate_id = 0 and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
  } else {
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.last_xml_export from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.affiliate_id = 0 and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
  }
  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_count++;
    $rows++;

    // Get parent_id for subcategories if search
    if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

    if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
      $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

      $cInfo_array = array_merge($categories, $category_childs, $category_products);
      $cInfo = new objectInfo($cInfo_array);
    }

    if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
      if (isset($HTTP_GET_VARS['search'])) $cPath_selected = $categories['parent_id'];
      $on_click_effect = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'"';
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
    } else {
      $on_click_effect = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'"';
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
    }
    ?>
    <?php if (XML_DUMP_ENABLE == "True") {?>
          <td align="center" style="width:5px"><input type="checkbox" id="cat<?php echo $categories['categories_id'];?>" values="<?php echo $categories['categories_id'];?>" onClick="javascript:setflagcookie('<?php echo $categories['categories_id'];?>','xml_categories','cat')"></td>
          <td <?php echo $on_click_effect; ?>>
          <?php
            if (tep_not_null($categories["last_xml_export"])) {
              echo tep_image(DIR_WS_IMAGES.'icons/success.gif',sprintf(TEXT_LAST_XML_DUMP,$categories["last_xml_export"]),10,10);
            } else {
              echo tep_image(DIR_WS_IMAGES.'icons/error.gif',TEXT_NEVER_EXPORTED,10,10);
            }

          ?>
          </td>
    <?php } ?>
    <td class="dataTableContent" <?php echo $on_click_effect; ?>><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
    <td class="dataTableContent" align="center" <?php echo $on_click_effect; ?>>
    <?php
    if ($categories['categories_status'] == '1') {
      echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
    }
    ?>
    </td>
    <td class="dataTableContent" align="right" <?php echo $on_click_effect; ?>><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
    </tr>
    <?php
  }

  $products_count = 0;
  if (isset($HTTP_GET_VARS['search'])) {
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p.sort_order, p2c.categories_id, p.last_xml_export from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id " . (tep_session_is_registered('login_vendor')?" and p.vendor_id = '" . $login_id . "'":''). " and (pd.products_name like '%" . tep_db_input($search) . "%' or p.products_model like '%" . tep_db_input($search) . "%') and pd.affiliate_id = 0 order by p.sort_order, pd.products_name");
  } else {
    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p.sort_order, p.last_xml_export from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id " . (tep_session_is_registered('login_vendor')?" and p.vendor_id = '" . $login_id . "'":''). " and pd.language_id = '" . (int)$languages_id . "'  and pd.affiliate_id = 0 and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by p.sort_order, pd.products_name");
  }
  while ($products = tep_db_fetch_array($products_query)) {
    $products_count++;
    $rows++;

    // Get categories_id for product if search
    if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];

    if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      // find out the rating average from customer reviews
      $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
      $reviews = tep_db_fetch_array($reviews_query);
      $pInfo_array = array_merge($products, $reviews);
      $pInfo = new objectInfo($pInfo_array);
    }

    if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
      $on_click_effect = '  onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'"';
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      if (isset($HTTP_GET_VARS['search'])) $cPath_selected = $products['categories_id'];
    } else {
      $on_click_effect = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'"';
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
    }
    ?>
    

    <?php if (XML_DUMP_ENABLE == "True") {?>
    <td align="center" style="width:5px"><input type="checkbox" id="<?php echo $products['products_id'];?>" onClick="javascript:setflagcookie('<?php echo $products['products_id'];?>','xml_products','')"></td>
    <td class="dataTableContent" <?php echo $on_click_effect; ?> align="center">
    <?php
      if (tep_not_null($products["last_xml_export"])) {
        echo tep_image(DIR_WS_IMAGES.'icons/success.gif',sprintf(TEXT_LAST_XML_DUMP,$products["last_xml_export"]),10,10);
      } else {
        echo tep_image(DIR_WS_IMAGES.'icons/error.gif',TEXT_NEVER_EXPORTED,10,10);
      }

    ?>
    </td>
    <?php }?>    
    <td class="dataTableContent" <?php echo $on_click_effect; ?>><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
    <td class="dataTableContent" align="center" <?php echo $on_click_effect; ?>>
    <?php
    if ($products['products_status'] == '1') {
      echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
    }
    ?></td>
    <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
    </tr>
    <?php
  }
  if (isset($HTTP_GET_VARS['search'])) $cPath = $cPath_selected;
  $cPath_back = '';
  if (sizeof($cPath_array) > 0) {
    for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
      if (empty($cPath_back)) {
        $cPath_back .= $cPath_array[$i];
      } else {
        $cPath_back .= '_' . $cPath_array[$i];
      }
    }
  }

  $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
  ?>
  <tr>
  <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
  <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
  <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
  </tr>
       <?php if (XML_DUMP_ENABLE == "True") {?>
       <tr>
        <td class="smallText" colspan=2 align="center"><?php echo $backup_to_xml;?></td>
      </tr>
      <?php 
      if ($can_backup_xml) {?>
       <tr>
        <td class="smallText" colspan=2 align="center">
          <a href="<?php echo tep_href_link(FILENAME_BACKUP_XML_DATA,'action=all&datatype=products')?>"><?php echo TEXT_XML_ALL_PRODUCTS;?></a> |
          <a onClick="javascript:check_selected_datas('xml_products','products');" href="#"><?php echo TEXT_XML_SELECTED_PRODUCTS;?></a><br><br>
          <a onClick="javascript:check_selected_datas('xml_categories','categories');" href="#"><?php echo TEXT_XML_SELECTED_CATEGORIES;?></a>

        </td>
      </tr>
      <? }?>
       <tr>
        <td class="smallText" colspan=2 align="center"><br><br></td>
      </tr>
      <?php }?>  
  </table></td>
  </tr>
  </table></td>
  <?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'new_category':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

    $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
    $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

    $category_inputs_string = '';
    $languages = tep_get_languages();
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
    }

    $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
    $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
    $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'edit_category':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

    $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
    $contents[] = array('text' => TEXT_EDIT_INTRO);

    $category_inputs_string = '';
    $languages = tep_get_languages();
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
    }

    $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
    $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
    $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'delete_category':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

    $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
    $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
    $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
    if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
    if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'move_category':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

    $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
    $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
    $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'delete_product':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

    $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
    $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
    $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

    $product_categories_string = '';
    $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
    for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
      $category_path = '';
      for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
        $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $category_path = substr($category_path, 0, -16);
      $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
    }
    $product_categories_string = substr($product_categories_string, 0, -4);

    $contents[] = array('text' => '<br>' . $product_categories_string);
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'move_product':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

    $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
    $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
    $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
    $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'copy_to':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

    $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
    $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
    $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
    $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
    $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
    // BOF: WebMakers.com Added: Attributes Copy
    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
    // only ask about attributes if they exist
    if (tep_has_product_attributes($pInfo->products_id)) {
      $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
      $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
      $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
      $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
    }
    // EOF: WebMakers.com Added: Attributes Copy

    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;

    /////////////////////////////////////////////////////////////////////
    // WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
    case 'copy_product_attributes':
    $copy_attributes_delete_first=1;

    $heading[] = array('text' => '<b>' . 'Copy Attributes to another product' . '</b>');
    $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
    $contents[] = array('text' => '<br>Copying Attributes from #' . $pInfo->products_id . '<br><b>' . $pInfo->products_name . '</b>');
    $contents[] = array('text' => 'Copying Attributes to #&nbsp;' . tep_draw_input_field('copy_to_products_id', $copy_to_products_id, 'size="3"'));
    $contents[] = array('text' => '<br>Delete ALL Attributes before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first', '1', $copy_attributes_delete_first, 'size="2"'));
    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));

    $contents[] = array('text' => 'Skip Duplicated Attributes  &nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped', '1'));

    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
    if ($pID) {
      $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
    } else {
      $contents[] = array('align' => 'center', 'text' => '<br>Select a product for display');
    }
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    // WebMakers.com Added: Copy Attributes Existing Product to All Products in Category
    case 'copy_product_attributes_categories':
    $copy_attributes_delete_first='1';
    $copy_attributes_duplicates_skipped='1';
    $copy_attributes_duplicates_overwrite='0';

    $heading[] = array('text' => '<b>' . 'Copy Product Attributes to Category ...' . '</b>');
    $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
    $contents[] = array('text' => 'Copy Product Attributes from Product ID#&nbsp;' . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"'));
    $contents[] = array('text' => '<br>Copying to all products in Category ID#&nbsp;' . $cID . '<br>Category Name: <b>' . tep_get_category_name($cID, $languages_id) . '</b>');
    $contents[] = array('text' => '<br>Delete ALL Attributes before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
    $contents[] = array('text' => '<br>' . 'Otherwise ...');
    $contents[] = array('text' => 'Duplicate Attributes should be skipped&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
    $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Duplicate Attributes should be overwritten&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));

    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    case 'empty':
    $heading[] = array('text' => '<b>' . TEXT_DELETE_TEST_DATA . '</b>');
    $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=empty_database', 'post', 'onsubmit="return confirm(\'' . JS_TEXT_DELETE_TEST_DATA . '\')"'));
    $contents[] = array('align' => 'left', 'text' => tep_draw_checkbox_field('products', '1', true) . '&nbsp;' . TEXT_PRODUCTS_AND_CATEGORIES_ONLY);
    $contents[] = array('align' => 'left', 'text' => tep_draw_checkbox_field('customers', '1', true) . '&nbsp;' . TEXT_CUSTOMERS_ONLY);
    $contents[] = array('align' => 'left', 'text' => tep_draw_checkbox_field('orders', '1', true) . '&nbsp;' . TEXT_ORDERS_ONLY);
    $contents[] = array('align' => 'center', 'text' => tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
    break;
    default:
    if ($rows > 0) {
      if (isset($cInfo) && is_object($cInfo)) { // category info box contents
        $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

        if (!tep_session_is_registered('login_vendor')){
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
        }
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
        $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
        if (!tep_session_is_registered('login_vendor')){
          if ($cInfo->childs_count==0 and $cInfo->products_count >= 1) {
            $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
            if ($cID) {
              $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=copy_product_attributes_categories') . '">' . 'Copy Product Attributes to <br>ALL products in Category: ' . tep_get_category_name($cID, $languages_id) . '<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
            } else {
              $contents[] = array('align' => 'center', 'text' => '<br>Select a Category to copy attributes to');
            }
          }
        }
      } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
        $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
        if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
        if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
        $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
        if (USE_MARKET_PRICES == 'True'){
          $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format(tep_get_products_price($pInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id'])) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
        }else{
          $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
        }
        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . ' ' . $pInfo->sort_order);
        // WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_black.gif','','100%','3'));
        if (!tep_session_is_registered('login_vendor')){
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '">' . 'Products Attributes Copier:<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
          if ($pID) {
            $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
          } else {
            $contents[] = array('align' => 'center', 'text' => '<br>Select a product to display attributes');
          }
        }
      }
    } else { // create category/product info
      $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

      $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
    }
    break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
  }
  ?>
  </tr>
  </table></td>
  </tr>
  </table>
  <?php
}
?>
</td>
<!-- body_text_eof //-->
</tr>
</table>
<?php if (XML_DUMP_ENABLE == "True") {?>
           <script language="Javascript">
             restoreBoxes("xml_products","");
             restoreBoxes("xml_categories","cat");
           </script>
<?php }?>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
