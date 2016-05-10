<?php
  switch(current($abx)) {

    case 'img':

      include(DIR_WS_MODULES . 'auctionblox/includes/images.inc.php');

      break;

    default:

      include(DIR_WS_MODULES . 'auctionblox/includes/modules/catalog/checkout.php');

      break;

  }
?>