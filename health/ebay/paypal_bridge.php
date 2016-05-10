<?php

class paypal_bridge {
  var $mode = 'Sandbox';
  var $api = 'Certificate';
  var $paypal_username = '';
  var $paypal_password = '';
  var $certificate_path = false;
  var $signature_value = false;
  var $http_proxy = false;

  var $_version = '2.0';
  var $_request = '';
  var $_response = '';
  var $_error = '';

  function paypal_bridge( $config ) {
    if ( !is_array($config) ) die('paypal_bridge need config');
    foreach( $config as $key=>$val ) {
      if ( isset($this->{$key}) ) $this->{$key} = $val;
    }
  }

  function getServiceLocation(){
    $ret = '';
    $mode_api = $this->mode.$this->api;
    switch ( $mode_api ) {
      case 'SandboxCertificate':
        $ret = 'https://api.sandbox.paypal.com/2.0/';
        break;
      case 'SandboxSignature':
        $ret = 'https://api-3t.sandbox.paypal.com/2.0/';
        break;
      case 'LiveCertificate':
        $ret = 'https://api.paypal.com/2.0/';
        break;
      case 'LiveSignature':
        $ret = 'https://api-3t.paypal.com/2.0/';
        break;
      default:
        die( 'What is '.$mode_api );
    }
    return $ret;
  }

  function _SoapHeader(){
    $sig_addon = '';
    if ( $this->api=='Signature' ) {
      $sig_addon = '<Signature>'.$this->signature_value.'</Signature>';
    }
    return '<soap:Header>' .
             '<RequesterCredentials xmlns="urn:ebay:api:PayPalAPI">' .
               '<Credentials xmlns="urn:ebay:apis:eBLBaseComponents">' .
                 '<Username>'.$this->paypal_username.'</Username>'.
                 '<ebl:Password xmlns:ebl="urn:ebay:apis:eBLBaseComponents">'.$this->paypal_password.'</ebl:Password>'.
                 $sig_addon .
               '</Credentials>'.
              '</RequesterCredentials>'.
           '</soap:Header>';
  }

  /*
  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_soap_r_GetTransactionDetails
  */
  function _GetTransactionDetailsRequest( $params ){
    return '<GetTransactionDetailsReq xmlns="urn:ebay:api:PayPalAPI">'.
             '<GetTransactionDetailsRequest>'.
               '<Version xmlns="urn:ebay:apis:eBLBaseComponents">'.$this->_version.'</Version>'.
               '<TransactionID>'.$params['TransactionID'].'</TransactionID>'.
             '</GetTransactionDetailsRequest>'.
           '</GetTransactionDetailsReq>';
  }

  function call( $method, $params=false ){
    $request = '<?xml version="1.0" encoding="utf-8"?'.'>'."\n";
    $request .= '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
    $request .= $this->_SoapHeader();
    $request .= '<soap:Body>';
    switch ($method){
      case 'GetTransactionDetails':
        $request .= $this->_GetTransactionDetailsRequest( $params );
      break;
      default:
      die( 'Unknown method ['.$method.']' );
    }
    $request .= '</soap:Body>';
    $request .= '</soap:Envelope>';
    if ( $this->http_request( $request ) ) {
      $parser = new paypal_xmlParser( $this->_response );
      $root = $parser->GetRoot();
      $data = $parser->GetData();
      $data = $data[$root]['SOAP-ENV:Body'][$method.'Response'];
      switch ($method){
        case 'GetTransactionDetails':
          if (isset($data['PaymentTransactionDetails']['PaymentItemInfo']['PaymentItem']['Name']) ){
            $data['PaymentTransactionDetails']['PaymentItemInfo']['PaymentItem'] = array($data['PaymentTransactionDetails']['PaymentItemInfo']['PaymentItem']);
          }
        break;
        default:
      }
      return $data;
    }else{
      $err = array('Timestamp'=>array('VALUE'=>date('Y-m-d').'T'.date('H-i-s').'Z'),
                   'Ack' => array('VALUE'=>'Failure'),
                   'Errors' => array(
                      array('ShortMessage'=>array('VALUE'=>$this->_error), 'LongMessage'=>array('VALUE'=>$this->_error) )
                   )
      );
      return false;
    };
  }

