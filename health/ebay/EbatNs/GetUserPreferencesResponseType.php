<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'BidderNoticePreferencesType.php';
require_once 'SellerFavoriteItemPreferencesType.php';
require_once 'ProStoresCheckoutPreferenceType.php';
require_once 'AbstractResponseType.php';
require_once 'CrossPromotionPreferencesType.php';
require_once 'SellerPaymentPreferencesType.php';
require_once 'CombinedPaymentPreferencesType.php';
require_once 'EndOfAuctionEmailPreferencesType.php';

class GetUserPreferencesResponseType extends AbstractResponseType
{
	// start props
	// @var BidderNoticePreferencesType $BidderNoticePreferences
	var $BidderNoticePreferences;
	// @var CombinedPaymentPreferencesType $CombinedPaymentPreferences
	var $CombinedPaymentPreferences;
	// @var CrossPromotionPreferencesType $CrossPromotionPreferences
	var $CrossPromotionPreferences;
	// @var SellerPaymentPreferencesType $SellerPaymentPreferences
	var $SellerPaymentPreferences;
	// @var SellerFavoriteItemPreferencesType $SellerFavoriteItemPreferences
	var $SellerFavoriteItemPreferences;
	// @var EndOfAuctionEmailPreferencesType $EndOfAuctionEmailPreferences
	var $EndOfAuctionEmailPreferences;
	// @var boolean $EmailShipmentTrackingNumberPreference
	var $EmailShipmentTrackingNumberPreference;
	// @var ProStoresCheckoutPreferenceType $ProStoresPreference
	var $ProStoresPreference;
	// end props

/**
 *

 * @return BidderNoticePreferencesType
 */
	function getBidderNoticePreferences()
	{
		return $this->BidderNoticePreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBidderNoticePreferences($value)
	{
		$this->BidderNoticePreferences = $value;
	}
/**
 *

 * @return CombinedPaymentPreferencesType
 */
	function getCombinedPaymentPreferences()
	{
		return $this->CombinedPaymentPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCombinedPaymentPreferences($value)
	{
		$this->CombinedPaymentPreferences = $value;
	}
/**
 *

 * @return CrossPromotionPreferencesType
 */
	function getCrossPromotionPreferences()
	{
		return $this->CrossPromotionPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCrossPromotionPreferences($value)
	{
		$this->CrossPromotionPreferences = $value;
	}
/**
 *

 * @return SellerPaymentPreferencesType
 */
	function getSellerPaymentPreferences()
	{
		return $this->SellerPaymentPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerPaymentPreferences($value)
	{
		$this->SellerPaymentPreferences = $value;
	}
/**
 *

 * @return SellerFavoriteItemPreferencesType
 */
	function getSellerFavoriteItemPreferences()
	{
		return $this->SellerFavoriteItemPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerFavoriteItemPreferences($value)
	{
		$this->SellerFavoriteItemPreferences = $value;
	}
/**
 *

 * @return EndOfAuctionEmailPreferencesType
 */
	function getEndOfAuctionEmailPreferences()
	{
		return $this->EndOfAuctionEmailPreferences;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEndOfAuctionEmailPreferences($value)
	{
		$this->EndOfAuctionEmailPreferences = $value;
	}
/**
 *

 * @return boolean
 */
	function getEmailShipmentTrackingNumberPreference()
	{
		return $this->EmailShipmentTrackingNumberPreference;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEmailShipmentTrackingNumberPreference($value)
	{
		$this->EmailShipmentTrackingNumberPreference = $value;
	}
/**
 *

 * @return ProStoresCheckoutPreferenceType
 */
	function getProStoresPreference()
	{
		return $this->ProStoresPreference;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProStoresPreference($value)
	{
		$this->ProStoresPreference = $value;
	}
/**
 *

 * @return 
 */
	function GetUserPreferencesResponseType()
	{
		$this->AbstractResponseType('GetUserPreferencesResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'BidderNoticePreferences' =>
				array(
					'required' => false,
					'type' => 'BidderNoticePreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CombinedPaymentPreferences' =>
				array(
					'required' => false,
					'type' => 'CombinedPaymentPreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CrossPromotionPreferences' =>
				array(
					'required' => false,
					'type' => 'CrossPromotionPreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerPaymentPreferences' =>
				array(
					'required' => false,
					'type' => 'SellerPaymentPreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerFavoriteItemPreferences' =>
				array(
					'required' => false,
					'type' => 'SellerFavoriteItemPreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EndOfAuctionEmailPreferences' =>
				array(
					'required' => false,
					'type' => 'EndOfAuctionEmailPreferencesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EmailShipmentTrackingNumberPreference' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ProStoresPreference' =>
				array(
					'required' => false,
					'type' => 'ProStoresCheckoutPreferenceType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
