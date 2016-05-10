<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class TimeZoneDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var string $TimeZoneID
	var $TimeZoneID;
	// @var string $StandardLabel
	var $StandardLabel;
	// @var string $StandardOffset
	var $StandardOffset;
	// @var string $DaylightSavingsLabel
	var $DaylightSavingsLabel;
	// @var string $DaylightSavingsOffset
	var $DaylightSavingsOffset;
	// @var boolean $DaylightSavingsInEffect
	var $DaylightSavingsInEffect;
	// end props

/**
 *

 * @return string
 */
	function getTimeZoneID()
	{
		return $this->TimeZoneID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTimeZoneID($value)
	{
		$this->TimeZoneID = $value;
	}
/**
 *

 * @return string
 */
	function getStandardLabel()
	{
		return $this->StandardLabel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStandardLabel($value)
	{
		$this->StandardLabel = $value;
	}
/**
 *

 * @return string
 */
	function getStandardOffset()
	{
		return $this->StandardOffset;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStandardOffset($value)
	{
		$this->StandardOffset = $value;
	}
/**
 *

 * @return string
 */
	function getDaylightSavingsLabel()
	{
		return $this->DaylightSavingsLabel;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDaylightSavingsLabel($value)
	{
		$this->DaylightSavingsLabel = $value;
	}
/**
 *

 * @return string
 */
	function getDaylightSavingsOffset()
	{
		return $this->DaylightSavingsOffset;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDaylightSavingsOffset($value)
	{
		$this->DaylightSavingsOffset = $value;
	}
/**
 *

 * @return boolean
 */
	function getDaylightSavingsInEffect()
	{
		return $this->DaylightSavingsInEffect;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDaylightSavingsInEffect($value)
	{
		$this->DaylightSavingsInEffect = $value;
	}
/**
 *

 * @return 
 */
	function TimeZoneDetailsType()
	{
		$this->EbatNs_ComplexType('TimeZoneDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'TimeZoneID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StandardLabel' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'StandardOffset' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DaylightSavingsLabel' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DaylightSavingsOffset' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DaylightSavingsInEffect' =>
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