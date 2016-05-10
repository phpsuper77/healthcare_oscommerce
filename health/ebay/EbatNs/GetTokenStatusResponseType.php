<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'TokenStatusType.php';
require_once 'AbstractResponseType.php';

class GetTokenStatusResponseType extends AbstractResponseType
{
	// start props
	// @var TokenStatusType $TokenStatus
	var $TokenStatus;
	// end props

/**
 *

 * @return TokenStatusType
 */
	function getTokenStatus()
	{
		return $this->TokenStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTokenStatus($value)
	{
		$this->TokenStatus = $value;
	}
/**
 *

 * @return 
 */
	function GetTokenStatusResponseType()
	{
		$this->AbstractResponseType('GetTokenStatusResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'TokenStatus' =>
				array(
					'required' => false,
					'type' => 'TokenStatusType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>