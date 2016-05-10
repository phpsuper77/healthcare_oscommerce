<?php
/*
  $Id: login.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Checkout');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', 'New Customer');
define('TEXT_CLICK_FOR_LOGIN','Returning Customers click here to log in');
//basket
define('TEXT_CART_HEAD',HEADER_TITLE_CART_CONTENTS);
define('TEXT_CHECKOUT_CART_HEADING','You are purchasing the following items:');
define('TABLE_HEADING_PRODUCTS','Product(s)');
define('TABLE_HEADING_QUANTITY','Qty.');
define('TABLE_HEADING_TOTAL','Item total');
define('SUB_TITLE_SUB_TOTAL','Subtotal:');
//\basket
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('HEADING_RETURNING_CUSTOMER_LOGIN','Returning Customer Login');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer.');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');

// {{
define('HEADING_ORDER_INFORMATION', 'Order Information');
define('HEADING_BILLING_ADDRESS', 'Billing Address');
define('HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TEXT_IF_SHIPPING_IS_SAME_AS_BILLING', 'Shipping Address is the same as the Billing Address %s ');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.\n');
define('JS_COUNTRY', '* The \'Country\' entry must be selected.\n');
define('JS_TELEPHONE', '* The \'Phone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_SHIP_ADDRESS', '* The \'Shipping Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_SHIP_POST_CODE', '* The \'Shipping Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_SHIP_CITY', '* The \'Shipping City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_SHIP_STATE', '* The \'Shipping State\' entry must be selected.\n');
define('JS_SHIP_COUNTRY', '* The \'Shipping Country\' entry must be selected.\n');

define('SHIP_FIRST_NAME_ERROR', 'Shipping First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('SHIP_LAST_NAME_ERROR', 'Shipping Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('SHIP_STREET_ADDRESS_ERROR', 'Shipping Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('SHIP_POST_CODE_ERROR', 'Shipping Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('SHIP_CITY_ERROR', 'Shipping City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('SHIP_STATE_ERROR', 'Shipping State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('SHIP_STATE_ERROR_SELECT', 'Please select a Shipping State from the States pull down menu.');
define('SHIP_COUNTRY_ERROR', 'You must select a Shipping Country from the Countries pull down menu.');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping Method');
define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please select the preferred shipping method to use on this order.');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');

define('TABLE_HEADING_PAYMENT_METHOD', 'Payment Method');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please select the preferred payment method to use on this order.');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');

define('TABLE_HEADING_COMMENTS', 'Add Comments About Your Order');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to confirm this order.');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_TEXT0', 'Thank you for registering with ' . STORE_NAME . "\n\n");
define('EMAIL_LOGIN', 'Your login is: %s' . "\n");
define('EMAIL_PASSWORD', 'Your password is: %s' . "\n\n");
define('EMAIL_TEXT1', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was submitted during registration at ' . HTTP_SERVER.DIR_WS_CATALOG . ' If this email is incorrect, or an error has been made, please accept our apologies and we will immediately correct our database once you contact us.');

define('HEADING_CONTACT_INFO', 'Contact Information');

define('TEXT_NEW_ADDRESS', 'New Address');
define('ENTRY_ADDRESS_BOOK', 'Address Book:');
// }}

define('ENTRY_COMMERCE_IDS', 'CommerceID:');
define('ENTRY_COMMERCE_IDS_ERROR_EXISTS', 'Customer with this CommerceID already exists!');
define('ENTRY_COMMERCE_IDS_ERROR_WRONG', 'Wrong CommerceID!');
define('ENTRY_COMMERCE_IDS_TEXT', '*');

define('ENTRY_USER_KEY', 'User key (if available):');
define('ENTRY_USER_KEY_TEXT', '');

define('HEADING_CONDITIONS_INFORMATION', 'General Terms Of Delivery');
define('TEXT_CONDITIONS_CONFIRM', 'I accept the General Terms Of Delivery');
define('TEXT_CONDITIONS_DOWNLOAD', 'Download General Terms Of Delivery');

define('TEXT_PURCHASE_TOTAL','Total cost: ');
define('TEXT_PURCHASE_SUBTOTAL','Total goods:');
define('TEXT_PURCHASE_SHIPPING','Shipping:');
define('TEXT_PURCHASE_TAX','Value Added Tax (VAT) at 17.5%:');

define('ENTRY_CREATE_ACCOUNT', '<strong>Register?</strong>');
define('TEXT_CREATE_ACCOUNT','Register for quicker ordering next time (temp account otherwise)');
?>
