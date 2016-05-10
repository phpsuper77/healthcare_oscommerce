<?php
// $Id: ClientProxy.tpl.php,v 1.1 2007/05/31 11:38:00 michael Exp $
/* $Log: ClientProxy.tpl.php,v $
/* Revision 1.1  2007/05/31 11:38:00  michael
/* - initial checkin
/* - version < 513
/*
 * 
 * 4     29.05.06 9:59 Charnisch
 * 
 * 3     4.03.06 15:12 Charnisch
 * 
 * 2     3.02.06 11:29 Mcoslar
 * 
 * 1     30.01.06 12:11 Charnisch
 */
// 
// auto-generated 29.05.2009 15:17 
// Ebay-Schema Version 617
//
/**
 * Load files we depend on.
 */

require_once 'EbatNs_Client.php';
require_once 'EbatNs_Session.php';

/**
 * The WSDL version the SDK is built against.
 */
define('EBAY_WSDL_VERSION', 617);

/**
 * This class is the basic interface to the eBay-Webserice for the user.
 * We generated the "proxy" externally as the SOAP-wsdl proxy generator does
 * not really did what we needed.
 */
class EbatNs_ServiceProxy extends EbatNs_Client
{
    function EbatNs_ServiceProxy(& $session, $converter = 'EbatNs_DataConverterIso')
    {
        if (is_a($session, 'EbatNs_Session'))
        {
			// Initialize the SOAP Client.
			$this->EbatNs_Client($session, $converter);
		}
		else
		{
			if (is_string($session))
			{
				$session = & new EbatNs_Session($session);
				$this->EbatNs_Client($session, $converter);
			}
		}
    }

	function isGood($response, $ignoreWarnings = true)
	{
		if ($ignoreWarnings)
			return ($response->Ack == 'Success' || $response->Ack == 'Warning');		
		else
			return ($response->Ack == 'Success');
	}

	function isFailure($response)
	{
		return ($response->Ack == 'Failure');
	}
	
	function getErrorsToString($response, $asHtml = false, $addSlashes = true)
	{
		$errmsg = '';
		if (count($response->Errors))
			foreach ($response->Errors as $errorEle)
				$errmsg .= '#' . $errorEle->getErrorCode() . ' : ' . ($asHtml ? htmlentities($errorEle->getLongMessage()) :  $errorEle->getLongMessage()) . ($asHtml?"<br>" : "\r\n");
	
		if ($addSlashes)
			return addslashes($errmsg);
		else			
			return $errmsg;
	}
	/**
 *

 * @return AddDisputeResponseType
 * @param AddDisputeRequestType $request 
 */
	function &AddDispute($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddDispute', $request));

	}
/**
 *

 * @return AddDisputeResponseResponseType
 * @param AddDisputeResponseRequestType $request 
 */
	function &AddDisputeResponse($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddDisputeResponse', $request));

	}
/**
 *

 * @return AddFixedPriceItemResponseType
 * @param AddFixedPriceItemRequestType $request 
 */
	function &AddFixedPriceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddFixedPriceItem', $request));

	}
/**
 *

 * @return AddItemResponseType
 * @param AddItemRequestType $request 
 */
	function &AddItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddItem', $request));

	}
/**
 *

 * @return AddItemFromSellingManagerTemplateResponseType
 * @param AddItemFromSellingManagerTemplateRequestType $request 
 */
	function &AddItemFromSellingManagerTemplate($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddItemFromSellingManagerTemplate', $request));

	}
/**
 *

 * @return AddItemsResponseType
 * @param AddItemsRequestType $request 
 */
	function &AddItems($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddItems', $request));

	}
/**
 *

 * @return AddMemberMessageAAQToPartnerResponseType
 * @param AddMemberMessageAAQToPartnerRequestType $request 
 */
	function &AddMemberMessageAAQToPartner($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddMemberMessageAAQToPartner', $request));

	}
/**
 *

 * @return AddMemberMessageRTQResponseType
 * @param AddMemberMessageRTQRequestType $request 
 */
	function &AddMemberMessageRTQ($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddMemberMessageRTQ', $request));

	}
/**
 *

 * @return AddMemberMessagesAAQToBidderResponseType
 * @param AddMemberMessagesAAQToBidderRequestType $request 
 */
	function &AddMemberMessagesAAQToBidder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddMemberMessagesAAQToBidder', $request));

	}
