<?php
/*
  $Id: auction_listing.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
  require_once(DIR_WS_FUNCTIONS . 'array_ext.php');

  class auctionListing 
  {
    var $ACTIVE = 'A';
	var $ENDED = 'E';
    var $PENDING = 'P';
  
    var $ID;
    var $auctionHouseID;
	var $extID;
	var	$extTitle;
	var	$isBuyItNow;
	var $timeStart;
	var	$timeEnd;
	var $qty;
	var	$qtySold;
	var $currency;
	var	$priceEnd;
	var $priceStart;
	var $lastDownloaded;
	var $fee;
	var $site;
	var $type;
	var $uuid;
	var $status;
	var $version;
	
    // constructor
	function auctionListing() 
	{
    }
	
	function exists()
	{
		if(isset($this->extID) && strlen($this->extID) > 0)
		{
			//OK, this is listing that is on eBay.  Use the listing ID to lookup
			//+++TODO: REMOVE AFTER WE GO LIVE FOR A WEEK OR TWO
			$query_string = "SELECT auction_item_id FROM " . TABLE_AUCTION_ITEMS . " WHERE ext_id = '$this->extID' and auction_house_id = $this->auctionHouseID LIMIT 1";
		}
		else
		{
			$query_string = "SELECT auction_item_id FROM " . TABLE_AUCTION_ITEMS . " WHERE uuid = '$this->uuid' and auction_house_id = $this->auctionHouseID LIMIT 1";
		}
				
		$query =  tep_db_query($query_string);
		if( tep_db_num_rows($query) > 0 )
			return true;
		else
			return false;
	}
	
	function buildSQLArray()
	{
		$dbProduct = array( 'auction_item_id' => $this->ID,
							'auction_house_id' => $this->auctionHouseID,
							'ext_id' => $this->extID,
							'ext_title' => $this->extTitle,
							'listing_type' => $this->type,
							'site' => $this->site,
							'ext_desc' => $this->extDesc,
							'is_buy_it_now' => $this->isBuyItNow,
							'start_time' => $this->timeStart,
							'end_time' => $this->timeEnd,
							'quantity' => $this->qty,
							'quantity_sold' => $this->qtySold,
							'currency' => $this->currency,
							'start_price' => $this->priceStart,
							'end_price' => $this->priceEnd,
							'listing_fee' => $this->fee,
							'uuid' => $this->uuid,
							'status' => $this->status,
							'version' => $this->version,
							'last_downloaded' => $this->lastDownloaded);

		// remove any nulls
		return array_prune($dbProduct);
	}

	function fromSQLArray($sql_array)
	{
		$listing = new auctionListing();
		
		$listing->ID = $sql_array['auction_item_id'];
		$listing->auctionHouseID = $sql_array['auction_house_id'];
		$listing->site = $sql_array['site'];
		$listing->type = $sql_array['listing_type'];
		$listing->extID = $sql_array['ext_id'];
		$listing->extTitle = $sql_array['ext_title'];
		$listing->extDesc = $sql_array['ext_desc'];
		$listing->isBuyItNow = $sql_array['is_buy_it_now'];
		$listing->timeStart = $sql_array['start_time'];
		$listing->timeEnd = $sql_array['end_time'];
		$listing->qty = $sql_array['quantity'];
		$listing->qtySold = $sql_array['quantity_sold'];
		$listing->currency = $sql_array['currency'];
		$listing->priceStart = $sql_array['start_price'];
		$listing->priceEnd = $sql_array['end_price'];
		$listing->fee = $sql_array['listing_fee'];
		$listing->uuid = $sql_array['uuid'];
		$listing->status = $sql_array['status'];
		$listing->version = $sql_array['version'];
		$listing->lastDownloaded = $sql_array['last_downloaded'];

		return $listing;
	}

	function insert()
	{
		$dbProduct = $this->buildSQLArray();
		$auction_query = tep_db_perform(TABLE_AUCTION_ITEMS, $dbProduct);
		
		$this->ID = tep_db_insert_id();
		return $this->ID;
	}
	
	function update()
	{
		$dbProduct = $this->buildSQLArray();
		if(isset($this->extID) && strlen($this->extID) > 0)
		{
			// old listing type may not have UUID.  let's use this
			// and remove temporarily
			//+++TODO: REMOVE AFTER WE GO LIVE FOR A WEEK OR TWO
			$auction_query = tep_db_perform(TABLE_AUCTION_ITEMS, $dbProduct, 'update', "auction_house_id = $this->auctionHouseID AND ext_id = '$this->extID' LIMIT 1");
		}
		else
		{
			$auction_query = tep_db_perform(TABLE_AUCTION_ITEMS, $dbProduct, 'update', "auction_house_id = $this->auctionHouseID AND uuid = '$this->uuid' LIMIT 1");
		}
			
		return $this->ID;
	}
	
	function updateByID()
	{
		$dbProduct = $this->buildSQLArray();
		$auction_query = tep_db_perform(TABLE_AUCTION_ITEMS, $dbProduct, 'update', "auction_item_id = $this->ID LIMIT 1");
		return $this->ID;
	}
  }
?>
