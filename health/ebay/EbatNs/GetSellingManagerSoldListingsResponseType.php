<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SellingManagerSoldOrderType.php';
require_once 'AbstractResponseType.php';
require_once 'PaginationResultType.php';

class GetSellingManagerSoldListingsResponseType extends AbstractResponseType
{
	// start props
	// @var SellingManagerSoldOrderType $SaleRecord
	var $SaleRecord;
	// @var PaginationResultType $PaginationResult
	var $PaginationResult;
	// end props

/**
 *

 * @return SellingManagerSoldOrderType
 * @param  $index 
 */
	function getSaleRecord($index = null)
	{
		if ($index) {
		return $this->SaleRecord[$index];
	} else {
		return $this->SaleRecord;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSaleRecord($value, $index = null)
	{
		if ($index) {
	$this->SaleRecord[$index] = $value;
	} else {
	$this->SaleRecord = $value;
	}

	}
/**
 *

 * @return PaginationResultType
 */
	function getPaginationResult()
	{
		return $this->PaginationResult;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPaginationResult($value)
	{
		$this->PaginationResult = $value;
	}
/**
 *

 * @return 
 */
	function GetSellingManagerSoldListingsResponseType()
	{
		$this->AbstractResponseType('GetSellingManagerSoldListingsResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SaleRecord' =>
				array(
					'required' => false,
					'type' => 'SellingManagerSoldOrderType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'PaginationResult' =>
				array(
					'required' => false,
					'type' => 'PaginationResultType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
