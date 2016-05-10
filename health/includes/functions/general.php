<?php
/*
$Id: general.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

////
// Stop from parsing any further PHP code
function debug($var, $message = '') {
  if (is_array($var) || is_object($var)) {
    echo '<pre>' . $message . "\n";
    print_r($var);
    echo '</pre>';
  } else {
    echo '$var = ' . $var . '<br>';
  }
}

function tep_exit() {
  tep_session_close();
  exit();
}

////
// Redirect to another page or site
function tep_redirect($url) {
  if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
  }
  if ( (ENABLE_SSL == true) && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
    if (substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER) { // NONSSL url
      $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
    }
  }
  $url = str_replace('&amp;', '&', $url);

  header('Location: ' . $url);
  tep_exit();
}

////
// Parse the data used in the html tags to ensure the tags will not break
function tep_parse_input_field_data($data, $parse) {
  return strtr(trim($data), $parse);
}

function tep_output_string($string, $translate = false, $protected = false) {
  if ($protected == true) {
    return htmlspecialchars($string);
  } else {
    if ($translate == false) {
      return tep_parse_input_field_data($string, array('"' => '&quot;'));
    } else {
      return tep_parse_input_field_data($string, $translate);
    }
  }
}

function tep_output_string_protected($string) {
  return tep_output_string($string, false, true);
}

function tep_sanitize_string($string) {
  $string = ereg_replace(' +', ' ', trim($string));

  return preg_replace("/[<>]/", '_', $string);
}

////
// Return a random row from a database query
function tep_random_select($query) {
  $random_product = '';
  $random_query = tep_db_query($query);
  $num_rows = tep_db_num_rows($random_query);
  if ($num_rows > 0) {
    $random_row = tep_rand(0, ($num_rows - 1));
    tep_db_data_seek($random_query, $random_row);
    $random_product = tep_db_fetch_array($random_query);
  }

  return $random_product;
}

////
// Return a product's name
// TABLES: products
function tep_get_products_name($product_id, $language = '', $search_terms = array()) {
  global $languages_id, $HTTP_SESSION_VARS;
  if (empty($language)) $language = $languages_id;

  $product_query = tep_db_query("select if(length(pd1.products_name) > 0, pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd1 on pd.products_id = pd1.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$language . "' where pd.products_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$language . "' and pd.affiliate_id = '0'");

  $product = tep_db_fetch_array($product_query);

  if (sizeof($search_terms) == 0) {
    return $product['products_name'];
  } else {
    if (MSEARCH_ENABLE == "true" && MSEARCH_HIGHLIGHT_ENABLE == "true") {
      return highlight_text($product['products_name'],$search_terms);
    } else {
      return $product['products_name'];
    }
  }
}

function tep_get_products_seo_page_name($product_id){
  global $languages_id, $HTTP_SESSION_VARS;
  $product_query = tep_db_query("select if(length(p.products_seo_page_name) > 0, p.products_seo_page_name, if(length(pd1.products_name), pd1.products_name, pd.products_name)) as products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd1 on pd.products_id = pd1.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$languages_id . "' where pd.products_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = '0' and p.products_id = pd.products_id");

  $product = tep_db_fetch_array($product_query);
  return $product['products_name'];
}

function tep_get_products_direct_url($product_id, $parameters = ""){
  global $languages_id, $HTTP_SESSION_VARS, $lng;
  
        /*if($parameters!="" && strstr($parameters, 'language='))
        {          
          $language_name = '';
          $cPath_param = substr($parameters, strpos($parameters, 'language=') + 9);
          if (strpos($cPath_param, '&') !== false)
            $language_name = substr($cPath_param, 0, strpos($cPath_param, '&'));
          else 
					  $language_name = $cPath_param;
					  
					$new_languages_id = (int)$lng->catalog_languages[$language_name]['id'];
					if($new_languages_id > 0)$languages_id = $new_languages_id;
        }*/
  
  $product_query = tep_db_fetch_array(tep_db_query("select pd.direct_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd.language_id = '" . (int)$languages_id . "' and pd.products_id = '" . (int)$product_id . "' and p.products_id = pd.products_id"));
										
	if(tep_not_null($product_query['direct_url']))
  return $product_query['direct_url'];
  else
  return false;
}

function tep_get_product_parent_category_id($products_id = '', $categories_id = '')
{
  $return_cPath = '';
  if($products_id > 0)
  {

  $query_categories_id = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "'");
   if(tep_db_num_rows($query_categories_id))
   {
    $row_prod_category_id = tep_db_fetch_array($query_categories_id);
    $categories_id = $row_prod_category_id['categories_id'];
   }
  } 
	  
   $query_category_parent_id = tep_db_query("select categories_id, parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");

    $row_category_parent_id = tep_db_fetch_array($query_category_parent_id);
    $search_cat_id = (int)$row_category_parent_id['categories_id'];
    if($search_cat_id > 0)
    $return_cPath = $search_cat_id;
		if($row_category_parent_id['parent_id'] >= 1)
		{
    do{
    
     $query_category_parent_id = tep_db_query("select categories_id, parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $search_cat_id . "'");
     $row_parent_id = tep_db_fetch_array($query_category_parent_id);
     $search_cat_id = (int)$row_parent_id['parent_id'];
     if($search_cat_id > 0)
     $return_cPath = $search_cat_id . '_' . $return_cPath;              
    }while($search_cat_id >= 1);
		return $return_cPath; 
    }
    else return $return_cPath;
}

function tep_get_categories_name($who_am_i) {
  global $languages_id, $HTTP_SESSION_VARS;
  $the_categories_name = tep_db_fetch_array(tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd left join " . TABLE_CATEGORIES_DESCRIPTION .  " cd1 on cd.categories_id = cd1.categories_id and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and cd1.language_id = '" . (int)$languages_id . "' and cd1.categories_id = '" . (int)$who_am_i . "' where cd.categories_id = '" . (int)$who_am_i . "' and cd.language_id = '" . (int)$languages_id . "' and cd.affiliate_id = '0'"));
  return $the_categories_name['categories_name'];
}

////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products
function tep_get_products_special_price($product_id, $qty = 1) {
  Global $currency_id, $customer_groups_id;

  if ($customer_groups_id != 0 && !check_customer_groups($customer_groups_id, 'groups_is_show_price')){
    return false;
  }

  if (tep_check_product($product_id)) {
    $product_price = tep_get_products_price($product_id, $qty);
  } else {
    return false;
  }

  if (PRODUCTS_BUNDLE_SETS == 'True') {
    $bundle_sets_query = tep_db_query("select p.products_id, sp.num_product from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.sets_id = '" . (int)$product_id . "' and p.products_status = '1' and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
    if (tep_db_num_rows($bundle_sets_query) > 0)
    {
      $sets_discount = tep_db_fetch_array(tep_db_query("select products_sets_discount from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'"));
      if ($sets_discount['products_sets_discount'] > 0)
      {
        return ($product_price * (100 - $sets_discount['products_sets_discount']) / 100);
      }
    }
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    if (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0){
      $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status");
    }else{
      $specials_query = tep_db_query("select s.specials_id, if(sp.specials_new_products_price is NULL, -2, sp.specials_new_products_price) as specials_new_products_price from " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . (int)$customer_groups_id . "'  and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where s.products_id = '" . (int)$product_id . "'  and if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ) and s.status ");
    }
  }else{
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status");
  }

  if (tep_db_num_rows($specials_query)) {
    $special = tep_db_fetch_array($specials_query);
    $special_price = $special['specials_new_products_price'];
    if ($special_price == -2) {
      if ($customer_groups_id != 0){
        if (USE_MARKET_PRICES == 'True') {
          $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . (int)$special['specials_id'] . "' and currencies_id = '" . (int)$currency_id . "' and groups_id = 0");
        }else{
          $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status");
        }
        if (tep_db_num_rows($specials_query)){
          $special = tep_db_fetch_array($specials_query);
          $discount = check_customer_groups($customer_groups_id, 'groups_discount');
          $special_price = $special['specials_new_products_price'] * (1 - ($discount/100));
        }else{
          $special_price = false;
        }
      }else{
        $special_price = false;
      }
    }
  } else {
    $special_price = false;
  }

  $product = tep_db_fetch_array( tep_db_query('select products_model from '.TABLE_PRODUCTS." where products_id='".(int)$product_id."'") );
  if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
    return $special_price;
  }

  Global $salemaker_array;
  if (sizeof($salemaker_array)){

    for ($i=0,$n=sizeof($salemaker_array);$i<$n;$i++){
      if (!is_array($salemaker_array[$i]['sale_categories_all'])){
        continue;
      }else{
        $query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id in ('" . implode("', '", $salemaker_array[$i]['sale_categories_all']) . "')");
        $data = tep_db_fetch_array($query);
        if (!$data['total']){
          continue;
        }else{
          $product_price = tep_get_products_price($product_id, $qty);
          if (($salemaker_array[$i]['sale_pricerange_from'] > 0 && $product_price < $salemaker_array[$i]['sale_pricerange_from']) || ($salemaker_array[$i]['sale_pricerange_to'] > 0 && $product_price > $salemaker_array[$i]['sale_pricerange_to'])){
            continue;
          }else{
            if (!$special_price) {
              $tmp_special_price = $product_price;
            } else {
              $tmp_special_price = $special_price;
            }
            switch ($salemaker_array[$i]['sale_deduction_type']) {
              case 0:
                $sale_product_price = $product_price - $salemaker_array[$i]['sale_deduction_value'];
                $sale_special_price = $tmp_special_price - $salemaker_array[$i]['sale_deduction_value'];
                break;
              case 1:
                $sale_product_price = $product_price - (($product_price * $salemaker_array[$i]['sale_deduction_value']) / 100);
                $sale_special_price = $tmp_special_price - (($tmp_special_price * $salemaker_array[$i]['sale_deduction_value']) / 100);
                break;
              case 2:
                $sale_product_price = $salemaker_array[$i]['sale_deduction_value'];
                $sale_special_price = $salemaker_array[$i]['sale_deduction_value'];
                break;
              default:
                return $special_price;
            }
            if ($sale_product_price < 0) {
              $sale_product_price = 0;
            }

            if ($sale_special_price < 0) {
              $sale_special_price = 0;
            }
            if (!$special_price) {
              return number_format($sale_product_price, 4, '.', '');
            } else {
              switch($salemaker_array[$i]['sale_specials_condition']){
                case 0:
                  return number_format($sale_product_price, 4, '.', '');
                  break;
                case 1:
                  return number_format($special_price, 4, '.', '');
                  break;
                case 2:
                  return number_format($sale_special_price, 4, '.', '');
                  break;
                default:
                  return number_format($special_price, 4, '.', '');
              }
            }
          }
        }
      }
    }
    return $special_price;

    /*

    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_id . "'");
    $i_count = 0;
    while ($product_to_categories = tep_db_fetch_array($product_to_categories_query)){
      if ($i_count++ != 0)
      $while_arr .= 'or' ;
      $while_arr .= "(sale_categories_all like '%," . $product_to_categories['categories_id'] . ",%')";
    }

    $sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where (". $while_arr . ") and sale_status = '1' and groups_id = '" . $customer_groups_id . "' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_deduction_value > 0) and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");
    if (tep_db_num_rows($sale_query)) {
      $sale = tep_db_fetch_array($sale_query);
    } else {
      return $special_price;
    }

    if (!$special_price) {
      $tmp_special_price = $product_price;
    } else {
      $tmp_special_price = $special_price;
    }

    switch ($sale['sale_deduction_type']) {
      case 0:
        $sale_product_price = $product_price - $sale['sale_deduction_value'];
        $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
        break;
      case 1:
        $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
        $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
        break;
      case 2:
        $sale_product_price = $sale['sale_deduction_value'];
        $sale_special_price = $sale['sale_deduction_value'];
        break;
      default:
        return $special_price;
    }

    if ($sale_product_price < 0) {
      $sale_product_price = 0;
    }

    if ($sale_special_price < 0) {
      $sale_special_price = 0;
    }

    if (!$special_price) {
      return number_format($sale_product_price, 4, '.', '');
    } else {
      switch($sale['sale_specials_condition']){
        case 0:
          return number_format($sale_product_price, 4, '.', '');
          break;
        case 1:
          return number_format($special_price, 4, '.', '');
          break;
        case 2:
          return number_format($sale_special_price, 4, '.', '');
          break;
        default:
          return number_format($special_price, 4, '.', '');
      }
    }
    */
  }else{
    return $special_price;
  }
}

