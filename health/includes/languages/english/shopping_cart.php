<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Cart Contents');
define('HEADING_TITLE', 'What\'s In My Cart?');
define('TABLE_HEADING_REMOVE', 'Remove');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'Your Shopping Cart is empty!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Products marked with <span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span> do not exist in desired quantity in our stock.<br>Please alter the quantity of products marked with (<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>), Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Products marked with <span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span> do not exist in desired quantity in our stock.<br>You can still purchase them and we will put any remaining quantity onto back order for despatch when stock arrives.');

define('TEXT_WE_ALSO_RECOMMEND', 'We Also Recommend:');
define('TABLE_HEADING_PRODUCT_NAME', 'Product Name');
define('TABLE_HEADING_PRODUCT_PRICE', 'Price');
define('TEXT_BUY_NOW', 'Buy now'); 
define('TEXT_OR_USE', '<b>- Or use -</b>');
define('TEXT_VAT_FORM_TITLE', 'VAT EXEMPTION');
define('TEXT_VAT_FORM_DESCRIPTION', 'To qualify for the VAT-Free Price you need to complete a VAT Exempt declaration to indicate that you suffer from a disability or chronic condition (e.g. asthma/COPD) and product(s) is being purchased for one named patient. Please <a href="%s">log-in</a> if you sent the form early. The form is pre-filled if you <a href="%s">create account</a>');
define('TEXT_VAT_FORM_DESCRIPTION_LOGGED_IN', 'We can\'t find your VAT Exempt declaration in our records. Please re-send.');
define('TEXT_VAT_FORM_DESCRIPTION_FOUND', 'We have your VAT Exempt declaration. Please proceed to checkout.');

define('TABLE_HEADING_VAT_EXEMPTION', 'VAT exempt');
define('TEXT_VAT_EXEMPT', 'VAT exempt');
define('TEXT_VAT_INC', 'VAT inc.');
?>