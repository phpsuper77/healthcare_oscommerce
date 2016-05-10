<?php

function ebay_schedule_log($task, $extra_info='', $extra_text=''){
  $sql_array = array('connector_id'=>ebay_config::getEbaySiteID(),
                     'feedtype'=>$task,
                     'extra_info' => $extra_info,
                     'extra_text' => $extra_text,
                     'log_date' => 'now()');
  tep_db_perform(TABLE_EBAY_LOG, $sql_array);
}

function ebay_log_purge(){
  $log = ebay_config::getLogToFile();
  if ( $log!==false ) {
    $pattern = '/'.preg_replace('/\.txt$/','(\d{8})\.(.*)',basename($log)).'/';
    $target = date('Ymd',strtotime("-14 days"));
    $today = date('Ymd');
    $path = dirname($log);
    $can = array();
    if (is_dir($path)) {
      if (substr($path,-1)!='/' ) $path .= '/';
      if ($dh = @opendir($path)) {
        while (($file = readdir($dh)) !== false) {
          $m=array();
          if ( strpos($file,'.')===0 || is_dir($path.$file) ) continue; //skip hidden files and root dirs & dirs
          if (preg_match($pattern, $file, $m)) {
            $m['filename'] = $path.$file;
            $can[] = $m;
          }
        }
        closedir($dh);
      }
    }
    if ( count($can)>0 ) {
      $can_gzip = false;
      if ( is_callable('exec') ) {
        $gzip_locate = @exec("which gzip");
        if ( !empty($gzip_locate) && is_executable($gzip_locate) ) {
          $can_gzip = $gzip_locate;
        }
      }
      foreach($can as $fi) {
        if ( $fi[1]==$today ) continue;
        if ( $fi[1]<$target ) {
          // too old - remove
          @unlink($fi['filename']);
        }else{
          if ( $fi[2]=='txt' && $can_gzip!==false ) {
            @exec($can_gzip.' "'. escapeshellcmd($fi['filename']).'"');
          }
        }
      }
    }
  }
}

function ebay_schedule_orders(){
  $trans = new ebay_transaction();
  $trans->GetSellerTransactions();
  $trans->sendOrderPaid();
  $trans->sendOrderShipped();
  $info  = "Imported :".$trans->stat['imported']."\n";
  $info .= "Paid mark :".$trans->stat['paid']."\n";
  $info .= "Feedback :".$trans->stat['feedback']."\n";
  $info .= "Shipped :".$trans->stat['shipped'];
  $text = (!empty($trans->stat['text'])?$trans->stat['text']:'');
  ebay_schedule_log('process_orders', $info, $text);
}
function ebay_schedule_getproducts(){
  $ep = new ebay_products();
  $ep->GetSellerList();
  $info = "Downloaded : ".$ep->stat['downloaded'];
  $text = (!empty($ep->stat['text'])?$ep->stat['text']:'');
  ebay_schedule_log('get_products', $info, $text);
}
function ebay_schedule_products(){
  $ep = new ebay_products();
  $ep->maintain();
  $info = "Checked : ".$ep->stat['checked']."\n";
  $text = (!empty($ep->stat['text'])?$ep->stat['text']:'');
  $ep = new ebay_products();
  $ep->process_diffs();
  $info .= "Update Inventory : ".$ep->stat['update_qty_price']."\n";
  $info .= "Activated : ".$ep->stat['activate']."\n";
  $info .= "Ended : ".$ep->stat['end_item']."\n";
  $info .= "Update Info : ".$ep->stat['update']."\n";
  if ( $ep->stat['skipped']>0 ) {
    $info .= "Skipped Info : ".$ep->stat['skipped']."\n";
  }
  if ( is_array($ep->stat['status_text']) ) {
    foreach( $ep->stat['status_text'] as $extError ) {
      $text .= $extError."\n";
    }
  }
  $text .= (!empty($ep->stat['text'])?$ep->stat['text']:'');
  ebay_schedule_log('process_products', $info, $text);
}

