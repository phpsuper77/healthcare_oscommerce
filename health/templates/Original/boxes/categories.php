<?php
/*
  $Id: categories.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/

  function tep_show_category_old($counter) {
    global $foo, $categories_string, $id, $aa, $cPath_array;
    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
    }
    if ($foo[$counter]['level'] == '0') {
                        // top categories
                        $categories_string .= '<tr><td colspan="2">' . tep_draw_separator("spacer.gif", "1", "5"). '</td></tr>'. "\n";
                        if (isset($cPath_array) && in_array($counter, $cPath_array)) {
        /* Put here image for active top category */
                                $categories_string .= '<tr valign="top">'. "\n";
                                if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['parent'] == $counter)) {
                                        $categories_string .= '

                                         <td><a  class=infoBoxLink href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'<table cellspacing=0 cellpadding=2 width="100%" border=0>'. "\n";
                                } else {
                                        $categories_string .= '<td class=infoBoxContents width="100%"><a class=infoBoxLink href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name'].'</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</td></tr>'. "\n";
                                }
                        } else {
        /* Put here image for inactive top category */
                                $categories_string .= '<tr valign="top"><td class=infoBoxContents width="100%"><a  class=infoBoxLink href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name'].'</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</td></tr>' . "\n";
                        }
                } else {
                        //non top categories
                        if (isset($cPath_array) && in_array($counter, $cPath_array)) {
          /* Image for nested active categories */
          $categories_string .= '<tr><td>'.tep_draw_separator("spacer.gif", "8", "8") . '</td><td class=infoBoxContents width="100%"><a  class=infoBoxLink href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$foo[$counter]['path']). '">'.$foo[$counter]['name'].'</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</td></tr>'. "\n";
                                if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['parent'] == $counter)) {
                                        $categories_string .= '<tr><td width="8">'.tep_draw_separator("spacer.gif", "1", "1").'</td><td width="100%"><table cellspacing=0 cellpadding=2 width="100%" border=0>'. "\n";
                                }
                        } else {
          /* Image for inactive nested categories */
          $categories_string .= '<tr><td>'.tep_draw_separator("spacer.gif", "8", "8") . '</td><td class=infoBoxContents width="100%"><a  class=infoBoxLink href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$foo[$counter]['path'] ). '">'.$foo[$counter]['name'].'</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</td></tr>'. "\n";
                        }
                        if ($foo[$counter]['next_id'] == false) {
                                for ($i=1;$i<=$foo[$counter]['level'];$i++) {
                                        $categories_string .= '</table></td></tr>'. "\n";
                                }
                        }else if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['level'] < $foo[$counter]['level'])) {
                                $j = $foo[$counter]['level'] - $foo[$foo[$counter]['next_id']]['level'];
                                for ($i=0;$i<$j;$i++) {
                                        $categories_string .= '</table></td></tr>'. "\n";
                                }
                        }
                }

    if ($foo[$counter]['next_id']) {
      tep_show_category($foo[$counter]['next_id']);
    }
  }


  function tep_show_category($counter) {
    global $foo, $categories_string, $id, $aa, $cPath_array;
    if (SHOW_COUNTS == 'true') {
      $products_in_category = tep_count_products_in_category($counter);
    }
    if ($foo[$counter]['level'] == '0') {
                        // top categories
                        if (isset($cPath_array) && in_array($counter, $cPath_array)) {
        /* Put here image for active top category */
                                if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['parent'] == $counter)) {
                                        $categories_string .= '<dt class="level-sel"><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>'. "\n" . '<dd>' . "\n" . '<dl>' . "\n";
                                } else {
                                        $categories_string .= '<dt class="level-act"><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>'. "\n";
                                }
                        } else {
        /* Put here image for inactive top category */
                                $categories_string .= '<dt><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$counter ). '">'.$foo[$counter]['name'].'</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>' . "\n";
                        }
                } else {
                        //non top categories
                        if (isset($cPath_array) && in_array($counter, $cPath_array)) {
          /* Image for nested active categories */
                                if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['parent'] == $counter)) {
          $categories_string .= '<dt class="level-sel"><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$foo[$counter]['path'] ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>'. "\n" . '<dd>' . "\n" . '<dl>' . "\n";

                                } else {
          $categories_string .= '<dt class="level-act"><a  href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$foo[$counter]['path'] ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>'. "\n";
        }
                        } else {
          $categories_string .= '<dt><a href="'.tep_href_link(FILENAME_DEFAULT, 'cPath='.$foo[$counter]['path'] ). '">'.$foo[$counter]['name']. '</a>'.($products_in_category>0?'&nbsp;(' .$products_in_category. ')':'') .'</dt>'. "\n";
                        }
                        if ($foo[$counter]['next_id'] == false) {
                                for ($i=1;$i<=$foo[$counter]['level'];$i++) {
                                        $categories_string .= '</dl></dd>'. "\n";
                                }
                        }else if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['level'] < $foo[$counter]['level'])) {
                                $j = $foo[$counter]['level'] - $foo[$foo[$counter]['next_id']]['level'];
                                for ($i=0;$i<$j;$i++) {
                                        $categories_string .= '</dl></dd>'. "\n";
                                }
                        }
                }

    if ($foo[$counter]['next_id']) {
      tep_show_category($foo[$counter]['next_id']);
    }
  }
