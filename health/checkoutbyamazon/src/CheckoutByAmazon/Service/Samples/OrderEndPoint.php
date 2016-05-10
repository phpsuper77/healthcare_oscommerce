<?

chdir("../../../../../");
include_once("includes/application_top.php");

ob_start();
print_r($HTTP_POST_VARS);
$out = ob_get_clean();
mail('muzz@alljammin.com', 'end point', $out);

ob_start();

$content =  implode("|", $HTTP_POST_VARS);
$content =  explode('|', $content);
$content = stripslashes($content[1]);

$notificationType = $HTTP_POST_VARS['NotificationType']; 

switch ($notificationType) {
	case "NewOrderNotification":
		$notificationType = "1";
		break;
	case "OrderCancelledNotification":
		$notificationType = "5";
		break;
	case "OrderReadyToShipNotification":
		$notificationType = "2";
		break;	
}


//$content = file_get_contents('e://temp//heathcare4all//ipn.xml');
//$content = stripslashes($content);

$amazon_order = json_decode(json_encode((array) simplexml_load_string($content)),1);
$amazon_order_id = $amazon_order['ProcessedOrder']['AmazonOrderID'];

/* Cancel Order Notification */
if ($notificationType == 5) {
	$sql = "UPDATE orders SET orders_status = 5 WHERE amazon_checkout_id = '$amazon_order_id'";
	tep_db_query($sql);
	die;
}

/* Order Ready to ship Notification */
if ($notificationType == 2) {
	$sql = "UPDATE orders SET orders_status = 2 WHERE amazon_checkout_id = '$amazon_order_id'";
	tep_db_query($sql);
	
	if ($notificationType == 2) {
		$sql = "UPDATE orders 
				SET 
					customers_telephone = '".$amazon_order['ProcessedOrder']['ShippingAddress']['PhoneNumber']."' ,
					shipping_class = '".$amazon_order['ProcessedOrder']['DisplayableShippingLabel']."'				
				WHERE amazon_checkout_id = '$amazon_order_id'";
		tep_db_query($sql);
	}	
	die;
}


$duplicate_order_sql = "SELECT COUNT(*) AS total_count FROM orders WHERE amazon_checkout_id = '$amazon_order_id'";
$duplicate = tep_db_fetch_array(tep_db_query($duplicate_order_sql));	
if ($duplicate['total_count'] > 0) {
	die;
}


$shipping_country_sql = "SELECT countries_name FROM countries WHERE countries_iso_code_2 LIKE '".$amazon_order['ProcessedOrder']['ShippingAddress']['CountryCode']."'";
$country = tep_db_fetch_array(tep_db_query($shipping_country_sql));	
$shipping_country =  $country['countries_name'];
						
$sql_data_array = array('amazon_checkout_id' => $amazon_order_id,
						'customers_id' => "-1",
						'customers_name' => $amazon_order['ProcessedOrder']['BuyerInfo']['BuyerName'],
                        //'customers_firstname' => $amazon_order['ProcessedOrder']['BuyerInfo']['BuyerName'],
						//'customers_lastname' => $amazon_order['ProcessedOrder']['BuyerInfo']['BuyerName'],                        
						'customers_street_address' => $amazon_order['ProcessedOrder']['ShippingAddress']['AddressFieldOne'],
						'customers_city' => $amazon_order['ProcessedOrder']['ShippingAddress']['City'],
						'customers_state' => $amazon_order['ProcessedOrder']['ShippingAddress']['State'],
						'customers_postcode' => $amazon_order['ProcessedOrder']['ShippingAddress']['PostalCode'],
						'customers_country' => $shipping_country,
						'customers_email_address' => $amazon_order['ProcessedOrder']['BuyerInfo']['BuyerEmailAddress'],						
						'delivery_name' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						//'delivery_firstname' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						//'delivery_lastname' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						'delivery_street_address' => $amazon_order['ProcessedOrder']['ShippingAddress']['AddressFieldOne'],
						'delivery_city' => $amazon_order['ProcessedOrder']['ShippingAddress']['City'],
						'delivery_state' => $amazon_order['ProcessedOrder']['ShippingAddress']['State'],
						'delivery_postcode' => $amazon_order['ProcessedOrder']['ShippingAddress']['PostalCode'],
						'delivery_country' => $shipping_country,						
						'billing_name' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						//'billing_firstname' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						//'billing_lastname' => $amazon_order['ProcessedOrder']['ShippingAddress']['Name'],
						'billing_street_address' => $amazon_order['ProcessedOrder']['ShippingAddress']['AddressFieldOne'],
						'billing_city' => $amazon_order['ProcessedOrder']['ShippingAddress']['City'],
						'billing_postcode' => $amazon_order['ProcessedOrder']['ShippingAddress']['PostalCode'],
						'billing_state' => $amazon_order['ProcessedOrder']['ShippingAddress']['State'],
						'billing_country' => $shipping_country,
						'payment_method' => 'Amazon',
						'date_purchased' => $amazon_order['ProcessedOrder']['OrderDate'],
						'last_modified' => $amazon_order['ProcessedOrder']['OrderDate'],
						'orders_status' => "1",
						'currency' => "GBP",
						'currency_value' => "1.0000",
						'customers_address_format_id' => '6',
						'delivery_address_format_id' => '6',
						'billing_address_format_id' => '6',						
						);
