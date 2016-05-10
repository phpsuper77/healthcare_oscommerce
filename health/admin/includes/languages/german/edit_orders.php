<?php
/*
  $Id: edit_orders.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Bestellung editieren');
define('HEADING_TITLE_SEARCH', 'Bestell-ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Produkt zur Bestellung hinzuf&uuml;gen');

define('ENTRY_UPDATE_TO_CC', '(W&auml;hlen Sie <b>Creditkarte</b> aus.)');
define('TABLE_HEADING_COMMENTS', 'Kommentare');
define('TABLE_HEADING_CUSTOMERS', 'Kunden');
define('TABLE_HEADING_ORDER_TOTAL', 'Bestellung gesamt');
define('TABLE_HEADING_DATE_PURCHASED', 'Gekauft am');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_QUANTITY', 'Menge.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produkte');
define('TABLE_HEADING_TAX', 'MWSt');
define('TABLE_HEADING_TOTAL', 'Insgesamt');
define('TABLE_HEADING_UNIT_PRICE', 'Einzelpreis');
define('TABLE_HEADING_TOTAL_PRICE', 'Gesamtpreis');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrigtigt am');
define('TABLE_HEADING_DATE_ADDED', 'Hinzugef&uuml;gt am');

define('ENTRY_CUSTOMER', 'Kunde:');
define('ENTRY_NAME', 'Kunde:');
define('ENTRY_SOLD_TO', 'VERKAUFT AN:');
define('ENTRY_DELIVERY_TO', 'Geliefert an:');
define('ENTRY_SHIP_TO', 'VERSENDEN AN:');
define('ENTRY_SHIPPING_ADDRESS', 'Versandadresse:');
define('ENTRY_BILLING_ADDRESS', 'Rechnungsadresse:');
define('ENTRY_PAYMENT_METHOD', 'Zahlweise:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kreditkartentyp:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kreditkarteninhaber:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kreditkartennummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Kreditkartenablaufsdatum:');
define('ENTRY_SUB_TOTAL', 'Netto-Preis:');
define('ENTRY_TAX', 'MWSt:');
define('ENTRY_SHIPPING', 'Versandkosten:');
define('ENTRY_TOTAL', 'Insgesamt:');
define('ENTRY_DATE_PURCHASED', 'Gekauft am:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Letztes Update:');
define('ENTRY_NOTIFY_CUSTOMER', 'den Kunden benachrichtigen:');
define('ENTRY_NOTIFY_COMMENTS', 'Anh&auml;ngende Kommentare:');
define('ENTRY_PRINTABLE', 'Rechnung drucken');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Bestellung l&ouml;schen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, diese Bestellung l&ouml;schen zu wollen?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Produktmenge wiederherstellen');
define('TEXT_DATE_ORDER_CREATED', 'Erstellt am:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Letzte Modifizierung:');
define('TEXT_INFO_PAYMENT_METHOD', 'Zahlweise:');

define('TEXT_ALL_ORDERS', 'Alle Bestellungen');
define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellhistorie verf&uuml;gbar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Bestellung updaten');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestellt am:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Ihre Bestellung wurde zu folgendem Status updatet.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bitte falls Sie Fragen haben, antworten Sie auf diese E-Mail.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Die Kommentare zu Ihrer Bestellung sind es' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fehler: Bestellung existiert nicht.');
define('SUCCESS_ORDER_UPDATED', 'Erfolg: Bestellung wurde mit Erfolg updatet.');
define('WARNING_ORDER_NOT_UPDATED', 'Warnung: Nichts zu &auml;ndern. Die Bestellung wurde nicht updatet.');

define('ENTRY_CREDIT_CARD_CVN', 'CVN:');
define('TEXT_ADD_A_NEW_PRODUCT', 'Neues Produkt zunzufügen');
define('TEXT_CALCULATE_TOTALS', 'Gesamtbetrag neu berechnen:');
define('HEADING_TITLE_SEARCH_PRODUCTS','Suche:');
define('TEXT_PRODUCT','Produkt:');
define('TEXT_APPLY_FILTER','Die Produktliste ist zu groß. Bitte benutzen Sie Suche');
define('TEXT_QUANTITY', 'Menge:');
define('TEXT_OPTIONS', 'Optionen:');
define('TEXT_CHOOSE_SHIPPING_METHOD', 'Choose shipping method:');
define('TEXT_SELECT_PAYMENT_METHOD', 'Choose payment method:');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Zur Zeit bieten wir Ihnen nur eine Zahlungsweise an.'); 
define('TEXT_ENTER_SHIPPING_INFORMATION', 'Zur Zeit bieten wir Ihnen nur eine Versandart an.');
define('TEXT_NO_PRODUCTS_FOUND', 'Es gibt kein Produkt!');
?>
