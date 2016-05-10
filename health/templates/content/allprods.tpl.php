<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php 
// Set number of columns in listing
define ('NR_COLUMNS', 2);?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
  $header_text = '&nbsp;'
  //EOF: Lango Added for template MOD
?>
      <tr> 
        <td width="100%">
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => ''));
  new contentPageHeading($infobox_contents);
?>        
        </td> 
      </tr>
<?php 
if (CELLPADDING_SUB < 5) {
?>
      <tr> 
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td> 
      </tr>
<?php
}
// BOF: Lango Added for template MOD
}else{
  $header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr> 
        <td> 
             <?php    

             $info_box_contents = array();
             $info_box_contents[0][] = array('align' => 'center',
             'params' => 'class="productListing-heading"',
             'text' => TABLE_HEADING_PRODUCT_NAME);

             $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
             'text' => TABLE_HEADING_MODEL, 'align' => 'center');

             $info_box_contents[0][] = array('align' => 'center',
             'params' => 'class="productListing-heading"',
             'text' => TABLE_HEADING_PRICE);
             if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
               $products_query = tep_db_query("SELECT p.products_id, p.products_model, p.products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_tax_class_id FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' WHERE p.products_id = pd.products_id AND p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " AND pd.language_id = '".(int)$languages_id."' and pd.affiliate_id = 0 ORDER BY products_name");
             }else{
               $products_query = tep_db_query("SELECT p.products_id, p.products_model, p.products_price, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_tax_class_id FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' WHERE p.products_id = pd.products_id AND p.products_status = 1  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " AND pd.language_id = '".(int)$languages_id."' and pd.affiliate_id = 0 ORDER BY products_name");
             }

               $products_array = array();
               while($products = tep_db_fetch_array($products_query))
               {
                 $products_array[] = array('id'=> $products['products_id'],
                 'name'    => $products['products_name'],
                 'model'   => $products['products_model'],
                 'price'   => tep_get_products_price($products['products_id'], 1, $products['products_price']),
                 'tax'     => $products['products_tax_class_id'],
                 'special' => tep_get_products_special_price($products['products_id']));
               }

               $num_prods = sizeof($products_array);  // This optimizes that slow FOR loop...

               for ($i = 0; $i < $num_prods; $i++)    // Traverse Rows
               {
                 // Rotate Row Colors
                 if ($i % 2)  // Odd Row
                 {
                   $info_box_contents[] = array('params' => 'class="productListing-odd"');
                 }
                 else   // Guess...
                 {
                   $info_box_contents[] = array('params' => 'class="productListing-even"');
                 }


                 $this_id = $products_array[$i]['id'];
                 $this_name = $products_array[$i]['name'];
                 $this_model = $products_array[$i]['model'];
                 $this_price = $products_array[$i]['price'];
                 $this_special = $products_array[$i]['special'];
                 $this_tax = $products_array[$i]['tax'];
                 $this_url = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $this_id, 'NONSSL');

                 $cur_row = sizeof($info_box_contents) - 1;
                 $info_box_contents[$cur_row][] = array('align' => 'left',
                 'params' => 'class="productListing-data"',
                 'text' => "<a href='$this_url'>$this_name</a>");
                 $info_box_contents[$cur_row][] = array('align' => 'center',
                 'params' => 'class="productListing-data"',
                 'text' => "<a href='$this_url'>$this_model</a>");

                 if (tep_not_null($this_special))
                 {
                   $info_box_contents[$cur_row][] = array('align' => 'right',
                   'params' => 'class="productListing-data"',
                   'text' => "<a href='$this_url'><s class='productPriceOld'>".$currencies->display_price($this_price, tep_get_tax_rate($this_tax))."</s> <span class='productPriceSpecial'>".$currencies->display_price($this_special, tep_get_tax_rate($this_tax))."</span></a></td>");
                 }
                 else
                 {
                   $info_box_contents[$cur_row][] = array('align' => 'right',
                   'params' => 'class="productListing-data" valign="top"',
                   'text' => "<a href='$this_url'>".$currencies->display_price($this_price, tep_get_tax_rate($this_tax))."</a>");
                 }
               }

             new productListingBox($info_box_contents);


?> 
            </td> 
          </tr> 
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
$info_box_contents = array();
$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
array('params' => 'class="main" align="right" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
new buttonBox($info_box_contents);
?>
        </td>
      </tr>

   </table>
