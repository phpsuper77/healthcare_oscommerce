<?php
/*
  $Id: password_forgotten.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Anmelden');
define('NAVBAR_TITLE_2', 'Passwort vergessen');

define('HEADING_TITLE', 'Wie war noch mal mein Passwort?');

define('TEXT_MAIN', 'Sollten Sie Ihr Passwort nicht mehr wissen, geben Sie bitte unten Ihre E-Mail-Adresse ein, um ein neues Passwort per E-Mail zu erhalten.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'Fehler: Die eingegebene E-Mail-Adresse ist nicht registriert. Bitte versuchen Sie es noch einmal.');

define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Ihr neues Passwort.');
define('EMAIL_PASSWORD_REMINDER_BODY', '&Uuml;ber die Adresse ' . $_SERVER['REMOTE_ADDR'] . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort f&uuml;r \'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n");

define('SUCCESS_PASSWORD_SENT', 'Erfolg: Ein neues Passwort wurde per E-Mail verschickt.');
?>
