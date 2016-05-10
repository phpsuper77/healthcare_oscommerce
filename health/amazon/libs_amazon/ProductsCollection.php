<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/


class AmazonProductsCollection {
  var $newSKUS = array();

  function makeProductFeed(){
    $pList = new proxyProduct();
    $skus = $pList->getNewList();
    $this->newSKUS = $skus;
    $messageCounter = 1;
    $productFeed = '';
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazon($SKU);
      if ( is_object($obj) ) {
        $productFeed .= "<Message>\n\t".
                          "<MessageID>".$messageCounter."</MessageID>".
                          "<OperationType>Update</OperationType>\n\t".
                          $obj->toProductXML()."\n".
                        "</Message>\n";
      }
    }
    
    $feedStr = '';
    if ( !empty($productFeed) ) {
      $feedStr = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".
                 '<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n".
                   AmazonHeader::toXML().
                   '<MessageType>Product</MessageType>'.
                   $productFeed.
                 '</AmazonEnvelope>'."\n";
    }
    return $feedStr;
  }

  function markNewAsAdded(){
    $rs = new RecordSetProxy();
    foreach( $this->newSKUS as $sku ) {
      $check = $rs->fetchOne("SELECT count(*) as c from ".TABLE_AMAZON_PRODUCTS." where product_sku='".$rs->esc($sku)."'");
      if ( (int)$check==0 ) {
        $rs->insert(
          TABLE_AMAZON_PRODUCTS,
          array(
            'product_sku'=>$sku,
            'product_state'=>'N'
          )
        );
      }
    }
  }

  function kickOff( $skuArr ){
    if ( count($skuArr)>0 ) {
      $rs = new RecordSetProxy();
      foreach($skuArr as $idx=>$sku) { $skuArr[$idx]=$rs->esc($sku); }
      $rs->query("DELETE FROM ".TABLE_AMAZON_PRODUCTS." WHERE product_sku IN ('".implode("','", $skuArr)."')");
    }
  }
  
  function makePricingFeed(){
    $pList = new proxyProduct();
    $skus = $pList->getActiveList();
    $messageCounter = 1;
    $productFeed = '';
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazonPI($SKU);
      if ( is_object($obj) ) {
        $productFeed .= "<Message>\n\t".
                          "<MessageID>".$messageCounter."</MessageID>".
                          "<OperationType>Update</OperationType>\n\t".
                          $obj->toPriceXML()."\n".
                        "</Message>\n";
        $messageCounter++;
      }
    }
    $skus = $pList->getNotActiveList();
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazonPI($SKU);
      if ( is_object($obj) ) {
        $productFeed .= "<Message>\n\t".
                          "<MessageID>".$messageCounter."</MessageID>".
                          "<OperationType>Delete</OperationType>\n\t".
                          $obj->toPriceXML()."\n".
                        "</Message>\n";
        $messageCounter++;
      }
    }
    $feedStr = '';
    if ( !empty($productFeed) ) {
      $feedStr = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".
                 '<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n".
                   AmazonHeader::toXML().
                   '<MessageType>Price</MessageType>'.
                   $productFeed.
                 '</AmazonEnvelope>'."\n";
    }
    return $feedStr;
  }

  function makeInventoryFeed(){
    $pList = new proxyProduct();
    $skus = $pList->getActiveList();
    $messageCounter = 1;
    $productFeed = '';
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazonPI($SKU);
      if ( is_object($obj) ) {
        $productFeed .= "<Message>\n\t".
                          "<MessageID>".$messageCounter."</MessageID>".
                          "<OperationType>Update</OperationType>\n\t".
                          $obj->toInventoryXML()."\n".
                        "</Message>\n";
        $messageCounter++;
      }
    }
    $skus = $pList->getNotActiveList();
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazonPI($SKU);
      if ( is_object($obj) ) {
        $productFeed .= "<Message>\n\t".
                          "<MessageID>".$messageCounter."</MessageID>".
                          "<OperationType>Delete</OperationType>\n\t".
                          $obj->toInventoryXML()."\n".
                        "</Message>\n";
        $messageCounter++;
      }
    }
    $feedStr = '';
    if ( !empty($productFeed) ) {
      $feedStr = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".
                 '<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n".
                   AmazonHeader::toXML().
                   '<MessageType>Inventory</MessageType>'.
                   $productFeed.
                 '</AmazonEnvelope>'."\n";
    }
    return $feedStr;
  }

  function makeImageFeed(){
    $pList = new proxyProduct();
    $skus = $pList->getActiveList();
    $messageCounter = 1;
    $productFeed = '';
    foreach( $skus as $SKU ) {
      $obj = $pList->forAmazonIMG($SKU);
      if ( is_object($obj) ) {
        $arrXML = $obj->toImageXML();
        foreach( $arrXML as $subStr ) {
          $productFeed .= "<Message>\n\t".
                            "<MessageID>".$messageCounter."</MessageID>".
                            "<OperationType>Update</OperationType>\n\t".
                            $subStr ."\n".
                          "</Message>\n";
          $messageCounter++;
        }
      }
    }
    $feedStr = '';
    if ( !empty($productFeed) ) {
      $feedStr = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n".
                 '<AmazonEnvelope xsi:noNamespaceSchemaLocation="amzn-envelope.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n".
                   AmazonHeader::toXML().
                   '<MessageType>ProductImage</MessageType>'.
                   $productFeed.
                 '</AmazonEnvelope>'."\n";
    }
    return $feedStr;
  }
}

?>
