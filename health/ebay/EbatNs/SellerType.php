<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SellerPaymentMethodCodeType.php';
require_once 'AddressType.php';
require_once 'IntegratedMerchantCreditCardInfoType.php';
require_once 'SellerBusinessCodeType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'MerchandizingPrefCodeType.php';
require_once 'CharityAffiliationDetailsType.php';
require_once 'CurrencyCodeType.php';
require_once 'SellerLevelCodeType.php';
require_once 'SiteCodeType.php';
require_once 'ProStoresCheckoutPreferenceType.php';
require_once 'SchedulingInfoType.php';
require_once 'SellerGuaranteeLevelCodeType.php';

class SellerType extends EbatNs_ComplexType
{
	// start props
	// @var int $PaisaPayStatus
	var $PaisaPayStatus;
	// @var boolean $AllowPaymentEdit
	var $AllowPaymentEdit;
	// @var CurrencyCodeType $BillingCurrency
	var $BillingCurrency;
	// @var boolean $CheckoutEnabled
	var $CheckoutEnabled;
	// @var boolean $CIPBankAccountStored
	var $CIPBankAccountStored;
	// @var boolean $GoodStanding
	var $GoodStanding;
	// @var MerchandizingPrefCodeType $MerchandizingPref
	var $MerchandizingPref;
	// @var boolean $QualifiesForB2BVAT
	var $QualifiesForB2BVAT;
	// @var SellerGuaranteeLevelCodeType $SellerGuaranteeLevel
	var $SellerGuaranteeLevel;
	// @var SellerLevelCodeType $SellerLevel
	var $SellerLevel;
	// @var AddressType $SellerPaymentAddress
	var $SellerPaymentAddress;
	// @var SchedulingInfoType $SchedulingInfo
	var $SchedulingInfo;
	// @var boolean $StoreOwner
	var $StoreOwner;
	// @var anyURI $StoreURL
	var $StoreURL;
	// @var SellerBusinessCodeType $SellerBusinessType
	var $SellerBusinessType;
	// @var boolean $RegisteredBusinessSeller
	var $RegisteredBusinessSeller;
	// @var SiteCodeType $StoreSite
	var $StoreSite;
	// @var SellerPaymentMethodCodeType $PaymentMethod
	var $PaymentMethod;
	// @var ProStoresCheckoutPreferenceType $ProStoresPreference
	var $ProStoresPreference;
	// @var boolean $CharityRegistered
	var $CharityRegistered;
	// @var boolean $SafePaymentExempt
	var $SafePaymentExempt;
	// @var int $PaisaPayEscrowEMIStatus
	var $PaisaPayEscrowEMIStatus;
	// @var CharityAffiliationDetailsType $CharityAffiliationDetails
	var $CharityAffiliationDetails;
	// @var float $TransactionPercent
	var $TransactionPercent;
	// @var IntegratedMerchantCreditCardInfoType $IntegratedMerchantCreditCardInfo
	var $IntegratedMerchantCreditCardInfo;
	// end props

/**
 *

 * @return int
 */
	function getPaisaPayStatus()
	{
		return $this->PaisaPayStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaisaPayStatus($value)
	{
		$this->PaisaPayStatus = $value;
	}
/**
 *

 * @return boolean
 */
	function getAllowPaymentEdit()
	{
		return $this->AllowPaymentEdit;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAllowPaymentEdit($value)
	{
		$this->AllowPaymentEdit = $value;
	}
/**
 *

 * @return CurrencyCodeType
 */
	function getBillingCurrency()
	{
		return $this->BillingCurrency;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBillingCurrency($value)
	{
		$this->BillingCurrency = $value;
	}
/**
 *

 * @return boolean
 */
	function getCheckoutEnabled()
	{
		return $this->CheckoutEnabled;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCheckoutEnabled($value)
	{
		$this->CheckoutEnabled = $value;
	}
/**
 *

 * @return boolean
 */
	function getCIPBankAccountStored()
	{
		return $this->CIPBankAccountStored;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCIPBankAccountStored($value)
	{
		$this->CIPBankAccountStored = $value;
	}
/**
 *

 * @return boolean
 */
	function getGoodStanding()
	{
		return $this->GoodStanding;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setGoodStanding($value)
	{
		$this->GoodStanding = $value;
	}
/**
 *

 * @return MerchandizingPrefCodeType
 */
	function getMerchandizingPref()
	{
		return $this->MerchandizingPref;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMerchandizingPref($value)
	{
		$this->MerchandizingPref = $value;
	}
/**
 *

 * @return boolean
 */
	function getQualifiesForB2BVAT()
	{
		return $this->QualifiesForB2BVAT;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setQualifiesForB2BVAT($value)
	{
		$this->QualifiesForB2BVAT = $value;
	}
/**
 *

 * @return SellerGuaranteeLevelCodeType
 */
	function getSellerGuaranteeLevel()
	{
		return $this->SellerGuaranteeLevel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerGuaranteeLevel($value)
	{
		$this->SellerGuaranteeLevel = $value;
	}
/**
 *

 * @return SellerLevelCodeType
 */
	function getSellerLevel()
	{
		return $this->SellerLevel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerLevel($value)
	{
		$this->SellerLevel = $value;
	}
/**
 *

 * @return AddressType
 */
	function getSellerPaymentAddress()
	{
		return $this->SellerPaymentAddress;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerPaymentAddress($value)
	{
		$this->SellerPaymentAddress = $value;
	}
/**
 *

 * @return SchedulingInfoType
 */
	function getSchedulingInfo()
	{
		return $this->SchedulingInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSchedulingInfo($value)
	{
		$this->SchedulingInfo = $value;
	}
/**
 *

 * @return boolean
 */
	function getStoreOwner()
	{
		return $this->StoreOwner;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStoreOwner($value)
	{
		$this->StoreOwner = $value;
	}
/**
 *

 * @return anyURI
 */
	function getStoreURL()
	{
		return $this->StoreURL;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStoreURL($value)
	{
		$this->StoreURL = $value;
	}
/**
 *

 * @return SellerBusinessCodeType
 */
	function getSellerBusinessType()
	{
		return $this->SellerBusinessType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerBusinessType($value)
	{
		$this->SellerBusinessType = $value;
	}
/**
 *

 * @return boolean
 */
	function getRegisteredBusinessSeller()
	{
		return $this->RegisteredBusinessSeller;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRegisteredBusinessSeller($value)
	{
		$this->RegisteredBusinessSeller = $value;
	}
/**
 *

 * @return SiteCodeType
 */
	function getStoreSite()
	{
		return $this->StoreSite;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStoreSite($value)
	{
		$this->StoreSite = $value;
	}
/**
 *

 * @return SellerPaymentMethodCodeType
 */
	function getPaymentMethod()
	{
		return $this->PaymentMethod;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaymentMethod($value)
	{
		$this->PaymentMethod = $value;
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

 * @return boolean
 */
	function getCharityRegistered()
	{
		return $this->CharityRegistered;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCharityRegistered($value)
	{
		$this->CharityRegistered = $value;
	}
/**
 *

 * @return boolean
 */
	function getSafePaymentExempt()
	{
		return $this->SafePaymentExempt;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSafePaymentExempt($value)
	{
		$this->SafePaymentExempt = $value;
	}
/**
 *

 * @return int
 */
	function getPaisaPayEscrowEMIStatus()
	{
		return $this->PaisaPayEscrowEMIStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaisaPayEscrowEMIStatus($value)
	{
		$this->PaisaPayEscrowEMIStatus = $value;
	}
/**
 *

 * @return CharityAffiliationDetailsType
 */
	function getCharityAffiliationDetails()
	{
		return $this->CharityAffiliationDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCharityAffiliationDetails($value)
	{
		$this->CharityAffiliationDetails = $value;
	}
/**
 *

 * @return float
 */
	function getTransactionPercent()
	{
		return $this->TransactionPercent;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTransactionPercent($value)
	{
		$this->TransactionPercent = $value;
	}
/**
 *

 * @return IntegratedMerchantCreditCardInfoType
 */
	function getIntegratedMerchantCreditCardInfo()
	{
		return $this->IntegratedMerchantCreditCardInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIntegratedMerchantCreditCardInfo($value)
	{
		$this->IntegratedMerchantCreditCardInfo = $value;
	}
/**
 *

 * @return 
 */
	function SellerType()
	{
		$this->EbatNs_ComplexType('SellerType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'PaisaPayStatus' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AllowPaymentEdit' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'BillingCurrency' =>
				array(
					'required' => false,
					'type' => 'CurrencyCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CheckoutEnabled' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'CIPBankAccountStored' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'GoodStanding' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'MerchandizingPref' =>
				array(
					'required' => false,
					'type' => 'MerchandizingPrefCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'QualifiesForB2BVAT' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'SellerGuaranteeLevel' =>
				array(
					'required' => false,
					'type' => 'SellerGuaranteeLevelCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerLevel' =>
				array(
					'required' => false,
					'type' => 'SellerLevelCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerPaymentAddress' =>
				array(
					'required' => false,
					'type' => 'AddressType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SchedulingInfo' =>
				array(
					'required' => false,
					'type' => 'SchedulingInfoType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StoreOwner' =>
				array(
					'required' => true,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '1..1'
				),
				'StoreURL' =>
				array(
					'required' => false,
					'type' => 'anyURI',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerBusinessType' =>
				array(
					'required' => false,
					'type' => 'SellerBusinessCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RegisteredBusinessSeller' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StoreSite' =>
				array(
					'required' => false,
					'type' => 'SiteCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaymentMethod' =>
				array(
					'required' => false,
					'type' => 'SellerPaymentMethodCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
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
				),
				'CharityRegistered' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SafePaymentExempt' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaisaPayEscrowEMIStatus' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CharityAffiliationDetails' =>
				array(
					'required' => false,
					'type' => 'CharityAffiliationDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TransactionPercent' =>
				array(
					'required' => false,
					'type' => 'float',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IntegratedMerchantCreditCardInfo' =>
				array(
					'required' => false,
					'type' => 'IntegratedMerchantCreditCardInfoType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
