<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class StringMatchCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// @var string $StartsWith
	var $StartsWith = 'StartsWith';
	// @var string $Contains
	var $Contains = 'Contains';
	// end props

/**
 *

 * @return 
 */
	function StringMatchCodeType()
	{
		$this->EbatNs_FacetType('StringMatchCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_StringMatchCodeType = new StringMatchCodeType();

?>
