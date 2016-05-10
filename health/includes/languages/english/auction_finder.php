<?php
/*
  $Id: auction_finder.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome to checkout!');
define('TEXT_STEP_BY_STEP', ''); // should be empty

define('HEADING_AUCTION_CUSTOMER', 'Checkout');
define('TEXT_AUCTION_CUSTOMER', 'Welcome to your auction checkout process!' );
define('TEXT_AUCTION_CUSTOMER_INTRODUCTION', 'By using the ' . STORE_NAME . ' checkout, you will be able to shop faster, be up to date on your order status, and track delivery of your order.<br><br>');
define('TEXT_AUCTION_FINDER_ERROR', 'We are unable to find your order.  This may be due to following reasons:<br>1.  We have not yet updated our system with your auction information.<br>2.  You are not using the email address that you registered with at ' . $g_auction_house . '.<br/><br/>Please Note:<br/>It may take up to 2 hours for your auction closing information to be updated in our system.');
define('TEXT_AUCTION_INVALID_ERROR', 'There is a problem with your order.&nbsp;&nbsp;Please email ' . STORE_OWNER_EMAIL_ADDRESS . ' and provide your checkout e-mail address.');

define('ENTRY_AUCTION_EMAIL_ADDRESS', $g_auction_house . ' E-Mail Address:');
define('ENTRY_AUCTION_HOUSE', 'Auction Company:');

?>
