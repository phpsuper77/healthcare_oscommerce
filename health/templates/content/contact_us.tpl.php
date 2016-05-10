
    <?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send')); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
<?php
  if ($messageStack->size('contact') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('contact'); ?></td>
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

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) {
?>
      <tr>
        <td class="main" align="center"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?></td>
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
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td>
                  <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
            <tr>
              <td><table border="0" width="100%" cellspacing="2" cellpadding="4"  class="contentBoxContents">
              <tr>
                <td class="main"><?php echo ENTRY_NAME; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('name'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_EMAIL; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo tep_draw_input_field('email'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_ENQUIRY; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_textarea_field('enquiry', 'soft', 50, 15); ?></td>
              </tr>
			<?php if (ANTI_SPAM_ROBOT == 'True') { ?>
			              <tr>
			                <td><table border="0" cellspacing="2" cellpadding="2">
			                  <tr>
			                    <td><?php tep_session_unregister('random'); unset($random); unset($HTTP_SESSION_VARS['random']); ?><img src="<?php echo tep_href_link('robot.php'); ?>" border="0"></td>
			                  </tr>
			                  <tr>
			                    <td><?php echo ENTRY_ROBOT; ?></td>
			          		  </tr>
			                  <tr>
			                    <td align="left"><?php echo tep_draw_input_field('robot', '', 'maxlength="10" style="width:150px"'); ?>&nbsp;</td>
			      			  </tr>
			                </table></td>
			              </tr>
			<?php } ?>
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
  }
?>
    </table></form>
	
<br />	
<div class="contact">

	<!-- Start: Address -->
	<div class="address float-left">
		<span class="swatch-matt-blue"><b>Healthcare4all Ltd</b></span><br />
		<br />
		1 Osprey Close<br />
		Alwoodley<br />
		Leeds<br />
		LS17 8XE<br />
		<br />
		Tel: 0113 350 5432<br />
		Fax: 0113 88 00 765<br />
		<br />
		Registered Address Only - No collection of products available
	</div>
	<!-- End: Address -->	
	
	<div id="mapCanvas" style="height:300px; width:300px;" class="float-right">
	</div>				
	
</div>	
<? include(DIR_WS_TEMPLATES.TEMPLATE_NAME."/java/contact.phtml"); ?>
<script type="text/javascript" src="<?=DIR_WS_TEMPLATES.TEMPLATE_NAME?>/java/contact.phtml"></script>


