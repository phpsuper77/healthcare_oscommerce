<?php
/*
  $Id: links.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// define our link functions
  require_once(DIR_WS_FUNCTIONS . 'links.php');

// calculate link category path
  if (isset($HTTP_GET_VARS['lPath'])) {
    $lPath = $HTTP_GET_VARS['lPath'];
    $current_links_id = $lPath;
    $display_mode = 'links';
  } elseif (isset($HTTP_GET_VARS['links_id'])) {
    $lPath = tep_get_link_path($HTTP_GET_VARS['links_id']);
  } else {
    $lPath = '';
    $display_mode = 'categories';
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS);

  // links breadcrumb
  $link_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$lPath . "' and language_id = '" . (int)$languages_id . "'");
  $link_categories_value = tep_db_fetch_array($link_categories_query);

  if ($display_mode == 'links') {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
    $breadcrumb->add($link_categories_value['link_categories_name'], tep_href_link(FILENAME_LINKS, 'lPath=' . $lPath));
  } else {
    $breadcrumb->add(NAVBAR_TITLE, FILENAME_LINKS);
  }


  $content = CONTENT_LINKS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

