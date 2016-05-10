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
  $infobox_contents[] = array(array('text' =>sprintf(HEADING_TITLE_REVIEWS, $product_info_values['newsdesk_article_name'])), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>         
         </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = sprintf(HEADING_TITLE, $product_info_values['newsdesk_article_name']);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('newsdesk_reviews_write') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('newsdesk_reviews_write'); ?></td>
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
<?php
  }
?> 
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td class="mainBold"><?php echo TABLE_HEADING_NUMBER; ?></td>
		<td class="mainBold"><?php echo TABLE_HEADING_AUTHOR; ?></td>
		<td align="center" class="mainBold"><?php echo TABLE_HEADING_RATING; ?></td>
		<td align="center" class="mainBold"><?php echo TABLE_HEADING_READ; ?></td>
		<td align="right" class="mainBold"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
	</tr>
	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>

<?php
$reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_NEWSDESK_REVIEWS . " where newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' and approved = 1 order by reviews_id DESC");

if (tep_db_num_rows($reviews)) {
	$row = 0;
	while ($reviews_values = tep_db_fetch_array($reviews)) {
		$row++;
		if (strlen($row) < 2) {
			$row = '0' . $row;
		}
		$date_added = tep_date_short($reviews_values['date_added']);
		if (($row / 2) == floor($row / 2)) {
			echo '<tr class="productReviews-even">' . "\n";
		} else {
			echo '<tr class="productReviews-odd">' . "\n";
		}
		echo '<td class="smallText">' . $row . '.</td>' . "\n";
		echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a></td>' . "\n";
		echo '<td align="center" class="smallText">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'reviews/stars_' . $reviews_values['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_values['reviews_rating'])) . '</td>' . "\n";
		echo '<td align="center" class="smallText">' . $reviews_values['reviews_read'] . '</td>' . "\n";
		echo '<td align="right" class="smallText">' . $date_added . '</td>' . "\n";
		echo '</tr>' . "\n";
	}
} else {
?>

	<tr class="productReviews-odd">
		<td colspan="5" class="smallText"><?php echo TEXT_NO_REVIEWS; ?></td>
	</tr>

<?php
}
?>
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
                               array('params' => 'align=left width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_NEWSDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_NEWSDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>        
        </td>
      </tr>
        </table></td>
      </tr>
    </table>