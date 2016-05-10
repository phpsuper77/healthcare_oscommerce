<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'MinimumFeedbackScoreDetailsType.php';
require_once 'MaximumItemRequirementsDetailsType.php';
require_once 'EbatNs_ComplexType.php';
require_once 'VerifiedUserRequirementsDetailsType.php';
require_once 'MaximumBuyerPolicyViolationsDetailsType.php';
require_once 'MaximumUnpaidItemStrikesInfoDetailsType.php';

class SiteBuyerRequirementDetailsType extends EbatNs_ComplexType
{
	// start props
	// @var boolean $LinkedPayPalAccount
	var $LinkedPayPalAccount;
	// @var MaximumBuyerPolicyViolationsDetailsType $MaximumBuyerPolicyViolations
	var $MaximumBuyerPolicyViolations;
	// @var MaximumItemRequirementsDetailsType $MaximumItemRequirements
	var $MaximumItemRequirements;
	// @var MaximumUnpaidItemStrikesInfoDetailsType $MaximumUnpaidItemStrikesInfo
	var $MaximumUnpaidItemStrikesInfo;
	// @var MinimumFeedbackScoreDetailsType $MinimumFeedbackScore
	var $MinimumFeedbackScore;
	// @var boolean $ShipToRegistrationCountry
	var $ShipToRegistrationCountry;
	// @var VerifiedUserRequirementsDetailsType $VerifiedUserRequirements
	var $VerifiedUserRequirements;
	// end props

/**
 *

 * @return boolean
 */
	function getLinkedPayPalAccount()
	{
		return $this->LinkedPayPalAccount;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setLinkedPayPalAccount($value)
	{
		$this->LinkedPayPalAccount = $value;
	}
/**
 *

 * @return MaximumBuyerPolicyViolationsDetailsType
 */
	function getMaximumBuyerPolicyViolations()
	{
		return $this->MaximumBuyerPolicyViolations;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMaximumBuyerPolicyViolations($value)
	{
		$this->MaximumBuyerPolicyViolations = $value;
	}
/**
 *

 * @return MaximumItemRequirementsDetailsType
 */
	function getMaximumItemRequirements()
	{
		return $this->MaximumItemRequirements;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMaximumItemRequirements($value)
	{
		$this->MaximumItemRequirements = $value;
	}
/**
 *

 * @return MaximumUnpaidItemStrikesInfoDetailsType
 */
	function getMaximumUnpaidItemStrikesInfo()
	{
		return $this->MaximumUnpaidItemStrikesInfo;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMaximumUnpaidItemStrikesInfo($value)
	{
		$this->MaximumUnpaidItemStrikesInfo = $value;
	}
/**
 *

 * @return MinimumFeedbackScoreDetailsType
 */
	function getMinimumFeedbackScore()
	{
		return $this->MinimumFeedbackScore;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setMinimumFeedbackScore($value)
	{
		$this->MinimumFeedbackScore = $value;
	}
/**
 *

 * @return boolean
 */
	function getShipToRegistrationCountry()
	{
		return $this->ShipToRegistrationCountry;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setShipToRegistrationCountry($value)
	{
		$this->ShipToRegistrationCountry = $value;
	}
/**
 *

 * @return VerifiedUserRequirementsDetailsType
 */
	function getVerifiedUserRequirements()
	{
		return $this->VerifiedUserRequirements;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setVerifiedUserRequirements($value)
	{
		$this->VerifiedUserRequirements = $value;
	}
/**
 *

 * @return 
 */
	function SiteBuyerRequirementDetailsType()
	{
		$this->EbatNs_ComplexType('SiteBuyerRequirementDetailsType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'LinkedPayPalAccount' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MaximumBuyerPolicyViolations' =>
				array(
					'required' => false,
					'type' => 'MaximumBuyerPolicyViolationsDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MaximumItemRequirements' =>
				array(
					'required' => false,
					'type' => 'MaximumItemRequirementsDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MaximumUnpaidItemStrikesInfo' =>
				array(
					'required' => false,
					'type' => 'MaximumUnpaidItemStrikesInfoDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'MinimumFeedbackScore' =>
				array(
					'required' => false,
					'type' => 'MinimumFeedbackScoreDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				),
				'ShipToRegistrationCountry' =>
				array(
					'required' => false,
					'type' => 'boolean',
					'nsURI' => 'http://www.w3.org/2001/XMLSchema',
					'array' => false,
					'cardinality' => '0..1'
				),
				'VerifiedUserRequirements' =>
				array(
					'required' => false,
					'type' => 'VerifiedUserRequirementsDetailsType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
