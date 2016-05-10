<?php
/*
  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox

  Released under the GNU General Public License
*/

   $abx = explode('-',!empty($_GET['abx']) ? $_GET['abx'] : '');

  switch(current($abx)) {

    case 'img':

      include(DIR_WS_MODULES . 'auctionblox/includes/images.inc.php');

      break;

    default:

      include(DIR_WS_MODULES . 'auctionblox/includes/modules/catalog/checkout.php');

      break;

  }

?>
