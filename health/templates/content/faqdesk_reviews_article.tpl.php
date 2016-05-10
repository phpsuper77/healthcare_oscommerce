<?php

?>

<!-- body_text //-->

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
	<tr>
		<td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>sprintf(HEADING_TITLE, $product_info_values['faqdesk_question'])), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', sprintf(HEADING_TITLE, $product_info_values['faqdesk_question']), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?> 
		</td>
	</tr>
<?php
  if ($messageStack->size('faqdesk_reviews_write') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('faqdesk_reviews_write'); ?></td>
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
		<td>
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
$reviews = tep_db_query("select reviews_rating, reviews_id, customers_name, date_added, last_modified, reviews_read from " . TABLE_FAQDESK_REVIEWS . " where faqdesk_id = '" . (int)$HTTP_GET_VARS['faqdesk_id'] . "' and  approved = 1 order by reviews_id DESC");

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
		echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_INFO, $get_params . '&reviews_id=' . $reviews_values['reviews_id'], 'NONSSL') . '">' . $reviews_values['customers_name'] . '</a></td>' . "\n";
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
		<td class="main" colspan="5">
    
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=left', 'text' => '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, $get_params, 'NONSSL') . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?> 
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>

<!-- body_text_eof //-->

<?php
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:	FaqDesk
	version:		1.2.5
	date:			2003-09-01
	author:			Carsten aka moyashi
	web site:		www..com

*/
?>