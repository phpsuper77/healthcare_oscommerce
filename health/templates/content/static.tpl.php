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
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td class="main"><?php 
            if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/' . $content . '.tpl.php')){
              include(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/' . $content . '.tpl.php');
            }else{
              if ($content == CONTENT_WISHLIST_HELP){
                echo TEXT_INFORMATION_WHISHLIST;
              }else{
                if ($content == CONTENT_CONDITIONS && is_file(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php')){
                  include(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php');
                }else{
                  echo TEXT_INFORMATION;
                }
              }
            }
            ?></td>
          </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  $continue_shopping_url = get_affiliate_continue_shopping_url();
  if (tep_not_null($continue_shopping_url))
  {
    $continue_shopping_url = str_replace('{SID}', tep_session_name() . '=' . tep_session_id(), $continue_shopping_url);
    $but = '<a href="' . $continue_shopping_url . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>';
  }
  else
  {
    if ($content != CONTENT_AFFILIATE_TERMS){
      $but = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>';
    }else{
      $but = '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>';
    }  
    
  }

  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="right" class="main" width=100%', 'text' => $but),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
    </table>

