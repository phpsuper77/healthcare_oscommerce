<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SellingManagerAutoSecondChanceOfferTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $BidsGreaterThanAmount
	var $BidsGreaterThanAmount = 'BidsGreaterThanAmount';
	// @var string $BidsGreaterThanCostPlusAmount
	var $BidsGreaterThanCostPlusAmount = 'BidsGreaterThanCostPlusAmount';
	// @var string $BidsGreaterThanCostPlusPercentage
	var $BidsGreaterThanCostPlusPercentage = 'BidsGreaterThanCostPlusPercentage';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SellingManagerAutoSecondChanceOfferTypeCodeType()
	{
		$this->EbatNs_FacetType('SellingManagerAutoSecondChanceOfferTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SellingManagerAutoSecondChanceOfferTypeCodeType = new SellingManagerAutoSecondChanceOfferTypeCodeType();

?>