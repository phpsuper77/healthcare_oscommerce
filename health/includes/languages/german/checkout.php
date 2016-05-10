<?php
/*
  $Id: login.php,v 1.15 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Kasse');
define('HEADING_TITLE', 'Melden Sie sich an');

define('HEADING_NEW_CUSTOMER', 'Neukunde');
define('TEXT_NEW_CUSTOMER', 'Ich bin Neukunde.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' steht zu Ihrer Verf�gung eine M�glichkeit, mit Ihren eigenen Bestellungen knapp mit der Zeit zu sein, jederzeit eine aktuelle �bersicht �ber Ihren Bestellstatus sowie Ihre bisherigen Bestellungen zu haben.');

define('HEADING_RETURNING_CUSTOMER', 'Bereits Kunde');
define('TEXT_RETURNING_CUSTOMER', 'Ich bin bereits Kunde.');

define('TEXT_PASSWORD_FORGOTTEN', 'Sie haben Ihr Passwort vergessen? Dann klicken Sie <u>hier</u>');

define('TEXT_LOGIN_ERROR', 'Fehler: Keine �bereinstimmung mit der eingebenen E-Mail-Adresse und/oder dem Passwort.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Achtung:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win();">[Mehr Information]</a>');

// {{
define('HEADING_ORDER_INFORMATION', 'Bestellinfo');
define('HEADING_BILLING_ADDRESS', 'Rechnungsadresse');
define('HEADING_SHIPPING_ADDRESS', 'Versandadresse');
define('TEXT_IF_SHIPPING_IS_SAME_AS_BILLING', 'Bitte klicken Sie hier %s, falls die Versandadresse der Rechnungsadresse entspricht.');

define('JS_GENDER', '* Das  \'Geschlecht\' muss angegeben werden.\n');
define('JS_FIRST_NAME', '* Der \'Vorname\' muss mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muss mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_EMAIL_ADDRESS', '* Die \'E-Mail Adresse\' muss mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_ADDRESS', '* Die \'Stra�e\' muss mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_POST_CODE', '* Die  \'PLZ\' muss mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_CITY', '* Die \'Stadt\' muss mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_STATE', '* Der  \Ort\' muss ausgew�hlt werden.\n');
define('JS_STATE_SELECT', '-- w�hlen --');
define('JS_ZONE', '* Der \'Ort\' muss aus der L�nderliste gew�hlt werden.\n');
define('JS_COUNTRY', '*Das \'Land\' muss ausgew�hlt werden.\n');
define('JS_TELEPHONE', '* Die  \'Telefonnummer\' muss mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_PASSWORD', '* Das  \'Passwort\' und die \'Best�tigung\' m�ssen �bereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten.\n');

define('JS_SHIP_ADDRESS', '* Die \'Versandstra�e\' muss mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_SHIP_POST_CODE', '* Die \'Versand-PLZ\' muss mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_SHIP_CITY', '* Die \'Versandstadt\' muss mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen enthalten.\n');
define('JS_SHIP_STATE', '* Der \'Versandort\' muss ausgew�hlt werden.\n');
define('JS_SHIP_COUNTRY', '* Das \'Versandland\'muss ausgew�hlt werden.\n');

define('SHIP_FIRST_NAME_ERROR', 'Der Vorname muss mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_LAST_NAME_ERROR', 'Der Nachname muss mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_STREET_ADDRESS_ERROR', 'Die Stra�e muss mindestens  ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_POST_CODE_ERROR', 'Die PLZ muss mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_CITY_ERROR', 'Die Sadt muss mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_STATE_ERROR', 'Der Ort muss mindestens ' . ENTRY_STATE_MIN_LENGTH . ' Zeichen enthalten.');
define('SHIP_STATE_ERROR_SELECT', 'Bitte w�hlen Sie den Versandort aus.');
define('SHIP_COUNTRY_ERROR', 'Bitte w�hlen Sie das Versandland aus.');

define('TABLE_HEADING_SHIPPING_METHOD', 'Versandart');
define('TEXT_CHOOSE_SHIPPING_METHOD', 'Bitte w�hlen Sie eine beliebige Versandart aus.');
define('TITLE_PLEASE_SELECT', 'Bitte w�hlen Sie');
define('TEXT_ENTER_SHIPPING_INFORMATION', 'Dies ist die einzige vorhandene Versandart f�r Ihre Bestellung.');

define('TABLE_HEADING_PAYMENT_METHOD', 'Zahlungsweise');
define('TEXT_SELECT_PAYMENT_METHOD', 'Bitte w�hlen Sie eine beliebige Zahlungsweise aus.');
define('TITLE_PLEASE_SELECT', 'Bitte w�hlen Sie');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Dies ist die einzige vorhandene Zahlungsweise f�r Ihre Bestellung.');

define('TABLE_HEADING_COMMENTS', 'Kommentare zu Ihrer Bestellung einf�gen');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Den Bestellvorgang fortsetzen');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'um diese Bestellung zu best�tigen.');

define('EMAIL_SUBJECT', 'Herzlich willkommen bei ' . STORE_NAME);
define('EMAIL_GREET_NONE', 'Ser geehrte(r)%s' . "\n\n");
define('EMAIL_TEXT0', 'Vielen Dank f�r Ihre Anmeldung bei ' . STORE_NAME . "\n\n");
define('EMAIL_LOGIN', 'Ihr Login lautet: %s' . "\n");
define('EMAIL_PASSWORD', 'Ihr Passwort lautet: %s' . "\n\n");
define('EMAIL_TEXT1', 'Jetzt stehen Ihnen <b>diverse Services</b> zur Verf�gung. Einer der Services ist:' . "\n\n" . '<li><b>permanenter Warenkorb</b> - In den Warenkorb hizugef�gte Artikel bleiben im Korb, bis Sie diese entweder l�schen oder damit zur Kasse gehen.' . "\n" . '<li><b>Adressenbuch</b> - Wir k�nnen jetzt Ihre Artikel zu verschiedenen Adressen liefern! Es ist eine gute M�glichkeit, ein Geburtstagsgeschenk direkt dem Geburtstagskind zuzuliefern. ' . "\n" . '<li><b>Bestellhistorie</b> - �bersicht von allen bei uns get�tigten Bestellungen.' . "\n" . '<li><b>Produktbewertungen</b> - Teilen Sie Ihre Meinung zum Produkt f�r andere Kunden mit.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was submitted during registration at ' . HTTP_SERVER.DIR_WS_CATALOG . ' Muss diese Email fehlerhafte Daten enthalten, bitte nehmen Sie Verst�ndnis und wir werden unverz�glich unsere Datenbank �ndern, sobald Sie uns kontaktieren.');

define('HEADING_CONTACT_INFO', 'Kontaktinformation');

define('TEXT_NEW_ADDRESS', 'Neue Adresse');
define('ENTRY_ADDRESS_BOOK', 'Adressenbuch:');
// }}

define('ENTRY_COMMERCE_IDS', 'CommerceID:');
define('ENTRY_COMMERCE_IDS_ERROR_EXISTS', 'Der Kunde mit gleicher CommerceID existiert schon!');
define('ENTRY_COMMERCE_IDS_ERROR_WRONG', 'Unkorrekte CommerceID!');
define('ENTRY_COMMERCE_IDS_TEXT', '*');

define('ENTRY_USER_KEY', 'Benutzerschlussel (falls vorhanden):');
define('ENTRY_USER_KEY_TEXT', '');

define('HEADING_CONDITIONS_INFORMATION', 'Allgemeine Gesch�fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_CONFIRM', 'Ich akzeptiere Ihre Allgemeinen Gesch�fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_DOWNLOAD', 'AGB\'s herunterladen');
?>
