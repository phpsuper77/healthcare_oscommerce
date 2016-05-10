<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
/*
  $Id: application_top.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
  if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    date_default_timezone_set('Europe/London');
  } else {
    error_reporting(E_ALL & ~E_NOTICE);
  }

// MySQL error
  $mysql_errors = array(); 
  
function do_404() {
  	header('HTTP/1.0 404 Not Found');
	$fp = @fopen( 'http://www.healthcare4all.co.uk/information.php?info_id=16', 'r' ) or die( 'Heh.' ); 
		while ( $line = @fgets( $fp, 1024 ) ) { 
			print $line; 
		} 
	fclose( $fp );	  	
	die;
}

//============================ error log =============================
function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars){
   // timestamp for the error entry
   $dt = date("Y-m-d H:i:s (T)");
   $errortype = array (
               E_ERROR              => 'Error',
               E_WARNING            => 'Warning',
               E_PARSE              => 'Parsing Error',
               E_NOTICE             => 'Notice',
               E_CORE_ERROR         => 'Core Error',
               E_CORE_WARNING       => 'Core Warning',
               E_COMPILE_ERROR      => 'Compile Error',
               E_COMPILE_WARNING    => 'Compile Warning',
               E_USER_ERROR         => 'User Error',
               E_USER_WARNING       => 'User Warning',
               E_USER_NOTICE        => 'User Notice',
               E_STRICT             => 'Runtime Notice',
               E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
               );
   // set of errors for which a var trace will be saved
   $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
   
   $err = "<errorentry>\n";
   $err .= "\t<datetime>" . $dt . "</datetime>\n";
   $err .= "\t<errornum>" . $errno . "</errornum>\n";
   $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
   $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
   $err .= "\t<scriptname>" . $filename . "</scriptname>\n";
   $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";

   if (in_array($errno, $user_errors)) {
       $err .= "\t<vartrace>" . var_export($vars,true) . "</vartrace>\n";
   }
   $err .= "</errorentry>\n\n<br>";
   echo $err; 
}
//$old_error_handler = set_error_handler("userErrorHandler");
//\=========================== error log =============================
// check if register_globals is enabled.
// since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
  if (function_exists('ini_get')) {
    //ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
	if (ini_get('register_globals') == "") {
		foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $_SG) {
			foreach ($$_SG as $_SGK => $_SGV) {
				$$_SGK = $_SGV;
			}
		}  
	}
	
  }
  

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters
  require('includes/configure.php');

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osc 2.2 ms2 Holbi Trueloaded');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  if (isset($_SERVER['SCRIPT_NAME']) || isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $HTTP_SERVER_VARS['SCRIPT_NAME']);
  if (!isset($PHP_SELF)) $PHP_SELF = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $HTTP_SERVER_VARS['PHP_SELF']);

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// include the list of project filenames
  define('ONE_PAGE_CHECKOUT', 'True');
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters

  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if ($configuration['cfgKey'] == 'STORE_NAME'){
      $store_name = $configuration['cfgValue'];
    }else if ($configuration['cfgKey'] == 'STORE_OWNER'){
      $store_owner = $configuration['cfgValue'];
    } else if ($configuration['cfgKey'] == 'EMAIL_FROM'){
      $email_from = $configuration['cfgValue'];
    } else {
      define($configuration['cfgKey'], $configuration['cfgValue']);
    }
  }
  if (!defined("DEFAULT_USER_GROUP")){
    define("DEFAULT_USER_GROUP", 0);
  }
  
  if (DOWN_FOR_MAINTENANCE == 'true' ) {
    $maintenance_on_at_time_raw = tep_db_query("select last_modified from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'DOWN_FOR_MAINTENANCE'");
    $maintenance_on_at_time= tep_db_fetch_array($maintenance_on_at_time_raw);
    define('TEXT_DATE_TIME', $maintenance_on_at_time['last_modified']);
  }

// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION >= '4.0.4') {
        ob_start('ob_gzhandler');
      } else {
        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
        ob_start();
        ob_implicit_flush();
      }
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }
  $tax_rates_array = array();

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');
/*
  if ($request_type == 'NONSSL' && $_SERVER['SERVER_NAME'] != str_replace('http://', '', HTTP_SERVER)){
    tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params()));
  }
*/
if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && (SEARCH_ENGINE_UNHIDE == 'True'))
{
  function seo_urldecode($str)
  {
    $str = str_replace(urlencode('&'), '&', $str);
    $str = str_replace(urlencode('#'), '#', $str);
    $str = str_replace(urlencode('/'), '/', $str);
    $str = str_replace(urlencode('+'), '+', $str);
    //$str = str_replace(urlencode('?'), '?', $str);

    //$str = str_replace('-', ' ', $str);

    return trim($str);
  }

  function seo_get_cPath($categories_path)
  {
    $cPath = '';
    if (SEO_URL_PARTS_CATEGORIES == 'Full Categories Path')
    {
      $parent_id = 0;
      foreach (split ('/', $categories_path) as $cat_name)
      {
        if (tep_not_null($cat_name))
        {
          if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
          {
            $cat_name = seo_urldecode($cat_name);
            $res = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c where c.categories_id = cd.categories_id and cd.categories_name = '" . tep_db_input($cat_name) . "' and c.parent_id = '" . (int)$parent_id . "' and c.categories_status = 1 group by c.categories_id");
            if ($data = tep_db_fetch_array($res))
            {
              $cPath .= '_' . $data['categories_id'];
              $parent_id = $data['categories_id'];
            }
            else break;
          }
          elseif (preg_match("(^(.*)\.(\d+)$)", $cat_name, $regs))
          {
            $res = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$regs[2] . "' and parent_id = '" . (int)$parent_id . "' and categories_status = 1");
            if ($data = tep_db_fetch_array($res))
            {
              $cPath .= '_' . $data['categories_id'];
              $parent_id = $data['categories_id'];
            }
            else break;
          }
        }
      }
      $cPath = substr($cPath, 1);
// {{
      if (count(split('/', $categories_path)) != count(split('_', $cPath)))
      {
        $arr = split('/', $categories_path);
        if (count($arr)-1 != count(split('_', $cPath)) || tep_not_null($arr[count($arr)-1]))
        { 
			do_404();  
          tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
          //header("HTTP/1.0 404 Not Found");
          exit;
        }
      }
// }}
    }
    elseif (preg_match("(^(.*)\.(\d+)/?$)", $categories_path, $regs))
    {
      $cat_id = $regs[2];
      $categories = array();
      tep_get_parent_categories($categories, $cat_id);
      $categories = array_reverse($categories);
      $cPath = implode('_', $categories);
      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $cat_id;
    }
    return $cPath;
  }
  
  	
	/* Star: Do we need to 301 this url ? - Musaffar Patel */
	//print "<pre>";
	//print_r($_GET);
	//die;
	
	$page_url = trim($_SERVER['REQUEST_URI'], '/');
	$sql = "SELECT * FROM products_redirects WHERE old_url LIKE '".$page_url."' LIMIT 1";
	$redirect = tep_db_fetch_array(tep_db_query($sql));	
	
	if ($redirect['new_url'] != '' && $redirect['old_url'] != $redirect['new_url']) {
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://".$_SERVER["SERVER_NAME"]."/".$redirect['new_url']); 					
		die;
	}
	/* End: Do we need to 301 this url ? - Musaffar Patel */
  
  	/* Start : 301 redirect old url's with categories in url to new url format */
	if ($_GET['product_def']) {
	    $arr = split('/', $_SERVER["REQUEST_URI"]);
    	$arr = array_reverse($arr);		
		$products_name =  str_replace('.html', '', $arr[0]);
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: http://".$_SERVER["SERVER_NAME"]."/".$products_name); 		
	}
	/* End : 301 redirect old url's with categories in url to new url format */
	if ($HTTP_GET_VARS['cPath_name'] != "") {
		$PHP_SELF = "/index.php";
		$product_canonical =  strtolower("http://".$_SERVER["SERVER_NAME"]."/".urlencode($_GET['cPath_name']));		
		
		/* If this is category url, simply use the current url in lowercase for the canonical tag */
		if (strpos($_SERVER['REQUEST_URI'], '.htm') > 0) {
			$product_canonical = "http://".$_SERVER["SERVER_NAME"].strtolower($_SERVER['REQUEST_URI']);
		}
	}	
 
	$show_direct_link = false; 
  if(basename($PHP_SELF) == FILENAME_DEFAULT && !isset($HTTP_GET_VARS['cPath']))
  {
		$cPath_name = tep_db_prepare_input($HTTP_GET_VARS['cPath_name']);

		if(tep_not_null($cPath_name))
		{           
		 $query_product_direct = tep_db_fetch_array(tep_db_query("select products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where direct_url = '" . addslashes(utf8_decode($cPath_name)) . "' order by products_id desc limit 1"));                                        
		 if($query_product_direct['products_id'] > 0)
		 {       
		   $show_direct_link = true; $HTTP_GET_VARS['products_id'] = $query_product_direct['products_id'];
			 $PHP_SELF = FILENAME_PRODUCT_INFO;      
			 $_SERVER['PHP_SELF'] = ($request_type == 'SSL' ? DIR_WS_HTTPS_CATALOG . FILENAME_PRODUCT_INFO : DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO );

		 } else {
			/* If a direct_url does not match the product url then match the product name to the product url */
			$products_name = seo_urldecode($cPath_name);
			$query_product_direct = tep_db_fetch_array(tep_db_query("select p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where pd.products_id = p.products_id and p.products_status = 1 and p.products_id = p2c.products_id and if(length(p.products_seo_page_name) > 0, p.products_seo_page_name, pd.products_name) = '" . tep_db_input($products_name) . "' " . $add_query));                                        		
			if ($query_product_direct['products_id'] != "")	$show_direct_link = true; 
			$HTTP_GET_VARS['products_id'] = $query_product_direct['products_id'];
		 }
		 
		 $query_category_direct = tep_db_fetch_array(tep_db_query("select categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " where direct_url = '" . addslashes(utf8_decode($cPath_name)) . "' order by categories_id desc limit 1"));                                      
		 if($query_category_direct['categories_id'] > 0)
		 {       
		   $p = array();
		   tep_get_parent_categories($p, (int)$query_category_direct['categories_id']);
			 if(count($p)>0)
			 {
			  $p = array_reverse($p); reset($p);
			  $cPath = implode("_", $p) . "_" . $query_category_direct['categories_id'];
			 } 
		   else
		   {
			  $cPath = $query_category_direct['categories_id'];
			 } 
		   $HTTP_GET_VARS['cPath'] = $cPath;
		   $show_cat_direct_link = true;   		   
		 }		 
		}
  }
  
	if ($product_canonical == "" && $_GET['products_id'] != "") {
		$productID = $_GET['products_id'];
		$query = "SELECT direct_url, products_name FROM products_description WHERE products_ID = $productID";
		$product_url_info = tep_db_fetch_array(tep_db_query($query));
		  
		if ($product_url_info['direct_url'] == "") {
			$product_canonical = strtolower("http://".$_SERVER["SERVER_NAME"]."/".urlencode($product_url_info['products_name']));
		} else {
			  $product_canonical = strtolower($product_url_info['direct_url']);
		}
	};

	if ($_SERVER["REMOTE_ADDR"] == "82.27.84.26") {
		die;
	}


// {{{



  // SEO Manufacturers
  function seo_get_manufacturers($manufacturers_name) {
      if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
      {                                                         // && SEO_URL_PARTS_ID == 'False'
       $manufacturer_query = tep_db_query("select * from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_seo_name = '" . tep_db_input(urldecode($manufacturers_name)) ."'");
	    if (tep_db_num_rows($manufacturer_query)) {
	      $manufacturer_data = tep_db_fetch_array($manufacturer_query);
	      return $manufacturer_data['manufacturers_id'];
	    }

	    $manufacturer_query = tep_db_query("select * from " . TABLE_MANUFACTURERS . " where manufacturers_name = '" . tep_db_input(urldecode($manufacturers_name)) ."'");
	    if (tep_db_num_rows($manufacturer_query)) {
	      $manufacturer_data = tep_db_fetch_array($manufacturer_query);
	      return $manufacturer_data['manufacturers_id'];
	    } else {
	      return 0;
	    }
      }
      elseif (preg_match("(^(.*)\.(\d+)$)", $manufacturers_name, $regs))
      {
        $res = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$regs[2] . "' and parent_id = '" . (int)$parent_id . "' and categories_status = 1");

      $manufacturer_query = tep_db_query("select * from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_seo_name = '" . $regs[2] ."'");
	    if (tep_db_num_rows($manufacturer_query)) {
	      $manufacturer_data = tep_db_fetch_array($manufacturer_query);
	      return $manufacturer_data['manufacturers_id'];
	    }

	    $manufacturer_query = tep_db_query("select * from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $regs[2] ."'");
	    if (tep_db_num_rows($manufacturer_query)) {
	      $manufacturer_data = tep_db_fetch_array($manufacturer_query);
	      return $manufacturer_data['manufacturers_id'];
	    } else {
	      return 0;
	    }
      }
  }

	if (basename($PHP_SELF) == FILENAME_DEFAULT && strlen($HTTP_GET_VARS['manufacturers_name']) > 0)
  {

    if (get_magic_quotes_gpc()) $HTTP_GET_VARS['manufacturers_name'] = stripslashes($HTTP_GET_VARS['manufacturers_name']);
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['manufacturers_name'], 0, 2);
      $HTTP_GET_VARS['manufacturers_name'] = substr($HTTP_GET_VARS['manufacturers_name'], 3);
    }
    $HTTP_GET_VARS['manufacturers_name'] = substr($HTTP_GET_VARS['manufacturers_name'], 6); // remove 'info/'
    $manufacturers_id = $HTTP_GET_VARS['manufacturers_id'] = seo_get_manufacturers($HTTP_GET_VARS['manufacturers_name']);
    unset($HTTP_GET_VARS['manufacturers_name']);

    if ($cPath == '') {  
		do_404();
      tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
      //header("HTTP/1.0 404 Not Found");
    }
  }
  // eof SEO Manufacturers


  if (basename($PHP_SELF) == FILENAME_INFORMATION && strlen($HTTP_GET_VARS['info_name']) > 0)
  {
    if (preg_match("(^(.*)\.(\d+)$)", $HTTP_GET_VARS['info_name'], $regs))
    {
      $res = tep_db_query("select information_id from " . TABLE_INFORMATION . " where information_id = '" . (int)$regs[2] . "' and visible = 1 group by information_id");
      if ($data = tep_db_fetch_array($res))
      {
        $info_id = $HTTP_GET_VARS['info_id'] = $data['information_id'];
      }
    }
    elseif (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
    {
      if (get_magic_quotes_gpc()) $HTTP_GET_VARS['info_name'] = stripslashes($HTTP_GET_VARS['info_name']);
      if (SEO_URL_PARTS_LANGUAGE == 'True')
      {
        $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['info_name'], 0, 2);
        $HTTP_GET_VARS['info_name'] = substr($HTTP_GET_VARS['info_name'], 3);
      }
      $HTTP_GET_VARS['info_name'] = substr($HTTP_GET_VARS['info_name'], 5); // remove 'info/'
      $info_name = seo_urldecode($HTTP_GET_VARS['info_name']);
      $res = tep_db_query("select information_id from " . TABLE_INFORMATION . " where info_title = '" . tep_db_input($info_name) . "' and visible = 1 group by information_id");
      if ($data = tep_db_fetch_array($res))
      {
        $info_id = $HTTP_GET_VARS['info_id'] = $data['information_id'];
      }
    }
    if ( !($info_id > 0) ) {
		do_404();
      tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
      //header("HTTP/1.0 404 Not Found");
      exit;
    }
    unset($HTTP_GET_VARS['info_name']);
  }



    // Newsdesk
  include('includes/application_top_newsdesk.php');
  if (basename($PHP_SELF) == 'newsdesk_info.php' && strlen($HTTP_GET_VARS['newsdesk_info']) > 0)
  {
    if (get_magic_quotes_gpc()) $HTTP_GET_VARS['newsdesk_info'] = stripslashes($HTTP_GET_VARS['newsdesk_info']);
//    $HTTP_GET_VARS['newsdesk_info'] = str_replace('_', ' ', $HTTP_GET_VARS['newsdesk_info']);
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['newsdesk_info'], 0, 6);
      $HTTP_GET_VARS['newsdesk_info'] = substr($HTTP_GET_VARS['newsdesk_info'], 7);
    }
    $HTTP_GET_VARS['newsdesk_info'] = substr($HTTP_GET_VARS['newsdesk_info'], 9); // remove 'newsdesk/'

    if (preg_match("(^(.*)\.(\d+)$)", $HTTP_GET_VARS['newsdesk_info'], $regs))
    {
      $res = tep_db_query("select newsdesk_id from " . TABLE_NEWSDESK_DESCRIPTION . " where newsdesk_id = '" . (int)$regs[2] . "'");
      if ($data = tep_db_fetch_array($res))
      {
        $HTTP_GET_VARS['newsdesk_id'] = $data['newsdesk_id'];
      }
    }
    if (!isset($HTTP_GET_VARS['newsdesk_id']) && (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)'))
    {
      $HTTP_GET_VARS['newsdesk_info'] = seo_urldecode($HTTP_GET_VARS['newsdesk_info']);
      $res = tep_db_query("select p.newsdesk_id from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = pd.newsdesk_id and pd.newsdesk_article_name = '" . $HTTP_GET_VARS['newsdesk_info'] . "'");
      if ($data = tep_db_fetch_array($res))
      {
        $HTTP_GET_VARS['newsdesk_id'] = $data['newsdesk_id'];
      }
    }
    unset($HTTP_GET_VARS['newsdesk_info']);
  }
  // eof Newsdesk


	// Faqdesk
  include('includes/application_top_faqdesk.php');
  if (basename($PHP_SELF) == 'faqdesk_info.php' && strlen($HTTP_GET_VARS['faqdesk_info']) > 0)
  {
    if (get_magic_quotes_gpc()) $HTTP_GET_VARS['faqdesk_info'] = stripslashes($HTTP_GET_VARS['faqdesk_info']);
//    $HTTP_GET_VARS['faqdesk_id'] = str_replace('_', ' ', $HTTP_GET_VARS['faqdesk_id']);
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['faqdesk_info'], 0, 6);
      $HTTP_GET_VARS['faqdesk_id'] = substr($HTTP_GET_VARS['faqdesk_info'], 7);
    }
    $HTTP_GET_VARS['faqdesk_info'] = substr($HTTP_GET_VARS['faqdesk_info'], 8); // remove 'faqdesk/'

    if (preg_match("(^(.*)\.(\d+)$)", $HTTP_GET_VARS['faqdesk_info'], $regs))
    {
      $res = tep_db_query("select faqdesk_id from " . TABLE_FAQDESK_DESCRIPTION . " where faqdesk_id = '" . (int)$regs[2] . "'");
      if ($data = tep_db_fetch_array($res))
      {
        $HTTP_GET_VARS['faqdesk_id'] = $data['faqdesk_id'];
      }
    }
    if (!isset($HTTP_GET_VARS['faqdesk_id']) && (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)'))
    {
      $HTTP_GET_VARS['faqdesk_info'] = seo_urldecode($HTTP_GET_VARS['faqdesk_info']);
      $res = tep_db_query("select p.faqdesk_id from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = pd.faqdesk_id and pd.faqdesk_question like '" . tep_db_input($HTTP_GET_VARS['faqdesk_info']) . "'");
      if ($data = tep_db_fetch_array($res))
      {
        $HTTP_GET_VARS['faqdesk_id'] = $data['faqdesk_id'];
      }
    }
    unset($HTTP_GET_VARS['faqdesk_info']);
  }
  // eof Faqdesk

   
