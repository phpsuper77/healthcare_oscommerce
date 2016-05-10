<?php
/*
  $Id: newsdesk_latest.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Released under the GNU General Public License

  Ti use this box call rss.php?box=categories
*/

$latest_news_var_query = tep_db_query(
'select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, 
p.newsdesk_image, p.newsdesk_date_added, p.newsdesk_last_modified, 
p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' 
pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . (int)$languages_id . '" and newsdesk_status = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_NEWSDESK_NEWS);


$latest_news_string = '';

$row = 0;
while ($latest_news = tep_db_fetch_array($latest_news_var_query))  {
$latest_news['newsdesk'] = array(
		'name' => $latest_news['newsdesk_article_name'],
		'id' => $latest_news['newsdesk_id'],
		'date' => $latest_news['newsdesk_date_added'],
	);

//
// Print
//
print "<item>\n";
print "<title>".$latest_news['newsdesk_article_name'] ."</title>\n";
print "<link>".tep_href_link(FILENAME_NEWSDESK_INFO, "newsdesk_id=".$latest_news['newsdesk_id'])  . "</link>\n";
print "</item>\n\n";

	$row++;
}

?>


<?php
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