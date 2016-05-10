<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SearchFlagsCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Charity
	var $Charity = 'Charity';
	// @var string $SearchInDescription
	var $SearchInDescription = 'SearchInDescription';
	// @var string $PayPalBuyerPaymentOption
	var $PayPalBuyerPaymentOption = 'PayPalBuyerPaymentOption';
	// @var string $NowAndNew
	var $NowAndNew = 'NowAndNew';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SearchFlagsCodeType()
	{
		$this->EbatNs_FacetType('SearchFlagsCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SearchFlagsCodeType = new SearchFlagsCodeType();

?>
