<?php
/*
  $Id: easypopulate.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
*/

require('includes/application_top.php');

require_once(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

define('TEMP_DIR', 'temp/');
define('MAX_CSV_LINE_LENGTH',1000000);

// Global variables definitions
// Tab delitemed ep file
global $separator;
$separator = "\t"; // tab is default

global $max_categories; // maximun categories in which product can be located.
$max_categories = 7; // 7 is default

global $report_string;
$report_string = '';
//Fields definition array for table products
global $keyfieldindex;
$keyfieldindex = 0;

$fields = array();
$fields[] = array('name' => 'products_model', 'value' => 'Products Model');
$fields[] = array('name' => 'products_quantity', 'value' => 'Products Quantity');
$fields[] = array('name' => 'products_image', 'value' => 'Products Image');
$fields[] = array('name' => 'products_image_med', 'value' => 'Products Medium Image');
$fields[] = array('name' => 'products_image_lrg', 'value' => 'Products Large Image');
$fields[] = array('name' => 'products_image_sm_1', 'value' => 'Products Small Image 1');
$fields[] = array('name' => 'products_image_xl_1', 'value' => 'Products Large Image 1');
$fields[] = array('name' => 'products_image_alt_1', 'value' => 'Products Image 1 Alt');
$fields[] = array('name' => 'products_image_sm_2', 'value' => 'Products Small Image 2');
$fields[] = array('name' => 'products_image_xl_2', 'value' => 'Products Large Image 2');
$fields[] = array('name' => 'products_image_alt_2', 'value' => 'Products Image 2 Alt');
$fields[] = array('name' => 'products_image_sm_3', 'value' => 'Products Small Image 3');
$fields[] = array('name' => 'products_image_xl_3', 'value' => 'Products Large Image 3');
$fields[] = array('name' => 'products_image_alt_3', 'value' => 'Products Image 3 Alt');
$fields[] = array('name' => 'products_image_sm_4', 'value' => 'Products Small Image 4');
$fields[] = array('name' => 'products_image_xl_4', 'value' => 'Products Large Image 4');
$fields[] = array('name' => 'products_image_alt_4', 'value' => 'Products Image 4 Alt');
$fields[] = array('name' => 'products_image_sm_5', 'value' => 'Products Small Image 5');
$fields[] = array('name' => 'products_image_xl_5', 'value' => 'Products Large Image 5');
$fields[] = array('name' => 'products_image_alt_5', 'value' => 'Products Image 5 Alt');
$fields[] = array('name' => 'products_image_sm_6', 'value' => 'Products Small Image 6');
$fields[] = array('name' => 'products_image_xl_6', 'value' => 'Products Large Image 6');
$fields[] = array('name' => 'products_image_alt_6', 'value' => 'Products Image 6 Alt');
$fields[] = array('name' => 'sort_order', 'value' => 'Products Sort order');
$fields[] = array('name' => 'products_date_available', 'value' => 'Products Date Available');
$fields[] = array('name' => 'products_weight', 'value' => 'Products Weigth');

$fields[] = array('name' => 'products_mpn', 'value' => 'Products MPN');
$fields[] = array('name' => 'products_ean', 'value' => 'Products EAN');
$fields[] = array('name' => 'products_upc', 'value' => 'Products UPC');

$fields[] = array('name' => 'products_status', 'value' => 'Products Status');
if (SEARCH_ENGINE_UNHIDE == 'True'){
  $fields[] = array('name' => 'products_seo_page_name', 'value' => 'Products SEO page name');
}

/*
$fields[] = array('name' => 'products_aaa', 'value' => 'Products AAA', 'get' => 'aaa', 'set' => 'aaa_set');

function aaa($field_name, $products_id){
  return $field_name . $products_id;
}
function aaa_set($field_name, $products_id, $value){
  echo $field_name . ' ' . $products_id . ' ' . $value;
}
*/

function get_products_price($field_name, $products_id){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($group_id == 0 && $cur_id == 0){
      $query = tep_db_query("select products_price as products_group_price from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
      $data = tep_db_fetch_array($query);
    }else{
      $query = tep_db_query("select products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if ( tep_db_num_rows($query)==0 ) return -2;
      $data = tep_db_fetch_array($query);
    }
    return $data['products_group_price'];
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $query = tep_db_query("select products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if ( tep_db_num_rows($query)==0 ) return -2;
    $data = tep_db_fetch_array($query);
    return $data['products_group_price'];
  }
}

function set_products_price($field_name, $products_id, $value){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }    
    if ($cur_id == 0 && $group_id == 0){
      tep_db_query("update " . TABLE_PRODUCTS . " set products_price = '" . $value . "' where products_id = '" . $products_id . "'" );
    }else{
      $check = tep_db_query("select products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if (tep_db_num_rows($check)){
        tep_db_query("update " . TABLE_PRODUCTS_PRICES . " set products_group_price = '" . $value . "' where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'" );
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " set products_group_price = '" . $value . "', products_id = '" . $products_id . "', currencies_id = '" . $cur_id . "', groups_id = '" . $group_id . "'" );
      }
    }
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $check = tep_db_query("select products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if (tep_db_num_rows($check)){
      tep_db_query("update " . TABLE_PRODUCTS_PRICES . " set products_group_price = '" . $value . "' where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0" );
    }else{
      tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " set products_group_price = '" . $value . "', products_id = '" . $products_id . "', currencies_id = '" . $cur_id . "', groups_id = 0" );
    }
  }
}

