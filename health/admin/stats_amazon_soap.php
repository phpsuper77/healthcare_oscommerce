<?php
/*
  $Id: stats_products_viewed.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('../amazon/core.php');
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
  $header_title_menu_link= tep_href_link(FILENAME_STATS_AMAZON_SOAP, 'selected_box=reports');
  $header_title_submenu=HEADING_TITLE;
  $data_r = tep_db_query("select distinct as_feedtype from " . TABLE_AMAZON_SOAP . " order by as_feedtype");
  $a_feedtype = array();
  while( $data = tep_db_fetch_array($data_r) ){
    if ( empty($data['as_feedtype']) ) continue;
    $a_feedtype[] = array('id'=>$data['as_feedtype'],'text'=>$data['as_feedtype']);
  }
  if ( count($a_feedtype)>0 ) {
     $header_title_additional = tep_draw_form('filterfeed',FILENAME_STATS_AMAZON_SOAP,'','get').tep_draw_hidden_field('page').'Feed: '.tep_draw_pull_down_menu('filterfeed',array_merge( array(array('id'=>'','text'=>'--All--')), $a_feedtype ),'','onchange="this.form.submit()"').'</form>';
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_POLL; ?></td>
              </tr>
<?php
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_raw = "select * from " . TABLE_AMAZON_SOAP . (isset( $_GET['filterfeed'] ) && !empty($_GET['filterfeed'])? " where as_feedtype='".tep_db_input($_GET['filterfeed'])."' ":'') . " order by as_id DESC";
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($status = tep_db_fetch_array($products_query)) {
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent"><?php echo $status['as_date']; ?></td>
                <td class="dataTableContent"><?php echo $status['as_feedtype']; ?></td>
                <td class="dataTableContent"><?php echo $status['as_state']; ?></td>
                <td class="dataTableContent"><?php echo $status['as_lastAckDate']; ?></td>
              </tr>
<?php
    if ( !empty($status['as_lastinfo']) ) {
      echo '<tr><td colspan="4">';
      $ProcessingReport = new AmazonProcessingReport();
      $ProcessingReport->fromXML( $status['as_lastinfo'] );
      $ProcessingReport->render();
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
