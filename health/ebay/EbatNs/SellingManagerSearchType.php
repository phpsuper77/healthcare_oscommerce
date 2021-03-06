<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'SellingManagerSearchTypeCodeType.php';

class SellingManagerSearchType extends EbatNs_ComplexType
{
	// start props
	// @var SellingManagerSearchTypeCodeType $SearchType
	var $SearchType;
	// @var string $SearchValue
	var $SearchValue;
	// end props

/**
 *

 * @return SellingManagerSearchTypeCodeType
 */
	function getSearchType()
	{
		return $this->SearchType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSearchType($value)
	{
		$this->SearchType = $value;
	}
/**
 *

 * @return string
 */
	function getSearchValue()
	{
		return $this->SearchValue;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSearchValue($value)
	{
		$this->SearchValue = $value;
	}
/**
 *

 * @return 
 */
	function SellingManagerSearchType()
	{
		$this->EbatNs_ComplexType('SellingManagerSearchType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SearchType' =>
				array(
					'required' => false,
					'type' => 'SellingManagerSearchTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SearchValue' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
