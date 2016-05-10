<?php
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        //print_r($HTTP_POST_VARS);die;
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $products_qty = 1; // tep_db_prepare_input($HTTP_POST_VARS['products_qty']);
        $group_price = tep_db_prepare_input($HTTP_POST_VARS['group_price']);
        if($group_price >= 0){
          // clear prev data, in case if input combination already exists
          tep_db_query("delete from " . TABLE_GIVE_AWAY_PRODUCTS . " where products_id = '" . $products_id . "'");
          tep_db_query("insert into " . TABLE_GIVE_AWAY_PRODUCTS . " (products_id, shopping_cart_price, products_qty) values('" . $products_id . "', '" . $group_price . "', '" . $products_qty . "')");
        }
        tep_redirect(tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'update':
        //print_r($HTTP_POST_VARS);die;
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $gap_id = tep_db_prepare_input($HTTP_POST_VARS['gap_id']);
        $products_qty = 1; // tep_db_prepare_input($HTTP_POST_VARS['products_qty']);
        $group_price = tep_db_prepare_input($HTTP_POST_VARS['group_price']);
        tep_db_query("update " . TABLE_GIVE_AWAY_PRODUCTS . " set products_qty = '" . $products_qty . "', shopping_cart_price = '" . $group_price . "' where gap_id = '" . (int)$gap_id . "'");
        tep_redirect(tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gap_id));
        break;
      case 'deleteconfirm':
        $gap_id = tep_db_prepare_input($HTTP_GET_VARS['gapID']);

        tep_db_query("delete from " . TABLE_GIVE_AWAY_PRODUCTS . " where gap_id = '" . (int)$gap_id . "'");
        tep_redirect(tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page']));
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
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?
$header_title_menu = BOX_HEADING_CATALOG;
$header_title_menu_link = tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
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
      <tr><td valign="top">
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($HTTP_GET_VARS['gapID']) ) {
      $form_action = 'update';
      $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, gap.gap_id, gap.shopping_cart_price, gap.products_qty from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_GIVE_AWAY_PRODUCTS . " gap where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = '0' and p.products_id = gap.products_id and gap.gap_id = '" . (int)$HTTP_GET_VARS['gapID'] . "'");
      $product = tep_db_fetch_array($product_query);
      $gapInfo = new objectInfo($product);
      /*echo '<pre>';
      print_r($gapInfo);
      echo '</pre>';*/
    } else {
      $gapInfo = new objectInfo(array());
      $gap_array = array();
      $gap_query = tep_db_query("select distinct(p.products_id), pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = '0' order by pd.products_name");
      $gap_array = array();
      while ($gap = tep_db_fetch_array($gap_query)) {
        $gap_array[] = array('id' => $gap['products_id'], 'text' => $gap['products_name']);
      }
    }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr><form name="new_special" <?php echo 'action="' . tep_href_link(FILENAME_GIVE_AWAY, tep_get_all_get_params(array('action', 'info', 'gapID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('products_id', $HTTP_GET_VARS['gapID']); ?>
        <td height="100%"><br><table border="0" cellspacing="0" cellpadding="2" width="100%">
          <tr>
            <td width="22%" class="main"><nobr><?php echo TEXT_GIVE_AWAY_PRODUCT; ?><nobr>&nbsp;</td>
            <td class="main"><?php echo (isset($gapInfo->products_name)) ? $gapInfo->products_name . ' <small>(' . $currencies->format($gapInfo->products_price) . ')</small>' : tep_draw_pull_down_menu('products_id', $gap_array); echo tep_draw_hidden_field('gap_id', (isset($gapInfo->gap_id) ? $gapInfo->gap_id : '')) . (($action == 'edit') && isset($HTTP_GET_VARS['gapID']) ? tep_draw_hidden_field('products_id', $gapInfo->products_id) : ''); ?></td>
          </tr>
<!-- <?php /* ?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_QTY; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('products_qty', (isset($gapInfo->products_qty) ? $gapInfo->products_qty : '')); ?></td>
          </tr>
<?php */ ?> -->
          <tr>
            <td class="main"><?php echo TEXT_GROUP_GIVE_AWAY_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('group_price', (isset($gapInfo->shopping_cart_price) ? $gapInfo->shopping_cart_price : '')); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><?php //echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['gapID']) ? '&gapID=' . $HTTP_GET_VARS['gapID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
    </table>
<?php
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SHOPPING_CART_PRICE; ?></td>
<!-- <?php /* ?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_QTY; ?></td>
<?php */ ?> -->
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $gap_query_raw = "select p.products_id, pd.products_name, gap.gap_id, gap.products_qty, gap.shopping_cart_price from " . TABLE_PRODUCTS . " p, " . TABLE_GIVE_AWAY_PRODUCTS . " gap, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = '0' and p.products_id = gap.products_id order by pd.products_name";
    $gap_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $gap_query_raw, $gap_query_numrows);
    $gap_query = tep_db_query($gap_query_raw);
    while ($gap = tep_db_fetch_array($gap_query)) {
      if ((!isset($HTTP_GET_VARS['gapID']) || (isset($HTTP_GET_VARS['gapID']) && ($HTTP_GET_VARS['gapID'] == $gap['gap_id']))) && !isset($gapInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$gap['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $gapInfo_array = array_merge($gap, $products);
        $gapInfo = new objectInfo($gapInfo_array);
      }

      if (isset($gapInfo) && is_object($gapInfo) && ($gap['gap_id'] == $gapInfo->gap_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gapInfo->gap_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gap['gap_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $gap['products_name']; ?></td>
                <td  class="dataTableContent" align="right"><?php echo $currencies->format($gap['shopping_cart_price']); ?></td>
<!-- <?php /* ?>
                <td  class="dataTableContent" align="right"><?php echo $gap['products_qty']; ?></td>
<?php */ ?> -->
                <td class="dataTableContent" align="right"><?php if (isset($gapInfo) && is_object($gapInfo) && ($gap['gap_id'] == $gapInfo->gap_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gap['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gap_split->display_count($gap_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $gap_split->display_links($gap_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
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

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => tep_draw_form('gap', FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gapInfo->gap_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $gapInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gapInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($gapInfo)) {
        $heading[] = array('text' => '<b>' . $gapInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gapInfo->gap_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_GIVE_AWAY, 'page=' . $HTTP_GET_VARS['page'] . '&gapID=' . $gapInfo->gap_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($gapInfo->products_image, $gapInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
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
    </table>
  <?
}
?>       </td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
