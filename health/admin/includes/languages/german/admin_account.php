<?php
/*
  $Id: admin_account.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Admin-Account');

define('TABLE_HEADING_ACCOUNT', 'Mein Account');

define('TEXT_INFO_FULLNAME', '<b>Name: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Vorname: </b>');
define('TEXT_INFO_LASTNAME', '<b>Nachname: </b>');
define('TEXT_INFO_EMAIL', '<b>E-Mail-Adresse: </b>');
define('TEXT_INFO_PASSWORD', '<b>Passwort: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Versteckt-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Passwortbest&auml;tigung: </b>');
define('TEXT_INFO_CREATED', '<b>Account erstellt am: </b>');
define('TEXT_INFO_LOGDATE', '<b>Letzter Zugriff: </b>');
define('TEXT_INFO_LOGNUM', '<b>Log-Nummer: </b>');
define('TEXT_INFO_GROUP', '<b>Gruppenlevel: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Die E-Mail-Adresse existiert bereits! Bitte versuchen Sie es noch einmal.</font>');
define('TEXT_INFO_MODIFIED', 'Modifiziert: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Account editieren ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Passwortbest&auml;tigung ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Passwort:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>ERROR:</b> Das Passwort ist unkorrekt!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Klicken Sie den <b>Editieren</b>-Button unten, um Ihren Account bearbeiten zu k&ouml;nnen.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br><b>WARNUNG:</b><br>Hallo <b>%s</b>, Sie sind hier zum erstem Mal. Wir empfehlen Ihnen Ihr Passwort zu &auml;ndern!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br><b>WARNUNG:</b><br>Hallo <b>%s</b>, wir empfehlen Ihnen Ihre E-Mail (<font color="red">admin@localhost</font>) und Ihr Passwort zu &auml;ndern!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Alle Felder sind auszuf&uuml;llen. Klicken Sie speichern, um aktiviert zu werden.');

define('JS_ALERT_FIRSTNAME',        '- Notwendige Angabe: Vorname \n');
define('JS_ALERT_LASTNAME',         '- Notwendige Angabe: Nachname \n');
define('JS_ALERT_EMAIL',            '- Notwendige Angabe: E-Mail-Adresse \n');
define('JS_ALERT_PASSWORD',         '- Notwendige Angabe: Passwort \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Vornamenlänge muss über ');
define('JS_ALERT_LASTNAME_LENGTH',  '- Nachnamenlänge muss über ');
define('JS_ALERT_PASSWORD_LENGTH',  '- Passwortnlänge muss über ');
define('JS_ALERT_EMAIL_FORMAT',     '- Das Format der E-Mail-Adresse ist unwirksam! \n');
define('JS_ALERT_EMAIL_USED',       '- Die E-Mail-Addresse existiert bereits! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- Passwort stimmt mit dem der Bestätigung nicht überein! \n');

define('ADMIN_EMAIL_SUBJECT', 'Pers&ouml;nliche Angaben bearbeiten');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'Ihre pers&ouml;nlichen Angaben und Ihr Passwort wurden ge&auml;ndert. Falls diese &Auml;nderung ohne Ihr Wissen vorgenommen worden sind, bitte nehmen Sie dringend mit dem Administrator Kontakt!' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Bitte auf diese automatische Nachricht nicht antworten!'); 
?>
