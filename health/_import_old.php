<?php
  define('DB_SERVER_FROM', 'db.maytech.net');
  define('DB_SERVER_USERNAME_FROM', 'healthc');
  define('DB_SERVER_PASSWORD_FROM', 'Q8MhEJp3kF0M');
  define('DBFROM', 'healthc');

  define('DB_SERVER_TO', 'db.maytech.net');
  define('DB_SERVER_USERNAME_TO', 'health4all2');
  define('DB_SERVER_PASSWORD_TO', 'Diamatrox3281'); 
  define('DBTO', 'health4all2');

  define('DESTPREFIX', '');

echo "<font color=red>From ".DBFROM." to ".DBTO."</font><hr>";

$_dry_run = false;

function _short_desc($text, $cut=255){
  $text = trim( strip_tags($text) );
  $text = preg_replace('/\s{2,}/', ' ', $text);
  if ( $cut!==false ) { 
    $text = substr( $text, 0, $cut );
    $text = preg_replace( '/([\w\s]*?)$/', '',$text );
  }
  return $text;  
}

  $src = array(
          'address_book' => array ( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'address_book'
                                             , 'trans'=>array('entry_cf'=>null,'entry_piva'=>null)                                             
                                           )
                            ),
          /*'banners' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'banners'
                                           )
                            ),*/

          'categories' => array( 'dest'=>array( 'truncate' => true,
                                                'table' => DESTPREFIX.'categories'
                                           )
                            ),
          'categories_description' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'categories_description'
                                                ,'trans'=>array(
                                                   'categories_seo_url'=>null,
                                                   'categories_htc_title_tag'=>'categories_head_title_tag',
                                                   'categories_htc_desc_tag'=>'categories_head_desc_tag',
                                                   'categories_htc_keywords_tag'=>'categories_head_keywords_tag',
                                                   'categories_htc_description'=> 'categories_description')
                                           )
                            ),

          'counter' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'counter'
                                           )
                            ),

          'customers' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'customers'
                                             ,'trans'=>array(
                                                 'member_flag'=>null
                                             )
                                           )
                            ),
          'customers_info' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'customers_info'
                                           )
                            ),

          'customers_basket' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'customers_basket'
                                             ,'trans'=>array(
                                                 'final_price'=>null
                                             )
                                            , 'compute' => array(
                                              'final_price' => ' $val = 0; ',
                                              )

                                           )
                            ),
          'customers_basket_attributes' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'customers_basket_attributes'
                                           )
                            ),
          'currencies' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'currencies'
                                           )
                            ),  

          'coupons' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'coupons')
                            ),
          'coupons_description' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'coupons_description')
                            ),
          'coupon_email_track' => array( 'dest'=>array( 'truncate' => true,
                                         'table' => DESTPREFIX.'coupon_email_track')
                            ),
          'coupon_gv_customer' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'coupon_gv_customer')
                            ),
          'coupon_gv_queue' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'coupon_gv_queue')
                            ),
          'coupon_redeem_track' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'coupon_redeem_track')
                            ),

          'featuredproducts' => array( 'dest'=>array( 'truncate' => true,
                                                'table' => DESTPREFIX.'featured'
                                             ,'trans'=>array(
                                                 'id'=>'featured_id',
                                                 'products_id'=>'products_id',
                                                 'serial'=>null,
                                             )
                                           )
                            ),

          'geo_zones' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'geo_zones'
                                           )
                            ),
                            /*
          'languages' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'languages'
                                           )
                            ),*/
          'manufacturers' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'manufacturers'
                                           )
                            ),
          'manufacturers_info' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'manufacturers_info'
                                             ,'trans'=>array(
                                                 'manufacturers_htc_title_tag'=>null,
                                                 'manufacturers_htc_desc_tag'=>null,
                                                 'manufacturers_htc_keywords_tag'=>null,
                                                 'manufacturers_htc_description'=>null,
                                             )
                                             
                                           )
                            ),
          'newsletters' => array( 'dest'=>array( 'truncate' => true,
                                                 'table' => DESTPREFIX.'newsletters'
                                           )
                            ),


          'orders' => array( 'dest'=>array( 'truncate' => true,
                                            'table' => DESTPREFIX.'orders'
                                            , 'trans'=>array(
                                              'account_name'=>null,
                                              'account_number'=>null,
                                              'po_number'=>null,
                                              'payment_id'=>null, //????!!!!!!! TODO
                                            )
                                            , 'compute' => array(
                                              'customers_firstname' => 'list($val,$val2) = split( \' \', $data_from[\'customers_name\'], 2 );',
                                              'customers_lastname' => 'list($val2,$val) = split( \' \', $data_from[\'customers_name\'], 2 );',
                                              'delivery_firstname' => 'list($val,$val2) = split( \' \', $data_from[\'delivery_name\'], 2 );',
                                              'delivery_lastname' => 'list($val2,$val) = split( \' \', $data_from[\'delivery_name\'], 2 );',
                                              'billing_firstname' => 'list($val,$val2) = split( \' \', $data_from[\'billing_name\'], 2 );',
                                              'billing_lastname' => 'list($val2,$val) = split( \' \', $data_from[\'billing_name\'], 2 );',
                                              'language_id' => ' $val = 1; '
                                              
                                           )
                                        )
                            ),
          'orders_products' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'orders_products'
                                             ,'compute' => array(
                                                              'uprid'=>' $val = $data_from[\'products_id\'];'
                                                            )
                                           )
                            ),
          'orders_products_attributes' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'orders_products_attributes'
                                           )
                            ),
          'orders_products_download' => array( 'dest'=>array( 'truncate' => true,
                                               'table' => DESTPREFIX.'orders_products_download'
                                           )
                            ),
          'orders_status' => array( 'dest'=>array( 'truncate' => true,
                                                   'table' => DESTPREFIX.'orders_status'
                                           )
                            ),                            
          'orders_status_history' => array( 'dest'=>array( 'truncate' => true,
                                            'table' => DESTPREFIX.'orders_status_history'
                                           )
                            ),

          'orders_total' => array( 'dest'=>array( 'truncate' => true,
                                                  'table' => DESTPREFIX.'orders_total'
                                           )
                            ),


          'products' => array( 'dest'=>array( 'truncate' => true,
                                              'table' => DESTPREFIX.'products'
                                              /*,'trans'=>array(
                                                             'products_percentage'=>null
                                                            )*/
                                           )
                            ),
          'products_attributes' => array( 'dest'=>array( 'truncate' => true,
                                                         'table' => DESTPREFIX.'products_attributes'
                                              ,'trans'=>array(
                                                             'attribute_sort'=>'products_options_sort_order'
                                                            )
                                                         
                                           )
                            ),
          'products_attributes_download' => array( 'dest'=>array( 'truncate' => true,
                                                   'table' => DESTPREFIX.'products_attributes_download'
                                           )
                            ),
          'products_description' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_description'
                                              ,'trans'=>array(
                                                             'products_seo_url'=>null,
                                                            )
                                           )
                            ),
          'products_notifications' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_notifications'
                                           )
                            ),
          'products_options' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_options'
                                           )
                            ),
          'products_options_values' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_options_values'
                                           )
                            ),
          'products_options_values_to_products_options' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_options_values_to_products_options'
                                           )
                            ),

