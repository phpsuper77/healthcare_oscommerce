<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class CalculatedShippingChargeOptionCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $ChargeEachItem
	var $ChargeEachItem = 'ChargeEachItem';
	// @var string $ChargeEntireOrder
	var $ChargeEntireOrder = 'ChargeEntireOrder';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function CalculatedShippingChargeOptionCodeType()
	{
		$this->EbatNs_FacetType('CalculatedShippingChargeOptionCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_CalculatedShippingChargeOptionCodeType = new CalculatedShippingChargeOptionCodeType();

?>