// }}}


  if (basename($PHP_SELF) == FILENAME_DEFAULT && strlen($HTTP_GET_VARS['cPath_name']) > 0 && $show_direct_link == false && $show_cat_direct_link!=true)
  {	  
    if (get_magic_quotes_gpc()) $HTTP_GET_VARS['cPath_name'] = stripslashes($HTTP_GET_VARS['cPath_name']);
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['cPath_name'], 0, 2);
      $HTTP_GET_VARS['cPath_name'] = substr($HTTP_GET_VARS['cPath_name'], 3);
    }
    $cPath = $HTTP_GET_VARS['cPath'] = seo_get_cPath($HTTP_GET_VARS['cPath_name']);
	
    if (!tep_not_null($cPath))
    {
		
      //tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
	  do_404();
      exit;
    }
    unset($HTTP_GET_VARS['cPath_name']);
  }
  
  
  if (basename($PHP_SELF) == FILENAME_PRODUCT_INFO && strlen($HTTP_GET_VARS['product_def']) > 0)
  {
    if (get_magic_quotes_gpc()) $HTTP_GET_VARS['product_def'] = stripslashes($HTTP_GET_VARS['product_def']);
    if (SEO_URL_PARTS_LANGUAGE == 'True')
    {
      $HTTP_GET_VARS['language'] = substr($HTTP_GET_VARS['product_def'], 0, 2);
      $HTTP_GET_VARS['product_def'] = substr($HTTP_GET_VARS['product_def'], 3);
    }
	
    $add_query = '';
    $arr = split('/', $HTTP_GET_VARS['product_def']);
    $arr = array_reverse($arr);
    reset($arr);
    list(, $products_name) = each($arr);
    if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
    {
      $products_name = seo_urldecode($products_name);
    }
	
    if (SEO_URL_PARTS_MODEL == 'True')
    {
      list(, $products_model) = each($arr);
      if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)')
      {
        $products_model = seo_urldecode($products_model);
        $add_query .= " and p.products_model = '" . tep_db_input($products_model) . "' ";
      }
    }
	
    if (SEO_URL_PARTS_MANUFACTURER == 'True')
    {
      list(, $products_manufacturer) = each($arr);
      if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
      {
        $products_manufacturer = seo_urldecode($products_manufacturer);
        $manufacturers_query = tep_db_query("select manufacturers_id from " . TABLE_MANUFACTURERS . " where manufacturers_name = '" . tep_db_input($products_manufacturer) . "'");
        $manufacturers = tep_db_fetch_array($manufacturers_query);
        $manufacturers_id = $manufacturers['manufacturers_id'];
      }
      else
      {
        preg_match("(^(.*)\.(\d+)$)", $products_manufacturer, $regs);
        $manufacturers_id = $regs[2];
      }
      $add_query .= " and p.manufacturers_id = '" . (int)$manufacturers_id . "' ";
    }
	
    $categories_path = '';
    while ( list(, $products_cats) = each($arr) )
    {
      $categories_path = $products_cats . '/' . $categories_path;
    }	
    $cPath = $HTTP_GET_VARS['cPath'] = seo_get_cPath($categories_path);	
    if (tep_not_null($cPath))
    {
      $cPath_ar = tep_parse_category_path($cPath);
      $cPath = implode('_', $cPath_ar);
      $products_category_id = $cPath_ar[(sizeof($cPath_ar)-1)];
    }	
    else
    {
      $products_category_id = 0;
    }	
    if (SEO_URL_ENCODING_METHOD == 'Standard URL Encode (%XX)' && SEO_URL_PARTS_ID == 'False')
    {
      $products_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where pd.products_id = p.products_id and p.products_status = 1 and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$products_category_id . "' and if(length(p.products_seo_page_name) > 0, p.products_seo_page_name, pd.products_name) = '" . tep_db_input($products_name) . "' " . $add_query);
    }
    else
    {
      preg_match("(^(.*)\.(\d+)$)", $products_name, $regs);
      $products_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where pd.products_id = p.products_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$products_category_id . "' and pd.products_id = '" . (int)$regs[2] . "' " . $add_query);
    }	
    if ($products_data = tep_db_fetch_array($products_query))
    {
      unset($HTTP_GET_VARS['product_def']);
      $products_id = $HTTP_GET_VARS['products_id'] = $products_data['products_id'];
    }
    else
    {   
      //tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
      //header("HTTP/1.0 404 Not Found");
	  do_404();
      exit;
      //tep_redirect(tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'search_in_description=1&inc_subcat=1&keywords=' . urlencode($products_name)));
    }	
  }  
}
else
{	
  if (basename($PHP_SELF) == FILENAME_DEFAULT && strlen($HTTP_GET_VARS['cPath_name']) > 0)
  {   
  	do_404();
    tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
    //header("HTTP/1.0 404 Not Found");
    exit;
  }
  if (basename($PHP_SELF) == FILENAME_PRODUCT_INFO && strlen($HTTP_GET_VARS['product_def']) > 0)
  {  
	  do_404();
    tep_redirect(tep_href_link(FILENAME_INFORMATION, 'info_id=16'));
    //header("HTTP/1.0 404 Not Found");
    exit;
  }
}  
// }}

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');
// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// check if sessions are supported, otherwise use the php3 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCsid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCsid');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
  if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

