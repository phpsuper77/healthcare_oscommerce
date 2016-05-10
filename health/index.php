<?php 
/*
  $Id: index.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require("includes/application_top.php");
include_once('controllers/front/FrontController.php');
$controller = new FrontController();
$canonical_tag = $controller->get_canonical_tag();
  
  
  if ($_GET['amazontest'] == "1") $_SESSION['amazonsandbox'] = 1;

// the following cPath references come from application_top.php
  $category_depth = 'top';
  if (isset($cPath) && tep_not_null($cPath)) {
    $listing_url = get_affiliate_directory_listing_url();
    if (tep_not_null($listing_url))
    {
      $ar = split('_', $cPath);
      $listing_url = str_replace('{CID}', $ar[sizeof($ar) - 1], $listing_url);
      $listing_url = str_replace('{SID}', tep_session_name() . '=' . tep_session_id(), $listing_url);
      tep_redirect($listing_url);
    }
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $cateqories_products = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . $customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  left JOIN " . TABLE_PRODUCTS . " pr on p.products_id = pr.products_id   where pr.products_status = 1 and  categories_id = '" . (int)$current_category_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) "));
    }else{
      $cateqories_products = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left JOIN " . TABLE_PRODUCTS . " pr on p.products_id = pr.products_id  where pr.products_status = 1 and p.categories_id = '" . (int)$current_category_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " "));
    }
    if ($cateqories_products['total'] > 0) {
      $category_depth = 'products'; // display products
    } else {
      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "' and categories_status = 1");
      $category_parent = tep_db_fetch_array($category_parent_query);
      if ($category_parent['total'] > 0) {
        $category_depth = 'nested'; // navigate through the categories
      } else {
        $category_depth = 'products'; // category has no products, but display the 'no products' message
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

  if ($category_depth == 'nested') {
//Changed to the following
    $category_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, if(length(cd1.categories_heading_title), cd1.categories_heading_title, cd.categories_heading_title) as categories_heading_title, if(length(cd1.categories_description), cd1.categories_description, cd.categories_description) as categories_description, c.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.categories_id = '" . (int)$current_category_id . "' and c.categories_status = 1 and cd.affiliate_id = 0 and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
//End Categories Description 1.5

    $category = tep_db_fetch_array($category_query);

    $content = CONTENT_INDEX_NESTED;

  } elseif (($category_depth == 'products' || isset($HTTP_GET_VARS['manufacturers_id'])) && $show_direct_link == false) {
// create column list
    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION);

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
        case 'PRODUCT_LIST_NAME':
          $select_column_list .= 'if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, ';
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $select_column_list .= 'm.manufacturers_name, ';
          break;
        case 'PRODUCT_LIST_SHORT_DESRIPTION':
          $select_column_list .= 'if(length(pd1.products_description_short), pd1.products_description_short, pd.products_description_short) as products_description_short, '; 
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $select_column_list .= 'p.products_quantity, ';
          break;
        case 'PRODUCT_LIST_IMAGE':
          $select_column_list .= 'p.products_image, ';
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $select_column_list .= 'p.products_weight, ';
          break;
      }
    }
 // Get the category name and description
    $category_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, if(length(cd1.categories_heading_title), cd1.categories_heading_title, cd.categories_heading_title) as categories_heading_title, if(length(cd1.categories_description), cd1.categories_description, cd.categories_description) as categories_description, c.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);

//========================= EXTRA PROPERTYES =================================  
    $propf_from = '';// add to all listing_sql
    $propf_where = '';// add to all listing_sql
    if (PRODUCTS_PROPERTIES == 'True'){
      $properties_yes_no_array = array(array('id' => '', 'text' => OPTION_NONE), array('id' => 'true', 'text' => OPTION_TRUE), array('id' => 'false', 'text' => OPTION_FALSE));
      $properties_query = tep_db_query("select pr.properties_id, pr.properties_type from " . TABLE_PROPERTIES_DESCRIPTION . " prd, " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' and INSTR(pr.mode, 'filter') and pr.properties_type in (0, 1, 2, 3, 4) order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");
      if (tep_db_num_rows($properties_query) > 0) {
        $j = 1;
        while ($properties_array = tep_db_fetch_array($properties_query)){
          if (isset($HTTP_GET_VARS[$properties_array['properties_id']]) && $HTTP_GET_VARS[$properties_array['properties_id']] != ''){
            if (is_array($HTTP_GET_VARS[$properties_array['properties_id']]) && $HTTP_GET_VARS[$properties_array['properties_id']][0] == '' ) {$j++;continue;}
            $propf_from .= " left join " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p" . $j ." on p.products_id = p2p" . $j . ".products_id AND p2p" . $j .".properties_id='".$properties_array['properties_id']."' ";
            $propf_where .= " and p2p" . $j . ".language_id = '" . (int)$languages_id. "' ";

            if (is_array($HTTP_GET_VARS[$properties_array['properties_id']])){
              $propf_where .= " and (";
              for ($i=0,$n=sizeof($HTTP_GET_VARS[$properties_array['properties_id']]);$i<$n;$i++){
                if ($i > 0){
                  $propf_where .= " or ";
                }
                if (isset($HTTP_GET_VARS['exact']) && $properties_array['properties_type']=='0') {
                  $propf_where .= "p2p" . $j . ".set_value = '" . $HTTP_GET_VARS[$properties_array['properties_id']][$i] . "'";
                }else{
                  $propf_where .= "p2p" . $j . ".set_value like '%" . $HTTP_GET_VARS[$properties_array['properties_id']][$i] . "%'";
                }
              }
              $propf_where .= " ) ";
            }else{
              if (isset($HTTP_GET_VARS['exact']) && $properties_array['properties_type']=='0') {
                $propf_where .= " and p2p" . $j . ".set_value = '" . $HTTP_GET_VARS[$properties_array['properties_id']] . "' ";
              }else{
                $propf_where .= " and p2p" . $j . ".set_value like '%" . $HTTP_GET_VARS[$properties_array['properties_id']] . "%' ";
              }
            }
          }
          $j++;
        }
      }
    } //\ if (PRODUCTS_PROPERTIES == 'True')
//\========================= EXTRA PROPERTYES =================================  

// show the products of a specified manufacturer
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
        // We are asked to show only a specific category
        if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $propf_from . " where p.products_status = 1 and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = p2c.products_id and pd.products_id = p2c.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'" . $propf_where;
        }else{
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . $propf_from . " where p.products_status = 1 and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'" . $propf_where;
        }
      } else {
// We show them all
        if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $propf_from . " where p.products_status = 1 and pd.products_id = p.products_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'" . $propf_where;
        }else{
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . $propf_from . " where p.products_status = 1 and pd.products_id = p.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and pd.affiliate_id = 0 and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'" . $propf_where;
        }
      }
    } else {
// show the products in a given categorie
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only specific catgeory
        if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $propf_from . " where p.products_status = 1 and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.affiliate_id = 0 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'" . $propf_where;
        }else{
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . $propf_from . " where p.products_status = 1 and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.affiliate_id = 0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'" . $propf_where;
        }

      } else {
// We show them all
        if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' " . $propf_from . " where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p2c.categories_id = '" . (int)$current_category_id . "'" . $propf_where;         
        }else{
          $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . $languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id " . $propf_from . " where p.products_status = 1 and p.products_id = p2c.products_id and pd.products_id = p2c.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p2c.categories_id = '" . (int)$current_category_id . "'" . $propf_where;
        }
      }
    }

    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
          $HTTP_GET_VARS['sort'] = $i+1 . 'a';
          $listing_sql .= " order by p.sort_order, products_name";
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);
      $listing_sql .= ' order by p.sort_order';
      switch ($column_list[$sort_col-1]) {
        case 'PRODUCT_LIST_MODEL':
          $listing_sql .= ", p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", products_name";
          break;
        case 'PRODUCT_LIST_NAME':
          $listing_sql .= ", products_name " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'PRODUCT_LIST_MANUFACTURER':
          $listing_sql .= ", m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", products_name";
          break;
        case 'PRODUCT_LIST_QUANTITY':
          $listing_sql .= ", p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", products_name";
          break;
        case 'PRODUCT_LIST_IMAGE':
          $listing_sql .= ", products_name";
          break;
        case 'PRODUCT_LIST_WEIGHT':
          $listing_sql .= ", p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", products_name";
          break;
        case 'PRODUCT_LIST_PRICE':
          $listing_sql .= ", final_price " . ($sort_order == 'd' ? 'desc' : '') . ", products_name";
          break;
      }
    }
	

    $content = CONTENT_INDEX_PRODUCTS;

  } else { // default page

    //$content = CONTENT_INDEX_DEFAULT;
                             
  if($show_direct_link != true){
    $content = CONTENT_INDEX_DEFAULT;
  }else{  
    require(FILENAME_PRODUCT_INFO);
   }

  }
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
