<?php
// autogenerated file 29.05.2009 15:17
// $Id: $
// $Log: $
//
//
require_once 'EbatNs_FacetType.php';

class PictureManagerPictureDisplayTypeCodeType extends EbatNs_FacetType
{
	// start props
	// @var string $Thumbnail
	var $Thumbnail = 'Thumbnail';
	// @var string $BIBO
	var $BIBO = 'BIBO';
	// @var string $Standard
	var $Standard = 'Standard';
	// @var string $Large
	var $Large = 'Large';
	// @var string $Supersize
	var $Supersize = 'Supersize';
	// @var string $Original
	var $Original = 'Original';
	// @var string $CustomCode
	var $CustomCode = 'CustomCode';
	// end props

/**
 *

 * @return 
 */
	function PictureManagerPictureDisplayTypeCodeType()
	{
		$this->EbatNs_FacetType('PictureManagerPictureDisplayTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');

	}
}

$Facet_PictureManagerPictureDisplayTypeCodeType = new PictureManagerPictureDisplayTypeCodeType();

?>
