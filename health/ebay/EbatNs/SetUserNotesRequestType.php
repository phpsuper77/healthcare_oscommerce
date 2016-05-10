<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SetUserNotesActionCodeType.php';
require_once 'AbstractRequestType.php';
require_once 'ItemIDType.php';

class SetUserNotesRequestType extends AbstractRequestType
{
	// start props
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var SetUserNotesActionCodeType $Action
	var $Action;
	// @var string $NoteText
	var $NoteText;
	// @var string $TransactionID
	var $TransactionID;
	// end props

/**
 *

 * @return ItemIDType
 */
	function getItemID()
	{
		return $this->ItemID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setItemID($value)
	{
		$this->ItemID = $value;
	}
/**
 *

 * @return SetUserNotesActionCodeType
 */
	function getAction()
	{
		return $this->Action;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAction($value)
	{
		$this->Action = $value;
	}
/**
 *

 * @return string
 */
	function getNoteText()
	{
		return $this->NoteText;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNoteText($value)
	{
		$this->NoteText = $value;
	}
/**
 *

 * @return string
 */
	function getTransactionID()
	{
		return $this->TransactionID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setTransactionID($value)
	{
		$this->TransactionID = $value;
	}
/**
 *

 * @return 
 */
	function SetUserNotesRequestType()
	{
		$this->AbstractRequestType('SetUserNotesRequestType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ItemID' =>
				array(
					'required' => false,
					'type' => 'ItemIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Action' =>
				array(
					'required' => false,
					'type' => 'SetUserNotesActionCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NoteText' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'TransactionID' =>
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
