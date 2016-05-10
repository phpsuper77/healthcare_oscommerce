<?php
/*
  $Id: order.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function order($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {
    // changed by Art. Add cc_cvn selection, orders_type
      $order_query = tep_db_query("select o.transaction_id, o.approval_code, o.customers_id, o.customers_name, o.customers_firstname, o.customers_lastname, o.customers_company, o.customers_street_address, o.customers_suburb, o.customers_city, o.customers_postcode, o.customers_state, o.customers_country, o.customers_telephone, o.customers_email_address, o.customers_address_format_id, o.delivery_name, o.delivery_firstname, o.delivery_lastname, o.delivery_company, o.delivery_street_address, o.delivery_suburb, o.delivery_city, o.delivery_postcode, o.delivery_state, o.delivery_country, o.delivery_address_format_id, o.billing_name, o.billing_firstname, o.billing_lastname, o.billing_company, o.billing_street_address, o.billing_suburb, o.billing_city, o.billing_postcode, o.billing_state, o.billing_country, o.billing_address_format_id, o.payment_method, o.payment_class, o.shipping_class, o.cc_type, o.cc_owner, o.cc_number, o.cc_cvn, o.cc_expires, o.orders_type, o.currency, o.currency_value, o.date_purchased, o.orders_status, o.last_modified, o.language_id, a.individual_id as orders_admin, a.individual_id as customers_admin from " . TABLE_ORDERS . " o left join " . TABLE_ADMIN . " a on a.admin_id=o.admin_id where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);

      $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text'],
                                'value' => $totals['value'],
                                'class' => $totals['class'],
                                'sort_order' => $totals['sort_order']);
      }

      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
                          // added by Art. Start
                          'cc_cvn' => $order['cc_cvn'],
                          'orders_type' => $order['orders_type'],
                          // added by Art. Stop
                          'tax_groups' => array(),
                          'order_admin' => (tep_not_null($order['orders_admin'])?$order['orders_admin']:TEXT_INTERNET),
                          'date_purchased' => $order['date_purchased'],
                          'orders_status' => $order['orders_status'],
                          'payment_class' => $order['payment_class'],
                          'shipping_class' => $order['shipping_class'],
                          'shipping_method' => $order['shipping_method'],
                          'language_id' => $order['language_id'],
                          'shipping_cost' => 0,
                          'subtotal' => 0,
                          'tax' => 0,
                          'tax_groups' => array(),
                          'transaction_id' => $order['transaction_id'],
                          'approval_code' => $order['approval_code'],
                          'last_modified' => $order['last_modified']);

      $country = get_country_info($order['customers_country'], $order['language_id']);
      $this->customer = array('customer_id' => $order['customers_id'],
                              'name' => $order['customers_name'],
                              'firstname' => $order['customers_firstname'],
                              'lastname' => $order['customers_lastname'],
                              'admin' => (tep_not_null($order['customers_admin'])?$order['customers_admin']:TEXT_INTERNET),
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $country,
                              'zone_id' => tep_get_zone_id($country['id'], $order['customers_state']),
                              'country_id' => $country['id'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address']);

      $country = get_country_info($order['delivery_country'], $order['language_id']);
      $this->delivery = array('name' => $order['delivery_name'],
                              'firstname' => $order['delivery_firstname'],
                              'lastname' => $order['delivery_lastname'],      
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $country,
                              'zone_id' => tep_get_zone_id($country['id'], $order['delivery_state']),
                              'country_id' => $country['id'],
                              'format_id' => $order['delivery_address_format_id']);

      $country = get_country_info($order['billing_country'], $order['language_id']);
      $this->billing = array('name' => $order['billing_name'],
                             'firstname' => $order['billing_firstname'],
                             'lastname' => $order['billing_lastname'],      
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $country,
                             'zone_id' => tep_get_zone_id($country['id'], $order['billing_state']),
                             'country_id' => $country['id'],
                             'format_id' => $order['billing_address_format_id']);

      $index = 0;
      $subtotal = 0;
      $tax = 0;
      $tax_groups = array();

      $tax_class_query = tep_db_query("select tax_class_id, tax_zone_id, sum(tax_rate) as rate,  tax_description from " . TABLE_TAX_RATES . " group by tax_class_id, tax_zone_id order by tax_description");
      while ($tax_class = tep_db_fetch_array($tax_class_query)) {
        $query = tep_db_query("select * from " . TABLE_TAX_RATES . " where tax_class_id = '" . $tax_class['tax_class_id'] . "' and tax_zone_id = '" . $tax_class['tax_zone_id'] . "'");
        if (tep_db_num_rows($query) > 1){
          $str = '';
          while ($data = tep_db_fetch_array($query)){
            if ($str == ''){
              $str .= $data['tax_description'];
            }else{
              $str .= " + " . $data['tax_description'];
            }
          }
          $tax_class['tax_description'] = $str;
        }
        $tax_class_array[] = array('id' => $tax_class['tax_class_id'] . '_' . $tax_class['tax_zone_id'],
                                   'text' => $tax_class['tax_description'], 
                                   'rate' => $tax_class['rate']);
      }       

      $orders_products_query = tep_db_query("select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $this->products[$index] = array('qty' => $orders_products['products_quantity'],
                                        'name' => $orders_products['products_name'],
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'final_price' => $orders_products['final_price']);

        $subtotal += $orders_products['final_price'] * $orders_products['products_quantity'];
        $tax += $orders_products['final_price'] * $orders_products['products_quantity'] * $orders_products['products_tax'] / 100;
        $selected_tax = '';
        for ($l=0,$n=sizeof($tax_class_array);$l<$n;$l++){
          if ($tax_class_array[$l]['rate'] == $orders_products['products_tax']){
            $selected_tax = $tax_class_array[$l]['text'];
            break;
          }
        }
        if (!isset($tax_groups[$selected_tax])) {
          $tax_groups[$selected_tax] = $orders_products['final_price'] * $orders_products['products_quantity'] * $orders_products['products_tax'] / 100;
        } else {
          $tax_groups[$selected_tax] += $orders_products['final_price'] * $orders_products['products_quantity'] * $orders_products['products_tax'] / 100;
        }

        $subindex = 0;
        $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($attributes_query)) {
          while ($attributes = tep_db_fetch_array($attributes_query)) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                     'value' => $attributes['products_options_values'],
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;
          }
        }
        $index++;
      }
      $res = tep_db_query("select count(*) as total from " . TABLE_ORDERS_PRODUCTS_DOWNLOAD  . " where orders_id='" . $this->order_id . "'");
      $d = tep_db_fetch_array($res);
      if ($d['total']==0){
        $this->content_type = 'physical';
      } else {
        if ($d['total']==count($this->contents)){
          $this->content_type = 'virtual';
        } else {
          $this->content_type = 'mixed';
        }
      }
      /*      'shipping_cost' => 0, */
      $this->info['subtotal'] = tep_round($subtotal, 2);
      $this->info['total'] = tep_round($subtotal, 2) + tep_round($tax, 2);
      $this->info['tax'] = tep_round($tax, 2);
      $this->info['tax_groups'] = $tax_groups;
    }
  }
?>
