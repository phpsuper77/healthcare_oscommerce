<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'RecommendationsType.php';
require_once 'AbstractResponseType.php';

class GetCategorySpecificsResponseType extends AbstractResponseType
{
	// start props
	// @var RecommendationsType $Recommendations
	var $Recommendations;
	// @var string $TaskReferenceID
	var $TaskReferenceID;
	// @var string $FileReferenceID
	var $FileReferenceID;
	// end props

/**
 *

 * @return RecommendationsType
 * @param  $index 
 */
	function getRecommendations($index = null)
	{
		if ($index) {
		return $this->Recommendations[$index];
	} else {
		return $this->Recommendations;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setRecommendations($value, $index = null)
	{
		if ($index) {
	$this->Recommendations[$index] = $value;
	} else {
	$this->Recommendations = $value;
	}

	}
/**
 *

 * @return string
 */
	function getTaskReferenceID()
	{
		return $this->TaskReferenceID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTaskReferenceID($value)
	{
		$this->TaskReferenceID = $value;
	}
/**
 *

 * @return string
 */
	function getFileReferenceID()
	{
		return $this->FileReferenceID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFileReferenceID($value)
	{
		$this->FileReferenceID = $value;
	}
/**
 *

 * @return 
 */
	function GetCategorySpecificsResponseType()
	{
		$this->AbstractResponseType('GetCategorySpecificsResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Recommendations' =>
				array(
					'required' => false,
					'type' => 'RecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				),
				'TaskReferenceID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FileReferenceID' =>
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
