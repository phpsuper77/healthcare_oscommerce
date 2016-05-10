<?php
if ( !class_exists('Log') ) {
  require_once( dirname(__FILE__).'/Log.php' );
}

function connector_errorHandler($code, $message, $file, $line) {
  $skip = 'Non-static method';
  if ( substr($message, 0, strlen($skip) )==$skip ) return;
  //Use of undefined constant USE_MARKET_PRICES -
  if ( preg_match('/^Use of undefined constant (\w+) -/',$message, $m) ) {
    // upper cased constant, - most of features constant
    if ( strtoupper($m[1])==$m[1] ) return;
  }
  if ( strpos($file, '/EbatNs/')!==false ) return;
  if ( strpos($file, '/Log')!==false ) return;

  switch ($code) {
    case E_WARNING:
    case E_USER_WARNING:
        $priority = PEAR_LOG_WARNING;
        break;
    case E_NOTICE:
    case E_USER_NOTICE:
        $priority = PEAR_LOG_NOTICE;
        break;
    case E_ERROR:
    case E_USER_ERROR:
        $priority = PEAR_LOG_ERR;
        break;
    default:
        $priority = PEAR_LOG_INFO;
  }
  $conf = array('mode' => 0666, 'timeFormat' => '%X %x');
  $logger = &Log::singleton('file', DIR_FS_CATALOG.(substr(DIR_FS_CATALOG,-1)=='/'?'':'/').'temp/php_errors.log', 'ident', $conf);
  $logger->log($message . ' in ' . $file . ' at line ' . $line, $priority);
}
set_error_handler('connector_errorHandler');

?>