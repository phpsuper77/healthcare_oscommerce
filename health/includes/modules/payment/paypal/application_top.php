<?php
/*
  $Id: application_top.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Portions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
  function debugWriteFile($str,$mode="a") {
    $fp = @fopen("ipn.txt",$mode);  @flock($fp, LOCK_EX); @fwrite($fp,$str); @flock($fp, LOCK_UN); @fclose($fp);
  }

  $postString = ''; foreach($_POST as $key => $val) $postString .= $key.' = '.$val."\n";
  if($postString != '') {
    debugWriteFile($postString,"w+");
  }
*/

  // set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);
  //error_reporting(0);

  // check if register_globals is enabled.
  // since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.

  // Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) {

    require_once('includes/local/configure.php');

  }

  // include server parameters
  require_once('includes/configure.php');
  require_once(dirname(__FILE__) . '/configure.php');

  // define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');

  // set php_self in the local scope
  if (isset($PHP_SELF) === false)
    $PHP_SELF = $_SERVER['PHP_SELF'];

  // include the list of project filenames
  require_once(DIR_WS_INCLUDES . 'filenames.php');
  require_once(dirname(__FILE__) . '/filenames.php');

  // include the list of project database tables
  require_once(DIR_WS_INCLUDES . 'database_tables.php');
  require_once(dirname(__FILE__) . '/database_tables.php');

  // include the database functions
  require_once(DIR_WS_FUNCTIONS . 'database.php');

  // make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

  // set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query))
    define($configuration['cfgKey'], $configuration['cfgValue']);

  // define general functions used application-wide
  require_once(DIR_WS_FUNCTIONS . 'general.php');
  require_once(DIR_WS_FUNCTIONS . 'html_output.php');

  // some code to solve compatibility issues
  require_once(DIR_WS_FUNCTIONS . 'compatibility.php');

  // define how the session functions will be used
  require_once(DIR_WS_FUNCTIONS . 'sessions.php');

  // include currencies class and create an instance
  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // include the mail classes
  require_once(DIR_WS_CLASSES . 'mime.php');
  require_once(DIR_WS_CLASSES . 'email.php');

  require_once(DIR_WS_MODULES . 'payment/paypal.php');

  $paypal = paypal::newCheckout();
  $paypal->loadFromSessionByTransactionSignature($_POST['custom']);

  if (isset($paypal->vars['language']) === true) {

    // include the language translations
    $language = $paypal->vars['language'];

    $languages_id = $paypal->vars['language_id'];

    require_once(DIR_WS_LANGUAGES . $paypal->vars['language'] . '.php');

  } else {

    require_once(dirname(__FILE__) . '/languages/english.php');

  }
?>