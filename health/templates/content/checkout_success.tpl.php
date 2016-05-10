    <?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' => HEADING_TITLE, 'params' => 'align=center'));
  new contentPageHeading($infobox_contents);
?>          
        
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="4" cellpadding="2">
          <tr>
            <td valign="top"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', $HEADING_TITLE); ?></td>
            <td valign="top" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?><?php echo $TEXT_SUCCESS; ?><br><br>
<?php
  if ($global['global_product_notifications'] != '1') {
    echo TEXT_NOTIFY_PRODUCTS . '<br><p class="productsNotifications">';

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
        echo tep_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br>';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }

    echo '</p>';
  } else {
    echo TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
  }
?>
            <b><?php echo TEXT_THANKS_FOR_SHOPPING; ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php /* vat form addon {{ */ ?>
<?php
  // pre check - need logged customer w/o ticked flag, vat exempt product in last order
  $vat_form_heed = false;
  $last_order = (int)$orders['orders_id'];
  if (tep_session_is_registered('customer_id') && $last_order>0) {
    $cart_check = tep_db_fetch_array(tep_db_query(
      "select count(*) as c ".
      "from ".TABLE_ORDERS_PRODUCTS." op, ".TABLE_PRODUCTS." p ".
      "where op.orders_id='".$last_order."' ".
        "and p.vat_exempt_flag=1 ".
        "and p.products_id=op.products_id " 
    ));
    if ( (int)$cart_check['c']!=0 ) {
      $vat_form_heed = true;
    }
  }
  
  if ( $vat_form_heed && !tep_check_vat_form($customer_id) ) {
    @include(DIR_WS_LANGUAGES.$language.'/'.FILENAME_SHOPPING_CART);
?>
      <tr>
        <td class="vat_info_warning" align="center">

        <div class="pageHeading"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'warning.png', TEXT_VAT_FORM_TITLE, '', '', 'style="vertical-align: middle; padding-right: 30px;"') . TEXT_VAT_FORM_TITLE?></div>
        <p class="main" style="margin-top:0;text-align:justify;"><?php 
         $checked_flag = false;
         echo TEXT_VAT_FORM_DESCRIPTION_LOGGED_IN;
          ?></p>
        <input type="checkbox" onclick="document.getElementById('veForm').style.display=(this.checked?'':'none');" <?php echo ($checked_flag?'checked':'')?>><?php echo TEXT_VAT_EXEMPT_TEXT; ?>
        <span style="display:none" id="veForm"><a href="<?php echo tep_href_link(FILENAME_VATFORM, '');?>"><?php echo TEXT_FILL_VAT_FORM; ?></span>
        </td>
      </tr>
<?php
}
?>
<?php /* }} vat form addon */ ?>
<?php 
//ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM - Start Addition
  $gv_query=tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='".$customer_id."'");
  if ($gv_result=tep_db_fetch_array($gv_query)) {
    if ($gv_result['amount'] > 0) {
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
      <tr>
        <td align="center" class="main"><?php echo GV_HAS_VOUCHERA; echo tep_href_link(FILENAME_GV_SEND); echo GV_HAS_VOUCHERB; ?></td>
      </tr>
<?php
}}
//ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM - End Addition
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
      <tr> 
        <td align="left" class="main">
        
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="left" class="main" width=100%', 'text' => '<a href="javascript:popupOrder(\'' . tep_href_link (FILENAME_ORDERS_PRINTABLE,  tep_get_all_get_params(array('order_id')) . 'order_id=' . (empty($HTTP_GET_VARS['order_id'])?$orders['orders_id']:$HTTP_GET_VARS['order_id'])) . '\')">' . tep_template_image_button('button_printorder.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_PRINT_ORDER, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right', 'text' => tep_template_image_submit('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"')),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
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
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'checkout_bullet.gif'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>
<?php 
//---PayPal WPP Modification START ---//
  tep_paypal_wpp_checkout_completed($ec_enabled);
//---PayPal WPP Modification END ---// 
?>

    </table></form>
<!-- Google Code for Sale/Transaction Conversion Page --> 
<script type="text/javascript"><!--
var google_conversion_id = 1060602245;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "ligpCKnDrAEQhYPe-QM"; 
var google_conversion_value = 0; 
//-->
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
 <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1060602245/?label=ligpCKnDrAEQhYPe-QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- /Google Code for Sale/Transaction Conversion Page -->