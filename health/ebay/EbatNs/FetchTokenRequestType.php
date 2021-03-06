<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'AbstractRequestType.php';

class FetchTokenRequestType extends AbstractRequestType
{
	// start props
	// @var string $SecretID
	var $SecretID;
	// @var string $SessionID
	var $SessionID;
	// @var boolean $IncludeRESTToken
	var $IncludeRESTToken;
	// end props

/**
 *

 * @return string
 */
	function getSecretID()
	{
		return $this->SecretID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSecretID($value)
	{
		$this->SecretID = $value;
	}
/**
 *

 * @return string
 */
	function getSessionID()
	{
		return $this->SessionID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSessionID($value)
	{
		$this->SessionID = $value;
	}
/**
 *

 * @return boolean
 */
	function getIncludeRESTToken()
	{
		return $this->IncludeRESTToken;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIncludeRESTToken($value)
	{
		$this->IncludeRESTToken = $value;
	}
/**
 *

 * @return 
 */
	function FetchTokenRequestType()
	{
		$this->AbstractRequestType('FetchTokenRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SecretID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SessionID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IncludeRESTToken' =>
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
