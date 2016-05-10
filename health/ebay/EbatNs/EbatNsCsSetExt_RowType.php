<?php
// autogenerated file 23.02.2007 11:57
// $Id$
// $Log$
//
require_once 'EbatNs_ComplexType.php';
require_once 'EbatNsCsSetExt_WidgetType.php';

class EbatNsCsSetExt_RowType extends EbatNs_ComplexType
{
	// start props
	// @var EbatNsCsSetExt_WidgetType $Widget
	var $Widget;
	// end props

/**
 *

 * @return EbatNsCsSetExt_WidgetType
 * @param  $index 
 */
	function getWidget($index = null)
	{
		if ($index) {
		return $this->Widget[$index];
	} else {
		return $this->Widget;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setWidget($value, $index = null)
	{
		if ($index) {
	$this->Widget[$index] = $value;
	} else {
	$this->Widget = $value;
	}

	}
/**
 *

 * @return 
 */
	function EbatNsCsSetExt_RowType()
	{
		$this->EbatNs_ComplexType('EbatNsCsSetExt_RowType', 'http://www.intradesys.com/Schemas/ebay/AttributeData_Extension.xsd');
		$this->_elements = array_merge($this->_elements,
			array(
				'Widget' =>
				array(
					'required' => true,
					'type' => 'EbatNsCsSetExt_WidgetType',
					'nsURI' => 'http://www.intradesys.com/Schemas/ebay/AttributeData_Extension.xsd',
					'array' => true,
					'cardinality' => '1..*'
				)
			));

	}
}
?>
