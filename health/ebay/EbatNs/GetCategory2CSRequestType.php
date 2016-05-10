<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractRequestType.php';

class GetCategory2CSRequestType extends AbstractRequestType
{
	// start props
	// @var string $CategoryID
	var $CategoryID;
	// @var string $AttributeSystemVersion
	var $AttributeSystemVersion;
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

 * @return string
 */
	function getAttributeSystemVersion()
	{
		return $this->AttributeSystemVersion;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAttributeSystemVersion($value)
	{
		$this->AttributeSystemVersion = $value;
	}
/**
 *

 * @return 
 */
	function GetCategory2CSRequestType()
	{
		$this->AbstractRequestType('GetCategory2CSRequestType', 'urn:ebay:apis:eBLBaseComponents');
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
				'AttributeSystemVersion' =>
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
