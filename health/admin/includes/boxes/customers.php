<?php
/*
  $Id: customers.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => tep_href_link(tep_selected_file(FILENAME_CUSTOMERS), 'selected_box=customers'));
  if ($selected_box == 'customers' || $menu_dhtml == true) {
    $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS) .
                                   tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS) .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER) .
                                   (CUSTOMERS_GROUPS_ENABLE=='True'?tep_admin_files_boxes(FILENAME_GROUPS, BOX_CUSTOMERS_GROUPS):'').
                                   tep_admin_files_boxes(FILENAME_SUBSCRIBERS,BOX_CUSTOMERS_SUBSCRIBERS)
    );
                                   
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
