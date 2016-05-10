<?php
/*
  $Id: specials.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        tep_set_specials_status($HTTP_GET_VARS['id'], $HTTP_GET_VARS['flag']);

        tep_redirect(tep_href_link(FILENAME_SPECIALS, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'sID=' . $HTTP_GET_VARS['id'], 'NONSSL'));
        break;
      case 'insert':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $specials_price = tep_db_prepare_input($HTTP_POST_VARS['specials_price']);
        $expires_date = tep_calendar_rawdate(tep_db_prepare_input($HTTP_POST_VARS['expires_date']));
        
        if (substr($specials_price, -1) == '%') {
          $new_special_insert_query = tep_db_query("select products_id, products_price from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $new_special_insert = tep_db_fetch_array($new_special_insert_query);

          $products_price = $new_special_insert['products_price'];
          $specials_price = ($products_price - (($specials_price / 100) * $products_price));
        }
        
        tep_db_query("insert into " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, expires_date, status) values ('" . (int)$products_id . "', '" . tep_db_input($specials_price) . "', now(), '" . tep_db_input($expires_date) . "', '1')");
        $specials_id = tep_db_insert_id();
// [[ Market prices
        if (USE_MARKET_PRICES == 'True') {
          foreach($currencies->currencies as $key => $value)
          {
            if (substr($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']], -1) == '%')
            {
              $new_special_insert_query = tep_db_query("select products_id, products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$products_id . "' and currencies_id = '" . (int)$currencies->currencies[$key]['id'] ."'");
              $new_special_insert = tep_db_fetch_array($new_special_insert_query);
  
              $products_price = $new_special_insert['products_group_price'];
              $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']] = ($products_price - (($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']] / 100) * $products_price));
            }
          }
  
          $def_cur_price = 0;
          if ($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] != '')
          {
            $def_cur_price = $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']];
          }
          else
          {
            foreach($currencies->currencies as $key => $value)
            {
              if ($HTTP_POST_VARS['specials_new_products_price'][$value['id']] != '')
              {
                $def_cur_price = $HTTP_POST_VARS['specials_new_products_price'][$value['id']] / $value['value'];
                $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] = $def_cur_price;
                break;
              }
            }
          }
          if ($def_cur_price != 0)
          {
            foreach($currencies->currencies as $key => $value)
            {
              if ($HTTP_POST_VARS['specials_new_products_price'][$value['id']] == '')
              {
                $HTTP_POST_VARS['specials_new_products_price'][$value['id']] = $def_cur_price * $value['value'];
              }
            }
          }        
          foreach($currencies->currencies as $key => $value)
          {
            tep_db_query("INSERT INTO " . TABLE_SPECIALS_PRICES . " (specials_id, currencies_id, specials_new_products_price) values ('" . $specials_id . "', '" . $currencies->currencies[$key]['id'] . "', '" . tep_db_prepare_input($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']]) . "')");
            $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
            while ($data = tep_db_fetch_array($data_query))
            {
              $sql_data_array = array('specials_id' => $specials_id,
                                      'specials_new_products_price' => tep_db_prepare_input($HTTP_POST_VARS['specials_new_products_price_' . $data['groups_id']][$currencies->currencies[$key]['id']]), 
                                      'groups_id' => $data['groups_id'], 
                                      'currencies_id' => $currencies->currencies[$key]['id']);
              tep_db_perform(TABLE_SPECIALS_PRICES, $sql_data_array);
            }          
            
          }
        }else{
          $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
          while ($data = tep_db_fetch_array($data_query))
          {
            $sql_data_array = array('specials_id' => $specials_id,
                                    'specials_new_products_price' => tep_db_prepare_input($HTTP_POST_VARS['specials_groups_prices_' . $data['groups_id']]), 
                                    'groups_id' => $data['groups_id'], 
                                    'currencies_id' => '0');
            tep_db_perform(TABLE_SPECIALS_PRICES, $sql_data_array);
          }
        }
// ]] Market prices
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'update':
        $specials_id = tep_db_prepare_input($HTTP_POST_VARS['specials_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $specials_price = tep_db_prepare_input($HTTP_POST_VARS['specials_price']);
        $expires_date = tep_calendar_rawdate(tep_db_prepare_input($HTTP_POST_VARS['expires_date']));

        if (substr($specials_price, -1) == '%') $specials_price = ($products_price - (($specials_price / 100) * $products_price));

        tep_db_query("update " . TABLE_SPECIALS . " set specials_new_products_price = '" . tep_db_input($specials_price) . "', specials_last_modified = now(), expires_date = '" . tep_db_input($expires_date) . "' where specials_id = '" . (int)$specials_id . "'");

// [[ Market prices
        if (USE_MARKET_PRICES == 'True'){
          $data_query = tep_db_query("select products_id from " . TABLE_SPECIALS . " where specials_id = '" . (int)$specials_id ."'");
          $data = tep_db_fetch_array($data_query);
          $products_id = $data['products_id'];
          foreach($currencies->currencies as $key => $value)
          {
  //          if ($key == DEFAULT_CURRENCY) continue;
            if (substr($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']], -1) == '%')
            {
              $new_special_insert_query = tep_db_query("select products_id, products_group_price from " . TABLE_PRODUCTS_PRICES . " where products_id = '" . (int)$products_id . "' and currencies_id = '" . (int)$currencies->currencies[$key]['id'] ."'");
              $new_special_insert = tep_db_fetch_array($new_special_insert_query);
  
              $products_price = $new_special_insert['products_group_price'];
              $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']] = ($products_price - (($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']] / 100) * $products_price));
            }
          }
  
          $def_cur_price = 0;
          if ($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] != '')
          {
            $def_cur_price = $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']];
          }
          else
          {
            foreach($currencies->currencies as $key => $value)
            {
              if ($HTTP_POST_VARS['specials_new_products_price'][$value['id']] != '')
              {
                $def_cur_price = $HTTP_POST_VARS['specials_new_products_price'][$value['id']] / $value['value'];
                $HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[DEFAULT_CURRENCY]['id']] = $def_cur_price;
                break;
              }
            }
          }
          if ($def_cur_price != 0)
          {
            foreach($currencies->currencies as $key => $value)
            {
              if ($HTTP_POST_VARS['specials_new_products_price'][$value['id']] == '')
              {
                $HTTP_POST_VARS['specials_new_products_price'][$value['id']] = $def_cur_price * $value['value'];
              }
            }
          }
          foreach($currencies->currencies as $key => $value)
          {
            $products_prices = tep_db_query("select * from " . TABLE_SPECIALS_PRICES . " WHERE specials_id = '" . $HTTP_POST_VARS['specials_id'] . "' and currencies_id = '" . $currencies->currencies[$key]['id'] . "'");
            $prices = tep_db_fetch_array($products_prices);
            if (empty($prices))
            {
              tep_db_query("insert into " . TABLE_SPECIALS_PRICES . " (specials_id, currencies_id, specials_new_products_price) values ('".$HTTP_POST_VARS['specials_id']."', '" . $currencies->currencies[$key]['id'] . "', '" . tep_db_prepare_input($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']]) . "')");
            }
            else
            {
              tep_db_query("update " . TABLE_SPECIALS_PRICES . " set specials_new_products_price = '" . tep_db_prepare_input($HTTP_POST_VARS['specials_new_products_price'][$currencies->currencies[$key]['id']]) . "' WHERE specials_id = '" . $HTTP_POST_VARS['specials_id'] . "' and currencies_id = '" . $currencies->currencies[$key]['id'] . "'");
            }
            tep_db_query("delete from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . $HTTP_POST_VARS['specials_id'] . "' and currencies_id = '" . $currencies->currencies[$key]['id'] . "' and groups_id != 0");
            $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
            while ($data = tep_db_fetch_array($data_query))
            {
              $sql_data_array = array('specials_id' => $HTTP_POST_VARS['specials_id'],
                                      'specials_new_products_price' => tep_db_prepare_input($HTTP_POST_VARS['specials_new_products_price_' . $data['groups_id']][$currencies->currencies[$key]['id']]), 
                                      'groups_id' => $data['groups_id'], 
                                      'currencies_id' => $currencies->currencies[$key]['id']);
              tep_db_perform(TABLE_SPECIALS_PRICES, $sql_data_array);
            }            
            
          }
        }else{
          tep_db_query("delete from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . $specials_id . "'");
          $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
          while ($data = tep_db_fetch_array($data_query))
          {
            $sql_data_array = array('specials_id' => $specials_id,
                                    'specials_new_products_price' => tep_db_prepare_input($HTTP_POST_VARS['specials_groups_prices_' . $data['groups_id']]), 
                                    'groups_id' => $data['groups_id'], 
                                    'currencies_id' => '0');
            tep_db_perform(TABLE_SPECIALS_PRICES, $sql_data_array);
          }

        }
// ]] Market prices
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials_id));
        break;
      case 'deleteconfirm':
        $specials_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);

        tep_db_query("delete from " . TABLE_SPECIALS . " where specials_id = '" . (int)$specials_id . "'");
// [[
        if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True' ){
          tep_db_query("delete from " . TABLE_SPECIALS_PRICES . " where specials_id = '" . tep_db_input($specials_id) . "'");
        }
// ]]
        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page']));
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
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
if ( ($action == 'new') || ($action == 'edit') ) echo tep_init_calendar(); 

$header_title_menu=BOX_HEADING_CATALOG;
$header_title_menu_link= tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr><td valign="top"  height="100%">
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($HTTP_GET_VARS['sID']) ) {
      $form_action = 'update';

      $product_query = tep_db_query("select p.products_id, s.specials_id, pd.products_name, p.products_price, s.specials_new_products_price, s.expires_date from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 and p.products_id = s.products_id and s.specials_id = '" . (int)$HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);

      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $specials_array = array();
      $specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
      while ($specials = tep_db_fetch_array($specials_query)) {
        $specials_array[] = $specials['products_id'];
      }
    }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr><form name="new_special" <?php echo 'action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('specials_id', $HTTP_GET_VARS['sID']); ?>
        <td height="100%"><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;</td>
            <td class="main"><?php echo (isset($sInfo->products_name)) ? $sInfo->products_name . ' <small>(' . $currencies->format(tep_get_products_price($sInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id'])) . ')</small>' : tep_draw_products_pull_down('products_id', 'style="font-size:10px"', $specials_array); echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : '')); ?></td>
          </tr>
<?php
if (USE_MARKET_PRICES == 'True'){
?>
  <tr><td colspan="2">
    <div class="tab-pane" id="pricesTabPane">
      <script type="text/javascript"><!--
      var pricesTabPane = new WebFXTabPane( document.getElementById( "pricesTabPane" ) );
      //-->
      </script>
<?php
// [[
    foreach ($currencies->currencies as $key => $value)
    {
?>
        <div class="tab-page" id="tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>">
        <h2 class="tab"><?php echo $currencies->currencies[$key]['title']; ?></h2>

        <script type="text/javascript"><!--
        pricesTabPane.addTabPage( document.getElementById( "tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>" ) );
        //-->
        </script>
          <table border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td class="main" nowrap><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('specials_new_products_price[' . $currencies->currencies[$key]['id'] . ']', (($specials_new_products_price[$currencies->currencies[$key]['id']]) ? stripslashes($specials_new_products_price[$currencies->currencies[$key]['id']]) : tep_get_specials_price($sInfo->specials_id, $currencies->currencies[$key]['id'])), 'size="20"'); ?></td>
          </tr>
          <?php
          $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
          while ($data = tep_db_fetch_array($data_query))
          {
          ?>
          <tr>
            <td class="main"><?php echo $data['groups_name']; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('specials_new_products_price_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']', tep_get_specials_price($sInfo->specials_id, $currencies->currencies[$key]['id'], $data['groups_id'], '-2'), 'size="20"'); ?></td>
          </tr>
          <?php
          }
          ?>          
          </table>
        </div>
<?php
    }
// ]]
?>
  </div>
  </td></tr>
<?php
}else{
?>  
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('specials_price', tep_get_specials_price($sInfo->specials_id), 'size="20"'); ?></td>
          </tr>
          <?php
            
          $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
          while ($data = tep_db_fetch_array($data_query))
          {
          ?>
          <tr>
            <td class="main"><?php echo $data['groups_name']; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('specials_groups_prices_' . $data['groups_id'], tep_get_specials_price($sInfo->specials_id, 0, $data['groups_id'], "-2"), 'size="20"'); ?></td>
          </tr>
          <?php
          }
          ?>
<?php
}
?>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main" height="100%"><?php echo tep_draw_calendar( 'new_special', 'expires_date', $sInfo->expires_date ); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['sID']) ? '&sID=' . $HTTP_GET_VARS['sID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
    </table>
<?php
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $specials_query_raw = "select p.products_id, pd.products_name, p.products_price, s.specials_id, s.specials_new_products_price, s.specials_date_added, s.specials_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.affiliate_id = 0 and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id order by pd.products_name";
    $specials_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);
    while ($specials = tep_db_fetch_array($specials_query)) {
      if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$specials['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $sInfo_array = array_merge($specials, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $specials['products_name']; ?></td>
                <td  class="dataTableContent" align="right"><span class="oldPrice"><?php 
                if (USE_MARKET_PRICES == 'True'){
                  echo $currencies->format(tep_get_products_price($specials['products_id'], $currencies->currencies[DEFAULT_CURRENCY]['id']));
                }else{
                  echo $currencies->format($specials['products_price']); 
                }
                ?></span> <span class="specialPrice"><?php 
                if (USE_MARKET_PRICES == 'True'){
                  echo $currencies->format(tep_get_specials_price($specials['specials_id'], $currencies->currencies[DEFAULT_CURRENCY]['id']));
                }else{
                  echo $currencies->format($specials['specials_new_products_price']); 
                }
                ?></span></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($specials['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
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

      $contents = array('form' => tep_draw_form('specials', FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->specials_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->specials_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        if (USE_MARKET_PRICES == 'True'){
          $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format(tep_get_products_price($sInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id'])));
          $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format(tep_get_specials_price($sInfo->specials_id, $currencies->currencies[DEFAULT_CURRENCY]['id'])));
          if (tep_get_products_price($sInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id']) == 0){
            $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' 100%');
          }else{
            $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (tep_get_specials_price($sInfo->specials_id, $currencies->currencies[DEFAULT_CURRENCY]['id']) / tep_get_products_price($sInfo->products_id, $currencies->currencies[DEFAULT_CURRENCY]['id'])) * 100) . '%');
          }
        }else{
          $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($sInfo->products_price));
          $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($sInfo->specials_new_products_price));
          if ($sInfo->products_price <= 0){
            $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' 100%');
          }else{
            $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / $sInfo->products_price) * 100)) . '%');
          }
        }

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->expires_date) . '</b>');
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
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
