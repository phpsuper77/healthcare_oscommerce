<?php
ini_set ( 'display_errors', 0 );
require ('includes/application_top.php');
require_once (DIR_WS_CLASSES . 'currencies.php');

$currencies = new currencies ();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php
echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php
	echo CHARSET;
	?>">
<title><?php
echo TITLE;
?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
$header_title_menu = BOX_HEADING_CATALOG;
$header_title_menu_link = tep_href_link ( "data.php", 'selected_box=categories' );
$header_title_submenu = HEADING_TITLE;
?>
<?php

require (DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td background="images/left_separator.gif"
			width="<?php
			echo BOX_WIDTH;
			?>" valign="top" height="100%"
			valign=top>
		<table border="0" width="<?php
		echo BOX_WIDTH;
		?>" cellspacing="0"
			cellpadding="0" height="100%" valign=top>
			<tr>
				<td width=100% height=25 colspan=2>
				<table border="0" width="100%" cellspacing="0" cellpadding="0"
					background="images/infobox/header_bg.gif">
					<tr>
						<td width="28"><img src="images/l_left_orange.gif" width="28"
							height="25" alt="" border="0"></td>
						<td background="images/l_orange_bg.gif"><img
							src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
					</tr>
				</table>
				</td>
			</tr>
			</tr>
			<tr>
				<td valign=top>
				<table border="0" width="<?php
				echo BOX_WIDTH;
				?>" cellspacing="0"
					cellpadding="0" valign=top>
					<!-- left_navigation //-->
<?php
require (DIR_WS_INCLUDES . 'column_left.php');
?>
<!-- left_navigation_eof //-->
				</table>
				</td>
				<td width=1 background="images/line_nav.gif"><img
					src="images/line_nav.gif"></td>
			</tr>
		</table>
		</td>
		<!-- body_text //-->
		<td width="100%" valign="top" height="100%">
		<table border="0" width="100%" cellspacing="0" cellpadding="0"
			height="100%">
			<tr>
				<td height="100%">
				<table border="0" width="100%" cellspacing="0" cellpadding="0"
					height="100%">
					<tr>
						<td valign="top">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent"><?php
								echo TEXT_DATAFEED_NAME;
								?></td>

								<td class="dataTableHeadingContent"><?php
								echo TEXT_CREATE_DATAFEED;
								?></td>
								<td class="dataTableHeadingContent"><?php
								echo TEXT_DATAFEED_DATE;
								?></td>
								<td class="dataTableHeadingContent"><?php
								echo TEXT_DATAFEED_SIZE;
								?></td>
								<td class="dataTableHeadingContent"><?php
								echo TEXT_DOWNLOAD_DATAFEED;
								?></td>
							</tr>
              <?php
														$data ['file_name'] = "Webgains.csv";
														$datafeedDir = '../datafeeds/';
														?>
                    <tr class="dataTableRow"
								onmouseover="rowOverEffect(this)"
								onmouseout="rowOutEffect(this)">
								<td class="dataTableContent"><?php
								echo "Webgains";
								?></td>

								<td class="dataTableContent"><?php
								echo '<a href="' . tep_href_link ( "data.php", 'action=create' ) . '&file=' . $data ['file_name'] . '">' . TEXT_CREATE . '</a>';
								?></td>
								<td class="dataTableContent">
                      <?php
																						if ($_REQUEST ['action'] == 'create')
																							Webgains ();
																						if ($_REQUEST ['file'] == 'Webgains.csv') {
																							echo date ( PHP_DATE_TIME_FORMAT, exec ( 'stat -c %Y ' . escapeshellarg ( $datafeedDir . $data ['file_name'] ) ) );
																						} elseif (is_file ( $datafeedDir . $data ['file_name'] )) {
																							echo date ( PHP_DATE_TIME_FORMAT, filemtime ( $datafeedDir . $data ['file_name'] ) );
																						} else {
																							echo '<font color="red">-</font>';
																						}
																						?>
                      
                      </td>
								<td class="dataTableContent">
                      <?php
																						if ($_REQUEST ['file'] == 'Webgains.csv') {
																							echo implode ( " ", formatByteDown ( exec ( 'stat -c %s ' . escapeshellarg ( $datafeedDir . $data ['file_name'] ) ) ) );
																						} elseif (is_file ( $datafeedDir . $data ['file_name'] )) {
																							echo implode ( " ", formatByteDown ( filesize ( $datafeedDir . $data ['file_name'] ) ) );
																						} else {
																							echo '<font color="red">' . TEXT_FILE_DOES_NOT_EXISTS . '</font>';
																						}
																						?>
                      </td>
								<td class="dataTableContent">
                      <?php
																						if (is_file ( $datafeedDir . $data ['file_name'] )) {
																							echo '<a href="' . tep_href_link ( $datafeedDir . $data ['file_name'] ) . '">' . Download . '</a>';
																						} else {
																							echo '<font color="red">' . TEXT_NOT_AVAILABLE . '</font>';
																						}
																						
																						?>
                      </td>
							</tr>
<?php
function formatByteDown($value, $limes = 3, $comma = 2) {
	$byteUnits = array ('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB' );
	
	$dh = pow ( 10, $comma );
	$li = pow ( 10, $limes );
	$return_value = $value;
	$unit = $byteUnits [0];
	
	for($d = 6, $ex = 15; $d >= 1; $d --, $ex -= 3) {
		if (isset ( $byteUnits [$d] ) && $value >= $li * pow ( 10, $ex )) {
			$value = round ( $value / (pow ( 1024, $d ) / $dh) ) / $dh;
			$unit = $byteUnits [$d];
			break 1;
		} // end if
	} // end for
	

	$return_value = number_format ( $value, $comma, '.', ' ' );
	
	return array ($return_value, $unit );
}
function prepare_string($in_desc, $len = false, $dquote = false, $lite = false) {
	$in_desc = strip_tags ( $in_desc );
	
	if ($dquote === false) {
		$in_desc = trim ( str_replace ( array ('&nbsp;', "\t", "\n", "\r", '"' ), ' ', $in_desc ) );
	} else {
		$in_desc = trim ( str_replace ( array ('&nbsp;', "\t", "\n", "\r" ), ' ', $in_desc ) );
		$in_desc = trim ( str_replace ( array ('"' ), '""', $in_desc ) );
	}
	
	if ($len !== false)
		return substr ( trim ( $in_desc ), 0, ($len - 1) );
	return trim ( $in_desc );
}
function prepare_desc($in_desc, $use_limit = true, $filter_text = false) {
	global $desc_length;
	$in_desc = str_replace ( '>', '> ', $in_desc );
	$in_desc = strip_tags ( $in_desc );
	$in_desc = trim ( str_replace ( array ('&nbsp;', chr ( 160 )/* nbsp representation */, ',', "\t", "\n", "\r", '"' ), ' ', $in_desc ) );
	//$in_desc = striphtml(trim($in_desc));
	$in_desc = trim ( str_replace ( array (',', "\t", "\n", "\r", "\"" ), ' ', $in_desc ) );
	if ($filter_text) {
		// try remove "Click here for the full review" or "Click here for the review" or "Click here for review"
		$in_desc = preg_replace ( '/click\s*?here\s*?for(\s*?the)?(\s*?full)?\s*?review\.?/i', ' ', $in_desc );
	}
	$in_desc = preg_replace ( '/\s{2,}/', ' ', $in_desc );
	if ($use_limit === true) {
		if (strlen ( $in_desc ) > $desc_length)
			$in_desc = substr ( $in_desc, 0, $desc_length - 1 ) . '...';
	} elseif (is_string ( $use_limit ) && substr ( $use_limit, - 1 ) == 'w') {
		$words = split ( ' ', $in_desc );
		$wordlimit = ( int ) $use_limit;
		$in_desc = implode ( ' ', array_slice ( $words, 0, $wordlimit ) ) . (count ( $words ) > $wordlimit ? ' ...' : '');
	} elseif ($use_limit !== false && ( int ) $use_limit > 0) {
		if (strlen ( $in_desc ) > ( int ) $use_limit)
			$in_desc = substr ( $in_desc, 0, ( int ) $use_limit - 1 ) . '...';
	}
	$in_desc = trim ( str_replace ( array ('&nbsp;', chr ( 160 )/* nbsp representation */,',', "\t", "\n", "\r", '"' ), ' ', $in_desc ) );
	
	return trim ( $in_desc );
}
function deleteupper($string){
	$string=str_replace('"','""',$string);
	$string=str_replace('"','',$string);
	$string=trim($string);
	$string=rtrim($string);
	return $string;
}
function field_prepare($in,$quote=true) {

 $in = strip_tags( trim($in));
 $in = str_replace(array("\r\n", "\n", "\r", "\t")," ", $in);
 //$in = strtr( $in, $trans );
 if($quote){
 	$in = '"'.$in.'"';
 }
 return $in;
}
function Webgains() {

$fp = fopen ( "../datafeeds/Webgains.csv", "w" );
	$headerData = array ('0' => 'Product Name', '1' => 'Brand', '2' => 'Short Description', '3' => 'product ID', '4' => 'Description', '5' => 'Deeplink', '6' => 'image_URL', '7' => 'Price', '8' => 'Category', 

	'9' => 'In Stock', '10' => 'top sellers' );
	$sep = ",";
	$quote = '"';
    	fwrite ( $fp, $quote . implode ( $quote . $sep . $quote, $headerData ) . $quote . "\n" );
	$rezult = tep_db_query ( "SELECT * from products_description,products_to_categories,products,categories_description,manufacturers where products_description.products_id=products_to_categories.products_id and categories_description.language_id=1 and products_to_categories.categories_id=categories_description.categories_id and products_description.language_id=1 and products.products_id=products_description.products_id and products.manufacturers_id=manufacturers.manufacturers_id and products.products_status=1" );
	while ( $row = tep_db_fetch_array ( $rezult ) ) {
		$pprice = tep_add_tax($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));       
    	$pprice = number_format($pprice, 2, '.', '');
       $prod_description = field_prepare(substr((strlen(trim(strip_tags($row['products_description'])))>0?trim($row['products_description']):$row['products_name']), 0, 65536));
    	
    	$i ++;
		fwrite ( $fp, $quote.deleteupper(prepare_string ( $row ['products_name'] )) .$quote. $sep );
		if ($row ['manufacturers_name'] == '')
			$row ['manufacturers_name'] = "no";
		fwrite ( $fp, $quote.deleteupper(prepare_string ( $row ['manufacturers_name'] )) .$quote. $sep );
		
		/*if ($row ['products_description_short'] == '')
			$row ['products_description_short'] = "no";*/
		fwrite ( $fp, $quote.deleteupper(strip_tags($row ['products_description_short'])) .$quote. $sep );
		fwrite ( $fp, $quote.deleteupper($row ['products_id']) .$quote. $sep );
		
		fwrite ( $fp, $quote.deleteupper($prod_description) .$quote. $sep );
		fwrite ( $fp, $quote.deleteupper(str_replace('https://', 'http://', tep_href_catalog_seo_link(FILENAME_PRODUCT_INFO,'products_id='.$row['products_id'],'NONSSL',false, true))) .$quote. $sep );
		$_SERVER ['HTTP_HOST']=str_replace('www.','',$_SERVER ['HTTP_HOST']);
		fwrite ( $fp, $quote.deleteupper(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $row['products_image']).$quote. $sep );
		fwrite ( $fp, $quote.deleteupper($pprice) .$quote. $sep );
		fwrite ( $fp,  $quote.deleteupper($row ['categories_name'])  .$quote. $sep );
		if ($row ['products_quantity'] > 0) {
			$in = 'Y';
		} else {
			$in = 'N';
		}
		fwrite ( $fp, $quote.deleteupper($in) .$quote. $sep );
		fwrite ( $fp, $quote.deleteupper($i) .$quote. "\n" );
		
		
	}

}
/*/////
 * 
 */
