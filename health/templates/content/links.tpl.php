<?php
  if ($display_mode == 'categories') {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>         
        </td>
      </tr>

<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>        

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?> 
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    $categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name, lcd.link_categories_description, lc.link_categories_image from " . TABLE_LINK_CATEGORIES . " lc, " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lc.link_categories_id = lcd.link_categories_id and lc.link_categories_status = '1' and lcd.language_id = '" . (int)$languages_id . "' order by lcd.link_categories_name");

    $number_of_categories = tep_db_num_rows($categories_query);

    if ($number_of_categories > 0) {
      $rows = 0;
      while ($categories = tep_db_fetch_array($categories_query)) {
        $rows++;
        $lPath_new = 'lPath=' . $categories['link_categories_id'];
        $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

        echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_LINKS, $lPath_new) . '">';
        
        if (tep_not_null($categories['link_categories_image'])) {
          echo tep_links_image(DIR_WS_IMAGES . $categories['link_categories_image'], $categories['link_categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>';
        } else {
          echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif', $categories['link_categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, 'style="border: 3px double black"') . '<br>';
        }

        echo '<br><b><u>' . $categories['link_categories_name'] . '</b></u></a><br><br>' . $categories['link_categories_description'] . '</td>' . "\n";
        if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
          echo '              </tr>' . "\n";
          echo '              <tr>' . "\n";
        }
      }
    } else {
?>
                <td><?php new infoBox(array(array('text' => TEXT_NO_CATEGORIES))); ?></td>
<?php
    }
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_LINKS_SUBMIT, tep_get_all_get_params()) . '">' . tep_template_image_button('button_submit_link.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_SUBMIT_LINK, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>            
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>       
    </table>
<?php
  } elseif ($display_mode == 'links') {
// create column list
    $define_list = array('LINK_LIST_TITLE' => LINK_LIST_TITLE,
                         'LINK_LIST_URL' => LINK_LIST_URL,
                         'LINK_LIST_IMAGE' => LINK_LIST_IMAGE,
                         'LINK_LIST_DESCRIPTION' => LINK_LIST_DESCRIPTION, 
                         'LINK_LIST_COUNT' => LINK_LIST_COUNT);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'LINK_LIST_TITLE':
          $select_column_list .= 'ld.links_title, ';
          break;
        case 'LINK_LIST_URL':
          $select_column_list .= 'l.links_url, ';
          break;
        case 'LINK_LIST_IMAGE':
          $select_column_list .= 'l.links_image_url, ';
          break;
        case 'LINK_LIST_DESCRIPTION':
          $select_column_list .= 'ld.links_description, ';
          break;
        case 'LINK_LIST_COUNT':
          $select_column_list .= 'l.links_clicked, ';
          break;
      }
    }

// show the links in a given category
// We show them all
    $listing_sql = "select " . $select_column_list . " l.links_id from " . TABLE_LINKS_DESCRIPTION . " ld, " . TABLE_LINKS . " l, " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2lc where l.links_status = '2' and l.links_id = l2lc.links_id and ld.links_id = l2lc.links_id and ld.language_id = '" . (int)$languages_id . "' and l2lc.link_categories_id = '" . (int)$current_links_id . "'";

    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'LINK_LIST_TITLE') {
          $HTTP_GET_VARS['sort'] = $i+1 . 'a';
          $listing_sql .= " order by ld.links_title";
          break;
        }
      }
    } else {
      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
      $sort_order = substr($HTTP_GET_VARS['sort'], 1);
      $listing_sql .= ' order by ';
      switch ($column_list[$sort_col-1]) {
        case 'LINK_LIST_TITLE':
          $listing_sql .= "ld.links_title " . ($sort_order == 'd' ? 'desc' : '');
          break;
        case 'LINK_LIST_URL':
          $listing_sql .= "l.links_url " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
        case 'LINK_LIST_IMAGE':
          $listing_sql .= "ld.links_title";
          break;
        case 'LINK_LIST_DESCRIPTION':
          $listing_sql .= "ld.links_description " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
        case 'LINK_LIST_COUNT':
          $listing_sql .= "l.links_clicked " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
      }
    }
    $image = 'spacer.gif';
    if ($current_links_id) {
      $image_query = tep_db_query("select link_categories_image from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$current_links_id . "'");
      $image_value = tep_db_fetch_array($image_query);

      if (tep_not_null($image_value['link_categories_image'])) {
        $image = $image_value['link_categories_image'];
      }
    }    
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(($image == 'spacer.gif' ? DIR_WS_TEMPLATE_IMAGES . 'spacer.gif' : DIR_WS_IMAGES .  $image), HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>         
        </td>
      </tr>

<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>        

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?> 

      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_LINK_LISTING); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_LINKS_SUBMIT, tep_get_all_get_params()) . '">' . tep_template_image_button('button_submit_link.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_SUBMIT_LINK, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>

        </td>
      </tr>
<?php
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>       
    </table>
<?php
  }
?>