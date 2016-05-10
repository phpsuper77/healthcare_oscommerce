<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ItemArrayType.php';
require_once 'AbstractResponseType.php';
require_once 'UserType.php';

class GetBidderListResponseType extends AbstractResponseType
{
	// start props
	// @var UserType $Bidder
	var $Bidder;
	// @var ItemArrayType $BidItemArray
	var $BidItemArray;
	// end props

/**
 *

 * @return UserType
 */
	function getBidder()
	{
		return $this->Bidder;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBidder($value)
	{
		$this->Bidder = $value;
	}
/**
 *

 * @return ItemArrayType
 */
	function getBidItemArray()
	{
		return $this->BidItemArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBidItemArray($value)
	{
		$this->BidItemArray = $value;
	}
/**
 *

 * @return 
 */
	function GetBidderListResponseType()
	{
		$this->AbstractResponseType('GetBidderListResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Bidder' =>
				array(
					'required' => false,
					'type' => 'UserType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BidItemArray' =>
				array(
					'required' => false,
					'type' => 'ItemArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
