<?php
if (!tep_db_num_rows($product_info)) { // product not found in database
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td class="main"><br><?php echo TEXT_NEWS_NOT_FOUND; ?></td>
	</tr>
	<tr>
		<td align="right">
<?php
$info_box_contents = array();
$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
array('params' => 'align=right width=100%', 'text' => '<a href="'. tep_href_link(FILENAME_FAQDESK_INDEX, tep_get_all_get_params(array('faqdesk_id')), 'NONSSL').'">'. tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"').'</a>'),
array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
new buttonBox($info_box_contents);
?>

		</td>
	</tr>
</table>


<?php
} else {
  tep_db_query("update " . TABLE_FAQDESK_DESCRIPTION . " set faqdesk_extra_viewed = faqdesk_extra_viewed+1 where faqdesk_id = '" . (int)$HTTP_GET_VARS['faqdesk_id'] . "' and language_id = '" . (int)$languages_id . "'");
  

  if (($product_info_values['faqdesk_image'] != '')) {
    $insert_image = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image'], ($product_info_values['faqdesk_image_text']?$product_info_values['faqdesk_image_text']:$product_info_values['faqdesk_question']), '', '', 'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>';
  }


  if (($product_info_values['faqdesk_image_two'] != '') ) {
    $insert_image_two = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
'. tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_two'], ($product_info_values['faqdesk_image_text_two']?$product_info_values['faqdesk_image_text_two']:$product_info_values['faqdesk_question']), '', '', 'hspace="5" vspace="5"'). '
		</td>
	</tr>
</table>';
  }

  if (($product_info_values['faqdesk_image_three'] != '')) {
    $insert_image_three = '
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>' . tep_image(DIR_WS_IMAGES . $product_info_values['faqdesk_image_three'], ($product_info_values['faqdesk_image_text_three']?$product_info_values['faqdesk_image_text_three']:$product_info_values['faqdesk_question']), '', '', 'hspace="5" vspace="5"') . '
		</td>
	</tr>
</table>';
  }

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
$infobox_contents = array();
$infobox_contents[] = array(array('text' =>TEXT_FAQDESK_HEADING), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
new contentPageHeading($infobox_contents);
?>     
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
  $header_text = TEXT_FAQDESK_HEADING;
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
	<tr class="headerNavigation">
		<td class="mainBold"><?php echo $product_info_values['faqdesk_question']; ?></td>
		<td class="main" align="right">&nbsp;
			<?php if ( FAQDESK_DATE_AVAILABLE ) echo sprintf(TEXT_FAQDESK_DATE, tep_date_long($product_info_values['faqdesk_date_added']));; ?>
		</td>
	</tr>
</table>


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td width="100%" class="main" valign="top">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_ANSWER_SHORT; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_short']); ?>

<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_ANSWER_LONG; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
</table>
<?php echo stripslashes($product_info_values['faqdesk_answer_long']); ?>

<?php if ($product_info_values['faqdesk_extra_url']) { ?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_LINK_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_FAQDESK_LINK, tep_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info_values['faqdesk_extra_url']), 'NONSSL', true, false)); ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php
if ( false ) {
$reviews = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_REVIEWS . " where approved='1' and faqdesk_id = '" . (int)$HTTP_GET_VARS['faqdesk_id'] . "'");
$reviews_values = tep_db_fetch_array($reviews);
?>
<br>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS_HEADING; ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_VIEWED . $product_info_values['faqdesk_extra_viewed'] ?></td>
	</tr>
<?php
}

if ( DISPLAY_FAQDESK_REVIEWS ) {
?>
	<tr>
		<td class="main"><?php echo TEXT_FAQDESK_REVIEWS . ' ' . $reviews_values['count']; ?></td>
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
</table>

<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
  if ($reviews_values['count'] > 0) {
    require FILENAME_FAQDESK_ARTICLE_REQUIRE;
  }
}
?>
<?php
/*
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td align="right" class="main">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>';
?>
</td>
</tr>
</table>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
</tr>
<tr>
<td class="main">
<a href="<?php echo tep_href_link(FILENAME_FAQDESK_REVIEWS_ARTICLE, substr(tep_get_all_get_params(), 0, -1)); ?>">
<?php echo tep_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS); ?></a>
</td>
<td align="right" class="main">
<a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>
</td>
</tr>
</table>
*/
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="3">
<?php
if ( DISPLAY_FAQDESK_REVIEWS ) {
  $add =  '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>';
}
$info_box_contents = array();
$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
array('params' => 'align=left', 'text' => $add),
array('params' => 'align=right width=100%', 'text' => '<a href="'. tep_href_link(FILENAME_FAQDESK_INDEX, tep_get_all_get_params(array('faqdesk_id')), 'NONSSL').'">'. tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"').'</a>'),
array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
new buttonBox($info_box_contents);
?>        
       
        </td>
      </tr>
    </table>
<?php
}
?>
