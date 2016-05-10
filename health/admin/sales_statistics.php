<?php
/*
  $Id: sales_statistics.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
    $patterns = array ("/filter_name=[^&]*&/", 
                       "/([?&])x=[^&]*&/", 
                       "/([?&])y=[^&]*&/", 
                       "/apply_filter=[^&]*&/", 
                       "/remove_filter=[^&]*&/", 
                       "/&$/", 
                       "/month=[^&]*&/", 
                       "/year=[^&]*&/", 
                       "/&+/", 
                       "/^&/", 
                       "/" . tep_session_name() . "=[^&]*&/" ); 
    $replace = array ('', '\\1', '\\1', '', '', '', '', '', '&', '', ''); 
    $str = preg_replace ($patterns, $replace, $HTTP_SERVER_VARS["QUERY_STRING"] . '&');

  if (strlen($HTTP_GET_VARS['filter_name'])>0){ 
    //save customer's filter
    tep_db_query("insert into " . TABLE_SALES_FILTERS . " set sales_filter_vals='" . tep_db_input(tep_db_prepare_input($str)) . "', sales_filter_name='" . tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['filter_name'])) . "'");
    $GLOBALS['filter_name'] = '';
  }
  if ($HTTP_GET_VARS['remove_filter']==1){ 
    tep_db_query("delete from " . TABLE_SALES_FILTERS . " where sales_filter_vals='" . tep_db_prepare_input($str) . "' limit 1 ");
    unset($HTTP_GET_VARS['remove_filter']);
  }

  $r = tep_db_query("select * from " . TABLE_SALES_FILTERS . " where sales_filter_vals='" . tep_db_prepare_input($str) . "' limit 0,1 ");
  if ($d = tep_db_fetch_array($r)){
    $applied_filter = tep_href_link(FILENAME_SALES_STATISTICS, htmlspecialchars($d['sales_filter_vals']));

  }

  $order_extension = tep_banner_image_extension(); //available IMAGE types (gd required)

  $colors_html = array();
  for ($i=0; $i<count($colors_graph);$i++){
    $colors_html[] = sprintf("#%02X%02X%02X", $colors_graph[$i][0], $colors_graph[$i][1], $colors_graph[$i][2]);
  }

// check if the graphs directory exists
  $dir_ok = true;
  $years_array = array();
  $years_query = tep_db_query("select distinct year(date_purchased) as order_year from " . TABLE_ORDERS . " order by order_year desc");
  while ($years = tep_db_fetch_array($years_query)) {
    $years_array[] = array('id' => $years['order_year'],
                           'text' => $years['order_year']);
  }

  $months_array = array();
  for ($i=1; $i<13; $i++) {
    $months_array[] = array('id' => $i,
                            'text' => strftime('%B', mktime(0,0,0,$i,1)));
  }

  $type_array = array(array('id' => 'daily',
                            'text' => STATISTICS_TYPE_DAILY),
                      array('id' => 'monthly',
                            'text' => STATISTICS_TYPE_MONTHLY),
                      array('id' => 'yearly',
                            'text' => STATISTICS_TYPE_YEARLY));
  $filters_query = tep_db_query("select sales_filter_vals, sales_filter_name from " . TABLE_SALES_FILTERS . " order by sales_filter_name");
  $filters = array();
  $filters[] = array('id'=> '', 'text' => TEXT_SELECT);
  while ($d = tep_db_fetch_array($filters_query)){
    $filters[] = array('id'=> tep_href_link(FILENAME_SALES_STATISTICS, htmlspecialchars($d['sales_filter_vals'])), 'text' => $d['sales_filter_name']);
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_REPORTS;
  $header_title_menu_link= tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports');
  $header_title_submenu=HEADING_TITLE;
  ?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr><?php echo tep_draw_form('sales', FILENAME_SALES_STATISTICS, '', 'get'); ?>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
          </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;</td>
            <td class="pageHeading" ><?php echo tep_draw_separator('pixel_trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="main" align="right" Valign="top"><table cellpadding="3" cellspacing="0">
              <tr>
                <td class="main" Valign="top"><?php echo TEXT_SAVE_AS_FILTER . tep_draw_input_field('filter_name'); ?></td>
                <td class="main" Valign="top"><?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT) ?> </td>
              </tr>
              <tr>
                <td align="right" class="main" Valign="top"><?php echo tep_draw_pull_down_menu('apply_filter', $filters, $applied_filter) ?> <?php echo tep_image_submit('button_select.gif', IMAGE_APPLY, 'onclick="document.location=document.sales.apply_filter.options[document.sales.apply_filter.selectedIndex].value; return false;"')?></td>
                <td class="main" Valign="top"><?php echo tep_image_submit('button_delete.gif', IMAGE_DELETE, 'onclick="if ((document.sales.apply_filter.selectedIndex>0) && confirm(\'' . TEXT_REMOVE . '\'+ \' \' + document.sales.apply_filter.options[document.sales.apply_filter.selectedIndex].text +\'?\')) document.location=document.sales.apply_filter.options[document.sales.apply_filter.selectedIndex].value + \'&amp;remove_filter=1\'; return false;"'); ?>
                </td>
              </tr>
            </table></td>
            <td class="main" align="right"><?php 
  $graph_types_array = array('bars','lines','linepoints','points');
  $graph_types = array();
  for ($i=0; $i<count($graph_types_array); $i++){
    $graph_types[] = array('id'=>$graph_types_array[$i], 'text'=>$graph_types_array[$i]);
  }
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
  while ($d= tep_db_fetch_array($orders_query)){
    $classes_array[] = $d['class'];
    if (($HTTP_GET_VARS[$d['class']]=='on') || $chk){
      $sel_total[] = $d['class'];
      $headers_array[$d['class']] = $j+(($show_total_order)?2:1);
      $j++;
      $tmp[] = 0;
    }
  }  
  echo TITLE_GRAPH . ' ' . tep_draw_pull_down_menu('graph', $graph_types, $HTTP_GET_VARS['graph'], 'onChange="this.form.submit();"') . '<noscript><input type="submit" value="GO"></noscript>&nbsp;';
  

            echo TITLE_TYPE . ' ' . tep_draw_pull_down_menu('type', $type_array, (($HTTP_GET_VARS['type']) ? $HTTP_GET_VARS['type'] : 'daily'), 'onChange="this.form.submit();"'); ?><noscript><input type="submit" value="GO"></noscript><br>
<?php

  switch ($HTTP_GET_VARS['type']) {
    case 'yearly': break;
    case 'monthly':
      echo TITLE_YEAR . ' ' . tep_draw_pull_down_menu('year', $years_array, (($HTTP_GET_VARS['year']) ? $HTTP_GET_VARS['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" value="GO"></noscript>';
      break;
    default:
    case 'daily':
      echo TITLE_MONTH . ' ' . tep_draw_pull_down_menu('month', $months_array, (($HTTP_GET_VARS['month']) ? $HTTP_GET_VARS['month'] : date('n')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" value="GO"></noscript><br>Year: ' . tep_draw_pull_down_menu('year', $years_array, (($HTTP_GET_VARS['year']) ? $HTTP_GET_VARS['year'] : date('Y')), 'onChange="this.form.submit();"') . '<noscript><input type="submit" value="GO"></noscript>';
      break;
  }


?>
            </td></tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="columnLeft"><span class="formAreaTitle"><?php echo HEADING_SHOW_TOTALS;?>&nbsp;</span></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '2'); ?></td>
              </tr>

              <tr>
                <td class="formArea"><table width="100%" border="0" cellspacing="1" cellpadding="2" >
                  <tr>
                    <td class="main" <?php echo 'style="background-color:' . $colors_html[0] . '"';?>">&nbsp;<?php echo tep_draw_checkbox_field('ototal', '', $chk);?>&nbsp;</td>
                    <td class="main"><?php echo strtoupper(TABLE_HEADING_COUNT);?>&nbsp;</td>
                  </tr>
        <?php 
          $c = 0;
          foreach($classes_array as $v){
              $bg = 'style="background-color:' . $colors_html[$c+1] .'"';
              $c++;
        ?>
                  <tr>
                    <td class="main" <?php echo $bg;?>>&nbsp;<?php echo tep_draw_checkbox_field($v, '', $chk);?>&nbsp;</td>
                    <td class="main"><?php echo str_replace('_', ' ', strtoupper(substr($v, 3)));?>&nbsp;</td>
                  </tr>
        <?php 
          }
        ?>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
              </tr>
<!-- orders statuses -->
              <tr valign="top">
                <td colspan="2" class="columnLeft"><span class="formAreaTitle"><?php echo HEADING_SHOW_ORDERS_STATUSES;?>&nbsp;</span></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '2'); ?></td>
             </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="1" cellpadding="2" class="formArea">
        <?php 
          $sel_status = array();
          $status_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS . " where language_id='" . $languages_id . "'");
          while ($d = tep_db_fetch_array($status_query)){
            if ($HTTP_GET_VARS['s_' . $d['orders_status_id']]=='on'){
              $sel_status[] = $d['orders_status_id'];
            }
        ?>
                  <tr>
                    <td class="main">&nbsp;<?php echo tep_draw_checkbox_field('s_' . $d['orders_status_id'], '', $chk);?>&nbsp;</td>
                    <td class="main"><?php echo $d['orders_status_name'];?></td>
                  </tr>
        <?php 
          }
          if (count($sel_status)>0){
            $sel_status_sql = " and o.orders_status in ('" . implode("', '", $sel_status) . "')";
          } else {
            $sel_status_sql = ' and 1 ';
          }
        ?>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
            </table></td>
            <td valign="top" align="center">
<?php




  if ( (function_exists('imagecreate')) && ($dir_ok) && ($order_extension) ) {
    if ($chk){
      $exl = array('file', 'show_stat', 'graph', 'apply_filter', 'remove_filter');
    } else {
      $exl = array('file', 'show_stat', 'apply_filter', 'remove_filter');
    }
    switch ($HTTP_GET_VARS['type']) {
      case 'yearly':
        include(DIR_WS_INCLUDES . 'graphs/orders_yearly.php');
          echo tep_image(tep_href_link('statimgs.php', tep_get_all_get_params($exl) . 'file=1&show_stat=1'));
        break;
      case 'monthly':
        include(DIR_WS_INCLUDES . 'graphs/orders_monthly.php');
//        echo tep_image(DIR_WS_IMAGES . 'graphs/order_monthly.' . $order_extension);
          echo tep_image(tep_href_link('statimgs.php', tep_get_all_get_params($exl) . 'file=2&show_stat=1'));
        break;
      default:
      case 'daily':
        include(DIR_WS_INCLUDES . 'graphs/orders_daily.php');
          echo tep_image(tep_href_link('statimgs.php', tep_get_all_get_params($exl) . 'file=3&show_stat=1'));
//        echo tep_image(DIR_WS_IMAGES . 'graphs/order_daily.' . $order_extension);
        break;
    }
?>
          <table border="0" width="600" cellspacing="1" cellpadding="2">
            <tr class="dataTableHeadingRow">
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SOURCE; ?></td>
<?php if ($show_total_order) {?>
             <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNT; ?></td>
<?php }?>
<?php 
    foreach ($headers_array as $k => $v) {  
//      if (preg_match('/^ot_/', $k)){
        $ttl = str_replace('_', ' ', strtoupper(substr($k, 3)));
?>
             <td class="dataTableHeadingContent" ><?php echo $ttl; ?></td>
<?php
//      }
    }
?>
           </tr>
<?php
    $totals_array = array();
//echo "<PRE>"; print_r($stats); echo "</PRE>"; 
//    for ($i=0; $i<count($stats); $i++) {
    $i = 0;
    foreach ($stats as $i => $a) {
      if ($i<0) continue;
      echo '            <tr class="dataTableRow">' . "\n";
//      $a = $stats[$i];
      $j = 0;
      foreach ($a as $k => $v) {  
        echo '              <td class="dataTableContent" align="right">' . ($v>0?(($j <($show_total_order?2:1) )?$v:number_format($v, 2)):'') . '</td>' . "\n";
        if ($i==0){
          $totals_array[$j] = (($j <($show_total_order?2:1) )?$v:number_format($v, 2,'.',''));
        } else {
          $totals_array[$j] += (($j <($show_total_order?2:1) )?$v:number_format($v, 2,'.',''));
        }
        $j++;
      }
      echo '            </tr>' . "\n";
    }
      echo '            <tr class="dataTableRow">' . "\n";
      $a = $stats[0];
      $j = 0;
      foreach ($a as $k) {  
        echo '              <td class="dataTableContent" align="right"><b>' . ($j>0?($totals_array[$j]>0?(($j <($show_total_order?2:1) )?$totals_array[$j]:number_format($totals_array[$j], 2)):''):TEXT_TOTAL) . '</b></td>' . "\n";
        $j++;
      }
      echo '            </tr>' . "\n";
?>
          </table>
<?php
  }  else {
    /*include(DIR_WS_FUNCTIONS . 'html_graphs.php');
    switch ($HTTP_GET_VARS['type']) {
      case 'yearly':
        echo tep_order_graph_yearly($HTTP_GET_VARS['bID']);
        break;
      case 'monthly':
        echo tep_order_graph_monthly($HTTP_GET_VARS['bID']);
        break;
      default:
      case 'daily':
        echo tep_order_graph_daily($HTTP_GET_VARS['bID']);
        break;
    }
    */
    echo "Your server doesn't support this module (GD library is required)";
  }
?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
    </table></td>
   </form>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
