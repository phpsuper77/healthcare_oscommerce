<?php
/*
$Id: create_order.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License

*/


require('includes/application_top.php');

// #### Get Available Customers

$name = $HTTP_POST_VARS['name'];
if(strlen($name) == 0)$name = $HTTP_GET_VARS['name'];
if(strlen($name)>0) {
  $str_query = "select distinct c.customers_id, customers_firstname, customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c , " . TABLE_ADDRESS_BOOK . " a where a.customers_id = c.customers_id and c.customers_default_address_id = a.address_book_id and (customers_lastname like '%" . $name . "%' or customers_firstname like '%" . $name . "%' or c.customers_email_address like '%" . $name . "%' or entry_company like '%" . $name . "%' or entry_street_address like '%" . $name . "%' or entry_suburb like '%" . $name . "%' or entry_postcode like '%" . $name . "%' or entry_city like '%" . $name . "%' or entry_state like '%" . $name . "%' or c.customers_telephone like '%" . $name . "%' or c.customers_fax like '%" . $name . "%' or c.customers_alt_telephone like '%" . $name . "%' or c.customers_alt_email_address like '%" . $name . "%' or c.customers_cell like '%" . $name . "%' ) ORDER BY customers_lastname";
  $query = tep_db_query($str_query);
} else {
  $res = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS);
  $d = tep_db_fetch_array($res);
  if ($d['total'] <= MAX_PRODUCTS_PULLDOWN_WO_FILTER){
    $str_query = "select distinct c.customers_id, customers_firstname, customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c , " . TABLE_ADDRESS_BOOK . " a where a.customers_id = c.customers_id and c.customers_default_address_id = a.address_book_id ORDER BY customers_lastname";
    $query = tep_db_query($str_query);
  } else {
    $query = tep_db_query("select '" . TEXT_APPLY_FILTER ."' as customers_lastname from " . TABLE_CUSTOMERS . " c limit 1");
  }
}
$result = $query;

if (tep_db_num_rows($result) > 0) {
  $customers = array();
  while($db_Row = tep_db_fetch_array($result)) {
    $customers[] = array('id' => $db_Row["customers_id"], 'text' => $db_Row["customers_lastname"] . "  " . $db_Row["customers_firstname"] . ' ' . $db_Row['customers_email_address']);
  }
} else {
  $customers[] = array('id' => 0, 'text' => TEXT_CUSTOMERS_NOT_FOUND);
}

$fields = array("entry_street_address", "entry_firstname", "entry_lastname", "entry_city", "entry_postcode", "entry_country_id");
if (ACCOUNT_GENDER == 'true') {
  $fields[] = "entry_gender";
}
if (ACCOUNT_COMPANY == 'true') {
  $fields[] = "entry_company";
}
if (ACCOUNT_STATE == 'true') {
  $fields[] = "entry_state";
}
if (ACCOUNT_SUBURB == 'true') {
  $fields[] = "entry_suburb";
}

$js_arrs  = 'var fields = new Array("' . implode('", "', $fields) . '");' . "\n";

foreach($fields as $field){
  $js_arrs .= 'var ' . $field . ' = new Array();' . "\n";
}

if(IsSet($HTTP_GET_VARS['Customer']))	{
  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
  $account = tep_db_fetch_array($account_query);
  $customer = $account['customers_id'];
  $own_aid = 0;
  if ($HTTP_GET_VARS['aID']>0) {
    $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where address_book_id='" . (int)$HTTP_GET_VARS['aID'] . "'");
    $own_aid = tep_db_num_rows($address_query);
    $address = tep_db_fetch_array($address_query);
    $aID = $address['address_book_id'];
  }
  if ($address['customers_id'] != $HTTP_GET_VARS['Customer']) {
    $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "' and address_book_id='" . $account['customers_default_address_id'] . "'");
    $address = tep_db_fetch_array($address_query);
    $aID = $address['address_book_id'];
  }
  $info_array = array_merge($address, $account);
  $cInfo = new objectInfo($info_array);
} else {
  $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $customers[0]['id'] . "'");
  $account = tep_db_fetch_array($account_query);
  $customer = $account['customers_id'];
  $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customers[0]['id'] . "' and address_book_id='" . $account['customers_default_address_id'] . "'");
  $address = tep_db_fetch_array($address_query);
  $aID = $address['address_book_id'];
  if (is_array($account) && sizeof($account)>0) $info_array = array_merge($address, $account);
  $cInfo = new objectInfo($info_array);
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER_PROCESS);


