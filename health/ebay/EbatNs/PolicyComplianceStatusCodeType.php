<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PolicyComplianceStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Good
	var $Good = 'Good';
	// @var string $Fair
	var $Fair = 'Fair';
	// @var string $Poor
	var $Poor = 'Poor';
	// @var string $Failing
	var $Failing = 'Failing';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PolicyComplianceStatusCodeType()
	{
		$this->EbatNs_FacetType('PolicyComplianceStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PolicyComplianceStatusCodeType = new PolicyComplianceStatusCodeType();

?>
