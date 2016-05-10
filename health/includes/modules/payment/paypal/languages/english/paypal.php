<?php
/*
  $Id: paypal.php,v 1.1.1.1 2005/12/03 21:36:12 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  //begin ADMIN text
  define('HEADING_ADMIN_TITLE', 'PayPal Instant Payment Notifications');
  define('HEADING_PAYMENT_STATUS', 'Status');
  define('TEXT_ALL_IPNS', 'All');
  define('TEXT_INFO_PAYPAL_IPN_HEADING', 'PayPal IPN');
  define('TABLE_HEADING_ACTION', 'Action');
  define('TEXT_DISPLAY_NUMBER_OF_TRANSACTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> IPN\'s)');

  //shared with TransactionSummaryLogs
  define('TABLE_HEADING_DATE', 'Date');
  define('TABLE_HEADING_DETAILS', 'Details');
  define('TABLE_HEADING_PAYMENT_STATUS', 'Status');
  define('TABLE_HEADING_PAYMENT_GROSS', 'Gross');
  define('TABLE_HEADING_PAYMENT_FEE', 'Fee');
  define('TABLE_HEADING_PAYMENT_NET_AMOUNT', 'Net Amount');

  //TransactionSummaryLogs
  define('TABLE_HEADING_TXN_ACTIVITY', 'Transaction Activity');
  define('IMAGE_BUTTON_TXN_ACCEPT', 'Accept');

  //AcceptOrder
  define('SUCCESS_ORDER_ACCEPTED', 'Order Accepted!');
  define('ERROR_UNAUTHORIZED_REQUEST', 'Unauthorized Request!');
  define('ERROR_ORDER_UNPAID', 'Payment has not been Completed!');

  //Template Page Titles
  define('TEXT_NO_IPN_HISTORY', 'No PayPal Transaction Information Available (%s)');
  define('HEADING_DETAILS_TITLE', 'Transaction Details');
  define('HEADING_ITP_TITLE', 'IPN Test Panel');
  define('HEADING_ITP_HELP_TITLE', 'IPN Test Panel - Guide');
  define('HEADING_HELP_CONTENTS_TITLE', 'Help Contents');
  define('HEADING_HELP_CONFIG_TITLE', 'Configuration Guide');
  define('HEADING_HELP_FAQS_TITLE', 'Frequently Asked Questions');
  define('HEADING_ITP_RESULTS_TITLE', 'IPN Test Panel - Results');

  //IPN Test Panel
  define('IMAGE_ERROR', 'Error icon');

  define('EMAIL_SEPARATOR', "------------------------------------------------------");

  define('UNKNOWN_TXN_TYPE', 'Unknown Transaction Type');
  define('UNKNOWN_TXN_TYPE_MSG', 'An unknown transaction (%s) occurred from ' . $_SERVER['REMOTE_ADDR'] . "\nAre you running any tests?");

  define('UNKNOWN_POST', 'Unknown Post');
  define('UNKNOWN_POST_MSG', "An unknown POST from %s was received.\nAre you running any tests?");

  define('CONNECTION_TYPE', 'Connection Type');
  define('CONNECTION_TYPE_MSG', "curl: %s transport: %s domain: %s port: %s ");


  define('PAYPAL_RESPONSE', 'PayPal Response');
  define('PAYPAL_RESPONSE_MSG', "%s");

  define('EMAIL_RECEIVER', 'Email and Business ID config');
  define('EMAIL_RECEIVER_MSG', "Store Configuration Settings\nPrimary PayPal Email Address(s): %s\nBusiness IDs: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s");
  define('EMAIL_RECEIVER_ERROR_MSG', "Store Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n\nPayPal Transaction ID: %s");

  define('TXN_DUPLICATE', 'Duplicate Transaction');
  define('TXN_DUPLICATE_MSG', "A duplicate IPN transaction (%s) has been received.\nPlease check your PayPal Account");

  define('IPN_TXN_INSERT', 'IPN INSERTED');
  define('IPN_TXN_INSERT_MSG', "IPN %s has been inserted");

  define('CART_TEST', 'Cart Test');
  define('CART_TEST_MSG', "Store (converted) order total: %s %s\nPayPal MC Total: %s %s");
  define('CART_TEST_ERR_MSG', "Invalid Cart Test\n".CART_TEST_MSG);

  define('CHECK_TXN_SIGNATURE', 'Validate '.MODULE_PAYMENT_PAYPAL_AGENT.' Transaction Signature');
  define('CHECK_TXN_SIGNATURE_MSG', "Incorrect Signature\nPayPal: %s\nosC: %s");

  define('CHECK_TOTAL', 'Validate Total Transaction Amount');
  define('CHECK_TOTAL_MSG', "Incorrect Total\nPayPal: %s\nSession: %s");

  define('DEBUG', MODULE_PAYMENT_PAYPAL_AGENT.' Debug Email Notification');
  define('DEBUG_MSG', "%s");

  define('PAYMENT_SEND_MONEY_DESCRIPTION', 'Money Received');
  define('PAYMENT_SEND_MONEY_DESCRIPTION_MSG', "You have received a payment of %s %s \n".EMAIL_SEPARATOR."\nThis payment was sent by someone from the PayPal website, using the Send Money tab");

  define('PAYPAL_AUCTION','Ebay Auction');
  define('PAYPAL_AUCTION_MSG','You have received an Ebay/PayPal Auction Instant Payment Notification, please login to your osCommerce Administration for further details.');

  define('TEST_COMPLETE', 'Test Complete');
  define('TEST_INCOMPLETE', 'Invalid Test');
  define('TEST_INCOMPLETE_MSG', "An error has occured, mostly likely because the Custom field in the IPN Test Panel did not have a valid transaction id.\n");

  define('HTTP_ERROR', 'HTTP Error');
  define('HTTP_ERROR_MSG', "An HTTP Error occured during authentication\n".EMAIL_SEPARATOR."\ncurl: %s transport: %s domain: %s port: %s");

  define('IPN_EMAIL', 'Attention!');
  define('IPN_EMAIL_MSG', "This is email has NOT been sent by PayPal.\n\nYou have received this email via the osCommerce ".MODULE_PAYMENT_PAYPAL_AGENT." Contribution\n\nTo discontinue receiving this notice disable 'Debug Email Notifications' in your osCommerce PayPal configuration panel.");
?>