<?php
/*
  $Id: advanced_search_result.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require("includes/application_top.php");
include_once('controllers/front/FrontController.php');
$controller = new FrontController();
$canonical_tag = $controller->get_canonical_tag();
  include_once("classes/ProductViews.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);

  $error = false;

  if ( (isset($HTTP_GET_VARS['keywords']) && empty($HTTP_GET_VARS['keywords'])) &&
       (isset($HTTP_GET_VARS['dfrom']) && (empty($HTTP_GET_VARS['dfrom']) || ($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING))) &&
       (isset($HTTP_GET_VARS['dto']) && (empty($HTTP_GET_VARS['dto']) || ($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING))) &&
       (isset($HTTP_GET_VARS['pfrom']) && !is_numeric($HTTP_GET_VARS['pfrom'])) &&
       (isset($HTTP_GET_VARS['pto']) && !is_numeric($HTTP_GET_VARS['pto'])) && (PRODUCTS_PROPERTIES != 'True')) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $dfrom = '';
    $dto = '';
    $pfrom = '';
    $pto = '';
    $keywords = '';

    if (isset($HTTP_GET_VARS['dfrom'])) {
      $dfrom = (($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dfrom']);
    }

    if (isset($HTTP_GET_VARS['dto'])) {
      $dto = (($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dto']);
    }

    if (isset($HTTP_GET_VARS['pfrom'])) {
      $pfrom = $HTTP_GET_VARS['pfrom'];
    }

    if (isset($HTTP_GET_VARS['pto'])) {
      $pto = $HTTP_GET_VARS['pto'];
    }

    if (isset($HTTP_GET_VARS['keywords'])) {
      $keywords = $HTTP_GET_VARS['keywords'];
    }

    $date_check_error = false;
    if (tep_not_null($dfrom)) {
      if (!tep_checkdate($dfrom, DOB_FORMAT_STRING, $dfrom_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_FROM_DATE);
      }
    }

    if (tep_not_null($dto)) {
      if (!tep_checkdate($dto, DOB_FORMAT_STRING, $dto_array)) {
        $error = true;
        $date_check_error = true;

        $messageStack->add_session('search', ERROR_INVALID_TO_DATE);
      }
    }

    if (($date_check_error == false) && tep_not_null($dfrom) && tep_not_null($dto)) {
      if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $error = true;

        $messageStack->add_session('search', ERROR_TO_DATE_LESS_THAN_FROM_DATE);
      }
    }

    $price_check_error = false;
    if (tep_not_null($pfrom)) {
      if (!settype($pfrom, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
      }
    }

    if (tep_not_null($pto)) {
      if (!settype($pto, 'double')) {
        $error = true;
        $price_check_error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
      }
    }

    if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
      if ($pfrom > $pto) {
        $error = true;

        $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
      }
    }

    if (tep_not_null($keywords)) {
      if (!tep_parse_search_string($keywords, $search_keywords)) {
        $error = true;

        $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
      }
    }
  }

  if (empty($dfrom) && empty($dto) && empty($pfrom) && empty($pto) && empty($keywords) && (PRODUCTS_PROPERTIES != 'True')) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error == true) {
    tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'NONSSL', true, false));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ADVANCED_SEARCH));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'NONSSL', true, false));

  // create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }

  $select_column_list = '';

  for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name, ';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity, ';
        break;
      case 'PRODUCT_LIST_SHORT_DESRIPTION':
        $select_column_list .= 'if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short , ';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $select_column_list .= 'p.products_image, ';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight, ';
        break;
    }
  }

  $select_str = "select distinct " . $select_column_list . " m.manufacturers_id, p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $tax_classes_query = tep_db_query("select tax_class_id from " . TABLE_TAX_CLASS . " ");
    $rates_sql = " (CASE products_tax_class_id ";
    while ($tax_class = tep_db_fetch_array($tax_classes_query)) {
      $tax_rates[$tax_class['tax_class_id']] = tep_get_tax_rate($tax_class['tax_class_id']);
      $rates_sql .= " WHEN '" . $tax_class['tax_class_id'] . "' THEN '" . tep_get_tax_rate($tax_class['tax_class_id']) . "' ";
    }
    $rates_sql .= " ELSE 0 END) ";
    $select_str .= ", " . $rates_sql . " as tax_rate ";
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True') {
    $from_str = "from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id left join " . TABLE_CATEGORIES . " c on p2c.categories_id = c.categories_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' left join " . TABLE_SPECIALS_PRICES . " sp on s.specials_id = sp.specials_id and sp.groups_id = '" . (int)$customer_groups_id . "' and sp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' ";
  } else {
    $from_str = "from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id)  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p.products_id = p2c.products_id left join " . TABLE_CATEGORIES . " c on p2c.categories_id = c.categories_id ";
  }

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    if (!tep_session_is_registered('customer_country_id')) {
      $customer_country_id = STORE_COUNTRY;
      $customer_zone_id = STORE_ZONE;
    }
    $from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
  }
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True') {
    $where_str = " where p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and (c.categories_status = 1 or c.categories_status is NULL)  and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and pd.affiliate_id = 0 and IF(s.status,  if(sp.specials_new_products_price is NULL, 1, sp.specials_new_products_price != -1 ), 1) ";
  } else {
    $where_str = " where p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and (c.categories_status = 1 or c.categories_status is NULL) ";
  }

  if (isset($HTTP_GET_VARS['categories_id']) && tep_not_null($HTTP_GET_VARS['categories_id'])) {
    if (isset($HTTP_GET_VARS['inc_subcat']) && ($HTTP_GET_VARS['inc_subcat'] == '1')) {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);

      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";

      for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
      }

      $where_str .= ")";
    } else {
      $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";
    }
  }

  if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
    $where_str .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
  }

  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str .= " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_keywords[$i] . " ";
          break;
        default:
          $keyword = tep_db_prepare_input($search_keywords[$i]);
          if (MSEARCH_ENABLE == "true") {
                  $where_str .= "(if(length(pd1.products_name), pd1.products_name_soundex, pd.products_name_soundex) like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%' ";
                  if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= "or if(length(pd1.products_description), pd1.products_description_soundex, pd.products_description_soundex) like '%" . tep_db_input($keyword) . "%'";
                  $where_str .= ')';
          } else {
            $where_str .= "(if(length(pd1.products_name), pd1.products_name, pd.products_name) like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
            if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= " or if(length(pd1.products_description), pd1.products_description, pd.products_description) like '%" . tep_db_input($keyword) . "%'";
            $where_str .= ')';

          }
          break;
      }
    }
    if (PRODUCTS_PROPERTIES == 'True') {
      $properties_yes_no_array = array(array('id' => '', 'text' => OPTION_NONE), array('id' => 'true', 'text' => OPTION_TRUE), array('id' => 'false', 'text' => OPTION_FALSE));
      $sql = "select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES_DESCRIPTION . " prd, " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' and INSTR(pr.mode, 'search') and pr.properties_type in (0, 1, 2, 3, 4) order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name";
      $properties_query = tep_db_query($sql);
      if (tep_db_num_rows($properties_query) > 0)
      {
        $where_str .= " or (";
        for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
          switch ($search_keywords[$i]) {
            case '(':
            case ')':
            case 'and':
            case 'or':
            $where_str .= " " . $search_keywords[$i] . " ";
            break;
            default:
            $keyword = tep_db_prepare_input($search_keywords[$i]);
            $properties_query = tep_db_query($sql);
            $j = 1;
            while ($properties_array = tep_db_fetch_array($properties_query)) {
              if ($j != 1) {
                $where_str .= " or ";
              }
              if ($j == 1) {
                $where_str .= " ( ";
              }
              if ($i == 0 || strpos($from_str, TABLE_PROPERTIES_TO_PRODUCTS . " p2p" . $properties_array['properties_id'])===false) {
                $where_str .= " p2p" . $properties_array['properties_id'] . ".set_value like '%" . tep_db_input($keyword) . "%' ";
                $from_str  .= " left join " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p" . $properties_array['properties_id'] ." on p.products_id = p2p" . $properties_array['properties_id'] . ".products_id and p2p" . $properties_array['properties_id'] . ".language_id = '" . (int)$languages_id. "' and p2p" . $properties_array['properties_id'] . ".properties_id = " . $properties_array['properties_id'] . " ";
              } else {
                $where_str .= " p2p" . $properties_array['properties_id'] . ".set_value like '%" . tep_db_input($keyword) . "%' ";
              }
              $j++;
            }
            $where_str .= " ) ";
          }
        }
        $where_str .= " )";
      }
    }
    $where_str .= " )";
  }
  if (PRODUCTS_PROPERTIES == 'True' && true){
    $sql = "select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES_DESCRIPTION . " prd, " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' and INSTR(pr.mode, 'filter') and pr.properties_type in (0, 1, 2, 3, 4) order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name";
    $properties_query = tep_db_query($sql);
    if (tep_db_num_rows($properties_query) > 0)
    {
      $j = 1;
      while ($properties_array = tep_db_fetch_array($properties_query)){

        if (isset($HTTP_GET_VARS[$properties_array['properties_id']]) && $HTTP_GET_VARS[$properties_array['properties_id']] != ''){
          if (is_array($HTTP_GET_VARS[$properties_array['properties_id']]) && $HTTP_GET_VARS[$properties_array['properties_id']][0] == '' ) {$j++;continue;}
          if (strpos($from_str, TABLE_PROPERTIES_TO_PRODUCTS . " p2p" . $properties_array['properties_id'])===false) {
            $from_str  .= " left join " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p" . $properties_array['properties_id'] ." on p.products_id = p2p" . $properties_array['properties_id'] . ".products_id and p2p" . $properties_array['properties_id'] . ".properties_id = " . $properties_array['properties_id'] . " ";
            $where_str .= " and p2p" . $properties_array['properties_id'] . ".language_id = '" . (int)$languages_id. "' ";
          }

          if (is_array($HTTP_GET_VARS[$properties_array['properties_id']])){
            $where_str .= " and (";
            for ($i=0,$n=sizeof($HTTP_GET_VARS[$properties_array['properties_id']]);$i<$n;$i++){
              if ($i > 0){
                $where_str .= " or ";
              }
              if (isset($HTTP_GET_VARS['exact']) && $properties_array['properties_type']=='0') {
                $where_str .= "p2p" . $properties_array['properties_id'] . ".set_value = '" . $HTTP_GET_VARS[$properties_array['properties_id']][$i] . "'";
              }else{
                $where_str .= "p2p" . $properties_array['properties_id'] . ".set_value like '%" . $HTTP_GET_VARS[$properties_array['properties_id']][$i] . "%'";
              }
            }
            $where_str .= " ) ";
          }else{
            if (isset($HTTP_GET_VARS['exact']) && $properties_array['properties_type']=='0') {
              $where_str .= " and p2p" . $properties_array['properties_id'] . ".set_value = '" . $HTTP_GET_VARS[$properties_array['properties_id']] . "' ";
            }else{
              $where_str .= " and p2p" . $properties_array['properties_id'] . ".set_value like '%" . $HTTP_GET_VARS[$properties_array['properties_id']] . "%' ";
            }
          }
        }
        $j++;
      }
    }
  }

  if (tep_not_null($dfrom)) {
    $where_str .= " and p.products_date_added >= '" . tep_date_raw($dfrom) . "'";
  }

  if (tep_not_null($dto)) {
    $where_str .= " and p.products_date_added <= '" . tep_date_raw($dto) . "'";
  }

  if (USE_MARKET_PRICES != 'True') {
    if (tep_not_null($pfrom)) {
      if ($currencies->is_set($currency)) {
        $rate = $currencies->get_value($currency);

        $pfrom = $pfrom / $rate;
      }
    }

    if (tep_not_null($pto)) {
      if (isset($rate)) {
        $pto = $pto / $rate;
      }
    }
  }

  if ($pfrom > 0 || $pto > 0) {
    $ids = array();
    $ids[] = 0;
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True') {
      $query = tep_db_query("select p.products_id, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )");
    } else {
      $query = tep_db_query("select products_id, products_tax_class_id, products_price from " . TABLE_PRODUCTS . " where products_status = 1");
    }
    while ($data = tep_db_fetch_array($query)) {
      $special_price = tep_get_products_special_price($data['products_id']);
      $price = tep_get_products_price($data['products_id'], 1, $data['products_price']);
      if ($special_price) {
        $price = tep_add_tax($special_price,tep_get_tax_rate($data['products_tax_class_id']));
      } else {
        $price = tep_add_tax($price,tep_get_tax_rate($data['products_tax_class_id']));
      }
      if ($pfrom > 0 && $pto > 0) {
        if ($price > $pfrom && $price < $pto) {
          $ids[] = $data['products_id'];
        }
      }elseif ($pfrom > 0) {
        if ($price > $pfrom) {
          $ids[] = $data['products_id'];
        }
      }elseif ($pto > 0) {
        if ($price < $pto) {
          $ids[] = $data['products_id'];
        }
      }
    }
    $where_str .= " and p.products_id in (" . implode(",", $ids) . ") ";
  }

  if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
        $HTTP_GET_VARS['sort'] = $i+1 . 'a';
        $order_str = ' order by products_name';
        break;
      }
    }
  } else {
    $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
    $sort_order = substr($HTTP_GET_VARS['sort'], 1);
    $order_str = ' order by ';
    switch ($column_list[$sort_col-1]) {
      case 'PRODUCT_LIST_MODEL':
        $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", products_name";
        break;
      case 'PRODUCT_LIST_NAME':
        $order_str .= "products_name " . ($sort_order == 'd' ? "desc" : "");
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", products_name";
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", products_name";
        break;
      case 'PRODUCT_LIST_IMAGE':
        $order_str .= "products_name";
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", products_name";
        break;
      case 'PRODUCT_LIST_PRICE':
        $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", products_name";
        break;
    }
  }


	$listing_sql = $select_str . $from_str . $where_str . $order_str;
	$results_found = tep_db_num_rows(tep_db_query($listing_sql));
	if ($results_found == 0) {	
		$listing_sql = ProductViews::getBestSellersQuery(array("limit"=>0));
	}
  
  
  $content = CONTENT_ADVANCED_SEARCH_RESULT;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
