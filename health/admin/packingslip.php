<?php
/*
  $Id: packingslip.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
  $order_comment = '';
  $orders_status_history = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$oID . "' order by orders_status_history_id limit 1");
  if ($orders_res = tep_db_fetch_array($orders_status_history)){
    $order_comment = $orders_res['comments'];
    if ( preg_match('/^Google Checkout/i', $order_comment ) ) $order_comment='';
  };

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER .  $HTTP_GET_VARS['oID']; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10" onload="window.focus();">
<?php
$template_name = 'Original'; // By default
$query_template_name = tep_db_fetch_array(tep_db_query("select affiliate_template from " . TABLE_AFFILIATE . " a, " . TABLE_AFFILIATE_SALES . " a2s where a2s.affiliate_id = a.affiliate_id and a2s.affiliate_orders_id = '" . (int)$oID . "'"));
if (is_array($query_template_name) && $query_template_name['affiliate_template'] != '') {
  $template_name = $query_template_name['affiliate_template'];
}
?>
<!-- body_text //-->
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr> 
    <td align="center" class="main"><table align="center" width="600" border="0" cellspacing="0" cellpadding="5">
      <tr  id="print_panel" name="print_panel"> 
        <td valign="top" align="left" class="main"><script language="JavaScript">
        function showButtons(){ 
          document.getElementById('print_panel').style.display='';
        }   
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:document.getElementById(\'print_panel\').style.display=\'none\';window.print();window.setTimeout(\'showButtons()\', 10000);" onMouseOut=document.imprim.src="<?php echo DIR_WS_TEMPLATES . $template_name . '/images/printimage.gif'; ?>" onMouseOver=document.imprim.src="<?php echo DIR_WS_TEMPLATES . $template_name . '/images/printimage_over.gif'; ?>"><img src="<?php echo DIR_WS_TEMPLATES . $template_name . '/images/printimage.gif'; ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><?=tep_image(DIR_WS_TEMPLATES . $template_name . '/images/close_window.jpg');?></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left"> 
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
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
            <td colspan="2" align="center" class="titleHeading"><b><?php echo LABEL_ORDER_NUMBER . $HTTP_GET_VARS['oID']; ?></b></td>
          </tr>
          <tr align="left"> 
            <td colspan="2" class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td align="center" valign="top"  width="50%"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="main"> 
                <td class="main"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
              </tr>
              <tr class="main"> 
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr class="main"> 
                <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr class="main"> 
                <td class="main"><?php echo '&nbsp;<b>Telephone#</b>' . ' &nbsp;' . $order->customer['telephone']; ?></td>
              </tr>
              <tr class="main"> 
                <td class="main"><?php echo '&nbsp;<b>eMail Address:</b>' . ' &nbsp;' . $order->customer['email_address']; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top" width="50%"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="main"> 
                <td class="main"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
              </tr>
              <tr class="main"> 
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr><td valign="top" class="main"><?php
               if( defined('INVOICE_RETURN_ADDRESS') && INVOICE_RETURN_ADDRESS!='' ) {
                 echo '<br><b>'. TEXT_RETURN_ADDRESS.'</b> '. INVOICE_RETURN_ADDRESS.'';
               }
               if ( !empty($order_comment) ) {
                 echo '<br><b>'. TEXT_ORDER_COMMENTS.'</b> '. $order_comment.'';
               } 
              ?></td></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
  </tr>
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
        <td class="main"><?php echo $order->info['payment_method']; ?></td>
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
          </tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr class="dataTableRow">' . "\n" .
           '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (sizeof($order->products[$i]['attributes']) > 0) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . str_replace(array('&amp;nbsp;', '&lt;b&gt;', '&lt;/b&gt;', '&lt;br&gt;'), array('&nbsp;', '<b>', '</b>', '<br>'), htmlspecialchars($order->products[$i]['attributes'][$j]['option'])) . ($order->products[$i]['attributes'][$j]['value'] ? ': ' . htmlspecialchars($order->products[$i]['attributes'][$j]['value']) : '');
          echo '</i></small></nobr>';
        }
      }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '      </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
