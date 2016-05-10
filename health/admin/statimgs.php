<?php 
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/sales_statistics.php');

  if (!$HTTP_GET_VARS['graph']){
    $HTTP_GET_VARS['graph'] = 'bars';
    $chk = true; 
  }
  $show_total_order = ($HTTP_GET_VARS['ototal']=='on' || $chk);
  $headers_array = array(); // todo group all totals by title and show sum by class
  $classes_array = array();

  $orders_query = tep_db_query("select distinct ot.class from " . TABLE_ORDERS_TOTAL . " ot order by sort_order, ot.class");
  $i = 0;
  $j = 0;
  $tmp = array(); // array for bad days (with 0 total(s))
  $sel_total = array();
  if (!$show_total_order)
    array_splice($colors_graph, 0, 1); 
  while ($d= tep_db_fetch_array($orders_query)){
    $classes_array[] = $d['class'];
    if (($HTTP_GET_VARS[$d['class']]=='on') || $chk){
      $sel_total[] = $d['class'];
      $headers_array[$d['class']] = $j+(($show_total_order)?2:1);
      $j++;
      $tmp[] = 0;
    } else {
      array_splice($colors_graph, $j+(($show_total_order)?1:0), 1); 
    }
  }  

//echo "<pre>";print_r($colors_graph);

  $sel_status = array();
  $status_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS . " where language_id='" . $languages_id . "'");
  while ($d = tep_db_fetch_array($status_query)){
    if ($HTTP_GET_VARS['s_' . $d['orders_status_id']]=='on'){
      $sel_status[] = $d['orders_status_id'];
    }
  }
  if (count($sel_status)>0){
    $sel_status_sql = " and o.orders_status in ('" . implode("', '", $sel_status) . "')";
  } else {
    $sel_status_sql = ' and 1 ';
  }

  switch ($HTTP_GET_VARS['file']){
    case 1:
      include(DIR_WS_INCLUDES . 'graphs/orders_yearly.php');
    break;
    case 2:
      include(DIR_WS_INCLUDES . 'graphs/orders_monthly.php');
    break;
    case 3:
      include(DIR_WS_INCLUDES . 'graphs/orders_daily.php');
    break;
  }
  


?>