//===================================================
function ebay_item_prepeare_description( $text ){
  $text = preg_replace('/<\/?a.*?'.'>/ims','',$text);
  $text = preg_replace('/<script.*?<\/script>/ims','',$text);
  $text = preg_replace('/<noscript.*?<\/noscript>/ims','',$text);
  $text = preg_replace('/(<br \/>){2,}/ims','<br />',$text);
  return $text;
}
function ebay_item_template_description( $params, $template='' ){
  static $tpl_cache = array();
  $_tpl_ = false;
  if ( !array_key_exists($template, $tpl_cache ) ) {
    $tpl_cache[$template]=false;
    if ( !empty($template) ){
      if ( preg_match('/^https?::/i',$template) ){
        $http_template = @file($template);
        if ( $http_template!==false ) $tpl_cache[$template] = implode('', $http_template);
      }elseif( is_file( dirname(__FILE__).'/template/'.$template ) ){
        $file_template = @file(dirname(__FILE__).'/template/'.$template);
        if ( $file_template!==false ) $tpl_cache[$template] = implode('', $file_template);
      }
    }
  }
  $_tpl_ = $tpl_cache[$template];
  
  $ret = '';
  if ( $_tpl_==false ){
    $ret = $params['Description'];
  }else{
    $search = array_keys($params);
    foreach( $search as $idx=>$rep ) $search[$idx] = '/%%'.$rep.'%%/i'; 
    $replace = array_values($params);
    $ret = preg_replace ($search, $replace, $_tpl_);
  }
  return $ret;
}

//===================================================
if ( !function_exists('tep_get_products_price') ) {
  function tep_get_products_price($products_id){
    $data = tep_db_fetch_array(tep_db_query("select products_price from ".TABLE_PRODUCTS." where products_id='".(int)$products_id."'"));
    return $data['products_price'];
  }
}
function ebay_uprid_price( $uprid ){
//  $a_tax_class = tep_db_fetch_array(tep_db_query("select products_tax_class_id from ".TABLE_PRODUCTS." where products_id='".(int)$uprid."'"));
  
  $special_price = tep_get_products_special_price( (int)$uprid );
  $base_price = tep_get_products_price( (int)$uprid );

  $attributes_price = 0;
  
  preg_match_all('/\{(\d+)\}(\d+)/', $uprid, $arr, PREG_SET_ORDER);
  if (is_array($arr) && sizeof($arr)>0) {
    foreach($arr as $attr_data) {
      if (isset($attr_data[1]) && isset($attr_data[2])) {
        $get_attr_price_q = tep_db_query("select price_prefix, options_values_price from ".TABLE_PRODUCTS_ATTRIBUTES." where products_id='".intval($uprid)."' and options_id='".intval($attr_data[1])."' and options_values_id='".intval($attr_data[2])."'");
        if (tep_db_num_rows($get_attr_price_q)>0) {
          $get_attr_price = tep_db_fetch_array($get_attr_price_q);
          if ($get_attr_price['options_values_price']>0) $attributes_price += ($get_attr_price['price_prefix']=='-'?-1:1)*$get_attr_price['options_values_price'];
        }
      }
    }
  }
  $final_price = ($special_price? 
                    $special_price+$attributes_price
                   :$base_price+$attributes_price);

  $ret = array('special'=>$special_price, 
               'base'=>$base_price,
               'attributes'=>$attributes_price,
               'tax_rate' => EBAY_TAX_RATE, 
               'final'=>$final_price,
               'final_gross'=>tep_round($final_price+tep_calculate_tax($final_price, EBAY_TAX_RATE),4) );
  
  return $ret;
}

