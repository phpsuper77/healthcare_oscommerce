<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'SiteCodeType.php';

class SubscriptionType extends EbatNs_ComplexType
{
	// start props
	// @var string $EIASToken
	var $EIASToken;
	// @var SiteCodeType $SiteID
	var $SiteID;
	// @var boolean $Active
	var $Active;
	// end props

/**
 *

 * @return string
 */
	function getEIASToken()
	{
		return $this->EIASToken;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEIASToken($value)
	{
		$this->EIASToken = $value;
	}
/**
 *

 * @return SiteCodeType
 */
	function getSiteID()
	{
		return $this->SiteID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSiteID($value)
	{
		$this->SiteID = $value;
	}
/**
 *

 * @return boolean
 */
	function getActive()
	{
		return $this->Active;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setActive($value)
	{
		$this->Active = $value;
	}
/**
 *

 * @return 
 */
	function SubscriptionType()
	{
		$this->EbatNs_ComplexType('SubscriptionType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'EIASToken' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SiteID' =>
				array(
					'required' => false,
					'type' => 'SiteCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Active' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
