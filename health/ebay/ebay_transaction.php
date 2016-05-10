<?php
require_once EBAY_DIR_EBATLIB.'/TransactionType.php';

class ebay_transaction {
  var $stat = array('imported'=>0,
                    'shipped'=>0,
                    'paid'=>0,
                    'feedback'=>0,
                    'text'=>'');

  function GetSellerTransactions(){
    $core = ebay_core::get();

    $logger = $core->get_logger();
    $logger->info('Start ebay_transaction::GetSellerTransactions()');

    $proxy = $core->get_proxy();
    require_once EBAY_DIR_EBATLIB.'/GetSellerTransactionsRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/GetSellerTransactionsResponseType.php';
    require_once EBAY_DIR_EBATLIB.'/PaginationType.php';


    $day_number = 5;
    $req = new GetSellerTransactionsRequestType();
    $req->setDetailLevel('ReturnAll');
    $req->setNumberOfDays($day_number);
    $pagination = new PaginationType();
    $pagination->setPageNumber(1);
    $req->setPagination( $pagination );

    do{
      $objReqPage = $req->getPagination();
      $logger->info('GetSellerTransactionsRequest NumberOfDays='.$day_number.'; Pagination.PageNumber='.$objReqPage->getPageNumber().';');
      $res = $proxy->GetSellerTransactions($req);
      if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
        $objResPage = $res->getPaginationResult();
        $logger->info('GetSellerTransactionsResponse ['.$res->Ack.'] Seller.Email='.$res->getSeller()->getEmail().'; PageNumber='.$res->getPageNumber().'; PaginationResult.TotalNumberOfPages='.$objResPage->getTotalNumberOfPages().';');

        $transaction_array = $res->getTransactionArray();
        if ( is_array($transaction_array) ) {
          foreach( $transaction_array as $transaction ) {
            // create order from transaction
            //$trans = new ebay_transaction();
            //$trans->process( $transaction );
            $this->process( $transaction );
          }
        }

        $objReqPage = $req->getPagination();
        $next_page = intval( $objReqPage->getPageNumber() ) + 1;
        if ( $next_page==1 ) {
          $logger->warning('Try set next page to 1, look like inf. loop, escape now');
          break;
        }
        $objReqPage->setPageNumber($next_page);
      }else{
        $logger->notice('Exit action order_import by bad ack ['.$res->Ack.']');
        break;
      }
    } while ( $res->getHasMoreTransactions() );