/**
 *

 * @return AddOrderResponseType
 * @param AddOrderRequestType $request 
 */
	function &AddOrder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddOrder', $request));

	}
/**
 *

 * @return AddSecondChanceItemResponseType
 * @param AddSecondChanceItemRequestType $request 
 */
	function &AddSecondChanceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddSecondChanceItem', $request));

	}
/**
 *

 * @return AddSellingManagerInventoryFolderResponseType
 * @param AddSellingManagerInventoryFolderRequestType $request 
 */
	function &AddSellingManagerInventoryFolder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddSellingManagerInventoryFolder', $request));

	}
/**
 *

 * @return AddSellingManagerProductResponseType
 * @param AddSellingManagerProductRequestType $request 
 */
	function &AddSellingManagerProduct($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddSellingManagerProduct', $request));

	}
/**
 *

 * @return AddSellingManagerTemplateResponseType
 * @param AddSellingManagerTemplateRequestType $request 
 */
	function &AddSellingManagerTemplate($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddSellingManagerTemplate', $request));

	}
/**
 *

 * @return AddToItemDescriptionResponseType
 * @param AddToItemDescriptionRequestType $request 
 */
	function &AddToItemDescription($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddToItemDescription', $request));

	}
/**
 *

 * @return AddToWatchListResponseType
 * @param AddToWatchListRequestType $request 
 */
	function &AddToWatchList($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddToWatchList', $request));

	}
/**
 *

 * @return AddTransactionConfirmationItemResponseType
 * @param AddTransactionConfirmationItemRequestType $request 
 */
	function &AddTransactionConfirmationItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('AddTransactionConfirmationItem', $request));

	}
/**
 *

 * @return CompleteSaleResponseType
 * @param CompleteSaleRequestType $request 
 */
	function &CompleteSale($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('CompleteSale', $request));

	}
/**
 *

 * @return ConfirmIdentityResponseType
 * @param ConfirmIdentityRequestType $request 
 */
	function &ConfirmIdentity($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ConfirmIdentity', $request));

	}
/**
 *

 * @return DeleteMyMessagesResponseType
 * @param DeleteMyMessagesRequestType $request 
 */
	function &DeleteMyMessages($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteMyMessages', $request));

	}
/**
 *

 * @return DeleteSellingManagerInventoryFolderResponseType
 * @param DeleteSellingManagerInventoryFolderRequestType $request 
 */
	function &DeleteSellingManagerInventoryFolder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteSellingManagerInventoryFolder', $request));

	}
/**
 *

 * @return DeleteSellingManagerItemAutomationRuleResponseType
 * @param DeleteSellingManagerItemAutomationRuleRequestType $request 
 */
	function &DeleteSellingManagerItemAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteSellingManagerItemAutomationRule', $request));

	}
/**
 *

 * @return DeleteSellingManagerProductResponseType
 * @param DeleteSellingManagerProductRequestType $request 
 */
	function &DeleteSellingManagerProduct($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteSellingManagerProduct', $request));

	}
/**
 *

 * @return DeleteSellingManagerTemplateResponseType
 * @param DeleteSellingManagerTemplateRequestType $request 
 */
	function &DeleteSellingManagerTemplate($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteSellingManagerTemplate', $request));

	}
/**
 *

 * @return DeleteSellingManagerTemplateAutomationRuleResponseType
 * @param DeleteSellingManagerTemplateAutomationRuleRequestType $request 
 */
	function &DeleteSellingManagerTemplateAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('DeleteSellingManagerTemplateAutomationRule', $request));

	}
/**
 *

 * @return EndFixedPriceItemResponseType
 * @param EndFixedPriceItemRequestType $request 
 */
	function &EndFixedPriceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('EndFixedPriceItem', $request));

	}
/**
 *

 * @return EndItemResponseType
 * @param EndItemRequestType $request 
 */
	function &EndItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('EndItem', $request));

	}
/**
 *

 * @return EndItemsResponseType
 * @param EndItemsRequestType $request 
 */
	function &EndItems($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('EndItems', $request));

	}
/**
 *

 * @return FetchTokenResponseType
 * @param FetchTokenRequestType $request 
 */
	function &FetchToken($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('FetchToken', $request));

	}
/**
 *

 * @return GetAccountResponseType
 * @param GetAccountRequestType $request 
 */
	function &GetAccount($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetAccount', $request));

	}
/**
 *

 * @return GetAdFormatLeadsResponseType
 * @param GetAdFormatLeadsRequestType $request 
 */
	function &GetAdFormatLeads($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetAdFormatLeads', $request));

	}
