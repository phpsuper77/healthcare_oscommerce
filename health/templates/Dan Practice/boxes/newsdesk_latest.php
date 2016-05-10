<?php

// set application wide parameters
// this query set is for NewsDesk
if ( DISPLAY_LATEST_NEWS_BOX ) {
?>
<!-- newsdesk //-->
	<tr>
		<td class="infoBoxCell">
<?php
$latest_news_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, p.newsdesk_image, p.newsdesk_date_added, p.newsdesk_last_modified, p.newsdesk_date_available, p.newsdesk_status from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . (int)$languages_id . '" and if(p.newsdesk_date_available is null, 1, to_days(newsdesk_date_available) <= to_days(now())) and newsdesk_status = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_NEWSDESK_NEWS);

if (tep_db_num_rows($latest_news_var_query)) { // there is no news
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_NEWSDESK_LATEST);
    
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }
    
  }
  $info_box_contents = array();
  while ($latest_news = tep_db_fetch_array($latest_news_var_query))  {
    $info_box_contents[] = array(
  		                           'align' => 'left',
  		                           'params' => 'valign="top"',
  		                           'text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $latest_news['newsdesk_id']) . '">' . $latest_news['newsdesk_article_name'] . '</a>');
    
  }

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }

}
?>
<!-- newsdesk_eof //-->
		</td>
	</tr>

<?php
}
?>