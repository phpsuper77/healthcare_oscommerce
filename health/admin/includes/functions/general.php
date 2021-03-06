<?php
/*
$Id: general.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

//Admin begin
////
//Check login and file access

function debug($var, $message = '') {
  if (is_array($var) || is_object($var)) {
    echo '<pre>' . $message . "\n";
    print_r($var);
    echo '</pre>';
  } else {
    echo '$var = ' . $var . '<br>';
  }
}


function tep_admin_check_login() {
  
  global $PHP_SELF, $login_groups_id, $navigation, $login_id;
  if (!tep_session_is_registered('login_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  } else {
    $filename = basename( $PHP_SELF );
    if ($filename != FILENAME_DEFAULT && $filename != FILENAME_FORBIDEN && $filename != FILENAME_LOGOFF && $filename != FILENAME_ADMIN_ACCOUNT && $filename != FILENAME_POPUP_IMAGE && $filename != 'packingslip.php' && $filename != 'invoice.php') {
      $db_file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_name = '" . $filename . "'");
      if (!tep_db_num_rows($db_file_query)) {
        tep_redirect(tep_href_link(FILENAME_FORBIDEN));
      }
      if (tep_session_is_registered('login_affiliate')){
        if ($filename == FILENAME_MODULES){
          $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_payments = 'y'");
          if (!tep_db_num_rows($data_query)){
            tep_redirect(tep_href_link(FILENAME_FORBIDEN));
          }
        }elseif ($filename == FILENAME_INFOBOX_CONFIGURATION){
          $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_infobox = 'y'");
          if (!tep_db_num_rows($data_query)){
            tep_redirect(tep_href_link(FILENAME_FORBIDEN));
          }
        }elseif ($filename == FILENAME_BANNER_MANAGER){
          $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_banners = 'y'");
          if (!tep_db_num_rows($data_query)){
            tep_redirect(tep_href_link(FILENAME_FORBIDEN));
          }
        }
      }
    }
  }
}

////
//Return 'true' or 'false' value to display boxes and files in index.php and column_left.php
function tep_admin_check_boxes($filename, $boxes='') {
  global $login_groups_id, $login_id;

  $is_boxes = 1;
  if ($boxes == 'sub_boxes') {
    $is_boxes = 0;
  }
  $dbquery = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '" . $is_boxes . "' and admin_files_name = '" . $filename . "'");

  $return_value = false;
  if (tep_db_num_rows($dbquery)) {
    $return_value = true;
    if (tep_session_is_registered('login_affiliate')){
      if ($filename == FILENAME_MODULES){
        $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_payments = 'y'");
        if (!tep_db_num_rows($data_query)){
          $return_value = false;
        }
      }elseif ($filename == FILENAME_INFOBOX_CONFIGURATION || $filename == 'design_controls.php'){
        $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_infobox = 'y'");
        if (!tep_db_num_rows($data_query)){
          $return_value = false;
        }
      }elseif ($filename == FILENAME_BANNER_MANAGER){
        $data_query = tep_db_query("select * from ".TABLE_AFFILIATE." where affiliate_id = '" . $login_id. "' and affiliate_manage_banners = 'y'");
        if (!tep_db_num_rows($data_query)){
          $return_value = false;
        }
      }
    }
  }
  return $return_value;
}

function tep_admin_index_link($filename, $text, $params = ''){
  global $login_groups_id, $login_id;

  if ($filename == FILENAME_BANNER_MANAGER){
    $check_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $login_id . "'");
    $data = tep_db_fetch_array($check_query);
    $can_manage_banners = $data['affiliate_manage_banners'];
    if ($can_manage_banners == 'y'){
      echo '<a class="subtitle" href="' . tep_href_link($filename, $params) . '">'. $text . '</a>';
    }
  }else{
    $query = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '" . $is_boxes . "' and admin_files_name = '" . $filename . "'");
    if (tep_db_num_rows($query)){
      echo '<a class="subtitle" href="' . tep_href_link($filename, $params) . '">'. $text . '</a>';
    }
  }
}
////
//Return files stored in box that can be accessed by user
function tep_admin_files_boxes($filename, $sub_box_name, $params = '') {
  global $login_groups_id;
  $sub_boxes = '';
  if ($filename == FILENAME_BANNER_MANAGER && tep_session_is_registered('login_affiliate')){
    $check_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $login_id . "'");
    $data = tep_db_fetch_array($check_query);
    $can_manage_banners = $data['affiliate_manage_banners'];
    if ($can_manage_banners == 'y'){
      $sub_boxes = '<a href="' . tep_href_link($filename, $params) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';
    }
  }else{
    $dbquery = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' and admin_files_name = '" . $filename . "'");
    if (tep_db_num_rows($dbquery)) {
      $sub_boxes = '<a href="' . tep_href_link($filename, $params) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br>';
    }
  }
  return $sub_boxes;
}

////
//Get selected file for index.php
function tep_selected_file($filename, $default_file = '') {
  global $login_groups_id, $login_id;
  if ($default_file != ''){
    $default_file = $default_file;
  }else{
    $randomize = $filename;//FILENAME_ADMIN_ACCOUNT;
  }


  $can_manage_banners = 'y';
  if ($filename == 'tools.php'){
    $check_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $login_id . "'");
    $data = tep_db_fetch_array($check_query);
    $can_manage_banners = $data['affiliate_manage_banners'];
    if ($can_manage_banners == 'y'){
      $dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . $filename . "'");
    }else{
      $dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . $filename . "' and admin_files_name != 'banner_manager.php'");
    }
  }else{
    $dbquery = tep_db_query("select admin_files_id as boxes_id from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '1' and admin_files_name = '" . $filename . "'");
  }

  if (tep_db_num_rows($dbquery)) {
    $boxes_id = tep_db_fetch_array($dbquery);
    if ($default_file){
      $randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' " . ($can_manage_banners != 'y'?" and admin_files_name != 'banner_manager.php'":'') . " and admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "' and admin_files_name = '" . $default_file . "'");
    }else{
      $randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' " . ($can_manage_banners != 'y'?" and admin_files_name != 'banner_manager.php'":'') . " and admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "' and admin_files_name = '" . $filename . "'");
    }
    if (!tep_db_num_rows($randomize_query)) {
      $randomize_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where FIND_IN_SET( '" . $login_groups_id . "', admin_groups_id) and admin_files_is_boxes = '0' " . ($can_manage_banners != 'y'?" and admin_files_name != 'banner_manager.php'":'') . " and admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "'");
    }
    if (tep_db_num_rows($randomize_query)) {
      $file_selected = tep_db_fetch_array($randomize_query);
      $randomize = $file_selected['admin_files_name'];
    }else{
      $randomize = $filename;
    }
  }else{
    $randomize = FILENAME_DEFAULT;
  }
  return $randomize;
}
//Admin end

////
// Redirect to another page or site
function tep_redirect($url) {
  global $logger;
  if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
  }

  header('Location: ' . $url);

  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    $logger->timer_stop();
  }

  exit;
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
  $string = ereg_replace(' +', ' ', $string);

  return preg_replace("/[<>]/", '_', $string);
}

function tep_customers_name($customers_id) {
  $customers = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
  $customers_values = tep_db_fetch_array($customers);

  return $customers_values['customers_firstname'] . ' ' . $customers_values['customers_lastname'];
}

function tep_get_path($current_category_id = '') {
  global $cPath_array;

  if ($current_category_id == '') {
    $cPath_new = implode('_', $cPath_array);
  } else {
    if (sizeof($cPath_array) == 0) {
      $cPath_new = $current_category_id;
    } else {
      $cPath_new = '';
      $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[(sizeof($cPath_array)-1)] . "'");
      $last_category = tep_db_fetch_array($last_category_query);

      $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $current_category = tep_db_fetch_array($current_category_query);

      if ($last_category['parent_id'] == $current_category['parent_id']) {
        for ($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      } else {
        for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      }

      $cPath_new .= '_' . $current_category_id;

      if (substr($cPath_new, 0, 1) == '_') {
        $cPath_new = substr($cPath_new, 1);
      }
    }
  }

  return 'cPath=' . $cPath_new;
}

function tep_get_all_get_params($exclude_array = '') {
  global $HTTP_GET_VARS;

  if ($exclude_array == '') $exclude_array = array();

  $get_url = '';

  reset($HTTP_GET_VARS);
  while (list($key, $value) = each($HTTP_GET_VARS)) {
    if (($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array))) $get_url .= $key . '=' . $value . '&';
  }

  return $get_url;
}

function tep_date_long($raw_date) {
  if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

  $year = (int)substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 5, 2);
  $day = (int)substr($raw_date, 8, 2);
  $hour = (int)substr($raw_date, 11, 2);
  $minute = (int)substr($raw_date, 14, 2);
  $second = (int)substr($raw_date, 17, 2);

  return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
}

function tep_date_format($raw_date, $format) {
  if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

  $year = (int)substr($raw_date, 0, 4);
  $month = (int)substr($raw_date, 5, 2);
  $day = (int)substr($raw_date, 8, 2);
  $hour = (int)substr($raw_date, 11, 2);
  $minute = (int)substr($raw_date, 14, 2);
  $second = (int)substr($raw_date, 17, 2);

  return strftime($format, mktime($hour, $minute, $second, $month, $day, $year));
}

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
function tep_date_short($raw_date) {
  if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

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

function tep_datetime_short($raw_datetime) {
  if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

  $year = (int)substr($raw_datetime, 0, 4);
  $month = (int)substr($raw_datetime, 5, 2);
  $day = (int)substr($raw_datetime, 8, 2);
  $hour = (int)substr($raw_datetime, 11, 2);
  $minute = (int)substr($raw_datetime, 14, 2);
  $second = (int)substr($raw_datetime, 17, 2);

  return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
}

function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
  global $languages_id;

  if (!is_array($category_tree_array)) $category_tree_array = array();
  if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

  if ($include_itself) {
    $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "' and affiliate_id = 0");
    $category = tep_db_fetch_array($category_query);
    $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
  }

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' and affiliate_id = 0 order by c.sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
    if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
    $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
  }

  return $category_tree_array;
}

function tep_draw_products_pull_down($name, $parameters = '', $exclude = '') {
  global $currencies, $languages_id, $HTTP_POST_VARS;

  if ($exclude == '') {
    $exclude = array();
  }

  $select_string = '<select name="' . $name . '"';

  if ($parameters) {
    $select_string .= ' ' . $parameters;
  }

  $select_string .= '>';

  $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by products_name");
  while ($products = tep_db_fetch_array($products_query)) {
    if (!in_array($products['products_id'], $exclude)) {
      $select_string .= '<option ' . (($HTTP_POST_VARS[$name]==$products['products_id'])?' selected ':'') . ' value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format(tep_get_products_price($products['products_id'], $currencies->currencies[DEFAULT_CURRENCY]['id'])) . ')</option>';
    }
  }

  $select_string .= '</select>';

  return $select_string;
}

function tep_options_name($options_id) {
  global $languages_id;

  $options = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$options_id . "' and language_id = '" . (int)$languages_id . "'");
  $options_values = tep_db_fetch_array($options);

  return $options_values['products_options_name'];
}

function tep_values_name($values_id) {
  global $languages_id;

  $values = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_id . "' and language_id = '" . (int)$languages_id . "'");
  $values_values = tep_db_fetch_array($values);

  return $values_values['products_options_values_name'];
}


function getNewSize($pic, $reqW, $reqH)
{
  $size = @GetImageSize ($pic);

  if($size[0] == 0 || $size[1] == 0)
  {
    $newsize[0] = $reqW;
    $newsize[1] = $reqH;
    return $newsize;
  }

  $scale = @min($reqW/$size[0], $reqH/$size[1]);
  $newsize[0] = $size[0]*$scale; $newsize[1] = $size[1]*$scale;
  return $newsize;
}

function tep_info_image($image, $alt, $width = '', $height = '') {
  if (tep_not_null($image) && (file_exists(DIR_FS_CATALOG_IMAGES . $image)) ) {
    if ($width != '' && $height != ''){
      $size = @GetImageSize(DIR_FS_CATALOG_IMAGES . $image);

      if(!($size[0] <= $width && $size[1] <= $height)) {
        $newsize = getNewSize(DIR_FS_CATALOG_IMAGES . $image, $width, $height);

        $width = $newsize[0];
        $height = $newsize[1];

      } else {
        $width = $size[0];
        $height = $size[1];
      }
    }
    $image = tep_image(DIR_WS_CATALOG_IMAGES . $image, $alt, $width, $height);
  } else {
    $image = TEXT_IMAGE_NONEXISTENT;
  }
  return $image;
}

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

function tep_get_country_name($country_id, $lan_id = 0) {
  Global $languages_id;
  if ($lan_id == 0){
    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "' and language_id = '" . (int)$languages_id . "'");
  }else{
    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "' and language_id = '" . (int)$lan_id . "'");
  }

  if (!tep_db_num_rows($country_query)) {
    return $country_id;
  } else {
    $country = tep_db_fetch_array($country_query);
    return $country['countries_name'];
  }
}

function tep_get_zone_name($country_id, $zone_id, $default_zone) {
  $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
  if (tep_db_num_rows($zone_query)) {
    $zone = tep_db_fetch_array($zone_query);
    return $zone['zone_name'];
  } else {
    return $default_zone;
  }
}

function tep_not_null($value) {
  if (is_array($value)) {
    if (sizeof($value) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
      return true;
    } else {
      return false;
    }
  }
}

function tep_browser_detect($component) {
  global $HTTP_USER_AGENT;

  return stristr($HTTP_USER_AGENT, $component);
}

function tep_tax_classes_pull_down($parameters, $selected = '') {
  $select_string = '<select ' . $parameters . '>';
  $classes_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
  while ($classes = tep_db_fetch_array($classes_query)) {
    $select_string .= '<option value="' . $classes['tax_class_id'] . '"';
    if ($selected == $classes['tax_class_id']) $select_string .= ' SELECTED';
    $select_string .= '>' . $classes['tax_class_title'] . '</option>';
  }
  $select_string .= '</select>';

  return $select_string;
}

function tep_geo_zones_pull_down($parameters, $selected = '') {
  $select_string = '<select ' . $parameters . '>';
  $zones_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
  while ($zones = tep_db_fetch_array($zones_query)) {
    $select_string .= '<option value="' . $zones['geo_zone_id'] . '"';
    if ($selected == $zones['geo_zone_id']) $select_string .= ' SELECTED';
    $select_string .= '>' . $zones['geo_zone_name'] . '</option>';
  }
  $select_string .= '</select>';

  return $select_string;
}

function tep_get_geo_zone_name($geo_zone_id) {
  $zones_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$geo_zone_id . "'");

  if (!tep_db_num_rows($zones_query)) {
    $geo_zone_name = $geo_zone_id;
  } else {
    $zones = tep_db_fetch_array($zones_query);
    $geo_zone_name = $zones['geo_zone_name'];
  }

  return $geo_zone_name;
}

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
  if (isset($address['country']) && tep_not_null($address['country'])) {
    if (is_array($address['country'])){
      $country = tep_output_string_protected($address['country']['title']);
    }else{
      $country = tep_output_string_protected($address['country']);
    }
  }elseif (isset($address['country_id']) && tep_not_null($address['country_id'])) {
    $country = tep_get_country_name($address['country_id']);

    if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
      $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
    }
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

////////////////////////////////////////////////////////////////////////////////////////////////
//
// Function    : tep_get_zone_code
//
// Arguments   : country           country code string
//               zone              state/province zone_id
//               def_state         default string if zone==0
//
// Return      : state_prov_code   state/province code
//
// Description : Function to retrieve the state/province code (as in FL for Florida etc)
//
////////////////////////////////////////////////////////////////////////////////////////////////
function tep_get_zone_code($country, $zone, $def_state) {

  $state_prov_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . (int)$zone . "'");

  if (!tep_db_num_rows($state_prov_query)) {
    $state_prov_code = $def_state;
  }
  else {
    $state_prov_values = tep_db_fetch_array($state_prov_query);
    $state_prov_code = $state_prov_values['zone_code'];
  }

  return $state_prov_code;
}

function tep_get_uprid($prid, $params) {
  $uprid = $prid;
  if ( (is_array($params)) && (!strstr($prid, '{')) ) {
    while (list($option, $value) = each($params)) {
      $uprid = $uprid . '{' . $option . '}' . $value;
    }
  }

  return $uprid;
}

function tep_get_prid($uprid) {
  $pieces = explode('{', $uprid);

  return $pieces[0];
}

function tep_get_languages() {
  $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
  while ($languages = tep_db_fetch_array($languages_query)) {
    $languages_array[] = array('id' => $languages['languages_id'],
    'name' => $languages['name'],
    'code' => $languages['code'],
    'image' => $languages['image'],
    'directory' => $languages['directory']);
  }

  return $languages_array;
}

function tep_get_category_heading_title($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);
  return $category['categories_heading_title'];
}

function tep_get_category_description($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);
  return $category['categories_description'];
}

function tep_get_category_name($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);

  return $category['categories_name'];
}

function tep_get_orders_status_name($orders_status_id, $language_id = '') {
  global $languages_id;

  if (!$language_id) $language_id = $languages_id;
  $orders_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$orders_status_id . "' and language_id = '" . (int)$language_id . "'");
  $orders_status = tep_db_fetch_array($orders_status_query);

  return $orders_status['orders_status_name'];
}

function tep_get_orders_status() {
  global $languages_id;

  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_id");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_status_array[] = array('id' => $orders_status['orders_status_id'],
    'text' => $orders_status['orders_status_name']);
  }

  return $orders_status_array;
}

function tep_get_products_name($product_id, $language_id = 0, $affiliate_id = 0) {
  global $languages_id;

  if ($language_id == 0) $language_id = $languages_id;
  $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_name'];
}


function tep_get_direct_url($product_id, $language_id = 0, $affiliate_id = 0) {
  global $languages_id;

  if ($language_id == 0) $language_id = $languages_id;
  $product_query = tep_db_query("select direct_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['direct_url'];
}

function tep_get_cat_direct_url($categories_id, $language_id = 0, $affiliate_id = 0) {
  global $languages_id;

  if ($language_id == 0) $language_id = $languages_id;
  $product_query = tep_db_query("select direct_url from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . (int)$affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['direct_url'];
}


function tep_get_infobox_file_name($infobox_id, $language_id = 0) {
  global $languages_id;

  if ($language_id == 0) $language_id = $languages_id;
  $infobox_query = tep_db_query("select infobox_file_name from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$infobox_id . "' and language_id = '" . (int)$language_id . "'");
  $infobox = tep_db_fetch_array($infobox_query);

  return $infobox['infobox_file_name'];
}

function tep_get_products_description($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_description'];
}

function tep_get_products_description_short($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_description_short from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_description_short'];
}

function tep_get_products_features($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_features from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_features'];
}

function tep_get_products_faq($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_faq from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_faq'];
}

function tep_get_products_ebay_description($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_ebay_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_ebay_description'];
}

function tep_get_products_head_title_tag($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_head_title_tag'];
}

function tep_get_products_head_desc_tag($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_head_desc_tag'];
}

function tep_get_products_head_keywords_tag($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_head_keywords_tag'];
}

function tep_get_products_url($product_id, $language_id, $affiliate_id = 0) {
  $product_query = tep_db_query("select products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $product = tep_db_fetch_array($product_query);

  return $product['products_url'];
}

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
function tep_get_manufacturer_url($manufacturer_id, $language_id) {
  $manufacturer_query = tep_db_query("select manufacturers_url from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
  $manufacturer = tep_db_fetch_array($manufacturer_query);

  return $manufacturer['manufacturers_url'];
}

////
// Wrapper for class_exists() function
// This function is not available in all PHP versions so we test it before using it.
function tep_class_exists($class_name) {
  if (function_exists('class_exists')) {
    return class_exists($class_name);
  } else {
    return true;
  }
}

////
// Count how many products exist in a category
// TABLES: products, products_to_categories, categories
function tep_products_in_category_count($categories_id, $include_deactivated = false) {
  $products_count = 0;

  if ($include_deactivated) {
    $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$categories_id . "'");
  } else {
    $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$categories_id . "'");
  }

  $products = tep_db_fetch_array($products_query);

  $products_count += $products['total'];

  $childs_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
  if (tep_db_num_rows($childs_query)) {
    while ($childs = tep_db_fetch_array($childs_query)) {
      $products_count += tep_products_in_category_count($childs['categories_id'], $include_deactivated);
    }
  }

  return $products_count;
}

////
// Count how many subcategories exist in a category
// TABLES: categories
function tep_childs_in_category_count($categories_id) {
  $categories_count = 0;

  $categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$categories_id . "'");
  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_count++;
    $categories_count += tep_childs_in_category_count($categories['categories_id']);
  }

  return $categories_count;
}

////
// Returns an array with countries
// TABLES: countries
function tep_get_countries($default = '') {
  Global $languages_id;
  $countries_array = array();
  if ($default) {
    $countries_array[] = array('id' => '',
    'text' => $default);
  }
  $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where language_id = '" . (int)$languages_id . "' order by countries_name");
  while ($countries = tep_db_fetch_array($countries_query)) {
    $countries_array[] = array('id' => $countries['countries_id'],
    'text' => $countries['countries_name']);
  }

  return $countries_array;
}

////
// return an array with country zones
function tep_get_country_zones($country_id) {
  $zones_array = array();
  $zones_query = tep_db_query("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' order by zone_name");
  while ($zones = tep_db_fetch_array($zones_query)) {
    $zones_array[] = array('id' => $zones['zone_id'],
    'text' => $zones['zone_name']);
  }

  return $zones_array;
}

function tep_prepare_country_zones_pull_down($country_id = '') {
  // preset the width of the drop-down for Netscape
  $pre = '';
  if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
    for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
  }

  $zones = tep_get_country_zones($country_id);

  if (sizeof($zones) > 0) {
    $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
    $zones = array_merge($zones_select, $zones);
  } else {
    $zones = array(array('id' => '', 'text' => TYPE_BELOW));
    // create dummy options for Netscape to preset the height of the drop-down
    if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<9; $i++) {
        $zones[] = array('id' => '', 'text' => $pre);
      }
    }
  }

  return $zones;
}

////
// Get list of address_format_id's
function tep_get_address_formats() {
  $address_format_query = tep_db_query("select address_format_id from " . TABLE_ADDRESS_FORMAT . " order by address_format_id");
  $address_format_array = array();
  while ($address_format_values = tep_db_fetch_array($address_format_query)) {
    $address_format_array[] = array('id' => $address_format_values['address_format_id'],
    'text' => $address_format_values['address_format_id']);
  }
  return $address_format_array;
}

////
// Alias function for Store configuration values in the Administration Tool
function tep_cfg_pull_down_country_list($country_id) {
  return tep_draw_pull_down_menu('configuration_value', tep_get_countries(), $country_id);
}

function tep_cfg_pull_down_zone_list($zone_id) {
  return tep_draw_pull_down_menu('configuration_value', tep_get_country_zones(STORE_COUNTRY), $zone_id);
}

function tep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
  $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

  $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
  $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
  while ($tax_class = tep_db_fetch_array($tax_class_query)) {
    $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
    'text' => $tax_class['tax_class_title']);
  }

  return tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
}

function tep_cfg_show_password($text) {
  return str_repeat('*', strlen($text));
}

function tep_cfg_password($text) {
  return tep_draw_password_field('configuration_value', $text);
}
////
// Function to read in text area in admin
function tep_cfg_textarea($text) {
  return tep_draw_textarea_field('configuration_value', false, 35, 5, $text);
}

function tep_cfg_get_zone_name($zone_id) {
  $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_id = '" . (int)$zone_id . "'");

  if (!tep_db_num_rows($zone_query)) {
    return $zone_id;
  } else {
    $zone = tep_db_fetch_array($zone_query);
    return $zone['zone_name'];
  }
}

////
// Sets the status of a banner
function tep_set_banner_status($banners_id, $status) {
  if ($status == '1') {
    return tep_db_query("update " . TABLE_BANNERS . " set status = '1', expires_impressions = NULL, expires_date = NULL, date_status_change = NULL where banners_id = '" . $banners_id . "'");
  } elseif ($status == '0') {
    return tep_db_query("update " . TABLE_BANNERS . " set status = '0', date_status_change = now() where banners_id = '" . $banners_id . "'");
  } else {
    return -1;
  }
}

////
// Sets the status of a product
function tep_set_product_status($products_id, $status) {
  if ($status == '1') {
    return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
  } elseif ($status == '0') {
    return tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0', products_last_modified = now() where products_id = '" . (int)$products_id . "'");
  } else {
    return -1;
  }
}

////
// Sets the status of a product on special
function tep_set_specials_status($specials_id, $status) {
  if ($status == '1') {
    return tep_db_query("update " . TABLE_SPECIALS . " set status = '1', expires_date = NULL, date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  } elseif ($status == '0') {
    return tep_db_query("update " . TABLE_SPECIALS . " set status = '0', date_status_change = now() where specials_id = '" . (int)$specials_id . "'");
  } else {
    return -1;
  }
}

////
// Sets timeout for the current script.
// Cant be used in safe mode.
function tep_set_time_limit($limit) {
  if (!get_cfg_var('safe_mode')) {
    set_time_limit($limit);
  }
}

////
// Alias function for Store configuration values in the Administration Tool
function tep_cfg_select_option($select_array, $key_value, $key = '') {
  $string = '';

  for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    $name = ((tep_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');

    $string .= '<br><input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';

    if ($key_value == $select_array[$i]) $string .= ' CHECKED';

    $string .= '> ' . $select_array[$i];
  }

  return $string;
}

////
// Alias function for module configuration keys
function tep_mod_select_option($select_array, $key_name, $key_value) {
  reset($select_array);
  while (list($key, $value) = each($select_array)) {
    if (is_int($key)) $key = $value;
    $string .= '<br><input type="radio" name="configuration[' . $key_name . ']" value="' . $key . '"';
    if ($key_value == $key) $string .= ' CHECKED';
    $string .= '> ' . $value;
  }

  return $string;
}

////
// Retreive server information
function tep_get_system_information() {
  global $HTTP_SERVER_VARS;

  $db_query = tep_db_query("select now() as datetime");
  $db = tep_db_fetch_array($db_query);

  list($system, $host, $kernel) = preg_split('/[\s,]+/', @exec('uname -a'), 5);

  return array('date' => tep_datetime_short(date('Y-m-d H:i:s')),
  'system' => $system,
  'kernel' => $kernel,
  'host' => $host,
  'ip' => gethostbyname($host),
  'uptime' => @exec('uptime'),
  'http_server' => $HTTP_SERVER_VARS['SERVER_SOFTWARE'],
  'php' => PHP_VERSION,
  'zend' => (function_exists('zend_version') ? zend_version() : ''),
  'db_server' => DB_SERVER,
  'db_ip' => gethostbyname(DB_SERVER),
  'db_version' => 'MySQL ' . (function_exists('mysql_get_server_info') ? mysql_get_server_info() : ''),
  'db_date' => tep_datetime_short($db['datetime']));
}

function tep_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {
  global $languages_id;

  if (!is_array($categories_array)) $categories_array = array();

  if ($from == 'product') {
    $categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$id . "'");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($categories['categories_id'] == '0') {
        $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
      } else {
        $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$categories['categories_id'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $category = tep_db_fetch_array($category_query);
        $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
        if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
        $categories_array[$index] = array_reverse($categories_array[$index]);
      }
      $index++;
    }
  } elseif ($from == 'category') {
    $category_query = tep_db_query("select cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);
    $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
    if ( (tep_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = tep_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
  }

  return $categories_array;
}

function tep_output_generated_category_path($id, $from = 'category') {
  $calculated_category_path_string = '';
  $calculated_category_path = tep_generate_category_path($id, $from);
  for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
    for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
      $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
  }
  $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

  if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

  return $calculated_category_path_string;
}

function tep_get_generated_category_path_ids($id, $from = 'category') {
  $calculated_category_path_string = '';
  $calculated_category_path = tep_generate_category_path($id, $from);
  for ($i=0, $n=sizeof($calculated_category_path); $i<$n; $i++) {
    for ($j=0, $k=sizeof($calculated_category_path[$i]); $j<$k; $j++) {
      $calculated_category_path_string .= $calculated_category_path[$i][$j]['id'] . '_';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -1) . '<br>';
  }
  $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

  if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

  return $calculated_category_path_string;
}

function tep_remove_category($category_id) {
  $category_image_query = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  $category_image = tep_db_fetch_array($category_image_query);

  $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
  $duplicate_image = tep_db_fetch_array($duplicate_image_query);

  if ($duplicate_image['total'] < 2) {
    if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
      @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
    }
  }

  tep_db_query("delete from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");

  if (USE_CACHE == 'true') {
    tep_reset_cache_block('categories');
    tep_reset_cache_block('also_purchased');
  }
}

function tep_remove_product($product_id) {
  $product_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
  $product_image = tep_db_fetch_array($product_image_query);

  $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_image = '" . tep_db_input($product_image['products_image']) . "'");
  $duplicate_image = tep_db_fetch_array($duplicate_image_query);

  if ($duplicate_image['total'] < 2) {
    if (file_exists(DIR_FS_CATALOG_IMAGES . $product_image['products_image'])) {
      @unlink(DIR_FS_CATALOG_IMAGES . $product_image['products_image']);
    }
  }
  //if (USE_MARKET_PRICES == 'True'){
  tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_TO_AFFILIATES . " where products_id = '" . (int)$product_id . "'");
  $query = tep_db_query("select specials_id from " . TABLE_SPECIALS . " where products_id = '" .(int)$product_id . "'");
  while ($data = tep_db_fetch_array($query)){
    tep_db_query("delete from " . TABLE_SPECIALS_PRICES . " where specials_id = " . $data['specials_id']);
  }
  $query = tep_db_query("select products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
  while ($data = tep_db_fetch_array($query)){
    tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . (int)$data['products_attributes_id'] . "'");
  }
  //}
  if (PRODUCTS_PROPERTIES == 'True'){
    $query = tep_db_query("select p.*, p2p.set_value from " . TABLE_PROPERTIES . " p, " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p where p.properties_id = p2p.properties_id and p2p.products_id = '" . (int)$product_id . "'");
    while ($data = tep_db_fetch_array($query)){
      if (($data['properties_type'] == 5 || $data['properties_type'] == 6)){
        @unlink(DIR_WS_CATALOG . $data['set_value']);
      }
    }
    tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
  }
  if (SUPPLEMENT_STATUS == 'True'){
    tep_db_query("delete from " . TABLE_PRODUCTS_UPSELL . " where products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_PRODUCTS_UPSELL . " where upsell_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_CATS_PRODUCTS_XSELL . " where xsell_products_id = '" . (int)$product_id . "'");
    tep_db_query("delete from " . TABLE_CATS_PRODUCTS_UPSELL . " where upsell_products_id = '" . (int)$product_id . "'");
  }
  tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where products_id = '" . (int)$product_id . "'");
  tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$product_id . "'");

  if (PRODUCTS_BUNDLE_SETS == 'True') {
    tep_db_query("delete from " . TABLE_SETS_PRODUCTS . " where sets_id = '" . (int)$product_id . "'");
  }

  if (PRODUCTS_INVENTORY == 'True') {
    tep_db_query("delete from " . TABLE_INVENTORY . " where prid = '" . (int)$product_id . "'");
  }

  $product_reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");
  while ($product_reviews = tep_db_fetch_array($product_reviews_query)) {
    tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$product_reviews['reviews_id'] . "'");
  }
  tep_db_query("delete from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_id . "'");

  if (USE_CACHE == 'true') {
    tep_reset_cache_block('categories');
    tep_reset_cache_block('also_purchased');
  }
}

function tep_remove_order($order_id, $restock = false) {
  if ($restock == 'on') {
    $order_query = tep_db_query("select uprid, products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    while ($order = tep_db_fetch_array($order_query)) {
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
      update_stock($order['uprid'], $order['products_quantity'], 0);
    }
  }

  tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
  tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
  tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "'");
  tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
  tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
}

function tep_reset_cache_block($cache_block) {
  global $cache_blocks;

  for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
    if ($cache_blocks[$i]['code'] == $cache_block) {
      if ($cache_blocks[$i]['multiple']) {
        if ($dir = @opendir(DIR_FS_CACHE)) {
          while ($cache_file = readdir($dir)) {
            $cached_file = $cache_blocks[$i]['file'];
            $languages = tep_get_languages();
            for ($j=0, $k=sizeof($languages); $j<$k; $j++) {
              $cached_file_unlink = ereg_replace('-language', '-' . $languages[$j]['directory'], $cached_file);
              if (ereg('^' . $cached_file_unlink, $cache_file)) {
                @unlink(DIR_FS_CACHE . $cache_file);
              }
            }
          }
          closedir($dir);
        }
      } else {
        $cached_file = $cache_blocks[$i]['file'];
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $cached_file = ereg_replace('-language', '-' . $languages[$i]['directory'], $cached_file);
          @unlink(DIR_FS_CACHE . $cached_file);
        }
      }
      break;
    }
  }
}

function tep_get_file_permissions($mode) {
  // determine type
  if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
    $type = 's';
  } elseif ( ($mode & 0x4000) == 0x4000) { // directory
    $type = 'd';
  } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
    $type = 'l';
  } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
    $type = '-';
  } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
    $type = 'b';
  } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
    $type = 'c';
  } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
    $type = 'p';
  } else { // unknown
    $type = '?';
  }

  // determine permissions
  $owner['read']    = ($mode & 00400) ? 'r' : '-';
  $owner['write']   = ($mode & 00200) ? 'w' : '-';
  $owner['execute'] = ($mode & 00100) ? 'x' : '-';
  $group['read']    = ($mode & 00040) ? 'r' : '-';
  $group['write']   = ($mode & 00020) ? 'w' : '-';
  $group['execute'] = ($mode & 00010) ? 'x' : '-';
  $world['read']    = ($mode & 00004) ? 'r' : '-';
  $world['write']   = ($mode & 00002) ? 'w' : '-';
  $world['execute'] = ($mode & 00001) ? 'x' : '-';

  // adjust for SUID, SGID and sticky bit
  if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
  if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
  if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

  return $type .
  $owner['read'] . $owner['write'] . $owner['execute'] .
  $group['read'] . $group['write'] . $group['execute'] .
  $world['read'] . $world['write'] . $world['execute'];
}

function tep_remove($source) {
  global $messageStack, $tep_remove_error;

  if (isset($tep_remove_error)) $tep_remove_error = false;

  if (is_dir($source)) {
    $dir = dir($source);
    while ($file = $dir->read()) {
      if ( ($file != '.') && ($file != '..') ) {
        if (is_writeable($source . '/' . $file)) {
          tep_remove($source . '/' . $file);
        } else {
          $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
          $tep_remove_error = true;
        }
      }
    }
    $dir->close();

    if (is_writeable($source)) {
      rmdir($source);
    } else {
      $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
      $tep_remove_error = true;
    }
  } else {
    if (is_writeable($source)) {
      unlink($source);
    } else {
      $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
      $tep_remove_error = true;
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

function unhtmlentities ($string)  {
  $trans_tbl = get_html_translation_table (HTML_ENTITIES);
  $trans_tbl = array_flip ($trans_tbl);
  return strtr ($string, $trans_tbl);
}

function tep_get_mail_body($force_new=false, $force_affiliate=false){
  global $login_id;
  static $mail_body = false;
  static $last_params = '';
  if ( $force_affiliate!==false ) {
    $new_params = ( (int)$force_affiliate>0?'ref='.(int)$force_affiliate:'');
  }elseif (tep_session_is_registered('login_affiliate')){
    $new_params = ( (int)$login_id>0?'ref='.(int)$login_id:'');
  }
  if ( $new_params!==$last_params ) {
    $last_params = $new_params;
    $force_new = true; 
  }
  if ( $force_new || $mail_body===false ){
    $tmp_body = @file(tep_catalog_href_link('email_template.php', $last_params, 'NONSSL'));
    if ( $tmp_body===false ) {
      $mail_body = '##EMAIL_TEXT##';
    }else{
      $mail_body = implode('',$tmp_body);
      $mail_body = trim(preg_replace('/\s{2,}/',' ',$mail_body)); // replace new line and multiple spaces to one space
    }
  }
  return $mail_body;
}

function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
  if (SEND_EMAILS != 'true') return false;
  // {{
  if (EMAIL_USE_HTML != 'true')
  {
    $email_text = str_replace('&nbsp;', ' ', $email_text);
    $email_text = unhtmlentities ($email_text);
  }
  // }}
  // Instantiate a new mail object
  $message = new email(array('X-Mailer: osCommerce'));

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

  // Send message
  $message->build_message();
  $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
}

function tep_get_tax_class_title($tax_class_id) {
  if ($tax_class_id == '0') {
    return TEXT_NONE;
  } else {
    $classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$tax_class_id . "'");
    $classes = tep_db_fetch_array($classes_query);

    return $classes['tax_class_title'];
  }
}

function tep_banner_image_extension() {
  if (function_exists('imagetypes')) {
    if (imagetypes() & IMG_PNG) {
      return 'png';
    } elseif (imagetypes() & IMG_JPG) {
      return 'jpg';
    } elseif (imagetypes() & IMG_GIF) {
      return 'gif';
    }
  } elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
    return 'png';
  } elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
    return 'jpg';
  } elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
    return 'gif';
  }

  return false;
}

////
// Wrapper function for round() for php3 compatibility
function tep_round($value, $precision) {
  if (PHP_VERSION < 4) {
    $exp = pow(10, $precision);
    return round($value * $exp) / $exp;
  } else {
    return round($value, $precision);
  }
}

////
// Add tax to a products price
function tep_add_tax($price, $tax) {
  global $currencies;

  if (DISPLAY_PRICE_WITH_TAX == 'true') {
    return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
  } else {
    return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }
}

// Calculates Tax rounding the result
function tep_calculate_tax($price, $tax) {
  global $currencies;

  return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
}

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
  global $customer_zone_id, $customer_country_id;

  if ( ($country_id == -1) && ($zone_id == -1) ) {
    if (!tep_session_is_registered('customer_id')) {
      $country_id = STORE_COUNTRY;
      $zone_id = STORE_ZONE;
    } else {
      $country_id = $customer_country_id;
      $zone_id = $customer_zone_id;
    }
  }

  $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za ON tr.tax_zone_id = za.geo_zone_id left join " . TABLE_GEO_ZONES . " tz ON tz.geo_zone_id = tr.tax_zone_id WHERE (za.zone_country_id IS NULL OR za.zone_country_id = '0' OR za.zone_country_id = '" . (int)$country_id . "') AND (za.zone_id IS NULL OR za.zone_id = '0' OR za.zone_id = '" . (int)$zone_id . "') AND tr.tax_class_id = '" . (int)$class_id . "' GROUP BY tr.tax_priority");
  if (tep_db_num_rows($tax_query)) {
    $tax_multiplier = 0;
    while ($tax = tep_db_fetch_array($tax_query)) {
      $tax_multiplier += $tax['tax_rate'];
    }
    return $tax_multiplier;
  } else {
    return 0;
  }
}

////
// Returns the tax rate for a tax class
// TABLES: tax_rates
function tep_get_tax_rate_value($class_id) {
  $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$class_id . "' group by tax_priority");
  if (tep_db_num_rows($tax_query)) {
    $tax_multiplier = 0;
    while ($tax = tep_db_fetch_array($tax_query)) {
      $tax_multiplier += $tax['tax_rate'];
    }
    return $tax_multiplier;
  } else {
    return 0;
  }
}

function tep_call_function($function, $parameter, $object = '') {
  if ($object == '') {
    return call_user_func($function, $parameter);
  } elseif (PHP_VERSION < 4) {
    return call_user_method($function, $object, $parameter);
  } else {
    return call_user_func(array($object, $function), $parameter);
  }
}

function tep_get_zone_class_title($zone_class_id) {
  if ($zone_class_id == '0') {
    return TEXT_NONE;
  } else {
    $classes_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$zone_class_id . "'");
    $classes = tep_db_fetch_array($classes_query);

    return $classes['geo_zone_name'];
  }
}

function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
  $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

  $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
  $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
  while ($zone_class = tep_db_fetch_array($zone_class_query)) {
    $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
    'text' => $zone_class['geo_zone_name']);
  }

  return tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
}

function tep_cfg_pull_down_order_statuses($order_status_id, $key = '') {
  global $languages_id;

  $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

  $statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));
  $statuses_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");
  while ($statuses = tep_db_fetch_array($statuses_query)) {
    $statuses_array[] = array('id' => $statuses['orders_status_id'],
    'text' => $statuses['orders_status_name']);
  }

  return tep_draw_pull_down_menu($name, $statuses_array, $order_status_id);
}

function tep_get_order_status_name($order_status_id, $language_id = '') {
  global $languages_id;

  if ($order_status_id < 1) return TEXT_DEFAULT;

  if (!is_numeric($language_id)) $language_id = $languages_id;

  $status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$order_status_id . "' and language_id = '" . (int)$language_id . "'");
  $status = tep_db_fetch_array($status_query);

  return $status['orders_status_name'];
}

////
// Return a random value
function tep_rand($min = null, $max = null) {
  static $seeded;

  if (!$seeded) {
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

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
function tep_convert_linefeeds($from, $to, $string) {
  if ((PHP_VERSION < "4.0.5") && is_array($from)) {
    return ereg_replace('(' . implode('|', $from) . ')', $to, $string);
  } else {
    return str_replace($from, $to, $string);
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
// Alias function for array of configuration values in the Administration Tool
function tep_cfg_select_multioption($select_array, $key_value, $key = '') {
  for ($i=0; $i<sizeof($select_array); $i++) {
    $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
    $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
    $key_values = explode( ", ", $key_value);
    if ( in_array($select_array[$i], $key_values) ) $string .= 'CHECKED';
    $string .= '> ' . $select_array[$i];
  }
  return $string;
}

//create a select list to display list of themes available for selection
function tep_cfg_pull_down_template_list($template_id, $key = '') {
  $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

  $template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_name");
  while ($template = tep_db_fetch_array($template_query)) {
    $template_array[] = array('id' => $template['template_name'],
    'text' => $template['template_name']);
  }

  return tep_draw_pull_down_menu($name, $template_array, $template_id);
}


// BOF: WebMakers.com Added: Downloads Controller
require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
// EOF: WebMakers.com Added: Downloads Controller

// stock processing
function update_stock($uprid, $qty, $old_qty = 0, $products_attributes = ''){
  if (PRODUCTS_INVENTORY != 'True'){
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "select products_quantity, pad.products_attributes_filename from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_ATTRIBUTES . " pa on p.products_id=pa.products_id left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pa.products_attributes_id=pad.products_attributes_id where p.products_id = '" . tep_get_prid($uprid) . "'";
        // Will work with only one option for downloadable products
        // otherwise, we have to build the query dynamically with a loop
        //$products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " and ( ";
          foreach($products_attributes as $options_id => $options_values_id){
            $stock_query_raw .= " ( pa.options_id = '" . $options_id . "' and pa.options_values_id = '" . $options_values_id . "' ) or";
          }
          $stock_query_raw .= " 0 )";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($uprid) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
        // do not decrement quantities if products_attributes_filename exists
        $delta = $qty - $old_qty;
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $delta;
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($uprid) . "'");
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($uprid) . "'");
        }
      }
    }
  }else{
    $prid = tep_get_prid($uprid);
    if (STOCK_LIMITED == 'true') {
      if ($qty > $old_qty){
        $q = "+'" . ($qty - $old_qty) . "'";
      } else {
        $q = "-'" . ($old_qty - $qty) . "'";
      }
      if (DOWNLOAD_ENABLED == 'true') {
        preg_match_all("/\{\d+\}/", $uprid, $arr);
        $options_id = $arr[0][1];
        preg_match_all("/\}[^\{]+/", $uprid, $arr);
        $values_id = $arr[0][1];
        $stock_query_raw = "SELECT count(*) as total FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad WHERE pa.products_attributes_id=pad.products_attributes_id and pa.products_id = '" . $prid . "' and pad.products_attributes_filename<>'' ";
        // Will work with only one option for downloadable products
        // otherwise, we have to build the query dynamically with a loop
        if (is_array($options_id)) {
          $stock_query_raw .= " and ( 0 ";
          for ($k=0; $k<count($options_id); $k++){
            $stock_query_raw .= " OR (pa.options_id = '" . $options_id[$k] . "' AND pa.options_values_id = '" . $values_id[$k] . "')  ";
          }
          $stock_query_raw .= ") ";
        }
        $stock_query = tep_db_query($stock_query_raw);
        $d = tep_db_fetch_array($stock_query);
        if ($d['total']>0) {
          // the download option selected
          return true;
        }
      }
      // in products table save total qty in the inventory - by attributes
      tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity  " . $q . " where products_id = '" . $prid . "'");
      $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $prid . "'");
      $d = tep_db_fetch_array($stock_query);
      if ( ($d['products_quantity'] < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $prid . "'");
      }
      // update inventory and set active attrib, send notification
      $uprid = normalize_id($uprid);
      $res = tep_db_query("select inventory_id from " . TABLE_INVENTORY . " where products_id = '" . tep_db_input($uprid) . "'");
      if ($d = tep_db_fetch_array($res)) {
        tep_db_query("update " . TABLE_INVENTORY . " set products_quantity = products_quantity " . $q . " where inventory_id = '" . tep_db_input($d['inventory_id']) . "'");
      } else {
        $r = tep_db_query("select * from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id='" . $prid . "' and pd.products_id='" . $prid . "'");
        if ($d = tep_db_fetch_array($res)) {
          tep_db_query("insert into " . TABLE_INVENTORY . " set products_model='" . tep_db_input(tep_db_prepare_input($d['products_model'])) . "', products_name = '" . tep_db_input(tep_db_prepare_input($d['products_name'])) . "', products_id = '" . tep_db_input($uprid) . "', prid = '" . tep_db_input($prid) . "', products_quantity = '" . $q . "' ");
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
    }
  }
}

function sort_image($sort, $col){
  if (substr($sort, 0, 1) ==  $col){
    if (substr($sort, 1, 1) ==  'a'){
      return ' +';
    } else {
      return ' -';
    }
  }
}

function get_options_selects($uprid){
  global $languages_id;
  $str = '';
  preg_match_all('/\}(\d+)/', $uprid, $arr);
  $oids = $arr[1];
  preg_match_all('/\{(\d+)/', $uprid, $arr);
  for ($i=0;$i<count($arr[1]);$i++){
    $vids[$arr[1][$i]] = $oids[$i];
  }
  $pid = tep_get_prid($uprid);
  $str = '';
  $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pid . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . $languages_id . "' order by popt.products_options_id");
  while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
    $products_options_array = array();
    $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pid . "' and pa.options_id = '" . $products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'");
    while ($products_options = tep_db_fetch_array($products_options_query)) {
      $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
    }
    $str .= tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $vids[$products_options_name['products_options_id']]) . '&nbsp;';
  }
  return $str;
}

function normalize_id($uprid) {
  // sort atrributes by option id
  if (preg_match("/^\d+$/", $uprid)){
    return $uprid;
  } else {
    $product_id = tep_get_prid($uprid);
    preg_match_all('/\{(\d+)/', $uprid, $arr);
    $oids = $arr[1];
    preg_match_all('/\}(\d+)/', $uprid, $arr);
    for ($i=0;$i<count($arr[1]);$i++){
      $vids[$oids[$i]] = $arr[1][$i];
    }
    ksort($vids);
    return tep_get_uprid($product_id, $vids);
  }
}

function get_options($uprid){
  global $languages_id;
  $str = '';
  preg_match_all('/\}(\d+)/', $uprid, $arr);
  $oids = $arr[1];
  $products_options_query = tep_db_query("select pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " asmt where asmt.products_options_values_id=pov.products_options_values_id and pov.products_options_values_id in ('" . implode("', '", $oids) . "')and pov.language_id = '" . $languages_id . "' order by products_options_id ");
  while ($d = tep_db_fetch_array($products_options_query)){
    $str .= $d['products_options_values_name'] . ' ' . $d['products_options_values_id'] . ' ';
  }
  return $str;
}
function make_inventory_name( $uprid ){
  global $languages_id;
  $inv_name = '';
  $arr = split("[{}]", $uprid);
  $data_r = tep_db_query("select products_name from ".TABLE_PRODUCTS_DESCRIPTION." where products_id='".(int)$arr[0]."' and language_id='".(int)$languages_id."'");
  if( $data = tep_db_fetch_array($data_r) ){
    $inv_name = strip_tags($data['products_name']);
  }  
  if ( count($arr)>1 ) {
    for ($j=1,$m=sizeof($arr);$j<$m;$j=$j+2){
      $opt_id = (int)$arr[$j];
      $val_id = (int)$arr[$j+1];
      $options_values_name_data = tep_db_fetch_array(tep_db_query("select products_options_values_name as name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id  = '" . $val_id. "' and language_id  = '" . (int)$languages_id . "'"));
      $inv_name .= ' ' . $options_values_name_data['name'];
    }
  }
  return $inv_name;
}
function make_inventory_model( $uprid ){
  global $languages_id;
  $inv_model = '';
  $arr = split("[{}]", $uprid);
  $data_r = tep_db_query("select products_model from ".TABLE_PRODUCTS." where products_id='".(int)$arr[0]."'");
  if( $data = tep_db_fetch_array($data_r) ){
    $inv_model = strip_tags($data['products_model']);
  }  
  if ( count($arr)>1 ) {
    for ($j=1,$m=sizeof($arr);$j<$m;$j=$j+2){
      $opt_id = (int)$arr[$j];
      $val_id = (int)$arr[$j+1];
      $inv_model.='_'.$val_id;
    }
  }
  return $inv_model;
}


function get_country_info($country_name, $language_id = ''){
  Global $languages_id;
  if ($language_id == ''){
    $language_id = $languages_id;
  }
  $res = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($country_name) . "' and language_id = '" . (int)$language_id . "'");
  $ret = array();

  if ($d = tep_db_fetch_array($res)) {
    $ret = array('id' => $d['countries_id'],
    'title' => $d['countries_name'],
    'iso_code_2' => $d['countries_iso_code_2'],
    'iso_code_3' => $d['countries_iso_code_3']);
  }else{
    $res = tep_db_query("select * from " . TABLE_COUNTRIES . " where soundex(countries_name) = soundex('" . tep_db_input($country_name) . "') or countries_iso_code_2 like '" . preg_replace("/\W/", "", tep_db_input($country_name)) . "' or countries_iso_code_3 like '" . preg_replace("/\W/", "", tep_db_input($country_name)) . "'");
    if ($d = tep_db_fetch_array($res)) {
      $ret = array('id' => $d['countries_id'],
      'title' => $d['countries_name'],
      'iso_code_2' => $d['countries_iso_code_2'],
      'iso_code_3' => $d['countries_iso_code_3']);
    }else {
      $ret = $country_name;
    }
  }


  return $ret;
}

function tep_get_zone_id($country_id, $zone_name) {

  $zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . tep_db_input($zone_name) . "'");

  if (!tep_db_num_rows($zone_id_query)) {
    return 0;
  } else {
    $zone_id_row = tep_db_fetch_array($zone_id_query);
    return $zone_id_row['zone_id'];
  }
}

function get_box_name ($page){
  switch (basename($page)){
    case FILENAME_ADMIN_MEMBERS:
    case FILENAME_ADMIN_GROUPS:
    case FILENAME_ADMIN_FILES:
    case FILENAME_ADMIN_ACCOUNT:
    case FILENAME_FORBIDEN:
      return 'administrator';
      break;

    case FILENAME_CONFIGURATION:
      return 'configuration';
      break;

    case FILENAME_COMPONENTS:
    case FILENAME_CATEGORIES:
    case FILENAME_PRODUCTS_ATTRIBUTES:
    case FILENAME_MANUFACTURERS:
    case FILENAME_DISTRIBUTORS:
    case FILENAME_REVIEWS:
    case FILENAME_SPECIALS:
    case FILENAME_EASYPOPULATE:
    case FILENAME_FEATURED:
    case FILENAME_PRODUCTS_EXPECTED:
    case FILENAME_XSELL_PRODUCTS:
    case FILENAME_MAKE_STOCK:
    case FILENAME_SALEMAKER:
    case FILENAME_SALEMAKER_INFO:
    case FILENAME_LABELS:
    case FILENAME_INGREDIENTS:
    case FILENAME_INGREDIENTS_GROUPS:
    case FILENAME_PRODUCTS_LABEL:
    case FILENAME_INVENTORY:
    case FILENAME_INVENTORY_ORDERS:
    case FILENAME_GIFT:
    case FILENAME_INVENTORY_ORDERS:
    case FILENAME_INVENTORY:
      return 'catalog';
      break;
    case FILENAME_NEWSDESK:
    case FILENAME_NEWSDESK_REVIEWS:
      return 'newsdesk';
      break;

    case FILENAME_FAQDESK:
    case FILENAME_FAQDESK_REVIEWS:
    case FILENAME_FAQDESK_CONFIGURATION:
      return 'faqdesk';
      break;

    case FILENAME_MODULES:
    case FILENAME_SHIP_ZONES:
    case FILENAME_ZONE_TABLE:
      return 'modules';
      break;

    case FILENAME_COUPON_ADMIN:
    case FILENAME_GV_QUEUE:
    case FILENAME_GV_MAIL:
    case FILENAME_GV_SENT:
      return 'gv_admin';
      break;

    case FILENAME_CUSTOMERS:
    case FILENAME_ORDERS:
    case FILENAME_CREATE_ACCOUNT:
    case FILENAME_CREATE_ACCOUNT_PROCESS:
    case FILENAME_CREATE_ACCOUNT_SUCCESS:
    case FILENAME_CREATE_ORDER_PROCESS:
    case FILENAME_CREATE_ORDER:
    case FILENAME_EDIT_ORDERS:
    case FILENAME_ORDERS_INVOICE:
    case FILENAME_ORDERS_PACKINGSLIP:
    case FILENAME_EDIT_ORDERS:
    case FILENAME_UPDATE_ORDER_SHIPPING:
    case FILENAME_UPDATE_SHIPPING_PROCESS:
      return 'customers';
      break;

    case FILENAME_PAYPALIPN_TRANSACTIONS:
    case FILENAME_PAYPALIPN_TESTS:
      return 'paypalipn';
      break;

    case FILENAME_AFFILIATE_SUMMARY:
    case FILENAME_AFFILIATE_AFFILIATES:
    case FILENAME_AFFILIATE_PAYMENT:
    case FILENAME_AFFILIATE_SALES:
    case FILENAME_AFFILIATE_CLICKS:
    case FILENAME_AFFILIATE_BANNERS:
    case FILENAME_AFFILIATE_CONTACT:
      return 'affiliate';
      break;

    case FILENAME_COUNTRIES:
    case FILENAME_ZONES:
    case FILENAME_GEO_ZONES:
    case FILENAME_TAX_CLASSES:
    case FILENAME_TAX_RATES:
      return 'taxes';
      break;

    case FILENAME_CURRENCIES:
    case FILENAME_LANGUAGES:
    case FILENAME_ORDERS_STATUS:
    case FILENAME_CREDIT_TERMS:
      return 'localization';
      break;

    case FILENAME_TEMPLATE_CONFIGURATION:
    case FILENAME_INFOBOX_CONFIGURATION:
      return 'design_controls';
      break;

    case FILENAME_LINKS:
    case FILENAME_LINK_CATEGORIES:
    case FILENAME_LINKS_CONTACT:
      return 'links';
      break;

    case FILENAME_STATS_MONTHLY_SALES:
    case FILENAME_STATS_CUSTOMERS:
    case FILENAME_STATS_PRODUCTS_PURCHASED:
    case FILENAME_STATS_PRODUCTS_VIEWED:
      return 'reports';
      break;

    case FILENAME_INFOBOX_ADMIN:
    case FILENAME_DEFINE_MAINPAGE:
    case FILENAME_BACKUP:
    case FILENAME_BANNER_MANAGER:
    case FILENAME_BANNER_STATISTICS:
    case FILENAME_CACHE:
    case FILENAME_CATALOG_ACCOUNT_HISTORY_INFO:
    case FILENAME_DEFINE_LANGUAGE:
    case FILENAME_FILE_MANAGER:
    case FILENAME_MAIL:
    case FILENAME_SERVER_INFO:
    case FILENAME_NEWSLETTERS:
    case FILENAME_POPUP_IMAGE:
    case FILENAME_SHIPPING_MODULES:
    case FILENAME_WHOS_ONLINE:
    case FILENAME_INFORMATION_MANAGER:
      return 'tools';
      break;

    default:
      return '';
      break;

  }
}
//****************************************//
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
function tep_cfg_get_timezone_name($zone_id)
{
  foreach(tep_get_timezones() as $unused => $timezone)
  {
    if($timezone['id'] === $zone_id)
    {
      return $timezone['text'];
    }
  }
  return "";
}

