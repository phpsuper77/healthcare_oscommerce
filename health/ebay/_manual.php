<?php
chdir('..');
$HTTP_SERVER_VARS['SCRIPT_FILENAME'] = preg_replace('/\/([^\/]+?)$/','/',dirname(__FILE__)).basename(__FILE__);
require('includes/application_top.php');
require('ebay/core.php'); 

$var_known_func = array( 'getsellerlist' => 'Download product list from ebay',
                         'products_maintain' => 'Make delta statuses from actual and remote products',
                         'products_process' => 'Process generated (products_maintain) delta statuses',
                         'gettransactions' => 'Get ebay transactions aka Orders',
                         'sendorderpaid' => 'Set pay flag on ebay Side and give positive feedback for buyer',
                         'sendordershipped' => 'send shipped flag and tracking number',
                         'refill_ebayinfo' => 'get some info about ebay site'
                       );

$do_action = '';
if ( ebay_core::isCLI() ) {
  if (!empty($argv[1]) && in_array($argv[1], array_keys($var_known_func) ) ) {
    $do_action = $argv[1];
  }
}else{
  $do_action = isset( $_POST['action'] )?$_POST['action']:'';
}

if ( ebay_core::isCLI() ) {
  if ( count($argv)==1 || (empty($do_action)) ) {
    echo "Usage: {$argv[0]} [action]\nActions list\n";
    foreach( $var_known_func as $call=>$desc ) {
      echo "\t$call - $desc\n";
    }
  }
}else{
 $func_list = array( array('id'=>'','text'=>'') );
 foreach( $var_known_func as $call=>$desc ) {
   $func_list[] = array('id'=>$call, 'text'=> $call.' - '.$desc);
 }
 echo tep_draw_form('ebay_manual_control', tep_href_link( 'ebay/'.basename($PHP_SELF) ));
 echo tep_draw_pull_down_menu( 'action', $func_list );
 
 echo '<input type="submit"></form>';
}

switch ($do_action){
  case 'getsellerlist':
    $ep = new ebay_products();
    $ep->GetSellerList();
  break;
  case 'products_maintain':
    $ep = new ebay_products();
    $ep->maintain();
  break;
  case 'products_process':
    $ep = new ebay_products();
    $ep->process_diffs();
  break;
  case 'gettransactions':
    $trans = new ebay_transaction();
    $trans->GetSellerTransactions();
  break;
  case 'sendorderpaid':
    $trans = new ebay_transaction();
    $trans->sendOrderPaid();
  break;
  case 'sendordershipped':
    $trans = new ebay_transaction();
    $trans->sendOrderShipped();
  break;
  case 'refill_ebayinfo':
    $details = new ebay_details();
    $details->collect();
  break;
}

?>