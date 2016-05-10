<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SellerRatingSummaryArrayType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'BuyerRoleMetricsType.php';
require_once 'FeedbackPeriodArrayType.php';
require_once 'SellerRoleMetricsType.php';

class FeedbackSummaryType extends EbatNs_ComplexType
{
	// start props
	// @var FeedbackPeriodArrayType $BidRetractionFeedbackPeriodArray
	var $BidRetractionFeedbackPeriodArray;
	// @var FeedbackPeriodArrayType $NegativeFeedbackPeriodArray
	var $NegativeFeedbackPeriodArray;
	// @var FeedbackPeriodArrayType $NeutralFeedbackPeriodArray
	var $NeutralFeedbackPeriodArray;
	// @var FeedbackPeriodArrayType $PositiveFeedbackPeriodArray
	var $PositiveFeedbackPeriodArray;
	// @var FeedbackPeriodArrayType $TotalFeedbackPeriodArray
	var $TotalFeedbackPeriodArray;
	// @var int $NeutralCommentCountFromSuspendedUsers
	var $NeutralCommentCountFromSuspendedUsers;
	// @var int $UniqueNegativeFeedbackCount
	var $UniqueNegativeFeedbackCount;
	// @var int $UniquePositiveFeedbackCount
	var $UniquePositiveFeedbackCount;
	// @var int $UniqueNeutralFeedbackCount
	var $UniqueNeutralFeedbackCount;
	// @var SellerRatingSummaryArrayType $SellerRatingSummaryArray
	var $SellerRatingSummaryArray;
	// @var SellerRoleMetricsType $SellerRoleMetrics
	var $SellerRoleMetrics;
	// @var BuyerRoleMetricsType $BuyerRoleMetrics
	var $BuyerRoleMetrics;
	// end props

/**
 *

 * @return FeedbackPeriodArrayType
 */
	function getBidRetractionFeedbackPeriodArray()
	{
		return $this->BidRetractionFeedbackPeriodArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBidRetractionFeedbackPeriodArray($value)
	{
		$this->BidRetractionFeedbackPeriodArray = $value;
	}
/**
 *

 * @return FeedbackPeriodArrayType
 */
	function getNegativeFeedbackPeriodArray()
	{
		return $this->NegativeFeedbackPeriodArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNegativeFeedbackPeriodArray($value)
	{
		$this->NegativeFeedbackPeriodArray = $value;
	}
/**
 *

 * @return FeedbackPeriodArrayType
 */
	function getNeutralFeedbackPeriodArray()
	{
		return $this->NeutralFeedbackPeriodArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNeutralFeedbackPeriodArray($value)
	{
		$this->NeutralFeedbackPeriodArray = $value;
	}
/**
 *

 * @return FeedbackPeriodArrayType
 */
	function getPositiveFeedbackPeriodArray()
	{
		return $this->PositiveFeedbackPeriodArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPositiveFeedbackPeriodArray($value)
	{
		$this->PositiveFeedbackPeriodArray = $value;
	}
/**
 *

 * @return FeedbackPeriodArrayType
 */
	function getTotalFeedbackPeriodArray()
	{
		return $this->TotalFeedbackPeriodArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTotalFeedbackPeriodArray($value)
	{
		$this->TotalFeedbackPeriodArray = $value;
	}
/**
 *

 * @return int
 */
	function getNeutralCommentCountFromSuspendedUsers()
	{
		return $this->NeutralCommentCountFromSuspendedUsers;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNeutralCommentCountFromSuspendedUsers($value)
	{
		$this->NeutralCommentCountFromSuspendedUsers = $value;
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

 * @return SellerRatingSummaryArrayType
 */
	function getSellerRatingSummaryArray()
	{
		return $this->SellerRatingSummaryArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerRatingSummaryArray($value)
	{
		$this->SellerRatingSummaryArray = $value;
	}
/**
 *

 * @return SellerRoleMetricsType
 */
	function getSellerRoleMetrics()
	{
		return $this->SellerRoleMetrics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerRoleMetrics($value)
	{
		$this->SellerRoleMetrics = $value;
	}
/**
 *

 * @return BuyerRoleMetricsType
 */
	function getBuyerRoleMetrics()
	{
		return $this->BuyerRoleMetrics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBuyerRoleMetrics($value)
	{
		$this->BuyerRoleMetrics = $value;
	}
/**
 *

 * @return 
 */
	function FeedbackSummaryType()
	{
		$this->EbatNs_ComplexType('FeedbackSummaryType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'BidRetractionFeedbackPeriodArray' =>
				array(
					'required' => false,
					'type' => 'FeedbackPeriodArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NegativeFeedbackPeriodArray' =>
				array(
					'required' => false,
					'type' => 'FeedbackPeriodArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NeutralFeedbackPeriodArray' =>
				array(
					'required' => false,
					'type' => 'FeedbackPeriodArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PositiveFeedbackPeriodArray' =>
				array(
					'required' => false,
					'type' => 'FeedbackPeriodArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TotalFeedbackPeriodArray' =>
				array(
					'required' => false,
					'type' => 'FeedbackPeriodArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NeutralCommentCountFromSuspendedUsers' =>
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
				'UniqueNeutralFeedbackCount' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerRatingSummaryArray' =>
				array(
					'required' => false,
					'type' => 'SellerRatingSummaryArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerRoleMetrics' =>
				array(
					'required' => false,
					'type' => 'SellerRoleMetricsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BuyerRoleMetrics' =>
				array(
					'required' => false,
					'type' => 'BuyerRoleMetricsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
