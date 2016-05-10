<?php
/*
  $Id: products_attributes.php,v 1.3 2005/12/04 00:58:29 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  if ($HTTP_GET_VARS['pav_action']) {
    switch ($HTTP_GET_VARS['pav_action']) {
      case 'save':
        for($i=0, $n = sizeof($languages); $i < $n; $i++) {
          $value = tep_db_prepare_input($HTTP_POST_VARS['option_value'][$languages[$i]['id']]);
          $check = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages[$i]['id']. "' and products_options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
          if (tep_db_num_rows($check)) {
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value) . "' where language_id = '" . (int)$languages[$i]['id']. "' and products_options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
          } else {
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value) . "', language_id = '" . (int)$languages[$i]['id']. "', products_options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('pav_action'))));
        break;
      case 'delete':
        $checkQuery = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
        while ($checkData = tep_db_fetch_array($checkQuery)) {
          if (USE_MARKET_PRICES == 'True') {
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . (int)$checkData['products_attributes_id'] . "'");
          }
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$HTTP_GET_VARS['pavID'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('pav_action', 'pavID', 'page'))));
        break;
      case 'new':
        $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
        $max_values_id_values = tep_db_fetch_array($max_values_id_query);
        $next_id = $max_values_id_values['next_id'];
        if ( !($next_id > 0) ) $next_id = 1;
        for($i=0, $n = sizeof($languages); $i < $n; $i++) {
          $value = tep_db_prepare_input($HTTP_POST_VARS['option_value'][$languages[$i]['id']]);
          $check = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages[$i]['id']. "' and products_options_values_id = '" . $next_id . "'");
          if (tep_db_num_rows($check)) {
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value) . "' where language_id = '" . (int)$languages[$i]['id']. "' and products_options_values_id = '" . $next_id . "'");
          } else {
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . tep_db_input($value) . "', language_id = '" . (int)$languages[$i]['id']. "', products_options_values_id = '" . $next_id . "'");
          }
        }
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_values_id = '" . (int)$next_id . "', products_options_id = '" . $HTTP_GET_VARS['paID'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('pav_action', 'pavID', 'page'))));
        break;
    }
  }
  if ($HTTP_GET_VARS['action']) {
    switch($HTTP_GET_VARS['action']) {
      case 'save':
        for($i=0, $n = sizeof($languages); $i < $n; $i++) {
          $value = tep_db_prepare_input($HTTP_POST_VARS['option_name'][$languages[$i]['id']]);
          $check = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages[$i]['id']. "' and products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
          if (tep_db_num_rows($check)) {
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($value) . "', products_options_sort_order = '" . $HTTP_POST_VARS['option_sort_order'][$languages[$i]['id']] . "' where language_id = '" . (int)$languages[$i]['id']. "' and products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
          } else {
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($value) . "', language_id = '" . (int)$languages[$i]['id']. "', products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "', products_options_sort_order = '" . $HTTP_POST_VARS['option_sort_order'][$languages[$i]['id']] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('action'))));  
        break;
      case 'create':
        $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
        $max_options_id_values = tep_db_fetch_array($max_options_id_query);
        $next_id = $max_options_id_values['next_id'];
        if ( !($next_id > 0) ) $next_id = 1;
        for($i=0, $n = sizeof($languages); $i < $n; $i++) {
          $value = tep_db_prepare_input($HTTP_POST_VARS['option_name'][$languages[$i]['id']]);
          $check = tep_db_query('select * from ' . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages[$i]['id']. "' and products_options_id = '" . (int)$next_id . "'");
          if (tep_db_num_rows($check)) {
            tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($value) . "', products_options_sort_order = '" . $HTTP_POST_VARS['option_sort_order'][$languages[$i]['id']] . "' where language_id = '" . (int)$languages[$i]['id']. "' and products_options_id = '" . (int)$next_id . "'");
          } else {
            tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($value) . "', language_id = '" . (int)$languages[$i]['id']. "', products_options_id = '" . (int)$next_id . "', products_options_sort_order = '" . $HTTP_POST_VARS['option_sort_order'][$languages[$i]['id']] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('action', 'paID')) . 'paID=' . $next_id));
        break;
      case 'delete':
        $checkQuery = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
        while ($checkData = tep_db_fetch_array($checkQuery)) {
          if (USE_MARKET_PRICES == 'True') {
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . (int)$checkData['products_attributes_id'] . "'");
          }
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
        
        $checkQuery = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
        while($checkData = tep_db_fetch_array($checkQuery)) {
          tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $checkData['products_options_values_id'] . "'");
          
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$HTTP_GET_VARS['paID'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, tep_get_all_get_params(array('action', 'paID'))));
    
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
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($HTTP_GET_VARS['option_page'] ? $HTTP_GET_VARS['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'selected_box=catalog');
  $header_title_submenu=HEADING_TITLE;
?>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<!-- test // -->
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
    <td width="100%" valign="top" height=100%>
    
<?php
  if (tep_not_null($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action'] == 'list') {
    //Product options values list
 ?>
    
  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%" valign=top>
    <tr valign=top >
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0" height=100%>
        <tr>
          <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="dataTableHeadingRow">
               <td class="dataTableHeadingContent" width="100%"><?php echo TABLE_HEADING_OPT_NAME;?></td>
               <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTION;?></td>
             </tr>
  <?php
    $Qvalues_raw = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . $languages_id . "' and pov2po.products_options_id = '" . $HTTP_GET_VARS['paID'] . "' order by pov.products_options_values_id";
    $values_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $Qvalues_raw, $values_query_numrows, 'pov.products_options_values_id');
    $Qvalues = tep_db_query($Qvalues_raw);
    while ($Dvalues = tep_db_fetch_array($Qvalues)) {
      if ((!isset($HTTP_GET_VARS['pavID']) || (isset($HTTP_GET_VARS['pavID']) && $HTTP_GET_VARS['pavID'] == $Dvalues['products_options_values_id'])) && !isset($Ivalues) && (substr($action, 0, 3) != 'new'))  {
        $Ivalues = new objectInfo($Dvalues);
      }
      if (isset($Ivalues) && is_object($Ivalues) && $Dvalues['products_options_values_id'] == $Ivalues->products_options_values_id) {
        echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Dvalues['products_options_values_id'] . '&pav_action=edit&page=' . $HTTP_GET_VARS['page'] ) . '\'">' . "\n";
      } else {
        echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Dvalues['products_options_values_id'] . '&page=' . $HTTP_GET_VARS['page'] ) . '\'">' . "\n";
      }
      ?>
      <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Dvalues['products_options_values_id'] . '&pav_action=edit&page=' . $HTTP_GET_VARS['page'] ) . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;<b>' . htmlspecialchars($Dvalues['products_options_values_name']) . '</b>'; ?></td>
      <td class="dataTableContent" align="center">
      <?php if (isset($Ivalues) && is_object($Ivalues) && ($Dvalues['products_options_values_id'] == $Ivalues->products_options_values_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Dvalues['products_options_values_id'] . '&pav_action=edit&page=' . $HTTP_GET_VARS['page'] ) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
      </td>
      <?php
      echo "</tr>\n";
    }
  ?>
                <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $values_split->display_count($values_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_VALUES); ?></td>
                    <td class="smallText" align="right"><?php echo $values_split->display_links($values_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'pavID'))); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID']. '&page=' . $HTTP_GET_VARS['page'] ) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pav_action=new_option_value&pavID=' . $Ivalues->products_options_values_id . '&page=' . $HTTP_GET_VARS['page'] ) . '">' . tep_image_button('button_new.gif', IMAGE_NEW) . '</a>'; ?></td>
                  </tr>
          </table></td>
     

          </tr>
      </table></td>
<?php
  function tep_count_products_options_values($option_value) {
    $checkData = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " where options_values_id = '" . $option_value. "'"));
    return $checkData['total'];
  }
  function tep_options_value_value($option_value, $languages_id) {
    $checkData = tep_db_fetch_array(tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $option_value . "' and language_id = '" . $languages_id . "'"));
    return $checkData['products_options_values_name'];
  }
  $heading = array();
  $contents = array();
  if (tep_not_null($HTTP_GET_VARS['pav_action'])) {
    switch($HTTP_GET_VARS['pav_action']) {
      case 'edit':
        $heading[] = array('text' => TEXT_OPTION_VALUE_EDIT_HEADING);
        $contents = array('form' => tep_draw_form('option_values', FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&pav_action=save&page=' . $HTTP_GET_VARS['page'], 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br>' . TEXT_OPTION_VALUE_EDIT_INTRO);
        $languages = tep_get_languages();
        $values_inputs_string = '';
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $values_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_value[' . $languages[$i]['id'] . ']', tep_options_value_value($Ivalues->products_options_values_id, $languages[$i]['id']));
        }
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_VALUE . ' ' . $values_inputs_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id) . '&page=' . $HTTP_GET_VARS['page'] . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_confirm':
        $heading[] = array('text' => TEXT_OPTION_VALUE_DELETE_HEADING);
        $contents[] = array('text' => '<br>' . TEXT_OPTION_VALUE_DELETE_INTRO);

        if ($num_products = tep_count_products_options_values($Ivalues->products_options_values_id) > 0)
          $contents[] = array('text' => '<br>' . sprintf(TEXT_OPTION_VALUE_DELETE_NOTICE, $num_products));
        $contents[] = array('text' => '<br>' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&pav_action=delete&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' . '&nbsp;' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'new_option_value':
        $heading[] = array('text' => TEXT_OPTION_VALUE_NEW_HEADING);
        $contents = array('form' => tep_draw_form('option_values', FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&pav_action=new', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br>' . TEXT_OPTION_VALUE_NEW_INTRO);
        $languages = tep_get_languages();
        $values_inputs_string = '';
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $values_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_value[' . $languages[$i]['id'] . ']', '');
        }
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_VALUE . ' ' . $values_inputs_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');      
        break;
    }
  }elseif (isset($Ivalues) && is_object($Ivalues)) {
    $heading[] = array('text' => $Ivalues->products_options_values_name);
    $contents[] = array('text' => '<br>' . sprintf(TEXT_OPTION_VALUE_NOTICE, tep_count_products_options_values($Ivalues->products_options_values_id)));
    $contents[] = array('text' => '<br>' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&pav_action=edit&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>' . '&nbsp;' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $HTTP_GET_VARS['paID'] . '&action=list&pavID=' . $Ivalues->products_options_values_id . '&pav_action=delete_confirm&page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
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
  <?php    
} else {
    //Products options list
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%" valign=top>
    <tr valign=top height=100%>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
        <tr height="100%">
          <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr class="dataTableHeadingRow">
               <td class="dataTableHeadingContent"><?php echo TEXT_OPTION_NAME;?></td>
               <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPTION_SORT_ORDER;?></td>
               <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION;?></td>
             </tr>
  <?php
    $Qgroups = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
    while ($Dgroups = tep_db_fetch_array($Qgroups)) {
      if ((!isset($HTTP_GET_VARS['paID']) || (isset($HTTP_GET_VARS['paID']) && $HTTP_GET_VARS['paID'] == $Dgroups['products_options_id'])) && !isset($Igroups) )  {
        $Igroups = new objectInfo($Dgroups);
      }
      if (isset($Igroups) && is_object($Igroups) && $Dgroups['products_options_id'] == $Igroups->products_options_id) {
        echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Dgroups["products_options_id"] . '&action=list') . '\'">' . "\n";
      } else {
        echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Dgroups["products_options_id"]) . '\'">' . "\n";
      }
      ?>
      <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Dgroups["products_options_id"] . '&action=list') . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . htmlspecialchars($Dgroups['products_options_name']) . '</b>'; ?></td>
      <td class="dataTableContent"><?php echo $Dgroups['products_options_sort_order'];?></td>
      <td class="dataTableContent" align="right">
      <?php if (isset($Igroups) && is_object($Igroups) && ($Dgroups['products_options_id'] == $Igroups->products_options_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Dgroups["products_options_id"]) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
      </td>
      <?php
      echo "</tr>\n";
    }
  ?>
                  <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Igroups->products_options_id . '&action=new_option') . '">' . tep_image_button('button_new.gif', IMAGE_NEW) . '</a>'; ?></td>
                  </tr>
          </table></td>
          </tr>
          </table>
          </td>
        
<?php

  function tep_options_value_name($products_options_id, $languages_id) {
    $checkData = tep_db_fetch_array(tep_db_query("select products_options_name, products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $products_options_id . "' and  language_id = '" . $languages_id . "'"));
    return $checkData['products_options_name'];
  }
  function tep_options_value_sort_order($products_options_id, $languages_id) {
    $checkData = tep_db_fetch_array(tep_db_query("select products_options_name, products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $products_options_id . "' and  language_id = '" . $languages_id . "'"));
    return $checkData['products_options_sort_order'];
  }
  function tep_count_products_options($products_options_id) {
    $checkData = tep_db_fetch_array(tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_ATTRIBUTES . " where options_id = '" . $products_options_id . "'"));
    return $checkData['total'];
  }
  function tep_count_options_values($products_options_id) {
    $checkData = tep_db_fetch_array(tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $products_options_id . "'"));
    return $checkData['total'];
  }

  $heading = array();
  $contents = array();
  if (tep_not_null($HTTP_GET_VARS['action'])) {
    switch($HTTP_GET_VARS['action']) {
      case 'edit':
        $heading[] = array('text' => TEXT_OPTION_EDIT_HEADING);
        $contents = array('form' => tep_draw_form('option_values', FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Igroups->products_options_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br>' . TEXT_OPTION_EDIT_INTRO);
        $languages = tep_get_languages();
        $values_inputs_string = '';
        $sort_order_string = '';
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $values_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_name[' . $languages[$i]['id'] . ']', tep_options_value_name($Igroups->products_options_id, $languages[$i]['id']));
          $sort_order_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_sort_order[' . $languages[$i]['id'] . ']', tep_options_value_sort_order($Igroups->products_options_id, $languages[$i]['id']));
        }
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_NAME . ' ' . $values_inputs_string);
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_SORT_ORDER . ' ' . $sort_order_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_confirm':
        $heading[] = array('text' => TEXT_OPTION_DELETE_HEADING);
        $contents[] = array('text' => '<br>' . TEXT_OPTION_DELETE_INTRO);

        
        $contents[] = array('text' => '<br>' . sprintf(TEXT_OPTION_DELETE_NOTICE, tep_count_products_options($Igroups->products_options_id), tep_count_options_values($Igroups->products_options_id)));
        $contents[] = array('text' => '<br>' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' . '&nbsp;' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'new_option':
        $heading[] = array('text' => TEXT_OPTION_NEW_HEADING);
        $contents = array('form' => tep_draw_form('option_values', FILENAME_PRODUCTS_ATTRIBUTES, 'paID=' . $Igroups->products_options_id . '&action=create', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br>' . TEXT_OPTION_NEW_INTRO);
        $languages = tep_get_languages();
        $values_inputs_string = '';
        $sort_order_string = '';
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $values_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_name[' . $languages[$i]['id'] . ']', '');
          $sort_order_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('option_sort_order[' . $languages[$i]['id'] . ']', '');
        }
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_NAME . ' ' . $values_inputs_string);
        $contents[] = array('text' => '<br>' . TABLE_HEADING_OPT_SORT_ORDER . ' ' . $sort_order_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      
        break;
    }
  }elseif (isset($Igroups) && is_object($Igroups)) {
    $heading[] = array('text' => $Igroups->products_options_name);
    $contents[] = array('text' => '<br>' . sprintf(TEXT_OPTION_NOTICE, tep_count_products_options($Igroups->products_options_id), tep_count_options_values($Igroups->products_options_id)));
    $contents[] = array('text' => '<br>' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>' . '&nbsp;' . '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES,  'paID=' . $Igroups->products_options_id . '&action=delete_confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif" heght="100%">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }  
?>      
    </tr>
  </table>
  <?php
  }
?>     
</td>    
</tr>
</table>
</td>    
</tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