////
// Return a product's stock
// TABLES: products
function tep_get_products_stock($products_id) {
  $products_id = normalize_id($products_id);
  if (PRODUCTS_INVENTORY == 'True'){
    $stock_query = tep_db_query("select products_quantity from " . TABLE_INVENTORY . " where products_id = '" . tep_db_input($products_id) . "'");
    if (tep_db_num_rows($stock_query)){
      $stock_values = tep_db_fetch_array($stock_query);
    }else{
      $products_id = tep_get_prid($products_id);
      $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
      $stock_values = tep_db_fetch_array($stock_query);
    }
  }else{
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);
  }

  return $stock_values['products_quantity'];
}

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
function tep_check_stock($products_id, $products_quantity) {
  $stock_left = tep_get_products_stock($products_id) - $products_quantity;
  $out_of_stock = '';

  if ($stock_left < 0) {
    $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
  }

  return $out_of_stock;
}

////
// Break a word in a string if it is longer than a specified length ($len)
function tep_break_string($string, $len, $break_char = '-') {
  $l = 0;
  $output = '';
  for ($i=0, $n=strlen($string); $i<$n; $i++) {
    $char = substr($string, $i, 1);
    if ($char != ' ') {
      $l++;
    } else {
      $l = 0;
    }
    if ($l > $len) {
      $l = 1;
      $output .= $break_char;
    }
    $output .= $char;
  }

  return $output;
}

////
// Return all HTTP GET variables, except those passed as a parameter
function tep_get_all_get_params($exclude_array = '',$as_fields=false) {
  global $HTTP_GET_VARS;

  if (!is_array($exclude_array)) $exclude_array = array();

  $get_url = '';
  if (is_array($HTTP_GET_VARS) && (sizeof($HTTP_GET_VARS) > 0)) {
    reset($HTTP_GET_VARS);
    while (list($key, $value) = each($HTTP_GET_VARS)) {
      if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
        if (is_array($value)){
          for ($i=0,$n=sizeof($value);$i<$n;$i++){
            if ($as_fields) {
              $get_url .= tep_draw_hidden_field( $key.'[]', $value[$i]);
            }else{
              $get_url .= $key . rawurlencode('[]') . '='. rawurlencode(stripslashes($value[$i])) . '&';
            }
          }
        }else{
          if ($as_fields) {
            $get_url .= tep_draw_hidden_field( $key, $value );
          }else{
            $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
          }
        }
      }
    }
  }

  return $get_url;
}

////
// Returns an array with countries
// TABLES: countries
function tep_get_countries($countries_id = '', $with_iso_codes = false) {
  Global $languages_id;
  $countries_array = array();
  if (tep_not_null($countries_id)) {
    if ($with_iso_codes == true) {
      $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' and language_id = '" .(int)$languages_id. "' order by countries_name");
      $countries_values = tep_db_fetch_array($countries);
      $countries_array = array('countries_name' => $countries_values['countries_name'],
      'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
      'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
    } else {
      $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' and language_id = '" .(int)$languages_id. "'");
      $countries_values = tep_db_fetch_array($countries);
      $countries_array = array('countries_name' => $countries_values['countries_name']);
    }
  } else {
    $countries = tep_db_query("select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where language_id = '" .(int)$languages_id. "' order by countries_name");
    while ($countries_values = tep_db_fetch_array($countries)) {
      $countries_array[] = array('countries_id' => $countries_values['countries_id'],
      'countries_name' => $countries_values['countries_name'],
	  'countries_iso_code_2' => $countries_values['countries_iso_code_2']);
    }
  }

  return $countries_array;
}

////
// Alias function to tep_get_countries, which also returns the countries iso codes
function tep_get_countries_with_iso_codes($countries_id) {
  return tep_get_countries($countries_id, true);
}

////
// Generate a path to categories
function tep_get_path($current_category_id = '') {
  global $cPath_array;

  if (tep_not_null($current_category_id)) {
    $cp_size = sizeof($cPath_array);
    if ($cp_size == 0) {
      $cPath_new = $current_category_id;
    } else {
      $cPath_new = '';
      $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
      $last_category = tep_db_fetch_array($last_category_query);

      $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $current_category = tep_db_fetch_array($current_category_query);

      if ($last_category['parent_id'] == $current_category['parent_id']) {
        for ($i=0; $i<($cp_size-1); $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      } else {
        for ($i=0; $i<$cp_size; $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      }
      $cPath_new .= '_' . $current_category_id;

      if (substr($cPath_new, 0, 1) == '_') {
        $cPath_new = substr($cPath_new, 1);
      }
    }
  } else {
    $cPath_new = implode('_', $cPath_array);
  }

  return 'cPath=' . $cPath_new;
}

////
// Returns the clients browser
function tep_browser_detect($component) {
  return stristr($_SERVER["HTTP_USER_AGENT"], $component);
}

////
// Alias function to tep_get_countries()
function tep_get_country_name($country_id) {
  $country_array = tep_get_countries($country_id);

  return $country_array['countries_name'];
}

////
// Returns the zone (State/Province) name
// TABLES: zones
function tep_get_zone_name($country_id, $zone_id, $default_zone) {
  $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
  if (tep_db_num_rows($zone_query)) {
    $zone = tep_db_fetch_array($zone_query);
    return $zone['zone_name'];
  } else {
    return $default_zone;
  }
}

////
// Returns the zone (State/Province) code
// TABLES: zones
function tep_get_zone_code($country_id, $zone_id, $default_zone) {
  $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
  if (tep_db_num_rows($zone_query)) {
    $zone = tep_db_fetch_array($zone_query);
    return $zone['zone_code'];
  } else {
    return $default_zone;
  }
}

////
// Wrapper function for round()
function tep_round($number, $precision) {
// {{
  if (abs($number) < (1 / pow(10, $precision + 1))) $number = 0;
// }}
  if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.') + 1)) > $precision)) {
    $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

    if (substr($number, -1) >= 5) {
      if ($precision > 1) {
        $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
      } elseif ($precision == 1) {
        $number = substr($number, 0, -1) + 0.1;
      } else {
        $number = substr($number, 0, -1) + 1;
      }
    } else {
      $number = substr($number, 0, -1);
    }
  }

  return $number;
}

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
  global $customer_zone_id, $customer_country_id, $customer_groups_id, $tax_rates_array;

  if ($customer_groups_id != 0 && !check_customer_groups($customer_groups_id, 'groups_is_tax_applicable')){
    return 0;
  }


  if ( ($country_id == -1) && ($zone_id == -1) ) {
    if (!tep_session_is_registered('customer_id')) {
      $country_id = STORE_COUNTRY;
      $zone_id = STORE_ZONE;
    } else {
      $country_id = $customer_country_id;
      $zone_id = $customer_zone_id;
    }
  }
  if (isset($tax_rates_array[$class_id][$country_id][$zone_id])){
    return $tax_rates_array[$class_id][$country_id][$zone_id];
  }

  $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");

  if (tep_db_num_rows($tax_query)) {
    $tax_multiplier = 1.0;
    while ($tax = tep_db_fetch_array($tax_query)) {
      $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
    }
    $tax_rates_array[$class_id][$country_id] = array($zone_id => ($tax_multiplier - 1.0) * 100);
    return ($tax_multiplier - 1.0) * 100;
  } else {
    $tax_rates_array[$class_id][$country_id] = array($zone_id => 0);
    return 0;
  }
}

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
function tep_get_tax_description($class_id, $country_id, $zone_id) {
  $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
  if (tep_db_num_rows($tax_query)) {
    $tax_description = '';
    while ($tax = tep_db_fetch_array($tax_query)) {
      $tax_description .= $tax['tax_description'] . ' + ';
    }
    $tax_description = substr($tax_description, 0, -3);

    return $tax_description;
  } else {
    return TEXT_UNKNOWN_TAX_RATE;
  }
}

////
// Add tax to a products price
function tep_add_tax($price, $tax) {
  global $currencies, $currency;

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
    return tep_round($price, $currencies->currencies[$currency]['decimal_places']) + tep_calculate_tax($price, $tax);
  } else {
    return tep_round($price, $currencies->currencies[$currency]['decimal_places']);
  }
}

