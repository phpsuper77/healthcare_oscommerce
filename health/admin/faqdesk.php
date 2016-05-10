<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');

if ($HTTP_GET_VARS['action']) {
  switch ($HTTP_GET_VARS['action']) {
    case 'setflag':
    if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
      if ($HTTP_GET_VARS['pID']) {
        faqdesk_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
      }
      if ($HTTP_GET_VARS['cID']) {
        faqdesk_set_categories_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('faqdesk');
      }
    }

    // -----------------------------------------------------------------------
    // sticky call area ... you know the green/red lights
    // -----------------------------------------------------------------------
    case 'setflag_sticky':
    // -----------------------------------------------------------------------
    if ( ($HTTP_GET_VARS['flag_sticky'] == '0') || ($HTTP_GET_VARS['flag_sticky'] == '1') ) {
      if ($HTTP_GET_VARS['pID']) {
        faqdesk_set_product_sticky($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag_sticky']);
      }

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('faqdesk');
      }
    }


    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $HTTP_GET_VARS['cPath']));
    break;

    case 'insert_category':
    case 'update_category':
    //  double call codes ... all in one mentality ???
    //
    $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
    $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

    //$sql_data_array = array('sort_order' => $sort_order);
    $catagory_status = tep_db_prepare_input($HTTP_POST_VARS['catagory_status']);
    $sql_data_array = array('sort_order' => $sort_order, 'catagory_status' => $catagory_status);

    if ($HTTP_GET_VARS['action'] == 'insert_category') {
      $insert_sql_data = array(
      'parent_id' => $current_category_id,
      'date_added' => 'now()'
      );
      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
      tep_db_perform(TABLE_FAQDESK_CATEGORIES, $sql_data_array);
      $categories_id = tep_db_insert_id();
    } elseif ($HTTP_GET_VARS['action'] == 'update_category') {
      $update_sql_data = array('last_modified' => 'now()');
      $sql_data_array = array_merge($sql_data_array, $update_sql_data);
      tep_db_perform(TABLE_FAQDESK_CATEGORIES, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
    }  // top if closing bracket

    $languages = tep_get_languages();
    $categories_name_array = $HTTP_POST_VARS['categories_name'];
    $categories_description_array = $HTTP_POST_VARS['categories_description'];
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $language_id = $languages[$i]['id'];
      $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
      'categories_description' => tep_db_prepare_input($categories_description_array[$language_id]));
      if ($HTTP_GET_VARS['action'] == 'insert_category') {
        $insert_sql_data = array(
        'categories_id' => $categories_id,
        'language_id' => $language_id
        );
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_FAQDESK_CATEGORIES_DESCRIPTION, $sql_data_array);
      } elseif ($HTTP_GET_VARS['action'] == 'update_category') {
        tep_db_perform(TABLE_FAQDESK_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\' and language_id = \'' . $language_id . '\'');
      }
    }

    $categories_image = tep_get_uploaded_file('categories_image');
    $image_directory = tep_get_local_path(DIR_FS_CATALOG_IMAGES);

    if (is_uploaded_file($categories_image['tmp_name'])) {
      tep_db_query("update " . TABLE_FAQDESK_CATEGORIES . " set categories_image = '" . $categories_image['name'] . "' where categories_id = '" . tep_db_input($categories_id) . "'");
      tep_copy_uploaded_file($categories_image, $image_directory);
    }


    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $categories_id));
    break;

    case 'delete_category_confirm':
    if ($HTTP_POST_VARS['categories_id']) {
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

      $categories = faqdesk_get_category_tree($categories_id, '', '0', '', true);
      $products = array();
      $products_delete = array();

      for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
        $product_ids_query = tep_db_query("select faqdesk_id from " . TABLE_FAQDESK_TO_CATEGORIES . " where categories_id = '" . $categories[$i]['id'] . "'");
        while ($product_ids = tep_db_fetch_array($product_ids_query)) {
          $products[$product_ids['faqdesk_id']]['categories'][] = $categories[$i]['id'];
        }
      }

      reset($products);
      while (list($key, $value) = each($products)) {
        $category_ids = '';
        for ($i = 0, $n = sizeof($value['categories']); $i < $n; $i++) {
          $category_ids .= '\'' . $value['categories'][$i] . '\', ';
        }
        $category_ids = substr($category_ids, 0, -2);

        $check_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . $key . "' and categories_id not in (" . $category_ids . ")");
        $check = tep_db_fetch_array($check_query);
        if ($check['total'] < '1') {
          $products_delete[$key] = $key;
        }
      }

      // Removing categories can be a lengthy process
      tep_set_time_limit(0);
      for ($i = 0, $n = sizeof($categories); $i < $n; $i++) {
        faqdesk_remove_category($categories[$i]['id']);
      }

      reset($products_delete);
      while (list($key) = each($products_delete)) {
        faqdesk_remove_product($key);
      }

    }  // main if closing bracket

    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath));
    break;

    // --------------------------------------------------------
    case 'delete_product_confirm':
    // --------------------------------------------------------
    if ( ($HTTP_POST_VARS['faqdesk_id']) && (is_array($HTTP_POST_VARS['product_categories'])) ) {
      $product_id = tep_db_prepare_input($HTTP_POST_VARS['faqdesk_id']);
      $product_categories = $HTTP_POST_VARS['product_categories'];

      for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
        tep_db_query("delete from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . tep_db_input($product_id) . "' and categories_id = '" . tep_db_input($product_categories[$i]) . "'");
      }

      $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . tep_db_input($product_id) . "'");
      $product_categories = tep_db_fetch_array($product_categories_query);

      if ($product_categories['total'] == '0') {
        faqdesk_remove_product($product_id);
      }

      if ($HTTP_POST_VARS['delete_image'] == 'yes') {
        unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
      }

    }  // top if closing bracket

    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath));
    break;

    // --------------------------------------------------------
    case 'move_category_confirm':
    // --------------------------------------------------------
    if ( ($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id']) ) {
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
      $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);
      tep_db_query("update " . TABLE_FAQDESK_CATEGORIES . " set parent_id = '" . tep_db_input($new_parent_id) . "', last_modified = now() where categories_id = '" . tep_db_input($categories_id) . "'");

    }  // top if closing bracket

    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
    break;

    case 'move_product_confirm':
      $faqdesk_id = tep_db_prepare_input($HTTP_POST_VARS['faqdesk_id']);
      $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);
  
      $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . tep_db_input($faqdesk_id) . "' and categories_id = '" . tep_db_input($new_parent_id) . "'");
      $duplicate_check = tep_db_fetch_array($duplicate_check_query);
      if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_FAQDESK_TO_CATEGORIES . " set categories_id = '" . tep_db_input($new_parent_id) . "' where faqdesk_id = '" . tep_db_input($faqdesk_id) . "' and categories_id = '" . $current_category_id . "'");
  
      tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $new_parent_id . '&pID=' . $faqdesk_id));
      break;

    case 'insert_product':
    case 'update_product':
    // Another double case situation -- must be an all in one mentality!
    if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
      $HTTP_GET_VARS['action'] = 'new_product';
    } else {

      $faqdesk_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
      $faqdesk_date_available = tep_db_prepare_input($HTTP_POST_VARS['faqdesk_date_available']);

      $faqdesk_date_available = (date('Y-m-d') < $faqdesk_date_available) ? $faqdesk_date_available : 'null';

      $sql_data_array = array(
      'faqdesk_image' => (($HTTP_POST_VARS['faqdesk_image'] == 'none') ? '' : tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image'])),
      'faqdesk_image_two' => (($HTTP_POST_VARS['faqdesk_image_two'] == 'none') ? '' : tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image_two'])),
      'faqdesk_image_three' => (($HTTP_POST_VARS['faqdesk_image_three'] == 'none') ? '' : tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image_three'])),
      'faqdesk_date_available' => $faqdesk_date_available,
      'faqdesk_status' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_status']),
      'faqdesk_sticky' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_sticky']),
      'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['sort_order']),
      );

      if ($HTTP_GET_VARS['action'] == 'insert_product') {
        $insert_sql_data = array('faqdesk_date_added' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_FAQDESK, $sql_data_array);
        $faqdesk_id = tep_db_insert_id();
        tep_db_query("insert into " . TABLE_FAQDESK_TO_CATEGORIES . " (faqdesk_id, categories_id) values ('" . $faqdesk_id . "', '" . $current_category_id . "')");
      } elseif ($HTTP_GET_VARS['action'] == 'update_product') {
        $update_sql_data = array('faqdesk_last_modified' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $update_sql_data);
        tep_db_perform(TABLE_FAQDESK, $sql_data_array, 'update', 'faqdesk_id = \'' . tep_db_input($faqdesk_id) . '\'');
      }

      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];

        $sql_data_array = array(
        'faqdesk_question' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_question'][$language_id]),
        'faqdesk_answer_long' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_answer_long'][$language_id]),
        'faqdesk_answer_short' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_answer_short'][$language_id]),
        'faqdesk_extra_url' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_extra_url'][$language_id]),
        'faqdesk_image_text' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image_text'][$language_id]),
        'faqdesk_image_text_two' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image_text_two'][$language_id]),
        'faqdesk_image_text_three' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_image_text_three'][$language_id]),
        
        'faqdesk_article_meta_title' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_article_meta_title'][$language_id]),
			  'faqdesk_article_meta_description' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_article_meta_description'][$language_id]),
			  'faqdesk_article_meta_key' => tep_db_prepare_input($HTTP_POST_VARS['faqdesk_article_meta_key'][$language_id])
        );

        if ($HTTP_GET_VARS['action'] == 'insert_product') {
          $insert_sql_data = array(
          'faqdesk_id' => $faqdesk_id,
          'language_id' => $language_id
          );
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_FAQDESK_DESCRIPTION, $sql_data_array);
        } elseif ($HTTP_GET_VARS['action'] == 'update_product') {
          tep_db_perform(TABLE_FAQDESK_DESCRIPTION, $sql_data_array, 'update', 'faqdesk_id = \'' . tep_db_input($faqdesk_id) . '\' and language_id = \'' . $language_id . '\'');
        }
      }

      tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $faqdesk_id));
    }  // midway closing if bracket
    break;

    // --------------------------------------------------------
    case 'copy_to_confirm':
    // --------------------------------------------------------
    if ( (tep_not_null($HTTP_POST_VARS['faqdesk_id'])) && (tep_not_null($HTTP_POST_VARS['categories_id'])) ) {
      $faqdesk_id = tep_db_prepare_input($HTTP_POST_VARS['faqdesk_id']);
      $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

      if ($HTTP_POST_VARS['copy_as'] == 'link') {
        if ($HTTP_POST_VARS['categories_id'] != $current_category_id) {
          $check_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK_TO_CATEGORIES . " where faqdesk_id = '" . tep_db_input($faqdesk_id) . "' and categories_id = '" . tep_db_input($categories_id) . "'");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            tep_db_query("insert into " . TABLE_FAQDESK_TO_CATEGORIES . " (faqdesk_id, categories_id) values ('" . tep_db_input($faqdesk_id) . "', '" . tep_db_input($categories_id) . "')");
          }
        } else {
          $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
        }
      } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {


        $product_query = tep_db_query("select faqdesk_image, faqdesk_image_two, faqdesk_image_three, faqdesk_date_added, faqdesk_date_available, faqdesk_status, faqdesk_sticky from " . TABLE_FAQDESK . " where faqdesk_id = '" . tep_db_input($faqdesk_id) . "'");
        $product = tep_db_fetch_array($product_query);

        tep_db_query("insert into " . TABLE_FAQDESK . " (faqdesk_image, faqdesk_image_two, faqdesk_image_three, faqdesk_date_added, faqdesk_date_available, faqdesk_status, faqdesk_sticky) values ('" . $product['faqdesk_image'] . "','" . $product['faqdesk_image_two'] . "', '" . $product['faqdesk_image_three'] . "', '" . $product['faqdesk_date_added']  . "', '" . $product['faqdesk_date_available'] . "', '" . $product['faqdesk_status'] . "', '" . $product['faqdesk_sticky'] . "')");
        $dup_faqdesk_id = tep_db_insert_id();


        $description_query = tep_db_query("select language_id, faqdesk_question, faqdesk_answer_long, faqdesk_extra_url, faqdesk_image_text, faqdesk_image_text_two, faqdesk_image_text_three, faqdesk_extra_viewed, faqdesk_answer_short, faqdesk_article_meta_title, faqdesk_article_meta_description, faqsdesk_article_meta_key from " . TABLE_FAQDESK_DESCRIPTION . " where faqdesk_id = '" . tep_db_input($faqdesk_id) . "'");

        while ($description = tep_db_fetch_array($description_query)) {
          tep_db_query("insert into " . TABLE_FAQDESK_DESCRIPTION . " (faqdesk_id, language_id, faqdesk_question,
faqdesk_answer_long, faqdesk_extra_url, faqdesk_image_text, faqdesk_image_text_two, faqdesk_image_text_three, 
faqdesk_extra_viewed, faqdesk_answer_short, faqdesk_article_meta_title, faqdesk_article_meta_description, faqsdesk_article_meta_key) values ('" . $dup_faqdesk_id . "', '" . $description['language_id'] . "', '" 
          . addslashes($description['faqdesk_question']) . "', '" . addslashes($description['faqdesk_answer_long']) . "',
'" . $description['faqdesk_extra_url'] . "', '" . $description['faqdesk_image_text'] . "', '" . $description['faqdesk_image_text_two'] . "', 
'" . $description['faqdesk_image_text_three'] . "', '" . $description['faqdesk_extra_viewed'] . "', 
'" . $description['faqdesk_answer_short'] . "', '" . $description['faqdesk_article_meta_title'] . "', , '" . $description['faqdesk_article_meta_description'] . "', , '" . $description['faqdesk_article_meta_key'] . "')");
        }


        tep_db_query("insert into " . TABLE_FAQDESK_TO_CATEGORIES . " (faqdesk_id, categories_id) values ('" . $dup_faqdesk_id . "', '" . tep_db_input($categories_id) . "')");
        $faqdesk_id = $dup_faqdesk_id;
      }

    }  // top closing if bracket

    tep_redirect(tep_href_link(FILENAME_FAQDESK, 'cPath=' . $categories_id . '&pID=' . $faqdesk_id));
    break;

    // --------------------------------------------------------
  } // very top switch closing bracket
}  // very top if closing bracket

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
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
echo tep_init_calendar();
$header_title_menu=BOX_HEADING_FAQDESK;
$header_title_menu_link= tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk');
$header_title_submenu=HEADING_TITLE;
$header_title_additional=tep_draw_form('search', FILENAME_FAQDESK, '', 'get').HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search', $HTTP_GET_VARS['search']).'</form><br>';
$header_title_additional.=tep_draw_form('goto', FILENAME_FAQDESK, '', 'get').HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', faqdesk_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"').'</form>';
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
		<td width="100%" valign="top" height="100%">

