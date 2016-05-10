<?php
/*
  $Id: html_output.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function
// {{ SEO
  function seo_urlencode($str)
  {
    switch (SEO_URL_ENCODING_METHOD)
    {
    case 'Replace with Underscore (_)':
      $str = urlencode($str);
      $str = preg_replace("/(%[\da-f]{2}|\+)/i", "_", $str);
      return urlencode(strlen($str) > 0 ? $str : ' ');
    case 'Replace Umlauts with 2 Letters Equivs, Other - with (_)':
      $search = array ("'ß'",
                       "'ä'",
                       "'ö'",
                       "'ü'",
                       "'Ä'",
                       "'Ö'",
                       "'Ü'");
      $replace = array ("ss",
                        "ae",
                        "oe",
                        "ue",
                        "AE",
                        "OE",
                        "UE");
      $str = preg_replace($search, $replace, $str);
      $str = urlencode($str);
      $str = preg_replace("/(%[\da-f]{2}|\+)/i", "_", $str);
      return urlencode(strlen($str) > 0 ? $str : ' ');
    /*case 'Standard URL Encode (%XX)': default:
      $str = str_replace('&', urlencode(urlencode('&')), $str);
      $str = str_replace('#', urlencode(urlencode('#')), $str);
      $str = str_replace('/', urlencode(urlencode('/')), $str);
      $str = str_replace('+', urlencode(urlencode('+')), $str);
      $str = str_replace('?', urlencode(urlencode('?')), $str);

      $str = str_replace('-', '_', $str);

      return str_replace('+', '-', urlencode(strlen($str) > 0 ? $str : ' '));*/
     case 'Standard URL Encode (%XX)': default:
      $str = str_replace('&', urlencode(urlencode('&')), $str);
      $str = str_replace('#', urlencode(urlencode('#')), $str);
      $str = str_replace('/', urlencode(urlencode('/')), $str);
      $str = str_replace('+', urlencode(urlencode('+')), $str);
      return urlencode(strlen($str) > 0 ? $str : ' '); 
    }
  }

  function seo_categories_path($cPath_param, $add_extention = true)
  {
    global $languages_id;

    if (SEO_URL_PARTS_CATEGORIES == 'Full Categories Path')
    {		
      $url = '';
      $arr = explode('_', $cPath_param);
      for ($i=0,$n=sizeof($arr);$i<$n;$i++){
        //$res = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id ='" . $arr[$i] . "' and language_id = '" . (int)$languages_id . "'");
        //$data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
        {
          if ($i == ($n - 1) && $add_extention){			  		  
            $url .= seo_urlencode(tep_get_categories_name($arr[$i])) . '.htm';
          }else{
            $url .= seo_urlencode(tep_get_categories_name($arr[$i])) . '/';
          }
        }
        else
        {
          $url .= seo_urlencode(tep_get_categories_name($arr[$i])) . '.' . $arr[$i] . '/';
        }
      }
    }
    else
    {
      $arr = explode('_', $cPath_param);
      $curr_cat = $arr[count($arr) - 1];
      $parent_cat = $arr[count($arr) - 2];
      $res = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c where c.categories_id = cd.categories_id and c.categories_id = '" . (int)$curr_cat . "' and c.parent_id = '" . (int)$parent_cat . "' and cd.language_id = '" . (int)$languages_id . "'");
      $data = tep_db_fetch_array($res);
      $url = seo_urlencode(tep_get_categories_name($data['categories_id'])) . '.' . $data['categories_id'] . '/';
    } // end if (SEO_URL_PARTS_CATEGORIES == 'Full Categories Path')
    return $url;
  }
// }}

////
// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
	  
    global $request_type, $session_started, $SID;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

// {{ SEO
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      global $languages_id, $language, $lng, $lang_code;
      if (!tep_not_null($lang_code))
      {
        if (!isset($lng) || (isset($lng) && !is_object($lng)))
        {
          include(DIR_WS_CLASSES . 'language.php');
          $lng = new language;
        }
        reset($lng->catalog_languages);
        while (list($key, $value) = each($lng->catalog_languages))
        {
          if ($languages_id == $value['id'] && $language == $value['directory'])
          {
            $lang_code = $key;
            break;
          }
        }
      }
    }