function tep_cfg_pull_down_timezone_list($zone_id) {
  return tep_draw_pull_down_menu('configuration_value', tep_get_timezones(), $zone_id);
}

function tep_get_timezones()
{
  return array(
  array('id' => '-12', 'text' => '(GMT - 12:00) Eniwetok, Kwajalein'),
  array('id' => '-11', 'text' => '(GMT - 11:00) Midway Island, Samoa'),
  array('id' => '-10', 'text' => '(GMT - 10:00) Hawaii'),
  array('id' => '-09', 'text' => '(GMT - 9:00) Alaska'),
  array('id' => '-08', 'text' => '(GMT - 8:00) Pacific Time, Tijuana'),
  array('id' => '-07', 'text' => '(GMT - 7:00) Mountain Time, Arizona'),
  array('id' => '-06', 'text' => '(GMT - 6:00) Central Time, Mexico City'),
  array('id' => '-05', 'text' => '(GMT - 5:00) Eastern Time, Lima, Indiana'),
  array('id' => '-04', 'text' => '(GMT - 4:00) Atlantic Time, Caracas'),
  array('id' => '-03.5', 'text' => '(GMT - 3:30) Newfoundland'),
  array('id' => '-03', 'text' => '(GMT - 3:00) Greenland, Buenos Aires'),
  array('id' => '-02', 'text' => '(GMT - 2:00) Mid-Atlantic'),
  array('id' => '-01', 'text' => '(GMT - 1:00) Cape Verde Islands, Azores'),
  array('id' => '-00', 'text' => '(GMT + 0:00) Casablanca, London'),
  array('id' => '+01', 'text' => '(GMT + 1:00) Berlin, Rome, Paris '),
  array('id' => '+02', 'text' => '(GMT + 2:00) Cairo, Athens, Instanbul'),
  array('id' => '+03', 'text' => '(GMT + 3:00) Moscow, St. Petersburg'),
  array('id' => '+03.5', 'text' => '(GMT + 3:30) Tehran'),
  array('id' => '+04', 'text' => '(GMT + 4:00) Abu Dhabi, Muscat'),
  array('id' => '+04.5', 'text' => '(GMT + 4:30) Kabul'),
  array('id' => '+05', 'text' => '(GMT + 5:00) Islamabad, Karachi'),
  array('id' => '+05.5', 'text' => '(GMT + 5:30) Calcutta, New Delhi'),
  array('id' => '+05.75', 'text' => '(GMT + 5:45) K�thmand�'),
  array('id' => '+06', 'text' => '(GMT + 6:00) Sri Lanka'),
  array('id' => '+07', 'text' => '(GMT + 7:00) Bangkok, Hanoi, Jakarta'),
  array('id' => '+08', 'text' => '(GMT + 8:00) Beijing, Singapore, Taipei'),
  array('id' => '+09', 'text' => '(GMT + 9:00) Seoul, Osaka, Tokyo'),
  array('id' => '+09.5', 'text' => '(GMT + 9:30) Darwin, Adelaide'),
  array('id' => '+10', 'text' => '(GMT + 10:00) Melbourne, Sydney, Guam'),
  array('id' => '+11', 'text' => '(GMT + 11:00) Magadan, Solomon Islands'),
  array('id' => '+12', 'text' => '(GMT + 12:00) Fiji Islands'),
  array('id' => '+13', 'text' => '(GMT + 13:00) Nuku\'alofa, Tonga'));
}

