    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
    <tr>
      <td>
<?php
$info_box_contents = array();
if (MAX_MANUFACTURERS_LIST < 2) {
	$cat_choose = array(array('id' => '', 'text' => FAQ_BOX_CATEGORIES_CHOOSE));
} else {
	$cat_choose = '';
}
$categories_array = faqdesk_get_categories($cat_choose);
$hide = tep_hide_session_id();
$info_box_contents[] = array(
                      array(
		'form' => '<form action="' . tep_href_link(FILENAME_FAQDESK_INDEX) . '" method="get">',
		'align' => 'left',
		'text'  => faqdesk_show_draw_pull_down_menu('faqPath', $categories_array,'','onChange="this.form.submit();" size="' . ((sizeof($categories_array) < MAX_MANUFACTURERS_LIST) ? sizeof($categories_array) : MAX_MANUFACTURERS_LIST) . '" style="width:' . BOX_WIDTH . '"')),
                      array(	'form'  => '<form name="quick_find_faq" method="get" action="' . tep_href_link(FILENAME_FAQDESK_SEARCH_RESULT, '', 'NONSSL', false) . '">',
	'align' => 'right',
	'text'  => $hide . '<input type="text" name="keywords" size="20" maxlength="30" value="' 
. htmlspecialchars(StripSlashes(@$HTTP_GET_VARS["keywords"])) . '" style="width: ' . (BOX_WIDTH-30) . 'px">&nbsp;' . tep_template_image_submit('button_quick_find.' . BUTTON_IMAGE_TYPE, BOX_HEADING_SEARCH, 'class="transpng"'))	);

new contentBox($info_box_contents);
?>
		</td>
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
    <td>
<?php
$category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_heading_title, cd.categories_description from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.categories_id = '" . (int)$current_faq_id . "' and cd.categories_id =  c.categories_id and cd.language_id = '" . (int)$languages_id . "'");
$category = tep_db_fetch_array($category_query);
if ( ALLOW_CATEGORY_DESCRIPTIONS == 'true' ) {
  if ($category['categories_heading_title']){
	  $header_title = $category['categories_heading_title'];
  }else{
    $header_title = $category['categories_name'];
  }
} else {
	$header_title = HEADING_TITLE;
}
$header_image = '';
if ($category['categories_image'] != '') {
  $header_image = tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>$header_title), array('params'=> 'align=right', 'text' => $header_image));
  new contentPageHeading($infobox_contents);

?>
</td>
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

<?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
	<tr>
		<td class="main">
		<?php echo $category['categories_description']; ?>
		</td>
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

<?php } ?>

<?php
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . (int)$current_faq_id  . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

if (tep_db_num_rows($categories_query) > 0){
?>
<tr>
  <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TABLE_HEADING_SUBCATEGORY);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
	<tr>
<?php
$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . (int)$current_faq_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");


if (($categories['categories_image'] != '')){
  echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}

$rows = 0;
while ($categories = tep_db_fetch_array($categories_query)) {
	$rows++;
	$faqPath_new = faqdesk_get_path($categories['categories_id']);
	$width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
	echo '
		<td align="center" class="smallText" style="width: ' . $width . '" valign="top"><a href="' 
		. tep_href_link(FILENAME_FAQDESK_INDEX, $faqPath_new, 'NONSSL') . '">';
if (($categories['categories_image'] != '')) {
  echo tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
}
	echo '<br>' . $categories['categories_name'] . '</a></td>' . "\n";
	if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories_query))) {
		echo '</tr>' . "\n";
		echo '<tr>' . "\n";
	}
}
?>
</tr>
</table></td>
</tr></table>
  </td>
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

<?php
if ($category_depth == 'products') {
?>
<tr>
  <td>
<?php
  include(FILENAME_FAQDESK_SHOW);
  ?>
  </td>
</tr>

<?php
}
?>
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
  <td>
<?php
include(DIR_WS_MODULES . FILENAME_FAQDESK_STICKY);
  ?>
  </td>
</tr>

    </table>
