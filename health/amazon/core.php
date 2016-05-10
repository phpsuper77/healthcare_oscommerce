<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/
$MODULE_PWD = dirname(__FILE__);

set_time_limit(0);
@ini_set('max_execution_time',0);

// nuSOAP
include_once( $MODULE_PWD . '/libs/nusoap.php');
include_once( $MODULE_PWD . '/libs/PEAR.php');
include_once( $MODULE_PWD . '/libs/mimePart.php');
include_once( $MODULE_PWD . '/libs/mimeDecode.php');
include_once( $MODULE_PWD . '/libs/nusoapmime.php');
include_once( $MODULE_PWD . '/libs/xmlParser.php');

// base amazon libs
require($MODULE_PWD.'/libs_amazon/axsd.php');
require($MODULE_PWD.'/libs_amazon/Header.php');
require($MODULE_PWD.'/libs_amazon/soap_wrap.php');
require($MODULE_PWD.'/libs_amazon/Atomic.php');
require($MODULE_PWD.'/libs_amazon/BaseProduct.php');
require($MODULE_PWD.'/libs_amazon/ProductsCollection.php');
require($MODULE_PWD.'/libs_amazon/ProcessingReport.php');
require($MODULE_PWD.'/libs_amazon/Order.php');

require($MODULE_PWD.'/libs_amazon/ProductTypeCEConsumerElectronics.php');
require($MODULE_PWD.'/libs_amazon/ProductTypeBeauty.php');
require($MODULE_PWD.'/libs_amazon/ProductTypeHealth.php');

// base functions
include_once( $MODULE_PWD . '/functions.php');

// commerce proxy
include_once( $MODULE_PWD . '/proxy/core.php');

// Core class
include_once( $MODULE_PWD . '/AmazonCore.php');
include_once( $MODULE_PWD . '/Launcher.php');
include_once( $MODULE_PWD . '/Scheduler.php');
include_once( $MODULE_PWD . '/config.php');
class AmazonConfig extends amazonSoapConfig {};


define('AFWS_ORDER_IMPORT_CREATE_CUSTOMER', 'No'); // Yes | No
define('AFWS_ORDER_IMPORT_FAIL', -1);
define('AFWS_ORDER_IMPORT_DOUBLE', -2);
define('AFWS_ORDER_IMPORT_OK', 1);

global $_amazon_fws;
$_amazon_fws = new AmazonCore();
 
?>
