<?php
							
 require('includes/application_top.php');
 require(DIR_WS_CLASSES . 'order.php');
     
 // Settings
 
  $quantity_days = '7'; $mod_debug = false; /*false*/ $mod_test = true; /*false*/ $mod_test_emails = "dsichevsky@holbi.co.uk"; //"dsichevsky@holbi.co.uk"; 
	$show_only_customers = false;
 
 // eof Settings
											
 $query_orders = tep_db_query($query_str = "select * from " . TABLE_ORDERS . " where date_purchased like '" .  date("Y-m-d", mktime(date('H')+8, date('i'), date('s'), date("m")  , date("d")-(int)$quantity_days, date("Y"))) . "%' and orders_status in (3, 8)");
 if($mod_debug==true && $show_only_customers==false)echo '<i>Customers query:</i>: ' . $query_str . '<p>';
                                                           
 $arr_customers_products = array(); // $arr_customers_newsent = array(); 
 
 if(tep_db_num_rows($query_orders))
 {        
  while($row_os = tep_db_fetch_array($query_orders))
  {
      $customers_order_id = $row_os['orders_id'];
      $customers_order_name = $row_os['customers_name'];
      $customers_order_email_address = $row_os['customers_email_address']; $arr_orders_id = array();
   //echo $row_os['orders_id'] . ' ' . $row_os['customers_name'] . ' ' . $row_os['customers_email_address'] . '<br>';
   // Orders products
      $order = new order($row_os['orders_id']);
      $string_product_name = ''; $string_product_links = '';
      
      if($mod_debug==true)
      echo '<p>' . $order->customer['name'] . ' - ' . $customers_order_email_address . '<br>';//  print_r($order->products); echo '<p>';
      
      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      
      $query_products = tep_db_fetch_array(tep_db_query($query_str = "select count(p.products_id) as count_products, p.products_id, p.products_name from " . TABLE_ORDERS_PRODUCTS . " p where p.products_id = '" . (int)$order->products[$i]['id'] . "' and p.order_product_newsletter = '0' group by p.products_id")); //
      
      if($mod_debug==true && $show_only_customers==false)echo '<i>Query orders products:</i> ' . $query_str . '<br>';
      
      if((int)$query_products['count_products'] > 0)
      {                                        
       $query_order_product_newsletter = tep_db_query($query_str = "select op.products_id, o.orders_id from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on o.orders_id = op.orders_id where o.customers_id = '" . (int)$row_os['customers_id'] . "' and op.products_id = '" . $query_products['products_id'] . "' and op.order_product_newsletter = '0'");
			 
			 if($mod_debug==true && $show_only_customers==false)echo '<i>Query customers orders products:</i> ' . $query_str . '<br>';                     
			                  
			 // check exists customer-products
			    $add_product_in_mail = true;
			/*	  for($icp = 0; $icp < count($arr_customers_products); $icp++)
				  {
				   //echo $arr_customers_products[$icp]['product_id'] . '<br>';
				   if(in_array($query_products['products_id'], $arr_customers_products[$row_os['customers_id'][$icp]])){$add_product_in_mail = false; break;}
				  }*/  
				// eof check exists customer-products
			                                                                                    
       if((int)tep_db_num_rows($query_order_product_newsletter)>0 && $add_product_in_mail == true)
       {  
			                                                    
        if($query_products['products_name'] != '' && $query_products['products_id'] > 0)
        {
         //echo $query_products['products_name'] . ' ' .$query_products['products_id'] . ' ' . $add_product_in_mail . '<br>';
         $string_product_name .= $query_products['products_name'] . "\n";
         $string_product_links .= '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $query_products['products_id']) . '">' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $query_products['products_id']) . '</a>' . "\n"; 
         //tep_db_query("update " .TABLE_ORDERS_PRODUCTS . " set order_product_newsletter = '0' where orders_id = '" . (int)$row_os['orders_id'] . "' and products_id = '" . $query_products['products_id'] . "'");
         $arr_customers_products[] = array('customer_id' => $row_os['customers_id'], 'product_id' => $query_products['products_id']);
                                
         if($mod_debug==true)
         echo '<b>Product: ' . $query_products['products_name'] . '</b><br>';
        }
        $row_opn = tep_db_fetch_array($query_order_product_newsletter);
        $arr_orders_id[] = $row_opn['orders_id'];
			 } 
      }
			 //echo $order->products[$i]['name'] . '<br>';
      }
      
				if($string_product_name != '')
        {            
 
         $email_text = 'Share your opinion with thousands of people and win &pound;25!' . "\n\n" .
                       'Hi ' . $customers_order_name . '!' . "\n" . 
                       'We\'d love to know what you think of the products that you recently' . "\n" . 
                       'purchased from us (good or bad):' . "\n\n" .
                       $string_product_name . "\n" .
                       'As well as providing great advice to future customers, spare a minute to' . "\n" .  
                       'write a short review and you\'ll be automatically entered into our' . "\n" .  
                       'monthly competition to win a &pound;25 ' . STORE_NAME . ' Gift Voucher.' . "\n\n" . 
                       'You don\'t need to login - just click the link(s) below and write a review!' . "\n\n" . 
                       $string_product_links . "\n" . 
                       'Best regards, ' . STORE_NAME; 
                       
          //if($mod_debug==true);
          //echo $customers_order_name . '<br>' . ($mod_test==true?$mod_test_emails:$customers_order_email_address) . '<br>' . 'What do you think of your purchase?' . '<br>' . $email_text . '<p>_______________________<p>';
          
          if($mod_debug==false)
          {
            //echo '<h3>' . $customers_order_name . '</h3>' . $email_text . '<p>_______________________________________<p>';
            tep_mail_s($customers_order_name, ($mod_test==true?$mod_test_emails:$customers_order_email_address), 'What do you think of your purchase?', $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            echo 'Customer <b><u>' . ($mod_test==true?$mod_test_emails:$customers_order_email_address) . ' - ' . $customers_order_name . '</u></b> sent...<p>';
					  //if(tep_mail($customers_order_name, $customers_order_email_address, 'What do you think of your purchase?', $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS))
            //$arr_customers_newsent[] = $customers_order_id;
          }
					else
					{
					  echo 'customers e-mail sent...<p>';
					}
					
					if($mod_test==false)
          tep_db_query("update " .TABLE_ORDERS_PRODUCTS . " set order_product_newsletter = '1' where orders_id in ('" . implode("', '", $arr_orders_id) . "')");
        }

   // eof Orders products
  }
 }
 
 
 //if($mod_debug == false)echo 'Newsletter sent.';
 
 
 
  
  function tep_mail_s($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, $attach_file = array()) {
    if (SEND_EMAILS != 'true') return false;
		global $contents;
    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer')); 
    
    if(count($attach_file)>0)$message->add_mixed_part();

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
// {{
      //$email_text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>', $email_text);
                                  
      //$contents = implode('', file('email_template.php'));
        
      $email_text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>', $email_text);

      $contents = tep_get_mail_body();  
               
      $email_subject = str_replace('$', '/$/', $email_subject);
      $email_text = str_replace('$', '/$/', $email_text);
      $search = array ("'##EMAIL_TITLE##'i",
                       "'##EMAIL_TEXT##'i",
					             "'##EMAIL_DATE##'i");
      $replace = array ($email_subject,
                        $email_text,
                       date("d/m/Y"));
      $email_text = str_replace ('/$/', '$', preg_replace ($search, $replace, $contents));
// }}
      $message->add_html($email_text, $text);
      //echo '<pre>'; print_r($message);
    } else {
      $message->add_text($text);
    }
    
    if(count($attach_file)>0)
		{
		 //echo '<pre>'; print_r($attach_file); die;
     $attachments = $message->get_file($attach_file['file']);
     $message->add_attachment($attachments, $attach_file['name'], $attach_file['type']);
		}

    // Send message
		$message->build_message();
    
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  } 
?>