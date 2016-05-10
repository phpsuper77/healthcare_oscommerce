<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SellingManagerTemplateDetailsType.php';
require_once 'EbatNs_ComplexType.php';

class SellingManagerTemplateDetailsArrayType extends EbatNs_ComplexType
{
	// start props
	// @var SellingManagerTemplateDetailsType $SellingManagerTemplateDetails
	var $SellingManagerTemplateDetails;
	// end props

/**
 *

 * @return SellingManagerTemplateDetailsType
 * @param  $index 
 */
	function getSellingManagerTemplateDetails($index = null)
	{
		if ($index) {
		return $this->SellingManagerTemplateDetails[$index];
	} else {
		return $this->SellingManagerTemplateDetails;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setSellingManagerTemplateDetails($value, $index = null)
	{
		if ($index) {
	$this->SellingManagerTemplateDetails[$index] = $value;
	} else {
	$this->SellingManagerTemplateDetails = $value;
	}

	}
/**
 *

 * @return 
 */
	function SellingManagerTemplateDetailsArrayType()
	{
		$this->EbatNs_ComplexType('SellingManagerTemplateDetailsArrayType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SellingManagerTemplateDetails' =>
				array(
					'required' => false,
					'type' => 'SellingManagerTemplateDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
