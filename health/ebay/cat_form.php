<?php
chdir('..');
$HTTP_SERVER_VARS['SCRIPT_FILENAME'] = preg_replace('/\/([^\/]+?)$/','/',dirname(__FILE__)).basename(__FILE__);

require('includes/application_top.php');
require('ebay/core.php');
require_once('ebay/ebay_form_hack.php');

ob_start(); {
  $core = ebay_core::get();
  $cat = new ebay_categories();

  $chars = $cat->Category2CS($_GET['cat_id']);
} ob_end_clean();

if ( is_array($chars) ) {
  $form_data = get_form_data_for( $chars[0]['SetID'], $chars[0]['SetVersion'] );
  if ( is_array($form_data) ) {
    renderForm( $form_data, (int)$_GET['try'] );
  }
}else{
  echo '<div class="row">No data</div>';
}

?>