
<table border="0" width="100%" cellspacing="3" cellpadding="0">
	<tr>

<?php
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_FAQDESK_SEARCH_RESULTS, 'p.faqdesk_id');

if (($listing_split->number_of_pages > 1) && (FAQDESK_PREV_NEXT_BAR_LOCATION == '1' || FAQDESK_PREV_NEXT_BAR_LOCATION == '3')) {
?>

	<tr>
		<td>
		
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_FAQDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
<?php if (CELLPADDING_SUB < 5){ ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
<?php } ?>
</table>		

		</td>
	</tr>


<?php
}
?>

	<tr>
		<td>

<?php
$list_box_contents = array();
//$list_box_contents[] = array('params' => 'class="productListing-heading"');
$cur_row = sizeof($list_box_contents) - 1;

$cl_size = sizeof($column_list);
/*
for ($col=0; $col<$cl_size; $col++) {
	switch ($column_list[$col]) {
	case 'FAQDESK_DATE_AVAILABLE':
		$lc_text = TABLE_HEADING_DATE_AVAILABLE;
		$lc_align = 'left';
		break;
	case 'FAQDESK_SHORT_ANSWER':
		$lc_text = TABLE_HEADING_ARTICLE_SHORTTEXT;
		$lc_align = 'left';
		break;
	case 'FAQDESK_LONG_ANSWER':
		$lc_text = TABLE_HEADING_ARTICLE_DESCRIPTION;
		$lc_align = 'left';
		break;
	case 'FAQDESK_QUESTION':
		$lc_text = TABLE_HEADING_ARTICLE_NAME;
		$lc_align = 'left';
		break;
}

if ($column_list[$col] != 'FAQDESK_ARTICLE_URL' && $column_list[$col] )
	$lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
	$list_box_contents[$cur_row][] = array(
		'align' => $lc_align,
		'params' => 'class="productListing-heading"',
		'text'  => "&nbsp;" . $lc_text . "&nbsp;"
		);
}
*/
if ($listing_split->number_of_rows > 0) {
	$number_of_faqs = '0';
	$listing = tep_db_query($listing_split->sql_query);
	while ($listing_values = tep_db_fetch_array($listing)) {
		$number_of_faqs++;

		if ( ($number_of_faqs/2) == floor($number_of_faqs/2) ) {
			$list_box_contents[] = array('params' => 'class="productListing-even"');
		} else {
			$list_box_contents[] = array('params' => 'class="productListing-odd"');
		}

		$cur_row = sizeof($list_box_contents) - 1;
		$cl_size = sizeof($column_list);
  	$lc_align = '';
	  $lc_text = '';
		for ($col=0; $col<$cl_size; $col++) {
/*
			switch ($column_list[$col]) {
		case 'FAQDESK_DATE_AVAILABLE':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_date_added'] . '</a>&nbsp;';
			break;
		case 'FAQDESK_LONG_ANSWER':
			$lc_text = '&nbsp;' . $listing_values['faqdesk_answer_long'] . '&nbsp;';
			break;
		case 'FAQDESK_SHORT_ANSWER':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_answer_short'] . '</a>&nbsp;';
			break;
		case 'FAQDESK_QUESTION':
			$lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '">' . $listing_values['faqdesk_question'] . '</a>&nbsp;';
			break;
		}
*/
			switch ($column_list[$col]) {
		case 'FAQDESK_DATE_AVAILABLE':
			$lc_text .= '<div class="smallText">' . $listing_values['faqdesk_date_added'] . '</div>';
			break;
		case 'FAQDESK_LONG_ANSWER':
			$lc_text .= '<div>' . $listing_values['faqdesk_answer_long'] . '</div>';
			break;
		case 'FAQDESK_SHORT_ANSWER':
			$lc_text .= '<div>' . $listing_values['faqdesk_answer_short'] . '</div>';
			break;
		case 'FAQDESK_QUESTION':
		  if ( strlen($listing_values['faqdesk_answer_long'])>0 ) {
			  $lc_text .= '<div><a href="' . tep_href_link(FILENAME_FAQDESK_INFO, ($faqPath ? 'faqPath=' . $faqPath . '&' : '') 
			. 'faqdesk_id=' . $listing_values['faqdesk_id']) . '"><span class="bold">' . $listing_values['faqdesk_question'] . '</span></a></div>';
      }else{
			  $lc_text .= '<div class="bold">' . $listing_values['faqdesk_question'] . '</div>';
			}
			break;
		}
		}
			$list_box_contents[$cur_row][] = array(
				'align' => $lc_align,
				'params' => 'class="productListing-data"',
				'text'  => $lc_text
				);

	}

	new tableBox($list_box_contents, true);

	echo '</td>' . "\n";
	echo '</tr>' . "\n";
	} else {
?>
      <tr>
        <td><?php new contentBox(array(array('text' => TEXT_NO_ARTICLES))); ?></td>
      </tr>

<?php
}


if (($listing_split->number_of_pages > 1) && (FAQDESK_PREV_NEXT_BAR_LOCATION == '2' || FAQDESK_PREV_NEXT_BAR_LOCATION == '3')) {
?>

	<tr>
		<td>
		
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_FAQDESK_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>

</table>		

		</td>
	</tr>


<?php
}
?>

</table>
