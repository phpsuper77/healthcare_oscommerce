    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td width="100%">
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

<?php
  if ($HTTP_GET_VARS['action'] == 'process') {
?>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, '0', '0', 'align="left"') . TEXT_SUCCESS; ?><br><br><?php echo 'gv '.$id1; ?></td>
      </tr>
      <tr>
        <td align="right"><br><a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><?php echo tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"'); ?></a></td>
      </tr>
<?php
  }  
  if ($HTTP_GET_VARS['action'] == 'send' && !$error) {
    // validate entries
      $gv_amount = (double) $gv_amount;
      $gv_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $gv_result = tep_db_fetch_array($gv_query);
      $send_name = $gv_result['customers_firstname'] . ' ' . $gv_result['customers_lastname'];
?>
      <tr>
        <td><form action="<?php echo tep_href_link(FILENAME_GV_SEND, 'action=process', 'NONSSL'); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo sprintf(MAIN_MESSAGE, $currencies->format($HTTP_POST_VARS['amount']), $HTTP_POST_VARS['to_name'], $HTTP_POST_VARS['email'], $HTTP_POST_VARS['to_name'], $currencies->format($HTTP_POST_VARS['amount']), $send_name); ?></td>
          </tr>
<?php
      if ($HTTP_POST_VARS['message']) {
?>
           <tr>
            <td class="main"><?php echo sprintf(PERSONAL_MESSAGE, $gv_result['customers_firstname']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo stripslashes($HTTP_POST_VARS['message']); ?></td>
          </tr>
<?php
      }

      echo tep_draw_hidden_field('send_name', $send_name) . tep_draw_hidden_field('to_name', stripslashes($HTTP_POST_VARS['to_name'])) . tep_draw_hidden_field('email', $HTTP_POST_VARS['email']) . tep_draw_hidden_field('amount', $gv_amount) . tep_draw_hidden_field('message', stripslashes($HTTP_POST_VARS['message']));
?>
          <tr>
            <td class="main"><?php echo tep_template_image_submit('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'name=back class="classpng"'); ?></td>
            <td align="right"><br><?php echo tep_template_image_submit('button_send.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"'); ?></td>
          </tr>
        </table></form></td>
      </tr>
<?php
  } elseif ($HTTP_GET_VARS['action']=='' || $error) {
?>
      <tr>
        <td class="main"><?php echo HEADING_TEXT; ?></td>
      </tr>
      <tr>
        <td><form action="<?php echo tep_href_link(FILENAME_GV_SEND, 'action=send', 'NONSSL'); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_NAME; ?><br><?php echo tep_draw_input_field('to_name', stripslashes($HTTP_POST_VARS['to_name']));?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL; ?><br><?php echo tep_draw_input_field('email', $HTTP_POST_VARS['email']); if ($error) echo $error_email; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_AMOUNT; ?><br><?php echo tep_draw_input_field('amount', $HTTP_POST_VARS['amount'], '', '', false); if ($error) echo $error_amount; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_MESSAGE; ?><br><?php echo tep_draw_textarea_field('message', 'soft', 50, 15, stripslashes($HTTP_POST_VARS['message'])); ?></td>
          </tr>
        </table>
<?php
    $back = sizeof($navigation->path)-2;
?>        
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=left width=100% class=main', 'text' => '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'class="main" align="right"', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>             

        
        </form></td>
      </tr>
<?php
  }
?>
    </table></td>
