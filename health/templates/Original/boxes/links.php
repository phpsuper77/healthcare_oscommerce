<?php
/*
  $Id: links.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/
  require_once(DIR_WS_FUNCTIONS . 'links.php');
  
  function tep_show_links_category($counter) {
    global $foo, $categories_string, $id, $aa, $lPath_array;

    if ($foo[$counter]['level'] == '0'){
			if (isset($lPath_array) && in_array($counter, $lPath_array)){
        /* Put here image for active top category */
				$categories_string .= '<tr valign="top">'. "\n";
				if (($foo[$counter]['next_id'] != false) && ($foo[$foo[$counter]['next_id']]['parent'] == $counter)){
					$categories_string .= '<td><a class="infoBoxLink" href="'.tep_href_link(FILENAME_LINKS, 'lPath='.$counter ). '">'.$foo[$counter]['name'].'</a></td></tr>'. "\n";
				}else{
					$categories_string .= '<td class=infoBoxContents width="100%"><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_LINKS, 'lPath='.$counter ). '">'.$foo[$counter]['name'].'</a></td></tr>'. "\n";
				}
			}else{
        /* Put here image for inactive top category */
				$categories_string .= '<tr valign="top"><td class=infoBoxContents width="100%"><a  class="infoBoxLink" href="'.tep_href_link(FILENAME_LINKS, 'lPath='.$counter ). '">'.$foo[$counter]['name'].'</a></td></tr>' . "\n";
			}
		}
		if ($foo[$counter]['next_id']) {
      tep_show_links_category($foo[$counter]['next_id']);
    }

  }
?>
<!-- links_categories //-->
<?php
  $aa = 0;
  $foo = array();

  $categories_string = '';

  $categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES . " lc, " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lc.link_categories_id = lcd.link_categories_id and lc.link_categories_status = 1 and lcd.language_id='" . (int)$languages_id ."' order by link_categories_sort_order, lcd.link_categories_name");
  unset($prev_id);
  while ($categories = tep_db_fetch_array($categories_query))  {
    $foo[$categories['link_categories_id']] = array(
                                        'name' => $categories['link_categories_name'],
                                        'parent' => 0,
                                        'level' => 0,
                                        'path' => $categories['link_categories_id'],
                                        'next_id' => false
                                       );

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories['link_categories_id'];
    }

    $prev_id = $categories['link_categories_id'];
    
    if (!isset($first_element_links)) {
      $first_element_links = $categories['link_categories_id'];
    }
  }
  $lPath_array = split('_', $HTTP_GET_VARS['lPath']);
  //------------------------
  $top_links = '';
  $listing_sql = "select l.links_id, l.links_url, ld.links_title from " . TABLE_LINKS_DESCRIPTION . " ld, " . TABLE_LINKS . " l, " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2lc where l.links_status = '2' and l.links_id = l2lc.links_id and ld.links_id = l2lc.links_id and ld.language_id = '" . (int)$languages_id . "' and l2lc.link_categories_id = 0 order by l.links_rating";
  $links_r = tep_db_query($listing_sql);
  while( $links = tep_db_fetch_array($links_r) ){
		$top_links .= '<tr valign="top"><td class=infoBoxContents width="100%"><a class="infoBoxLink" href="'.tep_get_links_url($links['links_id']). '" target="_blank" rel="nofollow">'.$links['links_title'].'</a></td></tr>' . "\n";
  }
  if ( !empty($top_links) || count($foo)>0 ) {

    if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
      new infoBoxImageHeading($info_box_contents);
    }else{

      echo '<tr><td class="infoBoxCell">';

      $info_box_contents = array();
      $info_box_contents[] = array('text'  => BOX_HEADING_LINKS_CATEGORIES);
      $infoboox_class_heading = $infobox_class . 'Heading';
      if (class_exists($infoboox_class_heading)){
        new $infoboox_class_heading($info_box_contents, false, false);
      }else{
        new infoBoxHeading($info_box_contents, false, false);
      }    
    }

  	$categories_string = '<table cellspacing=0 cellpadding=0 width="100%" border=0>';
    tep_show_links_category($first_element_links);
    $categories_string .= $top_links;
  	$categories_string .= '</table>';
  
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $categories_string
                                );
  
    if (class_exists($infobox_class)){
      new $infobox_class($info_box_contents);
    }else{
      new infoBox($info_box_contents);
    }
    
    echo '</td></tr>';
    
  }
?>
<!-- links_categories_eof //-->
