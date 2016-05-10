<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => ''));
  new contentPageHeading($infobox_contents);
?> 
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td>
<?php
  list($usec, $sec) = explode(' ', microtime());
  srand( (float) $sec + ((float) $usec * 100000) );
  $mtm= rand();

  if (tep_session_is_registered('affiliate_ref') && $HTTP_SESSION_VARS['affiliate_ref'] != ''){
    $add_sql = " and (f.affiliate_id = '" . (int)$affiliate_ref . "' or f.affiliate_id = 0) ";
  }else{
    $add_sql = " and f.affiliate_id = 0 ";
  }
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $featured_products_query_raw = "select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_FEATURED . " f, " . TABLE_PRODUCTS . " p " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on p.products_id = pd1.products_id and pd1.language_id = '" . $languages_id . "' and pd1.affiliate_id='".(int)$HTTP_SESSION_VARS['affiliate_ref']."' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "'  where p.products_id = f.products_id and p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and f.status = '1' ".($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'')." and pd.products_id=p.products_id and pd.language_id='" . (int)$languages_id . "' and pd.affiliate_id=0 " . $add_sql . " order by rand($mtm) ";
  }else{
    $featured_products_query_raw = "select p.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_FEATURED . " f, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p ".($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'')." left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on p.products_id = pd1.products_id and pd1.language_id = '" . (int)$languages_id . "' and pd1.affiliate_id='".(int)$HTTP_SESSION_VARS['affiliate_ref']."' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_id = f.products_id and p.products_status = 1 ".($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'')." and pd.products_id=p.products_id and pd.language_id='" . (int)$languages_id . "' and pd.affiliate_id=0 and f.status = '1' " . $add_sql . " order by rand($mtm) ";
  }

  $featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS_LISTING);

  $featured_products_query = tep_db_query($featured_products_split->sql_query);
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $featured_products_array[] = array('id' => $featured_products['products_id'],
                                  'name' => $featured_products['products_name'],
                                  'image' => $featured_products['products_image'],
                                  'price' => tep_get_products_price($featured_products['products_id'], 1, $featured_products['products_price']),
                                  'specials_price' => tep_get_products_special_price($featured_products['products_id']),
                                  'tax_class_id' => $featured_products['products_tax_class_id'],
                                  'date_added' => tep_date_long($featured_products['products_date_added']),
                                  'manufacturer' => $featured_products['manufacturers_name']);
  }

  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

  require(DIR_WS_MODULES  . FILENAME_FEATURED_PRODUCTS);


// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </td>
      </tr>
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table>
