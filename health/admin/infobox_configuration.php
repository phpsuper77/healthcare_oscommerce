<?php
/*
  $Id: infobox_configuration.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');


////
// Alias function for Store configuration values in the Administration Tool
function tep_cfg_select_option_infobox($select_array, $key_value, $key = '') {
  $string = '';

  for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
    $name = ((tep_not_null($key)) ? 'infobox_' .$key  : 'infobox_display');
    $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i][0] . '"';
    if ($key_value == $select_array[$i][0]) $string .= ' CHECKED';
    $string .= '> ' . $select_array[$i][1];
  }
  return $string;
}

function tep_get_templates() {
  Global $login_id;
  if (tep_session_is_registered('login_affiliate')){
    $templates_query = tep_db_query("select t.template_id, t.template_name from " . TABLE_TEMPLATE . " t, " . TABLE_AFFILIATE . " a where a.affiliate_template = t.template_name and a.affiliate_id = '" . $login_id . "' order by template_id");
    if (!tep_db_num_rows($templates_query)){
      $templates_query = tep_db_query("select t.template_id, t.template_name from " . TABLE_TEMPLATE . " t where t.template_name = '" . tep_db_input(DEFAULT_TEMPLATE) . "' order by template_id");
    }
  }else{
    $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_id");
  }
  while ($template = tep_db_fetch_array($templates_query)) {
    $template_array[] = array('id' => $template['template_id'],
    'name' => $template['template_name']);
  }

  return $template_array;
}



  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {

    switch ($action) {

     case 'position_update': //set the status of a template active buttons.
        if ( ($HTTP_GET_VARS['flag'] == 'up') || ($HTTP_GET_VARS['flag'] == 'down') ) {
          if ($HTTP_GET_VARS['gID']) {
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $HTTP_GET_VARS['loc'] .  "', last_modified = now() where location = '" . $HTTP_GET_VARS['loc1'] . "' and display_in_column = '" . $HTTP_GET_VARS['col'] . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $HTTP_GET_VARS['loc1'] .  "', last_modified = now() where infobox_id = '" . (int)$iID . "' and display_in_column = '" . $HTTP_GET_VARS['col'] . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
        }
        }
        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $iID));
        break;


case 'fixweight': 
    global  $infobox_id, $cID;
    $rightpos = 'right';
    $leftpos = 'left';

    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $leftpos . "' and template_id = '" . (int)$gID . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'") . " order by location");

    $sorted_position = 0;
      while ($result = tep_db_fetch_array($result_query)) {
	$sorted_position++;
	tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$gID . "'");
    }

    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $rightpos . "' and template_id = '" . (int)$gID . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'") ." order by location");

    $sorted_position = 0;
      while ($result = tep_db_fetch_array($result_query)) {
	$sorted_position++;
	tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$gID . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
    }

        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
        break;


      case 'setflag': //set the status of a news item.
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if ($HTTP_GET_VARS['cID']) {
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_display = '" . $HTTP_GET_VARS['flag'] . "' where infobox_id = '" . (int)$cID . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
          }
        }

        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
        break;

      case 'setflagcolumn': //set the status of a news item.
        if ( ($HTTP_GET_VARS['flag'] == 'left') || ($HTTP_GET_VARS['flag'] == 'right') ) {
          if ($HTTP_GET_VARS['cID']) {
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set display_in_column = '" . $HTTP_GET_VARS['flag'] . "' where infobox_id = '" . (int)$cID . "' and template_id = '" . $HTTP_GET_VARS['gID'] . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
          }
        }

tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
        break;

      case 'save':

      $configuration_active = tep_db_prepare_input($HTTP_POST_VARS['infobox_active']);

      $infobox_file_name = tep_db_prepare_input($HTTP_POST_VARS['infobox_file_name']);
      $infobox_define = tep_db_prepare_input($HTTP_POST_VARS['infobox_define']);
      $display_in_column = tep_db_prepare_input($HTTP_POST_VARS['infobox_column']);
      $location = tep_db_prepare_input($HTTP_POST_VARS['location']);
      $box_heading = tep_db_prepare_input($HTTP_POST_VARS['box_heading']);
      $box_template = tep_db_prepare_input($HTTP_POST_VARS['box_template']);
      $box_heading_font_color = tep_db_prepare_input($HTTP_POST_VARS['hexval']);
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_file_name = '" . tep_db_input($infobox_file_name) . "',
							 infobox_define = '" . tep_db_input($infobox_define) . "',
							 location = '" . tep_db_input($location) . "',
							 display_in_column = '" . tep_db_input($display_in_column) . "',
							 infobox_display = '" . tep_db_input($configuration_active) . "',
							 box_heading = '" . tep_db_input($box_heading) . "',
							 box_template = '" . tep_db_input($box_template) . "',
							 box_heading_font_color = '" . tep_db_input($box_heading_font_color) . "',
							 last_modified = now() where infobox_id = '" . (int)$cID . "' and template_id = '" . $HTTP_GET_VARS['gID'] . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));

        if (!tep_session_is_registered('login_affiliate')){
          $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . $HTTP_GET_VARS['gID']);
          $template = tep_db_fetch_array($templates_query);
          $dest_dir = DIR_FS_TEMPLATES .$template['template_name'] . '/images/infoboxheading/';
          $languages = tep_get_languages();

          for ($i=0,$n=sizeof($languages);$i<$n;$i++){
            if ($HTTP_POST_VARS['unlink_' . $languages[$i]['code']] == 'yes'){
              @unlink($dest_dir . $cID . '_' . $languages[$i]['id'] . '.jpg');
            }else{
              $image = new upload($languages[$i]['code']);
              $image->set_destination($dest_dir);
              $image->parse();
              $image->set_filename($cID . '_' . $languages[$i]['id'] . '.jpg');
              $image->save();
            }
          }
        }
        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));
        break;

 case 'insert':

      $infobox_file_name = tep_db_prepare_input($HTTP_POST_VARS['infobox_file_name']);
      $infobox_define = tep_db_prepare_input($HTTP_POST_VARS['infobox_define']);
      $configuration_active = tep_db_prepare_input($HTTP_POST_VARS['infobox_active']);
      $display_in_column = tep_db_prepare_input($HTTP_POST_VARS['infobox_column']);
      $location = tep_db_prepare_input($HTTP_POST_VARS['location']);
      $box_heading = tep_db_prepare_input($HTTP_POST_VARS['box_heading']);
      $box_template = tep_db_prepare_input($HTTP_POST_VARS['box_template']);
      $template_id = tep_db_prepare_input($HTTP_GET_VARS['gID']);

      tep_db_query("insert into " . TABLE_INFOBOX_CONFIGURATION . " (template_id, infobox_file_name, infobox_display, infobox_define, display_in_column, location, box_heading, box_template, affiliate_id) values ('" . tep_db_input($template_id) . "', '" . tep_db_input($infobox_file_name) . "', '" . tep_db_input($configuration_active) . "', '" . tep_db_input($infobox_define) . "', '" . tep_db_input($display_in_column) . "', '" . tep_db_input($location) . "', '" . tep_db_input($box_heading) . "', '" . tep_db_input($box_template) . "', '" . (tep_session_is_registered('login_affiliate')?$login_id:0) . "')");
       tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID']));     
        break;

    case 'deleteconfirm':
      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);;

      tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . tep_db_input($cID) . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'"));
      
      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cID));  
      break;
    }
  }

  $gID = (isset($HTTP_GET_VARS['gID'])) ? $HTTP_GET_VARS['gID'] : 1;

  $template = tep_get_templates();
  $template_array = array();
  $template_selected = '';

  for ($i = 0, $n = sizeof($template); $i < $n; $i++) {
    $template_array[] = array('id' => $template[$i]['id'],
                               'text' => $template[$i]['name']);
  }

  if (tep_session_is_registered('login_affiliate')){

    $template_data = tep_db_fetch_array(tep_db_query("select t.template_id from " . TABLE_TEMPLATE . " t, " . TABLE_AFFILIATE . " a where a.affiliate_id = '" . $login_id . "' and t.template_name = a.affiliate_template"));
    $gID = $template_data['template_id'];
    $HTTP_GET_VARS['gID'] = $template_data['template_id'];

    $query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where affiliate_id = '" . $login_id . "' and template_id = '" . $gID . "'");
    if (!tep_db_num_rows($query)){
       $templates_query = tep_db_query("select t.template_id, t.template_name from " . TABLE_TEMPLATE . " t, " . TABLE_AFFILIATE . " a where a.affiliate_template = t.template_name and a.affiliate_id = '" . $login_id . "' order by template_id");
      if (!tep_db_num_rows($templates_query)){
        $templates_query = tep_db_query("select t.template_id, t.template_name from " . TABLE_TEMPLATE . " t where t.template_name = '" . tep_db_input(DEFAULT_TEMPLATE) . "' order by template_id");
      }
      $template_data = tep_db_fetch_array($templates_query);
      $data_query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $template_data['template_id']. "' and affiliate_id = 0");
      while ($data = tep_db_fetch_array($data_query))
      {
        $str = "insert into " . TABLE_INFOBOX_CONFIGURATION . " set ";
        foreach($data as $key => $value){
          if ($key == 'template_id'){
            $str .= " template_id = '" . $gID . "', ";
          }elseif ($key == 'infobox_id'){
            $str .= " infobox_id = '', ";
          }elseif ($key == 'affiliate_id'){
            $str .= " affiliate_id = '" . $login_id . "', ";
          }else{
            $str .= " " . $key . " = '" . tep_db_input($value) . "', ";
          }
        }
        $str = substr($str, 0, strlen($str)-2);
        tep_db_query($str);
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i = 40;
  window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
}
//--></script>
<script type="text/javascript" language="JavaScript"> 

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var infobox_file_name = document.infobox_configuration.infobox_file_name.value;
  var box_heading = document.infobox_configuration.box_heading.value;
  var infobox_define = document.infobox_configuration.infobox_define.value;  

  if (infobox_file_name == "") {
    error_message = error_message + "<?php echo JS_INFO_BOX_FILENAME; ?>";
    error = 1;
  }

  if (box_heading == "") {
    error_message = error_message + "<?php echo JS_INFO_BOX_HEADING; ?>";
    error = 1;
  }


  if (infobox_define == "" || infobox_define == "BOX_HEADING_????") {
    error_message = error_message + "<?php echo JS_BOX_HEADING; ?>";
    error = 1;
  }

  
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}


<!-- Begin 
function showColor(val) { 
document.infobox_configuration.hexval.value = val; 
} 
// End --> 


//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CONFIGURATION;
  $header_title_menu_link= tep_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('gID', FILENAME_INFOBOX_CONFIGURATION, '', 'get').tep_draw_pull_down_menu('gID', $template_array,  $template_selected,
  'onChange="this.form.submit();"').'</form>';
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
    <td width="100%" valign="top"  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td  height="100%"><center><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFOBOX_FILE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTIVE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COLUMN; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_TEMPLATE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_KEY; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>

<?php
$count_left_active = 0;
$count_right_active = 0;
$totInf_boxes = 1;

  $configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $HTTP_GET_VARS['gID'] . "' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'") . " order by display_in_column, location");
  while ($configuration = tep_db_fetch_array($configuration_query)) {

$totInf_boxes++;
      $cfgloc = $configuration['location'];
      $cfgValue = $configuration['infobox_display'];
      $cfgcol = $configuration['display_in_column'];
      $cfgtemp = $configuration['box_template'];
      $cfgkey = $configuration['infobox_define'];
      $cfgfont = $configuration['box_heading_font_color'];

	$location1 = $cfgloc /*- 1*/;
	$location3 = $cfgloc /*+ 1*/;

	    $res = tep_db_query("select infobox_id,location from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $HTTP_GET_VARS['gID'] . "' and location < ' $location1 ' and display_in_column ='$cfgcol' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'") ." order by location desc limit 1"); 
$con1 =  tep_db_fetch_array($res);

	    $res2 = tep_db_query("select infobox_id,location from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $HTTP_GET_VARS['gID'] . "' and location > ' $location3 ' and display_in_column ='$cfgcol' " . (tep_session_is_registered('login_affiliate')?" and affiliate_id = '" . $login_id . "'":" and affiliate_id='0'") . " order by location limit 1");
$con2 =  tep_db_fetch_array($res2);



if (($cfgcol == 'left') && ($cfgValue != 'no')) { 
$count_left_active++;
} else if (($cfgcol == 'right') && ($cfgValue != 'no'))
{$count_right_active++; 
}
	$infobox_list .= $configuration['infobox_file_name']. ",";

    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $configuration['infobox_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select infobox_define, date_added, last_modified from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$configuration['infobox_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['infobox_id'] == $cInfo->infobox_id) ) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' .   tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $configuration['box_heading']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($configuration['infobox_display'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflag&flag=0&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id'] ) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflag&flag=1&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
              

                <td class="dataTableContent" align="center"><?php
      if ($configuration['display_in_column'] == 'left') {
        echo tep_image(DIR_WS_IMAGES . 'icon_infobox_green.gif', IMAGE_INFOBOX_STATUS_GREEN, 14, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflagcolumn&flag=right&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_infobox_red_light.gif', IMAGE_INFOBOX_STATUS_RED_LIGHT, 14, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflagcolumn&flag=left&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_infobox_green_light.gif', IMAGE_INFOBOX_STATUS_GREEN_LIGHT, 14, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_infobox_red.gif', IMAGE_INFOBOX_STATUS_RED, 14, 10);
      }
?></td>
                <td class="dataTableContent" align="left"><?php echo htmlspecialchars($cfgtemp); ?></td>
                <td class="dataTableContent" align="left"><?php echo htmlspecialchars($cfgkey); ?></td>


<td height="30" align="center" valign="middle">
<?php
	    if ($con1) {
		echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=position_update&loc1=' .$con1['location'].'&loc=' .$cfgloc.'&flag=up&col=' . $cfgcol . '&iID=' .$configuration['infobox_id'] . '&gID=' . $HTTP_GET_VARS['gID']) . '">' . tep_image(DIR_WS_IMAGES . 'up.gif', IMAGE_ICON_STATUS_RED_LIGHT, 11, 14) . '</a>&nbsp;&nbsp;';
	    }
	    if ($con2) {
		echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=position_update&loc1=' .$con2['location'].'&loc=' .$cfgloc.'&flag=down&col=' . $cfgcol . '&iID=' .$configuration['infobox_id'] . '&gID=' . $HTTP_GET_VARS['gID']) . '">' . tep_image(DIR_WS_IMAGES . 'down.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 11, 14) . '</a>';
	    }
?>
</td>

                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($configuration['infobox_id'] == $cInfo->infobox_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>

            </table></td>
<?php

  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':

     echo tep_draw_form('infobox_configuration', FILENAME_INFOBOX_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=save', 'post', 'onSubmit="return check_form();" enctype="multipart/form-data"') . tep_draw_hidden_field('cID', $cInfo->infobox_id);
?>

      <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#B3BAC5">
          <tr>
            <td class="pageHeading"><?php echo TEXT_INFO_HEADING_UPDATE_INFOBOX . ' -- <font color="' . $cInfo->box_heading_font_color . '">' . $cInfo->box_heading;?></font></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td colspan="4" class="infoBoxContent" width="100%" align="center">
                <font color="red">* Denotes required field</font> </td>
              </tr>
              <tr>
                <td class="infoBoxContent" width="30%" align="center">
<?php

      echo '<br><b>Filename</b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=filename') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('infobox_file_name',$cInfo->infobox_file_name,'size="20"','true');
?>
                </td>
                <td class="infoBoxContent" width="30%" align="center">
<?php
	echo '<br><b>The infoBox heading? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=heading') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('box_heading',$cInfo->box_heading,'size="25"','true');
?>
                </td>
                <td class="infoBoxContent" width="20%" align="center">
<?php
      echo '<br><b>Which box Template? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=template') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('box_template',$cInfo->box_template,'size="25"','true');