// }}
    if (tep_not_null($parameters)) {
//      $link .= $page . '?' . tep_output_string($parameters);
// {{ SEO
      if ((SEO_URL_PARTS_LANGUAGE == 'True') && (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True') && ($search_engine_safe == true))
      {
        if (!preg_match("(^[a-z]{2}/)", $page))
        {
          $link .= $lang_code . '/' . $page . '?' . tep_output_string($parameters);
        }
        else
        {
          $link .= $page . '?' . tep_output_string($parameters);
        }
      }
      else
      {
        $link .= $page . '?' . tep_output_string($parameters);		
      }
// }}
      $separator = '&';
    } else {
//      $link .= $page;
// {{ SEO
      if ((SEO_URL_PARTS_LANGUAGE == 'True') && (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True') && ($search_engine_safe == true))
      {
        if (!preg_match("(^[a-z]{2}/)", $page))
        {
          $link .= $lang_code . '/' . $page;
        }
        else
        {
          $link .= $page;
        }
      }
      else
      {
        $link .= $page;
      }
// }}
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    if ( ((SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True')) && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);	  	  
/*
      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
*/
// {{ SEO
      global $languages_id;
      if (strstr($page, FILENAME_DEFAULT) && strstr($parameters, 'cPath='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_DEFAULT));

        $cPath_param = substr($parameters, strpos($parameters, 'cPath=') + 6);
        if (strpos($cPath_param, '&') !== false)
        {
          $cPath_param = substr($cPath_param, 0, strpos($cPath_param, '&'));
          $parameters = str_replace('cPath=' . $cPath_param . '&', '', $parameters);
        }
        else
        {
          $parameters = str_replace('cPath=' . $cPath_param, '', $parameters);
        }
		
        //$url = seo_categories_path($cPath_param);
        if(!$direct_url = tep_get_categories_direct_url($cPath_param, $parameters))
         $url = seo_categories_path($cPath_param);
        else
				 $url = $direct_url;		
				 
        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . tep_output_string($parameters);
          $separator = '&';
        }
        else
        {
          $link .= $url;
          $separator = '?';
        }
      }
      elseif ( strstr($page, FILENAME_PRODUCT_INFO) && strstr($parameters, 'products_id=') &&
               !strstr($parameters, '{') && !strstr($parameters, '}') )
      {
        $link = substr($link, 0, strpos($link, FILENAME_PRODUCT_INFO));
		
        $products_id_param = substr($parameters, strpos($parameters, 'products_id=') + 12);
        if (strpos($products_id_param, '&') !== false)
        {
          $products_id_param = substr($products_id_param, 0, strpos($products_id_param, '&'));
          $parameters = str_replace('products_id=' . $products_id_param . '&', '', $parameters);
        }
        else
        {
          $parameters = str_replace('products_id=' . $products_id_param, '', $parameters);
        }			
			

        if (strstr($parameters, 'cPath='))
        {
          $cPath_param = substr($parameters, strpos($parameters, 'cPath=') + 6);
          if (strpos($cPath_param, '&') !== false)
          {
            $cPath_param = substr($cPath_param, 0, strpos($cPath_param, '&'));
            $parameters = str_replace('cPath=' . $cPath_param . '&', '', $parameters);
          }
          else
          {
            $parameters = str_replace('cPath=' . $cPath_param, '', $parameters);
          }
        }

        $search = array("/manufacturers_id=\d*/", "/&+$/");
        $replace = array('', '');
        $parameters = preg_replace($search, $replace, $parameters);

				
				if(!$direct_url = tep_get_products_direct_url($products_id_param, $parameters))
				{     

        if (tep_not_null($cPath_param))
        {
          $cPath_ar = tep_parse_category_path($cPath_param);
          $cPath_param = implode('_', $cPath_ar);
          $products_category_id = $cPath_ar[(sizeof($cPath_ar)-1)];
          $category_query = tep_db_query("select count(*) as in_category from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id_param . "' and categories_id = '" . (int)$products_category_id . "'");
          $category = tep_db_fetch_array($category_query);
          if ($category['in_category'])
          {
            $url = seo_categories_path($cPath_param, false);
          }
          else
          {
            $url = seo_categories_path(tep_get_product_path($products_id_param), false);
          }
        }
        else
        {			
			$url = seo_categories_path(tep_get_product_path($products_id_param), false);			
        }
		
		/* Musaffar Patel */
		$url = "";
		
		

        if (SEO_URL_PARTS_MANUFACTURER == 'True')
        {
          $data_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_MANUFACTURERS . " m where m.manufacturers_id = p.manufacturers_id and p.products_id = '" . (int)$products_id_param . "'");
          $data = tep_db_fetch_array($data_query);
          if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
          {
            $url .= seo_urlencode($data['manufacturers_name']) . '/';
          }
          else
          {
            $url .= seo_urlencode($data['manufacturers_name']) . ($data['manufacturers_id'] ? '.' . $data['manufacturers_id'] : '') . '/';
          }
        }

        if (SEO_URL_PARTS_MODEL == 'True')
        {
          $data_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id_param . "'");
          $data = tep_db_fetch_array($data_query);
          $url .= seo_urlencode($data['products_model']) . '/';
        }

        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
        {
			//$url .= seo_urlencode(tep_get_products_seo_page_name((int)$products_id_param)) . '.html';
			$url .= seo_urlencode(tep_get_products_seo_page_name((int)$products_id_param));
        }
        else
        {
          $url .= seo_urlencode(tep_get_products_seo_page_name((int)$products_id_param)) . '.' . (int)$products_id_param . '.html';
        }

    				}
        else
        {
         $url = $direct_url;
        }    
        
        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . tep_output_string($parameters);
          $separator = '&';
        }
        else
        {
          $link .= $url;
          $separator = '?';
        }
      }
// {{{
      elseif (strstr($page, FILENAME_INFORMATION) && strstr($parameters, 'info_id='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_INFORMATION));
        // remove FILENAME_INFORMATION from link

        $tmp = array();
        preg_match('/info_id=(\d+)/', $parameters, $tmp);
        $info_id_param = $tmp[1]; 
        $search = array("/info_id=\d*/", "/&+$/");
        $replace = array('', '');
        $parameters = preg_replace($search, $replace, $parameters);


        global $languages_id;
        $res = tep_db_query("select info_title, information_id from " . TABLE_INFORMATION . " where information_id = '" . (int)$info_id_param . "' and languages_id = '" . ((int)$languages_id<1?1:$languages_id) . "' and visible = 1");
        $data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
        {
          $url = 'info/' . seo_urlencode($data['info_title']) . '.html';
        }
        else
        {
          $url = 'info/' . seo_urlencode($data['info_title']) . '.' . $data['information_id'] . '.html';
        }

        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . tep_output_string($parameters);
          $separator = '&';
        }
        else
        {
          $link .= $url;
          $separator = '?';
        }		
      }
      elseif (strstr($page, FILENAME_FAQDESK_INFO) && strstr($parameters, 'faqdesk_id='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_FAQDESK_INFO));

        $tmp = array();
        preg_match('/faqdesk_id=(\d+)/', $parameters, $tmp);
        $faqdesk_id_param = $tmp[1]; 
        $search = array("/faqdesk_id=\d*/", "/&+$/");
        $replace = array('', '');
        $parameters = preg_replace($search, $replace, $parameters);

        global $languages_id, $lng;
        if($parameters!="" && strstr($parameters, 'language='))
        {
          $language_name = '';
          $cPath_param = substr($parameters, strpos($parameters, 'language=') + 9);
          if (strpos($cPath_param, '&') !== false)
            $language_name = substr($cPath_param, 0, strpos($cPath_param, '&'));
          else
            $language_name = $cPath_param;

          $new_languages_id = (int)$lng->catalog_languages[$language_name]['id'];
          if($new_languages_id > 0)$languages_id = $new_languages_id;
        }

        $res = tep_db_query("select p.faqdesk_id, pd.faqdesk_question from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . (int)$faqdesk_id_param . "' and pd.faqdesk_id = '" . (int)$faqdesk_id_param . "' and pd.language_id = '" . $languages_id . "'");
        $data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
        {
          $url = 'faqdesk/' . seo_urlencode($data['faqdesk_question']) . '.html';
        }
        else
        {
          $url = 'faqdesk/' . seo_urlencode($data['faqdesk_question']) . '.' . $data['faqdesk_id'] . '.html';
        }

        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . $parameters;
          $separator = '&';
        }
        else
        {
          $link .= $url;
          $separator = '?';		  
        }
      }
      elseif (strstr($page, FILENAME_NEWSDESK_INFO) && strstr($parameters, 'newsdesk_id='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_NEWSDESK_INFO));
        $tmp = array();
        preg_match('/newsdesk_id=(\d+)/', $parameters, $tmp);
        $newsdesk_id_param = $tmp[1]; 
        $search = array('/newsdesk_id=\d*/', '/&+$/');
        $replace = array('', '');
        $parameters = preg_replace($search, $replace, $parameters);

        global $languages_id, $lng;
        if($parameters!="" && strstr($parameters, 'language='))
        {
          $language_name = '';
          $cPath_param = substr($parameters, strpos($parameters, 'language=') + 9);
          if (strpos($cPath_param, '&') !== false)
            $language_name = substr($cPath_param, 0, strpos($cPath_param, '&'));
          else
            $language_name = $cPath_param;

          $new_languages_id = (int)$lng->catalog_languages[$language_name]['id'];
          if($new_languages_id > 0)$languages_id = $new_languages_id;
        }

        $res = tep_db_query("select p.newsdesk_id, pd.newsdesk_article_name from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . (int)$newsdesk_id_param . "' and pd.newsdesk_id = '" . (int)$newsdesk_id_param . "' and pd.language_id = '" . $languages_id . "'");
        $data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
        {
          $url = 'newsdesk/' . seo_urlencode($data['newsdesk_article_name']) . '.html';
        }
        else
        {
          $url = 'newsdesk/' . seo_urlencode($data['newsdesk_article_name']) . '.' . $data['newsdesk_id'] . '.html';
        }

        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . $parameters;
          $separator = '&';
        }
        else
        {
          $link .= $url;
          $separator = '?';
        }
      }
