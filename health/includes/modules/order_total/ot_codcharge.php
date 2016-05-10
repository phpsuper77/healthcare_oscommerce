<?php

  class ot_codcharge {
    var $title, $output;

    function ot_codcharge() {
      $this->code = 'ot_codcharge';
      $this->title = MODULE_ORDER_TOTAL_CODCHARGE_TITLE;
      $this->description = MODULE_ORDER_TOTAL_CODCHARGE_DESCRIPTION;
      $this->enabled = ((MODULE_ORDER_TOTAL_CODCHARGE_STATUS == 'true') ? true : false);
      $this->sort_order = MODULE_ORDER_TOTAL_CODCHARGE_SORT_ORDER;

      $this->output = array();
    }

    function process() {
      global $order, $currencies, $payment;

      if (MODULE_ORDER_TOTAL_CODCHARGE_ORDER_FEE == 'true') {
        switch (MODULE_ORDER_TOTAL_CODCHARGE_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
          case 'both':
            $pass = true; break;
          default:
            $pass = false; break;
        }

        if ( ($pass == true) && ($order->info['payment_class'] == 'cod') ) {
          $tax = tep_get_tax_rate(MODULE_ORDER_TOTAL_CODCHARGE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $tax_description = tep_get_tax_description(MODULE_ORDER_TOTAL_CODCHARGE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);

          $order->info['tax'] += tep_calculate_tax(MODULE_ORDER_TOTAL_CODCHARGE_FEE, $tax);
          $order->info['tax_groups']["$tax_description"] += tep_calculate_tax(MODULE_ORDER_TOTAL_CODCHARGE_FEE, $tax);
          $order->info['total'] += MODULE_ORDER_TOTAL_CODCHARGE_FEE + tep_calculate_tax(MODULE_ORDER_TOTAL_CODCHARGE_FEE, $tax);

          $this->output[] = array('title' => $this->title . ':',
                                  'text' => $currencies->format(tep_add_tax(MODULE_ORDER_TOTAL_CODCHARGE_FEE, $tax), true, $order->info['currency'], $order->info['currency_value']),
                                  'value' => tep_add_tax(MODULE_ORDER_TOTAL_CODCHARGE_FEE, $tax));
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_CODCHARGE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_CODCHARGE_STATUS', 'MODULE_ORDER_TOTAL_CODCHARGE_SORT_ORDER', 'MODULE_ORDER_TOTAL_CODCHARGE_ORDER_FEE', 'MODULE_ORDER_TOTAL_CODCHARGE_FEE', 'MODULE_ORDER_TOTAL_CODCHARGE_DESTINATION', 'MODULE_ORDER_TOTAL_CODCHARGE_TAX_CLASS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display COD Order Fee', 'MODULE_ORDER_TOTAL_CODCHARGE_STATUS', 'true', 'Do you want to display the COD order fee?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_CODCHARGE_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow COD Fee', 'MODULE_ORDER_TOTAL_CODCHARGE_ORDER_FEE', 'false', 'Do you want to allow COD fees?', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Order Fee', 'MODULE_ORDER_TOTAL_CODCHARGE_FEE', '5', 'COD order fee.', '6', '5', 'currencies->format', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Attach COD Order Fee On Orders Made', 'MODULE_ORDER_TOTAL_CODCHARGE_DESTINATION', 'both', 'Attach COD order fee for orders sent to the set destination.', '6', '6', 'tep_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_ORDER_TOTAL_CODCHARGE_TAX_CLASS', '0', 'Use the following tax class on the COD order fee.', '6', '7', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
