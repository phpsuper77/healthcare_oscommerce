<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class StoreItemListSortOrderCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $EndingFirst
	var $EndingFirst = 'EndingFirst';
	// @var string $NewlyListed
	var $NewlyListed = 'NewlyListed';
	// @var string $LowestPriced
	var $LowestPriced = 'LowestPriced';
	// @var string $HighestPriced
	var $HighestPriced = 'HighestPriced';
	// @var string $LowestPricedPlusShipping
	var $LowestPricedPlusShipping = 'LowestPricedPlusShipping';
	// @var string $HighestPricedPlusShipping
	var $HighestPricedPlusShipping = 'HighestPricedPlusShipping';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function StoreItemListSortOrderCodeType()
	{
		$this->EbatNs_FacetType('StoreItemListSortOrderCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_StoreItemListSortOrderCodeType = new StoreItemListSortOrderCodeType();

?>
