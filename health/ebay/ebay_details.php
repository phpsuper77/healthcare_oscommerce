<?php

class ebay_details {

  function collect(){
    require_once EBAY_DIR_EBATLIB.'/GeteBayDetailsRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/GeteBayDetailsResponseType.php';
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_details::collect()');
    $proxy = $core->get_proxy();
    $req = new GeteBayDetailsRequestType();
    //$req->setDetailName('URLDetails');
    
    $logger->info('Call GeteBayDetails');
    $res = $proxy->GeteBayDetails($req);
    if ( $res->getAck()=='Success' ) {
      $logger->info('GeteBayDetails ['.$res->getAck().'] URLDetails['.(is_array($res->URLDetails)?count($res->URLDetails):'').'] ');
      $reg = $core->get_registry();
      if ( is_array($res->URLDetails) ) {
        foreach( $res->URLDetails as $URLDetails ) {
          if ( !empty($URLDetails->URLType) ) {
            $reg->setValue( $URLDetails->URLType, $URLDetails->URL );
          }
        }
      }
      $ShippingServiceDetails = array('domestic'=>array(),'international'=>array());
      if ( is_array($res->ShippingServiceDetails) ) {
        foreach( $res->ShippingServiceDetails as $ShippingServiceDetail ) {
          if ( $ShippingServiceDetail->ValidForSellingFlow!==true ) continue;
          if ( !is_array($ShippingServiceDetail->ServiceType) || array_search('Flat', $ShippingServiceDetail->ServiceType)===false ) {
            continue;
          }
          $ship = array( 'code' => $ShippingServiceDetail->ShippingServiceID,
                         'description' => $ShippingServiceDetail->Description,
                         'service' => $ShippingServiceDetail->ShippingService );
          if ( !is_null($ShippingServiceDetail->ShippingTimeMax) ) $ship['time_max'] = $ShippingServiceDetail->ShippingTimeMax;
          if ( !is_null($ShippingServiceDetail->ShippingTimeMin) ) $ship['time_min'] = $ShippingServiceDetail->ShippingTimeMin;

          if ( $ShippingServiceDetail->InternationalService===true ) {
            $ShippingServiceDetails['international'][ $ShippingServiceDetail->ShippingService ] = $ship;
          }else{
            $ShippingServiceDetails['domestic'][ $ShippingServiceDetail->ShippingService ] = $ship;
          }
        }
      }
      $reg->setBlob( 'ShippingServiceDetails', serialize($ShippingServiceDetails) );
      
      //DispatchTimeMaxDetails
      
    }else{
      $logger->notice('GeteBayDetails ['.$res->Ack.']');
      if ( is_array($res->Errors) ) {
        foreach( $res->Errors as $ErrorType ) {
          $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                          '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
        }
      }
    }
    
    $cat = new ebay_categories();
    $cat->do_update();
    
    $logger->info('Exit ebay_details::collect()');
  }
  
}

?>