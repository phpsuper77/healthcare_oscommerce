<?php
/*
  $Id: currencies.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($currencies) && is_object($currencies)) {
?>
<!-- currencies //-->
<?php
    $boxHeading = BOX_HEADING_CURRENCIES;
    $corner_left = 'square';
    $corner_right = 'square';
	$boxContent_attributes = ' align="center"';

    reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

    $hidden_get_variables = '';
    reset($HTTP_GET_VARS);
    while (list($key, $value) = each($HTTP_GET_VARS)) {
      if ( ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
        $hidden_get_variables .= tep_draw_hidden_field($key, $value);
      }
    }

    $boxContent = tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get');
    $boxContent .= tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onChange="this.form.submit();" style="width: 100%"');
    $boxContent .= $hidden_get_variables;
    $boxContent .= tep_hide_session_id();
    $boxContent .= '</form>';

    require(DIR_WS_TEMPLATES . TEMPLATENAME_BOX);
?>
<!-- currencies_eof //-->
<?
  $boxContent_attributes = '';
  }
?>
