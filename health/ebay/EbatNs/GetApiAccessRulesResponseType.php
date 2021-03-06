<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'ApiAccessRuleType.php';
require_once 'AbstractResponseType.php';

class GetApiAccessRulesResponseType extends AbstractResponseType
{
	// start props
	// @var ApiAccessRuleType $ApiAccessRule
	var $ApiAccessRule;
	// end props

/**
 *

 * @return ApiAccessRuleType
 * @param  $index 
 */
	function getApiAccessRule($index = null)
	{
		if ($index) {
		return $this->ApiAccessRule[$index];
	} else {
		return $this->ApiAccessRule;
	}

	}
/**
 *

 * @return void
 * @param  $value 
 * @param  $index 
 */
	function setApiAccessRule($value, $index = null)
	{
		if ($index) {
	$this->ApiAccessRule[$index] = $value;
	} else {
	$this->ApiAccessRule = $value;
	}

	}
/**
 *

 * @return 
 */
	function GetApiAccessRulesResponseType()
	{
		$this->AbstractResponseType('GetApiAccessRulesResponseType', 'urn:ebay:apis:eBLBaseComponents');
		$this->_elements = array_merge($this->_elements,
			array(
				'ApiAccessRule' =>
				array(
					'required' => false,
					'type' => 'ApiAccessRuleType',
					'nsURI' => 'urn:ebay:apis:eBLBaseComponents',
					'array' => true,
					'cardinality' => '0..*'
				)
			));

	}
}
?>
