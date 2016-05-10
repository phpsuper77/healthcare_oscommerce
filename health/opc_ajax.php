<?php
  define('GZIP_COMPRESSION','false');
  require_once 'includes/modules/' . 'JsHttpRequest.php';
//  $JsHttpRequest =& new Subsys_JsHttpRequest_Php('iso-8859-1');
  $HTTP_POST_VARS = $_POST;

  require('includes/application_top.php');

  $JsHttpRequest =& new Subsys_JsHttpRequest_Php(CHARSET);

  $navigation->remove_current_page();

  define('CHECKOUT_CTLPARAM_COMMON','style="width:250px;"');
  define('ONE_PAGE_CALCULATE','yep');
  define('ONE_PAGE_SHIPPING_VAR','_ship'); // used for ot_shipping process, else session overwrite
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'opc_namespace.php');
  require(DIR_WS_CLASSES . 'payment.php');
    
function aj_dump( $message ){  
return;
  if (!$log_file = fopen(DIR_FS_CATALOG . '/temp/opc_test.txt', "a")) {
     $errstr = "Cannot open '" . $log . "' file.";
     trigger_error($errstr, E_USER_ERROR);
  }
  fwrite($log_file, sprintf("\r\n\r\n%s", date("r", time())));
  fwrite($log_file, sprintf("\r\n%s", $message));
  fclose($log_file); 
  chmod(DIR_FS_CATALOG . '/temp/opc_test.txt',0666);
}
aj_dump( var_export($_POST,true) );
$ctl_ship_state = false;  

  if ($_POST['q'] == 'get_rates' || $_POST['q'] == 'apply_coupon'){
    global $_RESULT;

// user not logged in !
    $bill_info = opc::find_countries((int)$_POST['country'],$_POST['state'],'state');
    $_country_info = tep_get_countries($bill_info['country_id'], true);
    $country_info = array('id' => $bill_info['country_id'],
                          'title' => $_country_info['countries_name'],    
                          'iso_code_2' => $_country_info['countries_iso_code_2'], 
                          'iso_code_3' => $_country_info['countries_iso_code_3'] );
    $opc_billto = array(
      'postcode' => $_POST['postcode'],
      'state' => $bill_info['state'],
      'zone_id' => $bill_info['zone_id'],
      'country' => $country_info,
      'country_id' => $bill_info['country_id'],
      'format_id' => tep_get_address_format_id($bill_info['country_id'])
    );

    $ship_info = opc::find_countries((int)$_POST['ship_country'],$_POST['ship_state'],'ship_state');
    $_country_info = tep_get_countries($ship_info['country_id'], true);
    $country_info = array('id' => $ship_info['country_id'],
                          'title' => $_country_info['countries_name'],    
                          'iso_code_2' => $_country_info['countries_iso_code_2'], 
                          'iso_code_3' => $_country_info['countries_iso_code_3'] );
    $opc_sendto = array(
      'postcode' => $_POST['ship_postcode'],
      'state' => $ship_info['state'],
      'zone_id' => $ship_info['zone_id'],
      'country' => $country_info,
      'country_id' => $ship_info['country_id'],
      'format_id' => tep_get_address_format_id($ship_info['country_id'])
    );

    $order = new opc_order();

    // payment slice
    require_once(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment();
    require_once(DIR_WS_CLASSES . 'order_total.php');
    $payment_modules->update_status();
    $selection = $payment_modules->selection();
    $jspayments = array();
    if ( is_array($selection) ) foreach($selection as $p_sel){
      $jspayments[] = $p_sel['id'];
    }
    if ( count($jspayments)==0 ) $jspayments[] = 'none';
//aj_dump( var_export($order,true) );

   // ok make shippings - this part similar to checkout.php
    $opc_shippings = array();
    $opc_shipping = true;
    if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
      $opc_shipping = false;
    }
    // weight and count needed for shipping !
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
// load all enabled shipping modules
    require_once(DIR_WS_CLASSES . 'http_client.php');
    require_once(DIR_WS_CLASSES . 'shipping.php');
    
    if ($opc_shipping !== false) {
      $free_shipping = false;
      $shipping_modules = new shipping;
      if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
        $pass = false;
        switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY) $pass = true;
          break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY) $pass = true;
          break;
          case 'both': 
            $pass = true;
          break;
        }
        if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) { // $order->info['total'] in this point subtotal + tax - opc not load any shippings yet !!! testhere
          //$free_shipping = true;
          include_once(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
          //$free_shipping = array('id'=>'free_free', 'title'=>FREE_SHIPPING_TITLE, 'cost'=>'0' );
          $free_shipping = array( 'id'=>'free', 'methods'=>array('title'=>FREE_SHIPPING_TITLE, 'cost'=>'0') );
        }
      }
      // get all available shipping quotes
      if ( is_array($free_shipping) ) {
        $quotes = $free_shipping;
      }else{
        $quotes = $shipping_modules->quote();
      }
      $opc_shippings = $quotes;
      $opc_cheapest = 'none'; $opc_cheapest_val = 9999999;
      $registered_shipping = false;
      $order_shippings = array();
      if ( is_array($opc_shippings) ) foreach ( $opc_shippings as $_ship ) {
        if (isset($_ship['error'])) {
        } else {
          if ( is_array($_ship['methods']) ) foreach ( $_ship['methods'] as $_method ) {
            $_id = $_ship['id'].'_'.$_method['id'];
            if ( $registered_shipping===false && is_array($shipping) && $shipping['id']==$_id ) $registered_shipping = $_id;
            //testhere
            $_title = (($free_shipping == true)?$quote[0]['methods'][0]['title']:$_ship['module'].' ('.$_method['title'].')');
            $order_shippings[] = array('id'=>$_id, 'title'=>$_title, 'cost'=>(string)$_method['cost'] );
            if ( $opc_cheapest_val>$_method['cost'] ) { $opc_cheapest = $_id; $opc_cheapest_val=$_method['cost'];}
          }
        }
      }
      if ( $_POST['q'] != 'apply_coupon' ) {
        $jsshippings = opc::jsshippings($quotes, $free_shipping );
        $jsshippings['prefered_shipping'] = (($registered_shipping!==false)?$registered_shipping:$opc_cheapest);
      }
      $jsshippings['shipping']=true;
    }else{
      $order_shippings = array();
      $jsshippings = array('shipping'=>false);
    }