function get_products_discount_price($field_name, $products_id){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($group_id == 0 && $cur_id == 0){
      $query = tep_db_query("select products_price_discount as products_group_discount_price from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
      $data = tep_db_fetch_array($query);
    }else{
      $query = tep_db_query("select products_group_discount_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      $data = tep_db_fetch_array($query);
    }
    return $data['products_group_discount_price'];
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $query = tep_db_query("select products_group_discount_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    $data = tep_db_fetch_array($query);
    return $data['products_group_discount_price'];
  }
}

function set_products_discount_price($field_name, $products_id, $value){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($cur_id == 0 && $group_id == 0){
      tep_db_query("update " . TABLE_PRODUCTS . " set products_price_discount = '" . $value . "' where products_id = '" . $products_id . "'" );
    }else{
      $check = tep_db_query("select products_group_discount_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if (tep_db_num_rows($check)){
        tep_db_query("update " . TABLE_PRODUCTS_PRICES . " set products_group_discount_price = '" . $value . "' where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'" );
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " set products_group_discount_price = '" . $value . "', products_id = '" . $products_id . "', currencies_id = '" . $cur_id . "', groups_id = '" . $group_id . "'" );
      }
    }
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $check = tep_db_query("select products_group_discount_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if (tep_db_num_rows($check)){
      tep_db_query("update " . TABLE_PRODUCTS_PRICES . " set products_group_discount_price = '" . $value . "' where products_id = '" . $products_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0" );
    }else{
      tep_db_query("insert into " . TABLE_PRODUCTS_PRICES . " set products_group_discount_price = '" . $value . "', products_id = '" . $products_id . "', currencies_id = '" . $cur_id . "', groups_id = 0" );
    }
  }
}



if (USE_MARKET_PRICES == 'True'){
  foreach ($currencies->currencies as $key => $value){
    if (CUSTOMERS_GROUPS_ENABLE == 'True'){
      $groups_query = tep_db_query("select * from " . TABLE_GROUPS);
      $fields[] = array('name' => 'products_price_' . $value['id'] . '_0', 'value' => 'Products Price ' . $key, 'get' => 'get_products_price', 'set' => 'set_products_price');
      if (DISCOUNT_TABLE_ENABLE == 'True'){
        $fields[] = array('name' => 'products_price_discount_' . $value['id'] . '_0', 'value' => 'Products Discount Price ' . $key, 'get' => 'get_products_discount_price', 'set' => 'set_products_discount_price');
      }      
      while ($groups_data = tep_db_fetch_array($groups_query)){
        $fields[] = array('name' => 'products_price_' . $value['id'] . '_' . $groups_data['groups_id'], 'value' => 'Products Price ' . $key . ' ' . $groups_data['groups_name'], 'get' => 'get_products_price', 'set' => 'set_products_price');
        if (DISCOUNT_TABLE_ENABLE == 'True'){
          $fields[] = array('name' => 'products_price_discount_' . $value['id'] . '_' . $groups_data['groups_id'], 'value' => 'Products Discount Price ' . $key . ' ' . $groups_data['groups_name'], 'get' => 'get_products_discount_price', 'set' => 'set_products_discount_price');
        }

      }
    }else{
      $fields[] = array('name' => 'products_price_' . $value['id'], 'value' => 'Products Price ' . $key, 'get' => 'get_products_price', 'set' => 'set_products_price');
      if (DISCOUNT_TABLE_ENABLE == 'True'){
        $fields[] = array('name' => 'products_price_discount_' . $value['id'], 'value' => 'Products Discount Price ' . $key, 'get' => 'get_products_discount_price', 'set' => 'set_products_discount_price');
      }
    }
  }
}elseif(CUSTOMERS_GROUPS_ENABLE == 'True' && USE_MARKET_PRICES != 'True'){
  $groups_query = tep_db_query("select * from " . TABLE_GROUPS);
  $fields[] = array('name' => 'products_price_0', 'value' => 'Products Price ', 'get' => 'get_products_price', 'set' => 'set_products_price');
  if (DISCOUNT_TABLE_ENABLE == 'True'){
    $fields[] = array('name' => 'products_price_discount_0', 'value' => 'Products Discount Price', 'get' => 'get_products_discount_price', 'set' => 'set_products_discount_price');
  }

  while ($groups_data = tep_db_fetch_array($groups_query)){
    $fields[] = array('name' => 'products_price_' . $groups_data['groups_id'], 'value' => 'Products Price ' . $groups_data['groups_name'], 'get' => 'get_products_price', 'set' => 'set_products_price');
    if (DISCOUNT_TABLE_ENABLE == 'True'){
      $fields[] = array('name' => 'products_price_discount_' . $groups_data['groups_id'], 'value' => 'Products Discount Price ' . $groups_data['groups_name'], 'get' => 'get_products_discount_price', 'set' => 'set_products_discount_price');
    }
  }
}else{
  $fields[] = array('name' => 'products_price', 'value' => 'Products Price');
  if (DISCOUNT_TABLE_ENABLE == 'True'){
    $fields[] = array('name' => 'products_price_discount', 'value' => 'Products Discount Price');
  }
}

$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
$txt = '';
while ($tax_class = tep_db_fetch_array($tax_class_query)) {
  $txt .= $tax_class['tax_class_id'] . '=' . $tax_class['tax_class_title'] . ';';
}
$fields[] = array('name' => 'products_tax_class_id', 'value' => 'Products tax Class possible values: ' . $txt);

//Language Fields from products_deacription
$fields_languages = array();

$fields_languages[] = array('name' => 'products_name', 'value' => 'Products Name');
$fields_languages[] = array('name' => 'products_description', 'value' => 'Products Description');
$fields_languages[] = array('name' => 'products_url', 'value' => 'Products URL');
if (SEARCH_ENGINE_UNHIDE == 'True'){
  $fields_languages[] = array('name' => 'products_head_title_tag', 'value' => 'Products Head Title Tag');
  $fields_languages[] = array('name' => 'products_head_desc_tag', 'value' => 'Products Description Tag');
  $fields_languages[] = array('name' => 'products_head_keywords_tag', 'value' => 'Products Keywords Tag');
}

$additional_fields = array();
$additional_fields[0] = array('table' => TABLE_MANUFACTURERS, 'link_field' => 'manufacturers_id', 'language_table' => TABLE_MANUFACTURERS_INFO, 'table_prefix' => 'm', 'language_table_prefix' => 'mi', 'language_field' => 'languages_id', 'default_empty'=>null );
$additional_fields[0]['data'][] = array('name' => 'manufacturers_name', 'value' => 'Manufacturers Name', 'language' => '0');
$additional_fields[0]['data'][] = array('name' => 'manufacturers_image', 'value' => 'Manufacturers Image', 'language' => '0');
$additional_fields[0]['data'][] = array('name' => 'manufacturers_url', 'value' => 'Manufacturers URL', 'language' => '1');

$fields_categories = array();
$fields_categories[] = array('name' => 'key_field', 'value' => 'KEY_FIELD');
$fields_categories[] = array('name' => 'categories_image', 'value' => 'Categories Image');
$fields_categories[] = array('name' => 'sort_order', 'value' => 'Categories Sort Order');
$fields_categories[] = array('name' => 'categories_status', 'value' => 'Categories Status');

$fields_categories_languages = array();
$fields_categories_languages[] = array('name' => 'categories_name', 'value' => 'Categories Name');
$fields_categories_languages[] = array('name' => 'categories_description', 'value' => 'Categories Description');
if (SEARCH_ENGINE_UNHIDE == 'True'){
  $fields_categories_languages[] = array('name' => 'categories_head_title_tag', 'value' => 'Categories Title Tag');
  $fields_categories_languages[] = array('name' => 'categories_head_desc_tag', 'value' => 'Categories Description Tag');
  $fields_categories_languages[] = array('name' => 'categories_head_keywords_tag', 'value' => 'Categories Keywords Tag');
  $fields_categories_languages[] = array('name' => 'categories_heading_title', 'value' => 'Categories Heading Title');
}


$fields_attributes = array();
$fields_attributes[] = array('name' => 'products_model', 'value' => 'Products Model');

function get_products_attributes_price($field_name, $products_attributes_id){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($cur_id == 0 && $group_id == 0){
      $query = tep_db_query("select options_values_price as attributes_group_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $products_attributes_id . "'");
    }else{
      $query = tep_db_query("select attributes_group_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if ( tep_db_num_rows($query)==0 ) return -2;
    }    
    $data = tep_db_fetch_array($query);
    return $data['attributes_group_price'];
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $query = tep_db_query("select attributes_group_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if ( tep_db_num_rows($query)==0 ) return -2; //????
    $data = tep_db_fetch_array($query);
    return $data['attributes_group_price'];
  }
}

function set_products_attributes_price($field_name, $products_attributes_id, $value){

  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($cur_id == 0 && $group_id == 0){
      tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "'");
    }else{
      $check = tep_db_query("select attributes_group_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if (tep_db_num_rows($check)){
        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'" );
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_price = '" . $value . "', products_attributes_id = '" . $products_attributes_id . "', currencies_id = '" . $cur_id . "', groups_id = '" . $group_id . "'" );
      }
    }
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $check = tep_db_query("select attributes_group_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if (tep_db_num_rows($check)){
      tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0" );
    }else{
      tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_price = '" . $value . "', products_attributes_id = '" . $products_attributes_id . "', currencies_id = '" . $cur_id . "', groups_id = 0" );
    }
  }  
}


function get_products_attributes_discount_price($field_name, $products_attributes_id){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }
    if ($cur_id == 0 && $group_id == 0){
      $query = tep_db_query("select products_attributes_discount_price as attributes_group_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $products_attributes_id . "'");
    }else{
      $query = tep_db_query("select attributes_group_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
    }
    $data = tep_db_fetch_array($query);
    return $data['attributes_group_discount_price'];
  }else{
    $ar = split('_', $field_name);
    $cur_id = $ar[sizeof($ar) - 1];    
    $query = tep_db_query("select attributes_group_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    $data = tep_db_fetch_array($query);
    return $data['attributes_group_discount_price'];
  }
}

function set_products_attributes_discount_price($field_name, $products_attributes_id, $value){

  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $ar = split('_', $field_name);
    $group_id = $ar[sizeof($ar) - 1];
    if (USE_MARKET_PRICES == 'True'){
      $cur_id = $ar[sizeof($ar) - 2];
    }else{
      $cur_id = 0;
    }    
    if ($cur_id == 0 && $group_id == 0){
      tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_attributes_discount_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "'");
    }else{
      $check = tep_db_query("select attributes_group_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'");
      if (tep_db_num_rows($check)){
        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_discount_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = '" . $group_id . "'" );
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_discount_price = '" . $value . "', products_attributes_id = '" . $products_attributes_id . "', currencies_id = '" . $cur_id . "', groups_id = '" . $group_id . "'" );
      }
    }
  }else{
    $cur_id = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
    $check = tep_db_query("select attributes_group_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0");
    if (tep_db_num_rows($check)){
      tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_discount_price = '" . $value . "' where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $cur_id . "' and groups_id = 0" );
    }else{
      tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " set attributes_group_discount_price = '" . $value . "', products_attributes_id = '" . $products_attributes_id . "', currencies_id = '" . $cur_id . "', groups_id = 0" );
    }
  }  
}


if (USE_MARKET_PRICES == 'True'){
  foreach ($currencies->currencies as $key => $value){
    if (CUSTOMERS_GROUPS_ENABLE == 'True'){
      $groups_query = tep_db_query("select * from " . TABLE_GROUPS);
      $fields_attributes[] = array('name' => 'options_values_price_' . $value['id'] . '_0', 'value' => 'Option Price ' . $key, 'get' => 'get_products_attributes_price', 'set' => 'set_products_attributes_price');     
      if (DISCOUNT_TABLE_ENABLE == 'True'){
        $fields_attributes[] = array('name' => 'products_attributes_discount_price_' . $value['id'] . '_0', 'value' => 'Option Discount Price ' . $key, 'get' => 'get_products_attributes_discount_price', 'set' => 'set_products_attributes_discount_price');
      }
      while ($groups_data = tep_db_fetch_array($groups_query)){
        $fields_attributes[] = array('name' => 'options_values_price_' . $value['id'] . '_' . $groups_data['groups_id'], 'value' => 'Option Price ' . $key . ' ' . $groups_data['groups_name'], 'get' => 'get_products_attributes_price', 'set' => 'set_products_attributes_price');
        if (DISCOUNT_TABLE_ENABLE == 'True'){
          $fields_attributes[] = array('name' => 'products_attributes_discount_price_' . $value['id'] . '_' . $groups_data['groups_id'], 'value' => 'Option Discount Price ' . $key . ' ' . $groups_data['groups_name'], 'get' => 'get_products_attributes_discount_price', 'set' => 'set_products_attributes_discount_price');
        }
      }
    }else{
      $fields_attributes[] = array('name' => 'options_values_price_' . $value['id'], 'value' => 'Option Price ' . $key, 'get' => 'get_products_attributes_price', 'set' => 'set_products_attributes_price');
      if (DISCOUNT_TABLE_ENABLE == 'True'){
        $fields_attributes[] = array('name' => 'products_attributes_discount_price_'  . $value['id'], 'value' => 'Option Discount Price ' . $key, 'get' => 'get_products_attributes_discount_price', 'set' => 'set_products_attributes_discount_price');
      }
    }
  }
}elseif(CUSTOMERS_GROUPS_ENABLE == 'True' && USE_MARKET_PRICES != 'True'){
  $groups_query = tep_db_query("select * from " . TABLE_GROUPS);
  $fields_attributes[] = array('name' => 'options_values_price_0', 'value' => 'Option Price', 'get' => 'get_products_attributes_price', 'set' => 'set_products_attributes_price');
  if (DISCOUNT_TABLE_ENABLE == 'True'){
    $fields_attributes[] = array('name' => 'products_attributes_discount_price_0', 'value' => 'Option Discount Price', 'get' => 'get_products_attributes_discount_price', 'set' => 'set_products_attributes_discount_price');
  }
  
  while ($groups_data = tep_db_fetch_array($groups_query)){
    $fields_attributes[] = array('name' => 'options_values_price_' . $groups_data['groups_id'], 'value' => 'Option Price ' . $groups_data['groups_name'], 'get' => 'get_products_attributes_price', 'set' => 'set_products_attributes_price');
    if (DISCOUNT_TABLE_ENABLE == 'True'){
      $fields_attributes[] = array('name' => 'products_attributes_discount_price_' . $groups_data['groups_id'], 'value' => 'Option Discount Price ' . $groups_data['groups_name'], 'get' => 'get_products_attributes_discount_price', 'set' => 'set_products_attributes_discount_price');
    }
  }
}else{
  $fields_attributes[] = array('name' => 'options_values_price', 'value' => 'Option Price');
  if (DISCOUNT_TABLE_ENABLE == 'True'){
    $fields_attributes[] = array('name' => 'products_attributes_discount_price', 'value' => 'Option Discount Price');
  }
}



