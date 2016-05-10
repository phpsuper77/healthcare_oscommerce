<?php
// /catalog/includes/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.0
// Add META TAGS and Modify TITLE
//
// NOTE: Globally replace all fields in products table with current product name just to get things started: 
// In phpMyAdmin use: UPDATE products_description set PRODUCTS_HEAD_TITLE_TAG = PRODUCTS_NAME
//

$get_def_q = tep_db_query("select m.meta_tags_key, if(m1.meta_tags_value is null, m.meta_tags_value, m1.meta_tags_value) as meta_tags_value
 from ".TABLE_META_TAGS." m left join ".TABLE_META_TAGS." m1 on m.meta_tags_key = m1.meta_tags_key and m.language_id = m1.language_id and m1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where m.language_id = '".(int)$languages_id."' and m.affiliate_id = 0");
if (tep_db_num_rows($get_def_q)>0) {
  while($get_def = tep_db_fetch_array($get_def_q)) {
    define(trim($get_def['meta_tags_key']), $get_def['meta_tags_value']);
  }
}

echo '  <META NAME="Reply-to" CONTENT="' . STORE_OWNER_EMAIL_ADDRESS . '">' . "\n";

$the_desc='';
$the_key_words='';
$the_title='';

// Define specific settings per page:
switch (true) {

// Index page
  case ((strstr($_SERVER['PHP_SELF'],'index.php') or strstr($PHP_SELF,'index.php')) && !isset($current_category_id)):
     $the_title = (defined('HEAD_TITLE_TAG_DEFAULT') && tep_not_null(HEAD_TITLE_TAG_DEFAULT)?HEAD_TITLE_TAG_DEFAULT:HEAD_TITLE_TAG_ALL);
     $the_key_words = (defined('HEAD_KEY_TAG_DEFAULT') && tep_not_null(HEAD_KEY_TAG_DEFAULT)?HEAD_KEY_TAG_DEFAULT:HEAD_KEY_TAG_ALL);
     $the_desc = (defined('HEAD_DESC_TAG_DEFAULT') && tep_not_null(HEAD_DESC_TAG_DEFAULT)?HEAD_DESC_TAG_DEFAULT:HEAD_DESC_TAG_ALL);
  break;
  
// category.PHP
  case ((strstr($_SERVER['PHP_SELF'],'index.php') or strstr($PHP_SELF,'index.php')) && isset($current_category_id) && intval($current_category_id)>0):
    $the_category_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, if(length(cd1.categories_head_title_tag), cd1.categories_head_title_tag, cd.categories_head_title_tag) as categories_head_title_tag, if(length(cd1.categories_head_desc_tag), cd1.categories_head_desc_tag, cd.categories_head_desc_tag) as categories_head_desc_tag, if(length(cd1.categories_head_keywords_tag), cd1.categories_head_keywords_tag, cd.categories_head_keywords_tag) as categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.affiliate_id = 0 and cd.language_id = '" . (int)$languages_id . "'");
    $the_category = tep_db_fetch_array($the_category_query);

    $the_manufacturers_query= tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);

    if (empty($the_category['categories_head_title_tag'])) {
      $the_title= $the_category['categories_name'] . ' ' . (defined('HEAD_TITLE_TAG_DEFAULT') && tep_not_null(HEAD_TITLE_TAG_DEFAULT)?HEAD_TITLE_TAG_DEFAULT:HEAD_TITLE_TAG_ALL) . ' ' . $the_manufacturers['manufacturers_name'];
    } else {
      $the_title= $the_category['categories_head_title_tag'] . ' ' . (defined('HEAD_TITLE_TAG_DEFAULT') && tep_not_null(HEAD_TITLE_TAG_DEFAULT)?HEAD_TITLE_TAG_DEFAULT:HEAD_TITLE_TAG_ALL);
    }

    if (empty($the_category['categories_head_keywords_tag'])) {
      $the_key_words= $the_category['categories_name'].(defined('HEAD_KEY_TAG_DEFAULT') && tep_not_null(HEAD_KEY_TAG_DEFAULT)?', '.HEAD_KEY_TAG_DEFAULT:', '.HEAD_KEY_TAG_ALL);
    } else {
      $the_key_words= $the_category['categories_head_keywords_tag'].(defined('HEAD_KEY_TAG_DEFAULT') && tep_not_null(HEAD_KEY_TAG_DEFAULT)?', '.HEAD_KEY_TAG_DEFAULT:', '.HEAD_KEY_TAG_ALL);
    } 

    if (empty($the_category['categories_head_desc_tag'])) {
      $the_desc= $the_category['categories_name'].' '.(defined('HEAD_DESC_TAG_DEFAULT') && tep_not_null(HEAD_DESC_TAG_DEFAULT)?HEAD_DESC_TAG_DEFAULT:HEAD_DESC_TAG_ALL);
    } else {
      $the_desc= $the_category['categories_head_desc_tag'].' '.(defined('HEAD_DESC_TAG_DEFAULT') && tep_not_null(HEAD_DESC_TAG_DEFAULT)?HEAD_DESC_TAG_DEFAULT:HEAD_DESC_TAG_ALL);
    }

    break;

// PRODUCT_INFO.PHP
  case ( strstr($_SERVER['PHP_SELF'],'product_info.php') or strstr($PHP_SELF,'product_info.php') ):
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description), pd1.products_description, pd.products_description) as products_description, if(length(pd1.products_head_title_tag), pd1.products_head_title_tag, pd.products_head_title_tag) as products_head_title_tag, if(length(pd1.products_head_keywords_tag), pd1.products_head_keywords_tag, pd.products_head_keywords_tag) as products_head_keywords_tag, if(length(pd1.products_head_desc_tag), pd1.products_head_desc_tag, pd.products_head_desc_tag) as products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and pd.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'" . " and pd.affiliate_id = 0 and pd.language_id ='" .  (int)$languages_id . "'");
    }else{
      $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, if(length(pd1.products_description), pd1.products_description, pd.products_description) as products_description, if(length(pd1.products_head_title_tag), pd1.products_head_title_tag, pd.products_head_title_tag) as products_head_title_tag, if(length(pd1.products_head_keywords_tag), pd1.products_head_keywords_tag, pd.products_head_keywords_tag) as products_head_keywords_tag, if(length(pd1.products_head_desc_tag), pd1.products_head_desc_tag, pd.products_head_desc_tag) as products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'" . " and pd.affiliate_id = 0 and pd.language_id ='" .  (int)$languages_id . "'");
    }
    $the_product_info = tep_db_fetch_array($the_product_info_query);

    if (empty($the_product_info['products_head_title_tag'])) {
      $the_title= $the_product_info['products_name'] . ' ' . (defined('HEAD_TITLE_TAG_PRODUCT_INFO') && tep_not_null(HEAD_TITLE_TAG_PRODUCT_INFO)?HEAD_TITLE_TAG_PRODUCT_INFO:HEAD_TITLE_TAG_ALL);
    } else {
      $the_title= $the_product_info['products_head_title_tag'] . ' ' . (defined('HEAD_TITLE_TAG_PRODUCT_INFO') && tep_not_null(HEAD_TITLE_TAG_PRODUCT_INFO)?HEAD_TITLE_TAG_PRODUCT_INFO:HEAD_TITLE_TAG_ALL);
    }

    if (empty($the_product_info['products_head_keywords_tag'])) {
      $the_key_words = $the_product_info['products_name'] . ', ' . (defined('HEAD_KEY_TAG_PRODUCT_INFO') && tep_not_null(HEAD_KEY_TAG_PRODUCT_INFO)?HEAD_KEY_TAG_PRODUCT_INFO:HEAD_KEY_TAG_ALL);
    } else {
      $the_key_words = $the_product_info['products_head_keywords_tag'] . ', ' . (defined('HEAD_KEY_TAG_PRODUCT_INFO') && tep_not_null(HEAD_KEY_TAG_PRODUCT_INFO)?HEAD_KEY_TAG_PRODUCT_INFO:HEAD_KEY_TAG_ALL);
    }

    if (empty($the_product_info['products_head_desc_tag'])) {
      $the_desc = $the_product_info['products_name'] . ' ' . (defined('HEAD_DESC_TAG_PRODUCT_INFO') && tep_not_null(HEAD_DESC_TAG_PRODUCT_INFO)?HEAD_DESC_TAG_PRODUCT_INFO:HEAD_DESC_TAG_ALL);
    } else {
      $the_desc = $the_product_info['products_head_desc_tag'] . ' ' . (defined('HEAD_DESC_TAG_PRODUCT_INFO') && tep_not_null(HEAD_DESC_TAG_PRODUCT_INFO)?HEAD_DESC_TAG_PRODUCT_INFO:HEAD_DESC_TAG_ALL);
    }
	
	/* Create custom meta description tag - Musaffar Patel 201209 --*/
	if ($the_product_info['products_model'] != "" && $the_product_info['products_description'] != "") {	
	
		$meta_description = strip_tags($the_product_info['products_description']);
		$meta_description = "Model ".$the_product_info['products_model']." - ".implode(' ', array_slice(explode(' ', $meta_description), 0, 15));
		$the_desc = $meta_description;
	}
	/* End */
    break;


