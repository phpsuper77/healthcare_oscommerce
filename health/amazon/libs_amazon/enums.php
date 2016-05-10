<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class ProductTypeEnum{
  function enum(){
    return array(
      'ASIN',
      'ISBN',
      'UPC',
      'EAN',
      'GTIN',
    );
  }
}

class ConditionTypeEnum{
  function enum(){
    return array(
      'New',
      'UsedLikeNew',
      'UsedVeryGood',
      'UsedGood',
      'UsedAcceptable',
      'CollectibleLikeNew',
      'CollectibleVeryGood',
      'CollectibleGood',
      'CollectibleAcceptable',
      'Refurbished',
      'Club',
    );
  }
}



?>