// SEO Manufacturers
      elseif (strstr($page, FILENAME_DEFAULT) && strstr($parameters, 'manufacturers_id='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_DEFAULT));

        $info_id_param = substr($parameters, strpos($parameters, 'manufacturers_id=') + 17);
        if (strpos($info_id_param, '&') !== false)
        {
          $info_id_param = substr($info_id_param, 0, strpos($info_id_param, '&'));
          $parameters = str_replace('manufacturers_id=' . $info_id_param . '&', '', $parameters);
        }
        else
        {
          $parameters = str_replace('manufacturers_id=' . $info_id_param, '', $parameters);
        }

        global $languages_id, $lng;

        if($parameters!="" && strstr($parameters, 'language='))
        {
          $language_name = '';
          $cPath_param = substr($parameters, strpos($parameters, 'language=') + 9);
          if (strpos($cPath_param, '&') !== false)
            $language_name = substr($cPath_param, 0, strpos($cPath_param, '&'));
          else
            $language_name = $cPath_param;

          $new_languages_id = (int)$lng->catalog_languages[$language_name]['id'];
          if($new_languages_id > 0)$languages_id = $new_languages_id;
        }

        //if (SEO_URL_PARTS_MANUFACTURER == 'True')
        //{

          $data_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, mi.manufacturers_seo_name from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . (int)$languages_id . "' and m.manufacturers_id = '" . (int)$info_id_param . "'");
          $data = tep_db_fetch_array($data_query);
          if(strlen($data['manufacturers_seo_name'])>0)$data['manufacturers_name'] = $data['manufacturers_seo_name'];
          if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
          {                                                          // && SEO_URL_PARTS_ID == 'False'
//            $url .= seo_urlencode($data['manufacturers_name']) . '/';
            $url = 'brand/' . seo_urlencode($data['manufacturers_name']) . '.html';
          }
          else
          {
            $url = 'brand/' . seo_urlencode($data['manufacturers_name']) . ($data['manufacturers_id'] ? '.' . $data['manufacturers_id'] : '') . '.html';
//            $url = 'brand/' . seo_urlencode($data['manufacturers_name']) . '.' . $data['manufacturers_id'] . '.html';
          }
        //}


        if (tep_not_null($parameters))
        {
          $link .= $url . '?' . $parameters;
          $separator = '&';		  
        }
        else
        {
          $link .= $url;
          $separator = '?';		  
        }
      }