?>
                </td>
                <td class="infoBoxContent" width="20%" align="center">
<?php
      echo '<br><b>Which column? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=column') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_cfg_select_option_infobox(array(array('left', 'left'), array('right', 'right')),$cInfo->display_in_column,'column') . '</b><br>';
?>
</td>
                    </tr>
              <tr>
                <td class="infoBoxContent" width="30%" align="center">
<?php
      echo '<br><b>What Position? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=position') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('location',$cInfo->location,'size=3');
?>
 </td>
                <td colspan="2" class="infoBoxContent" width="40%" align="center">
<?php
      echo '<br><b>  Define key </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=define') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('infobox_define',$cInfo->infobox_define,'size="35"','true');
?>

 </td>
                <td class="infoBoxContent" width="30%" align="center">
<?php

      echo '<br><b>Set this box Active? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=active') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_cfg_select_option_infobox(array(array('1', 'Yes'), array('0', 'No')),$cInfo->infobox_display,'active') . '</b><br><br>';

?>
</td>
                    </tr>
 <tr>
                <td colspan="4" class="infoBoxContent" width="100%" align="center">
  
</td>
                    </tr>
<?php
if (!tep_session_is_registered('login_affiliate')){
?>
              <tr>
                <td colspan=2 class=infoBoxContent width=100%>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class=infoBoxContent >
                  <b><?php echo TEXT_HEADING_IMAGES; ?></b>
                </td>
              </tr>
                <?php
                  $languages = tep_get_languages();
                  $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . $HTTP_GET_VARS['gID']);
                  $template = tep_db_fetch_array($templates_query);

                  for ($i=0, $n=sizeof($languages);$i<$n;$i++){
                    echo '<tr><td class="infoBoxContent"><b>' . $languages[$i]['name'] . '</b></td>' ;

                    echo '<td >' . tep_draw_file_field($languages[$i]['code']) . '</td>';
                    echo '</tr>';
                    if (is_file(DIR_FS_TEMPLATES . $template['template_name'] . '/images/infoboxheading/' . $cInfo->infobox_id . '_' . $languages[$i]['id'] . '.jpg')){
                       echo '<tr><td colspan="2"><img src="'.DIR_WS_TEMPLATES . $template['template_name'] . '/images/infoboxheading/' . $cInfo->infobox_id . '_' . $languages[$i]['id'] . '.jpg'.'"></td></tr>';
                    }
                    echo '<tr><td></td><td class="infoBoxContent"><input type="checkbox" name="unlink_' . $languages[$i]['code'] . '" value="yes">' . '&nbsp;&nbsp;<b>' . TEXT_UNLINK . '</b></td></tr>';

                  }
                ?>
           </table>
           </td>
           </tr>
