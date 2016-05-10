<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ListingDurationDefinitionType.php';
require_once 'EbatNs_ComplexType.php';

class ListingDurationDefinitionsType extends EbatNs_ComplexType
{
	// start props
	// @var ListingDurationDefinitionType $ListingDuration
	var $ListingDuration;
	// end props

/**
 *

 * @return ListingDurationDefinitionType
 * @param  $index 
 */
	function getListingDuration($index = null)
	{
		if ($index) {
		return $this->ListingDuration[$index];
	} else {
		return $this->ListingDuration;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setListingDuration($value, $index = null)
	{
		if ($index) {
	$this->ListingDuration[$index] = $value;
	} else {
	$this->ListingDuration = $value;
	}

	}
/**
 *

 * @return 
 */
	function ListingDurationDefinitionsType()
	{
		$this->EbatNs_ComplexType('ListingDurationDefinitionsType', 'http://www.w3.org/2001/XMLSchema');
		$this->_elements = array_merge($this->_elements,
			array(
				'ListingDuration' =>
				array(
					'required' => false,
					'type' => 'ListingDurationDefinitionType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));
	$this->_attributes = array_merge($this->_attributes,
		array(
			'Version' =>
			array(
				'name' => 'Version',
				'type' => 'int',
				'use' => 'required'
			)
		));

	}
}
?>
