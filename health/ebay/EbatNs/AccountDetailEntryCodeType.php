<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class AccountDetailEntryCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Unknown
	var $Unknown = 'Unknown';
	// @var string $FeeInsertion
	var $FeeInsertion = 'FeeInsertion';
	// @var string $FeeBold
	var $FeeBold = 'FeeBold';
	// @var string $FeeFeatured
	var $FeeFeatured = 'FeeFeatured';
	// @var string $FeeCategoryFeatured
	var $FeeCategoryFeatured = 'FeeCategoryFeatured';
	// @var string $FeeFinalValue
	var $FeeFinalValue = 'FeeFinalValue';
	// @var string $PaymentCheck
	var $PaymentCheck = 'PaymentCheck';
	// @var string $PaymentCC
	var $PaymentCC = 'PaymentCC';
	// @var string $CreditCourtesy
	var $CreditCourtesy = 'CreditCourtesy';
	// @var string $CreditNoSale
	var $CreditNoSale = 'CreditNoSale';
	// @var string $CreditPartialSale
	var $CreditPartialSale = 'CreditPartialSale';
	// @var string $RefundCC
	var $RefundCC = 'RefundCC';
	// @var string $RefundCheck
	var $RefundCheck = 'RefundCheck';
	// @var string $FinanceCharge
	var $FinanceCharge = 'FinanceCharge';
	// @var string $AWDebit
	var $AWDebit = 'AWDebit';
	// @var string $AWCredit
	var $AWCredit = 'AWCredit';
	// @var string $AWMemo
	var $AWMemo = 'AWMemo';
	// @var string $CreditDuplicateListing
	var $CreditDuplicateListing = 'CreditDuplicateListing';
	// @var string $FeePartialSale
	var $FeePartialSale = 'FeePartialSale';
	// @var string $PaymentElectronicTransferReversal
	var $PaymentElectronicTransferReversal = 'PaymentElectronicTransferReversal';
	// @var string $PaymentCCOnce
	var $PaymentCCOnce = 'PaymentCCOnce';
	// @var string $FeeReturnedCheck
	var $FeeReturnedCheck = 'FeeReturnedCheck';
	// @var string $FeeRedepositCheck
	var $FeeRedepositCheck = 'FeeRedepositCheck';
	// @var string $PaymentCash
	var $PaymentCash = 'PaymentCash';
	// @var string $CreditInsertion
	var $CreditInsertion = 'CreditInsertion';
	// @var string $CreditBold
	var $CreditBold = 'CreditBold';
	// @var string $CreditFeatured
	var $CreditFeatured = 'CreditFeatured';
	// @var string $CreditCategoryFeatured
	var $CreditCategoryFeatured = 'CreditCategoryFeatured';
	// @var string $CreditFinalValue
	var $CreditFinalValue = 'CreditFinalValue';
	// @var string $FeeNSFCheck
	var $FeeNSFCheck = 'FeeNSFCheck';
	// @var string $FeeReturnCheckClose
	var $FeeReturnCheckClose = 'FeeReturnCheckClose';
	// @var string $Memo
	var $Memo = 'Memo';
	// @var string $PaymentMoneyOrder
	var $PaymentMoneyOrder = 'PaymentMoneyOrder';
	// @var string $CreditCardOnFile
	var $CreditCardOnFile = 'CreditCardOnFile';
	// @var string $CreditCardNotOnFile
	var $CreditCardNotOnFile = 'CreditCardNotOnFile';
	// @var string $Invoiced
	var $Invoiced = 'Invoiced';
	// @var string $InvoicedCreditCard
	var $InvoicedCreditCard = 'InvoicedCreditCard';
	// @var string $CreditTransferFrom
	var $CreditTransferFrom = 'CreditTransferFrom';
	// @var string $DebitTransferTo
	var $DebitTransferTo = 'DebitTransferTo';
	// @var string $InvoiceCreditBalance
	var $InvoiceCreditBalance = 'InvoiceCreditBalance';
	// @var string $eBayDebit
	var $eBayDebit = 'eBayDebit';
	// @var string $eBayCredit
	var $eBayCredit = 'eBayCredit';
	// @var string $PromotionalCredit
	var $PromotionalCredit = 'PromotionalCredit';
	// @var string $CCNotOnFilePerCustReq
	var $CCNotOnFilePerCustReq = 'CCNotOnFilePerCustReq';
	// @var string $CreditInsertionFee
	var $CreditInsertionFee = 'CreditInsertionFee';
	// @var string $CCPaymentRejected
	var $CCPaymentRejected = 'CCPaymentRejected';
	// @var string $FeeGiftIcon
	var $FeeGiftIcon = 'FeeGiftIcon';
	// @var string $CreditGiftIcon
	var $CreditGiftIcon = 'CreditGiftIcon';
	// @var string $FeeGallery
	var $FeeGallery = 'FeeGallery';
	// @var string $FeeFeaturedGallery
	var $FeeFeaturedGallery = 'FeeFeaturedGallery';
	// @var string $CreditGallery
	var $CreditGallery = 'CreditGallery';
	// @var string $CreditFeaturedGallery
	var $CreditFeaturedGallery = 'CreditFeaturedGallery';
	// @var string $ItemMoveFee
	var $ItemMoveFee = 'ItemMoveFee';
	// @var string $OutageCredit
	var $OutageCredit = 'OutageCredit';
	// @var string $CreditPSA
	var $CreditPSA = 'CreditPSA';
	// @var string $CreditPCGS
	var $CreditPCGS = 'CreditPCGS';
	// @var string $FeeReserve
	var $FeeReserve = 'FeeReserve';
	// @var string $CreditReserve
	var $CreditReserve = 'CreditReserve';
	// @var string $eBayVISACredit
	var $eBayVISACredit = 'eBayVISACredit';
	// @var string $BBAdminCredit
	var $BBAdminCredit = 'BBAdminCredit';
	// @var string $BBAdminDebit
	var $BBAdminDebit = 'BBAdminDebit';
	// @var string $ReferrerCredit
	var $ReferrerCredit = 'ReferrerCredit';
	// @var string $ReferrerDebit
	var $ReferrerDebit = 'ReferrerDebit';
	// @var string $SwitchCurrency
	var $SwitchCurrency = 'SwitchCurrency';
	// @var string $PaymentGiftCertificate
	var $PaymentGiftCertificate = 'PaymentGiftCertificate';
	// @var string $PaymentWireTransfer
	var $PaymentWireTransfer = 'PaymentWireTransfer';
	// @var string $PaymentHomeBanking
	var $PaymentHomeBanking = 'PaymentHomeBanking';
	// @var string $PaymentElectronicTransfer
	var $PaymentElectronicTransfer = 'PaymentElectronicTransfer';
	// @var string $PaymentAdjustmentCredit
	var $PaymentAdjustmentCredit = 'PaymentAdjustmentCredit';
	// @var string $PaymentAdjustmentDebit
	var $PaymentAdjustmentDebit = 'PaymentAdjustmentDebit';
	// @var string $Chargeoff
	var $Chargeoff = 'Chargeoff';
	// @var string $ChargeoffRecovery
	var $ChargeoffRecovery = 'ChargeoffRecovery';
	// @var string $ChargeoffBankruptcy
	var $ChargeoffBankruptcy = 'ChargeoffBankruptcy';
	// @var string $ChargeoffSuspended
	var $ChargeoffSuspended = 'ChargeoffSuspended';
	// @var string $ChargeoffDeceased
	var $ChargeoffDeceased = 'ChargeoffDeceased';
	// @var string $ChargeoffOther
	var $ChargeoffOther = 'ChargeoffOther';
	// @var string $ChargeoffWacko
	var $ChargeoffWacko = 'ChargeoffWacko';
	// @var string $FinanceChargeReversal
	var $FinanceChargeReversal = 'FinanceChargeReversal';
	// @var string $FVFCreditReversal
	var $FVFCreditReversal = 'FVFCreditReversal';
	// @var string $ForeignFundsConvert
	var $ForeignFundsConvert = 'ForeignFundsConvert';
	// @var string $ForeignFundsCheckReversal
	var $ForeignFundsCheckReversal = 'ForeignFundsCheckReversal';
	// @var string $EOMRestriction
	var $EOMRestriction = 'EOMRestriction';
	// @var string $AllFeesCredit
	var $AllFeesCredit = 'AllFeesCredit';
	// @var string $SetOnHold
	var $SetOnHold = 'SetOnHold';
	// @var string $RevertUserState
	var $RevertUserState = 'RevertUserState';
	// @var string $DirectDebitOnFile
	var $DirectDebitOnFile = 'DirectDebitOnFile';
	// @var string $DirectDebitNotOnFile
	var $DirectDebitNotOnFile = 'DirectDebitNotOnFile';
	// @var string $PaymentDirectDebit
	var $PaymentDirectDebit = 'PaymentDirectDebit';
	// @var string $DirectDebitReversal
	var $DirectDebitReversal = 'DirectDebitReversal';
	// @var string $DirectDebitReturnedItem
	var $DirectDebitReturnedItem = 'DirectDebitReturnedItem';
	// @var string $FeeHighlight
	var $FeeHighlight = 'FeeHighlight';
	// @var string $CreditHighlight
	var $CreditHighlight = 'CreditHighlight';
	// @var string $BulkUserSuspension
	var $BulkUserSuspension = 'BulkUserSuspension';
	// @var string $FeeRealEstate30DaysListing
	var $FeeRealEstate30DaysListing = 'FeeRealEstate30DaysListing';
	// @var string $CreditRealEstate30DaysListing
	var $CreditRealEstate30DaysListing = 'CreditRealEstate30DaysListing';
	// @var string $TradingLimitOverrideOn
	var $TradingLimitOverrideOn = 'TradingLimitOverrideOn';
	// @var string $TradingLimitOverrideOff
	var $TradingLimitOverrideOff = 'TradingLimitOverrideOff';
	// @var string $EquifaxRealtimeFee
	var $EquifaxRealtimeFee = 'EquifaxRealtimeFee';
	// @var string $CreditEquifaxRealtimeFee
	var $CreditEquifaxRealtimeFee = 'CreditEquifaxRealtimeFee';
	// @var string $PaymentEquifaxDebit
	var $PaymentEquifaxDebit = 'PaymentEquifaxDebit';
	// @var string $PaymentEquifaxCredit
	var $PaymentEquifaxCredit = 'PaymentEquifaxCredit';
	// @var string $Merged
	var $Merged = 'Merged';
	// @var string $AutoTraderOn
	var $AutoTraderOn = 'AutoTraderOn';
	// @var string $AutoTraderOff
	var $AutoTraderOff = 'AutoTraderOff';
	// @var string $PaperInvoiceOn
	var $PaperInvoiceOn = 'PaperInvoiceOn';
	// @var string $PaperInvoiceOff
	var $PaperInvoiceOff = 'PaperInvoiceOff';
	// @var string $AccountStateSwitch
	var $AccountStateSwitch = 'AccountStateSwitch';
	// @var string $FVFCreditReversalAutomatic
	var $FVFCreditReversalAutomatic = 'FVFCreditReversalAutomatic';
	// @var string $CreditSoftOutage
	var $CreditSoftOutage = 'CreditSoftOutage';
	// @var string $LACatalogFee
	var $LACatalogFee = 'LACatalogFee';
	// @var string $LAExtraItem
	var $LAExtraItem = 'LAExtraItem';
	// @var string $LACatalogItemFeeRefund
	var $LACatalogItemFeeRefund = 'LACatalogItemFeeRefund';
	// @var string $LACatalogInsertionRefund
	var $LACatalogInsertionRefund = 'LACatalogInsertionRefund';
	// @var string $LAFinalValueFee
	var $LAFinalValueFee = 'LAFinalValueFee';
	// @var string $LAFinalValueFeeRefund
	var $LAFinalValueFeeRefund = 'LAFinalValueFeeRefund';
	// @var string $LABuyerPremiumPercentageFee
	var $LABuyerPremiumPercentageFee = 'LABuyerPremiumPercentageFee';
	// @var string $LABuyerPremiumPercentageFeeRefund
	var $LABuyerPremiumPercentageFeeRefund = 'LABuyerPremiumPercentageFeeRefund';
	// @var string $LAAudioVideoFee
	var $LAAudioVideoFee = 'LAAudioVideoFee';
	// @var string $LAAudioVideoFeeRefund
	var $LAAudioVideoFeeRefund = 'LAAudioVideoFeeRefund';
	// @var string $FeeIPIXPhoto
	var $FeeIPIXPhoto = 'FeeIPIXPhoto';
	// @var string $FeeIPIXSlideShow
	var $FeeIPIXSlideShow = 'FeeIPIXSlideShow';
	// @var string $CreditIPIXPhoto
	var $CreditIPIXPhoto = 'CreditIPIXPhoto';
	// @var string $CreditIPIXSlideShow
	var $CreditIPIXSlideShow = 'CreditIPIXSlideShow';
	// @var string $FeeTenDayAuction
	var $FeeTenDayAuction = 'FeeTenDayAuction';
	// @var string $CreditTenDayAuction
	var $CreditTenDayAuction = 'CreditTenDayAuction';
	// @var string $TemporaryCredit
	var $TemporaryCredit = 'TemporaryCredit';
	// @var string $TemporaryCreditReversal
	var $TemporaryCreditReversal = 'TemporaryCreditReversal';
	// @var string $SubscriptionAABasic
	var $SubscriptionAABasic = 'SubscriptionAABasic';
	// @var string $SubscriptionAAPro
	var $SubscriptionAAPro = 'SubscriptionAAPro';
	// @var string $CreditAABasic
	var $CreditAABasic = 'CreditAABasic';
	// @var string $CreditAAPro
	var $CreditAAPro = 'CreditAAPro';
	// @var string $FeeLargePicture
	var $FeeLargePicture = 'FeeLargePicture';
	// @var string $CreditLargePicture
	var $CreditLargePicture = 'CreditLargePicture';
	// @var string $FeePicturePack
	var $FeePicturePack = 'FeePicturePack';
	// @var string $CreditPicturePackPartial
	var $CreditPicturePackPartial = 'CreditPicturePackPartial';
	// @var string $CreditPicturePackFull
	var $CreditPicturePackFull = 'CreditPicturePackFull';
	// @var string $SubscriptioneBayStores
	var $SubscriptioneBayStores = 'SubscriptioneBayStores';
	// @var string $CrediteBayStores
	var $CrediteBayStores = 'CrediteBayStores';
	// @var string $FeeInsertionFixedPrice
	var $FeeInsertionFixedPrice = 'FeeInsertionFixedPrice';
	// @var string $CreditInsertionFixedPrice
	var $CreditInsertionFixedPrice = 'CreditInsertionFixedPrice';
	// @var string $FeeFinalValueFixedPrice
	var $FeeFinalValueFixedPrice = 'FeeFinalValueFixedPrice';
	// @var string $CreditFinalValueFixedPrice
	var $CreditFinalValueFixedPrice = 'CreditFinalValueFixedPrice';
	// @var string $ElectronicInvoiceOn
	var $ElectronicInvoiceOn = 'ElectronicInvoiceOn';
	// @var string $ElectronicInvoiceOff
	var $ElectronicInvoiceOff = 'ElectronicInvoiceOff';
	// @var string $FlagDDDDPending
	var $FlagDDDDPending = 'FlagDDDDPending';
	// @var string $FlagDDPaymentConfirmed
	var $FlagDDPaymentConfirmed = 'FlagDDPaymentConfirmed';
	// @var string $FixedPriceDurationFee
	var $FixedPriceDurationFee = 'FixedPriceDurationFee';
	// @var string $FixedPriceDurationCredit
	var $FixedPriceDurationCredit = 'FixedPriceDurationCredit';
	// @var string $BuyItNowFee
	var $BuyItNowFee = 'BuyItNowFee';
	// @var string $BuyItNowCredit
	var $BuyItNowCredit = 'BuyItNowCredit';
	// @var string $FeeSchedule
	var $FeeSchedule = 'FeeSchedule';
	// @var string $CreditSchedule
	var $CreditSchedule = 'CreditSchedule';
	// @var string $SubscriptionSMBasic
	var $SubscriptionSMBasic = 'SubscriptionSMBasic';
	// @var string $SubscriptionSMBasicPro
	var $SubscriptionSMBasicPro = 'SubscriptionSMBasicPro';
	// @var string $CreditSMBasic
	var $CreditSMBasic = 'CreditSMBasic';
	// @var string $CreditSMBasicPro
	var $CreditSMBasicPro = 'CreditSMBasicPro';
	// @var string $StoresGTCFee
	var $StoresGTCFee = 'StoresGTCFee';
	// @var string $StoresGTCCredit
	var $StoresGTCCredit = 'StoresGTCCredit';
	// @var string $ListingDesignerFee
	var $ListingDesignerFee = 'ListingDesignerFee';
	// @var string $ListingDesignerCredit
	var $ListingDesignerCredit = 'ListingDesignerCredit';
	// @var string $ExtendedAuctionFee
	var $ExtendedAuctionFee = 'ExtendedAuctionFee';
	// @var string $ExtendedAcutionCredit
	var $ExtendedAcutionCredit = 'ExtendedAcutionCredit';
	// @var string $PayPalOTPSucc
	var $PayPalOTPSucc = 'PayPalOTPSucc';
	// @var string $PayPalOTPPend
	var $PayPalOTPPend = 'PayPalOTPPend';
	// @var string $PayPalFailed
	var $PayPalFailed = 'PayPalFailed';
	// @var string $PayPalChargeBack
	var $PayPalChargeBack = 'PayPalChargeBack';
	// @var string $ChargeBack
	var $ChargeBack = 'ChargeBack';
	// @var string $ChargeBackReversal
	var $ChargeBackReversal = 'ChargeBackReversal';
	// @var string $PayPalRefund
	var $PayPalRefund = 'PayPalRefund';
	// @var string $BonusPointsAddition
	var $BonusPointsAddition = 'BonusPointsAddition';
	// @var string $BonusPointsReduction
	var $BonusPointsReduction = 'BonusPointsReduction';
	// @var string $BonusPointsPaymentAutomatic
	var $BonusPointsPaymentAutomatic = 'BonusPointsPaymentAutomatic';
	// @var string $BonusPointsPaymentManual
	var $BonusPointsPaymentManual = 'BonusPointsPaymentManual';
	// @var string $BonusPointsPaymentReversal
	var $BonusPointsPaymentReversal = 'BonusPointsPaymentReversal';
	// @var string $BonusPointsCashPayout
	var $BonusPointsCashPayout = 'BonusPointsCashPayout';
	// @var string $VATCredit
	var $VATCredit = 'VATCredit';
	// @var string $VATDebit
	var $VATDebit = 'VATDebit';
	// @var string $VATStatusChangePending
	var $VATStatusChangePending = 'VATStatusChangePending';
	// @var string $VATStatusChangeApproved
	var $VATStatusChangeApproved = 'VATStatusChangeApproved';
	// @var string $VATStatusChange_Denied
	var $VATStatusChange_Denied = 'VATStatusChange_Denied';
	// @var string $VATStatusDeletedByCSR
	var $VATStatusDeletedByCSR = 'VATStatusDeletedByCSR';
	// @var string $VATStatusDeletedByUser
	var $VATStatusDeletedByUser = 'VATStatusDeletedByUser';
	// @var string $SMProListingDesignerFee
	var $SMProListingDesignerFee = 'SMProListingDesignerFee';
	// @var string $SMProListingDesignerCredit
	var $SMProListingDesignerCredit = 'SMProListingDesignerCredit';
	// @var string $StoresSuccessfulListingFee
	var $StoresSuccessfulListingFee = 'StoresSuccessfulListingFee';
	// @var string $StoresSuccessfulListingFeeCredit
	var $StoresSuccessfulListingFeeCredit = 'StoresSuccessfulListingFeeCredit';
	// @var string $StoresReferralFee
	var $StoresReferralFee = 'StoresReferralFee';
	// @var string $StoresReferralCredit
	var $StoresReferralCredit = 'StoresReferralCredit';
	// @var string $SubtitleFee
	var $SubtitleFee = 'SubtitleFee';
	// @var string $SubtitleFeeCredit
	var $SubtitleFeeCredit = 'SubtitleFeeCredit';
	// @var string $eBayStoreInventorySubscriptionCredit
	var $eBayStoreInventorySubscriptionCredit = 'eBayStoreInventorySubscriptionCredit';
	// @var string $AutoPmntReqExempt
	var $AutoPmntReqExempt = 'AutoPmntReqExempt';
	// @var string $AutoPmntReqRein
	var $AutoPmntReqRein = 'AutoPmntReqRein';
	// @var string $PictureManagerSubscriptionFee
	var $PictureManagerSubscriptionFee = 'PictureManagerSubscriptionFee';
	// @var string $PictureManagerSubscriptionFeeCredit
	var $PictureManagerSubscriptionFeeCredit = 'PictureManagerSubscriptionFeeCredit';
	// @var string $SellerReportsBasicFee
	var $SellerReportsBasicFee = 'SellerReportsBasicFee';
	// @var string $SellerReportsBasicCredit
	var $SellerReportsBasicCredit = 'SellerReportsBasicCredit';
	// @var string $SellerReportsPlusFee
	var $SellerReportsPlusFee = 'SellerReportsPlusFee';
	// @var string $SellerReportsPlusCredit
	var $SellerReportsPlusCredit = 'SellerReportsPlusCredit';
	// @var string $PaypalOnFile
	var $PaypalOnFile = 'PaypalOnFile';
	// @var string $PaypalOnFileByCSR
	var $PaypalOnFileByCSR = 'PaypalOnFileByCSR';
	// @var string $PaypalOffFile
	var $PaypalOffFile = 'PaypalOffFile';
	// @var string $BorderFee
	var $BorderFee = 'BorderFee';
	// @var string $BorderFeeCredit
	var $BorderFeeCredit = 'BorderFeeCredit';
	// @var string $FeeSearchableMobileDE
	var $FeeSearchableMobileDE = 'FeeSearchableMobileDE';
	// @var string $SalesReportsPlusFee
	var $SalesReportsPlusFee = 'SalesReportsPlusFee';
	// @var string $SalesReportsPlusCredit
	var $SalesReportsPlusCredit = 'SalesReportsPlusCredit';
	// @var string $CreditSearchableMobileDE
	var $CreditSearchableMobileDE = 'CreditSearchableMobileDE';
	// @var string $EmailMarketingFee
	var $EmailMarketingFee = 'EmailMarketingFee';
	// @var string $EmailMarketingCredit
	var $EmailMarketingCredit = 'EmailMarketingCredit';
	// @var string $FeePictureShow
	var $FeePictureShow = 'FeePictureShow';
	// @var string $CreditPictureShow
	var $CreditPictureShow = 'CreditPictureShow';
	// @var string $ProPackBundleFee
	var $ProPackBundleFee = 'ProPackBundleFee';
	// @var string $ProPackBundleFeeCredit
	var $ProPackBundleFeeCredit = 'ProPackBundleFeeCredit';
	// @var string $BasicUpgradePackBundleFee
	var $BasicUpgradePackBundleFee = 'BasicUpgradePackBundleFee';
	// @var string $BasicUpgradePackBundleFeeCredit
	var $BasicUpgradePackBundleFeeCredit = 'BasicUpgradePackBundleFeeCredit';
	// @var string $ValuePackBundleFee
	var $ValuePackBundleFee = 'ValuePackBundleFee';
	// @var string $ValuePackBundleFeeCredit
	var $ValuePackBundleFeeCredit = 'ValuePackBundleFeeCredit';
	// @var string $ProPackPlusBundleFee
	var $ProPackPlusBundleFee = 'ProPackPlusBundleFee';
	// @var string $ProPackPlusBundleFeeCredit
	var $ProPackPlusBundleFeeCredit = 'ProPackPlusBundleFeeCredit';
	// @var string $FinalEntry
	var $FinalEntry = 'FinalEntry';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// @var string $ExtendedDurationFee
	var $ExtendedDurationFee = 'ExtendedDurationFee';
	// @var string $ExtendedDurationFeeCredit
	var $ExtendedDurationFeeCredit = 'ExtendedDurationFeeCredit';
	// @var string $InternationalListingFee
	var $InternationalListingFee = 'InternationalListingFee';
	// @var string $InternationalListingCredit
	var $InternationalListingCredit = 'InternationalListingCredit';
	// @var string $MarketplaceResearchExpiredSubscriptionFee
	var $MarketplaceResearchExpiredSubscriptionFee = 'MarketplaceResearchExpiredSubscriptionFee';
	// @var string $MarketplaceResearchExpiredSubscriptionFeeCredit
	var $MarketplaceResearchExpiredSubscriptionFeeCredit = 'MarketplaceResearchExpiredSubscriptionFeeCredit';
	// @var string $MarketplaceResearchBasicSubscriptionFee
	var $MarketplaceResearchBasicSubscriptionFee = 'MarketplaceResearchBasicSubscriptionFee';
	// @var string $MarketplaceResearchBasicSubscriptionFeeCredit
	var $MarketplaceResearchBasicSubscriptionFeeCredit = 'MarketplaceResearchBasicSubscriptionFeeCredit';
	// @var string $MarketplaceResearchProSubscriptionFee
	var $MarketplaceResearchProSubscriptionFee = 'MarketplaceResearchProSubscriptionFee';
	// @var string $BasicBundleFee
	var $BasicBundleFee = 'BasicBundleFee';
	// @var string $BasicBundleFeeCredit
	var $BasicBundleFeeCredit = 'BasicBundleFeeCredit';
	// @var string $MarketplaceResearchProSubscriptionFeeCredit
	var $MarketplaceResearchProSubscriptionFeeCredit = 'MarketplaceResearchProSubscriptionFeeCredit';
	// @var string $VehicleLocalSubscriptionFee
	var $VehicleLocalSubscriptionFee = 'VehicleLocalSubscriptionFee';
	// @var string $VehicleLocalSubscriptionFeeCredit
	var $VehicleLocalSubscriptionFeeCredit = 'VehicleLocalSubscriptionFeeCredit';
	// @var string $VehicleLocalInsertionFee
	var $VehicleLocalInsertionFee = 'VehicleLocalInsertionFee';
	// @var string $VehicleLocalInsertionFeeCredit
	var $VehicleLocalInsertionFeeCredit = 'VehicleLocalInsertionFeeCredit';
	// @var string $VehicleLocalFinalValueFee
	var $VehicleLocalFinalValueFee = 'VehicleLocalFinalValueFee';
	// @var string $VehicleLocalFinalValueFeeCredit
	var $VehicleLocalFinalValueFeeCredit = 'VehicleLocalFinalValueFeeCredit';
	// @var string $VehicleLocalGTCFee
	var $VehicleLocalGTCFee = 'VehicleLocalGTCFee';
	// @var string $VehicleLocalGTCFeeCredit
	var $VehicleLocalGTCFeeCredit = 'VehicleLocalGTCFeeCredit';
	// @var string $eBayMotorsProFee
	var $eBayMotorsProFee = 'eBayMotorsProFee';
	// @var string $CrediteBayMotorsProFee
	var $CrediteBayMotorsProFee = 'CrediteBayMotorsProFee';
	// @var string $eBayMotorsProFeatureFee
	var $eBayMotorsProFeatureFee = 'eBayMotorsProFeatureFee';
	// @var string $CrediteBayMotorsProFeatureFee
	var $CrediteBayMotorsProFeatureFee = 'CrediteBayMotorsProFeatureFee';
	// @var string $FeeGalleryPlus
	var $FeeGalleryPlus = 'FeeGalleryPlus';
	// @var string $CreditGalleryPlus
	var $CreditGalleryPlus = 'CreditGalleryPlus';
	// @var string $PrivateListing
	var $PrivateListing = 'PrivateListing';
	// @var string $CreditPrivateListing
	var $CreditPrivateListing = 'CreditPrivateListing';
	// @var string $ImmoProFee
	var $ImmoProFee = 'ImmoProFee';
	// @var string $CreditImmoProFee
	var $CreditImmoProFee = 'CreditImmoProFee';
	// @var string $ImmoProFeatureFee
	var $ImmoProFeatureFee = 'ImmoProFeatureFee';
	// @var string $CreditImmoProFeatureFee
	var $CreditImmoProFeatureFee = 'CreditImmoProFeatureFee';
	// @var string $RealEstateProFee
	var $RealEstateProFee = 'RealEstateProFee';
	// @var string $CreditRealEstateProFee
	var $CreditRealEstateProFee = 'CreditRealEstateProFee';
	// @var string $RealEstateProFeatureFee
	var $RealEstateProFeatureFee = 'RealEstateProFeatureFee';
	// @var string $CreditRealEstateProFeatureFee
	var $CreditRealEstateProFeatureFee = 'CreditRealEstateProFeatureFee';
	// end props

/**
 *

 * @return 
 */
	function AccountDetailEntryCodeType()
	{
		$this->EbatNs_FacetType('AccountDetailEntryCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_AccountDetailEntryCodeType = new AccountDetailEntryCodeType();

?>