<?php
}
?>
              <tr>
                <td colspan="4" class="infoBoxContent" width="100%" align="center">
<?php
      echo '<br><br><br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>
</form>
</td>
                    </tr>

<?php

      break;


case 'new':

      echo tep_draw_form('infobox_configuration', FILENAME_INFOBOX_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=insert', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('cID', $cInfo->infobox_id);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#B3BAC5">
          <tr>
            <td class="pageHeading"><?php echo TEXT_INFO_HEADING_NEW_INFOBOX;?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

              <tr>
                <td colspan="4" class="infoBoxContent" width="100%" align="center">
<font color="red">* Denotes required field</font>
</td>
                    </tr>
              <tr>
                <td class="infoBoxContent" width="30%" align="center">
<?php
 	$gID = $HTTP_GET_VARS['gID'];

    $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . $gID);
    $template = tep_db_fetch_array($templates_query);

	  if ($handle = opendir(DIR_FS_TEMPLATES.$template['template_name']."/boxes")) {
     /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle))) { 
    	  if(is_file(DIR_FS_TEMPLATES .$template['template_name']. '/boxes/' . $file) && stristr($infobox_list.".,..", $file) == FALSE){
	    	$dirs[] = $file;
	      	$dirs_array[] = array('id' => $file,
                                 'text' => $file);
            
         }
        }
        closedir($handle); 
     }

      echo '<br><b>Filename</b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=filename') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_pull_down_menu('infobox_file_name',$dirs_array,'', "style='width:150;'", 'true');
