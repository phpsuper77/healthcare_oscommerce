<?php
require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

function tep_fputcsv($handler, $data) {
    $str = '';
    foreach ($data as $cell) {
        $str .= '"' . str_replace("\r", '', str_replace("\n", ' ', str_replace('"', '""', $cell))) . '",';
    }
    $str = substr($str, 0, -1);
    fputs($handler, $str . "\n");
}

function check_file($fname) {
    if (!is_file($fname))
        return return_msg(ERROR_FILE_NONEXISTENT);
    if (!is_writable($fname))
        return return_msg(ERROR_FILE_NOTWRITABLE);
    return return_msg(ERROR_FILE_OK, 1);
}

function return_msg($msg, $type=2) {
    if ($type == 2) {
        return '<span style="FONT-SIZE: 10px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #ff0000;">' . $msg . '</span>';
    } elseif ($type == 1) {
        return '<span style="FONT-SIZE: 10px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #0000ff;">' . $msg . '</span>';
    } else {
        return $msg;
    }
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_TO_CSV);

$csv_file = 'tradebox.csv';
if (tep_not_null($action)) {
    switch ($action) {
        case 'doexport':
            tep_set_time_limit(0);
        $date_sql = '';
        if (tep_not_null($HTTP_POST_VARS['datefrom'])) {
          $datefrom = $HTTP_POST_VARS['datefrom'] = tep_calendar_rawdate($HTTP_POST_VARS['datefrom']);
          $date_sql .= " and o.date_purchased>'". tep_db_prepare_input($HTTP_POST_VARS['datefrom']) . " 00:00:00'";
        }
        if (tep_not_null($HTTP_POST_VARS['dateto'])) {
          $dateto = $HTTP_POST_VARS['dateto'] = tep_calendar_rawdate($HTTP_POST_VARS['dateto']);
          $date_sql .= " and o.date_purchased<'". tep_db_prepare_input($HTTP_POST_VARS['dateto']) . " 23:59:59'";
        }
        if (!tep_not_null($HTTP_POST_VARS['tradebox_exported'])) {
          $date_sql .= " and o.tradebox_exported=0";
        }



//set column value

            $colum_name[] = 'Order Date';
            $colum_name[] = 'Order ID';
            $colum_name[] = 'Item ID';
            $colum_name[] = 'SKU';
            $colum_name[] = 'Item Description';
            $colum_name[] = 'Item Price';
            $colum_name[] = 'Quantity';
            $colum_name[] = 'Carriage Amount';
            $colum_name[] = 'Other Amount';
            $colum_name[] = 'Customer Name';
            $colum_name[] = 'Shipping Address 1';
            $colum_name[] = 'Shipping Address 2';
            $colum_name[] = 'Shipping Address 3';
            $colum_name[] = 'Shipping Address 4';
            $colum_name[] = 'Shipping Address 5';
            $colum_name[] = 'Shipping Country';
            $colum_name[] = 'Shipping Country Code';
            $colum_name[] = 'Customer Email';
            $colum_name[] = 'Customer Telephone';
            $colum_name[] = 'Billing Name';
            $colum_name[] = 'Billing Address 1';
            $colum_name[] = 'Billing Address 2';
            $colum_name[] = 'Billing Address 3';
            $colum_name[] = 'Billing Address 4';
            $colum_name[] = 'Billing Address 5';
            $colum_name[] = 'Billing Country';
            $colum_name[] = 'Billing Country Code';
            $colum_name[] = 'Message';
            $colum_name[] = 'Payment Method';
            $colum_name[] = 'Shipping Method';
            $colum_name[] = 'Order Status';
            $colum_name[] = 'Origin';

// end column
            $fp = fopen(DIR_FS_CATALOG . 'temp/' . $csv_file, 'w');

            $sql = "SELECT o.date_purchased, o.orders_id, op.products_id, op.products_model, op.products_name, op.final_price, op.products_tax, op.products_quantity, o.customers_name, o.delivery_street_address, o.delivery_suburb, o.delivery_city, o.delivery_state, o.delivery_postcode, o.delivery_country, c.countries_iso_code_2, o.customers_email_address, o.customers_telephone, o.billing_name, o.billing_street_address, o.billing_suburb, o.billing_city, o.billing_state, o.billing_postcode, o.billing_country, c.countries_iso_code_2, o.payment_method, o.shipping_method   FROM " . TABLE_ORDERS . " as o left join " . TABLE_ORDERS_PRODUCTS . " as op on o.orders_id=op.orders_id  left join " . TABLE_COUNTRIES . " as c on o.delivery_country=c.countries_name  WHERE o.language_id ='" . (int) $languages_id . "' and c.language_id = o.language_id " . $date_sql . " order by o.orders_id";

            $query = tep_db_query($sql);
            $tmp = 0;
            $orders_exported = array();
            while ($expdata = tep_db_fetch_array($query)) {
                if ($tmp == 0) {
                  tep_fputcsv($fp, $colum_name);
                }			
				
                $result_array = array();
                $sipping_method = array();
                $order_status = array();


                /**/
                $order_status = tep_db_fetch_array(tep_db_query("SELECT os.orders_status_name as status from " . TABLE_ORDERS_STATUS_HISTORY . " as osh left join " . TABLE_ORDERS_STATUS . " as os on osh.orders_status_id=os.orders_status_id where osh.orders_id='" . (int) $expdata['orders_id'] . "' and os.language_id ='" . (int) $languages_id . "' order by osh.date_added DESC limit 1"));
                $sipping_method = tep_db_fetch_array(tep_db_query("SELECT title, value from " . TABLE_ORDERS_TOTAL . " where class='ot_shipping' and orders_id ='" . (int) $expdata['orders_id'] . "'"));
                $orders_exported[] = $expdata['orders_id'];


                $result_array['date_purchased'] = date('d/m/Y', strtotime($expdata['date_purchased']));
                $result_array['orders_id'] = $expdata['orders_id'];
                $result_array['products_id'] = $expdata['products_id'];

                $result_array['products_model'] = $expdata['products_model'];
                $result_array['products_name'] = $expdata['products_name'];
                $result_array['products_price'] = tep_add_tax($expdata['final_price'], $expdata['products_tax']);
                $result_array['products_quantity'] = $expdata['products_quantity'];
                $result_array['carriage_amount'] = ($tmp==$expdata['orders_id']?0:$sipping_method['value']+0);
                $result_array['other_amount'] = 0;

                $result_array['customers_name'] = $expdata['customers_name'];
                $result_array['delivery_street_address'] = $expdata['delivery_street_address'];
                $result_array['delivery_suburb'] = $expdata['delivery_suburb'];
                $result_array['delivery_city'] = $expdata['delivery_city'];
                $result_array['delivery_state'] = $expdata['delivery_state'];
                $result_array['delivery_postcode'] = $expdata['delivery_postcode'];
                $result_array['delivery_country'] = $expdata['delivery_country'];
                $result_array['countries_iso_code_2'] = $expdata['countries_iso_code_2'];
                $result_array['customers_email_address'] = $expdata['customers_email_address'];
                $result_array['customers_telephone'] = $expdata['customers_telephone'];
                $result_array['billing_name'] = $expdata['billing_name'];
                $result_array['billing_street_address'] = $expdata['billing_street_address'];
                $result_array['billing_suburb'] = $expdata['billing_suburb'];
                $result_array['billing_city'] = $expdata['billing_city'];
                $result_array['billing_state'] = $expdata['billing_state'];
                $result_array['billing_postcode'] = $expdata['billing_postcode'];
                $result_array['billing_country'] = $expdata['billing_country'];
                $result_array['billing_country_code'] = $result_array['countries_iso_code_2'];
                $result_array['message'] = '';
                $result_array['payment_method'] = $expdata['payment_method'];
                if ($sipping_method['title'] == 'Shipping:') {
                    $sipping_method['title'] = '';
                } else {
                    $sipping_method['title'] = str_replace(':', '', $sipping_method['title']);
                }
                $result_array['shipping_method'] = $sipping_method['title'];

                $result_array['status'] = $order_status['status'];
                $result_array['origin'] = 'Website';


                tep_fputcsv($fp, $result_array);
				$tmp = $expdata['orders_id'];
            }
            fclose($fp);
            if (count($orders_exported)>0){
              tep_db_query("update " . TABLE_ORDERS . " set tradebox_exported=1 where orders_id in (" . implode(',', $orders_exported) . ")");
            }
            $messageStack->add(FILE_UPDATED, 'success');
            break;
        case 'download':
            header('Cache-Control: none');
            header('Pragma: none');
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $csv_file);
            readfile(DIR_FS_CATALOG . 'temp/' . $csv_file);
            exit();
            break;
    }
}
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
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
        <!-- header //-->
