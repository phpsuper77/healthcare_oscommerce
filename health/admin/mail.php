<?php
/*
  $Id: mail.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($HTTP_POST_VARS['customers_email_address']) && !isset($HTTP_POST_VARS['back_x']) ) {
    switch ($HTTP_POST_VARS['customers_email_address']) {
      case '***':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . ((tep_session_is_registered('login_affiliate'))?" where affiliate_id = '" . $login_id . "'":''));
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'" . ((tep_session_is_registered('login_affiliate'))?" and affiliate_id = '" . $login_id . "'":'') );
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $customers_email_address = tep_db_prepare_input($HTTP_POST_VARS['customers_email_address']);

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "' " . ((tep_session_is_registered('login_affiliate'))?" and affiliate_id = '" . $login_id . "'":''));
        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
        break;
    }

    $from = tep_db_prepare_input($HTTP_POST_VARS['from']);
    $subject = tep_db_prepare_input($HTTP_POST_VARS['subject']);
    $message = tep_db_prepare_input($HTTP_POST_VARS['message']);

    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: osCommerce'));
    // add the message to the object
    
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Email when WYSIWYG Disabled)
    if (EMAIL_USE_HTML == 'false') {
    $mimemessage->add_text($message);
    } else {
    $mimemessage->add_html($message);
    }
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Email when WYSIWYG Enabled)

    $mimemessage->build_message();
    while ($mail = tep_db_fetch_array($mail_query)) {
      $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
    }

    tep_redirect(tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && !isset($HTTP_POST_VARS['customers_email_address']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($HTTP_GET_VARS['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $HTTP_GET_VARS['mail_sent_to']), 'success');
  }
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
<?php
  $header_title_menu=BOX_HEADING_TOOLS;
  $header_title_menu_link= tep_href_link(FILENAME_BACKUP, 'selected_box=tools');
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
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($action == 'preview') && isset($HTTP_POST_VARS['customers_email_address']) ) {
    switch ($HTTP_POST_VARS['customers_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $mail_sent_to = $HTTP_POST_VARS['customers_email_address'];
        break;
    }
?>
          <tr><?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=send_email_to_user'); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($HTTP_POST_VARS['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br>
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
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_MAIL) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                    </tr>
                    <td class="smallText">
                <?php if (EMAIL_USE_HTML == 'false'){echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="back"');
                } ?><?php if (EMAIL_USE_HTML == 'false') {echo(TEXT_EMAIL_BUTTON_HTML);
                 } else { echo(TEXT_EMAIL_BUTTON_TEXT); } ?>
                    </td>
                  </tr>
                </table></td>
             </tr>
            </table></td>
          </form></tr>
          
          
<?php
  } else {
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

          <tr><?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=preview'); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " " . ((tep_session_is_registered('login_affiliate'))?" where affiliate_id = '" . $login_id . "'":''). " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
?>
              <tr>
                <td class="main" nowrap><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($HTTP_GET_VARS['customer']) ? $HTTP_GET_VARS['customer'] : ''));?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" nowrap><?php echo TEXT_FROM; ?></td>
                <td>
                <?php 
                  if (tep_session_is_registered('login_affiliate')){
                    $data = tep_db_fetch_array(tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . $login_id . "'"));
                    echo tep_draw_input_field('from', $data['affiliate_firstname'] . ' ' . $data['affiliate_lastname'] . '<' . $data['affiliate_email_from'] . '>'); 
                  }else{
                    echo tep_draw_input_field('from', EMAIL_FROM); 
                  }
                ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" nowrap><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo tep_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main" nowrap><?php echo TEXT_MESSAGE; ?></td>
                <td width="100%"><?php 
                // {{
                if (!tep_not_null($message))
                {
                  $contents = tep_get_mail_body();
            
                  $search = array ("'##EMAIL_TITLE##'i",
                                   "'##EMAIL_TEXT##'i");
                  $replace = array (TEXT_YOU_TITLE_HERE,
                                    TEXT_YOU_CONTENT_HERE);
                  $message = preg_replace ($search, $replace, $contents);
                }
                // }}
                echo tep_draw_textarea_field('message', 'soft', '60', '15', '', 'id="editor" class="editor" style="width: 100%;height:450px;"'); 
                ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
              
              
                <td colspan="2" align="right">
                <?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?>
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