    $logger->info('Exit ebay_transaction::GetSellerTransactions()');

  }

  function make_pp_AB ( $paypal_array ){
    $ret = array();
    $ret['name'] = $paypal_array['Name']['VALUE'];
    list($fname,$lname) = explode(' ', $ret['name'], 2);
    if ( in_array( strtolower($fname), array('mr', 'mrs', 'miss', 'mr & mrs', 'mr.', 'mrs.', 'miss.' ) ) ) {
      list($_fname,$lname) = explode(' ', $lname, 2);
      $fname .= ' '.$_fname;
    }
    $ret['first_name'] = $fname;
    $ret['last_name'] = $lname;

    $ret['street_address'] = $paypal_array['Street1']['VALUE'];
    $ret['suburb'] = !empty($paypal_array['Street2']['VALUE'])?$paypal_array['Street2']['VALUE']:'';
    if ( ACCOUNT_SUBURB != 'true' ) {
      $ret['street_address'] = trim( $ret['street_address'].' '.$ret['suburb'] );
    }
    $ret['city'] = $paypal_array['CityName']['VALUE'];
    $ret['postcode'] = !empty($paypal_array['PostalCode']['VALUE'])?$paypal_array['PostalCode']['VALUE']:'';
    $ret['phone'] = (!empty($paypal_array['Phone']['VALUE'])?$paypal_array['Phone']['VALUE']:'');

    $CountryInfo = ebay_country_info($paypal_array['Country']['VALUE']);
    $StateInfo = ebay_state_info($paypal_array['StateOrProvince']['VALUE'], $CountryInfo['id']);
    $state = $StateInfo['zone_name'];
    $zone_id = $StateInfo['zone_id'];

    //$ShippingAddress->getCountryName();
    $ret['country'] = $CountryInfo['name'];
    $ret['country_id'] = $CountryInfo['id'];
    $ret['state'] = $state;
    $ret['zone_id'] = $zone_id;
    $ret['address_format_id'] = $CountryInfo['address_format_id'];
    $ret['_clean_'] = !isset($CountryInfo['not_found']);
    $ret['_country_'] = isset($CountryInfo['not_found'])?$CountryInfo['not_found']:'';

    return $ret;
  }

  function makeAB( $ShippingAddress ){
    $ret = array();
    $ret['name'] = $ShippingAddress->getName();
    $fname = $ShippingAddress->getFirstName();
    $lname = $ShippingAddress->getLastName();
    if ( empty($fname) || empty($lname) ) {
      list($fname,$lname) = explode(' ', $ret['name'], 2);
      if ( in_array( strtolower($fname), array('mr', 'mrs', 'miss', 'mr & mrs', 'mr.', 'mrs.', 'miss.' ) ) ) {
        list($_fname,$lname) = explode(' ', $lname, 2);
        $fname .= ' '.$_fname;
      }
    }
    $ret['first_name'] = $fname;
    $ret['last_name'] = $lname;

    $addr1 = $ShippingAddress->getStreet();
    $customers_street_address = $ShippingAddress->getStreet1();
    $customers_suburb = $ShippingAddress->getStreet2();
    $addr4 = $ShippingAddress->getCounty();

    if ( empty($customers_street_address) ) $customers_street_address='';
    if ( !empty($addr1) ) $customers_street_address = trim($addr1.' '.$customers_street_address);
    if ( empty($customers_suburb) )  $customers_suburb = '';
    if ( !empty($addr4) ) $customers_suburb = trim($addr4.' '.$customers_suburb);

    if ( ACCOUNT_SUBURB == 'true' && strlen($customers_street_address)>64 ) {
      $customers_street_address_ = substr($customers_street_address,0,64);
      if (substr($customers_street_address,64,1) != ',' || substr($customers_street_address,64,1) != ' ') {
        $customers_street_address_ = preg_replace('/([, ])[^, ]*$/','\1',$customers_street_address_);
      }
      $customers_suburb = substr($customers_street_address,strlen($customers_street_address_));
      $customers_street_address = $customers_street_address_;
    }

    $ret['street_address'] = $customers_street_address;
    $ret['suburb'] = $customers_suburb;
    if ( ACCOUNT_SUBURB != 'true' ) {
      $ret['street_address'] = trim( $ret['street_address'].' '.$ret['suburb'] );
    }
    $ret['city'] = $ShippingAddress->getCityName();
    $ret['postcode'] = $ShippingAddress->getPostalCode();
    $ret['phone'] = $ShippingAddress->getPhone();

    $state = $ShippingAddress->getStateOrProvince();
    $CountryCode = $ShippingAddress->getCountry();
    $CountryInfo = ebay_country_info($CountryCode);
    $StateInfo = ebay_state_info($state, $CountryInfo['id']);
    $state = $StateInfo['zone_name'];
    $zone_id = $StateInfo['zone_id'];

    //$ShippingAddress->getCountryName();
    $ret['country'] = $CountryInfo['name'];
    $ret['country_id'] = $CountryInfo['id'];
    $ret['state'] = $state;
    $ret['zone_id'] = $zone_id;
    $ret['address_format_id'] = $CountryInfo['address_format_id'];
    $ret['_clean_'] = !isset($CountryInfo['not_found']);
    $ret['_country_'] = isset($CountryInfo['not_found'])?$CountryInfo['not_found']:'';

    return $ret;
  }

  function makeOrderItemArrays( $Item, $override_qty=false ){
    $product_ebay_id = $Item->getItemId();
    $product_uprid = $Item->getSKU();
    $product = ebay_product_reverse($product_uprid, $product_ebay_id);
    $product['_ebay_id'] = $product_ebay_id;
    if ( empty($product['products_name']) ) $product['products_name'] = $Item->getTitle();

    $StartPrice = $Item->getStartPrice();
    $start_price = new ebay_amount($StartPrice);

    $SellingStatus = $Item->getSellingStatus();

    /* imho CurrentPrice is StartPrice*Qty - check this */
    $product_price = $SellingStatus->getCurrentPrice();
    $_product_price = new ebay_amount($product_price);

    if ( $override_qty===false ) {
      $product_qty = $SellingStatus->getQuantitySold();
      $product['products_quantity'] = $product_qty;
    }else{
      $product_qty = $override_qty;
      $product['products_quantity'] = $override_qty;
    }
    $product['products_price'] = $start_price->getConverted();
    $product['final_price'] += $start_price->getConverted();
    //$product['final_price'] *= $product['products_quantity'];
    $product['products_tax'] = EBAY_TAX_RATE;

// Normalize TABLE_EBAY_PRODUCTS_LIST
    $ListingStatus = $SellingStatus->getListingStatus();
    if ( !empty($ListingStatus) ) {
      //Active Completed Ended
      $epl_state = ($ListingStatus=='Active')?'active':'hold';
      tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set ".
                     "epl_state='".$epl_state."', ".
                     "epl_quantity=epl_quantity-".$product_qty." ".
                   "where connector_id='".ebay_config::getEbaySiteID()."' and ".
                   "epl_sku='".tep_db_input($product_uprid)."'");
    }
    return $product;
  }

  function orderExists( $ebay_order_id ){
    $check = tep_db_query("select count(*) as c ".
                          "from ".TABLE_EBAY_ORDERS." where ".
                          "ebay_orders_id='".tep_db_input($ebay_order_id)."' and ".
                          "connector_id='".ebay_config::getEbaySiteID()."'");
    $check = tep_db_fetch_array($check);
    return ((int)$check['c'] != 0);
  }

  function process( $trans ){
    global $currencies;
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Enter ebay_transaction::process()');
    $EbayOrderID = $trans->getTransactionID();

    $TransactionStatus = $trans->getStatus();
    //$TransactionStatus->getPaymentMethodUsed() 'PayPal'
    /*
     * CheckoutStatus
     BuyerRequestsTotal - (out) Buyer requests total from seller.
     CheckoutComplete - (out) Checkout complete.
     CheckoutIncomplete - (out) Checkout incomplete--no details specified.
     CustomCode - (out) Reserved for future use.
     SellerResponded - (out) Seller responded to buyer's request.
     */
    $CheckoutStatus = $TransactionStatus->getCheckoutStatus();
    /*
     * CompleteStatus
      Complete - (in/out) Transaction is complete.
      CustomCode - (in/out) Reserved for internal or future use
      Incomplete - (in/out) Transaction is incomplete.
      Pending - (in/out) Transaction is pending.
     */
    $CompleteStatus = $TransactionStatus->getCompleteStatus();
    /*
     * eBayPaymentStatus
      BuyerCreditCardFailed - (out) The buyer's credit card failed.
      BuyerECheckBounced - (out) The buyer's eCheck bounced.
      BuyerFailedPaymentReportedBySeller - (out) The seller reports that the buyer's payment failed.
      CustomCode - (out) Reserved for internal or future use.
      NoPaymentFailure - (out) No payment failure.
      PaymentInProcess - (out) Currently for eBay Germany only.
      PayPalPaymentInProcess - (out) The payment from buyer to seller is in PayPal process, but has not yet been completed.
     */
    $eBayPaymentStatus = $TransactionStatus->geteBayPaymentStatus();
    $bBuyerSelectedShipping = $TransactionStatus->getBuyerSelectedShipping();
    /*
     * CustomCode   	Reserved for internal or future use.
     * MerchantHold  	The payment hold referred to as a "merchant hold" results from a possible 
     *                issue with a seller. If this value is returned, then the following values, 
     *                as a result, will be returned: In GetMyeBaySelling, 
     *                PaidWithPayPal is returned in TransactionArray.Transaction.SellerPaidStatus. 
     *                In GetMyeBayBuying, PaidWithPayPal is returned in 
     *                TransactionArray.Transaction.BuyerPaidStatus. 
     * None  	        Indicates that there is no payment review hold and no merchant hold.
     * PaymentReview  The payment hold referred to as a "payment review" hold results from a possible 
     *                issue with a buyer. If this value is returned, then the following values, 
     *                as a result, will be returned: In GetMyeBaySelling, NotPaid is returned in 
     *                TransactionArray.Transaction.SellerPaidStatus. In GetMyeBayBuying, 
     *                PaidWithPayPal is returned in TransactionArray.Transaction.BuyerPaidStatus.
     * Released  	    Indicates that a payment hold has been released.
     */
    $PaymentHoldStatus = $TransactionStatus->getPaymentHoldStatus();
    $LastTimeModified = $TransactionStatus->getLastTimeModified();

    $eBAY_statuses = array('checkout_status' => $CheckoutStatus,
                           'complete_status' => $CompleteStatus,
                           'eBay_payment_status' => $eBayPaymentStatus,
                           'payment_hold_status' => $PaymentHoldStatus,
                           'last_time_modified' => $LastTimeModified
    );
    $logger->info('Found TransactionID='.$EbayOrderID.'; '.
                        'Status.CheckoutStatus='.$CheckoutStatus.'; '.
                        'Status.CompleteStatus='.$CompleteStatus.'; '.
                        'Status.eBayPaymentStatus='.$eBayPaymentStatus.'; '.
                        'Status.PaymentHoldStatus='.$PaymentHoldStatus.'; '.
                        'Status.LastTimeModified='.$LastTimeModified.'; '.
                        'Status.BuyerSelectedShipping='.($bBuyerSelectedShipping?'true':'false').';'
    );
    if ( $CheckoutStatus!='CheckoutComplete' ) {
      $logger->info('Skip order by $CheckoutStatus!=\'CheckoutComplete\'');
      return;
    }
    if ( $eBayPaymentStatus=='PayPalPaymentInProcess' ) {
      $logger->warning('Skip order by $eBayPaymentStatus==\'PayPalPaymentInProcess\'');
      return;
    }

    $ExternalTransaction = $trans->getExternalTransaction();
    $paypal_trans_id = '';
    $paypal_datetime = '';
    $paypal_amount = 0;
    if ( is_array($ExternalTransaction) && count($ExternalTransaction)>1 ) {
      $logger->crit('Have '.count($ExternalTransaction).' ext transaction for '.$EbayOrderID.', '.
                    'connector not handle more then 1, teach me');
    }
    if ( is_object($ExternalTransaction[0]) ) {
      $paypal_trans_id = $ExternalTransaction[0]->getExternalTransactionID();
      $paypal_datetime = $ExternalTransaction[0]->getExternalTransactionTime();
      $paypal_amount = $ExternalTransaction[0]->getPaymentOrRefundAmount();
    }
//TODO: !! some time call GetTransactionDetails not need!!
    $paypal_info = array();
    $logger->info('Call paypal GetTransactionDetails TransactionID='.$paypal_trans_id.';');
    $pp_bridge = new paypal_bridge( ebay_config::getPaypalConfig() );
    $ppres = $pp_bridge->call('GetTransactionDetails', array('TransactionID'=>$paypal_trans_id) );
    $billing = false;
    $paypal_comment = '';
    if ( $ppres['Ack']['VALUE']=='Success' ) {
      $PayerInfo = $ppres['PaymentTransactionDetails']['PayerInfo'];
      $PaymentInfo = $ppres['PaymentTransactionDetails']['PaymentInfo'];
      $paypal_info['payer'] = $PayerInfo['Payer']['VALUE'];
      $paypal_info['payer_status'] = $PayerInfo['PayerStatus']['VALUE'];
      $paypal_info['ship_address_status'] = $PayerInfo['Address']['AddressStatus']['VALUE'];
      $paypal_info['payment_date'] = ebay_tools::_dateTime($PaymentInfo['PaymentDate']['VALUE']);
      $paypal_info['transactionID'] = $PaymentInfo['TransactionID']['VALUE'];
      $paypal_info['transactionID_parent'] = $PaymentInfo['ParentTransactionID']['VALUE'];
      $paypal_info['transaction_type'] = $PaymentInfo['TransactionType']['VALUE'];
      $paypal_info['payment_type'] = $PaymentInfo['PaymentType']['VALUE'];
      $paypal_info['gross_amount'] = $PaymentInfo['GrossAmount']['VALUE'];
      $paypal_info['gross_amount_currency'] = $PaymentInfo['GrossAmount']['currencyID'];
      $paypal_info['fee_amount'] = $PaymentInfo['FeeAmount']['VALUE'];
      $paypal_info['fee_amount_currency'] = $PaymentInfo['FeeAmount']['currencyID'];
      $paypal_info['payment_status'] = $PaymentInfo['PaymentStatus']['VALUE'];
      $paypal_info['pending_reason'] = $PaymentInfo['PendingReason']['VALUE'];
      $paypal_info['reason_code'] = $PaymentInfo['ReasonCode']['VALUE'];
      $paypal_info['memo'] = '';
      //
      $billing = ebay_transaction::make_pp_AB( $PayerInfo['Address'] );

      $paypal_PaymentItemInfo = $ppres['PaymentTransactionDetails']['PaymentItemInfo'];
      if ( !empty($paypal_PaymentItemInfo['Memo']['VALUE']) ) {
        $paypal_info['memo'] = $paypal_comment = $paypal_PaymentItemInfo['Memo']['VALUE'];
      }
      $paypal_info['item_list'] = '';
      $paypal_items = $paypal_PaymentItemInfo['PaymentItem'];
      foreach( $paypal_items as $paypal_item ) {
        if ( !empty($paypal_info['item_list']) ) $paypal_info['item_list'] .= '<br>';
        $paypal_info['item_list'] .= $paypal_item['Quantity']['VALUE'].' x ['.$paypal_item['Number']['VALUE'] .'] '. $paypal_item['Name']['VALUE'];
      }
    }else{
      $paypal_err_str = '';
      foreach( $ppres['Errors'] as $pp_error ){
        $paypal_err_str .= 'ShortMessage='.$pp_error['ShortMessage']['VALUE'].'; ';
        $paypal_err_str .= 'LongMessage='.$pp_error['LongMessage']['VALUE'].';'."\n";
      }
      $logger->notice( 'Ack='.$ppres['Ack']['VALUE'].'; '.$paypal_err_str.' skip order' );
      return;
    }
    if ( strtolower($paypal_info['payment_status'])!='completed' ) {
      $logger->alert( 'Got $paypal_info[\'payment_status\']='.$paypal_info['payment_status'].'; wait for "Completed" skip order' );
      return;
    }

    if ( ebay_transaction::orderExists($EbayOrderID) ) {
      tep_db_perform(TABLE_EBAY_ORDERS, 
                     $eBAY_statuses, 
                     'update', 
                       "ebay_orders_id='".tep_db_input($EbayOrderID)."' and ".
                       "connector_id='".ebay_config::getEbaySiteID()."'");
      $logger->info('Skip order by (orderExists(\''.$EbayOrderID.'\')==true)');
      return;
    }

    if ( !empty($paypal_comment) ) {
      $orders_comments = $paypal_comment."\n";
    }else{
      $orders_comments = '';
    }

    $Buyer = new BuyerType();
    $Buyer = $trans->getBuyer();

    $BuyerInfo = $Buyer->getBuyerInfo();
    $ebay_buyer = array(
       'buyer_feedback_score' => $Buyer->getFeedbackScore(),
       'buyer_new_user' => ($Buyer->getNewUser()?'1':'0'),
       'buyer_userID' => $Buyer->getUserID(),
       'buyer_IDVerified' => ($Buyer->getIDVerified()?'1':'0'),
       'buyer_registration_date' => $Buyer->getRegistrationDate(),
       'buyer_status' => $Buyer->getStatus(),
    );
    if ( !empty($ebay_buyer['buyer_registration_date']) ) {
      $ebay_buyer['buyer_registration_date'] = substr($ebay_buyer['buyer_registration_date'], 0, 10);
    }

/*   INITIAL STUB - only save ebay orders USE ON SETUP STAGE  */
if (false) {
    $sql_data_array = array('ebay_orders_id' => $EbayOrderID,
                            'connector_id' => ebay_config::getEbaySiteID(),
                            'orders_id' => 0,
                            'paypal_txn_id' => $paypal_trans_id,
                            'raw_items' => '--,--,--',
                            'created_date' => $OrderDate,
                            'ship_sended'=>2,
                            'payment_received'=>2,
                            'feedback_sended'=>2,
                            'download_date' => 'now()');
    $sql_data_array = array_merge($sql_data_array, $ebay_buyer, $eBAY_statuses);
    tep_db_perform(TABLE_EBAY_ORDERS, $sql_data_array);
    $logger->info('SAVE ORDER '.$EbayOrderID.' LINK AND EXIT --- SETUP STAGE');
    return;
}
/*  INITIAL STUB  */

    $ShippingAddress = $BuyerInfo->getShippingAddress();
    $customers_email_address = $Buyer->getEmail();
    $delivery = ebay_transaction::makeAB( $ShippingAddress );
    if ( $billing===false ) {
      $billing = $delivery;
    }
    $customers_phone = $delivery['phone'];
    $customers_name = $billing['name'];
    $customers_firstname = $billing['first_name'];
    $customers_lastname = $billing['last_name'];

    $OrderDate = $trans->getCreatedDate(); //TODO: ZONE safe?
    $Item = $trans->getItem();
    $real_qty = $trans->getQuantityPurchased();
    $product = ebay_transaction::makeOrderItemArrays( $Item, $real_qty );
    $_raw_items[] = $product['_ebay_id'];
    unset($product['_ebay_id']);

    $ShippingService = $trans->getShippingServiceSelected();
    $ebay_shipping_method = $ShippingService->getShippingService();
    $ebay_shipping_cost = $ShippingService->getShippingServiceCost();
    $_shipping_cost = new ebay_amount( $ebay_shipping_cost );

    $AmountPaid = $trans->getAmountPaid();
    $_ebay_amount_paid = new ebay_amount($AmountPaid);
    $summary = $_ebay_amount_paid->summary();
    $currency = $summary['currency'];
    $currency_value = $summary['value'];

    $order_subtotal = $order_total = $_ebay_amount_paid->getConverted();
    $order_shipping = $order_tax = 0;

    $shipping_variants = shipping_quote( $product['uprid'], false, true );
    $_dec_subtotal = 0;
    if ( is_array( $shipping_variants[$ebay_shipping_method] ) ) {
      $shipping_info = $shipping_variants[$ebay_shipping_method];
      if ( ebay_config::forceFreeShipping() ) {
        // $product['products_price'] $product['final_price'] - price from ebay
        // incl. shipping(s) GROSS
        // so.... decrease estimated shipping
        $_shop_shipping = $shipping_info['cost_gross'];
        $product['products_price'] = number_format($product['products_price']-$_shop_shipping,4,'.','');
        $product['final_price'] = number_format($product['final_price']-$_shop_shipping,4,'.','');
        $_shop_shipping *= $product['products_quantity'];
        $order_shipping = $_shop_shipping;
        $order_subtotal -= $_shop_shipping;
      }
    }else{
      // got shipping not configured in shop
      $_ship_gross_cost = $_shipping_cost->getConverted();
      $_ship_cost = ebay_tools::tax_reduce( $_ship_gross_cost );
      $shipping_info = array( 'shipping_title' => $ebay_shipping_method,
                              'shipping_class' => $ebay_shipping_method,
                              'cost_gross' => $_ship_gross_cost,
                              'cost' => $_ship_cost,
                              'tax' => EBAY_TAX_RATE
      );
      $order_subtotal -= $_ship_gross_cost;
      $order_shipping = $_ship_gross_cost; 
      //$_dec_subtotal = -$shipping_info['cost_gross'];
    }

    $product['final_price'] = ebay_tools::tax_reduce( $product['final_price'], EBAY_TAX_RATE);
    $product['products_price'] = ebay_tools::tax_reduce( $product['products_price'], EBAY_TAX_RATE);
    //echo '<pre>'; var_dump( $product ); echo '</pre>';

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      $_order_subtotal_net = ebay_tools::tax_reduce($order_subtotal);
      $_shipping_net = ebay_tools::tax_reduce($order_shipping); // shipping always shown w tax
      $order_tax = $order_total - ($_order_subtotal_net+$_shipping_net);
      $order_tax = tep_round( $order_tax, 2 );
    }else{
      $order_subtotal = ebay_tools::tax_reduce($order_subtotal);
      $_shipping_net = ebay_tools::tax_reduce($order_shipping); // shipping always shown w tax
      $order_tax = $order_total - ($_order_subtotal_net+$_shipping_net);
      $order_tax = tep_round( $order_tax, 2 );
    }

    $ebay_transaction_price = $trans->getTransactionPrice();
    $_transaction_price = new ebay_amount( $ebay_transaction_price );

