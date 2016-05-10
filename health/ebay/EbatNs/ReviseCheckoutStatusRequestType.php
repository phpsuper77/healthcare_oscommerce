<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ExternalTransactionType.php';
require_once 'CheckoutMethodCodeType.php';
require_once 'BuyerPaymentMethodCodeType.php';
require_once 'RCSPaymentStatusCodeType.php';
require_once 'CompleteStatusCodeType.php';
require_once 'ItemIDType.php';
require_once 'AmountType.php';
require_once 'InsuranceSelectedCodeType.php';
require_once 'AbstractRequestType.php';
require_once 'AddressType.php';

class ReviseCheckoutStatusRequestType extends AbstractRequestType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var string $TransactionID
	var $TransactionID;
	// @var string $OrderID
	var $OrderID;
	// @var AmountType $AmountPaid
	var $AmountPaid;
	// @var BuyerPaymentMethodCodeType $PaymentMethodUsed
	var $PaymentMethodUsed;
	// @var CompleteStatusCodeType $CheckoutStatus
	var $CheckoutStatus;
	// @var token $ShippingService
	var $ShippingService;
	// @var boolean $ShippingIncludedInTax
	var $ShippingIncludedInTax;
	// @var CheckoutMethodCodeType $CheckoutMethod
	var $CheckoutMethod;
	// @var InsuranceSelectedCodeType $InsuranceType
	var $InsuranceType;
	// @var RCSPaymentStatusCodeType $PaymentStatus
	var $PaymentStatus;
	// @var AmountType $AdjustmentAmount
	var $AdjustmentAmount;
	// @var AddressType $ShippingAddress
	var $ShippingAddress;
	// @var string $BuyerID
	var $BuyerID;
	// @var AmountType $ShippingInsuranceCost
	var $ShippingInsuranceCost;
	// @var AmountType $SalesTax
	var $SalesTax;
	// @var AmountType $ShippingCost
	var $ShippingCost;
	// @var string $EncryptedID
	var $EncryptedID;
	// @var ExternalTransactionType $ExternalTransaction
	var $ExternalTransaction;
	// @var string $MultipleSellerPaymentID
	var $MultipleSellerPaymentID;
	// @var AmountType $CODCost
	var $CODCost;
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

 * @return AmountType
 */
	function getAmountPaid()
	{
		return $this->AmountPaid;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAmountPaid($value)
	{
		$this->AmountPaid = $value;
	}
/**
 *

 * @return BuyerPaymentMethodCodeType
 */
	function getPaymentMethodUsed()
	{
		return $this->PaymentMethodUsed;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaymentMethodUsed($value)
	{
		$this->PaymentMethodUsed = $value;
	}
/**
 *

 * @return CompleteStatusCodeType
 */
	function getCheckoutStatus()
	{
		return $this->CheckoutStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCheckoutStatus($value)
	{
		$this->CheckoutStatus = $value;
	}
/**
 *

 * @return token
 */
	function getShippingService()
	{
		return $this->ShippingService;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingService($value)
	{
		$this->ShippingService = $value;
	}
/**
 *

 * @return boolean
 */
	function getShippingIncludedInTax()
	{
		return $this->ShippingIncludedInTax;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingIncludedInTax($value)
	{
		$this->ShippingIncludedInTax = $value;
	}
/**
 *

 * @return CheckoutMethodCodeType
 */
	function getCheckoutMethod()
	{
		return $this->CheckoutMethod;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCheckoutMethod($value)
	{
		$this->CheckoutMethod = $value;
	}
/**
 *

 * @return InsuranceSelectedCodeType
 */
	function getInsuranceType()
	{
		return $this->InsuranceType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInsuranceType($value)
	{
		$this->InsuranceType = $value;
	}
/**
 *

 * @return RCSPaymentStatusCodeType
 */
	function getPaymentStatus()
	{
		return $this->PaymentStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaymentStatus($value)
	{
		$this->PaymentStatus = $value;
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

 * @return AmountType
 */
	function getShippingInsuranceCost()
	{
		return $this->ShippingInsuranceCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingInsuranceCost($value)
	{
		$this->ShippingInsuranceCost = $value;
	}
/**
 *

 * @return AmountType
 */
	function getSalesTax()
	{
		return $this->SalesTax;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSalesTax($value)
	{
		$this->SalesTax = $value;
	}
/**
 *

 * @return AmountType
 */
	function getShippingCost()
	{
		return $this->ShippingCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingCost($value)
	{
		$this->ShippingCost = $value;
	}
/**
 *

 * @return string
 */
	function getEncryptedID()
	{
		return $this->EncryptedID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEncryptedID($value)
	{
		$this->EncryptedID = $value;
	}
/**
 *

 * @return ExternalTransactionType
 */
	function getExternalTransaction()
	{
		return $this->ExternalTransaction;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setExternalTransaction($value)
	{
		$this->ExternalTransaction = $value;
	}
/**
 *

 * @return string
 */
	function getMultipleSellerPaymentID()
	{
		return $this->MultipleSellerPaymentID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMultipleSellerPaymentID($value)
	{
		$this->MultipleSellerPaymentID = $value;
	}
/**
 *

 * @return AmountType
 */
	function getCODCost()
	{
		return $this->CODCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCODCost($value)
	{
		$this->CODCost = $value;
	}
/**
 *

 * @return 
 */
	function ReviseCheckoutStatusRequestType()
	{
		$this->AbstractRequestType('ReviseCheckoutStatusRequestType', 'urn:ebay:apis:eBLBaseComponents');
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
				'OrderID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AmountPaid' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaymentMethodUsed' =>
				array(
					'required' => false,
					'type' => 'BuyerPaymentMethodCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CheckoutStatus' =>
				array(
					'required' => false,
					'type' => 'CompleteStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingService' =>
				array(
					'required' => false,
					'type' => 'token',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingIncludedInTax' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CheckoutMethod' =>
				array(
					'required' => false,
					'type' => 'CheckoutMethodCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InsuranceType' =>
				array(
					'required' => false,
					'type' => 'InsuranceSelectedCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaymentStatus' =>
				array(
					'required' => false,
					'type' => 'RCSPaymentStatusCodeType',
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
				'ShippingAddress' =>
				array(
					'required' => false,
					'type' => 'AddressType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
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
				'ShippingInsuranceCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SalesTax' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EncryptedID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ExternalTransaction' =>
				array(
					'required' => false,
					'type' => 'ExternalTransactionType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MultipleSellerPaymentID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CODCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
