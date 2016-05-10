<?php
/*
  $Id: order_history.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (tep_session_is_registered('customer_id')) {
// retreive the last x products purchased
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $orders_query = tep_db_query("select distinct op.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and op.products_id = p.products_id and p.products_status = 1 and pd.products_id=p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id =0 group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
    }else{
      $orders_query = tep_db_query("select distinct op.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_status = 1 and pd.products_id=p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id =0 group by products_id order by o.date_purchased desc limit " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
    }
    if (tep_db_num_rows($orders_query)) {
?>
<!-- customer_orders //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_CUSTOMER_ORDERS);

    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }        
  }
      $customer_orders_string = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';
      while ($orders = tep_db_fetch_array($orders_query)) {
        $customer_orders_string .= '  <tr>' .
                                   '    <td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$orders['products_id']) . '">' . /*tep_get_products_name((int)$orders['products_id']) .*/ $orders['products_name'] . '</a></td>' .
                                   '    <td class="infoBoxContents" align="right" valign="top"><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . (int)$orders['products_id']) . '">' . tep_image(DIR_WS_ICONS . 'cart.png', ICON_CART, '', '', 'class="transpng"') . '</a></td>' .
                                   '  </tr>';
        
      }
      $customer_orders_string .= '</table>';

      $info_box_contents = array();
      $info_box_contents[] = array('text' => $customer_orders_string);

      if (class_exists($infobox_class)){
        new $infobox_class($info_box_contents);
      }else{
        new infoBox($info_box_contents);
      }
?>
            </td>
          </tr>
<!-- customer_orders_eof //-->
<?php
    }
  }
?>
