<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SellingManagerShippedStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Shipped
	var $Shipped = 'Shipped';
	// @var string $Unshipped
	var $Unshipped = 'Unshipped';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SellingManagerShippedStatusCodeType()
	{
		$this->EbatNs_FacetType('SellingManagerShippedStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SellingManagerShippedStatusCodeType = new SellingManagerShippedStatusCodeType();

?>