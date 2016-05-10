<?php
/*
  $Id: advanced_search.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Erweiterte Suche');
define('NAVBAR_TITLE_2', 'Suchergebnisse');

define('HEADING_TITLE_1', 'Geben Sie Ihre Suchkriterien ein');
define('HEADING_TITLE_2', 'Artikel zu Suchkriterien;');

define('HEADING_SEARCH_CRITERIA', 'Geben Sie Ihre Stichworte ein');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Auch in den Beschreibungen suchen');
define('ENTRY_CATEGORIES', 'Kategorien:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Unterkategorien mit einbeziehen');
define('ENTRY_MANUFACTURERS', 'Hersteller:');
define('ENTRY_PRICE_FROM', 'Preis ab:');
define('ENTRY_PRICE_TO', 'Preis bis:');
define('ENTRY_DATE_FROM', 'Hinzugef&uuml;gt ab:');
define('ENTRY_DATE_TO', 'Hinzugef&uuml;gt bis:');

define('TEXT_SEARCH_HELP_LINK', '<u>Hilfe zur erweiterten Suche</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Alle Kategorien');
define('TEXT_ALL_MANUFACTURERS', 'Alle Hersteller');

define('HEADING_SEARCH_HELP', 'Hilfe zur erweiterten Suche');
define('TEXT_SEARCH_HELP', 'Die Suchfunktion erm&ouml;glicht, das Produkt bei den  Produktnamen-, Produktbeschreibungs-, Hersteller- und Modellkategorien leicht zu erreichen.<br><br>Zu Ihrer Verf&uuml;gung stehen logische Operatoren wie z. B. "AND" (Und) und "OR" (oder).<br><br>Als Beispiel geben Sie also an: <u>Microsoft AND Maus</u>.<br><br>Desweiteren verwenden Sie Klammern,  um die Suche zu verschachteln, also z.B.:<br><br><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br><br>Mit Anf&uuml;hrungszeichen werden mehrere W&ouml;rter zu einem Suchbegriff zusammengefasst.');
define('TEXT_CLOSE_WINDOW', '<u>Fenster schlie&szlig;en</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Artikelnummer');
define('TABLE_HEADING_PRODUCTS', 'Bezeichnung');
define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
define('TABLE_HEADING_QUANTITY', 'Menge');
define('TABLE_HEADING_PRICE', 'Einzelpreis');
define('TABLE_HEADING_WEIGHT', 'Gewicht');
define('TABLE_HEADING_BUY_NOW', 'Jetzt bestellen');

define('TEXT_NO_PRODUCTS', 'Es wurden leider keine Artikel zu diesen Suchkriterien gefunden.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Wenigstens ein Feld des Suchformulars ist auszuf&uuml;llen.');
define('ERROR_INVALID_FROM_DATE', 'Unzul&auml;ssiges <b>ab</b> Datum');
define('ERROR_INVALID_TO_DATE', 'Unzul&auml;ssiges <b>bis jetzt</b> Datum');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Das Datum <b>ab</b> muss h&ouml;her oder gleich mit dem <b>bis jetzt</b> sein');
define('ERROR_PRICE_FROM_MUST_BE_NUM', '<b>Preis ab</b> ist mit Ziffern einzugeben');
define('ERROR_PRICE_TO_MUST_BE_NUM', '<b>Preis bis</b> ist mit Ziffern einzugeben');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', '<b>Preis bis</b> muss h&ouml;her oder gleich mit dem <b>ab</b> sein.');
define('ERROR_INVALID_KEYWORDS', 'Unzul&aum&auml;ssiger Suchbegriff ');
define('OPTION_NONE', 'Nichts eingegeben');
define('OPTION_TRUE', 'Ja');
define('OPTION_FALSE', 'Nein');
?>
