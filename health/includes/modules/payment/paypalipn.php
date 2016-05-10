<?php
/*
  $Id: paypalipn.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Paypal IPN v0.981 for Milestone 2
  Copyright (c) 2003 Pablo Pasqualino
  pablo_osc@osmosisdc.com
  http://www.osmosisdc.com

  Released under the GNU General Public License
*/

  class paypalipn {
    var $code, $title, $description, $enabled, $notify_url, $curl, $add_shipping_to_amount, $add_tax_to_amount, $update_stock_before_payment, $allowed_currencies, $default_currency, $test_mode;

// class constructor
    function paypalipn() {
      global $order;
	
      $this->code = 'paypalipn';
      $this->title = MODULE_PAYMENT_PAYPALIPN_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPALIPN_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYPALIPN_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYPALIPN_STATUS == 'True') ? true : false);
      $this->notify_url = MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL;
      $this->curl = ((MODULE_PAYMENT_PAYPALIPN_CURL == 'True') ? true : false);
      $this->add_shipping_to_amount = ((MODULE_PAYMENT_PAYPALIPN_ADD_SHIPPING_TO_AMOUNT == 'True') ? true : false);
      $this->add_tax_to_amount = ((MODULE_PAYMENT_PAYPALIPN_ADD_TAX_TO_AMOUNT == 'True') ? true : false);
      $this->update_stock_before_payment = ((MODULE_PAYMENT_PAYPALIPN_UPDATE_STOCK_BEFORE_PAYMENT == 'True') ? true : false);
      $this->allowed_currencies = MODULE_PAYMENT_PAYPALIPN_ALLOWED_CURRENCIES;
      $this->default_currency = MODULE_PAYMENT_PAYPALIPN_DEFAULT_CURRENCY;
      $this->test_mode = ((MODULE_PAYMENT_PAYPALIPN_TEST_MODE == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PAYPALIPN,'','SSL');
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPALIPN_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPALIPN_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPALIPN_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      global $language;
      if (function_exists('curl_exec')) {
        $curl_message = '<br>cURL has been <b>DETECTED</b> in your system';
      } else {
        $curl_message = '<br>cURL has <b>NOT</b> been <b>DETECTED</b> in your system';
      };

      $paypal_supported_currencies = "'USD','EUR','GBP','CAD','JPY'";

      $available_currencies_query = tep_db_query("select title,code,symbol_left,symbol_right from " . TABLE_CURRENCIES . " where code IN($paypal_supported_currencies) order by currencies_id");
      if (tep_db_num_rows($available_currencies_query)) {
        while ($available_currencies = tep_db_fetch_array($available_currencies_query)) {
          $osc_allowed_currencies .= $available_currencies[code].',';
        };
        $osc_allowed_currencies = substr($osc_allowed_currencies,0,strlen($osc_allowed_currencies)-1);
      } else {
        $osc_allowed_currencies = 'USD';
      };

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow PayPal IPN', 'MODULE_PAYMENT_PAYPALIPN_STATUS', 'True', 'Do you want to accept PayPal IPN payments and notifications?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal IPN ID', 'MODULE_PAYMENT_PAYPALIPN_ID', 'you@yourbusiness.com', 'Your business ID at PayPal.  Usually the email address you signed up with.  You can create a free PayPal account at <a href=\"http://www.paypal.com/\" target=\"_blank\">http://www.paypal.com</a>.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal IPN Notify URL', 'MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL','".HTTP_CATALOG_SERVER . DIR_WS_CATALOG."paypal_notify.php', 'Exact location in which your paypal_notify.php file resides.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Use cURL', 'MODULE_PAYMENT_PAYPALIPN_CURL','False', 'Use cURL to communicate with PayPal?" . $curl_message . "', '6', '4', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Add Shipping to Amount', 'MODULE_PAYMENT_PAYPALIPN_ADD_SHIPPING_TO_AMOUNT','False', 'Add shipping amount to order amount? (will set shipping amount to $0 in PayPal)', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Add Tax to Amount', 'MODULE_PAYMENT_PAYPALIPN_ADD_TAX_TO_AMOUNT','False', 'Add tax amount to order amount? (will set tax amount to $0 in PayPal)', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Update Stock Before Payment', 'MODULE_PAYMENT_PAYPALIPN_UPDATE_STOCK_BEFORE_PAYMENT','False', 'Should Products Stock be updated even when the payment is not yet COMPLETED?', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal IPN Allowed Currencies', 'MODULE_PAYMENT_PAYPALIPN_ALLOWED_CURRENCIES','$osc_allowed_currencies', 'Allowed currencies in which customers can pay.<br>Allowed by PayPal: " . str_replace('\'','',$paypal_supported_currencies) . "<br>Allowed in your shop: $osc_allowed_currencies<br>To add more currencies to your shop go to Localization->Currencies.', '6', '9', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Default Currency', 'MODULE_PAYMENT_PAYPALIPN_DEFAULT_CURRENCY','USD', 'Default currency to use when customer try to pay in a NON allowed (because of PayPal or you) currency', '6', '10', 'tep_cfg_select_option(array(\'" . str_replace(',',"\', \'",$osc_allowed_currencies) . "\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal IPN Test Mode', 'MODULE_PAYMENT_PAYPALIPN_TEST_MODE','False', 'Run in TEST MODE? If so, you will be able to send TEST IPN from Admin->PayPal_IPN->Test_IPN, BUT you will not be able to receive real IPN\'s from PayPal.', '6', '11', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal IPN Sort order of display.', 'MODULE_PAYMENT_PAYPALIPN_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('PayPal IPN Payment Zone', 'MODULE_PAYMENT_PAYPALIPN_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '13', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('PayPal IPN Set Order Status', 'MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '14', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPALIPN_STATUS', 'MODULE_PAYMENT_PAYPALIPN_ID', 'MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL', 'MODULE_PAYMENT_PAYPALIPN_CURL', 'MODULE_PAYMENT_PAYPALIPN_ADD_SHIPPING_TO_AMOUNT', 'MODULE_PAYMENT_PAYPALIPN_ADD_TAX_TO_AMOUNT', 'MODULE_PAYMENT_PAYPALIPN_UPDATE_STOCK_BEFORE_PAYMENT', 'MODULE_PAYMENT_PAYPALIPN_ALLOWED_CURRENCIES', 'MODULE_PAYMENT_PAYPALIPN_DEFAULT_CURRENCY', 'MODULE_PAYMENT_PAYPALIPN_TEST_MODE', 'MODULE_PAYMENT_PAYPALIPN_ZONE', 'MODULE_PAYMENT_PAYPALIPN_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPALIPN_SORT_ORDER');
    }
  }
?>