<?php

  if (!defined('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS')) define('MODULE_PAYMENT_GOOGLECHECKOUT_STATUS', '1' );
  if (!defined('MODULE_PAYMENT_GOOGLECHECKOUT_CHARGED_STATUS_ID')) define('MODULE_PAYMENT_GOOGLECHECKOUT_CHARGED_STATUS_ID', '2');
  if (!defined('MODULE_PAYMENT_GOOGLECHECKOUT_DELIVERED_STATUS_ID')) define('MODULE_PAYMENT_GOOGLECHECKOUT_DELIVERED_STATUS_ID', '3' );

  function send_google_req($postargs, $log_on=true) {
    $message_log = false;
    if(MODULE_PAYMENT_GOOGLECHECKOUT_MODE == 'https://checkout.google.com/') {
      $url = 'https://checkout.google.com/cws/v2/Merchant/'.MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID.'/request';
    }else{
      $url = 'https://sandbox.google.com/checkout/cws/v2/Merchant/'.MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID.'/request';
    }

    $session = curl_init($url);

    $header_string_1 = "Authorization: Basic ".base64_encode(MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID.':'.MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY);
    $header_string_2 = "Content-Type: application/xml;charset=UTF-8";	
    $header_string_3 = "Accept: application/xml;charset=UTF-8";
	
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array($header_string_1, $header_string_2, $header_string_3));
    curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
    curl_setopt($session, CURLOPT_HEADER, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	// Uncomment the following and set the path to your CA-bundle.crt file if SSL verification fails
	//curl_setopt($session, CURLOPT_CAINFO, "C:\\Program Files\\xampp\\apache\\conf\\ssl.crt\\ca-bundle.crt");

    $response = curl_exec($session);
	  if (curl_errno($session)) {
		  die(curl_error($session));
	  } else {
	    curl_close($session);
    }
    if ( $log_on ) {
      $message_log = fopen( preg_replace('/\/$/','',DIR_FS_CATALOG).'/temp/status_chng.log', "a");
      if ( $message_log!==false ) fwrite($message_log, sprintf("CALL TRACE\r\n%s\n", var_export(debug_backtrace(),true) ));
      if ( $message_log!==false ) fwrite($message_log, sprintf("\r\n%s\n",$postargs));
      if ( $message_log!==false ) fwrite($message_log, sprintf("\r\n%s\n",$response));
    }

    // Get HTTP Status code from the response
    $status_code = array();
    preg_match('/\d\d\d/', $response, $status_code);
    
    if ( $message_log!==false ) fwrite($message_log, sprintf("\r\n%s\n",$status_code[0]));
    if ( $message_log!==false ) fclose( $message_log );

    // Check for errors
    switch( $status_code[0] ) {
      case 200:
      // Success
        break;
      case 503:
        die('Error 503: Service unavailable. An internal problem prevented us from returning data to you.');
	      break;
      case 403:
        die('Error 403: Forbidden. You do not have permission to access this resource, or are over your rate limit.');
        break;
      case 400:
        die('Error 400: Bad request. The parameters passed to the service did not match as expected. The exact error is returned in the XML response.');
        break;
      default:
        die('Error :' . $status_code[0]);
    }
  }


  function google_checkout_state_change($check_status, $status, $oID, $cust_notify, $notify_comments) {
    // If status update is from Pending -> Processing on the Admin UI
    // this invokes the processing-order and charge-order commands
    // 1->Pending, 2-> Processing
    global $carrier_select, $tracking_number;

    $google_answer = tep_db_fetch_array(tep_db_query("select o.google_orders_id, ot.value from " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where o.orders_id = " . (int)$oID ." and o.orders_id=ot.orders_id and ot.class='ot_total'"));

    $google_order = $google_answer['google_orders_id'];  
    $amt = $google_answer['value'];  
    if ( !tep_not_null($google_order) ) return;

    if ($check_status['orders_status'] == MODULE_PAYMENT_GOOGLECHECKOUT_STATUS && $status == MODULE_PAYMENT_GOOGLECHECKOUT_STATUS) {
    //if($check_status['orders_status'] == MODULE_PAYMENT_GOOGLECHECKOUT_STATUS && ($status == 2)) {

      $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">
                  <charge-order xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"". $google_order. "\">
                  <amount currency=\"" . DEFAULT_CURRENCY . "\">" . $amt . "</amount>
                  </charge-order>";
      send_google_req($postargs); 
      
      $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">
                  <process-order xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"". $google_order. "\"/> ";
      send_google_req($postargs); 

    }			
    
    // If status update is from Processing -> Delivered on the Admin UI
    // this invokes the deliver-order and archive-order commands
    // 2->Processing, 3-> Delivered
    // Delivered now only from Awaiting
    //if($check_status['orders_status'] == MODULE_PAYMENT_GOOGLECHECKOUT_STATUS &&  $status == MODULE_PAYMENT_GOOGLECHECKOUT_DELIVERED_STATUS_ID) {
    // prevent double ship state on google
    $double_status_check = tep_db_fetch_array(tep_db_query("select count(*) as c from ".TABLE_ORDERS_STATUS_HISTORY." where orders_id='".(int)$oID."' and orders_status_id='".(int)MODULE_PAYMENT_GOOGLECHECKOUT_STATUS."'"));
    if( (int)$double_status_check['c']==0 && in_array($check_status['orders_status'], array('4','2','100000') ) &&  $status == MODULE_PAYMENT_GOOGLECHECKOUT_STATUS) {
      $send_mail = "false";
      if($cust_notify == 1) $send_mail = "true";
      					
      $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">
                   <deliver-order xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"". $google_order. "\"> 
                   	 <send-email>" . $send_mail . "</send-email>";
      if(isset($carrier_select) &&  ($carrier_select != 'select') && isset($tracking_number) && !empty($tracking_number)) {
				$postargs .=	"<tracking-data>
								        <carrier>" . $carrier_select . "</carrier>
								        <tracking-number>" . $tracking_number . "</tracking-number>
								    	 </tracking-data>";
				$comments = "Shipping Tracking Data:\n Carrier: " . $carrier_select . "\n Tracking Number: " . $tracking_number . "";
				//tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($cust_notify) . "', '" . tep_db_input($comments)  . "')");
      }
			$postargs .=  "</deliver-order> ";
      send_google_req($postargs); 

      $postargs = "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">
                   <archive-order xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"". $google_order. "\"/>";
      send_google_req($postargs); 
    }
    
    if(tep_not_null($notify_comments)) {
      $send_mail = "false";
      if($cust_notify == 1) $send_mail = "true";
      $notify_comments = strip_tags($notify_comments);
      $notify_comments = preg_replace('/\s{2,}/',' ',$notify_comments);
      $notify_comments = htmlspecialchars($notify_comments);
      $notify_comments = substr($notify_comments, 0, 254);
      if ( !empty($notify_comments) ) {
        //$notify_comments = preg_replace("/([\x80-\xFF])/e", "chr(0xC0|ord('\\1')>>6).chr(0x80|ord('\\1')&0x3F)", $notify_comments);
        $postargs =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?".">
                   <send-buyer-message xmlns=\"http://checkout.google.com/schema/2\" google-order-number=\"". $google_order. "\">
                   <send-email>" . $send_mail . "</send-email>
                   <message>". $notify_comments . "</message>
                   </send-buyer-message>";    
        send_google_req($postargs);
      }
    }
  }


?>