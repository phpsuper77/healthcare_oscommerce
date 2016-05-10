<?php

if ( ($category_depth == 'products') ) {

define('HEADING_TITLE', 'Nachrichten');

define('TABLE_HEADING_IMAGE', 'Image');
define('TABLE_HEADING_ARTICLE_NAME', 'Headline');
define('TABLE_HEADING_ARTICLE_SHORTTEXT', 'Kurzfassung');
define('TABLE_HEADING_ARTICLE_DESCRIPTION', 'Inhalt');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_DATE_AVAILABLE', 'Datum');
define('TABLE_HEADING_ARTRICLE_URL', 'URL f&uuml;r Ressourcen');

define('TEXT_NO_ARTICLES', 'Zu dieser Kategorie gibt es leider keine Nachrichten.');

define('TEXT_NUMBER_OF_ARTICLES', 'Artikelnummer: ');
define('TEXT_SHOW', '<b>Show:</b>');

} elseif ($category_depth == 'Top') {

define('HEADING_TITLE', 'Was ist hier neu?');

} elseif ($category_depth == 'nested') {

define('HEADING_TITLE', 'Nachrichtenkategorien');

}



/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	NewsDesk
	version:		1.4.5
	date:			2003-08-31
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>