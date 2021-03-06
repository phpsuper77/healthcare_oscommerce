<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractResponseType.php';
require_once 'OrderArrayType.php';

class GetOrderTransactionsResponseType extends AbstractResponseType
{
	// start props
	// @var OrderArrayType $OrderArray
	var $OrderArray;
	// end props

/**
 *

 * @return OrderArrayType
 */
	function getOrderArray()
	{
		return $this->OrderArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setOrderArray($value)
	{
		$this->OrderArray = $value;
	}
/**
 *

 * @return 
 */
	function GetOrderTransactionsResponseType()
	{
		$this->AbstractResponseType('GetOrderTransactionsResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'OrderArray' =>
				array(
					'required' => false,
					'type' => 'OrderArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
