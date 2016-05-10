<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'SearchResultItemType.php';

class ExpansionArrayType extends EbatNs_ComplexType
{
	// start props
	// @var SearchResultItemType $ExpansionItem
	var $ExpansionItem;
	// @var int $TotalAvailable
	var $TotalAvailable;
	// end props

/**
 *

 * @return SearchResultItemType
 * @param  $index 
 */
	function getExpansionItem($index = null)
	{
		if ($index) {
		return $this->ExpansionItem[$index];
	} else {
		return $this->ExpansionItem;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setExpansionItem($value, $index = null)
	{
		if ($index) {
	$this->ExpansionItem[$index] = $value;
	} else {
	$this->ExpansionItem = $value;
	}

	}
/**
 *

 * @return int
 */
	function getTotalAvailable()
	{
		return $this->TotalAvailable;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTotalAvailable($value)
	{
		$this->TotalAvailable = $value;
	}
/**
 *

 * @return 
 */
	function ExpansionArrayType()
	{
		$this->EbatNs_ComplexType('ExpansionArrayType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ExpansionItem' =>
				array(
					'required' => false,
					'type' => 'SearchResultItemType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'TotalAvailable' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
