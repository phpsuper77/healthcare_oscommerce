<?php
/*
  $Id: groups.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['gID'])) $groups_id = tep_db_prepare_input($HTTP_GET_VARS['gID']);
        $groups_name = tep_db_prepare_input($HTTP_POST_VARS['groups_name']);
        $groups_discount = tep_db_prepare_input($HTTP_POST_VARS['groups_discount']);
        $groups_is_tax_applicable = tep_db_prepare_input($HTTP_POST_VARS['groups_is_tax_applicable']);
        $groups_is_show_price = tep_db_prepare_input($HTTP_POST_VARS['groups_is_show_price']);
        $groups_disable_checkout = tep_db_prepare_input($HTTP_POST_VARS['groups_disable_checkout']);
        $groups_is_reseller = tep_db_prepare_input($HTTP_POST_VARS['groups_is_reseller']);
        $new_approve = tep_db_prepare_input($HTTP_POST_VARS['new_approve']);
        $groups_default = $HTTP_POST_VARS['default'];
        $sql_data_array = array('groups_name' => $groups_name,
                                'groups_discount' => $groups_discount,
                                'groups_is_show_price' => $groups_is_show_price,
                                'groups_is_tax_applicable' => $groups_is_tax_applicable,
                                'new_approve' => $new_approve,
                                'groups_is_reseller' => $groups_is_reseller,
                                'groups_disable_checkout' => $groups_disable_checkout);

        $image_active = new upload('image_active');
        $image_active->set_destination(DIR_FS_CATALOG_IMAGES . 'icons/');
        if ($image_active->parse() && $image_active->save()) {
          $sql_data_array['image_active'] = $image_active->filename;
        }
        $image_inactive = new upload('image_inactive');
        $image_inactive->set_destination(DIR_FS_CATALOG_IMAGES . 'icons/');
        if ($image_inactive->parse() && $image_inactive->save()) {
          $sql_data_array['image_inactive'] = $image_inactive->filename;
        }

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_GROUPS, $sql_data_array);
          $groups_id = tep_db_insert_id();

        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
          tep_db_perform(TABLE_GROUPS, $sql_data_array, 'update', "groups_id = '" . (int)$groups_id . "'");
        }

        tep_redirect(tep_href_link(FILENAME_GROUPS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'gID=' . $groups_id));
        break;
      case 'deleteconfirm':
        $groups_id = tep_db_prepare_input($HTTP_GET_VARS['gID']);

        if (DEFAULT_USER_GROUP == $groups_id){
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '0' where configuration_key = 'DEFAULT_USER_GROUP'");
        }
        if (DEFAULT_USER_LOGIN_GROUP == $groups_id){
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '0' where configuration_key = 'DEFAULT_USER_LOGIN_GROUP'");
        }

        tep_db_query("delete from " . TABLE_GROUPS . " where groups_id = '" . (int)$groups_id . "'");

        tep_db_query("update " . TABLE_CUSTOMERS . " set groups_id = '0' where groups_id = '" . (int)$groups_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_PRICES . " where groups_id = '" . (int)$groups_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where groups_id = '" . (int)$groups_id . "'");
        tep_db_query("delete from " . TABLE_SPECIALS_PRICES . " where groups_id = '" . (int)$groups_id . "'");
        tep_db_query("delete from " . TABLE_SALEMAKER_SALES . " where groups_id = '" . (int)$groups_id . "'");

        tep_redirect(tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page']));
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php
$header_title_menu = BOX_HEADING_CUSTOMERS;
$header_title_menu_link = tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
$header_title_submenu = HEADING_TITLE;
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
<!--<?php /* ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php */ ?>-->
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_GROUPS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DISCOUNT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $groups_query_raw = "select groups_id, groups_name, groups_discount, groups_is_tax_applicable,  groups_disable_checkout, date_added, last_modified, groups_is_show_price, new_approve, groups_is_reseller, image_active, image_inactive from " . TABLE_GROUPS . " order by groups_name";
  $groups_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $groups_query_raw, $groups_query_numrows);
  $groups_query = tep_db_query($groups_query_raw);
  while ($groups = tep_db_fetch_array($groups_query)) {
    if ((!isset($HTTP_GET_VARS['gID']) || (isset($HTTP_GET_VARS['gID']) && ($HTTP_GET_VARS['gID'] == $groups['groups_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $mInfo = new objectInfo($groups);
    }

    if (isset($mInfo) && is_object($mInfo) && ($groups['groups_id'] == $mInfo->groups_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $groups['groups_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $groups['groups_id']) . '\'">' . "\n";
    }
?>
<?php
     echo '                <td class="dataTableContent">' . $groups['groups_name'] . '</td>' . "\n";
?>
                <td class="dataTableContent" align="right"><?php echo $groups['groups_discount']; ?>&nbsp;%</td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($groups['groups_id'] == $mInfo->groups_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $groups['groups_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $groups_split->display_count($groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_GROUPS); ?></td>
                    <td class="smallText" align="right"><?php echo $groups_split->display_links($groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_GROUP . '</b>');

      $contents = array('form' => tep_draw_form('groups', FILENAME_GROUPS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_GROUPS_NAME . '<br>' . tep_draw_input_field('groups_name'));
      $contents[] = array('text' => '<br>' . TEXT_GROUPS_DISCOUNT . '<br>' . tep_draw_input_field('groups_discount', '0.00', 'size="5"') . '&nbsp;%');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_tax_applicable', '1', true) . '&nbsp;' . TEXT_GROUPS_IS_TAX_APPLICABLE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_show_price', '1', true) . '&nbsp;' . TEXT_GROUPS_IS_SHOW_PRICE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_disable_checkout', '1', false) . '&nbsp;' . TEXT_GROUPS_DISABLE_CHECKOUT);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('new_approve', '1', false) . '&nbsp;' . TEXT_GROUPS_NEW_APPROVE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_reseller', '1', false) . '&nbsp;' . TEXT_GROUPS_IS_RESELLER);
      $contents[] = array('text' => '<br>' . tep_draw_file_field('image_active') . '&nbsp;' . TEXT_ACTIVE_IMAGE);
      $contents[] = array('text' => '<br>' . tep_draw_file_field('image_inactive') . '&nbsp;' . TEXT_INACTIVE_IMAGE);

//      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $HTTP_GET_VARS['gID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_GROUP . '</b>');

      $contents = array('form' => tep_draw_form('groups', FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_GROUPS_NAME . '<br>' . tep_draw_input_field('groups_name', $mInfo->groups_name));
      $contents[] = array('text' => '<br>' . TEXT_GROUPS_DISCOUNT . '<br>' . tep_draw_input_field('groups_discount', $mInfo->groups_discount, 'size="5"') . '&nbsp;%');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_tax_applicable', '1', $mInfo->groups_is_tax_applicable) . '&nbsp;' . TEXT_GROUPS_IS_TAX_APPLICABLE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_show_price', '1', $mInfo->groups_is_show_price) . '&nbsp;' . TEXT_GROUPS_IS_SHOW_PRICE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_disable_checkout', '1', $mInfo->groups_disable_checkout) . '&nbsp;' . TEXT_GROUPS_DISABLE_CHECKOUT);

      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('new_approve', '1', $mInfo->new_approve) . '&nbsp;' . TEXT_GROUPS_NEW_APPROVE);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('groups_is_reseller', '1', $mInfo->groups_is_reseller) . '&nbsp;' . TEXT_GROUPS_IS_RESELLER);
      $contents[] = array('text' => '<br>' . TEXT_ACTIVE_IMAGE . '<br>' . tep_draw_file_field('image_active'));
      if (is_file(DIR_FS_CATALOG_IMAGES . 'icons/' . $mInfo->image_active)){
        $contents[] = array('text' => '<br><img src="' . DIR_WS_CATALOG_IMAGES . 'icons/' . $mInfo->image_active . '" border="0" width="24" height="24">');
      }
      $contents[] = array('text' => '<br>' . TEXT_INACTIVE_IMAGE . '<br>' . tep_draw_file_field('image_inactive'));
      if (is_file(DIR_FS_CATALOG_IMAGES . 'icons/' . $mInfo->image_inactive)){
        $contents[] = array('text' => '<br><img src="' . DIR_WS_CATALOG_IMAGES . 'icons/' . $mInfo->image_inactive . '" border="0" width="24" height="24">');
      }


//      if ($mInfo->groups_default != 1) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);


      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_GROUP . '</b>');
      $contents = array('form' => tep_draw_form('groups', FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->groups_name . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->groups_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>' . ($mInfo->groups_default != 1?' <a href="' . tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : ''));

        $data = tep_db_fetch_array(tep_db_query("select count(customers_id) as total_customers from " . TABLE_CUSTOMERS . " where groups_id = '" . (int)$mInfo->groups_id . "'"));
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_GROUPS_CUSTOMERS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $mInfo->groups_id) . '">' . TEXT_EDIT_CUSTOMERS . '&nbsp;(' . (int)$data['total_customers'] . ')</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
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
