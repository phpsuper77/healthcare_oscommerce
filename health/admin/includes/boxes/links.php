<?php
/*
  $Id: links.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- links //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LINKS,
                     'link'  => tep_href_link(tep_selected_file(FILENAME_LINKS) , 'selected_box=links'));

 if ($selected_box == 'links' || $menu_dhtml == true) {
    $contents[] = array('text'  => 
                                   tep_admin_files_boxes(FILENAME_LINKS, BOX_LINKS_LINKS) .
                                   tep_admin_files_boxes(FILENAME_LINK_CATEGORIES, BOX_LINKS_LINK_CATEGORIES) .
                                   tep_admin_files_boxes(FILENAME_LINKS_CONTACT, BOX_LINKS_LINKS_CONTACT));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- links_eof //-->