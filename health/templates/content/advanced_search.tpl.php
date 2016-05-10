    <?php echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);"') . tep_hide_session_id(); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
  $infobox_contents[] = array(array('text' =>HEADING_TITLE_1), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE_1, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>        
        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td></td>
      </tr>
<?php
  }
?> 
<?php
}else{
$header_text = HEADING_TITLE_1;
}
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('search') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('search'); ?></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td></td>
      </tr>
<?php
  }
?> 
<?php
  }
?>
      <tr>
        <td>
        
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_SEARCH_CRITERIA);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">
              <tr>
                <td WIDTH="100%" HEIGHT="1"></td>
              </tr>
              <tr>
                <td class="boxText"><?php echo tep_draw_input_field('keywords', '', 'style="width: 100%"'); ?></td>
              </tr>
              <tr>
                <td align="right" class="boxText"><?php echo tep_draw_checkbox_field('search_in_description', '1') . ' ' . TEXT_SEARCH_IN_DESCRIPTION; ?></td>
              </tr>
              <tr>
                <td width="100%" height="1"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td width="100%" height="1"></td>
      </tr>
<?php
  }
?> 
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_SEARCH_HELP) . '\')">' . TEXT_SEARCH_HELP_LINK . '</a>'; ?></td>
            <td class="smallText" align="right"><?php echo tep_template_image_submit('button_search.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_SEARCH, 'class="transpng"'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td width="100%" height="1"></td>
      </tr>
<?php
  }
?> 
      <tr>
        <td>        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">
              <tr>
                <td class="fieldKey"><?php echo ENTRY_CATEGORIES; ?></td>
                <td class="fieldValue"><?php echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES)))); ?></td>
              </tr>
              <tr>
                <td class="fieldKey">&nbsp;</td>
                <td class="smallText"><?php echo tep_draw_checkbox_field('inc_subcat', '1', true) . ' ' . ENTRY_INCLUDE_SUBCATEGORIES; ?></td>
              </tr>
              <tr>
                <td colspan="2" width="100%" height="10"></td>
              </tr>
              <tr>
                <td class="fieldKey"><?php echo ENTRY_MANUFACTURERS; ?></td>
                <td class="fieldValue"><?php echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS)))); ?></td>
              </tr>
              <tr>
                <td colspan="2" width="100%" height="10"></td>
              </tr>
              <tr>
                <td class="fieldKey"><?php echo ENTRY_PRICE_FROM; ?></td>
                <td class="fieldValue"><?php echo tep_draw_input_field('pfrom'); ?></td>
              </tr>
              <tr>
                <td class="fieldKey"><?php echo ENTRY_PRICE_TO; ?></td>
                <td class="fieldValue"><?php echo tep_draw_input_field('pto'); ?></td>
              </tr>
              <tr>
                <td colspan="2" width="100%" height="10"></td>
              </tr>
              <tr>
                <td class="fieldKey"><?php echo ENTRY_DATE_FROM; ?></td>
                <td class="fieldValue"><?php echo tep_draw_input_field('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?></td>
              </tr>
              <tr>
                <td class="fieldKey"><?php echo ENTRY_DATE_TO; ?></td>
                <td class="fieldValue"><?php echo tep_draw_input_field('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?></td>
              </tr>
<?php
if (PRODUCTS_PROPERTIES == 'True'){
  $properties_yes_no_array = array(array('id' => '', 'text' => OPTION_NONE), array('id' => 'true', 'text' => OPTION_TRUE), array('id' => 'false', 'text' => OPTION_FALSE));
  $properties_query = tep_db_query("select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES_DESCRIPTION . " prd, " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' and INSTR(pr.mode, 'search') and pr.properties_type in (0, 1, 2, 3, 4) order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");
  if (tep_db_num_rows($properties_query) > 0)
  {
    while ($properties_array = tep_db_fetch_array($properties_query)){
      
?>
    <tr>
      <td class="fieldKey" valign="top"><?php echo $properties_array['properties_name'] . ':'; ?></td>
      <td class="fieldValue">
<?php
switch ($properties_array['properties_type']){
  case '0': case '1':
    echo tep_draw_input_field($properties_array['properties_id'] , '');
    break;
  case '2':
    echo tep_draw_pull_down_menu($properties_array['properties_id'], $properties_yes_no_array);
    break;
  case '3':
    $properties_values = explode("\n", $properties_array['possible_values']);
    for ($i=0,$n=sizeof($properties_values);$i<$n;$i++){
      echo tep_draw_checkbox_field($properties_array['properties_id']. '[]', $properties_values[$i]) . '&nbsp;' . $properties_values[$i] . '<br>';
    }  
    break;
  case '4':
      $properties_values = explode("\n", $properties_array['possible_values']);
      for ($i=0,$n=sizeof($properties_values);$i<$n;$i++){
         echo tep_draw_checkbox_field($properties_array['properties_id'] . '[]', $properties_values[$i], false) . '&nbsp;' . $properties_values[$i] . '<br>';
      }
    break;
}
?>      
      </td>
    </tr>
<?php
    }
  }
}
?>              
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
    </table></form>
