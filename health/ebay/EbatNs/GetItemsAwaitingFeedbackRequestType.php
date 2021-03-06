<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'PaginationType.php';
require_once 'ItemSortTypeCodeType.php';
require_once 'AbstractRequestType.php';

class GetItemsAwaitingFeedbackRequestType extends AbstractRequestType
{
	// start props
	// @var ItemSortTypeCodeType $Sort
	var $Sort;
	// @var PaginationType $Pagination
	var $Pagination;
	// end props

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
	function GetItemsAwaitingFeedbackRequestType()
	{
		$this->AbstractRequestType('GetItemsAwaitingFeedbackRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Sort' =>
				array(
					'required' => false,
					'type' => 'ItemSortTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
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
