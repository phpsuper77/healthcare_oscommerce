<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class OrderStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Active
	var $Active = 'Active';
	// @var string $Inactive
	var $Inactive = 'Inactive';
	// @var string $Completed
	var $Completed = 'Completed';
	// @var string $Cancelled
	var $Cancelled = 'Cancelled';
	// @var string $Shipped
	var $Shipped = 'Shipped';
	// @var string $Default
	var $Default = 'Default';
	// @var string $Authenticated
	var $Authenticated = 'Authenticated';
	// @var string $InProcess
	var $InProcess = 'InProcess';
	// @var string $Invalid
	var $Invalid = 'Invalid';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function OrderStatusCodeType()
	{
		$this->EbatNs_FacetType('OrderStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_OrderStatusCodeType = new OrderStatusCodeType();

?>