// #### Generate Page
	?>	
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if ( true ) {
?> 
  <title><?php echo HEADING_TITLE; ?></title>
    <base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>">
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

		<?php require('includes/form_check.js.php'); ?>
  <script >
  <!--
  <?php echo $js_arrs;?>
  function select_address(address_id, prefix){
    var f = document.create_order;
    for (i=0; i<fields.length; i++){
      if (f.elements[prefix+fields[i]].type=='text') {
        f.elements[prefix+fields[i]].value = eval(fields[i]+'[' + address_id +']');
      }
      if (f.elements[prefix+fields[i]].type=='select-one'){
        setselected(f.elements[prefix+fields[i]], eval(fields[i]+'[' + address_id +']'));
      }
      //alert(f.elements[prefix+fields[i]].name + " " + prefix+fields[i] + " " + f.elements[prefix+fields[i]] + " " + f.elements[prefix+fields[i]].type);
      if (fields[i]=='entry_gender'){
        setchecked(f.elements[prefix+fields[i]], eval(fields[i]+'[' + address_id +']'));
      }

    }
  }
  function setselected(item, val){
    for(j=0; j<item.length; j++){
      if (item.options[j].value==val) {
        item.selectedIndex = j;
        return;
      }
    }
  };
  function setchecked(item, val){
    for(j=0; j<item.length; j++){
      if (item[j].value==val) {
        item[j].checked = true;
        return;
      }
    }
  };
  function copy_address(prefix){
    // fields is global array
    var f = document.create_order;
    for (i=0; i<fields.length; i++){
      if (f.elements[prefix+fields[i]].type=='text') {
        f.elements[prefix+fields[i]].value = f.elements[fields[i]].value ;
      }
      if (f.elements[prefix+fields[i]].type=='select-one'){
        f.elements[prefix+fields[i]].selectedIndex = f.elements[fields[i]].selectedIndex;
      }
      if (fields[i]=='entry_gender'){
        copychecked(f.elements[prefix+fields[i]], f.elements[fields[i]]);
      }
    }
  };
  function copychecked(item_to, item_from){
    for(j=0; j<item_from.length; j++){
      if (item_from[j].checked) {
        item_to[j].checked = true;
        return;
      }
    }
  };

  // -->
  </script>

		</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
		<!-- header //-->
<?
$header_title_menu=BOX_HEADING_CUSTOMERS;
$header_title_menu_link= tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers');
$header_title_submenu=HEADING_TITLE;
  ?>
		<?php
		require(DIR_WS_INCLUDES . 'header.php');
		?>
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
<td width="100%" valign="top"><table border='0' cellpadding='0' cellspacing="0" width=100%>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td class="main" valign="top">

<?php
echo tep_draw_form('search', FILENAME_CREATE_ORDER, '', 'GET');
?>
	    <table border='0' >
      	<tr>
        	<td class=main><b><?php echo TEXT_FILTER_CUSTOMERS; ?></b></td>
          <td class=main><?php echo tep_draw_input_field('name')?></td>
	        <td><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH) ?></td>
	        <td><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'action=edit&redirect=neworder') . '">' . tep_image_button('button_new.gif', IMAGE_NEW) . '</a>' ?></td>
      	</tr>
<?php 
echo tep_draw_hidden_field(tep_session_name(), tep_session_id());
?>	
	</form>
