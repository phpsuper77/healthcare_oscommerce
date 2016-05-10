<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SKUType.php';
require_once 'NameValueListArrayType.php';
require_once 'AbstractRequestType.php';
require_once 'ItemIDType.php';

class GetItemRequestType extends AbstractRequestType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var boolean $IncludeWatchCount
	var $IncludeWatchCount;
	// @var boolean $IncludeCrossPromotion
	var $IncludeCrossPromotion;
	// @var boolean $IncludeItemSpecifics
	var $IncludeItemSpecifics;
	// @var boolean $IncludeTaxTable
	var $IncludeTaxTable;
	// @var SKUType $SKU
	var $SKU;
	// @var SKUType $VariationSKU
	var $VariationSKU;
	// @var NameValueListArrayType $VariationSpecifics
	var $VariationSpecifics;
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

 * @return boolean
 */
	function getIncludeWatchCount()
	{
		return $this->IncludeWatchCount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeWatchCount($value)
	{
		$this->IncludeWatchCount = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeCrossPromotion()
	{
		return $this->IncludeCrossPromotion;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeCrossPromotion($value)
	{
		$this->IncludeCrossPromotion = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeItemSpecifics()
	{
		return $this->IncludeItemSpecifics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeItemSpecifics($value)
	{
		$this->IncludeItemSpecifics = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeTaxTable()
	{
		return $this->IncludeTaxTable;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeTaxTable($value)
	{
		$this->IncludeTaxTable = $value;
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

 * @return SKUType
 */
	function getVariationSKU()
	{
		return $this->VariationSKU;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVariationSKU($value)
	{
		$this->VariationSKU = $value;
	}
/**
 *

 * @return NameValueListArrayType
 */
	function getVariationSpecifics()
	{
		return $this->VariationSpecifics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVariationSpecifics($value)
	{
		$this->VariationSpecifics = $value;
	}
/**
 *

 * @return 
 */
	function GetItemRequestType()
	{
		$this->AbstractRequestType('GetItemRequestType', 'urn:ebay:apis:eBLBaseComponents');
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
				'IncludeWatchCount' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeCrossPromotion' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeItemSpecifics' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeTaxTable' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
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
				),
				'VariationSKU' =>
				array(
					'required' => false,
					'type' => 'SKUType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VariationSpecifics' =>
				array(
					'required' => false,
					'type' => 'NameValueListArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