// [[
function tep_get_products_price($product_id, $currency_id = 0, $group_id = 0, $default = '')
{
  if (USE_MARKET_PRICES != 'True'){
    $currency_id = 0;
  }
  if (CUSTOMERS_GROUPS_ENABLE != 'True'){
    $group_id = 0;
  }
  if ($currency_id == 0 && $group_id == 0){
    $product_query = tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
  }else{
    $product_query = tep_db_query("select products_group_price as products_price from " . TABLE_PRODUCTS_PRICES . " where  products_id = '" . $product_id . "' and  groups_id = '" . $group_id . "' and  currencies_id = '" . $currency_id . "'");
  }
  $product = tep_db_fetch_array($product_query);

  if ($product['products_price'] == '' && $default != ''){
    $product['products_price'] = $default;
  }
  return $product['products_price'];
}

function tep_get_specials_price($specials_id, $currency_id = 0, $group_id = 0, $default = '')
{
  if (USE_MARKET_PRICES != 'True'){
    $currency_id = 0;
  }
  if (CUSTOMERS_GROUPS_ENABLE != 'True'){
    $group_id = 0;
  }
  if ($currency_id == 0 && $group_id == 0){
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where specials_id = '" . $specials_id . "'");
  }else{
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS_PRICES . " where  specials_id = '" . $specials_id . "' and  groups_id = '" . $group_id . "' and  currencies_id = '" . $currency_id . "'");
  }
  $specials_data = tep_db_fetch_array($specials_query);
  if ($specials_data['specials_new_products_price'] == '' && $default != ''){
    $specials_data['specials_new_products_price'] = $default;
  }
  return $specials_data['specials_new_products_price'];
}

