<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'UserIDType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'BidderStatusCodeType.php';
require_once 'AmountType.php';

class BidApprovalType extends EbatNs_ComplexType
{
	// start props
	// @var UserIDType $UserID
	var $UserID;
	// @var AmountType $ApprovedBiddingLimit
	var $ApprovedBiddingLimit;
	// @var string $DeclinedComment
	var $DeclinedComment;
	// @var BidderStatusCodeType $Status
	var $Status;
	// end props

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

 * @return AmountType
 */
	function getApprovedBiddingLimit()
	{
		return $this->ApprovedBiddingLimit;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setApprovedBiddingLimit($value)
	{
		$this->ApprovedBiddingLimit = $value;
	}
/**
 *

 * @return string
 */
	function getDeclinedComment()
	{
		return $this->DeclinedComment;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDeclinedComment($value)
	{
		$this->DeclinedComment = $value;
	}
/**
 *

 * @return BidderStatusCodeType
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

 * @return 
 */
	function BidApprovalType()
	{
		$this->EbatNs_ComplexType('BidApprovalType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'UserID' =>
				array(
					'required' => false,
					'type' => 'UserIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ApprovedBiddingLimit' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DeclinedComment' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Status' =>
				array(
					'required' => false,
					'type' => 'BidderStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
