<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ListingTypeCodeType.php';
require_once 'PaginationType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'ItemSortTypeCodeType.php';

class ItemListCustomizationType extends EbatNs_ComplexType
{
	// start props
	// @var boolean $Include
	var $Include;
	// @var ListingTypeCodeType $ListingType
	var $ListingType;
	// @var ItemSortTypeCodeType $Sort
	var $Sort;
	// @var int $DurationInDays
	var $DurationInDays;
	// @var boolean $IncludeNotes
	var $IncludeNotes;
	// @var PaginationType $Pagination
	var $Pagination;
	// end props

/**
 *

 * @return boolean
 */
	function getInclude()
	{
		return $this->Include;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setInclude($value)
	{
		$this->Include = $value;
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

 * @return ItemSortTypeCodeType
 */
	function getSort()
	{
		return $this->Sort;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSort($value)
	{
		$this->Sort = $value;
	}
/**
 *

 * @return int
 */
	function getDurationInDays()
	{
		return $this->DurationInDays;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDurationInDays($value)
	{
		$this->DurationInDays = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeNotes()
	{
		return $this->IncludeNotes;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeNotes($value)
	{
		$this->IncludeNotes = $value;
	}
/**
 *

 * @return PaginationType
 */
	function getPagination()
	{
		return $this->Pagination;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPagination($value)
	{
		$this->Pagination = $value;
	}
/**
 *

 * @return 
 */
	function ItemListCustomizationType()
	{
		$this->EbatNs_ComplexType('ItemListCustomizationType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Include' =>
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
				'Sort' =>
				array(
					'required' => false,
					'type' => 'ItemSortTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DurationInDays' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeNotes' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Pagination' =>
				array(
					'required' => false,
					'type' => 'PaginationType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
