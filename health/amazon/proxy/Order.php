<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

// https://images-na.ssl-images-amazon.com/images/G/01/rainier/help/xsd/release_1_9/OrderReport.xsd
// https://sellercentral-europe.amazon.com/gp/help/external/help.html?ie=UTF8&itemID=1271&language=en%5FUS

class ProxyOrderReport extends AmazonOrderReport {

  function ProxyOrderReport(){
    parent::AmazonOrderReport();
  }

  function xmlAddress( $xmlArray ){
    $name = (isset($xmlArray['Name']['VALUE'])?$xmlArray['Name']['VALUE']:'');
    $addr1 = (isset($xmlArray['AddressFieldOne']['VALUE'])?$xmlArray['AddressFieldOne']['VALUE']:'');
    $addr2 = (isset($xmlArray['AddressFieldTwo']['VALUE'])?$xmlArray['AddressFieldTwo']['VALUE']:'');
    $addr3 = (isset($xmlArray['AddressFieldThree']['VALUE'])?$xmlArray['AddressFieldThree']['VALUE']:'');
    $city = (isset($xmlArray['City']['VALUE'])?$xmlArray['City']['VALUE']:'');
    $county = (isset($xmlArray['County']['VALUE'])?$xmlArray['County']['VALUE']:'');
    $state = (isset($xmlArray['StateOrRegion']['VALUE'])?$xmlArray['StateOrRegion']['VALUE']:'');
    $zip = (isset($xmlArray['PostalCode']['VALUE'])?$xmlArray['PostalCode']['VALUE']:'');
    $CountryCode = (isset($xmlArray['CountryCode']['VALUE'])?$xmlArray['CountryCode']['VALUE']:'');
    $PhoneNumber = (isset($xmlArray['PhoneNumber']['VALUE'])?$xmlArray['PhoneNumber']['VALUE']:'');
    $CountryInfo = amazon_country_info($CountryCode);
    $StateInfo = amazon_state_info($state, $CountryInfo['id']);
    $state = $StateInfo['zone_name'];
    $zone_id = $StateInfo['zone_id'];
    // code from old import
    $customers_street_address = $addr1 . ((!empty($addr2) || !empty($addr3))?', ' . $addr2 . ' ' . $addr3:'');
    $customers_suburb = ''; // blank

    if ( ACCOUNT_SUBURB == 'true' && strlen($customers_street_address)>30 ) {
      $customers_street_address_ = substr($customers_street_address,0,30);
      if (substr($customers_street_address,30,1) != ',' || substr($customers_street_address,30,1) != ' ') {
        $customers_street_address_ = preg_replace('/([, ])[^, ]*$/','\1',$customers_street_address_);
      }
      $customers_suburb = substr($customers_street_address,strlen($customers_street_address_));
      $customers_street_address = $customers_street_address_;
    }
    list($fname,$lname) = explode(' ', $name, 2);
    if ( in_array( strtolower($fname), array('mr', 'mrs', 'miss', 'mr & mrs', 'mr.', 'mrs.', 'miss.' ) ) ){
      list($_fname,$lname) = explode(' ', $lname, 2);
      $fname .= ' '.$_fname;
    }
    
    return array(
                 'name' => $name,
                 'first_name' => $fname,
                 'last_name' => $lname,
                 'street_address' => $customers_street_address,
                 'suburb' => $customers_suburb, 
                 'city' => $city,
                 'postcode' => $zip,
                 'country' => $CountryInfo['name'],
                 'country_id' => $CountryInfo['id'],
                 'state' => $state,
                 'zone_id' => $zone_id, 
                 'address_format_id' => $CountryInfo['address_format_id'],
                 '_clean_' => !isset($CountryInfo['not_found']),
                 '_country_' => isset($CountryInfo['not_found'])?$CountryInfo['not_found']:'',
                 '_phone' => $PhoneNumber
                );
  }
  
