<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class GeographicExposureCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $National
	var $National = 'National';
	// @var string $LocalOnly
	var $LocalOnly = 'LocalOnly';
	// @var string $LocalOptional
	var $LocalOptional = 'LocalOptional';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function GeographicExposureCodeType()
	{
		$this->EbatNs_FacetType('GeographicExposureCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_GeographicExposureCodeType = new GeographicExposureCodeType();

?>