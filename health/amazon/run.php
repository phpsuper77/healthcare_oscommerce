<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

$HTTP_SERVER_VARS['SCRIPT_FILENAME'] = dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']);
chdir('../');
include('includes/application_top.php');

include('amazon/core.php');

$_amazon_fws->startup();

if ( AFWS_WEBS_STATUS=='True' ) 
{
  $scheduler = new Scheduler();
  $scheduler->run();
}

$_amazon_fws->shutdown();

include('includes/application_bottom.php');
?>