tep_db_perform("orders", $sql_data_array);
$order_id = tep_db_insert_id();


if ($amazon_order['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']['AmazonOrderItemCode'] == "") {
	$array = $amazon_order['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'];
}
else {
	$array = $amazon_order['ProcessedOrder']['ProcessedOrderItems'];
}
	
foreach($array as $key=>$item) {
	$sql = "SELECT products_id FROM products WHERE products_model LIKE '".$item['SKU']."'";		
    $product = tep_db_fetch_array(tep_db_query($sql));	
	if ($product['products_id']) {
		$product_id = $product['products_id'];
	} else {
		$product_id = "-1";
	}
	$product_data_array = array(	'orders_id' => $order_id,
									'products_id' => $product_id,
									'products_name' => $item['Title'],
									'products_model' => $item['SKU'],
									'products_price' => $item['Price']['Amount'],
									'final_price' => $item['Price']['Amount'],
									'products_tax' => '0',
									'products_quantity' => $item['Quantity']
									
					  );
	tep_db_perform("orders_products", $product_data_array);
	
	$subtotal 		+= $item['ItemCharges']['Component'][0]['Charge']['Amount'];
	$shipping 	+= $item['ItemCharges']['Component'][1]['Charge']['Amount'];
	$promo 		+= $item['ItemCharges']['Component'][2]['Charge']['Amount'];	
	$shipping_method = $item['ItemCustomData'];
}


/* Add shippingg total */
$ordershipping_data_array = array(	
								'orders_id' => $order_id, 
								'title' => $shipping_method,
								'text' => $shipping.' GBP',	'value' => $shipping,
								'class' => 'ot_shipping','sort_order' => '1');
tep_db_perform("orders_total", $ordershipping_data_array);								
				  
/* Add Sub Total */
$ordersubtotal_data_array = array(	'orders_id' => $order_id, 'title' => 'Sub-Total',
								'text' => $subtotal.' GBP',	'value' => $subtotal,
								'class' => 'ot_subtotal','sort_order' => '2');
tep_db_perform("orders_total", $ordersubtotal_data_array);


/* Add Promo Total */
$ordersubtotal_data_array = array(	'orders_id' => $order_id, 'title' => 'Discount Coupon',
									'text' => $promo.' GBP',	'value' => $promo,
									'class' => 'ot_coupon','sort_order' => '3');
tep_db_perform("orders_total", $ordersubtotal_data_array);


$total = ($subtotal + $shipping) - $promo;
$ordertotal_data_array = array(	'orders_id' => $order_id,
								'title' => 'Total',
								'text' => $total.' GBP',
								'value' => $total,
								'class' => 'ot_total',
								'sort_order' => '4'
				  );

tep_db_perform("orders_total", $ordertotal_data_array);
$out = ob_get_clean();
mail('muzz@alljammin.com', 'end point', $out);

//ot_coupon
?>
