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

define('HEADING_TITLE', 'Lieferantenbezahlung');
define('HEADING_TITLE_SEARCH', 'Suche:');
define('HEADING_TITLE_STATUS','Status:');

define('TEXT_ALL_PAYMENTS','Alle Bezahlungen');
define('TEXT_NO_PAYMENT_HISTORY', 'Es gibt keine Bezahlunghistorie');


define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_VENDOR_NAME', 'Lieferant');
define('TABLE_HEADING_PAYMENT','Bezahlung (inkl.)');
define('TABLE_HEADING_NET_PAYMENT','Bezahlung (exkl.)');
define('TABLE_HEADING_DATE_BILLED','Rechnungsausstelldatum');
define('TABLE_HEADING_NEW_VALUE', 'Neuer Wert');
define('TABLE_HEADING_OLD_VALUE', 'Alter Wert');
define('TABLE_HEADING_VENDOR_NOTIFIED', 'Lieferant benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'Hinzugefügt am');

define('TEXT_DATE_PAYMENT_BILLED','Rechnung ausgestellt am:');
define('TEXT_DATE_ORDER_LAST_MODIFIED','Letzte Modifizierung:');
define('TEXT_VENDOR_PAYMENT','Lieferantenbezahlung');
define('TEXT_VENDOR_BILLED','Zahlungseingang');
define('TEXT_VENDOR','Lieferant');
define('TEXT_INFO_DELETE_INTRO','Sind Sie sicher, diese Bezahlung löschen zu wollen?');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Bezahlungen)');

define('TEXT_VENDOR_PAYING_POSSIBILITIES','Dem Lieferanten bezahlen über:');
define('TEXT_VENDOR_PAYMENT_CHECK','Check:');
define('TEXT_VENDOR_PAYMENT_CHECK_PAYEE','Bezahlbar an:');
define('TEXT_VENDOR_PAYMENT_PAYPAL','PayPal:');
define('TEXT_VENDOR_PAYMENT_PAYPAL_EMAIL','PayPal Account Email:');
define('TEXT_VENDOR_PAYMENT_BANK_TRANSFER','Lastschrift:');
define('TEXT_VENDOR_PAYMENT_BANK_NAME','Bankname:');
define('TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NAME','Kontoname:');
define('TEXT_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER','Kontonummer:');
define('TEXT_VENDOR_PAYMENT_BANK_BRANCH_NUMBER','ABA/BSB Nummer:');
define('TEXT_VENDOR_PAYMENT_BANK_SWIFT_CODE','SWIFT Code:');

define('TEXT_INFO_HEADING_DELETE_PAYMENT','Bezahlung löschen');

define('IMAGE_VENDOR_BILLING','Rechnungsausstellung starten');

define('ERROR_PAYMENT_DOES_NOT_EXIST','Die Bezahlung existiert nicht');


define('SUCCESS_BILLING','Die Rechnungen wurden für Ihre Lieferanten erfolgreich ausgestellt');
define('SUCCESS_PAYMENT_UPDATED','Der Bezahlungsstatus wurde erfolgreich aktualisiert');

define('PAYMENT_STATUS','Bezahlungsstatus');
define('PAYMENT_NOTIFY_VENDOR', 'Lieferanten benachrichtigen');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Bezahlungsaktualisierung');
define('EMAIL_TEXT_VENDOR_PAYMENT_NUMBER', 'Bezahlungsnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailierte Rechnung:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Rechnungsausstelldatum');
define('EMAIL_TEXT_STATUS_UPDATE', 'Ihre Bezahlung wurde zum folgenden Status aktualisiert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bitte antworten Sie auf diese Email, wenn Sie Fragen haben.' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'Sie erhielten eine neue Rechnung' . "\n");
define('TEXT_VENDOR_HEADER', 'Ihre Bezahlungen:');

define('TABLE_HEADING_DATE', 'Bezahlungsdatum');
define('TABLE_HEADING_PAYMENT', 'Lieferanteneinkommen');
define('TABLE_HEADING_STATUS', 'Bezahlungsstatus');
define('TABLE_HEADING_PAYMENT_ID','Bezahlungs-ID');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Bezahlungen)');
define('TEXT_INFORMATION_PAYMENT_TOTAL', 'Ihr aktueller Einkommenszuwachs:');
define('TEXT_NO_PAYMENTS', 'Keine Bezahlung wurde letzte Zeit aufgenommen.');

?>