$fields_attributes[] = array('name' => 'price_prefix', 'value' => 'Price Prefix');
$fields_attributes[] = array('name' => 'products_options_sort_order', 'value' => 'Attributes Sort Order');
$fields_attributes[] = array('name' => 'product_attributes_one_time', 'value' => 'Attributes One Time');
$fields_attributes[] = array('name' => 'products_attributes_weight', 'value' => 'Attributes Weight');
$fields_attributes[] = array('name' => 'products_attributes_weight_prefix', 'value' => 'Attributes Weight Prefix');

$languages = tep_get_languages();
for ($i=0, $n=sizeof($languages);$i<$n;$i++){
  $fields_attributes[] = array('name' => 'products_options_name_' . $languages[$i]['id'], 'value' => 'Option Name ' . $languages[$i]['code'], 'set' => 'set_products_options', 'get' => 'get_products_options', 'field' => 'options_id' );
  $fields_attributes[] = array('name' => 'products_options_sort_order_' . $languages[$i]['id'], 'value' => 'Option Sort Order ' . $languages[$i]['code'], 'set' => 'set_products_options', 'get' => 'get_products_options', 'field' => 'options_id');
}

function get_products_options_values($field_name, $options_values_id){
  $cur_lan = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
  $field = substr($field_name, 0, strrpos($field_name, "_"));
  $query = tep_db_query("select " . $field . " from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $cur_lan . "' and products_options_values_id = '" . $options_values_id . "'" );
  $data = tep_db_fetch_array($query);
  return $data[$field];
}

$languages = tep_get_languages();
for ($i=0, $n=sizeof($languages);$i<$n;$i++){
  $fields_attributes[] = array('name' => 'products_options_values_name_' . $languages[$i]['id'], 'value' => 'Option Value ' . $languages[$i]['code'], 'set' => 'set_products_options_values', 'get' => 'get_products_options_values', 'field' => 'options_values_id');
}

function set_products_options($val){
  Global $fields_attributes, $languages_id;
  $ep_array = explode('^', $val);
  $options_id = 0;
  for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_id'){
      if ($fields_attributes[$i]['name'] == 'products_options_name_' . $languages_id){
        $query = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS . " where products_options_name = '" .tep_db_input($ep_array[$fields_attributes[$i]['pos']]). "' and language_id = '" . $languages_id ."'");
        if (tep_db_num_rows($query) > 0){
          $data = tep_db_fetch_array($query);
          $options_id = $data['products_options_id'];
        }else{
          $max_options_id_query = tep_db_query("select ifnull(max(products_options_id),0) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
          $max_options_id_values = tep_db_fetch_array($max_options_id_query);
          $options_id = $max_options_id_values['next_id'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " set products_options_id = '" . $options_id . "', language_id = '" . $languages_id . "', products_options_name = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "'");
        }
        break;
      }
    }
  }
  for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_id'){
      $cur_lan = substr($fields_attributes[$i]['name'], strrpos($fields_attributes[$i]['name'], "_") + 1, strlen($fields_attributes[$i]['name']));
      $field = substr($fields_attributes[$i]['name'], 0, strrpos($fields_attributes[$i]['name'], "_"));
      $query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '".$cur_lan."' and products_options_id = '" . $options_id . "'");
      if (tep_db_num_rows($query) > 0){
        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set " . $field . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "' where language_id = '".$cur_lan."' and products_options_id = '" . $options_id . "'");
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " set " . $field . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "', language_id = '".$cur_lan."', products_options_id = '" . $options_id . "'");
      }
    }
  }
  return $options_id;
}

function get_products_options($field_name, $options_id){
  $cur_lan = substr($field_name, strrpos($field_name, "_") + 1, strlen($field_name));
  $field = substr($field_name, 0, strrpos($field_name, "_"));
  $query = tep_db_query("select " . $field . " from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $cur_lan . "' and products_options_id = '" . $options_id . "'" );
  $data = tep_db_fetch_array($query);
  return $data[$field];
}

function set_products_options_values($val){
  Global $fields_attributes, $languages_id;
  $ep_array = explode('^', $val);
  $options_values_id = 0;
  for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_values_id'){
      if ($fields_attributes[$i]['name'] == 'products_options_values_name_' . $languages_id){
        $query = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_name = '" .tep_db_input($ep_array[$fields_attributes[$i]['pos']]). "' and language_id = '" . $languages_id ."'");
        if (tep_db_num_rows($query) > 0){
          $data = tep_db_fetch_array($query);
          $options_values_id = $data['products_options_values_id'];
        }else{
          $max_values_id_query = tep_db_query("select ifnull(max(products_options_values_id),0) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
          $max_values_id_values = tep_db_fetch_array($max_values_id_query);
          $options_values_id = $max_values_id_values['next_id'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_id = '" . $options_values_id . "', language_id = '" . $languages_id . "', products_options_values_name = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "'");
        }
        break;
      }
    }
  }
  for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_values_id'){
      $cur_lan = substr($fields_attributes[$i]['name'], strrpos($fields_attributes[$i]['name'], "_") + 1, strlen($fields_attributes[$i]['name']));
      $field = substr($fields_attributes[$i]['name'], 0, strrpos($fields_attributes[$i]['name'], "_"));
      $query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '".$cur_lan."' and products_options_values_id = '" . $options_values_id . "'");
      if (tep_db_num_rows($query) > 0){
        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set " . $field . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "' where language_id = '".$cur_lan."' and products_options_values_id = '" . $options_values_id . "'");
      }else{
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " set " . $field . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "', language_id = '".$cur_lan."', products_options_values_id = '" . $options_values_id . "'");
      }
    }
  }
  return $options_values_id;
}

$properties = array();
$properties[] = array('name' => 'products_model', 'value' => 'Products Model', 'table' => '');
$properties[] = array('name' => 'properties_type', 'value' => 'Property Type');
$properties[] = array('name' => 'additional_info', 'value' => 'Property Additional Info');
$properties[] = array('name' => 'mode', 'value' => 'Property Mode');
//$properties[] = array('name' => 'properties_type', 'value' => 'Property Type');

$properties_categories = array();
$properties_categories[] = array('name' => 'categories_name', 'value' => 'Category Name', 'checked' => '1');
$properties_categories[] = array('name' => 'categories_description', 'value' => 'Category Description');

$properties_languages = array();
$properties_languages[] = array('name' => 'properties_name', 'value' => 'Property Name', 'checked' => '1');
$properties_languages[] = array('name' => 'properties_description', 'value' => 'Property Description');
$properties_languages[] = array('name' => 'possible_values', 'value' => 'Property Possible Values');

$properties_products = array();
$properties_products[] = array('name' => 'set_value', 'value' => 'Products Property Value');
$properties_products[] = array('name' => 'additional_info', 'value' => 'Products Property Additional Info');

if (isset($HTTP_GET_VARS['dltype']) && $HTTP_GET_VARS['dltype'] != ''){
  makeEPFile();
}

if (isset($HTTP_GET_VARS['split']) && $HTTP_GET_VARS['split'] != ''){
  if ($HTTP_GET_VARS['split'] == 0){
    updateEPProducts();
  }
  if ($HTTP_GET_VARS['split'] == 1){
    updateEPCategories();
  }
  if ($HTTP_GET_VARS['split'] == 2){
    updateEPAttributes();
  }
  if ($HTTP_GET_VARS['split'] == 3){
    updateEPProperties();
  }
}


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_EASYPOPULATE, 'selected_box=catalog');
  $header_title_submenu= EP_HEDING_TITLE ;
?>
<? include(DIR_WS_INCLUDES . 'header.php'); ?>
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
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
        </tr>
     </table>
<p class="smallText">

      </p>

      <table width="75%" border="0">
        <tr>
          <td width="75%" class="main">
           <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'split=0');?>" METHOD=POST>
              <p>
                <div align = "left">
                <p><b><?php echo TEXT_UPLOAD_MAIN_EP_FILE;?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <?php echo tep_image_submit("button_insert.gif");?><br>
                </p>
              </div>

              </form>
           <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'split=1');?>" METHOD=POST>
              <p>
                <div align = "left">
                <p><b><?php echo TEXT_UPLOAD_CATEGORIES_EP_FILE;?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <?php echo tep_image_submit("button_insert.gif");?><br>
                </p>
              </div>

              </form>
              <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'split=2');?>" METHOD=POST>
              <p>
                <div align = "left">
                <p><b><?php echo TEXT_UPLOAD_ATTRIBUTES_EP_FILE;?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <?php echo tep_image_submit("button_insert.gif");?><br>
                </p>
              </div>

             </form>
<?PHP
if (PRODUCTS_PROPERTIES == 'True'){
?>              
              <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'split=3');?>" METHOD=POST>
              <p>
                <div align = "left">
                <p><b><?php echo TEXT_UPLOAD_PROPERTIES_EP_FILE;?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <?php echo tep_image_submit("button_insert.gif");?><br>
                </p>
              </div>

             </form>
<?PHP
}
?>


		<p><b><?php echo DOWNLOAD_EP_FILES;?></b></p>