/*
    $order_subtotal = $_transaction_price->getConverted() + $_dec_subtotal;
    $order_subtotal_net = ebay_tools::tax_reduce($order_subtotal);
    $order_tax = 0;
    $order_tax += number_format( $shipping_info['cost_gross']-$shipping_info['cost'], 4, '.', '' );
    $order_tax += number_format(  $order_subtotal-$order_subtotal_net, 4, '.', '' );

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      //$order_subtotal =
      $order_shipping = $shipping_info['cost_gross'];
    }else{
      $order_subtotal = $order_subtotal_net;
      $order_shipping = $shipping_info['cost'];
    }
*/
    
    $order_totals = array();
    $order_totals[] = array('title' => 'Sub-Total:',
                            'text' => $currencies->format($order_subtotal, true, $currency, $currency_value),
                            'value' => ($order_subtotal),
                            'class' => 'ot_subtotal',
                            'sort_order' => '1');
    $order_totals[] = array('title' => $shipping_info['shipping_title'] . ':',
                            'text' => $currencies->format($order_shipping, true, $currency, $currency_value),
                            'value' => $order_shipping,
                            'class' => 'ot_shipping',
                            'sort_order' => '2');
    $order_totals[] = array('title' => 'Tax:',
                            'text' => $currencies->format($order_tax, true, $currency, $currency_value),
                            'value' => $order_tax,
                            'class' => 'ot_tax',
                            'sort_order' => '3');
    $order_totals[] = array('title' => 'Total:',
                            'text' => '<b>' . $currencies->format($order_total, true, $currency, $currency_value) . '</b>',
                            'value' => $order_total,
                            'class' => 'ot_total',
                            'sort_order' => '10');

    if ( ebay_config::orderImportCreateCustomers() ) {
      $test_exists_r = tep_db_query("SELECT customers_id ".
                                    "FROM ".TABLE_CUSTOMERS." WHERE ".
                                      "customers_email_address='".tep_db_input($customers_email_address)."'");
      if ( tep_db_num_rows($test_exists_r)>0 ) {
        $test_exists = tep_db_fetch_array($test_exists_r);
        $customers_id = $test_exists['customers_id'];
      } else {
      // insert customer
        $sql_data_array = array('customers_firstname' => tep_db_prepare_input($customers_firstname),
                                'customers_lastname' => tep_db_prepare_input($customers_lastname),
                                'customers_email_address' => tep_db_prepare_input($customers_email_address),
                                'customers_telephone' => $customers_phone,
                                'customers_default_address_id' => '');
        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
        $customers_id = tep_db_insert_id();

        // insert customer address
        $sql_data_array = array('customers_id' => tep_db_prepare_input($customers_id),
                                'entry_firstname' => $delivery['first_name'],
                                'entry_lastname' => $delivery['last_name'],
                                'entry_street_address' => $delivery['street_address'],
                                'entry_suburb' => $delivery['suburb'],
                                'entry_postcode' => $delivery['postcode'],
                                'entry_state' => $delivery['state'],
                                'entry_zone_id' => $delivery['zone_id'],
                                'entry_city' => $delivery['city'],
                                'entry_country_id' => $delivery['country_id']);
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
        $customers_address_id = tep_db_insert_id();
        unset($sql_data_array);

        // update customer (add default address id)
        tep_db_query("update " . TABLE_CUSTOMERS . " set ".
                       "customers_default_address_id = '" . (int)$customers_address_id . "' ".
                     "where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " ( ".
                       "customers_info_id, ".
                       "customers_info_number_of_logons, " .
                       "customers_info_date_account_created " .
                     ") values (".
                       "'" . (int)$customers_id . "', ".
                       "'0', ".
                       "now() ".
                     ")");
      }
    }else {
      $customers_id = ebay_config::orderImportCustomerID();
    }

    // write order in DB
    // insert order
    $sql_data_array = array('customers_id' => intval($customers_id),
                            'customers_name' => $customers_name,
                            'customers_firstname' => $customers_firstname,
                            'customers_lastname' => $customers_lastname,
                            'customers_street_address' => $billing['street_address'],
                            'customers_suburb' => $billing['suburb'],
                            'customers_city' => $billing['city'],
                            'customers_postcode' => $billing['postcode'],
                            'customers_state' => $billing['state'],
                            'customers_country' => $billing['country'],
                            'customers_email_address' => $customers_email_address,
                            'customers_telephone' => $customers_phone,
                            'customers_address_format_id' => $billing['address_format_id'],
                            'delivery_name' => $delivery['name'],
                            'delivery_firstname' => $delivery['first_name'],
                            'delivery_lastname' => $delivery['last_name'],
                            'delivery_street_address' => $delivery['street_address'],
                            'delivery_suburb' => $delivery['suburb'],
                            'delivery_city' => $delivery['city'],
                            'delivery_postcode' => $delivery['postcode'],
                            'delivery_state' => $delivery['state'],
                            'delivery_country' => $delivery['country'],
                            'delivery_address_format_id' => $delivery['address_format_id'],
                            'billing_name' => $billing['name'],
                            'billing_firstname' => $billing['first_name'],
                            'billing_lastname' => $billing['last_name'],
                            'billing_street_address' => $billing['street_address'],
                            'billing_suburb' => $billing['suburb'],
                            'billing_city' => $billing['city'],
                            'billing_postcode' => $billing['postcode'],
                            'billing_state' => $billing['state'],
                            'billing_country' => $billing['country'],
                            'billing_address_format_id' => $billing['address_format_id'],

                            'payment_class' => ebay_config::payment('class'),
                            'payment_method' => ebay_config::payment('method'),
                            'shipping_method' => $shipping_info['shipping_title'],
                            'shipping_class' => $shipping_info['shipping_class'],

                            'language_id'=> '1',
                            'cc_type' => '',
                            'cc_owner' => '',
                            'cc_number' => '',
                            'cc_expires' => '',
                            'date_purchased' => $OrderDate,
                            'orders_status' => '0',
                            'currency' => $currency,
                            'currency_value' => $currency_value );
    tep_db_perform(TABLE_ORDERS, $sql_data_array);
    $inserted_order_id = tep_db_insert_id();
    unset($sql_data_array);

    // save product
    $product_attributes = $product['attributes'];
    unset( $product['attributes'] );
    $sql_data_array = $product;
    $sql_data_array['orders_id'] = $inserted_order_id;
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $inserted_orders_products_id = tep_db_insert_id();
    unset($sql_data_array);
    if (PRODUCTS_INVENTORY == 'True'){
      update_stock($product['uprid'], 0, $product['products_quantity']);
    }
    
    if ( is_array($product_attributes) ) {
      foreach( $product_attributes as $product_attribute ){
        $sql_data_array = $product_attribute;
        $sql_data_array['orders_id'] = $inserted_order_id;
        $sql_data_array['orders_products_id'] = $inserted_orders_products_id;
        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
        unset($sql_data_array);
      }
    }
    // ok, write ot_
    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $sql_data_array = array('orders_id' => $inserted_order_id,
                              'title' => $order_totals[$i]['title'],
                              'text' => $order_totals[$i]['text'],
                              'value' => $order_totals[$i]['value'],
                              'class' => $order_totals[$i]['class'],
                              'sort_order' => $order_totals[$i]['sort_order']);
      tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
      unset($sql_data_array);
    }

    // insert order_status_history (comments)
    $sql_data_array = array('orders_id' => $inserted_order_id,
                            'orders_status_id' => (int)ebay_config::orderImportDefaultStatus(),
                            'date_added' => 'now()',
                            'customer_notified' => '0',
                            'comments' => $orders_comments);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    unset($sql_data_array);
    //fixate order
    tep_db_query("update ".TABLE_ORDERS." set ".
                    "orders_status='".(int)ebay_config::orderImportDefaultStatus()."', ".
                    "shipping_method='".tep_db_input($shipping_info['shipping_title'])."', ".
                    "shipping_class='".tep_db_input($shipping_info['shipping_class'])."' ".
                  "where orders_id='".(int)$inserted_order_id."'");

    // ebay order record
    $sql_data_array = array('ebay_orders_id' => $EbayOrderID,
                            'connector_id' => ebay_config::getEbaySiteID(),
                            'orders_id' => $inserted_order_id,
                            'paypal_txn_id' => $paypal_trans_id,
                            'raw_items' => implode(',',$_raw_items),
                            'created_date' => $OrderDate,
                            'download_date' => 'now()');
    $sql_data_array = array_merge($sql_data_array, $ebay_buyer, $eBAY_statuses);
    tep_db_perform(TABLE_EBAY_ORDERS, $sql_data_array);
    unset($sql_data_array);

    $sql_data_array = $paypal_info;
    $sql_data_array['orders_id'] = $inserted_order_id;
    tep_db_perform(TABLE_EBAY_PAYPAL, $sql_data_array);
    unset($sql_data_array);
    $this->stat['text'] .= 'EbayOrderID '.$EbayOrderID.' = ShopOrder '.$inserted_order_id.' '."\n";
    $this->stat['imported']++;
  }

  function sendOrderPaid() {
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Start ebay_transaction::sendOrderPaid()');
    require_once EBAY_DIR_EBATLIB.'/CompleteSaleRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/CompleteSaleResponseType.php';
    require_once EBAY_DIR_EBATLIB.'/FeedbackInfoType.php';
    require_once EBAY_DIR_EBATLIB.'/ShipmentType.php';

    $criteria = " eo.payment_received<>2 ";
    if ( ebay_config::setFeedbackWithPaid() ) {
      $criteria = ' ( '.$criteria.' or eo.feedback_sended<>2 )';
    }
    $orders_r = tep_db_query("select eo.ebay_orders_id, eo.orders_id, ".
                               "eo.payment_received, eo.feedback_sended, ".
                               //"eo.payment_date, eo.feedback_date, ".
                               "eo.raw_items, ".
                               "o.orders_id as shop_order_id ".
                             "from ".TABLE_EBAY_ORDERS." eo ".
                             "left join ".TABLE_ORDERS." o on o.orders_id=eo.orders_id ".
                             "where eo.connector_id = '".ebay_config::getEbaySiteID()."' and ".
                             "eo.eBay_payment_status='NoPaymentFailure' and ".
                             $criteria);
    while( $orders = tep_db_fetch_array($orders_r) ) {
      if ( empty( $orders['shop_order_id'] ) ) {
        $logger->notice('Ebay order '.$orders['ebay_orders_id'].' deleted in shop, need handle');
        continue;
      }
      $items= split(',', $orders['raw_items']);
      $itemID = '';
      if ( count($items)==1 ) {
        $itemID = $items[0];
      }elseif ( count($items)>1 ) {
        $logger->notice('Ebay order '.$orders['ebay_orders_id'].' contain '.count($items).' items, need handle');
      }

      $req = new CompleteSaleRequestType();
      $req->setTransactionID( $orders['ebay_orders_id'] );
      if ( !empty($itemID) ) $req->setItemID( $itemID );
      if ( $orders['payment_received']!=2 ) $req->setPaid(1);
      if ( ebay_config::setFeedbackWithPaid() && $orders['feedback_sended']!=2 ) {
        $FeedbackInfo = new FeedbackInfoType();
        $CommentText = ebay_config::getDefaultFeedbackText();
        if ( !empty($CommentText) ) {
          $FeedbackInfo->setCommentText($CommentText);
        }
        $FeedbackInfo->setCommentType( 'Positive' );
        $req->setFeedbackInfo( $FeedbackInfo );
      }
      $proxy = $core->get_proxy();
      $logger->info('Call CompleteSale orderID='.$orders['ebay_orders_id'].'; $itemID='.$itemID.'; set Paid flag '.(ebay_config::setFeedbackWithPaid()?' and Positive Feedback for buyer':''));
      $res = $proxy->CompleteSale($req);
      if ( $res->Ack=='Success' ) {
        $logger->info('CompleteSale ['.$res->Ack.']');
        $sql_array = array();
        if ( $orders['payment_received']!=2 ) {
          $sql_array['payment_received'] = 2;
          $sql_array['payment_date'] = 'now()';
          $this->stat['paid']++;
        }
        if ( ebay_config::setFeedbackWithPaid() && $orders['feedback_sended']!=2 ) {
          $this->stat['feedback']++;
          $sql_array['feedback_sended'] = 2;
          $sql_array['feedback_date'] = 'now()';
        }
        if ( count($sql_array)>0 ) {
          tep_db_perform(TABLE_EBAY_ORDERS,
                         $sql_array,
                         'update',
                         "ebay_orders_id='".tep_db_input($orders['ebay_orders_id'])."'");
        }
      }else{
        $logger->err('CompleteSale ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $sku_info = '';
            if ( is_array($ErrorType->ErrorParameters) ) {
              foreach( $ErrorType->ErrorParameters as $ErrorParameterType ) {
                if ( !empty($sku_info) ) $sku_info.=';';
                $sku_info .= $ErrorParameterType->getValue();
              }
            }
            $logger->err($sku_info.' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                                  '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
            if ($ErrorType->ShortMessage=='Adding feedback failed: invalid item number or invalid transaction or feedback already left.') {
              $logger->err('Reset orders feed back flag');
              $sql_array = array(//'feedback_date'=>'now()'
                                 'feedback_sended' => 2);
              tep_db_perform(TABLE_EBAY_ORDERS,
                             $sql_array,
                             'update',
                             "ebay_orders_id='".tep_db_input($orders['ebay_orders_id'])."'");
            }
          }
        }
      }
    }
    $logger->info('Exit ebay_transaction::sendOrderPaid()');
  }


  function sendOrderShipped() {
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Start ebay_transaction::sendOrderShipped()');
    require_once EBAY_DIR_EBATLIB.'/CompleteSaleRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/CompleteSaleResponseType.php';
    require_once EBAY_DIR_EBATLIB.'/FeedbackInfoType.php';
    require_once EBAY_DIR_EBATLIB.'/ShipmentType.php';

    $criteria = " eo.ship_sended <> 2 ";
    if ( !ebay_config::setFeedbackWithPaid() ) {
      $criteria = ' ( '.$criteria.' or eo.feedback_sended<>2 )';
    }

    $ebayship_sql = "select eo.ebay_orders_id, o.orders_id, eo.raw_items, ".
         "eo.feedback_sended, eo.ship_sended, " .
         "min(osh.date_added) as shipped_date ".
       "from " . TABLE_ORDERS . " o, ".TABLE_EBAY_ORDERS." eo, ".TABLE_ORDERS_STATUS_HISTORY." osh ".
       "where eo.connector_id = '".ebay_config::getEbaySiteID()."' and ".
             "eo.orders_id=o.orders_id and ".
             "o.orders_status='".ebay_config::orderShippedStatus()."' and ".
             $criteria." and ".
             "osh.orders_id=o.orders_id and ".
             "osh.orders_status_id='".ebay_config::orderShippedStatus()."' ".
       "group by eo.ebay_orders_id, o.orders_id order by shipped_date";

    $ebayship_r = tep_db_query($ebayship_sql);
    $logger->info('Found '.tep_db_num_rows($ebayship_r).' shipped orders');

    while($orders = tep_db_fetch_array($ebayship_r)) {
      $items= split(',', $orders['raw_items']);
      $itemID = '';
      if ( count($items)==1 ) {
        $itemID = $items[0];
      }elseif ( count($items)>1 ) {
        $logger->notice('Ebay order '.$orders['ebay_orders_id'].' contain '.count($items).' items, need handle');
      }

      $carier = '';
      $tracking = '';
      $comment = '';
      // !!!!
      $ot_shipname_r = tep_db_query("select title from ".TABLE_ORDERS_TOTAL." where orders_id='".(int)$orders['orders_id']."' and class='ot_shipping'");
      if ( tep_db_num_rows($ot_shipname_r)!=0 ) {
        $ot_shipname = tep_db_fetch_array($ot_shipname_r);
        if ( !empty($ot_shipname['title']) ) $orders['shipping_method'] = $ot_shipname['title'];
      }
      $shipping_method = trim(strip_tags($orders['shipping_method']));
      $shipping_method = preg_replace( '/\s*:$/','', $shipping_method );
      if ( preg_match('/(.*)City.Link(.*)/i',$shipping_method,$m) ) {
        $methods = array();
        if ( isset($m[1]) && !empty($m[1]) ) $methods[] = trim($m[1]);
        if ( isset($m[2]) && !empty($m[2]) ) $methods[] = trim($m[2]);
        $metod = implode(', ',$methods);
        $carier = 'City Link';
        $comment = 'Order posted via "'.$shipping_method.'" ';
      }elseif ( preg_match('/(.*)Royal.Mail(.*)/i',$shipping_method,$m) ) {
        $methods = array();
        if ( isset($m[1]) && !empty($m[1]) ) $methods[] = trim($m[1]);
        if ( isset($m[2]) && !empty($m[2]) ) $methods[] = trim($m[2]);
        $metod = implode(', ',$methods);
        $carier = 'Other';
        $comment = 'Order posted via "'.$shipping_method.'" ';
      }elseif ( preg_match('/(.*)Fedex(.*)/i',$shipping_method,$m) ) {
        $methods = array();
        if ( isset($m[1]) && !empty($m[1]) ) $methods[] = trim($m[1]);
        if ( isset($m[2]) && !empty($m[2]) ) $methods[] = trim($m[2]);
        $metod = implode(', ',$methods);
        $carier = 'Other';
        $comment = 'Order posted via "'.$shipping_method.'" ';
      }else{
        $comment = 'Order posted via "'.$shipping_method.'" ';
      }

      $req = new CompleteSaleRequestType();
      $req->setTransactionID( $orders['ebay_orders_id'] );
      if ( !empty($itemID) ) $req->setItemID( $itemID );

      if ( !ebay_config::setFeedbackWithPaid() && $orders['feedback_sended']!=2 ) {
        $FeedbackInfo = new FeedbackInfoType();
        $CommentText = ebay_config::getDefaultFeedbackText();
        if ( !empty($CommentText) ) {
          $FeedbackInfo->setCommentText($CommentText);
        }
        $FeedbackInfo->setCommentType( 'Positive' );
        $req->setFeedbackInfo( $FeedbackInfo );
      }

      if ( $orders['ship_sended']!=2 ) {
        $Shipment = new ShipmentType();
        $Shipment->setShippedTime( ebay_tools::dateTime($orders['shipped_date']) );
        if (!empty($comment)) $Shipment->setNotes($comment);
        if (!empty($tracking)) $Shipment->setShipmentTrackingNumber($tracking);
        if (!empty($carier)) $Shipment->setShippingCarrierUsed($carier);

        $req->setShipment($Shipment);
        $req->setShipped(true);
        $req->setErrorHandling('FailOnError');
      }

      $proxy = $core->get_proxy();
      $logger->info('Call CompleteSale orderID='.$orders['ebay_orders_id'].'; $itemID='.$itemID.'; set Ship flag and tracking info');
      $res = $proxy->CompleteSale($req);
      if ( $res->Ack=='Success' ) {
        $logger->info('CompleteSale ['.$res->Ack.']');
        $sql_array = array();
        if ( $orders['ship_sended']!=2 ) {
          $sql_array['ship_sended'] = 2;
          $sql_array['ship_date'] = 'now()';
          $this->stat['shipped']++;
        }
        if ( !ebay_config::setFeedbackWithPaid() && $orders['feedback_sended']!=2 ) {
          $this->stat['feedback']++;
          $sql_array['feedback_sended'] = 2;
          $sql_array['feedback_date'] = 'now()';
        }
$logger->debug( var_export($sql_array, true) );
        if ( count($sql_array)>0 ) {
          tep_db_perform(TABLE_EBAY_ORDERS,
                       $sql_array,
                       'update',
                       "ebay_orders_id='".tep_db_input($orders['ebay_orders_id'])."'");
        }
      }else{
        $logger->err('CompleteSale ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $sku_info = '';
            if ( is_array($ErrorType->ErrorParameters) ) {
              foreach( $ErrorType->ErrorParameters as $ErrorParameterType ) {
                if ( !empty($sku_info) ) $sku_info.=';';
                $sku_info .= $ErrorParameterType->getValue();
              }
            }
            $logger->err($sku_info.' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                                  '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
            if ($ErrorType->ShortMessage=='Adding feedback failed: invalid item number or invalid transaction or feedback already left.') {
              $logger->err('Reset orders feed back flag');
              $sql_array = array(//'feedback_date'=>'now()'
                                 'feedback_sended' => 2);
              tep_db_perform(TABLE_EBAY_ORDERS,
                             $sql_array,
                             'update',
                             "ebay_orders_id='".tep_db_input($orders['ebay_orders_id'])."'");
            }

          }
        }
      }

    }
    $logger->info('Exit ebay_transaction::sendOrderShipped()');
  }

}
?>
