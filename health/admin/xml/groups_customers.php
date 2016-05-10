<?php
/*
  $Id: groups_customers.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ( !($HTTP_GET_VARS['gID'] > 0) )
  {
    tep_redirect(tep_href_link(FILENAME_GROUPS));
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'add':
        tep_db_query("update " . TABLE_CUSTOMERS . " set groups_id = '" . (int)$HTTP_GET_VARS['gID'] . "' where customers_id = '" . $HTTP_GET_VARS['customers_id'] . "'");
        break;
      case 'del':
        tep_db_query("update " . TABLE_CUSTOMERS . " set groups_id = '0' where customers_id = '" . $HTTP_GET_VARS['customers_id'] . "'");
        break;
    }
    tep_redirect(tep_href_link(FILENAME_GROUPS_CUSTOMERS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $HTTP_GET_VARS['gID'] . '&filter=' . $HTTP_GET_VARS['filter']));
  }

  $customers_id_in_group = array();
  $data_query = tep_db_query("select g.groups_name, ñ.customers_id from " . TABLE_GROUPS . " g, " . TABLE_CUSTOMERS . " ñ where g.groups_id = ñ.groups_id and g.groups_id = '" . (int)$HTTP_GET_VARS['gID'] . "'");
  while ($data = tep_db_fetch_array($data_query))
  {
    $group_name = $data['groups_name'];
    $customers_id_in_group[] = $data['customers_id'];
  }

  $filter = $HTTP_GET_VARS['filter'];
  $query = tep_db_query("select c.customers_id, customers_firstname, customers_lastname, count(a.address_book_id) from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a where a.customers_id = c.customers_id and c.customers_default_address_id = a.address_book_id " . (strlen($filter) > 0 ? ' and (customers_lastname like "%' . $filter . '%" or customers_firstname like "%' . $filter . '%" or customers_email_address like "%' . $filter . '%" or entry_company like "%' . $filter . '%" or entry_street_address like "%' . $filter . '%" or entry_suburb like "%' . $filter . '%" or entry_postcode like "%' . $filter . '%" or entry_city like "%' . $filter . '%" or entry_state like "%' . $filter . '%" or customers_telephone like "%' . $filter . '%" or customers_fax like "%' . $filter . '%" )' : '') . " group by c.customers_id order by customers_lastname");

  $customers_in_group_options = '';
  $customers_not_in_group_options = '';
  while ($db_Row = tep_db_fetch_array($query))
  {
    if (in_array ($db_Row["customers_id"], $customers_id_in_group))
    {
      $customers_in_group_options .= "<option value=\"" . $db_Row["customers_id"] . "\">" . $db_Row["customers_lastname"] . ", " . $db_Row["customers_firstname"] . "</option>";
    }
    else
    {
      $customers_not_in_group_options .= "<option value=\"" . $db_Row["customers_id"] . "\">" . $db_Row["customers_lastname"] . ", " . $db_Row["customers_firstname"] . "</option>";
    }
  }
?>  
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php
$header_title_menu = BOX_HEADING_CUSTOMERS;
$header_title_menu_link = tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
$header_title_submenu = $group_name;
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
  
<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
          </table>
        </td>
        <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td class="main" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
<!--<?php /* ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Customers of Group '<?php echo $group_name; ?>'</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php */ ?>-->
      <tr>
        <td>
          <form action="<?php echo tep_href_link(FILENAME_GROUPS_CUSTOMERS); ?>" method="get">
          <input type="hidden" name="page" value="<?php echo $HTTP_GET_VARS['page']; ?>">
          <input type="hidden" name="gID" value="<?php echo $HTTP_GET_VARS['gID']; ?>">
          <table border="0">
            <tr>
              <td><font class=main><b>Filter customers:</b></font> <input type="text" name="filter" value="<?php echo htmlspecialchars($filter); ?>"></td>
              <td valign="bottom"><input type="submit" value="Filter Customers"></td>
            </tr>
          </table>
          </form>
          <form action="<?php echo tep_href_link(FILENAME_GROUPS_CUSTOMERS); ?>" method="get">
          <input type="hidden" name="action" value="del">
          <input type="hidden" name="page" value="<?php echo $HTTP_GET_VARS['page']; ?>">
          <input type="hidden" name="gID" value="<?php echo $HTTP_GET_VARS['gID']; ?>">
          <input type="hidden" name="filter" value="<?php echo $HTTP_GET_VARS['filter']; ?>">
          <table border="0">
            <tr>
              <td><font class=main><b>Customers in Group:</b></font></td>
              <td><select name="customers_id"><?php echo $customers_in_group_options; ?></select></td>
              <td valign="bottom"><?php echo tep_image_submit('button_delete.gif', IMAGE_DELETE); ?></td>
            </tr>
          </table>
          </form>
          <form action="<?php echo tep_href_link(FILENAME_GROUPS_CUSTOMERS); ?>" method="get">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="page" value="<?php echo $HTTP_GET_VARS['page']; ?>">
          <input type="hidden" name="gID" value="<?php echo $HTTP_GET_VARS['gID']; ?>">
          <input type="hidden" name="filter" value="<?php echo $HTTP_GET_VARS['filter']; ?>">
          <table border="0">
            <tr>
              <td><font class=main><b>Customers not in Group:</b></font></td>
              <td><select name="customers_id"><?php echo $customers_not_in_group_options; ?></select></td>
              <td valign="bottom"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?></td>
            </tr>
          </table>
          </form>
        </td>
      </tr>
      <tr>
        <td><a href="<?php echo tep_href_link(FILENAME_GROUPS, 'page=' . $HTTP_GET_VARS['page'] . '&gID=' . $HTTP_GET_VARS['gID']); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
      </tr>
    </table></td>        
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php 
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>