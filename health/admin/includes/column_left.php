<?php
/*
  $Id: column_left.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

//Admin begin
//  require(DIR_WS_BOXES . 'configuration.php');
//  require(DIR_WS_BOXES . 'catalog.php');
//  require(DIR_WS_BOXES . 'modules.php');
//  require(DIR_WS_BOXES . 'customers.php');
//  require(DIR_WS_BOXES . 'taxes.php');
//  require(DIR_WS_BOXES . 'localization.php');
//  require(DIR_WS_BOXES . 'reports.php');
//  require(DIR_WS_BOXES . 'tools.php');
//DWD Modify: Information Page Unlimited 1.1f - PT
// require(DIR_WS_BOXES . 'information.php');
//DWD Modify End
  if    (MENU_DHTML != true) {
 define('BOX_WIDTH', 125);

  if (tep_admin_check_boxes('administrator.php') == true) {
    require(DIR_WS_BOXES . 'administrator.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('configuration.php') == true) {
    require(DIR_WS_BOXES . 'configuration.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
  if (tep_admin_check_boxes('catalog.php') == true) {
    require(DIR_WS_BOXES . 'catalog.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('information.php') == true) {
    require(DIR_WS_BOXES . 'information.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
  if (tep_admin_check_boxes('newsdesk.php') == true) {
    require(DIR_WS_BOXES . 'newsdesk.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
  if (tep_admin_check_boxes('faqdesk.php') == true) {
    require(DIR_WS_BOXES . 'faqdesk.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
  if (tep_admin_check_boxes('modules.php') == true) {
    require(DIR_WS_BOXES . 'modules.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  // ICW CREDIT CLASS Gift Voucher Addittion
  if (tep_admin_check_boxes('gv_admin.php') == true) {
    require(DIR_WS_BOXES . 'gv_admin.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  // END ICW CREDIT CLASS Gift Voucher Addittion
  if (tep_admin_check_boxes('customers.php') == true) {
    require(DIR_WS_BOXES . 'customers.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('paypalipn.php') == true) {
    require(DIR_WS_BOXES . 'paypalipn.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('affiliate.php') == true) {
    require(DIR_WS_BOXES . 'affiliate.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (VENDOR_ENABLED == 'True'){
    if (tep_admin_check_boxes('vendor.php') == true) {
      require(DIR_WS_BOXES . 'vendor.php');
      echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
    }
  }
  if (tep_admin_check_boxes('taxes.php') == true) {
    require(DIR_WS_BOXES . 'taxes.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('localization.php') == true) {
    require(DIR_WS_BOXES . 'localization.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('design_controls.php') == true) {
    require(DIR_WS_BOXES . 'design_controls.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
   if (tep_admin_check_boxes('links.php') == true) {
    require(DIR_WS_BOXES . 'links.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }
 

  if (tep_admin_check_boxes('reports.php') == true) {
    require(DIR_WS_BOXES . 'reports.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  } 
  if (tep_admin_check_boxes('tools.php') == true) {
    require(DIR_WS_BOXES . 'tools.php');
    echo '<tr><td height="1" colspan="2"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>';
  }

  //+++AUCTIONBLOX.COM
if (AUCTION_BLOX_ENABLED == 'True'){
  if (tep_admin_check_boxes('auctionblox.php') == true) {
    //+++AUCTIONBLOX.COM
    require(DIR_FS_CATALOG_MODULES . 'auctionblox/external/oscommerce/includes/boxes/auctionblox.php');
    //+++AUCTIONBLOX.COM
    //require(DIR_WS_BOXES . 'auctionblox.php'); 
  }
}
  //+++AUCTIONBLOX.COM 


//Admin end
}
?>