<?php
$warn_products = '';
$warn_caregories = '';
$office_limit = 31998;
$tpd_r = tep_db_query("SELECT max(length(products_description)) as pd_len, max(length(products_head_desc_tag)) as hd_len, max(length(products_head_keywords_tag )) as hk_len FROM ".TABLE_PRODUCTS_DESCRIPTION);
$tpd_a = tep_db_fetch_array($tpd_r);
foreach ( $tpd_a as $col=>$max_length ) { if ((int)$max_length>(int)$office_limit) $warn_products = TEXT_WARN_LONGTEXT_EDIT; }
$tpd_r = tep_db_query("SELECT max(length(categories_description)) as cd_len, max(length(categories_head_desc_tag)) as hd_len, max(length(categories_head_keywords_tag)) as hk_len FROM ".TABLE_CATEGORIES_DESCRIPTION);
$tpd_a = tep_db_fetch_array($tpd_r);
foreach ( $tpd_a as $col=>$max_length ) { if ((int)$max_length>(int)$office_limit) $warn_caregories = TEXT_WARN_LONGTEXT_EDIT; }
?>
	      <!-- Download file links -  Add your custom fields here -->
	  <a href="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'download=stream&dltype=main');?>"><?php echo TEXT_DOWNLOAD_MAIN_EP_FILE;?></a><?php echo $warn_products;?><br>
    <a href="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'download=stream&dltype=categories');?>"><?php echo TEXT_DOWNLOAD_CATEGORIES_EP_FILE;?></a><?php echo $warn_caregories;?><br>
    <a href="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'download=stream&dltype=attributes');?>"><?php echo TEXT_DOWNLOAD_ATTRIBUTES_EP_FILE;?></a><br>
<?php
if (PRODUCTS_PROPERTIES == 'True'){
?>
    <a href="<?php echo tep_href_link(FILENAME_EASYPOPULATE, 'download=stream&dltype=properties');?>"><?php echo TEXT_DOWNLOAD_PROPERTIES_EP_FILE;?></a><br>
<?php
}
?>
 <!--<?php /* ?>
		<p><b>Create EP and Froogle Files in Temp Dir (<? echo $tempdir; ?>)</b></p>
	  <a href="easypopulate.php?download=tempfile&dltype=full">Create Complete tab-delimited .txt file in temp dir</a><br>
          <a href="easypopulate.php?download=tempfile&dltype=priceqty"">Create Model/Price/Qty tab-delimited .txt file in temp dir</a><br>
          <a href="easypopulate.php?download=tempfile&dltype=category">Create Model/Category tab-delimited .txt file in temp dir</a><br>
	  <a href="easypopulate.php?download=tempfile&dltype=froogle">Create Froogle tab-delimited .txt file in temp dir</a><br>
	  <a href="easypopulate.php?download=tempfile&dltype=mopics">Create Mopics tab-delimited .txt file in temp dir</a><br>
	  <a href="easypopulate.php?download=tempfile&dltype=attrib">Create Model/Attributes tab-delimited .txt file in temp dir</a><br>
 <?php */ ?>-->
	  </td>
	</tr>
      </table>
    </td>
 </tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<p> </p>
<p> </p><p><br>
</p></body>
</html>

<?php
function restoreText($thetext){
  $thetext = str_replace('\n',"\n",$thetext);
  $thetext = str_replace('\r',"\r",$thetext);
  $thetext = str_replace('\t',"\t",$thetext);
  return $thetext;
}

function saveText($thetext){
  if ( !tep_not_null($thetext) ) return '';
  $thetext = str_replace("\r",'\r',$thetext);
  $thetext = str_replace("\n",'\n',$thetext);
	$thetext = str_replace("\t",'\t',$thetext);
  $thetext = str_replace('\"','"',$thetext);
  $thetext = str_replace('"','""',$thetext);
  return $thetext;
}

