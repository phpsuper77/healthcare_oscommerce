<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PurchasePurposeTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Other
	var $Other = 'Other';
	// @var string $BuyNowItem
	var $BuyNowItem = 'BuyNowItem';
	// @var string $ShoppingCart
	var $ShoppingCart = 'ShoppingCart';
	// @var string $AuctionItem
	var $AuctionItem = 'AuctionItem';
	// @var string $GiftCertificates
	var $GiftCertificates = 'GiftCertificates';
	// @var string $Subscription
	var $Subscription = 'Subscription';
	// @var string $Donation
	var $Donation = 'Donation';
	// @var string $eBayBilling
	var $eBayBilling = 'eBayBilling';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PurchasePurposeTypeCodeType()
	{
		$this->EbatNs_FacetType('PurchasePurposeTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PurchasePurposeTypeCodeType = new PurchasePurposeTypeCodeType();

?>
