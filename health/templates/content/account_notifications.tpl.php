    <?php echo tep_draw_form('account_notifications', tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
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
  $infobox_contents[] = array(array('text' =>HEADING_TITLE), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>
        </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
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
  $info_box_contents[] = array('text' => MY_NOTIFICATIONS_TITLE);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%"> 
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo MY_NOTIFICATIONS_DESCRIPTION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => GLOBAL_NOTIFICATIONS_TITLE);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('product_global')">
                    <td class="main" width="30"><?php echo tep_draw_checkbox_field('product_global', '1', (($global['global_product_notifications'] == '1') ? true : false), 'onclick="checkBox(\'product_global\')"'); ?></td>
                    <td class="main"><b><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></b></td>
                  </tr>
                  <tr>
                    <td width="30">&nbsp;</td>
                    <td class="main"><?php echo GLOBAL_NOTIFICATIONS_DESCRIPTION; ?></td>
                  </tr>
                </table></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
  if ($global['global_product_notifications'] != '1') {
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => NOTIFICATIONS_TITLE);

  new contentBoxHeading($info_box_contents, false, false);

?>
        <table border="0" width="100%" cellspacing="0" cellpadding="1" class="contentBox">
          <tr>
            <td><table border="0" cellspacing="2" cellpadding="2" class="contentBoxContents" width="100%" height="100%">  
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $products_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.customers_id = '" . (int)$customer_id . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " and if(pp.products_group_price is null, 1, pp.products_group_price != -1 )");
    }else{
      $products_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . "  where p.customers_id = '" . (int)$customer_id . "' " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . " ");
    }
    $products_check = tep_db_fetch_array($products_check_query);
    if ($products_check['total'] > 0) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo NOTIFICATIONS_DESCRIPTION; ?></td>
                  </tr>
<?php
      $counter = 0;
      $products_query = tep_db_query("select pd.products_id, if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name from " . TABLE_PRODUCTS_NOTIFICATIONS . " pn, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = pd.products_id and pd1.language_id='" . (int)$languages_id ."' and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where pn.customers_id = '" . (int)$customer_id . "' and pn.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
?>
                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('products[]', <?php echo $counter; ?>)">
                    <td class="main" width="30"><?php echo tep_draw_checkbox_field('products[]', (int)$products['products_id'], true, 'onclick="checkBox(\'products[]\', ' . $counter . ')"'); ?></td>
                    <td class="main"><b><?php echo $products['products_name']; ?></b></td>
                  </tr>
<?php
        $counter++;
      }
    } else {
?>
                  <tr>
                    <td class="main"><?php echo NOTIFICATIONS_NON_EXISTING; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
<?php
  if (CELLPADDING_SUB < 5){
?>      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
  }
?>
      <tr>
        <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="left" class="main" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align="right" class="main"', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>          
       
        </td>
      </tr>
    </table></form>
