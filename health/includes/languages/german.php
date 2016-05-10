<?php
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
@setlocale(LC_TIME, 'de_DE.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d. %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');


function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="de"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Neues Konto');
define('HEADER_TITLE_MY_ACCOUNT', 'Ihr Konto');
define('HEADER_TITLE_CART_CONTENTS', 'Warenkorb');
define('HEADER_TITLE_CHECKOUT', 'Kasse');
define('HEADER_TITLE_TOP', 'Startseite');
define('HEADER_TITLE_CATALOG', 'Katalog');
define('HEADER_TITLE_LOGOFF', 'Abmelden');
define('HEADER_TITLE_LOGIN', 'Anmelden');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'Zugriffe seit');

// text for gender
define('MALE', 'Herr');
define('FEMALE', 'Frau');
define('MALE_ADDRESS', 'Herr');
define('FEMALE_ADDRESS', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Kategorien');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Hersteller');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Neue Produkte');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Schnellsuche');
define('BOX_SEARCH_TEXT', 'Verwenden Sie Stichw�rter, um ein Produkt zu finden.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'erweiterte Suche');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Angebote');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Bewertungen');
define('BOX_REVIEWS_WRITE_REVIEW', 'Bewerten Sie dieses Produkt!');
define('BOX_REVIEWS_NO_REVIEWS', 'Es liegen noch keine Bewertungen vor');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s von 5 Sternen!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Warenkorb');
define('BOX_SHOPPING_CART_EMPTY', '0 Produkte');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Bestell&uuml;bersicht');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Bestseller');
define('BOX_HEADING_BESTSELLERS_IN', 'Bestseller<br>&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Benachrichtigungen');
define('BOX_NOTIFICATIONS_NOTIFY', 'Benachrichtigen Sie mich &uuml;ber Aktuelles zu diesem Artikel <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Benachrichtigen Sie mich nicht mehr zu diesem Artikel <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Hersteller Info');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Mehr Produkte');

// languages box test in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Sprachen');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'W&auml;hrungen');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informationen');
define('BOX_INFORMATION_PRIVACY', 'Privatsph&auml;re<br>&nbsp;und Datenschutz');
define('BOX_INFORMATION_CONDITIONS', 'Unsere AGB\'s');
define('BOX_INFORMATION_SHIPPING', 'Liefer- und<br>&nbsp;Versandkosten');
define('BOX_INFORMATION_CONTACT', 'Kontakt');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Weiterempfehlen');
define('BOX_TELL_A_FRIEND_TEXT', 'Empfehlen Sie diesen Artikel einfach per eMail weiter.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Versandinformationen');
define('CHECKOUT_BAR_PAYMENT', 'Zahlungsweise');
define('CHECKOUT_BAR_CONFIRMATION', 'Best&auml;tigung');
define('CHECKOUT_BAR_FINISHED', 'Fertig!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Bitte w&auml;hlen');
define('TYPE_BELOW', 'bitte unten eingeben');

// javascript messages
define('JS_ERROR', 'Notwendige Angaben fehlen!\nBitte richtig ausf�llen.\n\n');

define('JS_REVIEW_TEXT', '* Der Text muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.\n');
define('JS_REVIEW_RATING', '* Geben Sie Ihre Bewertung ein.\n');
define('TEXT_REVIEW_TEXT_ERROR', '* Der Text muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.');
define('TEXT_REVIEW_RATING_ERROR', '* Geben Sie Ihre Bewertung ein.');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Bitte w�hlen Sie eine Zahlungsweise f�r Ihre Bestellung.\n');

define('JS_ERROR_SUBMITTED', 'Diese Seite wurde bereits best�tigt. Bet�tigen Sie bitte OK und warten bis der Prozess durchgef�hrt wurde.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Bitte w�hlen Sie eine Zahlungsweise f�r Ihre Bestellung.');

define('CATEGORY_COMPANY', 'Firmendaten');
define('CATEGORY_PERSONAL', 'Ihre pers&ouml;nlichen Daten');
define('CATEGORY_ADDRESS', 'Ihre Adresse');
define('CATEGORY_CONTACT', 'Ihre Kontaktinformationen');
define('CATEGORY_OPTIONS', 'Optionen');
define('CATEGORY_PASSWORD', 'Ihr Passwort');

