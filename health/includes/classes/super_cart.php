<?php
/*
  $Id: super_cart.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/

  require_once(DIR_WS_CLASSES . 'shopping_cart.php');
  require_once(DIR_WS_CLASSES . 'auction_helper.php');

  class superCart extends shoppingCart 
  {
    var $email;
	
    function superCart($email_address = '')
	{
		$this->email = $email_address;
    	parent::shoppingCart();
	}

	// This function does not ALWAYS set the order id, but at least it updates the status
	// to checked out
    function reset($reset_database = false, $order_id = 0) 
	{
		parent::reset($reset_database);
		
		if($reset_database == true)
		{
			// Mark the items in the shopping cart completed.
			$auctionHelper = new auctionHelper();
			foreach($this->internal_get_auction_products() as $key => $auction)
		    {
				$tempProduct = array('ID' => $auction['auction_basket_id'],
		                          	'statusID' => $auctionHelper->CHECKEDOUT,
								  	'ordersID' => $order_id);

				$auctionHelper->update(null, $tempProduct);
			}
		}
    }	
	
	function internal_get_auction_products()
	{
	  if (!isset($this->email) || strlen($this->email) == 0)
	  {
	      global $g_auction_email_address;
		  if (isset($g_auction_email_address) && strlen($g_auction_email_address) > 0)
		  {
			  $this->email = $g_auction_email_address;
		  }
		  else
		  {
		  	  global $customer_id;
			  if(isset($customer_id) && $customer_id >= 0)
			  {
				  $customer_query = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = $customer_id");
			      $customer = tep_db_fetch_array($customer_query);
				  $this->email = $customer['customers_email_address'];
			  }
		  }
	  }
 
	  $auctionHelper = new auctionHelper;
	  $auctions = $auctionHelper->getWonAuctionListings($this->email, 0);	  
	  
      $products_array = array();
	  if (is_array($auctions))
	  {
		  global $languages_id;
		  foreach($auctions as $key => $auction)
		  {
        	    $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$auction['product_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
		        if ($products = tep_db_fetch_array($products_query)) 
				{
        		  $products_array[] = array('id' => $products['products_id'],
                	                    'name' => $auction['ext_title'] . ' (eBay# ' . $auction['ext_id'] . ')',
                    	                'model' => $products['products_model'],
                        	            'image' => $products['products_image'],
                            	        'price' => $auction['price_end'],
                                	    'quantity' => $auction['qty'],
	                                    'weight' => $products['products_weight'],
	//TODO FIX ATTRIBUTES               'final_price' => ($products_price + $this->attributes_price($products_id)),
    	                                'final_price' => $auction['price_end'],
        	                            'tax_class_id' => $products['products_tax_class_id'],
										'shipping_type' => $auction['shipping_type'],
										'shipping' => $auction['shipping'],
										'shipping_additional' => $auction['shipping_additional'],
										'insurance' => $auction['insurance'],
										'insurance_option' => $auction['insurance_option'],
										'insurance_per_item' => $auction['insurance_per_item'],
										'is_auction_item' => true,
										'auction_basket_id' => $auction['id'],
										'item_url' => $auction['item_url'],
            	   						'attributes' => array());
	//TODO FIX ATTRIBUTES               'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''));
				}
				else
				{
					// product ID not set
					// We can't use weight based shipping since we don't know the weight
        			$products_array[] = array('id' => $auction['ext_id'],
                	                    'name' => $auction['ext_title'] . ' (eBay# ' . $auction['ext_id'] . ')',
                    	                'model' => '',
                        	            'image' => 'auctionlogo1.gif',
                            	        'price' => $auction['price_end'],
                                	    'quantity' => $auction['qty'],
	                                    'weight' => 999,
	//TODO FIX ATTRIBUTES               'final_price' => ($products_price + $this->attributes_price($products_id)),
    	                                'final_price' => $auction['price_end'],
        	                            'tax_class_id' => 0,
										'shipping_type' => $auction['shipping_type'],
										'shipping' => $auction['shipping'],
										'shipping_additional' => $auction['shipping_additional'],
										'insurance' => $auction['insurance'],
										'insurance_option' => $auction['insurance_option'],
										'insurance_per_item' => $auction['insurance_per_item'],
										'is_auction_item' => true,
										'auction_basket_id' => $auction['id'],
										'item_url' => $auction['item_url'],
										'attributes' => array());
	//TODO FIX ATTRIBUTES               'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''));
			}
		 }
      }
	  
	  return $products_array;
	}
	
	function internal_get_store_products()
	{
	  // Fix for bad programming in shopping cart
	  return parent::get_products();
    }	
	
	
	function get_products()
	{
	  $store_products_array = $this->internal_get_store_products();
	  // Fix for bad programming in shopping cart
	  if( $store_products_array === false )
		  $store_products_array = array();

	  $auction_products_array = $this->internal_get_auction_products();
	  
      return array_merge($auction_products_array, $store_products_array);
    }	
	
    function count_contents() // get total number of items in cart 
	{
		$count = 0;
		foreach($this->get_products() as $key => $product)
		{
			$count += $product['quantity'];
		}
		return $count;
	}
	
	function count_auction_contents()
	{
		$count = 0;
		foreach($this->internal_get_auction_products() as $key => $product)
		{
			$count += $product['quantity'];
		}
		return $count;	
	}
	
    function calculate() 
	{
		parent::calculate();
		
		$auctionProducts = $this->internal_get_auction_products();
        if (!is_array($auctionProducts)) return 0;
		
		reset($auctionProducts);
		foreach($auctionProducts as $key => $auctionProduct)
		{
        	$qty = $auctionProduct['quantity'];
//          $prid = $product['products_id'];
			$products_tax = tep_get_tax_rate($auctionProduct['tax_class_id']);
			$products_price = $auctionProduct['price'];
			$products_weight = $auctionProduct['weight'];

			$this->total += tep_add_tax($products_price, $products_tax) * $qty;
			$this->weight += ($qty * $products_weight);

/*TODO ATTRIBUTES
  // attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          reset($this->contents[$products_id]['attributes']);
          while (list($option, $value) = each($this->contents[$products_id]['attributes'])) {
            $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$prid . "' and options_id = '" . (int)$option . "' and options_values_id = '" . (int)$value . "'");
            $attribute_price = tep_db_fetch_array($attribute_price_query);
            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            } else {
              $this->total -= $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
            }
          }
        }
*/      }
    }		
	
/*
    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function get_content_type() {
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        reset($this->contents);
        while (list($products_id, ) = each($this->contents)) {
          if (isset($this->contents[$products_id]['attributes'])) {
            reset($this->contents[$products_id]['attributes']);
            while (list(, $value) = each($this->contents[$products_id]['attributes'])) {
              $virtual_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad where pa.products_id = '" . (int)$products_id . "' and pa.options_values_id = '" . (int)$value . "' and pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = tep_db_fetch_array($virtual_check_query);

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'virtual';
                    break;
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                    break;
                  default:
                    $this->content_type = 'physical';
                    break;
                }
              }
            }
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
                break;
              default:
                $this->content_type = 'physical';
                break;
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
*/
  }
?>
