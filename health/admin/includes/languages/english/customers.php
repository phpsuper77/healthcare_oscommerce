<?php
/*
  $Id: customers.php,v 1.1.1.1 2005/12/03 21:36:04 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Customers');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Number of Reviews:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this customer?');
define('TEXT_DELETE_REVIEWS', 'Delete %s review(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Customer');
define('TYPE_BELOW', 'Type below');
define('PLEASE_SELECT', 'Select One');
// added by Art. Start
define('ENTRY_ALT_TELEPHONE_NUMBER', 'Alt. Telephone Number:');
define('ENTRY_CELL', 'Cell Number:');
define('ENTRY_ALT_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CELL_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_BONUS_POINTS','Bonus points:');
define('ENTRY_CREDIT_AVAIL','Credit available:');
define('TEXT_INFO_OWC_MEMBER','OnlineWorld Club Member: ');
define('ENTRY_OWC_MEMBER','OnlineWorld Club Member:');
define('ENTRY_CUSTOMERS_TYPE','Customers Type:');
define('SEARCH_TITLE_LAST_NAME','Last Name:');
define('SEARCH_TITLE_FIRST_NAME','First Name:');
define('SEARCH_TITLE_COMPANY','Company:');
define('SEARCH_TITLE_TELEPHONE_NUMBER','Telephone Number:');
define('SEARCH_TITLE_CITY','City:');
define('SEARCH_TITLE_STATE','State:');
define('SEARCH_TITLE_POSTAL_CODE','Postal Code:');
define('SEARCH_TITLE_CARD','Last 4-Digit of Credit Card:');
define('BOX_CATALOG_DISCOUNTS','Wholesale Discounts');
// added by Art. Stop
define('CATEGORY_GROUPS', 'Customer groups');
define('ENTRY_GROUP', 'Group:');
define('TABLE_HEADING_EMAIL', 'E-mail');
define('ENTRY_ACTIVE', 'Status:');
define('TEXT_ACTIVE', 'active');
define('TEXT_NOT_ACTIVE', 'inactive');

define('TEXT_SHOW_ALL', 'Show All');
define('TEXT_SHOW_ACTIVE', 'Show active');
define('TEXT_SHOW_INACTIVE', 'Show inactive');
define('TEXT_FILTER', 'Filter:&nbsp;');
define('TEXT_GROUPS', 'Groups:&nbsp;');

define('ENTRY_BUSINESS_COMPANY_ERROR', 'You must provide your VAT-ID.');
define('ENTRY_BUSINESS', 'VAT-ID: ');
define('ENTRY_BUSINESS_ERROR', 'VAT-ID error.'); 
define('ENTRY_VAT_ID_TEXT', '*');
define('ENTRY_VAT_ID_ERROR', 'VAT-ID error.');

define('ENTRY_VAT_EXEMPTION_FORM', 'VAT exemption form sent?');
define('ENTRY_VAT_EXEMPTION_FORM_SENT_YES', 'yes');
define('ENTRY_VAT_EXEMPTION_FORM_SENT_NO', 'no');
define('TEXT_NO_DATE' , '(No date)');
?>