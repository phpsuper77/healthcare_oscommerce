<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class NameValueRelationshipType extends EbatNs_ComplexType
{
	// start props
	// @var string $ParentName
	var $ParentName;
	// @var string $ParentValue
	var $ParentValue;
	// end props

/**
 *

 * @return string
 */
	function getParentName()
	{
		return $this->ParentName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setParentName($value)
	{
		$this->ParentName = $value;
	}
/**
 *

 * @return string
 */
	function getParentValue()
	{
		return $this->ParentValue;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setParentValue($value)
	{
		$this->ParentValue = $value;
	}
/**
 *

 * @return 
 */
	function NameValueRelationshipType()
	{
		$this->EbatNs_ComplexType('NameValueRelationshipType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ParentName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ParentValue' =>
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
