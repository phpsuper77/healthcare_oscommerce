<?php
/*
  $Id: coupon_restrict.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

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
define('TEXT_COUPON_ACTIVE', 'Aktive Gutscheine');
define('TEXT_COUPON_INACTIVE', 'Inaktive Gutscheine');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_FROM', 'Von:');
define('TEXT_FREE_SHIPPING', 'Freier Versand');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECT_CUSTOMER', 'Kunden ausw&auml;hlen');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTER_CUSTOMERS', 'An alle Newsletter-Abonnenten');
define('TEXT_CONFIRM_DELETE', 'Sind Sie sicher, diesen Gutschein l&ouml;schen zu wollen?');

define('TEXT_TO_REDEEM', 'Sie k&ouml;nnen diesen Gutschein beim Checkout einl&ouml;sen, indem Sie nur den Einl&ouml;scode in der Box eingeben und den einl&ouml;sen Button klicken.');
define('TEXT_IN_CASE', ' Falls Sie einige Probleme haben. ');
define('TEXT_VOUCHER_IS', 'Der Einl&ouml;scode lautet ');
define('TEXT_REMEMBER', 'Bitte den Einl&ouml;scode nicht verlieren');
define('TEXT_VISIT', 'wenn Sie besuchen ' . tep_get_clickable_link(HTTP_SERVER . DIR_WS_CATALOG));
define('TEXT_ENTER_CODE', ' und den Code eingeben ');

define('TABLE_HEADING_ACTION', 'Aktion');



define('NOTICE_EMAIL_SENT_TO', 'Anmerkung: Die Email gesendet an: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fehler: Kein Kunde wurde ausgew&auml;hlt.');
define('COUPON_NAME', 'Gutscheinname');
//define('COUPON_VALUE', 'Coupon Value');
define('COUPON_AMOUNT', 'Gutscheinwert');
define('COUPON_CODE', 'Gutscheincode');
define('COUPON_STARTDATE', 'Startdatum');
define('COUPON_FINISHDATE', 'Enddatum');
define('COUPON_FREE_SHIP', 'Freier Versand');
define('COUPON_DESC', 'Gutscheinbeschreibung');
define('COUPON_MIN_ORDER', 'Minimale Bestellanzahl pro Gutschein ');
define('COUPON_USES_COUPON', 'Verwendungsanzahl pro Gutschein');
define('COUPON_USES_USER', 'Verwendungsanzahl pro Kunden');
define('COUPON_PRODUCTS', 'Valide Produktliste');
define('COUPON_CATEGORIES', 'Valide Kategorieliste');
define('VOUCHER_NUMBER_USED', 'Verwendungsanzahl');
define('DATE_CREATED', 'Erstellt am');
define('DATE_MODIFIED', 'Modifiziert am');
define('TEXT_HEADING_NEW_COUPON', 'Neuen Gutschein erstellen');
define('TEXT_NEW_INTRO', 'Bitte f&uuml;llen Sie folgende Information zum neuen Gutschein aus. <br>');


define('COUPON_NAME_HELP', 'Gutschein-Kurzname');
define('COUPON_AMOUNT_HELP', 'Discountwert f&uuml;r den Gutschein, entweder fixer oder f&uuml;gen Sie  % am Ende f&uuml;r prozentualen Discount hizu.');
define('COUPON_CODE_HELP', 'Sie k&ouml;nnen Ihren eigenen Code hier eingeben oder lassen Sie einfach blank sein, dieser wird automatisch generiert.');
define('COUPON_STARTDATE_HELP', 'G&uuml;ltig von');
define('COUPON_FINISHDATE_HELP', 'G&uuml;ltig bis');
define('COUPON_FREE_SHIP_HELP', 'Der Gutschein bietet einen Versand f&uuml;r eine Bestellung an.');
define('COUPON_DESC_HELP', 'Gutscheinbeschreibung f&uuml;r den Kunden');
define('COUPON_MIN_ORDER_HELP', 'Minimaler Bestellwert vor dem Gutscheinablaufsdatum');
define('COUPON_USES_COUPON_HELP', 'Maximale Verwendungsanzahl des Gutscheins, einfach blank hinterlassen, wenn Sie diese beschr&auml;nken wollen.');
define('COUPON_USES_USER_HELP', 'Verwendungsanzahl des Gutscheins pro Benutzer, eifach blank hinterlassen, wenn Sie nicht beschr&auml;nken wollen.');
define('COUPON_PRODUCTS_HELP', 'Mit Komma separatierte product_ids List, mit der dieser Gutschein verwendet werden kann. Eifach blank hinterlassen, wenn Sie nicht beschr&auml;nken wollen.');
define('COUPON_CATEGORIES_HELP', 'Mit Komma separatierte cpaths List, mit der dieser Gutschein verwendet werden kann. Eifach blank hinterlassen, wenn Sie nicht beschr&auml;nken wollen.');
?>
