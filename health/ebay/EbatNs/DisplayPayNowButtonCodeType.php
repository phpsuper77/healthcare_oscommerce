<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class DisplayPayNowButtonCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $ShowPayNowButtonForAllPaymentMethods
	var $ShowPayNowButtonForAllPaymentMethods = 'ShowPayNowButtonForAllPaymentMethods';
	// @var string $ShowPayNowButtonForPayPalOnly
	var $ShowPayNowButtonForPayPalOnly = 'ShowPayNowButtonForPayPalOnly';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function DisplayPayNowButtonCodeType()
	{
		$this->EbatNs_FacetType('DisplayPayNowButtonCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_DisplayPayNowButtonCodeType = new DisplayPayNowButtonCodeType();

?>