// eof SEO Manufacturers
// }}}
      else
      {
/*
        $link = str_replace('?', '/', $link);
        $link = str_replace('&', '/', $link);
        $link = str_replace('=', '/', $link);
*/
         if (tep_not_null($parameters))
         {
           $separator = '&';
         }
         else
         {
            $separator = '?';
         }		 
      }
// }}
    }
	

    if (isset($_sid)  && ($session_started)) {
      $link .= $separator . tep_output_string($_sid);
    }

    $link = preg_replace("/&(?!amp;)/", "&amp;", $link);
    $link = str_replace(' ', '%20', $link);

    $link = str_replace('/' . FILENAME_DEFAULT, '/', $link);
    return $link;
  }


  function getNewSize($pic, $reqW, $reqH, $na=false)
  {
    $size = @GetImageSize ($pic);

    if ($size[0] == 0 || $size[1] == 0)
    {
            $newsize[0] = $reqW;
            $newsize[1] = $reqH;
            return $newsize;
    }
    if ( $na && (int)$reqW>0 && is_numeric($reqW) && (int)$reqH>0 && is_numeric($reqH) ) {
      if ( $size[0]>(int)$reqW || $size[1]>(int)$reqH ) {
        $scale = @min($reqW/$size[0], $reqH/$size[1]);
      }else{
        $scale = 1;
      }
    }else{
      $scale = @min($reqW/$size[0], $reqH/$size[1]);
    }
//    $scale = @($reqH/$size[1]);
    $newsize[0] = $size[0]*$scale; $newsize[1] = $size[1]*$scale;
    return $newsize;
  }