function makeEPFile(){
  Global $HTTP_GET_VARS, $separator, $max_categories, $fields, $fields_languages, $additional_fields;
  Global $fields_categories, $fields_categories_languages, $languages_id;
  Global $properties, $properties_categories, $properties_languages, $properties_products;
  $languages = tep_get_languages();
  $fileout = '';
  $type = 'products';
  
  $filename = (($HTTP_GET_VARS['dltype'] == 'main')?'products':$HTTP_GET_VARS['dltype']) . '_' . strftime('%Y%b%d_%H%I').'.txt';
  //$mime_type = 'text/comma-separated-values';
  $mime_type = 'application/vnd.ms-excel';

//  header('Content-Encoding: ' . $content_encoding);
  header('Content-Type: ' . $mime_type);
  header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Content-Disposition: attachment; filename="' . $filename . '"');

  if (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
  } else {
      header('Pragma: no-cache');
  }
  
  if ($HTTP_GET_VARS['dltype'] == 'main'){
    $field_count = 0;
    $sql = 'select ';
    for ($i=0, $n=sizeof($fields);$i<$n;$i++){
      if ($field_count == 0){
        $fileout .= '"' . saveText($fields[$i]['value']) . '"';
      }else{
        $fileout .= $separator . '"' . saveText($fields[$i]['value']) . '"';
      }
      if (!isset($fields[$i]['get'])){
        $sql .= $fields[$i]['name']  . ', ';
      }
      $field_count++;
    }
    for ($i=0,$n=sizeof($additional_fields);$i<$n;$i++){
      if (isset($additional_fields[$i]['link_field'])){
        if ($additional_fields[$i]['link_field'] != 'products_id'){
        $sql .= $additional_fields[$i]['link_field'] . ', ';
        }
      }
    }
    $sql .= ' products_id from ' . TABLE_PRODUCTS;
    $name_sql = 'select ';
    $languages = tep_get_languages();

    for ($i=0, $m=sizeof($languages); $i<$m; $i++) {
      for ($j=0,$n=sizeof($fields_languages);$j<$n;$j++){
        $field_count++;
        $fileout .= $separator . '"' . saveText($fields_languages[$j]['value']) . ' ' . $languages[$i]['code'] .'"';
        if ($i == 0){
          $name_sql .= $fields_languages[$j]['name'] . ', ';
        }
      }
    }
    $name_sql .= ' products_id from ' . TABLE_PRODUCTS_DESCRIPTION;
    $add_sql = array();
    for ($i=0,$n=sizeof($additional_fields);$i<$n;$i++){
      $add_sql[$i] = 'select ';
      $ln_str = false;
      $add_count = 0;
      for ($j=0,$m=sizeof($additional_fields[$i]['data']);$j<$m;$j++){
        if ($additional_fields[$i]['data'][$j]['language'] == 0){
          $add_count++;
          $fileout .= $separator . '"' . str_replace('"', "'", $additional_fields[$i]['data'][$j]['value']) . '"';
          $add_sql[$i] .= $additional_fields[$i]['table_prefix'] . '.' . $additional_fields[$i]['data'][$j]['name'] . ', ';
        }else{
          $languages = tep_get_languages();
          for ($l=0,$o=sizeof($languages);$l<$o;$l++){
            $add_count++;
            $ln_str = true;
            $fileout .= $separator . '"' . saveText($additional_fields[$i]['data'][$j]['value']) . ' ' . $languages[$l]['code'] .  '"';
            $add_sql[$i] .= $additional_fields[$i]['language_table_prefix'] . $l . '.' . $additional_fields[$i]['data'][$j]['name'].' as '.$additional_fields[$i]['data'][$j]['name'] . $l . ', ';
          }
        }
      }
      $add_sql[$i] = substr($add_sql[$i], 0, strlen($add_sql[$i]) - 2);
      $additional_fields[$i]['field_count'] = $add_count;
      $add_sql[$i] .=  ' from ' . $additional_fields[$i]['table'] . ' ' . $additional_fields[$i]['table_prefix'];
      if ($ln_str){
        $languages = tep_get_languages();
        for ($l=0,$o=sizeof($languages);$l<$o;$l++){
          $add_sql[$i] .= ' left join ' . $additional_fields[$i]['language_table'] . ' ' .  $additional_fields[$i]['language_table_prefix'] . $l . ' on ' . $additional_fields[$i]['language_table_prefix'] . $l . '.' .  $additional_fields[$i]['link_field'] . ' = ' . $additional_fields[$i]['table_prefix'] . '.' . $additional_fields[$i]['link_field'] . ' and ' . $additional_fields[$i]['language_table_prefix'] . $l . '.' .  $additional_fields[$i]['language_field'] . ' = ' . $languages[$l]['id'];
        }
      }
    }
    for ($i=0;$i<$max_categories;$i++){
      $fileout .= $separator . '"' . saveText(TEXT_CATEGORIES) . '_' . $i . '"';
    }
    $fileout .= "\n";
    echo $fileout; $fileout=''; flush();

    $query = tep_db_query($sql);
    while ($data = tep_db_fetch_array($query)){
      for ($i=0, $n=sizeof($fields);$i<$n;$i++){
        if ($i == 0){
          $fileout .= '"' . saveText($data[$fields[$i]['name']]) . '"';
        }else{
          if (!isset($fields[$i]['get'])){
            $fileout .= $separator . '"' . saveText($data[$fields[$i]['name']]) . '"';
          }else{
            eval('$str=' . $fields[$i]['get'] . '("'.$fields[$i]['name'].'", ' .$data['products_id'].');' );
            $fileout .= $separator . '"' . saveText($str) . '"';
          }
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $m=sizeof($languages); $i<$m; $i++) {
        $lan_query = tep_db_query($name_sql . " where products_id = '".$data['products_id']."' and language_id = '" .$languages[$i]['id']. "' and affiliate_id=0");
        $lan_data = tep_db_fetch_array($lan_query);
        for ($j=0,$n=sizeof($fields_languages);$j<$n;$j++){
          $fileout .= $separator . '"' . saveText($lan_data[$fields_languages[$j]['name']]) . '"';
        }
      }
      for ($i=0,$n=sizeof($additional_fields);$i<$n;$i++){
        $add_query = tep_db_query($add_sql[$i] . ' where ' . $additional_fields[$i]['table_prefix'] . '.' . $additional_fields[$i]['link_field'] . "='" . $data[$additional_fields[$i]['link_field']] . "'");
        if (tep_db_num_rows($add_query) > 0){
          $add_data = tep_db_fetch_array($add_query);
          foreach($add_data as $key=>$value){
            $fileout .= $separator . '"' . saveText($value) . '"';
          }
        }else{
          for ($j=0,$m=$additional_fields[$i]['field_count'];$j<$m;$j++){
            $fileout .= $separator . '""';
          }
        }
      }
    $sql_categories = "select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = ".$data['products_id'] . " limit ".$max_categories;
    $query_categories = tep_db_query($sql_categories);
    if (tep_db_num_rows($query_categories) > 0){
      $i =0;
      while ($data_categories = tep_db_fetch_array($query_categories)){
        if ($i > $max_categories){
          break;
        }
        $i++;
        if ($data_categories['categories_id'] == 0){
          continue;
        }
        $fileout .= $separator . '"' . saveText(tep_get_categories_full_path($data_categories['categories_id'])) . '"';
      }
    }
      $fileout .= "\n";
      echo $fileout; $fileout=''; flush();
    }
  }elseif ($HTTP_GET_VARS['dltype'] == 'categories'){
    $field_count = 0;
    $type= 'categories';
    $sql = 'select ';
    for ($i=0, $n=sizeof($fields_categories);$i<$n;$i++){
      if ($field_count == 0){
        $fileout .= '"' . saveText($fields_categories[$i]['value']) . '"';
      }else{
        $fileout .= $separator . '"' . saveText($fields_categories[$i]['value']) . '"';
      }
      if ($fields_categories[$i]['name'] != 'key_field')
        $sql .= $fields_categories[$i]['name']  . ', ';
      $field_count++;
    }
    $sql .= ' categories_id from ' . TABLE_CATEGORIES;
    $name_sql = 'select ';
    $languages = tep_get_languages();

    for ($i=0, $m=sizeof($languages); $i<$m; $i++) {
      for ($j=0,$n=sizeof($fields_categories_languages);$j<$n;$j++){
        $field_count++;
        $fileout .= $separator . '"' . saveText($fields_categories_languages[$j]['value']) . ' ' . $languages[$i]['code'] .'"';
        if ($i == 0){
          $name_sql .= $fields_categories_languages[$j]['name'] . ', ';
        }
      }
    }
    $name_sql .= ' categories_id from ' . TABLE_CATEGORIES_DESCRIPTION;
    $fileout .= "\n";
    echo $fileout; $fileout=''; flush();
    
    $query = tep_db_query($sql);        
    while ($data = tep_db_fetch_array($query)){
      for ($i=0, $n=sizeof($fields_categories);$i<$n;$i++){
        if ($i == 0){
          if ($fields_categories[$i]['name'] == 'key_field'){
            $fileout .= '"' . saveText(tep_get_categories_full_path($data['categories_id'])) . '"';            
          }else{
            $fileout .= '"' . saveText($data[$fields[$i]['name']]) . '"';
          }          
        }else{
          if ($fields_categories[$i]['name'] == 'key_field'){
            $fileout .= $separator . '"' . saveText(tep_get_categories_full_path($data['categories_id'])) . '"';
          }else{
            $fileout .= $separator . '"' . saveText($data[$fields_categories[$i]['name']]) . '"';
          }
        }
      }

      $languages = tep_get_languages();
      for ($i=0, $m=sizeof($languages); $i<$m; $i++) {
        $lan_query = tep_db_query($name_sql . " where categories_id = '".$data['categories_id']."' and language_id = '" .$languages[$i]['id']. "' and affiliate_id=0");
        $lan_data = tep_db_fetch_array($lan_query);
        for ($j=0,$n=sizeof($fields_categories_languages);$j<$n;$j++){
          $fileout .= $separator . '"' . saveText($lan_data[$fields_categories_languages[$j]['name']]) . '"';
        }
      }
      $fileout .= "\n";
      echo $fileout; $fileout=''; flush();      
    }
  }elseif ($HTTP_GET_VARS['dltype'] == 'attributes'){
    Global $fields_attributes;
    $type = 'attributes';
    $str = "select pa.*, p.products_model from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS . " p where pa.products_id = p.products_id order by products_model" ;
    $fileout = '';
    for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
      if ($i > 0){
        $fileout .= $separator;
      }
      $fileout .= '"' . $fields_attributes[$i]['value'] . '"';
    }
    $fileout .= "\n";
    echo $fileout; $fileout=''; flush();
    $query = tep_db_query($str);
    while ($data = tep_db_fetch_array($query)){
      for ($i=0, $n=sizeof($fields_attributes);$i<$n;$i++){
        if ($i>0){
          $fileout .= $separator;
        }
        if (isset($fields_attributes[$i]['get'])){
          if (isset($fields_attributes[$i]['field'])){
            eval('$str=' . $fields_attributes[$i]['get'] . '("'.$fields_attributes[$i]['name'].'", ' .$data[$fields_attributes[$i]['field']].');' );
          }else{
            eval('$str=' . $fields_attributes[$i]['get'] . '("'.$fields_attributes[$i]['name'].'", ' .$data['products_attributes_id'].');' );
          }
          $fileout .= '"' . saveText($str) . '"';
        }else{
          $fileout .= '"' . saveText($data[$fields_attributes[$i]['name']]) . '"';
        }
      }
      $fileout .= "\n";
      echo $fileout; $fileout=''; flush();
    }
  }elseif ($HTTP_GET_VARS['dltype'] == 'properties'){
    //$properties, $properties_categories, $properties_languages, $properties_products
    $fileout = '';
    $type = 'properties';
    for ($i=0,$n=sizeof($properties);$i<$n;$i++){
      if ($i>0){
        $fileout .= $separator;
      }
      $fileout .= '"' . $properties[$i]['value'] . '"';
    }
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      for ($j=0,$m=sizeof($properties_categories);$j<$m;$j++){
        $fileout .= $separator . '"' . $properties_categories[$j]['value'] . ' ' . $languages[$i]['code'] . '"';
      }
    }
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      for ($j=0,$m=sizeof($properties_languages);$j<$m;$j++){
        $fileout .= $separator . '"' . $properties_languages[$j]['value'] . ' ' . $languages[$i]['code'] . '"';
      }
    }
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      for ($j=0,$m=sizeof($properties_products);$j<$m;$j++){
        $fileout .= $separator . '"' . $properties_products[$j]['value'] . ' ' . $languages[$i]['code'] . '"';
      }
    }

    $fileout .= "\n";
    echo $fileout; $fileout=''; flush();

    $query = tep_db_query("select p2p.products_id, p.products_model, p2p.properties_id, pp.properties_type, pp.additional_info, pp.mode from " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p left join " . TABLE_PRODUCTS ." p on p2p.products_id = p.products_id left join " . TABLE_PROPERTIES ." pp on pp.properties_id = p2p.properties_id where p2p.language_id = '" . (int)$languages_id . "'");
//    echo "select p2p.products_id, p.products_model, p2p.properties_id, pp.properties_type, pp.additional_info, pp.mode from " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p left join " . TABLE_PRODUCTS ." p on p2p.products_id = p.products_id left join " . TABLE_PROPERTIES ." pp on pp.properties_id = p2p.properties_id where p2p.language_id = '" . (int)$languages_id . "'<br>";
    while ($data = tep_db_fetch_array($query)){
      for ($i=0,$n=sizeof($properties);$i<$n;$i++){
        if ($i>0){
          $fileout .= $separator;
        }
        $fileout .= '"' . saveText($data[$properties[$i]['name']]) . '"';
      }

      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $check_query = tep_db_query("select * from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where properties_id = '" . $data['properties_id'] ."'");
        if (tep_db_num_rows($check_query)){
          $check_data = tep_db_fetch_array($check_query);
          $categories_query = tep_db_query("select * from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " where categories_id = '" .$check_data['categories_id']. "' and language_id = '" . $languages[$i]['id'] . "'");
          $categories_data = tep_db_fetch_array($categories_query);
          for ($j=0,$m=sizeof($properties_categories);$j<$m;$j++){
            $fileout .= $separator . '"' . saveText($categories_data[$properties_categories[$j]['name']]). '"';
          }
        }else{
          for ($j=0,$m=sizeof($properties_categories);$j<$m;$j++){
            $fileout .= $separator . '""';
          }
        }
      }
  
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $check_query = tep_db_query("select * from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id = '" . $data['properties_id'] ."' and language_id = '" . $languages[$i]['id'] . "'");
        $check_data = tep_db_fetch_array($check_query);
        for ($j=0,$m=sizeof($properties_languages);$j<$m;$j++){
            $fileout .= $separator . '"' . saveText($check_data[$properties_languages[$j]['name']]). '"';
        }
      }

      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $check_query = tep_db_query("select * from " . TABLE_PROPERTIES_TO_PRODUCTS . " where properties_id = '" . $data['properties_id'] ."' and language_id = '" . $languages[$i]['id'] . "' and products_id = '" . $data['products_id'] . "'");
        $check_data = tep_db_fetch_array($check_query);
        for ($j=0,$m=sizeof($properties_products);$j<$m;$j++){
            $fileout .= $separator . '"' . saveText($check_data[$properties_products[$j]['name']]). '"';
        }
      }
      $fileout .= "\n";
      echo $fileout; $fileout=''; flush();
    }

  }
  die();
}

