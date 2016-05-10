<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonBaseProduct{
  var $_subType = '';
  var $SKU = '';
  var $ProductType = 'ASIN';
  var $ProductValue = '';
  var $ReleaseDate = null;
  var $ConditionType = '';
  var $ConditionNote = null;
  var $Title = '';
  var $Brand = null;
  var $Description = null;
  var $BulletPoint = array();
  var $Manufacturer = null;
  var $MfrPartNumber = null;
  var $SearchTerms = array();
  var $PlatinumKeywords = array();
  var $RecommendedBrowseNode1 = '';
  var $RecommendedBrowseNode2 = null;
  var $Quantity = 0;
  var $RestockDate = null;
  var $Images = array();
  /**
   * @var AmazonCurrency
   */
  var $Price;
  /**
   * @var AmazonCurrency
   */
  var $SalePrice;
  var $Sale_EndDate;

  function AmazonBaseProduct(){

  }
  /*
   * Override in product type
   */
  function getProductDataXML(){
    return '';
  }

  function toProductXML(){
    $xml = '';
    $xml .= '<Product>';
    $xml .=   '<SKU>'.axsd::normalizedString($this->SKU,1,40).'</SKU>';
    $xml .=   '<StandardProductID>'.
                '<Type>'.$this->ProductType.'</Type>'.
                '<Value>'.$this->ProductValue.'</Value>'.
              '</StandardProductID>';
    if ( !empty($this->ReleaseDate) ) {
      $xml .= '<ReleaseDate>'.axsd::dateTime($this->ReleaseDate).'</ReleaseDate>';
    }
    if ( !empty($this->ConditionType) ) {
      $xml .= '<Condition>'.
                '<ConditionType>'.$this->ConditionType.'</ConditionType>'.
                ( !empty($this->ConditionNote)?'<ConditionNote>'.axsd::SuperLongStringNotNull($this->ConditionNote).'</ConditionNote>':'' ).
              '</Condition>';
    }
    $xml .=   '<DescriptionData>';
    $xml .=     '<Title>'.axsd::LongStringNotNull($this->Title).'</Title>';
    if ( !empty($this->Brand) ) {
      $xml .=   '<Brand>'.axsd::StringNotNull($this->Brand).'</Brand>';
    }
    $xml .=     '<Description>'.axsd::normalizedString($this->Description,1,2000).'</Description>';
    if ( is_array($this->BulletPoint) ) {
      $xsdLimit=5;
      foreach( $this->BulletPoint as $strBulletPoint ) {
        if ( !empty($strBulletPoint) ) {
          $xml .= '<BulletPoint>'.axsd::LongStringNotNull($strBulletPoint).'</BulletPoint>';
          $xsdLimit--;
          if ($xsdLimit==0) break;
        }
      }
    }

    /*
    					<xsd:element name="ItemDimensions" type="Dimensions" minOccurs="0"/>
							<xsd:element name="PackageDimensions" type="SpatialDimensions" minOccurs="0"/>
							<xsd:element name="PackageWeight" type="PositiveWeightDimension" minOccurs="0"/>
							<xsd:element name="ShippingWeight" type="PositiveWeightDimension" minOccurs="0"/>
    */

    if ( !empty($this->Manufacturer) ) {
      $xml .=   '<Manufacturer>'.axsd::StringNotNull($this->Manufacturer).'</Manufacturer>';
    }
    if ( !empty($this->MfrPartNumber) ) {
      $xml .=   '<MfrPartNumber>'.dataFilter::FortyStringNotNull($this->MfrPartNumber).'</MfrPartNumber>';
    }
    if ( is_array($this->SearchTerms) ) {
      $xsdLimit=5;
      foreach( $this->SearchTerms as $strSearchTerms ) {
        if ( !empty($strSearchTerms) ) {
          $xml .= '<SearchTerms>'.axsd::LongStringNotNull($strSearchTerms).'</SearchTerms>';
          $xsdLimit--;
          if ($xsdLimit==0) break;
        }
      }
    }
    if ( is_array($this->PlatinumKeywords) ) {
      $xsdLimit=20;
      foreach( $this->PlatinumKeywords as $strPlatinumKeywords ) {
        if ( !empty($strPlatinumKeywords) ) {
          $xml .= '<PlatinumKeywords>'.axsd::LongStringNotNull($strPlatinumKeywords).'</PlatinumKeywords>';
          $xsdLimit--;
          if ($xsdLimit==0) break;
        }
      }
    }
    if ( !empty($this->RecommendedBrowseNode1) ) {
      $xml .=   '<RecommendedBrowseNode>'.axsd::nonNegativeInteger($this->RecommendedBrowseNode1).'</RecommendedBrowseNode>';
    }
    if ( !empty($this->RecommendedBrowseNode2) ) {
      $xml .=   '<RecommendedBrowseNode>'.axsd::nonNegativeInteger($this->RecommendedBrowseNode2).'</RecommendedBrowseNode>';
    }

    $xml .=   '</DescriptionData>';
    $xml .=   '<ProductData>'.$this->getProductDataXML().'</ProductData>';
    $xml .= '</Product>';

    return $xml;
  }

  function toInventoryXML(){
    return
  	'<Inventory>'.
			'<SKU>'.axsd::normalizedString($this->SKU,1,40).'</SKU>'.
			'<Quantity>'.axsd::nonNegativeInteger($this->Quantity).'</Quantity>'.
			( !empty($this->RestockDate)? '<RestockDate>'.axsd::date($this->RestockDate).'</RestockDate>' : '' ) .
			'<SwitchFulfillmentTo>'.'MFN'.'</SwitchFulfillmentTo>'.
		'</Inventory>';
	}

  function toPriceXML(){
    if ( !is_object($this->Price) ) return '';
    return
		'<Price>'.
			'<SKU>'.axsd::normalizedString($this->SKU,1,40).'</SKU>'.
      $this->Price->toXML('StandardPrice').
			(
        is_object($this->SalePrice) && !empty($this->Sale_EndDate)?
        '<Sale>'.
          '<StartDate>'.axsd::dateTime( date('Y-m-d') ).'</StartDate>'.
          '<EndDate>'.axsd::dateTime( $this->Sale_EndDate ).'</EndDate>'.
          $this->SalePrice->toXML('SalePrice').
        '</Sale>':
        ''
      ).
		'</Price>';
	}

  function toImageXML(){
    $ret = array();
    $xml_image = array('Main','PT1','PT2','PT3','PT4','PT5','PT6','PT7','PT8');
    if ( is_array($this->Images) && count($this->Images)>0 ) {
      $counter = 0;
      foreach( $this->Images as $imgUrl ) {
        if ( !isset($xml_image[$counter]) ) break;
        $image_coded = preg_replace('/^\//', '', $imgUrl);
        $image_coded = str_replace(' ', '%20', $image_coded);
        $ret[] = '<ProductImage>'.
                   '<SKU>'.axsd::normalizedString($this->SKU,1,40).'</SKU>'.
                   '<ImageType>'.$xml_image[$counter].'</ImageType>'.
                   '<ImageLocation>'.AmazonConfig::getSiteUrl().'/'.$image_coded.'</ImageLocation>'.
                 '</ProductImage>';
        $counter++;
      }
    }
    return $ret;
  }
}

?>
