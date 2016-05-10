<?php
/*
  $Id: paypalipn.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Paypal IPN v0.981 for Milestone 2
  Copyright (c) 2003 Pablo Pasqualino
  pablo_osc@osmosisdc.com
  http://www.osmosisdc.com

  Released under the GNU General Public License
*/
?>
<!-- paypalipn //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PAYPALIPN_ADMIN,
                     'link'  => tep_href_link(tep_selected_file('paypalipn.php', FILENAME_PAYPALIPN_TRANSACTIONS), tep_get_all_get_params(array('selected_box')) . 'selected_box=paypalipn'));

  if ($selected_box == 'paypalipn' || $menu_dhtml == true) {
    $contents[] = array('text'  => 
      tep_admin_files_boxes(FILENAME_PAYPALIPN_TRANSACTIONS, BOX_PAYPALIPN_ADMIN_TRANSACTIONS) .
      tep_admin_files_boxes(FILENAME_PAYPALIPN_TESTS, BOX_PAYPALIPN_ADMIN_TESTS));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- paypalipn_eof //-->