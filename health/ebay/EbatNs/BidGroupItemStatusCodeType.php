<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class BidGroupItemStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $CurrentBid
	var $CurrentBid = 'CurrentBid';
	// @var string $Cancelled
	var $Cancelled = 'Cancelled';
	// @var string $Pending
	var $Pending = 'Pending';
	// @var string $Skipped
	var $Skipped = 'Skipped';
	// @var string $Ended
	var $Ended = 'Ended';
	// @var string $Won
	var $Won = 'Won';
	// @var string $GroupClosed
	var $GroupClosed = 'GroupClosed';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function BidGroupItemStatusCodeType()
	{
		$this->EbatNs_FacetType('BidGroupItemStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_BidGroupItemStatusCodeType = new BidGroupItemStatusCodeType();

?>
