<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PictureSetCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Standard
	var $Standard = 'Standard';
	// @var string $Supersize
	var $Supersize = 'Supersize';
	// @var string $Large
	var $Large = 'Large';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PictureSetCodeType()
	{
		$this->EbatNs_FacetType('PictureSetCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PictureSetCodeType = new PictureSetCodeType();

?>
