<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

function amazon_country_info( $ISO2 ) {
  global $languages_id;
  static $_fws_country_info;
  if ( !is_array($_fws_country_info) ) $_fws_country_info = array();
  if ( !is_array( $_fws_country_info[$ISO2] ) ) {
    $check = new RecordSetProxy();
    $check->query("select countries_id as id, countries_name as name, address_format_id from ".TABLE_COUNTRIES." where countries_iso_code_2='".$check->esc($ISO2)."' and language_id='".(int)$languages_id."'");
    if ( $iso = $check->next() ) {
      $_fws_country_info[$ISO2] = $iso;
    }else{
      $check->query("select countries_id as id, countries_name as name, address_format_id from ".TABLE_COUNTRIES." where countries_iso_code_2='".$check->esc(AmazonConfig::getAmazonCountry())."' and language_id='".(int)$languages_id."'");
      $_fws_country_info[$ISO2] = $check->next();
      $_fws_country_info[$ISO2]['not_found'] = $ISO2;
    }
  }
  return $_fws_country_info[$ISO2];
}

function amazon_state_info( $stateOrRegion, $countryID ) {
  global $languages_id;
  static $_fws_state_info;
  if ( !is_array($_fws_state_info) ) $_fws_state_info = array();
  if ( !is_array($_fws_state_info[$countryID]) ) $_fws_state_info[$countryID] = array();
  
  if ( !isset( $_fws_state_info[$countryID][$stateOrRegion] ) ) {
    $count_check = tep_db_fetch_array(tep_db_query("select count(*) as c from ".TABLE_ZONES." where zone_country_id='".(int)$countryID."'"));
    if ( intval($count_check['c'])!=0 ) {
      $check = new RecordSetProxy();
      $add_search = '';
      $clean = preg_replace('/\W/', '', $stateOrRegion);
      if ( $stateOrRegion!=$clean ) {
        $add_search = " or zone_code like '".$check->esc($clean)."' or zone_name like '".$check->esc($clean)."' "; 
      }
      $check->query("select zone_id, zone_name from ".TABLE_ZONES." where zone_country_id='".(int)$countryID."' and (zone_code like '".$check->esc($stateOrRegion)."' ".$add_search." or zone_name like '".$check->esc($stateOrRegion)."') limit 1");
      if( $data = $check->next() ){
        $_fws_state_info[$countryID][$stateOrRegion] = $data; 
      }else{
        $check->query("select zone_id, zone_name from ".TABLE_ZONES." where zone_country_id='".(int)$countryID."' and ( SOUNDEX(zone_code)=SOUNDEX('".$check->esc($stateOrRegion)."') or SOUNDEX(zone_name)=SOUNDEX('".$check->esc($stateOrRegion)."') ) limit 1");
        if( $data = tep_db_fetch_array($data_r) ){
          $_fws_state_info[$countryID][$stateOrRegion] = $data;
        }else{
          $_fws_state_info[$countryID][$stateOrRegion] = array('zone_id'=>0, 'zone_name'=>$stateOrRegion);
        }
      }
    }else{
      $_fws_state_info[$countryID][$stateOrRegion] = array('zone_id'=>0, 'zone_name'=>$stateOrRegion);
    }
  }
  return $_fws_state_info[$countryID][$stateOrRegion];
}

function uprid_price_add($uprid, $pprice)  {
  preg_match_all('/\{(\d+)\}(\d+)/', $uprid, $arr, PREG_SET_ORDER);

  if (is_array($arr) && sizeof($arr)>0) {
    $reader = new RecordSetProxy();
    foreach($arr as $attr_data) {
      if (isset($attr_data[1]) && isset($attr_data[2])) {
        $reader->query("SELECT products_attributes_id, price_prefix, options_values_price ".
                       "FROM ".TABLE_PRODUCTS_ATTRIBUTES." ".
                       "WHERE products_id='".intval($uprid)."' ".
                         "AND options_id='".intval($attr_data[1])."' ".
                         "AND options_values_id='".intval($attr_data[2])."'");
        if ( $attr_price = $reader->next() ) {
          $attr_price['options_values_price'] = tep_get_options_values_price($attr_price['products_attributes_id']);
          if ((float)$attr_price['options_values_price']>0) {
            $pprice += ($get_attr_price['price_prefix']=='-'?-1:1)*((float)$attr_price['options_values_price']);
          }
        }
      }
    }
  }

  return $pprice;
}

function ordered_product_info( $uprid, $amazonSKU, $amazonTitle ){
  global $languages_id;
  $ret = array(
    'products_model' => $amazonSKU,
    'products_name' => $amazonTitle,
    'attributes' => array()
  );
  if ( (int)$uprid>0 ) {
    $reader = new RecordSetProxy();
    $reader->query("SELECT ".
                     "IF(LENGTH(i.products_model)>0,i.products_model,p.products_model) AS products_model, ".
                     "pd.products_name ".
                   "FROM ".TABLE_INVENTORY." i, ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd ".
                   "WHERE i.products_id='".$reader->esc($uprid)."' ".
                     "AND p.products_id=i.prid ".
                     "AND pd.products_id=i.prid AND pd.language_id='".(int)$languages_id."' AND pd.affiliate_id=0");
    if ( $reader->count()>0 ) {
      $ret = $reader->next();
      $ret['attributes'] = array();
      preg_match_all('/\{(\d+)\}(\d+)/', $uprid, $arr, PREG_SET_ORDER);
      if (is_array($arr) && sizeof($arr)>0) {
        foreach($arr as $attr_data) {
          if (!empty($attr_data[1]) && !empty($attr_data[2])) {
            $reader->query("SELECT pa.products_attributes_id, pa.price_prefix, pa.options_values_price, ".
                             "po.products_options_id, po.products_options_name as products_options, ".
                             "pov.products_options_values_id, pov.products_options_values_name as products_options_values ".
                           "FROM ".TABLE_PRODUCTS_ATTRIBUTES." pa, ".
                             TABLE_PRODUCTS_OPTIONS." po, ".TABLE_PRODUCTS_OPTIONS_VALUES." pov ".
                           "WHERE pa.products_id='".intval($uprid)."' ".
                            "AND pa.options_id='".intval($attr_data[1])."' ".
                            "AND pa.options_values_id='".intval($attr_data[2])."' ".
                            "AND po.products_options_id=pa.options_id AND po.language_id='".(int)$languages_id."' ".
                            "AND pov.products_options_values_id=pa.options_values_id AND pov.language_id='".(int)$languages_id."'");
            if ( $attr_info = $reader->next() ) {
              unset($attr_info['products_attributes_id']);
              $attr_info['options_values_price']='0.00';
              $ret['attributes'][] = $attr_info;
            }
          }
        }
      }
    }
  }
  return $ret;
}
?>