// Calculates Tax rounding the result
function tep_calculate_tax($price, $tax) {
  global $currencies, $currency;

  return tep_round($price * $tax / 100, $currencies->currencies[$currency]['decimal_places']);
}

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
function tep_count_products_in_category($category_id, $include_inactive = false) {
  Global $customer_groups_id, $HTTP_SESSION_VARS, $currency_id;
  $products_count = 0;

  if (!$include_inactive){
    $add_sql = " and p.products_status = 1 ";
  }
  if ($customer_groups_id == 0){
    $products = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES . " c1, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c1.categories_id = '" . (int)$category_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and (c.categories_left >= c1.categories_left and c.categories_right <= c1.categories_right and c.categories_status = 1) " . $add_sql));
  }else{
    $products = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES . " c1, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0') . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_id = p2c.products_id and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) and p2c.categories_id = c.categories_id and c1.categories_id = '" . (int)$category_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and (c.categories_left >= c1.categories_left and c.categories_right <= c1.categories_right and c.categories_status = 1) " . $add_sql ));
  }

  return $products['total'];
}

////
// Return true if the category has subcategories
// TABLES: categories
function tep_has_category_subcategories($category_id) {
  $child_category = tep_db_fetch_array(tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "' and categories_status = 1"));

  if ($child_category['count'] > 0) {
    return true;
  } else {
    return false;
  }
}

////
// Returns the address_format_id for the given country
// TABLES: countries;
function tep_get_address_format_id($country_id) {
  Global $languages_id;
  $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "' and language_id = '" .(int)$languages_id. "'");
  if (tep_db_num_rows($address_format_query)) {
    $address_format = tep_db_fetch_array($address_format_query);
    return $address_format['format_id'];
  } else {
    return '1';
  }
}

////
// Return a formatted address
// TABLES: address_format
function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
  $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
  $address_format = tep_db_fetch_array($address_format_query);

  $company = tep_output_string_protected($address['company']);
  if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
    $firstname = tep_output_string_protected($address['firstname']);
    $lastname = tep_output_string_protected($address['lastname']);
  } elseif (isset($address['name']) && tep_not_null($address['name'])) {
    $firstname = tep_output_string_protected($address['name']);
    $lastname = '';
  } else {
    $firstname = '';
    $lastname = '';
  }
  $street = tep_output_string_protected($address['street_address']);
  $suburb = tep_output_string_protected($address['suburb']);
  $city = tep_output_string_protected($address['city']);
  $state = tep_output_string_protected($address['state']);
  if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
    $country = tep_get_country_name($address['country_id']);

    if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
      $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
    }
  } elseif (isset($address['country']) && tep_not_null($address['country'])) {
    $country = tep_output_string_protected($address['country']);
  } else {
    $country = '';
  }
  $postcode = tep_output_string_protected($address['postcode']);
  $zip = $postcode;

  if ($html) {
    // HTML Mode
    $HR = '<hr>';
    $hr = '<hr>';
    if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
      $CR = '<br>';
      $cr = '<br>';
      $eoln = $cr;
    } else { // Use values supplied
      $CR = $eoln . $boln;
      $cr = $CR;
    }
  } else {
    // Text Mode
    $CR = $eoln;
    $cr = $CR;
    $HR = '----------------------------------------';
    $hr = '----------------------------------------';
  }

  $statecomma = '';
  $streets = $street;
  if ($suburb != '') $streets = $street . $cr . $suburb;
  if ($country == '') $country = tep_output_string_protected($address['country']);
  if ($state != '') $statecomma = $state . ', ';

  $fmt = $address_format['format'];
  eval("\$address = \"$fmt\";");

  if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
    $address = $company . $cr . $address;
  }

  return $address;
}

////
// Return a formatted address
// TABLES: customers, address_book
function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
  $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
  $address = tep_db_fetch_array($address_query);

  $format_id = tep_get_address_format_id($address['country_id']);

  return tep_address_format($format_id, $address, $html, $boln, $eoln);
}

function tep_row_number_format($number) {
  if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

  return $number;
}

function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
  global $languages_id, $HTTP_SESSION_VARS;

  if (!is_array($categories_array)) $categories_array = array();

  $categories_query = tep_db_query("select c.categories_id, if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on c.categories_id = cd1.categories_id and cd1.affiliate_id = '".(tep_session_is_registered('affiliate_ref')?(int)$HTTP_SESSION_VARS['affiliate_ref']:'0')."' and cd1.language_id = '" . (int)$languages_id . "' where c.parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.affiliate_id = 0 and cd.language_id = '" . (int)$languages_id . "' AND c.categories_status = 1 order by c.sort_order, cd.categories_name");

  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_array[] = array('id' => $categories['categories_id'],
    'text' => $indent . $categories['categories_name']);

    if ($categories['categories_id'] != $parent_id) {
      $categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
    }
  }

  return $categories_array;
}

function tep_get_manufacturers($manufacturers_array = '') {
  if (!is_array($manufacturers_array)) $manufacturers_array = array();

  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
  }

  return $manufacturers_array;
}


////
// Return all subcategory IDs
// TABLES: categories
function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
  $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "' and categories_status = 1");
  while ($subcategories = tep_db_fetch_array($subcategories_query)) {
    $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
    if ($subcategories['categories_id'] != $parent_id) {
      tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
    }
  }
}

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
function tep_date_long($raw_date) {
  if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

  $year = (int)substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 5, 2);
  $day = (int)substr($raw_date, 8, 2);
  $hour = (int)substr($raw_date, 11, 2);
  $minute = (int)substr($raw_date, 14, 2);
  $second = (int)substr($raw_date, 17, 2);

  return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
}

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
function tep_date_short($raw_date) {
  if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

  $year = substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 5, 2);
  $day = (int)substr($raw_date, 8, 2);
  $hour = (int)substr($raw_date, 11, 2);
  $minute = (int)substr($raw_date, 14, 2);
  $second = (int)substr($raw_date, 17, 2);

  if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
    return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  } else {
    return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
  }
}

////
// Parse search string into indivual objects
function tep_parse_search_string($search_str = '', &$objects) {
  $search_str = stripslashes(trim(strtolower($search_str)));

  // Break up $search_str on whitespace; quoted string will be reconstructed later
  $pieces = split('[[:space:]]+', $search_str);

  if (MSEARCH_ENABLE == "true") {
    if (sizeof($pieces)>1) {
      $pares = array();
      for ($j=0;$j<sizeof($pieces);$j++) {
        for ($k=0;$k<sizeof($pieces);$k++) {
          if ($j!=$k) {
            if (strlen($pieces[$j])>=MSEARCH_WORD_LENGTH && strlen($pieces[$k])>=MSEARCH_WORD_LENGTH) {
              $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($pieces[$j]." ".$pieces[$k])."') as sx"));
              $pares[] = $ks_hash["sx"];
            }
          }
        }

      }
      $pares = array_unique($pares);
      $pieces = $pares;
    } else {
      if (strlen($pieces[0])>=MSEARCH_WORD_LENGTH) {
        $ks_hash =  tep_db_fetch_array(tep_db_query("select soundex('".addslashes($pieces[0])."') as sx"));
        $pieces[0] = $ks_hash["sx"];
      } else {
        $pieces[0] = '';
      }
    }
  }

  $objects = array();
  $tmpstring = '';
  $flag = '';

  for ($k=0; $k<count($pieces); $k++) {
    while (substr($pieces[$k], 0, 1) == '(') {
      $objects[] = '(';
      if (strlen($pieces[$k]) > 1) {
        $pieces[$k] = substr($pieces[$k], 1);
      } else {
        $pieces[$k] = '';
      }
    }

    $post_objects = array();

    while (substr($pieces[$k], -1) == ')')  {
      $post_objects[] = ')';
      if (strlen($pieces[$k]) > 1) {
        $pieces[$k] = substr($pieces[$k], 0, -1);
      } else {
        $pieces[$k] = '';
      }
    }

    // Check individual words

    if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
      $objects[] = trim($pieces[$k]);

      for ($j=0; $j<count($post_objects); $j++) {
        $objects[] = $post_objects[$j];
      }
    } else {
      /* This means that the $piece is either the beginning or the end of a string.
      So, we'll slurp up the $pieces and stick them together until we get to the
      end of the string or run out of pieces.
      */

      // Add this word to the $tmpstring, starting the $tmpstring
      $tmpstring = trim(ereg_replace('"', ' ', $pieces[$k]));

      // Check for one possible exception to the rule. That there is a single quoted word.
      if (substr($pieces[$k], -1 ) == '"') {
        // Turn the flag off for future iterations
        $flag = 'off';

        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }

        unset($tmpstring);

        // Stop looking for the end of the string and move onto the next word.
        continue;
      }

      // Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
      $flag = 'on';

      // Move on to the next word
      $k++;

      // Keep reading until the end of the string as long as the $flag is on

      while ( ($flag == 'on') && ($k < count($pieces)) ) {
        while (substr($pieces[$k], -1) == ')') {
          $post_objects[] = ')';
          if (strlen($pieces[$k]) > 1) {
            $pieces[$k] = substr($pieces[$k], 0, -1);
          } else {
            $pieces[$k] = '';
          }
        }

        // If the word doesn't end in double quotes, append it to the $tmpstring.
        if (substr($pieces[$k], -1) != '"') {
          // Tack this word onto the current string entity
          $tmpstring .= ' ' . $pieces[$k];

          // Move on to the next word
          $k++;
          continue;
        } else {
          /* If the $piece ends in double quotes, strip the double quotes, tack the
          $piece onto the tail of the string, push the $tmpstring onto the $haves,
          kill the $tmpstring, turn the $flag "off", and return.
          */
          $tmpstring .= ' ' . trim(ereg_replace('"', ' ', $pieces[$k]));

          // Push the $tmpstring onto the array of stuff to search for
          $objects[] = trim($tmpstring);

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

          // Turn off the flag to exit the loop
          $flag = 'off';
        }
      }
    }
  }

  // add default logical operators if needed
  $temp = array();
  for($i=0; $i<(count($objects)-1); $i++) {
    $temp[] = $objects[$i];
    if ( ($objects[$i] != 'and') &&
    ($objects[$i] != 'or') &&
    ($objects[$i] != '(') &&
    ($objects[$i+1] != 'and') &&
    ($objects[$i+1] != 'or') &&
    ($objects[$i+1] != ')') ) {
      $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
    }
  }
  $temp[] = $objects[$i];
  $objects = $temp;

  $keyword_count = 0;
  $operator_count = 0;
  $balance = 0;

  if (MSEARCH_ENABLE == "true") {
    $objects = array_unique($objects);
    $tt = $objects;
    $objects = array();
    foreach ($tt as $key => $val) {
      if (tep_not_null($val)&&$val!=ADVANCED_SEARCH_DEFAULT_OPERATOR) {
        $objects[] = $val;
        $objects[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $tt = $objects;
    $objects = array();
    for ($i=0;$i<sizeof($tt)-1;$i++) {
      $objects[] = $tt[$i];
    }
  }

  for($i=0; $i<count($objects); $i++) {
    if ($objects[$i] == '(') $balance --;
    if ($objects[$i] == ')') $balance ++;
    if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
      $operator_count ++;
    } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
      $keyword_count ++;
    }
  }

  if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
    return true;
  } else {
    return false;
  }
}

