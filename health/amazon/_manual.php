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

$testmode = isset($_POST['testmode']);
$tofile = isset($_POST['tofile']);

if ( isset($_POST['action']) ) {
  switch($_POST['action']){
    case "poll":
      if ( !$testmode ) Launcher::amazonPoll();
    break;
    case "product_feed":
      Launcher::postProductFeed('products', $testmode);
    break;
    case "inventory_feed":
      Launcher::postProductFeed('inventory', $testmode);
    break;
    case "price_feed":
      Launcher::postProductFeed('price', $testmode);
    break;
    case "image_feed":
      Launcher::postProductFeed('images', $testmode);
    break;
    case "order_import_file":
      Launcher::orderImportFromFile();
    break;
    case "order_import":
      Launcher::orderImport($testmode);
    break;
    case "check_remote_reports":
      //amazon_check_remote_reports( $tofile );
    break;
    case "send_shipped":
      Launcher::sendShipped( $testmode );
    break;
    default: break;
  }
}

$_amazon_fws->shutdown();

?>
<form method="post">
  Test mode <input type="checkbox" name="testmode" <?php echo ($testmode?' checked':'');?>> <br>
  Check remote reports download first one to file <input type="checkbox" name="tofile" <?php echo ($tofile?' checked':'');?>> <br>
  <select name="action">
    <option value="poll">Poll status for sended</option>
    <option value="product_feed">Run post product feed</option>
    <option value="inventory_feed">Run post inventory feed</option>
    <option value="price_feed">Run post price feed</option>
    <option value="image_feed">Run post Image feed</option>

    <option value="order_import_file">Make amazon orders from file</option>

    <option value="order_import">Get amazon orders</option>
    <option value="send_shipped">Run post shipping feed</option>
    
  </select>
  <input type="submit">
</form>
<?php
echo '<pre>'; var_dump( memory_get_usage() ); echo '</pre>';
include('includes/application_bottom.php');
?>
