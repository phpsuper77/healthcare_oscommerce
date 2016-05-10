<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractResponseType.php';
require_once 'StorePreferencesType.php';

class GetStorePreferencesResponseType extends AbstractResponseType
{
	// start props
	// @var StorePreferencesType $StorePreferences
	var $StorePreferences;
	// end props

/**
 *

 * @return StorePreferencesType
 */
	function getStorePreferences()
	{
		return $this->StorePreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStorePreferences($value)
	{
		$this->StorePreferences = $value;
	}
/**
 *

 * @return 
 */
	function GetStorePreferencesResponseType()
	{
		$this->AbstractResponseType('GetStorePreferencesResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'StorePreferences' =>
				array(
					'required' => false,
					'type' => 'StorePreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
