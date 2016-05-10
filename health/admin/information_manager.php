<?php
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . 'information.php');

function browse_information () {
  global $languages_id;
  $daftar=tep_db_query("SELECT * FROM " . TABLE_INFORMATION . " WHERE languages_id=$languages_id and affiliate_id = 0 ORDER BY v_order");
  $result = array();
  while ($buffer = tep_db_fetch_array($daftar)) {
    $result[]=$buffer;
  }
  return $result;
}

function read_data ($information_id, $language_id, $affiliate_id = 0) {
  $result = tep_db_fetch_array(tep_db_query("SELECT * FROM " . TABLE_INFORMATION . " WHERE information_id=$information_id and languages_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'"));
  return $result;
}

function add_information($data, $language_id, $affiliate_id = 0) {
  global $insert_id;
  $query ="INSERT INTO " . TABLE_INFORMATION . " (information_id, visible, v_order, info_title, description, languages_id, page_title, page, scope, meta_description, meta_key, affiliate_id, page_type) VALUES('" . $insert_id . "', '" . $data['visible'][$language_id] . "', '" . $data['v_order'][$language_id] . "', '" . tep_db_input($data['info_title'][$language_id][$affiliate_id]) . "', '" . tep_db_input($data['description'][$language_id][$affiliate_id]) . "','" . $language_id . "', '" . tep_db_input($data['page_title'][$language_id][$affiliate_id]) . "', '" . $data['page'][$language_id] . "', '" . (is_array($data['scope'][$language_id])?implode(',', $data['scope'][$language_id]):'') . "', '" . tep_db_input($data['meta_description'][$language_id]) . "', '" . tep_db_input($data['meta_key'][$language_id]) . "', '" . $affiliate_id . "', '" . $data['page_type'][$language_id] . "')";
  tep_db_query($query);
  if ($insert_id == ''){
    $insert_id = tep_db_insert_id();
  }
}

$warning=tep_image(DIR_WS_ICONS . 'warning.gif', WARNING_INFORMATION);

function error_message($error) {
  global $warning;
  switch ($error) {
    case "20":return "<tr class=messageStackError><td>$warning ." . ERROR_20_INFORMATION . "</td></tr>";break;
    case "80":return "<tr class=messageStackError><td>$warning " . ERROR_80_INFORMATION . "</td></tr>";break;
    default:return $error;
  }
}

switch($adgrafics_information) {
case "AddSure":
  if ( !tep_session_is_registered('login_affiliate') ) {
    $insert_id = '';
    $HTTP_POST_VARS = tep_db_prepare_input($HTTP_POST_VARS);
    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      add_information($HTTP_POST_VARS, $languages[$i]['id']);
      $affiliates = tep_get_affiliates();
      for($j=0;$j<sizeof($affiliates);$j++) {
        add_information($HTTP_POST_VARS, $languages[$i]['id'], $affiliates[$j]['id']);
      }
    }
    $data=browse_information();
    $title="" . tep_image(DIR_WS_ICONS . 'confirm_red.gif', CONFIRM_INFORMATION) .SUCCED_INFORMATION . ADD_QUEUE_INFORMATION . " $v_order ";
  }
  tep_redirect(tep_href_link(FILENAME_INFORMATION_MANAGER));
    //include('information_list.php');

  break;
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

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_INFORMATION;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=information');
  $header_title_submenu=MANAGER_INFORMATION;
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
          </table>
        </td>
        <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"  height="100%">
