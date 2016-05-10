<?php
/*
  $Id: theme_select.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
    if (tep_session_is_registered('customer_id')) {

  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {

?>
<!-- theme //-->
         <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  } else {

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_TEMPLATE_SELECT);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    } else {
      new infoBoxHeading($info_box_contents, false, false);
    }
  }

  $template_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where active = '1' order by template_name");
  $templates_array = array();
  $templates_array[] = array('id' => '', 'text' => substr(PULL_DOWN_DEFAULT, 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN));
  
  while ($template_values = tep_db_fetch_array($template_query)) {
    $templates_array[] = array('id' => $template_values['template_name'], 'text' => substr($template_values['template_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN));
  }

  $info_box_contents = array();
  $info_box_contents[] = array('form'  => tep_draw_form('template', tep_href_link(basename($PHP_SELF), tep_get_all_get_params('action') . 'action=update_template', $request_type)),
                               'align' => 'center',
                               'text'  => tep_draw_pull_down_menu('template', $templates_array, TEMPLATE_NAME, 'onChange="this.form.submit();"'));

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  } else {
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<?php
}}
?>
<!-- template_eof //-->
