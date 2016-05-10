<?php
// autogenerated file 23.02.2007 11:57
// $Id$
// $Log$
//
require_once 'EbatNs_ComplexType.php';
require_once 'EbatNsCsSetExt_ValType.php';

class EbatNsCsSetExt_ValueListType extends EbatNs_ComplexType
{
	// start props
	// @var EbatNsCsSetExt_ValType $Value
	var $Value;
	// end props

/**
 *

 * @return EbatNsCsSetExt_ValType
 * @param  $index 
 */
	function getValue($index = null)
	{
		if ($index) {
		return $this->Value[$index];
	} else {
		return $this->Value;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setValue($value, $index = null)
	{
		if ($index) {
	$this->Value[$index] = $value;
	} else {
	$this->Value = $value;
	}

	}
/**
 *

 * @return 
 */
	function EbatNsCsSetExt_ValueListType()
	{
		$this->EbatNs_ComplexType('EbatNsCsSetExt_ValueListType', 'http://www.w3.org/2001/XMLSchema');
		$this->_elements = array_merge($this->_elements,
			array(
				'Value' =>
				array(
					'required' => true,
					'type' => 'EbatNsCsSetExt_ValType',
					'nsURI' => 'http://www.intradesys.com/Schemas/ebay/AttributeData_Extension.xsd',
					'array' => true,
					'cardinality' => '1..*'
				)
			));
	$this->_attributes = array_merge($this->_attributes,
		array(
			'count' =>
			array(
				'name' => 'count',
				'type' => 'int',
				'use' => 'required'
			)
		));

	}
}
?>