/**
 *

 * @return GetAllBiddersResponseType
 * @param GetAllBiddersRequestType $request 
 */
	function &GetAllBidders($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetAllBidders', $request));

	}
/**
 *

 * @return GetApiAccessRulesResponseType
 * @param GetApiAccessRulesRequestType $request 
 */
	function &GetApiAccessRules($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetApiAccessRules', $request));

	}
/**
 *

 * @return GetAttributesCSResponseType
 * @param GetAttributesCSRequestType $request 
 */
	function &GetAttributesCS($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetAttributesCS', $request));

	}
/**
 *

 * @return GetAttributesXSLResponseType
 * @param GetAttributesXSLRequestType $request 
 */
	function &GetAttributesXSL($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetAttributesXSL', $request));

	}
/**
 *

 * @return GetBestOffersResponseType
 * @param GetBestOffersRequestType $request 
 */
	function &GetBestOffers($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetBestOffers', $request));

	}
/**
 *

 * @return GetBidderListResponseType
 * @param GetBidderListRequestType $request 
 */
	function &GetBidderList($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetBidderList', $request));

	}
/**
 *

 * @return GetCategoriesResponseType
 * @param GetCategoriesRequestType $request 
 */
	function &GetCategories($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategories', $request));

	}
/**
 *

 * @return GetCategory2CSResponseType
 * @param GetCategory2CSRequestType $request 
 */
	function &GetCategory2CS($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategory2CS', $request));

	}
/**
 *

 * @return GetCategoryFeaturesResponseType
 * @param GetCategoryFeaturesRequestType $request 
 */
	function &GetCategoryFeatures($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategoryFeatures', $request));

	}
/**
 *

 * @return GetCategoryListingsResponseType
 * @param GetCategoryListingsRequestType $request 
 */
	function &GetCategoryListings($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategoryListings', $request));

	}
/**
 *

 * @return GetCategoryMappingsResponseType
 * @param GetCategoryMappingsRequestType $request 
 */
	function &GetCategoryMappings($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategoryMappings', $request));

	}
/**
 *

 * @return GetCategorySpecificsResponseType
 * @param GetCategorySpecificsRequestType $request 
 */
	function &GetCategorySpecifics($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCategorySpecifics', $request));

	}
/**
 *

 * @return GetChallengeTokenResponseType
 * @param GetChallengeTokenRequestType $request 
 */
	function &GetChallengeToken($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetChallengeToken', $request));

	}
/**
 *

 * @return GetCharitiesResponseType
 * @param GetCharitiesRequestType $request 
 */
	function &GetCharities($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCharities', $request));

	}
/**
 *

 * @return GetClientAlertsAuthTokenResponseType
 * @param GetClientAlertsAuthTokenRequestType $request 
 */
	function &GetClientAlertsAuthToken($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetClientAlertsAuthToken', $request));

	}
/**
 *

 * @return GetContextualKeywordsResponseType
 * @param GetContextualKeywordsRequestType $request 
 */
	function &GetContextualKeywords($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetContextualKeywords', $request));

	}
/**
 *

 * @return GetCrossPromotionsResponseType
 * @param GetCrossPromotionsRequestType $request 
 */
	function &GetCrossPromotions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetCrossPromotions', $request));

	}
/**
 *

 * @return GetDescriptionTemplatesResponseType
 * @param GetDescriptionTemplatesRequestType $request 
 */
	function &GetDescriptionTemplates($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetDescriptionTemplates', $request));

	}
/**
 *

 * @return GetDisputeResponseType
 * @param GetDisputeRequestType $request 
 */
	function &GetDispute($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetDispute', $request));

	}
/**
 *

 * @return GetFeedbackResponseType
 * @param GetFeedbackRequestType $request 
 */
	function &GetFeedback($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetFeedback', $request));

	}
/**
 *

 * @return GetHighBiddersResponseType
 * @param GetHighBiddersRequestType $request 
 */
	function &GetHighBidders($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetHighBidders', $request));

	}
/**
 *

 * @return GetItemResponseType
 * @param GetItemRequestType $request 
 */
	function &GetItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetItem', $request));

	}
/**
 *

 * @return GetItemRecommendationsResponseType
 * @param GetItemRecommendationsRequestType $request 
 */
	function &GetItemRecommendations($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetItemRecommendations', $request));

	}
