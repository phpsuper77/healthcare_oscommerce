<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PromotionalSaleTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $PriceDiscountOnly
	var $PriceDiscountOnly = 'PriceDiscountOnly';
	// @var string $FreeShippingOnly
	var $FreeShippingOnly = 'FreeShippingOnly';
	// @var string $PriceDiscountAndFreeShipping
	var $PriceDiscountAndFreeShipping = 'PriceDiscountAndFreeShipping';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PromotionalSaleTypeCodeType()
	{
		$this->EbatNs_FacetType('PromotionalSaleTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PromotionalSaleTypeCodeType = new PromotionalSaleTypeCodeType();

?>
