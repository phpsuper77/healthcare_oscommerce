<?php
/*
  $Id: checkout_payment.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Kasse');
define('NAVBAR_TITLE_2', 'Zahlungsweise');

define('HEADING_TITLE', 'Zahlungsweise');

define('TABLE_HEADING_BILLING_ADDRESS', 'Rechnungsadresse');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Bitte w&auml;hlen Sie aus Ihrem Adressbuch die gew&uuml;nschte Rechnungsadresse f&uuml;r Ihre Bestellung.');
define('TITLE_BILLING_ADDRESS', 'Rechnungsadresse:');

define('TABLE_HEADING_PAYMENT_METHOD', 'Zahlungsweise');
define('TEXT_SELECT_PAYMENT_METHOD', 'Bitte w&auml;hlen Sie die gew&uuml;nschte Zahlungsweise f&uuml;r Ihre Bestellung.');
define('TITLE_PLEASE_SELECT', 'Bitte w&auml;hlen Sie aus');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Zur Zeit bieten wir Ihnen nur eine Zahlungsweise an.');

define('TABLE_HEADING_COMMENTS', 'F&uuml;gen Sie hier Ihre Anmerkungen zu dieser Bestellung ein');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Den Bestellvorgang fortsetzen');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'Die Bestellung best&auml;tigen.');
define('HEADING_CONDITIONS_INFORMATION', 'Allgemeine Gesch&auml;fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_CONFIRM', 'Ich akzeptiere Ihre Allgemeinen Gesch&auml;fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_DOWNLOAD', 'AGB\'s herunterladen');
define('TEXT_ERROR_CHECKOUT', 'You cann\'t process to checkout due to some limitations. Please <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">contact</a> store owner for more information.');
?>