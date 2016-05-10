<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'ReasonCodeDetailType.php';
require_once 'SiteCodeType.php';

class VeROSiteDetailType extends EbatNs_ComplexType
{
	// start props
	// @var SiteCodeType $Site
	var $Site;
	// @var ReasonCodeDetailType $ReasonCodeDetail
	var $ReasonCodeDetail;
	// end props

/**
 *

 * @return SiteCodeType
 */
	function getSite()
	{
		return $this->Site;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSite($value)
	{
		$this->Site = $value;
	}
/**
 *

 * @return ReasonCodeDetailType
 * @param  $index 
 */
	function getReasonCodeDetail($index = null)
	{
		if ($index) {
		return $this->ReasonCodeDetail[$index];
	} else {
		return $this->ReasonCodeDetail;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setReasonCodeDetail($value, $index = null)
	{
		if ($index) {
	$this->ReasonCodeDetail[$index] = $value;
	} else {
	$this->ReasonCodeDetail = $value;
	}

	}
/**
 *

 * @return 
 */
	function VeROSiteDetailType()
	{
		$this->EbatNs_ComplexType('VeROSiteDetailType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Site' =>
				array(
					'required' => false,
					'type' => 'SiteCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ReasonCodeDetail' =>
				array(
					'required' => false,
					'type' => 'ReasonCodeDetailType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
