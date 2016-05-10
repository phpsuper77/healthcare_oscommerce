<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Kasse');
define('NAVBAR_TITLE_2', 'Erfolgreich');

define('HEADING_TITLE', 'Ihre Bestellung ist ausgef&uuml;hrt worden.');

define('TEXT_SUCCESS', 'Ihre Bestellung ist eingegangen und wird bearbeitet! Die Lieferung erfolgt innerhalb von ca. 2-5 Werktagen.');
define('TEXT_NOTIFY_PRODUCTS', 'Bitte benachrichtigen Sie mich &uuml;ber Aktuelles zu folgenden Produkten:');
define('TEXT_SEE_ORDERS', 'Sie k&ouml;nnen Ihre Bestellung(en) auf der Seite <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '"><u>\'Ihr Konto\'</a></u> jederzeit einsehen und dort auch Ihre <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '"><u>\'Bestell&uuml;bersicht\'</u></a> haben lassen.');
define('TEXT_CONTACT_STORE_OWNER', 'Falls Sie Fragen bez&uuml;glich Ihrer Bestellung haben, wenden Sie sich an unseren <a href="' . tep_href_link(FILENAME_CONTACT_US) . '"><u>Vertrieb</u></a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Wir danken Ihnen f&uuml;r Ihren Online-Einkauf!');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Herunterladen m&ouml;glich bis:');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'max. Anz. Downloads');
define('HEADING_DOWNLOAD', 'Artikel herunterladen:');
define('FOOTER_DOWNLOAD', 'Sie k&ouml;nnen Ihre Artikel auch sp&auml;ter unter \'%s\' herunterladen');
define('TABLE_HEADING_COMMENTS', 'Fügen Sie Kommentare zum Bestellverlauf ein');
define('PAYPAL_NAVBAR_TITLE_2_OK', 'Erfolg'); // PAYPALIPN
define('PAYPAL_NAVBAR_TITLE_2_PENDING', 'Ihre Bestellung ist in Bearbeitung.'); // PAYPALIPN
define('PAYPAL_NAVBAR_TITLE_2_FAILED', 'Ihre Zahlung hatte Misserfolg'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_OK', 'Ihre Bestellung wurde bearbeitet!'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_PENDING', 'Ihre Bestellung ist in Bearbeitung!'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_FAILED', 'Ihre Zahlung hatte Misserfolg!'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_OK', 'Ihre Bestellung wurde erfolgreich bearbeitet! Ihre Produkte werden im Laufe von 2-5 Werktagen geliefert.'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_PENDING', 'Ihre Bestellung ist in Bearbeitung!'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_FAILED', 'Ihre Zahlung hatte Misserfolg! Bitte überprüfen Sie, dass Ihre PayPal-Daten korrekt sind.'); // PAYPALIPN

?>