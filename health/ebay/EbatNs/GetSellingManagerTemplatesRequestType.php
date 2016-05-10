<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractRequestType.php';

class GetSellingManagerTemplatesRequestType extends AbstractRequestType
{
	// start props
	// @var long $SaleTemplateID
	var $SaleTemplateID;
	// end props

/**
 *

 * @return long
 * @param  $index 
 */
	function getSaleTemplateID($index = null)
	{
		if ($index) {
		return $this->SaleTemplateID[$index];
	} else {
		return $this->SaleTemplateID;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSaleTemplateID($value, $index = null)
	{
		if ($index) {
	$this->SaleTemplateID[$index] = $value;
	} else {
	$this->SaleTemplateID = $value;
	}

	}
/**
 *

 * @return 
 */
	function GetSellingManagerTemplatesRequestType()
	{
		$this->AbstractRequestType('GetSellingManagerTemplatesRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SaleTemplateID' =>
				array(
					'required' => false,
					'type' => 'long',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>