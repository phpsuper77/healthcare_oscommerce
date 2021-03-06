<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'InventoryStatusType.php';
require_once 'AbstractRequestType.php';

class ReviseInventoryStatusRequestType extends AbstractRequestType
{
	// start props
	// @var InventoryStatusType $InventoryStatus
	var $InventoryStatus;
	// end props

/**
 *

 * @return InventoryStatusType
 * @param  $index 
 */
	function getInventoryStatus($index = null)
	{
		if ($index) {
		return $this->InventoryStatus[$index];
	} else {
		return $this->InventoryStatus;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setInventoryStatus($value, $index = null)
	{
		if ($index) {
	$this->InventoryStatus[$index] = $value;
	} else {
	$this->InventoryStatus = $value;
	}

	}
/**
 *

 * @return 
 */
	function ReviseInventoryStatusRequestType()
	{
		$this->AbstractRequestType('ReviseInventoryStatusRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'InventoryStatus' =>
				array(
					'required' => false,
					'type' => 'InventoryStatusType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
