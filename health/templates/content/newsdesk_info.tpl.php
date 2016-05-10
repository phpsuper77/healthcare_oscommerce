    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
$product_info = tep_db_query("select p.newsdesk_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_url, pd.newsdesk_article_viewed, p.newsdesk_date_added, p.newsdesk_date_available, pd.newsdesk_image_text, pd.newsdesk_image_text_two, pd.newsdesk_image_text_three from " . TABLE_NEWSDESK . " p, " . TABLE_NEWSDESK_DESCRIPTION . " pd where p.newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' and pd.newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' and pd.language_id = '" . (int)$languages_id . "' and if(p.newsdesk_date_available is null, 1, to_days(newsdesk_date_available) <= to_days(now()))");

if (!tep_db_num_rows($product_info)) { // product not found in database
?>
	<tr>
		<td class="main"><?php new contentBox(array(array('text' => TEXT_NEWS_NOT_FOUND))); ?></td>
	</tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>

	<tr>
		<td align="right" width="100%">
      <?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right ', 'text' => '<a href="'. tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') .'">'.tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"').'</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>

		</td>
	</tr>

<?php
} else {
// BOF: Lango Added for template MOD
$product_info_values = tep_db_fetch_array($product_info);

if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' => $product_info_values['newsdesk_article_name']));
  new contentPageHeading($infobox_contents);
?>         
        </td>
      </tr>

	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = TEXT_NEWSDESK_HEADING;
}
// EOF: Lango Added for template MOD
?>
<?php



	tep_db_query("update " . TABLE_NEWSDESK_DESCRIPTION . " set newsdesk_article_viewed = newsdesk_article_viewed+1 where newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' and language_id = '" . (int)$languages_id . "'");
	

if ($product_info_values['newsdesk_image'] != '') {

$insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image'], ($product_info_values['newsdesk_image_text']?$product_info_values['newsdesk_image_text']:$product_info_values['newsdesk_article_name']), '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

if ($product_info_values['newsdesk_image_two'] != '') {
$insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_two'], ($product_info_values['newsdesk_image_text_two']?$product_info_values['newsdesk_image_text_two']:$product_info_values['newsdesk_article_name']), '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}

if ($product_info_values['newsdesk_image_three'] != '') {
$insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['newsdesk_image_three'], ($product_info_values['newsdesk_image_text_three']?$product_info_values['newsdesk_image_text_three']:$product_info_values['newsdesk_article_name']), '', '', 
'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>
';
}
?>

<tr>
        <td>



<table border="0" width="100%" cellspacing="3" cellpadding="3">
      <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td width="100%" class="main" valign="top" colspan="2">
<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr class="headerNavigation">
		<td class="mainBold"><?php echo stripslashes($product_info_values['newsdesk_article_shorttext']); ?></td>
		<td class="main" align="right">&nbsp;
			<?php echo sprintf(TEXT_NEWSDESK_DATE, tep_date_long($product_info_values['newsdesk_date_added']));; ?>
		</td>
	</tr>
</table>
		</td>
	</tr>
	<tr>
		<td width="100%" class="main" valign="top">

<?php echo stripslashes($product_info_values['newsdesk_article_description']); ?>

<?php if ($product_info_values['newsdesk_article_url']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_NEWSDESK_LINK, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['newsdesk_article_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php
$reviews = tep_db_query("select count(*) as count from " . TABLE_NEWSDESK_REVIEWS . " where approved='1' and newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "'");
$reviews_values = tep_db_fetch_array($reviews);
?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="mainBold"><?php echo TEXT_NEWSDESK_REVIEWS_HEADING; ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_VIEWED . $product_info_values['newsdesk_article_viewed'] ?></td>
	</tr>
<?php
if ( DISPLAY_NEWSDESK_REVIEWS ) {
?>
	<tr>
		<td class="main"><?php echo TEXT_NEWSDESK_REVIEWS . ' ' . $reviews_values['count']; ?></td>
	</tr>
<?php
}
?>
</table>
		</td>
		<td width="" class="main" valign="top" align="center">
<?php
echo $insert_image;
echo $insert_image_two;
echo $insert_image_three;
?>
		</td>

	</tr>
	<tr>
		<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
</table>

<?php
if ( DISPLAY_NEWSDESK_REVIEWS ) {
	if ($reviews_values['count'] > 0) {
		require FILENAME_NEWSDESK_ARTICLE_REQUIRE;
	}
}
?>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php 
if ( DISPLAY_NEWSDESK_REVIEWS ) {
	$add = '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE,
	IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>';
}
?>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=left width=100%', 'text' => $add),
                               array('params' => 'align=right ', 'text' => '<a href="'. tep_href_link(FILENAME_DEFAULT, '', 'NONSSL').'">'. tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"').'</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
		</td>
	</tr>
</table>

		</td>
	</tr>


<?php } ?>
</table>
<!-- body_text_eof //-->
