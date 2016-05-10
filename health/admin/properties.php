<?php
/*
  $Id: properties.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  function tep_get_properties_name($properties_id, $language_id){
    $data =  tep_db_fetch_array(tep_db_query("select properties_name from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id = ". (int)$properties_id . " and language_id = " . $language_id));
    return $data['properties_name'];
  }

  function tep_get_properties_description($properties_id, $language_id){
    $data =  tep_db_fetch_array(tep_db_query("select properties_description from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id = ". (int)$properties_id . " and language_id = " . $language_id));
    return $data['properties_description'];
  }

  function tep_get_properties_category_name($categories_id, $language_id){
    $data =  tep_db_fetch_array(tep_db_query("select categories_name from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " where categories_id = ". (int)$categories_id . " and language_id = " . $language_id));
    return $data['categories_name'];
  }

  function tep_get_properties_category_description($categories_id, $language_id){
    $data =  tep_db_fetch_array(tep_db_query("select categories_description from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " where categories_id = ". (int)$categories_id . " and language_id = " . $language_id));
    return $data['categories_description'];
  }

  function tep_properties_in_category_count($categories_id){
    $data = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where categories_id = " . (int)$categories_id ));
    return $data['total'];
  }

  function tep_get_properties_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_PROPERTIES_CATEGORIES . " c, " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = tep_get_properties_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }
    return $category_tree_array;
  }

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert_category':
      case 'update_category':
        if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

//        $categories_status = tep_db_prepare_input($HTTP_POST_VARS['categories_status']);
//        $sql_data_array = array('sort_order' => $sort_order, 'categories_status' => $categories_status);
          $sql_data_array = array('sort_order' => $sort_order);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_PROPERTIES_CATEGORIES, $sql_data_array);

          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_PROPERTIES_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $categories_name_array = $HTTP_POST_VARS['categories_name'];
          $categories_description_array = $HTTP_POST_VARS['categories_description'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]), 'categories_description' => tep_db_prepare_input($categories_description_array[$language_id]));

          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PROPERTIES_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_PROPERTIES_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $properties = array();
          $properties_delete = array();

          $properties_ids_query = tep_db_query("select properties_id from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
          
          while ($properties_ids = tep_db_fetch_array($properties_ids_query)) {
            $properties[] = $properties_ids['properties_id'];
          }

          reset($properties);
          while (list($key, $value) = each($properties)) {
            tep_db_query("delete from " . TABLE_PROPERTIES . " where properties_id =" . $value);
            tep_db_query("delete from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id =" . $value);
            tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where properties_id =" . $value);
          }
          tep_db_query("delete from " . TABLE_PROPERTIES_CATEGORIES . " where categories_id = " .$categories_id);
          tep_db_query("delete from " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " where categories_id = " .$categories_id);
          tep_db_query("delete from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where categories_id = " .$categories_id);
        }

        tep_redirect(tep_href_link(FILENAME_PROPERTIES));
        break;
      case 'delete_property_confirm':
        if (isset($HTTP_POST_VARS['properties_id'])) {
          $properties_id = tep_db_prepare_input($HTTP_POST_VARS['properties_id']);
          tep_db_query("delete from " . TABLE_PROPERTIES . " where properties_id =" . $properties_id);
          tep_db_query("delete from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id =" . $properties_id);
          tep_db_query("delete from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where properties_id =" . $properties_id);
          tep_db_query("delete from " . TABLE_PROPERTIES_TO_PRODUCTS . " where properties_id =" . $properties_id);

        }

        tep_redirect(tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath));
        break;
      case 'move_property_confirm':
        $properties_id = tep_db_prepare_input($HTTP_POST_VARS['properties_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $query = tep_db_query("select * from " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " where properties_id = '" . (int)$properties_id . "' and categories_id = '" . (int)$current_category_id . "'");
        if (tep_db_num_rows($query) > 0){
          tep_db_query("update " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where properties_id = '" . (int)$properties_id . "' and categories_id = '" . (int)$current_category_id . "'");
        }else{
          tep_db_query("insert into " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " (properties_id, categories_id) values ('" . (int)$properties_id . "' ,'" . (int)$new_parent_id . "')");
        }

        tep_redirect(tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $new_parent_id . '&pID=' . $properties_id));
        break;
      case 'insert_property':
      case 'update_property':
        if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
          $action = 'new_property';
        } else {
          if (isset($HTTP_GET_VARS['pID'])) $properties_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
          
          if ($HTTP_POST_VARS['properties_type'] == 5 || $HTTP_POST_VARS['properties_type'] == 6){
            $HTTP_POST_VARS['additional_info'] = 1;
          }
          if ($HTTP_POST_VARS['additional_info'] == 'on'){
            $HTTP_POST_VARS['additional_info'] = 1;
          }

          $sql_data_array = array('properties_type' => tep_db_prepare_input($HTTP_POST_VARS['properties_type']),
                                  'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['sort_order']),
                                  'mode' => tep_db_prepare_input($HTTP_POST_VARS['mode']),
                                  'additional_info' => tep_db_prepare_input($HTTP_POST_VARS['additional_info'])
                                  );

          if ($action == 'insert_property') {

//            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PROPERTIES, $sql_data_array);
            $properties_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " (properties_id, categories_id) values ('" . (int)$properties_id . "', '" . (int)$current_category_id . "')");
          } elseif ($action == 'update_property') {

            tep_db_perform(TABLE_PROPERTIES, $sql_data_array, 'update', "properties_id = '" . (int)$properties_id . "'");

          }

          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = array('properties_name' => tep_db_prepare_input($HTTP_POST_VARS['properties_name'][$language_id]),
                                    'properties_description' => tep_db_prepare_input($HTTP_POST_VARS['properties_description'][$language_id]),
                                    'possible_values' => tep_db_prepare_input($HTTP_POST_VARS['possible_values'][$language_id]));

            if ($action == 'insert_property') {
              $insert_sql_data = array('properties_id' => $properties_id,
                                       'language_id' => $language_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PROPERTIES_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_property') {
              tep_db_perform(TABLE_PROPERTIES_DESCRIPTION, $sql_data_array, 'update', "properties_id = '" . (int)$properties_id . "' and language_id = '" . (int)$language_id . "'");
            }
          }

          tep_redirect(tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $properties_id));
        }
        break;

      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['properties_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $properties_id = tep_db_prepare_input($HTTP_POST_VARS['properties_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $property_query = tep_db_query("select sort_order, properties_type, mode from " . TABLE_PROPERTIES . " where properties_id = '" . (int)$properties_id . "'");
          $property = tep_db_fetch_array($property_query);

            tep_db_query("insert into " . TABLE_PROPERTIES . " (sort_order, properties_type, mode) values ('" . tep_db_input($property['sort_order']) . "', '" . tep_db_input($property['properties_type']) . "', '" . tep_db_input($property['mode']). "')");
            $dup_property_id = tep_db_insert_id();

            $description_query = tep_db_query("select language_id, properties_name, properties_description from " . TABLE_PROPERTIES_DESCRIPTION . " where properties_id = '" . (int)$properties_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PROPERTIES_DESCRIPTION . " (properties_id, language_id, properties_name, properties_description) values ('" . (int)$dup_property_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['properties_name']) . "', '" . tep_db_input($description['properties_description']) . "')");
            }

            tep_db_query("insert into " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " (properties_id, categories_id) values ('" . (int)$dup_property_id . "', '" . (int)$categories_id . "')");
            $properties_id = $dup_property_id;

        }

        tep_redirect(tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $categories_id . '&pID=' . $properties_id));
        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?

{
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_PROPERTIES, 'selected_box=catalog');
  $header_title_submenu=HEADING_TITLE;
  if($action != 'new_property'){
    $header_title_additional=tep_draw_form('search', FILENAME_PROPERTIES, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search').'</form>';
  }
}
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
    <td width="100%" valign="top">
<?php
  if ($action == 'new_property') {
    $parameters = array('properties_name' => '',
                       'properties_description' => '',
                       'properties_id' => '',
                       'sort_order' => '',
                       'mode' => '',
                       'properties_type' => '',
                       'additional_info' => '',
                       'possible_values' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {

      $properties_query = tep_db_query("select pd.properties_name, pd.properties_description, p.properties_id, p.properties_type, p.sort_order, p.mode, p.additional_info, pd.possible_values from " . TABLE_PROPERTIES . " p, " . TABLE_PROPERTIES_DESCRIPTION . " pd where p.properties_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.properties_id = pd.properties_id and pd.language_id = '" . (int)$languages_id . "'");

      $property = tep_db_fetch_array($properties_query);

      $pInfo->objectInfo($property);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $properties_name = $HTTP_POST_VARS['properties_name'];
      $properties_description = $HTTP_POST_VARS['properties_description'];
      $possible_values = $HTTP_POST_VARS['possible_values'];
    }

    $languages = tep_get_languages();
    
    $properties_types = array(array('id' => 0, 'text' => TEXT_SHORT_TEXT),
                              array('id' => 1, 'text' => TEXT_LONG_TEXT),
                              array('id' => 2, 'text' => TEXT_TRUE_FALSE),
                              array('id' => 3, 'text' => TEXT_CHECKBOXES), 
                              array('id' => 4, 'text' => TEXT_RADIO_BUTTONS),
                              array('id' => 5, 'text' => TEXT_FILE),
                              array('id' => 6, 'text' => TEXT_IMAGE));
    if (!isset($pInfo->additional_info)) $pInfo->additional_info = '0';
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
    <?php echo tep_draw_form('new_property', FILENAME_PROPERTIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] . '&action=update_property' : '&action=insert_property') , 'post', 'enctype="multipart/form-data"'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PROPERTY, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PROPERTIES_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('properties_type', $properties_types, $pInfo->properties_type); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PROPERTIES_ADDITIONAL_INFO; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_checkbox_field('additional_info', '', $pInfo->additional_info); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>           
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PROPERTIES_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('properties_name[' . $languages[$i]['id'] . ']', (isset($properties_name[$languages[$i]['id']]) ? $properties_name[$languages[$i]['id']] : tep_get_properties_name($pInfo->properties_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PROPERTIES_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('properties_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($properties_description[$languages[$i]['id']]) ? stripslashes($properties_description[$languages[$i]['id']]) : stripslashes(tep_get_properties_description($pInfo->properties_id, $languages[$i]['id'])))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
  
          <tr>
            <td class="main"><?php echo TEXT_PROPERTIES_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $pInfo->sort_order); ?></td>
          </tr>          
          <tr>
            <td class="main"><?php echo TEXT_PROPERTIES_MODE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('mode', $pInfo->mode); ?></td>
          </tr>          
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>          

<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PROPERTIES_POSSIBLE_VALUES; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('possible_values[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($possible_values[$languages[$i]['id']]) ? stripslashes($possible_values[$languages[$i]['id']]) : stripslashes(tep_get_properties_possible_values($pInfo->properties_id, $languages[$i]['id'])))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>          
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
       </table>
       </td>
       </tr>

      <tr>
        <td class="main" align="right" ><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
<?php
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PROPERTIES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.sort_order from " . TABLE_PROPERTIES_CATEGORIES . " c, " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.categories_name like '%" . $HTTP_GET_VARS['search'] . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id, c.sort_order from " . TABLE_PROPERTIES_CATEGORIES . " c, " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
    }
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
//        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_properties = array('properties_count' => tep_properties_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_properties);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROPERTIES, "cPath="  .$categories['categories_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, "cPath=".$categories['categories_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>

<?php
    }

    $properties_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $properties_query = tep_db_query("select p.properties_id, pd.properties_name from " . TABLE_PROPERTIES . " p, " . TABLE_PROPERTIES_DESCRIPTION . " pd, " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2c where p.properties_id = pd.properties_id and pd.language_id = '" . (int)$languages_id . "' and p.properties_id = p2c.properties_id and pd.properties_name like '%" . tep_db_input($search) . "%' order by pd.properties_name");
    } else {
      $properties_query = tep_db_query("select p.properties_id, pd.properties_name from " . TABLE_PROPERTIES . " p, " . TABLE_PROPERTIES_DESCRIPTION . " pd, " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2c where p.properties_id = pd.properties_id and pd.language_id = '" . (int)$languages_id . "' and p.properties_id = p2c.properties_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.properties_name");
    }
    while ($properties = tep_db_fetch_array($properties_query)) {
      $properties_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $properties['categories_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $properties['properties_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        //$pInfo_array = array_merge($properties);
        $pInfo = new objectInfo($properties);
      }

      if (isset($pInfo) && is_object($pInfo) && ($properties['properties_id'] == $pInfo->properties_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $properties['properties_id'] . '&action=new_property') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $properties['properties_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $properties['properties_id'] . '&action=new_property') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $properties['properties_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($properties['properties_id'] == $pInfo->properties_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $properties['properties_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PROPERTIES . '&nbsp;' . $properties_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search']) && (!isset($HTTP_GET_VARS['cPath']) || $HTTP_GET_VARS['cPath'] == '')) echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;';
echo '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&action=new_property') . '">' . tep_image_button('button_new.gif', IMAGE_NEW_PROPERTY) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_PROPERTIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }
        $languages = tep_get_languages();
        $category_description_string = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', 20, 5);
        }
        
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_DESCRIPTION . $category_description_string);
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_PROPERTIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_properties_category_name($cInfo->categories_id, $languages[$i]['id']));
        }
        $category_description_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_description_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', 20, 5, tep_get_properties_category_description($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_DESCRIPTION . $category_description_string);
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_PROPERTIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->properties_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PROPERTIES, $cInfo->properties_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_property':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PROPERTY . '</b>');

        $contents = array('form' => tep_draw_form('properties', FILENAME_PROPERTIES, 'action=delete_property_confirm&cPath=' . $cPath) . tep_draw_hidden_field('properties_id', $pInfo->properties_id));
        $contents[] = array('text' => TEXT_DELETE_PROPERTY_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->properties_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $pInfo->properties_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_property':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PROPERTY . '</b>');

        $contents = array('form' => tep_draw_form('properties', FILENAME_PROPERTIES, 'action=move_property_confirm&cPath=' . $cPath) . tep_draw_hidden_field('properties_id', $pInfo->properties_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PROPERTY_INTRO, $pInfo->properties_name));
/*
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
*/
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->properties_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_properties_category_tree(), $current_category_id));

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $pInfo->properties_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_PROPERTIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('properties_id', $pInfo->properties_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
/*
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
*/
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_properties_category_tree(), $current_category_id));
/*
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
*/
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' . $pInfo->properties_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> ');
            $contents[] = array('text' => '<br>' . TEXT_PROPERTIES . ' ' . $cInfo->properties_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_properties_name($pInfo->properties_id, $languages_id) . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' .
tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' .
$pInfo->properties_id . '&action=new_property') . '">' .
tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' .
tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' .
$pInfo->properties_id . '&action=delete_property') . '">' .
tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' .
tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' .
$pInfo->properties_id . '&action=move_property') . '">' .
tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' .
tep_href_link(FILENAME_PROPERTIES, 'cPath=' . $cPath . '&pID=' .
$pInfo->properties_id . '&action=copy_to') . '">' .
tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PROPERTIES);
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
    </table>
<?php
  }
?>
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
