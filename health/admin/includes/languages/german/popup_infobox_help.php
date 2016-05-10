<?php
/*
  $Id: popup_infobox_help.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Hilfe zur Infobox');
define('TEXT_INFO_HEADING_NEW_INFOBOX', 'Hilfe zur Infobox');

define('TEXT_INFOBOX_HELP_FILENAME', 'Dies muss den Namen der Box-Datei anzeigen, die Sie unter <u>catalog/includes/boxes</u> erstellt haben.<br><br> Dabei ist es Unterstrich (_) als Symbol bei der Dateianlage und Leerzeichen bei der Benennung zu ber�cksichtigen. <br><br>Zum Beispiel:<br>Ihre neue Infobox-Datei soll <b>new_box.php</b> hei�en, aber die Box selber muss so aussehen "<b> new box</b>"<br><br>Ein anderes Beispiel: <b>whats_new</b> box.<br> Aber <b>what\'s new</b>');

define('TEXT_INFOBOX_HELP_HEADING', 'Wollen Sie etwas �ber der Infobox in Ihrem Katalog anzeigen lassen, so ist es dann sehr einfach.<br><div align="center"><img border="0" src="images/help1.gif"><br></div>');

define('TEXT_INFOBOX_HELP_DEFINE', 'Als Beispiel nehmen wir: <b>BOX_HEADING_WHATS_NEW</b>.<br> Dies finden Sie unter: <b> define(\'BOX_HEADING_WHATS_NEW\', \'What\'s New?\');</b><br><br> Wenn Sie die Datei <u>catalog/includes/languages/english.php</u> �ffnen, finden Sie BOX_HEADING, aber bitte den Text nicht l�nger machen wie in der Datenbank oder wie es in den Dateien <b>column_left.php</b> und <b>column_right.php</b> definiert ist.<br>Aber bitte nicht l�schen!! ');

define('TEXT_INFOBOX_HELP_COLUMN', 'W�hlen Sie <b>linksb�ndig</b> oder <b>rechtsb�ndig</b> aus. <br> M�chten Sie Ihre Infobox in der linken Spalte anzeigen lassen, w�hlen Sie <b>linksb�ndig</b> aus. M�chten Sie Ihre Infobox in der rechten Spalte anzeigen lassen, dann w�hlen Sie <b>rechtsb�ndig</b> aus. <br><br>Als Standard wird dies immer inder <b>linksb�ndig</b> angezeigt');

define('TEXT_INFOBOX_HELP_POSITION', 'Geben Sie eine beliebige Nummer ein. Je gr��er die Nummer ist, desto niedriger auf der Seite wird die Infobox angezeigt.<br><br> Wenn Sie dieselbe Nummer f�r mehhrere Infoboxen eingegeben haben, dann werden diese alphbetischerweise.<br><br>Haben Sie Nummer nich eingegeben, wird dann die Infobox auch alphabetischerweise angezeigt.');

define('TEXT_INFOBOX_HELP_ACTIVE', 'Als Best�tigung zur Infobox-Anzeige klicken Sie <b>ja</b> an oder <b>nein</b>, wenn Sie diese nicht anzeigen m�chten.<br><br>Als Standard ist <b>ja</b> eingestellt.');

define('TEXT_CLOSE_WINDOW', '<u>Fenster schlie�en</u> [x]');

?>
