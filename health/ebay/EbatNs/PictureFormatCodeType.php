<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PictureFormatCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $JPG
	var $JPG = 'JPG';
	// @var string $GIF
	var $GIF = 'GIF';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PictureFormatCodeType()
	{
		$this->EbatNs_FacetType('PictureFormatCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PictureFormatCodeType = new PictureFormatCodeType();

?>
