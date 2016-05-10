<?php
/*
  $Id: password_forgotten.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  function randomize() {
    $salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
    srand((double)microtime()*1000000);
    $i = 0;
    while ($i <= 7) {
      $num = rand() % 33;
          $tmp = substr($salt, $num, 1);
          $pass = $pass . $tmp;
          $i++;
      }
      return $pass;
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $log_times = $HTTP_POST_VARS['log_times']+1;
    if ($log_times >= 4) {
      tep_session_register('password_forgotten');
    }
    if (isset($HTTP_GET_VARS['type']) && ($HTTP_GET_VARS['type'] == 'affiliate')){
      $check_affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($email_address) . "' and affiliate_isactive = 1");
      if (!tep_db_num_rows($check_affiliate_query)){
        $HTTP_GET_VARS['login'] = 'fail';
      }else{
        $check_affiliate = tep_db_fetch_array($check_affiliate_query);
        if ($check_affiliate['affiliate_firstname'] != $firstname){
          $HTTP_GET_VARS['login'] = 'fail';
        }else{
          $HTTP_GET_VARS['login'] = 'success';
          $makePassword = randomize();
          tep_mail($check_affiliate['affiliate_firstname'] . ' ' . $check_affiliate['affiliate_lastname'], $check_affiliate['affiliate_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(AFFILIATE_EMAIL_TEXT, $check_affiliate['affiliate_firstname'], tep_get_clickable_link(HTTP_SERVER . DIR_WS_ADMIN), $check_affiliate['affiliate_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_password = '" . tep_encrypt_password($makePassword) . "' where affiliate_id = '" . $check_affiliate['affiliate_id'] . "'");
        }
      }
    }elseif (isset($HTTP_GET_VARS['type']) && ($HTTP_GET_VARS['type'] == 'vendor')){
       $check_vendor_query = tep_db_query("select * from " . TABLE_VENDOR . " where vendor_email_address = '" . tep_db_input($email_address) . "' and vendor_status = 1");
      if (!tep_db_num_rows($check_vendor_query)){
        $HTTP_GET_VARS['login'] = 'fail';
      }else{
        $check_vendor = tep_db_fetch_array($check_vendor_query);
        if ($check_vendor['vendor_firstname'] != $firstname){
          $HTTP_GET_VARS['login'] = 'fail';
        }else{
          $HTTP_GET_VARS['login'] = 'success';
          $makePassword = randomize();
          tep_mail($check_vendor['vendor_firstname'] . ' ' . $check_vendor['vendor_lastname'], $check_vendor['vendor_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(VENDOR_EMAIL_TEXT, $check_vendor['vendor_firstname'], tep_get_clickable_link(HTTP_SERVER . DIR_WS_ADMIN), $check_vendor['vendor_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          tep_db_query("update " . TABLE_VENDOR . " set vendor_password = '" . tep_encrypt_password($makePassword) . "' where vendor_id = '" . $check_vendor['vendor_id'] . "'");
        }
      }
    }else{
  // Check if email exists
      $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else {
        $check_admin = tep_db_fetch_array($check_admin_query);
        if ($check_admin['check_firstname'] != $firstname) {
          $HTTP_GET_VARS['login'] = 'fail';
        } else {
          $HTTP_GET_VARS['login'] = 'success';

          $makePassword = randomize();
  
          tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], tep_get_clickable_link(HTTP_SERVER . DIR_WS_ADMIN), $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
        }
      }
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
  <table cellspacing=0 cellpadding=0 width=100% height=100% border=0>
    <tr>
      <td>
        <table cellspacing=0 cellpadding=0 border=0 align="center">
        <!-- Header -->    
          <tr valign="middle">
            <td align="center">
              <table cellspacing=0 cellpadding=0 width=772 border=0>
<td width=249  background="<?=DIR_WS_IMAGES?>page_top_right.jpg"><?=tep_image(DIR_WS_IMAGES."page_top_right.jpg","","1","100%")?></td>
                  <td width="227" background="<?=DIR_WS_IMAGES?>top_pic_bg.gif" align="center"><?=tep_image(DIR_WS_IMAGES."logo.gif","","227","108")?></td>
                  <td  width=249  background="<?=DIR_WS_IMAGES?>page_top_right.jpg"><?=tep_image(DIR_WS_IMAGES."page_top_right.jpg","","1","108")?></td>
              </table>
              <table cellspacing=0 cellpadding=0 width=772 height="33" border=0>
                <tr>
                  <td background="<?=DIR_WS_IMAGES?>nav_bg.jpg" height="33" align="center" class=headerbarcontent>
                    <a class=headerlink href="http://www.holbi.co.uk"><?=HEADER_TITLE_SUPPORT_SITE?></a>&nbsp;|&nbsp;
                    <a class=headerlink href="<?=tep_catalog_href_link()?>"><?=HEADER_TITLE_ONLINE_CATALOG?></a>&nbsp;|&nbsp;
                    <a class=headerlink href="<?=tep_href_link(FILENAME_LOGOFF, '', 'NONSSL')?>"><?=HEADER_TITLE_LOGOFF?></a>&nbsp;&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
        <!-- Header_oef -->    
        <!-- Content -->  
          <tr>
            <td align="center">
              <table cellspacing=0 cellpadding=0 width=772 height="274" border=0>
                <tr>
                  <td width="100%" height="274" align="center">
                    <table cellspacing=0 cellpadding=0 width="343" height="198" border=0 background="<?=DIR_WS_IMAGES?>login_bg.jpg">
                      <tr valign="middle">
                        <td align="center">
                          <table cellspacing=0 cellpadding=3 border=0>
                          <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process' . (isset($HTTP_GET_VARS['type']) && (isset($HTTP_GET_VARS['type']))?'&type=' . $HTTP_GET_VARS['type']:'')); ?>

                        <?php
  if ($HTTP_GET_VARS['login'] == 'success') {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif ($HTTP_GET_VARS['login'] == 'fail') {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
  if (tep_session_is_registered('password_forgotten')) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo TEXT_FORGOTTEN_FAIL; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(array('login', 'action')) , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                                    </tr>
<?php
  } elseif (isset($success_message)) {
?>
                                    <tr>
                                      <td class="smallText"><?php echo $success_message; ?></td>
                                    </tr>
                                    <tr>
                                      <td align="center" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(array('login', 'action')) , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                                    </tr>
<?php
  } else {
    if (isset($info_message)) {
?>
                                    <tr>
                                      <td colspan="2" class="smallText" align="center"><?php echo $info_message; ?><?php echo tep_draw_hidden_field('log_times', $log_times); ?></td>
                                    </tr>
<?php
    } else {
?>
                                    <tr>
                                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?><?php echo tep_draw_hidden_field('log_times', '0'); ?></td>
                                    </tr>
<?php
    }
?>
                                    <tr>
                                      <td class="main"><?php echo ENTRY_FIRSTNAME; ?></td>
                                      <td class="main"><?php echo tep_draw_input_field('firstname'); ?></td>
                                    </tr>
                                    <tr>
                                      <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                                      <td class="main"><?php echo tep_draw_input_field('email_address'); ?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="top"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(array('login', 'action')) , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?>&nbsp;</td>
                                    </tr>
<?php
  }
?>
                            </form>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        <!-- Content_oef --> 

        <!-- Footer --> 
        <tr>
          <td>
          <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
          </td>
        </tr>
        <!-- Footer_oef --> 
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
