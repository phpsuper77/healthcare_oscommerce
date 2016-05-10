<?php
/*
  $Id: product_notifications.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($HTTP_GET_VARS['products_id']) && tep_check_product((int)$HTTP_GET_VARS['products_id'])) {
    $query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    if (($customer_groups_id != 0 && tep_db_num_rows($query)) || $customer_groups_id == 0){
?>
<!-- notifications //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents, tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_NOTIFICATIONS);
    
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false, tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));
    }else{
      new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));
    }    
  }
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
      $check = tep_db_fetch_array($check_query);

      $notification_exists = (($check['count'] > 0) ? true : false);
    } else {
      $notification_exists = false;
    }

    $info_box_contents = array();
    if ($notification_exists == true) {
      $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'content/notification.png', IMAGE_BUTTON_REMOVE_NOTIFICATIONS, '', '', 'class="transpng"') . '</a></td><td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name($HTTP_GET_VARS['products_id'])) .'</a></td></tr></table>');
    } else {
      $info_box_contents[] = array('text' => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'content/notification.png', IMAGE_BUTTON_NOTIFICATIONS, '', '', 'class="transpng"') . '</a></td><td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name($HTTP_GET_VARS['products_id'])) .'</a></td></tr></table>');
    }

    if (class_exists($infobox_class)){
      new $infobox_class($info_box_contents);
    }else{
      new infoBox($info_box_contents);
    }
?>
            </td>
          </tr>
<!-- notifications_eof //-->
<?php
    }
  }
?>
