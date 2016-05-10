<?php
require_once( EBAY_DIR_EBATLIB.'/EbatNs_Logger.php' );

class oscEbatNs_Logger extends EbatNs_Logger{
  var $_storage;
  
  function oscEbatNs_Logger( $beautfyXml = false, $destination = 'stdout', $asHtml = false, $SecureLogging = true ){
    parent::EbatNs_Logger( $beautfyXml, $destination, $asHtml, $SecureLogging );
  }
  
  function log($msg, $subject = null){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $old_id = $logger->getIdent( );
    $logger->setIdent( $subject );
    $logger->debug( $msg );
    $logger->setIdent( $old_id );
  }

	/*function logXml($xmlMsg, $subject = null) {
		if ($this->_debugSecureLogging){
			$xmlMsg = preg_replace("/<eBayAuthToken>.*<\/eBayAuthToken>/", "<eBayAuthToken>...</eBayAuthToken>", $xmlMsg);
			$xmlMsg = preg_replace("/<AuthCert>.*<\/AuthCert>/", "<AuthCert>...</AuthCert>", $xmlMsg);
		}
		$this->log($xmlMsg, $subject);
	}*/

}

?>