<?php
/*
  $Id: database_tables.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_META_TAGS','meta_tags');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');
  define('TABLE_PAYPALIPN_TXN', 'paypalipn_txn'); // PAYPALIPN

// Added for Xsell Products Mod
  define('TABLE_PRODUCTS_XSELL', 'products_xsell');

// Lango Added for template and infobox mod
  define('TABLE_INFOBOX_CONFIGURATION', 'infobox_configuration');
  define('TABLE_TEMPLATE', 'template');

// Lango Added for Salemaker mod
  define('TABLE_SALEMAKER_SALES', 'salemaker_sales');

// Lango Added for Featured Products
  define('TABLE_FEATURED', 'featured');

// Lango Added for Wishlist
  define('TABLE_WISHLIST', 'customers_wishlist');
// banktransfer payment
  define('TABLE_BANKTRANSFER','banktransfer');
  define('TABLE_BANKTRANSFER_BLZ','banktransfer_blz');
// VJ Links Manager v1.00 begin
  define('TABLE_LINK_CATEGORIES', 'link_categories');
  define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');
  define('TABLE_LINKS', 'links');
  define('TABLE_LINKS_DESCRIPTION', 'links_description');
  define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');
// VJ Links Manager v1.00 end

  define('TABLE_INFORMATION', 'information');
  //+++AUCTIONBLOX.COM
  define('TABLE_AUCTION_BASKET', 'auction_basket');
  define('TABLE_AUCTION_HOUSES', 'auction_houses');
  define('TABLE_AUCTION_WINNERS', 'auction_winners');
  define('TABLE_AUCTION_PRODUCT_MATCHER', 'auction_product_matcher');
  define('TABLE_AUCTION_SALE_STATUS', 'auction_sale_status');
  define('TABLE_AUCTION_ITEMS', 'auction_items');
  define('TABLE_WORKFLOW_STATE', 'workflow_state');
  define('TABLE_FEEDBACK_STATE', 'feedback_state');

  define('TABLE_PAYPALIPN_AUCTION','paypalipn_auction');
  //+++AUCTIONBLOX.COM
  define('TABLE_CATS_PRODUCTS_XSELL', 'cats_products_xsell');
  define('TABLE_PRODUCTS_UPSELL', 'products_upsell');
  define('TABLE_CATEGORIES_UPSELL', 'categories_upsell');
  define('TABLE_CATS_PRODUCTS_UPSELL', 'cats_products_upsell');

  define('TABLE_PRODUCTS_PRICES', 'products_prices');
  define('TABLE_PRODUCTS_ATTRIBUTES_PRICES', 'products_attributes_prices');
  define('TABLE_SPECIALS_PRICES', 'specials_prices');
  define('TABLE_INVENTORY', 'inventory');

  define('TABLE_PROPERTIES_CATEGORIES', 'properties_categories');
  define('TABLE_PROPERTIES_CATEGORIES_DESCRIPTION', 'properties_categories_description');
  define('TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES', 'properties_to_properties_categories');
  define('TABLE_PROPERTIES', 'properties');
  define('TABLE_PROPERTIES_DESCRIPTION', 'properties_description');
  define('TABLE_PROPERTIES_TO_PRODUCTS', 'properties_to_products');

  define('TABLE_GROUPS', 'groups');
//  define('TABLE_PRODUCTS_GROUPS_PRICES', 'products_groups_prices');
//  define('TABLE_PRODUCTS_ATTRIBUTES_GROUPS_PRICES', 'products_attributes_groups_prices');
//  define('TABLE_SPECIALS_GROUPS_PRICES', 'specials_groups_prices');
  define('TABLE_VENDOR', 'vendor_vendor');
  define('TABLE_VENDOR_SALES', 'vendor_sales');
  define('TABLE_SEARCH_ENGINES', 'search_engines');
  define('TABLE_SEARCH_WORDS', 'search_words');
  define('TABLE_PRODUCTS_TO_AFFILIATES', 'products_to_affiliates');

  define('TABLE_SUBSCRIBERS', 'subscribers');

  define('TABLE_PROTX_DIRECT', 'protx_direct');

  define('TABLE_SETS_PRODUCTS', 'sets_products');

  define('TABLE_GIVE_AWAY_PRODUCTS', 'give_away_products');
 define('TABLE_SALES_SURVAVE','sales_survave');
?>
