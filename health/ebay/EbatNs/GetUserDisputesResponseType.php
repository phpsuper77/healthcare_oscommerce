<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'PaginationResultType.php';
require_once 'AbstractResponseType.php';
require_once 'DisputeFilterCountType.php';
require_once 'DisputeIDType.php';
require_once 'DisputeArrayType.php';

class GetUserDisputesResponseType extends AbstractResponseType
{
	// start props
	// @var DisputeIDType $StartingDisputeID
	var $StartingDisputeID;
	// @var DisputeIDType $EndingDisputeID
	var $EndingDisputeID;
	// @var DisputeArrayType $DisputeArray
	var $DisputeArray;
	// @var int $ItemsPerPage
	var $ItemsPerPage;
	// @var int $PageNumber
	var $PageNumber;
	// @var DisputeFilterCountType $DisputeFilterCount
	var $DisputeFilterCount;
	// @var PaginationResultType $PaginationResult
	var $PaginationResult;
	// end props

/**
 *

 * @return DisputeIDType
 */
	function getStartingDisputeID()
	{
		return $this->StartingDisputeID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStartingDisputeID($value)
	{
		$this->StartingDisputeID = $value;
	}
/**
 *

 * @return DisputeIDType
 */
	function getEndingDisputeID()
	{
		return $this->EndingDisputeID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEndingDisputeID($value)
	{
		$this->EndingDisputeID = $value;
	}
/**
 *

 * @return DisputeArrayType
 */
	function getDisputeArray()
	{
		return $this->DisputeArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDisputeArray($value)
	{
		$this->DisputeArray = $value;
	}
/**
 *

 * @return int
 */
	function getItemsPerPage()
	{
		return $this->ItemsPerPage;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItemsPerPage($value)
	{
		$this->ItemsPerPage = $value;
	}
/**
 *

 * @return int
 */
	function getPageNumber()
	{
		return $this->PageNumber;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPageNumber($value)
	{
		$this->PageNumber = $value;
	}
/**
 *

 * @return DisputeFilterCountType
 * @param  $index 
 */
	function getDisputeFilterCount($index = null)
	{
		if ($index) {
		return $this->DisputeFilterCount[$index];
	} else {
		return $this->DisputeFilterCount;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setDisputeFilterCount($value, $index = null)
	{
		if ($index) {
	$this->DisputeFilterCount[$index] = $value;
	} else {
	$this->DisputeFilterCount = $value;
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
	function GetUserDisputesResponseType()
	{
		$this->AbstractResponseType('GetUserDisputesResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'StartingDisputeID' =>
				array(
					'required' => false,
					'type' => 'DisputeIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EndingDisputeID' =>
				array(
					'required' => false,
					'type' => 'DisputeIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DisputeArray' =>
				array(
					'required' => false,
					'type' => 'DisputeArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ItemsPerPage' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PageNumber' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DisputeFilterCount' =>
				array(
					'required' => false,
					'type' => 'DisputeFilterCountType',
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
