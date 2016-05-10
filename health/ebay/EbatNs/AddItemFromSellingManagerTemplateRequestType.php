<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ItemType.php';
require_once 'AbstractRequestType.php';

class AddItemFromSellingManagerTemplateRequestType extends AbstractRequestType
{
	// start props
	// @var long $SaleTemplateID
	var $SaleTemplateID;
	// @var dateTime $ScheduleTime
	var $ScheduleTime;
	// @var ItemType $Item
	var $Item;
	// end props

/**
 *

 * @return long
 */
	function getSaleTemplateID()
	{
		return $this->SaleTemplateID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSaleTemplateID($value)
	{
		$this->SaleTemplateID = $value;
	}
/**
 *

 * @return dateTime
 */
	function getScheduleTime()
	{
		return $this->ScheduleTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setScheduleTime($value)
	{
		$this->ScheduleTime = $value;
	}
/**
 *

 * @return ItemType
 */
	function getItem()
	{
		return $this->Item;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItem($value)
	{
		$this->Item = $value;
	}
/**
 *

 * @return 
 */
	function AddItemFromSellingManagerTemplateRequestType()
	{
		$this->AbstractRequestType('AddItemFromSellingManagerTemplateRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SaleTemplateID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ScheduleTime' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Item' =>
				array(
					'required' => false,
					'type' => 'ItemType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
