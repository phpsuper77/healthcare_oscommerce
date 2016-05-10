<?php echo tep_draw_form('submit_link', tep_href_link(FILENAME_LINKS_SUBMIT, '', 'SSL'), 'post', 'onSubmit="return check_form(submit_link);"') . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
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

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td class="smallText"><br><?php echo TEXT_MAIN; ?></td>
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
  if ($messageStack->size('submit_link') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('submit_link'); ?></td>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
           <td class="inputRequirement" align="right"><?php echo FORM_REQUIRED_INFORMATION; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
        
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_WEBSITE);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td class="main" width="25%"><?php echo ENTRY_LINKS_TITLE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_title') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_TITLE_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_TITLE_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_LINKS_URL; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_url', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_URL_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_URL_TEXT . '</span>': ''); ?></td>
              </tr>
<?php
  //link category drop-down list
  $categories_array = array();
  $categories_query = tep_db_query("select lcd.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lcd.language_id = '" . (int)$languages_id . "'order by lcd.link_categories_name");
  while ($categories_values = tep_db_fetch_array($categories_query)) {
    $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);
  }

  if (isset($HTTP_GET_VARS['lPath'])) {
    $current_categories_id = $HTTP_GET_VARS['lPath'];

    $current_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id ='" . (int)$current_categories_id . "' and language_id ='" . (int)$languages_id . "'");
    if ($categories = tep_db_fetch_array($current_categories_query)) {
      $default_category = $categories['link_categories_name'];
    } else {
      $default_category = '';
    }
  }
?>
              <tr>
                <td class="main"><?php echo ENTRY_LINKS_CATEGORY; ?></td>
                <td class="main">
<?php
    echo tep_draw_pull_down_menu('links_category', $categories_array, $default_category);

    if (tep_not_null(ENTRY_LINKS_CATEGORY_TEXT)) echo '&nbsp;<span class="inputRequirement">' . ENTRY_LINKS_CATEGORY_TEXT;
?>
                </td>
              </tr>
              <tr>
                <td class="main" valign="top"><?php echo ENTRY_LINKS_DESCRIPTION; ?></td>
                <td class="main"><?php echo   tep_draw_textarea_field('links_description', 'wrap', 15, 5, '', 'style="width:250px;"') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_DESCRIPTION_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_DESCRIPTION_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_LINKS_IMAGE; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_image', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_IMAGE_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_IMAGE_TEXT . '</span>': ''); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_CONTACT);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td class="main" width="25%"><?php echo ENTRY_LINKS_CONTACT_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_contact_name') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_CONTACT_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_CONTACT_NAME_TEXT . '</span>': ''); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_contact_email') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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
  $info_box_contents = array();
  $info_box_contents[] = array('text' => CATEGORY_RECIPROCAL);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">                <tr>
                <td class="main" width="25%"><?php echo ENTRY_LINKS_RECIPROCAL_URL; ?></td>
                <td class="main"><?php echo tep_draw_input_field('links_reciprocal_url', 'http://') . '&nbsp;' . (tep_not_null(ENTRY_LINKS_RECIPROCAL_URL_TEXT) ? '<span class="inputRequirement">' . ENTRY_LINKS_RECIPROCAL_URL_TEXT . '</span>': ''); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right width=100%', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>

        </td>
      </tr>
<?php
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>      
    </table></form>