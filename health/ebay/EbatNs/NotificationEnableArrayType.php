<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'NotificationEnableType.php';

class NotificationEnableArrayType extends EbatNs_ComplexType
{
	// start props
	// @var NotificationEnableType $NotificationEnable
	var $NotificationEnable;
	// end props

/**
 *

 * @return NotificationEnableType
 * @param  $index 
 */
	function getNotificationEnable($index = null)
	{
		if ($index) {
		return $this->NotificationEnable[$index];
	} else {
		return $this->NotificationEnable;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setNotificationEnable($value, $index = null)
	{
		if ($index) {
	$this->NotificationEnable[$index] = $value;
	} else {
	$this->NotificationEnable = $value;
	}

	}
/**
 *

 * @return 
 */
	function NotificationEnableArrayType()
	{
		$this->EbatNs_ComplexType('NotificationEnableArrayType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'NotificationEnable' =>
				array(
					'required' => false,
					'type' => 'NotificationEnableType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