?>
 </td>
                <td class="infoBoxContent" width="30%" align="center">
<?php
	echo '<br><b>The infoBox heading? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=heading') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('box_heading','Example','size="30"','true');
?>
 </td>
                <td class="infoBoxContent" width="20%" align="center">
<?php
      echo '<br><b>Which box Template? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=template') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('box_template','example.tpl.php','size="25"','true');
?>
 </td>
                <td class="infoBoxContent" width="20%" align="center">
<?php
      echo '<br><b>Which column? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=column') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_cfg_select_option_infobox(array(array('left', 'left'), array('right', 'right')),'left','column') . '</b><br>';
?>
</td>
                    </tr>
              <tr>
                <td class="infoBoxContent" width="30%" align="center">
<?php
      echo '<br><b>What Position? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=position') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('location',$totInf_boxes,'size=3');
?>
 </td>
                <td colspan="2" class="infoBoxContent" width="40%" align="center">
<?php
      echo '<br><b>  Define key </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=define') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_draw_input_field('infobox_define','BOX_HEADING_EXAMPLE','size="35"','true');
?>
 </td>
                <td class="infoBoxContent" width="30%" align="center">
<?php

      echo '<br><b>Set this box Active? </b><br><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=active') . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a> ' . tep_cfg_select_option_infobox(array(array('1', 'Yes'), array('0', 'No')),'1','active') . '</b><br>';
