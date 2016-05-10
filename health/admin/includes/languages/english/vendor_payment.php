<?php
/*
  $Id: vendor_payment.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  OSC-Affiliate
  
  Contribution based on:
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Vendor Payment');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_STATUS','Status:');

define('TEXT_ALL_PAYMENTS','All Payments');
define('TEXT_NO_PAYMENT_HISTORY', 'No Payment History Available');


define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_VENDOR_NAME', 'Vendor');
define('TABLE_HEADING_PAYMENT','Payment (incl.)');
define('TABLE_HEADING_NET_PAYMENT','Payment (excl.)');
define('TABLE_HEADING_DATE_BILLED','Date Billed');
define('TABLE_HEADING_NEW_VALUE', 'New Value');
define('TABLE_HEADING_OLD_VALUE', 'Old Value');
define('TABLE_HEADING_VENDOR_NOTIFIED', 'Vendor Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('TEXT_DATE_PAYMENT_BILLED','Billed:');
define('TEXT_DATE_ORDER_LAST_MODIFIED','Last modified:');
define('TEXT_VENDOR_PAYMENT','Vendor earned payment');
define('TEXT_VENDOR_BILLED','Paymentday');
define('TEXT_VENDOR','Vendor');
define('TEXT_INFO_DELETE_INTRO','Are you sure you want to delete this payment?');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> payments)');

define('TEXT_VENDOR_PAYING_POSSIBILITIES','You can pay your Vendor by:');
define('TEXT_VENDOR_PAYMENT_CHECK','Check:');
define('TEXT_VENDOR_PAYMENT_CHECK_PAYEE','Payable to:');
define('TEXT_VENDOR_PAYMENT_PAYPAL','PayPal:');
define('TEXT_VENDOR_PAYMENT_PAYPAL_EMAIL','PayPal Acount Email:');
define('TEXT_VENDOR_PAYMENT_BANK_TRANSFER','Banktransfer:');
define('TEXT_VENDOR_PAYMENT_BANK_NAME','Bank Name:');
define('TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NAME','Account Name:');
define('TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER','Account Number:');
define('TEXT_VENDOR_PAYMENT_BANK_BRANCH_NUMBER','ABA/BSB number (branch number):');
define('TEXT_VENDOR_PAYMENT_BANK_SWIFT_CODE','SWIFT Code:');

define('TEXT_INFO_HEADING_DELETE_PAYMENT','Delete Payment');

define('IMAGE_VENDOR_BILLING','Start Billing Engine');

define('ERROR_PAYMENT_DOES_NOT_EXIST','Payment does not exist');


define('SUCCESS_BILLING','Your Vendors have been sucessfully billed');
define('SUCCESS_PAYMENT_UPDATED','The Payment status has been updated successfully');

define('PAYMENT_STATUS','Payment Status');
define('PAYMENT_NOTIFY_VENDOR', 'Notify Vendor');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Payment Update');
define('EMAIL_TEXT_VENDOR_PAYMENT_NUMBER', 'Payment Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Date billed');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your payment has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'A new invoice arrived to your payments' . "\n");
define('TEXT_VENDOR_HEADER', 'Your Payments:');

define('TABLE_HEADING_DATE', 'Payment Date');
define('TABLE_HEADING_PAYMENT', 'Vendor Earnings');
define('TABLE_HEADING_STATUS', 'Payment Status');
define('TABLE_HEADING_PAYMENT_ID','Payment-ID');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> payments)');
define('TEXT_INFORMATION_PAYMENT_TOTAL', 'Your current earnings amount to:');
define('TEXT_NO_PAYMENTS', 'No payments have been recorded yet.');

?>