/**
 *

 * @return GetItemShippingResponseType
 * @param GetItemShippingRequestType $request 
 */
	function &GetItemShipping($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetItemShipping', $request));

	}
/**
 *

 * @return GetItemTransactionsResponseType
 * @param GetItemTransactionsRequestType $request 
 */
	function &GetItemTransactions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetItemTransactions', $request));

	}
/**
 *

 * @return GetItemsAwaitingFeedbackResponseType
 * @param GetItemsAwaitingFeedbackRequestType $request 
 */
	function &GetItemsAwaitingFeedback($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetItemsAwaitingFeedback', $request));

	}
/**
 *

 * @return GetMemberMessagesResponseType
 * @param GetMemberMessagesRequestType $request 
 */
	function &GetMemberMessages($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMemberMessages', $request));

	}
/**
 *

 * @return GetMessagePreferencesResponseType
 * @param GetMessagePreferencesRequestType $request 
 */
	function &GetMessagePreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMessagePreferences', $request));

	}
/**
 *

 * @return GetMyMessagesResponseType
 * @param GetMyMessagesRequestType $request 
 */
	function &GetMyMessages($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMyMessages', $request));

	}
/**
 *

 * @return GetMyeBayBuyingResponseType
 * @param GetMyeBayBuyingRequestType $request 
 */
	function &GetMyeBayBuying($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMyeBayBuying', $request));

	}
/**
 *

 * @return GetMyeBayRemindersResponseType
 * @param GetMyeBayRemindersRequestType $request 
 */
	function &GetMyeBayReminders($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMyeBayReminders', $request));

	}
/**
 *

 * @return GetMyeBaySellingResponseType
 * @param GetMyeBaySellingRequestType $request 
 */
	function &GetMyeBaySelling($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetMyeBaySelling', $request));

	}
/**
 *

 * @return GetNotificationPreferencesResponseType
 * @param GetNotificationPreferencesRequestType $request 
 */
	function &GetNotificationPreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetNotificationPreferences', $request));

	}
/**
 *

 * @return GetNotificationsUsageResponseType
 * @param GetNotificationsUsageRequestType $request 
 */
	function &GetNotificationsUsage($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetNotificationsUsage', $request));

	}
/**
 *

 * @return GetOrderTransactionsResponseType
 * @param GetOrderTransactionsRequestType $request 
 */
	function &GetOrderTransactions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetOrderTransactions', $request));

	}
/**
 *

 * @return GetOrdersResponseType
 * @param GetOrdersRequestType $request 
 */
	function &GetOrders($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetOrders', $request));

	}
/**
 *

 * @return GetPictureManagerDetailsResponseType
 * @param GetPictureManagerDetailsRequestType $request 
 */
	function &GetPictureManagerDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetPictureManagerDetails', $request));

	}
/**
 *

 * @return GetPictureManagerOptionsResponseType
 * @param GetPictureManagerOptionsRequestType $request 
 */
	function &GetPictureManagerOptions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetPictureManagerOptions', $request));

	}
/**
 *

 * @return GetPopularKeywordsResponseType
 * @param GetPopularKeywordsRequestType $request 
 */
	function &GetPopularKeywords($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetPopularKeywords', $request));

	}
/**
 *

 * @return GetProductFamilyMembersResponseType
 * @param GetProductFamilyMembersRequestType $request 
 */
	function &GetProductFamilyMembers($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductFamilyMembers', $request));

	}
/**
 *

 * @return GetProductFinderResponseType
 * @param GetProductFinderRequestType $request 
 */
	function &GetProductFinder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductFinder', $request));

	}
/**
 *

 * @return GetProductFinderXSLResponseType
 * @param GetProductFinderXSLRequestType $request 
 */
	function &GetProductFinderXSL($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductFinderXSL', $request));

	}
/**
 *

 * @return GetProductSearchPageResponseType
 * @param GetProductSearchPageRequestType $request 
 */
	function &GetProductSearchPage($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductSearchPage', $request));

	}
/**
 *

 * @return GetProductSearchResultsResponseType
 * @param GetProductSearchResultsRequestType $request 
 */
	function &GetProductSearchResults($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductSearchResults', $request));

	}
/**
 *

 * @return GetProductSellingPagesResponseType
 * @param GetProductSellingPagesRequestType $request 
 */
	function &GetProductSellingPages($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProductSellingPages', $request));

	}
/**
 *

 * @return GetProductsResponseType
 * @param GetProductsRequestType $request 
 */
	function &GetProducts($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetProducts', $request));

	}