////
// Check date
function tep_checkdate($date_to_check, $format_string, &$date_array) {
  $separator_idx = -1;

  $separators = array('-', ' ', '/', '.');
  $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
  $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

  $format_string = strtolower($format_string);

  if (strlen($date_to_check) != strlen($format_string)) {
    return false;
  }

  $size = sizeof($separators);
  for ($i=0; $i<$size; $i++) {
    $pos_separator = strpos($date_to_check, $separators[$i]);
    if ($pos_separator != false) {
      $date_separator_idx = $i;
      break;
    }
  }

  for ($i=0; $i<$size; $i++) {
    $pos_separator = strpos($format_string, $separators[$i]);
    if ($pos_separator != false) {
      $format_separator_idx = $i;
      break;
    }
  }

  if ($date_separator_idx != $format_separator_idx) {
    return false;
  }

  if ($date_separator_idx != -1) {
    $format_string_array = explode( $separators[$date_separator_idx], $format_string );
    if (sizeof($format_string_array) != 3) {
      return false;
    }

    $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
    if (sizeof($date_to_check_array) != 3) {
      return false;
    }

    $size = sizeof($format_string_array);
    for ($i=0; $i<$size; $i++) {
      if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
      if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
      if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
    }
  } else {
    if (strlen($format_string) == 8 || strlen($format_string) == 9) {
      $pos_month = strpos($format_string, 'mmm');
      if ($pos_month != false) {
        $month = substr( $date_to_check, $pos_month, 3 );
        $size = sizeof($month_abbr);
        for ($i=0; $i<$size; $i++) {
          if ($month == $month_abbr[$i]) {
            $month = $i;
            break;
          }
        }
      } else {
        $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
      }
    } else {
      return false;
    }

    $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
    $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
  }

  if (strlen($year) != 4) {
    return false;
  }

  if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
    return false;
  }

  if ($month > 12 || $month < 1) {
    return false;
  }

  if ($day < 1) {
    return false;
  }

  if (tep_is_leap_year($year)) {
    $no_of_days[1] = 29;
  }

  if ($day > $no_of_days[$month - 1]) {
    return false;
  }

  $date_array = array($year, $month, $day);

  return true;
}

////
// Check if year is a leap year
function tep_is_leap_year($year) {
  if ($year % 100 == 0) {
    if ($year % 400 == 0) return true;
  } else {
    if (($year % 4) == 0) return true;
  }

  return false;
}

////
// Return table heading with sorting capabilities
function tep_create_sort_heading($sortby, $colnum, $heading) {
  global $PHP_SELF;

  $sort_prefix = '';
  $sort_suffix = '';

  if ($sortby) {
    $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
    $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
  }

  return $sort_prefix . $heading . $sort_suffix;
}

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
function tep_get_parent_categories(&$categories, $categories_id) {
  $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "' and categories_status = 1 ");
  while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
    if ($parent_categories['parent_id'] == 0) return true;
    $categories[sizeof($categories)] = $parent_categories['parent_id'];
    if ($parent_categories['parent_id'] != $categories_id) {
      tep_get_parent_categories($categories, $parent_categories['parent_id']);
    }
  }
}

function tep_check_product($products_id){
  global $customer_groups_id, $HTTP_SESSION_VARS;
  if ($customer_groups_id == 0){
    $products_check_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.products_status = 1 and p.products_id = '" . (int)$products_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  ");
  }else{
    $products_check_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "'  where p.products_status = 1 and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) and p.products_id = '" . (int)$products_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " ");
  }
  return tep_db_num_rows($products_check_query);
}

////
// Construct a category path to the product
// TABLES: products_to_categories
function tep_get_product_path($products_id) {
  $cPath = '';

  if (!tep_check_product($products_id)){
    return '';
  }
  $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, ".TABLE_CATEGORIES." c where p.products_id = '" . (int)$products_id . "' and p.products_status = 1 and p.products_id = p2c.products_id and c.categories_id=p2c.categories_id and c.categories_status=1 limit 1");
  if (tep_db_num_rows($category_query)) {
    $category = tep_db_fetch_array($category_query);
    $categories = array();
    tep_get_parent_categories($categories, $category['categories_id']);

    $categories = array_reverse($categories);

    $cPath = implode('_', $categories);

    if (tep_not_null($cPath)) $cPath .= '_';
    $cPath .= $category['categories_id'];
  }

  return $cPath;
}

////
// Return a product ID with attributes
function tep_get_uprid($prid, $params) {
  $uprid = $prid;
  if ( (is_array($params)) && (!strstr($prid, '{')) ) {
    while (list($option, $value) = each($params)) {
      $uprid = $uprid . '{' . $option . '}' . $value;
    }
  }

  return $uprid;
}

////
// Return a product ID from a product ID with attributes
function tep_get_prid($uprid) {
  $pieces = explode('{', $uprid);

  if (is_numeric($pieces[0])) {
    return $pieces[0];
  } else {
    return false;
  }

}

////
// Return a customer greeting
function tep_customer_greeting() {
  global $customer_id, $customer_first_name;

  if (tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id')) {
    $greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
  } else {
    $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
  }

  return $greeting_string;
}

function tep_get_mail_body($force_new=false){
  global $HTTP_SESSION_VARS;
  static $mail_body = false;
  if ( $force_new || $mail_body===false ){
    $tmp_body = @file(tep_href_link('email_template.php', ( (int)$HTTP_SESSION_VARS['affiliate_ref']>0?'ref='.(int)$HTTP_SESSION_VARS['affiliate_ref']:''), 'NONSSL', false));
    if ( $tmp_body===false ) {
      $mail_body = '##EMAIL_TEXT##';
    }else{
      $mail_body = implode('',$tmp_body);
      $mail_body = trim(preg_replace('/\s{2,}/',' ',$mail_body)); // replace new line and multiple spaces to one space
    }
  }
  return $mail_body;
}

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $attachments='') {
  if (SEND_EMAILS != 'true') return false;

  // Instantiate a new mail object
  $message = new email(array('X-Mailer: osCommerce Mailer'));

  // Build the text version
  $text = strip_tags($email_text);
  if (EMAIL_USE_HTML == 'true') {
    // {{
    $email_text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>', $email_text);

    $contents = tep_get_mail_body();

    $email_subject = str_replace('$', '/$/', $email_subject);
    $email_text = str_replace('$', '/$/', $email_text);
    $search = array ("'##EMAIL_TITLE##'i",
    "'##EMAIL_TEXT##'i");
    $replace = array ($email_subject,
    $email_text);
    $email_text = str_replace ('/$/', '$', preg_replace ($search, $replace, $contents));
    // }}
    $message->add_html($email_text, $text);
  } else {
    $message->add_text($text);
  }

  if ($attachments != '' && is_array($attachments)){
    foreach($attachments as $attachment) {
      $message->add_attachment($attachment['file'], $attachment['name']);
    }
  }

  // Send message
  $message->build_message();
  $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
}

