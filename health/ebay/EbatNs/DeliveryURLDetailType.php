<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'EnableCodeType.php';

class DeliveryURLDetailType extends EbatNs_ComplexType
{
	// start props
	// @var string $DeliveryURLName
	var $DeliveryURLName;
	// @var anyURI $DeliveryURL
	var $DeliveryURL;
	// @var EnableCodeType $Status
	var $Status;
	// end props

/**
 *

 * @return string
 */
	function getDeliveryURLName()
	{
		return $this->DeliveryURLName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDeliveryURLName($value)
	{
		$this->DeliveryURLName = $value;
	}
/**
 *

 * @return anyURI
 */
	function getDeliveryURL()
	{
		return $this->DeliveryURL;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDeliveryURL($value)
	{
		$this->DeliveryURL = $value;
	}
/**
 *

 * @return EnableCodeType
 */
	function getStatus()
	{
		return $this->Status;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStatus($value)
	{
		$this->Status = $value;
	}
/**
 *

 * @return 
 */
	function DeliveryURLDetailType()
	{
		$this->EbatNs_ComplexType('DeliveryURLDetailType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'DeliveryURLName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DeliveryURL' =>
				array(
					'required' => false,
					'type' => 'anyURI',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Status' =>
				array(
					'required' => false,
					'type' => 'EnableCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>