  function http_request( $xml_contents ){
    $this->_request = $xml_contents;
    $this->_response = '';
    $this->_error = '';

    //Initialize curl
    $ch = curl_init();
    if ( !$ch ) {
      $this->_error = 'Curl Init error';
      return false;
    }

    //For the poor souls on GoDaddy and the like, set the connection to go through their proxy
    if ( !empty($this->http_proxy) ) {
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      curl_setopt($ch, CURLOPT_PROXY, $this->http_proxy);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);
    if ( $this->api=='Certificate' ) {
      curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM");
      curl_setopt($ch, CURLOPT_SSLCERT, $this->certificate_path);
    }
    curl_setopt($ch, CURLOPT_URL, $this->getServiceLocation());
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_contents);

    $this->_response = curl_exec($ch);

    //!! paypal_xmlParser
    if ( !empty($this->_response) ) {
      curl_close($ch);
      //Simple check to make sure that this is a valid response
//      if (strpos($response, 'SOAP-ENV') === false) {
//        $response = false;
//      }
      return true;
    } else {
      $this->_error = curl_error($ch) . ' (Error No. ' . curl_errno($ch) . ')';
      curl_close($ch);
    }
    return false;
  }
}

  // XML to Array
  class paypal_xmlParser {

    var $params = array(); //Stores the object representation of XML data
    var $root = NULL;
    var $global_index = -1;
    var $fold = false;

   /* Constructor for the class
    * Takes in XML data as input( do not include the <xml> tag
    */
    function paypal_xmlParser($input, $xmlParams=array(XML_OPTION_CASE_FOLDING => 0)) {
      $xmlp = xml_parser_create('utf-8');
      foreach($xmlParams as $opt => $optVal) {
        switch( $opt ) {
          case XML_OPTION_CASE_FOLDING:
            $this->fold = $optVal;
           break;
          default:
           break;
        }
        xml_parser_set_option($xmlp, $opt, $optVal);
      }

      if(xml_parse_into_struct($xmlp, $input, $vals, $index)) {
        $this->root = $this->_foldCase($vals[0]['tag']);
        $this->params = $this->xml2ary($vals);
      }
      xml_parser_free($xmlp);
    }

    function _foldCase($arg) {
      return( $this->fold ? strtoupper($arg) : $arg);
    }

/*
 * Credits for the structure of this function
 * http://mysrc.blogspot.com/2007/02/php-xml-to-array-and-backwards.html
 *
 * Adapted by Ropu - 05/23/2007
 *
 */
    function xml2ary($vals) {

        $mnary=array();
        $ary=&$mnary;
        foreach ($vals as $r) {
            $t=$r['tag'];
            if ($r['type']=='open') {
                if (isset($ary[$t]) && !empty($ary[$t])) {
                    if (isset($ary[$t][0])){
                      $ary[$t][]=array();
                    }
                    else {
                      $ary[$t]=array($ary[$t], array());
                    }
                    $cv=&$ary[$t][count($ary[$t])-1];
                }
                else {
                  $cv=&$ary[$t];
                }
                $cv=array();
                if (isset($r['attributes'])) {
                  foreach ($r['attributes'] as $k=>$v) {
                    $cv[$k]=$v;
                  }
                }

                $cv['_p']=&$ary;
                $ary=&$cv;

            } else if ($r['type']=='complete') {
                if (isset($ary[$t]) && !empty($ary[$t])) { // same as open
                    if (isset($ary[$t][0])) {
                      $ary[$t][]=array();
                    }
                    else {
                      $ary[$t]=array($ary[$t], array());
                    }
                    $cv=&$ary[$t][count($ary[$t])-1];
                }
                else {
                  $cv=&$ary[$t];
                }
                if (isset($r['attributes'])) {
                  foreach ($r['attributes'] as $k=>$v) {
                    $cv[$k]=$v;
                  }
                }
                $cv['VALUE'] = (isset($r['value']) ? $r['value'] : '');
                if ( function_exists('utf8_decode') ) $cv['VALUE'] = utf8_decode($cv['VALUE']);

            } elseif ($r['type']=='close') {
                $ary=&$ary['_p'];
            }
        }

        $this->_del_p($mnary);
        return $mnary;
    }

    // _Internal: Remove recursion in result array
    function _del_p(&$ary) {
        foreach ($ary as $k=>$v) {
            if ($k==='_p') {
              unset($ary[$k]);
            }
            else if(is_array($ary[$k])) {
              $this->_del_p($ary[$k]);
            }
        }
    }

    /* Returns the root of the XML data */
    function GetRoot() {
      return $this->root;
    }

    /* Returns the array representing the XML data */
    function GetData() {
      return $this->params;
    }
  }


?>