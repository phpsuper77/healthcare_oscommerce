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

  if (isset($HTTP_GET_VARS['login']) && ($HTTP_GET_VARS['login'] == 'fail')) {
    $info_message = TEXT_LOGIN_ERROR;
  }

  if (isset($info_message)) {
?>

      <tr>
        <td class="smallText"><?php echo $info_message; ?></td>
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
        <td><?php echo tep_draw_form('login', tep_href_link(DIR_WS_HTTP_ADMIN_CATALOG . 'login.php' , 'action=process&type=affiliate', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1">
              <tr>
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_NEW_AFFILIATE);
  new contentBoxHeading($contents);
?>
                  </tr>
                  <tr>
                    <td class="main" valign="top" height="100%">
<?php
  $contents = array();
  $contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array(
                      'params' => 'height=100% class=main', 'text' =>TEXT_NEW_AFFILIATE . '<br><br>' . TEXT_NEW_AFFILIATE_INTRODUCTION
                      );
  $contents[] = array(
                      'params' => 'class=main', 'text' =>'<a  href="' . tep_href_link(FILENAME_AFFILIATE_TERMS, '', 'SSL') . '">' . TEXT_NEW_AFFILIATE_TERMS . '</a>'
                      );
                      
  $contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array('align' => 'right', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>');
  new contentBox($contents, 'height=100%');
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1">
              <tr>
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="2">
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_RETURNING_AFFILIATE);
  new contentBoxHeading($contents);
?>                    
                    </td>
                  </tr>
                  
                  <tr>
                    <td class="main" height="100%">
<?php
  $contents = array();
  $contents[] = array('params'=>'colspan=2', 'text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array(array('params' => 'height=100% class=main colspan=2 valign=top', 'text' =>TEXT_RETURNING_AFFILIATE));
  $contents[] = array(array('params' => 'class=main', 'text' =>TEXT_AFFILIATE_ID), 
                      array('text' => tep_draw_input_field('affiliate_username')));
  $contents[] = array(array('params' => 'class=main', 'text' =>TEXT_AFFILIATE_PASSWORD), 
                      array('text' => tep_draw_password_field('affiliate_password')));
  $contents[] = array('params'=>'colspan=2', 'text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array('params'=>'colspan=2', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_AFFILIATE_PASSWORD_FORGOTTEN . '</a>');
  $contents[] = array('params'=>'colspan=2', 'text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array('params'=>'colspan=2', 'align' => 'right', 'text' => tep_template_image_submit('button_login.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_LOGIN, 'class="transpng"'));
  new contentBox($contents, 'height=100%');
?> 
                    </td>
                  </tr>

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
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
        </table></form></td>
      </tr>
    </table>