<table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">

<?php
// --------------------------------------------------------
//  start of main body-text table
//
//  Also in here you'll find the new_product wood work
// --------------------------------------------------------
if ($HTTP_GET_VARS['action'] == 'new_product') {
  if ( ($HTTP_GET_VARS['pID']) && (!$HTTP_POST_VARS) ) {
    $product_query = tep_db_query("select pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url, pd.faqdesk_image_text, pd.faqdesk_image_text_two, pd.faqdesk_image_text_three, p.faqdesk_id, p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, p.faqdesk_date_added, p.faqdesk_last_modified, p.faqdesk_date_available, p.faqdesk_status, p.faqdesk_sticky, pd.faqdesk_article_meta_title, pd.faqdesk_article_meta_description, pd.faqdesk_article_meta_key from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . " pd where p.faqdesk_id = '" . $HTTP_GET_VARS['pID'] . "' and p.faqdesk_id = pd.faqdesk_id and pd.language_id = '" . $languages_id . "'");
    $product = tep_db_fetch_array($product_query);
    $pInfo = new objectInfo($product);
  } elseif ($HTTP_POST_VARS) {
    $pInfo = new objectInfo($HTTP_POST_VARS);
    $faqdesk_question = $HTTP_POST_VARS['faqdesk_question'];
    $faqdesk_answer_long = $HTTP_POST_VARS['faqdesk_answer_long'];
    
   	$faqdesk_article_meta_title = $HTTP_POST_VARS['faqdesk_article_meta_title'];
	  $faqdesk_article_meta_description = $HTTP_POST_VARS['faqdesk_article_meta_description'];
	  $faqdesk_article_meta_key = $HTTP_POST_VARS['faqdesk_article_meta_key'];
    
    $faqdesk_answer_short = $HTTP_POST_VARS['faqdesk_answer_short'];
    $faqdesk_extra_url = $HTTP_POST_VARS['faqdesk_extra_url'];
    $faqdesk_image_text = $HTTP_POST_VARS['faqdesk_image_text'];
    $faqdesk_image_text_two = $HTTP_POST_VARS['faqdesk_image_text_two'];
    $faqdesk_image_text_three = $HTTP_POST_VARS['faqdesk_image_text_three'];
    $faqdesk_image = $HTTP_POST_VARS['faqdesk_image'];
    $faqdesk_image_two = $HTTP_POST_VARS['faqdesk_image_two'];
    $faqdesk_image_three = $HTTP_POST_VARS['faqdesk_image_three'];
  } else {
    $pInfo = new objectInfo(array());
  }
  $pInfo->faqdesk_date_available = ($pInfo->faqdesk_date_available) ? $pInfo->faqdesk_date_available : date('Y-m-d');
  $languages = tep_get_languages();

  switch ($pInfo->faqdesk_status) {
    case '0': $in_status = false; $out_status = true; break;
    case '1':
    default: $in_status = true; $out_status = false;
  }

  switch ($pInfo->faqdesk_sticky) {
    case '0': $sticky_on = false; $sticky_off = true; break;
    case '1': $sticky_on = true; $sticky_off = false; break;
    default: $sticky_on = false; $sticky_off = true;
  }

  require('includes/classes/directory_listing.php');
  $osC_Dir_Images = new osC_DirectoryListing('../images');
  $osC_Dir_Images->setExcludeEntries('CVS');
  $osC_Dir_Images->setIncludeFiles(false);
  $osC_Dir_Images->setRecursive(true);
  $osC_Dir_Images->setAddDirectoryToFilename(true);
  $files = $osC_Dir_Images->getFiles();

  $image_directories = array(array('id' => '', 'text' => 'images/'));
  foreach ($files as $file) {
    $image_directories[] = array('id' => $file['name'], 'text' => 'images/' . $file['name']);
  }
?>
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
<script language="javascript">
function reloadImage(sSource, sDest) {
  var image = document.new_product[sSource].value;
  var preview = document.getElementById(sDest);

  preview.src = '../images/' + image;
}
</script>
<tr>
<td valign="top">
<?php echo tep_draw_form('new_product', FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="3" cellpadding="3">
<tr>
<td class="pageHeading"><?php echo sprintf(TEXT_NEW_FAQDESK, faqdesk_output_generated_category_path($current_category_id)); ?></td>
<td class="pageHeading" align="right">
<?php echo tep_draw_hidden_field('faqdesk_date_added', (($pInfo->faqdesk_date_added) ? $pInfo->faqdesk_date_added : date('Y-m-d'))); ?>
</td>
</tr>
</table>


<table border="0" width="100%" cellspacing="10" cellpadding="0">
<tr>
<td class="main" valign="top">
<div class="tab-pane" id="mainTabPane">
<script type="text/javascript"><!--
var mainTabPane = new WebFXTabPane( document.getElementById( "mainTabPane" ) );
//--></script>
<div class="tab-page" id="tabDescription">
<h2 class="tab"><?php echo TAB_GENERAL; ?></h2>
<script type="text/javascript"><!--
mainTabPane.addTabPage( document.getElementById( "tabDescription" ) );
//--></script>
<table border="0" cellpadding="5" cellspacing="0">
<tr>
<td class="smallText"><?php echo TEXT_FAQDESK_STATUS;?> </td>
<td class="smallText"><?php echo tep_draw_radio_field('faqdesk_status', '1', $in_status) . '&nbsp;' . TEXT_FAQDESK_AVAILABLE; ?><?php echo tep_draw_radio_field('faqdesk_status', '0', $out_status) . '&nbsp;' . TEXT_FAQDESK_NOT_AVAILABLE; ?></td>
</tr>
<tr>
<td class="smallText"><?php echo TEXT_FAQDESK_STICKY;?> </td>
<td class="smallText"><?php echo tep_draw_radio_field('faqdesk_sticky', '1', $sticky_on) . '&nbsp;' . TEXT_FAQDESK_STICKY_ON; ?><?php echo tep_draw_radio_field('faqdesk_sticky', '0', $sticky_off) . '&nbsp;' . TEXT_FAQDESK_STICKY_OFF; ?></td>
</tr>
<tr>
<td class="smallText"><?php echo TEXT_FAQDESK_SORTORDER;?> </td>
<td class="smallText"><?php echo tep_draw_input_field('sort_order', $pInfo->sort_order, 'size="4"'); ?></td>
</tr>
<tr>
<td class="smallText"><?php echo TEXT_FAQDESK_START_DATE; ?>&nbsp;&nbsp;<small>(<?php echo strtoupper(DATE_FORMAT_SPIFFYCAL); ?>)</small></td>
            <td class="smallText"><?php echo tep_draw_calendar('new_product', 'faqdesk_date_available',$pInfo->faqdesk_date_available)?></td>
          </tr>                         
       </table>
    <div class="tab-pane" id="descriptionTabPane">
      <script type="text/javascript"><!--
      var descriptionTabPane = new WebFXTabPane( document.getElementById( "descriptionTabPane" ) );
      //--></script>
      <?php
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        ?>
        <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //--></script>
        <table border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td class="smallText"><?php echo TEXT_FAQDESK_QUESTION;?> </td>
        <td class="smallText"><?php echo tep_draw_input_field('faqdesk_question[' . $languages[$i]['id'] . ']', (($faqdesk_question[$languages[$i]['id']]) ? stripslashes($faqdesk_question[$languages[$i]['id']]) : faqdesk_get_faqdesk_question($pInfo->faqdesk_id, $languages[$i]['id'])), 'size="50"'); ?></td>
        </tr>
        <tr>
        <td class="smallText" valign="top"><?php echo TEXT_FAQDESK_ANSWER_SHORT;?> </td>
        <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'faqdesk_answer_short[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo faqdesk_draw_textarea_field('faqdesk_answer_short[' . $languages[$i]['id'] . ']', 'soft', '70', '10', (($faqdesk_answer_short[$languages[$i]['id']]) ? stripslashes($faqdesk_answer_short[$languages[$i]['id']]) : faqdesk_get_faqdesk_answer_short($pInfo->faqdesk_id, $languages[$i]['id'])));?></fieldset></td>
        </tr>
        <tr>
        <td class="smallText" valign="top"><?php echo TEXT_FAQDESK_ANSWER_LONG;?> </td>
        <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'faqdesk_answer_long[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('faqdesk_answer_long[' . $languages[$i]['id'] . ']', 'soft', '70', '20', (($faqdesk_answer_long[$languages[$i]['id']]) ? stripslashes($faqdesk_answer_long[$languages[$i]['id']]) : faqdesk_get_faqdesk_answer_long($pInfo->faqdesk_id, $languages[$i]['id']))); ?></fieldset></td>
        </tr>
        <tr>
        <td class="smallText"><?php echo TEXT_FAQDESK_URL . '&nbsp;&nbsp;<small>' . TEXT_FAQDESK_URL_WITHOUT_HTTP . '</small>'; ?></td>
        <td class="smallText"><?php echo tep_draw_input_field('faqdesk_extra_url[' . $languages[$i]['id'] . ']', (($faqdesk_extra_url[$languages[$i]['id']]) ? stripslashes($faqdesk_extra_url[$languages[$i]['id']]) : faqdesk_get_faqdesk_extra_url($pInfo->faqdesk_id, $languages[$i]['id'])), 'size="45"'); ?></td>
        </tr>
        
        
        <?
				 
				  $arr_meta = faqdesk_get_faqdesk_meta($pInfo->faqdesk_id, $languages[$i]['id']);
				 
				 ?> 
          
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FAQDESK_META_TITLE;?> </td>
            <td class="main"><?php echo tep_draw_input_field('faqdesk_article_meta_title[' . $languages[$i]['id'] . ']', (($faqdesk_article_meta_title[$languages[$i]['id']]) ? stripslashes($faqdesk_article_meta_title[$languages[$i]['id']]) : $arr_meta['meta_title']), 'size="45"');
?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FAQDESK_META_DESCRIPTION;?> </td>
            <td class="main"><?php echo tep_draw_textarea_field('faqdesk_article_meta_description[' . $languages[$i]['id'] . ']', 'soft', '50', '10', (($faqdesk_article_meta_description[$languages[$i]['id']]) ? stripslashes($faqdesk_article_meta_description[$languages[$i]['id']]) : $arr_meta['meta_description']));
?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FAQDESK_META_KEY;?> </td>
            <td class="main"><?php echo tep_draw_textarea_field('faqdesk_article_meta_key[' . $languages[$i]['id'] . ']', 'soft', '50', '10', (($faqdesk_article_meta_key[$languages[$i]['id']]) ? stripslashes($faqdesk_article_meta_key[$languages[$i]['id']]) : $arr_meta['meta_key']));
?></td>
          </tr>
        
        
        </table>
        </div>
        <?php
      }
      ?>
      </div>
      </div>
      <div class="tab-page" id="tabImage">
      <h2 class="tab"><?php echo TAB_IMAGE;?></h2>

      <script type="text/javascript">
      <!--
      mainTabPane.addTabPage( document.getElementById( "tabImage" ) );
      //-->
      </script>

      <table border="0" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_FAQDESK_IMAGE_ONE; ?></legend>


      <table border="0" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
      <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_previous_image', (isset($pInfo) ? $pInfo->faqdesk_image : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_previous_image\', \'previewImage\');" class="infoBoxButton">'; ?></p>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText"><?php echo TEXT_FAQDESK_IMAGE_ONE; ?></td>
      <td class="smallText"><?php echo tep_draw_file_field('faqdesk_image'); ?></td>
      </tr>
      <tr>
      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
      <td class="smallText"><?php echo tep_draw_pull_down_menu('faqdesk_image_location', $image_directories, dirname($pInfo->faqdesk_image)); ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </td>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_PREVIEW; ?></legend>

      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td align="center"><?php
      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->faqdesk_image) || $pInfo->faqdesk_image == ''){
        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImage"');
      }else{
        echo tep_image('../images/' . $pInfo->faqdesk_image, '', '', '', 'id="previewImage"');
      }
      ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td colspan="2" class="smallText">
      <fieldset >
      <legend><?php echo TEXT_FAQDESK_IMAGE_SUBTITLE; ?></legend>
      <table border="0" width="100%" cellspacing="3" cellpadding="3">
      <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
      <tr>
      <td class="main">
      <?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
      </td>
      <td class="main">
      <?php echo tep_draw_input_field('faqdesk_image_text[' . $languages[$i]['id'] . ']', (($faqdesk_image_text[$languages[$i]['id']]) ?
      stripslashes($faqdesk_image_text[$languages[$i]['id']]) : faqdesk_get_faqdesk_image_text($pInfo->faqdesk_id, $languages[$i]['id'])), 'size="50"'); ?>
      </td>
      </tr>
      <?php
      }
      ?>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>

      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_FAQDESK_IMAGE_TWO; ?></legend>


      <table border="0" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
      <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_previous_image_two', (isset($pInfo) ? $pInfo->faqdesk_image_two : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_previous_image_two\', \'previewImageTwo\');" class="infoBoxButton">'; ?></p>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText"><?php echo TEXT_FAQDESK_IMAGE_TWO; ?></td>
      <td class="smallText"><?php echo tep_draw_file_field('faqdesk_image_two'); ?></td>
      </tr>
      <tr>
      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
      <td class="smallText"><?php echo tep_draw_pull_down_menu('faqdesk_image_location_two', $image_directories, dirname($pInfo->faqdesk_image_two)); ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </td>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_PREVIEW; ?></legend>

      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td align="center"><?php
      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->faqdesk_image_two) || $pInfo->faqdesk_image_two == ''){
        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImageTwo"');
      }else{
        echo tep_image('../images/' . $pInfo->faqdesk_image_two, '', '', '', 'id="previewImageTwo"');
      }
      ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td colspan="2" class="smallText">
      <fieldset >
      <legend><?php echo TEXT_FAQDESK_IMAGE_SUBTITLE_TWO; ?></legend>
      <table border="0" width="100%" cellspacing="3" cellpadding="3">
      <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
      <tr>
      <td class="main">
      <?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
      </td>
      <td class="main">
      <?php echo tep_draw_input_field('faqdesk_image_text_two[' . $languages[$i]['id'] . ']', (($faqdesk_image_text_two[$languages[$i]['id']]) ?
      stripslashes($faqdesk_image_text_two[$languages[$i]['id']]) : faqdesk_get_faqdesk_image_text_two($pInfo->faqdesk_id, $languages[$i]['id'])), 'size="50"'); ?>
      </td>
      </tr>
      <?php
      }
      ?>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>

      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset s>
      <legend><?php echo TEXT_FAQDESK_IMAGE_THREE; ?></legend>


      <table border="0" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
      <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_previous_image_three', (isset($pInfo) ? $pInfo->faqdesk_image_three : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_previous_image_three\', \'previewImageThree\');" class="infoBoxButton">'; ?></p>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset >
      <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText"><?php echo TEXT_FAQDESK_IMAGE_THREE; ?></td>
      <td class="smallText"><?php echo tep_draw_file_field('faqdesk_image_three'); ?></td>
      </tr>
      <tr>
      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
      <td class="smallText"><?php echo tep_draw_pull_down_menu('faqdesk_image_location_three', $image_directories, dirname($pInfo->faqdesk_image_three)); ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </td>
      <td class="smallText" width="50%" height="100%" valign="top">
      <fieldset>
      <legend><?php echo TEXT_PREVIEW; ?></legend>

      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td align="center"><?php
      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->faqdesk_image_three) || $pInfo->faqdesk_image_three == ''){
        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImageThree"');
      }else{
        echo tep_image('../images/' . $pInfo->faqdesk_image_three, '', '', '', 'id="previewImageThree"');
      }
      ?></td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>
      <tr>
      <td colspan="2" class="smallText">
      <fieldset >
      <legend><?php echo TEXT_FAQDESK_IMAGE_SUBTITLE_THREE; ?></legend>
      <table border="0" width="100%" cellspacing="3" cellpadding="3">
      <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
      <tr>
      <td class="main">
      <?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
      </td>
      <td class="main">
      <?php echo tep_draw_input_field('faqdesk_image_text_three[' . $languages[$i]['id'] . ']', (($faqdesk_image_text_three[$languages[$i]['id']]) ?
      stripslashes($faqdesk_image_text_three[$languages[$i]['id']]) : faqdesk_get_faqdesk_image_text_three($pInfo->faqdesk_id, $languages[$i]['id'])), 'size="50"'); ?>
      </td>
      </tr>
      <?php
      }
      ?>
      </table>
      </fieldset>
      </td>
      </tr>
      </table>
      </fieldset>
      </td>
      </tr>

      </table>
      </div>
      </div>
      </td>
      </tr>
      </table>

      <br>


      <table border="0" width="100%" cellspacing="3" cellpadding="3">
      <tr>
      <td class="main" align="right">
      <?php echo tep_draw_hidden_field('faqdesk_date_added', (($pInfo->faqdesk_date_added) ? $pInfo->faqdesk_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
      </td>
      </tr>
      </table>

      </form>
		</td>
	</tr>


<?php
} elseif ($HTTP_GET_VARS['action'] == 'new_product_preview') {
  if ($HTTP_POST_VARS) {
    $HTTP_POST_VARS['faqdesk_date_available'] = tep_calendar_rawdate( $HTTP_POST_VARS['faqdesk_date_available'] );
    
    $pInfo = new objectInfo($HTTP_POST_VARS);

    $faqdesk_question = $HTTP_POST_VARS['faqdesk_question'];
    $faqdesk_answer_long = $HTTP_POST_VARS['faqdesk_answer_long'];
    $faqdesk_answer_short = $HTTP_POST_VARS['faqdesk_answer_short'];

    $faqdesk_article_meta_title = $HTTP_POST_VARS['faqdesk_article_meta_title'];
    $faqdesk_article_meta_description = $HTTP_POST_VARS['faqdesk_article_meta_description'];
    $faqdesk_article_meta_key = $HTTP_POST_VARS['faqdesk_article_meta_key'];

// Meta -------

if(is_array($faqdesk_article_meta_title))
{
  foreach($faqdesk_article_meta_title as $key => $value)
  {
    $faqdesk_article_meta_title[$key] = nl2br($value);
  }
}
else
{
  $faqdesk_article_meta_title = nl2br($faqdesk_article_meta_title);
}


if(is_array($faqdesk_article_meta_description))
{
  foreach($faqdesk_article_meta_description as $key => $value)
  {
    $faqdesk_article_meta_description[$key] = nl2br($value);
  }
}
else
{
  $faqdesk_article_meta_description = nl2br($faqdesk_article_meta_description);
}

if(is_array($faqdesk_article_meta_key))
{
  foreach($faqdesk_article_meta_key as $key => $value)
  {
    $faqdesk_article_meta_key[$key] = nl2br($value);
  }
}
else
{
  $faqdesk_article_meta_key = nl2br($faqdesk_article_meta_key);
}

// Meta eof -------     


    $faqdesk_extra_url = $HTTP_POST_VARS['faqdesk_extra_url'];
    $faqdesk_image_text = $HTTP_POST_VARS['faqdesk_image_text'];
    $faqdesk_image_text_two = $HTTP_POST_VARS['faqdesk_image_text_two'];
    $faqdesk_image_text_three = $HTTP_POST_VARS['faqdesk_image_text_three'];

    $faqdesk_image = new upload('faqdesk_image');
    $faqdesk_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['faqdesk_image_location']);
    if ($faqdesk_image->parse() && $faqdesk_image->save()) {
      $faqdesk_image_name = (!empty($_POST['faqdesk_image_location']) ? $_POST['faqdesk_image_location'] . '/' : '') . $faqdesk_image->filename;
    } else {
      $faqdesk_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
    }

    $faqdesk_image = new upload('faqdesk_image_two');
    $faqdesk_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['faqdesk_image_location_two']);
    if ($faqdesk_image->parse() && $faqdesk_image->save()) {
      $faqdesk_image_name_two = (!empty($_POST['faqdesk_image_location_two']) ? $_POST['faqdesk_image_location_two'] . '/' : '') . $faqdesk_image->filename;
    } else {
      $faqdesk_image_name_two = (isset($HTTP_POST_VARS['products_previous_image_two']) ? $HTTP_POST_VARS['products_previous_image_two'] : '');
    }

    $faqdesk_image = new upload('faqdesk_image_three');
    $faqdesk_image->set_destination(DIR_FS_CATALOG_IMAGES . $_POST['faqdesk_image_location_three']);
    if ($faqdesk_image->parse() && $faqdesk_image->save()) {
      $faqdesk_image_name_three = (!empty($_POST['faqdesk_image_location_three']) ? $_POST['faqdesk_image_location_three'] . '/' : '') . $faqdesk_image->filename;
    } else {
      $faqdesk_image_name_three = (isset($HTTP_POST_VARS['products_previous_image_three']) ? $HTTP_POST_VARS['products_previous_image_three'] : '');
    }

  } else {
    $product_query = tep_db_query("
select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, 
pd.faqdesk_extra_url, pd.faqdesk_image_text, pd.faqdesk_image_text_two, pd.faqdesk_image_text_three, p.faqdesk_image, 
p.faqdesk_image_two, p.faqdesk_image_three, p.faqdesk_date_added, p.faqdesk_last_modified,
p.faqdesk_date_available, p.faqdesk_status, p.sort_order, p.faqdesk_sticky, pd.faqdesk_article_meta_title, pd.faqdesk_article_meta_description, pd.faqdesk_article_meta_key from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_DESCRIPTION . "
pd where p.faqdesk_id = pd.faqdesk_id and p.faqdesk_id = '" . $HTTP_GET_VARS['pID'] . "'
");
    $product = tep_db_fetch_array($product_query);

    $pInfo = new objectInfo($product);
    $faqdesk_image_name = $pInfo->faqdesk_image;
    $faqdesk_image_name_two = $pInfo->faqdesk_image_two;
    $faqdesk_image_name_three = $pInfo->faqdesk_image_three;
  }

  $form_action = ($HTTP_GET_VARS['pID']) ? 'update_product' : 'insert_product';

  echo tep_draw_form($form_action, FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
?>
  <tr>
    <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
  </tr>
<?
$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  if ($HTTP_GET_VARS['read'] == 'only') {
    $pInfo->faqdesk_question = faqdesk_get_faqdesk_question($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_answer_long = faqdesk_get_faqdesk_answer_long($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_answer_short = faqdesk_get_faqdesk_answer_short($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_extra_url = faqdesk_get_faqdesk_extra_url($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_image_text = faqdesk_get_faqdesk_image_text($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_image_text_two = faqdesk_get_faqdesk_image_text_two($pInfo->faqdesk_id, $languages[$i]['id']);
    $pInfo->faqdesk_image_text_three = faqdesk_get_faqdesk_image_text_three($pInfo->faqdesk_id, $languages[$i]['id']);
    
   	    // Meta
	      $arr_meta = faqdesk_get_faqdesk_meta($pInfo->faqdesk_id, $languages[$i]['id']);
	      $pInfo->faqdesk_article_meta_title = $arr_meta['meta_title'];
	      $pInfo->faqdesk_article_meta_description = $arr_meta['meta_description'];
	      $pInfo->faqdesk_article_meta_key = $arr_meta['meta_key'];
	      // Meta eof
    
  } else {
    $pInfo->faqdesk_question = tep_db_prepare_input($faqdesk_question[$languages[$i]['id']]);
    $pInfo->faqdesk_answer_long = tep_db_prepare_input($faqdesk_answer_long[$languages[$i]['id']]);
    $pInfo->faqdesk_answer_short = tep_db_prepare_input($faqdesk_answer_short[$languages[$i]['id']]);
    $pInfo->faqdesk_extra_url = tep_db_prepare_input($faqdesk_extra_url[$languages[$i]['id']]);
    $pInfo->faqdesk_image_text = tep_db_prepare_input($faqdesk_image_text[$languages[$i]['id']]);
    $pInfo->faqdesk_image_text_two = tep_db_prepare_input($faqdesk_image_text_two[$languages[$i]['id']]);
    $pInfo->faqdesk_image_text_three = tep_db_prepare_input($faqdesk_image_text_three[$languages[$i]['id']]);
    
  	    // Meta
	      $pInfo->faqdesk_article_meta_title = tep_db_prepare_input($faqdesk_article_meta_title[$languages[$i]['id']]);
	      $pInfo->faqdesk_article_meta_description = tep_db_prepare_input($faqdesk_article_meta_description[$languages[$i]['id']]);
	      $pInfo->faqdesk_article_meta_key = tep_db_prepare_input($faqdesk_article_meta_key[$languages[$i]['id']]);
	      // Meta eof
    
  }
?>

	<tr>
		<td>
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr>
		<td colspan="2">

<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent" width="5%">
<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
		</td>
		<td class="headerBarContent"><?php echo $pInfo->faqdesk_question; ?></td>
	</tr>
</table>

		</td>
	</tr>
	<tr>
		<td width="50%" valign="top">

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_ANSWER_SHORT; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo $pInfo->faqdesk_answer_short; ?>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_ANSWER_LONG; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo $pInfo->faqdesk_answer_long; ?>
		</td>
	</tr>
</table>


		</td>
		<td width="50%" valign="top">


<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_IMAGE_PREVIEW_ONE; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php
// BEGIN >> code change by Peter
echo (($faqdesk_image_name) ? tep_image(DIR_WS_CATALOG_IMAGES . $faqdesk_image_name, $pInfo->faqdesk_question, '', '',
'align="right" hspace="5" vspace="5"') : '') .'';
// END >> code change by Peter
?>
		</td>
	</tr>
	<tr>
		<td><?php echo $pInfo->faqdesk_image_text; ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_IMAGE_PREVIEW_TWO; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php
// BEGIN >> code change by Peter
echo (($faqdesk_image_name_two) ? tep_image(DIR_WS_CATALOG_IMAGES . $faqdesk_image_name_two, $pInfo->faqdesk_question, '', '',
'align="right" hspace="5" vspace="5"') : '') .'';
// END >> code change by Peter
?>
		</td>
	</tr>
	<tr>
		<td><?php echo $pInfo->faqdesk_image_text_two; ?></td>
	</tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_IMAGE_PREVIEW_THREE; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php
// BEGIN >> code change by Peter
echo (($faqdesk_image_name_three) ? tep_image(DIR_WS_CATALOG_IMAGES . $faqdesk_image_name_three, $pInfo->faqdesk_question, '', '',
'align="right" hspace="5" vspace="5"') : '') .'';
// END >> code change by Peter
?>
		</td>
	</tr>
	<tr>
		<td><?php echo $pInfo->faqdesk_image_text_three; ?></td>
	</tr>
</table>

<?php if ($pInfo->faqdesk_extra_url) { ?>
<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_ADDED_LINK_HEADER; ?></td>
	</tr>
	<tr>
		<td class="main">
<?php echo sprintf(TEXT_FAQDESK_ADDED_LINK, $pInfo->faqdesk_extra_url); ?>
		</td>
	</tr>
</table>
<?php
} // --------->>>>>>>> end of loop control for checking url
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_DATE_ADDED; ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo sprintf(tep_date_long($pInfo->faqdesk_date_added)); ?></td>
	</tr>
	<tr>
</table>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
	<tr class="headerBar">
		<td class="headerBarContent"><?php echo TEXT_FAQDESK_DATE_AVAILABLE; ?></td>
	</tr>
	<tr>
		<td class="main"><?php echo sprintf(tep_date_long($pInfo->faqdesk_date_available)); ?></td>
	</tr>
	<tr>
</table>


		</td>
	</tr>
</table>


<?php } // --------->>>>>>>> break time to run some code checks


if ($HTTP_GET_VARS['read'] == 'only') {
  if ($HTTP_GET_VARS['origin']) {
    $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
    if ($pos_params != false) {
      $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
      $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
    } else {
      $back_url = $HTTP_GET_VARS['origin'];
      $back_url_params = '';
    }
  } else {
    $back_url = FILENAME_FAQDESK;
    $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id;
  }
?>

	<tr>
		<td align="right">
<?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
		</td>
	</tr>

<?php
} else {  // --------->>>>>>>> were do we go from here
?>


	<tr>
		<td align="right" class="smallText">


<?php
// --------------------------------------------------------
// Re-Post all POST'ed variables
// main table area that shows the catagories, the left box, and the counts at the bottom of the catagory area
// --------------------------------------------------------
reset($HTTP_POST_VARS);
while (list($key, $value) = each($HTTP_POST_VARS)) {
  if (!is_array($HTTP_POST_VARS[$key])) {
    echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
  }
}

$languages = tep_get_languages();
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  echo tep_draw_hidden_field('faqdesk_question[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_question[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_answer_long[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_answer_long[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_answer_short[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_answer_short[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_extra_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_extra_url[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_image_text[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_image_text[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_image_text_two[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_image_text_two[$languages[$i]['id']])));
  echo tep_draw_hidden_field('faqdesk_image_text_three[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_image_text_three[$languages[$i]['id']])));
  
   // Meta
	 echo tep_draw_hidden_field('faqdesk_article_meta_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_article_meta_title[$languages[$i]['id']])));
	 echo tep_draw_hidden_field('faqdesk_article_meta_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_article_meta_description[$languages[$i]['id']])));
	 echo tep_draw_hidden_field('faqdesk_article_meta_key[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($faqdesk_article_meta_key[$languages[$i]['id']])));
	 // Meta eof
  
}

echo tep_draw_hidden_field('faqdesk_image', stripslashes($faqdesk_image_name));
echo tep_draw_hidden_field('faqdesk_image_two', stripslashes($faqdesk_image_name_two));
echo tep_draw_hidden_field('faqdesk_image_three', stripslashes($faqdesk_image_name_three));

echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

if ($HTTP_GET_VARS['pID']) {
  echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
} else {
  echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
}

echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $HTTP_GET_VARS['pID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?>

		</td>
</form>
	</tr>

<?php
}
} else {
?>

	<tr>
		<td height="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
	<tr>
		<td valign="top"  height="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr class="dataTableHeadingRow">
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_FAQDESK; ?></td>
		<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>

		<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STICKY; ?></td>

		<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
	</tr>

<?php
$categories_count = 0;
$rows = 0;

if ($HTTP_GET_VARS['search']) {
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_description, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.catagory_status from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.categories_name like '%" . $HTTP_GET_VARS['search'] . "%' order by c.sort_order, cd.categories_name");
} else {
  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_description, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.catagory_status from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");}



  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_count++;
    $rows++;
    // --------------------------------------------------------
    // Get parent_id for subcategories if search
    // --------------------------------------------------------
    if ($HTTP_GET_VARS['search']) $cPath= $categories['parent_id'];

    if ( ((!$HTTP_GET_VARS['cID']) && (!$HTTP_GET_VARS['pID']) || (@$HTTP_GET_VARS['cID'] == $categories['categories_id'])) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
      $category_childs = array('childs_count' => faqdesk_childs_in_category_count($categories['categories_id']));
      $category_products = array('products_count' => faqdesk_products_in_category_count($categories['categories_id']));

      $cInfo_array = array_merge($categories, $category_childs, $category_products);
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) {
      echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQDESK, faqdesk_get_path($categories['categories_id'])) . '\'">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
    }

?>

		<td class="dataTableContent">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK, faqdesk_get_path($categories['categories_id'])) . '">'
. tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>';
?>
	</td>
		<td class="dataTableContent" align="center">

<?php
if ($categories['catagory_status'] == '1') {
  echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="'
  . tep_href_link(FILENAME_FAQDESK, 'action=setflag&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">'
  . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
} else {
  echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'action=setflag&flag=1&cID=' . $categories['categories_id']
  . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10)
  . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
}
?>

		</td>
		<td class="dataTableContent" align="right">&nbsp;</td>

		<td class="dataTableContent" align="right">
<?php
if ( (is_object($cInfo)) && ($categories['categories_id'] == $cInfo->categories_id) ) {
  echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
} else {
  echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
}
?>

		&nbsp;</td>
	</tr>

<?php
  }

  $products_count = 0;
  if ($HTTP_GET_VARS['search']) {
    $products_query = tep_db_query("
select p.faqdesk_id, pd.faqdesk_question, p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, p.faqdesk_date_added, 
p.faqdesk_last_modified, p.faqdesk_date_available, p.faqdesk_status, p.faqdesk_sticky, p2c.categories_id from " . TABLE_FAQDESK . " p, " 
    . TABLE_FAQDESK_DESCRIPTION . " pd, " . TABLE_FAQDESK_TO_CATEGORIES . " p2c where p.faqdesk_id = pd.faqdesk_id and pd.language_id = '"
    . $languages_id . "' and p.faqdesk_id = p2c.faqdesk_id and pd.faqdesk_question like '%" . $HTTP_GET_VARS['search'] . "%'
order by p.sort_order, pd.faqdesk_question
");
  } else {
    $products_query = tep_db_query("
select p.faqdesk_id, pd.faqdesk_question, p.faqdesk_image, p.faqdesk_image_two, p.faqdesk_image_three, p.faqdesk_date_added, 
p.faqdesk_last_modified, p.faqdesk_date_available, p.faqdesk_status, p.faqdesk_sticky from " . TABLE_FAQDESK . " p, " 
    . TABLE_FAQDESK_DESCRIPTION . " pd, " . TABLE_FAQDESK_TO_CATEGORIES . " p2c where p.faqdesk_id = pd.faqdesk_id and pd.language_id = '"
    . $languages_id . "' and p.faqdesk_id = p2c.faqdesk_id and p2c.categories_id = '" . $current_category_id . "' order by p.sort_order, pd.faqdesk_question
");
  }
  while ($products = tep_db_fetch_array($products_query)) {
    $products_count++;
    $rows++;

    // --------------------------------------------------------
    // Get categories_id for product if search
    // --------------------------------------------------------
    if ($HTTP_GET_VARS['search']) $cPath=$products['categories_id'];
    if ( ((!$HTTP_GET_VARS['pID']) && (!$HTTP_GET_VARS['cID']) || (@$HTTP_GET_VARS['pID'] == $products['faqdesk_id'])) && (!$pInfo) && (!$cInfo) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {
      // --------------------------------------------------------
      // find out the rating average from customer reviews
      // --------------------------------------------------------
      $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $products['faqdesk_id'] . "'");
      $reviews = tep_db_fetch_array($reviews_query);
      $pInfo_array = array_merge($products, $reviews);
      $pInfo = new objectInfo($pInfo_array);
    }

    if ( (is_object($pInfo)) && ($products['faqdesk_id'] == $pInfo->faqdesk_id) ) {
      echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $products['faqdesk_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $products['faqdesk_id']) . '\'">' . "\n";
    }
?>
		<td class="dataTableContent">
<?php
echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $products['faqdesk_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['faqdesk_question'];
?>
		</td>
		<td class="dataTableContent" align="center">
<?php
if ($products['faqdesk_status'] == '1') {
  echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK, 'action=setflag&flag=0&pID=' . $products['faqdesk_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
} else {
  echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'action=setflag&flag=1&pID=' . $products['faqdesk_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
}
?>
		</td>
		<td class="dataTableContent" align="center">
<?php
if ($products['faqdesk_sticky'] == '1') {
  echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK, 'action=setflag_sticky&flag_sticky=0&pID=' . $products['faqdesk_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
} else {
  echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'action=setflag_sticky&flag_sticky=1&pID=' . $products['faqdesk_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
}
?>
		</td>
		<td class="dataTableContent" align="right">
<?php
if ( (is_object($pInfo)) && ($products['faqdesk_id'] == $pInfo->faqdesk_id) ) {
  echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
} else {
  echo '<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $products['faqdesk_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
}
?>
		&nbsp;</td>
	</tr>
<?php
  }

  if ($cPath_array) {
    $cPath_back = '';
    for($i = 0, $n = sizeof($cPath_array) - 1; $i < $n; $i++) {
      if ($cPath_back == '') {
        $cPath_back .= $cPath_array[$i];
      } else {
        $cPath_back .= '_' . $cPath_array[$i];
      }
    }
  }

  $cPath_back = ($cPath_back) ? 'cPath=' . $cPath_back : '';
  // --------------------------------------------------------
  //  Bottom to main page that has counts and new catagories and news items buttons
  // --------------------------------------------------------
?>

	<tr>
		<td colspan="4">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_FAQDESK . '&nbsp;' . $products_count; ?></td>
		<td align="right" class="smallText">
<?php
if ($cPath) echo '<a href="' . tep_href_link(FILENAME_FAQDESK, $cPath_back . '&cID=' . $current_category_id) . '">' .
tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!$HTTP_GET_VARS['search']) echo '<a href="' .
tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif',
IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_faq.gif', IMAGE_NEW_STORY) . '</a>';
?>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>
		</td>

<?php
// --------------------------------------------------------
// types of actions and the text based informatioin declaration area
// --------------------------------------------------------
$heading = array();
$contents = array();
switch ($HTTP_GET_VARS['action']) {

  // --------------------------------------------------------
  case 'new_category':
  // --------------------------------------------------------
  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

  $contents = array('form' => tep_draw_form('newcategory', FILENAME_FAQDESK, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
  $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

  $category_inputs_string = '';
  $languages = tep_get_languages();
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $category_inputs_string .= '
<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], 
    $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']
	');
  }

  $categories_description_inputs_string = '';
  $languages = tep_get_languages();
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $categories_description_inputs_string .= '
<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], 
    $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_description[' . $languages[$i]['id'] . ']
	');
  }

  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_DESCRIPTION_NAME . $categories_description_inputs_string);
  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
  $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
  //$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  $contents[] = array('text' => '<br>' . TEXT_SHOW_STATUS . '<br>' . tep_draw_input_field('catagory_status', $cInfo->catagory_status, 'size="2"') . '1=Enabled 0=Disabled');
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  break;

  // --------------------------------------------------------
  case 'edit_category':
  // --------------------------------------------------------
  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');
  $contents = array(
  'form' => tep_draw_form('categories', FILENAME_FAQDESK, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id)
  );
  $contents[] = array('text' => TEXT_EDIT_INTRO);

  $category_inputs_string = '';
  $languages = tep_get_languages();
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $category_inputs_string .= '
<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], 
    $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']',
    faqdesk_get_category_name($cInfo->categories_id, $languages[$i]['id'])
    );
  }

  $categories_description_inputs_string = '';
  $languages = tep_get_languages();
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $categories_description_inputs_string .= '
<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], 
    $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_description[' . $languages[$i]['id'] . ']',
    faqdesk_get_category_description($cInfo->categories_id, $languages[$i]['id'])
    );
  }

  $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
  $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_DESCRIPTION . $categories_description_inputs_string);
  $contents[] = array(
  'text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>'
  );
  $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
  $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
  /*
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  */
  $contents[] = array('text' => '<br>' . TEXT_SHOW_STATUS . '<br>' . tep_draw_input_field('catagory_status', $cInfo->catagory_status, 'size="2"') . '1=Enabled 0=Disabled');
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');


  break;

  // --------------------------------------------------------
  case 'delete_category':
  // --------------------------------------------------------

  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

  $contents = array('form' => tep_draw_form('categories', FILENAME_FAQDESK, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
  $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
  $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
  if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
  if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_FAQDESK, $cInfo->products_count));
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  break;

  // --------------------------------------------------------
  case 'move_category':
  // --------------------------------------------------------

  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

  $contents = array('form' => tep_draw_form('categories', FILENAME_FAQDESK, 'action=move_category_confirm') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
  $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
  $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', faqdesk_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  break;

  // --------------------------------------------------------
  case 'delete_product':
  // --------------------------------------------------------

  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_NEWS . '</b>');

  $contents = array('form' => tep_draw_form('products', FILENAME_FAQDESK, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('faqdesk_id', $pInfo->faqdesk_id));
  $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
  $contents[] = array('text' => '<br><b>' . $pInfo->faqdesk_question . '</b>');

  $product_categories_string = '';
  $product_categories = faqdesk_generate_category_path($pInfo->faqdesk_id, 'product');
  for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
    $category_path = '';
    for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
      $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
    }
    $category_path = substr($category_path, 0, -16);
    $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
  }

  $product_categories_string = substr($product_categories_string, 0, -4);

  $contents[] = array('text' => '<br>' . $product_categories_string);

  $contents[] = array('text' => '<br>' . TEXT_DELETE_IMAGE_INTRO);

  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  break;

  // --------------------------------------------------------
  case 'move_product':
  // --------------------------------------------------------

  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

  $contents = array('form' => tep_draw_form('products', FILENAME_FAQDESK, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('faqdesk_id', $pInfo->faqdesk_id));
  $contents[] = array('text' => sprintf(TEXT_MOVE_FAQDESK_INTRO, $pInfo->faqdesk_question));
  $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . faqdesk_output_generated_category_path($pInfo->faqdesk_id, 'product') . '</b>');
  $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->faqdesk_question) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', faqdesk_get_category_tree(), $current_category_id));
  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
  break;

  // --------------------------------------------------------
  case 'copy_to':
  // --------------------------------------------------------

  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

  $contents = array(
  'form' => tep_draw_form('copy_to', FILENAME_FAQDESK, 'action=copy_to_confirm&cPath=' . $cPath) .
  tep_draw_hidden_field('faqdesk_id', $pInfo->faqdesk_id)
  );
  $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
  $contents[] = array(
  'text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . faqdesk_output_generated_category_path($pInfo->faqdesk_id, 'product')
  . '</b>'
  );
  $contents[] = array(
  'text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', faqdesk_get_category_tree(), $current_category_id)
  );
  $contents[] = array(
  'text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>'
  . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE
  );
  $contents[] = array(
  'align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_FAQDESK,
  'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'
  );
  break;

  
  default:
  //  right box that runs the buttons and what not
  if ($rows > 0) {
    if (is_object($cInfo)) { // category info box contents
    $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

    $contents[] = array(
    'align' => 'center',
    'text' => '
<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . 
    tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' .
    $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' .
    tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' .
    tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>'
    );
    $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));

    if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
    $contents[] = array(
    'text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image
    );
    $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_FAQDESK . ' ' . $cInfo->products_count);
    } elseif (is_object($pInfo)) { // news info box contents
    $heading[] = array('text' => '<b>' . faqdesk_get_faqdesk_question($pInfo->faqdesk_id, $languages_id) . '</b>');
    $contents[] = array(
    'align' => 'center',
    'text' => '<a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id . '&action=new_product') .
    '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' .
    $pInfo->faqdesk_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' .
    tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' . $pInfo->faqdesk_id . '&action=move_product') . '">' .
    tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_FAQDESK, 'cPath=' . $cPath . '&pID=' .
    $pInfo->faqdesk_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>'
    );
    $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->faqdesk_date_added));
    if (tep_not_null($pInfo->faqdesk_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->faqdesk_last_modified));
    if (date('Y-m-d') < $pInfo->faqdesk_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->faqdesk_date_available));
    $contents[] = array(
    'text' => '
<br>' . tep_info_image($pInfo->faqdesk_image, $pInfo->faqdesk_question, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->faqdesk_image .
    '<br>' . tep_info_image($pInfo->faqdesk_image_two, $pInfo->faqdesk_question, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->faqdesk_image_two .
    '<br>' . tep_info_image($pInfo->faqdesk_image_three, $pInfo->faqdesk_question, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->faqdesk_image_three
    );
    $contents[] = array('text' => '<br>' . TEXT_FAQDESK_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
    }
  } else { // create category/news info
  $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');
  $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_story, $parent_categories_name));
  }

  break;

}

if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
  echo '<td width="25%" valign="top" background="images/right_bg.gif">' . "\n";
  $box = new box;
  echo $box->infoBox($heading, $contents);
  echo '</td>' . "\n";
}
?>

	</tr>
</table>
		</td>
	</tr>

<?php
}
?>
</table>
		</td>
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