function tep_href_catalog_seo_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_CATALOG;
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
      if ((SEO_URL_PARTS_LANGUAGE == 'True') && (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True'))
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
      if ((SEO_URL_PARTS_LANGUAGE == 'True') && (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True'))
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

        $url = seo_categories_path($cPath_param);

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

        if (tep_not_null($cPath_param))
        {
          $cPath_ar = tep_parse_category_path($cPath_param);
          $cPath_param = implode('_', $cPath_ar);
          $products_category_id = $cPath_ar[(sizeof($cPath_ar)-1)];
          $category_query = tep_db_query("select count(*) as in_category from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id_param . "' and categories_id = '" . (int)$products_category_id . "'");
          $category = tep_db_fetch_array($category_query);
          if ($category['in_category'])
          {
            $url = seo_categories_path($cPath_param);
          }
          else
          {
            $url = seo_categories_path(tep_get_product_path1($products_id_param));
          }
        }
        else
        {
          $url = seo_categories_path(tep_get_product_path1($products_id_param));
        }

        if (SEO_URL_PARTS_MANUFACTURER == 'True')
        {
          $data_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_MANUFACTURERS . " m where m.manufacturers_id = p.manufacturers_id and p.products_id = '" . (int)$products_id_param . "'");
          $data = tep_db_fetch_array($data_query);
          if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
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

        $data_query = tep_db_query("select p.products_id, if(length(p.products_seo_page_name) > 0, p.products_seo_page_name, pd.products_name) products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and p.products_id = '" . (int)$products_id_param . "' and pd.language_id = '" . (int)$languages_id . "'");
        $data = tep_db_fetch_array($data_query);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
        {
          $url .= seo_urlencode($data['products_name']) . '.html';
        }
        else
        {
          $url .= seo_urlencode($data['products_name']) . '.' . $data['products_id'] . '.html';
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
        if($data['direct_link'] != '')$link = HTTP_SERVER . DIR_WS_CATALOG . $data['direct_link'];
      }
// {{{
      elseif (strstr($page, FILENAME_INFORMATION) && strstr($parameters, 'info_id='))
      {
        $link = substr($link, 0, strpos($link, FILENAME_INFORMATION));

        $info_id_param = substr($parameters, strpos($parameters, 'info_id=') + 8);
        if (strpos($info_id_param, '&') !== false)
        {
          $info_id_param = substr($info_id_param, 0, strpos($info_id_param, '&'));
          $parameters = str_replace('info_id=' . $info_id_param . '&', '', $parameters);
        }
        else
        {
          $parameters = str_replace('info_id=' . $info_id_param, '', $parameters);
        }

        global $languages_id;
        $res = tep_db_query("select info_title, information_id from " . TABLE_INFORMATION . " where information_id = '" . (int)$info_id_param . "' and languages_id = '" . (int)$languages_id . "' and visible = 1");
        $data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
        {
          $url = 'info/' . seo_urlencode($data['info_title']) . '.html';
        }
        else
        {
          $url = 'info/' . seo_urlencode($data['info_title']) . '.' . $data['information_id'] . '.html';
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
      $link .= $separator . $_sid;
    }

    return $link;
  }
  function seo_categories_path($cPath_param)
  {
    global $languages_id;

    if (SEO_URL_PARTS_CATEGORIES == 'Full Categories Path')
    {
      $url = '';
      $arr = explode('_', $cPath_param);
      for ($i=0,$n=sizeof($arr);$i<$n;$i++){
        $res = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id ='" . $arr[$i] . "' and language_id = '" . (int)$languages_id . "'");
        $data = tep_db_fetch_array($res);
        if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
        {
          $url .= seo_urlencode($data['categories_name']) . '/';
        }
        else
        {
          $url .= seo_urlencode($data['categories_name']) . '.' . $data['categories_id'] . '/';
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
      $url = seo_urlencode($data['categories_name']) . '.' . $data['categories_id'] . '/';
    } // end if (SEO_URL_PARTS_CATEGORIES == 'Full Categories Path')
    return $url;
  }
  function tep_get_product_path1($products_id) {
    $cPath = '';
    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = 1 and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories1($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

  function tep_get_parent_categories1(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "' and categories_status = 1 ");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories1($categories, $parent_categories['parent_id']);
      }
    }
  }
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
    case 'Standard URL Encode (%XX)': default:
      $str = str_replace('&', urlencode(urlencode('&')), $str);
      $str = str_replace('#', urlencode(urlencode('#')), $str);
      $str = str_replace('/', urlencode(urlencode('/')), $str);
      $str = str_replace('+', urlencode(urlencode('+')), $str);
      return urlencode(strlen($str) > 0 ? $str : ' ');
    }
  }
?>