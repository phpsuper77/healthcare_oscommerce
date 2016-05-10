<?php
/*
  $Id: links.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Links');
define('HEADING_TITLE_SEARCH', 'Suche:');

define('TABLE_HEADING_TITLE', 'Titel');
define('TABLE_HEADING_URL', 'URL');
define('TABLE_HEADING_CLICKS', 'Klicks');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_HEADING_DELETE_LINK', 'Link l&ouml;schen');
define('TEXT_INFO_HEADING_CHECK_LINK', 'Link &uuml;berpr&uuml;fen');

define('TEXT_DELETE_INTRO', 'Sind Sie sicher, diesen Link l&ouml;schen zu wollen?');

define('TEXT_INFO_LINK_CHECK_RESULT', 'Link-&Uuml;berpr&uuml;fergebnis:');
define('TEXT_INFO_LINK_CHECK_FOUND', 'Gefunden');
define('TEXT_INFO_LINK_CHECK_NOT_FOUND', 'Nicht gefunden');
define('TEXT_INFO_LINK_CHECK_ERROR', 'Fehler bei dem URL-Auslesen');


define('TEXT_INFO_LINK_STATUS', 'Status:');
define('TEXT_INFO_LINK_CATEGORY', 'Kategorie:');
define('TEXT_INFO_LINK_CONTACT_NAME', 'Kontaktname:');
define('TEXT_INFO_LINK_CONTACT_EMAIL', 'Kontakt-Email:');
define('TEXT_INFO_LINK_CLICK_COUNT', 'Klicks:');
define('TEXT_INFO_LINK_DESCRIPTION', 'Beschreibung:');
define('TEXT_DATE_LINK_CREATED', 'Link Submittet:');
define('TEXT_DATE_LINK_LAST_MODIFIED', 'Letzte Modifizierung:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE EXISTIERT NICHT');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Link Status Update');
define('EMAIL_TEXT_STATUS_UPDATE', 'Sehr geehrte(r) %s,' . "\n\n" . 'Der Status Ihres Links auf ' . STORE_NAME . ' wurde aktualisiert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bitte auf diese Email antworten, falls es Fragen geben m&uuml;ssen.' . "\n");

// VJ todo - move to common language file
define('CATEGORY_WEBSITE', 'Webseite Details');
define('CATEGORY_RECIPROCAL', 'Reziproke Page Details');
define('CATEGORY_OPTIONS', 'Optionen');

define('ENTRY_LINKS_TITLE', 'Seitentitel:');
define('ENTRY_LINKS_TITLE_ERROR', 'Linktitel muss mindestens ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' Zeichen haben.');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'URL muss mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Zeichen haben.');
define('ENTRY_LINKS_CATEGORY', 'Kategorie:');
define('ENTRY_LINKS_DESCRIPTION', 'Beschreibung:');
define('ENTRY_LINKS_DESCRIPTION_ERROR', 'Beschreibung  muss mindestens ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' Zeichen haben.');
define('ENTRY_LINKS_IMAGE', 'Image URL:');
define('ENTRY_LINKS_CONTACT_NAME', 'Vollst&auml;ndiger Name:');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Ihr vollst&auml;ndiger Name muss mindestens ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' Zeichen haben.');
define('ENTRY_LINKS_RECIPROCAL_URL', 'Reziproke Page:');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Reziproke Page muss mindestens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' Zeichen haben.');
define('ENTRY_LINKS_STATUS', 'Status:');
define('ENTRY_LINKS_NOTIFY_CONTACT', 'Kontakt benachrichtigen:');
define('ENTRY_LINKS_RATING', 'Rating:');
define('ENTRY_LINKS_RATING_ERROR', 'Rating muss leer sein.');

define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Angezeigt <b>%d</b> bis <b>%d</b> (von <b>%d</b> Links)');

define('IMAGE_NEW_LINK', 'Neuer Link');
define('IMAGE_CHECK_LINK', 'Link &uuml;berpr&uuml;fen');
?>