/**
 *

 * @return GetPromotionRulesResponseType
 * @param GetPromotionRulesRequestType $request 
 */
	function &GetPromotionRules($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetPromotionRules', $request));

	}
/**
 *

 * @return GetPromotionalSaleDetailsResponseType
 * @param GetPromotionalSaleDetailsRequestType $request 
 */
	function &GetPromotionalSaleDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetPromotionalSaleDetails', $request));

	}
/**
 *

 * @return GetSearchResultsResponseType
 * @param GetSearchResultsRequestType $request 
 */
	function &GetSearchResults($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSearchResults', $request));

	}
/**
 *

 * @return GetSellerDashboardResponseType
 * @param GetSellerDashboardRequestType $request 
 */
	function &GetSellerDashboard($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellerDashboard', $request));

	}
/**
 *

 * @return GetSellerEventsResponseType
 * @param GetSellerEventsRequestType $request 
 */
	function &GetSellerEvents($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellerEvents', $request));

	}
/**
 *

 * @return GetSellerListResponseType
 * @param GetSellerListRequestType $request 
 */
	function &GetSellerList($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellerList', $request));

	}
/**
 *

 * @return GetSellerPaymentsResponseType
 * @param GetSellerPaymentsRequestType $request 
 */
	function &GetSellerPayments($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellerPayments', $request));

	}
/**
 *

 * @return GetSellerTransactionsResponseType
 * @param GetSellerTransactionsRequestType $request 
 */
	function &GetSellerTransactions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellerTransactions', $request));

	}
/**
 *

 * @return GetSellingManagerAlertsResponseType
 * @param GetSellingManagerAlertsRequestType $request 
 */
	function &GetSellingManagerAlerts($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerAlerts', $request));

	}
/**
 *

 * @return GetSellingManagerEmailLogResponseType
 * @param GetSellingManagerEmailLogRequestType $request 
 */
	function &GetSellingManagerEmailLog($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerEmailLog', $request));

	}
/**
 *

 * @return GetSellingManagerInventoryResponseType
 * @param GetSellingManagerInventoryRequestType $request 
 */
	function &GetSellingManagerInventory($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerInventory', $request));

	}
/**
 *

 * @return GetSellingManagerInventoryFolderResponseType
 * @param GetSellingManagerInventoryFolderRequestType $request 
 */
	function &GetSellingManagerInventoryFolder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerInventoryFolder', $request));

	}
/**
 *

 * @return GetSellingManagerItemAutomationRuleResponseType
 * @param GetSellingManagerItemAutomationRuleRequestType $request 
 */
	function &GetSellingManagerItemAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerItemAutomationRule', $request));

	}
/**
 *

 * @return GetSellingManagerSaleRecordResponseType
 * @param GetSellingManagerSaleRecordRequestType $request 
 */
	function &GetSellingManagerSaleRecord($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerSaleRecord', $request));

	}
/**
 *

 * @return GetSellingManagerSoldListingsResponseType
 * @param GetSellingManagerSoldListingsRequestType $request 
 */
	function &GetSellingManagerSoldListings($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerSoldListings', $request));

	}
/**
 *

 * @return GetSellingManagerTemplateAutomationRuleResponseType
 * @param GetSellingManagerTemplateAutomationRuleRequestType $request 
 */
	function &GetSellingManagerTemplateAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerTemplateAutomationRule', $request));

	}
/**
 *

 * @return GetSellingManagerTemplatesResponseType
 * @param GetSellingManagerTemplatesRequestType $request 
 */
	function &GetSellingManagerTemplates($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSellingManagerTemplates', $request));

	}
/**
 *

 * @return GetSessionIDResponseType
 * @param GetSessionIDRequestType $request 
 */
	function &GetSessionID($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSessionID', $request));

	}
/**
 *

 * @return GetShippingDiscountProfilesResponseType
 * @param GetShippingDiscountProfilesRequestType $request 
 */
	function &GetShippingDiscountProfiles($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetShippingDiscountProfiles', $request));

	}
/**
 *

 * @return GetStoreResponseType
 * @param GetStoreRequestType $request 
 */
	function &GetStore($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetStore', $request));

	}
/**
 *

 * @return GetStoreCategoryUpdateStatusResponseType
 * @param GetStoreCategoryUpdateStatusRequestType $request 
 */
	function &GetStoreCategoryUpdateStatus($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetStoreCategoryUpdateStatus', $request));

	}
