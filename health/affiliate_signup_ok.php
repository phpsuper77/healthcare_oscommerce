<?php
/*
  $Id: affiliate_signup_ok.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SIGNUP_OK);

  $content = CONTENT_AFFILIATE_SIGNUP_OK;
//  require('includes/form_check.js.php');
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
