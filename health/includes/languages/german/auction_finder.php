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
define('NAVBAR_TITLE', 'Anmelden');
define('HEADING_TITLE', 'Willkommen zur Kasse!');
define('TEXT_STEP_BY_STEP', ''); // should be empty

define('HEADING_AUCTION_CUSTOMER', 'Kasse');
define('TEXT_AUCTION_CUSTOMER', 'Willkommen zu Ihrem Auktionscheckout!' );
define('TEXT_AUCTION_CUSTOMER_INTRODUCTION', 'Indem Sie das ' . STORE_NAME . ' Checkout verwenden, zu Ihrer Verf&uuml;gung steht die M&ouml;glichkeit, Eink&auml;ufe schneller zu machen, eine &Uuml;bersicht &uuml;ber Ihren Bestellstatus und eine Kontrolle &uuml;ber Ihre Lieferung Ihrer Bestellung zu haben.<br><br>');
define('TEXT_AUCTION_FINDER_ERROR', 'Ihre Bestellung ist nicht zu finden. Hier sind einige Gr&uuml;nde:<br>1. Wir haben unser System mit Ihrer Auktionsinformation immer noch nicgt updatet.<br>2. Sie verwenden die nicht E-Mail-Adresse, die von Ihnen bei der Anmeldung  ' . $g_auction_house . 'eingegeben worden ist.<br/><br/>Anmerkung:<br/>Das Update in unserem System kann gegen 2 Stunden  in Anspruch nehmen.');
define('TEXT_AUCTION_INVALID_ERROR', 'Es gibt ein Problem mit Ihrer Bestellung.&nbsp;&nbsp;Bitte mailen Sie ' . STORE_OWNER_EMAIL_ADDRESS . ' und teilen Sie Ihre Checkout-E-mail-Adresse mit.');

define('ENTRY_AUCTION_EMAIL_ADDRESS', $g_auction_house . ' E-Mail-Adresse:');
define('ENTRY_AUCTION_HOUSE', 'Auktionskompanie:');

?>
