<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractRequestType.php';

class GetProductFinderXSLRequestType extends AbstractRequestType
{
	// start props
	// @var string $FileName
	var $FileName;
	// @var string $FileVersion
	var $FileVersion;
	// end props

/**
 *

 * @return string
 */
	function getFileName()
	{
		return $this->FileName;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFileName($value)
	{
		$this->FileName = $value;
	}
/**
 *

 * @return string
 */
	function getFileVersion()
	{
		return $this->FileVersion;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFileVersion($value)
	{
		$this->FileVersion = $value;
	}
/**
 *

 * @return 
 */
	function GetProductFinderXSLRequestType()
	{
		$this->AbstractRequestType('GetProductFinderXSLRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'FileName' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'FileVersion' =>
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
