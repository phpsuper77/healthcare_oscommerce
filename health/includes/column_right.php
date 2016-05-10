<?php
/*
  $Id: column_right.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != '' && tep_check_affiliate_infobox($HTTP_SESSION_VARS['affiliate_ref'])){
  $column_query = tep_db_query('select infobox_id, display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . (int)TEMPLATE_ID . " and infobox_display = 1 and display_in_column = 'right' and affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' order by location");
}else{
  $column_query = tep_db_query('select infobox_id, display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . (int)TEMPLATE_ID . ' and infobox_display = 1 and display_in_column = "right" and affiliate_id = 0 order by location');
}

$design_column = 'right';

while ($column = tep_db_fetch_array($column_query)) {
  if ( file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle'])) {

    define($column['cfgkey'],$column['box_heading']);
    $infobox_define = $column['box_heading'];
    $infobox_template = $column['box_template'];
    $font_color = $column['box_heading_font_color'];
    $infobox_class = $column['box_template'];
    $infobox_id = $column['infobox_id'];
    require_once(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
  }
}
?>