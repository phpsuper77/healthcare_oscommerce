<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'SearchStandingDashboardType.php';
require_once 'SellerFeeDiscountDashboardType.php';
require_once 'BuyerSatisfactionDashboardType.php';
require_once 'SellerAccountDashboardType.php';
require_once 'PolicyComplianceDashboardType.php';
require_once 'PowerSellerDashboardType.php';
require_once 'AbstractResponseType.php';

class GetSellerDashboardResponseType extends AbstractResponseType
{
	// start props
	// @var SearchStandingDashboardType $SearchStanding
	var $SearchStanding;
	// @var SellerFeeDiscountDashboardType $SellerFeeDiscount
	var $SellerFeeDiscount;
	// @var PowerSellerDashboardType $PowerSellerStatus
	var $PowerSellerStatus;
	// @var PolicyComplianceDashboardType $PolicyCompliance
	var $PolicyCompliance;
	// @var BuyerSatisfactionDashboardType $BuyerSatisfaction
	var $BuyerSatisfaction;
	// @var SellerAccountDashboardType $SellerAccount
	var $SellerAccount;
	// end props

/**
 *

 * @return SearchStandingDashboardType
 */
	function getSearchStanding()
	{
		return $this->SearchStanding;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSearchStanding($value)
	{
		$this->SearchStanding = $value;
	}
/**
 *

 * @return SellerFeeDiscountDashboardType
 */
	function getSellerFeeDiscount()
	{
		return $this->SellerFeeDiscount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerFeeDiscount($value)
	{
		$this->SellerFeeDiscount = $value;
	}
/**
 *

 * @return PowerSellerDashboardType
 */
	function getPowerSellerStatus()
	{
		return $this->PowerSellerStatus;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPowerSellerStatus($value)
	{
		$this->PowerSellerStatus = $value;
	}
/**
 *

 * @return PolicyComplianceDashboardType
 */
	function getPolicyCompliance()
	{
		return $this->PolicyCompliance;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setPolicyCompliance($value)
	{
		$this->PolicyCompliance = $value;
	}
/**
 *

 * @return BuyerSatisfactionDashboardType
 */
	function getBuyerSatisfaction()
	{
		return $this->BuyerSatisfaction;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setBuyerSatisfaction($value)
	{
		$this->BuyerSatisfaction = $value;
	}
/**
 *

 * @return SellerAccountDashboardType
 */
	function getSellerAccount()
	{
		return $this->SellerAccount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setSellerAccount($value)
	{
		$this->SellerAccount = $value;
	}
/**
 *

 * @return 
 */
	function GetSellerDashboardResponseType()
	{
		$this->AbstractResponseType('GetSellerDashboardResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'SearchStanding' =>
				array(
					'required' => false,
					'type' => 'SearchStandingDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerFeeDiscount' =>
				array(
					'required' => false,
					'type' => 'SellerFeeDiscountDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PowerSellerStatus' =>
				array(
					'required' => false,
					'type' => 'PowerSellerDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'PolicyCompliance' =>
				array(
					'required' => false,
					'type' => 'PolicyComplianceDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'BuyerSatisfaction' =>
				array(
					'required' => false,
					'type' => 'BuyerSatisfactionDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'SellerAccount' =>
				array(
					'required' => false,
					'type' => 'SellerAccountDashboardType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
