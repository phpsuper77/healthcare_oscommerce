<?php
/*
  LoginBox v5.2.wfc1.0
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  IMPORTANT NOTE:

  This script is not part of the official osC distribution
  but an add-on contributed to the osC community. Please
  read the README and INSTALL documents that are provided
  with this file for further information and installation notes.

  LoginBox v5.0 was originally designed by Aubrey Kilian <aubrey@mycon.co.za>
  LoginBox v5.2 rewritten by Linda McGrath <osCOMMERCE@WebMakers.com>
  LoginBox v5.2.wfc1.0 modified by Justin of World Famous Comics <justin@wfcomics.com>
*/

// WebMakers.com Added: Do not show if on login or create account
if ( (!strstr($_SERVER['PHP_SELF'],'login.php')) and (!strstr($_SERVER['PHP_SELF'],'create_account.php')) and !tep_session_is_registered('customer_id') )  {
?>
<!-- loginbox //-->
<?php
    if (!tep_session_is_registered('customer_id')) {
?>
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => HEADER_TITLE_LOGIN);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }    
  }
    $loginboxcontent = '<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tr><td align="center" class="boxText">' . ENTRY_EMAIL_ADDRESS . '</td></tr><tr><td align="center" class="boxText"><input type="text" name="email_address" maxlength="96" size="15" value=""></td></tr><tr><td align="center" class="boxText">' . ENTRY_PASSWORD . '</td> </tr> <tr><td align="center" class="boxText"><input type="password" name="password" maxlength="40" size="15" value=""></td></tr>    <tr><td align="center" class="boxText" style="padding-top:5px;">' . tep_template_image_submit("button_login." . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_LOGIN, 'class="transpng"') . '</td></tr></table>';
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'form' => '<form name="login" method="post" action="'.tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . '">',
                                 'text'  => $loginboxcontent);
    $info_box_contents[] = array('align' => 'center','text'  => '<a class="infoBoxLink" HREF="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . TEXT_SECURE_LOGIN . '</A>');
    $info_box_contents[] = array('align' => 'center','text'  => '<a class="infoBoxLink" HREF="'. tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', "SSL") . '">' . LOGIN_BOX_PASSWORD_FORGOTTEN . '</A>');
  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<?php
  } 
?>
<!-- loginbox_eof //-->
<?php
// WebMakers.com Added: My Account Info Box
} else {
  if (tep_session_is_registered('customer_id')) {
?>

<!-- my_account_info //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_LOGIN_BOX_MY_ACCOUNT);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false, tep_href_link(FILENAME_ACCOUNT));
    }else{
      new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_ACCOUNT));
    }    
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a>'); 
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a>');
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . LOGIN_BOX_ADDRESS_BOOK . '</a>');
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a>');
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'NONSSL') . '">' . LOGIN_BOX_PRODUCT_NOTIFICATIONS . '</a>');
  $info_box_contents[] = array('text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . HEADER_TITLE_LOGOFF . '</a>');

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- my_account_info_eof //-->

<?php
  }
}
?>