/**
 *

 * @return GetStoreCustomPageResponseType
 * @param GetStoreCustomPageRequestType $request 
 */
	function &GetStoreCustomPage($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetStoreCustomPage', $request));

	}
/**
 *

 * @return GetStoreOptionsResponseType
 * @param GetStoreOptionsRequestType $request 
 */
	function &GetStoreOptions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetStoreOptions', $request));

	}
/**
 *

 * @return GetStorePreferencesResponseType
 * @param GetStorePreferencesRequestType $request 
 */
	function &GetStorePreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetStorePreferences', $request));

	}
/**
 *

 * @return GetSuggestedCategoriesResponseType
 * @param GetSuggestedCategoriesRequestType $request 
 */
	function &GetSuggestedCategories($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetSuggestedCategories', $request));

	}
/**
 *

 * @return GetTaxTableResponseType
 * @param GetTaxTableRequestType $request 
 */
	function &GetTaxTable($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetTaxTable', $request));

	}
/**
 *

 * @return GetTokenStatusResponseType
 * @param GetTokenStatusRequestType $request 
 */
	function &GetTokenStatus($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetTokenStatus', $request));

	}
/**
 *

 * @return GetUserResponseType
 * @param GetUserRequestType $request 
 */
	function &GetUser($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetUser', $request));

	}
/**
 *

 * @return GetUserContactDetailsResponseType
 * @param GetUserContactDetailsRequestType $request 
 */
	function &GetUserContactDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetUserContactDetails', $request));

	}
/**
 *

 * @return GetUserDisputesResponseType
 * @param GetUserDisputesRequestType $request 
 */
	function &GetUserDisputes($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetUserDisputes', $request));

	}
/**
 *

 * @return GetUserPreferencesResponseType
 * @param GetUserPreferencesRequestType $request 
 */
	function &GetUserPreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetUserPreferences', $request));

	}
/**
 *

 * @return GetVeROReasonCodeDetailsResponseType
 * @param GetVeROReasonCodeDetailsRequestType $request 
 */
	function &GetVeROReasonCodeDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetVeROReasonCodeDetails', $request));

	}
/**
 *

 * @return GetVeROReportStatusResponseType
 * @param GetVeROReportStatusRequestType $request 
 */
	function &GetVeROReportStatus($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetVeROReportStatus', $request));

	}
/**
 *

 * @return GetWantItNowPostResponseType
 * @param GetWantItNowPostRequestType $request 
 */
	function &GetWantItNowPost($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetWantItNowPost', $request));

	}
/**
 *

 * @return GetWantItNowSearchResultsResponseType
 * @param GetWantItNowSearchResultsRequestType $request 
 */
	function &GetWantItNowSearchResults($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GetWantItNowSearchResults', $request));

	}
/**
 *

 * @return GeteBayDetailsResponseType
 * @param GeteBayDetailsRequestType $request 
 */
	function &GeteBayDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GeteBayDetails', $request));

	}
/**
 *

 * @return GeteBayOfficialTimeResponseType
 * @param GeteBayOfficialTimeRequestType $request 
 */
	function &GeteBayOfficialTime($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('GeteBayOfficialTime', $request));

	}
/**
 *

 * @return IssueRefundResponseType
 * @param IssueRefundRequestType $request 
 */
	function &IssueRefund($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('IssueRefund', $request));

	}
/**
 *

 * @return LeaveFeedbackResponseType
 * @param LeaveFeedbackRequestType $request 
 */
	function &LeaveFeedback($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('LeaveFeedback', $request));

	}
/**
 *

 * @return MoveSellingManagerInventoryFolderResponseType
 * @param MoveSellingManagerInventoryFolderRequestType $request 
 */
	function &MoveSellingManagerInventoryFolder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('MoveSellingManagerInventoryFolder', $request));

	}
/**
 *

 * @return PlaceOfferResponseType
 * @param PlaceOfferRequestType $request 
 */
	function &PlaceOffer($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('PlaceOffer', $request));

	}
/**
 *

 * @return RelistFixedPriceItemResponseType
 * @param RelistFixedPriceItemRequestType $request 
 */
	function &RelistFixedPriceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RelistFixedPriceItem', $request));

	}
/**
 *

 * @return RelistItemResponseType
 * @param RelistItemRequestType $request 
 */
	function &RelistItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RelistItem', $request));

	}
