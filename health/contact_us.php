<?php
/*
  $Id: contact_us.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	require('includes/application_top.php');
	include_once('controllers/front/FrontController.php');
	$controller = new FrontController();
	$canonical_tag = $controller->get_canonical_tag();
 ?> 
 <?
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {
    $HTTP_POST_VARS['email'] = preg_replace( "/\n/", " ", $HTTP_POST_VARS['email'] );
    $HTTP_POST_VARS['email'] = preg_replace( "/\r/", " ", $HTTP_POST_VARS['email'] );
    $HTTP_POST_VARS['email'] = str_replace("Content-Type:","",$HTTP_POST_VARS['email']);
    $HTTP_POST_VARS['name'] = preg_replace( "/\n/", " ", $HTTP_POST_VARS['name'] );
    $HTTP_POST_VARS['name'] = preg_replace( "/\r/", " ", $HTTP_POST_VARS['name'] );
    $HTTP_POST_VARS['name'] = str_replace("Content-Type:","",$HTTP_POST_VARS['name']); 
    
    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email']);
    $enquiry = tep_db_prepare_input($HTTP_POST_VARS['enquiry']);

    if (!tep_validate_email($email_address)) {
      $error = true;
      $email = ''; 
      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

// {{
    if (ANTI_SPAM_ROBOT == 'True')
    {
      if ( strlen($HTTP_SESSION_VARS['random']) == 0 || strcasecmp($HTTP_POST_VARS['robot'], $HTTP_SESSION_VARS['random']) != 0 )
      {
        $error = true;
        $robot = '';
        $messageStack->add('contact', ENTRY_ROBOT_ERROR);
      }
      tep_session_unregister('random'); unset($random); unset($HTTP_SESSION_VARS['random']);
    }
// }}

    if (!$error) {
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);
      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
    }
  } else {
    $enquiry = '';
    $name = '';
    $email = '';
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));

  $content = CONTENT_CONTACT_US;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  ?>
  <link href="<?=DIR_WS_TEMPLATES.TEMPLATE_NAME?>/css/contact-us.css" rel="stylesheet" type="text/css" /> 
  <?
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
