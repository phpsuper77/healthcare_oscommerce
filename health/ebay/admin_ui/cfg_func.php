<?php

function tep_cfg_ebay_pin_top_categories( $list='', $key = '' ){
  $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value[]');
  $cat = new ebay_categories();
  $values = $cat->get_tree();
  $default = preg_split('/[, ]/', $list, -1, PREG_SPLIT_NO_EMPTY);

  $field = '<select size="10" multiple="multiple" name="' . tep_output_string($name) . '"';
  for ($i=0, $n=sizeof($values); $i<$n; $i++) {
    $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
    if ( in_array($values[$i]['id'],$default)) {
      $field .= ' SELECTED';
    }

    $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
  }
  $field .= '</select>';

  return $field;
}

function tep_cfg_ebay_show_top_categories( $list='' ){
  $default = preg_split('/[, ]/', $list, -1, PREG_SPLIT_NO_EMPTY);
  $cat = new ebay_categories();
  $values = $cat->get_tree();

  $ret = '';
  for ($i=0, $n=sizeof($values); $i<$n; $i++) {
    if ( in_array($values[$i]['id'],$default)) {
      if (!empty($ret)) $ret.=', ';
      $ret .= $values[$i]['text'];
    }
  }

  return $ret;
}

?>