<?php
/*
  $Id: stats_recover_cart_sales.php,v 1.6 2005/08/16 20:56:38 lane Exp $
  Recover Cart Sales Report v2.22

  Recover Cart Sales contribution: JM Ivler 11/20/03
  (c) Ivler / Ideas From the Deep / osCommerce
  
  Released under the GNU General Public License

 Modifed by Aalst (recover_cart_sales.php,v 1.2 .. 1.36)
 aalst@aalst.com

 Modified by Lane Roathe (recover_cart_sales.php,v 1.4d .. v2.22)
 lane@ifd.com  www.osc-modsquad.com / www.ifd.com
*/
  require('includes/application_top.php');

  if (RECOVER_CART_SALES_DISPLAY != 'True') {
    exit();
  }

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  function tep_date_order_stat($raw_date) {
    if ($raw_date == '') return false;
    $year = substr($raw_date, 2, 2);
    $month = (int)substr($raw_date, 4, 2);
    $day = (int)substr($raw_date, 6, 2);
    return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
  }

  function seadate($day) 
  {
    $ts = date("U");
    $rawtime = strtotime("-".$day." days", $ts);
    $ndate = date("Ymd", $rawtime);
    return $ndate;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_REPORTS;
  $header_title_menu_link= tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports');
  $header_title_submenu=HEADING_TITLE;

  $tdate = $_POST['tdate'];
  if ($tdate == '') 
    $tdate = RCS_REPORT_DAYS;
  $ndate = seadate($tdate); 
  $header_title_additional='<form method=post action='.$PHP_SELF.'><table align="right" width="100%"><tr class="dataTableContent" align="right"><td nowrap>'.DAYS_FIELD_PREFIX.'<input type=text size=4 width=4 value='.$tdate.' name=tdate>'.DAYS_FIELD_POSTFIX.'<input type=submit value="'.DAYS_FIELD_BUTTON.'"></td></tr></table></form>';
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
      </table>
    </td>
    <!-- body_text //-->
    <td width="100%" valign="top" height="100%">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
        </tr>
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  // Init vars
  $custknt = 0;
  $total_recovered = 0;
  $custlist = '';
  // Query database for abandoned carts within our timeframe
  $conquery = tep_db_query("select * from ". TABLE_SCART ." where dateadded >= '".$ndate."' order by dateadded DESC" );
  $rc_cnt = mysql_num_rows($conquery);
  // Loop though each one and process it
  for ($i = 0; $i < $rc_cnt; $i++)
  {
    $inrec = tep_db_fetch_array($conquery);
    $cid = $inrec['customers_id'];

    // we have to get the customer data in order to better locate matching orders
    $query1 = tep_db_query("select c.customers_firstname, c.customers_lastname from ".TABLE_CUSTOMERS." c where c.customers_id ='".$cid."'");
    $crec = tep_db_fetch_array($query1);

    // Query DB for the FIRST order that matches this customer ID and came after the abandoned cart
    $orders_query_raw = "select o.orders_id, o.customers_id, o.date_purchased, s.orders_status_name, ot.text as order_total, ot.value from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where (o.customers_id = " . (int)$cid . ' OR o.customers_email_address like "' . $crec['customers_email_address'] .'" OR o.customers_name like "' . $crec['customers_firstname'] . ' ' . $crec['customers_lastname'] . '") and o.orders_status > ' . RCS_PENDING_SALE_STATUS . ' and s.orders_status_id = o.orders_status and o.date_purchased >= "' . $inrec['dateadded'] . '" and ot.class = "ot_total"';
    $orders_query = tep_db_query($orders_query_raw);
    $orders = tep_db_fetch_array($orders_query);

    // If we got a match, create the table entry to display the information
    if( $orders )
    {
      $custknt++;
      $total_recovered += $orders['value'];
      $custknt % 2 ? $class = RCS_REPORT_EVEN_STYLE : $class = RCS_REPORT_ODD_STYLE;
      $custlist .= "<tr class=".$class.">".
          "<td class=datatablecontent align=right>".$inrec['scartid']."</td>".
          "<td>&nbsp;</td>".
          "<td class=datatablecontent align=center>".tep_date_order_stat($inrec['dateadded'])."</td>".
          "<td>&nbsp;</td>".
          "<td class=datatablecontent><a href='" . tep_href_link(FILENAME_CUSTOMERS, 'search=' . $crec['customers_lastname'], 'NONSSL') . "'>".$crec['customers_firstname']." ".$crec['customers_lastname']."</a></td>".
          "<td class=datatablecontent>".tep_date_short($orders['date_purchased'])."</td>".
          "<td class=datatablecontent align=center>".$orders['orders_status_name']."</td>".
          "<td class=datatablecontent align=right>".strip_tags($orders['order_total'])."</td>".
          "<td>&nbsp;</td>".
          "</tr>";
    }
  }
  $cline ="<tr><td height=\"15\" COLSPAN=8> </td></tr>".
              "<tr>".
                "<td align=right COLSPAN=3 class=main><b>". TOTAL_RECORDS ."</b></td>".
                "<td>&nbsp;</td>".
                "<td align=left COLSPAN=5 class=main>". $rc_cnt ."</td>".
              "</tr>".
              "<tr>".
                "<td align=right COLSPAN=3 class=main><b>". TOTAL_SALES ."</b></td>".
                "<td>&nbsp;</td>".
                "<td align=left COLSPAN=5 class=main>". $custknt . TOTAL_SALES_EXPLANATION ." </td>".
              "</tr>".
              "<tr><td height=\"12\" COLSPAN=6> </td></tr>";
  echo $cline;
?>
              <tr class="dataTableHeadingRow"><!-- Header -->
                <td width="7%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SCART_ID ?></td>
                <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
                <td width="10%" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SCART_DATE ?></td>
                <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
                <td width="50%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER ?></td>
                <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_DATE ?></td>
                <td width="10%" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER_STATUS ?></td>
                <td width="10%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_AMOUNT ?></td>
                <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
              </tr>
<?
   echo $custlist;  // BODY: <tr> sections with recovered cart data
?>
              <tr>
                <td colspan=9 valign="bottom"><hr width="100%" size="1" color="#800000" noshade></td>
              </tr>
              <tr class="main">
                <td align="right" valign="center" colspan=4 class="main"><b><?php echo TOTAL_RECOVERED ?>&nbsp;</b></font></td>
                <td align=left colspan=3 class="main"><b><?php echo $rc_cnt ? tep_round(($custknt / $rc_cnt) * 100, 2) : 0 ?>%</b></font></td>
                <td class="main" align="right"><b><?php echo $currencies->format(tep_round($total_recovered, 2)) ?></b></font></td>
                <td class="main">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
<!-- body_text_eof //-->
    </td>
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