//aj_dump( var_export($order,true) );
//aj_dump( var_export($opc_shippings,true) );
//aj_dump( var_export($order_shippings,true) );
///////////////////////////////////////////////////////////////////
    if(defined('ONE_PAGE_SHOW_TOTALS') && ONE_PAGE_SHOW_TOTALS=='true') {
      // init coupon vars
      $HTTP_POST_VARS['gv_redeem_code'] = '';
      if ( isset($_POST['gv_redeem_code']) ) $HTTP_POST_VARS['gv_redeem_code'] = $_POST['gv_redeem_code'];
      $ajot_array = array();
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        $opc_coupon_pool = array();
        require_once(DIR_WS_CLASSES . 'order_total.php');
        $order_shippings[] = array('id'=>'','title'=>'','cost'=>'0');
        foreach( $order_shippings as $idx=>$_ship ) {
          $order->change_shipping($_ship);
          $order_total_modules = new order_total();

          $order_total_modules->collect_posts();
          $order_total_modules->pre_confirmation_check();

          $order_total_array = $order_total_modules->process();
          if ( $_ship['id']=='' ) $_ship['id']='none'; // <- dummy totals for no shipping selected ??
          $ajot_array[ $_ship['id'] ] = array();
          foreach( $order_total_array as $_totals) {
            $ajot_array[ $_ship['id'] ]['ot'][] = array(
              'oc'=>$_totals['code'],
              'text'=>$_totals['title'],
              'cost'=>$_totals['text']
            );
          }
          if ( count($opc_coupon_pool)>0 ) {
            $ajot_array[ $_ship['id'] ]['label_coupon'] = $opc_coupon_pool['message']; 
            $ajot_array[ $_ship['id'] ]['errc'] = $opc_coupon_pool['error'];
          }
        }
      }
    }else{
      //testhere
      // alter easy array $ajot_array whith selectable shippings
    }