<?php
echo tep_draw_form('selectcustomer', FILENAME_CREATE_ORDER, '', 'GET');
?>
      	<tr>
        	<td class=main><b><?php echo TEXT_SELECT_A_CUSTOMER; ?></b></td>
          <td class=main><?php echo tep_draw_pull_down_menu('Customer', $customers, '', 'onchange="this.form.submit();"')?></td>
	        <td colspan=2><?php echo ($customers[0]['text']!=TEXT_CUSTOMERS_NOT_FOUND?tep_image_submit('button_select.gif', IMAGE_SELECT):''); ?></td>
      	</tr>
      <tr>
        <td colspan=3><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class=main><?php 
            $address_query = tep_db_query("select ab.*, if (LENGTH(ab.entry_state), ab.entry_state, z.zone_name) as entry_state, c.countries_name  from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_COUNTRIES . " c on ab.entry_country_id=c.countries_id  and c.language_id = '" . (int)$languages_id . "' left join " . TABLE_ZONES . " z on z.zone_country_id=c.countries_id and ab.entry_zone_id=z.zone_id where customers_id = '" . $customer . "' ");
            $addresses = array();
            $js_arrs = '';
            while ($d = tep_db_fetch_array($address_query)){
              foreach($fields as $field){
                $js_arrs .= '' . $field . '[' . $d['address_book_id'] . '] = "' . $d[$field] . '";' . "\n";
              }
              $addresses[] = array('id' => $d['address_book_id'], 'text' => $d['entry_company'] . ' ' . $d['entry_firstname'] . ' ' . $d['entry_lastname'] . ' ' . $d['entry_suburb'] . ' ' . $d['entry_city'] . ' ' . $d['entry_state'] . ' ' . $d['entry_postcode'] . ' ' . $d['countries_name']);
            }
            echo '<script>' . $js_arrs . '</script>' ;
            ?></td>
          </tr>
        </table></td>
      </tr>

    	</table>
<?php 
echo tep_draw_hidden_field(tep_session_name(), tep_session_id()) . tep_draw_hidden_field('name');
?>	
	</form>
    </td>
  </tr>
  <tr>
    <td width="100%" valign="top"><?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '') . tep_draw_hidden_field('customers_id', $cInfo->customers_id); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_CUSTOMER; ?></td>
            <td class="main"><?php echo $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . ' ' . $cInfo->customers_email_address . tep_draw_hidden_field('customers_lastname', $cInfo->customers_lastname) . tep_draw_hidden_field('customers_firstname', $cInfo->customers_firstname) . tep_draw_hidden_field('customers_email_address', $cInfo->customers_email_address);?></td>
          </tr>

          <!-- added by Art. Stop -->
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_BILLING_ADDRESS . ' ' . tep_draw_pull_down_menu('aID', $addresses, $aID, 'onchange="select_address(this.options[this.selectedIndex].value, \'\');"');
; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_firstname', $cInfo->entry_firstname, 'maxlength="32"');?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('entry_lastname', $cInfo->entry_lastname, 'maxlength="32"')
?></td>
          </tr>

<?php
if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('entry_gender', 'm', false, $cInfo->entry_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('entry_gender', 'f', false, $cInfo->entry_gender) . '&nbsp;&nbsp;' . FEMALE;?></td>
          </tr>
<?php
}
?>
        </table></td>
      </tr>
<?php
if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_company_error == true) {
    echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
  } else {
    echo $cInfo->entry_company . tep_draw_hidden_field('entry_company', $cInfo->entry_company);
  }
} else {
  echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
}
?></td>
          </tr>
        </table></td>
      </tr>
<?php
}
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_street_address_error == true) {
    echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
  } else {
    echo $cInfo->entry_street_address . tep_draw_hidden_field('entry_street_address');
  }
} else {
  echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
}
?></td>
          </tr>
<?php
if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_suburb_error == true) {
    echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
  } else {
    echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
  }
} else {
  echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
}
?></td>
          </tr>
<?php
}
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_post_code_error == true) {
    echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
  } else {
    echo $cInfo->entry_postcode . tep_draw_hidden_field('entry_postcode');
  }
} else {
  echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true);
}
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_city_error == true) {
    echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
  } else {
    echo $cInfo->entry_city . tep_draw_hidden_field('entry_city');
  }
} else {
  echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
}
?></td>
          </tr>