function tep_get_options_values_price($products_attributes_id, $currency_id)
{
  if (USE_MARKET_PRICES == 'True'){
    $product_query = tep_db_query("select options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_attributes_id . "' and currencies_id = '" . $currency_id . "'");
    $product = tep_db_fetch_array($product_query);
  }else{
    $product_query = tep_db_fetch_array(tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$products_attributes_id . "'"));
  }
  return $product['options_values_price'];
}
// ]]

function tep_set_categories_status($category_id, $status) {
  $chk_status = tep_db_fetch_array(tep_db_query("select categories_status from ".TABLE_CATEGORIES." where categories_id = '" . (int)$category_id . "'"));
  if ( !isset($chk_status['categories_status']) || (int)$chk_status['categories_status']==$status ) return;

  if ($status == '1') {
    tep_db_query("update " . TABLE_CATEGORIES . " set previous_status = NULL, categories_status = '1', last_modified = now() where categories_id = '" . $category_id . "'");
    $query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = " . $category_id);
    while ($data = tep_db_fetch_array($query)){
      tep_db_query("update " . TABLE_PRODUCTS . " set products_status = IFNULL(previous_status, '1'), previous_status = NULL where products_id = ".  $data['products_id']);
    }
    $tree = tep_get_category_tree($category_id);
    for ($i=1; $i<sizeof($tree); $i++) {
      tep_db_query("update " . TABLE_CATEGORIES . " set  categories_status = IFNULL(previous_status, '1'), previous_status = NULL, last_modified = now() where categories_id = '" . $tree[$i]['id'] . "'");
      $query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = " . $tree[$i]['id']);
      while ($data = tep_db_fetch_array($query)){
        tep_db_query("update " . TABLE_PRODUCTS . " set  products_status = IFNULL(previous_status, '1'), previous_status = NULL where products_id = ".  $data['products_id']);
      }
    }
  } elseif ($status == '0') {
    tep_db_query("update " . TABLE_CATEGORIES . " set previous_status = NULL, categories_status = '0', last_modified = now() where categories_id = '" . $category_id . "'");
    $query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = " . $category_id);
    while ($data = tep_db_fetch_array($query)){
      tep_db_query("update " . TABLE_PRODUCTS . " set previous_status = products_status, products_status = '0' where products_id = ".  $data['products_id']);
    }
    $tree = tep_get_category_tree($category_id);
    for ($i=1; $i<sizeof($tree); $i++) {
      tep_db_query("update " . TABLE_CATEGORIES . " set previous_status = categories_status, categories_status = '0', last_modified = now() where categories_id = '" . $tree[$i]['id'] . "'");
      $query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = " . $tree[$i]['id']);
      while ($data = tep_db_fetch_array($query)){
        tep_db_query("update " . TABLE_PRODUCTS . " set previous_status = products_status, products_status = '0' where products_id = ".  $data['products_id']);
      }
    }
  }
}

