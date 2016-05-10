<?php

/*

  $Id: vendor_terms.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $



  OSC-Affiliate



  Contribution based on:



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2002 - 2003 osCommerce



  Released under the GNU General Public License

*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VENDOR_TERMS);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_VENDOR_TERMS));

  $content = CONTENT_VENDOR_TERMS;

  $content_template = TEMPLATENAME_STATIC;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>

