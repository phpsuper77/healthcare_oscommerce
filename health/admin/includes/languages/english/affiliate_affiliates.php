<?php
/*
  $Id: affiliate_affiliates.php,v 1.1.1.1 2005/12/03 21:36:04 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Affiliates');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_USERHOMEPAGE', 'Homepage');
define('TABLE_HEADING_COMMISSION','Commission');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_AFFILIATE_ID','Affiliate ID');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_COMMISSION','Commission');
define('TEXT_INFO_NUMBER_OF_SALES', 'Number of Sales:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_SALES_TOTAL', 'Total Sales:');
define('TEXT_INFO_AFFILIATE_TOTAL', 'Commission:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this affiliate?');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Affiliate');
define('TEXT_DISPLAY_NUMBER_OF_AFFILIATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> affiliates)');

define('ENTRY_AFFILIATE_PAYMENT_DETAILS', 'Payable to:');
define('ENTRY_AFFILIATE_PAYMENT_CHECK','Check Payee Name:');
define('ENTRY_AFFILIATE_PAYMENT_PAYPAL','PayPal Account Email:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_NAME','Bank Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME','Account Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER','Account Number:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER','ABA/BSB number (branch number)');
define('ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE','SWIFT Code ');
define('ENTRY_AFFILIATE_COMPANY','Company');
define('ENTRY_AFFILIATE_COMPANY_TAXID','Tax-Id.:');
define('ENTRY_AFFILIATE_HOMEPAGE','Homepage');
define('ENTRY_AFFILIATE_COMMISSION',' Pay Per Sale Payment % Rate');

define('CATEGORY_COMMISSION','Individual Commission');
define('CATEGORY_PAYMENT_DETAILS','You get your money by:');

define('TYPE_BELOW', 'Type below');
define('PLEASE_SELECT', 'Select One');
define('TABLE_HEADING_STATUS', 'Status');
define('TEXT_STATUS','Status:');
define('TEXT_ACTIVE','Active');
define('TEXT_NOT_ACTIVE','Not Active');
define('ENTRY_STORE_NAME', 'Store name');
define('ENTRY_LOGO', 'Logo file:');
define('ENTRY_STYLESHEET', 'Stylesheet file:');
define('TEXT_REMOVE_LOGO', 'Remove logo.');
define('TEXT_REMOVE_STYLESHEET', 'Remove stylesheet.');
define('AFFILIATE_DATA_DIRECTORY_NOT_CREATED', 'Error creating affiliate home directory.');
define('AFFILIATE_DATA_DIRECTORY_CREATED', 'Success. Affiliate home directory created.');
define('ENTRY_TEMPLATE', 'Template:');
define('TEXT_MANAGE_PAYMENTS', 'Can manage payments?:');
define('TEXT_MANAGE_INFOBOX', 'Can manage infoboxes?:');
define('TEXT_MANAGE_LOGO', 'Can manage logo?:');
define('TEXT_MANAGE_STYLESHEET', 'Can manage stylesheet?:');
define('TEXT_MANAGE_BANNERS', 'Can manage banners?:');
define('ENTRY_OWN_DESCRIPTIONS', 'Can have own descriptions:');

define('TEXT_OWN_PRODUCT_INFO', 'Can set own product info page?:');
define('ENTRY_PRODUCT_INFO_URL', 'Product info page URL:');
define('TEXT_PRODUCT_INFO_URL_HELP', '<small>e.g. http://your.domain.com/your_page.php?contentid={CID}&{SID}<br><b>{CID}</b> and <b>{SID}</b> will be replaced with real value of Product Content ID and Session Name=ID</small>');
define('ENTRY_CONTINUE_SHOPPING_URL', 'Home page URL:<br><small>(Used for Continue Shopping button)</small>');
define('TEXT_CONTINUE_SHOPPING_URL_HELP', '<small>e.g. http://your.domain.com/?{SID}<br><b>{SID}</b> will be replaced with real value of Session Name=Session ID</small>');
define('ENTRY_DIRECTORY_LISTING_URL', 'Directory Listing URL:');
define('TEXT_DIRECTORY_LISTING_URL_HELP', '<small>e.g. http://your.domain.com/listing.php?category={CID}&{SID}<br><b>{CID}</b> and <b>{SID}</b> will be replaced with real value of current category ID and Session Name=ID</small>');

?>