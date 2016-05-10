<?php
/*
  $Id: affiliate_application_top.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/


// Set the local configuration parameters - mainly for developers
  if (file_exists(DIR_WS_INCLUDES . 'local/affiliate_configure.php')) include(DIR_WS_INCLUDES . 'local/affiliate_configure.php');

  require(DIR_WS_INCLUDES . 'affiliate_configure.php');
  require(DIR_WS_FUNCTIONS . 'affiliate_functions.php');
  define('AFFILIATE_EXTRA', 'False');
// define the database table names used in the contribution
  define('TABLE_AFFILIATE', 'affiliate_affiliate');
// if you change this -> affiliate_show_banner must be changed too
  define('TABLE_AFFILIATE_BANNERS', 'affiliate_banners');
  define('TABLE_AFFILIATE_BANNERS_HISTORY', 'affiliate_banners_history');
  define('TABLE_AFFILIATE_CLICKTHROUGHS', 'affiliate_clickthroughs');
  define('TABLE_AFFILIATE_SALES', 'affiliate_sales');
  define('TABLE_AFFILIATE_PAYMENT', 'affiliate_payment');
  define('TABLE_AFFILIATE_PAYMENT_STATUS', 'affiliate_payment_status');
  define('TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY', 'affiliate_payment_status_history');

// define the filenames used in the project
  define('FILENAME_AFFILIATE_SUMMARY', 'affiliate_summary.php');
  define('FILENAME_AFFILIATE_LOGOUT', 'affiliate_logout.php');
  define('FILENAME_AFFILIATE', 'affiliate_affiliate.php');
  define('FILENAME_AFFILIATE_CONTACT', 'affiliate_contact.php');
  define('FILENAME_AFFILIATE_FAQ', 'affiliate_faq.php');
  define('FILENAME_AFFILIATE_ACCOUNT', 'affiliate_details.php');
  define('FILENAME_AFFILIATE_DETAILS', 'affiliate_details.php');
  define('FILENAME_AFFILIATE_DETAILS_OK', 'affiliate_details_ok.php');
  define('FILENAME_AFFILIATE_TERMS','affiliate_terms.php');

  define('FILENAME_AFFILIATE_HELP_1', 'affiliate_help1.php');
  define('FILENAME_AFFILIATE_HELP_2', 'affiliate_help2.php');
  define('FILENAME_AFFILIATE_HELP_3', 'affiliate_help3.php');
  define('FILENAME_AFFILIATE_HELP_4', 'affiliate_help4.php');
  define('FILENAME_AFFILIATE_HELP_5', 'affiliate_help5.php');
  define('FILENAME_AFFILIATE_HELP_6', 'affiliate_help6.php');
  define('FILENAME_AFFILIATE_HELP_7', 'affiliate_help7.php');
  define('FILENAME_AFFILIATE_HELP_8', 'affiliate_help8.php');
  define('FILENAME_AFFILIATE_INFO', 'affiliate_info.php');

  define('FILENAME_AFFILIATE_BANNERS', 'affiliate_banners.php');
  define('FILENAME_AFFILIATE_SHOW_BANNER', 'affiliate_show_banner.php');
  define('FILENAME_AFFILIATE_CLICKS', 'affiliate_clicks.php');

  define('FILENAME_AFFILIATE_PASSWORD_FORGOTTEN', 'affiliate_password_forgotten.php');

  define('FILENAME_AFFILIATE_LOGOUT', 'affiliate_logout.php');
  define('FILENAME_AFFILIATE_SALES', 'affiliate_sales.php');
  define('FILENAME_AFFILIATE_SIGNUP', 'affiliate_signup.php');

  define('FILENAME_AFFILIATE_SIGNUP_OK', 'affiliate_signup_ok.php');
  define('FILENAME_AFFILIATE_PAYMENT', 'affiliate_payment.php');

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '/affiliate.php');

  $affiliate_clientdate = (date ("Y-m-d H:i:s"));
  $affiliate_clientbrowser = $HTTP_SERVER_VARS["HTTP_USER_AGENT"];
  $affiliate_clientip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
  $affiliate_clientreferer = $HTTP_SERVER_VARS["HTTP_REFERER"];

//  if (!$HTTP_SESSION_VARS['affiliate_ref']) {
  if (!isset($HTTP_SESSION_VARS['affiliate_ref']) && !(AFFILIATE_EXTRA != 'True' && isset($HTTP_SESSION_VARS['aff_ref']))) {
    if ($HTTP_GET_VARS['ref'] || $HTTP_POST_VARS['ref'] || $HTTP_COOKIE_VARS['affiliate_ref'] || $HTTP_COOKIE_VARS['aff_ref']) {
      if ($HTTP_GET_VARS['ref']) $affiliate_ref = $HTTP_GET_VARS['ref'];
      if ($HTTP_POST_VARS['ref']) $affiliate_ref = $HTTP_POST_VARS['ref'];
      if ($HTTP_COOKIE_VARS['affiliate_ref']) $affiliate_ref = $HTTP_COOKIE_VARS['affiliate_ref'];
      if ($HTTP_COOKIE_VARS['aff_ref']) $affiliate_ref = $HTTP_COOKIE_VARS['aff_ref'];

      if ($HTTP_GET_VARS['products_id']) $affiliate_products_id = $HTTP_GET_VARS['products_id'];
      if ($HTTP_POST_VARS['products_id']) $affiliate_products_id = $HTTP_POST_VARS['products_id'];
      if ($HTTP_GET_VARS['affiliate_banner_id']) $affiliate_banner_id = $HTTP_GET_VARS['affiliate_banner_id'];
      if ($HTTP_POST_VARS['affiliate_banner_id']) $affiliate_banner_id = $HTTP_POST_VARS['affiliate_banner_id'];

      // check affiliate status
      $affiliate_status = tep_db_fetch_array(tep_db_query("select affiliate_isactive as status from " . TABLE_AFFILIATE . " where affiliate_id='" . (int)$affiliate_ref . "'"));
      if($affiliate_status['status']!=0){
        if (AFFILIATE_EXTRA != 'True') {
          tep_session_register('aff_ref');
          $aff_ref = $affiliate_ref;
          setcookie('aff_ref', $affiliate_ref, time() + AFFILIATE_COOKIE_LIFETIME);
        } else {
          tep_session_register('affiliate_ref');
          setcookie('affiliate_ref', $affiliate_ref, time() + AFFILIATE_COOKIE_LIFETIME);
        }
        tep_session_register('affiliate_clickthroughs_id');
//        if (!$link_to) $link_to = "0";
        $sql_data_array = array('affiliate_id' => (int)$affiliate_ref,
                                'affiliate_clientdate' => $affiliate_clientdate,
                                'affiliate_clientbrowser' => $affiliate_clientbrowser,
                                'affiliate_clientip' => $affiliate_clientip,
                                'affiliate_clientreferer' => $affiliate_clientreferer,
                                'affiliate_products_id' => $affiliate_products_id,
                                'affiliate_banner_id' => $affiliate_banner_id);
        tep_db_perform(TABLE_AFFILIATE_CLICKTHROUGHS, $sql_data_array);
        $affiliate_clickthroughs_id = tep_db_insert_id();

        //Banner has been clicked, update stats:
        if ($affiliate_banner_id && $affiliate_ref) {
          $today = date('Y-m-d');
          $sql = "select * from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . (int)$affiliate_banner_id  . "' and  affiliate_banners_affiliate_id = '" . (int)$affiliate_ref . "' and affiliate_banners_history_date = '" . $today . "'";
          $banner_stats_query = tep_db_query($sql);

          //Banner has been shown today
          if (tep_db_fetch_array($banner_stats_query)) {
            tep_db_query("update " . TABLE_AFFILIATE_BANNERS_HISTORY . " set affiliate_banners_clicks = affiliate_banners_clicks + 1 where affiliate_banners_id = '" . (int)$affiliate_banner_id . "' and affiliate_banners_affiliate_id = '" . (int)$affiliate_ref. "' and affiliate_banners_history_date = '" . $today . "'");
          //Initial entry if banner has not been shown
          } else {
            $sql_data_array = array('affiliate_banners_id' => $affiliate_banner_id,
                                    'affiliate_banners_products_id' => $affiliate_products_id,
                                    'affiliate_banners_affiliate_id' => $affiliate_ref,
                                    'affiliate_banners_clicks' => '1',
                                    'affiliate_banners_history_date' => $today);
            tep_db_perform(TABLE_AFFILIATE_BANNERS_HISTORY, $sql_data_array);
          }
        }

      }
    }
  }

  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    // correct only for extra module
    $data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id='" . $affiliate_ref . "'"));
    if ($data['affiliate_store_name'] != ''){
      define('STORE_NAME', $data['affiliate_store_name']);
    }else{
      define('STORE_NAME', $store_name);
    }
    if ($data['affiliate_email_from'] != ''){
      define('EMAIL_FROM', $data['affiliate_email_from']);
    }else{
      define('EMAIL_FROM', $email_from);
    }
    define('EMAIL_FROM', $data['affiliate_firstname'] . ' ' . $data['affiliate_lastname']);
  }else{
    define('STORE_NAME', $store_name);
    define('STORE_OWNER', $store_owner);
    define('EMAIL_FROM', $email_from);
  }

?>