function tep_get_categories_head_title_tag($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_head_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);

  return $category['categories_head_title_tag'];
}

function tep_get_categories_head_desc_tag($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_head_desc_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);

  return $category['categories_head_desc_tag'];
}

function tep_get_categories_head_keywords_tag($category_id, $language_id, $affiliate_id = 0) {
  $category_query = tep_db_query("select categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "' and affiliate_id = '" . $affiliate_id . "'");
  $category = tep_db_fetch_array($category_query);

  return $category['categories_head_keywords_tag'];
}

function tep_get_status($default = '') {
  global $languages_id;

  $status_array = array();
  if ($default) {
    $status_array[] = array('id' => '',
    'text' => $default);
  }
  $status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "' order by orders_status_name");
  while ($status = tep_db_fetch_array($status_query)) {
    $status_array[] = array('id' => $status['orders_status_id'],
    'text' => $status['orders_status_name']);
  }
  return $status_array;
}

function tep_cfg_select_download_status($key_value) {
  $select_array = tep_get_status();
  $key_value_array = explode(',', $key_value);
  for ($i=0; $i<sizeof($select_array); $i++) {
    $string .= '<br><input type="checkbox" name="' . $select_array[$i]['text'] . '" value="' . $select_array[$i]['id'] . '"';
    for ($j=0;$j<sizeof($key_value_array);$j++) {
      if ($key_value_array[$j] == $select_array[$i][id]) $string .= ' CHECKED';
    }
    $string .= '> ' . $select_array[$i]['text'];
  }
  $string .= '<br><input type="hidden" name="flag" value="exist"';
  return $string;
}

