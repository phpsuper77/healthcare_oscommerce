<?php
/*
  $Id: header.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// WebMakers.com Added: Down for Maintenance
// Hide header if not to show
if (DOWN_FOR_MAINTENANCE_HEADER_OFF =='false') {

?>
<!-- header -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="header_bg">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="header">
  <tr>
    <td>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="logo">

<a href="<?php echo tep_href_link(FILENAME_DEFAULT);?>"><?php
  $logo = get_affiliate_logo();
  if ($logo != ''){
    echo tep_image($logo, STORE_NAME);
  }else{
    echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/' . STORE_LOGO, STORE_NAME);
  }
?></a>

            </td>
            <td class="telNamber"><?php echo TEXT_OWNER_PHONE;?></td>
            <td class="login" width="200">
<?php
if (!tep_session_is_registered('customer_id')) {
$loginboxcontent = '<form name="login" method="post" action="'.tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . '">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><input type="text" name="email_address" maxlength="96" size="15" value="' . ENTRY_EMAIL_ADDRESS . '" class="inp" onfocus="this.value = \'\'" style="width:110px"></td>
	</tr>
	<tr>
		<td style="padding:3px 0">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><input type="password" name="password" maxlength="40" size="15" class="inp"  onfocus="this.value = \'\'" style="width:110px"></td>
						<td style="padding: 0 0 0px 2px;">' . tep_template_image_submit("button_login." . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_LOGIN, 'class="transpng"') . '</td>
					</tr>
				</table>
		</td>
	</tr>
</table></form>';
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => $loginboxcontent);
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT) . '">' . TEXT_REGISTER . '</a>&nbsp;|&nbsp;<a href="'. tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', "SSL") . '">' . LOGIN_BOX_PASSWORD_FORGOTTEN . '</a>');
		new tableBox($info_box_contents, true);
}else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a>');
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a>');
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a>');
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . HEADER_TITLE_LOGOFF . '</a>');
		new tableBox($info_box_contents, true);
}
?>


            </td>
          </tr>
        </table>

    </td>
  </tr>
  <tr>
    <td class="headerNav">

        <table border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
<?php
$header_query = tep_db_query('SELECT i.information_id, i.languages_id, if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, i.page, i.page_type FROM ' . TABLE_INFORMATION .' i left join ' . TABLE_INFORMATION . ' i1 on i.information_id = i1.information_id and i1.languages_id = '. $languages_id . ' and i1.affiliate_id = ' . (int)$HTTP_SESSION_VARS['affiliate_ref'] . ' WHERE i.visible=\'1\' and i.languages_id ='.$languages_id.' and FIND_IN_SET(\'header\', i.scope) and i.affiliate_id = 0 ORDER BY i.v_order');
                        $col=0;
                        while($header_info = tep_db_fetch_array($header_query)){
                          $title_link = tep_not_null($header_info['page_title'])?$header_info['page_title']:$header_info['info_title'];
                          if ($col!=0) echo '';
                          if ($header_info['page'] == ''){
                            echo '<td><a' . ($col==0?' class=" hnFirst"':'') . ' href="' . tep_href_link(FILENAME_INFORMATION, 'info_id=' . $header_info['information_id']) . '" title="'. tep_output_string($title_link) .'"><span>'. $header_info['info_title']  .'</span></a></td>';
                          }else {
                            echo '<td><a' . ($col==0?' class=" hnFirst"':'') . ' href="' . tep_href_link($header_info['page'], '', $header_info['page_type']) . '" title="'. tep_output_string($title_link) .'"><span>' . $header_info['info_title'] . '</span></a></td>';
                          }
                          $col++;
                        }
?>
          </tr>
        </table>

    </td>
  </tr>
</table>

    </td>
  </tr>
</table>
<!-- header_eof //-->
<?php
}
?>
