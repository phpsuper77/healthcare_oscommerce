<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class NotificationDeliveryStatusCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// @var string $Delivered
	var $Delivered = 'Delivered';
	// @var string $Failed
	var $Failed = 'Failed';
	// @var string $Rejected
	var $Rejected = 'Rejected';
	// @var string $MarkedDown
	var $MarkedDown = 'MarkedDown';
	// end props

/**
 *

 * @return 
 */
	function NotificationDeliveryStatusCodeType()
	{
		$this->EbatNs_FacetType('NotificationDeliveryStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_NotificationDeliveryStatusCodeType = new NotificationDeliveryStatusCodeType();

?>
