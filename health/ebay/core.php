<?php
define('EBAY_DIR_EBATLIB', dirname(__FILE__).'/EbatNs' );
set_include_path(get_include_path() . PATH_SEPARATOR . EBAY_DIR_EBATLIB);

if ( (!empty($_SERVER['HOSTNAME']) && preg_match('/dragon$/', $_SERVER['HOSTNAME'])) ||
     (!empty($_SERVER['HTTP_HOST']) && preg_match('/dragon$/', $_SERVER['HTTP_HOST'])) ) {
  if (function_exists('date_default_timezone_set')) date_default_timezone_set('Europe/Kiev');
  define('DEV_SERVER',true);
}else{
  if (function_exists('date_default_timezone_set')) date_default_timezone_set('Europe/London');
  define('DEV_SERVER',false);
}

require( dirname(__FILE__) . '/ebay_core_class.php' );
require( dirname(__FILE__) . '/ebay_classes.php' );
require( dirname(__FILE__) . '/ebay_functions.php' );
//require( dirname(__FILE__) . '/ebay_logger.php' );
//require( dirname(__FILE__) . '/ebay_session.php' );
require( dirname(__FILE__) . '/ebay_registry.php' );
require( dirname(__FILE__) . '/ebay_categories.php' );
//require( dirname(__FILE__) . '/ebay_products.php' );

//require( dirname(__FILE__) . '/ebay_transaction.php' );
//require( dirname(__FILE__) . '/paypal_bridge.php' );
require( dirname(__FILE__) . '/scheduler.php' );
require( dirname(__FILE__) . '/admin_ui/order_info.php');
require( dirname(__FILE__) . '/admin_ui/cfg_func.php');
require( dirname(__FILE__) . '/ebay_details.php');


class amp3_ebay_config_sandbox{
  function getEbaySiteID() { return 3; } // readme.php #1
  function getEbayMode() { return 1; } // 1 = sandbox
  function getCompatibilityLevel() { return 445; }
  function getAppID() { return ''; }
  function getDevID() { return ''; }
  function getCertID() { return ''; }
  function getToken() {
    return '';
  }

  function getMainCategories(){
    return array(2984);
  }

  function defaultCurrency(){ return DEFAULT_CURRENCY; }
  function defaultCountry(){ return STORE_COUNTRY; }
  function payment( $type=false ){
    $ret = array('payment_class' => 'paypal_ebay',
                 'payment_method' => 'Ebay Paypal'); 
    if ( $type=='class' ) $ret = $ret['payment_class'];
    if ( $type=='method' ) $ret = $ret['payment_method'];
    return $ret;
  }
  function getPaypalConfig(){
    return array('mode' => 'Sandbox',
                 'api' => 'Certificate',
                 'paypal_username' => '',
                 'paypal_password' => '',
                 'certificate_path' => dirname(__FILE__).'/cert_key_pem.txt'
                );
  }
  function forceFreeShipping(){ return true; }
  // create customer account & address book
  function orderImportCreateCustomers() { return false; }
  // if orderImportCreateCustomers()==false use this customers_id in order
  function orderImportCustomerID() { return 0; }
  function orderImportDefaultStatus() { return 2; }
  function orderShippedStatus() { return 3; }
  function setFeedbackWithPaid() { return false; }
  function getProductImageOrigin(){ return 'http://www.healthcare4all.co.uk/images/'; }
  function initEvilSide(){
    define('EBAY_CONNECTOR_ID', '3');
    define('EBAY_TAX_RATE', '15.00');
    define('EBAY_TAX_IN_PRICE', 'true');
    define('EBAY_PRODUCT_LINK_TYPE', 'uprid'); // inventory enabled catalog connect by uprid, w/o direct to product
    define('TABLE_EBAY_CATEGORIES', 'ebay_categories');
    //define('TABLE_EBAY_PRODUCTS', 'ebay_products');
    define('TABLE_EBAY_REGISTRY', 'ebay_registry');
    define('TABLE_EBAY_ORDERS', 'ebay_orders');
    define('TABLE_EBAY_PAYPAL', 'ebay_paypal');
    define('TABLE_EBAY_PRODUCTS_LIST', 'ebay_products_list');
    define('TABLE_EBAY_LOG', 'ebay_log');
  }

  function getPaypalEmail(){
    return '';
  }
  function getItemLocation() { return EBAY_UK_ITEM_LOCATION; }
  function getDefaultFeedbackText(){ return EBAY_UK_DEFAULT_FEEDBACK_TEXT; }
  function getDefaultListingDuration(){ return EBAY_UK_DEFAULT_LISTINGDURATION; }
  function getDescriptionTemplate() { return 'default.html'; }
  function getReturnsProfile(){
    $ret = array('accept'=>'ReturnsNotAccepted',
                 'refund' => '',
                 'within' => '',
                 'description' => EBAY_UK_DEFAULT_RETURNS_DESCRIPTION,
                 'shippingcostpaidby' => ''
                );
    if ( EBAY_UK_DEFAULT_RETURNS_ACCEPT=='ReturnsAccepted' ) {
      $ret['accept'] = EBAY_UK_DEFAULT_RETURNS_ACCEPT;
      $ret['refund'] = EBAY_UK_DEFAULT_RETURNS_REFUND_OPTION;
      $ret['within'] = EBAY_UK_DEFAULT_RETURNS_WITHIN;
      $ret['shippingcostpaidby'] = EBAY_UK_DEFAULT_RETURNS_SHIPPINGCOSTPAIDBY;
    }
    return $ret;
  }

  function getLogToConsole() { return true; }
  function getLogToMail() { return false; }
  function getLogToFile() { return dirname(__FILE__).'/logs/debug.txt'; }
  
}

class ebay_config extends amp3_ebay_config_sandbox{}
ebay_config::initEvilSide();

if (DEV_SERVER) require( dirname(__FILE__) . '/int_php_log.php' );
?>