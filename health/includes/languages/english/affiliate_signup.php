<?php
/*
  $Id: affiliate_signup.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Affiliate Program');
define('HEADING_TITLE', 'Affiliate Program - Sign Up');

define('MAIL_AFFILIATE_SUBJECT', 'Welcome to the Affiliate Program');
define('MAIL_AFFILIATE_HEADER', 'Dear Affiliate,

thank you for joining the Affiliate Program.

Your Account Information:
***********************

');
define('MAIL_AFFILIATE_ID', 'Your Affiliate ID is: ');
define('MAIL_AFFILIATE_USERNAME', 'Your Affiliate Username is: ');
define('MAIL_AFFILIATE_PASSWORD', 'Your Password is: ');
define('MAIL_AFFILIATE_LINK', 'Link to your account here:');
define('MAIL_AFFILIATE_FOOTER', 'Have fun earning referal fees!

Your Affiliate Team');
define('ENTRY_FROM_EMAIL_ADDRESS', 'From e-mail address:');
define('ENTRY_FROM_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_FROM_EMAIL_ADDRESS_CHECK_ERROR', 'Your From E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_FROM_EMAIL_ADDRESS_ERROR', 'Your From E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.'); 
define('ENTRY_AFFILIATE_STORE_NAME', 'Store name:');
define('ENTRY_AFFILIATE_STORE_NAME_ERROR', 'Store name error.');
define('ENTRY_AFFILIATE_STORE_NAME_TEXT', '');
?>