// PRODUCTS_NEW.PHP
  case ( strstr($_SERVER['PHP_SELF'],'products_new.php') or strstr($PHP_SELF,'products_new.php') ):
     $the_title = (defined('HEAD_TITLE_TAG_WHATS_NEW') && tep_not_null(HEAD_TITLE_TAG_WHATS_NEW)?HEAD_TITLE_TAG_WHATS_NEW:HEAD_TITLE_TAG_ALL);
     $the_key_words = (defined('HEAD_KEY_TAG_WHATS_NEW') && tep_not_null(HEAD_KEY_TAG_WHATS_NEW)?HEAD_KEY_TAG_WHATS_NEW:HEAD_KEY_TAG_ALL);
     $the_desc = (defined('HEAD_DESC_TAG_WHATS_NEW') && tep_not_null(HEAD_DESC_TAG_WHATS_NEW)?HEAD_DESC_TAG_WHATS_NEW:HEAD_DESC_TAG_ALL);
    break;



// SPECIALS.PHP
  case ( strstr($_SERVER['PHP_SELF'],'specials.php')  or strstr($PHP_SELF,'specials.php') ):
    
     $the_title = (defined('HEAD_TITLE_TAG_SPECIALS') && tep_not_null(HEAD_TITLE_TAG_SPECIALS)?HEAD_TITLE_TAG_SPECIALS:HEAD_TITLE_TAG_ALL);

     $new = tep_db_query("select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status = 1 and s.products_id = p.products_id and p.products_id = pd.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' and pd.affiliate_id = 0 order by s.specials_date_added DESC ");

     $row = 0;
     $the_specials='';
     while ($new_values = tep_db_fetch_array($new)) {
        $the_specials .= $new_values['products_name'] . ', ';
     }
     $the_key_words = $the_specials . ', ' . (defined('HEAD_KEY_TAG_SPECIALS') && tep_not_null(HEAD_KEY_TAG_SPECIALS)?HEAD_KEY_TAG_SPECIALS:HEAD_KEY_TAG_ALL);

     $the_desc = (defined('HEAD_DESC_TAG_SPECIALS') && tep_not_null(HEAD_DESC_TAG_SPECIALS)?HEAD_DESC_TAG_SPECIALS:HEAD_DESC_TAG_ALL);
    
    break;

