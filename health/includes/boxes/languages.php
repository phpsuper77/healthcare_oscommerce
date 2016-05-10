<?php
/*
  $Id: languages.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
<?php
  $boxHeading = BOX_HEADING_LANGUAGES;
  $corner_left = 'square';
  $corner_right = 'square';
  $boxContent_attributes = ' align="center"';

  if (!isset($lng) || (isset($lng) && !is_object($lng))) {
    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
  }

  $boxContent = '';
  reset($lng->catalog_languages);
  while (list($key, $value) = each($lng->catalog_languages)) {
    $boxContent .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
  }

  require(DIR_WS_TEMPLATES . TEMPLATENAME_BOX);
?>
<!-- languages_eof //-->
<?
  $boxContent_attributes = '';
?>