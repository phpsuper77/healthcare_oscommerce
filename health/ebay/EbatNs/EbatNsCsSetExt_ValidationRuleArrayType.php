<?php
// autogenerated file 23.02.2007 11:57
// $Id$
// $Log$
//
require_once 'EbatNs_ComplexType.php';
require_once 'EbatNsCsSetExt_ValidationRuleType.php';

class EbatNsCsSetExt_ValidationRuleArrayType extends EbatNs_ComplexType
{
	// start props
	// @var EbatNsCsSetExt_ValidationRuleType $Rule
	var $Rule;
	// end props

/**
 *

 * @return EbatNsCsSetExt_ValidationRuleType
 * @param  $index 
 */
	function getRule($index = null)
	{
		if ($index) {
		return $this->Rule[$index];
	} else {
		return $this->Rule;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setRule($value, $index = null)
	{
		if ($index) {
	$this->Rule[$index] = $value;
	} else {
	$this->Rule = $value;
	}

	}
/**
 *

 * @return 
 */
	function EbatNsCsSetExt_ValidationRuleArrayType()
	{
		$this->EbatNs_ComplexType('EbatNsCsSetExt_ValidationRuleArrayType', 'http://www.intradesys.com/Schemas/ebay/AttributeData_Extension.xsd');
		$this->_elements = array_merge($this->_elements,
			array(
				'Rule' =>
				array(
					'required' => true,
					'type' => 'EbatNsCsSetExt_ValidationRuleType',
					'nsURI' => 'http://www.intradesys.com/Schemas/ebay/AttributeData_Extension.xsd',
					'array' => true,
					'cardinality' => '1..*'
				)
			));

	}
}
?>
