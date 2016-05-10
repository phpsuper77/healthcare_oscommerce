<?php
/*
  $Id: checkout_process.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo STORE_NAME; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<style type="text/css">
body {background-color:#FFFFFF;}
body, td, div {font-family: verdana, arial, sans-serif;}
</style>
</head>
<body onload="return document.paypal_payment_info.submit();">
<?php echo "\n".tep_draw_form('paypal_payment_info', $this->form_paypal_url, 'post'); ?>
<table cellpadding="0" width="100%" height="100%" cellspacing="0" style="border:1px solid #003366;">
  <tr><td align="middle" style="height:100%; vertical-align:middle;">
    <div><?php if (tep_not_null(MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO)) echo tep_image(DIR_WS_IMAGES . MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO); ?></div>
    <div style="color:#003366"><h1><?php echo MODULE_PAYMENT_PAYPAL_TEXT_TITLE_PROCESSING . tep_image(DIR_WS_MODULES .'payment/paypal/images/period_ani.gif'); ?></h1></div>
    <div style="margin:10px;padding:10px;"><?php echo MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION_PROCESSING?></div>
    <div style="margin:10px;padding:10px;"><?php echo tep_image_submit('button_ppcheckout.gif', MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT); ?></div>
  </td></tr>
</table>
<?php echo $this->formFields() . "\n". '</form>' . "\n"; ?>
</body></html>
<?php require_once(DIR_WS_MODULES . 'payment/paypal/application_bottom.php'); ?>
