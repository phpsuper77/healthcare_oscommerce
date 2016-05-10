<table border="0" width="100%" cellspacing="0" cellpadding="0">
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
  if (false){//if (CELLPADDING_SUB < 5){
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

  <!-- message -->
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php
  if ($messageStack->size('subscribers') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('subscribers'); ?></td>
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
switch ($act)
{
case "subscribed":
case "unsubscribed":
?>
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array(
      array('params' => 'align=right class="main"', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'));
      new buttonBox($info_box_contents);
      ?>
    </td>
  </tr>
  <?php
break;
default:
?>
  <tr>
    <td class="main">
      <?php
      ob_start();
      ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0" class="contentBox">
        <tr>
          <td width="100%" class="main_white"><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
        </tr>
      </table>
      <?php
      $buffer=ob_get_contents();
      ob_end_clean();
      $contentbox_contents = array();
      $contentbox_contents[]=array(array('text'=>$buffer,'params'=>'valign=top'));
      new contentBox($contentbox_contents,'height="100%"');
      ?>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td>
      <form name="subscribers_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_SUBSCRIBERS) . '"'; ?> onSubmit="return check_form();">
      <input type="hidden" name="action" value="sub">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <?php
            $info_box_contents = array();
            $info_box_contents[] = array('text' => CATEGORY_PERSONAL);
            new contentBoxHeading($info_box_contents, false, false);
            ?>
            <?php
            ob_start();
            ?>
            <table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_FIRST_NAME; ?></td>
                <td class="main">&nbsp;
                  <?php echo tep_draw_input_field('firstname', $firstname) . '&nbsp;<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT.'</span>';?>
                </td>
              </tr>
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_LAST_NAME; ?></td>
                <td class="main">&nbsp;
                  <?php echo tep_draw_input_field('lastname', $lastname) . '&nbsp;<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT.'</span>';?>
                </td>
              </tr>
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main">&nbsp;
                <?php    echo tep_draw_input_field('email_address', $email_address) . '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT.'</span>';
                ?></td>
              </tr>
            </table>
            <?php
            $buffer=ob_get_contents();
            ob_end_clean();
            $contentbox_contents = array();
            $contentbox_contents[]=array(array('text'=>$buffer,'params'=>'valign=top'));
            new contentBox($contentbox_contents,'height="100%"');
            ?>
          </td>
        </tr>
        <tr>
          <td>
          <?php
          $info_box_contents = array();
          $info_box_contents[] = array(
          array('params' => 'align=right class="main"', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')));
          new buttonBox($info_box_contents);
          ?>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td>
      <form name="subscribers_edit_unsub" method="post" <?php echo 'action="' . tep_href_link(FILENAME_SUBSCRIBERS) . '"'; ?> onSubmit="return check_unsub_form();">
      <input type="hidden" name="action" value="unsub">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <?php
            $info_box_contents = array();
            $info_box_contents[] = array('text' => CATEGORY_UNSUBSCRIBE);
            new contentBoxHeading($info_box_contents, false, false);
            ?>
            <?
            ob_start();
            ?>
            <table border="0" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main">&nbsp;<?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main">&nbsp;<?php echo tep_draw_input_field('unsub_email_address', $unsub_email_address) . '&nbsp;' ;?></td>
              </tr>
            </table>
            <?
            $buffer=ob_get_contents();
            ob_end_clean();
            $contentbox_contents = array();
            $contentbox_contents[]=array(array('text'=>$buffer,'params'=>'valign=top'));
            new contentBox($contentbox_contents,'height="100%"');
            ?>
          </td>
        </tr>
        <tr>
          <td>
          <?php
          $info_box_contents = array();
          $info_box_contents[] = array(
          array('params' => 'align=right class="main"', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')));
          new buttonBox($info_box_contents);
          ?>
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
<?php
}
?>
</table>