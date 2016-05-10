<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'BestOfferStatusCodeType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'AmountType.php';
require_once 'BestOfferTypeCodeType.php';

class BestOfferDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var int $BestOfferCount
	var $BestOfferCount;
	// @var boolean $BestOfferEnabled
	var $BestOfferEnabled;
	// @var AmountType $BestOffer
	var $BestOffer;
	// @var BestOfferStatusCodeType $BestOfferStatus
	var $BestOfferStatus;
	// @var BestOfferTypeCodeType $BestOfferType
	var $BestOfferType;
	// end props

/**
 *

 * @return int
 */
	function getBestOfferCount()
	{
		return $this->BestOfferCount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBestOfferCount($value)
	{
		$this->BestOfferCount = $value;
	}
/**
 *

 * @return boolean
 */
	function getBestOfferEnabled()
	{
		return $this->BestOfferEnabled;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBestOfferEnabled($value)
	{
		$this->BestOfferEnabled = $value;
	}
/**
 *

 * @return AmountType
 */
	function getBestOffer()
	{
		return $this->BestOffer;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBestOffer($value)
	{
		$this->BestOffer = $value;
	}
/**
 *

 * @return BestOfferStatusCodeType
 */
	function getBestOfferStatus()
	{
		return $this->BestOfferStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBestOfferStatus($value)
	{
		$this->BestOfferStatus = $value;
	}
/**
 *

 * @return BestOfferTypeCodeType
 */
	function getBestOfferType()
	{
		return $this->BestOfferType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBestOfferType($value)
	{
		$this->BestOfferType = $value;
	}
/**
 *

 * @return 
 */
	function BestOfferDetailsType()
	{
		$this->EbatNs_ComplexType('BestOfferDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'BestOfferCount' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BestOfferEnabled' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BestOffer' =>
				array(
					'required' => false,
					'type' => 'AmountType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BestOfferStatus' =>
				array(
					'required' => false,
					'type' => 'BestOfferStatusCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BestOfferType' =>
				array(
					'required' => false,
					'type' => 'BestOfferTypeCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
