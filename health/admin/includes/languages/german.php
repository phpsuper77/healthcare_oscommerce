<?php
/*
  $Id: german.php,v 1.1.1.1 2005/12/03 21:36:04 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//Admin begin
// header text in includes/header.php
define('HEADER_TITLE_ACCOUNT', 'Mein Konto');
define('HEADER_TITLE_LOGOFF', 'Abmelden');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'Mein Konto');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Mitgleidsgruppen');
define('BOX_ADMINISTRATOR_MEMBER', 'Mitglieder');
define('BOX_ADMINISTRATOR_BOXES', 'Dateizugang');
define('BOX_ADMINISTRATOR_ACCOUNT_UPDATE', 'Konto aktualisieren');

// images
define('IMAGE_FILE_PERMISSION', 'Dateirechte');
define('IMAGE_GROUPS', 'Gruppenliste');
define('IMAGE_INSERT_FILE', 'Datei einf&uuml;gen');
define('IMAGE_MEMBERS', 'Mitgliedsliste');
define('IMAGE_NEW_GROUP', 'Neue Gruppe');
define('IMAGE_NEW_MEMBER', 'Neues Mitglied');
define('IMAGE_NEXT', 'Weiter');

// constants for use in tep_prev_next_display function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Dateinamen)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Mitgliedern)');
//Admin end
// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'de_DE'
// on FreeBSD 4.0 I use 'de_DE.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'de_DE.ISO_8859-1');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT_LONG', '%A, %d. %B %Y %H:%M:%S'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_FORMAT_SPIFFYCAL', 'MM/dd/yyyy');  //Use only 'dd', 'MM' and 'yyyy' here in any order

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="de"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Holbi.co.uk');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Katalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');
define('HEADER_TITLE_CHAINREACTION', 'Chainreactionweb');
define('HEADER_TITLE_PHESIS', 'PHESIS Loaded6');
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Hauptseite definieren');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF
define('BOX_CATALOG_GSM','Google');

// text for gender
define('MALE', 'Herr');
define('FEMALE', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Konfiguration');
define('BOX_CONFIGURATION_MYSTORE', 'Mein Shop');
define('BOX_CONFIGURATION_LOGGING', 'Anmeldung');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Module');
define('BOX_MODULES_PAYMENT', 'Zahlungsweise');
define('BOX_MODULES_SHIPPING', 'Versandart');
define('BOX_MODULES_ORDER_TOTAL', 'Zusammenfassung');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Katalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Produktmerkmale');
define('BOX_CATALOG_MANUFACTURERS', 'Hersteller');
define('BOX_CATALOG_REVIEWS', 'Produktbewertungen');
define('BOX_CATALOG_SPECIALS', 'Sonderangebote');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'erwartete Artikel');
define('BOX_CATALOG_EASYPOPULATE', 'EasyPopulate');

define('BOX_CATALOG_SALEMAKER', 'SaleMaker');
// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_ORDERS', 'Bestellungen');
define('BOX_CUSTOMERS_EDIT_ORDERS', 'Bestellungen bearbeiten');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Land / Steuer');
define('BOX_TAXES_COUNTRIES', 'Land');
define('BOX_TAXES_ZONES', 'Bundesl&auml;nder');
define('BOX_TAXES_GEO_ZONES', 'Steuerzonen');
define('BOX_TAXES_TAX_CLASSES', 'Steuerklassen');
define('BOX_TAXES_TAX_RATES', 'Steuers&auml;tze');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Berichte');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'besuchte Artikel');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'gekaufte Artikel');
define('BOX_REPORTS_ORDERS_TOTAL', 'Kunden-Bestellstatistik');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Hilfsprogramme');
define('BOX_TOOLS_BACKUP', 'Datenbanksicherung');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Steuerung');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Sprachen definieren');
define('BOX_TOOLS_FILE_MANAGER', 'Datei-Manager');
define('BOX_TOOLS_MAIL', 'eMail versenden');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Rundschreiben Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Wer ist Online');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Sprachen/W&auml;hrungen');
define('BOX_LOCALIZATION_CURRENCIES', 'W&auml;hrungen');
define('BOX_LOCALIZATION_LANGUAGES', 'Sprachen');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Bestellstatus');

// infobox box text in includes/boxes/info_boxes.php
define('BOX_HEADING_BOXES', 'Infobox Admin');
define('BOX_HEADING_TEMPLATE_CONFIGURATION', 'Template Admin');
define('BOX_HEADING_CUSTOMIZATION', 'Kundenschaft');
// javascript messages
define('JS_ERROR', 'Während der Eingabe sind Fehler aufgetreten!\nBitte korrigieren Sie folgendes:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* Sie müssen diesem Wert einen Preis zuordnen\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Sie müssen ein Vorzeichen für den Preis angeben (+/-)\n');

define('JS_PRODUCTS_NAME', '* Der neue Artikel muss einen Namen haben\n');
define('JS_PRODUCTS_DESCRIPTION', '* Der neue Artikel muss eine Beschreibung haben\n');
define('JS_PRODUCTS_PRICE', '* Der neue Artikel muss einen Preis haben\n');
define('JS_PRODUCTS_WEIGHT', '* Der neue Artikel muss eine Gewichtsangabe haben\n');
define('JS_PRODUCTS_QUANTITY', '* Sie müssen dem neuen Artikel eine verfügbare Anzahl zuordnen\n');
define('JS_PRODUCTS_MODEL', '* Sie müssen dem neuen Artikel eine Artikel-Nr. zuordnen\n');
define('JS_PRODUCTS_IMAGE', '* Sie müssen dem Artikel ein Bild zuordnen\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Es muss ein neuer Preis für diesen Artikel festgelegt werden\n');

define('JS_GENDER', '* Die \'Anrede\' muss ausgewählt werden.\n');
define('JS_FIRST_NAME', '* Der \'Vorname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_DOB', '* Das \'Geburtsdatum\' muss folgendes Format haben: xx.xx.xxxx (Tag/Jahr/Monat).\n');
define('JS_EMAIL_ADDRESS', '* Die \'eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_EMAIL_FROM', '* Die \'From eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_ADDRESS', '* Die \'Strasse\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_POST_CODE', '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_CITY', '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_STATE', '* Das \'Bundesland\' muss ausgewählt werden.\n');
define('JS_STATE_SELECT', '-- Wählen Sie oberhalb --');
define('JS_ZONE', '* Das \'Bundesland\' muss aus der Liste für dieses Land ausgewählt werden.');
define('JS_COUNTRY', '* Das \'Land\' muss ausgewählt werden.\n');
define('JS_TELEPHONE', '* Die \'Telefonnummer\' muss aus mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_PASSWORD', '* Das \'Passwort\' sowie die \'Passwortbestätigung\' müssen übereinstimmen und aus mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Auftragsnummer %s existiert nicht!');

define('CATEGORY_PERSONAL', 'Pers&ouml;nliche Daten');
define('CATEGORY_ADDRESS', 'Adresse');
define('CATEGORY_CONTACT', 'Kontakt');
define('CATEGORY_COMPANY', 'Firma');
define('CATEGORY_OPTIONS', 'Optionen');

define('ENTRY_GENDER', 'Anrede:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">notwendige Eingabe</span>');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben</span>');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben</span>');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(z.B. 21.05.1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_EMAIL_FROM', 'From E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">ung&uuml;ltige eMail-Adresse!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">Diese eMail-Adresse existiert schon!</span>');
define('ENTRY_COMPANY', 'Firmenname:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Strasse:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben</span>');
define('ENTRY_SUBURB', 'weitere Anschrift:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zahlen</span>');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben</span>');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">notwendige Eingabe</font></small>');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen</span>');
define('ENTRY_FAX_NUMBER', 'Telefaxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Rundschreiben:');
define('ENTRY_NEWSLETTER_YES', 'abonniert');
define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');
define('ENTRY_NEWSLETTER_ERROR', '');

// images
define('IMAGE_ANI_SEND_EMAIL', 'eMail versenden');
define('IMAGE_BACK', 'Zur&uuml;ck');
define('IMAGE_BACKUP', 'Datensicherung');
define('IMAGE_CANCEL', 'Abbruch');
define('IMAGE_CONFIRM', 'Best&auml;tigen');
define('IMAGE_COPY', 'Kopieren');
define('IMAGE_COPY_TO', 'Kopieren nach');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'L&ouml;schen');
define('IMAGE_EDIT', 'Bearbeiten');
define('IMAGE_EMAIL', 'eMail versenden');
define('IMAGE_FILE_MANAGER', 'Datei-Manager');
define('IMAGE_ICON_STATUS_GREEN', 'aktiv');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'aktivieren');
define('IMAGE_ICON_STATUS_RED', 'inaktiv');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'deaktivieren');
define('IMAGE_ICON_INFO', 'Information');
define('IMAGE_INSERT', 'Einf&uuml;gen');
define('IMAGE_LOCK', 'Sperren');
define('IMAGE_MODULE_INSTALL', 'Module Installieren');
define('IMAGE_MODULE_REMOVE', 'Module Entfernen');
define('IMAGE_MOVE', 'Verschieben');
define('IMAGE_NEW_BANNER', 'Neuen Banner aufnehmen');
define('IMAGE_NEW_CATEGORY', 'Neue Kategorie erstellen');
define('IMAGE_NEW_COUNTRY', 'Neues Land aufnehmen');
define('IMAGE_NEW_CURRENCY', 'Neue W&auml;hrung einf&uuml;gen');
define('IMAGE_NEW_FILE', 'Neue Datei');
define('IMAGE_NEW_FOLDER', 'Neues Verzeichnis');
define('IMAGE_NEW_LANGUAGE', 'Neue Sprache anlegen');
define('IMAGE_NEW_NEWSLETTER', 'Neues Rundschreiben');
define('IMAGE_NEW_PRODUCT', 'Neuen Artikel aufnehmen');
define('IMAGE_NEW_SALE', 'New Sale');
define('IMAGE_NEW_TAX_CLASS', 'Neue Steuerklasse erstellen');
define('IMAGE_NEW_TAX_RATE', 'Neuen Steuersatz anlegen');
define('IMAGE_NEW_TAX_ZONE', 'Neue Steuerzone erstellen');
define('IMAGE_NEW_ZONE', 'Neues Bundesland einf&uuml;gen');
define('IMAGE_ORDERS', 'Bestellungen');
define('IMAGE_ORDERS_INVOICE', 'Rechnung');
define('IMAGE_ORDERS_PACKINGSLIP', 'Lieferschein');
define('IMAGE_PREVIEW', 'Vorschau');
define('IMAGE_RESET', 'Zur&uuml;cksetzen');
define('IMAGE_RESTORE', 'Zur&uuml;cksichern');
define('IMAGE_SAVE', 'Speichern');
define('IMAGE_SEARCH', 'Suchen');
define('IMAGE_SELECT', 'Ausw&auml;hlen');
define('IMAGE_SEND', 'Versenden');
define('IMAGE_SEND_EMAIL', 'eMail versenden');
define('IMAGE_UNLOCK', 'Entsperren');
define('IMAGE_UPDATE', 'Aktualisieren');
define('IMAGE_UPDATE_CURRENCIES', 'Wechselkurse aktualisieren');
define('IMAGE_UPLOAD', 'Hochladen');
define('IMAGE_CUSTOMER_NEWSLETTER', 'Export customers');
define('IMAGE_CUSTOMER_SUBSCRIBERS','Export subscribers');

define('ICON_CROSS', 'Falsch');
define('ICON_CURRENT_FOLDER', 'aktueller Ordner');
define('ICON_DELETE', 'L&ouml;schen');
define('ICON_ERROR', 'Fehler');
define('ICON_FILE', 'Datei');
define('ICON_FILE_DOWNLOAD', 'Herunterladen');
define('ICON_FOLDER', 'Ordner');
define('ICON_LOCKED', 'Gesperrt');
define('ICON_PREVIOUS_LEVEL', 'Vorherige Ebene');
define('ICON_PREVIEW', 'Vorschau');
define('ICON_STATISTICS', 'Statistik');
define('ICON_SUCCESS', 'Erfolg');
define('ICON_TICK', 'Wahr');
define('ICON_UNLOCKED', 'Entsperrt');
define('ICON_WARNING', 'Warnung');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seite %s von %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bannern)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> L&auml;ndern)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kunden)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> W&auml;hrungen)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sprachen)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Herstellern)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Rundschreiben)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellungen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellstatus)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> erwarteten Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bewertungen)');
define('TEXT_DISPLAY_NUMBER_OF_SALES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> sales)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sonderangeboten)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerklassen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerzonen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuers&auml;tzen)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bundesl&auml;ndern)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'Standard');
define('TEXT_SET_DEFAULT', 'als Standard definieren');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* erforderlich</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fehler: Es wurde keine Standardw&auml;hrung definiert. Bitte definieren Sie unter Adminstration -> Sprachen/W&auml;hrungen -> W&auml;hrungen eine Standardw&auml;hrung.');

define('TEXT_CACHE_CATEGORIES', 'Kategorien Box');
define('TEXT_CACHE_MANUFACTURERS', 'Hersteller Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Modul f&uuml;r ebenfalls gekaufte Artikel');

define('TEXT_NONE', '--keine--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Fehler: Die Destination existiert nicht.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Fehler: Die Destination ist schreibgesch&uuml;tzt.');
define('ERROR_FILE_NOT_SAVED', 'Fehler: Das File-Upload nicht gespeichert.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Fehler: Der File-Upload-Typ nicht erlaubt.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Erfolg: Das File-Upload wurde erfolgreich gespeichert.');
define('WARNING_NO_FILE_UPLOADED', 'Warnung: Kein File aktualisiert.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warnung: Die File-Uploads sind in der php.ini Konfigurationsdatei disablet.');
define('TEXT_DISPLAY_NUMBER_OF_PAYPALIPN_TRANSACTIONS', 'Angezeigt <b>%d</b> bis <b>%d</b> (von <b>%d</b> Transaktionen)'); // PAYPALIPN

define('BOX_HEADING_PAYPALIPN_ADMIN', 'Paypal IPN'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TRANSACTIONS', 'Transaktionen'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TESTS', 'Test IPN senden'); // PAYPALIPN
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Produkte'); // X-Sell

REQUIRE(DIR_WS_LANGUAGES . 'add_ccgvdc_german.php');// ICW CREDIT CLASS Gift Voucher Addittion

define('IMAGE_BUTTON_PRINT_ORDER', 'Bestellung ausdrucken');
// BOF: Lango Added for print order MOD
define('IMAGE_BUTTON_PRINT', 'Ausdrucken');
// EOF: Lango Added for print order MOD

// BOF: Lango Added for Featured product MOD
  define('BOX_CATALOG_FEATURED', 'Auktionsprodukte');
// EOF: Lango Added for Featured product MOD
// BOF: Lango Added for template MOD
// WebMakers.com Added: Attribute Sorter, Copier and Catalog additions
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

//include('includes/languages/english_support.php');
include('includes/languages/english_newsdesk.php');
include('includes/languages/english_faqdesk.php');
include('includes/languages/order_edit_english.php');
// information manager
define('BOX_HEADING_INFORMATION', 'Infosystem');
define('BOX_INFORMATION_MANAGER', 'Infomanager');
// infobox box text in includes/boxes/info_boxes.php
// NOT TRANSLATED START
define('BOX_HEADING_BOXES', 'Infobox Admin');
define('BOX_HEADING_TEMPLATE_CONFIGURATION', 'Template Admin');
define('BOX_HEADING_DESIGN_CONTROLS', 'Design Verwaltung');
// links manager box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links Manager');
define('BOX_LINKS_LINKS', 'Links');
define('BOX_LINKS_LINK_CATEGORIES', 'Link Kategorien');
define('BOX_LINKS_LINKS_CONTACT', 'Links Kontakt');
// NOT TRANSLATED END
define('ERROR_TEMPLATE_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Das Template-Image-Direktory ist schreibgesch&uuml;tzt');


define('ENTRY_NAME', 'Kunde:');
define('TEXT_INTERNET', 'Internet');
define('ENTRY_CUSTOMERS_TYPE','Kundentyp:');
define('ENTRY_ADMIN', 'Verkaufsperson:');
define('CATEGORY_BILLING_ADDRESS', 'Rechnungsadresse:');
define('CATEGORY_SHIPPING_ADDRESS', 'Versandadresse:');
define('ENTRY_CUSTOMER', 'Kunde:'); 

  define('BOX_REPORTS_MONTHLY_SALES', 'Monatlich Verk&auml;ufe/MWSt');
  define('BOX_REPORTS_SALES','Sales');
  define('BOX_TOOLS_SEARCH_STATISTICS', 'Statistik suchen');

  define('BOX_HEADING_XSELL', 'X-Sell & Up-Sell');
  define('BOX_CATALOG_XSELL_CATS_PRODUCTS', 'X-Sell Kategorieprodukte');
  define('BOX_CATALOG_UPSELL_PRODUCTS', 'Up-Sell Produkte');
  define('BOX_CATALOG_UPSELL_CATEGORIES', 'Up-Sell Kategorien');
  define('BOX_CATALOG_UPSELL_CATS_PRODUCTS', 'Up-Sell Kategorieprodukte');
// for inventory
define('BOX_CATALOG_INVENTORY', 'Zubeh&ouml;r');
define('TEXT_DISPLAY_NUMBER_OF_ITEM', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Artikeln)');
define('IMAGE_CONTINUE', 'Fortsetzen');
define('IMAGE_NEW_INVENTORY', 'Neues einf&uuml;gen');
define('BOX_CATALOG_PROPERTIES', 'Eigenschaften');
define('TEXT_FILE_DOES_NOT_EXIST', 'Datei nicht vorhanden.');

define('ERROR_FILE_NOT_WRITEABLE', 'Fehler: Die Datei ist schreibgesch&uuml;tzt. Bitte korrigieren Sie die Zugriffsrechte f&uuml;r: %s');
define('TEXT_ADMIN_LOGIN', 'Admin Anmeldung.');
define('TEXT_AFFILIATE_LOGIN', 'Partner Anmeldung.');
define('TEXT_ROOT', 'Admin');
define('BOX_CATALOG_DEFINE_AGB', 'AGB definieren');
define('BOX_CUSTOMERS_GROUPS', 'Kundengruppen');
define('TEXT_CURRENT_SERVER_TIME', 'Aktuelles Datum:');
define('BOX_VENDOR_SUMMARY', 'Lieferanten');
define('BOX_HEADING_VENDOR', 'Lieferanten');
define('BOX_VENDOR', 'Lieferanten');
define('BOX_VENDOR_DEFINE_VENDOR_INFO', 'Lieferanteninfo definieren');
define('BOX_VENDOR_DEFINE_VENDOR_TERMS', 'AGB für Lieferanten definieren');
define('BOX_VENDOR_CONTACT', 'Kontakt');
define('BOX_VENDOR_PAYMENT', 'Zahlung');
define('BOX_VENDOR_SALES', 'Lieferantenverkäufe');
define('BOX_HEADING_AUCTION', 'Auction');
// start search statistics
define('BOX_TOOLS_SEARCH_STATISTICS', 'Referrer Statistik');
define('TEXT_DISPLAY_NUMBER_OF_SE_STATISTICS', 'Zeige <b>%d</b> bis <b>%d</b> (von <b>%d</b> Suchworten)');
// end search statistics 

//xml related addon
  define('BOX_CATALOG_XML_RESTORE', 'Xml Restore');
  define('ERROR_CATALOG_XML_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog xml backups directory is not writeable: ' . DIR_FS_CATALOG_XML);
  define('ERROR_CATALOG_XML_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog xml backups directory does not exist: ' . DIR_FS_CATALOG_XML);
  define('TEXT_XML_BACKUP_IMPOSSIBLE','<font color="#FF0000">You can\'t backup, because there is no possibility to write into <b>xml backups directory</b></font>');
  define('TEXT_XML_BACKUP_POSSIBLE','XML backup. You can either backup all data <br> or specify data you want to backup by ticking needed checkboxes');
  define('TEXT_XML_DUMP', 'XML');
  define('TEXT_NEVER_EXPORTED', 'Wasn\'t exported yet');
  define('TEXT_LAST_XML_DUMP', 'Last XML dump was on %s');


  //products
  define('TEXT_NO_SELECTED_PRODUCTS', 'You haven\'t select products\n Please, select at least one!');
  define('TEXT_NO_SELECTED_CATEGORIES', 'You haven\'t select categories\n Please, select at least one!');
  define('TEXT_XML_ALL_PRODUCTS', 'Backup all products in shop');
  define('TEXT_XML_SELECTED_PRODUCTS', 'Backup selected <b>products</b>');
  define('TEXT_XML_SELECTED_CATEGORIES', 'Backup selected <b>categories</b>');
  //*products

  //customers
  define('TEXT_NO_SELECTED_CUSTOMERS', 'You haven\'t select customers\n Please, select at least one!');
  define('TEXT_XML_ALL_CUSTOMERS', 'Backup all customers in shop');
  define('TEXT_XML_SELECTED_CUSTOMERS', 'Backup selected <b>customers</b>');
  //*customers

  //orders
  define('TEXT_NO_SELECTED_ORDERS', 'You haven\'t select orders\n Please, select at least one!');
  define('TEXT_XML_ALL_ORDERS', 'Backup all orders in shop');
  define('TEXT_XML_SELECTED_ORDERS', 'Backup selected <b>orders</b>');
  //*orders
  define('TEXT_OPEN_WYSIWYG_EDITOR', 'Edit in WYSIWYG Editor');
  define('ERROR_TEMPLATE_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis mit Tempaltes Image existiert nicht: ');
  define('BOX_META_TAGS', 'Meta tags');

  define('BOX_CATALOG_BRAND_MANAGER','Brand Manager');

  define('BOX_REPORTS_RECOVER_CART_SALES', 'Recovered Sales Results');
  define('BOX_TOOLS_RECOVER_CART', 'Recover Cart Sales');
define('BOX_TOOLS_TRADEBOX', 'Export orders to CSV');
define('BOX_TOOLS_SALES_SERVAVE','Post Sales Review');
?>