define('ENTRY_COMPANY', 'Firmenname:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Anrede:');
define('ENTRY_GENDER_ERROR', 'Bitte das Geschlecht angeben.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_FIRST_NAME_ERROR', 'Der Vorname sollte mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_LAST_NAME_ERROR', 'Der Nachname sollte mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Bitte geben Sie Ihr Geburtsdatum in folgendem Format ein: TT.MM.JJJJ (z.B. 21.05.1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (z.B. 21.05.1970)');
define('ENTRY_EMAIL_ADDRESS', 'eMail-Adresse:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Die eMail Adresse sollte mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Die eMail Adresse scheint nicht g�ltig zu sein - bitte korrigieren.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Die eMail Adresse ist bereits gespeichert - bitte melden Sie sich mit dieser Adresse an oder er�ffnen Sie ein neues Konto mit einer anderen Adresse.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Strasse/Nr.:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Die Strassenadresse sollte mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Stadtteil:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_POST_CODE_ERROR', 'Die Postleitzahl sollte mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Ort:');
define('ENTRY_CITY_ERROR', 'Die Stadt sollte mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_STATE_ERROR', 'Das Bundesland sollte mindestens ' . ENTRY_STATE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_STATE_ERROR_SELECT', 'Bitte w�hlen Sie ein Bundesland aus der Liste.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', 'Bitte w�hlen Sie ein Land aus der Liste.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Die Telefonnummer sollte mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Telefaxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'abonniert');
define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_PASSWORD_ERROR', 'Das Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Beide eingegebenen Passw�rter m�ssen identisch sein.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Best&auml;tigung:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Altes Passwort:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Das Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_NEW', 'Neues Passwort:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Das neue Passwort sollte mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Die Passwort-Best�tigung muss mit Ihrem neuen Passwort �bereinstimmen.');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

define('FORM_REQUIRED_INFORMATION', '* Notwendige Eingabe');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seiten:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'angezeigte Produkte: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'angezeigte Bestellungen: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'angezeigte Meinungen: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'angezeigte neue Produkte: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'angezeigte Angebote <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'erste Seite');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'vorherige Seite');
define('PREVNEXT_TITLE_NEXT_PAGE', 'n&auml;chste Seite');
define('PREVNEXT_TITLE_LAST_PAGE', 'letzte Seite');
define('PREVNEXT_TITLE_PAGE_NO', 'Seite %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Vorhergehende %d Seiten');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'N&auml;chste %d Seiten');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;ERSTE');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;vorherige]');
define('PREVNEXT_BUTTON_NEXT', '[n&auml;chste&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LETZTE&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Neue Adresse');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adressbuch');
define('IMAGE_BUTTON_BACK', 'Zur�ck');
define('IMAGE_BUTTON_BUY_NOW', 'Jetzt kaufen!');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adresse �ndern');
define('IMAGE_BUTTON_CHECKOUT', 'Kasse');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bestellung best�tigen');
define('IMAGE_BUTTON_CONTINUE', 'Weiter');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Einkauf fortsetzen');
define('IMAGE_BUTTON_DELETE', 'L�schen');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Daten �ndern');
define('IMAGE_BUTTON_HISTORY', 'Bestell�bersicht');
define('IMAGE_BUTTON_LOGIN', 'Anmelden');
define('IMAGE_BUTTON_IN_CART', 'In den Warenkorb');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Benachrichtigungen');
define('IMAGE_BUTTON_QUICK_FIND', 'Schnellsuche');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Benachrichtigungen l�schen');
define('IMAGE_BUTTON_REVIEWS', 'Bewertungen');
define('IMAGE_BUTTON_SEARCH', 'Suchen');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Versandoptionen');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Weiterempfehlen');
define('IMAGE_BUTTON_UPDATE', 'Aktualisieren');
define('IMAGE_BUTTON_UPDATE_CART', 'Warenkorb aktualisieren');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Bewertung schreiben');

define('SMALL_IMAGE_BUTTON_DELETE', 'L�schen');
define('SMALL_IMAGE_BUTTON_EDIT', 'Bearbeiten');
define('SMALL_IMAGE_BUTTON_VIEW', 'Anzeigen');

define('ICON_ARROW_RIGHT', 'Zeige mehr');
define('ICON_CART', 'In den Warenkorb');
define('ICON_ERROR', 'Fehler');
define('ICON_SUCCESS', 'Erfolg');
define('ICON_WARNING', 'Warnung');

