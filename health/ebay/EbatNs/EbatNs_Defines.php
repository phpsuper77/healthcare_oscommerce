<?php 
// $Id: EbatNs_Defines.php,v 1.1 2007/05/31 11:38:00 michael Exp $
/*$Log: EbatNs_Defines.php,v $
/*Revision 1.1  2007/05/31 11:38:00  michael
/*- initial checkin
/*- version < 513
/*
 * 
 * 3     3.02.06 10:44 Mcoslar
 * 
 * 2     30.01.06 16:44 Mcoslar
 * �nderungen eingef�gt
*/
// ebay PHP at defines
DEFINE("EBAY_NOTHING", null);
DEFINE("EBAY_REMOVE_ACTION", '__DO_REMOVE_ACTION__');
DEFINE("EBAY_ERR_SUCCESS", "0");
DEFINE("EBAY_ERR_ERROR", "1");
DEFINE("EBAY_ERR_WARNING", "2");
// this are defines for cURL
// a few are not documented, but the names should be
// rather descriptive
if (!defined('CURLOPT_PROXYTYPE')) DEFINE('CURLOPT_PROXYTYPE', 101);
// DEFINE( 'CURLOPT_PROXYUSERPWD', 6 );
// DEFINE( 'CURLOPT_HTTPPROXYTUNNEL', 61 );
DEFINE('CURLAUTH_NONE', 0);
if (!defined('CURLPROXY_HTTP')) DEFINE('CURLPROXY_HTTP', 0);
DEFINE('CURLPROXY_SOCKS4', 4);
if (!defined('CURLPROXY_SOCKS5')) DEFINE('CURLPROXY_SOCKS5', 5);
if (!defined('CURLOPT_BUFFERSIZE')) DEFINE('CURLOPT_BUFFERSIZE', 98);
?>