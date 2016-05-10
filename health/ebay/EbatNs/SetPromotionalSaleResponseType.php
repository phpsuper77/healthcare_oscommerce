<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractResponseType.php';
require_once 'PromotionalSaleStatusCodeType.php';

class SetPromotionalSaleResponseType extends AbstractResponseType
{
	// start props
	// @var PromotionalSaleStatusCodeType $Status
	var $Status;
	// @var long $PromotionalSaleID
	var $PromotionalSaleID;
	// end props

/**
 *

 * @return PromotionalSaleStatusCodeType
 */
	function getStatus()
	{
		return $this->Status;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStatus($value)
	{
		$this->Status = $value;
	}
/**
 *

 * @return long
 */
	function getPromotionalSaleID()
	{
		return $this->PromotionalSaleID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPromotionalSaleID($value)
	{
		$this->PromotionalSaleID = $value;
	}
/**
 *

 * @return 
 */
	function SetPromotionalSaleResponseType()
	{
		$this->AbstractResponseType('SetPromotionalSaleResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Status' =>
				array(
					'required' => false,
					'type' => 'PromotionalSaleStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PromotionalSaleID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
