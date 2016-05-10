<?php
/*
  $Id: logoff.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

//tep_session_destroy();
  tep_session_unregister('login_id');
  tep_session_unregister('login_firstname');
  tep_session_unregister('login_groups_id');
  tep_session_unregister('login_affiliate');
  tep_session_unregister('login_vendor');
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
                          <table cellspacing=10 cellpadding=3 border=0>
                          <?php echo tep_draw_form('login', FILENAME_LOGIN, 'action=process'); ?>
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
                              <TD class=main><?php echo TEXT_MAIN; ?></td>
                            </TR>
                            <tr>
                                <td class="formAreaTitle" align="right"><?php echo '<a class="login_heading" href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
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