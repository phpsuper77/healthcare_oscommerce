<?php

define('HEADING_TITLE', 'FAQDesk ... Kategorie- und FAQ-Verwaltung');
define('HEADING_TITLE_SEARCH', 'Suche:');
define('HEADING_TITLE_GOTO', 'Gehe zu:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_FAQDESK', 'Frage');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATUS', 'Status');

define('IMAGE_NEW_STORY', 'Neue FAQ');

define('TEXT_CATEGORIES', 'Kategorien:');
define('TEXT_SUBCATEGORIES', 'Unterkategorien:');
define('TEXT_FAQDESK', 'FAQS:');
define('TEXT_NEW_FAQDESK', 'FAQS in der Kategorie &quot;%s&quot;');

define('TABLE_HEADING_LATEST_NEWS_HEADLINE', 'Headline');
define('TEXT_NEWS_ITEMS', 'FAQS:');
define('TEXT_INFO_HEADING_DELETE_ITEM', 'Artikel l&ouml;schen');
define('TEXT_DELETE_ITEM_INTRO', 'Sind Sie sicher, diesen Artikel l&ouml;schen zu wollen?');

define('TEXT_LATEST_NEWS_HEADLINE', 'Frage:');
define('TEXT_FAQDESK_ANSWER_LONG', 'Antwort (lang):');

define('IMAGE_NEW_NEWS_ITEM', 'Neue FAQ');

define('TEXT_FAQDESK_STATUS', 'FAQ Status:');
define('TEXT_FAQDESK_DATE_AVAILABLE', 'Verf&uuml;gbar ab:');
define('TEXT_FAQDESK_AVAILABLE', 'Beim Druckverlauf');
define('TEXT_FAQDESK_NOT_AVAILABLE', 'Out of Print');

define('TEXT_FAQDESK_URL', 'Extra URL:');
define('TEXT_FAQDESK_URL_WITHOUT_HTTP', '<small>(ohne http://)</small>');

define('TEXT_FAQDESK_ANSWER_SHORT', 'Antwort (kurz):');
define('TEXT_FAQDESK_ANSWER_LONG', 'Antwort (lang):');
define('TEXT_FAQDESK_QUESTION', 'Frage:');

define('TEXT_FAQDESK_DATE_AVAILABLE', 'Startdatum:');
define('TEXT_FAQDESK_DATE_ADDED', 'Diese FAQ wurde submittet auf:');

define('TEXT_FAQDESK_ADDED_LINK_HEADER', "Dies ist der Link, den Sie hinzugef&uuml;gt haben:");
define('TEXT_FAQDESK_ADDED_LINK', '<a href="http://%s" target="blank"><u>webpage</u></a>');

define('TEXT_FAQDESK_AVERAGE_RATING', 'Averages Rating:');
define('TEXT_DATE_ADDED', 'Hinzugef&uuml;gt am:');
define('TEXT_DATE_AVAILABLE', 'Verf&uuml;gbar ab:');
define('TEXT_LAST_MODIFIED', 'Letzte Modifizierung:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE EXISTIERT NICHT');
define('TEXT_NO_CHILD_CATEGORIES_OR_story', 'Bitte f&uuml;gen Sie eine neue Kategorie ein oder FAQ in<br>&nbsp;<br><b>%s</b>');

define('TEXT_EDIT_INTRO', 'Bitte nehmen Sie alle relevanten &Auml;nderungen vor');
define('TEXT_EDIT_CATEGORIES_ID', 'Kategorie-ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Kategoriename:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Kategoriebeschreibung:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Kategorie-Image:');
define('TEXT_EDIT_SORT_ORDER', 'Bestellung zuordnen:');

define('TEXT_INFO_COPY_TO_INTRO', 'Bitte w&auml;hlen Sie eine neue Kategorie, wohin Sie diese FAQ kopieren wollen');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Laufende Kategorien:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Neue Kategorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Kategorie bearbeiten');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Kategorie l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Kategorie verschieben');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'FAQ l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'FAQ verschieben');
define('TEXT_INFO_HEADING_COPY_TO', 'Kopieren nach');

