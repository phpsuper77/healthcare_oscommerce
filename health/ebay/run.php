<?php
chdir('..');
$HTTP_SERVER_VARS['SCRIPT_FILENAME'] = preg_replace('/\/([^\/]+?)$/','/',dirname(__FILE__)).basename(__FILE__);
require('includes/application_top.php');
require('ebay/core.php'); 

if ( EBAY_UK_CONNECTOR_STATUS=='True' ) {
  $scheduler = new Scheduler();
  $scheduler->run();
}else{
  echo "Disabled\n";
}

include('includes/application_bottom.php');
?>