function updateEPProducts(){
  Global $HTTP_GET_VARS, $fields, $fields_languages, $report_string, $keyfieldindex, $max_categories;
  Global $languages_id, $additional_fields, $messageStack, $separator;
  $ep_data = new upload('usrfl');
  $ep_data->set_destination(DIR_FS_CATALOG . TEMP_DIR);

  if ($ep_data->parse() && $ep_data->save()) {
    $ep_file = DIR_FS_CATALOG . TEMP_DIR . $ep_data->filename;
    $epPointer = fopen($ep_file, "r");
    $counter = 0;
    $fields_list = '';
    $fields_list_languages = '';
    $fields_restore = array();
    $fields_languages_restore = array();
    $categories_restore = array();
    while ($data = fgetcsv ($epPointer, MAX_CSV_LINE_LENGTH, $separator)) {
      foreach ( $data as $idx=>$val) { $data[$idx]=restoreText($val); }
      $ep_array = $data;
      if ($counter == 0){
        // Define Fields
        for ($i=0,$n=sizeof($fields);$i<$n;$i++){
          for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
            if ($ep_array[$j] == $fields[$i]['value']){
              $fields_restore[$i] = $j;
              break;
            }
          }
        }
        $languages = tep_get_languages();
        for($l=0,$o=sizeof($languages);$l<$o;$l++){
          $fields_languages_restore[$languages[$l]['id']] = array();
          for ($i=0,$n=sizeof($fields_languages);$i<$n;$i++){
            for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
              if ($ep_array[$j] == $fields_languages[$i]['value'] . ' ' . $languages[$l]['code']){
                $fields_languages_restore[$languages[$l]['id']][$i] = $j;
              }
            }
          }
        }
        for ($i=0;$i<$max_categories;$i++){
          for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
            if ($ep_array[$j] == (str_replace('"', "'", TEXT_CATEGORIES) . '_' . $i)){
              $categories_restore[$i] = $j;
              break;
            }
          }
        }
        for ($i=0,$n=sizeof($additional_fields);$i<$n;$i++){
          for ($l=0,$o=sizeof($additional_fields[$i]['data']);$l<$o;$l++){
            if ($additional_fields[$i]['data'][$l]['language'] == 0){
              for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
                if ($ep_array[$j] == $additional_fields[$i]['data'][$l]['value']){
                  $additional_fields[$i]['data'][$l]['pos'] = $j;
                  break;
                }
              }
            }else{
              $languages = tep_get_languages();
              for ($g=0,$q=sizeof($languages);$g<$q;$g++){
                for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
                  if ($ep_array[$j] == $additional_fields[$i]['data'][$l]['value'] . ' ' . $languages[$g]['code']){
                    $additional_fields[$i]['data'][$l][$languages[$g]['id']] = $j;
                    break;
                  }
                }
              }
            }
          }
        }
      }else{
        // Import procedure
        if (trim($ep_array[$fields_restore[$keyfieldindex]]) == ''){
          $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
          $counter++;
          continue;
        }else{
          $query = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_model = '" . tep_db_input($ep_array[$fields_restore[$keyfieldindex]]) . "'");
          if (tep_db_num_rows($query) > 1) {
            $messageStack->add(sprintf('Not unique key %s line %s', $ep_array[$fields_restore[$keyfieldindex]], $counter), 'error');
            $counter++;
            continue;          
          }elseif (tep_db_num_rows($query) > 0){
            $data = tep_db_fetch_array($query);
            $products_id = $data['products_id'];
            $sql = "update " . TABLE_PRODUCTS . " set ";
            for ($i=0,$n=sizeof($fields);$i<$n;$i++){
              if (!isset($fields[$i]['set'])){
                $sql .= $fields[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_restore[$i]]) . "', ";
              }
            }
            $sql .= " products_last_modified  = now() where products_id = '" . $products_id . "'";
            tep_db_query($sql);
            $messageStack->add(sprintf(TEXT_EP_LINE_UPDATED, $counter, $products_id), 'success');
            $languages = tep_get_languages();
            for ($l=0,$o=sizeof($languages);$l<$o;$l++){
              $query = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $products_id . "' and language_id = '" . $languages[$l]['id'] . "' and affiliate_id=0");
              if (tep_db_num_rows($query) > 0){
                $sql = "update " . TABLE_PRODUCTS_DESCRIPTION . " set ";
                for ($i=0,$n=sizeof($fields_languages);$i<$n;$i++){
                  $sql .= $fields_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_languages_restore[$languages[$l]['id']][$i]]) . "', ";
                }
                $sql .= " products_id = '" .$products_id. "', language_id = '" . $languages[$l]['id']. "' where products_id = '" .$products_id. "' and language_id = '" . $languages[$l]['id']. "' and affiliate_id=0";
              }else{
                $sql = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " set ";
                for ($i=0,$n=sizeof($fields_languages);$i<$n;$i++){
                  $sql .= $fields_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_languages_restore[$languages[$l]['id']][$i]]) . "', ";
                }
                $sql .= " products_id = '" .$products_id. "', language_id = '" . $languages[$l]['id']. "'";
              }
              tep_db_query($sql);
            }
          }else{
            $sql = "insert into " . TABLE_PRODUCTS . " set ";
            for ($i=0,$n=sizeof($fields);$i<$n;$i++){
              if (!isset($fields[$i]['set'])){
                $sql .= $fields[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_restore[$i]]) . "', ";
              }
            }
            $sql .= " products_id = '', products_date_added = now()";
            tep_db_query($sql);
            $products_id = tep_db_insert_id();
            $messageStack->add(sprintf(TEXT_EP_LINE_INSERTED, $counter, $products_id), 'success');
            $languages = tep_get_languages();
            for ($l=0,$o=sizeof($languages);$l<$o;$l++){
              $query = tep_db_query("select * from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $products_id . "' and language_id = '" . $languages[$l]['id'] . "'");
              if (tep_db_num_rows($query) > 0){
                tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $products_id . "'");
              }
              $sql = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " set ";
              for ($i=0,$n=sizeof($fields_languages);$i<$n;$i++){
                $sql .= $fields_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_languages_restore[$languages[$l]['id']][$i]]) . "', ";
              }
              $sql .= " products_id = '" .$products_id. "', language_id = '" . $languages[$l]['id']. "'";
              tep_db_query($sql);
            }
          }
          
          for ($i=0,$n=sizeof($fields);$i<$n;$i++){
            if (isset($fields[$i]['set'])){
              eval($fields[$i]['set'] . '("'.$fields[$i]['name'].'", ' .$products_id.', "' . tep_db_prepare_input($ep_array[$fields_restore[$i]]) . '");' );
            }
          }

// Additional fields import
          for ($i=0,$n=sizeof($additional_fields);$i<$n;$i++) {
            $empty_check = true;
            for ($j=0,$m=sizeof($additional_fields[$i]['data']);$j<$m;$j++){
              if ($additional_fields[$i]['data'][$j]['language'] == 0){
                if ( trim($ep_array[$additional_fields[$i]['data'][$j]['pos']]) != ''){
                  $empty_check = false;
                  break;
                }
              }else{
                if ( trim($ep_array[$additional_fields[$i]['data'][$j][$languages_id]]) != ''){
                  $empty_check = false;
                  break;
                }
              }
            }
            if ($empty_check){
              if ( array_key_exists('default_empty', $additional_fields[$i]) ) {
                tep_db_query("update " . TABLE_PRODUCTS . " set " . $additional_fields[$i]['link_field']. " = " .  (is_null($additional_fields[$i]['default_empty'])?'NULL':"'".tep_db_input($additional_fields[$i]['default_empty'])."'") . " where products_id = '" .$products_id. "'");
              }
              continue;
            }
            $check_str = 'select ' . $additional_fields[$i]['table_prefix'] . '.* from ' . $additional_fields[$i]['table'] . " " . $additional_fields[$i]['table_prefix'] . ", " . $additional_fields[$i]['language_table'] . " " . $additional_fields[$i]['language_table_prefix'] . " where ";
            for ($j=0,$m=sizeof($additional_fields[$i]['data']);$j<$m;$j++){
              if ($additional_fields[$i]['data'][$j]['language'] == 0){
                $check_str .= $additional_fields[$i]['table_prefix'] . "." .$additional_fields[$i]['data'][$j]['name'] . "='" . tep_db_input($ep_array[$additional_fields[$i]['data'][$j]['pos']]) . "' and ";
              }else{
                $check_str .= $additional_fields[$i]['language_table_prefix'] . "." . $additional_fields[$i]['data'][$j]['name'] . "='" . tep_db_input($ep_array[$additional_fields[$i]['data'][$j][$languages_id]]) . "' and ";
              }
            }
            $check_str .= " " . $additional_fields[$i]['table_prefix'] . "." . $additional_fields[$i]['link_field'] . " = " . $additional_fields[$i]['language_table_prefix'] . "." . $additional_fields[$i]['link_field'] . " and " . $additional_fields[$i]['language_table_prefix'] . "." . $additional_fields[$i]['language_field'] . " = " . $languages_id;
            $query = tep_db_query($check_str);
            if (tep_db_num_rows($query)){
              $data = tep_db_fetch_array($query);
              tep_db_query("update " . TABLE_PRODUCTS . " set " . $additional_fields[$i]['link_field']. " = " . $data[$additional_fields[$i]['link_field']] . " where products_id = '" .$products_id. "'");
            }else{
              $update_str = "insert " . $additional_fields[$i]['table'] . " set ";
              for ($j=0,$m=sizeof($additional_fields[$i]['data']);$j<$m;$j++){
                if ($additional_fields[$i]['data'][$j]['language'] == 0){
                  $update_str .= $additional_fields[$i]['data'][$j]['name'] . " = '" . tep_db_input($ep_array[$additional_fields[$i]['data'][$j]['pos']]) . "', ";
                }
              }
              $update_str .= " " . $additional_fields[$i]['link_field'] . "=''";
              tep_db_query($update_str);
              $attr_id = tep_db_insert_id();
              tep_db_query("update " . TABLE_PRODUCTS . " set " . $additional_fields[$i]['link_field']. " = " . $attr_id . " where products_id = '" . $products_id . "'");

              if (isset($additional_fields[$i]['language_table'])){
                tep_db_query("delete from " . $additional_fields[$i]['language_table'] . " where " . $additional_fields[$i]['link_field']. "=" . $attr_id);

                $languages = tep_get_languages();
                for ($g=0,$q=sizeof($languages);$g<$q;$g++){
                  $update_str = "insert into " . $additional_fields[$i]['language_table'] . " set ";
                  for ($j=0,$m=sizeof($additional_fields[$i]['data']);$j<$m;$j++){
                    if ($additional_fields[$i]['data'][$j]['language'] == 1){
                      $update_str .= $additional_fields[$i]['data'][$j]['name'] . " = '" . tep_db_input($ep_array[$additional_fields[$i]['data'][$j][$languages[$g]['id']]]) . "', ";
                    }
                  }
                  $update_str .= $additional_fields[$i]['language_field'] . "=" . $languages[$g]['id'] . ", " . $additional_fields[$i]['link_field'] . " = " . $attr_id;
                  tep_db_query($update_str);
                }
              }
            }
          }

