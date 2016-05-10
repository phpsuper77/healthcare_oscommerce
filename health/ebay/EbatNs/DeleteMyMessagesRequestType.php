<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'MyMessagesAlertIDArrayType.php';
require_once 'MyMessagesMessageIDArrayType.php';
require_once 'AbstractRequestType.php';

class DeleteMyMessagesRequestType extends AbstractRequestType
{
	// start props
	// @var MyMessagesAlertIDArrayType $AlertIDs
	var $AlertIDs;
	// @var MyMessagesMessageIDArrayType $MessageIDs
	var $MessageIDs;
	// end props

/**
 *

 * @return MyMessagesAlertIDArrayType
 */
	function getAlertIDs()
	{
		return $this->AlertIDs;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAlertIDs($value)
	{
		$this->AlertIDs = $value;
	}
/**
 *

 * @return MyMessagesMessageIDArrayType
 */
	function getMessageIDs()
	{
		return $this->MessageIDs;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMessageIDs($value)
	{
		$this->MessageIDs = $value;
	}
/**
 *

 * @return 
 */
	function DeleteMyMessagesRequestType()
	{
		$this->AbstractRequestType('DeleteMyMessagesRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'AlertIDs' =>
				array(
					'required' => false,
					'type' => 'MyMessagesAlertIDArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MessageIDs' =>
				array(
					'required' => false,
					'type' => 'MyMessagesMessageIDArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
