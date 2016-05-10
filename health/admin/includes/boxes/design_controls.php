<?php
/*
  $Id: design_controls.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php

	    $template_id_select_query = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
$template_id_select =  tep_db_fetch_array($template_id_select_query);

  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_DESIGN_CONTROLS,
                     'link'  => tep_href_link(tep_selected_file('design_controls.php', FILENAME_TEMPLATE_CONFIGURATION), 'cID=' . $template_id_select[template_id] . '&gID=' . $template_id_select[template_id] . '&selected_box=design_controls'));

//                     'link'  => tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $template_id_select[template_id] . '&selected_box=design_controls'));

  if ($selected_box == 'design_controls' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_TEMPLATE_CONFIGURATION, BOX_HEADING_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id]) .
              tep_admin_files_boxes(FILENAME_INFOBOX_CONFIGURATION, BOX_HEADING_BOXES, 'gID=' . $template_id_select[template_id]));
//    			  '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $template_id_select[template_id], 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADING_BOXES . '</a>');
//    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $template_id_select[template_id], 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADING_BOXES . '</a><br>'.
//				   '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id], 'NONSSL') . '" class="menuBoxContentLink">' . BOX_HEADING_TEMPLATE_CONFIGURATION . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_eof //-->
