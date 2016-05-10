<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

// https://sellercentral-europe.amazon.com/gp/help/external/help.html?ie=UTF8&itemID=13861&language=en%5FUS

/*
 *	http://www.amazon.com/merchants/merchant-interface/:Merchant
 */
class Merchant {
	var $merchantIdentifier = null; // http://www.w3.org/2001/XMLSchema:string
	var $merchantName = null; // http://www.w3.org/2001/XMLSchema:string
	
  function Merchant(){
    $this->merchantIdentifier = AmazonConfig::getMerchantId();
    $this->merchantName = AmazonConfig::getMerchantShopName();
    if ( function_exists('utf8_encode') ) {
      $this->merchantName = utf8_encode( $this->merchantName );
    } 
  }
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:DocumentDownloadAckStatus
 */
class DocumentDownloadAckStatus {
	// elements
	var $documentDownloadAckProcessingStatus; // http://www.w3.org/2001/XMLSchema:string
	var $documentID; // http://www.w3.org/2001/XMLSchema:string

	// attributes

	// ctor that initializes members from an associative array of values
	function DocumentDownloadAckStatus($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['documentDownloadAckProcessingStatus'])) $this->documentDownloadAckProcessingStatus = $values['documentDownloadAckProcessingStatus'];
			if (isset($values['documentID'])) $this->documentID = $values['documentID'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:ArrayOfDocumentDownloadAckStatus
 */
class ArrayOfDocumentDownloadAckStatus {
	// elements
	var $DocumentDownloadAckStatus; // http://www.amazon.com/merchants/merchant-interface/:DocumentDownloadAckStatus

	// attributes

	// ctor that initializes members from an associative array of values
	function ArrayOfDocumentDownloadAckStatus($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['DocumentDownloadAckStatus'])) $this->DocumentDownloadAckStatus = $values['DocumentDownloadAckStatus'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:DocumentSubmissionResponse
 */
class DocumentSubmissionResponse {
	// elements
	var $documentTransactionID; // http://www.w3.org/2001/XMLSchema:long

	// attributes

	// ctor that initializes members from an associative array of values
	function DocumentSubmissionResponse($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['documentTransactionID'])) $this->documentTransactionID = $values['documentTransactionID'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:MerchantDocumentInfo
 */
class MerchantDocumentInfo {
	// elements
	var $documentID; // http://www.w3.org/2001/XMLSchema:string
	var $generatedDateTime; // http://www.w3.org/2001/XMLSchema:dateTime

	// attributes

	// ctor that initializes members from an associative array of values
	function MerchantDocumentInfo($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['documentID'])) $this->documentID = $values['documentID'];
			if (isset($values['generatedDateTime'])) $this->generatedDateTime = $values['generatedDateTime'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:ArrayOfMerchantDocumentInfo
 */
class ArrayOfMerchantDocumentInfo {
	// elements
	var $MerchantDocumentInfo; // http://www.amazon.com/merchants/merchant-interface/:MerchantDocumentInfo

	// attributes

	// ctor that initializes members from an associative array of values
	function ArrayOfMerchantDocumentInfo($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['MerchantDocumentInfo'])) $this->MerchantDocumentInfo = $values['MerchantDocumentInfo'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:DocumentProcessingInfo
 */
class DocumentProcessingInfo {
	// elements
	var $documentProcessingStatus; // http://www.w3.org/2001/XMLSchema:string
	var $processingReport; // http://www.amazon.com/merchants/merchant-interface/:MerchantDocumentInfo

	// attributes

	// ctor that initializes members from an associative array of values
	function DocumentProcessingInfo($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['documentProcessingStatus'])) $this->documentProcessingStatus = $values['documentProcessingStatus'];
			if (isset($values['processingReport'])) $this->processingReport = $values['processingReport'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:SummaryInfo
 */
class SummaryInfo {
	// elements
	var $batchID; // http://www.w3.org/2001/XMLSchema:long
	var $batchStatus; // http://www.w3.org/2001/XMLSchema:string
	var $numberOfProcessed; // http://www.w3.org/2001/XMLSchema:int
	var $numberOfRecordsWithErrors; // http://www.w3.org/2001/XMLSchema:int
	var $numberOfRecordsWithWarnings; // http://www.w3.org/2001/XMLSchema:int
	var $numberOfSuccessful; // http://www.w3.org/2001/XMLSchema:int
	var $summaryLogFile; // http://www.w3.org/2001/XMLSchema:string
	var $uploadDateTime; // http://www.w3.org/2001/XMLSchema:dateTime

	// attributes

	// ctor that initializes members from an associative array of values
	function SummaryInfo($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['batchID'])) $this->batchID = $values['batchID'];
			if (isset($values['batchStatus'])) $this->batchStatus = $values['batchStatus'];
			if (isset($values['numberOfProcessed'])) $this->numberOfProcessed = $values['numberOfProcessed'];
			if (isset($values['numberOfRecordsWithErrors'])) $this->numberOfRecordsWithErrors = $values['numberOfRecordsWithErrors'];
			if (isset($values['numberOfRecordsWithWarnings'])) $this->numberOfRecordsWithWarnings = $values['numberOfRecordsWithWarnings'];
			if (isset($values['numberOfSuccessful'])) $this->numberOfSuccessful = $values['numberOfSuccessful'];
			if (isset($values['summaryLogFile'])) $this->summaryLogFile = $values['summaryLogFile'];
			if (isset($values['uploadDateTime'])) $this->uploadDateTime = $values['uploadDateTime'];
		}
	}
}

/*
 *	http://www.amazon.com/merchants/merchant-interface/:ArrayOfSummaryInfo
 */
class ArrayOfSummaryInfo {
	// elements
	var $SummaryInfo; // http://www.amazon.com/merchants/merchant-interface/:SummaryInfo

	// attributes

	// ctor that initializes members from an associative array of values
	function ArrayOfSummaryInfo($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['SummaryInfo'])) $this->SummaryInfo = $values['SummaryInfo'];
		}
	}
}

/*
 *	http://systinet.com/wsdl/java/lang/:ArrayOfstring
 */
class ArrayOfstring {
	// elements
	var $string; // http://www.w3.org/2001/XMLSchema:string

