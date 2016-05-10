<?php
//
// +----------------------------------------------------------------------+
//  osCommerce, Open Source E-Commerce Solutions                          +
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Jason LeBaron                                     |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.gnu.org/copyleft/gpl.html.                                |
// +----------------------------------------------------------------------+
// | includes/modules/payment/protx_direct.php                            |
// | Released under GPL                                                   |
// | Created by Jason LeBaron - jason@networkdad.com                      |
// | Updated by Thomas Hodges-Hoyland - osc@hodges-hoyland.me.uk (v5.1)   |
// +----------------------------------------------------------------------+
// $Id: protx_direct.php 1 2004-10-30 16:00:00Z networkdad $


  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_TITLE', 'Credit/Debit Card'); // Payment option title as displayed in the admin
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_PUBLIC_TITLE', 'Credit/Debit Card');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_DESCRIPTION', '<strong>Test Credit Card Numbers:</strong><br /><br />Visa#: 4929000000006<br />MC#: 5404000000000001<br />Delta#: 4462000000000003<br />Solo#: 6334900000000005 - Issue #: 1<br />Maestro#: 5641820000000005 - Issue #:01<br />AMEX#: 374200000000004 <br /><br />Any future date can be used for the expiration date and any 3 or 4 (AMEX) digit number can be used for the CVV2 Code.<br /><br /><a target="_blank" href="https://live.sagepay.com/mysagepay">My SagePay</a>');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_START_DATE', '<span class="protx_hidden">Credit Card Start Date:</span>');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CVV', 'CVV2 Number');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_CREDIT_CARD_ISSUE_NUMBER', '<span class="protx_hidden">Issue Number (Switch/Maestro/Solo cards):</span>');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_PROTX_DIRECT_TEXT_JS_CC_CVV', '* The 3 or 4 digit CVV number must be entered from the back of the credit card.\n');
  define('TEXT_CVV_WHAT_THIS', "<a href=\"javascript:;\" onClick=\"javascript:{window.open('cvs_help.php','test','scrollbars=yes,height=490,width=440,top=100,left=100');false}\"><small>(what&nbsp;this)</small></a>");
?>