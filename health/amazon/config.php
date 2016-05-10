<?php
/*
$Id: config.php

Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk


description:

*/

class amazonSoapConfig{

  function getDocVersion(){
    return '1.01';
  }

  function getSoapUser()         { return AFWS_WEBS_AUTH_USER; }
  function getSoapPassword()     { return AFWS_WEBS_AUTH_PASS; }
  function getMerchantId()       { return AFWS_WEBS_MERCHANT_ID; }
  function getMerchantShopName() { return AFWS_WEBS_MERCHANT_NAME; }

  function getSiteUrl() {
    return HTTP_SERVER.DIR_WS_HTTP_CATALOG;
  }
  
  function getWsdlUrl(){
    return amazonSoapConfig::getSiteUrl().'/amazon/wsdl/merchant-interface-mime.wsdl';
  }
  
  function getAmazonCurrency() {
    return 'GBP';
  }
  function getAmazonCountry() {
    return 'GB';
  }
  
  function getProductType(){
    return 'CEConsumerElectronics';
  }

  function getDefaultConditionNote(){
    return AFWS_DEFAULT_CONDITIONNOTE;
  }

  function orderImportCustomerId() { return 'create'; /*'create', 0|2223*/}
  function getInsertOrderStatus()  { return (int)AFWS_ORDERS_STATUS;}
  function getShippedOrderStatus() { return (int)AFWS_ORDERS_SHIPPED_STATUS; }
  function getPaymentClass()       { return 'amazon'; }
  function getPaymentMethod()      { return 'Amazon'; }
  function getTaxRate()            {
    static $rate=false;
    if ( $rate===false ) {
      $rate = '15.00';
      //$rate = tep_get_tax_rate(1);
    }
    return $rate;
  }

  function isEnabledLog() { return true; }
  function logDir(){ return dirname(__FILE__).'/logs/'; }
  function getLogKeep(){ return '14d'; }
  function getDBLogKeep(){ return '60d'; }

  function cronDividerPostProducts()  { return (int)AFWS_WEBS_SCHEDULE_PRODUCTS; }
  function cronDividerPostInventory() { return (int)AFWS_WEBS_SCHEDULE_INVENTORY; }
  function cronDividerPostPrice()     { return (int)AFWS_WEBS_SCHEDULE_PRICE; }
  function cronDividerPostImages()    { return (int)AFWS_WEBS_SCHEDULE_IMAGES;  }
  function cronDividerGetOrders()     { return (int)AFWS_WEBS_SCHEDULE_GETORDERS; }
  function cronDividerPostShipping()  { return (int)AFWS_WEBS_SCHEDULE_SHIPPING; }

  
}

?>