/**
 *

 * @return RemoveFromWatchListResponseType
 * @param RemoveFromWatchListRequestType $request 
 */
	function &RemoveFromWatchList($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RemoveFromWatchList', $request));

	}
/**
 *

 * @return RespondToBestOfferResponseType
 * @param RespondToBestOfferRequestType $request 
 */
	function &RespondToBestOffer($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RespondToBestOffer', $request));

	}
/**
 *

 * @return RespondToFeedbackResponseType
 * @param RespondToFeedbackRequestType $request 
 */
	function &RespondToFeedback($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RespondToFeedback', $request));

	}
/**
 *

 * @return RespondToWantItNowPostResponseType
 * @param RespondToWantItNowPostRequestType $request 
 */
	function &RespondToWantItNowPost($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RespondToWantItNowPost', $request));

	}
/**
 *

 * @return ReviseCheckoutStatusResponseType
 * @param ReviseCheckoutStatusRequestType $request 
 */
	function &ReviseCheckoutStatus($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseCheckoutStatus', $request));

	}
/**
 *

 * @return ReviseFixedPriceItemResponseType
 * @param ReviseFixedPriceItemRequestType $request 
 */
	function &ReviseFixedPriceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseFixedPriceItem', $request));

	}
/**
 *

 * @return ReviseInventoryStatusResponseType
 * @param ReviseInventoryStatusRequestType $request 
 */
	function &ReviseInventoryStatus($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseInventoryStatus', $request));

	}
/**
 *

 * @return ReviseItemResponseType
 * @param ReviseItemRequestType $request 
 */
	function &ReviseItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseItem', $request));

	}
/**
 *

 * @return ReviseMyMessagesResponseType
 * @param ReviseMyMessagesRequestType $request 
 */
	function &ReviseMyMessages($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseMyMessages', $request));

	}
/**
 *

 * @return ReviseMyMessagesFoldersResponseType
 * @param ReviseMyMessagesFoldersRequestType $request 
 */
	function &ReviseMyMessagesFolders($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseMyMessagesFolders', $request));

	}
/**
 *

 * @return ReviseSellingManagerInventoryFolderResponseType
 * @param ReviseSellingManagerInventoryFolderRequestType $request 
 */
	function &ReviseSellingManagerInventoryFolder($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseSellingManagerInventoryFolder', $request));

	}
/**
 *

 * @return ReviseSellingManagerProductResponseType
 * @param ReviseSellingManagerProductRequestType $request 
 */
	function &ReviseSellingManagerProduct($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseSellingManagerProduct', $request));

	}
/**
 *

 * @return ReviseSellingManagerSaleRecordResponseType
 * @param ReviseSellingManagerSaleRecordRequestType $request 
 */
	function &ReviseSellingManagerSaleRecord($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseSellingManagerSaleRecord', $request));

	}
/**
 *

 * @return ReviseSellingManagerTemplateResponseType
 * @param ReviseSellingManagerTemplateRequestType $request 
 */
	function &ReviseSellingManagerTemplate($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ReviseSellingManagerTemplate', $request));

	}
/**
 *

 * @return RevokeTokenResponseType
 * @param RevokeTokenRequestType $request 
 */
	function &RevokeToken($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('RevokeToken', $request));

	}
/**
 *

 * @return SaveItemToSellingManagerTemplateResponseType
 * @param SaveItemToSellingManagerTemplateRequestType $request 
 */
	function &SaveItemToSellingManagerTemplate($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SaveItemToSellingManagerTemplate', $request));

	}
/**
 *

 * @return SellerReverseDisputeResponseType
 * @param SellerReverseDisputeRequestType $request 
 */
	function &SellerReverseDispute($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SellerReverseDispute', $request));

	}
/**
 *

 * @return SendInvoiceResponseType
 * @param SendInvoiceRequestType $request 
 */
	function &SendInvoice($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SendInvoice', $request));

	}
/**
 *

 * @return SetMessagePreferencesResponseType
 * @param SetMessagePreferencesRequestType $request 
 */
	function &SetMessagePreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetMessagePreferences', $request));

	}
/**
 *

 * @return SetNotificationPreferencesResponseType
 * @param SetNotificationPreferencesRequestType $request 
 */
	function &SetNotificationPreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetNotificationPreferences', $request));

	}
/**
 *

 * @return SetPictureManagerDetailsResponseType
 * @param SetPictureManagerDetailsRequestType $request 
 */
	function &SetPictureManagerDetails($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetPictureManagerDetails', $request));

	}
