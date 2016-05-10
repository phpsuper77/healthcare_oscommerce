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
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('login') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('login'); ?></td>
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

  if ($cart->count_contents() > 0) {
?>
      <tr>
        <td class="smallText"><?php echo TEXT_VISITORS_CART; ?></td>
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
        <td><?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="50%" height="100%" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
              <tr>  
                <td>
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_NEW_CUSTOMER);
  new contentBoxHeading($contents);
?>
                </td>
              </tr>
              <tr>
                <td height="100%">
<?php
  $contents = array();
  $contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array(
                      'params' => 'height=100% class=main', 'text' =>TEXT_NEW_CUSTOMER . '<br><br>' . TEXT_NEW_CUSTOMER_INTRODUCTION
                      );
  $contents[] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array('align' => 'right', 'text' => '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>');
  new contentBox($contents, 'height=100%');
?>

                </td>
              </tr>
            </table>


            </td>
            <td width="50%" height="100%" valign="top">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
              <tr>  
                <td>
<?php
  $contents = array();
  $contents[] = array('text' => HEADING_RETURNING_CUSTOMER);
  new contentBoxHeading($contents);
?>
                </td>
              </tr>
              <tr>
              <td height="100%">
<?php
  $contents = array();
  $contents[] = array('params'=>'colspan=2', 'text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array(array('params' => 'height=100% class=main colspan=2 valign=top', 'text' =>TEXT_RETURNING_CUSTOMER));
  $contents[] = array(array('params' => 'class=main', 'text' =>ENTRY_EMAIL_ADDRESS), 
                      array('text' => tep_draw_input_field('email_address')));
  $contents[] = array(array('params' => 'class=main style="padding-top:5px;"', 'text' =>ENTRY_PASSWORD), 
                      array('params' => ' style="padding-top:5px;"', 'text' => tep_draw_password_field('password')));

  $contents[] = array('params'=>'colspan=2', 'text' => '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>');
  $contents[] = array('params'=>'colspan=2', 'text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
  $contents[] = array('params'=>'colspan=2', 'align' => 'right', 'text' => tep_template_image_submit('button_login.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_LOGIN, 'class="transpng"'));
  new contentBox($contents, 'height=100%');
?>            
               </td>
              </tr>
            </table></td>
          </tr>
        </table>
        </form>
<?php
    // ** GOOGLE CHECKOUT **
    // Checks if the Google Checkout payment module has been enabled and if so 
    // includes gcheckout.php to add the Checkout button to the page 
    if (defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS') && MODULE_PAYMENT_GOOGLECHECKOUT_STATUS == 'True') {
      include_once( DIR_FS_CATALOG . (substr(DIR_FS_CATALOG,-1)=='/'?'':'/'). 'googlecheckout/gcheckout.php');
    } 
    // ** END GOOGLE CHECKOUT **
?>
        </td>
      </tr>
<?php 
  //---PayPal WPP Modification START ---//
    tep_paypal_wpp_ep_button(FILENAME_SHOPPING_CART);
  //---PayPal WPP Modification END ---// 
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

    </table>

