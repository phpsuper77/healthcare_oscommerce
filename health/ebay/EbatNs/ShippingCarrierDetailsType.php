<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'ShippingCarrierCodeType.php';

class ShippingCarrierDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var int $ShippingCarrierID
	var $ShippingCarrierID;
	// @var string $Description
	var $Description;
	// @var ShippingCarrierCodeType $ShippingCarrier
	var $ShippingCarrier;
	// end props

/**
 *

 * @return int
 */
	function getShippingCarrierID()
	{
		return $this->ShippingCarrierID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingCarrierID($value)
	{
		$this->ShippingCarrierID = $value;
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

 * @return ShippingCarrierCodeType
 */
	function getShippingCarrier()
	{
		return $this->ShippingCarrier;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingCarrier($value)
	{
		$this->ShippingCarrier = $value;
	}
/**
 *

 * @return 
 */
	function ShippingCarrierDetailsType()
	{
		$this->EbatNs_ComplexType('ShippingCarrierDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ShippingCarrierID' =>
				array(
					'required' => false,
					'type' => 'int',
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
				),
				'ShippingCarrier' =>
				array(
					'required' => false,
					'type' => 'ShippingCarrierCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
