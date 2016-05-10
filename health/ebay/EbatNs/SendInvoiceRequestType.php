<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'BuyerPaymentMethodCodeType.php';
require_once 'SKUType.php';
require_once 'ShippingServiceOptionsType.php';
require_once 'ItemIDType.php';
require_once 'OrderIDType.php';
require_once 'AmountType.php';
require_once 'InternationalShippingServiceOptionsType.php';
require_once 'SalesTaxType.php';
require_once 'AbstractRequestType.php';
require_once 'InsuranceOptionCodeType.php';

class SendInvoiceRequestType extends AbstractRequestType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var string $TransactionID
	var $TransactionID;
	// @var OrderIDType $OrderID
	var $OrderID;
	// @var InternationalShippingServiceOptionsType $InternationalShippingServiceOptions
	var $InternationalShippingServiceOptions;
	// @var ShippingServiceOptionsType $ShippingServiceOptions
	var $ShippingServiceOptions;
	// @var SalesTaxType $SalesTax
	var $SalesTax;
	// @var InsuranceOptionCodeType $InsuranceOption
	var $InsuranceOption;
	// @var AmountType $InsuranceFee
	var $InsuranceFee;
	// @var BuyerPaymentMethodCodeType $PaymentMethods
	var $PaymentMethods;
	// @var string $PayPalEmailAddress
	var $PayPalEmailAddress;
	// @var string $CheckoutInstructions
	var $CheckoutInstructions;
	// @var boolean $EmailCopyToSeller
	var $EmailCopyToSeller;
	// @var AmountType $CODCost
	var $CODCost;
	// @var SKUType $SKU
	var $SKU;
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

 * @return InternationalShippingServiceOptionsType
 * @param  $index 
 */
	function getInternationalShippingServiceOptions($index = null)
	{
		if ($index) {
		return $this->InternationalShippingServiceOptions[$index];
	} else {
		return $this->InternationalShippingServiceOptions;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setInternationalShippingServiceOptions($value, $index = null)
	{
		if ($index) {
	$this->InternationalShippingServiceOptions[$index] = $value;
	} else {
	$this->InternationalShippingServiceOptions = $value;
	}

	}
/**
 *

 * @return ShippingServiceOptionsType
 * @param  $index 
 */
	function getShippingServiceOptions($index = null)
	{
		if ($index) {
		return $this->ShippingServiceOptions[$index];
	} else {
		return $this->ShippingServiceOptions;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setShippingServiceOptions($value, $index = null)
	{
		if ($index) {
	$this->ShippingServiceOptions[$index] = $value;
	} else {
	$this->ShippingServiceOptions = $value;
	}

	}
/**
 *

 * @return SalesTaxType
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

 * @return InsuranceOptionCodeType
 */
	function getInsuranceOption()
	{
		return $this->InsuranceOption;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInsuranceOption($value)
	{
		$this->InsuranceOption = $value;
	}
/**
 *

 * @return AmountType
 */
	function getInsuranceFee()
	{
		return $this->InsuranceFee;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInsuranceFee($value)
	{
		$this->InsuranceFee = $value;
	}
/**
 *

 * @return BuyerPaymentMethodCodeType
 * @param  $index 
 */
	function getPaymentMethods($index = null)
	{
		if ($index) {
		return $this->PaymentMethods[$index];
	} else {
		return $this->PaymentMethods;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setPaymentMethods($value, $index = null)
	{
		if ($index) {
	$this->PaymentMethods[$index] = $value;
	} else {
	$this->PaymentMethods = $value;
	}

	}
/**
 *

 * @return string
 */
	function getPayPalEmailAddress()
	{
		return $this->PayPalEmailAddress;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPayPalEmailAddress($value)
	{
		$this->PayPalEmailAddress = $value;
	}
/**
 *

 * @return string
 */
	function getCheckoutInstructions()
	{
		return $this->CheckoutInstructions;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCheckoutInstructions($value)
	{
		$this->CheckoutInstructions = $value;
	}
/**
 *

 * @return boolean
 */
	function getEmailCopyToSeller()
	{
		return $this->EmailCopyToSeller;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEmailCopyToSeller($value)
	{
		$this->EmailCopyToSeller = $value;
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

 * @return SKUType
 */
	function getSKU()
	{
		return $this->SKU;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSKU($value)
	{
		$this->SKU = $value;
	}
/**
 *

 * @return 
 */
	function SendInvoiceRequestType()
	{
		$this->AbstractRequestType('SendInvoiceRequestType', 'urn:ebay:apis:eBLBaseComponents');
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
				'InternationalShippingServiceOptions' =>
				array(
					'required' => false,
					'type' => 'InternationalShippingServiceOptionsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'ShippingServiceOptions' =>
				array(
					'required' => false,
					'type' => 'ShippingServiceOptionsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'SalesTax' =>
				array(
					'required' => false,
					'type' => 'SalesTaxType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InsuranceOption' =>
				array(
					'required' => false,
					'type' => 'InsuranceOptionCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'InsuranceFee' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PaymentMethods' =>
				array(
					'required' => false,
					'type' => 'BuyerPaymentMethodCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'PayPalEmailAddress' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CheckoutInstructions' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EmailCopyToSeller' =>
				array(
					'required' => false,
					'type' => 'boolean',
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
				),
				'SKU' =>
				array(
					'required' => false,
					'type' => 'SKUType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
