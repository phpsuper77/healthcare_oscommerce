<?php
/*
  $Id: product_listing.php,v 1.1.1.1 2008/13/02 21:36:11 max Exp $
*/

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
  $product_listing = new product_listing();
  $product_listing->listing_split=$listing_split;

  if($TEXT_DISPLAY_NUMBER_OF_ITEM=='') $TEXT_DISPLAY_NUMBER_OF_ITEM = TEXT_DISPLAY_NUMBER_OF_PRODUCTS;
  
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
	$product_listing->split_page_result($TEXT_DISPLAY_NUMBER_OF_ITEM);
  }
  
  $list_box_contents = array();
  $list_box_contents[] = array(array('params' => 'colspan="' . (LISTING_NUM_PRODUCTS_PER_ROW*2-1) . '" class="lineH"', 'text' => tep_draw_separator('spacer.gif', 1, 10)));
  if ($listing_split->number_of_rows > 0) { 
  	$list_box_contents = $product_listing->process_rol();
    new productListingBox_index($list_box_contents);
  } else {
    $list_box_contents = array();
    $list_box_contents[0] = array('params' => 'class="productListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="productListing-data"', 'text' => TEXT_NO_PRODUCTS);
    new productListingBox_index($list_box_contents);
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
	$product_listing->split_page_result($TEXT_DISPLAY_NUMBER_OF_ITEM);
  }
?>
