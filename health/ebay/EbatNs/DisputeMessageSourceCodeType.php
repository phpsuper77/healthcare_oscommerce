<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class DisputeMessageSourceCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Buyer
	var $Buyer = 'Buyer';
	// @var string $Seller
	var $Seller = 'Seller';
	// @var string $eBay
	var $eBay = 'eBay';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function DisputeMessageSourceCodeType()
	{
		$this->EbatNs_FacetType('DisputeMessageSourceCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_DisputeMessageSourceCodeType = new DisputeMessageSourceCodeType();

?>
