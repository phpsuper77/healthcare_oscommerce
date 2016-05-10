<?
// define the filenames used in the project


// BEGIN newdesk
//define('FILENAME_NEWSDESK_REVIEWS_WRITE', 'newsdesk_reviews_write.php');
//define('FILENAME_NEWSDESK_REVIEWS_INFO', 'newsdesk_reviews_info.php');
//define('FILENAME_NEWSDESK_REVIEWS_ARTICLE', 'newsdesk_reviews_article.php');
//define('FILENAME_NEWSDESK_INFO', 'newsdesk_info.php');
//define('FILENAME_NEWSDESK_INDEX', 'newsdesk_index.php');

define('FILENAME_NEWSDESK', 'newsdesk.php');
define('FILENAME_NEWSDESK_LISTING', 'newsdesk_listing.php');
define('FILENAME_NEWSDESK_LATEST', 'newsdesk_latest.php');

define('FILENAME_NEWSDESK_REVIEWS', 'newsdesk_reviews.php');
define('FILENAME_NEWSDESK_ARTICLE_REQUIRE', DIR_WS_INCLUDES . 'modules/newsdesk/newsdesk_article_require.php');

define('DIR_WS_RSS', DIR_WS_INCLUDES . 'modules/newsdesk/rss/');
//define('FILENAME_NEWSDESK_STICKY', 'newsdesk_sticky.php');
// END newsdesk


// BEGIN newdesk
define('TABLE_NEWSDESK', 'newsdesk');
define('TABLE_NEWSDESK_DESCRIPTION', 'newsdesk_description');
define('TABLE_NEWSDESK_TO_CATEGORIES', 'newsdesk_to_categories');
define('TABLE_NEWSDESK_CATEGORIES', 'newsdesk_categories');
define('TABLE_NEWSDESK_CATEGORIES_DESCRIPTION', 'newsdesk_categories_description');

define('TABLE_NEWSDESK_REVIEWS', 'newsdesk_reviews');
define('TABLE_NEWSDESK_REVIEWS_DESCRIPTION', 'newsdesk_reviews_description');
// END newsdesk


define('CONTENT_NEWSDESK_REVIEWS_WRITE', 'newsdesk_reviews_write');
define('FILENAME_NEWSDESK_REVIEWS_WRITE', CONTENT_NEWSDESK_REVIEWS_WRITE . '.php');

define('CONTENT_NEWSDESK_REVIEWS_INFO', 'newsdesk_reviews_info');
define('FILENAME_NEWSDESK_REVIEWS_INFO', CONTENT_NEWSDESK_REVIEWS_INFO . '.php');

define('CONTENT_NEWSDESK_REVIEWS_ARTICLE', 'newsdesk_reviews_article');
define('FILENAME_NEWSDESK_REVIEWS_ARTICLE', CONTENT_NEWSDESK_REVIEWS_ARTICLE . '.php');

define('CONTENT_NEWSDESK_INFO', 'newsdesk_info');
define('FILENAME_NEWSDESK_INFO', CONTENT_NEWSDESK_INFO . '.php');

define('CONTENT_NEWSDESK_INDEX', 'newsdesk_index');
define('FILENAME_NEWSDESK_INDEX', CONTENT_NEWSDESK_INDEX . '.php');


function displayNews(&$info_box_contents, $element){
if ( DISPLAY_NEWSDESK_IMAGE && $element['newsdesk_image'] != '') {
  $insert_image = '<table border="0" cellspacing="0" cellpadding="0"><tr><td><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $element['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $element['newsdesk_image'], '', '') . '</a></td></tr></table>';
}
if ( DISPLAY_NEWSDESK_IMAGE_TWO && $element['newsdesk_image_two'] != '') {
  $insert_image_two = '<table border="0" cellspacing="0" cellpadding="0"><tr><td><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $element['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $element['newsdesk_image_two'], '', '') . '</a></td></tr></table>';
}

if ( DISPLAY_NEWSDESK_IMAGE_THREE && $element['newsdesk_image_three'] != '') {
  $insert_image_three = '<table border="0" cellspacing="0" cellpadding="0"><tr><td><a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $element['newsdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . $element['newsdesk_image_three'], '', '') . '</a></td></tr></table>';
}
if ( DISPLAY_NEWSDESK_VIEWCOUNT ) {
  $insert_viewcount = '<i>' . TEXT_NEWSDESK_VIEWED . $element['newsdesk_article_viewed'] . '</i>';
}

if ( DISPLAY_NEWSDESK_READMORE ) {
  $insert_readmore = '<a class="smallText" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $element['newsdesk_id']) . '">[' . TEXT_NEWSDESK_READMORE . ']</a>';
}

if ( DISPLAY_NEWSDESK_SUMMARY ) {
  $insert_summary = '<b>'. $element['newsdesk_article_shorttext'] . '</b>';
}

if ( DISPLAY_NEWSDESK_HEADLINE ) {
  $insert_headline = '<b>' . $element['newsdesk_article_name'] . '</b>';
}

if ( DISPLAY_NEWSDESK_DATE ) {
  $insert_date = '- <i>' . tep_date_long($element['newsdesk_date_added']) . '</i>';
}
if (DISPLAY_NEWSDESK_SUMMARY){
  $short_text = $element['newsdesk_article_shorttext'];
}
if (NEWSDESK_ARTICLE_DESCRIPTION ){
  $long_text = $element['newsdesk_article_description'];
}


if (DISPLAY_NEWSDESK_REVIEWS){
  $reviews = tep_db_query("select count(*) as count from " . TABLE_NEWSDESK_REVIEWS . " where approved='1' and newsdesk_id = '" . (int)$element['newsdesk_id'] . "'");
  $reviews_values = tep_db_fetch_array($reviews);
}

		$info_box_contents[] = array(
			'align' => 'left',
			'params' => 'class="smallText" valign="top"',
			'text' => '
<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="smallText" colspan="2">
' . $insert_headline . $insert_date . '
		</td>
		<td class="smallText" colspan="2" align="right">' . $insert_viewcount . '</td>
	</tr>
	<tr>
		<td class="headerNavigation" colspan="5" width="100%">' . tep_draw_separator('pixel_trans.gif', '100%', '1') . '</td>
	</tr>
	<tr>
		<td colspan="3">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td valign="top" width="">


<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td class="smallText">' . $insert_summary . '</td>
	</tr>
	<tr>
		<td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
	</tr>
  <tr>
    <td class="smallText">' . $short_text . '</td>
  </tr>
  <tr>
    <td class="smallText">' . $long_text . '</td>
  </tr>
	<tr>
		<td class="smallText">' . $insert_readmore . '</td>
	</tr>
</table>

		</td>
		<td valign="top" align="right">

' . $insert_image . '
' . $insert_image_two . '
' . $insert_image_three . '

		</td>
	</tr>
  <tr>
    <td colspan="2" class="smallText"><a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_ARTICLE, 'newsdesk_id=' . $element['newsdesk_id']). '">' . TEXT_NEWSDESK_REVIEWS . '</a> ' . $reviews_values['count'] . '</td>
  </tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>
		<td colspan="2">' . tep_draw_separator('pixel_trans.gif', '100%', '5') . '</td>
	</tr>
</table>');

}
?>