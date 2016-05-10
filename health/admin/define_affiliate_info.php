<?php
/*
  $Id: define_affiliate_info.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  //////////////////////////////////////////////////////////////////////////

  define_affiliate_info.php Version 1.2

  /////////////////////////////////////////////////////////////////////////

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// This will cause it to look for 'mainpage.php'

  $HTTP_GET_VARS['filename'] = 'affiliate_info.tpl.php';

  switch ($HTTP_GET_VARS['action']) {
    case 'save':
      if ( ($HTTP_GET_VARS['lngdir']) && ($HTTP_GET_VARS['filename']) ) {
        if ($HTTP_GET_VARS['filename'] == $language . '.php') {
          $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['filename'];
        } else {
          $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir'] . '/' . $HTTP_GET_VARS['filename'];
        }
        if (file_exists($file)) {
          if (file_exists('bak' . $file)) {
            @unlink('bak' . $file);
          }
          @rename($file, 'bak' . $file);
          $new_file = fopen($file, 'w');
          $file_contents = stripslashes($HTTP_POST_VARS['file_contents']);
          fwrite($new_file, $file_contents, strlen($file_contents));
          fclose($new_file);
        }
        tep_redirect(tep_href_link(FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir']));
      }
      break;
  }

  if (!$HTTP_GET_VARS['lngdir']) $HTTP_GET_VARS['lngdir'] = $language;

  $languages_array = array();
  $languages = tep_get_languages();
  $lng_exists = false;
  for ($i=0; $i<sizeof($languages); $i++) {
    if ($languages[$i]['directory'] == $HTTP_GET_VARS['lngdir']) $lng_exists = true;

    $languages_array[] = array('id' => $languages[$i]['directory'],
                               'text' => $languages[$i]['name']);
  }
  if (!$lng_exists) $HTTP_GET_VARS['lngdir'] = $language;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>



  
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_AFFILIATE;
  $header_title_menu_link= tep_href_link(FILENAME_AFFILIATE, 'selected_box=affiliate');
  $header_title_submenu=BOX_AFFILIATE_DEFINE_AFFILIATE_INFO;
  $header_title_additional=tep_draw_form('lng', FILENAME_DEFINE_AFFILIATE_INFO, '', 'get').tep_draw_pull_down_menu('lngdir', $languages_array, '', 'onChange="this.form.submit();"').'</form>';
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">


<?php
  if ( ($HTTP_GET_VARS['lngdir']) && ($HTTP_GET_VARS['filename']) ) {
    if ($HTTP_GET_VARS['filename'] == $language . '.php') {
      $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['filename'];
    } else {
      $file = DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir'] . '/' . $HTTP_GET_VARS['filename'];
    }
    if (file_exists($file)) {
      $file_array = @file($file);
      $file_contents = @implode('', $file_array);

      $file_writeable = true;
      if (!is_writeable($file)) {
        $file_writeable = false;
        $messageStack->reset();
        $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
        echo $messageStack->output();
      }

?>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
_editor_url = '<?php echo ($request_type == 'SSL'?HTTPS_SERVER:HTTP_SERVER) . DIR_WS_ADMIN . DIR_WS_INCLUDES . 'javascript/htmlarea3/'; ?>';
_editor_lang = 'en';
//Change to current language code.
/*]]>*/
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/htmlarea.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/plugins/ImageManager/assets/dialog.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo DIR_WS_INCLUDES; ?>javascript/htmlarea3/plugins/ImageManager/IMEStandalone.js"></script>
<SCRIPT language="JavaScript">
HTMLArea.loadPlugin("TableOperations");
HTMLArea.loadPlugin("SpellChecker");
HTMLArea.loadPlugin("ContextMenu");
HTMLArea.loadPlugin("ListType");
HTMLArea.loadPlugin("ImageManager"); 
</SCRIPT>
<script language="JavaScript1.2" defer="defer"> 

var editor;
function editorGenerate(){
	editor = new HTMLArea("editor");  
	
  editor.registerPlugin(TableOperations);

  editor.registerPlugin(SpellChecker);
  editor.registerPlugin(ListType);
  editor.registerPlugin(ContextMenu);
  editor.registerPlugin(ImageManager);

  editor.generate(); 
  
}
</script> 
<script language="JavaScript">
initPage = function (){editorGenerate();}
function addEvent(obj, evType, fn) {if (obj.addEventListener) { obj.addEventListener(evType, fn, true); return true; }else if (obj.attachEvent) {  var r = obj.attachEvent("on"+evType, fn);  return r;  }else {  return false; }}
addEvent(window, 'load', initPage);
</script>
          <tr><?php echo tep_draw_form('language', FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir'] .  '&action=save'); ?>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo $HTTP_GET_VARS['filename']; ?></b></td>
              </tr>
              <tr>
                <td class="main" width="100%"><?php echo tep_draw_textarea_field('file_contents', 'soft', '150', '40', $file_contents, 'id="editor" class="editor" style="width: 100%;" ' . (($file_writeable) ? '' : 'readonly')); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td align="right"><?php if ($file_writeable) { echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; } else { echo '<a href="' . tep_href_link(FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; } ?></td>
              </tr>
            </table></td>
          </form></tr>


<?php
    } else {
?>
          <tr>
            <td class="main"><b><?php echo TEXT_FILE_DOES_NOT_EXIST; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td><?php echo '<a href="' . tep_href_link(FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
<?php
    }
  } else {
    $filename = $HTTP_GET_VARS['lngdir'] . '.php';
?>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText"><a href="<?php echo tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $HTTP_GET_VARS['lngdir'] . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a></td>
<?php
    $dir = dir(DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir']);
    $left = false;
    if ($dir) {
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      while ($file = $dir->read()) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          echo '                <td class="smallText"><a href="' . tep_href_link(FILENAME_DEFINE_AFFILIATE_INFO, 'lngdir=' . $HTTP_GET_VARS['lngdir']) . '">' . $file . '</a></td>' . "\n";
          if (!$left) {
            echo '              </tr>' . "\n" .
                 '              <tr>' . "\n";
          }
          $left = !$left;
        }
      }
      $dir->close();
    }
?>



              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'current_path=' . DIR_FS_CATALOG_LANGUAGES . $HTTP_GET_VARS['lngdir']) . '">' . tep_image_button('button_file_manager.gif', IMAGE_FILE_MANAGER) . '</a>'; ?></td>
          </tr>
<?php
  }
?>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
