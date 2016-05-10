<?php
/*
  $Id: reports.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

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
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => tep_href_link(tep_selected_file('reports.php', BOX_REPORTS_PRODUCTS_VIEWED), 'selected_box=reports'));

  if ($selected_box == 'reports' || $menu_dhtml == true) {
    $contents[] = array('text'  => 
//Admin begin
                        tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED) .
                        tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED) .
                        tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS, BOX_REPORTS_ORDERS_TOTAL) .
                        tep_admin_files_boxes(FILENAME_STATS_MONTHLY_SALES, BOX_REPORTS_MONTHLY_SALES) .
                        tep_admin_files_boxes(FILENAME_STATS_AMAZON_SOAP, 'Amazon SOAP').
                        tep_admin_files_boxes(FILENAME_STATS_EBAY, 'Ebay SOAP').
                        (SALES_STATS_DISPLAY == 'True' ? tep_admin_files_boxes(FILENAME_SALES_STATISTICS,BOX_REPORTS_SALES) : '') .
                        (RECOVER_CART_SALES_DISPLAY == 'True' ? tep_admin_files_boxes(FILENAME_STATS_RECOVER_CART_SALES, BOX_REPORTS_RECOVER_CART_SALES) : '')
                        );
    if (SEARCH_ENGINE_STATS == 'True') {
      $contents[sizeof($contents) - 1]['text'] .= tep_admin_files_boxes(FILENAME_SEARCH_STATISTICS, BOX_TOOLS_SEARCH_STATISTICS);
    }
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_eof //-->