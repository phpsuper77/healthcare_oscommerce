<!-- newsdesk //-->

	<tr>
		<td>

<?php
	$heading = array();
	$contents = array();

$heading[] = array(
	'text'  => BOX_HEADING_NEWSDESK,
	'link'  => tep_href_link(tep_selected_file('newsdesk.php', FILENAME_NEWSDESK), 'selected_box=newsdesk')
);

if ($selected_box == 'newsdesk' || $menu_dhtml == true) {
	$contents[] = array('text'  => 
//Admin begin
tep_admin_files_boxes(FILENAME_NEWSDESK, BOX_NEWSDESK) .
tep_admin_files_boxes(FILENAME_NEWSDESK_REVIEWS, BOX_NEWSDESK_REVIEWS)
	);
//Admin end
}


	$box = new box;
	echo $box->menuBox($heading, $contents);
?>

		</td>
	</tr>

<!-- newsdesk_eof //-->