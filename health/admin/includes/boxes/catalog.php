<?php
/*
  $Id: catalog.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link(tep_selected_file('catalog.php', FILENAME_CATEGORIES), 'selected_box=catalog'));

  if ($selected_box == 'catalog' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
          tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS) .
          tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
          ((PRODUCTS_INVENTORY == 'True') ? tep_admin_files_boxes(FILENAME_INVENTORY, BOX_CATALOG_INVENTORY) : '') .
          ((PRODUCTS_PROPERTIES == 'True') ? tep_admin_files_boxes(FILENAME_PROPERTIES, BOX_CATALOG_PROPERTIES) : '') .
          tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS) .
          ((BRAND_MANAGER_DISPLAY == 'True') ? tep_admin_files_boxes(FILENAME_BRAND_MANAGER, BOX_CATALOG_BRAND_MANAGER) : '') .
          tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS) .
          tep_admin_files_boxes(FILENAME_SPECIALS, BOX_CATALOG_SPECIALS) .
          ((XML_DUMP_ENABLE == "True") ? tep_admin_files_boxes(FILENAME_RESTORE_XML_DATA, BOX_CATALOG_XML_RESTORE) : '').
          tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS) .
          tep_admin_files_boxes(FILENAME_GIVE_AWAY, BOX_CATALOG_GIVE_AWAY) .
          tep_admin_files_boxes(FILENAME_EASYPOPULATE, BOX_CATALOG_EASYPOPULATE) .
          tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE) .
          tep_admin_files_boxes(FILENAME_SALEMAKER, BOX_CATALOG_SALEMAKER) .
          tep_admin_files_boxes(FILENAME_FEATURED, BOX_CATALOG_FEATURED) .
          tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED) . 
          tep_admin_files_boxes(FILENAME_GSM, BOX_CATALOG_GSM) .
          tep_admin_files_boxes(FILENAME_DATAFEEDS, BOX_CATALOG_DATAFFEDS) .
          (SEARCH_ENGINE_UNHIDE=='True' ? tep_admin_files_boxes(FILENAME_META_TAGS, BOX_META_TAGS) : '') .
          (GERMAN_SITE == 'True' ? tep_admin_files_boxes(FILENAME_DEFINE_AGB, BOX_CATALOG_DEFINE_AGB) : '')
          );
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
