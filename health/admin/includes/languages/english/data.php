<?php
  define('HEADING_TITLE', 'Datafeeds');
  define('TEXT_DATAFEED_NAME', 'Datafeed Name');
  define('TEXT_PRODUCTS_COUNT', 'Products Count');
  define('TEXT_CREATE_DATAFEED', 'Create Datafeed');
  define('TEXT_DATAFEED_DATE', 'Date Created');
  define('TEXT_DATAFEED_SIZE', 'Datafeed Size');
  define('TEXT_DOWNLOAD_DATAFEED', 'Download');
  $datafeed_array = array();
//  $datafeed_array['datafeed_a'] = array('name' => 'Amazon', 'file_name' => 'amazon.txt');
  $datafeed_array['datafeed_a_new'] = array('name' => 'Amazon (new)', 'file_name' => 'amazon_new.txt'); 
  $datafeed_array['datafeed_d'] = array('name' => 'Dealtime', 'file_name' => 'dealtime.csv', 'use_affiliates' => true );
  $datafeed_array['datafeed_e'] = array('name' => 'eDirectory', 'file_name' => 'edirectory.csv');
  $datafeed_array['datafeed_f'] = array('name' => 'Froogle', 'file_name' => 'googlebase.txt', 'use_affiliates' => true );
  $datafeed_array['datafeed_fde'] = array('name' => 'Froogle DE', 'file_name' => 'googlebase_de.txt', 'use_affiliates' => true );
//  $datafeed_array['datafeed_pt'] = array('name' => 'Productfeed trade', 'file_name' => 'productfeed_trade.csv');
  $datafeed_array['datafeed_ptv2'] = array('name' => 'Productfeed trade V2', 'file_name' => 'productfeed_trade_V2.csv');
  $datafeed_array['datafeed_sp'] = array('name' => 'Shopping partners', 'file_name' => 'shoppingpartners.csv');
  $datafeed_array['datafeed_px'] = array('name' => 'Pixmania', 'file_name' => 'pp_stock.csv');
  $datafeed_array['datafeed_pxir'] = array('name' => 'Pixmania Ireland', 'file_name' => 'ppir_stock.csv');
  $datafeed_array['datafeed_pxdk'] = array('name' => 'Pixmania Denmark', 'file_name' => 'ppdk_stock.csv');
  $datafeed_array['datafeed_pxse'] = array('name' => 'Pixmania Sweden', 'file_name' => 'ppse_stock.csv');
  
  $datafeed_array['datafeed_wg_usd'] = array('name' => 'Webgains USD', 'file_name' => 'webgains_usd.csv');
  $datafeed_array['datafeed_wg_eur'] = array('name' => 'Webgains EUR', 'file_name' => 'webgains_eur.csv');
  
  $datafeed_array['datafeed_dad'] = array('name' => 'DAD Inventory', 'file_name' => 'dad_inventory.csv');
  
  $datafeed_array['datafeed_reevoo'] = array('name' => 'Reevoo', 'file_name' => 'reevoo.txt');
  
  $datafeed_array['channelmax_uk'] = array('name' => 'ChannelMAX UK', 'file_name' => 'channelmax_uk.txt');
  $datafeed_array['channelmax_fr'] = array('name' => 'ChannelMAX FR', 'file_name' => 'channelmax_fr.txt');
  $datafeed_array['channelmax_de'] = array('name' => 'ChannelMAX DE', 'file_name' => 'channelmax_de.txt');
  

  define('TEXT_FILE_DOES_NOT_EXISTS', 'File does not exist');
  define('TEXT_CREATE', 'Create datafeed');
  define('TEXT_DOWNLOAD', 'Download');
  define('TEXT_NOT_AVAILABLE', 'not available');
  define('TEXT_CSV_FILE_CREATED', 'CSV file %s successfully created.');
  define('TEXT_CSV_FILE_ERROR', 'CSV file %s was not created.');
  define('TEXT_CSV_FILE_DOWNLOAD_ERROR', 'Can not download CSV file %s.');
  define('DEFAULT_STOCK_TEXT', 'FAST Next Day Courier Delivery');
?>
