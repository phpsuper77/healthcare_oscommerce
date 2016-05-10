<?php
/*
  $Id: contact_us.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'VAT Declaration Form');
define('NAVBAR_TITLE', 'VAT Declaration Form');
define('TEXT_SUCCESS', 'Your VAT Declaration Form has been successfully sent to the Store Owner.');
define('EMAIL_SUBJECT', 'VAT Declaration Form');

define('ENTRY_NAME', 'Name :');
define('ENTRY_NAME_ERROR', 'Please enter your Name');
define('ENTRY_ADDRESS', 'Address :');
define('ENTRY_ADDRESS_ERROR', 'Please enter your Address');
define('ENTRY_EMAIL', 'Email address :');
define('ENTRY_EMAIL_ERROR', 'Please enter your Email address'); 
define('ENTRY_PHONE', 'Telephone number :');
define('ENTRY_PHONE_ERROR', 'Please enter your Telephone number');
define('ENTRY_TICK_ERROR', 'You need to agree with statements');
define('ENTRY_TICK_BOXES_HEAD', '<b>You need to agree to the following 3 statements and tick each box to qualify for the VAT refund : </b>');
define('ENTRY_TICK_1', 'I am chronically unwell or am affected by a disabling condition by reason of (please describe your condition below) :');
define('ENTRY_DESCRIBE_STATEMENTS_ERROR', 'Please describe your condition');
define('ENTRY_TICK_2_S', 'I am receiving eligible medical goods from %s');

define('ENTRY_TICK_3', 'I declare that the goods supplied are to be used for my own personal use.');
define('ENTRY_SIGNED', 'Signed :');
define('ENTRY_SIGNED_ERROR', 'Please enter Signed');

define('ENTRY_SECONDARY', 'If the individual is a child or unable to sign the declaration on account of their disability/illness, then a third party may sign above and then complete their details below.');
define('ENTRY_SECONDARY_NAME', 'Name :');
define('ENTRY_SECONDARY_ADDRESS', 'Address :');
define('ENTRY_SECONDARY_RELATIONSHIP', 'Relationship to the named person above :');

define('REQ_MARK', '&nbsp;<font style="color:#EC1D24;vertical-align:top;">*</font>');
define('REQ_FIELDS_WARN', 'Required Fields are marked with an asterisk '.REQ_MARK.' The remaining fields are optional.');
define('PDF_TEXT_DATE', 'Date : ');
?>