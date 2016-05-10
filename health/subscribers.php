<?php
  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SUBSCRIBERS);

  $email_address = '';
  $firstname = '';
  $lastname = '';
  if ( !empty($HTTP_POST_VARS['action']) ) {
    switch ($HTTP_POST_VARS['action'])
    {
      case "sub":
        $error = false;
        $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
        $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
        $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
        
        if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add_session('subscribers', ENTRY_FIRST_NAME_ERROR);
        }
        if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $messageStack->add_session('subscribers', ENTRY_LAST_NAME_ERROR);
        }
        if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $messageStack->add_session('subscribers', ENTRY_EMAIL_ADDRESS_ERROR);
        } elseif (tep_validate_email($email_address) == false) {
          $error = true;
          $messageStack->add_session('subscribers', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
        } else {
          $check_email_query = tep_db_query("select count(*) as total from " . TABLE_SUBSCRIBERS . " where subscribers_email_address = '" . tep_db_input($email_address) . "'");
          $check_email = tep_db_fetch_array($check_email_query);
          if ($check_email['total'] > 0) {
            $error = true;
            $messageStack->add_session('subscribers', TEXT_ALREADY_SUBSCRIBED);
          }
          // check for registered customers
          if ( !$error ) {
            $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'".( (defined('ONE_PAGE_CHECKOUT') && ONE_PAGE_CHECKOUT=='True')?" AND opc_temp_account=0":''));
            $check_email = tep_db_fetch_array($check_email_query);
            if ($check_email['total'] > 0) {
              $error = true;
              $messageStack->add_session('subscribers', TEXT_ALREADY_REGISTERED);
            }
          }
          // and finnaly if no error - make
          if ( $error ) {
            tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, "email_address=".$email_address."&firstname=".$firstname."&lastname=".$lastname, 'NONSSL'));
          }else{
            tep_db_perform(
              TABLE_SUBSCRIBERS,
              array(
                'subscribers_email_address' => $email_address,
                'subscribers_firstname'     => $firstname, 
                'subscribers_lastname'      => $lastname,
              )
            );
            tep_mail($firstname.' '.$lastname, $email_address, LETTER_SUBJ_SUBSCRIBED, LETTER_SUBSCRIBED, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $messageStack->add_session('subscribers', TEXT_CONGRATILATION_SUBSCRIBED, 'success');
            tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params( array('action', 'act') ).'act=subscribed', 'NONSSL'));
          }
        }
      break;
      
      case "unsub":
        $email_address = tep_db_prepare_input($HTTP_POST_VARS['unsub_email_address']);
        if ( empty($email_address) || tep_validate_email($email_address) == false ) {
          //email error
          $messageStack->add_session('subscribers', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
          tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params( array('action', 'act') ), 'NONSSL'));
        }
  
        $check_r = tep_db_query("select subscribers_email_address, subscribers_firstname, subscribers_lastname from ".TABLE_SUBSCRIBERS." where subscribers_email_address ='".tep_db_input($email_address)."'");
        if ($check = tep_db_fetch_array($check_r)) {
          tep_db_query("delete from ".TABLE_SUBSCRIBERS." where subscribers_email_address='".tep_db_input($email_address)."'");
          tep_mail($check['subscribers_firstname'].' '.$check['subscribers_lastname'], $check['subscribers_email_address'], LETTER_SUBJ_UNSUBSCRIBED, LETTER_UNSUBSCRIBED, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          $messageStack->add_session('subscribers', TEXT_CONGRATILATION_UNSUBSCRIBED, 'success');
          tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params( array('action', 'act') ).'act=unsubscribed', 'NONSSL'));
        }
  
        $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'".( (defined('ONE_PAGE_CHECKOUT') && ONE_PAGE_CHECKOUT=='True')?" AND opc_temp_account=0":''));
        $check_email = tep_db_fetch_array($check_email_query);
        if ($check_email['total'] > 0) {
           $messageStack->add_session('subscribers', TEXT_REGISTERED_NEWSLETTER);
           tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, '', 'NONSSL'));
        }
  // error email address
        $messageStack->add_session('subscribers', TEXT_NOT_REGISTERED_FOR_NEWSLETTER);
        tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, 'email_address='.$email_address, 'NONSSL'));
      break;
      default:
      $email_address = !empty($HTTP_GET_VARS['email_address'])?tep_db_prepare_input($HTTP_GET_VARS['email_address']):'';
      $firstname = !empty($HTTP_GET_VARS['firstname'])?tep_db_prepare_input($HTTP_GET_VARS['firstname']):'';
      $lastname = !empty($HTTP_GET_VARS['lastname'])?tep_db_prepare_input($HTTP_GET_VARS['lastname']):'';
    }
  }else{
    $email_address = !empty($HTTP_GET_VARS['email_address'])?tep_db_prepare_input($HTTP_GET_VARS['email_address']):'';
    $firstname = !empty($HTTP_GET_VARS['firstname'])?tep_db_prepare_input($HTTP_GET_VARS['firstname']):'';
    $lastname = !empty($HTTP_GET_VARS['lastname'])?tep_db_prepare_input($HTTP_GET_VARS['lastname']):'';
  }

  if ( !empty($email_address) && tep_validate_email($email_address) == false) {
    $messageStack->add('subscribers', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SUBSCRIBERS));

  $content = CONTENT_SUBSCRIBERS;

  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>