<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class DisputeStateCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Locked
	var $Locked = 'Locked';
	// @var string $Closed
	var $Closed = 'Closed';
	// @var string $BuyerFirstResponsePayOption
	var $BuyerFirstResponsePayOption = 'BuyerFirstResponsePayOption';
	// @var string $BuyerFirstResponseNoPayOption
	var $BuyerFirstResponseNoPayOption = 'BuyerFirstResponseNoPayOption';
	// @var string $BuyerFirstResponsePayOptionLateResponse
	var $BuyerFirstResponsePayOptionLateResponse = 'BuyerFirstResponsePayOptionLateResponse';
	// @var string $BuyerFirstResponseNoPayOptionLateResponse
	var $BuyerFirstResponseNoPayOptionLateResponse = 'BuyerFirstResponseNoPayOptionLateResponse';
	// @var string $MutualCommunicationPayOption
	var $MutualCommunicationPayOption = 'MutualCommunicationPayOption';
	// @var string $MutualCommunicationNoPayOption
	var $MutualCommunicationNoPayOption = 'MutualCommunicationNoPayOption';
	// @var string $PendingResolve
	var $PendingResolve = 'PendingResolve';
	// @var string $MutualWithdrawalAgreement
	var $MutualWithdrawalAgreement = 'MutualWithdrawalAgreement';
	// @var string $MutualWithdrawalAgreementLate
	var $MutualWithdrawalAgreementLate = 'MutualWithdrawalAgreementLate';
	// @var string $NotReceivedNoSellerResponse
	var $NotReceivedNoSellerResponse = 'NotReceivedNoSellerResponse';
	// @var string $NotAsDescribedNoSellerResponse
	var $NotAsDescribedNoSellerResponse = 'NotAsDescribedNoSellerResponse';
	// @var string $NotReceivedMutualCommunication
	var $NotReceivedMutualCommunication = 'NotReceivedMutualCommunication';
	// @var string $NotAsDescribedMutualCommunication
	var $NotAsDescribedMutualCommunication = 'NotAsDescribedMutualCommunication';
	// @var string $MutualAgreementOrBuyerReturningItem
	var $MutualAgreementOrBuyerReturningItem = 'MutualAgreementOrBuyerReturningItem';
	// @var string $ClaimOpened
	var $ClaimOpened = 'ClaimOpened';
	// @var string $NoDocumentation
	var $NoDocumentation = 'NoDocumentation';
	// @var string $ClaimClosed
	var $ClaimClosed = 'ClaimClosed';
	// @var string $ClaimDenied
	var $ClaimDenied = 'ClaimDenied';
	// @var string $ClaimPending
	var $ClaimPending = 'ClaimPending';
	// @var string $ClaimPaymentPending
	var $ClaimPaymentPending = 'ClaimPaymentPending';
	// @var string $ClaimPaid
	var $ClaimPaid = 'ClaimPaid';
	// @var string $ClaimResolved
	var $ClaimResolved = 'ClaimResolved';
	// @var string $ClaimSubmitted
	var $ClaimSubmitted = 'ClaimSubmitted';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function DisputeStateCodeType()
	{
		$this->EbatNs_FacetType('DisputeStateCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_DisputeStateCodeType = new DisputeStateCodeType();

?>