//Categories Import.
          for ($i=0;$i<$max_categories;$i++){
            $val = $ep_array[$categories_restore[$i]];
            if ($val == ''){
              if ($i==0){
                $query = tep_db_query("select * from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '0' and products_id = '" .$products_id. "'");
                if (tep_db_num_rows($query) == 0){
                  tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '0', products_id = '" .$products_id. "'");
                }
              }
              break;
            }
            $ar = split(';', $val);
            $cur_category = 0;
            for($j=0,$n=sizeof($ar);$j<$n;$j++){
              $query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.parent_id = '" .$cur_category. "' and cd.language_id = '" . $languages_id . "' and cd.categories_name = '" .tep_db_input($ar[$j]). "'");
              if (tep_db_num_rows($query) > 0){
                $data = tep_db_fetch_array($query);
                $cur_category = $data['categories_id'];
              }else{
                tep_db_query("insert into " . TABLE_CATEGORIES . " set parent_id = '" .$cur_category . "', date_added =now(), categories_id = ''");
                $cur_category = tep_db_insert_id();
                tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" .$cur_category . "'");
                tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " set categories_id = '" .$cur_category . "', categories_name = '" . tep_db_input($ar[$j]). "', language_id = '" . $languages_id . "'");
              }
            }
            if ($cur_category > 0){
              $query = tep_db_query("select * from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $cur_category. "' and products_id = '" .$products_id. "'");
              if (tep_db_num_rows($query) == 0){
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . $cur_category. "', products_id = '" .$products_id. "'");
              }
            }
          }

        }
      }
      $counter++;
    }
    // dh '0000-00-00 00:00:00' 
    tep_db_query( "update ".TABLE_PRODUCTS." SET products_date_available=null where products_date_available='0000-00-00 00:00:00'" );
  }
}

function updateEPCategories(){
  Global $HTTP_GET_VARS, $fields_categories, $fields_categories_languages, $report_string, $keyfieldindex, $max_categories;
  Global $languages_id, $messageStack, $separator;
  $ep_data = new upload('usrfl');
  $ep_data->set_destination(DIR_FS_CATALOG . TEMP_DIR);

  if ($ep_data->parse() && $ep_data->save()) {
    $ep_file = DIR_FS_CATALOG . TEMP_DIR . $ep_data->filename;
    $epPointer = fopen($ep_file, "r");
    $counter = 0;
    while ($data = fgetcsv ($epPointer, MAX_CSV_LINE_LENGTH, $separator)) {
      foreach ( $data as $idx=>$val) { $data[$idx]=restoreText($val); }
      $ep_array = $data;
      if ($counter == 0){
        // Define Fields
        for ($i=0,$n=sizeof($fields_categories);$i<$n;$i++){
          for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
            if ($ep_array[$j] == $fields_categories[$i]['value']){
              $fields_categories[$i]['pos'] = $j;
              break;
            }
          }
        }
        $languages = tep_get_languages();
        for($l=0,$o=sizeof($languages);$l<$o;$l++){
          for ($i=0,$n=sizeof($fields_categories_languages);$i<$n;$i++){
            for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
              if ($ep_array[$j] == $fields_categories_languages[$i]['value'] . ' ' . $languages[$l]['code']){
                $fields_categories_languages[$i][$languages[$l]['id']] = $j;
              }
            }
          }
        }
      }else{
        if ($ep_array[$keyfieldindex] == ''){
          $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
        }else{
          $category_id = 0;
          $str = $ep_array[$keyfieldindex];
          $ar = split(';', $str);
          for($i=0,$n=sizeof($ar);$i<$n;$i++){
            $query = tep_db_query("select * from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where  c.categories_id = cd.categories_id and c.parent_id = '" .$category_id. "' and cd.language_id = '" . $languages_id . "' and cd.categories_name = '".tep_db_input($ar[$i])."' and cd.affiliate_id=0");
            if (tep_db_num_rows($query) > 0){
              $data = tep_db_fetch_array($query);
              $category_id = $data['categories_id'];
            }else{
              $query = tep_db_query("insert into " . TABLE_CATEGORIES . " set parent_id = '" .$category_id. "'");
              $category_id = tep_db_insert_id();
              $query = tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " set categories_id = '" . $category_id. "', language_id = '" . $languages_id ."', categories_name = '" .tep_db_input($ar[$i]). "'");
            }
          }
          $str = "update " . TABLE_CATEGORIES . " set ";
          for($i=0,$n=sizeof($fields_categories);$i<$n;$i++){
            if ($i == $keyfieldindex) continue;
            $str .= $fields_categories[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_categories[$i]['pos']]) . "', ";
          }
          $str .= " categories_id = '" . $category_id . "' where categories_id = '" .$category_id . "'";
          tep_db_query($str);
          $languages = tep_get_languages();
          for ($j=0,$m=sizeof($languages);$j<$m;$j++){
            $query = tep_db_query("select * from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $languages[$j]['id'] . "' and affiliate_id=0");
            if (tep_db_num_rows($query)){
              $str = "update " . TABLE_CATEGORIES_DESCRIPTION . " set ";
              for($i=0,$n=sizeof($fields_categories_languages);$i<$n;$i++){
                $str .= $fields_categories_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_categories_languages[$i][$languages[$j]['id']]]) . "', ";
              }
              $str .= " categories_id = '" . $category_id . "' where categories_id = '" . $category_id . "' and language_id = '" . $languages[$j]['id'] . "' and affiliate_id=0";
            }else{
              $str = "insert into " . TABLE_CATEGORIES_DESCRIPTION . " set ";
              for($i=0,$n=sizeof($fields_categories_languages);$i<$n;$i++){
                $str .= $fields_categories_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_categories_languages[$i][$languages[$j]['id']]]) . "', ";
              }
              $str .= " categories_id = '" . $category_id . "', language_id = '" . $languages[$j]['id'] . "'";
            }
            tep_db_query($str);
          }
          $messageStack->add(sprintf(TEXT_EP_LINE_IMPORTED, $counter), 'success');
        }
      }
      $counter++;
    }
  }

  tep_update_categories();
}

function tep_get_options_id($ep_array){
  Global $fields_attributes;
  $j = 0;
  for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_id'){
      eval('$j=' . $fields_attributes[$i]['set'] . '("' . tep_db_input(implode('^', $ep_array)) . '");');
      break;
    }
  }
  return $j;
}

function tep_get_options_valies_id($ep_array){
  Global $fields_attributes;
  $j = 0;
  for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
    if ($fields_attributes[$i]['field'] == 'options_values_id'){
      eval('$j=' . $fields_attributes[$i]['set'] . '("' . tep_db_input(implode('^', $ep_array)) . '");');
      break;
    }
  }
  return $j;
}

function updateEPAttributes(){
  Global $HTTP_GET_VARS, $fields_categories, $fields_categories_languages, $report_string, $keyfieldindex, $max_categories;
  Global $languages_id, $messageStack, $fields_attributes, $separator;
  $ep_data = new upload('usrfl');
  $ep_data->set_destination(DIR_FS_CATALOG . TEMP_DIR);

  if ($ep_data->parse() && $ep_data->save()) {
    $ep_file = DIR_FS_CATALOG . TEMP_DIR . $ep_data->filename;
    $epPointer = fopen($ep_file, "r");
    $counter = 0;
    while ($data = fgetcsv ($epPointer, MAX_CSV_LINE_LENGTH, $separator)) {
      foreach ( $data as $idx=>$val) { $data[$idx]=restoreText($val); }
      $ep_array = $data;
      if ($counter == 0){
        for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
          for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
            if ($fields_attributes[$i]['value'] == $ep_array[$j]){
              $fields_attributes[$i]['pos'] = $j;
              break;
            }
          }
        }
      }else{
        $query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model= '" . tep_db_input($ep_array[$fields_attributes[$keyfieldindex]['pos']]) . "'");
        if (tep_db_num_rows($query) == 0){
          $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
        }else{
          $data = tep_db_fetch_array($query);
          $products_id = $data['products_id'];
          $options_id = tep_get_options_id($ep_array);
          $options_values_id = tep_get_options_valies_id($ep_array);
          $query = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_id . "' and products_options_values_id = '" . $options_values_id . "'" );
          if (tep_db_num_rows($query) == 0){
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $options_id . "', products_options_values_id = '" . $options_values_id . "'");
          }
          $query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options_id . "' and options_values_id = '" . $options_values_id . "'");
          if (tep_db_num_rows($query)){
            $data = tep_db_fetch_array($query);
            $attr_id = $data['products_attributes_id'];
            $str = 'update ' . TABLE_PRODUCTS_ATTRIBUTES . " set ";
            for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
              if (!isset($fields_attributes[$i]['field']) && $i != $keyfieldindex && !isset($fields_attributes[$i]['set'])){
                $str .= $fields_attributes[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "', ";
              }
            }
            $str .= " products_attributes_id ='" . $attr_id . "' where products_id = '" . $products_id . "' and options_id = '" . $options_id . "' and options_values_id = '" . $options_values_id . "'";
            tep_db_query($str);
          }else{
            $str = 'insert into ' . TABLE_PRODUCTS_ATTRIBUTES . " set ";
            for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
              if (!isset($fields_attributes[$i]['field']) && $i != $keyfieldindex && !isset($fields_attributes[$i]['set'])){
                $str .= $fields_attributes[$i]['name'] . " = '" . tep_db_input($ep_array[$fields_attributes[$i]['pos']]) . "', ";
              }
            }
            $str .= " products_id = '" . $products_id . "', options_id = '" . $options_id . "', options_values_id = '" . $options_values_id . "'";
            tep_db_query($str);
            $attr_id = tep_db_insert_id();
          }
          for ($i=0,$n=sizeof($fields_attributes);$i<$n;$i++){
            if (!isset($fields_attributes[$i]['field']) && $i != $keyfieldindex && isset($fields_attributes[$i]['set'])){ 
              eval($fields_attributes[$i]['set'] . '("'.$fields_attributes[$i]['name'].'", ' .$attr_id.', "' . tep_db_prepare_input($ep_array[$fields_attributes[$i]['pos']]) . '");' );
            }
          }
          $messageStack->add(sprintf(TEXT_EP_LINE_IMPORTED, $counter), 'success');
        }
      }
      $counter++;
    }
  }
}

