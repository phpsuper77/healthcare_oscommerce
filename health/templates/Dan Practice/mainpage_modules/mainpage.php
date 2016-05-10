<?php
/*
  $Id: mainpage.php,v 2.0 2003/06/13

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- default_mainpage //-->

<?php
if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
  if (is_file(DIR_WS_AFFILIATES . $affiliate_ref . '/' . $language . '/' . FILENAME_DEFINE_MAINPAGE)){
    include(DIR_WS_AFFILIATES . $affiliate_ref . '/' . $language . '/' . FILENAME_DEFINE_MAINPAGE);
  }else{
    include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE);
  }
}else{
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_MAINPAGE);
}
?>

<!-- default_mainpage_eof //-->