////
// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '', $flag = true) {
    Global $language;

    $req_width = $width; $req_height = $height;
    if ($flag)
    {
      $size = @GetImageSize(DIR_FS_CATALOG . "/" .$src);
      if(!($size[0] <= $width && $size[1] <= $height)) {
        $newsize = getNewSize(DIR_FS_CATALOG . "/" .$src, $width, $height);

        $width = (int)$newsize[0];
        $height = (int)$newsize[1];

      } else {
        $width = (int)$size[0];
        $height = (int)$size[1];
      }

      if (($size[0] == 0 || $size[1] == 0) && (IMAGE_REQUIRED == 'true'))
      {
          $src = DIR_WS_TEMPLATE_IMAGES . 'buttons/'.$language.'/na.gif';
          //$newsize = getNewSize(DIR_FS_CATALOG . "/" .$src, $width, $height);
          $newsize = getNewSize(DIR_FS_CATALOG . "/" .$src, $req_width, $req_height, true);
          $width = (int)$newsize[0];
          $height = (int)$newsize[1];
      }
    }
    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = (int)($image_size[0] * $ratio);
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = (int)($image_size[1] * $ratio);
        } elseif (empty($width) && empty($height)) {
          $width = (int)$image_size[0];
          $height = (int)$image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
      $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_TEMPLATE_IMAGES . '' . $image, '', $width, $height, '', false);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string_protected(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string_protected($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {
      $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string_protected($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string_protected(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
	  
	  if ($values[$i]['extra'] != '') {
		  $field .= " ".$values[$i]['extra']." ";
	  }

    //$field .= '>' . tep_output_string($values[$i]['text'], array('&' => '&amp;', '"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
      $field .= '>' . preg_replace('/&amp;pound;/', '&pound;', tep_output_string($values[$i]['text'], array('&' => '&amp;', '"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;'))) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return str_replace('&amp;nbsp;', '&nbsp;', $field);
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries('',true);

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 
	  							 'text' => $countries[$i]['countries_name'],
								 'extra' => "rel-isocode='".$countries[$i]['countries_iso_code_2']."'"
								 );
    }
	
    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }

  /*
  Output product as table in design
  Input: array contained products_id, products_name, products_image, products_description_short, products_tax_class_id
  */

  function tep_output_product_table_sell($product_array, $buy_now_buttom = false){
    global $currencies;
    $special_price = tep_get_products_special_price($product_array['products_id']);
    if ($special_price){
      $str = '<table border="0" cellpadding="0" cellspacing="0" class="productTable">
                <tr>
                  <td></td>
                  <td class="productImageCell"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_array["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $product_array['products_image'], $product_array['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
                  </td>
                </tr>
                <tr>
                  <td valign="top" style="padding-top:5px;padding-right:3px;">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'contentbox/arrow.gif', $product_array['products_name'], 10, 10) . '</td>
                  <td valign="top" class="productNameCell"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_array['products_id']) . '">' . $product_array['products_name'] . '</a>
                  </td>
                </tr>';
      if ($product_array['products_description_short']){
        $str .= '<tr>
                    <td></td>
                    <td class="productNameCell">'.$product_array['products_description_short'] . '</td>
                 </tr>';
      }
      $str .= '<tr>
                  <td></td>
                  <td class="productPriceCell"><span class="productPriceOld">' . $currencies->display_price(tep_get_products_price($product_array['products_id'], 1, $product_array['products_price']), tep_get_tax_rate($product_array['products_tax_class_id'])) . '</span><br><span class="productPriceSpecial">' . $currencies->display_price($special_price, tep_get_tax_rate($product_array['products_tax_class_id'])) . '</span>
                  </td>
               </tr>
               ' . ($buy_now_buttom?'<tr><td></td><td align="center"><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $product_array['products_id'], 'NONSSL') . '">' . tep_template_image_button('button_buy_now.' . BUTTON_IMAGE_TYPE, TEXT_BUY . $product_array['products_name'] . TEXT_NOW, 'class="transpng"') .'</a></td></tr>':'') . '
               </table>';
    }else{
      $str = '<table border="0" cellpadding="0" cellspacing="0" class="productTable">
               <tr>
                <td></td>
                <td align="center" style="height:100%" valign="top" class="productImageCell"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_array["products_id"]) . '">' . tep_image(DIR_WS_IMAGES . $product_array['products_image'], $product_array['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
                </td>
             </tr>
             <tr>
               <td valign="top" style="padding-top:5px;padding-right:3px;">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'contentbox/arrow.gif', $product_array['products_name'], 10, 10) . '</td>
              <td valign="top" class="productNameCell"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_array['products_id']) . '">' . $product_array['products_name'] . '</a>
              </td>
            </tr>';
      if ($product_array['products_description_short']){
        $str .= '<tr>
                   <td></td>
                   <td class="productNameCell">'.$product_array['products_description_short'] . '</td>
                 </tr>';
      }
      $str .= '<tr>
              <td></td>
              <td class="productPriceCell"><span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($product_array['products_id'], 1, $product_array['products_price']), tep_get_tax_rate($product_array['products_tax_class_id'])) . '</span>
              </td>
            </tr>
            ' . ($buy_now_buttom?'<tr><td></td><td align="center"><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $product_array['products_id'], 'NONSSL') . '">' . tep_template_image_button('button_buy_now.' . BUTTON_IMAGE_TYPE, TEXT_BUY . $product_array['products_name'] . TEXT_NOW, 'class="transpng"') .'</a></td></tr>':'') . '
            </table>';
    }
    return $str;
  }
  function tep_cut_text($text,$cut=50){
  $text = preg_replace('/\s{2,}/', ' ', strip_tags($text) );
  $text = trim($text);
  $orl = strlen($text);
  if ( $cut!==false ) { 
    $text = substr( $text, 0, $cut );
  }
  if ( $orl!=strlen($text) ) {
    $text = preg_replace( '/(\w+)$/', '',$text );
    $text .= ' ...';    
  }
  return $text;
}

?>