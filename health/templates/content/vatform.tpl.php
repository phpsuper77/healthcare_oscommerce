<link rel="stylesheet" type="text/css" href="/templates/Original/css/vatform.css">

<?php
define('FRM_ELEMENTS_COMMON_ATTR','style="width:260px;"');
?>
<?php echo tep_draw_form('vatform', tep_href_link(FILENAME_VATFORM, tep_get_all_get_params(array('action')).'action=send')); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<? if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') : ?>
	<input name="subaction" type="hidden" value="" />	
	<?php
	  	$infobox_contents = array();
	  	$infobox_contents[] = array(array(
	  	'text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', 
		HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
		//new contentPageHeading($infobox_contents);
	?>
<? else : ?>
	<? $header_text = HEADING_TITLE; ?>
<? endif; ?>

<? if ($messageStack->size('vatform') > 0) : ?>
	<?=$messageStack->output('vatform'); ?>
<? endif; ?>
<?  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'success')) { ?>
	<?php HEADING_TITLE.TEXT_SUCCESS; ?>
      <tr>
        <td>
<?php
  $direct_to = FILENAME_DEFAULT;
  $direct_ch = 'NONSSL';
  if ( isset($HTTP_GET_VARS['iorigin']) ) {
    if ( $HTTP_GET_VARS['iorigin']==1 ) {
      $direct_to = FILENAME_SHOPPING_CART;
//      $direct_to = FILENAME_CHECKOUT_PAYMENT;
//      $direct_ch = 'SSL';
    }elseif( $HTTP_GET_VARS['iorigin']==2 ) {
      $direct_to = FILENAME_CHECKOUT_CONFIRMATION;
      $direct_ch = 'SSL';
    }elseif( $HTTP_GET_VARS['iorigin']==3 ) {
      $direct_to = FILENAME_CHECKOUT_PAYMENT;
      $direct_ch = 'SSL';
    }
  }
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link($direct_to,'subaction='.$_GET['action'],$direct_ch) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td class="main">
			<div class="page-heading">
				<h2 class="float-left">VAT Declaration From</h2>
				<input type="button" value="Skip" class="button-grey button-skip float-left" />
			</div>
			<div class="clear"></div>
			
			<p class="text">
				You told us one or more of the products in your basket are for personal use and for a long term
				medical condition.  This entitles you to <strong>VAT Exemption</strong>.<br />
				<br>			
				Simply complete the self declaration form below to confirm you do not need to pay VAT		
			</p>
			
			<div class="table-wrapper">
				<table cellpadding="0" cellspacing="0" border="0" class="table">
				<tr>
					<td width="30%"><?php echo ENTRY_NAME; ?><span class="swatch-red"><strong>*</strong></span></td>
					<td width="70%"><?php echo tep_draw_input_field('name','',FRM_ELEMENTS_COMMON_ATTR)?></td>
				</tr>
				<tr>
					<td><?php echo ENTRY_ADDRESS; ?><span class="swatch-red"><strong>*</strong></span></td>
					<td><?php echo tep_draw_textarea_field('address', 'soft', 30, 5,'', FRM_ELEMENTS_COMMON_ATTR)?></td>
				</tr>
				<tr>
					<td><?php echo ENTRY_EMAIL; ?><span class="swatch-red"><strong>*</strong></span></td>
					<td><?php echo tep_draw_input_field('email','',FRM_ELEMENTS_COMMON_ATTR)?></td>
				</tr>
				<tr>
					<td><?php echo ENTRY_PHONE; ?><span class="swatch-red"><strong>*</strong></span></td>
					<td><?php echo tep_draw_input_field('phone','',FRM_ELEMENTS_COMMON_ATTR)?></td>
				</tr>
				<tr>
					<td class="seperator" colspan="2"><span></span></td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
						<tr>
							<td valign="top"><?php echo tep_draw_checkbox_field('tick1', '', true); ?></td>
							<td valign="top">
								<b><?=ENTRY_TICK_1;?></b><span class="swatch-red"><strong>*</strong></span>
								<?php echo tep_draw_textarea_field('describe_condition', 'soft', 30, 2,'','style="width:95% !important;"')?>							
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
						<tr>
							<td valign="top"><?=tep_draw_checkbox_field('tick2', '', true); ?></td>
							<td valign="top">
								<b><?=sprintf(ENTRY_TICK_2_S, preg_replace('/\s+/ims', ' ',STORE_NAME_ADDRESS) );?></b>
								<span class="swatch-red"><strong>*</strong></span>
							</td>
						</tr>
						</table>
					</td>
				</tr>			
				<tr>
					<td colspan="2">
						<table width="100%">
						<tr>
							<td valign="top"><?php echo tep_draw_checkbox_field('tick3', '', true); ?></td>
							<td valign="top">
								<b><?php echo ENTRY_TICK_3;?></b>
								<span class="swatch-red"><strong>*</strong></span>
							</td>
						</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td><?php echo ENTRY_SIGNED; ?> <span class="swatch-red"><strong>*</strong></span></td>
					<td><?php echo tep_draw_input_field('signed','',FRM_ELEMENTS_COMMON_ATTR)?></td>
				</tr>
				<tr>
					<td class="seperator" colspan="2"><span></span></td>
				</tr>
				<tr>
					<td colspan="2">
						<p class="text">
							If the individual is a child or unable to sign the declaration on account of their
							disability/illness, then a third party may sign above and then complete their details below.					
						</p>
					</td>
				</tr>
				<tr>
					<td><?php echo ENTRY_SECONDARY_NAME; ?></td>
					<td valign="top"><?php echo tep_draw_input_field('sec_name','',FRM_ELEMENTS_COMMON_ATTR); ?></td>
				</tr>
				<tr>
					<td valign="top"><?php echo ENTRY_SECONDARY_ADDRESS; ?></td>
					<td valign="top"><?php echo tep_draw_textarea_field('sec_address', 'soft', 30, 5,'',FRM_ELEMENTS_COMMON_ATTR); ?></td>
				</tr>
				<tr>
					<td><?php echo ENTRY_SECONDARY_RELATIONSHIP; ?></td>
					<td><?php echo tep_draw_input_field('sec_relationship','',FRM_ELEMENTS_COMMON_ATTR); ?></td>
				</tr>			
				</table>
			</div>
		
		
		</td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>

        </td>
      </tr>
<?php
  }
?>
    </table>
	
	<div class="buttons">
		<input type="button" value="Skip" class="button-red button-skip" />
		<input type="submit" value="Continue" class="button-green submit" />
	</div>
	
	</form>
	
<script>

	$(document).ready(function() {
		$("input.button-skip").click(function() {
			$("input[name='subaction']").val("skip");
			$("form[name='vatform']").submit();
		});
		
	});

</script>