define('TEXT_GREETING_PERSONAL', 'Sch&ouml;n das Sie wieder da sind <span class="greetUser">%s!</span> M&ouml;chten Sie die <a href="%s"><u>neue Produkte</u></a> ansehen?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Wenn Sie nicht %s sind, melden Sie sich bitte <a href="%s"><u>hier</u></a> mit Ihrem Kundenkonto an.</small>');
define('TEXT_GREETING_GUEST', 'Herzlich Willkommen <span class="greetUser">Gast!</span> M&ouml;chten Sie sich <a href="%s"><u>anmelden</u></a>? Oder wollen Sie ein <a href="%s"><u>Kundenkonto</u></a> er&ouml;ffnen?');

define('TEXT_SORT_PRODUCTS', 'Sortierung der Artikel ist ');
define('TEXT_DESCENDINGLY', 'absteigend');
define('TEXT_ASCENDINGLY', 'aufsteigend');
define('TEXT_BY', ' nach ');

define('TEXT_REVIEW_BY', 'von %s');
define('TEXT_REVIEW_WORD_COUNT', '%s Worte');
define('TEXT_REVIEW_RATING', 'Bewertung: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Datum hinzugef&uuml;gt: %s');
define('TEXT_NO_REVIEWS', 'Es liegen noch keine Bewertungen vor.');

define('TEXT_NO_NEW_PRODUCTS', 'Zur Zeit gibt es keine neuen Produkte.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unbekannter Steuersatz');

define('TEXT_REQUIRED', '<span class="errorText">erforderlich</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>Fehler:</small> Die eMail kann nicht &uuml;ber den angegebenen SMTP-Server verschickt werden. Bitte kontrollieren Sie die Einstellungen in der php.ini Datei und f&uuml;hren Sie notwendige Korrekturen durch!</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warnung: Das Installationverzeichnis ist noch vorhanden auf: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Bitte l&ouml;schen Sie das Verzeichnis aus Gr&uuml;nden der Sicherheit!');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warnung: osC kann in die Konfigurationsdatei schreiben: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. Das stellt ein m&ouml;gliches Sicherheitsrisiko dar - bitte korrigieren Sie die Benutzerberechtigungen zu dieser Datei!');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warnung: Das Verzeichnis f&uuml;r die Sessions existiert nicht: ' . tep_session_save_path() . '. Die Sessions werden nicht funktionieren bis das Verzeichnis erstellt wurde!');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warnung: osC kann nicht in das Sessions Verzeichnis schreiben: ' . tep_session_save_path() . '. Die Sessions werden nicht funktionieren bis die richtigen Benutzerberechtigungen gesetzt wurden!');
define('WARNING_SESSION_AUTO_START', 'Warnung: session.auto_start ist enabled - Bitte disablen Sie dieses PHP Feature in der php.ini und starten Sie den WEB-Server neu!');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warnung: Das Verzeichnis f�r den Artikel Download existiert nicht: ' . DIR_FS_DOWNLOAD . '. Diese Funktion wird nicht funktionieren bis das Verzeichnis erstellt wurde!');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Das "G&uuml;ltig bis" Datum ist ung&uuml;ltig.<br>Bitte korrigieren Sie Ihre Angaben.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Die "KreditkarteNummer", die Sie angegeben haben, ist ung&uuml;ltig.<br>Bitte korrigieren Sie Ihre Angaben.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Die ersten 4 Ziffern Ihrer Kreditkarte sind: %s<br>Wenn diese Angaben stimmen, wird dieser Kartentyp leider nicht akzeptiert.<br>Bitte korrigieren Sie Ihre Angaben gegebenfalls.');

/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date("Y") . ' <a href="http://www.holbi.co.uk" target="_blank">Holbi</a><br>Powered by Holbi <a href="http://www.holbi.co.uk/clients/trueloaded/" target="_blank">TrueLoaded</a> Version');
require(DIR_WS_LANGUAGES . $language . '/add_ccgvdc.php');
/////////////////////////////////////////////////////////////////////
// HEADER.PHP
// Header Links
define('HEADER_LINKS_DEFAULT','HOME');
define('HEADER_LINKS_WHATS_NEW','WAS GIBT ES HIER NEUES?');
define('HEADER_LINKS_SPECIALS','SONDERANGEBOT');
define('HEADER_LINKS_REVIEWS','BEWERTUNGEN');
define('HEADER_LINKS_LOGIN','ANMELDEN');
define('HEADER_LINKS_LOGOFF','ABMELDEN');
define('HEADER_LINKS_PRODUCTS_ALL','KATALOG');
define('HEADER_LINKS_ACCOUNT_INFO','KONTOINFO');