//           products_related_products
          'products_to_categories' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_to_categories'
                                           )
                            ),
          'reviews' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'reviews'
                                             ,'compute'=>array(
                                                            'status' => '$val = 1;'
                                                         )
                                           )
                            ),
          'reviews_description' => array( 'dest'=>array( 'truncate' => true,
                                          'table' => DESTPREFIX.'reviews_description'
                                           )
                            ),
          'specials' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'specials'
                                           )
                            ),
          'tax_class' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'tax_class'
                                           )
                            ),
          'tax_rates'=>array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'tax_rates'
                                           )
                            ),
/*                            
          'zones' => array( 'dest'=>array( 'truncate' => true,
                                           'table' => DESTPREFIX.'zones'
                                           ) ),*/
          'zones_to_geo_zones' => array( 'dest'=>array( 'truncate' => true,
                                         'table' => DESTPREFIX.'zones_to_geo_zones'
                                                      )
                                       ),
          'products_related_products' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'products_xsell'
                                             , 'trans'=> array(
                                             'pop_id'=>'ID',
                                             'pop_products_id_master'=>'products_id',
                                             'pop_products_id_slave'=>'xsell_id',
                                             'pop_order_id'=>'sort_order',
                                                          )
                                           )
                            ),
          'scart' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'scart') ),
          'protx_direct' => array( 'dest'=>array( 'truncate' => true,
                                             'table' => DESTPREFIX.'protx_direct') ),

  );

  $custom_tables = array();
  
  $db_f = mysql_connect(DB_SERVER_FROM, DB_SERVER_USERNAME_FROM, DB_SERVER_PASSWORD_FROM,true) or die("Could not connect [from] server");
  $db_t = mysql_connect(DB_SERVER_TO, DB_SERVER_USERNAME_TO, DB_SERVER_PASSWORD_TO,true) or die("Could not connect [to] server");

  mysql_select_db(DBFROM,$db_f) or die("Could not choose [".DBFROM."]");
  mysql_select_db(DBTO,$db_t) or die("Could not choose [".DBTO."]");


