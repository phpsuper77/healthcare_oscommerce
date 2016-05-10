<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class ListingEnhancementDurationReferenceType extends EbatNs_ComplexType
{
	// start props
	// @var token $Duration
	var $Duration;
	// end props

/**
 *

 * @return token
 * @param  $index 
 */
	function getDuration($index = null)
	{
		if ($index) {
		return $this->Duration[$index];
	} else {
		return $this->Duration;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setDuration($value, $index = null)
	{
		if ($index) {
	$this->Duration[$index] = $value;
	} else {
	$this->Duration = $value;
	}

	}
/**
 *

 * @return 
 */
	function ListingEnhancementDurationReferenceType()
	{
		$this->EbatNs_ComplexType('ListingEnhancementDurationReferenceType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Duration' =>
				array(
					'required' => false,
					'type' => 'token',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
