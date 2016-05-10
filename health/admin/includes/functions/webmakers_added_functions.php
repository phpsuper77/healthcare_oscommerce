<?php
function tep_delete_products_attributes($delete_product_id) {
  // delete products attributes
  $products_delete_from_query= tep_db_query("select pa.products_id, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa  where pa.products_id='" . $delete_product_id . "'");
  while ( $products_delete_from=tep_db_fetch_array($products_delete_from_query) ) {
    tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $products_delete_from['products_attributes_id'] . "'");
  }
  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $delete_product_id . "'");
}

function updateAttributesPrices($new_id, $old_id){
  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $new_id . "'");
  $query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " where products_attributes_id = '" . $old_id . "'" );
  while ($data = tep_db_fetch_array($query)){
    tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_PRICES . " ( products_attributes_id, groups_id, currencies_id, attributes_group_price, attributes_group_discount_price ) values ('" . $new_id . "', '" . $data['groups_id'] . "', '" . $data['currencies_id'] . "', '" . $data['attributes_group_price'] . "', '" . $data['attributes_group_discount_price'] . "')");
  }
}

function tep_copy_products_attributes($products_id_from,$products_id_to) {
  global $copy_attributes_delete_first, $copy_attributes_duplicates_skipped, $currencies;

  $products_copy_to_query= tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
  $products_copy_to_check_query= tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
  $products_copy_from_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");
  $products_copy_from_check_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");

  // Check for errors in copy request
  if (!$products_copy_from_check=tep_db_fetch_array($products_copy_from_check_query) or !$products_copy_to_check=tep_db_fetch_array($products_copy_to_check_query) or $products_id_to == $products_id_from ) {
    echo '<table width="100%"><tr>';
    if ($products_id_to == $products_id_from) {
      // same products_id
      echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: Cannot copy from Product ID #' . $products_id_from . ' to Product ID # ' . $products_id_to . ' ... No copy was made' . '</b>' . '</td>';
    } else {
      if (!$products_copy_from_check) {
        // no attributes found to copy
        echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: No Attributes to copy from Product ID #' . $products_id_from . ' for: ' . tep_get_products_name($products_id_from) . ' ... No copy was made' . '</b>' . '</td>';
      } else {
        // invalid products_id
        echo '<td class="messageStackError">' . tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '<b>WARNING: There is no Product ID #' . $products_id_to . ' ... No copy was made' . '</b>' . '</td>';
      }
    }
    echo '</tr></table>';
  } else {

    if ($copy_attributes_delete_first=='1') {
      // delete all attributes
      tep_delete_products_attributes($products_id_to);
    }

    while ( $products_copy_from=tep_db_fetch_array($products_copy_from_query) ) {
      $check_attribute_query= tep_db_query("select products_id, products_attributes_id, options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_to . "' and options_id='" . $products_copy_from['options_id'] . "' and options_values_id ='" . $products_copy_from['options_values_id'] . "'");
      $check_attribute= tep_db_fetch_array($check_attribute_query);

      // Process Attribute
      $skip_it=false;
      switch (true) {
        case ($check_attribute and $copy_attributes_duplicates_skipped):
          // skip duplicate attributes
          $skip_it=true;
          break;
        default:
          // skip anything when $skip_it
          if (!$skip_it) {
            if ($check_attribute['products_id']) {
              $sql_data_array = array(
                                      'options_id' => tep_db_prepare_input($products_copy_from['options_id']),
                                      'options_values_id' => tep_db_prepare_input($products_copy_from['options_values_id']),
                                      'options_values_price' => tep_db_prepare_input($products_copy_from['options_values_price']),
                                      'price_prefix' => tep_db_prepare_input($products_copy_from['price_prefix']),
                                      'products_options_sort_order' => tep_db_prepare_input($products_copy_from['products_options_sort_order']),
                                      'product_attributes_one_time' => tep_db_prepare_input($products_copy_from['product_attributes_one_time']),
                                      'products_attributes_weight' => tep_db_prepare_input($products_copy_from['products_attributes_weight']),
                                      'products_attributes_weight_prefix' => tep_db_prepare_input($products_copy_from['products_attributes_weight_prefix']),
                                      'products_attributes_units' => tep_db_prepare_input($products_copy_from['products_attributes_units']),
                                      'products_attributes_discount_price' => tep_db_prepare_input($products_copy_from['products_attributes_discount_price']),
                                      'products_attributes_filename' => tep_db_prepare_input($products_copy_from['products_attributes_filename']),
                                      'products_attributes_maxdays' => tep_db_prepare_input($products_copy_from['products_attributes_maxdays']),
                                      'products_attributes_maxcount' => tep_db_prepare_input($products_copy_from['products_attributes_maxcount']),
                                      'products_attributes_units_price' => tep_db_prepare_input($products_copy_from['products_attributes_units_price'])
              );

              $cur_attributes_id = $check_attribute['products_attributes_id'];
              tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', 'products_id = \'' . tep_db_input($products_id_to) . '\' and products_attributes_id=\'' . tep_db_input($cur_attributes_id) . '\'');
              updateAttributesPrices($cur_attributes_id, $products_copy_from['products_attributes_id']);
            } else {
              tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order, product_attributes_one_time, products_attributes_weight, products_attributes_weight_prefix, products_attributes_units, 	products_attributes_units_price, products_attributes_discount_price, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) values ('', '" . $products_id_to . "', '" . $products_copy_from['options_id'] . "', '" . $products_copy_from['options_values_id'] . "', '" . $products_copy_from['options_values_price'] . "', '" . $products_copy_from['price_prefix'] . "', '" . $products_copy_from['products_options_sort_order'] . "', '" . $products_copy_from['product_attributes_one_time'] . "', '" . $products_copy_from['products_attributes_weight'] . "', '" . $products_copy_from['products_attributes_weight_prefix'] . "', '" . $products_copy_from['products_attributes_units'] . "', '" . $products_copy_from['products_attributes_units_price'] . "', '" . $products_copy_from['products_attributes_discount_price']. "', '" . $products_copy_from['products_attributes_filename']. "', '" . $products_copy_from['products_attributes_maxdays']. "', '" . $products_copy_from['products_attributes_maxcount']. "')");
              $cur_attributes_id = tep_db_insert_id();
              updateAttributesPrices($cur_attributes_id, $products_copy_from['products_attributes_id']);
            }
          } // $skip_it
          break;
      } // end of switch
    } // end of products attributes while loop
  } // end of no attributes or other errors
} // eof: tep_copy_products_attributes


// Check if product has attributes
function tep_has_product_attributes($products_id) {
  $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "'");
  $attributes = tep_db_fetch_array($attributes_query);

  if ($attributes['count'] > 0) {
    return true;
  } else {
    return false;
  }
}
?>
