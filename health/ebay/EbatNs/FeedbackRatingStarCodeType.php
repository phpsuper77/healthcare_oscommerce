<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class FeedbackRatingStarCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $None
	var $None = 'None';
	// @var string $Yellow
	var $Yellow = 'Yellow';
	// @var string $Blue
	var $Blue = 'Blue';
	// @var string $Turquoise
	var $Turquoise = 'Turquoise';
	// @var string $Purple
	var $Purple = 'Purple';
	// @var string $Red
	var $Red = 'Red';
	// @var string $Green
	var $Green = 'Green';
	// @var string $YellowShooting
	var $YellowShooting = 'YellowShooting';
	// @var string $TurquoiseShooting
	var $TurquoiseShooting = 'TurquoiseShooting';
	// @var string $PurpleShooting
	var $PurpleShooting = 'PurpleShooting';
	// @var string $RedShooting
	var $RedShooting = 'RedShooting';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function FeedbackRatingStarCodeType()
	{
		$this->EbatNs_FacetType('FeedbackRatingStarCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_FeedbackRatingStarCodeType = new FeedbackRatingStarCodeType();

?>