// alter table address_book add column entry_apt_number varchar(60);
   
  // check destination struct
  $run_next = true;
  echo '<h3>check destination struct</h3><hr>';
  $checks = array_merge($src,$custom_tables);
  //foreach ( $src as $src_table=>$dest ) {
  foreach ( $checks as $src_table=>$dest ) {  
    echo '<b> &nbsp; '.$src_table.'-&gt;'.$dest['dest']['table'].'</b><br>';
    $src_sch = get_schema( $src_table, $db_f);
    if ( isset($src[$src_table]['dest'])) $src[$src_table]['dest']['confirm']=true;    
    if ( $src_sch===false ) { 
      if ( isset($src[$src_table]['dest'])) $src[$src_table]['dest']['confirm']=false;    
      echo '<font color="darkred">'.DBFROM.'.<b>'.$src_table.'</b> not exist</font><br>';
      continue;
    }
    if ( isset($dest['dest']['trans']) && is_array($dest['dest']['trans']) ) {
      foreach( $dest['dest']['trans'] as $old_key=>$new_key ) {
        if ( !is_null($new_key) ) $src_sch[$new_key] = $src_sch[$old_key];
        unset($src_sch[$old_key]);
      }
    }
    if ( isset($dest['dest']['compute']) && is_array($dest['dest']['compute']) ) {
      foreach( $dest['dest']['compute'] as $computed_key=>$eval_expr ) {
        $src_sch[$computed_key] = 'computed field NF';
        eval($eval_expr);
      }
    }
    
    $dst_sch = get_schema( $dest['dest']['table'], $db_t);
    if ( $dst_sch===false ) {
      echo '<font color="darkred">'.DBTO.'.<b>'.$dest['dest']['table'].'</b> not exist</font><br>';
      continue;
    }

    if ( isset($dest['dest']['compute']) && is_array($dest['dest']['compute']) ) {
      foreach( $dest['dest']['compute'] as $computed_key=>$eval_expr ) {
        if ( isset( $dst_sch[$computed_key] ) ) $src_sch[$computed_key] = $dst_sch[$computed_key];
      }
    }

    $res = cmp_schema($src_sch,$dst_sch);
    if ( $res===true ) {
      echo '<font color=green>OK</font><br>';
    }else{
      $run_next = false;
    }
  }


// config test
echo '<h3>config test</h3><hr>';
$cfg_f_r = mysql_query('select * from configuration order by configuration_id',$db_f);
while ( $cfg_f = mysql_fetch_array($cfg_f_r) ) {
  
  $cfg_t_r = mysql_query('select * from '.DESTPREFIX.'configuration where configuration_key=\''.$cfg_f['configuration_key'].'\'',$db_t);
  if ( mysql_num_rows($cfg_t_r)==0 ) {
    echo 'TO '.$cfg_f['configuration_key'].' NF <br>';
  }else{
    $cfg_t = mysql_fetch_array($cfg_t_r);
    if ( $cfg_f['configuration_key']!=$cfg_t['configuration_key'] ) {
      echo 'FROM != TO '.$cfg_f['configuration_key'].'<br>';
    }else{
      if ( false && $_dry_run===false ) { ////////////////////////////////// skip config now
// config move
    //skip f c
        $skip = array('IMAGE_REQUIRED', 'SEARCH_ENGINE_FRIENDLY_URLS', 'MODULE_PAYMENT_INSTALLED', 'MODULE_ORDER_TOTAL_INSTALLED');
        if ( !in_array($cfg_f['configuration_key'],$skip)  ) {
          mysql_query('update '.DESTPREFIX.'configuration set configuration_value=\''.import_input($cfg_f['configuration_value'],'db_t').'\' where configuration_key=\''.$cfg_f['configuration_key'].'\'',$db_t);
        }
// \ config move    
      }
    }
  }
}

if ( $run_next ) {
  echo '<h1>Pass</h1>';
}else{
  die;
}


