<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'PictureManagerPictureDisplayType.php';

class PictureManagerPictureType extends EbatNs_ComplexType
{
	// start props
	// @var anyURI $PictureURL
	var $PictureURL;
	// @var string $Name
	var $Name;
	// @var dateTime $Date
	var $Date;
	// @var PictureManagerPictureDisplayType $DisplayFormat
	var $DisplayFormat;
	// end props

/**
 *

 * @return anyURI
 */
	function getPictureURL()
	{
		return $this->PictureURL;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPictureURL($value)
	{
		$this->PictureURL = $value;
	}
/**
 *

 * @return string
 */
	function getName()
	{
		return $this->Name;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setName($value)
	{
		$this->Name = $value;
	}
/**
 *

 * @return dateTime
 */
	function getDate()
	{
		return $this->Date;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setDate($value)
	{
		$this->Date = $value;
	}
/**
 *

 * @return PictureManagerPictureDisplayType
 * @param  $index 
 */
	function getDisplayFormat($index = null)
	{
		if ($index) {
		return $this->DisplayFormat[$index];
	} else {
		return $this->DisplayFormat;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setDisplayFormat($value, $index = null)
	{
		if ($index) {
	$this->DisplayFormat[$index] = $value;
	} else {
	$this->DisplayFormat = $value;
	}

	}
/**
 *

 * @return 
 */
	function PictureManagerPictureType()
	{
		$this->EbatNs_ComplexType('PictureManagerPictureType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'PictureURL' =>
				array(
					'required' => false,
					'type' => 'anyURI',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Name' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Date' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'DisplayFormat' =>
				array(
					'required' => false,
					'type' => 'PictureManagerPictureDisplayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
