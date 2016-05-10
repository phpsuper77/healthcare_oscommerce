<?php
////
// Get products_head_title_tag
// TABLES: products_description
function tep_get_header_tag_products_title($product_id) {
  global $languages_id, $HTTP_GET_VARS, $HTTP_SESSION_VARS;
  $product_header_tags_values = tep_db_fetch_array(tep_db_query("select if(length(pd1.products_head_title_tag), pd1.products_head_title_tag, pd.products_head_title_tag) as products_head_title_tag from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status = 1 and p.products_id = pd.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p.products_id = '" . (int)$product_id . "'"));

  return $product_header_tags_values['products_head_title_tag'];
}

////
// Get products_head_keywords_tag
// TABLES: products_description
function tep_get_header_tag_products_keywords($product_id) {
  global $languages_id, $HTTP_GET_VARS, $HTTP_SESSION_VARS;

  $product_header_tags_values = tep_db_fetch_array(tep_db_query("select if(length(pd1.products_head_keywords_tag), pd1.products_head_keywords_tag, pd.products_head_keywords_tag) as products_head_keywords_tag from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status = 1 and p.products_id = pd.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p.products_id = '" . (int)$product_id . "'"));

  return $product_header_tags_values['products_head_keywords_tag'];
}
  
////
// Get products_head_desc_tag
// TABLES: products_description
function tep_get_header_tag_products_desc($product_id) {
  global $languages_id, $HTTP_GET_VARS, $HTTP_SESSION_VARS;
  $product_header_tags_values = tep_db_fetch_array(tep_db_query("select if(length(pd1.products_head_desc_tag), pd1.products_head_desc_tag, pd.products_head_desc_tag) as products_head_desc_tag from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status = 1 and p.products_id = pd.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p.products_id = '" . (int)$product_id . "'"));

  return $product_header_tags_values['products_head_desc_tag'];
}

?>
