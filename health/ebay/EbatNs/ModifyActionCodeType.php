<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class ModifyActionCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Add
	var $Add = 'Add';
	// @var string $Delete
	var $Delete = 'Delete';
	// @var string $Update
	var $Update = 'Update';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function ModifyActionCodeType()
	{
		$this->EbatNs_FacetType('ModifyActionCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_ModifyActionCodeType = new ModifyActionCodeType();

?>