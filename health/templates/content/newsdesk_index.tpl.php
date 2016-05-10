<?php
if ($category_depth == 'nested') {
	$category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_NEWSDESK_CATEGORIES . " c, " .  TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_news_id . "' and cd.categories_id = '" . (int)$current_news_id . "' and cd.language_id = '" . $languages_id . "'");

	$category = tep_db_fetch_array($category_query);
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
//if (($category['categories_image'] = 'NULL') or ($category['categories_image'] = '')) {
if ( !tep_not_null($category['categories_image']) ) {
  $str = tep_draw_separator('pixel_trans.gif', '1', '1');
} else {
  $str = tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => $str));
  new contentPageHeading($infobox_contents);
?> 
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
  $header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>

<?php
if ($newsPath && ereg('_', $newsPath)) {
// check to see if there are deeper categories within the current category
	$category_links = array_reverse($newsPath_array);
	$size = sizeof($category_links);
	for($i=0; $i<$size; $i++) {
		$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
		if (tep_db_num_rows($categories_query) < 1) {
// do nothing, go through the loop
		} else {
			break; // we've found the deepest category the customer is in
		}
	}
} else {
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_news_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
  $print_echo = '';
  if ($categories['categories_image'] != '' && is_file(DIR_FS_CATALOG . '/' . DIR_WS_IMAGES . $categories['categories_image'])) {
    $print_echo = tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT);
  }

	$newsPath_new = newsdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

	echo '<td align="center" valign="top" height="100%">
     <TABLE WIDTH="' . $width . '" CELLSPACING=0 CELLPADDING=0 BORDER=0 height="100%">
     <TR>
       <TD height="100%" align="center"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new, 'NONSSL') . '">' .  $print_echo . '</a></TD>
     </TR>
     <tr>
      <td class="smallText" align="center"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new, 'NONSSL') . '">' .  $categories['categories_name'] . '</td>
     </tr>
     </TABLE>
  </td>' . "\n";

	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>

	</tr>
</table>
</td></tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
} elseif ($category_depth == 'products') {
  $newsdesk_query_raw = 'select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed, p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . ' pd, ' . TABLE_NEWSDESK_TO_CATEGORIES . " p2c WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = '" . (int)$languages_id . "' and newsdesk_status = 1 and p.newsdesk_id = p2c.newsdesk_id and p2c.categories_id = '" . (int)$current_news_id . "' ORDER BY newsdesk_sticky DESC, newsdesk_date_added DESC";
?>

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
	<tr>
		<td>
<?php
	$image = tep_db_query("select c.categories_image, cd.categories_name from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_id = '" . (int)$current_news_id . "'");
	
	$image = tep_db_fetch_array($image);
	$categories_name = $image['categories_name'];
  if ($image['categories_image'] != '' && is_file(DIR_FS_CATALOG . '/' . DIR_WS_IMAGES . $image['categories_image'])){
    $displ_image = tep_image(DIR_WS_IMAGES . $image['categories_image'], HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
  }

    
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' => HEADING_TITLE . ' ' . $categories_name), array('params'=> 'align=right', 'text' => $displ_image));
  new contentPageHeading($infobox_contents);

?>
<?php
// BOF: Lango Added for template MOD
}else{
  $header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_NEWSDESK_CATEGORIES . " c, " . TABLE_NEWSDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_news_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
  $print_echo = '';
  if ($categories['categories_image'] != '' && is_file(DIR_FS_CATALOG . '/' . DIR_WS_IMAGES . $categories['categories_image'])) {
    $print_echo = tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT);
  }

	$newsPath_new = newsdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

	echo '<td align="center" valign="top" height="100%">
     <TABLE WIDTH="' . $width . '" CELLSPACING=0 CELLPADDING=0 BORDER=0 height="100%">
     <TR>
       <TD height="100%" align="center"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new, 'NONSSL') . '">' .  $print_echo . '</a></TD>
     </TR>
     <tr>
      <td class="smallText" align="center"><a href="' . tep_href_link(FILENAME_NEWSDESK_INDEX, $newsPath_new, 'NONSSL') . '">' .  $categories['categories_name'] . '</td>
     </tr>
     </TABLE>
  </td>' . "\n";

	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>
	<tr>
		<td>
    <?php 
    $listing_split = new splitPageResults($newsdesk_query_raw, MAX_DISPLAY_SEARCH_RESULTS, 'p.newsdesk_id');
if (($listing_split->number_of_rows > 0)){
    if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
</table>
<?php
  }
	$info_box_contents = array();
	$row = 0;
  $listing_query = tep_db_query($listing_split->sql_query);

	while ($newsdesk_var = tep_db_fetch_array($listing_query)) {
    displayNews($info_box_contents, $newsdesk_var);
	}

  new contentBox($info_box_contents);

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>

<?php
  }
}else {
  $list_box_contents = array();

  $list_box_contents[0] = array('params' => 'class="productListing-odd"');
  $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                 'text' => TEXT_NO_ARTICLES);

  new contentBox($list_box_contents); 
}
?>    
    </td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="5">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>       
        </td>
      </tr>
<?php
}
?>
</table>
<!-- body_text_eof //-->