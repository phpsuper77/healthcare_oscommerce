<?php
/*
  $Id: se_statistics.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  switch ($HTTP_GET_VARS['action']) {
    case 'clearstat':
      tep_db_query("update " . TABLE_SEARCH_WORDS . " SET click_count=0 where search_words_id ='" . $HTTP_GET_VARS['swID'] . "' ");
      tep_db_query("update " . TABLE_ORDERS . " SET search_words_id=0 where search_words_id ='" . $HTTP_GET_VARS['swID'] . "' ");
    break;
    case 'clearstatall':
      $seID = $HTTP_GET_VARS['seID'];
      if ((strlen($HTTP_GET_VARS['seID'])==0) || ($HTTP_GET_VARS['seID']==0))
      {
        $res = tep_db_query("select search_engines_id from " . TABLE_SEARCH_ENGINES . " se where se.name='overture' ");
        if ($data = tep_db_fetch_array($res))
          $seID = $data['search_engines_id'];
      }
      tep_db_query("update " . TABLE_SEARCH_WORDS . " SET click_count=0 where search_engines_id='" . $seID . "' ");
      tep_db_query("update " . TABLE_ORDERS . " SET search_words_id=0 where search_engines_id='" . $seID . "' ");
    break;
  }
  
  $sql = "select se.search_engines_id, se.name, count(sw.search_words_id) as cnt from " . TABLE_SEARCH_ENGINES . " se left join " . TABLE_SEARCH_WORDS . " sw on se.search_engines_id=sw.search_engines_id ";
  if ($HTTP_GET_VARS['spwd']!=123)   $sql .= "  where se.show_flag=1 ";
  $sql .= " group by se.search_engines_id, se.name order by cnt desc ";
  $res = tep_db_query($sql);
  $se_array = array();
  $se_array[] = array("id" => 0, 'text' => TEXT_ALL);
  while ($data = tep_db_fetch_array($res)){
    $se_array[] = array("id" => $data['search_engines_id'], 'text' => $data['name'] );
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<?php
  if ( ($HTTP_GET_VARS['action'] == 'new') || ($HTTP_GET_VARS['action'] == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_REPORTS;
  $header_title_menu_link= tep_href_link(FILENAME_SEARCH_STATISTICS, 'selected_box=reports');
  $header_title_submenu=HEADING_TITLE;
  $header_title_additional= tep_draw_form('se_filter', FILENAME_SEARCH_STATISTICS, '', 'get') . '
              <input type="hidden" name="'. tep_session_name() . '" value="' . tep_session_id() . '" >' .
                tep_draw_pull_down_menu("seID", $se_array, $HTTP_GET_VARS['seID'], ' onchange="this.form.submit();" ') . 
             '</form>';
  if ($HTTP_GET_VARS['seID']>0){
    $header_title_additional .= '<br><a href="' . tep_href_link(FILENAME_SEARCH_STATISTICS, 'action=clearstatall&seID=' . $HTTP_GET_VARS['seID']) . '" onclick="return confirm(\'' . TEXT_CONFIRM_CLEAR_STATISTICS . '\');">' . TEXT_CLEAR_ALL_STATISTICS . '</a>';
  }
  $header_title_additional .= '<br><a href="' . tep_href_link(FILENAME_EXPORT,'seID='.$HTTP_GET_VARS['seID']) . '">Export All to CSV File</a>';
  ?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_KEYWORD; ?></td>
                <td class="dataTableHeadingContent" ><?php echo TABLE_HEADING_SEARCH_ENGINE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CLICK_COUNT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BUY_COUNT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BUY_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_AVG_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $specials_query_raw = "select se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count, count(o.orders_id) as buy_count, sum(ot.value*o.currency_value) as buy_total from " . TABLE_SEARCH_WORDS ." sw left join " . TABLE_ORDERS . " o on  (se.search_engines_id=o.search_engines_id) right join " . TABLE_SEARCH_ENGINES . " se on (sw.search_words_id=o.search_words_id) left join " . TABLE_ORDERS_TOTAL ." ot on (o.orders_id=ot.orders_id and ot.class='ot_subtotal') where se.search_engines_id=sw.search_engines_id ";
    if ($HTTP_GET_VARS['spwd']!=123) $specials_query_raw .= " and show_flag=1 ";
    if ($HTTP_GET_VARS['seID']>0){
      $specials_query_raw .= " and se.search_engines_id= '" . $HTTP_GET_VARS['seID'] . "' ";
    }
    $specials_query_raw .= "group by se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count order by buy_total desc, click_count desc, sw.word ";
    
//se.name='overture' and    
if (HTTP_SERVER=="http://triasphera.local")    echo $specials_query_raw;
    $specials_split = new splitPageResults($HTTP_GET_VARS['page'], 50, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);
//    $specials_query = tep_db_query("select 1;");
    while ($specials = tep_db_fetch_array($specials_query)) {
?>
              <tr>
                <td  class="dataTableContent"><?php echo $specials['word']; ?></td>
                <td  class="dataTableContent"><?php echo $specials['name']; ?></td>
                <td  class="dataTableContent" align=center><?php echo $specials['click_count']; ?></td>
                <td  class="dataTableContent" align=center><?php echo $specials['buy_count']; ?></td>
                <td  class="dataTableContent" align=center><?php echo $currencies->format($specials['buy_total']); ?></td>
                <td  class="dataTableContent" align=center><?php echo $currencies->format($specials['buy_count']>0?$specials['buy_total']/$specials['buy_count']:0); ?></td>
                <td  class="dataTableContent" align=right><?php echo '<a href="' . tep_href_link(FILENAME_SEARCH_STATISTICS, 'action=clearstat&swID=' . $specials['search_words_id']) . '" onclick="return confirm(\'' . TEXT_CONFIRM_CLEAR_STATISTICS . '\');">' . TEXT_CLEAR_STATISTICS . '</a>'; ?></td>
              </tr>
<?php
}
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_query_numrows, 50, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SE_STATISTICS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_query_numrows, 50, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'action'))); ?></td>
                  </tr>
                  <tr>
                </table></td>
              </tr>
              <?
                      $specials_query_raw = "select se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count, count(o.orders_id) as buy_count, sum(ot.value*o.currency_value) as buy_total from " . TABLE_SEARCH_WORDS ." sw left join " . TABLE_ORDERS . " o on  (se.search_engines_id=o.search_engines_id) right join " . TABLE_SEARCH_ENGINES . " se on (sw.search_words_id=o.search_words_id) left join " . TABLE_ORDERS_TOTAL ." ot on (o.orders_id=ot.orders_id and ot.class='ot_subtotal') where se.search_engines_id=sw.search_engines_id ";
                      if ($HTTP_GET_VARS['spwd']!=123) $specials_query_raw .= " and show_flag=1 ";
                      if ($HTTP_GET_VARS['seID']>0)
                      {
                        $specials_query_raw .= " and se.search_engines_id= '" . $HTTP_GET_VARS['seID'] . "' ";
                      }
                      $specials_query_raw .= "group by se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count order by buy_total desc, click_count desc, sw.word ";
                      $specials_query_clicks = tep_db_query($specials_query_raw);
                      $a = 0;
                      while ($specials_clicks = tep_db_fetch_array($specials_query_clicks)){
                        $a += $specials_clicks ['click_count'];
                        $b += $specials_clicks ['buy_count'];
                        $c += $specials_clicks ['buy_total'];
                      }

                      ?>
                  <tr>
                    <td class="dataTableContent">Total:</td><td>&nbsp;</td><td align=center class="dataTableContent"><?=$a?></td><td  align=center class="dataTableContent"><?=$b?></td><td  align=center class="dataTableContent"><?=$currencies->format($c)?></td>
                    </tr>
                  <!---->

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