function tep_cfg_select_admin_group($key_value){
  $status_array = array();
  $status_array[] = array('id' => '', 'text' => TEXT_NONE);
  $status_query = tep_db_query("select * from " . TABLE_ADMIN_GROUPS);
  while ($status = tep_db_fetch_array($status_query)){
    $status_array[] = array('id' => $status['admin_groups_id'], 'text' => $status['admin_groups_name']);
  }
  return tep_draw_pull_down_menu('configuration_value', $status_array, $key_value);
}

function tep_cfg_select_user_group($key_value){
  $status_array = array();
  $status_array[] = array('id' => '0', 'text' => TEXT_NONE);
  $status_query = tep_db_query("select * from " . TABLE_GROUPS);
  while ($status = tep_db_fetch_array($status_query)){
    $status_array[] = array('id' => $status['groups_id'], 'text' => $status['groups_name']);
  }
  return tep_draw_pull_down_menu('configuration_value', $status_array, $key_value);
}

function tep_cfg_select_user_edit_group($key_value){
  $status_array = array();
  $status_array[] = array('id' => '0', 'text' => TEXT_NONE);
  $status_query = tep_db_query("select * from " . TABLE_GROUPS);
  while ($status = tep_db_fetch_array($status_query)){
    $status_array[] = array('id' => $status['groups_id'], 'text' => $status['groups_name']);
  }
  return tep_draw_pull_down_menu('groups_id', $status_array, $key_value);
}

function tep_get_group_name($Value){
  $status = tep_db_fetch_array(tep_db_query("select * from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . $Value . "'"));
  return $status['admin_groups_name'];
}

function tep_get_user_group_name($Value){
  if ($Value == 0){
    return TEXT_NONE;
  }else{
    $status = tep_db_fetch_array(tep_db_query("select * from " . TABLE_GROUPS . " where groups_id = '" . $Value . "'"));
    return $status['groups_name'];
  }
}
function tep_get_status_name($id_status) {
  global $languages_id;
  if(strlen(trim($id_status)) == 0)
  {
    return   TEXT_NO_STATUS;
  }else
  {
    $status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "' and orders_status_id IN (" . $id_status . ") order by orders_status_name");
    while ($status = tep_db_fetch_array($status_query)) {
      $status_name[] = $status['orders_status_name'];
    }
    return implode(', ', $status_name);
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

function tep_get_module_status($affiliate_id, $module){
  $data_query = tep_db_query("select * from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_INSTALLED_" . $affiliate_id. "'");
  $data = tep_db_fetch_array($data_query);
  if (tep_db_num_rows($data_query)){
    if (in_array($module, explode(';', $data['configuration_value']))){
      return true;
    }else{
      return false;
    }
  }else{
    return true;
  }
}
function tep_affiliate_module_action($action, $module){
  global $PHP_SELF;
  $data_query = tep_db_query("select * from " . TABLE_AFFILIATE);
  while ($data = tep_db_fetch_array($data_query))
  {
    $module_key = 'MODULE_PAYMENT_INSTALLED_' . $data['affiliate_id'];
    $query = tep_db_query("select * from " . TABLE_CONFIGURATION . " where configuration_key = '" .$module_key. "'");
    if (tep_db_num_rows($query)){
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $class = basename($module);
      $file = $class . $file_extension;
      $data1 = tep_db_fetch_array($query);
      if ($action == 'install'){
        if (strpos($data1['configuration_value'], $file) === false){
          $str = $data1['configuration_value'] . ';' . $file;
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($str) . "' where configuration_key = '" . $module_key. "'");
        }
      }elseif ($action == 'remove'){
        $str = $data1['configuration_value'];
        $str = str_replace(";" . $file . ";", ";", $str);
        $str = str_replace(";" . $file , "", $str);
        $str = str_replace($file . ";", "", $str);
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($str) . "' where configuration_key = '" . $module_key. "'");
      }
    }
  }
}

function tep_get_affiliate_name($affiliate){
  if ($affiliate == 0){
    return TEXT_ROOT;
  }else{
    $data_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate. "'");
    while ($data = tep_db_fetch_array($data_query))
    {
      return $data['affiliate_firstname'] . " " . $data['affiliate_lastname'];
    }
  }
}

