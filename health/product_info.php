<?php
/*
  $Id: product_info.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once('includes/application_top.php');  

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_info_url = get_affiliate_product_info_url();
  if (tep_not_null($product_info_url))
  {
    $product_info_url = str_replace('{CID}', $HTTP_GET_VARS['products_id'], $product_info_url);
    $product_info_url = str_replace('{SID}', tep_session_name() . '=' . tep_session_id(), $product_info_url);
    tep_redirect($product_info_url);
  }

  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
		/* New query below does allows disabled products to be displayed, requested by JRC - Musaffar */
		//$sql = "select count(*) as total from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:0). "' where p.products_status = 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1) and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id=p.products_id and pd.language_id='" . (int)$languages_id . "' and pd.affiliate_id=0";
		$sql = "select count(*) as total, p.products_status from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:0). "' where 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1) and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id=p.products_id and pd.language_id='" . (int)$languages_id . "' and pd.affiliate_id=0";
		$product_check_query = tep_db_query($sql);
  } else {
		//$sql = "select count(*) as total from " . TABLE_PRODUCTS . " p   " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "'";	  
		$sql = "select count(*) as total, p.products_status from " . TABLE_PRODUCTS . " p   " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where 1 " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "'";	  
		$product_check_query = tep_db_query($sql);
  }

  $product_check = tep_db_fetch_array($product_check_query);    

  $content = CONTENT_PRODUCT_INFO;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  p
?>
