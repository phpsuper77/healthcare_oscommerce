<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ShippingPackageDetailsType.php';
require_once 'RegionDetailsType.php';
require_once 'SiteBuyerRequirementDetailsType.php';
require_once 'TimeZoneDetailsType.php';
require_once 'ShippingServiceDetailsType.php';
require_once 'ReturnPolicyDetailsType.php';
require_once 'DispatchTimeMaxDetailsType.php';
require_once 'UnitOfMeasurementDetailsType.php';
require_once 'ItemSpecificDetailsType.php';
require_once 'RegionOfOriginDetailsType.php';
require_once 'SiteDetailsType.php';
require_once 'CurrencyDetailsType.php';
require_once 'ListingStartPriceDetailsType.php';
require_once 'PaymentOptionDetailsType.php';
require_once 'TaxJurisdictionType.php';
require_once 'URLDetailsType.php';
require_once 'ShippingCarrierDetailsType.php';
require_once 'ShippingLocationDetailsType.php';
require_once 'CountryDetailsType.php';
require_once 'AbstractResponseType.php';

class GeteBayDetailsResponseType extends AbstractResponseType
{
	// start props
	// @var CountryDetailsType $CountryDetails
	var $CountryDetails;
	// @var CurrencyDetailsType $CurrencyDetails
	var $CurrencyDetails;
	// @var DispatchTimeMaxDetailsType $DispatchTimeMaxDetails
	var $DispatchTimeMaxDetails;
	// @var PaymentOptionDetailsType $PaymentOptionDetails
	var $PaymentOptionDetails;
	// @var RegionDetailsType $RegionDetails
	var $RegionDetails;
	// @var ShippingLocationDetailsType $ShippingLocationDetails
	var $ShippingLocationDetails;
	// @var ShippingServiceDetailsType $ShippingServiceDetails
	var $ShippingServiceDetails;
	// @var SiteDetailsType $SiteDetails
	var $SiteDetails;
	// @var TaxJurisdictionType $TaxJurisdiction
	var $TaxJurisdiction;
	// @var URLDetailsType $URLDetails
	var $URLDetails;
	// @var TimeZoneDetailsType $TimeZoneDetails
	var $TimeZoneDetails;
	// @var ItemSpecificDetailsType $ItemSpecificDetails
	var $ItemSpecificDetails;
	// @var UnitOfMeasurementDetailsType $UnitOfMeasurementDetails
	var $UnitOfMeasurementDetails;
	// @var RegionOfOriginDetailsType $RegionOfOriginDetails
	var $RegionOfOriginDetails;
	// @var ShippingPackageDetailsType $ShippingPackageDetails
	var $ShippingPackageDetails;
	// @var ShippingCarrierDetailsType $ShippingCarrierDetails
	var $ShippingCarrierDetails;
	// @var ReturnPolicyDetailsType $ReturnPolicyDetails
	var $ReturnPolicyDetails;
	// @var ListingStartPriceDetailsType $ListingStartPriceDetails
	var $ListingStartPriceDetails;
	// @var SiteBuyerRequirementDetailsType $BuyerRequirementDetails
	var $BuyerRequirementDetails;
	// end props

/**
 *

 * @return CountryDetailsType
 * @param  $index 
 */
	function getCountryDetails($index = null)
	{
		if ($index) {
		return $this->CountryDetails[$index];
	} else {
		return $this->CountryDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setCountryDetails($value, $index = null)
	{
		if ($index) {
	$this->CountryDetails[$index] = $value;
	} else {
	$this->CountryDetails = $value;
	}

	}
/**
 *

 * @return CurrencyDetailsType
 * @param  $index 
 */
	function getCurrencyDetails($index = null)
	{
		if ($index) {
		return $this->CurrencyDetails[$index];
	} else {
		return $this->CurrencyDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setCurrencyDetails($value, $index = null)
	{
		if ($index) {
	$this->CurrencyDetails[$index] = $value;
	} else {
	$this->CurrencyDetails = $value;
	}

	}
/**
 *

 * @return DispatchTimeMaxDetailsType
 * @param  $index 
 */
	function getDispatchTimeMaxDetails($index = null)
	{
		if ($index) {
		return $this->DispatchTimeMaxDetails[$index];
	} else {
		return $this->DispatchTimeMaxDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setDispatchTimeMaxDetails($value, $index = null)
	{
		if ($index) {
	$this->DispatchTimeMaxDetails[$index] = $value;
	} else {
	$this->DispatchTimeMaxDetails = $value;
	}

	}
/**
 *

 * @return PaymentOptionDetailsType
 * @param  $index 
 */
	function getPaymentOptionDetails($index = null)
	{
		if ($index) {
		return $this->PaymentOptionDetails[$index];
	} else {
		return $this->PaymentOptionDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setPaymentOptionDetails($value, $index = null)
	{
		if ($index) {
	$this->PaymentOptionDetails[$index] = $value;
	} else {
	$this->PaymentOptionDetails = $value;
	}

	}
/**
 *

 * @return RegionDetailsType
 * @param  $index 
 */
	function getRegionDetails($index = null)
	{
		if ($index) {
		return $this->RegionDetails[$index];
	} else {
		return $this->RegionDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setRegionDetails($value, $index = null)
	{
		if ($index) {
	$this->RegionDetails[$index] = $value;
	} else {
	$this->RegionDetails = $value;
	}

	}
/**
 *

 * @return ShippingLocationDetailsType
 * @param  $index 
 */
	function getShippingLocationDetails($index = null)
	{
		if ($index) {
		return $this->ShippingLocationDetails[$index];
	} else {
		return $this->ShippingLocationDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShippingLocationDetails($value, $index = null)
	{
		if ($index) {
	$this->ShippingLocationDetails[$index] = $value;
	} else {
	$this->ShippingLocationDetails = $value;
	}

	}
/**
 *

 * @return ShippingServiceDetailsType
 * @param  $index 
 */
	function getShippingServiceDetails($index = null)
	{
		if ($index) {
		return $this->ShippingServiceDetails[$index];
	} else {
		return $this->ShippingServiceDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShippingServiceDetails($value, $index = null)
	{
		if ($index) {
	$this->ShippingServiceDetails[$index] = $value;
	} else {
	$this->ShippingServiceDetails = $value;
	}

	}
/**
 *

 * @return SiteDetailsType
 * @param  $index 
 */
	function getSiteDetails($index = null)
	{
		if ($index) {
		return $this->SiteDetails[$index];
	} else {
		return $this->SiteDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSiteDetails($value, $index = null)
	{
		if ($index) {
	$this->SiteDetails[$index] = $value;
	} else {
	$this->SiteDetails = $value;
	}

	}
/**
 *

 * @return TaxJurisdictionType
 * @param  $index 
 */
	function getTaxJurisdiction($index = null)
	{
		if ($index) {
		return $this->TaxJurisdiction[$index];
	} else {
		return $this->TaxJurisdiction;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setTaxJurisdiction($value, $index = null)
	{
		if ($index) {
	$this->TaxJurisdiction[$index] = $value;
	} else {
	$this->TaxJurisdiction = $value;
	}

	}
/**
 *

 * @return URLDetailsType
 * @param  $index 
 */
	function getURLDetails($index = null)
	{
		if ($index) {
		return $this->URLDetails[$index];
	} else {
		return $this->URLDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setURLDetails($value, $index = null)
	{
		if ($index) {
	$this->URLDetails[$index] = $value;
	} else {
	$this->URLDetails = $value;
	}

	}
/**
 *

 * @return TimeZoneDetailsType
 * @param  $index 
 */
	function getTimeZoneDetails($index = null)
	{
		if ($index) {
		return $this->TimeZoneDetails[$index];
	} else {
		return $this->TimeZoneDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setTimeZoneDetails($value, $index = null)
	{
		if ($index) {
	$this->TimeZoneDetails[$index] = $value;
	} else {
	$this->TimeZoneDetails = $value;
	}

	}
/**
 *

 * @return ItemSpecificDetailsType
 * @param  $index 
 */
	function getItemSpecificDetails($index = null)
	{
		if ($index) {
		return $this->ItemSpecificDetails[$index];
	} else {
		return $this->ItemSpecificDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setItemSpecificDetails($value, $index = null)
	{
		if ($index) {
	$this->ItemSpecificDetails[$index] = $value;
	} else {
	$this->ItemSpecificDetails = $value;
	}

	}
/**
 *

 * @return UnitOfMeasurementDetailsType
 * @param  $index 
 */
	function getUnitOfMeasurementDetails($index = null)
	{
		if ($index) {
		return $this->UnitOfMeasurementDetails[$index];
	} else {
		return $this->UnitOfMeasurementDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setUnitOfMeasurementDetails($value, $index = null)
	{
		if ($index) {
	$this->UnitOfMeasurementDetails[$index] = $value;
	} else {
	$this->UnitOfMeasurementDetails = $value;
	}

	}
/**
 *

 * @return RegionOfOriginDetailsType
 * @param  $index 
 */
	function getRegionOfOriginDetails($index = null)
	{
		if ($index) {
		return $this->RegionOfOriginDetails[$index];
	} else {
		return $this->RegionOfOriginDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setRegionOfOriginDetails($value, $index = null)
	{
		if ($index) {
	$this->RegionOfOriginDetails[$index] = $value;
	} else {
	$this->RegionOfOriginDetails = $value;
	}

	}
/**
 *

 * @return ShippingPackageDetailsType
 * @param  $index 
 */
	function getShippingPackageDetails($index = null)
	{
		if ($index) {
		return $this->ShippingPackageDetails[$index];
	} else {
		return $this->ShippingPackageDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShippingPackageDetails($value, $index = null)
	{
		if ($index) {
	$this->ShippingPackageDetails[$index] = $value;
	} else {
	$this->ShippingPackageDetails = $value;
	}

	}
/**
 *

 * @return ShippingCarrierDetailsType
 * @param  $index 
 */
	function getShippingCarrierDetails($index = null)
	{
		if ($index) {
		return $this->ShippingCarrierDetails[$index];
	} else {
		return $this->ShippingCarrierDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShippingCarrierDetails($value, $index = null)
	{
		if ($index) {
	$this->ShippingCarrierDetails[$index] = $value;
	} else {
	$this->ShippingCarrierDetails = $value;
	}

	}
/**
 *

 * @return ReturnPolicyDetailsType
 */
	function getReturnPolicyDetails()
	{
		return $this->ReturnPolicyDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setReturnPolicyDetails($value)
	{
		$this->ReturnPolicyDetails = $value;
	}
/**
 *

 * @return ListingStartPriceDetailsType
 * @param  $index 
 */
	function getListingStartPriceDetails($index = null)
	{
		if ($index) {
		return $this->ListingStartPriceDetails[$index];
	} else {
		return $this->ListingStartPriceDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setListingStartPriceDetails($value, $index = null)
	{
		if ($index) {
	$this->ListingStartPriceDetails[$index] = $value;
	} else {
	$this->ListingStartPriceDetails = $value;
	}

	}
/**
 *

 * @return SiteBuyerRequirementDetailsType
 * @param  $index 
 */
	function getBuyerRequirementDetails($index = null)
	{
		if ($index) {
		return $this->BuyerRequirementDetails[$index];
	} else {
		return $this->BuyerRequirementDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setBuyerRequirementDetails($value, $index = null)
	{
		if ($index) {
	$this->BuyerRequirementDetails[$index] = $value;
	} else {
	$this->BuyerRequirementDetails = $value;
	}

	}
/**
 *

 * @return 
 */
	function GeteBayDetailsResponseType()
	{
		$this->AbstractResponseType('GeteBayDetailsResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'CountryDetails' =>
				array(
					'required' => false,
					'type' => 'CountryDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'CurrencyDetails' =>
				array(
					'required' => false,
					'type' => 'CurrencyDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'DispatchTimeMaxDetails' =>
				array(
					'required' => false,
					'type' => 'DispatchTimeMaxDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'PaymentOptionDetails' =>
				array(
					'required' => false,
					'type' => 'PaymentOptionDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'RegionDetails' =>
				array(
					'required' => false,
					'type' => 'RegionDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingLocationDetails' =>
				array(
					'required' => false,
					'type' => 'ShippingLocationDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingServiceDetails' =>
				array(
					'required' => false,
					'type' => 'ShippingServiceDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'SiteDetails' =>
				array(
					'required' => false,
					'type' => 'SiteDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'TaxJurisdiction' =>
				array(
					'required' => false,
					'type' => 'TaxJurisdictionType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'URLDetails' =>
				array(
					'required' => false,
					'type' => 'URLDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'TimeZoneDetails' =>
				array(
					'required' => false,
					'type' => 'TimeZoneDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ItemSpecificDetails' =>
				array(
					'required' => false,
					'type' => 'ItemSpecificDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'UnitOfMeasurementDetails' =>
				array(
					'required' => false,
					'type' => 'UnitOfMeasurementDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'RegionOfOriginDetails' =>
				array(
					'required' => false,
					'type' => 'RegionOfOriginDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingPackageDetails' =>
				array(
					'required' => false,
					'type' => 'ShippingPackageDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingCarrierDetails' =>
				array(
					'required' => false,
					'type' => 'ShippingCarrierDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ReturnPolicyDetails' =>
				array(
					'required' => false,
					'type' => 'ReturnPolicyDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ListingStartPriceDetails' =>
				array(
					'required' => false,
					'type' => 'ListingStartPriceDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'BuyerRequirementDetails' =>
				array(
					'required' => false,
					'type' => 'SiteBuyerRequirementDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
