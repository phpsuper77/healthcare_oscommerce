<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products Listing Module
*/
  if (sizeof($featured_products_array) == '0') {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_FEATURED_PRODUCTS; ?></td>
  </tr>
<?php
  } else { 
?>
<tr>
    <td class="main">
<?
unset($product_listing);

  	$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                         //'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                         'PRODUCT_LIST_SHORT_DESRIPTION' => PRODUCT_LIST_SHORT_DESRIPTION);
                         asort($define_list);

    $column_list = array();
    reset($define_list);
    foreach ($define_list as $key => $value) {
      if ($value > 0) $column_list[] = $key;
    }
  	
  	$product_listing = new product_listing();
	$product_listing->listing_split=$featured_products_split;
	
	if (PRODUCT_LISTING_MODE == 'Column'){
          $list_box_contents = $product_listing->process_col();
        }else{
          $list_box_contents = $product_listing->process_rol();
    }
    
    
    new productListingBox_index($list_box_contents);
 
?>
	</td>
  </tr>
<?
}
?>  