<table border=0 width="100%" cellspacing="0" cellpadding="0">
<?
  function update_information ($data, $language_id, $affiliate_id = 0) {
    global $login_id;
    if (!tep_session_is_registered('login_affiliate')) {
      $check = tep_db_query("select * from " . TABLE_INFORMATION . " where information_id= '" . $data[information_id] . "' and languages_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'");
      if (tep_db_num_rows($check)){
      $query = tep_db_query("UPDATE " . TABLE_INFORMATION . " SET info_title='" . tep_db_input($data['info_title'][$language_id][$affiliate_id]) . "', description='" . tep_db_input($data[description][$language_id][$affiliate_id]) . "', visible='" . tep_db_input($data['visible'][$language_id]) . "', v_order='" . tep_db_input($data['v_order'][$language_id]) . "', page_title='" . tep_db_input($data['page_title'][$language_id][$affiliate_id]) . "', page='" . tep_db_input($data['page'][$language_id]) . "', scope='" . (is_array($data['scope'][$language_id])?implode(',', $data['scope'][$language_id]):'') . "', page_type = '" . $data['page_type'][$language_id] . "', meta_description = '" . tep_db_prepare_input(tep_db_input(addslashes($data['meta_description'][$language_id]))) . "', meta_key = '" . tep_db_prepare_input(tep_db_input(addslashes($data['meta_key'][$language_id]))) . "' WHERE information_id= '" . $data[information_id] . "' and languages_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'");
      }else{
       tep_db_query("INSERT INTO " . TABLE_INFORMATION . " (information_id, visible, v_order, info_title, description, languages_id, page_title, page, scope, meta_description, meta_key, affiliate_id, page_type) VALUES('" . $data[information_id] . "', '" . $data['visible'][$language_id] . "', '" . $data['v_order'][$language_id] . "', '" . tep_db_input($data['info_title'][$language_id][$affiliate_id]) . "', '" . tep_db_input($data['description'][$language_id][$affiliate_id]) . "','" . $language_id . "', '" . tep_db_input($data['page_title'][$language_id][$affiliate_id]) . "', '" . $data['page'][$language_id] . "', '" . (is_array($data['scope'][$language_id])?implode(',', $data['scope'][$language_id]):'') . "', '" . tep_db_prepare_input(tep_db_input(addslashes($data['meta_description'][$language_id]))) . "', '" . tep_db_prepare_input(tep_db_input(addslashes($data['meta_key'][$language_id]))) . "', '" . $affiliate_id . "', '" . $data['page_type'][$language_id] . "')");
      }
    }else{
      if ( (int)$affiliate_id!=$login_id ) return;
      $check = tep_db_query("select * from " . TABLE_INFORMATION . " where information_id= '" . $data[information_id] . "' and languages_id = '" . $language_id . "' and affiliate_id = '" . $affiliate_id . "'");
      $upd_array = array(
        'info_title' => $data['info_title'][$language_id][$affiliate_id],
        'description' => $data['description'][$language_id][$affiliate_id], 
        //'visible' => $data['visible'][$language_id], 
        //'v_order' => $data['v_order'][$language_id], 
        'page_title' => $data['page_title'][$language_id][$affiliate_id], 
        //'page' => $data['page'][$language_id], 
        //'scope' => (is_array($data['scope'][$language_id])?implode(',', $data['scope'][$language_id]):''),
        //'page_type' => $data['page_type'][$language_id]
      );
        
      if (tep_db_num_rows($check)){
        tep_db_perform( TABLE_INFORMATION, $upd_array, 'update', "information_id='" . $data['information_id'] . "' and languages_id = '" . (int)$language_id . "' and affiliate_id = '" . (int)$affiliate_id . "'" );
      }else{
        $missing_data = tep_db_fetch_array(tep_db_query("select visible, v_order, page, scope, page_type from " . TABLE_INFORMATION . " where information_id= '" . $data['information_id'] . "' and languages_id = '" . $language_id . "' and affiliate_id = '0'"));
        if ( is_array($missing_data) ) {
          $upd_array = array_merge($upd_array, $missing_data);
          $upd_array['information_id'] = $data['information_id'];
          $upd_array['affiliate_id'] = (int)$affiliate_id;
          $upd_array['languages_id'] = (int)$language_id;
          tep_db_perform( TABLE_INFORMATION, $upd_array);
        }
      }
    }
  }
  function tep_set_information_visible($information_id, $visible) {
    if ( tep_session_is_registered('login_affiliate') ) return;
    if ($visible == '1') {
      return tep_db_query("update " . TABLE_INFORMATION . " set visible = '0' where information_id = '" . (int)$information_id . "'");
    } else{
      return tep_db_query("update " . TABLE_INFORMATION . " set visible = '1' where information_id = '" . (int)$information_id . "'");
    }
  }
  function delete_information ($information_id) {
    if ( tep_session_is_registered('login_affiliate') ) return;
    tep_db_query("DELETE FROM " . TABLE_INFORMATION . " WHERE information_id=".(int)$information_id);
  }

