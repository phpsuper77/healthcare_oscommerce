<?php
/*
  $Id: printorder.php,v 1.1 2003/01 xaglo
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    echo '<script>
      window.close();
    </script>';
    die();
//    $navigation->set_snapshot();
//    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $check_order_query = tep_db_query("select customers_id, payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['order_id'])) . "'");
  $check_order = tep_db_fetch_array($check_order_query);

  if ( $check_order===false || ( isset($check_order['customers_id']) && $check_order['customers_id'] != $customer_id) ) {
    echo '<script>'."\n".'window.close();'."\n".'</script>'."\n";
    die();
  }
  $payment_info = $check_order['payment_info'];

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_PRINTABLE);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . ' #' . $HTTP_GET_VARS['order_id']; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" onload="focus();">


<!-- body_text //-->
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr> 
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr id="print_panel" name="print_panel"> 
        <td valign="top" align="left" class="main"><script language="JavaScript">
        function showButtons(){ 
          document.getElementById('print_panel').style.display='';
        }        
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:document.getElementById(\'print_panel\').style.display=\'none\';window.print();window.setTimeout(\'showButtons()\', 10000);" onMouseOut=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_TEMPLATE_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><?=tep_image(DIR_WS_TEMPLATE_IMAGES . 'close_window.jpg');?></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left"> 
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
  </tr>
  <tr> 
    <td><table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
      <tr> 
        <td><table border="0" align="center" width="75%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . '' . STORE_LOGO, STORE_NAME); ?></td>
          </tr>
          <tr> 
            <td colspan="2" align="center" class="titleHeading"><b><?php echo TITLE_PRINT_ORDER . ' #' . $HTTP_GET_VARS['order_id']; ?></b></td>
          </tr>
          <tr align="left"> 
            <td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td class="main"><?php echo '<b>' . ENTRY_PAYMENT_METHOD . '</b> ' . $order->info['payment_method']; ?></td>
      </tr>
      <tr> 
        <td class="main"><?php echo $payment_info; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow"> 
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor=#000000>
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow"> 
                <td class="dataTableHeadingContent"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr> 
    <td><table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor=#000000>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow"> 
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
        <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'] . '<br>';

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<nobr><small>&nbsp;<i> - ' . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($order->products[$i]['attributes'][$j]['option'])) . ($order->products[$i]['attributes'][$j]['value'] ? ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) : '') . '</i><br></small></nobr>';
      }
    }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" colspan="7"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
