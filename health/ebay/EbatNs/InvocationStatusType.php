<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class InvocationStatusType extends EbatNs_FacetType
{
	// start props
	// @var string $InProgress
	var $InProgress = 'InProgress';
	// @var string $Success
	var $Success = 'Success';
	// @var string $Failure
	var $Failure = 'Failure';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function InvocationStatusType()
	{
		$this->EbatNs_FacetType('InvocationStatusType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_InvocationStatusType = new InvocationStatusType();

?>
