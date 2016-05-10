<?php
 $_SERVER['SCRIPT_NAME']='login.php';
  require('includes/application_top.php');


  tep_db_query("truncate ".TABLE_INVENTORY);

  // update inventory table
  $sql = "select p.products_id, pa.options_id, pa.options_values_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_ATTRIBUTES . " pa on p.products_id = pa.products_id order by products_id, options_id, options_values_id";
  //echo $sql;
  $res = tep_db_query($sql);
  $arr = array();
  while ($d = tep_db_fetch_array($res)){
    $arr[$d['products_id']][$d['options_id']][] = $d['options_values_id'];
  }
  foreach ($arr as $pID => $opt_arr){
    $arr1 = array();
    foreach ($opt_arr as $oid => $val_arr){
      if(tep_not_null($oid)){
        $arr1[] = add_str($val_arr, '{' . $oid . '}');
      }
    }
    $pids = array();
    $l = count($arr1)-1;
    $pids = get_all_uprid ($pID, $arr1, 0, $l, $pids);
    //echo "<pre>";    print_r($pids); echo "</pre>";
    $res = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id='" . $pID . "'");
    $d = tep_db_fetch_array($res);
    foreach ($pids as $uprid){
      $qty = $d['products_quantity'];
      $qty = ($qty < 0)?0:$qty;
      $sql = "insert into " . TABLE_INVENTORY . " (inventory_id,products_id, prid, products_name, products_model, products_quantity, send_notification) values ('','" . $uprid . "', '" . $pID . "', '" . addslashes(tep_get_products_name($pID, $languages_id) . ' ' . get_options($uprid)) . "', '" . $d['products_model'] . "', '" . $qty . "', 1)";
      $d['products_quantity'] = 0;
      //echo "$sql <br>";
      tep_db_query($sql);
    }
  }


  // update orders_products table
  $sql = "select op.orders_products_id as op_id, op.products_id, po.products_options_id as options_id, pov.products_options_values_id as values_id from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where opa.orders_products_id = op.orders_products_id and op.uprid='' and po.products_options_name = opa.products_options and po.language_id='" . $languages_id . "' and pov.products_options_values_name = opa.products_options_values and pov.language_id='" . $languages_id . "' order by op.orders_products_id, po.products_options_id, pov.products_options_values_id";
  $res = tep_db_query($sql);
  $arr = array();

  $tot_av = tep_db_num_rows($res);

  echo '<br>Av products -> ' . $tot_av . '<br>';
  flush();

  $tot_done = 0;

  //echo $sql;
  while ($d = tep_db_fetch_array($res)){
    $arr[$d['op_id']][$d['products_id']][$d['options_id']][] = $d['values_id'];
  }
  //echo sizeof($arr);
  //print_r($arr);
  $op_arr = $arr;
  foreach ($op_arr as $opID => $arr){
    foreach ($arr as $pID => $opt_arr){
      $arr1 = array();
      foreach ($opt_arr as $oid => $val_arr){
        if(tep_not_null($oid)){
          $arr1[] = add_str($val_arr, '{' . $oid . '}');
        }
      }
      $pids = array();
      $l = count($arr1)-1;
      $pids = get_all_uprid ($pID, $arr1, 0, $l, $pids);
      //echo "<pre>";    print_r($pids); echo "</pre>";
      $res = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id='" . $pID . "'");
      $d = tep_db_fetch_array($res);
      foreach ($pids as $uprid){
        $tot_done++;

        $qty = $d['products_quantity'];
        $sql = " update " . TABLE_ORDERS_PRODUCTS . " set uprid='" . $uprid . "' where orders_products_id='" . $opID . "'";
        //echo "$sql <br>";
        tep_db_query($sql);
      }
    }
  }

tep_db_query("update ".TABLE_ORDERS_PRODUCTS." set uprid=products_id where uprid=''");

  echo 'Done -> ' . $tot_done . '/' . $tot_av . ' order_products.<br>';

require(DIR_WS_INCLUDES . 'application_bottom.php');

function get_all_uprid ($str, $arr, $j, $l, $res){
  if ($l==$j){
    $res = array_merge($res, add_str($arr[$j], $str));
  } elseif(sizeof($arr)>0) {
    foreach ($arr[$j] as $val){
      $res = get_all_uprid($str . $val, $arr, ($j+1), $l, $res);
    }
  } else {
    $res = array();
    $res[] = $str;
  }

  $res = array_unique($res);
  return $res;
}

function add_str ($arr, $str){
  $a = array();
  foreach ($arr as $item){
    $a[] = $str . $item;
  }
  return  $a;
}
?>
