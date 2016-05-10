<?php
/*
  $Id: super_shipping.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
  require_once(DIR_WS_CLASSES . 'auction_helper.php');
  require_once(DIR_WS_CLASSES . 'shipping.php');
  
  class superShipping extends shipping 
  {
	function quote($method = '', $module = '')
	{

		global $order, $cart, $language;
		if( $this->uses_fixed_shipping($cart->get_products()))
		{
			if($this->requires_fixed_shipping_only($cart->get_products()))
			{
	            $include_modules[] = array('class'=> 'fixed', 'file' => 'fixed.php');
		        require_once(DIR_WS_LANGUAGES . $language . '/modules/shipping/' . $include_modules[0]['file']);
        		require_once(DIR_WS_MODULES . 'shipping/' . $include_modules[0]['file']);

		        $GLOBALS[$include_modules[0]['class']] = new $include_modules[0]['class'];
				$quotes_array[] = $GLOBALS[$include_modules[0]['class']]->quote($method, $module);
				return $quotes_array;
			}
			else
				return parent::quote($method, $module);
		}
		else
		{
			return parent::quote($method, $module);
		}
	}

	function requires_fixed_shipping_only($products)
	{
		if(MODULE_SHIPPING_FIXED_DISABLE_OTHERS === 'True' && MODULE_SHIPPING_FIXED_STATUS_STORE === 'True')
			return true;
			
		foreach($products as $key => $product)
		{
			if(isset($product['shipping_type']) && $product['shipping_type'] === 'F')
			{
				// At least one auction item uses fixed shipping,
				// so use fixed shipping for ALL items
				return true;
			}
		}
		return false;
	}
	
	function uses_fixed_shipping($products)
	{
		if(MODULE_SHIPPING_FIXED_STATUS_STORE === 'True')
		{
			// Allows the use of fixed shipping
			return true;
		}
		else if( $this->requires_fixed_shipping_only($products))
		{
			return true;
		}
		
		return false;
	}
	
    function cheapest() {
		return false;
	}
  }
?>
