<?php
/*
$Id: shipping_estimator.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 Edwin Bekaert (edwin@ednique.com)

Customized by: Linda McGrath osCommerce@WebMakers.com
* This now handles Free Shipping for orders over $total as defined in the Admin
* This now shows Free Shipping on Virtual products
* Everything is contained in an infobox for easier placement.

Released under the GNU General Public License

http://forums.oscommerce.com/viewtopic.php?t=38411

http://www.oscommerce.com/community/contributions,1094
*/
?>
<div id="shipping-estimator">

<!-- shipping_estimator //-->

<?php
// Only do when something is in the cart
if ($cart->count_contents() > 0) {

  // shipping cost
  require_once('includes/classes/http_client.php'); // shipping in basket

  if($cart->get_content_type() !== 'virtual') {
    if (tep_session_is_registered('customer_id')) {
      // user is logged in
      if (isset($HTTP_POST_VARS['address_id'])){
        // user changed address
        $sendto = $HTTP_POST_VARS['address_id'];
      }elseif (tep_session_is_registered('cart_address_id')){
        // user once changed address
        $sendto = $cart_address_id;
      }else{
        // first timer
        $sendto = $customer_default_address_id;
      }
      // set session now
      $cart_address_id = $sendto;
      tep_session_register('cart_address_id');
      // include the order class (uses the sendto !)
      require_once(DIR_WS_CLASSES . 'order.php');
      $order = new order;
    }else{
      // user not logged in !
      if (isset($HTTP_POST_VARS['country_id'])){
        // country is selected
        $country_info = tep_get_countries($HTTP_POST_VARS['country_id'],true);
        $order->delivery = array('postcode' => $HTTP_POST_VARS['zip_code'],
        'country' => array('id' => $HTTP_POST_VARS['country_id'], 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
        'country_id' => $HTTP_POST_VARS['country_id'],
        'format_id' => tep_get_address_format_id($HTTP_POST_VARS['country_id']));
        $cart_country_id = $HTTP_POST_VARS['country_id'];
        tep_session_register('cart_country_id');
        $cart_zip_code = $HTTP_POST_VARS['zip_code'];
        tep_session_register('cart_zip_code');
      }elseif (tep_session_is_registered('cart_country_id')){
        // session is available
        $country_info = tep_get_countries($cart_country_id,true);
        $order->delivery = array('postcode' => $cart_zip_code,
        'country' => array('id' => $cart_country_id, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
        'country_id' => $cart_country_id,
        'format_id' => tep_get_address_format_id($cart_country_id));
      } else {
        // first timer
        $cart_country_id = STORE_COUNTRY;
        tep_session_register('cart_country_id');
        // WebMakers.com Added: changes
        // changed from STORE_ORIGIN_ZIP to SHIPPING_ORIGIN_ZIP
        $cart_zip_code = SHIPPING_ORIGIN_ZIP;
        $country_info = tep_get_countries(STORE_COUNTRY,true);
        tep_session_register('cart_zip_code');
        $order->delivery = array('postcode' => SHIPPING_ORIGIN_ZIP,
        'country' => array('id' => STORE_COUNTRY, 'title' => $country_info['countries_name'], 'iso_code_2' => $country_info['countries_iso_code_2'], 'iso_code_3' =>  $country_info['countries_iso_code_3']),
        'country_id' => STORE_COUNTRY,
        'format_id' => tep_get_address_format_id($HTTP_POST_VARS['country_id']));
      }
      // set the cost to be able to calvculate free shipping
      $order->info = array('total' => $cart->show_total()); // TAX ????
    }
    // weight and count needed for shipping !
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping;
    $quotes = $shipping_modules->quote();
    $cheapest = $shipping_modules->cheapest();
    // set selections for displaying
    $selected_country = $order->delivery['country']['id'];
    $selected_zip = $order->delivery['postcode'];
    $selected_address = $sendto;
  }
  // eo shipping cost

  $info_box_contents = array();
  $info_box_contents[] = array('text' => SHIPPING_OPTIONS);

  //new contentBoxHeading($info_box_contents, true, true);

  // check free shipping based on order $total
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
      if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
      case 'international':
      if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
      case 'both':
      $pass = true; break;
      default:
      $pass = false; break;
    }
    $free_shipping = false;
    if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
  // end free shipping based on order total
  ?>
  
  <h3>Delivery Options <span>(<?=($total_count == 1 ? TEXT_ITEM_SE : TEXT_ITEMS_SE) . $total_count . '&nbsp;-&nbsp; ' . TEXT_WEIGHT_SE . ' ' . $total_weight . (STORE_COUNTRY==223?'lbs':'g');?>)</span></h3>
  
  <?
  $ShipTxt= tep_draw_form('estimator', tep_href_link(basename($_SERVER['SCRIPT_NAME']), '', 'NONSSL'), 'post'); //'onSubmit="return check_form();"'

	
  
  $ShipTxt.='<table>';
  if(sizeof($quotes)) {
    if (tep_session_is_registered('customer_id')) {
      // logged in
      $addresses_query = tep_db_query("select address_book_id, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $addresses_array[] = array('id' => $addresses['address_book_id'], 'text' => tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, 0, ' ', ' '));
      }
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
      SHIPPING_METHOD_ADDRESS .'&nbsp;'. tep_draw_pull_down_menu('address_id', $addresses_array, $selected_address, 'class="swatch-blue link-recalculate" onchange="document.estimator.submit();return false;"').'</td></tr>';
      $ShipTxt.='<tr valign="top"><td class="main">' . SHIPPING_METHOD_TO .'</td><td colspan="2" class="main">'. tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>') . '</td></tr>';
    } else {
      // not logged in
      //$ShipTxt.= "<span class='error'>".SHIPPING_OPTIONS_LOGIN."</span>";
      $ShipTxt.='<tr><td colspan="3" class="main" nowrap>' .
      '<span class="field-title">' . ENTRY_COUNTRY .'</span>'. tep_get_country_list('country_id', $selected_country,'style="width=200;"');
      if(SHIPPING_METHOD_ZIP_REQUIRED == "true"){
        $ShipTxt.='</td></tr>
		<tr><td colspan="3" class="main" nowrap><span class="field-title">'.ENTRY_POST_CODE .'</span>'. tep_draw_input_field('zip_code', $selected_zip, 'size="10"');
      }
      $ShipTxt.='&nbsp;<a href="_" class="swatch-blue link-recalculate" onclick="document.estimator.submit();return false;">' . SHIPPING_METHOD_RECALCULATE.'</a></td></tr>';
    }
    if ($free_shipping==1) {
      // order $total is free
      $ShipTxt.='<tr><td>&nbsp;</td><td class="main">' . sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . '</td><td>&nbsp;</td></tr>';
    }else{
      // shipping display
      $ShipTxt.='<tr><td class="main shipping-options-title" align="left"><b>' . SHIPPING_METHOD_TEXT . '</b></td><td class="main" align="center"><b>' . SHIPPING_METHOD_RATES . '</b></td></tr>';
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
        if(sizeof($quotes[$i]['methods'])==1){
          // simple shipping method
          $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][0]['id'];
          $ShipTxt.= '<tr class="'.$extra.'">';
          if($quotes[$i]['error']){
            $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
            $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
          }else{
            if($cheapest['id'] == $thisquoteid){
              $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</b></td><td align="right" class="main">'.
                ( (is_numeric($quotes[$i]['methods'][0]['cost']) && (float)$quotes[$i]['methods'][0]['cost']==0)?
                    TEXT_SHIP_FREE_COST:
                    '<b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax'])).'<b>'
                ).
                '</td></tr>';
            }else{
              $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['methods'][0]['title'].')</td><td align="right" class="main">'.
                ( (is_numeric($quotes[$i]['methods'][0]['cost']) && (float)$quotes[$i]['methods'][0]['cost']==0)?
                    TEXT_SHIP_FREE_COST:
                    $currencies->format(tep_add_tax($quotes[$i]['methods'][0]['cost'], $quotes[$i]['tax']))
                ).
                '</td></tr>';
            }
          }
        } else {
          // shipping method with sub methods (multipickup)
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
            $thisquoteid = $quotes[$i]['id'].'_'.$quotes[$i]['methods'][$j]['id'];
            $ShipTxt.= '<tr class="'.$extra.'">';
            $ShipTxt.='<td class="main">'.$quotes[$i]['icon'].'&nbsp;</td>';
            if($quotes[$i]['error']){
              $ShipTxt.='<td colspan="2" class="main">'.$quotes[$i]['module'].'&nbsp;';
              $ShipTxt.= '('.$quotes[$i]['error'].')</td></tr>';
            }else{
              if($cheapest['id'] == $thisquoteid){
                $ShipTxt.='<td class="main"><b>'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</b></td><td align="right" class="main">'.
                 (
                   (is_numeric($quotes[$i]['methods'][$j]['cost']) && (float)$quotes[$i]['methods'][$j]['cost']==0)?
                     TEXT_SHIP_FREE_COST:
                     '<b>'.$currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])).'</b>'
                 ).
                 '</td></tr>';
              }else{
                $ShipTxt.='<td class="main">'.$quotes[$i]['module'].'&nbsp;';
                $ShipTxt.= '('.$quotes[$i]['methods'][$j]['title'].')</td><td align="right" class="main">'.
                 (
                   (is_numeric($quotes[$i]['methods'][$j]['cost']) && (float)$quotes[$i]['methods'][$j]['cost']==0)?
                     TEXT_SHIP_FREE_COST:
                     $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax']))
                 ).
                 '</td></tr>';
              }
            }
          }
        }
      }
    }
  } else {
    // virtual product/download
    $ShipTxt.='<tr><td class="main">' . SHIPPING_METHOD_FREE_TEXT . ' ' . SHIPPING_METHOD_ALL_DOWNLOADS . '</td></tr>';
  }

  $ShipTxt.= '</table></form>';

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $ShipTxt);

  new contentBox($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
  );
  new infoboxFooter($info_box_contents, true, true);
} // Only do when something is in the cart
?>
              </td></tr></table>
<!-- shipping_estimator_eof //-->

</div>
