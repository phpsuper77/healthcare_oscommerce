<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'NameValueListArrayType.php';

class CategoryItemSpecificsType extends EbatNs_ComplexType
{
	// start props
	// @var string $CategoryID
	var $CategoryID;
	// @var NameValueListArrayType $ItemSpecifics
	var $ItemSpecifics;
	// end props

/**
 *

 * @return string
 */
	function getCategoryID()
	{
		return $this->CategoryID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCategoryID($value)
	{
		$this->CategoryID = $value;
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
	function CategoryItemSpecificsType()
	{
		$this->EbatNs_ComplexType('CategoryItemSpecificsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'CategoryID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
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
