<?php
/*
$Id: wishlist.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce
*/

// retreive the wishlist

if (tep_session_is_registered('customer_id')) {
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $wishlist_query = tep_db_query("select w.* from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' WHERE w.products_id = p.products_id and p.products_status = 1 and w.customers_id='".(int)$customer_id."' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )");
  }else{
    $wishlist_query = tep_db_query("select w.* from " . TABLE_WISHLIST . " w, " . TABLE_PRODUCTS . " p  WHERE w.products_id = p.products_id and p.products_status = 1 and w.customers_id='".(int)$customer_id."'");
  }

  // if we have something in this clients list:

?>
          <tr>
            <td class="infoBoxCell">

<!-- customer_wishlist //-->
<script language="javascript" type="text/javascript"><!--
function popupWindowWishlist(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=425,height=475,screenX=150,screenY=150,top=150,left=150')
}
//-->
</script>
<?php
if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
  new infoBoxImageHeading($info_box_contents, tep_href_link(FILENAME_WISHLIST, '','NONSSL'));
}else{
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => BOX_HEADING_CUSTOMER_WISHLIST);
  $infoboox_class_heading = $infobox_class . 'Heading';
  if (class_exists($infoboox_class_heading)){
    new $infoboox_class_heading($info_box_contents, false, false, tep_href_link(FILENAME_WISHLIST, '','NONSSL'));
  }else{
    new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_WISHLIST, '','NONSSL'));
  }  
}

$info_box_contents = array();

if (tep_db_num_rows($wishlist_query)) {
  if (tep_db_num_rows($wishlist_query) <= MAX_DISPLAY_WISHLIST_BOX) {
    $product_ids = '';
    $customer_wishlist_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
    while ($wishlist = tep_db_fetch_array($wishlist_query)) {
      $customer_wishlist_string .= '  <tr>' . "\n" .
      '    <td class="infoBoxContents"><a class="infoBoxLink" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($products['products_id']) . '&products_id=' . $wishlist['products_id'], 'NONSSL') . '">' . tep_get_products_name($wishlist['products_id']) . '</a></td>' . "\n" .
      '	  </tr>' . "\n" .
      '	  <tr>' . "\n" .
      '    <td class="infoBoxContents" align="center" valign="bottom"><b><a class="infoBoxLink" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=cust_order&pid=' . $wishlist['products_id'] . '&rfw=1', 'NONSSL') . '">' . BOX_WISHLIST_MOVE_TO_CART . '</a>&nbsp;|' . "\n" .
      '    <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=remove_wishlist&pid=' . $wishlist['products_id'], 'NONSSL') . '">' . BOX_WISHLIST_DELETE . '</a></b></td>' . "\n" .
      '  </tr><tr><td class="infoBoxContents" valign="top">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif', '', '1', '5') . tep_draw_separator('pixel_black.gif', '100%', '1') . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif', '', '1', '5') . '</td></tr>' . "\n";
    }
  } else {
    $customer_wishlist_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
    $customer_wishlist_string .= '<tr><td class="infoBoxContents">' . sprintf(TEXT_WISHLIST_COUNT, tep_db_num_rows($wishlist_query)) . '</td></tr>' . "\n";
  }
} else {
  $customer_wishlist_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
  $customer_wishlist_string .= '<tr><td class="infoBoxContents">' . BOX_WISHLIST_EMPTY . '</td></tr>' . "\n";
}
$customer_wishlist_string .= '<tr><td colspan="3" align="right"><a class="infoBoxLink" href="' . tep_href_link(FILENAME_WISHLIST, '','NONSSL') . '">' . BOX_VIEW_CUSTOMER_WISHLIST . ' [+]</a></td></tr>' . "\n";
$customer_wishlist_string .= '<tr><td colspan="3" align="right"><a class="infoBoxLink" href="javascript:popupWindowWishlist(\'' . tep_href_link('popup_' . FILENAME_WISHLIST_HELP, '','NONSSL') . '\')">'. BOX_HELP_CUSTOMER_WISHLIST . ' [?]</a></td></tr>' . "\n";
$customer_wishlist_string .= '<tr><td colspan="3" align="right"><a class="infoBoxLink" href="' . tep_href_link(FILENAME_WISHLIST_HELP, '','NONSSL') . '">'. BOX_HELP_CUSTOMER_WISHLIST . ' [?]</a></td></tr>' . "\n";
$customer_wishlist_string .= '</table>';

$info_box_contents[] = array('align' => 'left',
'text'  => $customer_wishlist_string);

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
           </td>
         </tr>
<?php
}
?>
<!-- customer_wishlist_eof //-->

