<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class BidActionCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Unknown
	var $Unknown = 'Unknown';
	// @var string $Bid
	var $Bid = 'Bid';
	// @var string $NotUsed
	var $NotUsed = 'NotUsed';
	// @var string $Retraction
	var $Retraction = 'Retraction';
	// @var string $AutoRetraction
	var $AutoRetraction = 'AutoRetraction';
	// @var string $Cancelled
	var $Cancelled = 'Cancelled';
	// @var string $AutoCancel
	var $AutoCancel = 'AutoCancel';
	// @var string $Absentee
	var $Absentee = 'Absentee';
	// @var string $BuyItNow
	var $BuyItNow = 'BuyItNow';
	// @var string $Purchase
	var $Purchase = 'Purchase';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// @var string $Offer
	var $Offer = 'Offer';
	// @var string $Counter
	var $Counter = 'Counter';
	// @var string $Accept
	var $Accept = 'Accept';
	// @var string $Decline
	var $Decline = 'Decline';
	// end props

/**
 *

 * @return 
 */
	function BidActionCodeType()
	{
		$this->EbatNs_FacetType('BidActionCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_BidActionCodeType = new BidActionCodeType();

?>