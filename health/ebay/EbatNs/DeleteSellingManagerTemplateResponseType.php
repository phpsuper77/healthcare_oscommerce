<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractResponseType.php';

class DeleteSellingManagerTemplateResponseType extends AbstractResponseType
{
	// start props
	// @var string $DeletedSaleTemplateID
	var $DeletedSaleTemplateID;
	// @var string $DeletedSaleTemplateName
	var $DeletedSaleTemplateName;
	// end props

/**
 *

 * @return string
 */
	function getDeletedSaleTemplateID()
	{
		return $this->DeletedSaleTemplateID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDeletedSaleTemplateID($value)
	{
		$this->DeletedSaleTemplateID = $value;
	}
/**
 *

 * @return string
 */
	function getDeletedSaleTemplateName()
	{
		return $this->DeletedSaleTemplateName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDeletedSaleTemplateName($value)
	{
		$this->DeletedSaleTemplateName = $value;
	}
/**
 *

 * @return 
 */
	function DeleteSellingManagerTemplateResponseType()
	{
		$this->AbstractResponseType('DeleteSellingManagerTemplateResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'DeletedSaleTemplateID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DeletedSaleTemplateName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
