<?php
/*
  $Id: links.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Links');

if ($display_mode == 'links') {
  define('HEADING_TITLE', 'Links');
  define('TABLE_HEADING_LINKS_IMAGE', '');
  define('TABLE_HEADING_LINKS_TITLE', 'Titel');
  define('TABLE_HEADING_LINKS_URL', 'URL');
  define('TABLE_HEADING_LINKS_DESCRIPTION', 'Beschreibung');
  define('TABLE_HEADING_LINKS_COUNT', 'Klicks');
  define('TEXT_NO_LINKS', '&Uuml;ber diese Kategorie stehen zu Ihrer Verf&uuml;gung keine Links .');
} elseif ($display_mode == 'Kategorien') {
  define('HEADING_TITLE', 'Link-Kategorien');
  define('TEXT_NO_CATEGORIES', 'Keine Link-Kategorien stehen zu Ihrer Verf&uuml;gung.');
}

// VJ todo - move to common language file
define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Links <b>%d</b> bis <b>%d</b> (aus <b>%d</b> )');

define('IMAGE_BUTTON_SUBMIT_LINK', 'Link absenden');
?>
