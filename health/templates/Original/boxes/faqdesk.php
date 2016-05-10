<?php
  // Return true if the category has subcategories
  // TABLES: categories

  function FAQDesk_box_has_category_subcategories($category_id) {
    $child_faqdesk_category_query = tep_db_query("select count(*) as count from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_faqdesk_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }


  // Return the number of products in a category
  // TABLES: products, products_to_categories, categories
  function FAQDesk_box_count_products_in_category($category_id, $include_inactive = false) {
    $products_faqdesk_count = 0;
    if ($include_inactive) {
      $products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES . " p2c where p.faqdesk_id = p2c.faqdesk_id and p2c.categories_id = '" . (int)$category_id . "'");
    } else {
      $products_faqdesk_faqdesk_query = tep_db_query("select count(*) as total from " . TABLE_FAQDESK . " p, " . TABLE_FAQDESK_TO_CATEGORIES . " p2c where p.faqdesk_id = p2c.faqdesk_id and p.faqdesk_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
    }
    $products_faqdesk = tep_db_fetch_array($products_faqdesk_faqdesk_query);
    $products_faqdesk_count += $products_faqdesk['total'];

    if (USE_RECURSIVE_COUNT == 'true') {
      $child_categories_query = tep_db_query("select categories_id from " . TABLE_FAQDESK_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
      if (tep_db_num_rows($child_categories_query)) {
        while ($child_categories = tep_db_fetch_array($child_categories_query)) {
          $products_faqdesk_count += FAQDesk_box_count_products_in_category($child_categories['categories_id'], $include_inactive);
        }
      }
    }

    return $products_faqdesk_count;
  }


  function FAQDesk_show_category($counter) {

    global $foo_faqdesk, $categories_faqdesk_string, $id_faq;

    if (SHOW_COUNTS == 'true') {
      $products_faqdesk_in_category = FAQDesk_box_count_products_in_category($counter);
    }

    if ($foo_faqdesk[$counter]['level'] == 0){
      $categories_faqdesk_string .= '<tr><td>' . tep_draw_separator("spacer.gif", "1", "5"). '</td></tr>'. "\n";
      if (isset($id_faq) && (in_array($counter, $id_faq)) ) {
        $categories_faqdesk_string .= '<tr>'. "\n";
        if (($foo_faqdesk[$counter]['next_id'] != false) && ($foo_faqdesk[$foo_faqdesk[$counter]['next_id']]['parent'] == $counter)){
          $categories_faqdesk_string .= '<td><table cellspacing=0 cellpadding=2 width="100%" border=0><tr><td colspan=2 class=infoboxcontents><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='.$counter ). '">'.$foo_faqdesk[$counter]['name']. '</a>'.(FAQDesk_box_has_category_subcategories($counter)?'&nbsp;-&gt;':'').($products_faqdesk_in_category>0?'&nbsp;(' .$products_faqdesk_in_category. ')':'') .'</td></tr>'. "\n";
        }else{
          $categories_faqdesk_string .= '<tr><td class=infoBoxContents><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='.$counter ). '">'.$foo_faqdesk[$counter]['name'].'</a>'.(FAQDesk_box_has_category_subcategories($counter)?'&nbsp;-&gt;':'').($products_faqdesk_in_category>0?'&nbsp;(' .$products_faqdesk_in_category. ')':'') .'</td></tr>' . "\n";
        }
      }else{
        $categories_faqdesk_string .= '<tr><td class=infoBoxContents><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='.$counter ). '">'.$foo_faqdesk[$counter]['name'].'</a>'.(FAQDesk_box_has_category_subcategories($counter)?'&nbsp;-&gt;':'').($products_faqdesk_in_category>0?'&nbsp;(' .$products_faqdesk_in_category. ')':'') .'</td></tr>' . "\n";
      }
    }else{
      //non top categories
      if (isset($id_faq) && in_array($counter, $id_faq)){
        /* Image for nested active categories */
        $categories_faqdesk_string .= '<tr><td>'.tep_draw_separator("spacer.gif", "8", "8") . '</td><td class=infoBoxContents width="100%"><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='.$foo_faqdesk[$counter]['path']). '">'.$foo_faqdesk[$counter]['name'].'</a>'.(FAQDesk_box_has_category_subcategories($counter)?'&nbsp;-&gt;':'').($products_faqdesk_in_category>0?'&nbsp;(' .$products_faqdesk_in_category. ')':'') .'</td></tr>'. "\n";
        if (($foo_faqdesk[$counter]['next_id'] != false) && ($foo_faqdesk[$foo_faqdesk[$counter]['next_id']]['parent'] == $counter)){
          $categories_faqdesk_string .= '<tr><td width="8">'.tep_draw_separator("spacer.gif", "1", "1").'</td><td width="100%"><table cellspacing=0 cellpadding=0 width="100%" border=0>'. "\n";
        }
      }else{
        /* Image for inactive nested categories */
        $categories_faqdesk_string .= '<tr><td>'.tep_draw_separator("spacer.gif", "8", "8") . '</td><td class=infoBoxContents width="100%"><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_FAQDESK_INDEX, 'faqPath='.$foo_faqdesk[$counter]['path'] ). '">'.$foo_faqdesk[$counter]['name'].'</a>'.(FAQDesk_box_has_category_subcategories($counter)?'&nbsp;-&gt;':'').($products_faqdesk_in_category>0?'&nbsp;(' .$products_faqdesk_in_category. ')':'') .'</td></tr>'. "\n";
      }
      if ($foo_faqdesk[$counter]['next_id'] == false){
        for ($i=1;$i<=$foo_faqdesk[$counter]['level'];$i++){
          $categories_faqdesk_string .= '</table>'. "\n";
        }
      }else if (($foo_faqdesk[$counter]['next_id'] != false) && ($foo_faqdesk[$foo_faqdesk[$counter]['next_id']]['level'] < $foo_faqdesk[$counter]['level'])){
        $j = $foo_faqdesk[$counter]['level'] - $foo_faqdesk[$foo_faqdesk[$counter]['next_id']]['level'];
        for ($i=0;$i<$j;$i++){
          $categories_faqdesk_string .= '</table>'. "\n";
        }
      }
    }

    if ($foo_faqdesk[$counter]['next_id']) {
      FAQDesk_show_category($foo_faqdesk[$counter]['next_id']);
    }
  }
 

