<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'AmountType.php';
require_once 'SellingManagerVendorDetailsType.php';

class SellingManagerProductDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var string $ProductName
	var $ProductName;
	// @var long $ProductID
	var $ProductID;
	// @var string $CustomLabel
	var $CustomLabel;
	// @var int $QuantityAvailable
	var $QuantityAvailable;
	// @var AmountType $UnitCost
	var $UnitCost;
	// @var long $FolderID
	var $FolderID;
	// @var boolean $RestockAlert
	var $RestockAlert;
	// @var int $RestockThreshold
	var $RestockThreshold;
	// @var SellingManagerVendorDetailsType $VendorInfo
	var $VendorInfo;
	// @var string $Note
	var $Note;
	// end props

/**
 *

 * @return string
 */
	function getProductName()
	{
		return $this->ProductName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProductName($value)
	{
		$this->ProductName = $value;
	}
/**
 *

 * @return long
 */
	function getProductID()
	{
		return $this->ProductID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProductID($value)
	{
		$this->ProductID = $value;
	}
/**
 *

 * @return string
 */
	function getCustomLabel()
	{
		return $this->CustomLabel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCustomLabel($value)
	{
		$this->CustomLabel = $value;
	}
/**
 *

 * @return int
 */
	function getQuantityAvailable()
	{
		return $this->QuantityAvailable;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setQuantityAvailable($value)
	{
		$this->QuantityAvailable = $value;
	}
/**
 *

 * @return AmountType
 */
	function getUnitCost()
	{
		return $this->UnitCost;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setUnitCost($value)
	{
		$this->UnitCost = $value;
	}
/**
 *

 * @return long
 */
	function getFolderID()
	{
		return $this->FolderID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFolderID($value)
	{
		$this->FolderID = $value;
	}
/**
 *

 * @return boolean
 */
	function getRestockAlert()
	{
		return $this->RestockAlert;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRestockAlert($value)
	{
		$this->RestockAlert = $value;
	}
/**
 *

 * @return int
 */
	function getRestockThreshold()
	{
		return $this->RestockThreshold;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRestockThreshold($value)
	{
		$this->RestockThreshold = $value;
	}
/**
 *

 * @return SellingManagerVendorDetailsType
 */
	function getVendorInfo()
	{
		return $this->VendorInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVendorInfo($value)
	{
		$this->VendorInfo = $value;
	}
/**
 *

 * @return string
 */
	function getNote()
	{
		return $this->Note;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNote($value)
	{
		$this->Note = $value;
	}
/**
 *

 * @return 
 */
	function SellingManagerProductDetailsType()
	{
		$this->EbatNs_ComplexType('SellingManagerProductDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ProductName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ProductID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CustomLabel' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'QuantityAvailable' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'UnitCost' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FolderID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RestockAlert' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RestockThreshold' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VendorInfo' =>
				array(
					'required' => false,
					'type' => 'SellingManagerVendorDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Note' =>
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
