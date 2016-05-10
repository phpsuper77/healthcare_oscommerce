    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
      <tr>
        <td>
<?php
   if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
     $str = $category['categories_heading_title'];
   } else {
     $str = $category['categories_name'];
   }
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>$str), array('params'=> 'align=right', 'text' => ''));
  new contentPageHeading($infobox_contents);
?>         
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right"><?php if (is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $category['categories_image'])) echo tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          <?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
          <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $category['categories_description']; ?></td>
          </tr>
          <?php } ?>
        </table></td>
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
<?php
    $categories_query = tep_db_query("select c.categories_id, if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status = 1 and cd.affiliate_id = 0 order by sort_order, categories_name");

    $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id']);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      echo '                <td align="center" class="smallText" width="' . $width . '" valign="top" height=100%><table border=0 cellpadding="0" cellspacing="2" width="100%" height="100%"><tr><td height="100%" align="center" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . (is_file(DIR_FS_CATALOG . '/' . DIR_WS_IMAGES . $categories['categories_image'])?tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT):tep_draw_separator('spacer.gif', SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT)) . '</a></td></tr><tr><td class="smallText" align="center"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . $categories['categories_name'] . '</a></td></tr></table></td>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
      }
    }

?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>
          </tr>
        </table></td>
      </tr>
      
<?php 
if (SUPPLEMENT_STATUS == 'True'){
  include(DIR_WS_MODULES . 'xsell_cat_products.php');
}
?>      
    </table>