function updateEPProperties(){
  global $languages_id, $keyfieldindex, $separator, $messageStack;
  global $properties, $properties_categories, $properties_languages, $properties_products;
  $ep_data = new upload('usrfl');
  $ep_data->set_destination(DIR_FS_CATALOG . TEMP_DIR);

  if ($ep_data->parse() && $ep_data->save()) {
    $ep_file = DIR_FS_CATALOG . TEMP_DIR . $ep_data->filename;
    $epPointer = fopen($ep_file, "r");
    $counter = 0;
    while ($data = fgetcsv ($epPointer, MAX_CSV_LINE_LENGTH, $separator)){
      foreach ( $data as $idx=>$val) { $data[$idx]=restoreText($val); }
      $ep_array = $data;
      if ($counter == 0){
        for ($i=0,$n=sizeof($properties);$i<$n;$i++){
          for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
            if ($properties[$i]['value'] == $ep_array[$j]){
              $properties[$i]['pos'] = $j;
              break;
            }
          }
        }
        $languages = tep_get_languages();
        for($l=0,$o=sizeof($languages);$l<$o;$l++){
          for ($i=0,$n=sizeof($properties_categories);$i<$n;$i++){
            for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
              if ($properties_categories[$i]['value'] . ' ' . $languages[$l]['code'] == $ep_array[$j]){
                $properties_categories[$i][$languages[$l]['id']] = $j;
                break;
              }
            }
          }
        }
        $languages = tep_get_languages();
        for($l=0,$o=sizeof($languages);$l<$o;$l++){
          for ($i=0,$n=sizeof($properties_languages);$i<$n;$i++){
            for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
              if ($properties_languages[$i]['value'] . ' ' . $languages[$l]['code'] == $ep_array[$j]){
                $properties_languages[$i][$languages[$l]['id']] = $j;
                break;
              }
            }
          }
        }
        $languages = tep_get_languages();
        for($l=0,$o=sizeof($languages);$l<$o;$l++){
          for ($i=0,$n=sizeof($properties_products);$i<$n;$i++){
            for ($j=0,$m=sizeof($ep_array);$j<$m;$j++){
              if ($properties_products[$i]['value'] . ' ' . $languages[$l]['code'] == $ep_array[$j]){
                $properties_products[$i][$languages[$l]['id']] = $j;
                break;
              }
            }
          }
        }
      }else{
        //$messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
        
        if ($properties[$keyfieldindex]['pos'] !== ''){
          if ($ep_array[$properties[$keyfieldindex]['pos']] != ''){
            $check = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_model = '" . $ep_array[$properties[$keyfieldindex]['pos']] . "'");
            if (tep_db_num_rows($check)){
              $data = tep_db_fetch_array($check);
              $products_id = $data['products_id'];

              //Properties categories import
              $categories_id = 0;
              $fields = array();
              $flag = false;
              $sql = 'select categories_id from ' . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " where ";
              for ($i=0,$n=sizeof($properties_categories);$i<$n;$i++){
                if ($properties_categories[$i]['checked'] == '1'){
                  $sql .= $properties_categories[$i]['name'] . " = '" . tep_db_input($ep_array[$properties_categories[$i][$languages_id]]) . "' and ";
                  if ($ep_array[$properties_categories[$i][$languages_id]] != ''){
                    $flag = true;
                  }
                }
              }
              $sql .= ' 1';
              
              if ($flag){
                $check_categories = tep_db_query($sql);
                if (tep_db_num_rows($check_categories)){
                  $data = tep_db_fetch_array($check_categories);
                  $categories_id = $data['categories_id'];
                }else{
                  tep_db_query("insert into " . TABLE_PROPERTIES_CATEGORIES . " set date_added = now()");
                  $categories_id = tep_db_insert_id();
                }
                $languages = tep_get_languages();
                for($i=0,$n=sizeof($languages);$i<$n;$i++){
                  if (tep_db_num_rows($check_categories)){
                    $sql = "update " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " set ";
                  }else{
                    $sql = "insert into " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " set ";
                  }
                  for ($j=0,$m=sizeof($properties_categories);$j<$m;$j++){
                    $sql .= $properties_categories[$j]['name'] . "='" . tep_db_input($ep_array[$properties_categories[$j][$languages[$i]['id']]]) . "', ";
                  }
                  if (tep_db_num_rows($check_categories)){
                    $sql = substr($sql, 0, -2);
                    $sql .= " where categories_id = '" . $categories_id . "' and language_id = '" . $languages[$i]['id'] . "' ";
                  }else{
                    $sql .= " categories_id = '" . $categories_id . "', language_id = '" . $languages[$i]['id'] . "' " ; 
                  }
                  tep_db_query($sql);
                }
              }
              //Properties categories import end
              
              $sql = "select pd.properties_id from " . TABLE_PROPERTIES . " p, " . TABLE_PROPERTIES_DESCRIPTION . " pd, " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2c where pd.language_id = '" . (int)$languages_id . "' and p.properties_id = pd.properties_id and p2c.properties_id = p.properties_id and p2c.categories_id = '" . $categories_id . "' and ";
              
              $flag = false;
              for ($i=0,$n=sizeof($properties_languages);$i<$n;$i++){
                if ($properties_languages[$i]['checked'] == 1 && $ep_array[$properties_languages[$i][$languages_id]] != ''){
                  $sql .= 'pd.' . $properties_languages[$i]['name'] . " = '" . tep_db_input($ep_array[$properties_languages[$i][$languages_id]]) . "' and";
                  $flag = true;
                }
              }
              $sql .= " 1";
              
              if ($flag){
                $check_query = tep_db_query($sql);
                if (tep_db_num_rows($check_query)){
                  $data = tep_db_fetch_array($check_query);
                  $properties_id = $data['properties_id'];
                }else{
                  $sql = "insert into " . TABLE_PROPERTIES . " set ";
                  for ($i=0,$n=sizeof($properties);$i<$n;$i++){
                    if ($i != $keyfieldindex)
                      $sql .= $properties[$i]['name'] . "= '" . tep_db_input($ep_array[$properties[$i]['pos']]) . "', ";
                  }
                  $sql .= " properties_id = ''";
                  tep_db_query($sql);
                  $properties_id = tep_db_insert_id();
                  $sql = "insert into " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " set categories_id = '" . $categories_id ."', properties_id = '" . $properties_id . "'";
                  tep_db_query($sql);
                }

                $languages = tep_get_languages();
                for($i=0,$n=sizeof($languages);$i<$n;$i++){
                  if (tep_db_num_rows($check_query)){
                    $sql = "update " . TABLE_PROPERTIES_DESCRIPTION . " set ";
                  }else{
                    $sql = "insert into " . TABLE_PROPERTIES_DESCRIPTION . " set ";
                  }
                  for ($j=0,$m=sizeof($properties_languages);$j<$m;$j++){
                    $sql .= $properties_languages[$j]['name'] . "='" . tep_db_input($ep_array[$properties_languages[$j][$languages[$i]['id']]]) . "', ";
                  }
                  if (tep_db_num_rows($check_query)){
                    $sql = substr($sql, 0, -2);
                    $sql .= " where properties_id = '" . $properties_id . "' and language_id = '" . $languages[$i]['id'] . "' ";
                  }else{
                    $sql .= " properties_id = '" . $properties_id . "', language_id = '" . $languages[$i]['id'] . "' " ; 
                  }
                  tep_db_query($sql);
                }
                $languages = tep_get_languages();
                tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . $products_id. "' and properties_id = '" . $properties_id . "'");
                for($i=0,$n=sizeof($languages);$i<$n;$i++){
                  $sql = 'insert into ' . TABLE_PROPERTIES_TO_PRODUCTS . " set ";
                  for ($j=0,$m=sizeof($properties_products);$j<$m;$j++){
                    $sql .= $properties_products[$j]['name'] . " = '" . tep_db_input($ep_array[$properties_products[$j][$languages[$i]['id']]]). "', ";
                  }
                  $sql .= " products_id = '" . $products_id . "', properties_id = '" . $properties_id. "', language_id = '" . $languages[$i]['id']. "'";
                  tep_db_query($sql);
                }
              }else{
                $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED_PROPERTY_REQUIRED_FIELD_EMPTY, $counter), 'warning');
              }
              
            }else{
              $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED_PRODUCT_NOT_FOUND, $counter), 'warning');
            }
          }else{
            $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
          }
        }else{
          $messageStack->add(sprintf(TEXT_EP_LINE_NOT_IMPORTED, $counter), 'warning');
        }
      }
      $counter++;
    }
  }
}

function tep_get_categories_full_path($categories_id){
  Global $languages_id;
  $sql2 = tep_db_fetch_array(tep_db_query("SELECT c.categories_id, cd.categories_name, c.parent_id FROM " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c WHERE c.categories_id = cd.categories_id and c.categories_id = '" . $categories_id . "' AND cd.affiliate_id = 0 AND cd.language_id = " . $languages_id)) ;
  if ($sql2['parent_id'] > 0){
    return tep_get_categories_full_path((int)$sql2['parent_id']) . ';' . $sql2['categories_name'];
  }else{
    return $sql2['categories_name'];
  }
}

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>