define('TEXT_DELETE_CATEGORY_INTRO', 'Sind Sie sicher, diese Kategorie l&ouml;schen zu wollen?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Sind Sie sicher, diese FAQ l&ouml;schen zu wollen?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNUNG:</b> Dies betrifft %s (Kinder)Kategorien, verlinkt mit dieser Kategorie!');
define('TEXT_DELETE_WARNING_FAQDESK', '<b>WARNING:</b> Dies betrifft %s FAQs, verlinkt mit dieser Kategorie!');

define('TEXT_MOVE_FAQDESK_INTRO', 'Bitte w&auml;hlen Sie, in welche Kategorie dies zu verschieben ist.');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Bitte w&auml;hlen Sie, in welche Kategorie dies zu verschieben ist.');
define('TEXT_MOVE', 'Verschieben <b>%s</b> nach:');

define('TEXT_NEW_CATEGORY_INTRO', 'Bitte f&uuml;llen Sie folgende Information zur neuen Kategorie aus');
define('TEXT_CATEGORIES_NAME', 'Kategoriename:');
define('TEXT_CATEGORIES_DESCRIPTION_NAME', 'Kategoriebeschreibung:');
define('TEXT_CATEGORIES_IMAGE', 'Kategorie-Image:');
define('TEXT_SORT_ORDER', 'Bestellung zuordnen:');

define('EMPTY_CATEGORY', 'Kategorie leeren');

define('TEXT_HOW_TO_COPY', 'Verfahren kopieren:');
define('TEXT_COPY_AS_LINK', 'Link FAQ');
define('TEXT_COPY_AS_DUPLICATE', 'FAQ duplizieren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fehler: FAQS in dieselbe Kategorie nicht verlinkt werden k&ouml;nnen.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Katalogimagedirectory ist schreibgesch&uuml;tzt: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Katalogimagedirectory existiert nicht: ' . DIR_FS_CATALOG_IMAGES);

define('TEXT_FAQDESK_START_DATE', 'Startdatum:');
define('TEXT_DATE_FORMAT', 'Formatiert:');

define('TEXT_SHOW_STATUS', 'Status');

define('TEXT_DELETE_IMAGE', 'Image(s) l&ouml;schen?');
define('TEXT_DELETE_IMAGE_INTRO', 'ACHTUNG:: Das L&ouml;schen kann komplett diese(s) Image(s) verschieben.');

define('TEXT_FAQDESK_STICKY', 'Sticky Status');
define('TEXT_FAQDESK_STICKY_ON', 'EIN');
define('TEXT_FAQDESK_STICKY_OFF', 'AUS');
define('TABLE_HEADING_STICKY', 'Sticky');

define('TEXT_FAQDESK_IMAGE', 'FAQ Image(s):');

define('TEXT_FAQDESK_IMAGE_ONE', 'Image 1:');
define('TEXT_FAQDESK_IMAGE_TWO', 'Image 2:');
define('TEXT_FAQDESK_IMAGE_THREE', 'Image 3:');

define('TEXT_FAQDESK_IMAGE_SUBTITLE', 'Geben Sie den Imagetitel zum Image 1 ein:');
define('TEXT_FAQDESK_IMAGE_SUBTITLE_TWO', 'Geben Sie den Imagetitel zum Image 2 ein:');
define('TEXT_FAQDESK_IMAGE_SUBTITLE_THREE', 'Geben Sie den Imagetitel zum Image 3 ein:');

define('TEXT_FAQDESK_IMAGE_PREVIEW_ONE', 'FAQ Imagenummer 1:');
define('TEXT_FAQDESK_IMAGE_PREVIEW_TWO', 'FAQ Imagenummer 2:');
define('TEXT_FAQDESK_IMAGE_PREVIEW_THREE', 'FAQ Imagenummer 3:');
define('TAB_GENERAL', 'General');
define('TAB_IMAGE', 'Images');

define('TEXT_IMAGE_LOCATION', 'Bild-Platzierung'); 
define('TEXT_PREVIEW', 'Vorschau');
define('TEXT_DESTINATION', 'Destination:');
define('TEXT_UPLOAD_NEW_IMAGE', 'Neues Bild uploaden');
?>