function tep_get_products_discount_price($product_id, $currency_id = 0, $group_id = 0, $default = ''){
  if (USE_MARKET_PRICES != 'True'){
    $currency_id = 0;
  }
  if (CUSTOMERS_GROUPS_ENABLE != 'True'){
    $group_id = 0;
  }
  if ($currency_id == 0 && $group_id == 0){
    $product_query = tep_db_query("select products_price_discount from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
  }else{
    $product_query = tep_db_query("select products_group_discount_price as products_price_discount from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . $product_id . "' and  groups_id = '" . $group_id . "' and  currencies_id = '" . $currency_id . "'");
  }
  $product = tep_db_fetch_array($product_query);
  if ($product['products_price_discount'] == '' && $default != ''){
    $product['products_price_discount'] = $default;
  }
  return $product['products_price_discount'];
}

function tep_get_products_price_edit_order($products_id, $currency_id = 0, $group_id = 0, $qty = 1, $recalculate_value = false){
  $price = tep_get_products_price($products_id, $currency_id, $group_id, false);
  if (CUSTOMERS_GROUPS_ENABLE == 'True' && $group_id != 0 && ($price === false || $price == -2) && $recalculate_value){
    $discount = tep_db_fetch_array(tep_db_query('select groups_discount from ' . TABLE_GROUPS . " where groups_id = '" . $group_id . "'"));
    $price = tep_get_products_price($products_id, $currency_id, 0);
    $price = $price * (100 - $discount['groups_discount']) / 100;
  }
  if ($qty > 1 && DISCOUNT_TABLE_ENABLE == 'True'){
    $discount_price = tep_get_products_discount_price($products_id, $currency_id, $group_id, false);
    if (CUSTOMERS_GROUPS_ENABLE == 'True' && $group_id != 0 && $discount_price === false && $recalculate_value) {
      $discount_price = tep_get_products_discount_price($products_id, $currency_id, 0, false);
      $apply_discount = true;
    }
    if ($discount_price !== false && $discount_price != -1){
      $ar = split("[:;]", $discount_price);
      for ($i=0,$n=sizeof($ar);$i<$n;$i=$i+2){
        if ($qty < $ar[$i]){
          if ($i > 0){
            $price = $ar[$i-1];
          }
          break;
        }
      }
      if (sizeof($ar) > 2 && $qty >= $ar[sizeof($ar)-2]){
        $discount_price = $ar[sizeof($ar)-1];
      }
      if ($apply_discount){
        $discount = tep_db_fetch_array(tep_db_query('select groups_discount from ' . TABLE_GROUPS . " where groups_id = '" . $group_id . "'"));
        $discount_price = $discount_price * (100 - $discount['groups_discount']) / 100;
      }
    }
    return $discount_price;
  }else{
    return $price;
  }
}

function tep_get_attributes_price_edit_order($attributes_id, $currency_id = 0, $group_id = 0, $qty = 1, $recalculate_value = false){
  $price = tep_get_attributes_price($attributes_id, $currency_id, $group_id, false);
  if (CUSTOMERS_GROUPS_ENABLE == 'True' && $group_id != 0 && ($price === false || $price == -2) && $recalculate_value){
    $discount = tep_db_fetch_array(tep_db_query('select groups_discount from ' . TABLE_GROUPS . " where groups_id = '" . $group_id . "'"));
    $price = tep_get_attributes_price($attributes_id, $currency_id, 0);
    $price = $price * (100 - $discount['groups_discount']) / 100;
  }
  if ($qty > 1 && DISCOUNT_TABLE_ENABLE == 'True'){
    $discount_price = tep_get_attributes_discount_price($attributes_id, $currency_id, $group_id, false);
    if (CUSTOMERS_GROUPS_ENABLE == 'True' && $group_id != 0 && $discount_price === false && $recalculate_value) {
      $discount_price = tep_get_attributes_discount_price($attributes_id, $currency_id, 0, false);
      $apply_discount = true;
    }
    if ($discount_price !== false && $discount_price != -1){
      $ar = split("[:;]", $discount_price);
      for ($i=0,$n=sizeof($ar);$i<$n;$i=$i+2){
        if ($qty >= $ar[$i]){
          //if ($i > 0){
            $price = $ar[$i+1];
          //}
          //break;
        }
      }
      if (sizeof($ar) > 2 && $qty >= $ar[sizeof($ar)-2]){
        $price = $ar[sizeof($ar)-1];
      }
      if ($apply_discount){
        $discount = tep_db_fetch_array(tep_db_query('select groups_discount from ' . TABLE_GROUPS . " where groups_id = '" . $group_id . "'"));
        $price = $price * (100 - $discount['groups_discount']) / 100;
      }
    }
    return $price;
  }else{
    if ($price == -2){
      return 0;
    }else{
      return $price;
    }
  }

}

function tep_get_attributes_price($attributes_id, $currency_id = 0, $group_id = 0, $default = ''){
  if (USE_MARKET_PRICES != 'True'){
    $currency_id = 0;
  }
  if (CUSTOMERS_GROUPS_ENABLE != 'True'){
    $group_id = 0;
  }
  if ($currency_id == 0 && $group_id == 0){
    $data_query = tep_db_query("select options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where  products_attributes_id  = '" . $attributes_id . "'");
  }else{
    $data_query = tep_db_query("select attributes_group_price as options_values_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $attributes_id  . "' and currencies_id = '" . $currency_id . "' and groups_id = '" . $group_id . "'");
  }
  $data = tep_db_fetch_array($data_query);
  if ($data['options_values_price'] == '' && $default != ''){
    $data['options_values_price'] = $default;
  }
  return $data['options_values_price'];
}

function tep_get_attributes_discount_price($attributes_id, $currency_id = 0, $group_id = 0, $default = ''){
  if (USE_MARKET_PRICES != 'True'){
    $currency_id = 0;
  }
  if (CUSTOMERS_GROUPS_ENABLE != 'True'){
    $group_id = 0;
  }
  if ($currency_id == 0 && $group_id == 0){
    $data_query = tep_db_query("select products_attributes_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES . " where  products_attributes_id  = '" . $attributes_id . "'");
  }else{
    $data_query = tep_db_query("select attributes_group_discount_price as products_attributes_discount_price from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $attributes_id  . "' and currencies_id = '" . $currency_id . "' and groups_id = '" . $group_id . "'");
  }
  $data = tep_db_fetch_array($data_query);
  if ($data['products_attributes_discount_price'] == '' && $default != ''){
    $data['products_attributes_discount_price'] = $default;
  }
  return $data['products_attributes_discount_price'];
}

function tep_get_specials_groups_price($specials_id, $group_id){
  $query = tep_db_query("select specials_groups_prices from " . TABLE_SPECIALS_GROUPS_PRICES . " where specials_id = '" . $specials_id . "' and groups_id = '" . $group_id . "'");
  if (tep_db_num_rows($query)){
    $data = tep_db_fetch_array($query);
    return $data['specials_groups_prices'];
  }else{
    return -2;
  }
}

function tep_get_properties_possible_values($property_id, $language_id){
  $data = tep_db_fetch_array(tep_db_query("select possible_values from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id = '" . $property_id . "' and language_id = '" . $language_id . "'"));
  return $data['possible_values'];
}

function check_customer_groups($groups_id, $field){
  $query = tep_db_query("select * from " . TABLE_GROUPS . " where groups_id = '" . $groups_id ."'");
  $data = tep_db_fetch_array($query);
  return $data[$field];
}

function tep_get_products_special_price_edit_order($product_id, $currency_id = 0, $customer_groups_id = 0) {

  $product_price = tep_get_products_price($product_id, $currency_id, $customer_groups_id);

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    if (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0){
      $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
    }else{
      $specials_query = tep_db_query("select s.specials_id, if(sp.specials_new_products_price is NULL, -2, sp.specials_new_products_price) as specials_new_products_price from " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . $customer_groups_id . "'  and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where s.products_id = '" . $product_id . "'  and if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ) and s.status ");
    }
  }else{
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
  }

  if (tep_db_num_rows($specials_query)) {
    $special = tep_db_fetch_array($specials_query);
    $special_price = $special['specials_new_products_price'];
    if ($special_price == -2 && $customer_groups_id != 0){
      if (USE_MARKET_PRICES == 'True'){
        $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . $special['specials_id'] . "' and currencies_id = '" . $currency_id . "' and groups_id = 0");
      }else{
        $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
      }
      if (tep_db_num_rows($specials_query)){
        $special = tep_db_fetch_array($specials_query);
        $discount = check_customer_groups($customer_groups_id, 'groups_discount');
        $special_price = $special['specials_new_products_price']* (1 - ($discount/100));
      }else{
        $special_price = false;
      }
    }
  } else {
    $special_price = false;
  }

  if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
    return $special_price;
  }

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
}

function tep_get_products_special_price($product_id) {
  Global $currency_id, $customer_groups_id;

  if ($customer_groups_id != 0 && !check_customer_groups($customer_groups_id, 'groups_is_show_price')){
    return false;
  }

  //if (tep_check_product($product_id)) {
    $product_price = tep_get_products_price($product_id, $currency_id, $customer_groups_id);
  //} else {
  //  return false;
  //}
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    if (USE_MARKET_PRICES != 'True' && $customer_groups_id == 0){
      $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
    }else{
      $specials_query = tep_db_query("select s.specials_id, if(sp.specials_new_products_price is NULL, -2, sp.specials_new_products_price) as specials_new_products_price from " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . $customer_groups_id . "'  and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where s.products_id = '" . $product_id . "'  and if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ) and s.status ");
    }
  }else{
    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
  }

  if (tep_db_num_rows($specials_query)) {
    $special = tep_db_fetch_array($specials_query);
    $special_price = $special['specials_new_products_price'];
    if ($special_price == -2 && $customer_groups_id != 0){
      if (USE_MARKET_PRICES == 'True'){
        $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . $special['specials_id'] . "' and currencies_id = '" . $currency_id . "' and groups_id = 0");
      }else{
        $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
      }
      if (tep_db_num_rows($specials_query)){
        $special = tep_db_fetch_array($specials_query);
        $discount = check_customer_groups($customer_groups_id, 'groups_discount');
        $special_price = $special['specials_new_products_price']* (1 - ($discount/100));
      }else{
        $special_price = false;
      }
    }
  } else {
    $special_price = false;
  }

  if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
    return $special_price;
  }

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
}


/*
function tep_get_products_special_price($product_id) {
Global $currency_id, $customer_groups_id;

$product_price = tep_get_products_price($product_id, $currency_id);

if (USE_MARKET_PRICES == 'True'){
$specials_query = tep_db_query("select sp.specials_new_products_price from " . TABLE_SPECIALS . " s, " . TABLE_SPECIALS_PRICES ." sp where s.specials_id =sp.specials_id and sp.currencies_id = ".(int)$currency_id . " and s.products_id = '" . $product_id . "' and status");
}else{
if ($customer_groups_id == 0){
$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
}else{
$specials_query = tep_db_query("select specials_groups_prices as specials_new_products_price from " . TABLE_SPECIALS . " s left join " . TABLE_SPECIALS_GROUPS_PRICES . " sgp on s.specials_id = sgp.specials_id and sgp.groups_id = '" . $customer_groups_id . "' where s.products_id = '" . $product_id . "' and if(sgp.specials_groups_prices is NULL, 1, sgp.specials_groups_prices != -1 ) and sgp.specials_groups_prices is not NULL and s.status ");
}
}
if (tep_db_num_rows($specials_query)) {
$special = tep_db_fetch_array($specials_query);
$special_price = $special['specials_new_products_price'];
} else {
if ($customer_groups_id != 0){
$specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status");
if (tep_db_num_rows($specials_query)){
$special = tep_db_fetch_array($specials_query);
$discount = check_customer_groups($customer_groups_id, 'groups_discount');
$special_price = $special['specials_new_products_price']* (1 - ($discount/100));
}else{
return false;
}
}else{
$special_price = false;
}
}

if(substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
return $special_price;
}

$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_id . "'");
$product_to_categories = tep_db_fetch_array($product_to_categories_query);
$category = $product_to_categories['categories_id'];

$sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where sale_categories_all like '%," . $category . ",%' and sale_status = '1' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0') and groups_id = '" . $customer_groups_id . "'");
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
}
*/


function tep_get_full_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
  global $languages_id;

  if (!is_array($category_tree_array)) $category_tree_array = array();
  //    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

  if ($include_itself && $parent_id != 0) {
    $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.affiliate_id = 0 and cd.categories_id = '" . (int)$parent_id . "'");
    $category = tep_db_fetch_array($category_query);
    $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name'], 'category' => '1');
  }

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.affiliate_id = 0 and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
    if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name'], 'category' => '1');
    $category_tree_array = tep_get_full_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);

    $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" .(int)$categories['categories_id'] . "' and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by p.sort_order, pd.products_name");
    while ($products = tep_db_fetch_array($products_query)){
      $category_tree_array[] = array('id' => $products['products_id'], 'text' => $spacing . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $products['products_name'], 'category' => '0');
    }

  }

  if ($parent_id == 0){
    $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" .(int)$parent_id . "' and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' order by p.sort_order, pd.products_name");
    while ($products = tep_db_fetch_array($products_query)){
      $category_tree_array[] = array('id' => $products['products_id'], 'text' => $spacing . '&nbsp;&nbsp;&nbsp;' . $products['products_name'], 'parent_id' => $parent_id, 'category' => '0');
    }
  }

  return $category_tree_array;
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

function tep_get_customers_group($customer_id){
  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $check = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($customer_id) . "'");
    $checkData = tep_db_fetch_array($check);
    return $checkData['groups_id'];
  }else{
    return 0;
  }
}
function get_inventory_uprid($ar, $idx){
  reset($ar);
  $next = false;
  foreach ($ar as $key => $value){
    if ($next){
      $next = $key;
      break;
    }
    if ($key == $idx){
      $next = true;
    }
  }
  if ($next !== false && $next !== true){
    $sub = get_inventory_uprid($ar, $next);
  }
  //}
  $ret = array();
  for ($i=0,$n=sizeof($ar[$idx]);$i<$n;$i++){
    if (is_array($sub)){
      for ($j=0,$m=sizeof($sub);$j<$m;$j++){
        $ret[] = '{' . $idx . '}' . $ar[$idx][$i] . $sub[$j];
      }
    }else{
      $ret[] = '{' . $idx . '}' . $ar[$idx][$i];
    }
  }
  return $ret;
}

function tep_db_insert_field($string){
  if (is_string($string)){
    if (get_magic_quotes_runtime()){
      if (PHP_VERSION > '4.3.0'){
        return mysql_real_escape_string(stripslashes($string));
      }else{
        return $string;
      }
    }else{
      if (PHP_VERSION > '4.3.0'){
        return mysql_real_escape_string(stripslashes($string));
      }else{
        return $string;
      }
    }
  }elseif (is_array($string)){
    reset($string);
    while (list($key, $value) = each($string)) {
      $string[$key] = tep_db_insert_field($value);
    }
    return $string;
  }else{
    return $string;
  }
}

function tep_get_affiliates($filter = 1){
  if ($filter){
    $query = tep_db_query("select * from " . TABLE_AFFILIATE . " where own_descriptions = 1 order by affiliate_id");
  }else{
    $query = tep_db_query("select * from " . TABLE_AFFILIATE . " order by affiliate_id");
  }
  $affiliates = array();
  while ($data = tep_db_fetch_array($query)){
    $affiliates[] = array('id' => $data['affiliate_id'],
                          'name' => $data['affiliate_firstname'] . ' ' . $data['affiliate_lastname']);
  }
  return $affiliates;
}

  function tep_categories_tree($parent_id = 0){
    global $counter, $level;
    $categories_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id='" . $parent_id . "' and c.categories_id=cd.categories_id and cd.language_id=1 order by sort_order, categories_name");
    while($categories = tep_db_fetch_array($categories_query)){
      $counter++;
      // update level and left part for node
      tep_db_query("update " . TABLE_CATEGORIES . " set categories_level='" . $level . "', categories_left='" . $counter . "' where categories_id='" . $categories['categories_id'] . "'");
      // check for siblings
      $sibling_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id='" . $categories['categories_id'] . "' and c.categories_id=cd.categories_id and cd.language_id=1 order by sort_order, categories_name");
      if(tep_db_num_rows($sibling_query) > 0){ // has siblings
        $level++;
        tep_categories_tree($categories['categories_id']);
        $level--;
      }
      $counter++;
      // update right part of node
      tep_db_query("update " . TABLE_CATEGORIES . " set categories_right='" . $counter . "' where categories_id='" . $categories['categories_id'] . "'");
    }
  }

  function tep_update_categories(){
    global $counter, $level;
    $counter = 1;
    $level = 1;
    tep_categories_tree();
  }

  //// moved from edit order ////
  function tep_get_tax_rate_value_edit_order($class_id, $tax_zone_id) {
    $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " where tax_class_id = '" . (int)$class_id . "' and tax_zone_id = '" . $tax_zone_id . "' group by tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier += $tax['tax_rate'];
      }
      return $tax_multiplier;
    } else {
      return 0;
    }
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

