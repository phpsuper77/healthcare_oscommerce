<?php
/*
  $Id: languages.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_LANGUAGES);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }    
    
  }

  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  $languages_string = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    if (SEO_URL_PARTS_LANGUAGE == 'True' && SEARCH_ENGINE_FRIENDLY_URLS == 'True' && SEARCH_ENGINE_UNHIDE == 'True')
    {
      $languages_string .= ' <a href="' . tep_href_link($key . '/' . basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency', 'cPath_name')), $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
    }
    else
    {
      $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency', 'cPath_name')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
    }
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
                               'text' => $languages_string);

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- languages_eof //-->
