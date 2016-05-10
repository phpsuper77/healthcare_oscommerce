<?php
/*
  $Id: password_forgotten.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require("includes/application_top.php");
include_once('controllers/front/FrontController.php');
$controller = new FrontController();
$canonical_tag = $controller->get_canonical_tag();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PASSWORD_FORGOTTEN);
  require(DIR_WS_CLASSES . 'opc_namespace.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);

    $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'" . (isset($affiliate_ref)?" and affiliate_id = '" . (int)$affiliate_ref . "'":''));
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);
      if ( opc::is_temp_customer($check_customer['customers_id']) ){
        $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
        opc::remove_temp_customer( $check_customer['customers_id'] );
      }else{
        $new_password = strtolower(tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH));
        $crypted_password = tep_encrypt_password($new_password);
  
        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_db_input($crypted_password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");
  
        tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, sprintf(EMAIL_PASSWORD_REMINDER_BODY, $new_password), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  
        $messageStack->add_session('login', SUCCESS_PASSWORD_SENT, 'success');
  
        tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
      }
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

  $content = CONTENT_PASSWORD_FORGOTTEN;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