/**
 *

 * @return SetPromotionalSaleResponseType
 * @param SetPromotionalSaleRequestType $request 
 */
	function &SetPromotionalSale($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetPromotionalSale', $request));

	}
/**
 *

 * @return SetPromotionalSaleListingsResponseType
 * @param SetPromotionalSaleListingsRequestType $request 
 */
	function &SetPromotionalSaleListings($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetPromotionalSaleListings', $request));

	}
/**
 *

 * @return SetSellingManagerFeedbackOptionsResponseType
 * @param SetSellingManagerFeedbackOptionsRequestType $request 
 */
	function &SetSellingManagerFeedbackOptions($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetSellingManagerFeedbackOptions', $request));

	}
/**
 *

 * @return SetSellingManagerItemAutomationRuleResponseType
 * @param SetSellingManagerItemAutomationRuleRequestType $request 
 */
	function &SetSellingManagerItemAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetSellingManagerItemAutomationRule', $request));

	}
/**
 *

 * @return SetSellingManagerTemplateAutomationRuleResponseType
 * @param SetSellingManagerTemplateAutomationRuleRequestType $request 
 */
	function &SetSellingManagerTemplateAutomationRule($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetSellingManagerTemplateAutomationRule', $request));

	}
/**
 *

 * @return SetShippingDiscountProfilesResponseType
 * @param SetShippingDiscountProfilesRequestType $request 
 */
	function &SetShippingDiscountProfiles($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetShippingDiscountProfiles', $request));

	}
/**
 *

 * @return SetStoreResponseType
 * @param SetStoreRequestType $request 
 */
	function &SetStore($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetStore', $request));

	}
/**
 *

 * @return SetStoreCategoriesResponseType
 * @param SetStoreCategoriesRequestType $request 
 */
	function &SetStoreCategories($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetStoreCategories', $request));

	}
/**
 *

 * @return SetStoreCustomPageResponseType
 * @param SetStoreCustomPageRequestType $request 
 */
	function &SetStoreCustomPage($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetStoreCustomPage', $request));

	}
/**
 *

 * @return SetStorePreferencesResponseType
 * @param SetStorePreferencesRequestType $request 
 */
	function &SetStorePreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetStorePreferences', $request));

	}
/**
 *

 * @return SetTaxTableResponseType
 * @param SetTaxTableRequestType $request 
 */
	function &SetTaxTable($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetTaxTable', $request));

	}
/**
 *

 * @return SetUserNotesResponseType
 * @param SetUserNotesRequestType $request 
 */
	function &SetUserNotes($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetUserNotes', $request));

	}
/**
 *

 * @return SetUserPreferencesResponseType
 * @param SetUserPreferencesRequestType $request 
 */
	function &SetUserPreferences($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('SetUserPreferences', $request));

	}
/**
 *

 * @return UploadSiteHostedPicturesResponseType
 * @param UploadSiteHostedPicturesRequestType $request 
 */
	function &UploadSiteHostedPictures($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->callXmlStyle('UploadSiteHostedPictures', $request));

	}
/**
 *

 * @return ValidateChallengeInputResponseType
 * @param ValidateChallengeInputRequestType $request 
 */
	function &ValidateChallengeInput($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ValidateChallengeInput', $request));

	}
/**
 *

 * @return ValidateTestUserRegistrationResponseType
 * @param ValidateTestUserRegistrationRequestType $request 
 */
	function &ValidateTestUserRegistration($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('ValidateTestUserRegistration', $request));

	}
/**
 *

 * @return VeROReportItemsResponseType
 * @param VeROReportItemsRequestType $request 
 */
	function &VeROReportItems($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('VeROReportItems', $request));

	}
/**
 *

 * @return VerifyAddFixedPriceItemResponseType
 * @param VerifyAddFixedPriceItemRequestType $request 
 */
	function &VerifyAddFixedPriceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('VerifyAddFixedPriceItem', $request));

	}
/**
 *

 * @return VerifyAddItemResponseType
 * @param VerifyAddItemRequestType $request 
 */
	function &VerifyAddItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('VerifyAddItem', $request));

	}
/**
 *

 * @return VerifyAddSecondChanceItemResponseType
 * @param VerifyAddSecondChanceItemRequestType $request 
 */
	function &VerifyAddSecondChanceItem($request)
	{
			$request->setVersion(EBAY_WSDL_VERSION);
		return ($res = & $this->call('VerifyAddSecondChanceItem', $request));

	}

}
?>