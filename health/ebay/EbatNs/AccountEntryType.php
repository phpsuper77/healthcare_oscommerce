<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'AccountDetailEntryCodeType.php';
require_once 'AmountType.php';
require_once 'ItemIDType.php';

class AccountEntryType extends EbatNs_ComplexType
{
	// start props
	// @var AccountDetailEntryCodeType $AccountDetailsEntryType
	var $AccountDetailsEntryType;
	// @var string $Description
	var $Description;
	// @var AmountType $Balance
	var $Balance;
	// @var dateTime $Date
	var $Date;
	// @var AmountType $GrossDetailAmount
	var $GrossDetailAmount;
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var string $Memo
	var $Memo;
	// @var AmountType $NetDetailAmount
	var $NetDetailAmount;
	// @var string $RefNumber
	var $RefNumber;
	// @var decimal $VATPercent
	var $VATPercent;
	// @var string $Title
	var $Title;
	// end props

/**
 *

 * @return AccountDetailEntryCodeType
 */
	function getAccountDetailsEntryType()
	{
		return $this->AccountDetailsEntryType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAccountDetailsEntryType($value)
	{
		$this->AccountDetailsEntryType = $value;
	}
/**
 *

 * @return string
 */
	function getDescription()
	{
		return $this->Description;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDescription($value)
	{
		$this->Description = $value;
	}
/**
 *

 * @return AmountType
 */
	function getBalance()
	{
		return $this->Balance;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBalance($value)
	{
		$this->Balance = $value;
	}
/**
 *

 * @return dateTime
 */
	function getDate()
	{
		return $this->Date;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDate($value)
	{
		$this->Date = $value;
	}
/**
 *

 * @return AmountType
 */
	function getGrossDetailAmount()
	{
		return $this->GrossDetailAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setGrossDetailAmount($value)
	{
		$this->GrossDetailAmount = $value;
	}
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
	function getMemo()
	{
		return $this->Memo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMemo($value)
	{
		$this->Memo = $value;
	}
/**
 *

 * @return AmountType
 */
	function getNetDetailAmount()
	{
		return $this->NetDetailAmount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNetDetailAmount($value)
	{
		$this->NetDetailAmount = $value;
	}
/**
 *

 * @return string
 */
	function getRefNumber()
	{
		return $this->RefNumber;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRefNumber($value)
	{
		$this->RefNumber = $value;
	}
/**
 *

 * @return decimal
 */
	function getVATPercent()
	{
		return $this->VATPercent;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVATPercent($value)
	{
		$this->VATPercent = $value;
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

 * @return 
 */
	function AccountEntryType()
	{
		$this->EbatNs_ComplexType('AccountEntryType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'AccountDetailsEntryType' =>
				array(
					'required' => false,
					'type' => 'AccountDetailEntryCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Description' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Balance' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Date' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'GrossDetailAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ItemID' =>
				array(
					'required' => false,
					'type' => 'ItemIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Memo' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NetDetailAmount' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RefNumber' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VATPercent' =>
				array(
					'required' => false,
					'type' => 'decimal',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
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
				)
			));

	}
}
?>
