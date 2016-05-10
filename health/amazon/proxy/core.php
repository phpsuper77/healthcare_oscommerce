<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

// init proxy
include( dirname(__FILE__).'/functions.php');
include( dirname(__FILE__).'/db.php');
include( dirname(__FILE__).'/product.php');
include( dirname(__FILE__).'/Order.php');

define('TABLE_AMAZON_SOAP', 'amazon_soap');
define('TABLE_AMAZON_PRODUCTS', 'amazon_products');
define('TABLE_AMAZON_ORDERS', 'amazon_orders');

?>
