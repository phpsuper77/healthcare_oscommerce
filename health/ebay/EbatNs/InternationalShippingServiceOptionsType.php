<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'AmountType.php';

class InternationalShippingServiceOptionsType extends EbatNs_ComplexType
{
	// start props
	// @var token $ShippingService
	var $ShippingService;
	// @var AmountType $ShippingServiceCost
	var $ShippingServiceCost;
	// @var AmountType $ShippingServiceAdditionalCost
	var $ShippingServiceAdditionalCost;
	// @var int $ShippingServicePriority
	var $ShippingServicePriority;
	// @var string $ShipToLocation
	var $ShipToLocation;
	// @var AmountType $ShippingInsuranceCost
	var $ShippingInsuranceCost;
	// end props

/**
 *

 * @return token
 */
	function getShippingService()
	{
		return $this->ShippingService;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingService($value)
	{
		$this->ShippingService = $value;
	}
/**
 *

 * @return AmountType
 */
	function getShippingServiceCost()
	{
		return $this->ShippingServiceCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingServiceCost($value)
	{
		$this->ShippingServiceCost = $value;
	}
/**
 *

 * @return AmountType
 */
	function getShippingServiceAdditionalCost()
	{
		return $this->ShippingServiceAdditionalCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingServiceAdditionalCost($value)
	{
		$this->ShippingServiceAdditionalCost = $value;
	}
/**
 *

 * @return int
 */
	function getShippingServicePriority()
	{
		return $this->ShippingServicePriority;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingServicePriority($value)
	{
		$this->ShippingServicePriority = $value;
	}
/**
 *

 * @return string
 * @param  $index 
 */
	function getShipToLocation($index = null)
	{
		if ($index) {
		return $this->ShipToLocation[$index];
	} else {
		return $this->ShipToLocation;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShipToLocation($value, $index = null)
	{
		if ($index) {
	$this->ShipToLocation[$index] = $value;
	} else {
	$this->ShipToLocation = $value;
	}

	}
/**
 *

 * @return AmountType
 */
	function getShippingInsuranceCost()
	{
		return $this->ShippingInsuranceCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingInsuranceCost($value)
	{
		$this->ShippingInsuranceCost = $value;
	}
/**
 *

 * @return 
 */
	function InternationalShippingServiceOptionsType()
	{
		$this->EbatNs_ComplexType('InternationalShippingServiceOptionsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ShippingService' =>
				array(
					'required' => false,
					'type' => 'token',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingServiceCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingServiceAdditionalCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingServicePriority' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShipToLocation' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingInsuranceCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
