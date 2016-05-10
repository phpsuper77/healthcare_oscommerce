<?php
/*
  $Id: coupon_admin.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Statistik');
define('HEADING_TITLE', 'Rabattgutscheine');
define('HEADING_TITLE_STATUS', 'Status : ');
define('TEXT_CUSTOMER', 'Kunde:');
define('TEXT_COUPON', 'Gutscheinname');
define('TEXT_COUPON_ALL', 'Alle Gutscheine');
define('TEXT_COUPON_ACTIVE', 'Gutscheine aktivieren');
define('TEXT_COUPON_INACTIVE', 'Gutscheine inaktivieren');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_FROM', 'Von:');
define('TEXT_FREE_SHIPPING', 'versandkostenfrei');
define('TEXT_MESSAGE', 'Nachricht:');
define('TEXT_SELECT_CUSTOMER', 'Kunden ausw�hlen');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTER_CUSTOMERS', 'An alle Newsletter Abonnenten');
define('TEXT_CONFIRM_DELETE', 'Sind Sie sicher, diesen gutschein l�schen zu wollen?');

define('TEXT_TO_REDEEM', 'Sie k�nnen diesen Gutschein beim Checkout einl�sen. Geben Sie einfach den Code ins Einl�sfeld ein und klicken Sie den Einl�sen Button an.');
define('TEXT_IN_CASE', ' Falls Sie Probleme haben m�ssen. ');
define('TEXT_VOUCHER_IS', 'Der Gutscheincode lautet ');
define('TEXT_REMEMBER', 'Bitte den Gutscheincode nicht verlieren.');
define('TEXT_VISIT', 'wenn Sie besuchen ' . tep_get_clickable_link(HTTP_SERVER . DIR_WS_CATALOG));
define('TEXT_ENTER_CODE', ' und den Code eingeben ');

define('TABLE_HEADING_ACTION', 'Aktion');

define('CUSTOMER_ID', 'Kunden-ID');
define('CUSTOMER_NAME', 'Kundennamee');
define('REDEEM_DATE', 'Eingel�st am');
define('IP_ADDRESS', 'IP-Adresse');

define('TEXT_REDEMPTIONS', 'Einl�sung');
define('TEXT_REDEMPTIONS_TOTAL', 'In Summe');
define('TEXT_REDEMPTIONS_CUSTOMER', 'F�r diesen Kunden');
define('TEXT_NO_FREE_SHIPPING', 'Nicht versandkostenfrei');

define('NOTICE_EMAIL_SENT_TO', 'Hinweis: Email abgesandt an: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fehler: Kein Kunde wurde ausgew�hlt.');
define('COUPON_NAME', 'Gutscheinnamee');
//define('COUPON_VALUE', 'Gutscheinwert');
define('COUPON_AMOUNT', 'Gutscheinwert');
define('COUPON_CODE', 'Gutscheincode');
define('COUPON_STARTDATE', 'Startdatum');
define('COUPON_FINISHDATE', 'Enddatum');
define('COUPON_FREE_SHIP', 'versandkostenfrei');
define('COUPON_DESC', 'Gutscheinbeschreibung');
define('COUPON_MIN_ORDER', 'Minimale Gutschein-Bestellung');
define('COUPON_USES_COUPON', 'Verwendbarkeit per Gutschein');
define('COUPON_USES_USER', 'Verwendbarkeit per Kunden');
define('COUPON_PRODUCTS', 'G�ltige Produktliste');
define('COUPON_CATEGORIES', 'G�ltige Kategorienliste');
define('VOUCHER_NUMBER_USED', 'Verwendbarkeitsnummer');
define('DATE_CREATED', 'Erstellt am');
define('DATE_MODIFIED', 'Modifiziert am');
define('TEXT_HEADING_NEW_COUPON', 'Neuen Gutschein erstellen');
define('TEXT_NEW_INTRO', 'Bitte geben Sie entsprechende Information f�r den neuen Gutschein ein.<br>');


define('COUPON_NAME_HELP', 'Kurzname des Gutscheines');
define('COUPON_AMOUNT_HELP', 'Der Wert des Rabattgutscheines, entweder fix oder geben Sie % am Ende f�r den prozentualen Rabatt ein.');
define('COUPON_CODE_HELP', 'Hier k�nnen Sie Ihren eigenen Code eingeben, oder einfach blank  hinterlassen, wenn Sie m�chten, dass der Code automatisch generiert wird.');
define('COUPON_STARTDATE_HELP', 'G�ltig ab');
define('COUPON_FINISHDATE_HELP', 'G�ltig bis');
define('COUPON_FREE_SHIP_HELP', 'Der Gutschein sieht einen versandfreien Versand.');
define('COUPON_DESC_HELP', 'Gutscheinbeschreibung f�r den Kunden');
define('COUPON_MIN_ORDER_HELP', 'Minimaler Bestellwert f�r die Gutscheing�ltigkeit');
define('COUPON_USES_COUPON_HELP', 'Maximale Verwendungsanzahl, einfach blank lassen, wenn der Gutschein nicht beschr�nkt werden soll.');
define('COUPON_USES_USER_HELP', 'Verwendungsanzahl, einfach blank lassen, wenn der Gutschein nicht beschr�nkt werden soll.');
define('COUPON_PRODUCTS_HELP', 'Mit Komma separatierte product_ids Liste, einfach blank lassen, wenn der Gutschein nicht beschr�nkt werden soll.');
define('COUPON_CATEGORIES_HELP', 'Mit Komma separatierte cpaths Liste, einfach blank lassen, wenn der Gutschein nicht beschr�nkt werden soll.');
define('TEXT_EMAIL_BUTTON_TEXT', 'E-mail');

define('COUPON_BUTTON_PREVIEW', 'Vorschau');
?>