// MANUFACTURERS.PHP
  case ((strstr($_SERVER['PHP_SELF'],'index.php') or strstr($PHP_SELF,'index.php')) && isset($HTTP_GET_VARS['manufacturers_id']) && intval($HTTP_GET_VARS['manufacturers_id'])>0 && (int)$current_category_id == 0):

    if(isset($HTTP_GET_VARS['language']) && $HTTP_GET_VARS['language'])
    {
     $slanguage = tep_db_prepare_input($HTTP_GET_VARS['language']);
     if(!is_null($language))
     {
      $query_slanguage_id = tep_db_fetch_array(tep_db_query("select languages_id from " . TABLE_LANGUAGES . " where code = '" . $slanguage . "'"));                                                                 
      if((int)$query_slanguage_id['languages_id'] > 0)
      {
       $slanguages_id = (int)$query_slanguage_id['languages_id'];
      }
     }
    }
  
	  $query_meta_manufacturers = tep_db_fetch_array(tep_db_query("select m.manufacturers_name, mi.manufacturers_meta_description, mi.manufacturers_meta_key, mi.manufacturers_meta_title from " . TABLE_MANUFACTURERS . " as m left join " . TABLE_MANUFACTURERS_INFO . " as mi on m.manufacturers_id = mi.manufacturers_id where m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'" . (isset($slanguages_id) && (int)$slanguages_id > 0?" and mi.languages_id = '" . (int)$slanguages_id . "'":"")));
	  $the_desc = (strlen($query_meta_manufacturers['manufacturers_meta_description'])>0?$query_meta_manufacturers['manufacturers_meta_description']: HEAD_DESC_TAG_ALL);
    $the_key_words = (strlen($query_meta_manufacturers['manufacturers_meta_key'])>0?$query_meta_manufacturers['manufacturers_meta_key']: HEAD_KEY_TAG_ALL);
    $the_title = (strlen($query_meta_manufacturers['manufacturers_meta_title'])>0?$query_meta_manufacturers['manufacturers_meta_title']:$query_meta_manufacturers['manufacturers_name']) . ' ' . HEAD_TITLE_TAG_ALL;
	  break;
	  