///////////////////////////////////////////////////////////////////
    if (defined('ONE_PAGE_SHOW_CART') && ONE_PAGE_SHOW_CART=='true' && $_POST['q'] != 'apply_coupon' ) {     
      $cart_content = opc::cart();
    }else{
      $cart_content = '';
    }
    
    $_RESULT = array(
      'cart_content' => $cart_content,
      'payments' => $jspayments
    );
    $_RESULT = array_merge($_RESULT, $jsshippings);
    if(defined('ONE_PAGE_SHOW_TOTALS') && ONE_PAGE_SHOW_TOTALS=='true') {
      $_RESULT['ajot_array'] = $ajot_array;
    }
    if ( !empty($bill_info['ctrl']) ) $_RESULT['ctl_state'] = $bill_info['ctrl'];
    if ( !empty($ship_info['ctrl']) ) $_RESULT['ctl_ship_state'] = $ship_info['ctrl'];    
//aj_dump( var_export($_RESULT,true) );    
  } elseif ($_POST['q']=='get_states') {
  /*
  $_POST['bill_country'];
  $_POST['country'];
  $_POST['state']
  */
    $bill_info = opc::find_countries((int)$_POST['country'],$_POST['state'],'state');
    $_country_info = tep_get_countries($bill_info['country_id'], true);
    $country_info = array('id' => $bill_info['country_id'],
                          'title' => $_country_info['countries_name'],    
                          'iso_code_2' => $_country_info['countries_iso_code_2'], 
                          'iso_code_3' => $_country_info['countries_iso_code_3'] );
    $opc_billto = array(
      'postcode' => $_POST['postcode'],
      'state' => $bill_info['state'],
      'zone_id' => $bill_info['zone_id'],
      'country' => $country_info,
      'country_id' => $bill_info['country_id'],
      'format_id' => tep_get_address_format_id($bill_info['country_id'])
    );

    $ship_info = opc::find_countries((int)$_POST['ship_country'],$_POST['ship_state'],'ship_state');
    $_country_info = tep_get_countries($ship_info['country_id'], true);
    $country_info = array('id' => $ship_info['country_id'],
                          'title' => $_country_info['countries_name'],    
                          'iso_code_2' => $_country_info['countries_iso_code_2'], 
                          'iso_code_3' => $_country_info['countries_iso_code_3'] );
    $opc_sendto = array(
      'postcode' => $_POST['ship_postcode'],
      'state' => $ship_info['state'],
      'zone_id' => $ship_info['zone_id'],
      'country' => $country_info,
      'country_id' => $ship_info['country_id'],
      'format_id' => tep_get_address_format_id($ship_info['country_id'])
    );
    
    $order = new opc_order();

    // payment slice
    require_once(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment();
    require_once(DIR_WS_CLASSES . 'order_total.php');
    $payment_modules->update_status();
    $selection = $payment_modules->selection();
    $jspayments = array();
    if ( is_array($selection) ) foreach($selection as $p_sel){
      $jspayments[] = $p_sel['id'];
    }
    if ( count($jspayments)==0 ) $jspayments[] = 'none';

    $_RESULT = array(
      'payments' => $jspayments
    );
    if ( !empty($bill_info['ctrl']) ) $_RESULT['ctl_state'] = $bill_info['ctrl'];
  } else {
    $_RESULT = array(
    'quotes' => 'Wrong request'
  );    

  }


?>