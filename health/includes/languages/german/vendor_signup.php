<?php
/*
  $Id: vendor_signup.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Lieferantenprogramm');
define('HEADING_TITLE', 'Lieferantenprogramm - Anmelden');

define('MAIL_VENDOR_SUBJECT', 'Willkommen beim Lieferantenprogramm');
define('MAIL_VENDOR_HEADER', 'Sehr geehrter Lieferant,

thank you for joining the Vendor Program.

Your Account Information:
***********************

');
define('MAIL_VENDOR_ID', 'Ihre Lieferanten ID lautet: ');
define('MAIL_VENDOR_USERNAME', 'Ihr Lieferantenname lautet: ');
define('MAIL_VENDOR_PASSWORD', 'Ihr Passwort lautet: ');
define('MAIL_VENDOR_LINK', 'Den Link zu Ihrem Konto finden Sie hier:');
define('MAIL_VENDOR_FOOTER', 'Viel Spaß beim Warenverkauf!

Your Vendor Team');
define('ENTRY_FROM_EMAIL_ADDRESS', 'Von E-Mail Adresse:');
define('ENTRY_FROM_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_FROM_EMAIL_ADDRESS_CHECK_ERROR', 'Ihre Von E-Mail Adresse scheint ungültig zu sein - Bitte nehmen Sie entsprechende Korrekturen vor.');
define('ENTRY_FROM_EMAIL_ADDRESS_ERROR', 'Ihre Von E-Mail Adresse muss mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.'); 
define('ENTRY_VENDOR_STORE_NAME', 'Shopname:');
define('ENTRY_VENDOR_STORE_NAME_ERROR', 'Shopnamen-Fehler.');
define('ENTRY_VENDOR_STORE_NAME_TEXT', '');
define('BOX_VENDOR_INFO', 'Lieferanteninfo');
define('BOX_VENDOR_SUMMARY', 'Lieferantendaten');
define('BOX_VENDOR_ACCOUNT', 'Lieferantenkonto bearbeiten');
define('BOX_VENDOR_CLICKRATE', 'Klicks-Report');
define('BOX_VENDOR_PAYMENT', 'Bezahlungs-Report');
define('BOX_VENDOR_SALES', 'Verkaufs-Report');
define('BOX_VENDOR_BANNERS', 'Lieferantenbanners');
define('BOX_VENDOR_CONTACT', 'Kontakt mit uns');
define('BOX_VENDOR_FAQ', 'FAQ zum Lieferantenprogramm');
define('BOX_VENDOR_LOGIN', 'Lieferantenanmeldung');
define('BOX_VENDOR_LOGOUT', 'Lieferantenabmeldung');

define('ENTRY_VENDOR_PAYMENT_DETAILS', 'Bezahlbar an:');
define('ENTRY_VENDOR_ACCEPT_AGB', 'Bitte prüfen Sie hier, ob Sie die <a target="_new" href="' . tep_href_link(FILENAME_VENDOR_TERMS, '', 'SSL') . '">AGB zum Lieferantenprogramm</a> durchgelesen haben und damit einverstanden sind.');
define('ENTRY_VENDOR_AGB_ERROR', ' &nbsp;<small><font color="#FF0000">Sie benötigen unsere AGB zum Lieferantenprogramm zu akzeptieren</font></small>');
define('ENTRY_VENDOR_PAYMENT_CHECK', 'Bezahlungsscheckname:');
define('ENTRY_VENDOR_PAYMENT_CHECK_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_PAYPAL', 'PayPal Account Email:');
define('ENTRY_VENDOR_PAYMENT_PAYPAL_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_PAYPAL_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME', 'Bank Name:');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME', 'Account Name:');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER', 'Kontonummer:');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER', 'ABA/BSB Nummer:');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE', 'SWIFT Code:');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_COMPANY', 'Unternehmen');
define('ENTRY_VENDOR_COMPANY_TEXT', '');
define('ENTRY_VENDOR_COMPANY_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_COMPANY_TAXID', 'Ust-ID:');
define('ENTRY_VENDOR_COMPANY_TAXID_TEXT', '');
define('ENTRY_VENDOR_COMPANY_TAXID_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich</font></small>');
define('ENTRY_VENDOR_HOMEPAGE', 'Homepage');
define('ENTRY_VENDOR_HOMEPAGE_TEXT', '&nbsp;<small><font color="#AABBDD">erforderlich (http://)</font></small>');
define('ENTRY_VENDOR_HOMEPAGE_ERROR', '&nbsp;<small><font color="#FF0000">erforderlich (http://)</font></small>');

define('CATEGORY_PAYMENT_DETAILS', 'Sie erhalten Ihr Geld an:');
define('TEXT_VENDOR_WARNING','<font color=#ff0000>WARNUNG!!! Lieferant ist nicht aktiviert!</font>');
?>