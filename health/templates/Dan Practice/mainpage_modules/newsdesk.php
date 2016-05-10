<!-- newsdesk //-->
<?php

$newsdesk_sticky_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed, p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . (int)$languages_id . '" and newsdesk_status = 1 and p.newsdesk_sticky = 1 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_STICKY_NEWS);

$newsdesk_var_query = tep_db_query('select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed, p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . (int)$languages_id . '" and newsdesk_status = 1 and p.newsdesk_sticky = 0 ORDER BY newsdesk_date_added DESC LIMIT ' . MAX_DISPLAY_NEWSDESK_NEWS);

if (tep_db_num_rows($newsdesk_var_query) || tep_db_num_rows($newsdesk_sticky_query)){

	$info_box_contents = array();
	$info_box_contents[] = array('align' => 'left',
                                 'text'  => TABLE_HEADING_NEWSDESK);
	new contentBoxHeading($info_box_contents, false, false);

	$info_box_contents = array();
	$row = 0;
	while ($newsdesk_var = tep_db_fetch_array($newsdesk_sticky_query)) {
    displayNews($info_box_contents, $newsdesk_var);
	}
	while ($newsdesk_var = tep_db_fetch_array($newsdesk_var_query)) {
    displayNews($info_box_contents, $newsdesk_var);
	}

	new contentBox($info_box_contents);

//  new infoboxFooter($info_box_contents, true, true);
}

?>
<!-- newsdesk_eof //-->
