<?php
/*
  $Id: array_ext.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
	function array_prune($inArray)
	{
    	reset($inArray);

		$retArray = Array();
		foreach ($inArray as $key => $value) 
		{
			if( $value != null && strlen($value) > 0)
			{
				$retArray[$key] = $value;
			}
		}
		return $retArray;
	}

?>