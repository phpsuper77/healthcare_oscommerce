<?php
/*
  $Id: mail.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'eMail an Kunden versenden');

define('TEXT_CUSTOMER', 'Kunde:');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_FROM', 'Absender:');
define('TEXT_MESSAGE', 'Nachricht:');
define('TEXT_SELECT_CUSTOMER', 'Kunden ausw&auml;hlen');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTER_CUSTOMERS', 'An alle Newsletter-Abonnenten');

define('NOTICE_EMAIL_SENT_TO', 'Hinweis: eMail wurde versendet an: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fehler: Es wurde kein Kunde ausgew&auml;hlt.');
define('TEXT_EMAIL_BUTTON_TEXT', '<p><HR><b><font color="red">Der Zurück Button ist INAKTAV, solange der HTML WYSIWG Editor angeschaltet bleibt,</b></font> WARUM? - Wenn Sie den Zurück Button zum HTML-EMAIL-Editiren klicken, fügt PHP (php.ini - "Magic Quotes = On") automatisch Backslashes "\\\\\\\" ein. Dies wird zu vielen Problemen verursachen wie z.B. fehlerhafte Bildanzage. Wenn Sie den WYSIWYG Editor im Adminbereich abschalten, wird die HTML Optin auch abgeschaltet und der Zuräck Button erscheint neu.<br><HR>');
define('TEXT_EMAIL_BUTTON_HTML', '<p><HR><b><font color="red">HTML ist zur Zeit desaktiviert!</b></font><br><br>Wenn Sie eine HTML-Email senden möchten, bitte aktivieren Sie den WYSIWYG Editor unter: Admin-->Configuration-->WYSIWYG Editor-->Options<br>');
define('TEXT_YOU_TITLE_HERE', 'Geben Sie hier Ihren Titel ein');
define('TEXT_YOU_CONTENT_HERE', 'Geben Sie hier Ihren Inhalt ein')
?>