<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SellingManagerEmailSentStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Successful
	var $Successful = 'Successful';
	// @var string $Failed
	var $Failed = 'Failed';
	// @var string $Pending
	var $Pending = 'Pending';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SellingManagerEmailSentStatusCodeType()
	{
		$this->EbatNs_FacetType('SellingManagerEmailSentStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SellingManagerEmailSentStatusCodeType = new SellingManagerEmailSentStatusCodeType();

?>