<?php
 echo tep_init_calendar();


$header_title_menu = BOX_HEADING_CATALOG;
$header_title_menu_link = tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
$header_title_submenu = HEADING_TITLE;
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
                <?php
    if ( isset($HTTP_POST_VARS['datefrom']) ) {
      $datefrom = $HTTP_POST_VARS['datefrom'];
    }else{
     $datefrom = date("Ymd",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
    }
    if ( isset($HTTP_POST_VARS['dateto']) ) {
      $dateto = $HTTP_POST_VARS['dateto'];
    }else{
        $dateto = date("Ymd",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
    }
?>
                 <?php echo tep_draw_form('export_form', FILENAME_ORDERS_TO_CSV, '') . tep_draw_hidden_field('action', 'doexport'); ?>
                <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
            </tr>
        </table></td>
    <!-- body_text //-->
<td width="100%" valign="top"  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
        <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><img src="images/spacer.gif" width="1" height="20" alt="" border="0"></td>
        </tr>
        <tr>
            <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
                    <tr>
                        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td colspan=6><?= tep_draw_separator('pixel_trans.gif', 1, 10) ?></td>
                                </tr>


                                <tr>
                                    <td class="pageHeading" colspan=6><?php echo HEADING_TITLE_TRADEBOX; ?></td>
                                </tr>
                                <tr>
                                    <td colspan=6><?= tep_draw_separator('pixel_trans.gif', 1, 10) ?></td>
                                </tr>
                                <tr class="dataTableHeadingRow">
                                    
                                    
                                   <!-- <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_TITLE; ?></td> -->
                                    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
                                    <td class="dataTableHeadingContent" align="left"><?php echo TEXT_FILTER_DATE_FROM;?></td>
                                    <td class="dataTableHeadingContent" align="left"><?php echo TEXT_FILTER_DATE_TO;?></td>
                                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_INC_EXPORTED; ?></td>
                                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_WARNING; ?></td>
                                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
                                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                                </tr>

                                <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                                      
                                    <!--<td class="dataTableContent" align="left"><?php echo FILE_ORDERS_TITLE; ?></td>-->
                                    <td class="dataTableContent" align="left"><?php echo $csv_file ?></td>
                                    
                                    <td class="dataTableContent" align="left"><?php echo tep_draw_calendar('export_form','datefrom',$datefrom) ;?></td>
                                    <td class="dataTableContent" align="left"><?php echo tep_draw_calendar('export_form','dateto',$dateto);?></td>
                                    <td class="dataTableContent" align="center"><?php echo tep_draw_checkbox_field('tradebox_exported','1');?></td>
                                  
                                    <td class="dataTableContent" align="center"><?php echo check_file(DIR_FS_CATALOG . 'temp/' . $csv_file) ?></td>
                                    <td class="dataTableContent" align="center"><?php if (file_exists(DIR_FS_CATALOG . 'temp/' . $csv_file))
    echo strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CATALOG . 'temp/' . $csv_file)); ?></td>
                                    <td class="dataTableContent" align="right"><?php if (file_exists(DIR_FS_CATALOG . 'temp/' . $csv_file))
    echo round((filesize(DIR_FS_CATALOG . 'temp/' . $csv_file) / 1024), 2) . 'kb'; ?></td>
                                    <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_TO_CSV, 'action=download') . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;'; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif', 1, 10) ?></td>
                                </tr>
                                <tr>
                                <td colspan="7" class="smallText" align="right"><?php echo tep_image_submit('button_update.gif')?></td>    
                                </tr>

                                <tr>
                                    <td colspan="7"><?php echo tep_draw_separator('pixel_trans.gif', 1, 10) ?></td>
                                </tr>


                            </table></td>
<?php
$heading = array();
$contents = array();

if ((tep_not_null($heading)) && (tep_not_null($contents))) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
}
?>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
