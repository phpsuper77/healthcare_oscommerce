<?php
/*
  $Id: categories.php,v 1.1.1.1 2003/09/18 19:03:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Kategorien / Artikel');
define('HEADING_TITLE_SEARCH', 'Suche: ');
define('HEADING_TITLE_GOTO', 'Gehe zu:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_NEW_PRODUCT', 'Neuer Artikel in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Kategorien:');
define('TEXT_SUBCATEGORIES', 'Unterkategorien:');
define('TEXT_PRODUCTS', 'Artikel:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Preis:');
define('TEXT_PRICE','Preis');
define('TEXT_PRODUCTS_TAX_CLASS', 'Steuerklasse:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'durchschnittl. Bewertung:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Anzahl:');
define('TEXT_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_LAST_MODIFIED', 'letzte &Auml;nderung:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Bitte f&uuml;gen Sie eine neue Kategorie oder einen Artikel ein.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'F&uuml;r weitere Informationen, besuchen Sie bitte die <a href="http://%s" target="blank"><u>Homepage</u></a> des Herstellers.');
define('TEXT_PRODUCT_DATE_ADDED', 'Diesen Artikel haben wir am %s in unseren Katalog aufgenommen.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Dieser Artikel ist erh&auml;ltlich ab %s.');

define('TEXT_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch.');
define('TEXT_EDIT_CATEGORIES_ID', 'Kategorie ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Kategorie Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Kategorie Bild:');
define('TEXT_EDIT_SORT_ORDER', 'Sortierreihenfolge:');

define('TEXT_INFO_COPY_TO_INTRO', 'Bitte w&auml;hlen Sie eine neue Kategorie aus, in die Sie den Artikel kopieren m&ouml;chten:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'aktuelle Kategorien:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Neue Kategorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Kategorie bearbeiten');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Kategorie l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Kategorie verschieben');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Artikel l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Artikel verschieben');
define('TEXT_INFO_HEADING_COPY_TO', 'Kopieren nach');

define('TEXT_DELETE_CATEGORY_INTRO', 'Sind Sie sicher, dass Sie diese Kategorie l&ouml;schen m&ouml;chten?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Sind Sie sicher, dass Sie diesen Artikel l&ouml;schen m&ouml;chten?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNUNG:</b> Es existieren noch %s (Unter-)Kategorien, die mit dieser Kategorie verbunden sind!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> Es existieren noch %s Artikel, die mit dieser Kategorie verbunden sind!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE', 'Verschiebe <b>%s</b> nach:');

define('TEXT_NEW_CATEGORY_INTRO', 'Bitte geben Sie die neue Kategorie mit allen relevanten Daten ein.');
define('TEXT_CATEGORIES_NAME', 'Kategorie Name:');
define('TEXT_CATEGORIES_IMAGE', 'Kategorie Bild:');
define('TEXT_SORT_ORDER', 'Sortierreihenfolge:');

define('TEXT_PRODUCTS_STATUS', 'Produktstatus:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_PRODUCT_AVAILABLE', 'auf Lager');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'nicht vorr&auml;tig');
define('TEXT_PRODUCTS_MANUFACTURER', 'Artikel-Hersteller:');
define('TEXT_PRODUCTS_NAME', 'Artikelname:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Artikelbeschreibung:');
define('TEXT_PRODUCTS_QUANTITY', 'Artikelanzahl:');
define('TEXT_PRODUCTS_QUANTITY_MINIMIAL', 'Mindestbestellmenge:');
define('TEXT_PRODUCTS_QUANTITY_ENOUGH', 'Maximale Menge:');
define('TEXT_PRODUCTS_MODEL', 'Artikel-Nr.:');
define('TEXT_PRODUCTS_IMAGE', 'Artikelbild:');
define('TEXT_PRODUCTS_URL', 'Herstellerlink:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(ohne f&uuml;hrendes http://)</small>');
define('TEXT_PRODUCTS_PRICE_NET', 'Artikelpreis (Netto):');
define('TEXT_PRODUCTS_PRICE_GROSS', 'Artikelpreis (Brutto):');
define('TEXT_PRODUCTS_WEIGHT', 'Artikelgewicht:');

define('EMPTY_CATEGORY', 'Leere Kategorie');

define('TEXT_HOW_TO_COPY', 'Kopiermethode:');
define('TEXT_COPY_AS_LINK', 'Produkt verlinken');
define('TEXT_COPY_AS_DUPLICATE', 'Produkt duplizieren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fehler: Produkte k&ouml;nnen nicht in der gleichen Kategorie verlinkt werden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist schreibgesch&uuml;tzt: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist nicht vorhanden: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Fehler: Die Kategorie kann zur Kinderkategorie nicht zugeordnet werden.');
define('TEXT_PRODUCTS_SEO_PAGE_NAME', 'SEO-Produktname:');
define('TEXT_PRODUCTS_IMAGE_NOTE','<b>Produktbild:</b><small><br>Das Hauptbidbeschreibung<br><u></u> page.<small>');
define('TEXT_PRODUCTS_IMAGE_MEDIUM', '<b>Größeres Bild:</b><br><small>Das Kleinbild auf die <br><u>Produktbeschreibungsseite</u> verschiebn.</small>');
define('TEXT_PRODUCTS_IMAGE_LARGE', '<b>Pop-up Bild:</b><br><small>Das Kleinbild auf die <br><u>pop-up Fenster</u>seite verschieben.</small>');
define('TEXT_PRODUCTS_IMAGE_REMOVE', 'Dieses Bild aus diesem Produkt verschieben??');
define('TEXT_PRODUCTS_IMAGE_DELETE', 'Dieses Bild aus dem Server löschen?');
define('TEXT_PRODUCTS_IMAGE_REMOVE_SHORT', 'Verschieben');
define('TEXT_PRODUCTS_IMAGE_DELETE_SHORT', 'Löschen');
define('TEXT_PRODUCTS_IMAGE_TH_NOTICE', '<b>SM = Kleinbild,</b> wenn ein "SM" Bild verwendet wird, ist es kein Pop-Up-Fenster zu erstellen. Das "SM" Bild wird direkt unter der Produktbeschreibung unterbracht.<br><br>');
define('TEXT_PRODUCTS_IMAGE_XL_NOTICE', '<b>XL = Großbild,</b> wird fürs Pop-up Bild verwendet<br><br><br>');
define('TEXT_PRODUCTS_IMAGE_ADDITIONAL', 'Mehr zusätzliche Bilder - Diese werden bei der Produktbeschreibung erschenen.');
define('TEXT_PRODUCTS_IMAGE_SM_1', 'SM Bild 1:');
define('TEXT_PRODUCTS_IMAGE_XL_1', 'XL Bild 1:');
define('TEXT_PRODUCTS_IMAGE_SM_2', 'SM Bild 2:');
define('TEXT_PRODUCTS_IMAGE_XL_2', 'XL Bild 2:');
define('TEXT_PRODUCTS_IMAGE_SM_3', 'SM Bild 3:');
define('TEXT_PRODUCTS_IMAGE_XL_3', 'XL Bild 3:');
define('TEXT_PRODUCTS_IMAGE_SM_4', 'SM Bild 4:');
define('TEXT_PRODUCTS_IMAGE_XL_4', 'XL Bild 4:');
define('TEXT_PRODUCTS_IMAGE_SM_5', 'SM Bild 5:');
define('TEXT_PRODUCTS_IMAGE_XL_5', 'XL Bild 5:');
define('TEXT_PRODUCTS_IMAGE_SM_6', 'SM Bild 6:');
define('TEXT_PRODUCTS_IMAGE_XL_6', 'XL Bild 6:');
define('TEXT_PRODUCTS_IMAGE_ALT_1', 'Zus&auml;tzliches Bild 1 Alt:');
define('TEXT_PRODUCTS_IMAGE_ALT_2', 'Zus&auml;tzliches Bild 2 Alt:');
define('TEXT_PRODUCTS_IMAGE_ALT_3', 'Zus&auml;tzliches Bild 3 Alt:');
define('TEXT_PRODUCTS_IMAGE_ALT_4', 'Zus&auml;tzliches Bild 4 Alt:');
define('TEXT_PRODUCTS_IMAGE_ALT_5', 'Zus&auml;tzliches Bild 5 Alt:');
define('TEXT_PRODUCTS_IMAGE_ALT_6', 'Zus&auml;tzliches Bild 6 Alt:');
define('TEXT_PRODUCTS_IMAGE_SM_RESIZE', 'Create small from large');
define('TEXT_PRODUCTS_IMAGE_MED_RESIZE', 'Create medium from large');
define('TEXT_DELETE_IMAGE', 'Bild löschen');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Kategorieheading-Titel:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Kategorieheading-Beschreibung:');
define('TEXT_NONE', '--keine--');
define('TEXT_PRODUCTS_PAGE_TITLE', 'Produktseitentitel:');
define('TEXT_PRODUCTS_HEADER_DESCRIPTION', 'Seitenheaderbeschreibung:');
define('TEXT_PRODUCTS_KEYWORDS', 'Produktschlüsselwörter:');
define('TEXT_PRODUCTS_IMAGE_LINKED', '<u>Verf&uuml;gbarkeitsstatus =</u>');

define('TEXT_ADDITIONAL_INFO', 'Zus&auml;tzliche Information');
define('OPTION_NONE', 'Nichts eingegeben');
define('OPTION_TRUE', 'Ja');
define('OPTION_FALSE', 'Nein');
define('TEXT_UNLINK_PROPERTY', 'Informationen l&ouml;schen');
define('TEXT_CATEGORIES_STATUS', 'Kategorie Verleih:');
define('TEXT_ACTIVE', 'Aktiv');
define('TEXT_INACTIVE', 'Inaktiv');
define('TEXT_CATEGORIES_PAGE_TITLE', 'Kategorieseitentitel:');
define('TEXT_CATEGORIES_HEADER_DESCRIPTION', 'Kategoriebeschreibung Meta-Tag:');
define('TEXT_CATEGORIES_KEYWORDS', ' Kategorieschl&uuml;sselw&ouml;rter:');
define('TEXT_PRODUCTS_DESCRIPTION_SHORT', 'Kurzbeschreibung');
define('TEXT_PRODUCTS_FILE', 'Produktdatei:');
define('TEXT_PRODUCTS_DISCOUNT_PRICE', 'Staffelpreis (Net):');
define('TEXT_DELETE_TEST_DATA', 'Remove test data?');
define('JS_TEXT_DELETE_TEST_DATA', 'Remove test data?');

define('TAB_GENERAL', 'Generell');
define('TAB_DATA', 'Daten');
define('TAB_IMAGES', 'Bild');
define('TEXT_LEGEND_PRICE', 'Preis');
define('TEXT_LEGEND_DATA', 'Daten');
define('TEXT_LEGEND_INFORMATION', 'Information');
define('TEXT_LEGEND_SMALL_IMAGE', 'Kleines Produktbild');
define('TEXT_IMAGE_LOCATION', 'Bild-Platzierung');
define('TEXT_UPLOAD_NEW_IMAGE', 'Neues Bild uploaden');
define('TEXT_PREVIEW', 'Vorschau');
define('TEXT_LEGEND_MEDIUM_IMAGE', 'Durchschnittliches Produktbild');
define('TEXT_LEGEND_LARGE_IMAGE', 'Großes Produktbild');
define('TEXT_DESTINATION', 'Destination:');
define('TAB_ATTRIBUTES', 'Merkmale');
define('TAB_PROPERTIES', 'Eigenschaften');
define('FIELDSET_ASSIGNED_ATTRIBUTES', 'Angegebene Merkmale');
define('TEXT_XSELL', 'XSell');
define('TEXT_UPSELL', 'UpSell');
define('FIELDSET_ASSIGNED_XSELL_CATEGORIES', 'XSell Kategorien');
define('FIELDSET_ASSIGNED_XSELL_PRODUCTS', 'XSell Produkte');
define('FIELDSET_ASSIGNED_UPSELL_CATEGORIES', 'UPSell Kategorien');
define('FIELDSET_ASSIGNED_UPSELL_PRODUCTS', 'UPSell Produkte');

define('TABLE_HEADING_SORT_ORDER','Sortierreihenfolge');

define('TEXT_PRODUCTS_TYPE','Typ');

//define('TEXT_ALLOWED_QTY','Maximale Einkaufsmenge: <BR><small>(0 bedeutet unbegrenzt)</small>');
//define('TEXT_ALLOWED_QTY_2','Maximale Einkaufsmenge: ');

//xml addon

define('ERROR_CATALOG_XML_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnus mit xml Katalog-Backup ist schreibgeschützt: ' . DIR_FS_CATALOG_XML);
define('ERROR_CATALOG_XML_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnus mit xml Katalog-Backup existiert nicht: ' . DIR_FS_CATALOG_XML);
define('TEXT_XML_DUMP', 'XML');
define('TEXT_XML_BACKUP_IMPOSSIBLE','<font color="#FF0000">Sie können für Produkte kein Backup durchfüheren, weil es nicht möglich ist, diese ins <b>xml Backup-Verzeichnis</b> einzuschreiben</b></font>');
define('TEXT_XML_BACKUP_POSSIBLE','XML Backup. Wenn Sie "Alles backupen" klicken, können Sie Backup entweder für den Gesamtkatalog oder für ausgewählte Produkte durchführen.');
define('TEXT_XML_ALL', 'Backup für alle im Shop vorhandenen Produkte');
define('TEXT_XML_SLECTED', 'Backup für ausgewählte <b>Produkte</b>');
define('TEXT_SELECT_PRODUCTS_F_BACKUP', 'Sie haben kein Produkt zum Backupen ausgewählt. Bitte markieren Sie mindestens ein Produkt!');
define('TEXT_SELECT_CATEGORIES_F_BACKUP', 'Sie haben keine Kategorie zum Backupen ausgewählt. Bitte markieren Sie mindestens eine!');
define('TABLE_HEADING_LAST_EXPORT','Dump');
define('TEXT_NEVER_EXPORTED', 'Noch nicht exportiert');
define('TEXT_LAST_XML_DUMP', 'Letztes XML Dump vom %s datiert');
define('TEXT_XML_SELECTED_CATEGORIES', 'Backup für ausgewählte <b>Kategorien</b>');

define('TEXT_INVENTORY', 'Zubeh&ouml;r');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel-Nr.:');
define('TEXT_HEADING_QUANTITY', 'Anzahl:');
define('TEXT_PRICE', 'Preis');
define('TEXT_DISCOUNT_PRICE', 'Staffelpreis');
define('TEXT_MAIN', 'Main');
define('TAB_AFFILIATES', 'Affiliates');

define('TAB_BUNDLES', 'Bundles');
define('FIELDSET_ASSIGNED_PRODUCTS', 'Assigned Products');
define('TEXT_NUMBER', 'Number:');
define('TEXT_PRODUCTS_SETS_DISCOUNT', 'Bundles Sets Discount (%):');
?>
