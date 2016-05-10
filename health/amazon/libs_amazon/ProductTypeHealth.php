<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonProductHealth extends AmazonBaseProduct{
  
  function getProductDataXML(){
    $xml='';
    $xml .= '<Health>';
    if ($this->_subType=='Health-PersonalCareAppliances') {
      $xml .=   '<ProductType><PersonalCareAppliances></PersonalCareAppliances></ProductType>';
    }else{
      $xml .=   '<ProductType><HealthMisc></HealthMisc></ProductType>';
    }
    $xml .= '</Health>';
    return $xml;
  }
  
}

?>
