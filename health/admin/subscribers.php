<?php
/*
  $Id: customers.php,v 1.71 2002/04/29 15:12:19 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'update':
        $subscribers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $subscribers_firstname = tep_db_prepare_input($HTTP_POST_VARS['subscribers_firstname']);
        $subscribers_lastname = tep_db_prepare_input($HTTP_POST_VARS['subscribers_lastname']);
        $subscribers_email_address = tep_db_prepare_input($HTTP_POST_VARS['subscribers_email_address']);

        $sql_data_array = array('subscribers_firstname' => $subscribers_firstname,
                                'subscribers_lastname' => $subscribers_lastname,
                                'subscribers_email_address' => $subscribers_email_address);

        tep_db_perform(TABLE_SUBSCRIBERS, $sql_data_array, 'update', "subscribers_id = '" . tep_db_input($subscribers_id) . "'");

        tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $subscribers_id));
        break;
      case 'deleteconfirm':
        $subscribers_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        
        tep_db_query("delete from " . TABLE_SUBSCRIBERS . " where subscribers_id = '" . tep_db_input($subscribers_id) . "'");
        tep_redirect(tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')))); 
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
?>
<script language="javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var subscribers_firstname = document.subscribers.subscribers_firstname.value;
  var subscribers_lastname = document.subscribers.subscribers_lastname.value;
  var subscribers_email_address = document.subscribers.subscribers_email_address.value;  


  if (subscribers_firstname = "" || subscribers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (subscribers_lastname = "" || subscribers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

  if (subscribers_email_address = "" || subscribers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CUSTOMERS;
  $header_title_menu_link= tep_href_link(FILENAME_SUBSCRIBERS, 'selected_box=customers');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional=tep_draw_form('search', FILENAME_SUBSCRIBERS, '', 'get').HEADING_TITLE_SEARCH.' ' 
 . tep_draw_input_field('search').'</form>';
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
   <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
<?php
  if ($HTTP_GET_VARS['action'] == 'edit') {
    $subscribers_query = tep_db_query("select c.subscribers_firstname, c.subscribers_lastname, c.subscribers_email_address  from " . TABLE_SUBSCRIBERS . " c where c.subscribers_id = '" . $HTTP_GET_VARS['cID'] . "'");
    $subscribers = tep_db_fetch_array($subscribers_query);
    $cInfo = new objectInfo($subscribers);
?>
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr><?php echo tep_draw_form('subscribers', FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('subscribers_firstname', $cInfo->subscribers_firstname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('subscribers_lastname', $cInfo->subscribers_lastname, 'maxlength="32"', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php echo tep_draw_input_field('subscribers_email_address', $cInfo->subscribers_email_address, 'maxlength="96"', true); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
      <tr>
        <td height="100%">&nbsp;</td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td valign=top><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if ( ($HTTP_GET_VARS['search']) && (tep_not_null($HTTP_GET_VARS['search'])) ) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = "where c.subscribers_lastname like '%" . $keywords . "%' or c.subscribers_firstname like '%" . $keywords . "%' or c.subscribers_email_address like '%" . $keywords . "'";
    }
    $subscribers_query_raw = "select c.subscribers_id, c.subscribers_lastname, c.subscribers_firstname, c.subscribers_email_address from " . TABLE_SUBSCRIBERS . ' c '  . $search . " order by c.subscribers_lastname, c.subscribers_firstname";
    $subscribers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $subscribers_query_raw, $subscribers_query_numrows);
    $subscribers_query = tep_db_query($subscribers_query_raw);
    while ($subscribers = tep_db_fetch_array($subscribers_query)) {
      if (((!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['cID'] == $subscribers['subscribers_id'])) && (!$cInfo)) {
        $cInfo_array = $subscribers;
        $cInfo = new objectInfo($cInfo_array);
      }
      if ( (is_object($cInfo)) && ($subscribers['subscribers_id'] == $cInfo->subscribers_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->subscribers_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID')) . 'cID=' . $subscribers['subscribers_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $subscribers['subscribers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $subscribers['subscribers_firstname']; ?></td>
				<td class="dataTableContent"><?php echo $subscribers['subscribers_email_address']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($subscribers['subscribers_id'] == $cInfo->subscribers_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID')) . 'cID=' . $subscribers['subscribers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $subscribers_split->display_count($subscribers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $subscribers_split->display_links($subscribers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_SUBSCRIBERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('subscribers', FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->subscribers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->subscribers_firstname . ' ' . $cInfo->subscribers_lastname . '</b>');
      if ($cInfo->number_of_reviews > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->subscribers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->subscribers_firstname . ' ' . $cInfo->subscribers_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->subscribers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SUBSCRIBERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->subscribers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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