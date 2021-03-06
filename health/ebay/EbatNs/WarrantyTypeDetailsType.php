<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class WarrantyTypeDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var token $WarrantyTypeOption
	var $WarrantyTypeOption;
	// @var string $Description
	var $Description;
	// end props

/**
 *

 * @return token
 */
	function getWarrantyTypeOption()
	{
		return $this->WarrantyTypeOption;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setWarrantyTypeOption($value)
	{
		$this->WarrantyTypeOption = $value;
	}
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

 * @return 
 */
	function WarrantyTypeDetailsType()
	{
		$this->EbatNs_ComplexType('WarrantyTypeDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'WarrantyTypeOption' =>
				array(
					'required' => false,
					'type' => 'token',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Description' =>
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
