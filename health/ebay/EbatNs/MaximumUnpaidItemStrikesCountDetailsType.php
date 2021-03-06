<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class MaximumUnpaidItemStrikesCountDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var int $Count
	var $Count;
	// end props

/**
 *

 * @return int
 * @param  $index 
 */
	function getCount($index = null)
	{
		if ($index) {
		return $this->Count[$index];
	} else {
		return $this->Count;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setCount($value, $index = null)
	{
		if ($index) {
	$this->Count[$index] = $value;
	} else {
	$this->Count = $value;
	}

	}
/**
 *

 * @return 
 */
	function MaximumUnpaidItemStrikesCountDetailsType()
	{
		$this->EbatNs_ComplexType('MaximumUnpaidItemStrikesCountDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Count' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
