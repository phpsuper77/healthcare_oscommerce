<?php
/* $Id: xsell_products.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2002 osCommerce

xsell from Senia :)
release 28.09.2004
*/

define('CROSS_SELL_SUCCESS', 'Produkt ID ' . $_GET['add_related_product_ID'] . ' mit ihren Empfehlungen wurde erfolgreich aktualisiert.');
define('SORT_CROSS_SELL_SUCCESS', 'Sortierreihenfolge für die empfohlenen Artikel sind für das Hauptprodukt ' .$_GET['add_related_product_ID'] . ' aktuallisiert worden.');

/* define('CROSS_SELL_SUCCESS', 'Cross Sell Items Successfully Update For Cross Sell Product #'.$_GET['add_related_product_ID']);
define('SORT_CROSS_SELL_SUCCESS', 'Sort Order Successfully Update For Cross Sell Product #'.$_GET['add_related_product_ID']);
*/

define('HEADING_TITLE', 'Aritikel für Empfehlungskauf');
define('TABLE_HEADING_PRODUCT_ID', 'Produkt Id');
define('TABLE_HEADING_PRODUCT_MODEL', 'Artikelnummer');
define('TABLE_HEADING_PRODUCT_NAME', 'Produktname');
define('TABLE_HEADING_CURRENT_SELLS', 'Empfohlene Artikel');
define('TABLE_HEADING_UPDATE_SELLS', 'Aktuallisiere Artikelempfehlungen');
define('TABLE_HEADING_PRODUCT_IMAGE', 'Artikelbild');
define('TABLE_HEADING_PRODUCT_PRICE', 'Produktpreis');
define('TABLE_HEADING_CROSS_SELL_THIS', 'Diesen Artikel zum verkauften Hauptprodukt empfehlen?');
define('TEXT_EDIT_SELLS', 'Bearbeiten');
define('TEXT_SORT', 'Anordnen');
define('TEXT_SETTING_SELLS', 'Einstellungen für die Artikelempfehlung zum Artikel');
define('TEXT_PRODUCT_ID', 'Produkt Id');
define('TEXT_MODEL', 'Art.nr.');
define('TABLE_HEADING_PRODUCT_SORT', 'Sortierreihenfolge');
define('TEXT_NO_IMAGE', 'Kein Bild');
define('TEXT_CROSS_SELL', 'Artikelempfehlung');
?>
