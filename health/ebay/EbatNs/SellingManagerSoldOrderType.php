<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'UnpaidItemStatusTypeCodeType.php';
require_once 'AddressType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'VATRateType.php';
require_once 'SellingManagerOrderStatusType.php';
require_once 'AmountType.php';
require_once 'SellingManagerSoldTransactionType.php';
require_once 'ShippingDetailsType.php';

class SellingManagerSoldOrderType extends EbatNs_ComplexType
{
	// start props
	// @var SellingManagerSoldTransactionType $SellingManagerSoldTransaction
	var $SellingManagerSoldTransaction;
	// @var AddressType $ShippingAddress
	var $ShippingAddress;
	// @var ShippingDetailsType $ShippingDetails
	var $ShippingDetails;
	// @var AmountType $CashOnDeliveryCost
	var $CashOnDeliveryCost;
	// @var AmountType $TotalAmount
	var $TotalAmount;
	// @var int $TotalQuantity
	var $TotalQuantity;
	// @var AmountType $ItemCost
	var $ItemCost;
	// @var VATRateType $VATRate
	var $VATRate;
	// @var AmountType $NetInsuranceFee
	var $NetInsuranceFee;
	// @var AmountType $VATInsuranceFee
	var $VATInsuranceFee;
	// @var AmountType $VATShippingFee
	var $VATShippingFee;
	// @var AmountType $NetShippingFee
	var $NetShippingFee;
	// @var AmountType $NetTotalAmount
	var $NetTotalAmount;
	// @var AmountType $VATTotalAmount
	var $VATTotalAmount;
	// @var AmountType $ActualShippingCost
	var $ActualShippingCost;
	// @var AmountType $AdjustmentAmount
	var $AdjustmentAmount;
	// @var string $NotesToBuyer
	var $NotesToBuyer;
	// @var string $NotesFromBuyer
	var $NotesFromBuyer;
	// @var string $NotesToSeller
	var $NotesToSeller;
	// @var SellingManagerOrderStatusType $OrderStatus
	var $OrderStatus;
	// @var UnpaidItemStatusTypeCodeType $UnpaidItemStatus
	var $UnpaidItemStatus;
	// @var AmountType $SalePrice
	var $SalePrice;
	// @var int $EmailsSent
	var $EmailsSent;
	// @var int $DaysSinceSale
	var $DaysSinceSale;
	// @var string $BuyerID
	var $BuyerID;
	// @var string $BuyerEmail
	var $BuyerEmail;
	// @var long $SaleRecordID
	var $SaleRecordID;
	// @var dateTime $CreationTime
	var $CreationTime;
	// end props

/**
 *

 * @return SellingManagerSoldTransactionType
 * @param  $index 
 */
	function getSellingManagerSoldTransaction($index = null)
	{
		if ($index) {
		return $this->SellingManagerSoldTransaction[$index];
	} else {
		return $this->SellingManagerSoldTransaction;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSellingManagerSoldTransaction($value, $index = null)
	{
		if ($index) {
	$this->SellingManagerSoldTransaction[$index] = $value;
	} else {
	$this->SellingManagerSoldTransaction = $value;
	}

	}
/**
 *

 * @return AddressType
 */
	function getShippingAddress()
	{
		return $this->ShippingAddress;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingAddress($value)
	{
		$this->ShippingAddress = $value;
	}
/**
 *

 * @return ShippingDetailsType
 */
	function getShippingDetails()
	{
		return $this->ShippingDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingDetails($value)
	{
		$this->ShippingDetails = $value;
	}
/**
 *

 * @return AmountType
 */
	function getCashOnDeliveryCost()
	{
		return $this->CashOnDeliveryCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCashOnDeliveryCost($value)
	{
		$this->CashOnDeliveryCost = $value;
	}
/**
 *

 * @return AmountType
 */
	function getTotalAmount()
	{
		return $this->TotalAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTotalAmount($value)
	{
		$this->TotalAmount = $value;
	}
/**
 *

 * @return int
 */
	function getTotalQuantity()
	{
		return $this->TotalQuantity;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTotalQuantity($value)
	{
		$this->TotalQuantity = $value;
	}
/**
 *

 * @return AmountType
 */
	function getItemCost()
	{
		return $this->ItemCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItemCost($value)
	{
		$this->ItemCost = $value;
	}
/**
 *

 * @return VATRateType
 * @param  $index 
 */
	function getVATRate($index = null)
	{
		if ($index) {
		return $this->VATRate[$index];
	} else {
		return $this->VATRate;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setVATRate($value, $index = null)
	{
		if ($index) {
	$this->VATRate[$index] = $value;
	} else {
	$this->VATRate = $value;
	}

	}
/**
 *

 * @return AmountType
 */
	function getNetInsuranceFee()
	{
		return $this->NetInsuranceFee;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNetInsuranceFee($value)
	{
		$this->NetInsuranceFee = $value;
	}
/**
 *

 * @return AmountType
 */
	function getVATInsuranceFee()
	{
		return $this->VATInsuranceFee;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATInsuranceFee($value)
	{
		$this->VATInsuranceFee = $value;
	}
/**
 *

 * @return AmountType
 */
	function getVATShippingFee()
	{
		return $this->VATShippingFee;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATShippingFee($value)
	{
		$this->VATShippingFee = $value;
	}
/**
 *

 * @return AmountType
 */
	function getNetShippingFee()
	{
		return $this->NetShippingFee;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNetShippingFee($value)
	{
		$this->NetShippingFee = $value;
	}
/**
 *

 * @return AmountType
 */
	function getNetTotalAmount()
	{
		return $this->NetTotalAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNetTotalAmount($value)
	{
		$this->NetTotalAmount = $value;
	}
/**
 *

 * @return AmountType
 */
	function getVATTotalAmount()
	{
		return $this->VATTotalAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATTotalAmount($value)
	{
		$this->VATTotalAmount = $value;
	}
/**
 *

 * @return AmountType
 */
	function getActualShippingCost()
	{
		return $this->ActualShippingCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setActualShippingCost($value)
	{
		$this->ActualShippingCost = $value;
	}
/**
 *

 * @return AmountType
 */
	function getAdjustmentAmount()
	{
		return $this->AdjustmentAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAdjustmentAmount($value)
	{
		$this->AdjustmentAmount = $value;
	}
/**
 *

 * @return string
 */
	function getNotesToBuyer()
	{
		return $this->NotesToBuyer;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNotesToBuyer($value)
	{
		$this->NotesToBuyer = $value;
	}
/**
 *

 * @return string
 */
	function getNotesFromBuyer()
	{
		return $this->NotesFromBuyer;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNotesFromBuyer($value)
	{
		$this->NotesFromBuyer = $value;
	}
/**
 *

 * @return string
 */
	function getNotesToSeller()
	{
		return $this->NotesToSeller;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNotesToSeller($value)
	{
		$this->NotesToSeller = $value;
	}
/**
 *

 * @return SellingManagerOrderStatusType
 */
	function getOrderStatus()
	{
		return $this->OrderStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setOrderStatus($value)
	{
		$this->OrderStatus = $value;
	}
/**
 *

 * @return UnpaidItemStatusTypeCodeType
 */
	function getUnpaidItemStatus()
	{
		return $this->UnpaidItemStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUnpaidItemStatus($value)
	{
		$this->UnpaidItemStatus = $value;
	}
/**
 *

 * @return AmountType
 */
	function getSalePrice()
	{
		return $this->SalePrice;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSalePrice($value)
	{
		$this->SalePrice = $value;
	}
/**
 *

 * @return int
 */
	function getEmailsSent()
	{
		return $this->EmailsSent;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEmailsSent($value)
	{
		$this->EmailsSent = $value;
	}
/**
 *

 * @return int
 */
	function getDaysSinceSale()
	{
		return $this->DaysSinceSale;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDaysSinceSale($value)
	{
		$this->DaysSinceSale = $value;
	}
/**
 *

 * @return string
 */
	function getBuyerID()
	{
		return $this->BuyerID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBuyerID($value)
	{
		$this->BuyerID = $value;
	}
/**
 *

 * @return string
 */
	function getBuyerEmail()
	{
		return $this->BuyerEmail;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBuyerEmail($value)
	{
		$this->BuyerEmail = $value;
	}
/**
 *

 * @return long
 */
	function getSaleRecordID()
	{
		return $this->SaleRecordID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSaleRecordID($value)
	{
		$this->SaleRecordID = $value;
	}
/**
 *

 * @return dateTime
 */
	function getCreationTime()
	{
		return $this->CreationTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCreationTime($value)
	{
		$this->CreationTime = $value;
	}
/**
 *

 * @return 
 */
	function SellingManagerSoldOrderType()
	{
		$this->EbatNs_ComplexType('SellingManagerSoldOrderType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SellingManagerSoldTransaction' =>
				array(
					'required' => false,
					'type' => 'SellingManagerSoldTransactionType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingAddress' =>
				array(
					'required' => false,
					'type' => 'AddressType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingDetails' =>
				array(
					'required' => false,
					'type' => 'ShippingDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CashOnDeliveryCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TotalAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TotalQuantity' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ItemCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATRate' =>
				array(
					'required' => false,
					'type' => 'VATRateType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'NetInsuranceFee' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATInsuranceFee' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATShippingFee' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NetShippingFee' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NetTotalAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATTotalAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ActualShippingCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AdjustmentAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NotesToBuyer' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NotesFromBuyer' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NotesToSeller' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'OrderStatus' =>
				array(
					'required' => false,
					'type' => 'SellingManagerOrderStatusType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UnpaidItemStatus' =>
				array(
					'required' => false,
					'type' => 'UnpaidItemStatusTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SalePrice' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EmailsSent' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DaysSinceSale' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BuyerID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BuyerEmail' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SaleRecordID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CreationTime' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
