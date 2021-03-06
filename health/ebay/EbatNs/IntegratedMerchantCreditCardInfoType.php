<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';
require_once 'SiteCodeType.php';

class IntegratedMerchantCreditCardInfoType extends EbatNs_ComplexType
{
	// start props
	// @var SiteCodeType $AvailableSite
	var $AvailableSite;
	// end props

/**
 *

 * @return SiteCodeType
 */
	function getAvailableSite()
	{
		return $this->AvailableSite;
	}
/**
 *

 * @return void
 * @param  $value 
 */
	function setAvailableSite($value)
	{
		$this->AvailableSite = $value;
	}
/**
 *

 * @return 
 */
	function IntegratedMerchantCreditCardInfoType()
	{
		$this->EbatNs_ComplexType('IntegratedMerchantCreditCardInfoType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'AvailableSite' =>
				array(
					'required' => false,
					'type' => 'SiteCodeType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => false,
					'cardinality' => '0..1'
				)
			));

	}
}
?>