  function transShipping( $FulfillmentServiceLevel='', $order_total=0 ){
    return array( 'shipping_title' => 'Shipping ('.$FulfillmentServiceLevel.')',
                  'shipping_class' => 'flat_flat');
  }

  // -- insert order routine - parse amazon xml data & put order info into tables
  function insertOrder( $xmlArray ){
    global $currencies;

    $xmlArray = $xmlArray['OrderReport'];

    if ( !isset($xmlArray['AmazonOrderID']['VALUE']) ) return AFWS_ORDER_IMPORT_FAIL;
    $AmazonOrderID = $xmlArray['AmazonOrderID']['VALUE'];
    
    if ( ProxyOrderReport::exists( $AmazonOrderID ) ) {
      // order already in system, mark them as Success & skip
      $this->ackList[] = array('AmazonOrderID'=>$AmazonOrderID /*, 'MerchantOrderID'=>$inserted_order_id*/ );
      return AFWS_ORDER_IMPORT_DOUBLE;
    }
    
    $OrderDate = axsd::_dateTime( $xmlArray['OrderDate']['VALUE'] );
    $OrderPostedDate = axsd::_dateTime( $xmlArray['OrderPostedDate']['VALUE'] );
    $TransactionDate = axsd::_dateTime( $xmlArray['TransactionDate']['VALUE'] );
    $AmazonShipping = $xmlArray['FulfillmentData']['FulfillmentServiceLevel']['VALUE'];
    $AmazonShippingMethod = $xmlArray['FulfillmentData']['FulfillmentMethod']['VALUE'];

    $customers_email_address = $xmlArray['BillingData']['BuyerEmailAddress']['VALUE'];
    $customers_name = $xmlArray['BillingData']['BuyerName']['VALUE'];
    $customers_phone = $xmlArray['BillingData']['BuyerPhoneNumber']['VALUE'];
    list( $customers_firstname, $customers_lastname ) = explode(' ', $customers_name, 2);
    
    $billing = ProxyOrderReport::xmlAddress($xmlArray['BillingData']['Address']);
    
    $ship_info = ProxyOrderReport::transShipping( $AmazonShipping );
    $deilvery = ProxyOrderReport::xmlAddress($xmlArray['FulfillmentData']['Address']);
    // fill empty billing data
    if ( empty($billing['street_address']) && empty($billing['city']) ) {
      /*
      BuyerEmailAddress
      BuyerName
      BuyerPhoneNumber
      present in xml and fillen into customer fields
      */
      $billing = $deilvery; 
    }

    $writer = new RecordSetProxy();
    if ( AmazonConfig::orderImportCustomerId()=='create' ) {
      $reader = new RecordSetProxy();
      $reader->query("SELECT customers_id ".
                     "FROM ".TABLE_CUSTOMERS." ".
                     "WHERE customers_email_address='".$reader->esc($customers_email_address)."'");
      if ( $reader->count()>0 ) {
        $test_exists = $reader->next();
        $customers_id = $test_exists['customers_id']; 
      }else{
        // insert customer
        $sql_data_array = array('customers_firstname' => tep_db_prepare_input($customers_firstname),
                                'customers_lastname' => tep_db_prepare_input($customers_lastname),
                                'customers_email_address' => tep_db_prepare_input($customers_email_address),
                                'customers_telephone' => $customers_phone,
                                'customers_default_address_id' => '');
        $customers_id = $writer->insert(TABLE_CUSTOMERS, $sql_data_array);
  
        // insert customer address
        $sql_data_array = array('customers_id' => tep_db_prepare_input($customers_id),
                                'entry_firstname' => $deilvery['first_name'],
                                'entry_lastname' => $deilvery['last_name'],
                                'entry_street_address' => $deilvery['street_address'],
                                'entry_suburb' => $deilvery['suburb'],
                                'entry_postcode' => $deilvery['postcode'],
                                'entry_state' => $deilvery['state'],
                                'entry_zone_id' => $deilvery['zone_id'],
                                'entry_city' => $deilvery['city'],
                                'entry_country_id' => $deilvery['country_id']);
        $customers_address_id = $writer->insert(TABLE_ADDRESS_BOOK, $sql_data_array);
        unset($sql_data_array);
    
          // update customer (add default address id)
        $writer->query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$customers_address_id . "' where customers_id = '" . (int)$customers_id . "'");
        $writer->query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customers_id . "', '0', now())");
      }
    } else {
      $customers_id = AmazonConfig::orderImportCustomerId();
    }
    // insert order
    $sql_data_array = array('customers_id' => tep_db_prepare_input($customers_id),
                            'customers_name' => $customers_name,
                            'customers_street_address' => $billing['street_address'],
                            'customers_suburb' => $billing['suburb'],
                            'customers_city' => $billing['city'],
                            'customers_postcode' => $billing['postcode'],
                            'customers_state' => $billing['state'],
                            'customers_country' => $billing['country'],
                            'customers_email_address' => $customers_email_address,
                            'customers_telephone' => $customers_phone,
                            'customers_address_format_id' => $billing['address_format_id'],
                            'delivery_name' => $deilvery['name'],
                            'delivery_street_address' => $deilvery['street_address'],
                            'delivery_suburb' => $deilvery['suburb'],
                            'delivery_city' => $deilvery['city'],
                            'delivery_postcode' => $deilvery['postcode'],
                            'delivery_state' => $deilvery['state'],
                            'delivery_country' => $deilvery['country'],
                            'delivery_address_format_id' => $deilvery['address_format_id'],
                            'billing_name' => $billing['name'],
                            'billing_street_address' => $billing['street_address'],
                            'billing_suburb' => $billing['suburb'],
                            'billing_city' => $billing['city'],
                            'billing_postcode' => $billing['postcode'],
                            'billing_state' => $billing['state'],
                            'billing_country' => $billing['country'],
                            'billing_address_format_id' => $billing['address_format_id'],

                            'payment_class' => AmazonConfig::getPaymentClass(),
                            'payment_method' => AmazonConfig::getPaymentMethod(),
                            'shipping_method' => $ship_info['shipping_title'],
                            'shipping_class' => $ship_info['shipping_class'],

                            'language_id'=> '1',
                            'cc_type' => '',
                            'cc_owner' => '',
                            'cc_number' => '',
                            'cc_expires' => '',
                            'date_purchased' => $OrderDate,
                            'orders_status' => '0',
                            'currency' => AmazonConfig::getAmazonCurrency(),
                            'currency_value' => 1);
    $inserted_order_id = $writer->insert(TABLE_ORDERS, $sql_data_array);
    unset($sql_data_array);

    $order_subtotal = 0;
    $order_shipping = 0;
    $order_gift_wrap = 0;
    $order_tax = 0;
    $order_total = 0;
    if ( !isset($xmlArray['Item'][0]) ) $xmlArray['Item'] = array($xmlArray['Item']);
    $_order_item_id = $xmlArray['Item'][0]['AmazonOrderItemCode']['VALUE'];
    
    $orders_comments = '';
    $orders_comments_a = '';
    if ( $billing['_clean_']==false || $deilvery['_clean_']==false ) {
      $_countries_codes = '';
      if ( !empty($billing['_country_']) ) $_countries_codes = $billing['_country_'];
      if ( !empty($deilvery['_country_']) ) $_countries_codes .= (!empty($_countries_codes)?',':'').$deilvery['_country_']; 
      $orders_comments_a .= sprintf("Amazon country ISO=%s NOT FOUND in shop\n",$_countries_codes); 
    }

    $orders_comments_a .= 'Amazon Order ID: ' . $AmazonOrderID . "\n" . 
                       'Amazon Payments Date: ' . $OrderPostedDate . "\n" . 
                       'Amazon Listing ID: ' . $_order_item_id . "\n" . 
                       'Amazon Purchase Date: ' . $OrderDate . "\n";
    $orders_comments_giftwrap = '';
    foreach( $xmlArray['Item'] as $amazonItem ) {
      $proxyProduct = new proxyProduct();
      $uprid = $proxyProduct->SkuToId( $amazonItem['SKU']['VALUE'] );
      if ($uprid===false) $uprid=0;
      $products_id = (int)$uprid;

      $item_price = 0;
      $item_ship_price = 0;
      $item_gift_wrap = 0;

      if ( is_array( $amazonItem['ItemPrice']['Component'] ) ) {
        foreach( $amazonItem['ItemPrice']['Component'] as $Component ) {
          switch( $Component['Type']['VALUE'] ) {
            case 'Principal':
              $item_price += $Component['Amount']['VALUE'];
              break;
            case 'Shipping':
              $item_ship_price += $Component['Amount']['VALUE'];
              break;
            case 'GiftWrap':
              $item_gift_wrap += $Component['Amount']['VALUE'];
              break;
            case 'Tax':
            case 'ShippingTax':
            case 'RestockingFee':
            case 'RestockingFeeTax':
            case 'GiftWrapTax':
            case 'Surcharge':
            case 'ReturnShipping':
            case 'Goodwill':
            case 'ExportCharge':
            case 'Other':
             ;
          }
        }
      }

      // insert order_products
      $products_tax = AmazonConfig::getTaxRate();
      $order_subtotal += $item_price;
      $order_shipping += $item_ship_price;
      $order_total += ($item_price + $item_ship_price + $item_gift_wrap);
      $order_gift_wrap += $item_gift_wrap;
      if ( floatval($products_tax)>0 ) {
        $order_tax = number_format($order_total - (($order_total*100)/(100+$products_tax)), 5, '.','');
      }

      $qty = $amazonItem['Quantity']['VALUE'];
      $shop_product = ordered_product_info( $uprid, $amazonItem['SKU']['VALUE'], $amazonItem['Title']['VALUE'] );
      $sql_data_array = array('orders_id' => $inserted_order_id,
                              'products_id' => (int)$products_id,
                              'products_model' => $shop_product['products_model'],
                              'products_name' => $shop_product['products_name'],
                              'products_price' => ($item_price*100/$qty/(100+$products_tax)),
                              'uprid' => $uprid,
                              'final_price' => ($item_price*100/$qty/(100+$products_tax)),
                              'products_tax' => $products_tax,
                              'products_quantity' => $qty);
      if ( AFWS_ORDER_IMPORT_PROCESS_STOCK=='true' ) {
        update_stock($sql_data_array['uprid'], 0, $sql_data_array['products_quantity']);
      }

      $inserted_orders_products_id = $writer->insert(TABLE_ORDERS_PRODUCTS, $sql_data_array);

      if ( tep_not_null($amazonItem['DeliveryInstructions']['VALUE']) ) {
        $orders_comments .= /*$sql_data_array['products_model'].' : '.*/ $amazonItem['DeliveryInstructions']['VALUE']."\n";
      }
      if ( tep_not_null($amazonItem['GiftMessageText']['VALUE']) ) {
        $orders_comments_giftwrap .= $sql_data_array['products_model'].' : '. $amazonItem['GiftMessageText']['VALUE']."\n";
      }
      
      unset($sql_data_array);
      // insert order_products_attributes
      $attributes_array = $shop_product['attributes'];
      if ( is_array($attributes_array) ) for ($i=0;$i<sizeof($attributes_array);$i++) {
        $sql_data_array = $attributes_array[$i];
        $sql_data_array['orders_id'] = $inserted_order_id;
        $sql_data_array['orders_products_id'] = $inserted_orders_products_id;
        $writer->insert(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
        unset($sql_data_array);
      }
      unset($i);
    }
    if ( !empty($orders_comments_giftwrap) ) {
      //$orders_comments .= "\nGift Wrap Text: ".$orders_comments_giftwrap;
    }
    $ship_info = ProxyOrderReport::transShipping( $xmlArray['FulfillmentData']['FulfillmentServiceLevel']['VALUE'], $order_total );

    $order_totals = array();
    $order_totals[] = array('title' => 'Sub-Total:',
                            'text' => $currencies->format($order_subtotal),
                            'value' => ($order_subtotal),
                            'class' => 'ot_subtotal',
                            'sort_order' => '1');
    $order_totals[] = array('title' => $ship_info['shipping_title'] . ':',
                            'text' => $currencies->format($order_shipping),
                            'value' => $order_shipping,
                            'class' => 'ot_shipping',
                            'sort_order' => '2'); 
    if ( $order_gift_wrap>0 ) {
      $order_totals[] = array('title' => 'Gift Wrap:',
                              'text' => $currencies->format($order_gift_wrap),
                              'value' => $order_gift_wrap,
                              'class' => 'ot_giftwrap',
                              'sort_order' => '770');
    }
    if ( floatval($order_tax)>0 ) {
      $order_totals[] = array('title' => 'VAT:',
                              'text' => $currencies->format($order_tax),
                              'value' => $order_tax,
                              'class' => 'ot_tax',
                              'sort_order' => '3');
    }
                           /*
                           array('title' => '',
                                 'text' => '',
                                 'value' => '',
                                 'class' => 'ot_discount',
                                 'sort_order' => '5'),
                           */
    $order_totals[] = array('title' => 'Total:',
                            'text' => '<b>' . $currencies->format($order_total) . '</b>',
                            'value' => $order_total,
                            'class' => 'ot_total',
                            'sort_order' => '10');
    
    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $sql_data_array = array('orders_id' => $inserted_order_id,
                              'title' => $order_totals[$i]['title'],
                              'text' => $order_totals[$i]['text'],
                              'value' => $order_totals[$i]['value'],
                              'class' => $order_totals[$i]['class'],
                              'sort_order' => $order_totals[$i]['sort_order']);
      $writer->insert(TABLE_ORDERS_TOTAL, $sql_data_array);
      unset($sql_data_array);
    }
    // insert order_status_history (comments)
    $sql_data_array = array('orders_id' => $inserted_order_id,
                            'orders_status_id' => AmazonConfig::getInsertOrderStatus(),
                            'date_added' => 'now()',
                            'customer_notified' => '0',
                            'comments' => $orders_comments);
    $writer->insert(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    unset($sql_data_array);
    
    $writer->query("update ".TABLE_ORDERS." set orders_status='".AmazonConfig::getInsertOrderStatus()."' where orders_id='".$inserted_order_id."'");

    $writer->insert(
      TABLE_AMAZON_ORDERS,
      array(
        'orders_id' => $inserted_order_id,
        'amazon_id' => $AmazonOrderID,
        'amazon_info' =>$orders_comments_a,
      )
    );

    $this->ackList[] = array('AmazonOrderID'=>$AmazonOrderID , 'MerchantOrderID'=>$inserted_order_id );
    
    return AFWS_ORDER_IMPORT_OK;
  }
  
  function exists( $AmazonOrderID ){
    $check = new RecordSetProxy();
    $count = $check->fetchOne("SELECT COUNT(*) AS c ".
                              "FROM ".TABLE_AMAZON_ORDERS." ".
                              "WHERE amazon_id='".$check->esc($AmazonOrderID)."'");
    return ( (int)$count!=0 );
  } 
}


