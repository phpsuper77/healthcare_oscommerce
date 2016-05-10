<?php
/*
  $Id: stats_products_viewed.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_FS_CATALOG.'ebay/core.php');
  
  $ebay_sites_id = array(
    '0' => 'United States',
  '100' => 'eBay Motors',
  '101' => 'Italy',
  '123' => 'Belgium (Dutch)',
  '146' => 'Netherlands',
  '15' => 'Australia',
  '16' => 'Austria',
  '186' => 'Spain',
  '193' => 'Switzerland',
  '196' => 'Taiwan',
  '2' => 'Canada',
  '201' => 'Hong Kong',
  '203' => 'India',
  '205' => 'Ireland',
  '207' => 'Malaysia',
  '210' => 'Canada (French)',
  '211' => 'Philippines',
  '212' => 'Poland',
  '216' => 'Singapore',
  '218' => 'Sweden',
  '223' => 'China',
  '23' => 'Belgium (French)',
  '3' => 'UK',
  '71' => 'France',
  '77' => 'Germany');

  $choose_connector = array();
  $active_ids = array();
  $data_r = tep_db_query("select distinct connector_id from ".TABLE_EBAY_LOG." order by connector_id");
  while( $data = tep_db_fetch_array($data_r) ){
    $active_ids[] = $data['connector_id'];
    $choose_connector[] = array('id'=>$data['connector_id'], 
                                'text'=>$data['connector_id']. (isset($ebay_sites_id[ $data['connector_id'] ])?' - '.$ebay_sites_id[ $data['connector_id'] ]:''));
  }
  if ( isset( $_GET['connector_id'] ) ) $connector_id = $_GET['connector_id'];
  if ( !in_array((int)$connector_id,$active_ids) ) $connector_id = (int)$active_ids[0];
     
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_REPORTS;
  $header_title_menu_link= tep_href_link(FILENAME_STATS_EBAY, 'selected_box=reports');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional = tep_draw_form('filterconnector', FILENAME_STATS_EBAY,'','get').'Connector: '.tep_draw_pull_down_menu('connector_id', $choose_connector, $connector_id,'onchange="this.form.submit()"').'</form>';

  $data_r = tep_db_query("select distinct feedtype from " . TABLE_EBAY_LOG . " where connector_id='".(int)$connector_id."' order by feedtype");
  $a_feedtype = array();
  while( $data = tep_db_fetch_array($data_r) ){
    if ( empty($data['feedtype']) ) continue;
    $a_feedtype[] = array('id'=>$data['feedtype'],'text'=>$data['feedtype']);
  }
  if ( count($a_feedtype)>0 ) {
     $header_title_additional .= '&nbsp;'.tep_draw_form('filterfeed',FILENAME_STATS_EBAY,'','get').tep_draw_hidden_field('page'). tep_draw_hidden_field('connector_id', $connector_id).'Feed: '.tep_draw_pull_down_menu('filterfeed',array_merge( array(array('id'=>'','text'=>'--All--')), $a_feedtype ),'','onchange="this.form.submit()"').'</form>';
  }
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FEED; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_RUN_INFO; ?></td>
              </tr>
<?php
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_raw = "select * from " . TABLE_EBAY_LOG . " where connector_id='".(int)$connector_id."' ".(isset( $_GET['filterfeed'] ) && !empty($_GET['filterfeed'])? " and feedtype='".tep_db_input($_GET['filterfeed'])."' ":'') . " order by log_id DESC";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($status = tep_db_fetch_array($products_query)) {
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent" valign="top"><?php echo $status['log_date']; ?></td>
                <td class="dataTableContent" valign="top"><?php echo $status['feedtype']; ?></td>
                <td class="dataTableContent" valign="top"><?php echo nl2br($status['extra_info']); ?></td>
              </tr>
<?php
    if ( !empty($status['extra_text']) ) {
      echo '<tr><td colspan="3" class="dataTableContent">';
      echo nl2br($status['extra_text']);
      echo '</td></tr>';
    }
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_AMAZON_STATS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page'))); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
