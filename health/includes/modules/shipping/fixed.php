<?php
/*
  $Id: fixed.php,v 1.1.1.1 2005/12/03 21:36:12 max Exp $
  by D. M. Gremlin

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class fixed {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function fixed() {

      global $order;
      $this->code = 'fixed';
      $this->title = MODULE_SHIPPING_FIXED_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FIXED_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_FIXED_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_FIXED_TAX_CLASS;
      $this->enabled = $this->is_enabled();

// Enable Individual Shipping Module
      //$this->enabled = MODULE_SHIPPING_FIXED_STATUS;
      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_FIXED_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_FIXED_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

// class methods

  function quote($method = '')
  {
    global $order;

    $module = null;
    $quotes = null;

    if($order->delivery['country_id'] === STORE_COUNTRY)
      $module = MODULE_SHIPPING_FIXED_DOMESTIC_TITLE;
    else
      $module = MODULE_SHIPPING_FIXED_GLOBAL_TITLE;

    $shipping = $this->calculateShipping();
    $insurance = $this->calculateInsurance();

    if($shipping < 0)
    {
          $quotes = array('id' => $this->code,
                  'module' => $module,
                  'error' => MODULE_SHIPPING_FIXED_SHIPPING_NOT_SET_ERROR);
    }
    else
    {
      $currencies = new currencies();

      $insuranceOption = $this->getInsuranceOption();

      // Sets $methods array
      if( $insuranceOption == '0' && $insurance == 0) // not offered
      {
        $quotes = array('id' => $this->code,
                                'module' => $module,
                                'methods' => array(array('id' => 'uninsured',
                                                         'title' => MODULE_SHIPPING_FIXED_SANDH,
                                                         'cost' => $shipping)));
      }
      else if( $insuranceOption == '1' )  // optional
      {
        if($method == 'uninsured')    // this is AFTER selection
        {
          $quotes = array('id' => $this->code,
                                  'module' => $module,
                                  'methods' => array(array('id' => 'uninsured',
                                                         'title' => MODULE_SHIPPING_FIXED_SANDH_NO_INSURANCE,
                                                         'cost' => $shipping)));
        }
        else if($method == 'insured' )  // this is AFTER selection
        {
          $quotes = array('id' => $this->code,
                                  'module' => $module,
                                  'methods' => array(array('id' => 'insured',
                                                         'title' => MODULE_SHIPPING_FIXED_SANDH_AND_INSURANCE,
                                                         'cost' => $shipping + $insurance)));
        }
        else
        {
          // we want all of them to choose from
          $methods[] = array('id' => 'insured',
                                       'title' => MODULE_SHIPPING_FIXED_SANDH_AND_INSURANCE,
                                       'cost' => $shipping + $insurance);

          $methods[] = array('id' => 'uninsured',
                                       'title' => MODULE_SHIPPING_FIXED_SANDH_NO_INSURANCE,
                                       'cost' => $shipping);

          $quotes = array('id' => $this->code,
                                  'module' => $module,
                                  'methods' => $methods);
        }
      }
      else if( $insuranceOption == '3')    // included in S&H
      {
        $quotes = array('id' => $this->code,
                                'module' => $module,
                                'methods' => array(array('id' => 'insured',
                                                         'title' => MODULE_SHIPPING_FIXED_SANDH_AND_INSURANCE,
                                                         'cost' => $shipping)));
      }
      else  // required
      {
        $quotes = array('id' => $this->code,
                                'module' => $module,
                                'methods' => array(array('id' => 'insured',
                                                         'title' => MODULE_SHIPPING_FIXED_SANDH_AND_INSURANCE,
                                                         'cost' => $shipping + $insurance)));
      }


      if ($this->tax_class > 0)
      {
            $quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
    }

        if (tep_not_null($this->icon)) $quotes['icon'] = tep_image($this->icon, $this->title);
        return $quotes;
  }

  function calculateShipping()
  {
    global $cart;
    
    $shipping = $this->calculateShippingForAuctionItemsOnly();
    $shipping += $this->calculateShippingForStoreItemsOnly();
    
    if($cart->count_contents() > 1 && is_numeric(MODULE_SHIPPING_FIXED_DISCOUNT))
    {
      $shipping = $shipping * ((100 - MODULE_SHIPPING_FIXED_DISCOUNT) / 100);
    }

    return $shipping;
  }

  function calculateShippingForAuctionItemsOnly()
  {
    global $cart, $order;

    $shipping = 0.0;
    foreach ($cart->internal_get_auction_products() as $key => $item)
    {
      // Must be an auction item.  
      if($item['shipping_type'] === 'E')
      {
        // eBay handles shipping totals and accts for global vs. domestic
        $shipping += $item['shipping'];
      }
      else if($item['shipping_type'] === 'F' || $item['shipping_type'] === 'N')
      {
        // we have to know whether global vs. domestic
        if( $order->delivery['country_id'] == STORE_COUNTRY )
        {
          $shipping = $item['shipping'];
        }
        else
        {
          $shipping = $item['shipping_global'];
        }
      }
    }

    return $shipping;
  }

  function calculateShippingForStoreItemsOnly()
  {
    global $cart, $order;

    foreach ($cart->internal_get_store_products() as $key => $item)
    {
      // This will remove any product attributes.  Our theory is
      // that attributes do not affect shipping price.
      $tokens = explode('{', $item['id']);
      $ids[] = $tokens[0];
    }

    $shipping = 0.0;
    if(is_array($ids) && sizeof($ids > 0))
    {
      $shipping_query_raw = "SELECT pfs.*" .
                  " FROM " . TABLE_ABX_FIXED_SHIPPING . " pfs" .
                  " WHERE pfs.products_id in (" . implode(",", $ids) . ")" .
                  " ORDER BY pfs.shipping DESC";

      $shipping_query = tep_db_query($shipping_query_raw);
      while($shipping_query_array = tep_db_fetch_array($shipping_query))
      {
        $item_shipping = 0.0;
        $item_shipping_additional = 0.0;

        if($shipping_query_array === false)
        {
          return -1;
        }

        if($shipping_query_array['is_enabled'] !== '1')
        {
          // Fixed shipping not enabled for this product
          return -1;
        }

        // Checks to see if the delivery address is domestic
        //   This avoid problems where they are global, but want it shipped domestic,
        //    or vice versa.
        if( $order->delivery['country_id'] == STORE_COUNTRY )
        {
          $item_shipping = $shipping_query_array['shipping'];
          $item_shipping_additional = $shipping_query_array['shipping_addl'];
        }
        else
        {
          $item_shipping = $shipping_query_array['shipping_global'];
          $item_shipping_additional = $shipping_query_array['shipping_global_addl'];
        }

        // Need to handle quantities when dealing with store items
         if ($shipping == 0.0)
        { $shipping += ($item_shipping + (($item['quantity'] - 1) * $item_shipping_additional)); }
        else
        { $shipping += ($item['quantity'] * $item_shipping_additional); }
      }
    }
    return $shipping;
  }



  function calculateInsurance()
  {
    global $cart, $order;

    $insurance = 0.0;

    if($this->containsStoreItems($cart->get_products()))
    {
      // Use calculated insurance for all items including auction items
      $total = 0.0;
      foreach($cart->get_products() as $key => $item)
      {
        $total += $item['quantity'] * $item['final_price'];
      }

      if($total >= MODULE_SHIPPING_FIXED_COST_TO_INSURE_MINIMUM)
        $insurance = MODULE_SHIPPING_FIXED_INSURANCE_BASE_AMT;

      // Don't want to double-charge if the minimum amt is 0
      if(MODULE_SHIPPING_FIXED_COST_TO_INSURE_MINIMUM < MODULE_SHIPPING_FIXED_COST_TO_INSURE_MULTIPLE)
        $total -= MODULE_SHIPPING_FIXED_COST_TO_INSURE_MULTIPLE;
      else
        $total -= MODULE_SHIPPING_FIXED_COST_TO_INSURE_MINIMUM;

      $multiple = (int)($total / MODULE_SHIPPING_FIXED_COST_TO_INSURE_MULTIPLE);
      $insurance += $multiple * MODULE_SHIPPING_FIXED_INSURANCE_MULTIPLE_AMT;
    }
    else
    {
      // Use eBay style fixed insurance costs
      foreach($cart->get_products() as $key => $item)
      {
         $insurance += $item['insurance'];
      }
    }

    return $insurance;
  }

  function containsStoreItems($products)
  {
    foreach ($products as $key => $item)
    {
      if(!isset($item['is_auction_item']))
      {
        return true;
      }
    }
  }

  function containsAuctionItems($products)
  {
    foreach ($products as $key => $item)
    {
      if(isset($item['is_auction_item']) && $item['is_auction_item'] = true)
      {
        return true;
      }
    }
  }

  function getInsuranceOption()
  {
    global $cart;
    foreach($cart->get_products() as $key => $item)
    {
      if(isset($item['insurance_option']))
      {
        return $item['insurance_option'];
      }
    }
    return "1"; // optional - default for store items
  }

  function is_enabled()
  {
    if (MODULE_SHIPPING_FIXED_STATUS_STORE == 'True')
      return true;

    return false;
  }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FIXED_STATUS_STORE'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
    
      // add discount 
      // add enable fixed vs store shipping when store item are added to cart
    
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shopping Cart Orders', 'MODULE_SHIPPING_FIXED_STATUS_STORE', 'False', 'Do you wish to offer fixed shipping prices for standard shopping cart orders that contain no eBay items?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('eBay Orders', 'MODULE_SHIPPING_FIXED_EBAY_WITH_STORE', 'False', 'Do you wish to offer fixed shipping prices for eBay orders that contain additional shopping cart items?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Shipping Modules', 'MODULE_SHIPPING_FIXED_DISABLE_OTHERS', 'False', 'Do you wish to disable all other shipping methods when Fixed Shipping is enabled?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_FIXED_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_FIXED_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_FIXED_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Multiple Item Discount', 'MODULE_SHIPPING_FIXED_DISCOUNT', '0', 'Please enter the percentage discount that you offer to customers that purchase multiple items.<br>Ex: 20% = 20', '6', '0', now())");
     
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cost Minimum', 'MODULE_SHIPPING_FIXED_COST_TO_INSURE_MINIMUM', '0', 'Minimum product cost for which insurance is charged.', '6', '0', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cost Multiple', 'MODULE_SHIPPING_FIXED_COST_TO_INSURE_MULTIPLE', '50', 'Increment of product cost for which to charge additional insurance.', '6', '0', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Base Insurance', 'MODULE_SHIPPING_FIXED_INSURANCE_BASE_AMT', '2.20', 'Minimum insurance amount to charge.', '6', '0', now())");
     tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Addl Insurance', 'MODULE_SHIPPING_FIXED_INSURANCE_MULTIPLE_AMT', '1.30', 'Additional insurance amount to charge.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_FIXED_STATUS_STORE', 'MODULE_SHIPPING_FIXED_EBAY_WITH_STORE', 'MODULE_SHIPPING_FIXED_DISABLE_OTHERS', 'MODULE_SHIPPING_FIXED_TAX_CLASS', 'MODULE_SHIPPING_FIXED_ZONE', 'MODULE_SHIPPING_FIXED_SORT_ORDER', 'MODULE_SHIPPING_FIXED_DISCOUNT', 'MODULE_SHIPPING_FIXED_COST_TO_INSURE_MINIMUM', 'MODULE_SHIPPING_FIXED_COST_TO_INSURE_MULTIPLE', 'MODULE_SHIPPING_FIXED_INSURANCE_BASE_AMT', 'MODULE_SHIPPING_FIXED_INSURANCE_MULTIPLE_AMT');
    }
  }
?>