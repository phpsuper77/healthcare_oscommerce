<?php
require_once( EBAY_DIR_EBATLIB.'/EbatNs_Session.php' );
require_once( EBAY_DIR_EBATLIB.'/EbatNs_ServiceProxy.php' );
if ( !class_exists('Log') ) {
  require_once( dirname(__FILE__).'/Log.php' );
}

define('EBAY_PRODUCT_STATUS_OK', 1);
define('EBAY_PRODUCT_STATUS_SKIPPED', 2);
define('EBAY_PRODUCT_STATUS_FAIL', 3);

class ebay_core {
  var $_session;
  var $_proxy;
  var $_logger;
  var $_registry;
  var $_ebay_lms_bde;
  var $_ebay_lms_fts;
  
  function ebay_core( ){
    $this->_session = null;
    $this->_proxy = null;
  }
  
  function isCLI(){
    return defined('STDOUT');
  }
  
  function get(){
    static $ebay_core;
    if ( !is_object($ebay_core) ) $ebay_core = new ebay_core();
    return $ebay_core; 
  }
  
  function get_registry(){
    if ( !is_object($this->_registry) ) {
      $this->_registry = new ebay_registry();
    }
    return $this->_registry;
  }

  function get_LMS_bde(){
    if ( !is_object($this->_ebay_lms_bde) ) {
      $this->_ebay_lms_bde = new ebay_lms_bde();
    }
    return $this->_ebay_lms_bde;
  }
  function get_LMS_fts(){
    if ( !is_object($this->_ebay_lms_fts) ) {
      $this->_ebay_lms_fts = new ebay_lms_fts();
    }
    return $this->_ebay_lms_fts;
  }
  
  function get_session(){
    if ( !is_object($this->_session) ) {
      $this->_session = new oscEbatNs_Session();
      $this->_session->Configure();
    }
    return $this->_session;
  }

  function get_logger(){
    if ( !is_object($this->_logger) ) {
      $this->_logger = &Log::singleton('composite');
  
      if ( ebay_config::getLogToConsole()===true ) {
        if ( defined('STDOUT') ) {
          $console = &Log::singleton('console', '', '', array(), PEAR_LOG_INFO );
          $this->_logger->addChild($console);
        }else{
          $console = &Log::singleton('display', '', '', array('error_prepend' => '<font color="#ff0000"><tt>', 'error_append'  => '</tt></font>'), PEAR_LOG_INFO );
          $this->_logger->addChild($console);
        }
      }

      if ( ebay_config::getLogToMail()!==false ) {
        $conf = array('subject' => 'eBay connector',
                      'from' => 'atkach@holbi.co.uk');
        $mail = &Log::singleton('mail', ebay_config::getLogToMail(), '', $conf, PEAR_LOG_INFO);
        $this->_logger->addChild($mail);
      }
  
      if ( ebay_config::getLogToFile()!==false ) {
        // splited file
        $file_path = ebay_config::getLogToFile();
        $file_path = preg_replace('/\.txt$/', date('Ymd').'.txt', $file_path);
        $file = &Log::singleton('file', $file_path, '', array('mode'=>0666));
        $this->_logger->addChild($file); 
      }
  
      $eat_all = &Log::singleton('null');
      $this->_logger->addChild($eat_all); 

    }
    return $this->_logger;
  }
  
  function get_proxy(){
    if ( !is_object($this->_proxy) ) {
      $session = $this->get_session();
    	$this->_proxy = new EbatNs_ServiceProxy( $session );
    	$logger = new oscEbatNs_Logger();
    	$this->_proxy->attachLogger($logger);
    	$this->_proxy->setLoggingOptions(array('LOG_TIMEPOINTS' => true, 'LOG_API_USAGE' => true));
    }
    return $this->_proxy;
  }
  
  function db_null_save( $type, & $dummy ){
  
  }
}

?>