<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonProductBeauty extends AmazonBaseProduct{
  
  function getProductDataXML(){
    $xml='';
    $xml .= '<Beauty>';
    $xml .=   '<ProductType><BeautyMisc></BeautyMisc></ProductType>';
    $xml .= '</Beauty>';
    return $xml;
  }
  
}

?>
