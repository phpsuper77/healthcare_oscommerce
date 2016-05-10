<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ProductListingDetailsType.php';
require_once 'SIFFTASRecommendationsType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'ProductRecommendationsType.php';
require_once 'AttributeRecommendationsType.php';
require_once 'RecommendationsType.php';
require_once 'ListingAnalyzerRecommendationsType.php';
require_once 'PricingRecommendationsType.php';

class GetRecommendationsResponseContainerType extends EbatNs_ComplexType
{
	// start props
	// @var ListingAnalyzerRecommendationsType $ListingAnalyzerRecommendations
	var $ListingAnalyzerRecommendations;
	// @var SIFFTASRecommendationsType $SIFFTASRecommendations
	var $SIFFTASRecommendations;
	// @var PricingRecommendationsType $PricingRecommendations
	var $PricingRecommendations;
	// @var AttributeRecommendationsType $AttributeRecommendations
	var $AttributeRecommendations;
	// @var ProductRecommendationsType $ProductRecommendations
	var $ProductRecommendations;
	// @var string $CorrelationID
	var $CorrelationID;
	// @var RecommendationsType $Recommendations
	var $Recommendations;
	// @var ProductListingDetailsType $ProductListingDetails
	var $ProductListingDetails;
	// @var string $Title
	var $Title;
	// end props

/**
 *

 * @return ListingAnalyzerRecommendationsType
 */
	function getListingAnalyzerRecommendations()
	{
		return $this->ListingAnalyzerRecommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setListingAnalyzerRecommendations($value)
	{
		$this->ListingAnalyzerRecommendations = $value;
	}
/**
 *

 * @return SIFFTASRecommendationsType
 */
	function getSIFFTASRecommendations()
	{
		return $this->SIFFTASRecommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSIFFTASRecommendations($value)
	{
		$this->SIFFTASRecommendations = $value;
	}
/**
 *

 * @return PricingRecommendationsType
 */
	function getPricingRecommendations()
	{
		return $this->PricingRecommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPricingRecommendations($value)
	{
		$this->PricingRecommendations = $value;
	}
/**
 *

 * @return AttributeRecommendationsType
 */
	function getAttributeRecommendations()
	{
		return $this->AttributeRecommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAttributeRecommendations($value)
	{
		$this->AttributeRecommendations = $value;
	}
/**
 *

 * @return ProductRecommendationsType
 */
	function getProductRecommendations()
	{
		return $this->ProductRecommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProductRecommendations($value)
	{
		$this->ProductRecommendations = $value;
	}
/**
 *

 * @return string
 */
	function getCorrelationID()
	{
		return $this->CorrelationID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCorrelationID($value)
	{
		$this->CorrelationID = $value;
	}
/**
 *

 * @return RecommendationsType
 */
	function getRecommendations()
	{
		return $this->Recommendations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRecommendations($value)
	{
		$this->Recommendations = $value;
	}
/**
 *

 * @return ProductListingDetailsType
 */
	function getProductListingDetails()
	{
		return $this->ProductListingDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setProductListingDetails($value)
	{
		$this->ProductListingDetails = $value;
	}
/**
 *

 * @return string
 */
	function getTitle()
	{
		return $this->Title;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTitle($value)
	{
		$this->Title = $value;
	}
/**
 *

 * @return 
 */
	function GetRecommendationsResponseContainerType()
	{
		$this->EbatNs_ComplexType('GetRecommendationsResponseContainerType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ListingAnalyzerRecommendations' =>
				array(
					'required' => false,
					'type' => 'ListingAnalyzerRecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SIFFTASRecommendations' =>
				array(
					'required' => false,
					'type' => 'SIFFTASRecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PricingRecommendations' =>
				array(
					'required' => false,
					'type' => 'PricingRecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AttributeRecommendations' =>
				array(
					'required' => false,
					'type' => 'AttributeRecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ProductRecommendations' =>
				array(
					'required' => false,
					'type' => 'ProductRecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CorrelationID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Recommendations' =>
				array(
					'required' => false,
					'type' => 'RecommendationsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ProductListingDetails' =>
				array(
					'required' => false,
					'type' => 'ProductListingDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Title' =>
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