	// attributes

	// ctor that initializes members from an associative array of values
	function ArrayOfstring($values) {
		if (isset($values) && is_array($values)) {
			if (isset($values['string'])) $this->string = $values['string'];
		}
	}
}

//---------------------------------------------------------------------------------------------
/*
 *	port: MerchantInterface
 *	location: https://merchant-api.amazon.com:443/gateway/merchant-interface-mime
 *	binding: MerchantInterface
 *	bindingType: http://schemas.xmlsoap.org/wsdl/soap/
 *	style: document
 *	transport: http://schemas.xmlsoap.org/soap/http
 */

class MerchantInterface extends nusoap_client_mime {
  var $_wrap_wsdl_location = null;
  var $_wsse_auth = false;
  var $_wsse_auth_user = false;
  var $_wsse_auth_pass = false;
  var $_soap_wrap_log_enable = false;
  var $_soap_wrap_log_path = '';
  
	function MerchantInterface($endpoint = '', $wsdl = 'wsdl', $proxyhost = false, $proxyport = false, $proxyusername = false, $proxypassword = false, $timeout = 0, $response_timeout = 30, $portName = 'MerchantInterface') {
    $this->_wrap_wsdl_location = AmazonConfig::getWsdlUrl();
    $this->_soap_wrap_log_enable = AmazonConfig::isEnabledLog();
    $this->_soap_wrap_log_path = AmazonConfig::logDir();

    if ( empty($endpoint) ) {
	    $endpoint = AmazonConfig::getWsdlUrl();
    } 
		parent::nusoap_client_mime($endpoint, $wsdl, $proxyhost, $proxyport, $proxyusername, $proxypassword, $timeout, $response_timeout, $portName);
    $err = $this->getError();
    if ($err){
      die('SOAP Constructor error');
    }
	}

