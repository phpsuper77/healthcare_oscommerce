<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AmountType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'ExternalProductIDType.php';
require_once 'OrderIDType.php';
require_once 'PaymentTypeCodeType.php';
require_once 'ItemIDType.php';

class SellerPaymentType extends EbatNs_ComplexType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var string $TransactionID
	var $TransactionID;
	// @var OrderIDType $OrderID
	var $OrderID;
	// @var string $SellerInventoryID
	var $SellerInventoryID;
	// @var string $PrivateNotes
	var $PrivateNotes;
	// @var ExternalProductIDType $ExternalProductID
	var $ExternalProductID;
	// @var string $Title
	var $Title;
	// @var PaymentTypeCodeType $PaymentType
	var $PaymentType;
	// @var AmountType $TransactionPrice
	var $TransactionPrice;
	// @var AmountType $ShippingReimbursement
	var $ShippingReimbursement;
	// @var AmountType $Commission
	var $Commission;
	// @var AmountType $AmountPaid
	var $AmountPaid;
	// @var dateTime $PaidTime
	var $PaidTime;
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

 * @return OrderIDType
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

 * @return string
 */
	function getSellerInventoryID()
	{
		return $this->SellerInventoryID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerInventoryID($value)
	{
		$this->SellerInventoryID = $value;
	}
/**
 *

 * @return string
 */
	function getPrivateNotes()
	{
		return $this->PrivateNotes;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPrivateNotes($value)
	{
		$this->PrivateNotes = $value;
	}
/**
 *

 * @return ExternalProductIDType
 */
	function getExternalProductID()
	{
		return $this->ExternalProductID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setExternalProductID($value)
	{
		$this->ExternalProductID = $value;
	}
/**
 *

 * @return string
 */
	function getTitle()
	{
		return $this->Title;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTitle($value)
	{
		$this->Title = $value;
	}
/**
 *

 * @return PaymentTypeCodeType
 */
	function getPaymentType()
	{
		return $this->PaymentType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaymentType($value)
	{
		$this->PaymentType = $value;
	}
/**
 *

 * @return AmountType
 */
	function getTransactionPrice()
	{
		return $this->TransactionPrice;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTransactionPrice($value)
	{
		$this->TransactionPrice = $value;
	}
/**
 *

 * @return AmountType
 */
	function getShippingReimbursement()
	{
		return $this->ShippingReimbursement;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShippingReimbursement($value)
	{
		$this->ShippingReimbursement = $value;
	}
/**
 *

 * @return AmountType
 */
	function getCommission()
	{
		return $this->Commission;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCommission($value)
	{
		$this->Commission = $value;
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

 * @return dateTime
 */
	function getPaidTime()
	{
		return $this->PaidTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaidTime($value)
	{
		$this->PaidTime = $value;
	}
/**
 *

 * @return 
 */
	function SellerPaymentType()
	{
		$this->EbatNs_ComplexType('SellerPaymentType', 'urn:ebay:apis:eBLBaseComponents');
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
					'type' => 'OrderIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerInventoryID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PrivateNotes' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ExternalProductID' =>
				array(
					'required' => false,
					'type' => 'ExternalProductIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Title' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaymentType' =>
				array(
					'required' => false,
					'type' => 'PaymentTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TransactionPrice' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShippingReimbursement' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Commission' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
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
				'PaidTime' =>
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
