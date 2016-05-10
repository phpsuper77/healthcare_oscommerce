<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'AmountType.php';

class RefundType extends EbatNs_ComplexType
{
	// start props
	// @var AmountType $RefundFromSeller
	var $RefundFromSeller;
	// @var AmountType $TotalRefundToBuyer
	var $TotalRefundToBuyer;
	// @var dateTime $RefundTime
	var $RefundTime;
	// end props

/**
 *

 * @return AmountType
 */
	function getRefundFromSeller()
	{
		return $this->RefundFromSeller;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRefundFromSeller($value)
	{
		$this->RefundFromSeller = $value;
	}
/**
 *

 * @return AmountType
 */
	function getTotalRefundToBuyer()
	{
		return $this->TotalRefundToBuyer;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTotalRefundToBuyer($value)
	{
		$this->TotalRefundToBuyer = $value;
	}
/**
 *

 * @return dateTime
 */
	function getRefundTime()
	{
		return $this->RefundTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRefundTime($value)
	{
		$this->RefundTime = $value;
	}
/**
 *

 * @return 
 */
	function RefundType()
	{
		$this->EbatNs_ComplexType('RefundType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'RefundFromSeller' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TotalRefundToBuyer' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RefundTime' =>
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