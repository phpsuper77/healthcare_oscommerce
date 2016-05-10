<?php
/*
  $Id: affiliate.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

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

  $heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                     'link'  => tep_href_link(tep_selected_file('affiliate.php', FILENAME_AFFILIATE_SUMMARY),  tep_get_all_get_params(array('selected_box')) . 'selected_box=affiliate'));

  if ($selected_box == 'affiliate' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_AFFILIATE_SUMMARY, BOX_AFFILIATE_SUMMARY) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE, BOX_AFFILIATE) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE_PAYMENT, BOX_AFFILIATE_PAYMENT) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE_SALES, BOX_AFFILIATE_SALES) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE_CLICKS, BOX_AFFILIATE_CLICKS) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE_BANNER_MANAGER, BOX_AFFILIATE_BANNERS) .
                                   tep_admin_files_boxes(FILENAME_AFFILIATE_CONTACT, BOX_AFFILIATE_CONTACT).
                                   tep_admin_files_boxes(FILENAME_DEFINE_AFFILIATE_INFO, BOX_AFFILIATE_DEFINE_AFFILIATE_INFO) .
                                   tep_admin_files_boxes(FILENAME_DEFINE_AFFILIATE_TERMS, BOX_AFFILIATE_DEFINE_AFFILIATE_TERMS)
                       );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- affiliates_eof //-->