<?php
/*
  $Id: login.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
    if (isset($HTTP_GET_VARS['type']) && ($HTTP_GET_VARS['type'] == 'affiliate')){
      $email_address = tep_db_prepare_input($HTTP_POST_VARS['affiliate_username']);
      $password = tep_db_prepare_input($HTTP_POST_VARS['affiliate_password']);

      $check_affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($email_address) . "' and affiliate_isactive = 1");
      if (!tep_db_num_rows($check_affiliate_query)) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else {
        $check_affiliate = tep_db_fetch_array($check_affiliate_query);
        if (!tep_validate_password($password, $check_affiliate['affiliate_password'])) {
          $HTTP_GET_VARS['login'] = 'fail';
        } else {
          if (tep_session_is_registered('password_forgotten')) {
            tep_session_unregister('password_forgotten');
          }

          $login_id = $check_affiliate['affiliate_id'];
          $login_firstname = $check_affiliate['login_firstname'];
          $login_lognum = $check_affiliate['affiliate_number_of_logons'];
          $login_logdate = $check_affiliate['affiliate_date_of_last_logon'];
          $login_modified = $check_affiliate['affiliate_date_account_last_modified'];
          $login_groups_id = AFFILIATE_DEFAULT_ADMIN_GROUP;
          $login_affiliate = 1;

          tep_session_register('login_id');
          tep_session_register('login_groups_id');
          tep_session_register('login_first_name');
          tep_session_register('login_affiliate');

          tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_date_of_last_logon = now(), affiliate_number_of_logons = affiliate_number_of_logons+1 where affiliate_id = '" . $login_id . "'");
          if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
            tep_redirect(tep_href_link(FILENAME_AFFILIATE));
          } else {
            if (sizeof($navigation->snapshot) > 0) {
              if (is_array($navigation->snapshot['get'])) {
                if (!in_array('selected_box', $navigation->snapshot['get'])){
                  $navigation->snapshot['get']['selected_box'] = get_box_name($navigation->snapshot['page']);
                }
              }
              unset($navigation->snapshot['get']['action']);

              $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
              $navigation->clear_snapshot();
  
              tep_redirect($origin_href);
            } else {
              tep_redirect(tep_href_link(FILENAME_DEFAULT));
            }
          }
        }
      }
    }elseif (isset($HTTP_GET_VARS['type']) && ($HTTP_GET_VARS['type'] == 'vendor') && VENDOR_ENABLED == 'true'){
      $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
      $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
      
      $check_vendor_query = tep_db_query("select * from " . TABLE_VENDOR . " where vendor_email_address = '" . tep_db_input($email_address) . "' and vendor_status = 1");
      if (!tep_db_num_rows($check_vendor_query)){
        $HTTP_GET_VARS['login'] = 'fail';
      }else{
        $check_vendor = tep_db_fetch_array($check_vendor_query);
        if (!tep_validate_password($password, $check_vendor['vendor_password'])){
          $HTTP_GET_VARS['login'] = 'fail';
        }else{
          if (tep_session_is_registered('password_forgotten')) {
            tep_session_unregister('password_forgotten');
          }

          $login_id = $check_vendor['vendor_id'];
          $login_firstname = $check_vendor['vendor_firstname'];
          $login_lognum = $check_vendor['vendor_number_of_logons'];
          $login_logdate = $check_vendor['vendor_date_of_last_logon'];
          $login_modified = $check_vendor['vendor_date_account_last_modified'];
          $login_groups_id = VENDOR_DEFAULT_ADMIN_GROUP;
          $login_vendor = 1;

          tep_session_register('login_id');
          tep_session_register('login_groups_id');
          tep_session_register('login_first_name');
          tep_session_register('login_vendor');

          tep_db_query("update " . TABLE_VENDOR . " set vendor_date_of_last_logon = now(), vendor_number_of_logons = vendor_number_of_logons+1 where vendor_id = '" . $login_id . "'");
          if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
            tep_redirect(tep_href_link(FILENAME_VENDOR));
          } else {
            if (sizeof($navigation->snapshot) > 0) {
              if (is_array($navigation->snapshot['get'])) {
                if (!in_array('selected_box', $navigation->snapshot['get'])){
                  $navigation->snapshot['get']['selected_box'] = get_box_name($navigation->snapshot['page']);
                }
              }
              
              unset($navigation->snapshot['get']['action']);
  
              $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
              $navigation->clear_snapshot();
  
              tep_redirect($origin_href);
            } else {
              tep_redirect(tep_href_link(FILENAME_DEFAULT));
            }
          }
        }
      }
    }else{
      $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
      $password = tep_db_prepare_input($HTTP_POST_VARS['password']);
  
  // Check if email exists
      $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
      if (!tep_db_num_rows($check_admin_query)) {
        $HTTP_GET_VARS['login'] = 'fail';
      } else {
        $check_admin = tep_db_fetch_array($check_admin_query);
        // Check that password is good
        if (!tep_validate_password($password, $check_admin['login_password'])) {
          $HTTP_GET_VARS['login'] = 'fail';
        } else {
          if (tep_session_is_registered('password_forgotten')) {
            tep_session_unregister('password_forgotten');
          }
  
          $login_id = $check_admin['login_id'];
          $login_groups_id = $check_admin['login_groups_id'];
          $login_firstname = $check_admin['login_firstname'];
          $login_email_address = $check_admin['login_email_address'];
          $login_logdate = $check_admin['login_logdate'];
          $login_lognum = $check_admin['login_lognum'];
          $login_modified = $check_admin['login_modified'];
  
          tep_session_register('login_id');
          tep_session_register('login_groups_id');
          tep_session_register('login_first_name');
  
          //$date_now = date('Ymd');
          tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
  
          if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
            tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
          } else {
            if (sizeof($navigation->snapshot) > 0) {
              if (is_array($navigation->snapshot['get'])) {
                if (!in_array('selected_box', $navigation->snapshot['get'])){
                  $navigation->snapshot['get']['selected_box'] = get_box_name($navigation->snapshot['page']);
                }
              }
              
              unset($navigation->snapshot['get']['action']);
  
              $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
              $navigation->clear_snapshot();
  
              tep_redirect($origin_href);
            } else {
              tep_redirect(tep_href_link(FILENAME_DEFAULT));
            }
          }
  
        }
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
   include(DIR_WS_INCLUDES . 'javascript/xml_used.js.php');  
?>
<?php if (XML_DUMP_ENABLE == "True") {?>
<script language="javascript">
  setCookie("xml_products", "", "Mon, 01-Jan-<?php echo (date("Y")+10);?> 00:00:00 GMT", "/");
  setCookie("xml_customers", "", "Mon, 01-Jan-<?php echo (date("Y")+10);?> 00:00:00 GMT", "/");
  setCookie("xml_orders", "", "Mon, 01-Jan-<?php echo (date("Y")+10);?> 00:00:00 GMT", "/");
  setCookie("xml_categories", "", "Mon, 01-Jan-<?php echo (date("Y")+10);?> 00:00:00 GMT", "/");
</script>
<?php }?>
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
                <tr>
                  <td width=249  background="<?=DIR_WS_IMAGES?>page_top_right.jpg"><?=tep_image(DIR_WS_IMAGES."page_top_right.jpg","","1","100%")?></td>
                  <td width="227" background="<?=DIR_WS_IMAGES?>top_pic_bg.gif" align="center"><?=tep_image(DIR_WS_IMAGES."logo.gif","","227","108")?></td>
                  <td  width=249  background="<?=DIR_WS_IMAGES?>page_top_right.jpg"><?=tep_image(DIR_WS_IMAGES."page_top_right.jpg","","1","108")?></td>
                </tr>
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
                          <?php echo tep_draw_form('login', FILENAME_LOGIN, 'action=process' . (isset($HTTP_GET_VARS['type'])?'&type=' . $HTTP_GET_VARS['type']:'')); ?>
                          <?php
                          if ($HTTP_GET_VARS['login'] == 'fail') {
                            $info_message = TEXT_LOGIN_ERROR;
                          }
                          if (isset($info_message)) {
                          ?>
                            <tr>
                              <td colspan="2" class="smallText" align="center"><?php echo $info_message; ?></td>
                            </tr>
                          <?php
                          } else {
                          ?>
                            <tr>
                              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                          <?php
                          }
                          ?>                                    
                            <TR>
                              <TD class=main><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                              <TD class=main><?php echo tep_draw_input_field(((isset($HTTP_GET_VARS['type']) && $HTTP_GET_VARS['type'] == 'affiliate')?'affiliate_username':'email_address')); ?></TD>
                            </TR>
                            <tr>
                              <td class="main"><?php echo ENTRY_PASSWORD; ?></td>
                              <td class="main"><?php echo tep_draw_password_field(((isset($HTTP_GET_VARS['type']) && $HTTP_GET_VARS['type'] == 'affiliate')?'affiliate_password':'password')); ?></td>
                            </tr>
                            <tr>
                              <td class="main" colspan="2"><?php
                                if (isset($HTTP_GET_VARS['type']) && $HTTP_GET_VARS['type'] == 'affiliate'){
                                  echo '<A class=sub href="' .tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_ADMIN_LOGIN . '</a>';
                                  if (VENDOR_ENABLED == 'true'){
                                    echo '<br><A class=sub href="' . tep_href_link(FILENAME_LOGIN, 'type=vendor' . tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_VENDOR_LOGIN . '</a>';
                                  }
                                }elseif(isset($HTTP_GET_VARS['type']) && $HTTP_GET_VARS['type'] == 'vendor'){
                                  echo '<A class=sub href="' .tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_ADMIN_LOGIN . '</a>';
                                  echo '<br><A class=sub href="' . tep_href_link(FILENAME_LOGIN, 'type=affiliate' . tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_AFFILIATE_LOGIN . '</a>';
                                }else{
                                  echo '<A class=sub href="' . tep_href_link(FILENAME_LOGIN, 'type=affiliate' . tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_AFFILIATE_LOGIN . '</a>';
                                  if (VENDOR_ENABLED == 'true'){
                                    echo '<br><A class=sub href="' . tep_href_link(FILENAME_LOGIN, 'type=vendor' . tep_get_all_get_params(array('action', 'type', 'login')), 'SSL') . '">' . TEXT_VENDOR_LOGIN . '</a>';
                                  }

                                }
                                ?>
                              </td>
                            </tr>
                            
                            <TR>
                              <td><A class=sub href="<?=tep_href_link(FILENAME_PASSWORD_FORGOTTEN, tep_get_all_get_params(array('action')), 'SSL')?>"><?=TEXT_PASSWORD_FORGOTTEN?></A></td>
                              <TD vAlign=top align=right><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_LOGIN); ?></TD>
                            </tr>
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