<?php
if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
$entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
if ($error == true) {
  if ($entry_state_error == true) {
    if ($entry_state_has_zones == true) {
      $zones_array = array();
      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
      while ($zones_values = tep_db_fetch_array($zones_query)) {
        $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
      }
      echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
    } else {
      echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
    }
  } else {
    echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
  }
} else {
  echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
}

?></td>
         </tr>
<?php
}
?>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_country_error == true) {
    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
  } else {
    echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
  }
} else {
  echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
}
?></td>
          </tr>
        </table></td>
      </tr>
      
    
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_SHIPPING_ADDRESS . ' ' . tep_draw_pull_down_menu('saID', $addresses, $aID, 'onchange="select_address(this.options[this.selectedIndex].value, \'s_\');"') . tep_draw_checkbox_field('csa', '', false, false, 'onclick="copy_address(\'s_\');"') . TEXT_COPY_BILLING_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('s_entry_firstname', $cInfo->entry_firstname, 'maxlength="32"');?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('s_entry_lastname', $cInfo->entry_lastname, 'maxlength="32"')
?></td>
          </tr>

<?php
if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php echo tep_draw_radio_field('s_entry_gender', 'm', false, $cInfo->entry_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('s_entry_gender', 'f', false, $cInfo->entry_gender) . '&nbsp;&nbsp;' . FEMALE;?></td>
          </tr>
<?php
}
?>
        </table></td>
      </tr>
<?php
if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_company_error == true) {
    echo tep_draw_input_field('s_entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
  } else {
    echo $cInfo->entry_company . tep_draw_hidden_field('s_entry_company', $cInfo->entry_company);
  }
} else {
  echo tep_draw_input_field('s_entry_company', $cInfo->entry_company, 'maxlength="32"');
}
?></td>
          </tr>
        </table></td>
      </tr>
<?php
}
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_street_address_error == true) {
    echo tep_draw_input_field('s_entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
  } else {
    echo $cInfo->entry_street_address . tep_draw_hidden_field('s_entry_street_address');
  }
} else {
  echo tep_draw_input_field('s_entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
}
?></td>
          </tr>
<?php
if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_suburb_error == true) {
    echo tep_draw_input_field('s_entry_suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
  } else {
    echo $cInfo->entry_suburb . tep_draw_hidden_field('s_entry_suburb');
  }
} else {
  echo tep_draw_input_field('s_entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
}
?></td>
          </tr>
<?php
}
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_post_code_error == true) {
    echo tep_draw_input_field('s_entry_postcode', $cInfo->entry_postcode, 'maxlength="8"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
  } else {
    echo $cInfo->entry_postcode . tep_draw_hidden_field('s_entry_postcode');
  }
} else {
  echo tep_draw_input_field('s_entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true);
}
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_city_error == true) {
    echo tep_draw_input_field('s_entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
  } else {
    echo $cInfo->entry_city . tep_draw_hidden_field('s_entry_city');
  }
} else {
  echo tep_draw_input_field('s_entry_city', $cInfo->entry_city, 'maxlength="32"', true);
}
?></td>
          </tr>
<?php
if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
$entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
if ($error == true) {
  if ($entry_state_error == true) {
    if ($entry_state_has_zones == true) {
      $zones_array = array();
      $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
      while ($zones_values = tep_db_fetch_array($zones_query)) {
        $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
      }
      echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
    } else {
      echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
    }
  } else {
    echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
  }
} else {
  echo tep_draw_input_field('s_entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
}

?></td>
         </tr>
<?php
}
?>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main">
<?php
if ($error == true) {
  if ($entry_country_error == true) {
    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
  } else {
    echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
  }
} else {
  echo tep_draw_pull_down_menu('s_entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
}
?>
</td>
          </tr>
        </table></td>
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
}
?>