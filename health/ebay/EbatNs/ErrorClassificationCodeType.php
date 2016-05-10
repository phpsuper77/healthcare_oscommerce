<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class ErrorClassificationCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $RequestError
	var $RequestError = 'RequestError';
	// @var string $SystemError
	var $SystemError = 'SystemError';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function ErrorClassificationCodeType()
	{
		$this->EbatNs_FacetType('ErrorClassificationCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_ErrorClassificationCodeType = new ErrorClassificationCodeType();

?>
