<form name="faqdesk_reviews_write" method="post" action="<?php echo tep_href_link(FILENAME_FAQDESK_REVIEWS_WRITE, 'action=process&faqdesk_id=' . $HTTP_GET_VARS['faqdesk_id'], 'NONSSL'); ?>" onSubmit="return checkForm();">

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
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	</tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
	<tr>
		<td class="main" width="50%">
		<b>
<?php echo SUB_TITLE_PRODUCT; ?>
		</b>
<?php echo $product_info_values['faqdesk_question']; ?>
		</td>
                                    <td class="main" rowspan="3" align="center">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>
                                        <p align="center">
                                        <?php echo $insert_image; ?>
                                        </td>
                                      </tr>
                                    </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main"><b><?php echo SUB_TITLE_FROM; ?></b>&nbsp;<?php echo $customer_values['customers_firstname'] . ' ' . $customer_values['customers_lastname'];?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main" colspan="2"><?php echo SUB_TITLE_RATING; ?>
                                    <?php echo TEXT_BAD; ?>
				<input type="radio" name="rating" value="1">
				<input type="radio" name="rating" value="2">
				<input type="radio" name="rating" value="3">
				<input type="radio" name="rating" value="4">
				<input type="radio" name="rating" value="5">
				<?php echo TEXT_GOOD; ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="main" colspan="2"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><?php echo tep_draw_textarea_field('review_text', 'soft', 60, 15);?></td>
                                  </tr>
                                  <tr>
                                    <td class="smallText" colspan="2"><?php echo TEXT_NO_HTML; ?></td>
                                  </tr>
                                  </td>
                                  </tr>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
</table>
      <tr>
        <td colspan="5"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="5">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=left', 'text' => '<a href="' . tep_href_link(FILENAME_FAQDESK_INFO, $get_params_back, 'NONSSL') . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right width=100%', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>        
        
        </td>
      </tr>
	<input type="hidden" name="get_params" value="<?php echo $get_params; ?>">
	</form>
</table>
<!-- body_text_eof //-->