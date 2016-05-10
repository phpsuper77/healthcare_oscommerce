<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class DateType extends EbatNs_ComplexType
{
	// start props
	// @var int $Year
	var $Year;
	// @var int $Month
	var $Month;
	// @var int $Day
	var $Day;
	// end props

/**
 *

 * @return int
 */
	function getYear()
	{
		return $this->Year;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setYear($value)
	{
		$this->Year = $value;
	}
/**
 *

 * @return int
 */
	function getMonth()
	{
		return $this->Month;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMonth($value)
	{
		$this->Month = $value;
	}
/**
 *

 * @return int
 */
	function getDay()
	{
		return $this->Day;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDay($value)
	{
		$this->Day = $value;
	}
/**
 *

 * @return 
 */
	function DateType()
	{
		$this->EbatNs_ComplexType('DateType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Year' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Month' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Day' =>
				array(
					'required' => false,
					'type' => 'int',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
