<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class LogoTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $WinningBidderNotice
	var $WinningBidderNotice = 'WinningBidderNotice';
	// @var string $Store
	var $Store = 'Store';
	// @var string $Custom
	var $Custom = 'Custom';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function LogoTypeCodeType()
	{
		$this->EbatNs_FacetType('LogoTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_LogoTypeCodeType = new LogoTypeCodeType();

?>