class ProxyOrderFulfillmentList extends AmazonOrderFulfillmentList {

  var $_exported;
  
  function OrderFulfillment(){
    parent::AmazonOrderFulfillmentList();
  }

  function load_db_list(){
    $rs = new RecordSetProxy();
    $rs->query("SELECT ao.amazon_id, o.orders_id, MIN(osh.date_added) as shipped_date, ".
                 "ot.title as shipping_method ".
               "FROM ".TABLE_AMAZON_ORDERS." ao, ".TABLE_ORDERS_STATUS_HISTORY." osh, " .
                 TABLE_ORDERS . " o ".
                 "LEFT JOIN ".TABLE_ORDERS_TOTAL." ot ON o.orders_id=ot.orders_id AND ot.class='ot_shipping' ".
               "WHERE ao.orders_id=o.orders_id AND o.orders_status='".AmazonConfig::getShippedOrderStatus()."' ".
                 "AND ao.amazon_ship = '0' AND osh.orders_id=o.orders_id ".
                 "AND osh.orders_status_id='".AmazonConfig::getShippedOrderStatus()."' ".
                 "GROUP BY ao.amazon_id, o.orders_id ".
                 "ORDER BY shipped_date");
    $orders_list = array();
    $_exported = array();
    while($a_amazonship = $rs->next()) {
      $OrderFulfillment = new AmazonOrderFulfillment();
      $OrderFulfillment->AmazonOrderID = $a_amazonship['amazon_id'];
      $OrderFulfillment->_orders_id = $a_amazonship['orders_id'];
      $OrderFulfillment->FulfillmentDate = substr($a_amazonship['shipped_date'],0,10).' 00:00:00';

      $shipping_method = trim(strip_tags($a_amazonship['shipping_method']));
      if ( substr($shipping_method, -1)==':' ) $shipping_method = substr($shipping_method, 0, -1);

      if ( preg_match('/(.*)City.Link(.*)/i',$shipping_method,$m) ) {
        $methods = array();
        if ( isset($m[1]) && !empty($m[1]) ) $methods[] = trim($m[1]);
        if ( isset($m[2]) && !empty($m[2]) ) $methods[] = trim($m[2]);
        $metod = implode(', ',$methods);
        $OrderFulfillment->CarrierCode = 'City Link';
        $OrderFulfillment->ShippingMethod = $metod;
      }elseif ( preg_match('/(.*)Royal.Mail(.*)/i',$shipping_method,$m) ) {
        $methods = array();
        if ( isset($m[1]) && !empty($m[1]) ) $methods[] = trim($m[1]);
        if ( isset($m[2]) && !empty($m[2]) ) $methods[] = trim($m[2]);
        $metod = implode(', ',$methods);
        $OrderFulfillment->CarrierCode = 'Royal Mail';
        $OrderFulfillment->ShippingMethod = $metod;
      }elseif ( preg_match('/(.*)USPS(.*)/i',$shipping_method,$m) ) {
        $OrderFulfillment->CarrierCode = 'USPS';
        $OrderFulfillment->ShippingMethod = trim($m[2]);
      }elseif ( preg_match('/(.*)UPS(.*)/i',$shipping_method,$m) ) {
        $OrderFulfillment->CarrierCode = 'UPS';
        $OrderFulfillment->ShippingMethod = trim($m[2]);
      }elseif ( preg_match('/(.*)FedEx(.*)/i',$shipping_method,$m) ) {
        $OrderFulfillment->CarrierCode = 'FedEx';
        $OrderFulfillment->ShippingMethod = trim($m[2]);
      }elseif ( preg_match('/(.*)DHL(.*)/i',$shipping_method,$m) ) {
        $OrderFulfillment->CarrierCode = 'DHL';
        $OrderFulfillment->ShippingMethod = trim($m[2]);
      }else{
        $OrderFulfillment->CarrierName = $shipping_method;
        $OrderFulfillment->ShippingMethod = $shipping_method;
      }

      if ( empty($OrderFulfillment->ShippingMethod) ) {
        $OrderFulfillment->ShippingMethod = trim($shipping_method);
      }

      $this->orders_list[ count($this->orders_list)+1 ] = $OrderFulfillment;
      $this->_exported[] = $a_amazonship['orders_id']; 
    }
  }
  
  function mark_ship_done(){
    if ( is_array($this->_exported) && count($this->_exported)>0 ) {
      $rs = new RecordSetProxy();
      $rs->query("UPDATE " . TABLE_AMAZON_ORDERS . " ".
                 "SET amazon_ship = '1' ".
                 "WHERE orders_id IN ('" . implode("','", $this->_exported) . "')");
    }
  }
  
}

?>