?>

<!-- faqdesk categories //-->
	<tr>
		<td class="infoBoxCell">
<?php
if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
  new infoBoxImageHeading($info_box_contents);
}else{

  $info_box_contents = array();
  $info_box_contents[] = array('text'  => BOX_HEADING_FAQDESK_CATEGORIES);
  $infoboox_class_heading = $infobox_class . 'Heading';
  if (class_exists($infoboox_class_heading)){
    new $infoboox_class_heading($info_box_contents, false, false);
  }else{
    new infoBoxHeading($info_box_contents, false, false);
  }    
}

$categories_faqdesk_string = '';
unset($prev_id);
unset($counter);
$categories_faqdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");

while ($categories_faqdesk = tep_db_fetch_array($categories_faqdesk_query))  {
  $foo_faqdesk[$categories_faqdesk['categories_id']] = array(
  'name' => $categories_faqdesk['categories_name'],
  'parent' => $categories_faqdesk['parent_id'],
  'level' => 0,
  'path' => $categories_faqdesk['categories_id'],
  'next_id' => false
  );

  if (isset($prev_id)) {
    $foo_faqdesk[$prev_id]['next_id'] = $categories_faqdesk['categories_id'];
  }

  $prev_id = $categories_faqdesk['categories_id'];

  if (!isset($counter)) {
    $counter = $categories_faqdesk['categories_id'];
  }
}

if ($faqPath) {
  $new_path = '';
  $id_faq = split('_', $faqPath);
  reset($id_faq);
  while (list($key, $value) = each($id_faq)) {
    unset($prev_id);
    unset($first_id);

    $categories_faqdesk_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_FAQDESK_CATEGORIES . " c, " . TABLE_FAQDESK_CATEGORIES_DESCRIPTION . " cd where c.catagory_status = '1' and c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");

    $category_faqdesk_check = tep_db_num_rows($categories_faqdesk_query);
    if ($category_faqdesk_check > 0) {
      $new_path .= $value;
      while ($row = tep_db_fetch_array($categories_faqdesk_query)) {
        $foo_faqdesk[$row['categories_id']] = array(
        'name' => $row['categories_name'],
        'parent' => $row['parent_id'],
        'level' => $key+1,
        'path' => $new_path . '_' . $row['categories_id'],
        'next_id' => false
        );

        if (isset($prev_id)) {
          $foo_faqdesk[$prev_id]['next_id'] = $row['categories_id'];
        }

        $prev_id = $row['categories_id'];

        if (!isset($first_id)) {
          $first_id = $row['categories_id'];
        }

        $last_id = $row['categories_id'];
      }
      $foo_faqdesk[$last_id]['next_id'] = $foo_faqdesk[$value]['next_id'];
      $foo_faqdesk[$value]['next_id'] = $first_id;
      $new_path .= '_';
    } else {
      break;
    }
  }
}

$categories_faqdesk_string = '<table cellspacing=0 cellpadding=0 width="100%" border=0>';

FAQDesk_show_category($counter);

$categories_faqdesk_string .= '</table>';

$info_box_contents = array();
$info_box_contents[] = array(
                             'text'  => $categories_faqdesk_string
                            );

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>

		</td>
	</tr>
<!-- faqdesk categories_eof //-->