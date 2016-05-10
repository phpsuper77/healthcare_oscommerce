    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php 
if (tep_db_num_rows($review_query)){
?>

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
  $infobox_contents[] = array(array('text' =>$products_name), array('params'=> 'align=right', 'text' => $products_price));
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
}else{
$header_text = $products_name . '&nbsp;&nbsp;&nbsp;&nbsp;' . $products_price;
}
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><?php echo '<b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($review_content['customers_name'])) . '</b>'; ?></td>
                    <td class="smallText" align="right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review_content['date_added'])); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>
           <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
            <tr>
              <td><table border="0" width="100%" cellspacing="2" cellpadding="4"  class="contentBoxContents">                                                 
                    <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td valign="top" class="main" style="text-align:justify;"><?php echo tep_break_string(nl2br(tep_output_string_protected($review_content['reviews_text'])), 60, '-<br>') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_TEMPLATE_IMAGES . 'reviews/stars_' . $review_content['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $review_content['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $review_content['reviews_rating'])) . '</i>'; ?></td>
                        <td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id'))) . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('params' => 'align=right width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '">' . tep_template_image_button('button_write_review.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_WRITE_REVIEW, 'class="transpng"') . '</a>'),

                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
                </td>
              </tr>
            </table></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
<?php
  if (tep_not_null($review_content['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $review_content['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $review_content['products_image'], addslashes($review_content['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle"')  . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $review_content['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $review_content['products_image'], $review_content['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . tep_template_image_button('image_enlarge.' . BUTTON_IMAGE_TYPE, TEXT_CLICK_TO_ENLARGE, 'class="transpng" align="absmiddle"')  . '</a>'; ?>
</noscript>
<?php
  }


  $wishlist_id_query = tep_db_query('select products_id as wPID from ' . TABLE_WISHLIST . ' where products_id= ' . $review_content['products_id'] . ' and customers_id = ' . (int)$customer_id . ' order by products_name');
$wishlist_Pid = tep_db_fetch_array($wishlist_id_query);

  echo '<div style="padding:5px 0px"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_template_image_button('button_in_cart.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_IN_CART, 'class="transpng"') . '</a></div>';
  echo '<div style="padding:5px 0px"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$review_content['products_id']) . '">' . tep_template_image_button('button_product_page.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_PRODUCT_PAGE, 'class="transpng"') . '</a></div>';
echo '<div style="padding:5px 0px"><form name="wishlist_quantity" method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_wishlist', 'NONSSL') . '">';
echo '                    <input type="hidden" name="products_id" value="' . $review_content['products_id'] . '">
                          <input type="hidden" name="products_model" value="' . $review_content['products_model'] . '">
                          <input type="hidden" name="products_name" value="' . htmlspecialchars(stripslashes($review_content['products_name'])) . '">
                          <input type="hidden" name="products_price" value="' . $review_content['products_price'] . '">
                          <input type="hidden" name="final_price" value="' . $review_content['final_price'] . '">
                          <input type="hidden" name="products_tax" value="' . $review_content['products_tax'] . '">'; 
    
if ( (!tep_not_null($wishlist_Pid[wPID])) && (tep_session_is_registered('customer_id')) )  echo tep_template_image_submit('button_add_wishlist.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_ADD_WISHLIST, 'class="transpng"');
echo  '
                        </form>
</div>';
?>
                </td>
              </tr>
            </table>
          </td>
          </tr>
        </table></td>
      </tr>
<?php 
}else{
?>          
      <tr>
        <td><?php new contentBox(array(array('text' => TEXT_NOT_REVIEWS))); ?></td>
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
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'class="main" align="right" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
<?php 
}
?>          
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
    </table>

