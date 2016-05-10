<?php
/*
  $Id: auction_helper.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
  require_once(DIR_WS_FUNCTIONS . 'array_ext.php');
  require_once(DIR_WS_CLASSES . 'net_http_client.php');

  class auctionHelper 
  {
    var $NOT_LISTED = 1;
    var $ACTIVE = 2;
	var $ENDED = 3;
	var $CHECKEDOUT = 4;
	var $COMPLETED = 5;
	
    var $email_address, $auction_house, $contents;

    // constructor
	function auctionHelper() {
//      $this->reset();
    }
	
	function getNumberOfWonAuctions($email_address, $auction_house)
	{
		$this->email_address = $email_address;
		$this->auction_house = $auction_house;

	    $check_auction_query = tep_db_query("select count(*) as total" .
										  	" from " . TABLE_AUCTION_BASKET . " a, " . TABLE_AUCTION_WINNERS . " b, " . TABLE_AUCTION_HOUSES . " c" .
										  	" where b.ext_email_address = '" . tep_db_input($email_address) . "'" .
//										  	" and c.auction_house_label = '" . tep_db_input($auction_house) . "'" .
										  	" and a.listing_status = " . $this->ENDED .
										  	" and a.auction_winner_id = b.auction_winner_id" . 
										  	" and a.auction_house_id = c.auction_house_id");

		$total = tep_db_fetch_array($check_auction_query);
      	return $total['total'];
	}

    function getAuctionListing($auction_listing_id)
	{
		// Update any auctions that have ended
		tep_db_query("update " . TABLE_AUCTION_BASKET . " set listing_status = " . $this->ENDED .
				     " where time_end < now() and listing_status = " . $this->ACTIVE);
	
		$auction_query = tep_db_query("select a.*, b.ext_customer_id, b.ext_email_address" .
									  " from " . TABLE_AUCTION_BASKET . " a, " . TABLE_AUCTION_WINNERS . " b" .
									  " where a.auction_winner_id = b.auction_winner_id" .
									  " and a.auction_basket_id = " . tep_db_prepare_input($auction_listing_id));
 
		$retArray = null;
		if ($auctions = tep_db_fetch_array($auction_query)) {
			$retArray = array(  'ID' => $auction_listing_id,
								'auctionHouseID' => $auctions['auction_house_id'],
								'extID' => $auctions['ext_id'],
								'extTitle' => $auctions['ext_title'],
								'extDesc' => $auctions['ext_desc'],
								'qty' => $auctions['quantity'],
								'priceStart' => $auctions['price_start'],
								'priceEnd' => $auctions['price_end'],
								'insurance' => $auctions['insurance'],
								'insuranceOption' => $auctions['insurance_option'],
								'insurancePerItem' => $auctions['insurance_per_item'],
								'shippingType' => $auctions['shipping_type'],
								'shipping' => $auctions['shipping'],
								'shippingAdditional' => $auctions['shipping_additional'],
								'shippingZip' => $auctions['shipping_zip'],
								'shippingCountry' => $auctions['shipping_country'],
								'tax' => $auctions['sales_tax'],
								'taxPercent' => $auctions['sales_tax_percent'],								
								'timeStart' => $auctions['time_start'],
								'timeEnd' => $auctions['time_end'],
								'statusID' => $auctions['listing_status'],
								'email' => $auctions['ext_email_address'],
								'userID' => $auctions['ext_customer_id'],
								'ordersID' => $auctions['orders_id'],
								'productID' => $auctions['products_id'],
								'workflowStateID' => $auctions['workflow_state'],
								'feedbackStateID' => $auctions['feedback_left']);
		}

		return $retArray;
	}
	
    function getWonAuctionListings($email_address, $auction_house)
	{
		return $this->getAuctionListingsWithStatus($email_address, $auction_house, $this->ENDED);
	}
	
    function getAuctionListingsWithStatus($email_address, $auction_house, $status)
	{
		$this->email_address = $email_address;
//		$this->auction_house = $auction_house;
		
		// Update any auctions that have ended
		tep_db_query("update " . TABLE_AUCTION_BASKET . " set listing_status = " . $this->ENDED .
				     " where time_end < now() and listing_status = " . $this->ACTIVE);

		$auction_query = tep_db_query("select a.auction_basket_id, a.ext_id, a.ext_title, a.ext_desc, a.quantity, a.price_start, a.price_end," .
									  " a.insurance, a.insurance_per_item, a.insurance_option, a.shipping_type, a.shipping, a.shipping_additional, a.sales_tax, a.products_id," .
									  " a.time_start, a.time_end, a.listing_status, a.auction_house_id," .
									  " a.orders_id, b.ext_email_address, c.auction_item_url, d.status_label" .
									  " from " . TABLE_AUCTION_BASKET . " a, " . TABLE_AUCTION_WINNERS . " b, " . TABLE_AUCTION_HOUSES . " c, " . TABLE_AUCTION_SALE_STATUS . " d" .							  
									  " where b.ext_email_address = '" . tep_db_input($email_address) . "'" .
//									  " and c.auction_house_label = '" . tep_db_input($auction_house) . "'" .
									  " and a.listing_status = " . $status .
									  " and a.auction_winner_id = b.auction_winner_id" . 
									  " and a.auction_house_id = c.auction_house_id" .
									  " and a.listing_status = d.status_id" .
									  " order by a.time_end desc");
									  
		while ($auctions = tep_db_fetch_array($auction_query)) {
			$this->contents[] = array(  'id' => $auctions['auction_basket_id'],
										'auction_house_id' => $auctions['auction_house_id'],
										'ext_id' => $auctions['ext_id'],
										'item_url' => sprintf($auctions['auction_item_url'], $auctions['ext_id']),
										'ext_title' => $auctions['ext_title'],
										'ext_desc' => $auctions['ext_desc'],
										'qty' => $auctions['quantity'],
										'price_start' => $auctions['price_start'],
										'price_end' => $auctions['price_end'],
										'insurance' => $auctions['insurance'],
										'insurance_per_item' => $auctions['insurance_per_item'],
										'insurance_option' => $auctions['insurance_option'],
										'shipping_type' => $auctions['shipping_type'],										
										'shipping' => $auctions['shipping'],
										'shipping_additional' => $auctions['shipping_additional'],
										'tax' => $auctions['sales_tax'],
										'time_start' => $auctions['time_start'],
										'time_end' => $auctions['time_end'],
										'status_id' => $status,
										'status' => $auctions['status_label'],
										'email' => $auctions['ext_email_address'],
										'orders_id' => $auctions['orders_id'],
										'product_id' => $auctions['products_id']);
		}
				
		return $this->contents;
	}
	
	function canCheckout($auctions)
	{
/*		foreach($auctions as $key => $auction)
		{
			if($auction['product_id'] === '0')
			{
				return false;
			}
		}
*/		return true;
	}	
	
    function getAllAuctionListingsByPage($page_num = 1, &$auction_split, $status = null)
	{
		// Update any auctions that have ended
		tep_db_query("update " . TABLE_AUCTION_BASKET . " set listing_status = " . $this->ENDED .
				     " where time_end < now() and listing_status = " . $this->ACTIVE);
					 
		$auction_query_raw = "select a.auction_basket_id, a.ext_id, a.ext_title, a.ext_desc, a.quantity, a.price_start, a.price_end," .
							  " a.insurance, a.shipping, a.sales_tax, a.products_id," .
							  " a.time_start, a.time_end, a.listing_status, a.orders_id," .
							  " b.ext_email_address, b.ext_customer_id, c.auction_house_label, c.auction_item_url," .
							  " d.status_label, w.label as workflow_label, f.label as feedback_label," .
							  " w.state_id as workflow_state_id, f.state_id as feedback_state_id " .
							  " from " . TABLE_AUCTION_BASKET . " a, " . TABLE_AUCTION_WINNERS . " b, " . TABLE_AUCTION_HOUSES . " c, " . TABLE_AUCTION_SALE_STATUS . " d, " . TABLE_WORKFLOW_STATE ." w, " . TABLE_FEEDBACK_STATE . " f" .
							  " where a.auction_winner_id = b.auction_winner_id" .
							  " and a.auction_house_id = c.auction_house_id" .
							  " and a.listing_status = d.status_id " .
							  " and a.feedback_left = f.state_id " .
							  " and a.workflow_state = w.state_id";
							  
		if( $status != null)
		{
		  $auction_query_raw = $auction_query_raw . " and a.listing_status = " . $status . " order by a.time_end desc";		
		}
		else
		{
		  $auction_query_raw = $auction_query_raw . " order by a.time_end desc";		
		}
							  						  
    	$auction_split = new splitPageResults($page_num, MAX_DISPLAY_SEARCH_RESULTS, $auction_query_raw, $num_rows);

		$auction_query = tep_db_query($auction_query_raw);

		$retArray = null;
		while ($auctions = tep_db_fetch_array($auction_query)) {
			$retArray[] = array('id' => $auctions['auction_basket_id'],
								'ext_id' => $auctions['ext_id'],
								'ext_title' => $auctions['ext_title'],
								'ext_desc' => $auctions['ext_desc'],
								'qty' => $auctions['quantity'],
								'price_start' => $auctions['price_start'],
								'price_end' => $auctions['price_end'],
								'insurance' => $auctions['insurance'],
								'shipping' => $auctions['shipping'],
								'tax' => $auctions['sales_tax'],
								'time_start' => $auctions['time_start'],
								'time_end' => $auctions['time_end'],
								'email' => $auctions['ext_email_address'],
								'user_id' => $auctions['ext_customer_id'],
								'status' => $auctions['status_label'],
								'status_id' => $auctions['listing_status'],
								'auction_house' => $auctions['auction_house_label'],
								'item_url' => $auctions['auction_item_url'],
								'orders_id' => $auctions['orders_id'],
								'product_id' => $auctions['products_id'],
								'workflow_state_id' => $auctions['workflow_state_id'],
								'workflow_label' => $auctions['workflow_label'],
								'feedback_state_id' => $auctions['feedback_state_id'],
								'feedback_label' => $auctions['feedback_label']);
		}
				
		return $retArray;
	}

	// inserts or returns an existing winner id based on email address
	function getAuctionWinnerID($email_address, $auction_house_id)
	{
		if( $email_address && $email_address != '')
		{
			$auction_query = tep_db_query("select auction_winner_id, ext_email_address from " . TABLE_AUCTION_WINNERS .
										  " where ext_email_address = '" . tep_db_prepare_input($email_address) . "'" .
										  " and auction_house_id = '" . tep_db_prepare_input($auction_house_id) . "'") ;

			if ($auction_winner = tep_db_fetch_array($auction_query)) 
			{
				// we have an existing winner id
				return $auction_winner['auction_winner_id'];
			}
			else
			{
				$auction_winner = array('ext_email_address ' => tep_db_prepare_input($email_address),
										'auction_house_id' => tep_db_prepare_input($auction_house_id));
				tep_db_perform(TABLE_AUCTION_WINNERS, $auction_winner);
				return tep_db_insert_id();
			}
		}
		return null;
	}
	
	function getProductIDFromNLQ($auction_title, $auction_house_id)
	{
		// make sure NLQ is support in this version of mySQL
//		$query_raw = "show variables like 'version'"; 
//		$query = tep_db_query($query_raw);
//		$query_array = tep_db_fetch_array($query);
//		$version = $query_array['Value'];

		// strip out all non-numerics except "."		
//		$version = ereg_replace("[^0-9\.]+","", $version); 
		
		//Version must be >= 3.23.23 to support NLQ

		$products_id = 0;

		// try to do a natural language query
		$query_raw = "SELECT products_id, MATCH (products_name) AGAINST ('" . tep_db_input($auction_title) . "') FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE MATCH (products_name) AGAINST ('" . tep_db_input($auction_title) . "') LIMIT 1";
		$query = tep_db_query($query_raw);
		if( ($query_array = tep_db_fetch_array($query)) != false )
		{
			$products_id = $query_array['products_id'];
		}
		
		return $products_id;
	}

	function getProductID($auction_title, $auction_house_id)	
	{
		// natural language query
		$products = array();
		$products_query_raw = "SELECT products_id FROM " . TABLE_AUCTION_PRODUCT_MATCHER . " WHERE auction_house_id = $auction_house_id AND ext_title LIKE '%" . tep_db_input($auction_title) . "%'";
	
		$products_query = tep_db_query($products_query_raw);

		$product = tep_db_fetch_array($products_query);
		$products_id = $product['products_id'];
		
		return $products_id;
	}	
	
	function insertProductMapping($auction_house_id, $products_id, $auction_title, $approved)
	{
		$query = tep_db_query("SELECT products_id FROM " . TABLE_AUCTION_PRODUCT_MATCHER . " WHERE auction_house_id = $auction_house_id AND ext_title = '" . tep_db_input($auction_title) . "'");
		if( tep_db_num_rows($query) == 0 )
		{			
			$dbProduct = array( 'auction_house_id' => $auction_house_id,
								'products_id' => $products_id,
								'ext_title' => tep_db_prepare_input($auction_title),
								'is_approved' => $approved);

			tep_db_perform(TABLE_AUCTION_PRODUCT_MATCHER, $dbProduct);
		}
	}	
	
	function insertOrUpdateWinner($winner)
	{
		if( $winner['email'] && $winner['email'] != '')
		{
			$winners_query = tep_db_query("select auction_winner_id from " . TABLE_AUCTION_WINNERS .
										  " where ext_email_address = '" . tep_db_input($winner['email']) . "'" .
										  " and auction_house_id = '" . tep_db_input($winner['auctionHouseID']) . "'") ;

			if ($dbWinner = tep_db_fetch_array($winners_query)) 
			{
				$winnerID = $dbWinner['auction_winner_id'];
				// we have an existing winner id
				$dbWinner = array('ext_customer_id' => tep_db_prepare_input($winner['userID']),
								  'ext_rating' => tep_db_prepare_input($winner['userRating']),
								  'ext_enabled' => tep_db_prepare_input($winner['userEnabled']),
								  'ext_email_address' => tep_db_prepare_input($winner['email']));
				// remove any nulls
				$dbWinner = array_prune($dbWinner);
														
				tep_db_perform(TABLE_AUCTION_WINNERS, $dbWinner, 'update', 'auction_winner_id = ' . $winnerID);
				return $winnerID;
			}
			else
			{
				$dbWinner = array('ext_email_address ' => tep_db_prepare_input($winner['email']),
								  'ext_customer_id' => tep_db_prepare_input($winner['userID']),
								  'ext_rating' => tep_db_prepare_input($winner['userRating']),
								  'ext_enabled' => tep_db_prepare_input($winner['userEnabled']),
								  'auction_house_id' => tep_db_prepare_input($winner['auctionHouseID']));
				// remove any nulls
				$dbWinner = array_prune($dbWinner);
										
				tep_db_perform(TABLE_AUCTION_WINNERS, $dbWinner);
				return tep_db_insert_id();
			}
		}
		return null;
	}

	function exists($winner, $product)
	{
//debugbreak();	
		$winnerID = $this->insertOrUpdateWinner($winner);
		$auctionHouseID = $product['auctionHouseID'];
		$extID = $product['extID'];
		$extKey2 = $product['extKey2'];
		
		$query =  tep_db_query("SELECT auction_basket_id FROM " . TABLE_AUCTION_BASKET . " WHERE auction_winner_id = $winnerID AND ext_id = '$extID' AND ext_key2 = '$extKey2' AND auction_house_id = $auctionHouseID");
		if( tep_db_num_rows($query) > 0 )
			return true;
		else
			return false;
	}

	function insert($winner, $product)
	{
		$winnerID = $this->insertOrUpdateWinner($winner);
		
		$dbProduct = array( 'auction_house_id' => $product['auctionHouseID'],
							'site' => $product['site'],
							'listing_type' => $product['listingType'],
							'ext_id' => $product['extID'],
							'ext_key2' => $product['extKey2'],
							'ext_title' => $product['extTitle'],
							'ext_desc' => $product['extDesc'],
							'time_start' => $product['timeStart'],
							'time_end' => $product['timeEnd'],
							'listing_status' => $product['statusID'],
							'auction_winner_id' => $winnerID,
							'quantity' => $product['qty'],
							'currency' => $product['currency'],
							'price_start' => $product['priceStart'],
							'price_end' => $product['priceEnd'],
							'insurance' => $product['insurance'],
							'insurance_option' => $product['insuranceOption'],
							'insurance_per_item' => $product['insurancePerItem'],
							'shipping_type' => $product['shippingType'],
							'shipping' => $product['shipping'],
							'shipping_additional' => $product['shippingAdditional'],
							'shipping_zip' => $product['shippingZip'],
							'shipping_country' => $product['shippingCountry'],
							'sales_tax' => $product['tax'],
							'sales_tax_percent' => $product['taxPercent'],
							'orders_id' => $product['ordersID'],
							'products_id' => $product['productID']);

		// remove any nulls
		$dbProduct = array_prune($dbProduct);
								
		$auction_query = tep_db_perform(TABLE_AUCTION_BASKET, $dbProduct);
		$id = tep_db_insert_id();
		if( $product['notify'] == 'true')
		{
			$this->sendInvoice($id);
		}
		
		return $auction_query;
	}
	
	function update($winner, $product)
	{
		$winnerID = $this->insertOrUpdateWinner($winner);
		
		$dbProduct = array( 'auction_house_id' => $product['auctionHouseID'],
							'site' => $product['site'],
							'listing_type' => $product['listingType'],
							'ext_id' => $product['extID'],
							'ext_key2' => $product['extKey2'],
							'ext_title' => $product['extTitle'],
							'ext_desc' => $product['extDesc'],
							'time_start' => $product['timeStart'],
							'time_end' => $product['timeEnd'],
							'listing_status' => $product['statusID'],
							'auction_winner_id' => $winnerID,
							'quantity' => $product['qty'],
							'currency' => $product['currency'],
							'price_start' => $product['priceStart'],
							'price_end' => $product['priceEnd'],
							'insurance' => $product['insurance'],
							'insurance_option' => $product['insuranceOption'],
							'insurance_per_item' => $product['insurancePerItem'],
							'shipping_type' => $product['shippingType'],
							'shipping' => $product['shipping'],
							'shipping_additional' => $product['shippingAdditional'],
							'shipping_zip' => $product['shippingZip'],
							'shipping_country' => $product['shippingCountry'],
							'sales_tax' => $product['tax'],
							'sales_tax_percent' => $product['taxPercent'],
							'orders_id' => $product['ordersID'],
							'products_id' => $product['productID']);
					
		// remove any nulls
		$dbProduct = array_prune($dbProduct);

		$auction_query = tep_db_perform(TABLE_AUCTION_BASKET, $dbProduct, 'update', 'auction_basket_id = '. $product['ID']);

		if( $product['notify'] == 'true')
		{
			$this->sendInvoice($product['ID']);
		}
		
		return $auction_query;
	}

	function sendInvoice($auction_basket_id)
	{
		$auction = $this->getAuctionListing($auction_basket_id);

	    $email_subject = $this->replaceEmailTokens(INVOICE_EMAIL_SUBJECT, $auction);
	    $email_body = $this->replaceEmailTokens(INVOICE_EMAIL_BODY, $auction);

	    tep_mail($auction['email'], $auction['email'], $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		if( strlen(AUCTION_EMAIL_ADDRESS) > 0 )
			tep_mail(AUCTION_EMAIL_ADDRESS, AUCTION_EMAIL_ADDRESS, $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		$this->setNextWorkflowState($auction_basket_id, 'INVOICE');
	}
	
	function sendReminder($auction_basket_id)
	{
		$auction = $this->getAuctionListing($auction_basket_id);

	    $email_subject = $this->replaceEmailTokens(REMINDER_EMAIL_SUBJECT, $auction);
	    $email_body = $this->replaceEmailTokens(REMINDER_EMAIL_BODY, $auction);

	    tep_mail($auction['email'], $auction['email'], $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		if( strlen(AUCTION_EMAIL_ADDRESS) > 0 )
			tep_mail(AUCTION_EMAIL_ADDRESS, AUCTION_EMAIL_ADDRESS, $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		$this->setNextWorkflowState($auction_basket_id, 'REMINDER');
	}		
	
	function sendWarning($auction_basket_id)
	{
		$auction = $this->getAuctionListing($auction_basket_id);

	    $email_subject = $this->replaceEmailTokens(WARNING_EMAIL_SUBJECT, $auction);
	    $email_body = $this->replaceEmailTokens(WARNING_EMAIL_BODY, $auction);

	    tep_mail($auction['email'], $auction['email'], $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		if( strlen(AUCTION_EMAIL_ADDRESS) > 0 )
			tep_mail(AUCTION_EMAIL_ADDRESS, AUCTION_EMAIL_ADDRESS, $email_subject, nl2br($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

		$this->setNextWorkflowState($auction_basket_id, 'WARNING');
	}		
	
	
	function getNextWorkflowState($currentState)
	{
		$query = tep_db_query("SELECT next_state_id FROM " . TABLE_WORKFLOW_STATE . " WHERE state_id = '" . $currentState . "'");
		$state = tep_db_fetch_array($query);
		return $state['next_state_id'];
	}
	
	function setNextWorkflowState($oID, $currentState)
	{
		$nextState = $this->getNextWorkflowState($currentState);
		tep_db_query("UPDATE " . TABLE_AUCTION_BASKET . " SET workflow_state = '$nextState' WHERE auction_basket_id = $oID");
	}		
		
	
	function replaceEmailTokens($str, $auction)
	{
		$str = str_replace('%AUCTION_EMAIL%', $auction['email'], $str);
		$str = str_replace('%AUCTION_ID%', $auction['extID'], $str);
		$str = str_replace('%AUCTION_TITLE%', $auction['extTitle'], $str);
		$str = str_replace('%AUCTION_CHECKOUT_LINK%', AUCTIONBLOX_CHECKOUT_LINK, $str);
		$str = str_replace('%STORE_NAME%', STORE_NAME, $str);
		$str = str_replace('%STORE_OWNER_EMAIL_ADDRESS%', STORE_OWNER_EMAIL_ADDRESS, $str);
		return $str;
	}	
	
/*	function email($subjectTemplate, $bodyTemplate, $email, $auctionHouseLabel, $auctionID, $auctionTitle)
	{
		$subject = str_replace('%email%', $email, $subjectTemplate);
		$subject = str_replace('%auctionHouse%', $auctionHouseLabel, $subjectTemplate);
		$subject = str_replace('%auctionID%', $auctionID, $subjectTemplate);
		$subject = str_replace('%auctionTitle%', $auctionTitle, $subjectTemplate);
				
		$body = str_replace('%email%', $email, $bodyTemplate);
		$body = str_replace('%auctionHouse%', $auctionHouseLabel, $bodyTemplate);
		$body = str_replace('%auctionID%', $auctionID, $bodyTemplate);
		$body = str_replace('%auctionTitle%', $auctionTitle, $bodyTemplate);
		
		tep_mail($winner['email'], $winner['email'], $email_subject, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	}
*/	

	
	function remove($auction_basket_id)
	{
		return tep_db_query("delete from " . TABLE_AUCTION_BASKET . " where auction_basket_id = " . tep_db_prepare_input($auction_basket_id));
	}	
	
	function updateStatusByOrder($status, $order_id)
	{
	   tep_db_query("update " . TABLE_AUCTION_BASKET . " set listing_status = " . $status . " where orders_id = " . $order_id);
	   return;
	}	

	function updateStatusByOID($auction_basket_id, $status)
	{
	   tep_db_query("update " . TABLE_AUCTION_BASKET . " set listing_status = " . $status . " where auction_basket_id = " . $auction_basket_id);
	   return;
	}	
		
	function isOrderAnonymous($order_id)
	{
		$query = tep_db_query("select c.customers_password from " . TABLE_ORDERS ." o, " . TABLE_CUSTOMERS . " c where o.customers_id = c.customers_id and o.orders_id = " . $order_id );
		$customer = tep_db_fetch_array($query);

		if( tep_validate_password(PWA_PASSWORD, $customer['customers_password']))
			return true;
		else
			return false;
	}	
	
	function getAPIVersions()
	{
		return array(0=>array('id' => '1.0', 'text' => '1.0'));
	}
	
	function getAPIActions()
	{
		return array(0=>array('id' => 'insert', 'text' => 'Insert'), 1=>array('id' => 'update', 'text' => 'Update'), 2=>array('id' => 'version', 'text' => 'Version'));
	}
	
	function getInsuranceOptions()
	{
		return array(0=>array('id' => '0', 'text' => 'Not Offered'), 1=>array('id' => '1', 'text' => 'Optional'), 2=>array('id' => '2', 'text' => 'Required'), 3=>array('id' => '3', 'text' => 'Included in S&H'));
	}
	
	function getShippingTypes()	
	{
		return array(0=>array('id' => '1', 'text' => 'Fixed shipping charges'), 1=>array('id' => '2', 'text' => 'Store shipping modules'));
	}
	
	function getListingStatusText($status_id)
	{
		if($status_id == null || strlen($status_id) == 0)
		{
			return "All";
		}
		
		//TODO: +++ Add language ID 
		$value_array = array();
		$query_raw = "SELECT status_label FROM " . TABLE_AUCTION_SALE_STATUS . " WHERE status_id = " . tep_db_input($status_id);
	
		$query = tep_db_query($query_raw);
		$value_array = tep_db_fetch_array($query);
		
		return $value_array['status_label'];
	}

	// returns in format of array[item0..n] = array(oID,ext_id,user_id)
	function createAffectedItemsArrayEx($postvars)
	{
	  $affectedItems = array();
	  $counter = 0;
	  foreach ($postvars as $key => $var) 
	  {

    	 $str = $key;
		 $str = strtok($str, "-");
    	 if($str == 'oID')
		 {
		 	 $counter++;

		     $affectedItems["item" . $counter] = array();
		     $affectedItems["item" . $counter]['oID'] = strtok("-");
		     $affectedItems["item" . $counter]['extID'] = strtok("-");
		     $affectedItems["item" . $counter]['userID'] = strtok("-");
		 }
	  }  	
	  return $affectedItems;
	}
	
	function createAffectedItemsArrayofOID($postvars)
	{

	  $affectedItems = array();
      $counter = 0;
	  foreach ($postvars as $key => $var) 
	  {
    	 $str = $key;
		 $str = strtok($str, "-");
    	 if($str == 'oID')
		 {
		     $counter++;
			 $affectedItems["item" . $counter] = strtok("-");
		 }
	  }  				
	  return $affectedItems;
	}
	
	function createQueryStringOfOID($location, $postvars)
	{
	  $qs = "";
	  $counter = 0;
	  
	  foreach ($postvars as $key => $var) 
	  {
    	 $str = $key;
		 $str = strtok($str, "-");
    	 if($str == 'oID')
		 {
		     $qs .= "oID" . $counter . "=" . strtok("-") . "&";
			 $counter++;
		 }
	  }  				
	  return $location . $qs;
	}	
	
	function leaveFeedback($feedbackType, $feedbackMessage, $affectedItems)
	{
		strtok(AUCTIONBLOX_API_URL, ":"); // remove http:
		//strtok("/"); // "

		$host = strtok(":");
		$port = strtok("/");
		$page = '/' . strtok("?");

		$host = strtok($host, "/");	// remove "//"

		$http = new Net_HTTP_Client();
//		$http->setProtocolVersion(1.1);	// 1.0 generally not supported
  		$http->addHeader("host", $host . ':' . $port);
  		$http->addHeader("accept", "*/*");		
		$http->Connect($host, $port);
		
		$affectedItems['version'] = '1.0';
		$affectedItems['perform'] = 'Feedback';
		$affectedItems['email'] = AUCTIONBLOX_USERID;
		$affectedItems['passcode'] = AUCTIONBLOX_PASSCODE;
		$affectedItems['feedbackType'] = $feedbackType;
		$affectedItems['feedbackComment'] = $feedbackMessage;
		
		$status = $http->Post($page, $affectedItems);
		$retValue;
		if ($status != 200) 
		{
			$retValue = "Leave Feedback Failed: " . $http->getStatusMessage();
		}
		else 
		{
		    $retValue = $http->getBody();
		}

		if( strtok($retValue, ".") === 'success')
		{
			foreach ($affectedItems as $key => $str) 
			{
				if(substr($key, 0, 4) === 'item')
				{
			 		$oID = strtok($str, ",");			
					tep_db_query("UPDATE " . TABLE_AUCTION_BASKET . " SET feedback_left = 'FEEDBACK_LEFT' WHERE auction_basket_id = $oID");
				}
			}  			
		}
		
		$http->Disconnect();
		return $retValue;
	}
  }
?>
