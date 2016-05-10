<?php
/*
  $Id: vendor.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_VENDOR,
                     'link'  => tep_href_link(tep_selected_file(FILENAME_VENDOR_SUMMARY),  tep_get_all_get_params(array('selected_box')) . 'selected_box=vendor'));

  if ($selected_box == 'vendor' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_VENDOR_SUMMARY, BOX_VENDOR_SUMMARY) .
                                   tep_admin_files_boxes(FILENAME_VENDOR, BOX_VENDOR) .
                                   tep_admin_files_boxes(FILENAME_VENDOR_CONTACT, BOX_VENDOR_CONTACT) .
                                   tep_admin_files_boxes(FILENAME_VENDOR_PAYMENT, BOX_VENDOR_PAYMENT) .
                                   tep_admin_files_boxes(FILENAME_VENDOR_SALES, BOX_VENDOR_SALES) .
                                   tep_admin_files_boxes(FILENAME_DEFINE_VENDOR_INFO, BOX_VENDOR_DEFINE_VENDOR_INFO) .
                                   tep_admin_files_boxes(FILENAME_DEFINE_VENDOR_TERMS, BOX_VENDOR_DEFINE_VENDOR_TERMS)
                       );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliates_eof //-->