<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'MyMessagesAlertIDType.php';
require_once 'MyMessagesFolderType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'MyMessagesResponseDetailsType.php';
require_once 'MyMessagesAlertResolutionStatusCode.php';
require_once 'MyMessagesForwardDetailsType.php';
require_once 'ItemIDType.php';

class MyMessagesAlertType extends EbatNs_ComplexType
{
	// start props
	// @var string $Sender
	var $Sender;
	// @var string $RecipientUserID
	var $RecipientUserID;
	// @var string $Subject
	var $Subject;
	// @var string $Priority
	var $Priority;
	// @var MyMessagesAlertIDType $AlertID
	var $AlertID;
	// @var string $ExternalAlertID
	var $ExternalAlertID;
	// @var string $ContentType
	var $ContentType;
	// @var string $Text
	var $Text;
	// @var MyMessagesAlertResolutionStatusCode $ResolutionStatus
	var $ResolutionStatus;
	// @var boolean $Read
	var $Read;
	// @var dateTime $CreationDate
	var $CreationDate;
	// @var dateTime $ReceiveDate
	var $ReceiveDate;
	// @var dateTime $ExpirationDate
	var $ExpirationDate;
	// @var dateTime $ResolutionDate
	var $ResolutionDate;
	// @var dateTime $LastReadDate
	var $LastReadDate;
	// @var ItemIDType $ItemID
	var $ItemID;
	// @var boolean $IsTimedResolution
	var $IsTimedResolution;
	// @var string $ActionURL
	var $ActionURL;
	// @var MyMessagesResponseDetailsType $ResponseDetails
	var $ResponseDetails;
	// @var MyMessagesForwardDetailsType $ForwardDetails
	var $ForwardDetails;
	// @var MyMessagesFolderType $Folder
	var $Folder;
	// end props

/**
 *

 * @return string
 */
	function getSender()
	{
		return $this->Sender;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSender($value)
	{
		$this->Sender = $value;
	}
/**
 *

 * @return string
 */
	function getRecipientUserID()
	{
		return $this->RecipientUserID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRecipientUserID($value)
	{
		$this->RecipientUserID = $value;
	}
/**
 *

 * @return string
 */
	function getSubject()
	{
		return $this->Subject;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSubject($value)
	{
		$this->Subject = $value;
	}
/**
 *

 * @return string
 */
	function getPriority()
	{
		return $this->Priority;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPriority($value)
	{
		$this->Priority = $value;
	}
/**
 *

 * @return MyMessagesAlertIDType
 */
	function getAlertID()
	{
		return $this->AlertID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAlertID($value)
	{
		$this->AlertID = $value;
	}
/**
 *

 * @return string
 */
	function getExternalAlertID()
	{
		return $this->ExternalAlertID;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setExternalAlertID($value)
	{
		$this->ExternalAlertID = $value;
	}
/**
 *

 * @return string
 */
	function getContentType()
	{
		return $this->ContentType;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setContentType($value)
	{
		$this->ContentType = $value;
	}
/**
 *

 * @return string
 */
	function getText()
	{
		return $this->Text;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setText($value)
	{
		$this->Text = $value;
	}
/**
 *

 * @return MyMessagesAlertResolutionStatusCode
 */
	function getResolutionStatus()
	{
		return $this->ResolutionStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setResolutionStatus($value)
	{
		$this->ResolutionStatus = $value;
	}
/**
 *

 * @return boolean
 */
	function getRead()
	{
		return $this->Read;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setRead($value)
	{
		$this->Read = $value;
	}
/**
 *

 * @return dateTime
 */
	function getCreationDate()
	{
		return $this->CreationDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setCreationDate($value)
	{
		$this->CreationDate = $value;
	}
/**
 *

 * @return dateTime
 */
	function getReceiveDate()
	{
		return $this->ReceiveDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setReceiveDate($value)
	{
		$this->ReceiveDate = $value;
	}
/**
 *

 * @return dateTime
 */
	function getExpirationDate()
	{
		return $this->ExpirationDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setExpirationDate($value)
	{
		$this->ExpirationDate = $value;
	}
/**
 *

 * @return dateTime
 */
	function getResolutionDate()
	{
		return $this->ResolutionDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setResolutionDate($value)
	{
		$this->ResolutionDate = $value;
	}
/**
 *

 * @return dateTime
 */
	function getLastReadDate()
	{
		return $this->LastReadDate;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setLastReadDate($value)
	{
		$this->LastReadDate = $value;
	}
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

 * @return boolean
 */
	function getIsTimedResolution()
	{
		return $this->IsTimedResolution;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setIsTimedResolution($value)
	{
		$this->IsTimedResolution = $value;
	}
/**
 *

 * @return string
 */
	function getActionURL()
	{
		return $this->ActionURL;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setActionURL($value)
	{
		$this->ActionURL = $value;
	}
/**
 *

 * @return MyMessagesResponseDetailsType
 */
	function getResponseDetails()
	{
		return $this->ResponseDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setResponseDetails($value)
	{
		$this->ResponseDetails = $value;
	}
/**
 *

 * @return MyMessagesForwardDetailsType
 */
	function getForwardDetails()
	{
		return $this->ForwardDetails;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setForwardDetails($value)
	{
		$this->ForwardDetails = $value;
	}
/**
 *

 * @return MyMessagesFolderType
 */
	function getFolder()
	{
		return $this->Folder;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setFolder($value)
	{
		$this->Folder = $value;
	}
/**
 *

 * @return 
 */
	function MyMessagesAlertType()
	{
		$this->EbatNs_ComplexType('MyMessagesAlertType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'Sender' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'RecipientUserID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Subject' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Priority' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'AlertID' =>
				array(
					'required' => false,
					'type' => 'MyMessagesAlertIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ExternalAlertID' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ContentType' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Text' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ResolutionStatus' =>
				array(
					'required' => false,
					'type' => 'MyMessagesAlertResolutionStatusCode',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Read' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'CreationDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ReceiveDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ExpirationDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ResolutionDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'LastReadDate' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ItemID' =>
				array(
					'required' => false,
					'type' => 'ItemIDType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'IsTimedResolution' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ActionURL' =>
				array(
					'required' => false,
					'type' => 'string',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ResponseDetails' =>
				array(
					'required' => false,
					'type' => 'MyMessagesResponseDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ForwardDetails' =>
				array(
					'required' => false,
					'type' => 'MyMessagesForwardDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'Folder' =>
				array(
					'required' => false,
					'type' => 'MyMessagesFolderType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
