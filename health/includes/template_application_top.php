<?php
/*
$Id: template_application_top.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

class tableBoxMessagestack {
  var $table_border = '0';
  var $table_width = '100%';
  var $table_cellspacing = '0';
  var $table_cellpadding = '2';
  var $table_parameters = '';
  var $table_row_parameters = '';
  var $table_data_parameters = '';

  // class constructor
  function tableBoxMessagestack($contents, $direct_output = false) {
    $tableBox1_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
    if (tep_not_null($this->table_parameters)) $tableBox1_string .= ' ' . $this->table_parameters;
    $tableBox1_string .= '>' . "\n";

    for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
      if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox1_string .= $contents[$i]['form'] . "\n";
      $tableBox1_string .= '  <tr';
      if (tep_not_null($this->table_row_parameters)) $tableBox1_string .= ' ' . $this->table_row_parameters;
      if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox1_string .= ' ' . $contents[$i]['params'];
      $tableBox1_string .= '>' . "\n";

      if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
        for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
          if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
            $tableBox1_string .= '    <td';
            if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox1_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
            if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
              $tableBox1_string .= ' ' . $contents[$i][$x]['params'];
            } elseif (tep_not_null($this->table_data_parameters)) {
              $tableBox1_string .= ' ' . $this->table_data_parameters;
            }
            $tableBox1_string .= '>';
            if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox1_string .= $contents[$i][$x]['form'];
            $tableBox1_string .= $contents[$i][$x]['text'];
            if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox1_string .= '</form>';
            $tableBox1_string .= '</td>' . "\n";
          }
        }
      } else {
        $tableBox1_string .= '    <td';
        if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox1_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
          $tableBox1_string .= ' ' . $contents[$i]['params'];
        } elseif (tep_not_null($this->table_data_parameters)) {
          $tableBox1_string .= ' ' . $this->table_data_parameters;
        }
        $tableBox1_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
      }

      $tableBox1_string .= '  </tr>' . "\n";
      if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox1_string .= '</form>' . "\n";
    }

    $tableBox1_string .= '</table>' . "\n";

    if ($direct_output == true) echo $tableBox1_string;

    return $tableBox1_string;
  }
}

if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
  $template = tep_db_fetch_array(tep_db_query('select * from ' . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'"));
  if ($template['affiliate_template'] != ''){
    define(TEMPLATE_NAME, $template['affiliate_template']);
    define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
    define(TEMPLATE_STYLE_ORIGINAL, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet_original.css");
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php');
  }else{
    define(TEMPLATE_NAME, DEFAULT_TEMPLATE);
    define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet.css");
    define(TEMPLATE_STYLE_ORIGINAL, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet_original.css");
    require(DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/boxes.tpl.php');
  }
}else{
  $cptemplate = array();
  if ($customer_id > 0){
    $cptemplate = tep_db_fetch_array(tep_db_query("select  customers_selected_template as template_selected from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'"));
  }

  if (tep_not_null($cptemplate['template_selected']) && is_dir(DIR_WS_TEMPLATES . $cptemplate['template_selected'])) {
    define(TEMPLATE_NAME, $cptemplate['template_selected']);
    define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
    define(TEMPLATE_STYLE_ORIGINAL, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet_original.css");
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php');
  }else if  (defined('DEFAULT_TEMPLATE') && tep_not_null(DEFAULT_TEMPLATE)){
    define(TEMPLATE_NAME, DEFAULT_TEMPLATE);
    define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet.css");
    define(TEMPLATE_STYLE_ORIGINAL, DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . "/stylesheet_original.css");
    require(DIR_WS_TEMPLATES . DEFAULT_TEMPLATE . '/boxes.tpl.php');
  }else {
    tep_db_query('UPDATE '.TABLE_CONFIGURATION.' SET configuration_value = "Original" WHERE configuration_key="DEFAULT_TEMPLATE"');
    define(TEMPLATE_NAME, 'Original');
    define(TEMPLATE_STYLE, DIR_WS_TEMPLATES . TEMPLATE_NAME . "/stylesheet.css");
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes.tpl.php');
  }
}


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
if ( file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php')) {
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/extra_html_output.php');
}
define('DIR_WS_TEMPLATE_IMAGES', DIR_WS_TEMPLATES . TEMPLATE_NAME .'/images/');
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

$template = tep_db_fetch_array(tep_db_query("select * from " . TABLE_TEMPLATE . " where template_name = '" . tep_db_input(TEMPLATE_NAME) . "'"));

define('TEMPLATE_ID', $template[template_id]);
define('CELLPADDING_MAIN', $template[template_cellpadding_main]);
define('CELLPADDING_LEFT', $template[template_cellpadding_left]);
define('CELLPADDING_RIGHT', $template[template_cellpadding_right]);
define('CELLPADDING_SUB', $template[template_cellpadding_sub]);
define('DISPLAY_COLUMN_LEFT', $template[include_column_left]);
define('DISPLAY_COLUMN_RIGHT', $template[include_column_right]);

define('SITE_WIDTH', $template[site_width]);
define('BOX_WIDTH_LEFT', $template[box_width_left]);
define('BOX_WIDTH_RIGHT', $template[box_width_right]);
define('SIDE_BOX_LEFT_WIDTH', $template[side_box_left_width]);
define('SIDE_BOX_RIGHT_WIDTH', $template[side_box_right_width]);
define('MAIN_TABLE_BORDER', $template[main_table_border]);
define('SHOW_HEADER_LINK_BUTTONS', $template[show_header_link_buttons]);
define('SHOW_CART_IN_HEADER', $template[cart_in_header]);
define('SHOW_LANGUAGES_IN_HEADER', $template[languages_in_header]);
define('SHOW_HEADING_TITLE_ORIGINAL', $template[show_heading_title_original]);
define('INCLUDE_MODULE_ONE', $template[module_one]);
define('INCLUDE_MODULE_TWO', $template[module_two]);
define('INCLUDE_MODULE_THREE', $template[module_three]);
define('INCLUDE_MODULE_FOUR', $template[module_four]);
define('INCLUDE_MODULE_FIVE', $template[module_five]);
define('INCLUDE_MODULE_SIX', $template[module_six]);
define('SHOW_CUSTOMER_GREETING', $template[customer_greeting]);

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//for templatebox
if ($HTTP_GET_VARS['action'] == 'update_template') {
  if ($template >= '1'){
    $thema_template = tep_db_prepare_input($HTTP_POST_VARS['template']);
    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_selected_template = '".tep_db_input($thema_template)."' where customers_id = '" . (int)$customer_id . "'");
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
  }
}
global $javascript, $content_template;
$javascript = false;
$content_template = false;

?>