    <?php echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
  $infobox_contents[] = array(array('text' =>$products_name), array('params'=> 'align=right', 'text' => $products_price));
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
}else{
$header_text =  $products_name . '<br>' . $products_price;
}
?>

<?php
  if ($messageStack->size('review') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('review'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php //echo '<b>' . SUB_TITLE_FROM . '</b> ' . tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); 
                echo '<b>' . (tep_session_is_registered('customer_id')?SUB_TITLE_FROM:REVIEW_YOUR_NAME . ':') . '</b> ' . tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']);
								
								if (!tep_session_is_registered('customer_id'))
								{
								 echo tep_draw_input_field('customer', $HTTP_POST_VARS['customer'], 'size="25"');
								}
								
								?></td>
              </tr>
              <tr>
                <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => SUB_TITLE_REVIEW);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">
                      <tr>
                        <td class="main"><?php echo tep_draw_textarea_field('review', 'soft', 60, 15); ?></td>
                      </tr>
                      <tr>
                        <td class="smallText" align="right"><?php echo TEXT_NO_HTML; ?></td>
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
                      <tr>
                        <td class="main"><?php echo '<b>' . SUB_TITLE_RATING . '</b> ' . TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
<?php
  if (tep_not_null($product_info['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle"')  . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle"')  . '</a>'; ?>
</noscript>
<?php
  }

  echo '<div style="padding:5px 0px"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_template_image_button('button_in_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_IN_CART, 'class="transpng"') . '</a></div>';
  echo '<div style="padding:5px 0px"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$product_info['products_id']) . '">' . tep_template_image_button('button_product_page.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_PRODUCT_PAGE, 'class="transpng"') . '</a></div>';

?>
                </td>
              </tr>
            </table>
          </td>
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
                               array('params' => 'align=left width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id', 'action'))) . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right width=100%', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>

                </td>
              </tr>

    </table></form>