?>
</td>
                    </tr>
              <tr>
                <td colspan="4" class="infoBoxContent" width="100%" align="center">
<?php
      echo '<br>' . tep_image_submit('button_module_install.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>
</form>
</td>
                    </tr>

<?php

      break;


 case 'delete':
?>
<tr>
        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#B3BAC5">
          <tr>
            <td class="pageHeading"><?php echo TEXT_INFO_HEADING_DELETE_INFOBOX . ' -- ' . $cInfo->box_heading;?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php

      $contents = array('form' => tep_draw_form('configuration', FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id . '&action=deleteconfirm'));
      $contents[] = array('align' => 'center', 'text' => TEXT_INFO_DELETE_INTRO);


      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_module_remove.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

default:

      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->infobox_file_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id . '&action=delete') . '">' . tep_image_button('button_module_remove.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=fixweight&gID=' . $HTTP_GET_VARS['gID'] . '&cID=' . $cInfo->infobox_id) . '">' . tep_image_button('button_update_box_positions.gif', IMAGE_UPDATE) . '</a><br>');

  $gID = $HTTP_GET_VARS['gID'];

  $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . $gID);
  $template = tep_db_fetch_array($templates_query);
  if (file_exists(DIR_FS_TEMPLATES.$template['template_name']."/boxes") && ($handle = opendir(DIR_FS_TEMPLATES.$template['template_name']."/boxes"))) {
   /* This is the correct way to loop over the directory. */
      while (false !== ($file = readdir($handle))) { 
  	  if(is_file(DIR_FS_TEMPLATES .$template['template_name']. '/boxes/' . $file) && stristr($infobox_list.".,..", $file) == FALSE){
        	$avail_boxes ++;
        }
      }
      closedir($handle); 

   
  	  if (($action != 'new') && ($action != 'edit') && ($action != 'delete') && ($avail_boxes > 0)){

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $HTTP_GET_VARS['gID'] . '&action=new') . '">' . tep_image_button('button_module_install.gif', IMAGE_NEW_INFOBOX) . '</a>');
	  }
      else if($avail_boxes == 0){
        $contents[] = array('align' => 'center', 'text' => 'This template does not have any infoboxes to install. Please put the infoboxes that you want to install in this template\'s boxes directory');

      }
  }
  else{
        $contents[] = array('align' => 'center', 'text' => 'This template does not have any infoboxes to install. Please put the infoboxes that you want to install in this template\'s boxes directory');
     
  }




        $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('align' => 'center','text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
       
		If ($cInfo->include_column_left == 'yes' && $count_left_active == 0) {
			$contents[] = array('align' => 'center','text' => '<font color="red" size="4">WARNING: No boxes selected in your LEFT column</font>');
		}
		If ($cInfo->include_column_right == 'yes' && $count_right_active == 0) {
			$contents[] = array('align' => 'center','text' => '<font color="red" size="4">WARNING: No boxes selected in your RIGHT column</font>');
		}
        $contents[] = array('align' => 'center','text' => '<br>There are currently <br>'. $count_left_active . ' active boxes in the left column and <br>'. $count_right_active . ' active boxes in the right column');
      }
      break;
  }



  if ( (tep_not_null($contents)) ) {
    echo '            </tr><tr><td width="100%" valign="top" align="center">' . "\n";

    $box = new box;
    echo $box->infoBox($heading,$contents);

    echo '            </td></tr> ' . "\n";

  } 

?>

          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
                 </table>
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