// NEWSDESK_INFO.PHP
  case ((strstr($_SERVER['PHP_SELF'],'newsdesk_info.php') or strstr($PHP_SELF,'newsdesk_info.php')) && isset($HTTP_GET_VARS['newsdesk_id']) && intval($HTTP_GET_VARS['newsdesk_id'])>0): // && (int)$current_category_id == 0
	  $query_meta_manufacturers = tep_db_fetch_array(tep_db_query("select newsdesk_article_name, newsdesk_article_meta_title, newsdesk_article_meta_description, newsdesk_article_meta_key from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . $HTTP_GET_VARS['newsdesk_id'] . "' and language_id = '" . $languages_id . "'"));      
	  $the_desc = (strlen($query_meta_manufacturers['newsdesk_article_meta_description'])>0?$query_meta_manufacturers['newsdesk_article_meta_description']: HEAD_DESC_TAG_ALL);
    $the_key_words = (strlen($query_meta_manufacturers['newsdesk_article_meta_key'])>0?$query_meta_manufacturers['newsdesk_article_meta_key']: HEAD_KEY_TAG_ALL);
    $the_title = (strlen($query_meta_manufacturers['newsdesk_article_meta_title'])>0?$query_meta_manufacturers['newsdesk_article_meta_title']:$query_meta_manufacturers['newsdesk_article_name']) . ' ' . HEAD_TITLE_TAG_ALL;
	  break;
		
// FAQDESK_INFO.PHP
  case ((strstr($_SERVER['PHP_SELF'],'faqdesk_info.php') or strstr($PHP_SELF,'faqdesk_info.php')) && isset($HTTP_GET_VARS['faqdesk_id']) && intval($HTTP_GET_VARS['faqdesk_id'])>0): // && (int)$current_category_id == 0
	  $query_meta_manufacturers = tep_db_fetch_array(tep_db_query("select faqdesk_question, faqdesk_article_meta_title, faqdesk_article_meta_description, faqdesk_article_meta_key from " . TABLE_FAQDESK_DESCRIPTION . " where faqdesk_id = '" . $HTTP_GET_VARS['faqdesk_id'] . "' and language_id = '" . $languages_id . "'"));
	  $the_desc = (strlen($query_meta_manufacturers['faqdesk_article_meta_description'])>0?$query_meta_manufacturers['faqdesk_article_meta_description']: HEAD_DESC_TAG_ALL);
    $the_key_words = (strlen($query_meta_manufacturers['faqdesk_article_meta_key'])>0?$query_meta_manufacturers['faqdesk_article_meta_key']: HEAD_KEY_TAG_ALL);
    $the_title = (strlen($query_meta_manufacturers['faqdesk_article_meta_title'])>0?$query_meta_manufacturers['faqdesk_article_meta_title']:$query_meta_manufacturers['faqdesk_question']) . ' ' . HEAD_TITLE_TAG_ALL;
	  break;			  

// PRODUCTS_REVIEWS_INFO.PHP and PRODUCTS_REVIEWS.PHP
  case ( strstr($_SERVER['PHP_SELF'],'product_reviews_info.php') or strstr($_SERVER['PHP_SELF'],'product_reviews.php') or strstr($_SERVER['PHP_SELF'],'product_reviews_write.php')  or strstr($PHP_SELF,'product_reviews_info.php') or strstr($PHP_SELF,'product_reviews.php') or strstr($PHP_SELF,'product_reviews_write.php') ):
     $the_title = (defined('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO') && tep_not_null(HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO)?HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO:HEAD_TITLE_TAG_ALL) . ' ' . tep_get_header_tag_products_title($HTTP_GET_VARS['products_id']);
     $the_key_words = tep_get_header_tag_products_keywords($HTTP_GET_VARS['products_id']) . ', ' . (defined('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO') && tep_not_null(HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO)?HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO:HEAD_KEY_TAG_ALL);
     $the_desc = tep_get_header_tag_products_desc($HTTP_GET_VARS['products_id']) . ' ' . (defined('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO') && tep_not_null(HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO)?HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO:HEAD_DESC_TAG_ALL);

    break;

// INFORMATION.PHP
  case ( (strstr($_SERVER['PHP_SELF'],'infrotmation.php')  or strstr($PHP_SELF,'information.php')) && (isset($HTTP_GET_VARS['info_id'])) ):
	  $query_infromation_page = tep_db_query("select page_title, meta_description, meta_key from ".TABLE_INFORMATION." WHERE visible='1' AND information_id='".$info_id."' AND languages_id = '" . $languages_id . "'");
	  if(tep_db_num_rows($query_infromation_page))
	  {
	    $row_info_page = tep_db_fetch_array($query_infromation_page);
	    $the_desc = (strlen($row_info_page['meta_description'])>0?$row_info_page['meta_description']:HEAD_DESC_TAG_ALL);
      $the_key_words = (strlen($row_info_page['meta_key'])>0?$row_info_page['meta_key']:HEAD_KEY_TAG_ALL);
      $the_title = strlen($row_info_page['page_title'])>0?$row_info_page['page_title'] . ' ' . HEAD_TITLE_TAG_ALL:HEAD_TITLE_TAG_ALL;
	  }
	  else
	  {
	   $the_desc = HEAD_DESC_TAG_ALL;
     $the_key_words = HEAD_KEY_TAG_ALL;
     $the_title = HEAD_TITLE_TAG_ALL;
	  }
	  break;    
	  
	case ( strstr($_SERVER['PHP_SELF'],'advanced_search_result.php')  or strstr($PHP_SELF,'advanced_search_result.php') ):	  
		$extra_tags[] = '<meta name="robots" content="nofollow" />';
		$no_follow = true;
		break;		

// ALL OTHER PAGES NOT DEFINED ABOVE
  default:
  
  // SEO addon
    $query_infromation_page = tep_db_query("select page_title, meta_description, meta_key from ".TABLE_INFORMATION." WHERE page='" . basename($PHP_SELF) . "' AND visible='1' AND languages_id = '" . $languages_id . "' AND affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'");
	  if(tep_db_num_rows($query_infromation_page))
	  {    
	    $row_info_page = tep_db_fetch_array($query_infromation_page);
	    $the_desc = (strlen($row_info_page['meta_description'])>0?$row_info_page['meta_description']:HEAD_DESC_TAG_ALL);
      $the_key_words = (strlen($row_info_page['meta_key'])>0?$row_info_page['meta_key']:HEAD_KEY_TAG_ALL);
      $the_title = strlen($row_info_page['page_title'])>0?$row_info_page['page_title'] . ' ' . HEAD_TITLE_TAG_ALL:HEAD_TITLE_TAG_ALL;
	  }
	  else
	  {
  // eof SEO addon
  
    $the_desc= HEAD_DESC_TAG_ALL;
    $the_key_words= HEAD_KEY_TAG_ALL;
    $the_title= HEAD_TITLE_TAG_ALL;
  // SEO addon
	  }
	// eof SEO addon	  
    break;

  }
function prepare_tags($value) {
  $value = unhtmlentities($value);
  $value = str_replace('"', "'", $value);
  $value = str_replace(array("\n","\r","\r\n","\n\r"), " ", $value);
  $value = strip_tags($value);
  return $value;
}
  

echo '  <META NAME="Description" Content="' . prepare_tags($the_desc) . '">' . "\n";
echo '  <META NAME="Keywords" CONTENT="' . prepare_tags($the_key_words) . '">' . "\n";
echo '  <META NAME="Author" CONTENT="' . STORE_OWNER . '">' . "\n";
if (!$no_follow) echo '  <META NAME="Robots" CONTENT="index,follow">' . "\n";
//echo '  <meta name="google-site-verification" content="1ov6RvWWHfDxd6jRD1hopuFYbcsSX8kVPeTyTY95Ja4" />' . "\n";

if ($extra_tags) foreach($extra_tags as $key=>$value) {
	print $value."\n";
}

if($request_type == 'SSL')echo '  <META NAME="Robots" CONTENT="noindex,follow">' . "\n";
echo '  <title>' . prepare_tags($the_title) . '</title>' . "\n";

?>
