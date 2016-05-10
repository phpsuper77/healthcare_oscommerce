<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonProductCEConsumerElectronics extends AmazonBaseProduct{
  
  function getProductDataXML(){
    $xml='';
    $xml .= '<CE>';
    $xml .=   '<ProductType><ConsumerElectronics></ConsumerElectronics></ProductType>';
    $xml .= '</CE>';
    return $xml;
  }
  
}

?>