/////////////////////////////////////////////////////////////////////
// BOF: Lango added for print order mod
define('IMAGE_BUTTON_PRINT_ORDER', 'Bestellung ausdrucken');
// EOF: Lango added for print order mod

define('BOX_HEADING_CUSTOMER_WISHLIST', 'Mein Wunschzettel');
define('BOX_WISHLIST_EMPTY', 'Zur Zeit auf Ihrem Wunschzettel kein Artikel vorhanden');
define('IMAGE_BUTTON_ADD_WISHLIST', 'Zum Wunschzettel hizuf&uuml;gen');
define('TEXT_WISHLIST_COUNT', 'Zur Zeit %s Artikel auf Ihrem Wunschzettel vorhanden.');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Artikeln von Ihrem Wunschzettel)');
define('BOX_WISHLIST_DELETE', 'L&ouml;schen');

// WebMakers.com Added: Attributes Sorter
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');
//include('includes/languages/english_support.php');
include(DIR_WS_LANGUAGES . $language . '/newsdesk.php');
include(DIR_WS_LANGUAGES . $language . '/faqdesk.php');
define('BOX_INFORMATION_ALLPRODS', 'Alle Artikel');

require(DIR_WS_LANGUAGES . $language . '/agb.php');  
define('HEADING_CUSTOMER_GREETING', 'Unsere Gl�ckw�nsche an Kunden');
define('MAINPAGE_HEADING_TITLE', 'Hauptseitentitel');
define('TABLE_HEADING_FEATURED_PRODUCTS', 'Auktionsprodukte');
define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', 'Auktionsprodukte in %s'); 
define('BOX_HEADING_QUICK_SEARCH', 'Suche');
define('BOX_INFORMATION_LINKS', 'Links');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Angezeigt <b>%d</b> bis <b>%d</b> (von <b>%d</b> Aktionsprodukten  insgesamt)');
define('TEXT_CUSTOMER_GREETING_HEADER', 'Unsere Gl�ckw�nsche');
define('TEXT_NO_PRODUCTS', 'Zur Zeit gibt es keinen Artikel.');
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'ANMERKUNG: Diese Webseite wird dem Umbau vorlegen : ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'ANMERKUNG: Diese Webseite wir momentan umgebaut');
define('BOX_HEADING_LINKS_CATEGORIES', 'Unsere Links');
define('BOX_HEADING_SHOP_BY_PRICE', 'Einkauf nach Preis');

  define('BOX_HEADING_LOGIN_BOX_MY_ACCOUNT','Meine Kontodaten.');

  define('LOGIN_BOX_MY_ACCOUNT','Meine Konto&uuml;bersicht');
  define('LOGIN_BOX_ACCOUNT_EDIT','Meine Kontodaten bearbeiten');
  define('LOGIN_BOX_ADDRESS_BOOK','Mein Adressenbuch bearbeiten');
  define('LOGIN_BOX_ACCOUNT_HISTORY','Meine Bestellhistorie anzeigen');
  define('LOGIN_BOX_PRODUCT_NOTIFICATIONS','Produktbenachrichtigung');

  define('LOGIN_BOX_PASSWORD_FORGOTTEN','Passwort vergessen?');