//===================================================
// use in order creation process
// use for products feed - estimate chipping cost and avail methods
function shipping_quote( $uprid, $additional=false, $get_all_variants=false ) {
   $ret = array();
/*
   if ( !is_array($additional) ) $additional = array();
   if ( !array_key_exists('products_weight', $additional) ) {
     $data = tep_db_fetch_array(tep_db_query("select products_weight from ".TABLE_PRODUCTS." where products_id='".(int)$uprid."'"));
     $additional['products_weight'] = (float)$data['products_weight'];
   }
   if ( !array_key_exists('price_gross', $additional) ) {
     $price = ebay_uprid_price( $uprid );
     $additional['price_gross'] = $price['final_gross'];
   }
*/
   $shipping_cost = (float)MODULE_SHIPPING_FLAT_COST;
   $shipping_cost = tep_round($shipping_cost, 2);
   $ret['UK_RoyalMailStandardParcel'] = array(
                         'shipping_title' => 'UK Postage (Royal Mail Standard Delivery)',
                         'shipping_class' => 'flat_flat',
                         'additional_cost' => '0.00',
                         'cost' => $shipping_cost,
                         'cost_gross' => $shipping_cost,
                         'tax' => 0 );

   return $ret;
}

function ebay_country_info( $ISO2 ) {
  global $languages_id;
  static $_country_info = array();
  if ( !isset($_country_info[$ISO2]) || 
      ( isset($_country_info[$ISO2]) && !is_array( $_country_info[$ISO2] ) ) ) {
    $data_r = tep_db_query("select countries_id as id, countries_name as name, address_format_id ".
                           "from ".TABLE_COUNTRIES." where ".
                             "countries_iso_code_2='".tep_db_input($ISO2)."' and ".
                             "language_id='".(int)$languages_id."'");
    if( $data = tep_db_fetch_array($data_r) ){
      $_country_info[$ISO2] = $data;
    }else{
      $data_r = tep_db_query("select countries_id as id, countries_name as name, address_format_id ".
                             "from ".TABLE_COUNTRIES." where ".
                               "countries_id='".(int)ebay_config::defaultCountry()."' and ".
                               "language_id='".(int)$languages_id."'");
      $_country_info[$ISO2] = tep_db_fetch_array($data_r);
      $_country_info[$ISO2]['not_found'] = $ISO2;
    }
  }
  return $_country_info[$ISO2];
}

function ebay_state_info( $stateOrRegion, $countryID ) {
  global $languages_id;
  static $_state_info = array();
  if ( !isset($_state_info[$countryID]) ||
       (isset($_state_info[$countryID]) && !is_array($_state_info[$countryID])) ) {
    $_state_info[$countryID] = array();
  }

  if ( !isset( $_state_info[$countryID][$stateOrRegion] ) ) {
    $count_check = tep_db_fetch_array(tep_db_query("select count(*) as c ".
                                                   "from ".TABLE_ZONES." where ".
                                                   "zone_country_id='".(int)$countryID."'"));
    if ( intval($count_check['c'])!=0 ) {
      $add_search = '';
      $clean = preg_replace('/\W/', '', $stateOrRegion);
      if ( $stateOrRegion!=$clean ) {
        $add_search = " or zone_code like '".tep_db_input($clean)."'".
                      " or zone_name like '".tep_db_input($clean)."' ";
      }
      $data_r = tep_db_query("select zone_id, zone_name ".
                             "from ".TABLE_ZONES." where ".
                               "zone_country_id='".(int)$countryID."' and ".
                               "(zone_code like '".tep_db_input($stateOrRegion)."' ".$add_search." or ".
                               " zone_name like '".tep_db_input($stateOrRegion)."') ".
                             "limit 1");
      if( $data = tep_db_fetch_array($data_r) ){
        $_state_info[$countryID][$stateOrRegion] = $data;
      }else{
        $data_r = tep_db_query("select zone_id, zone_name ".
                               "from ".TABLE_ZONES." where ".
                                 "zone_country_id='".(int)$countryID."' and ".
                                 "( SOUNDEX(zone_code)=SOUNDEX('".tep_db_input($stateOrRegion)."') or ".
                                 "  SOUNDEX(zone_name)=SOUNDEX('".tep_db_input($stateOrRegion)."') ) ".
                               "limit 1");
        if( $data = tep_db_fetch_array($data_r) ){
          $_state_info[$countryID][$stateOrRegion] = $data;
        }else{
          $_state_info[$countryID][$stateOrRegion] = array('zone_id'=>0, 'zone_name'=>$stateOrRegion);
        }
      }
    }else{
      $_state_info[$countryID][$stateOrRegion] = array('zone_id'=>0, 'zone_name'=>$stateOrRegion);
    }
  }
  return $_state_info[$countryID][$stateOrRegion];
}