////
// Check if product has attributes
function tep_has_product_attributes($products_id) {
// {{ Products Bundle Sets
  $bundle_products = array(tep_get_prid($products_id));
  if (PRODUCTS_BUNDLE_SETS == 'True')
  {
    global $customer_groups_id, $currency_id;
    $bundle_sets_query = tep_db_query("select p.products_id, sp.num_product from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.sets_id = '" . (int)$products_id . "' and p.products_status = '1' and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
    if (tep_db_num_rows($bundle_sets_query) > 0)
    {
      while ($bundle_sets = tep_db_fetch_array($bundle_sets_query))
      {
        $bundle_products[] = $bundle_sets['products_id'];
      }
    }
  }
// }}
  $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where " . (count($bundle_products) > 1 ? " products_id in ('" . implode("','", $bundle_products) . "')" : " products_id = '" . (int)$products_id . "'") );
  $attributes = tep_db_fetch_array($attributes_query);

  if ($attributes['count'] > 0) {
    return true;
  } else {
    return false;
  }
}

////
// Get the number of times a word/character is present in a string
function tep_word_count($string, $needle) {
  $temp_array = split($needle, $string);

  return sizeof($temp_array);
}

function tep_count_modules($modules = '') {
  $count = 0;

  if (empty($modules)) return $count;

  $modules_array = split(';', $modules);

  for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
    $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

    if (is_object($GLOBALS[$class])) {
      if ($GLOBALS[$class]->enabled) {
        $count++;
      }
    }
  }

  return $count;
}

function tep_count_payment_modules() {
  return tep_count_modules(MODULE_PAYMENT_INSTALLED);
}

function tep_count_shipping_modules() {
  return tep_count_modules(MODULE_SHIPPING_INSTALLED);
}

function tep_create_random_value($length, $type = 'mixed') {
  if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

  $rand_value = '';
  while (strlen($rand_value) < $length) {
    if ($type == 'digits') {
      $char = tep_rand(0,9);
    } else {
      $char = chr(tep_rand(0,255));
    }
    if ($type == 'mixed') {
      if (ereg('^[A-Z0-9]$', $char)) $rand_value .= $char;
    } elseif ($type == 'chars') {
      if (eregi('^[a-z]$', $char)) $rand_value .= $char;
    } elseif ($type == 'digits') {
      if (ereg('^[0-9]$', $char)) $rand_value .= $char;
    }
  }

  return $rand_value;
}

function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
  if (!is_array($exclude)) $exclude = array();

  $get_string = '';
  if (sizeof($array) > 0) {
    while (list($key, $value) = each($array)) {
      if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
        $get_string .= $key . $equals . $value . $separator;
      }
    }
    $remove_chars = strlen($separator);
    $get_string = substr($get_string, 0, -$remove_chars);
  }

  return $get_string;
}

function tep_not_null($value) {
  if (is_array($value)) {
    if (sizeof($value) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
      return true;
    } else {
      return false;
    }
  }
}

////
// Output the tax percentage with optional padded decimals
function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
  if (strpos($value, '.')) {
    $loop = true;
    while ($loop) {
      if (substr($value, -1) == '0') {
        $value = substr($value, 0, -1);
      } else {
        $loop = false;
        if (substr($value, -1) == '.') {
          $value = substr($value, 0, -1);
        }
      }
    }
  }

  if ($padding > 0) {
    if ($decimal_pos = strpos($value, '.')) {
      $decimals = strlen(substr($value, ($decimal_pos+1)));
      for ($i=$decimals; $i<$padding; $i++) {
        $value .= '0';
      }
    } else {
      $value .= '.';
      for ($i=0; $i<$padding; $i++) {
        $value .= '0';
      }
    }
  }

  return $value;
}

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
function tep_currency_exists($code) {
  $code = tep_db_prepare_input($code);

  $currency_code = tep_db_query("select currencies_id, code from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
  if ($d = tep_db_fetch_array($currency_code)) {
    return $d['code'];
  } else {
    return false;
  }
}

function tep_string_to_int($string) {
  return (int)$string;
}

////
// Parse and secure the cPath parameter values
function tep_parse_category_path($cPath) {
  // make sure the category IDs are integers
  $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

  // make sure no duplicate category IDs exist which could lock the server in a loop
  $tmp_array = array();
  $n = sizeof($cPath_array);
  for ($i=0; $i<$n; $i++) {
    if (!in_array($cPath_array[$i], $tmp_array)) {
      $tmp_array[] = $cPath_array[$i];
    }
  }

  return $tmp_array;
}

////
// Return a random value
function tep_rand($min = null, $max = null) {
  static $seeded;

  if (!isset($seeded)) {
    mt_srand((double)microtime()*1000000);
    $seeded = true;
  }

  if (isset($min) && isset($max)) {
    if ($min >= $max) {
      return $min;
    } else {
      return mt_rand($min, $max);
    }
  } else {
    return mt_rand();
  }
}

function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
  setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
}

function tep_get_ip_address() {
  if (isset($_SERVER)) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
  } else {
    if (getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_CLIENT_IP')) {
      $ip = getenv('HTTP_CLIENT_IP');
    } else {
      $ip = getenv('REMOTE_ADDR');
    }
  }

  return $ip;
}

function tep_count_customer_orders($id = '', $check_session = true) {
  global $customer_id;

  if (is_numeric($id) == false) {
    if (tep_session_is_registered('customer_id')) {
      $id = $customer_id;
    } else {
      return 0;
    }
  }

  if ($check_session == true) {
    if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
      return 0;
    }
  }

  $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
  $orders_check = tep_db_fetch_array($orders_check_query);

  return $orders_check['total'];
}

function tep_count_customer_address_book_entries($id = '', $check_session = true) {
  global $customer_id;

  if (is_numeric($id) == false) {
    if (tep_session_is_registered('customer_id')) {
      $id = $customer_id;
    } else {
      return 0;
    }
  }

  if ($check_session == true) {
    if ( (tep_session_is_registered('customer_id') == false) || ($id != $customer_id) ) {
      return 0;
    }
  }

  $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
  $addresses = tep_db_fetch_array($addresses_query);

  return $addresses['total'];
}

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
function tep_convert_linefeeds($from, $to, $string) {
  if ((PHP_VERSION < "4.0.5") && is_array($from)) {
    return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
  } else {
    return str_replace($from, $to, $string);
  }
}

// BOF: WebMakers.com Added: Downloads Controller
require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
// EOF: WebMakers.com Added: Downloads Controller
////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with htmlspecialchars()
function tep_decode_specialchars($string){
  $string=str_replace('&gt;', '>', $string);
  $string=str_replace('&lt;', '<', $string);
  $string=str_replace('&#039;', "'", $string);
  $string=str_replace('&quot;', "\"", $string);
  $string=str_replace('&amp;', '&', $string);

  return $string;
}

