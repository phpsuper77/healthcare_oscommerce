<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SellerPaymentMethodCodeType.php';
require_once 'SiteCodeType.php';
require_once 'VATStatusCodeType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'UserIDType.php';
require_once 'FeedbackRatingStarCodeType.php';
require_once 'SellerType.php';
require_once 'PayPalAccountStatusCodeType.php';
require_once 'PayPalAccountTypeCodeType.php';
require_once 'EBaySubscriptionTypeCodeType.php';
require_once 'CharityAffiliationsType.php';
require_once 'PayPalAccountLevelCodeType.php';
require_once 'BuyerType.php';
require_once 'UserStatusCodeType.php';
require_once 'BiddingSummaryType.php';
require_once 'AddressType.php';

class UserType extends EbatNs_ComplexType
{
	// start props
	// @var boolean $AboutMePage
	var $AboutMePage;
	// @var string $EIASToken
	var $EIASToken;
	// @var string $RESTToken
	var $RESTToken;
	// @var string $Email
	var $Email;
	// @var int $FeedbackScore
	var $FeedbackScore;
	// @var int $UniqueNegativeFeedbackCount
	var $UniqueNegativeFeedbackCount;
	// @var int $UniquePositiveFeedbackCount
	var $UniquePositiveFeedbackCount;
	// @var float $PositiveFeedbackPercent
	var $PositiveFeedbackPercent;
	// @var boolean $FeedbackPrivate
	var $FeedbackPrivate;
	// @var FeedbackRatingStarCodeType $FeedbackRatingStar
	var $FeedbackRatingStar;
	// @var boolean $IDVerified
	var $IDVerified;
	// @var boolean $eBayGoodStanding
	var $eBayGoodStanding;
	// @var boolean $NewUser
	var $NewUser;
	// @var AddressType $RegistrationAddress
	var $RegistrationAddress;
	// @var dateTime $RegistrationDate
	var $RegistrationDate;
	// @var SiteCodeType $Site
	var $Site;
	// @var UserStatusCodeType $Status
	var $Status;
	// @var UserIDType $UserID
	var $UserID;
	// @var boolean $UserIDChanged
	var $UserIDChanged;
	// @var dateTime $UserIDLastChanged
	var $UserIDLastChanged;
	// @var VATStatusCodeType $VATStatus
	var $VATStatus;
	// @var BuyerType $BuyerInfo
	var $BuyerInfo;
	// @var SellerType $SellerInfo
	var $SellerInfo;
	// @var CharityAffiliationsType $CharityAffiliations
	var $CharityAffiliations;
	// @var PayPalAccountLevelCodeType $PayPalAccountLevel
	var $PayPalAccountLevel;
	// @var PayPalAccountTypeCodeType $PayPalAccountType
	var $PayPalAccountType;
	// @var PayPalAccountStatusCodeType $PayPalAccountStatus
	var $PayPalAccountStatus;
	// @var EBaySubscriptionTypeCodeType $UserSubscription
	var $UserSubscription;
	// @var boolean $SiteVerified
	var $SiteVerified;
	// @var string $SkypeID
	var $SkypeID;
	// @var boolean $eBayWikiReadOnly
	var $eBayWikiReadOnly;
	// @var int $TUVLevel
	var $TUVLevel;
	// @var string $VATID
	var $VATID;
	// @var boolean $MotorsDealer
	var $MotorsDealer;
	// @var SellerPaymentMethodCodeType $SellerPaymentMethod
	var $SellerPaymentMethod;
	// @var BiddingSummaryType $BiddingSummary
	var $BiddingSummary;
	// @var boolean $UserAnonymized
	var $UserAnonymized;
	// @var int $UniqueNeutralFeedbackCount
	var $UniqueNeutralFeedbackCount;
	// @var boolean $EnterpriseSeller
	var $EnterpriseSeller;
	// @var string $BillingEmail
	var $BillingEmail;
	// end props

/**
 *

 * @return boolean
 */
	function getAboutMePage()
	{
		return $this->AboutMePage;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAboutMePage($value)
	{
		$this->AboutMePage = $value;
	}
/**
 *

 * @return string
 */
	function getEIASToken()
	{
		return $this->EIASToken;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEIASToken($value)
	{
		$this->EIASToken = $value;
	}
/**
 *

 * @return string
 */
	function getRESTToken()
	{
		return $this->RESTToken;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRESTToken($value)
	{
		$this->RESTToken = $value;
	}
/**
 *

 * @return string
 */
	function getEmail()
	{
		return $this->Email;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEmail($value)
	{
		$this->Email = $value;
	}
/**
 *

 * @return int
 */
	function getFeedbackScore()
	{
		return $this->FeedbackScore;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFeedbackScore($value)
	{
		$this->FeedbackScore = $value;
	}
/**
 *

 * @return int
 */
	function getUniqueNegativeFeedbackCount()
	{
		return $this->UniqueNegativeFeedbackCount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUniqueNegativeFeedbackCount($value)
	{
		$this->UniqueNegativeFeedbackCount = $value;
	}
/**
 *

 * @return int
 */
	function getUniquePositiveFeedbackCount()
	{
		return $this->UniquePositiveFeedbackCount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUniquePositiveFeedbackCount($value)
	{
		$this->UniquePositiveFeedbackCount = $value;
	}
/**
 *

 * @return float
 */
	function getPositiveFeedbackPercent()
	{
		return $this->PositiveFeedbackPercent;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPositiveFeedbackPercent($value)
	{
		$this->PositiveFeedbackPercent = $value;
	}
/**
 *

 * @return boolean
 */
	function getFeedbackPrivate()
	{
		return $this->FeedbackPrivate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFeedbackPrivate($value)
	{
		$this->FeedbackPrivate = $value;
	}
/**
 *

 * @return FeedbackRatingStarCodeType
 */
	function getFeedbackRatingStar()
	{
		return $this->FeedbackRatingStar;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFeedbackRatingStar($value)
	{
		$this->FeedbackRatingStar = $value;
	}
/**
 *

 * @return boolean
 */
	function getIDVerified()
	{
		return $this->IDVerified;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIDVerified($value)
	{
		$this->IDVerified = $value;
	}
/**
 *

 * @return boolean
 */
	function getEBayGoodStanding()
	{
		return $this->eBayGoodStanding;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEBayGoodStanding($value)
	{
		$this->eBayGoodStanding = $value;
	}
/**
 *

 * @return boolean
 */
	function getNewUser()
	{
		return $this->NewUser;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNewUser($value)
	{
		$this->NewUser = $value;
	}
/**
 *

 * @return AddressType
 */
	function getRegistrationAddress()
	{
		return $this->RegistrationAddress;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRegistrationAddress($value)
	{
		$this->RegistrationAddress = $value;
	}
/**
 *

 * @return dateTime
 */
	function getRegistrationDate()
	{
		return $this->RegistrationDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRegistrationDate($value)
	{
		$this->RegistrationDate = $value;
	}
/**
 *

 * @return SiteCodeType
 */
	function getSite()
	{
		return $this->Site;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSite($value)
	{
		$this->Site = $value;
	}
/**
 *

 * @return UserStatusCodeType
 */
	function getStatus()
	{
		return $this->Status;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStatus($value)
	{
		$this->Status = $value;
	}
/**
 *

 * @return UserIDType
 */
	function getUserID()
	{
		return $this->UserID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUserID($value)
	{
		$this->UserID = $value;
	}
/**
 *

 * @return boolean
 */
	function getUserIDChanged()
	{
		return $this->UserIDChanged;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUserIDChanged($value)
	{
		$this->UserIDChanged = $value;
	}
/**
 *

 * @return dateTime
 */
	function getUserIDLastChanged()
	{
		return $this->UserIDLastChanged;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUserIDLastChanged($value)
	{
		$this->UserIDLastChanged = $value;
	}
/**
 *

 * @return VATStatusCodeType
 */
	function getVATStatus()
	{
		return $this->VATStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATStatus($value)
	{
		$this->VATStatus = $value;
	}
/**
 *

 * @return BuyerType
 */
	function getBuyerInfo()
	{
		return $this->BuyerInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBuyerInfo($value)
	{
		$this->BuyerInfo = $value;
	}
/**
 *

 * @return SellerType
 */
	function getSellerInfo()
	{
		return $this->SellerInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerInfo($value)
	{
		$this->SellerInfo = $value;
	}
/**
 *

 * @return CharityAffiliationsType
 */
	function getCharityAffiliations()
	{
		return $this->CharityAffiliations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCharityAffiliations($value)
	{
		$this->CharityAffiliations = $value;
	}
/**
 *

 * @return PayPalAccountLevelCodeType
 */
	function getPayPalAccountLevel()
	{
		return $this->PayPalAccountLevel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPayPalAccountLevel($value)
	{
		$this->PayPalAccountLevel = $value;
	}
/**
 *

 * @return PayPalAccountTypeCodeType
 */
	function getPayPalAccountType()
	{
		return $this->PayPalAccountType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPayPalAccountType($value)
	{
		$this->PayPalAccountType = $value;
	}
/**
 *

 * @return PayPalAccountStatusCodeType
 */
	function getPayPalAccountStatus()
	{
		return $this->PayPalAccountStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPayPalAccountStatus($value)
	{
		$this->PayPalAccountStatus = $value;
	}
/**
 *

 * @return EBaySubscriptionTypeCodeType
 * @param  $index 
 */
	function getUserSubscription($index = null)
	{
		if ($index) {
		return $this->UserSubscription[$index];
	} else {
		return $this->UserSubscription;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setUserSubscription($value, $index = null)
	{
		if ($index) {
	$this->UserSubscription[$index] = $value;
	} else {
	$this->UserSubscription = $value;
	}

	}
/**
 *

 * @return boolean
 */
	function getSiteVerified()
	{
		return $this->SiteVerified;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSiteVerified($value)
	{
		$this->SiteVerified = $value;
	}
/**
 *

 * @return string
 * @param  $index 
 */
	function getSkypeID($index = null)
	{
		if ($index) {
		return $this->SkypeID[$index];
	} else {
		return $this->SkypeID;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSkypeID($value, $index = null)
	{
		if ($index) {
	$this->SkypeID[$index] = $value;
	} else {
	$this->SkypeID = $value;
	}

	}
/**
 *

 * @return boolean
 */
	function getEBayWikiReadOnly()
	{
		return $this->eBayWikiReadOnly;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEBayWikiReadOnly($value)
	{
		$this->eBayWikiReadOnly = $value;
	}
/**
 *

 * @return int
 */
	function getTUVLevel()
	{
		return $this->TUVLevel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTUVLevel($value)
	{
		$this->TUVLevel = $value;
	}
/**
 *

 * @return string
 */
	function getVATID()
	{
		return $this->VATID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATID($value)
	{
		$this->VATID = $value;
	}
/**
 *

 * @return boolean
 */
	function getMotorsDealer()
	{
		return $this->MotorsDealer;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMotorsDealer($value)
	{
		$this->MotorsDealer = $value;
	}
/**
 *

 * @return SellerPaymentMethodCodeType
 */
	function getSellerPaymentMethod()
	{
		return $this->SellerPaymentMethod;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerPaymentMethod($value)
	{
		$this->SellerPaymentMethod = $value;
	}
/**
 *

 * @return BiddingSummaryType
 */
	function getBiddingSummary()
	{
		return $this->BiddingSummary;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBiddingSummary($value)
	{
		$this->BiddingSummary = $value;
	}
/**
 *

 * @return boolean
 */
	function getUserAnonymized()
	{
		return $this->UserAnonymized;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUserAnonymized($value)
	{
		$this->UserAnonymized = $value;
	}
/**
 *

 * @return int
 */
	function getUniqueNeutralFeedbackCount()
	{
		return $this->UniqueNeutralFeedbackCount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUniqueNeutralFeedbackCount($value)
	{
		$this->UniqueNeutralFeedbackCount = $value;
	}
/**
 *

 * @return boolean
 */
	function getEnterpriseSeller()
	{
		return $this->EnterpriseSeller;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEnterpriseSeller($value)
	{
		$this->EnterpriseSeller = $value;
	}
/**
 *

 * @return string
 */
	function getBillingEmail()
	{
		return $this->BillingEmail;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBillingEmail($value)
	{
		$this->BillingEmail = $value;
	}
/**
 *

 * @return 
 */
	function UserType()
	{
		$this->EbatNs_ComplexType('UserType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'AboutMePage' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EIASToken' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RESTToken' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Email' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FeedbackScore' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UniqueNegativeFeedbackCount' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UniquePositiveFeedbackCount' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PositiveFeedbackPercent' =>
				array(
					'required' => false,
					'type' => 'float',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FeedbackPrivate' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FeedbackRatingStar' =>
				array(
					'required' => false,
					'type' => 'FeedbackRatingStarCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IDVerified' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'eBayGoodStanding' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NewUser' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RegistrationAddress' =>
				array(
					'required' => false,
					'type' => 'AddressType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RegistrationDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Site' =>
				array(
					'required' => false,
					'type' => 'SiteCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Status' =>
				array(
					'required' => false,
					'type' => 'UserStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UserID' =>
				array(
					'required' => false,
					'type' => 'UserIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UserIDChanged' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UserIDLastChanged' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATStatus' =>
				array(
					'required' => false,
					'type' => 'VATStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BuyerInfo' =>
				array(
					'required' => false,
					'type' => 'BuyerType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerInfo' =>
				array(
					'required' => false,
					'type' => 'SellerType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CharityAffiliations' =>
				array(
					'required' => false,
					'type' => 'CharityAffiliationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PayPalAccountLevel' =>
				array(
					'required' => false,
					'type' => 'PayPalAccountLevelCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PayPalAccountType' =>
				array(
					'required' => false,
					'type' => 'PayPalAccountTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PayPalAccountStatus' =>
				array(
					'required' => false,
					'type' => 'PayPalAccountStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UserSubscription' =>
				array(
					'required' => false,
					'type' => 'EBaySubscriptionTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'SiteVerified' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SkypeID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => true,
					'cardinality' => '0..*'
				),
				'eBayWikiReadOnly' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TUVLevel' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MotorsDealer' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerPaymentMethod' =>
				array(
					'required' => false,
					'type' => 'SellerPaymentMethodCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BiddingSummary' =>
				array(
					'required' => false,
					'type' => 'BiddingSummaryType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UserAnonymized' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UniqueNeutralFeedbackCount' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EnterpriseSeller' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BillingEmail' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
