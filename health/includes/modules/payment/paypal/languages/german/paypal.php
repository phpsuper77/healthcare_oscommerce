<?php
/*
  $Id: paypal.php,v 1.1.1.1 2005/12/03 21:36:12 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/

  //begin ADMIN text
  define('HEADING_ADMIN_TITLE', 'PayPal Sofortige Zahlungsbenachrichtigungen');
  define('HEADING_PAYMENT_STATUS', 'Status');
  define('TEXT_ALL_IPNS', 'Alle');
  define('TEXT_INFO_PAYPAL_IPN_HEADING', 'PayPal IPN');
  define('TABLE_HEADING_ACTION', 'Action');
  define('TEXT_DISPLAY_NUMBER_OF_TRANSACTIONS', 'Zeige <b>%d</b> bis <b>%d</b> (von <b>%d</b> IPN\'s)');

  //shared with TransactionSummaryLogs
  define('TABLE_HEADING_DATE', 'Datum');
  define('TABLE_HEADING_DETAILS', 'Details');
  define('TABLE_HEADING_PAYMENT_STATUS', 'Status');
  define('TABLE_HEADING_PAYMENT_GROSS', 'Gross');
  define('TABLE_HEADING_PAYMENT_FEE', 'Gebhr');
  define('TABLE_HEADING_PAYMENT_NET_AMOUNT', 'Nettowert');

  //TransactionSummaryLogs
  define('TABLE_HEADING_TXN_ACTIVITY', 'Transaction Activity');
  define('IMAGE_BUTTON_TXN_ACCEPT', 'Akzeptieren');

  //AcceptOrder
  define('SUCCESS_ORDER_ACCEPTED', 'Bestellung akzeptiert!');
  define('ERROR_UNAUTHORIZED_REQUEST', 'Unauthorisierte Anfrage!');
  define('ERROR_ORDER_UNPAID', 'Zahlung wurde nicht abgeschlossen!');

  //Template Page Titles
  define('TEXT_NO_IPN_HISTORY', 'Keine PayPal Transaktions Information verfgbar (%s)');
  define('HEADING_DETAILS_TITLE', 'Transaktions-Details');
  define('HEADING_ITP_TITLE', 'IPN Test Panel');
  define('HEADING_ITP_HELP_TITLE', 'IPN Test Panel - Guide');
  define('HEADING_HELP_CONTENTS_TITLE', 'Help Contents');
  define('HEADING_HELP_CONFIG_TITLE', 'Configuration Guide');
  define('HEADING_HELP_FAQS_TITLE', 'Frequently Asked Questions');
  define('HEADING_ITP_RESULTS_TITLE', 'IPN Test Panel - Results');

  //IPN Test Panel
  define('IMAGE_ERROR', 'Error icon');

  define('EMAIL_SEPARATOR', "------------------------------------------------------");

  define('UNKNOWN_TXN_TYPE', 'Unbekannter Transaktionstyp');
  define('UNKNOWN_TXN_TYPE_MSG', 'Eine unbekannte Transaktion (%s) wurde durchgefhrt von ' . $_SERVER['REMOTE_ADDR'] . "\nFhren Sie einige Tests durch?");

  define('UNKNOWN_POST', 'Unbekannte Nachricht');
  define('UNKNOWN_POST_MSG', "Eine unbekannte NACHRICHT von %s wurde erhalten.\nFhren Sie einige Tests durch?");

  define('CONNECTION_TYPE', 'Verbindungstyp');
  define('CONNECTION_TYPE_MSG', "curl: %s transport: %s domain: %s port: %s ");


  define('PAYPAL_RESPONSE', 'PayPal Antwort');
  define('PAYPAL_RESPONSE_MSG', "%s");

  define('EMAIL_RECEIVER', 'Email und Business ID Konfiguration');
  define('EMAIL_RECEIVER_MSG', "Shop Konfigurationseinstellungen\nErste PayPal Email Adresse: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Konfigurationseinstellungen\nErste PayPal Email Adresse: %s\nBusiness ID: %s");
  define('EMAIL_RECEIVER_ERROR_MSG', "Shop Konfigurationseinstellungen\nErste PayPal Email Adresse: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Konfigurationseinstellungen\nErste PayPal Email Adresse: %s\nBusiness ID: %s\n\nPayPal Transaction ID: %s");

  define('TXN_DUPLICATE', 'Doppelte Transaktion');
  define('TXN_DUPLICATE_MSG', "Eine doppelte IPN Transaktion (%s) wurde empfangen.\nBitte berprfen Sie Ihr PayPal Konto");

  define('IPN_TXN_INSERT', 'IPN EINGEF�T');
  define('IPN_TXN_INSERT_MSG', "IPN %s wurde eingefgt");

  define('CART_TEST', 'Warenkorb Test');
  define('CART_TEST_MSG', "Store (converted) order total: %s %s\nPayPal MC Total: %s %s");
  define('CART_TEST_ERR_MSG', "Invalid Cart Test\n".CART_TEST_MSG);

  define('CHECK_TXN_SIGNATURE', 'Validate '.MODULE_PAYMENT_PAYPAL_AGENT.' Transaction Signature');
  define('CHECK_TXN_SIGNATURE_MSG', "Incorrect Signature\nPayPal: %s\nosC: %s");

  define('CHECK_TOTAL', 'Validate Total Transaction Amount');
  define('CHECK_TOTAL_MSG', "Incorrect Total\nPayPal: %s\nSession: %s");

  define('DEBUG', MODULE_PAYMENT_PAYPAL_AGENT.' Debug Email Notification');
  define('DEBUG_MSG', "%s");

  define('PAYMENT_SEND_MONEY_DESCRIPTION', 'Zahlung erhalten');
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