// set the session ID if it exists
   if (isset($HTTP_POST_VARS[tep_session_name()])) {
     tep_session_id($HTTP_POST_VARS[tep_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[tep_session_name()]) ) {
     tep_session_id($HTTP_GET_VARS[tep_session_name()]);
   }
   

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
      tep_session_start();
      if (!tep_session_is_registered('referer_url')) { 
        $referer_url = $HTTP_SERVER_VARS['HTTP_REFERER']; 
        if ($referer_url) { 
          tep_session_register('referer_url'); 
        } 
      }
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      tep_session_start();
      if (!tep_session_is_registered('referer_url')) { 
        $referer_url = $HTTP_SERVER_VARS['HTTP_REFERER']; 
        if ($referer_url) { 
          tep_session_register('referer_url'); 
        } 
      }
      $session_started = true;
    }
  } else {
    tep_session_start();
    if (!tep_session_is_registered('referer_url')) { 
      $referer_url = $HTTP_SERVER_VARS['HTTP_REFERER']; 
      if ($referer_url) { 
        tep_session_register('referer_url'); 
      } 
    }
    $session_started = true;
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

  if (!tep_session_is_registered('customer_groups_id')){
    $customer_groups_id = DEFAULT_USER_GROUP;
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }
  
// vat exemption addon
  if(isset($HTTP_POST_VARS['vat_exemption_change']))
  {
     //if(is_array($HTTP_POST_VARS['vat_exemption_change']))
     //{
       if(!tep_session_is_registered('vat_exemption_arr'))
       tep_session_register('vat_exemption_arr');
       
       foreach($HTTP_POST_VARS['vat_exemption_change'] as $key => $value)
       { 
         $vat_exemption_arr[$key] = ((int)$value>0?1:'');
       }
       //echo '<pre>'; print_r($vat_exemption_arr); die;
     //}
     //tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }   
// end vat exemption addon  

// create the shopping cart & fix the cart if necesary
  if (tep_session_is_registered('cart') && is_object($cart)) {
    if (PHP_VERSION < 4) {
      $broken_cart = $cart;
      $cart = new shoppingCart;
      $cart->unserialize($broken_cart);
    }
  } else {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }

  if (SEARCH_ENGINE_STATS == 'True'){
    referer_stat();
  }

// mysql error
  if(!tep_session_is_registered('mysql_error_dump')) {
    $mysql_error_dump = array();
    tep_session_register('mysql_error_dump');
    if(count($mysql_errors) > 0) {
      $mysql_error_dump = $mysql_errors;
    }
  }
  else {
    if( count($mysql_errors) > 0) {
      if(count($mysql_error_dump == 0)) {
        $mysql_error_dump = $mysql_errors;
      }
      else {
        $mysql_error_dump = array_merge($mysql_error_dump, $mysql_errors);
      }
    }
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  if (!tep_session_is_registered('language') || isset($HTTP_GET_VARS['language'])) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($HTTP_GET_VARS['language']) && tep_not_null($HTTP_GET_VARS['language'])) {
      $lng->set_language($HTTP_GET_VARS['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }else{
    $language = $HTTP_SESSION_VARS['language'];
    $languages_id = $HTTP_SESSION_VARS['languages_id'];
  }


  // include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

  require(DIR_WS_CLASSES . 'breadcrumb.php');
  $breadcrumb = new breadcrumb;
  
  require(DIR_WS_INCLUDES . 'affiliate_application_top.php');
  
// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');

// currency
  if (tep_session_is_registered('currency'))
  {
    $currency = $HTTP_SESSION_VARS['currency'];
  }
  if (tep_session_is_registered('currency'))
  {
    $currency_id = $HTTP_SESSION_VARS['currency_id'];
  }  
  if (!tep_session_is_registered('currency') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');

    if (isset($HTTP_GET_VARS['currency'])) {
      if (!$currency = tep_currency_exists($HTTP_GET_VARS['currency'])) $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }
//currency_id
  if (!tep_session_is_registered('currency_id') || isset($HTTP_GET_VARS['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) )){
    if (!tep_session_is_registered('currency_id')) tep_session_register('currency_id');
    $currency_id = $currencies->currencies[$currency]['id'];
  }

// navigation history
  if (tep_session_is_registered('navigation')) {
    if (PHP_VERSION < 4) {
      $broken_navigation = $navigation;
      $navigation = new navigationHistory;
      $navigation->unserialize($broken_navigation);
    }
    $navigation = $HTTP_SESSION_VARS['navigation'];
  } else {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
  }
  $navigation->add_current_page();

// BOF: Down for Maintenance except for admin ip
if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR')){
  if (DOWN_FOR_MAINTENANCE == 'true' and !strstr($PHP_SELF, DOWN_FOR_MAINTENANCE_FILENAME)) { 
    tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME)); 
  }
}
// do not let people get to down for maintenance page if not turned on
if (DOWN_FOR_MAINTENANCE=='false' and strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}
// EOF: WebMakers.com Added: Down for Maintenance


// Shopping cart actions
  if (isset($HTTP_GET_VARS['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
    } else {
      $goto = basename($PHP_SELF);
      if ($HTTP_GET_VARS['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    if ($action == 'add_product' && ($HTTP_POST_VARS['add_to_whishlist_x'] || $HTTP_POST_VARS['add_to_whishlist_y'])){
      $HTTP_GET_VARS['action'] = 'add_wishlist';
      $action = 'add_wishlist';
    }
    switch ($HTTP_GET_VARS['action']) {
// {{
      case 'add_giveaway':
        if (isset($HTTP_GET_VARS['product_id']) && is_numeric($HTTP_GET_VARS['product_id'])) {
          $cart->add_cart($HTTP_GET_VARS['product_id'], $cart->get_quantity($HTTP_GET_VARS['product_id'], 1)+$_POST['qty'], '', true, 1);
        }
        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
      break;
      case 'remove_giveaway':
        if (isset($HTTP_GET_VARS['product_id']) && is_numeric($HTTP_GET_VARS['product_id'])) {
          $cart->remove_giveaway($HTTP_GET_VARS['product_id']);
        }
        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
      break;
// }}
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($HTTP_POST_VARS['products_id']); $i<$n; $i++) {
// {{
                                if ($HTTP_POST_VARS['ga'][$i]) continue; // GA are not processed
// }}
                                if (in_array($HTTP_POST_VARS['products_id'][$i], (is_array($HTTP_POST_VARS['cart_delete']) ? $HTTP_POST_VARS['cart_delete'] : array()))) {
                                  $cart->remove($HTTP_POST_VARS['products_id'][$i]);
                                } else {
                                  if (PHP_VERSION < 4) {
                                    // if PHP3, make correction for lack of multidimensional array.
                                    reset($HTTP_POST_VARS);
                                    while (list($key, $value) = each($HTTP_POST_VARS)) {
                                      if (is_array($value)) {
                                        while (list($key2, $value2) = each($value)) {
                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {
                                            $id2[$var[1]][$var[2]] = $value2;
                                          }
                                        }
                                      }
                                    }
                                    $attributes = ($id2[$HTTP_POST_VARS['products_id'][$i]]) ? $id2[$HTTP_POST_VARS['products_id'][$i]] : '';
                                  } else {
                                    $attributes = ($HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]]) ? $HTTP_POST_VARS['id'][$HTTP_POST_VARS['products_id'][$i]] : '';
                                  }
                                  $cart->add_cart($HTTP_POST_VARS['products_id'][$i], (int)$HTTP_POST_VARS['cart_quantity'][$i], $attributes, false, 0,  $vat_exemption_arr[$HTTP_POST_VARS['products_id'][$i]]);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;

      // customer adds a product from the products page
      case 'add_product' : if (isset($HTTP_POST_VARS['products_id']) && is_numeric($HTTP_POST_VARS['products_id']) && tep_check_product((int)$HTTP_POST_VARS['products_id'])) {
                   if (tep_session_is_registered('customer_id')) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=".(int)$customer_id." AND products_id=".(int)$HTTP_POST_VARS['products_id']);

                                if((int)$HTTP_POST_VARS['vat_exemption1']==0)$HTTP_POST_VARS['vat_exemption'] = 0;
                                $cart->add_cart($HTTP_POST_VARS['products_id'], $cart->get_quantity(tep_get_uprid($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['id']))+(is_numeric($HTTP_POST_VARS['qty'])?(int)$HTTP_POST_VARS['qty']:1), $HTTP_POST_VARS['id'], true, 0, ((int)$HTTP_POST_VARS['vat_exemption']>0?1:0));
                                if(!tep_session_is_registered('vat_exemption_arr'))
                                tep_session_register('vat_exemption_arr');

                                $vat_exemption_arr[$HTTP_POST_VARS['products_id']] = ((int)$HTTP_POST_VARS['vat_exemption']>0?1:'');
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
   break;
// Add product to the wishlist
///// CHANGES TO case 'add_wishlist' BY DREAMSCAPE /////
case 'add_wishlist' :  if (ereg('^[0-9]+$', $HTTP_POST_VARS['products_id'])) {
                         tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=".(int)$customer_id." AND products_id=".(int)$HTTP_POST_VARS['products_id']);
                         $pData = tep_db_fetch_array(tep_db_query("select p.products_model, if(length(pd1.products_name),pd1.products_name,pd.products_name) as products_name, p.products_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$languages_id . "' where pd.products_id = p.products_id and pd.affiliate_id=0 and pd.language_id = '" . (int)$languages_id . "' and p.products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "'"));
                         tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id, products_model, products_name, products_price) values ('" . (int)$customer_id . "', '" . (int)$products_id . "', '" . tep_db_input($pData['products_model']) . "', '" . tep_db_input($pData['products_name']) . "', '" . tep_get_products_price($products_id, 1, $pData['products_price']) . "' )");
     }
                              tep_redirect(tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params($parameters), 'NONSSL'));
                              break;
 
// Add wishlist item to the cart
case 'wishlist_add_cart':   reset ($lvnr);
                            reset ($lvanz);
                                 while (list($key,$elem) =each ($lvnr))
                                       {
                                        (list($key1,$elem1) =each ($lvanz));
                                        tep_db_query("update " . TABLE_WISHLIST . " SET products_quantity='".(int)$elem1."' WHERE customers_id='".(int)$customer_id."' AND products_id='".(int)$elem."'");
                                        tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_quantity='999'");
                                        $produkte_mit_anzahl=tep_db_query("select * from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_id='".(int)$elem."' AND products_quantity<>'0'");

                                        while ($HTTP_POST_VARS=tep_db_fetch_array($produkte_mit_anzahl))
                                              {
                                               $cart->add_cart($HTTP_POST_VARS['products_id'], $HTTP_POST_VARS['products_quantity']);
                                               }
                                        }
                                  reset ($lvanz);
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
                              break;


// remove item from the wishlist
///// CHANGES TO case 'remove_wishlisy' BY DREAMSCAPE /////
      case 'remove_wishlist' :
                             tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_id='".(int)$pid."'");
                             tep_redirect(tep_href_link(FILENAME_WISHLIST, tep_get_all_get_params(array('action', 'pid')), 'NONSSL'));
                             break;



      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($HTTP_GET_VARS['products_id']) && tep_check_product((int)$HTTP_GET_VARS['products_id'])) {
        if (tep_session_is_registered('customer_id')) { tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_id=" . (int)$products_id); }
                                if (tep_has_product_attributes($HTTP_GET_VARS['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['products_id']. '&qty=' . (is_numeric($HTTP_GET_VARS['qty']) ? $HTTP_GET_VARS['qty']:1)) );
                                } else {
                                  //$cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id'])+(is_numeric($HTTP_GET_VARS['qty'])?(int)$HTTP_GET_VARS['qty']:1));
                                  
                                  // VAT Exemption addon
                                  $vat_exempt_default = ""; 
                                  $query_product_exemp = tep_db_query($qzzz = "select vat_exempt_flag from " . TABLE_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and vat_exempt_flag = 1");                                  
                                  if(tep_db_num_rows($query_product_exemp))
                                  {
                                   $vat_exempt_default = 1;
                                   if(!tep_session_is_registered('vat_exemption_arr'))tep_session_register('vat_exemption_arr');
                                   $vat_exemption_arr[(int)$HTTP_GET_VARS['products_id']] = 1;
                                  }
                                  
                                  $cart->add_cart($HTTP_GET_VARS['products_id'], $cart->get_quantity($HTTP_GET_VARS['products_id'])+(is_numeric($HTTP_GET_VARS['qty'])?(int)$HTTP_GET_VARS['qty']:1), '', true, 0, $vat_exempt_default);
                                  // eof VAT Exemption addon
                                  
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;

      case 'notify' :         if (tep_session_is_registered('customer_id')) {
                                if (isset($HTTP_GET_VARS['products_id'])) {
                                  $notify = $HTTP_GET_VARS['products_id'];
                                } elseif (isset($HTTP_GET_VARS['notify'])) {
                                  $notify = $HTTP_GET_VARS['notify'];
                                } elseif (isset($HTTP_POST_VARS['notify'])) {
                                  $notify = $HTTP_POST_VARS['notify'];
                                } else {
                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
                                  }
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($HTTP_GET_VARS['pid']) &&  tep_check_product((int)$HTTP_GET_VARS['pid'])) {
                                if (tep_has_product_attributes($HTTP_GET_VARS['pid'])) {
                                  tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_id='".(int)$pid."'");
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $HTTP_GET_VARS['pid'], 'NONSSL'));
                                 } else {
                                  tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id='".(int)$customer_id."' AND products_id='".(int)$pid."'");
                                  $cart->add_cart($HTTP_GET_VARS['pid'], $cart->get_quantity($HTTP_GET_VARS['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
                              break;
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  if (!defined('STDOUT')) tep_update_whos_online();


// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// Lango added for template BOF:
  require(DIR_WS_INCLUDES . 'template_application_top.php');
// Lango added for template EOF:

// auto activate and expire banners
  require(DIR_WS_FUNCTIONS . 'banner.php');
  tep_activate_banners();
  tep_expire_banners();

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();
  tep_check_selemaker();

// auto expire featured products
  require(DIR_WS_FUNCTIONS . 'featured.php');
  tep_expire_featured();

// calculate category path
  if (isset($HTTP_GET_VARS['cPath'])) {
    $cPath = $HTTP_GET_VARS['cPath'];
  } elseif (isset($HTTP_GET_VARS['products_id']) && !isset($HTTP_GET_VARS['manufacturers_id'])) {
    $cPath = tep_get_product_path($HTTP_GET_VARS['products_id']);
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
    $query = tep_db_query("select * from " . TABLE_CATEGORIES . " where categories_status = 1 and categories_id = " . (int)$current_category_id);
    if (tep_db_num_rows($query) == 0){
      $current_category_id = 0;
    }
  } else {
    $current_category_id = 0;
  }

// include the breadcrumb class and start the breadcrumb trail

//  $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  //$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

  $breadcrumb->add((isset($HTTP_GET_VARS['products_id']) && (int)$HTTP_GET_VARS['products_id']>0 && (int)$current_category_id==66?tep_get_categories_name($current_category_id) . " ":"") . HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($HTTP_GET_VARS['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id']));
    }
  }

// add the products model to the breadcrumb trail
  if (isset($HTTP_GET_VARS['products_id'])) {
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $check_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = '" . (int)$HTTP_GET_VARS['products_id']. "'");
      if (tep_db_num_rows($check_products_query)){
        $breadcrumb->add(tep_get_products_name((int)$HTTP_GET_VARS['products_id']), tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));
      }
    }else{
      $breadcrumb->add(tep_get_products_name((int)$HTTP_GET_VARS['products_id']), tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $HTTP_GET_VARS['products_id']));
    }
  }

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');
// Include OSC-AFFILIATE
  require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');

  //include('includes/application_top_newsdesk.php');
  include('includes/application_top_faqdesk.php');
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/product_listing.tpl.php');

  require(DIR_WS_FUNCTIONS . 'header_tags.php');

  //---PayPal WPP Modification START ---//
  include(DIR_WS_INCLUDES . 'paypal_wpp/paypal_wpp_include.php');
  //---PayPal WPP Modification END ---//

  if (tep_not_null($HTTP_GET_VARS['rd']) && in_array(basename($PHP_SELF), array(FILENAME_LOGIN, FILENAME_CREATE_ACCOUNT))) {
    $navigation->set_snapshot(array('mode' => 'NONSSL', 'page' =>tep_sanitize_string($HTTP_GET_VARS['rd'])));
  }  
  
?>
