<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_ComplexType.php';

class CategoryMappingType extends EbatNs_ComplexType
{
	// start props
	// end props

/**
 *

 * @return 
 */
	function CategoryMappingType()
	{
		$this->EbatNs_ComplexType('CategoryMappingType', 'http://www.w3.org/2001/XMLSchema');
	$this->_attributes = array_merge($this->_attributes,
		array(
			'oldID' =>
			array(
				'name' => 'oldID',
				'type' => 'string',
				'use' => 'required'
			),
			'id' =>
			array(
				'name' => 'id',
				'type' => 'string',
				'use' => 'required'
			)
		));

	}
}
?>