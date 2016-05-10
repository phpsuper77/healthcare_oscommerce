<?php
/*
  $Id: formatter.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $
 
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
 
  Copyright (c) 2003 osCommerce
  
  This file is part of the eBay Auction Manager for osCommerce
  http://www.auctionblox.com/oscommerce
 
  Copyright (c) 2003, AuctionBlox.com  
 
  Released under the GNU General Public License
*/
// Output a raw date string in the sql date format
// $raw_date needs to be in this format: MM/DD/YYYY HH:MM:SS
// return format: YYYY-MM-DD HH:MM:SS

  function gmt_date_to_local_timestamp($utctime, $timezone = STORE_TIME_ZONE)
  {
    if ( ($utctime == '0000-00-00 00:00:00') || ($utctime == '') ) return false;
	
  	$time = strtotime($utctime);
	$time = gmt_timestamp_to_local_timestamp($time, $timezone);
	
	return $time;
  }
  
  function gmt_timestamp_to_local_timestamp($timestamp, $timezone = STORE_TIME_ZONE)
  {
	$timestamp += 60 * 60 * doubleval($timezone);
	return $timestamp;
  }

  function local_date_to_gmt_sql_format($date)
  {
  	$timestamp = local_date_to_gmt_timestamp($date);
	return sql_date_format($timestamp);  
  }

  function local_date_to_gmt_date_short($date)
  {
  	$timestamp = local_date_to_gmt_timestamp($date);
	return short_date_format($timestamp);  
  }
      
  function local_date_to_gmt_timestamp($date, $timezone = STORE_TIME_ZONE)
  {
    if ( ($date == '0000-00-00 00:00:00') || ($date == '') ) return false;
	
  	$time = strtotime($date);
	$time = local_timestamp_to_gmt_timestamp($time, $timezone);
	
	return $time;
  }
  
  function local_timestamp_to_gmt_date_short($timestamp)
  {
  	$timestamp = local_timestamp_to_gmt_timestamp($timestamp);
	return short_date_format($timestamp);  
  }  
  

  function local_timestamp_to_gmt_timestamp($timestamp, $timezone = STORE_TIME_ZONE)
  {
	$timestamp -= 60 * 60 * doubleval($timezone);
	return $timestamp;
  }
  
  function date_to_sql_date_format($date)
  {
  	$time = strtotime($date);
	return sql_date_format($time);
  }
  
  function sql_date_format($timestamp)
  {
  	 return strftime("%Y/%m/%d %H:%M:%S" , $timestamp);
  }
  
  // ex: 07/11/04 03:45 pm
  function simple_date_format($timestamp)
  {
  	 return strftime("%m/%d/%y %I:%M %p" , $timestamp);
  }
  
  function short_date_format($timestamp)
  {
  	 return strftime(DATE_TIME_FORMAT , $timestamp);
  }
  
  function gmt_date_to_local_pretty_format($utctime)
  {
  	$timestamp = gmt_date_to_local_timestamp($utctime);
	return simple_date_format($timestamp);
  }

  function gmt_date_to_local_short($utctime)
  {
  	$timestamp = gmt_date_to_local_timestamp($utctime);
	return short_date_format($timestamp);  
  }
  
/*  
  // lenient rules and can include timezone offset
  // ex: 11/31/2003 16:42:56-0000
  function osc_gmt_to_sql_format($raw_datetime) 
  {
  	$timestamp = strtotime($raw_datetime);
	return strftime("%Y/%m/%d %H:%M:%S" , $timestamp);
  }
  
  function tep_date_and_time_to_sql_format($raw_date, $raw_time)
  {
	$date = strtotime($raw_time);
	return strftime("%Y/%m/%d" , $date) . ' ' .  $raw_time;
  }
*/
?>