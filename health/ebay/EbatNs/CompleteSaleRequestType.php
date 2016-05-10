<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ListingTypeCodeType.php';
require_once 'ShipmentType.php';
require_once 'FeedbackInfoType.php';
require_once 'AbstractRequestType.php';
require_once 'ItemIDType.php';

class CompleteSaleRequestType extends AbstractRequestType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var string $TransactionID
	var $TransactionID;
	// @var FeedbackInfoType $FeedbackInfo
	var $FeedbackInfo;
	// @var boolean $Shipped
	var $Shipped;
	// @var boolean $Paid
	var $Paid;
	// @var ListingTypeCodeType $ListingType
	var $ListingType;
	// @var ShipmentType $Shipment
	var $Shipment;
	// @var string $OrderID
	var $OrderID;
	// end props

/**
 *

 * @return ItemIDType
 */
	function getItemID()
	{
		return $this->ItemID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItemID($value)
	{
		$this->ItemID = $value;
	}
/**
 *

 * @return string
 */
	function getTransactionID()
	{
		return $this->TransactionID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTransactionID($value)
	{
		$this->TransactionID = $value;
	}
/**
 *

 * @return FeedbackInfoType
 */
	function getFeedbackInfo()
	{
		return $this->FeedbackInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFeedbackInfo($value)
	{
		$this->FeedbackInfo = $value;
	}
/**
 *

 * @return boolean
 */
	function getShipped()
	{
		return $this->Shipped;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShipped($value)
	{
		$this->Shipped = $value;
	}
/**
 *

 * @return boolean
 */
	function getPaid()
	{
		return $this->Paid;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaid($value)
	{
		$this->Paid = $value;
	}
/**
 *

 * @return ListingTypeCodeType
 */
	function getListingType()
	{
		return $this->ListingType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setListingType($value)
	{
		$this->ListingType = $value;
	}
/**
 *

 * @return ShipmentType
 */
	function getShipment()
	{
		return $this->Shipment;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShipment($value)
	{
		$this->Shipment = $value;
	}
/**
 *

 * @return string
 */
	function getOrderID()
	{
		return $this->OrderID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setOrderID($value)
	{
		$this->OrderID = $value;
	}
/**
 *

 * @return 
 */
	function CompleteSaleRequestType()
	{
		$this->AbstractRequestType('CompleteSaleRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ItemID' =>
				array(
					'required' => false,
					'type' => 'ItemIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TransactionID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FeedbackInfo' =>
				array(
					'required' => false,
					'type' => 'FeedbackInfoType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Shipped' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Paid' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ListingType' =>
				array(
					'required' => false,
					'type' => 'ListingTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Shipment' =>
				array(
					'required' => false,
					'type' => 'ShipmentType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'OrderID' =>
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
