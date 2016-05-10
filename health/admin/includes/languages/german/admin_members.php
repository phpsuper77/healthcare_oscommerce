<?php
/*
  $Id: admin_members.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($HTTP_GET_VARS['gID']) {
  define('HEADING_TITLE', 'Admin-Gruppen');
} elseif ($HTTP_GET_VARS['gPath']) {
  define('HEADING_TITLE', 'Gruppen definieren');
} else {
  define('HEADING_TITLE', 'Admin-Mitglieder');
}

define('TEXT_COUNT_GROUPS', 'Gruppen: ');

define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_EMAIL', 'E-Mail-Adresse');
define('TABLE_HEADING_PASSWORD', 'Passwort');
define('TABLE_HEADING_CONFIRM', 'Passwortbest&auml;tigung');
define('TABLE_HEADING_GROUPS', 'Gruppenlevel');
define('TABLE_HEADING_CREATED', 'Account erstellt am');
define('TABLE_HEADING_MODIFIED', 'Account erstellt');
define('TABLE_HEADING_LOGDATE', 'Letzter Zugriff');
define('TABLE_HEADING_LOGNUM', 'Log-Nr');
define('TABLE_HEADING_LOG_NUM', 'Log-Nummer');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TABLE_HEADING_GROUPS_NAME', 'Gruppenname');
define('TABLE_HEADING_GROUPS_DEFINE', 'Boxen und Files w&auml;hlen');
define('TABLE_HEADING_GROUPS_GROUP', 'Level');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Kategorienpermission');


define('TEXT_INFO_HEADING_DEFAULT', 'Admin-Mitglied ');
define('TEXT_INFO_HEADING_DELETE', 'Permission l&ouml;schen ');
define('TEXT_INFO_HEADING_EDIT', 'Kategorie editieren / ');
define('TEXT_INFO_HEADING_NEW', 'Neues Admin-Mitglied ');

define('TEXT_INFO_DEFAULT_INTRO', 'Mitgliedsgruppe');
define('TEXT_INFO_DELETE_INTRO', '<nobr><b>%s</b></nobr> aus den <nobr>Admin-Mitgliedern verschieben?</nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'Sie k&ouml;nnen <nobr>%s die Gruppe nicht l&ouml;schen!</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Stellen Sie Permissionslevel hier ein: ');

define('TEXT_INFO_FULLNAME', 'Name: ');
define('TEXT_INFO_FIRSTNAME', 'Vorname: ');
define('TEXT_INFO_LASTNAME', 'Nachname: ');
define('TEXT_INFO_EMAIL', 'E-Mail-Adresse: ');
define('TEXT_INFO_PASSWORD', 'Passwort: ');
define('TEXT_INFO_CONFIRM', 'Passwortbest&auml;tigung: ');
define('TEXT_INFO_CREATED', 'Account erstellt am: ');
define('TEXT_INFO_MODIFIED', 'Account modifiziert am: ');
define('TEXT_INFO_LOGDATE', 'Letzter Zugriff: ');
define('TEXT_INFO_LOGNUM', 'Log-Nummer: ');
define('TEXT_INFO_GROUP', 'Gruppenlevel: ');
define('TEXT_INFO_ERROR', '<font color="red">Die E-Mail-Adresse existiert bereits! Bitte versuchen Sie es noch einmal.</font>');

define('JS_ALERT_FIRSTNAME', '- Notwendige Angabe: Vorname \n');
define('JS_ALERT_LASTNAME', '- Notwendige Angabe: Nachname \n');
define('JS_ALERT_EMAIL', '- Notwendige Angabe: E-Mail-Adresse \n');
define('JS_ALERT_EMAIL_FORMAT', '- Das Format der E-Mail-Adresse ist unwirksam! \n');
define('JS_ALERT_EMAIL_USED', '- Diese E-Mail-Adresse existiert bereits! \n');
define('JS_ALERT_LEVEL', '- Notwendige Angabe: Gruppenmitglied \n');

define('ADMIN_EMAIL_SUBJECT', 'Neues Admin-Mitglied');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'Der Zugriff zum Admin-Panel steht mit dem folgenden Passwort zu Ihrer Verf&uuml;gung. Falls Sie den Admin-Bereich betreten haben, bitte &auml;ndern Sie Ihr Passwort!' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Bitte auf diese automatische Nachricht nicht antworten!'); 
define('ADMIN_EMAIL_EDIT_SUBJECT', 'Admin-Mitgliedsprofil bearbeiten');
define('ADMIN_EMAIL_EDIT_TEXT', 'Hi %s,' . "\n\n" . 'Ihre pers&ouml;nlichen Angaben wurden von dem Administrator updatet.' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Bitte auf diese automatische Nachricht nicht antworten!'); 

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Admin-Gruppe ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Gruppe l&ouml;schen ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>ANMERKUNG:</b><li><b>bearbeiten:</b> den Gruppennamen bearbeiten.</li><li><b>l&ouml;schen:</b> Gruppe l&ouml;schen.</li><li><b>definieren:</b> den Gruppenzugriff definieren.</li>');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'Damit werden auch Mitglieder dieser Gruppe gel&ouml;scht. M&ouml;chten Sie <nobr><b>%s</b> Gruppe l&ouml;schen?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'Sie k&ouml;nnen diese Gruppe nicht l&ouml;schen!');
define('TEXT_INFO_GROUPS_INTRO', 'Geben Sie den einheitlichen Gruppennamen ein und klicken Sie weiter.');

define('TEXT_INFO_HEADING_GROUPS', 'Neue Gruppe');
define('TEXT_INFO_GROUPS_NAME', ' <b>Gruppenname:</b><br>Geben Sie den einheitlichen Gruppennamen ein. Danach klicken Sie weiter.<br>');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>FEHLER:</b> Der Gruppenname muss mindestens aus 5 Zeichen bestehen!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>FEHLER:</b> Der Gruppenname existiert schon!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Gruppenlevel: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Permission f&uuml;r Boxes:</b><br>Geben Sie Permission f&uuml;r die gew&auml;hlten Boxes.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Inkl. Files in: ');

define('TEXT_INFO_HEADING_DEFINE', 'Gruppe definieren');
if ($HTTP_GET_VARS['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Sie k&ouml;nnen die Filepermission f&uuml;r diese Gruppe nicht &auml;ndern.<br><br>');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Permission f&uuml;r diese Gruppe &auml;ndern, indem Boxes und Files ausgew&auml;hlt werden. Klicken Sie <b>speichern</b>, um &Auml;nderungen zu speichern.<br><br>');
}
define('TEXT_INFO_HEADING_EDIT_GROUP','Gruppe bearbeiten');
define('TEXT_INFO_EDIT_GROUP_INTRO','Nehmen Sie alle relevanten &Auml;nderungen vor');
?>
