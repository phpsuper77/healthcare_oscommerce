<?php
  class paypal_ebay {
    var $code, $title, $description, $enabled;
    var $admin_module = true;

// class constructor
    function paypal_ebay() {
      global $order;

      $this->code = 'paypal_ebay';
      $this->title = MODULE_PAYMENT_PAYPAL_EBAY_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_EBAY_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYPAL_EBAY_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PAYPAL_EBAY_STATUS == 'True') ? true : false);

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order;

      if (($this->enabled === true) && defined('DIR_WS_ADMIN') === false) {
         $this->enabled = false;
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
      return array('title' => MODULE_PAYMENT_PAYPAL_EBAY_TEXT_DESCRIPTION);
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

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_EBAY_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Paypay Ebay Module', 'MODULE_PAYMENT_PAYPAL_EBAY_STATUS', 'True', 'Do you want to accept payments via Bank Transfer?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_EBAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_EBAY_STATUS', 'MODULE_PAYMENT_PAYPAL_EBAY_SORT_ORDER');
    }
  } 
?>