?>
<!-- categories //-->
          <tr>
            <td class="infoBoxCell">
<?php
  $aa = 0;

/* Trueloaded original design issue. Please uncomment for Box heading output. */
/*

  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')) {
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_CATEGORIES);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)) {
      new $infoboox_class_heading($info_box_contents, false, false);
    } else {
      new infoBoxHeading($info_box_contents, false, false);
    }
  }
*/
  $categories_string = '';

  $categories_query = tep_db_query("select c.categories_id, if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, c.parent_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.parent_id = '0' and c.categories_id = cd.categories_id and c.categories_status = 1 and cd.language_id='" . (int)$languages_id ."' and cd.affiliate_id = 0 order by sort_order, categories_name");
  while ($categories = tep_db_fetch_array($categories_query))  {
    $foo[$categories['categories_id']] = array(
                                        'name' => $categories['categories_name'],
                                        'parent' => $categories['parent_id'],
                                        'level' => 0,
                                        'path' => $categories['categories_id'],
                                        'next_id' => false
                                       );

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['categories_id'];
    }

    $prev_id = $categories['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories['categories_id'];
    }
  }

  //------------------------
  if ($cPath) {
    $new_path = '';
    $id = split('_', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      unset($prev_id);
      unset($first_id);
      $categories_query = tep_db_query("select c.categories_id, if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, c.parent_id from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and c.categories_status = 1  and cd.affiliate_id = 0 and cd.language_id='" . (int)$languages_id ."' order by sort_order, categories_name");
      $category_check = tep_db_num_rows($categories_query);
      if ($category_check > 0) {
        $new_path .= $value;
        while ($row = tep_db_fetch_array($categories_query)) {
          $foo[$row['categories_id']] = array(
                                              'name' => $row['categories_name'],
                                              'parent' => $row['parent_id'],
                                              'level' => $key+1,
                                              'path' => $new_path . '_' . $row['categories_id'],
                                              'next_id' => false
                                             );

          if (isset($prev_id)) {
            $foo[$prev_id]['next_id'] = $row['categories_id'];
          }

          $prev_id = $row['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row['categories_id'];
          }

          $last_id = $row['categories_id'];
        }
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  $categories_string = '<table cellspacing=0 cellpadding=0 width="100%" border=0>' . "\n";
  $categories_string .= '<tr><td><div id="category-nav"><dl class="level1">' . "\n";

  tep_show_category($first_element);

  $categories_string .= '<dt><a href="' . tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL') . '">' . BOX_INFORMATION_ALLPRODS . '</a></dt>';
  $categories_string .= '</dl></div></td></tr>' . "\n";
  $categories_string .= '</table>' . "\n";

  echo $categories_string;
  /* Trueloaded original design issue. Please uncomment for Box output. */
  /*
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $categories_string
                              );
  if (class_exists($infobox_class)) {
    new $infobox_class($info_box_contents);
  } else {
    new infoBox($info_box_contents);
  }
  */
?>

                    </td>
          </tr>
<!-- categories_eof //-->