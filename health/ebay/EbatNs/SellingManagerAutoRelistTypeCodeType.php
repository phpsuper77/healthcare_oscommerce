<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SellingManagerAutoRelistTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $RelistOnceIfNotSold
	var $RelistOnceIfNotSold = 'RelistOnceIfNotSold';
	// @var string $RelistContinuouslyUntilSold
	var $RelistContinuouslyUntilSold = 'RelistContinuouslyUntilSold';
	// @var string $RelistContinuously
	var $RelistContinuously = 'RelistContinuously';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SellingManagerAutoRelistTypeCodeType()
	{
		$this->EbatNs_FacetType('SellingManagerAutoRelistTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SellingManagerAutoRelistTypeCodeType = new SellingManagerAutoRelistTypeCodeType();

?>
