<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class AddressStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $None
	var $None = 'None';
	// @var string $Confirmed
	var $Confirmed = 'Confirmed';
	// @var string $Unconfirmed
	var $Unconfirmed = 'Unconfirmed';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function AddressStatusCodeType()
	{
		$this->EbatNs_FacetType('AddressStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_AddressStatusCodeType = new AddressStatusCodeType();

?>
