<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ListingTypeCodeType.php';
require_once 'OrderStatusCodeType.php';
require_once 'TradingRoleCodeType.php';
require_once 'PaginationType.php';
require_once 'OrderIDArrayType.php';
require_once 'AbstractRequestType.php';

class GetOrdersRequestType extends AbstractRequestType
{
	// start props
	// @var OrderIDArrayType $OrderIDArray
	var $OrderIDArray;
	// @var dateTime $CreateTimeFrom
	var $CreateTimeFrom;
	// @var dateTime $CreateTimeTo
	var $CreateTimeTo;
	// @var TradingRoleCodeType $OrderRole
	var $OrderRole;
	// @var OrderStatusCodeType $OrderStatus
	var $OrderStatus;
	// @var ListingTypeCodeType $ListingType
	var $ListingType;
	// @var PaginationType $Pagination
	var $Pagination;
	// end props

/**
 *

 * @return OrderIDArrayType
 */
	function getOrderIDArray()
	{
		return $this->OrderIDArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setOrderIDArray($value)
	{
		$this->OrderIDArray = $value;
	}
/**
 *

 * @return dateTime
 */
	function getCreateTimeFrom()
	{
		return $this->CreateTimeFrom;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCreateTimeFrom($value)
	{
		$this->CreateTimeFrom = $value;
	}
/**
 *

 * @return dateTime
 */
	function getCreateTimeTo()
	{
		return $this->CreateTimeTo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCreateTimeTo($value)
	{
		$this->CreateTimeTo = $value;
	}
/**
 *

 * @return TradingRoleCodeType
 */
	function getOrderRole()
	{
		return $this->OrderRole;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setOrderRole($value)
	{
		$this->OrderRole = $value;
	}
/**
 *

 * @return OrderStatusCodeType
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
	function GetOrdersRequestType()
	{
		$this->AbstractRequestType('GetOrdersRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'OrderIDArray' =>
				array(
					'required' => false,
					'type' => 'OrderIDArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CreateTimeFrom' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CreateTimeTo' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'OrderRole' =>
				array(
					'required' => false,
					'type' => 'TradingRoleCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'OrderStatus' =>
				array(
					'required' => false,
					'type' => 'OrderStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
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
