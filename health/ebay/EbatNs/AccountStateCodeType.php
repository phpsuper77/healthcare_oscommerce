<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class AccountStateCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Active
	var $Active = 'Active';
	// @var string $Pending
	var $Pending = 'Pending';
	// @var string $Inactive
	var $Inactive = 'Inactive';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function AccountStateCodeType()
	{
		$this->EbatNs_FacetType('AccountStateCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_AccountStateCodeType = new AccountStateCodeType();

?>