// Could be placed in english.php
// shopping cart quotes
  define('SHIPPING_OPTIONS', 'Versandoptionen:');
  if (strstr($PHP_SELF,'shopping_cart.php')) {
    define('SHIPPING_OPTIONS_LOGIN', 'Bitte <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>loggen Sie sich ein</u></a>, um Ihre pers�nlichen Versandoptionen anzeigen zu k�nnen.');
  } else {
    define('SHIPPING_OPTIONS_LOGIN', 'Bitte loggen Sie sich ein, um Ihre pers�nlichen Versandoptionen anzeigen zu k�nnen.');
  }
  define('SHIPPING_METHOD_TEXT','Versandverfahren:');
  define('SHIPPING_METHOD_RATES','Raten:');
  define('SHIPPING_METHOD_TO','Versand an: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Versand an: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Einloggen</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','versandkostenfrei');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Downloads');
  define('SHIPPING_METHOD_RECALCULATE','Neu berechnen');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Adresse:');
  define('ERROR_NO_SHIPPING_METHOD', 'Bitte geben Sie eine andere Versandart an.');
  define('IMAGE_REDEEM_VOUCHER', 'Einl�sen');
  define('BOX_HEADING_WHOS_ONLINE', 'Wer ist Online');
  define('BOX_WHOS_ONLINE_THEREIS', 'Zur Zeit ist');
  define('BOX_WHOS_ONLINE_THEREARE', 'Zur Zeit sind');
  define('BOX_WHOS_ONLINE_GUEST', 'Gast');
  define('BOX_WHOS_ONLINE_GUESTS', 'G�ste');
  define('BOX_WHOS_ONLINE_AND', 'und');
  define('BOX_WHOS_ONLINE_MEMBER', 'Partner');
  define('BOX_WHOS_ONLINE_MEMBERS', 'Partner');  
  define('BOX_VIEW_CUSTOMER_WISHLIST', 'Meinen Wunschzettel anzeigen ');
  define('BOX_HELP_CUSTOMER_WISHLIST', 'Hilfe zum Wunschzettel ansehen ');
  define('TEXT_ITEM_SE', ' Artikelanzahl: ');
  define('TEXT_ITEMS_SE', ' Artikelanzahl: ');
  define('TEXT_WEIGHT_SE', 'Gewicht:');

  define('ENTRY_BUSINESS_COMPANY_ERROR', 'Bei Angabe der USt-ID m&uuml;ssen Sie einen Firmennamen eingeben.');
  define('ENTRY_BUSINESS', 'Europ&auml;ische USt-ID: ');
  define('ENTRY_BUSINESS_ERROR', 'Europ&auml;ische USt-ID Fehler.'); 
  define('ENTRY_VAT_ID_TEXT', '*');
  define('ENTRY_VAT_ID_ERROR', 'Europ�ische USt-ID Fehler.');
  define('TEXT_ONLINE', 'Online.');
  define('BOX_HEADING_VENDOR', 'Lieferanten');
  define('BOX_VENDOR_INFO', 'Lieferanteninfo');
  define('BOX_VENDOR_LOGIN', 'Anmelden');
  define('TEXT_NEWSDESK_REVIEWS', '&Uuml;berblick:'); 
  define('BOX_WISHLIST_MOVE_TO_CART', 'In den Warenkorb');
  define('ENTRY_GROUP', 'Group:');
  
  define('TEXT_DOWNLOAD_FILE_NOT_FOUND', 'Requested file not found. Please contact store owner.');
  define('TEXT_DOWNLOAD_PRODUCT_EXPIRED_DOWNLOADS', 'You\'ve already downloaded requested file for %s times.');
  define('TEXT_DOWNLOAD_PRODUCT_EXPIRED', 'Your file download expired at %s');
  define('TEXT_DOWNLOAD_PRODUCT_NOT_FOUND', 'Requested file not found. Please contact store owner.');
  
  define('TEXT_SECURE_LOGIN', 'Sicher anmelden');
  define('TEXT_PRODUCT_DETAILS', 'Product details');
  define('TEXT_BUY', '1 x \'');
  define('TEXT_NOW', '\' bestellen!');

  define('TEXT_DEMO_SHOP', 'Sehr geehrter Benutzer, Sie befinden sich jetzt im Demo-Shop von Holbi. Bitte testen Sie den Shop in Ruhe und &uuml;berzeugen Sie sich in dessen Vorteilen.<br> Sollten Sie Fragen haben, bitte setzen Sie sich mit uns per E-Mail <a href="mailto:info@holbi.eu">info@holbi.eu</a> in Verbindung.');

  define('TEXT_BUNDLE_PRODUCTS', 'Products in Set');
  define('TEXT_QTY', 'Qty');
  define('TEXT_PRICE', 'Price');
  define('TEXT_REGULAR_PRICE', 'Regular&nbsp;Price');
  define('TEXT_ALSO_AVAILABLE_IN_SETS', 'Also Available In Set(s)');
  define('TEXT_SHOW_CONTENTS_OF_MY_CART', 'Show contents of my cart');

  // SEO Reviews
  define('REVIEW_TEXT_FROM_LENGTH', '2');
  define('REVIEW_YOUR_NAME', 'Ihr Name');
  define('JS_REVIEW_CUSTOMER_FULL_NAME', '* \'' . REVIEW_YOUR_NAME . '\' muss mindestens ' . REVIEW_TEXT_FROM_LENGTH . ' Zeichen enthalten');
  // SEO Reviews
?>