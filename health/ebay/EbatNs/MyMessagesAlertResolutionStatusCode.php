<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class MyMessagesAlertResolutionStatusCode extends EbatNs_FacetType
{
	// start props
	// @var string $Unresolved
	var $Unresolved = 'Unresolved';
	// @var string $ResolvedByAutoResolution
	var $ResolvedByAutoResolution = 'ResolvedByAutoResolution';
	// @var string $ResolvedByUser
	var $ResolvedByUser = 'ResolvedByUser';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function MyMessagesAlertResolutionStatusCode()
	{
		$this->EbatNs_FacetType('MyMessagesAlertResolutionStatusCode', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_MyMessagesAlertResolutionStatusCode = new MyMessagesAlertResolutionStatusCode();

?>
