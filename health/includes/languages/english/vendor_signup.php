<?php
/*
  $Id: vendor_signup.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Vendor Program');
define('HEADING_TITLE', 'Vendor Program - Sign Up');

define('MAIL_VENDOR_SUBJECT', 'Welcome to the Vendor Program');
define('MAIL_VENDOR_HEADER', 'Dear Vendor,

thank you for joining the Vendor Program.

Your Account Information:
***********************

');
define('MAIL_VENDOR_ID', 'Your Vendor ID is: ');
define('MAIL_VENDOR_USERNAME', 'Your Vendor Username is: ');
define('MAIL_VENDOR_PASSWORD', 'Your Password is: ');
define('MAIL_VENDOR_LINK', 'Link to your account here:');
define('MAIL_VENDOR_FOOTER', 'Have fun selling goods!

Your Vendor Team');
define('ENTRY_FROM_EMAIL_ADDRESS', 'From e-mail address:');
define('ENTRY_FROM_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_FROM_EMAIL_ADDRESS_CHECK_ERROR', 'Your From E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_FROM_EMAIL_ADDRESS_ERROR', 'Your From E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.'); 
define('ENTRY_VENDOR_STORE_NAME', 'Store name:');
define('ENTRY_VENDOR_STORE_NAME_ERROR', 'Store name error.');
define('ENTRY_VENDOR_STORE_NAME_TEXT', '');

define('BOX_VENDOR_INFO', 'Vendor Information');
define('BOX_VENDOR_SUMMARY', 'Vendor Summary');
define('BOX_VENDOR_ACCOUNT', 'Edit Vendor Account');
define('BOX_VENDOR_CLICKRATE', 'Clickthrough Report');
define('BOX_VENDOR_PAYMENT', 'Payment Report');
define('BOX_VENDOR_SALES', 'Sales Report');
define('BOX_VENDOR_BANNERS', 'Vendor Banners');
define('BOX_VENDOR_CONTACT', 'Contact Us');
define('BOX_VENDOR_FAQ', 'Vendor Program FAQ');
define('BOX_VENDOR_LOGIN', 'Vendor Log In');
define('BOX_VENDOR_LOGOUT', 'Vendor Log Out');

define('ENTRY_VENDOR_PAYMENT_DETAILS', 'Payable to:');
define('ENTRY_VENDOR_ACCEPT_AGB', 'Check here to indicate that you have read and agree to the <a target="_new" href="' . tep_href_link(FILENAME_VENDOR_TERMS, '', 'SSL') . '">Associates Terms & Conditions</a>.');
define('ENTRY_VENDOR_AGB_ERROR', ' &nbsp;<small><font color="#FF0000">You must accept our Associates Terms & Conditions</font></small>');
define('ENTRY_VENDOR_PAYMENT_CHECK', 'Check Payee Name:');
define('ENTRY_VENDOR_PAYMENT_CHECK_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_PAYPAL', 'PayPal Account Email:');
define('ENTRY_VENDOR_PAYMENT_PAYPAL_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_PAYPAL_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME', 'Bank Name:');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_NAME_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME', 'Account Name:');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NAME_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER', 'Account Number:');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER', 'ABA/BSB number (branch number):');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_BRANCH_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE', 'SWIFT Code:');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_TEXT', '');
define('ENTRY_VENDOR_PAYMENT_BANK_SWIFT_CODE_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_COMPANY', 'Company');
define('ENTRY_VENDOR_COMPANY_TEXT', '');
define('ENTRY_VENDOR_COMPANY_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_COMPANY_TAXID', 'VAT-Id.:');
define('ENTRY_VENDOR_COMPANY_TAXID_TEXT', '');
define('ENTRY_VENDOR_COMPANY_TAXID_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_VENDOR_HOMEPAGE', 'Homepage');
define('ENTRY_VENDOR_HOMEPAGE_TEXT', '&nbsp;<small><font color="#AABBDD">required (http://)</font></small>');
define('ENTRY_VENDOR_HOMEPAGE_ERROR', '&nbsp;<small><font color="#FF0000">required (http://)</font></small>');

define('CATEGORY_PAYMENT_DETAILS', 'You get your money by:');
define('TEXT_VENDOR_WARNING','<font color=#ff0000>WARNING!!! Affiliate not Active!</font>'); 
?>