<?php
/*
  $Id: checkout_payment.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Payment Method');

define('HEADING_TITLE', 'Payment Information');

define('TABLE_HEADING_BILLING_ADDRESS', 'Billing Address');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');
define('TITLE_BILLING_ADDRESS', 'Billing Address:');

define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please select the preferred payment method to use on this order.');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Currently this is no payment method available to use on this order.');

define('TABLE_HEADING_COMMENTS', 'Add Comments About Your Order');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to confirm this order.');
define('HEADING_CONDITIONS_INFORMATION', 'Allgemeine Gesch&auml;fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_CONFIRM', 'Ich akzeptiere Ihre Allgemeinen Gesch&auml;fts- und Lieferbedingungen');
define('TEXT_CONDITIONS_DOWNLOAD', 'AGB\'s herunterladen');
define('TEXT_ERROR_CHECKOUT', 'You cann\'t process to checkout due to some limitations. Please <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">contact</a> store owner for more information.');
?>
