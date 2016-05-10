<?php
/*
  $Id: vendor_invoice.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

  OSC-vendor

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $payments_query = tep_db_query("select * from " . TABLE_VENDOR_PAYMENT . " where vendor_payment_id = '" . $HTTP_GET_VARS['pID'] . "'");
  $payments = tep_db_fetch_array($payments_query);

  $vendor_address['firstname'] = $payments['vendor_firstname'];
  $vendor_address['lastname'] = $payments['vendor_lastname'];
  $vendor_address['street_address'] = $payments['vendor_street_address'];
  $vendor_address['suburb'] = $payments['vendor_suburb'];
  $vendor_address['city'] = $payments['vendor_city'];
  $vendor_address['state'] = $payments['vendor_state'];
  $vendor_address['country'] = $payments['vendor_country'];
  $vendor_address['postcode'] = $payments['vendor_postcode']
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></td>
        <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'logo_l.gif', 'osCommerce', '204', '50'); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><?php echo tep_draw_separator(); ?></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="top"><b><?php echo TEXT_VENDOR; ?></b></td>
            <td class="main"><?php echo tep_address_format($payments['vendor_address_format_id'], $vendor_address, 1, '&nbsp;', '<br>'); ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
	      <tr>
             <td class="main"><b><?php echo TEXT_VENDOR_PAYMENT; ?></b></td>
             <td class="main">&nbsp;<?php echo $currencies->format($payments['vendor_payment_total']); ?></td>
          </tr>
          <tr>
             <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
             <td class="main"><b><?php echo TEXT_VENDOR_BILLED; ?></b></td>
             <td class="main">&nbsp;<?php echo tep_date_short($payments['vendor_payment_date']); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER_DATE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_VALUE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION_RATE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION_VALUE; ?></td>
      </tr>
<?php
  $vendor_payment_query = tep_db_query("select * from " . TABLE_VENDOR_PAYMENT . " where vendor_payment_id = '" . $HTTP_GET_VARS['pID'] . "'");
  $vendor_payment = tep_db_fetch_array($vendor_payment_query);
  $vendor_sales_query = tep_db_query("select * from " . TABLE_VENDOR_SALES . " where vendor_payment_id = '" . $payments['vendor_payment_id'] . "' order by vendor_payment_date desc");
  while ($vendor_sales = tep_db_fetch_array($vendor_sales_query)) {
?>

      <tr class="dataTableRow">
        <td class="dataTableContent" align="right" valign="top"><?php echo $vendor_sales['vendor_orders_id']; ?></td>
        <td class="dataTableContent" align="center" valign="top"><?php echo tep_date_short($vendor_sales['vendor_date']); ?></td>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo $currencies->display_price($vendor_sales['vendor_value'], ''); ?></b></td>
        <td class="dataTableContent" align="right" valign="top"><?php echo $vendor_sales['vendor_percent']; ?><?php echo ENTRY_PERCENT; ?></td>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo $currencies->display_price($vendor_sales['vendor_payment'], ''); ?></b></td>
      </tr>
<?php
  }
?>
    </table></td>
  </tr>
  <tr>
    <td align="right" colspan="5"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="right" class="smallText"><?php echo TEXT_SUB_TOTAL; ?></td>
        <td align="right" class="smallText"><?php echo $currencies->display_price($vendor_payment['vendor_payment'], ''); ?></td>
      </tr>
      <tr>
        <td align="right" class="smallText"><?php echo TEXT_TAX; ?></td>
        <td align="right" class="smallText"><?php echo $currencies->display_price($vendor_payment['vendor_payment_tax'], ''); ?></td>
      </tr>
      <tr>
        <td align="right" class="smallText"><b><?php echo TEXT_TOTAL; ?></b></td>
        <td align="right" class="smallText"><b><?php echo $currencies->display_price($vendor_payment['vendor_payment_total'], ''); ?></b></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