if ( $run_next ) {
  echo '<h1>START PROCESSING</h1>';
  foreach ( $src as $src_table=>$dest ) {
    echo '<b>'.$src_table.'</b><br>';
    if ( $dest['dest']['truncate'] ) { echo ' Do truncate<br>'; 
      if ( $_dry_run==false ) {
        if ( $dest['dest']['truncate'] ) {
          mysql_query('TRUNCATE TABLE '.$dest['dest']['table'].'',$db_t);
        }
      }
    }
    if ( !$dest['dest']['confirm'] ) { echo ' Skip: not confirmed<br>'; continue; }
    if ( $_dry_run==false ) {
      $data_from_r = mysql_query('select * from '.$src_table.'',$db_f);
      while( $data_from = mysql_fetch_assoc($data_from_r) ){
        if ( isset($dest['dest']['trans']) && is_array($dest['dest']['trans']) ) {
          foreach( $dest['dest']['trans'] as $old_key=>$new_key ) {
            if ( !is_null($new_key) ) $data_from[$new_key] = $data_from[$old_key];
            unset($data_from[$old_key]);
          }
        }
        foreach ( $data_from as $idx=>$val ) {
          if ( is_null($val) ) $data_from[$idx] = 'null';
        }
/// shnjagka
    if ( isset($dest['dest']['compute']) && is_array($dest['dest']['compute']) ) {
      foreach( $dest['dest']['compute'] as $computed_key=>$eval_expr ) {
        eval($eval_expr);
        $data_from[$computed_key] = $val;
      }
    }
//\
        import_perform( $dest['dest']['table'], $data_from, 'insert', '', 'db_t');
      }
    }
  }  
}
if ( $run_next ) {
  // process $custom_tables
// run orders
echo '<h1>Post Process</h1>';
if ( $_dry_run==false ) {
  
} // dry _ run
}

if ( $run_next && $_dry_run==false ) {
  
  $data_r = mysql_query("select products_id, language_id, products_description from products_description",$db_t);
  while( $data = mysql_fetch_assoc($data_r) ){
    mysql_query("update products_description 
    set products_description_short='".import_input( _short_desc($data['products_description']), 'db_t' )."' 
    where language_id = '".$data['language_id']."' and products_id = '".$data['products_id']."'",$db_t);
  }
  mysql_query("INSERT INTO `orders_status` (`orders_status_id`, `language_id`, `orders_status_name`) VALUES
(99999, 1, 'Paypal Processing')",$db_t);

  $data_r = mysql_query("select products_id, products_seo_url from products_description",$db_f);
  while( $data = mysql_fetch_assoc($data_r) ){
    mysql_query("update products set products_seo_page_name='".import_input($data['products_seo_url'],'db_t')."' where products_id = '".$data['products_id']."'",$db_t);
  }
  
}

echo '<h1>Stats</h1>';
  $checks = array_merge($src,$custom_tables);
  //foreach ( $src as $src_table=>$dest ) {
  foreach ( $checks as $src_table=>$dest ) {
      
    $c_from = mysql_fetch_assoc(mysql_query("select count(*) as c from ".$src_table."",$db_f));
    echo '<b> &nbsp; '.$src_table.' ['.$c_from['c'].'] ';
    if ( $src[$src_table]['dest']['confirm']!==false ) {
      $c_to = mysql_fetch_assoc(mysql_query("select count(*) as c from ".$dest['dest']['table']."",$db_t));
      echo $dest['dest']['table'].' ['.$c_to['c'].']';
      if ( $c_from['c']!=$c_to['c'] ) echo '&nbsp;<font color="red">&lt;--?</font>';
    }
    echo '</b><br>';
  }
  mysql_close($db_f);
  mysql_close($db_t);
//=--=-=-=-=-=-==-=-=-=-=-=-=-=-=-=-=-=-=-=

  function import_input($string, $link = 'db_link') {
    global $$link;
   
    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($string);
    }
   
    return addslashes($string);
  }

function import_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link'){
      global $$link;
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . import_input($value,$link) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . import_input($value,$link) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }
    /*
    if ( $table=='customers_basket' ) {
    echo '<pre>'; var_dump(1); echo '</pre>'.$query;
    die;
    }*/
      return mysql_query($query, $$link);

}

function cmp_schema($tbl1, $tbl2){
  $left = array_diff_assoc($tbl1,$tbl2);
  $right = array_diff_assoc($tbl2,$tbl1);
  if ( count($left)==count($right) && count($right)==0 ) {
    return true;
  }else{
    if ( count($left)==0 && count($right)>0 ) {
      $def_chk = true;
      foreach( $right as $fld=>$info ) {
        if ( is_null($info['default']) && $info['null']=='NO' ) $def_chk = false;
        echo ' Right '.$fld.' default value ['.$info['default'].']<br>';
      }
    }
    echo '<pre>$left';var_dump($left);echo '</pre>';  
    echo '<pre>$right';var_dump($right);echo '</pre>';
    if ( count($left)==0 ) return true;  
  }
  return false;  
}

function get_schema($table, $link){
  $r = mysql_query("SHOW COLUMNS FROM `".$table."`",$link);
  if (!$r) { return false; }  
  $ret = array();
  if (mysql_num_rows($r)>0) while ( $d = mysql_fetch_array($r) ) {
  
    $ret[$d['Field']] = array(
      'type'=>$d['Type'],
      'default'=>$d['Default'],
      'null'=>$d['Null']
    );
  }
  return $ret;
}
?>