	/*
	 *	operation: getDocument
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getDocument_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:documentIdentifier^ $documentIdentifier
	 */
	function getDocument($documentIdentifier) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'documentIdentifier' => $documentIdentifier);
		return $this->do_call('getDocument', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getDocument#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7TG9yZy9pZG9veC93YXNwL3R5cGVzL1Jlc3BvbnNlTWVzc2FnZUF0dGFjaG1lbnQ7KUxqYXZhL2xhbmcvU3RyaW5nOw==', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getDocumentInterfaceConformance
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getDocumentInterfaceConformance_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:documentIdentifier^ $documentIdentifier
	 */
	function getDocumentInterfaceConformance($documentIdentifier) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'documentIdentifier' => $documentIdentifier);
		return $this->do_call('getDocumentInterfaceConformance', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getDocumentInterfaceConformance#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7TG9yZy9pZG9veC93YXNwL3R5cGVzL1Jlc3BvbnNlTWVzc2FnZUF0dGFjaG1lbnQ7KUxqYXZhL2xhbmcvU3RyaW5nOw==', false, null, 'document', 'literal');
	}

	/*
	 *	operation: postDocumentDownloadAck
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_postDocumentDownloadAck_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:documentIdentifierArray^ $documentIdentifierArray
	 */
	function postDocumentDownloadAck($documentIdentifierArray) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'documentIdentifierArray' => $documentIdentifierArray);
		return $this->do_call('postDocumentDownloadAck', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#postDocumentDownloadAck#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7W0xqYXZhL2xhbmcvU3RyaW5nOylbTHdhc3BjU2VydmVyL0FteklTQS9Eb2N1bWVudERvd25sb2FkQWNrU3RhdHVzOw==', false, null, 'document', 'literal');
	}

	/*
https://sellercentral-europe.amazon.com/gp/help/external/help.html?ie=UTF8&itemID=13891&language=en%5FUS
	 *	operation: postDocument
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_postDocument_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
            _POST_PRODUCT_DATA_
            _POST_PRODUCT_RELATIONSHIP_DATA_
            _POST_PRODUCT_OVERRIDES_DATA_
            _POST_PRODUCT_IMAGE_DATA_
            _POST_PRODUCT_PRICING_DATA_
            _POST_INVENTORY_AVAILABILITY_DATA_
            _POST_TEST_ORDERS_DATA_
            _POST_ORDER_ACKNOWLEDGEMENT_DATA_
            _POST_ORDER_FULFILLMENT_DATA_
            _POST_PAYMENT_ADJUSTMENT_DATA_
            _POST_STORE_DATA_	 
	 *			http://systinet.com/xsd/SchemaTypes/:doc^ $doc
	 fault types
            _INVALID_MESSAGE_TYPE_
            _UNRECOGNIZED_MERCHANT_
            _MISSING_OR_INVALID_DATA_
            _INTERNAL_ERROR_	 
	 */
	function postDocument($messageType, $doc) {
	  $merchant = new Merchant();
	  $temp_doc_name = date('Ymd_His').$messageType.'.xml';
	  $doc = $this->addAttachment( $doc, $temp_doc_name, 'application/binary' );
		//$params = array('merchant' => $merchant, 'messageType' => $messageType, 'doc' => 'cid:'.$doc);
		$params = 
    '<merchant xmlns="http://systinet.com/xsd/SchemaTypes/">' .
       '<merchantIdentifier>'.$merchant->merchantIdentifier.'</merchantIdentifier>' .
       '<merchantName>'.$merchant->merchantName.'</merchantName>' .
    '</merchant>' .
    '<messageType xmlns="http://systinet.com/xsd/SchemaTypes/">'.$messageType.'</messageType>' .
    '<doc xmlns="http://systinet.com/xsd/SchemaTypes/" href="cid:'.$doc.'"></doc>';

		return $this->do_call('postDocument', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#postDocument#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7TG9yZy9pZG9veC93YXNwL3R5cGVzL1JlcXVlc3RNZXNzYWdlQXR0YWNobWVudDspTHdhc3BjU2VydmVyL0FteklTQS9Eb2N1bWVudFN1Ym1pc3Npb25SZXNwb25zZTs=', false, null, 'document', 'literal');
	}

	/*
	 *	operation: postDocumentInterfaceConformance
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_postDocumentInterfaceConformance_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
	 *			http://systinet.com/xsd/SchemaTypes/:doc^ $doc
	 */
	function postDocumentInterfaceConformance($messageType, $doc) {
	  $merchant = new Merchant();
	  $temp_doc_name = date('Ymd_His').$messageType.'.xml';
	  $doc = $this->addAttachment( $doc, $temp_doc_name, 'application/binary' );
		$params = 
    '<merchant xmlns="http://systinet.com/xsd/SchemaTypes/">' .
       '<merchantIdentifier>'.$merchant->merchantIdentifier.'</merchantIdentifier>' .
       '<merchantName>'.$merchant->merchantName.'</merchantName>' .
    '</merchant>' .
    '<messageType xmlns="http://systinet.com/xsd/SchemaTypes/">'.$messageType.'</messageType>' .
    '<doc xmlns="http://systinet.com/xsd/SchemaTypes/" href="cid:'.$doc.'"></doc>';
		return $this->do_call('postDocumentInterfaceConformance', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#postDocumentInterfaceConformance#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7TG9yZy9pZG9veC93YXNwL3R5cGVzL1JlcXVlc3RNZXNzYWdlQXR0YWNobWVudDspTHdhc3BjU2VydmVyL0FteklTQS9Eb2N1bWVudFN1Ym1pc3Npb25SZXNwb25zZTs=', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getLastNDocumentInfo
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getLastNDocumentInfo_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
	 *			http://systinet.com/xsd/SchemaTypes/:howMany^ $howMany
	 */
	function getLastNDocumentInfo($messageType, $howMany) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'messageType' => $messageType, 'howMany' => $howMany);
		return $this->do_call('getLastNDocumentInfo', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getLastNDocumentInfo#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7SSlbTHdhc3BjU2VydmVyL0FteklTQS9NZXJjaGFudERvY3VtZW50SW5mbzs=', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getLastNPendingDocumentInfo
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getLastNPendingDocumentInfo_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
	 *			http://systinet.com/xsd/SchemaTypes/:howMany^ $howMany
	 */
	function getLastNPendingDocumentInfo($messageType, $howMany) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'messageType' => $messageType, 'howMany' => $howMany);
		return $this->do_call('getLastNPendingDocumentInfo', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getLastNPendingDocumentInfo#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7SSlbTHdhc3BjU2VydmVyL0FteklTQS9NZXJjaGFudERvY3VtZW50SW5mbzs=', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getDocumentProcessingStatus
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getDocumentProcessingStatus_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:documentTransactionIdentifier^ $documentTransactionIdentifier
     _PENDING_: Feed submitted using postDocumentUnconfirmed API, but matching call to API postDocumentConfirmation is still to be made.
     _IN_PROGRESS_:Our system has received the submission.
     _DONE_:Feed processed completely and processing report is ready.
     _FAILED_DUE_TO_FATAL_ERRORS_ :Feed processing has ended abruptly 	 
	 */
	function getDocumentProcessingStatus($documentTransactionIdentifier) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'documentTransactionIdentifier' => $documentTransactionIdentifier);
		return $this->do_call('getDocumentProcessingStatus', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getDocumentProcessingStatus#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7SilMd2FzcGNTZXJ2ZXIvQW16SVNBL0RvY3VtZW50UHJvY2Vzc2luZ0luZm87', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getAllPendingDocumentInfo
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getAllPendingDocumentInfo_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
          _GET_ORDERS_DATA_
          _GET_PAYMENT_SETTLEMENT_DATA_	 
	 */
	function getAllPendingDocumentInfo($messageType) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'messageType' => $messageType);
		return $this->do_call('getAllPendingDocumentInfo', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getAllPendingDocumentInfo#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7KVtMd2FzcGNTZXJ2ZXIvQW16SVNBL01lcmNoYW50RG9jdW1lbnRJbmZvOw==', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getDocumentInfoInterfaceConformance
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getDocumentInfoInterfaceConformance_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:messageType^ $messageType
	 */
	function getDocumentInfoInterfaceConformance($messageType) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'messageType' => $messageType);
		return $this->do_call('getDocumentInfoInterfaceConformance', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getDocumentInfoInterfaceConformance#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7TGphdmEvbGFuZy9TdHJpbmc7KVtMd2FzcGNTZXJ2ZXIvQW16SVNBL01lcmNoYW50RG9jdW1lbnRJbmZvOw==', false, null, 'document', 'literal');
	}

	/*
	 *	operation: getLastNDocumentProcessingStatuses
	 *	input...
	 *		encodingStyle: 
	 *		namespace: 
	 *		use: literal
	 *		message: MerchantInterface_getLastNDocumentProcessingStatuses_1_Request
	 *		parts...
	 *			http://systinet.com/xsd/SchemaTypes/:merchant^ $merchant
	 *			http://systinet.com/xsd/SchemaTypes/:numberOfStatuses^ $numberOfStatuses
	 *			http://systinet.com/xsd/SchemaTypes/:uploadType^ $uploadType
	 */
	function getLastNDocumentProcessingStatuses($numberOfStatuses, $uploadType) {
	  $merchant = new Merchant();
		$params = array('merchant' => $merchant, 'numberOfStatuses' => $numberOfStatuses, 'uploadType' => $uploadType);
		return $this->do_call('getLastNDocumentProcessingStatuses', $params, '', 'http://www.amazon.com/merchants/merchant-interface/MerchantInterface#getLastNDocumentProcessingStatuses#KEx3YXNwY1NlcnZlci9BbXpJU0EvTWVyY2hhbnQ7SUxqYXZhL2xhbmcvU3RyaW5nOylbTHdhc3BjU2VydmVyL0FteklTQS9TdW1tYXJ5SW5mbzs=', false, null, 'document', 'literal');
	}


  function do_call($operation,$params=array(),$namespace='http://tempuri.org',$soapAction='',$headers=false,$rpcParams=null,$style='rpc',$use='encoded'){
    global $_amazon_fws;
    $_amazon_fws->say("MerchantInterface::do_call('$operation')\n");
    
    $this->setCredentials( AmazonConfig::getSoapUser(), AmazonConfig::getSoapPassword() );
    
    if ( $this->_wsse_auth && $headers==false ) {
      $headers = "<HeaderLogin SOAP-ENV:mustUnderstand='1'>".
				                  "<username>".htmlspecialchars($this->_wsse_auth_user)."</username>".
				                  "<password>".htmlspecialchars($this->_wsse_auth_pass)."</password>".
				                "</HeaderLogin>";
    }
    $result = $this->call($operation, $params, $namespace, $soapAction, $headers, $rpcParams, $style, $use);

    $this->transfer_log( );
    if ($this->fault){
      $_amazon_fws->say("Fault: [".$result['faultstring']."]\n");
      return false;
    } else {
      $err = $this->getError();
      if ($err) {
        $_amazon_fws->say("Error: [".$err."]\n");
        return false; 
      } else {
        //return $result[$method . 'Result'];
        return $result;
      }
    }
  }

  function transfer_log(){
    if ( !$this->_soap_wrap_log_enable ) return;
    $target_splits = array( $this->_soap_wrap_log_path,
                            date('Ym'),
                            'soap_wrap' . date('d') . '.txt'
                          );
    $write_string = '++++++++++++++++++++'."\n".'Transfer at '.date('H:i:s')."\n";
    $write_string .= "===SOAP Request===\n".$this->request."\n";
    $write_string .= "===SOAP Response===\n".$this->response."\n";
    if ( !empty($this->error_str) ) $write_string .= "===SOAP error_str===\n".$this->error_str."\n";

    $target_file = implode('/',$target_splits);
    $do_chmod = !file_exists($target_file);

    if ( $do_chmod ) {
      $deep_mk = '';
      for( $i=0; $i<count($target_splits)-1; $i++ ) {
        $deep_mk .= $target_splits[$i];
        if (!is_dir($deep_mk)) {
          @mkdir($deep_mk, 0777);
          @chmod($deep_mk, 0777);
        }
        $deep_mk .= '/';
      }
      if ( AmazonConfig::getLogKeep()!='' && preg_match('/(\d{1,})d/',AmazonConfig::getLogKeep(),$param_keep) ) {
        if ( (int)$param_keep[1]>0 ) {
          $del_mkt = mktime(0, 0, 0, date('m'), (date('d')-(int)$param_keep[1]), date('Y'));
          $clean_splits = array( $this->_soap_wrap_log_path,
                                 date('Ym', $del_mkt),
                                 'soap_wrap' . date('d',$del_mkt) . '.txt');
          $clean_file = implode('/', $clean_splits);
          if ( is_file($clean_file) ) @unlink($clean_file);
        }
      }
    }

    if ($log_file = fopen($target_file, "a")) {
      fputs($log_file, $write_string);
      fclose($log_file);
      if ( $do_chmod ) @chmod($target_file, 0666);
    } 
  }
  
}

?>
