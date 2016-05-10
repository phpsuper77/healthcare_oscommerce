<?php

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_REVIEWS_ARTICLE);

// lets retrieve all $HTTP_GET_VARS keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
  $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
  $get_params_back = $get_params;
}

$product = tep_db_query("select newsdesk_article_name from " . TABLE_NEWSDESK_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' and newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' ");
$product_info_values = tep_db_fetch_array($product);
?>



<!-- BEGIN newsdesk_article_require //-->
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="main"><?php echo sprintf(HEADING_TITLE_REVIEWS, $product_info_values['newsdesk_article_name']); ?></td>
	</tr>
	<tr>
		<td class="footer"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
	</tr>
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
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
$reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_NEWSDESK_REVIEWS . " where approved='1' and newsdesk_id = '" . (int)$HTTP_GET_VARS['newsdesk_id'] . "' order by reviews_id DESC");

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

	<tr>
		<td colspan="5"><?php echo tep_draw_separator(); ?></td>
	</tr>
	<tr>
		<td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
	</tr>
</table>