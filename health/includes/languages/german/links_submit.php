<?php
/*
  $Id: links_submit.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Links');
define('NAVBAR_TITLE_2', 'Link absenden');

define('HEADING_TITLE', 'Link-Information');

define('TEXT_MAIN', 'Bitte f&uuml;llen Sie das folgende Formular aus, um Ihre Website hinzuzuf&uuml;gen.');

define('EMAIL_SUBJECT', 'Willkommen bei ' . STORE_NAME . ' Link-Exchange.');
define('EMAIL_GREET_NONE', 'Sehr geehrte(r) %s' . "\n\n");
define('EMAIL_WELCOME', 'Wir begr&uuml;&szlig;en Sie bei <b>' . STORE_NAME . '</b> Link-Exchange-Programm.' . "\n\n");
define('EMAIL_TEXT', 'Ihr Link wurde erfolgreich an ' . STORE_NAME . ' abgesendet. Er wird in unsere Liste hinzugef&uuml;gt, sobald wir ihn genehmigen. Sie werden per E-Mail von Ihrem Status benachrigtigt. Falls Sie im Laufe von n&auml;chsten 48 Stunden keine Nachricht erhalten, bitte nehmen Sie Kontakt mit uns.' . "\n\n");
define('EMAIL_CONTACT', 'Zur Hilfe mit unserem Link-Exchange-Programm mailen Sie bitte unser Team: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Anmerkung:</b> Diese Email-Adresse wurde von uns &uuml;ber den Link erhalten. Falls Sie Probleme haben, bitte nehmen Sie Kontakt mit ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_OWNER_SUBJECT', 'Link-Submittal bei ' . STORE_NAME);
define('EMAIL_OWNER_TEXT', 'Ein neuer Link wurde bei ' . STORE_NAME . ' abgesendet. Er ist noch nicht genehmigt. Bitte &uuml;berpr&uuml;fen Sie diesen Link und aktivisieren Sie ihn.' . "\n\n");

define('TEXT_LINKS_HELP_LINK', '&nbsp;Hilfe&nbsp;[?]');

define('HEADING_LINKS_HELP', 'Links-Hilfe');
define('TEXT_LINKS_HELP', '<b>Site-Titel:</b> Descriptiver Titel f&uuml;r Ihre Website.<br><br><b>URL:</b> Web-Adresse Ihrer Website incl. \'http://\'.<br><br><b>Kategorie:</b> Die geeigneteste Kategorie, wo Ihre Website eingeordnet wird.<br><br><b>Beschreibung:</b> Eine Kurzbeschreibung von Ihrer Website.<br><br><b>Image-URL:</b> Die gew&uuml;nschte URL des Image incl. \'http://\'. Dieses Image wird zusammen mit Ihrem Website-Link.<br>Eg: http://your-domain.com/path/to/your/image.gif <br><br><b>Name:</b> Ihr Name.<br><br><b>Email:</b> Ihre Email-Adresse. Bitte geben Sie eine valide E-Mail ein.<br><br><b>Seite:</b> Die URL Ihrer Links-Seite.<br>Eg: http://your-domain.com/path/to/your/links_page.php');
define('TEXT_CLOSE_WINDOW', '<u>Fenster schlie&szlig;en</u> [x]');

// VJ todo - move to common language file
define('CATEGORY_WEBSITE', 'Webseiten-Details');
define('CATEGORY_RECIPROCAL', 'Reciprocal Page Details');

define('ENTRY_LINKS_TITLE', 'Site-Titel:');
define('ENTRY_LINKS_TITLE_ERROR', 'Der Link-Titel muss mindestens  ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' Symbole haben.');
define('ENTRY_LINKS_TITLE_TEXT', '*');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'Die URL  muss mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Symbole haben.');
define('ENTRY_LINKS_URL_TEXT', '*');
define('ENTRY_LINKS_CATEGORY', 'Kategorie:');
define('ENTRY_LINKS_CATEGORY_TEXT', '*');
define('ENTRY_LINKS_DESCRIPTION', 'Beschreibung:');
define('ENTRY_LINKS_DESCRIPTION_ERROR', 'Die Beschreibung muss mindestens ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' Symbole haben.');
define('ENTRY_LINKS_DESCRIPTION_TEXT', '*');
define('ENTRY_LINKS_IMAGE', 'Image URL:');
define('ENTRY_LINKS_IMAGE_TEXT', '');
define('ENTRY_LINKS_CONTACT_NAME', 'Name:');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Ihr Name muss mindestens ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' Symbole haben.');
define('ENTRY_LINKS_CONTACT_NAME_TEXT', '*');
define('ENTRY_LINKS_RECIPROCAL_URL', 'Reciprocal Page:');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Die reciprocale Page muss mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Symbole haben.');
define('ENTRY_LINKS_RECIPROCAL_URL_TEXT', '*');
?>