// reverse ebay_item_id sku to shop
function ebay_product_reverse($sku, $ebay_item_id){
  global $languages_id;
  $ret = array('products_id'=>0,
               'uprid' => '',
               'products_model' => $ebay_item_id,
               'products_name' => '',
               'products_price' => 0,
               'final_price' => 0, // put here attributes price. Little hack
               'attributes'=>false);

  if ( EBAY_PRODUCT_LINK_TYPE=='uprid' ) {
    $inv_r = tep_db_query("select products_id, prid, products_name, products_model ".
                          "from ".TABLE_INVENTORY." where products_id='".tep_db_input($sku)."'");
    if ( tep_db_num_rows($inv_r)==1 ) {
      $inv = tep_db_fetch_array($inv_r);
      $ret['products_id'] = $inv['prid'];
      $ret['products_model'] = $inv['products_model'];
      $ret['products_name'] = $inv['products_name'];
      $ret['uprid'] = $inv['products_id'];
      //$ret['attributes'] = false;
      if ( strpos($ret['uprid'],'{')!==false ) {
        preg_match_all('/\{(\d+)\}(\d+)/', $ret['uprid'], $arr, PREG_SET_ORDER);
        if (is_array($arr) && sizeof($arr)>0) {
          foreach($arr as $attr_data) {
            if (isset($attr_data[1]) && isset($attr_data[2])) {
              $_attr_q = tep_db_query("select po.products_options_name, ".
                                             "pov.products_options_values_name, ".
                                             "pa.price_prefix, pa.options_values_price ".
                                      "from ".TABLE_PRODUCTS_ATTRIBUTES." pa, ".
                                              TABLE_PRODUCTS_OPTIONS." po, ".
                                              TABLE_PRODUCTS_OPTIONS_VALUES." pov where ".
                                      "pa.products_id='".intval($ret['products_id'])."' and ".
                                      "pa.options_id='".intval($attr_data[1])."' and ".
                                      "pa.options_values_id='".intval($attr_data[2])."' and ".
                                      "po.products_options_id=pa.options_id and ".
                                      "po.language_id='".(int)$languages_id."' and ".
                                      "pov.products_options_values_id=pa.options_values_id and ".
                                      "pov.language_id='".(int)$languages_id."'");
              if (tep_db_num_rows($_attr_q)>0) {
                if (!is_array($ret['attributes'])) $ret['attributes'] = array();
                $_attr = tep_db_fetch_array($_attr_q);

                $attr['products_options'] = $_attr['products_options_name'];
                $attr['products_options_values'] = $_attr['products_options_values_name'];
                $attr['options_values_price'] = $_attr['options_values_price'];
                $attr['price_prefix'] = $_attr['price_prefix'];
                $ret['attributes'][] = $attr;
                if ( $attr['price_prefix']=='-' ) {
                  $ret['final_price'] -= (float)$attr['options_values_price'];
                }else{
                  $ret['final_price'] += (float)$attr['options_values_price'];
                }
              }
            }
          }
        }
      }
    }
  }else{
    die('Teach me ebay_product_reverse for '.EBAY_PRODUCT_LINK_TYPE.' '.__LINE__.':'.__FILE__);
  }
  return $ret;
}

?>