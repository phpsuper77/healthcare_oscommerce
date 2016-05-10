<?php
/*
  $Id: create_order.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
*/


  require('includes/application_top.php');

// #### Get Available Customers

	$query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname DESC");
    $result = $query;

	
	if (tep_db_num_rows($result) > 0)
	{
		// Query Successful
		$SelectCustomerBox = "<select name='Customer'>\n";
		while($db_Row = tep_db_fetch_array($result))
		{ $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
		  if(IsSet($HTTP_GET_VARS['Customer']) and $db_Row["customers_id"]==$HTTP_GET_VARS['Customer'])
			$SelectCustomerBox .= " SELECTED ";
		  //$SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . " - " . $db_Row["customers_id"] . "</option>\n"; 
		  $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";
		
		}
		
		$SelectCustomerBox .= "</select>\n";
	}

	if(IsSet($HTTP_GET_VARS['Customer']))
	{
  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
  $account = tep_db_fetch_array($account_query);
  $customer = $account['customers_id'];
  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
  $address = tep_db_fetch_array($address_query);
  //$customer = $account['customers_id'];
	}

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);
 

// #### Generate Page
	?>	
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->


<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->

<td valign="top">
		<table border='0' bgcolor='#7c6bce' width='100%'>
			<tr><td class=main><font color='#ffffff'><b>STEP 1 - Choose a customer & check their details</b></td></tr>
		</table>
		<table border='0' cellpadding='7'><tr><td class="main" valign="top">

<?php
	print "<form action='$PHP_SELF' method='GET'>\n";
	print "<table border='0'>\n";
	print "<tr>\n";
	print "<td><font class=main><b>Select Customer to Create an Order for:</b></font><br>$SelectCustomerBox</td>\n";
	print "<td valign='bottom'><input type='submit' value=\"Select Customer\"></td>\n";
	print "</tr>\n";
	print "</table>\n";
	print "</form>\n";
?>	
		<tr>
        
    <td width="100%" valign="top"><?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', '', '') . tep_draw_hidden_field('customers_id', $account->customers_id); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
								  
	 </tr> <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_CREATE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php

//onSubmit="return check_form();"

  require(DIR_WS_MODULES . 'create_order_details.php');
 
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td class="main" align="right"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
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