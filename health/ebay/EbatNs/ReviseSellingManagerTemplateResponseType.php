<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'FeesType.php';
require_once 'SellingManagerProductDetailsType.php';
require_once 'AbstractResponseType.php';

class ReviseSellingManagerTemplateResponseType extends AbstractResponseType
{
	// start props
	// @var long $SaleTemplateID
	var $SaleTemplateID;
	// @var FeesType $Fees
	var $Fees;
	// @var string $CategoryID
	var $CategoryID;
	// @var string $Category2ID
	var $Category2ID;
	// @var boolean $VerifyOnly
	var $VerifyOnly;
	// @var string $SaleTemplateName
	var $SaleTemplateName;
	// @var SellingManagerProductDetailsType $SellingManagerProductDetails
	var $SellingManagerProductDetails;
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

 * @return FeesType
 */
	function getFees()
	{
		return $this->Fees;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFees($value)
	{
		$this->Fees = $value;
	}
/**
 *

 * @return string
 */
	function getCategoryID()
	{
		return $this->CategoryID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCategoryID($value)
	{
		$this->CategoryID = $value;
	}
/**
 *

 * @return string
 */
	function getCategory2ID()
	{
		return $this->Category2ID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCategory2ID($value)
	{
		$this->Category2ID = $value;
	}
/**
 *

 * @return boolean
 */
	function getVerifyOnly()
	{
		return $this->VerifyOnly;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVerifyOnly($value)
	{
		$this->VerifyOnly = $value;
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

 * @return SellingManagerProductDetailsType
 */
	function getSellingManagerProductDetails()
	{
		return $this->SellingManagerProductDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellingManagerProductDetails($value)
	{
		$this->SellingManagerProductDetails = $value;
	}
/**
 *

 * @return 
 */
	function ReviseSellingManagerTemplateResponseType()
	{
		$this->AbstractResponseType('ReviseSellingManagerTemplateResponseType', 'urn:ebay:apis:eBLBaseComponents');
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
				'Fees' =>
				array(
					'required' => false,
					'type' => 'FeesType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CategoryID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Category2ID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VerifyOnly' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
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
				'SellingManagerProductDetails' =>
				array(
					'required' => false,
					'type' => 'SellingManagerProductDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
