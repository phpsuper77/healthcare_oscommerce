<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class SellingManagerPaisaPayPropertyTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $PaisaPayAwaitingShipment
	var $PaisaPayAwaitingShipment = 'PaisaPayAwaitingShipment';
	// @var string $PaisaPayTimeExtensionRequestDeclined
	var $PaisaPayTimeExtensionRequestDeclined = 'PaisaPayTimeExtensionRequestDeclined';
	// @var string $PaisaPayPendingReceived
	var $PaisaPayPendingReceived = 'PaisaPayPendingReceived';
	// @var string $PaisaPayRefundInitiated
	var $PaisaPayRefundInitiated = 'PaisaPayRefundInitiated';
	// @var string $PaisaPayTimeExtensionRequested
	var $PaisaPayTimeExtensionRequested = 'PaisaPayTimeExtensionRequested';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function SellingManagerPaisaPayPropertyTypeCodeType()
	{
		$this->EbatNs_FacetType('SellingManagerPaisaPayPropertyTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_SellingManagerPaisaPayPropertyTypeCodeType = new SellingManagerPaisaPayPropertyTypeCodeType();

?>
