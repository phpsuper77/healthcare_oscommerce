<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class ProductStateCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Update
	var $Update = 'Update';
	// @var string $UpdateMajor
	var $UpdateMajor = 'UpdateMajor';
	// @var string $UpdateNoDetails
	var $UpdateNoDetails = 'UpdateNoDetails';
	// @var string $Merge
	var $Merge = 'Merge';
	// @var string $Delete
	var $Delete = 'Delete';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function ProductStateCodeType()
	{
		$this->EbatNs_FacetType('ProductStateCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_ProductStateCodeType = new ProductStateCodeType();

?>