////
// saved from old code
function tep_output_warning($warning) {
  new errorBox(array(array('text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning)));
}

function tep_get_products_price($products_id, $qty = 1, $price = 0) {
  Global $currency, $currency_id, $customer_groups_id;
  if (PRODUCTS_BUNDLE_SETS != 'True' && USE_MARKET_PRICES != 'True' && CUSTOMERS_GROUPS_ENABLE != 'True' && $price > 0 && $qty == 1) {
    return $price;
  }
  if ($customer_groups_id != 0 && !check_customer_groups($customer_groups_id, 'groups_is_show_price')) {
    return false;
  }

  if (PRODUCTS_BUNDLE_SETS == 'True') {
    $bundle_sets_query = tep_db_query("select p.products_id, sp.num_product from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pgp on p.products_id = pgp.products_id and pgp.groups_id = '" . (int)$customer_groups_id . "' and pgp.currencies_id = '" . (int)(USE_MARKET_PRICES == 'True' ? $currency_id : 0) . "', " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = p.products_id and sp.sets_id = '" . (int)$products_id . "' and p.products_status = '1' and if(pgp.products_group_price is null, 1, pgp.products_group_price != -1 ) order by sp.sort_order");
    if (tep_db_num_rows($bundle_sets_query) > 0)
    {
      $bundle_sets_price = 0;
      while ($bundle_sets = tep_db_fetch_array($bundle_sets_query))
      {
        if (($new_price = tep_get_products_special_price($bundle_sets['products_id'], $qty * $bundle_sets['num_product']))) {
          $bundle_sets_price += $bundle_sets['num_product'] * $new_price;
        } else {
          $bundle_sets_price += $bundle_sets['num_product'] * tep_get_products_price($bundle_sets['products_id'], $qty * $bundle_sets['num_product']);
        }
      }
      return $bundle_sets_price;
    }
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True') {
    $query = tep_db_query("select products_group_price as products_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$products_id  . "' and groups_id = '" . (int)$customer_groups_id . "' and currencies_id = '" . (USE_MARKET_PRICES == 'True' ? $currency_id : '0') . "'");
    $num_rows = tep_db_num_rows($query);
    $data = tep_db_fetch_array($query);
    if (!$num_rows || ($data['products_price'] == -2) || (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0)) {
      if (USE_MARKET_PRICES == 'True') {
        $data = tep_db_fetch_array(tep_db_query("select products_group_price as products_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$products_id  . "' and groups_id = '0' and currencies_id = '" . (int)$currency_id . "'"));
      } else {
        $data  = tep_db_fetch_array(tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'"));
      }
      $discount = check_customer_groups($customer_groups_id, 'groups_discount');
      $data['products_price'] = $data['products_price'] * (1 - ($discount/100));
    }
  } else {
    $data  = tep_db_fetch_array(tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'"));
  }
  if ($qty > 1 && DISCOUNT_TABLE_ENABLE == 'True') {
    return tep_get_products_discount_price($products_id, $qty, $data['products_price']);
  } else {
    return $data['products_price'];
  }
}

function tep_get_products_discount_price($products_id, $qty, $products_price){
  Global $currency, $currency_id, $customer_groups_id;

  if (DISCOUNT_TABLE_ENABLE == 'True'){
    $apply_discount = false;
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $query = tep_db_query("select pp.products_group_discount_price as products_price_discount, pp.products_group_price from " . TABLE_PRODUCTS_PRICES . " pp where pp.products_id = '" . (int)$products_id  ."' and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'? $currency_id :'0'). "'");
      $num_rows = tep_db_num_rows($query);
      $data = tep_db_fetch_array($query);
      if (!$num_rows || ($data['products_price_discount'] == '' && $data['products_group_price'] == -2) || $data['products_price_discount'] == -2 || (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0)){
        if (USE_MARKET_PRICES == 'True'){
          $data = tep_db_fetch_array(tep_db_query("select pp.products_group_discount_price as products_price_discount from " . TABLE_PRODUCTS_PRICES . " pp where pp.products_id = '" . (int)$products_id  ."' and pp.groups_id = '0' and pp.currencies_id = '" . (int)$currency_id . "'"));
        }else{
          $data = tep_db_fetch_array(tep_db_query("select products_price_discount from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'"));
        }
        $apply_discount = true;
      }
    }else{
      $data  = tep_db_fetch_array(tep_db_query("select products_price_discount from " . TABLE_PRODUCTS . " where products_id=".(int)$products_id));
    }
    if ($data['products_price_discount'] == '' || $data['products_price_discount'] == -1){
      return $products_price;
    }
    $ar = split("[:;]", preg_replace('/;\s*$/', '', $data['products_price_discount'])); // remove final separator
    for ($i=0, $n=sizeof($ar);$i<$n;$i=$i+2){
      if ($qty < $ar[$i]){
        if ($i == 0){
          return $products_price;
        }
        $price = $ar[$i-1];
        break;
      }
    }
    if ($qty >= $ar[$i-2]){
      $price = $ar[$i-1];
    }
    if ($apply_discount){
      $discount = check_customer_groups($customer_groups_id, 'groups_discount');
      $price = $price * (1 - ($discount/100));
    }
    return $price;
  }else{
    return $products_price;
  }
}

function check_customer_groups($groups_id, $field){
  $query = tep_db_query("select * from " . TABLE_GROUPS . " where groups_id = '" . (int)$groups_id ."'");
  $data = tep_db_fetch_array($query);
  return $data[$field];
}

function tep_get_options_values_price($products_attributes_id, $qty = 1){
  Global $currency_id, $customer_groups_id;

  if ($customer_groups_id != 0 && !check_customer_groups($customer_groups_id, 'groups_is_show_price')){
    return false;
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True') {
    $query = tep_db_query("select attributes_group_price as options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES. " where products_attributes_id=".(int)$products_attributes_id . " and groups_id = '" . (int)$customer_groups_id . "' and currencies_id = '".(USE_MARKET_PRICES == 'True'?(int)$currency_id:0) . "'");
    $num_rows = tep_db_num_rows($query);
    $data = tep_db_fetch_array($query);
    if (!$num_rows || ($data['options_values_price'] == -2) || (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0)){
      if (USE_MARKET_PRICES == 'True'){
        $data = tep_db_fetch_array(tep_db_query("select attributes_group_price as options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES. " where products_attributes_id=".(int)$products_attributes_id . " and groups_id = '0' and currencies_id = '".(USE_MARKET_PRICES == 'True'?(int)$currency_id:0) . "'"));
      } else {
        $data = tep_db_fetch_array(tep_db_query("select options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$products_attributes_id . "'"));
      }
      $discount = check_customer_groups($customer_groups_id, 'groups_discount');
      $data['options_values_price'] = $data['options_values_price'] * (1 - ($discount/100));
    }
  }else{
    $data = tep_db_fetch_array(tep_db_query("select options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$products_attributes_id . "'"));
  }
  if ($qty > 1 && DISCOUNT_TABLE_ENABLE == 'True' && $data['options_values_price'] > 0){
    return tep_get_options_values_discount_price($products_attributes_id, $qty, $data['options_values_price']);
  }else{
    return $data['options_values_price'];
  }
}

function tep_get_options_values_discount_price($products_attributes_id, $qty, $options_values_price){
  Global $currency, $currency_id, $customer_groups_id;

  if (DISCOUNT_TABLE_ENABLE != 'True'){
    return $options_values_price;
  }
  $apply_discount = false;
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $query = tep_db_query("select attributes_group_discount_price as products_attributes_discount_price, attributes_group_price as options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES. " where products_attributes_id=".(int)$products_attributes_id . " and groups_id = '" . (int)$customer_groups_id . "' and currencies_id = '".(USE_MARKET_PRICES == 'True'?(int)$currency_id:0) . "'");
    $num_rows = tep_db_num_rows($query);
    $data = tep_db_fetch_array($query);
    if (!$num_rows || ($data['products_attributes_discount_price'] == '' && $data['options_values_price'] == -2) || $data['products_attributes_discount_price'] == -2 || (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0)){
      if (USE_MARKET_PRICES == 'True'){
        $data = tep_db_fetch_array(tep_db_query("select attributes_group_discount_price as products_attributes_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES. " where products_attributes_id=".(int)$products_attributes_id . " and groups_id = '0' and currencies_id = '".(USE_MARKET_PRICES == 'True'?(int)$currency_id:0) . "'"));
      }else{
        $data = tep_db_fetch_array(tep_db_query("select products_attributes_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$products_attributes_id . "'"));
      }
      $apply_discount = true;
    }
  }else{
    $data = tep_db_fetch_array(tep_db_query("select products_attributes_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$products_attributes_id . "'"));
  }

  if ($data['products_attributes_discount_price'] == ''){
    return $options_values_price;
  }
  $ar = split("[:;]", preg_replace('/;\s*$/', '', $data['products_attributes_discount_price'])); // remove final separator
  for ($i=0, $n=sizeof($ar);$i<$n;$i=$i+2){
    if ($qty < $ar[$i]){
      if ($i == 0){
        return $options_values_price;
      }else{
        $price = $ar[$i-1];
        break;
      }
    }
  }
  if ($qty >= $ar[$i-2]){
    $price = $ar[$i-1];
  }
  if ($apply_discount){
    $discount = check_customer_groups($customer_groups_id, 'groups_discount');
    $price = $price * (1 - ($discount/100));
  }
  return $price;
}

// for inventory
function normalize_id($uprid) {
  // sort atrributes by option id
  if (preg_match("/^\d+$/", $uprid)){
    return $uprid;
  } else {
    $product_id = tep_get_prid($uprid);
    preg_match_all('/\{([\d\-]+)/', $uprid, $arr);
    $oids = $arr[1];
    preg_match_all('/\}(\d+)/', $uprid, $arr);
    for ($i=0;$i<count($arr[1]);$i++){
      $vids[$oids[$i]] = $arr[1][$i];
    }
    ksort($vids);
    return tep_get_uprid($product_id, $vids);
  }
}

function update_stock($uprid, $qty, $old_qty=0){
  //return true;
  $prid = tep_get_prid($uprid);
  if (!tep_not_null($prid))
      return false;

  if (STOCK_LIMITED == 'true') {
    if ($qty > $old_qty){
      $q = "+" . (int)($qty - $old_qty) . "";
    } else {
      $q = "-" . (int)($old_qty - $qty) . "";
    }
    if (DOWNLOAD_ENABLED == 'true') {
      preg_match_all("/\{\d+\}/", $uprid, $arr);
      $options_id = $arr[0][1];
      preg_match_all("/\}[^\{]+/", $uprid, $arr);
      $values_id = $arr[0][1];

      // Will work with only one option for downloadable products
      // otherwise, we have to build the query dynamically with a loop
      if (is_array($options_id)) {
        $stock_query_raw = "SELECT count(*) as total FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad WHERE pa.products_attributes_id=pad.products_attributes_id and pa.products_id = '" . (int)$prid . "' and pad.products_attributes_filename<>'' ";
        $stock_query_raw .= " and ( 0 ";
        for ($k=0; $k<count($options_id); $k++){
          $stock_query_raw .= " OR (pa.options_id = '" . (int)$options_id[$k] . "' AND pa.options_values_id = '" . (int)$values_id[$k] . "')  ";
        }
        $stock_query_raw .= ") ";
        $d = tep_db_fetch_array(tep_db_query($stock_query_raw));
        if ($d['total']>0) {
          // the download option selected
          return true;
        }
      }
      $stock_query_raw = "SELECT count(*) as total FROM " . TABLE_PRODUCTS . " WHERE products_id = '" . (int)$prid . "' and products_file <> '' ";
      $d = tep_db_fetch_array(tep_db_query($stock_query_raw));
      if ($d['total'] > 0){
        return true;
      }
    }

// {{ Products Bundle Sets
    if (PRODUCTS_BUNDLE_SETS == 'True')
    {
      $vids = array();
      $attributes_query = tep_db_query("select options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "'");
      while ($attributes = tep_db_fetch_array($attributes_query))
      {
        if (preg_match('/\{' . $attributes['options_id'] . '\}' . $attributes['options_values_id'] . '(\{|$)/', $uprid))
        {
          $vids[$attributes['options_id']] = $attributes['options_values_id'];
        }
      }
      ksort($vids);
      $uprid = tep_get_uprid($prid, $vids);
    }
// }}
    if (PRODUCTS_INVENTORY == 'True'){
      $uprid = normalize_id($uprid);
      // update inventory and set active attrib, send notification
      $res = tep_db_query("select inventory_id from " . TABLE_INVENTORY . " where products_id = '" . tep_db_input($uprid) . "'");
      if ($d = tep_db_fetch_array($res)) {
        tep_db_query("update " . TABLE_INVENTORY . " set products_quantity = products_quantity " . $q . " where inventory_id = '" . tep_db_input($d['inventory_id']) . "'");
      } else {
        $r = tep_db_query("select * from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='" . $prid . "' and pd.products_id='" . $prid . "'");
        if ($uprid != $prid){
          if ($d = tep_db_fetch_array($r)) {
            tep_db_query("insert into " . TABLE_INVENTORY . " set products_model='" . tep_db_input(tep_db_prepare_input($d['products_model'])) . "', products_name = '" . tep_db_input(tep_db_prepare_input($d['products_name'])) . "', products_id = '" . tep_db_input($uprid) . "', prid = '" . tep_db_input($prid) . "', products_quantity = '" . $q . "' ");
          }
        }
      }

      if ($uprid != $prid){
        $stock_query = tep_db_query("select max(products_quantity) as max_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'");
        $d = tep_db_fetch_array($stock_query);
        if ( ($d['max_products_quantity'] < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = 0 where products_id = '" . $prid . "'");
        }
      }else{
        $stock_query = tep_db_query("select products_quantity as max_products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $prid . "'");
        $d = tep_db_fetch_array($stock_query);
        if ( ($d['max_products_quantity'] < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = 0 where products_id = '" . $prid . "'");
        }
      }
      $email_inventory = '';
      $res = tep_db_query("select * from " . TABLE_INVENTORY . " where send_notification=1 and  products_quantity <" . STOCK_REORDER_LEVEL . " order by products_quantity  ");
      while ($d = tep_db_fetch_array($res)){
        $email_inventory .= $d['products_name'] . ' (' . $d['products_model'] . ') - ' . $d['products_quantity'] . ' ' . "\n";
      }

      $res = tep_db_query("update " . TABLE_INVENTORY . " set send_notification=0 where send_notification=1 and  products_quantity<" . STOCK_REORDER_LEVEL);
      if (strlen(trim($email_inventory))>0){
        tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Inventory critical quantity notification', nl2br($email_inventory), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
      }
    }else{
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity  " . $q . " where products_id = '" . $prid . "'");
      $data_q = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where  products_id = '" . $prid . "'");
      $data = tep_db_fetch_array($data_q);
      if ($data['products_quantity'] < 1 && (STOCK_ALLOW_CHECKOUT == 'false')){
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status = 0 where products_id = '" . $prid . "'");
      }
    }
  }
}

function get_affiliate_logo(){
  global $affiliate_ref, $HTTP_SESSION_VARS;

  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){

    $data = tep_db_fetch_array(tep_db_query('select * from ' . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_ref . "' and affiliate_manage_logo = 'y'"));
    if ($data['affiliate_logo'] != '' && is_file(DIR_FS_AFFILIATES . $affiliate_ref . '/' . $data['affiliate_logo'])){
      return DIR_WS_AFFILIATES . $affiliate_ref . '/' . $data['affiliate_logo'];
    }else{
      return '';
    }
  }else{
    return '';
  }
}

function get_affiliate_stylesheet(){
  global $affiliate_ref, $HTTP_SESSION_VARS;

  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    $data = tep_db_fetch_array(tep_db_query('select * from ' . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_ref . "' and affiliate_manage_stylesheet = 'y'"));

    if ($data['affiliate_stylesheet'] != '' && is_file(DIR_FS_AFFILIATES . $affiliate_ref . '/' . $data['affiliate_stylesheet'])){
      return DIR_WS_AFFILIATES . $affiliate_ref . '/' . $data['affiliate_stylesheet'];
    }else{
      return '';
    }
  }else{
    return '';
  }
}

function tep_check_affiliate_infobox($affiliate_id){
  $query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_id . "' and affiliate_manage_infobox = 'y'");
  return tep_db_num_rows($query);
}


function checkVAT_local($number){
  if (!ereg("^(((BE|DE|PT)[0-9]{9})|((DK|FI|LU)[0-9]{8})|(IT[0-9]{11})|(GB[0-9]{9})|(GB[0-9]{12})|(ATU[0-9]{8})|(SE[0-9]{10}01)|(ES[A-Z0-9]{1}[0-9]{7}[A-Z0-9]{1})|(NL[0-9]{9}B[0-9]{2})|(IE[0-9]{1}[A-Z0-9]{1}[0-9]{5}[A-Z]{1})|(EL[0-9]{8,9})|(FR[A-Z0-9]{2}[0-9]{9}))",$number)){
    return false;
  }else{
    return true;
  }
}

function checkVAT($number){
  if (strpos($number, 'DE') === false){
    return checkVAT_local($number);
  }else{
    require(DIR_WS_CLASSES . 'http_client.php');
    $http = new httpClient();
    if (!$http->Connect("wddx.bff-online.de", 80)){
      return checkVAT_local($number);
    }
    $http->addHeader('Host', 'wddx.bff-online.de');
    $http->addHeader('User-Agent', 'osCommerce');
    $http->addHeader('Connection', 'Close');

    $status = $http->Get('/ustid.php?eigene_id='.TAX_NUMBER.'&abfrage_id='.$number);
    if ($status != 200) {
      return checkVAT_local($number);
    } else {
      $str = $http->getBody();
    }
    $http->Disconnect();
    $search = "<var name='fehler_code'><string>";
    $pos = strpos($str, $search);
    $code = 0;
    if ($pos !== false){
      $code = substr($str, $pos+strlen($search), 3);
    }

    if ($code == '200'){
      return true;
    }else{
      if ($code == '777' || $code == '205' || $code == '208' || $code == '666' || $code == '999'){
        return checkVAT_local($number);
      }else{
        return false;
      }
    }
  }
}

function referer_stat(){
  global $HTTP_REFERER, $PHP_SELF, $HTTP_GET_VARS, $search_engines_id, $search_words_id;
  $ref_data = parse_url($HTTP_REFERER);
  $localhost = parse_url(HTTP_SERVER);
  // overture
  if ((strpos($ref_data['host'], $localhost['host']) === false) && strlen($HTTP_GET_VARS['source'])>0)
  {
    $ref_data['host'] = 'overture.com';
    $ref_data['query'] = 'source=' . $HTTP_GET_VARS['source'];
  }
  /*
  // Findwhat
  if ((strpos($ref_data['host'], $localhost['host']) === false) && ($HTTP_GET_VARS['findwhat']==1) )
  {
  $ref_data['host'] = 'findwhat.com';
  if ($HTTP_GET_VARS['MT']) {
  $ref_data['query'] = 'MT=' . $HTTP_GET_VARS['MT'];
  } else {
  $ref_data['query'] = 'MT=nokeywords';
  }
  }
  // ePilot
  if ((strpos($ref_data['host'], $localhost['host']) === false) && ($HTTP_GET_VARS['epilot']==1) )
  {
  $ref_data['host'] = 'epilot.com';
  if ($HTTP_GET_VARS['k']) {
  $ref_data['query'] = 'k=' . $HTTP_GET_VARS['k'];
  } else {
  $ref_data['query'] = 'k=nokeywords';
  }
  }*/
  // kelkoo
  if ((strpos($ref_data['host'], $localhost['host']) === false) && ($HTTP_GET_VARS['kelkoo']==1) )
  {
    $ref_data['host'] = 'kelkoo.co.uk';
    $ref_data['query'] = 'keywords=' . $HTTP_GET_VARS['keywords'];
  }
  // referrer=thomweb
  if ((strpos($ref_data['host'], $localhost['host']) === false) && ($HTTP_GET_VARS['referrer']=='thomweb') )
  {
    $ref_data['host'] = 'thomweb';
    $ref_data['query'] = 'keywords=' . $HTTP_GET_VARS['keywords'];
  }
  // dealtime
  if ((strpos($ref_data['host'], $localhost['host']) === false) && ($HTTP_GET_VARS['dealtime']==1) )
  {
    $ref_data['host'] = 'dealtime.co.uk';
    $ref_data['query'] = 'keywords=' . $HTTP_GET_VARS['keywords'];
  }

  if (((strpos($ref_data['host'], $localhost['host']) !== false)) || ($search_engines_id>0)  || (strlen($ref_data['host'])==0))
  {
    return true;
  }
  else
  {
    $ref_data['host'] = preg_replace("/^w{2,3}\d?\.(.*)/i", "\\1", $ref_data['host']);
    $str_host = strtoLower(trim($ref_data['host']));

    $res = tep_db_query("select search_engines_id, wordkey, name from " . TABLE_SEARCH_ENGINES . " where url like '%" . tep_db_input($str_host) . "' order by show_flag desc ");
    if ($data = tep_db_fetch_array($res)) {
      $search_engines_id = $data['search_engines_id'];
      tep_session_register("search_engines_id");
      $arr = explode("&", $ref_data['query']);
      if (strlen($data['wordkey'])==0){
        return;
      }
      for ($i=0; $i<count($arr); $i++) {
        if (strpos($arr[$i], $data['wordkey']) !== false) {
          $val = explode('=', $arr[$i]);
          $search_word = trim(str_replace($data['name'], '', urldecode($val[1])));
        }
      }

      $res = tep_db_query("select search_words_id from " . TABLE_SEARCH_WORDS . " where search_engines_id=" . tep_db_input($search_engines_id) . " and word like '" . tep_db_input($search_word) . "'");
      if ($data = tep_db_fetch_array($res)) {
        $search_words_id = $data['search_words_id'];
        tep_session_register("search_words_id");
        tep_db_query("update " . TABLE_SEARCH_WORDS . " set click_count=click_count+1 where search_words_id='" . tep_db_input($search_words_id) . "'");
      } else {
        tep_db_query("insert into " . TABLE_SEARCH_WORDS . " set word='" . tep_db_input($search_word) . "', click_count=1, search_engines_id=" . (int)$search_engines_id);
        $search_words_id = tep_db_insert_id();
        tep_session_register("search_words_id");
      }
    }
    else
    {
      tep_db_query("insert into " . TABLE_SEARCH_ENGINES . " set url='" . tep_db_input($str_host) . "',  wordkey='" . tep_db_input(urldecode($ref_data['query'])) . "'");
      $search_engines_id = tep_db_insert_id();
      tep_session_register("search_engines_id");
    }
  }
}

function tep_get_products_description_search($product_id, $language = '', $search_terms = array()) {
  global $languages_id, $HTTP_SESSION_VARS;

  if (empty($language)) $language = $languages_id;

  $product_query = tep_db_query("select if(length(pd1.products_description), pd1.products_description, pd.products_description) as products_description from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_DESCRIPTION .  " pd1 on pd.products_id = pd1.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$language . "' where pd.products_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$language . "' and pd.affiliate_id=0");
  $product = tep_db_fetch_array($product_query);

  $descr = strip_tags($product['products_description']);
  $descr = str_replace(array(",",";",".","&","!",":"),array("","","","","",""),$descr);

  if ( tep_not_null( $descr ) && sizeof($search_terms)>0 ) {
    if (MSEARCH_ENABLE == "true" && MSEARCH_HIGHLIGHT_ENABLE == "true") {
      return highlight_text($product['products_description'],$search_terms);
    } else {
      return $product['products_description'];
    }
  } else {
    return $product['products_description'];
  }

}


function highlight_text($text,$search_terms) {
  $descr = strip_tags($text);
  $descr = preg_replace("'&#(\d+)'e","chr(\\1)",$descr);
  $descr = str_replace(array(",",";",".","&","!",":"),array("","","","","",""),$descr);


  $for_bold = array();
  $pieces = split('[[:space:]]+', $descr);
  if (sizeof($pieces)>1) {
    for ($j=0;$j<sizeof($pieces);$j++) {
      if (strlen($pieces[$j])>2) {
        if (sizeof($search_terms) == 1) {
          $pares[] = $pieces[$j];
        }
        for ($k=0;$k<sizeof($pieces);$k++) {
          if ($j!=$k&&strlen($pieces[$k])>2) {
            $pares[] = $pieces[$j]." ".$pieces[$k];
          }
        }
      }
    }
  } else {
    $pares[] = $pieces[0];
  }

  for ($i=0;$i<sizeof($search_terms);$i++) {
    for ($j=0;$j<sizeof($pares);$j++) {
      $ps = tep_db_fetch_array(tep_db_query("select soundex('".addslashes($pares[$j])."') as sx"));
      if (($search_terms[$i] != ADVANCED_SEARCH_DEFAULT_OPERATOR) && ($ps["sx"] == $search_terms[$i])) {
        $_tml = array();
        $_tml = explode(" ",$pares[$j]);
        $for_bold[] = $_tml[0];
        $for_bold[] = $_tml[1];
      }

    }

  }

  for ($i=0;$i<sizeof($for_bold);$i++) {
    $text = str_replace($for_bold[$i],'<font style="background-color: '.MSEARCH_HIGHLIGHT_BGCOLOR.';">'.$for_bold[$i].'</font>',$text);
  }

  return $text;

}

function tep_datetime_short($timestamp){
  return date(DATE_FORMAT, $timestamp);
}

function unhtmlentities ($string)  {
  $trans_tbl = get_html_translation_table (HTML_ENTITIES);
  $trans_tbl = array_flip ($trans_tbl);
  return strtr ($string, $trans_tbl);
}


  function get_affiliate_product_info_url()
  {
    global $affiliate_ref, $HTTP_SESSION_VARS;

    if (tep_session_is_registered('affiliate_ref') && tep_not_null($HTTP_SESSION_VARS['affiliate_ref']) && tep_not_null($affiliate_ref))
    {
      $data = tep_db_fetch_array(tep_db_query('select affiliate_own_product_info_url from ' . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_ref . "' and affiliate_own_product_info = 'y'"));

      if (tep_not_null($data['affiliate_own_product_info_url']))
      {
        return $data['affiliate_own_product_info_url'];
      }
      else
      {
        return '';
      }
    }
    else
    {
      return '';
    }
  }

  function get_affiliate_directory_listing_url()
  {
    global $affiliate_ref, $HTTP_SESSION_VARS;

    if (tep_session_is_registered('affiliate_ref') && tep_not_null($HTTP_SESSION_VARS['affiliate_ref']) && tep_not_null($affiliate_ref))
    {
      $data = tep_db_fetch_array(tep_db_query('select affiliate_directory_listing_url from ' . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_ref . "' and affiliate_own_product_info = 'y'"));

      if (tep_not_null($data['affiliate_directory_listing_url']))
      {
        return $data['affiliate_directory_listing_url'];
      }
      else
      {
        return '';
      }
    }
    else
    {
      return '';
    }
  }


  function get_affiliate_continue_shopping_url()
  {
    global $affiliate_ref, $HTTP_SESSION_VARS;

    if (tep_session_is_registered('affiliate_ref') && tep_not_null($HTTP_SESSION_VARS['affiliate_ref']) && tep_not_null($affiliate_ref))
    {
      $data = tep_db_fetch_array(tep_db_query('select affiliate_continue_shopping_url from ' . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_ref . "' and affiliate_own_product_info = 'y'"));

      if (tep_not_null($data['affiliate_continue_shopping_url']))
      {
        return $data['affiliate_continue_shopping_url'];
      }
      else
      {
        return '';
      }
    }
    else
    {
      return '';
    }
  }

  function tep_get_products_weight($products_id)
  {
    $product = tep_db_fetch_array(tep_db_query("select products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'"));
    return $product['products_weight'];
  }

  /**
   * If use "HTML email" function return clickable url
   * @author Dmitriy Makarov
   * @param string url result of tep_href_link() function <p>
   * or some other url
   *
   */
  function tep_get_clickable_link($tep_href_link) {
    if(EMAIL_USE_HTML == 'true') {
      if(tep_validate_email($tep_href_link)) {
        return '<a href="mailto:' . $tep_href_link . '">' . $tep_href_link . '</a>';
      }
      return '<a href="' . $tep_href_link . '">' . $tep_href_link . '</a>';
    }
    return $tep_href_link;
  }

  /**
   * Check is customer exist
   * @author Dmitriy Makarov
   * @param $customer_id
   * @return boolean
   */
  function tep_is_customer_exist($customer_id) {
    if (tep_db_num_rows(tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_id='" . (int)$customer_id ."'")) > 0) {
      return true;
    }
    return false;
  }

  function is_giveaway($products_id) {
    $query = tep_db_query("select * from " . TABLE_GIVE_AWAY_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    if (tep_db_num_rows($query) > 0) {
      return true;
    }
    return false;
  }
  
  function tep_check_vat_form($customer_id) {
    if ( $customer_id===false ) {
      // not logged, but in this session sent, prevent see not found message
      return tep_not_null($_SESSION['memo_vatsend_for'])?true:false;
    }
    if ( tep_session_is_registered('customer_id') && tep_not_null($_SESSION['memo_vatsend_for']) ) {
      // send w/o login or account
      $recheck = tep_db_fetch_array(tep_db_query("select count(*) as c from " . TABLE_CUSTOMERS . " c where c.customers_id='" . (int)$customer_id . "' AND customers_email_address='".tep_db_input($_SESSION['memo_vatsend_for'])."'"));
      if ( (int)$recheck['c']>0 ) {
        // yes, this customer send vat form in this session && memo_vatsend_for, mark account
        unset($_SESSION['memo_vatsend_for']);
        unset($_SESSION['memo_vatsend_date']);
        $send_date = $_SESSION['memo_vatsend_date'];
        if ( !tep_not_null($send_date) ) $send_date = date('Y-m-d H:i:s');
        tep_db_query("update " . TABLE_CUSTOMERS . " set vat_exemption_form_sent =1, vat_exemption_form_date='".tep_db_input($send_date)."' where customers_id='" . (int)$customer_id . "'");
      }
    }
  
    $r = tep_db_query("select vat_exemption_form_sent from " . TABLE_CUSTOMERS . " c where c.customers_id='" . (int)$customer_id . "'");
    $d = tep_db_fetch_array($r);
    if ($d['vat_exemption_form_sent']=='1') {
      return true;
    }
    return false;
  }
  
function tep_get_categories_direct_url($category_id, $parameters = ""){
  global $languages_id, $HTTP_SESSION_VARS, $lng;
         $arr_category_id = explode("_", $category_id);
         if(count($arr_category_id))$category_id = $arr_category_id[count($arr_category_id)-1]; 

        /*if($parameters!="" && strstr($parameters, 'language='))
        {          
          $language_name = '';
          $cPath_param = substr($parameters, strpos($parameters, 'language=') + 9);
          if (strpos($cPath_param, '&') !== false)
            $language_name = substr($cPath_param, 0, strpos($cPath_param, '&'));
          else 
					  $language_name = $cPath_param;
					  
					$new_languages_id = (int)$lng->catalog_languages[$language_name]['id'];
					if($new_languages_id > 0)$languages_id = $new_languages_id;
        }*/
        
  $category_query = tep_db_fetch_array(tep_db_query("select if(cd.direct_url, cd.direct_url, cd1.direct_url) as direct_url from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES_DESCRIPTION . " cd1 where cd.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$category_id . "' and c.categories_id = cd.categories_id and c.categories_id = cd1.categories_id and cd1.affiliate_id = '0'"));
							
	if(tep_not_null($category_query['direct_url']))
	{
	 //$c_Path = tep_get_path($category_id); 
	 /*$str_path_categories = "";
	 $count_cPath = count($arr_category_id)-1;
	 if((int)$count_cPath > 0)
	 {
	   //$arrCPath = substr($cPath_cc, 6);
	   //if($arrCPath!="")
	   //{
	     $arr_cPath = explode("_", $arrCPath);
	     for($i=0; $i<$count_cPath; $i++)
	     {
	      if($cName = tep_get_categories_direct_url($arr_category_id[$i])){}
	      else
	      $cName = tep_get_categories_name($arr_category_id[$i]);
	      if($cName!="")$str_path_categories .= seo_urlencode($cName) . "/";
	     }
		 //}
	 }*/
   return $str_path_categories . $category_query['direct_url'];
  }
	else
  return false;
}  
?>
