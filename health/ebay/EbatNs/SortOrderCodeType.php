<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SortOrderCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Ascending
	var $Ascending = 'Ascending';
	// @var string $Descending
	var $Descending = 'Descending';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SortOrderCodeType()
	{
		$this->EbatNs_FacetType('SortOrderCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SortOrderCodeType = new SortOrderCodeType();

?>