switch($adgrafics_information) {

case "Added":
    $title="" . ADD_QUEUE_INFORMATION ;
    echo tep_draw_form('edit_info',FILENAME_INFORMATION_MANAGER, 'adgrafics_information=AddSure');
    include('information_form.php');
  break;


case "Edit":
    if ($information_id) {

      $button=array("Update");
      $title="" . EDIT_ID_INFORMATION . " $information_id";
      echo tep_draw_form('edit_info',FILENAME_INFORMATION_MANAGER, 'adgrafics_information=Update','post', 'accept-charset="iso-8859-1"');
      echo tep_draw_hidden_field('information_id', "$information_id");
      include('information_form.php');
    } else {$error="80";}
  break;

case "Update":
    $HTTP_POST_VARS = tep_db_prepare_input($HTTP_POST_VARS);
    $languages = tep_get_languages();
    $affiliates = tep_get_affiliates();
	
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      update_information($HTTP_POST_VARS, $languages[$i]['id']);
      
      for($j=0;$j<sizeof($affiliates);$j++) {
        update_information($HTTP_POST_VARS, $languages[$i]['id'], $affiliates[$j]['id']);
      }      
      
    }
    $data=browse_information();
    $title="$confirm " . UPDATE_ID_INFORMATION . " $information_id " . SUCCED_INFORMATION . "";
    include('information_list.php');
  break;

case 'Visible':
    tep_set_information_visible($information_id, $visible);
    $data=browse_information();
    if ($visible == '1') {  $vivod=DEACTIVATION_ID_INFORMATION;
    }else{$vivod=ACTIVATION_ID_INFORMATION;}
    $title="$confirm $vivod $information_id " . SUCCED_INFORMATION . "";
    include('information_list.php');
        break;

case "Delete":
    if ($information_id) {
    $delete=read_data($information_id, $languages_id);
    $data=browse_information();
    $title="" . DELETE_CONFITMATION_ID_INFORMATION . " $information_id";
    ?>
    <tr>
      <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
    </tr>
<?
    echo "<tr class=pageHeading><td>$title  </td></tr>";
    echo "<tr><td>" . TITLE_INFORMATION . " $delete[info_title]</td></tr><tr><td align=right>";
    echo tep_draw_form('',FILENAME_INFORMATION_MANAGER, "adgrafics_information=DelSure&information_id=$val[information_id]");
    echo tep_draw_hidden_field('information_id', "$information_id");
    echo tep_image_submit('button_delete.gif', IMAGE_DELETE);
    echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    echo "</form></td></tr>";
    } else {$error="80";}
    break;


case "DelSure":
    if ($information_id) {
    delete_information($information_id);
    $data=browse_information();
    $title="$confirm " . DELETED_ID_INFORMATION . " $information_id " . SUCCED_INFORMATION . "";
    include('information_list.php');
    } else {$error="80";}
    break;
default:
    $data=browse_information();
//    $title="" . MANAGER_INFORMATION . "";
    include('information_list.php');
  }
if ($error) {
    $content=error_message($error);
    echo $content;
    $data=browse_information();
    $no=1;
     if (sizeof($data) > 0) {while (list($key, $val)=each($data)) {$no++; } } ;
    $title="" . ADD_QUEUE_INFORMATION . " $no";
    echo tep_draw_form('',FILENAME_INFORMATION_MANAGER, 'adgrafics_information=AddSure');
    include('information_form.php');
}
?>
</table>
</td>


<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
