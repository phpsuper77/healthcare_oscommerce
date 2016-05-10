<?php
  require('includes/application_top.php');
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($HTTP_POST_VARS['subject']) && !isset($HTTP_POST_VARS['back_x']) ) {
    // fix message for IE
    $message = tep_db_prepare_input($HTTP_POST_VARS['message']);
    $message = str_replace('"'.DIR_WS_CATALOG . '', '"'.HTTP_SERVER . DIR_WS_CATALOG . '', $message);

    $input_array = array('subject'=>tep_db_prepare_input($HTTP_POST_VARS['subject']),
                         'message'=>$message,
                         'updated'=>'now()'
                         );

     $get_ex_id_q = tep_db_query("select id from " . TABLE_SALES_SURVAVE);
     if (tep_db_num_rows($get_ex_id_q)>0) {
       $get_ex_id = tep_db_fetch_array($get_ex_id_q);
       tep_db_perform(TABLE_SALES_SURVAVE, $input_array, 'update', "id='" . intval($get_ex_id['id']) . "'");
     } else {
       tep_db_perform(TABLE_SALES_SURVAVE, $input_array);
     }
     $messageStack->add_session(TEXT_EMAIL_UPDATE, 'success');

     tep_redirect(tep_href_link(FILENAME_SALES_SERVAVE));
  }

  if ( ($action == 'preview') && !tep_not_null($HTTP_POST_VARS['subject']) ) {
    $messageStack->add(ERROR_NO_SUBJECT_SELECTED, 'error');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php
  $header_title_menu=BOX_HEADING_INFORMATION;
  $header_title_menu_link= tep_href_link(FILENAME_SALES_SURVAVE, 'selected_box=information');
  $header_title_submenu=HEADING_TITLE;
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
         <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>

<?php
  if ( ($action == 'preview') && tep_not_null($HTTP_POST_VARS['subject'])) {
?>
          <tr><?php echo tep_draw_form('mail', FILENAME_SALES_SERVAVE, 'action=send_email_to_user'); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><br>
                <?php
                if (EMAIL_USE_HTML == 'true') { echo (stripslashes($HTTP_POST_VARS['message'])); } else { echo htmlspecialchars(stripslashes($HTTP_POST_VARS['message'])); } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($HTTP_POST_VARS);
    while (list($key, $value) = each($HTTP_POST_VARS)) {
      if (!is_array($HTTP_POST_VARS[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
                    <tr>
                      <td align="right"><?php echo '<a href="' . tep_href_link('sales_survave.php') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
                    </tr>
                  </tr>
                </table></td>
             </tr>
            </table></td>
          </form></tr>


<?php
  } else {

  // get if exist
  $data = array('subject'=>'',
                'message'=>''
                );

  $get_ex_id_q = tep_db_query("select subject, message from " . TABLE_SALES_SURVAVE);
  if (tep_db_num_rows($get_ex_id_q)>0) $data = tep_db_fetch_array($get_ex_id_q);
?>
<?php
if (EMAIL_USE_HTML == 'true') {
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
<?php
}
?>

          <tr><?php echo tep_draw_form('mail', FILENAME_SALES_SERVAVE, 'action=preview'); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" nowrap><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject',$data['subject'],'style="width:250px"'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main" nowrap><?php echo TEXT_MESSAGE; ?></td>
                <td width="100%"><?php
                // {{
                $message = $data['message'];
/*
                if (!tep_not_null($message))
                {
                  $contents = @implode('', @file(tep_catalog_href_link('email_template.php', '', 'NONSSL')));

                  $search = array ("'##EMAIL_TITLE##'i",
                                   "'##EMAIL_TEXT##'i");
                  $replace = array (TEXT_YOU_TITLE_HERE,
                                    TEXT_YOU_CONTENT_HERE);
                  $message = preg_replace ($search, $replace, $contents);
                }*/
                 echo tep_draw_textarea_field('message', 'soft', '60', '15', '', 'id="editor" class="editor" style="width: 100%;height:450px;"'). '</td>';
                ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>


                <td colspan="2" align="right">
                <?php echo tep_image_submit('button_preview.gif', IMAGE_PREVIEW); ?>
                </td>


              </tr>
            </table></td>
          </form></tr>
<?php
  }
?>
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
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