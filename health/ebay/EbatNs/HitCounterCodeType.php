<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class HitCounterCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $NoHitCounter
	var $NoHitCounter = 'NoHitCounter';
	// @var string $HonestyStyle
	var $HonestyStyle = 'HonestyStyle';
	// @var string $GreenLED
	var $GreenLED = 'GreenLED';
	// @var string $Hidden
	var $Hidden = 'Hidden';
	// @var string $BasicStyle
	var $BasicStyle = 'BasicStyle';
	// @var string $RetroStyle
	var $RetroStyle = 'RetroStyle';
	// @var string $HiddenStyle
	var $HiddenStyle = 'HiddenStyle';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function HitCounterCodeType()
	{
		$this->EbatNs_FacetType('HitCounterCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_HitCounterCodeType = new HitCounterCodeType();

?>
