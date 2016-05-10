<!-- giveaway_products //-->
<?php
  $total = $cart->show_total();
  $giveaway_query = tep_db_query("select p.products_id, p.products_image, p.products_status, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_model, if(gap.shopping_cart_price <= '" . $total . "', 1, 0) as active, gap.shopping_cart_price as price, gap.products_qty as qty from " . TABLE_GIVE_AWAY_PRODUCTS . " gap, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . ", " . TABLE_PRODUCTS_DESCRIPTION . " pd where gap.products_id = p.products_id " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and pd.affiliate_id = '0' order by price, active desc, products_name");
  if (tep_db_num_rows($giveaway_query) > 0)
  {
    $row = 0;
    $first = true;
    while ($d = tep_db_fetch_array($giveaway_query))
    {
      if ($first) {
        $first = false;
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left', 'text' => TEXT_GA_PRODUCTS);
        new contentBoxHeading($info_box_contents, false, false);
        $info_box_contents = array();
      }
      if ($d['active'] == 1) {
        $checkbox = tep_draw_form('ga' . $row, tep_href_link(basename($PHP_SELF), 'product_id=' . $d['products_id'] . '&action=' . ($cart->in_giveaway($d['products_id']) ? 'remove_giveaway' : 'add_giveaway'))) . tep_draw_checkbox_field('giveaway', '1', $cart->in_giveaway($d['products_id']), 'onClick="this.form.submit();"') . tep_draw_hidden_field('qty', $d['qty']) . '</form><br>' . TEXT_ADD_GIVEAWAY;
      } else {
        $collect = $d['price'] - $total;
        if ($collect < 0) {
          $collect = 0;
        }
        $checkbox = sprintf(TEXT_SPEND_MORE, $currencies->format($collect));
      }
      $info_box_contents[] = array('params' => 'valign="top" height="100%" style="border-bottom: #b3b2b2 1px solid;"',
      'text' => '
      <table width="100%" border="0" cellpadding="1" cellspacing="1" height="100%">
        <tr>
          <td align="center" width="' . (SMALL_IMAGE_WIDTH + 5) . '">' . ($d['products_status'] ? '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $d['products_id']) . '">' : '') . tep_image(DIR_WS_IMAGES . $d['products_image'], $d['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class=shadow1') . ($d['products_status'] ? '</a>' : '') . '</td>
          <td class="main" align="left"><b>' . $d['products_name'] . '<br><br>' . sprintf(TEXT_PRICE_BEFORE, $d['qty'], $currencies->format($d['price'])) . '</b></td>
          <td class="main" align="left" width="22%">' . $checkbox . '</td>
        </tr>
      </table>');
      $row++;
    }
    new contentBox($info_box_contents);
  }
?>
<!-- giveaway_products_eof //-->