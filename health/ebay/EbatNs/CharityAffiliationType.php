<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class CharityAffiliationType extends EbatNs_ComplexType
{
	// start props
	// end props

/**
 *

 * @return 
 */
	function CharityAffiliationType()
	{
		$this->EbatNs_ComplexType('CharityAffiliationType', 'urn:ebay:apis:eBLBaseComponents');
	$this->_attributes = array_merge($this->_attributes,
		array(
			'id' =>
			array(
				'name' => 'id',
				'type' => 'string',
				'use' => 'required'
			),
			'type' =>
			array(
				'name' => 'type',
				'type' => 'CharityAffiliationTypeCodeType',
				'use' => 'required'
			)
		));

	}
}
?>
