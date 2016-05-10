<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'UserIDType.php';
require_once 'AbstractRequestType.php';

class GetMessagePreferencesRequestType extends AbstractRequestType
{
	// start props
	// @var UserIDType $SellerID
	var $SellerID;
	// @var boolean $IncludeASQPreferences
	var $IncludeASQPreferences;
	// end props

/**
 *

 * @return UserIDType
 */
	function getSellerID()
	{
		return $this->SellerID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerID($value)
	{
		$this->SellerID = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeASQPreferences()
	{
		return $this->IncludeASQPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeASQPreferences($value)
	{
		$this->IncludeASQPreferences = $value;
	}
/**
 *

 * @return 
 */
	function GetMessagePreferencesRequestType()
	{
		$this->AbstractRequestType('GetMessagePreferencesRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SellerID' =>
				array(
					'required' => false,
					'type' => 'UserIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeASQPreferences' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
