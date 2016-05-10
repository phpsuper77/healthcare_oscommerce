<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'NameValueListArrayType.php';
require_once 'VariationsType.php';

class SellingManagerProductSpecificsType extends EbatNs_ComplexType
{
	// start props
	// @var string $PrimaryCategoryID
	var $PrimaryCategoryID;
	// @var VariationsType $Variations
	var $Variations;
	// @var NameValueListArrayType $ItemSpecifics
	var $ItemSpecifics;
	// end props

/**
 *

 * @return string
 */
	function getPrimaryCategoryID()
	{
		return $this->PrimaryCategoryID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPrimaryCategoryID($value)
	{
		$this->PrimaryCategoryID = $value;
	}
/**
 *

 * @return VariationsType
 */
	function getVariations()
	{
		return $this->Variations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVariations($value)
	{
		$this->Variations = $value;
	}
/**
 *

 * @return NameValueListArrayType
 */
	function getItemSpecifics()
	{
		return $this->ItemSpecifics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItemSpecifics($value)
	{
		$this->ItemSpecifics = $value;
	}
/**
 *

 * @return 
 */
	function SellingManagerProductSpecificsType()
	{
		$this->EbatNs_ComplexType('SellingManagerProductSpecificsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'PrimaryCategoryID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Variations' =>
				array(
					'required' => false,
					'type' => 'VariationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ItemSpecifics' =>
				array(
					'required' => false,
					'type' => 'NameValueListArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
