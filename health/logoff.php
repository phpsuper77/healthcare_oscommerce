<?php
/*
  $Id: logoff.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  if (tep_session_is_registered('customer_id')){
    tep_session_unregister('customer_id');
    tep_session_unregister('customer_default_address_id');
    tep_session_unregister('customer_first_name');
    tep_session_unregister('customer_country_id');
    tep_session_unregister('customer_zone_id');
    tep_session_unregister('comments');
    tep_session_unregister('customer_groups_id');
    tep_session_unregister('cart_address_id');
    
    $customer_groups_id = DEFAULT_USER_GROUP;
  //ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
    tep_session_unregister('gv_id');
    tep_session_unregister('cc_id');
  //ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
    $cart->reset();
    tep_redirect(tep_href_link(FILENAME_LOGOFF));
  }

  $content = CONTENT_LOGOFF;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
