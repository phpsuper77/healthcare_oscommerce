<?php
/*
  $Id: affiliate_banners.php,v 1.1.1.1 2005/12/03 21:36:05 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 -2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Partnerprogramm Bannerverwaltung');

define('TABLE_HEADING_BANNERS', 'Banner');
define('TABLE_HEADING_GROUPS', 'Gruppe');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATISTICS', 'Anzeigen / Klicks');
define('TABLE_HEADING_PRODUCT_ID', 'Produkt ID');

define('TEXT_BANNERS_TITLE', 'Titel des Banners:');
define('TEXT_BANNERS_GROUP', 'Banner-Gruppe:');
define('TEXT_BANNERS_NEW_GROUP', ', oder geben Sie unten eine neue Banner-Gruppe ein');
define('TEXT_BANNERS_IMAGE', 'Bild (Datei):');
define('TEXT_BANNERS_IMAGE_LOCAL', ', oder geben Sie unten die lokale Datei auf Ihrem Server an');
define('TEXT_BANNERS_IMAGE_TARGET', 'Bildziel (Speichern nach):');
define('TEXT_BANNERS_HTML_TEXT', 'HTML Text:');
define('TEXT_AFFILIATE_BANNERS_NOTE', '<b>Banner Bemerkung:</b><ul><li>Sie k&ouml;nnen Bild- oder HTML-Text-Banner verwenden, beides gleichzeitig ist nicht m&ouml;glich.</li><li>Wenn Sie beide Bannerarten gleichzeitig verwenden, wird nur der HTML-Text Banner angezeigt.</li></ul>');

define('TEXT_BANNERS_LINKED_PRODUCT','Produkt ID');
define('TEXT_BANNERS_LINKED_PRODUCT_NOTE','Wenn Sie das Banner mit einem Produkt verlinken wollen, geben Sie hier die Produkt ID ein. Wenn Sie mit der Startseite verlinken wollen geben Sie "0" ein.');

define('TEXT_BANNERS_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_BANNERS_STATUS_CHANGE', 'Status ge&auml;ndert: %s');

define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diesen Banner l&ouml;schen m&ouml;chten?');
define('TEXT_INFO_DELETE_IMAGE', 'Bannerbild l&ouml;schen');

define('SUCCESS_BANNER_INSERTED', 'Erfolg: Der Banner wurde eingef&uuml;gt.');
define('SUCCESS_BANNER_UPDATED', 'Erfolg: Der Banner wurde aktualisiert.');
define('SUCCESS_BANNER_REMOVED', 'Erfolg: Der Banner wurde gel&ouml;scht.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Fehler: Ein Bannertitel wird ben&ouml;tigt.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Fehler: Eine Bannergruppe wird ben&ouml;tigt.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Zielverzeichnis existiert nicht %s.');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Zielverzeichnis ist nicht beschreibbar: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Fehler: Bild existiert nicht.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Fehler: Bild kann nicht gel&ouml;scht werden.');
define('TEXT_AFFILIATE_NAME', 'Banner-Name: ');
define('TEXT_INFORMATION', 'W&auml;hlen Sie einen untenhalb angebotenen Banner oder Link, den Sie auf Ihrer Webseite unterbringen m&ouml;chten.');
define('TEXT_AFFILIATE_INFO', 'Kopieren Sie den HTML-Code und f&uuml;gen Sie diesen in Ihrer Webseite ein.');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER', 'BUILD-A-LINK');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER_INFO', 'Geben Sie die Produkt-ID (in der URL sichtbar) von dem Produkt ein, das Sie auf Ihrer Webseite anbieten m&ouml;chten und bet&auml;tigen Sie den Button "Build a Link"');

define('IMAGE_BUTTON_BUILD_A_LINK','Link für einen Produkt-Banner erzeugen'); 
?>