<?

require('includes/application_top.php');

// get survey email text
$get_email_q = tep_db_query("select subject, message from " . TABLE_SALES_SURVAVE);
if (tep_db_num_rows($get_email_q) > 0) {
	$get_email = tep_db_fetch_array($get_email_q);

	$check_date = date('Y-m-d 00:00:00', mktime(0, 0, 0, date("m"), date("d") - SALES_SURVAVE_DAYS, date("Y")));

	//recepients
	$get_orders_q_raw = "select distinct o.orders_id, customers_name, customers_email_address, o.date_purchased from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_STATUS_HISTORY . " osh on o.orders_id=osh.orders_id where (TO_DAYS(osh.date_added) <= TO_DAYS('" . $check_date . "') and (survave_sended IS NULL or survave_sended='0000-00-00 00:00:00'))  and request_salessurvave='1' and osh.orders_status_id in (5, 3, 102, 100000) order by o.orders_id";

	$get_orders_q = tep_db_query($get_orders_q_raw);

	if (tep_db_num_rows($get_orders_q) > 0) {
		while ($get_orders = tep_db_fetch_array($get_orders_q)) {
			// ordered products - only active
			$r = tep_db_query("select op.products_name, p.products_id  from " . TABLE_PRODUCTS . " p, " . TABLE_ORDERS_PRODUCTS . " op where p.products_id=op.products_id and p.products_status>0 and op.orders_id='" . $get_orders['orders_id'] . "'");
			if (tep_db_num_rows($get_orders_q) > 0) {
				$products         = array();
				$products_reviews = array();
				while ($pd = tep_db_fetch_array($r)) {
					$products[]         = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pd['products_id']) . '">' . $pd['products_name'] . '</a>';
					$tmp_url            = tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . $pd['products_id']);
					$products_reviews[] = '<a href="' . $tmp_url . '" style="color:#6ea23c; text-decoration:none;"><span style="color:#6ea23c; text-decoration:none;">' . $tmp_url . '</span></a>';
				}
				$products_str         = implode('<br>' . "\n", $products);
				$products_reviews_str = implode('<br>' . "\n", $products_reviews);
				$replacer_array       = array(
					'CUSTOMER_NAME' => $get_orders['customers_name'],
					'CUSTOMER_EMAIL' => $get_orders['customers_email_address'],
					'ORDER_ID' => $get_orders['orders_id'],
					'PRODUCT_LIST' => $products_str,
					'PRODUCT_REVIEWS' => $products_reviews_str,
					'DATE_PURCHASED' => $get_orders['date_purchased']
				);
				$subject              = $get_email['subject'];
				$body                 = $get_email['message'];
				foreach ($replacer_array as $replace_key => $replace_value) {
					$subject = str_replace('%%' . $replace_key . '%%', $replace_value, $subject);
					$body    = str_replace('%%' . $replace_key . '%%', $replace_value, $body);
				}
				tep_mail($get_orders['customers_name'], $get_orders['customers_email_address'], $subject, $body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			}
			tep_db_query("update " . TABLE_ORDERS . " set survave_sended=now(), request_salessurvave='0' where orders_id='" . $get_orders['orders_id'] . "'");
		}
	}
}
?>
