<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

// https://images-na.ssl-images-amazon.com/images/G/01/rainier/help/xsd/release_1_9/OrderReport.xsd
// https://sellercentral-europe.amazon.com/gp/help/external/help.html?ie=UTF8&itemID=1271&language=en%5FUS

class AmazonOrderReport {

  var $ackList;
  function AmazonOrderReport(){
    $this->ackList = array();
  }

  //order create method
  function insertOrder( $xmlArray ){

  }

  function getOrderAcknowledgementXML(){
    if ( count($this->ackList)==0 ) return '';

    $ret_data = '';
    foreach( $this->ackList as $idx=>$ask ){
      $ret_data .= '<Message>' .
                     '<MessageID>'.($idx+1).'</MessageID>' .
                     '<OperationType>Update</OperationType>' .
                     '<OrderAcknowledgement>' .
                       '<AmazonOrderID>' . $ask['AmazonOrderID'] . '</AmazonOrderID>' .
                       (!empty($ask['MerchantOrderID'])?'<MerchantOrderID>' . $ask['MerchantOrderID'] . '</MerchantOrderID>':'') .
                       '<StatusCode>Success</StatusCode>'.
                     '</OrderAcknowledgement>' .
                   '</Message>'."\n";
    }
    return $ret_data;
  }
  
}

class OrderReport {
  var $_processed;
  var $_failed;
  var $_warning;
  var $_ok;
  function process( $rawXml ){
    $this->_processed = 0;
    $this->_failed = 0;
    $this->_warning = 0;
    $this->_ok = 0;
    $parser = new xmlParser( $rawXml );
    $root = $parser->GetRoot();
    $data = $parser->GetData();
    $data = $data[$root];
    
    $return_status = true;
    //check type
    $this->_fwsOrderReport = new ProxyOrderReport();
    if ( !isset($data['MessageType']['VALUE']) || $data['MessageType']['VALUE']!='OrderReport' ) return false;
    $_list = is_array( $data['Message'][0] )?$data['Message']:array($data['Message']);
    foreach ( $_list as $amazon_order ) {
      $this->_processed++;
      $insert_status = $this->_fwsOrderReport->insertOrder( $amazon_order );
      switch ( $insert_status ) {
        case AFWS_ORDER_IMPORT_FAIL:
          $this->_failed++;
        break;
        case AFWS_ORDER_IMPORT_DOUBLE:
          $this->_warning++;
        break;
        case AFWS_ORDER_IMPORT_OK:
          $this->_ok++;
        break;
      }
      //$return_status = ($return_status && $insert_status); // any bad order reset good response
    }
    return $this->confirmXML();
  }

  function confirmXML(){
    if ( !is_object($this->_fwsOrderReport) ) return '';
    $rr = $this->_fwsOrderReport->getOrderAcknowledgementXML();
    if (!empty($rr)) {
    $header = new AmazonHeader();
    return '<?xml version="1.0" encoding="UTF-8"?'.'>
<AmazonEnvelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="amzn-envelope.xsd">
'. $header->toXML().'<MessageType>OrderAcknowledgement</MessageType>
  '.$rr.'
</AmazonEnvelope>
';
    }else return '';
  }

}

// https://images-na.ssl-images-amazon.com/images/G/01/rainier/help/xsd/release_1_9/OrderFulfillment.xsd
// https://sellercentral-europe.amazon.com/gp/help/external/help.html?ie=UTF8&itemID=1361&language=en%5FUS
class AmazonOrderFulfillment {
  var $_orders_id;
  var $AmazonOrderID;
  var $MerchantFulfillmentID;
  var $FulfillmentDate;
  var $CarrierCode;
  var $CarrierName;
  var $ShippingMethod;
  var $ShipperTrackingNumber;
  
  function toXML(){
    return '<OrderFulfillment>' .
              '<AmazonOrderID>' . $this->AmazonOrderID . '</AmazonOrderID>' .
              '<FulfillmentDate>' . axsd::dateTime($this->FulfillmentDate, false) . '</FulfillmentDate>' .
              '<FulfillmentData>' .
                ( !empty($this->CarrierCode)?'<CarrierCode>' . axsd::safe($this->CarrierCode) . '</CarrierCode>' :
                  '<CarrierName>' . axsd::StringNotNull($this->CarrierName) . '</CarrierName>'
                ) .
                (!empty($this->ShippingMethod)?'<ShippingMethod>' . axsd::StringNotNull($this->ShippingMethod) . '</ShippingMethod>':'') .
                (!empty($this->ShipperTrackingNumber)?'<ShipperTrackingNumber>' . axsd::safe($this->ShipperTrackingNumber) . '</ShipperTrackingNumber>':'') .
              '</FulfillmentData>' .
           '</OrderFulfillment>';
  }
}

class AmazonOrderFulfillmentList {
  var $orders_list;
  
  function AmazonOrderFulfillmentList(){
    $this->orders_list = array();
  }

  function have_orders(){
    return count($this->orders_list);
  }
  
  function toXML(){
    $ret = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".
           '<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n";
    $header = new AmazonHeader();
    $ret .= $header->toXML();
    $ret .= "<MessageType>OrderFulfillment</MessageType>\n";
    foreach ( $this->orders_list as $MessageID=>$OrderFulfillment ) {
      $ret .= "<Message>\n\t<MessageID>".$MessageID."</MessageID>\n\t";
      $ret .= $OrderFulfillment->toXML()."\n</Message>\n";
    }
    return $ret.'</AmazonEnvelope>';
  }

}

?>
