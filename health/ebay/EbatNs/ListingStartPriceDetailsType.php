<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ListingTypeCodeType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'AmountType.php';

class ListingStartPriceDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var string $Description
	var $Description;
	// @var ListingTypeCodeType $ListingType
	var $ListingType;
	// @var AmountType $StartPrice
	var $StartPrice;
	// end props

/**
 *

 * @return string
 */
	function getDescription()
	{
		return $this->Description;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDescription($value)
	{
		$this->Description = $value;
	}
/**
 *

 * @return ListingTypeCodeType
 */
	function getListingType()
	{
		return $this->ListingType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setListingType($value)
	{
		$this->ListingType = $value;
	}
/**
 *

 * @return AmountType
 */
	function getStartPrice()
	{
		return $this->StartPrice;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStartPrice($value)
	{
		$this->StartPrice = $value;
	}
/**
 *

 * @return 
 */
	function ListingStartPriceDetailsType()
	{
		$this->EbatNs_ComplexType('ListingStartPriceDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Description' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ListingType' =>
				array(
					'required' => false,
					'type' => 'ListingTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StartPrice' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
