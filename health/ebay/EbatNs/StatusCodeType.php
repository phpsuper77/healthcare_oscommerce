<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class StatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Active
	var $Active = 'Active';
	// @var string $Inactive
	var $Inactive = 'Inactive';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function StatusCodeType()
	{
		$this->EbatNs_FacetType('StatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_StatusCodeType = new StatusCodeType();

?>