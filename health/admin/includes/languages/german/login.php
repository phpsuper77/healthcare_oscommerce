<?php
/*
  $Id: login.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Bestellung');
  define('HEADING_TITLE', 'On-line zu bestellen ist einfach.');
  define('TEXT_STEP_BY_STEP', 'Wir werden Sie gerne Schritt f&uuml;r Schritt in allen Prozessen begleiten.');
} else {
  define('NAVBAR_TITLE', 'Login');
  define('HEADING_TITLE', 'Willkommen, bitte melden Sie sich an');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_RETURNING_ADMIN', 'Login-Panel:');
define('HEADING_PASSWORD_FORGOTTEN', 'Passwort vergessen:');
define('TEXT_RETURNING_ADMIN', 'Nur f&uuml;r Personal!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail-Adresse:');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_FIRSTNAME', 'Vorname:');
define('IMAGE_BUTTON_LOGIN', 'Verschicken');

define('TEXT_PASSWORD_FORGOTTEN', 'Passwort vergessen?');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Unkorrekter Benutzername oder Passwort!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Vorname und Passwort stimmen nicht &uuml;berein!');
define('TEXT_FORGOTTEN_FAIL', 'Sie haben bereits 3 Male versucht. Aus Sicherungsgr&uuml;nden kontaktieren Sie bitte mit Ihrem Web-Administrator, um &uuml;ber ein neues Passwort anzufragen.<br>&nbsp;<br>&nbsp;');
define('TEXT_FORGOTTEN_SUCCESS', 'Das neue Passwort wurde auf Ihre E-Mail-Adresse gesendet. Bitte pr&uuml;fen Sie Ihre E-Mail und klicken Sie zur&uuml;ck, um den Login-Bereich wieder zu betreten.<br>&nbsp;<br>&nbsp;');

define('ADMIN_EMAIL_SUBJECT', 'Neues Passwort'); 
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'Der Zugang zum Admin-Panel ist Ihnen mit folgendem Passwort verf&uuml;gbar. Nachdem Sie den Admin-Bereich betreten haben, bitte Ihr Passwort &auml;ndern!' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Auf diese automatische Nachricht bitte nicht antworten!'); 
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'Der Zugang zum Affiliate-Panel ist Ihnen mit folgendem Passwort verf&uuml;gbar. Nachdem Sie den Affiliate-Bereich betreten haben, bitte Ihr Passwort &auml;ndern!' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Auf diese automatische Nachricht bitte nicht antworten!'); 
define('TEXT_VENDOR_LOGIN', 'Vendor login.');
?>
