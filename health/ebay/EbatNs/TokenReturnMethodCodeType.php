<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class TokenReturnMethodCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Redirect
	var $Redirect = 'Redirect';
	// @var string $FetchToken
	var $FetchToken = 'FetchToken';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function TokenReturnMethodCodeType()
	{
		$this->EbatNs_FacetType('TokenReturnMethodCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_TokenReturnMethodCodeType = new TokenReturnMethodCodeType();

?>
