<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'MarkUpMarkDownHistoryType.php';
require_once 'NotificationDetailsArrayType.php';
require_once 'NotificationStatisticsType.php';
require_once 'AbstractResponseType.php';

class GetNotificationsUsageResponseType extends AbstractResponseType
{
	// start props
	// @var dateTime $StartTime
	var $StartTime;
	// @var dateTime $EndTime
	var $EndTime;
	// @var NotificationDetailsArrayType $NotificationDetailsArray
	var $NotificationDetailsArray;
	// @var MarkUpMarkDownHistoryType $MarkUpMarkDownHistory
	var $MarkUpMarkDownHistory;
	// @var NotificationStatisticsType $NotificationStatistics
	var $NotificationStatistics;
	// end props

/**
 *

 * @return dateTime
 */
	function getStartTime()
	{
		return $this->StartTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setStartTime($value)
	{
		$this->StartTime = $value;
	}
/**
 *

 * @return dateTime
 */
	function getEndTime()
	{
		return $this->EndTime;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setEndTime($value)
	{
		$this->EndTime = $value;
	}
/**
 *

 * @return NotificationDetailsArrayType
 */
	function getNotificationDetailsArray()
	{
		return $this->NotificationDetailsArray;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNotificationDetailsArray($value)
	{
		$this->NotificationDetailsArray = $value;
	}
/**
 *

 * @return MarkUpMarkDownHistoryType
 */
	function getMarkUpMarkDownHistory()
	{
		return $this->MarkUpMarkDownHistory;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMarkUpMarkDownHistory($value)
	{
		$this->MarkUpMarkDownHistory = $value;
	}
/**
 *

 * @return NotificationStatisticsType
 */
	function getNotificationStatistics()
	{
		return $this->NotificationStatistics;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setNotificationStatistics($value)
	{
		$this->NotificationStatistics = $value;
	}
/**
 *

 * @return 
 */
	function GetNotificationsUsageResponseType()
	{
		$this->AbstractResponseType('GetNotificationsUsageResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'StartTime' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'EndTime' =>
				array(
					'required' => false,
					'type' => 'dateTime',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NotificationDetailsArray' =>
				array(
					'required' => false,
					'type' => 'NotificationDetailsArrayType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MarkUpMarkDownHistory' =>
				array(
					'required' => false,
					'type' => 'MarkUpMarkDownHistoryType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'NotificationStatistics' =>
				array(
					'required' => false,
					'type' => 'NotificationStatisticsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
