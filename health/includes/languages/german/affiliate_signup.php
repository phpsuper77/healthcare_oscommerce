<?php
/*
  $Id: affiliate_signup.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Partnerprogramm');
define('HEADING_TITLE', 'Anmeldung bei unserem Partnerprogramm');

define('MAIL_AFFILIATE_SUBJECT', 'Willkommen zum Partnerprogramm von' . STORE_NAME);
define('MAIL_AFFILIATE_HEADER', 'Verehrte(r) Partner(in)

Vielen Dank für Ihre Anmeldung bei unserem Partnerprogramm.

Ihre Anmeldeinformationen:
**************************

');
define('MAIL_AFFILIATE_ID', 'Ihre Partner-ID ist: ');
define('MAIL_AFFILIATE_USERNAME', 'Ihr Benutzername ist: ');
define('MAIL_AFFILIATE_PASSWORD', 'Ihr Passwort ist: ');
define('MAIL_AFFILIATE_LINK', 'Melden Sie sich hier an: ');
define('MAIL_AFFILIATE_FOOTER', 'Wir freuen uns auf eine gute Zusammenarbeit mit Ihnen!

Ihr Partnerprogramm-Team');

define('ENTRY_FROM_EMAIL_ADDRESS', 'Von E-Mail-Adresse:');
define('ENTRY_FROM_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_FROM_EMAIL_ADDRESS_CHECK_ERROR', 'Ihre E-Mail-Adresse von scheint ungültig zu sein. Bitte nehmen Sie entsprechende Änderungen vor.');
define('ENTRY_FROM_EMAIL_ADDRESS_ERROR', 'Ihre E-Mail-Adresse von muss mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_AFFILIATE_STORE_NAME', 'Shop-Name:');
define('ENTRY_AFFILIATE_STORE_NAME_ERROR', 'Fehler im Shop-Namen.');
define('ENTRY_AFFILIATE_STORE_NAME_TEXT', '');

?>