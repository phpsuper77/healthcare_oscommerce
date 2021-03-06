<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ItemType.php';
require_once 'AbstractRequestType.php';

class AddSellingManagerTemplateRequestType extends AbstractRequestType
{
	// start props
	// @var ItemType $Item
	var $Item;
	// @var string $SaleTemplateName
	var $SaleTemplateName;
	// @var long $ProductID
	var $ProductID;
	// end props

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

 * @return string
 */
	function getSaleTemplateName()
	{
		return $this->SaleTemplateName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSaleTemplateName($value)
	{
		$this->SaleTemplateName = $value;
	}
/**
 *

 * @return long
 */
	function getProductID()
	{
		return $this->ProductID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProductID($value)
	{
		$this->ProductID = $value;
	}
/**
 *

 * @return 
 */
	function AddSellingManagerTemplateRequestType()
	{
		$this->AbstractRequestType('AddSellingManagerTemplateRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Item' =>
				array(
					'required' => false,
					'type' => 'ItemType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SaleTemplateName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ProductID' =>
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