function tep_get_manufacturers_name($manufacturers_id) {
  $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id."'");
  $manufacturers = tep_db_fetch_array($manufacturers_query);
  return $manufacturers['manufacturers_name'];
}

function tep_get_uprid_price_info($uprid){
  $data_r = tep_db_query("select p.products_tax_class_id, p.products_price as p_price, s.specials_new_products_price as s_price from ".TABLE_PRODUCTS." p left join " . TABLE_SPECIALS . " s on (s.products_id = p.products_id and s.status=1) where p.products_id='".(int)$uprid."'");
  if( $d = tep_db_fetch_array($data_r) ){
    $ret = array();
    $ret['tax_class'] = $d['products_tax_class_id'];
    $ret['tax_rate'] = tep_get_tax_rate($d['products_tax_class_id']);
    $ret['attributes_cost'] = 0; 
    preg_match_all('/\{(\d+)\}(\d+)/', $uprid, $arr, PREG_SET_ORDER);
    if (is_array($arr) && sizeof($arr)>0) {
      foreach($arr as $attr_data) {
        if (isset($attr_data[1]) && isset($attr_data[2])) {
          $get_attr_price_q = tep_db_query("select price_prefix, options_values_price from ".TABLE_PRODUCTS_ATTRIBUTES." where products_id='".intval($uprid)."' and options_id='".intval($attr_data[1])."' and options_values_id='".intval($attr_data[2])."'");
          if (tep_db_num_rows($get_attr_price_q)>0) {
            $get_attr_price = tep_db_fetch_array($get_attr_price_q);
            if ($get_attr_price['options_values_price']>0) $ret['attributes_cost'] += ($get_attr_price['price_prefix']=='-'?-1:1)*$get_attr_price['options_values_price'];
          }
        }
      }
    }
    $ret['price'] = $d['p_price'];
    $ret['sale'] = $d['s_price'];

    if ( (float)$d['s_price']>0 && $d['s_price']<$d['p_price'] ) {
      $ret['final_price'] = $ret['sale']+$ret['attributes_cost'];
    }else{
      $ret['final_price'] = $ret['price']+$ret['attributes_cost'];
    }
    $ret['final_gross'] = tep_round( tep_add_tax( $ret['final_price'], $ret['tax_rate'] ), 2 );
    return $ret;
  }else{
    return false;
  }
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
    return '<a href="' . $tep_href_link . '">' . $tep_href_link . '</a>';
  }
  return $tep_href_link;
}

// ** GOOGLE CHECKOUT** 
// Function to store configuration values(shipping options) using 
// checkboxes in the Administration Tool 

//  carrier calculation
  // perhaps this function must be moved to googlecheckout class, is not too general
  function gc_cfg_select_CCshipping($key_value, $key = '') {
    //add ropu
    // i get all the shipping methods available!
    global $PHP_SELF,$language,$module_type;

    require_once (DIR_FS_CATALOG . 'includes/modules/payment/googlecheckout.php');
    $googlepayment = new googlecheckout();

    $javascript = "<script language='javascript'>
            
          function CCS_blur(valor, code, hid_id, pos){
            var hid = document.getElementById(hid_id);
            var temp = hid.value.substring((code  + '_CCS:').length).split('|');
            valor.value = isNaN(parseFloat(valor.value))?'':parseFloat(valor.value);
            if(valor.value != ''){ 
              temp[pos] = valor.value;
            }else {
              temp[pos] = 0;
              valor.value = '0';      
            }
            hid.value = code + '_CCS:' + temp[0] + '|'+ temp[1] + '|'+ temp[2];
          }

          function CCS_focus(valor, code, hid_id, pos){
            var hid = document.getElementById(hid_id);
            var temp = hid.value.substring((code  + '_CCS:').length).split('|');
          //  valor.value = valor.value.substr((code  + '_CCS:').length, hid.value.length);
            temp[pos] = valor.value;        
            hid.value = code + '_CCS:' + temp[0] + '|'+ temp[1] + '|'+ temp[2];        

          }
          </script>";

    $string .= $javascript;

    $key_values = explode( ", ", $key_value);

    foreach($googlepayment->cc_shipping_methods_names as $CCSCode => $CCSName){
      
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= "<br><b>" . $CCSName . "</b>"."\n";
      foreach($googlepayment->cc_shipping_methods[$CCSCode] as $type => $methods) {
        if (is_array($methods) && !empty($methods)) {
          $string .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'. $type .'</b><br />';            
            $string .= 'Def. Value | Fix Charge | Variable | Method Name';
          foreach($methods as $method => $method_name) {
            $string .= '<br>';
            
            // default value 
            $value = gc_compare($CCSCode . $method. $type , $key_values, "_CCS:",  '1.00|0|0');
            $values = explode('|',$value);
            $string .= DEFAULT_CURRENCY . ':<input size="3"  onBlur="CCS_blur(this, \'' .  $CCSCode. $method . $type . '\', \'hid_' .
                        $CCSCode . $method . $type . '\', 0);" onFocus="CCS_focus(this, \'' .  $CCSCode . $method .
                        $type . '\' , \'hid_' . $CCSCode . $method . $type .'\', 0);"  type="text" name="no_use' . $method . 
                        '" value="' . $values[0] . '"> ';

            $string .= DEFAULT_CURRENCY . ':<input size="3"  onBlur="CCS_blur(this, \'' .  $CCSCode. $method . $type . '\', \'hid_' .
                        $CCSCode . $method . $type . '\', 1 );" onFocus="CCS_focus(this, \''  . $CCSCode . $method .
                        $type . '\' , \'hid_' . $CCSCode . $method . $type .'\', 1);"  type="text" name="no_use' . $method . 
                        '" value="' . $values[1] . '"> ';

            $string .= '<input size="3"  onBlur="CCS_blur(this, \'' . $CCSCode. $method .  $type . '\', \'hid_' .
                        $CCSCode . $method . $type . '\', 2 );" onFocus="CCS_focus(this, \''  . $CCSCode . $method .
                        $type . '\' , \'hid_' . $CCSCode . $method . $type .'\', 2);"  type="text" name="no_use' . $method . 
                        '" value="' . $values[2] . '">% ';

            $string .= '<input size="10" id="hid_' . $CCSCode . $method . $type . '"  type="hidden" name="' . $name . 
                        '" value="' . $CCSCode . $method . $type . '_CCS:' . $value .  '">'."\n";      

            $string .= $method_name;
          }
        }
      }
    }
    return $string;
  }


  function gc_cfg_select_multioption($select_array, $key_value, $key = '') {

    for ($i=0; $i<sizeof($select_array); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' .  $select_array[$i] . '"';
      $key_values = explode( ", ", $key_value);
      if ( in_array($select_array[$i], $key_values) ) $string .= ' CHECKED';
      $string .= '>' . $select_array[$i];
    }
    $string .= '<input type="hidden" name="' . $name . '" value="--none--">';
    return $string;
  }

// Custom Function to store configuration values (shipping default values)  
  function gc_compare($key, $data, $sep="_VD:", $def_ret='1')
  {
    foreach($data as $value) {
      list($key2, $valor) = explode($sep, $value);
      if($key == $key2)   
        return $valor;
    }
    return $def_ret;
  }
  // perhaps this function must be moved to googlecheckout class, is not too general
  function gc_cfg_select_shipping($select_array, $key_value, $key = '') {

  //add ropu
  // i get all the shipping methods available!
  global $PHP_SELF,$language,$module_type;

  $module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

    $installed_modules = array();
    $select_array = array();
    for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
      $file = $directory_array[$i];

      include_once(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' .  $file);
      include_once($module_directory . $file);

      $class = substr($file, 0, strrpos($file, '.'));
      if (tep_class_exists($class)) {
        $module = new $class;
        //echo $class;
        if ($module->check() > 0) {

          $select_array[$module->code] = array('code' => $module->code,
                               'title' => $module->title,
                               'description' => $module->description,
                               'status' => $module->check());
        }
      }
    }
  require_once (DIR_FS_CATALOG . 'includes/modules/payment/googlecheckout.php');
  $googlepayment = new googlecheckout();

  $ship_calcualtion_mode = (count(array_keys($select_array)) >  count(array_intersect($googlepayment->shipping_support, array_keys($select_array)))) ? true :  false;
  if(!$ship_calcualtion_mode) {
    return '<br/><i>'. GOOGLECHECKOUT_TABLE_NO_MERCHANT_CALCULATION . '</i>';
  }

    $javascript = "<script language='javascript'>

            function VD_blur(valor, code, hid_id){
              var hid = document.getElementById(hid_id);
              valor.value = isNaN(parseFloat(valor.value))?'':parseFloat(valor.value);
              if(valor.value != ''){ 
                hid.value = code + '_VD:' + valor.value;
            //    valor.value = valor.value;  
            //    hid.disabled = false;
              }else {   
                hid.value = code + '_VD:0';
                valor.value = '0';      
              }

            }

            function VD_focus(valor, code, hid_id){
              var hid = document.getElementById(hid_id);    
//              valor.value = valor.value.substr((code  + '_VD:').length,  valor.value.length);
              hid.value = valor.value.substr((code  + '_VD:').length, valor.value.length);         
            }

            </script>";

    $string .= $javascript;

    $key_values = explode( ", ", $key_value);

    foreach($select_array as $i => $value){
      if ( $select_array[$i]['status'] && !in_array($select_array[$i]['code'],  $googlepayment->shipping_support) ) {
        $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
        $string .= "<br><b>" . $select_array[$i]['title'] . "</b>"."\n";
        if (is_array($googlepayment->mc_shipping_methods[$select_array[$i]['code']])) {
          foreach($googlepayment->mc_shipping_methods[$select_array[$i]['code']] as $type =>  $methods) {
            if (is_array($methods) && !empty($methods)) {
              $string .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'. $type .'</b>';            
              foreach($methods as $method => $method_name) {
                $string .= '<br>';
                // default value 
                $value = gc_compare($select_array[$i]['code'] . $method. $type  , $key_values, 1);
              $string .= '<input size="5"  onBlur="VD_blur(this, \'' .  $select_array[$i]['code']. $method . $type . '\', \'hid_' . $select_array[$i]['code'] .  $method . $type . '\' );" onFocus="VD_focus(this, \'' . $select_array[$i]['code'] . $method .  $type . '\' , \'hid_' . $select_array[$i]['code'] . $method . $type .'\');" type="text"  name="no_use' . $method . '" value="' . $value . '"';
                $string .= '>';
              $string .= '<input size="10" id="hid_' .  $select_array[$i]['code'] . $method . $type . '" type="hidden" name="' . $name . '" value="'  . $select_array[$i]['code'] . $method . $type . '_VD:' . $value . '"';      
                  $string .= '>'."\n";
                  $string .= $method_name;
              }
            }
          }
        }
        else {
          $string .= $select_array[$i]['code']  .GOOGLECHECKOUT_MERCHANT_CALCULATION_NOT_CONFIGURED;
        }
      }
    }
    return $string;
  }

// ** END GOOGLE CHECKOUT **


// SEO Manufacturers
  function tep_get_manufacturer_meta_descr($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_meta_description from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_meta_description'];
  }
  
  function tep_get_manufacturer_meta_key($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_meta_key from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_meta_key'];
  }
  
  function tep_get_manufacturer_meta_title($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_meta_title from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_meta_title'];
  }
	
  function tep_get_manufacturer_seo_name($manufacturer_id, $language_id) {
    $manufacturer_query = tep_db_query("select manufacturers_seo_name from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturer_id . "' and languages_id = '" . (int)$language_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_seo_name'];
  }  
// eof SEO Manufacturers
?>
