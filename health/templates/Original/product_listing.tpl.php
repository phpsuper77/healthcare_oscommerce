<?php
  class product_listing { 
  var $cart_field;
  var $listing_split;
  var $view_button=false;
  var $add_no_follow = false; //Musaffar
  
    function product_listing() { 
      $this->view_button = false;
    }
    function setViewMode( $newState ){
      $this->view_button = (bool)$newState;
    }
	
	function get_no_nollow() {
		if ($this->add_no_follow) {
			return('rel="nofollow"');
		}
		else {
			return("");
		}
	}	
    
    function split_page_result($TEXT_DISPLAY_NUMBER_OF_PRODUCTS = TEXT_DISPLAY_NUMBER_OF_PRODUCTS) { 
      
      $content[0][] = array('params' => 'class="smallText"',
                                  'text' => $this->listing_split->display_count($TEXT_DISPLAY_NUMBER_OF_PRODUCTS));
                                  
            $content[0][] = array('params' => 'class="smallText"',
                                  'text' => TEXT_RESULT_PAGE . ' ' . 
                      $this->listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))),
                                  'align' => 'right');
                                  
      $content[1][] = array('text' => tep_draw_separator('pixel_trans.gif', '100%', '10'));
                                
      new tableBox($content, true);
    }
    
    function process_col() {
      global $column_list, $currencies, $HTTP_GET_VARS, $cPath, $PHP_SELF;
      $listing_query = tep_db_query($this->listing_split->sql_query);
	  
       
    $row = 0;
    $list_box_contents[$row] = array('params' => 'class="productFirstRow"');
    $column = 0;
    while ($listing = tep_db_fetch_array($listing_query)) {
      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $product_link = tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']);
      }else{
        $product_link = tep_href_link(FILENAME_PRODUCT_INFO, ($this->view_button?'':($cPath ? 'cPath=' . $cPath . '&' : '')) . 'products_id=' . $listing['products_id']);
     }

      $product_contents = array();
	  
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';
		
		

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
			if ( tep_get_products_stock($listing['products_id']) >0) {
				$stock_image =  tep_image(DIR_WS_TEMPLATE_IMAGES . 'in_stock.gif');
			} else {
				$stock_image = tep_image(DIR_WS_TEMPLATE_IMAGES . 'out_stock.gif');
			}
			
            $lc_text = 
				'<tr><td class="productModelCell paddingLR">'.
				highlight_text($listing['products_model'], $search_keywords).'
				<span style="display:inline-block; float:right;">'.
				$stock_image.'</span>'.
				'</td></tr>';
            break;
          case 'PRODUCT_LIST_NAME':
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $lc_text = '<tr><td class="productNameCell paddingLR"><a href="' . $product_link . '">' . $listing['products_name'] . '&nbsp;</a>';
            } else {
             $lc_text = '<tr><td class="productNameCell paddingLR"><a href="' . $product_link . '"'.$this->get_no_nollow().'>' . $listing['products_name'] . '&nbsp;</a>';
            }
            $lc_text .= '</td></tr>';
            break;
          case 'PRODUCT_LIST_SHORT_DESRIPTION':
            $lc_text = '<tr><td class="productDescriptionCell paddingLR">' . $listing['products_description_short'] . '</td></tr>';
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_text = '<tr><td class="productManufacturersCell paddingLR"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a></td></tr>';
            break;
          case 'PRODUCT_LIST_PRICE':
          
            //TEXT_PRICE . ': ' . $products_price_excl_vat . ' ' . TEXT_EXCL_VAT . '<br><font style="font-size:13px;"><b>(' . $products_price . ' ' . TEXT_INCL_VAT . ')</b></font>'
            
            $products_price_excl_vat = $currencies->display_price(tep_get_products_price($listing['products_id'], 1, $listing['products_price']), 0) . tep_image(DIR_WS_IMAGES . 'vat.gif', '', 38, 14, 'style="vertical-align:bottom;"'); 
            if (tep_get_products_special_price($listing['products_id'])) {
              $lc_text = '<tr><td class="productPriceListingCurrent">' . TEXT_PRICE . ':&nbsp;' . $products_price_excl_vat . '</td></tr><tr><td class="productPriceCell paddingLR"><span class="productPriceSpecial">' . $currencies->display_price(tep_get_products_special_price($listing['products_id']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span></td></tr>'; //<span class="productPriceOld">(' .  $currencies->display_price(tep_get_products_price($listing['products_id'], 1, $listing['products_price']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;
            } else {
              $lc_text = '<tr><td class="productPriceListingCurrent">' . TEXT_PRICE . ':&nbsp;' . $products_price_excl_vat . '</td></tr>'; //<tr><td class="productPriceCell paddingLR"><span class="productPriceCell paddingLR">' . $currencies->display_price(tep_get_products_price($listing['products_id'], 1, $listing['products_price']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span></td></tr>
            }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_text = '<tr><td class="productQuantityCell paddingLR">' . $listing['products_quantity'] . '</td></tr>';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_text = '<tr><td class="productWeightCell paddingLR">' . $listing['products_weight'] . '</td></tr>';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_text = '';
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            if ( $this->view_button ) {
              $lc_text = '<tr><td class="productButtonCell paddingLR"><a href="' . $product_link . '">' . tep_template_image_button('small_view.' . BUTTON_IMAGE_TYPE, SMALL_IMAGE_BUTTON_VIEW, 'class="transpng"') . '</a></td></tr>';
            }else{
              $lc_text = '<tr><td class="productButtonCell paddingLR"><a href="' . tep_href_link(basename($PHP_SELF), 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_template_image_button('button_buy_now.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BUY_NOW, 'class="transpng"') . '</a></td></tr>';
            }
            /* $lc_text = '<tr><td class="productButtonCell">' . tep_draw_form('buynow', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array()) . 'action="buy_now"'), 'get') . tep_hide_session_id() . '<table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tr>
                      <td><a href="' .((isset($HTTP_GET_VARS['manufacturers_id']))?tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']):tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id'])) . '">' . TEXT_PRODUCT_DETAILS . '</a></td>
                      <td>' . TABLE_HEADING_QUANTITY . ':&nbsp;</td>
                      <td>' . tep_draw_input_field('qty', 1, 'style="WIDTH:35px;text-align:center;"') . tep_draw_hidden_field('products_id', $listing['products_id']) . tep_draw_hidden_field('action', 'buy_now') .  '</td>
                      <td>' . tep_template_image_submit('button_buy_now.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BUY_NOW, 'class="transpng"') . '</td>
                    </tr>
                  </table></form></td></tr'; */
            break;
        }
        $product_contents[] = $lc_text;

      }
      
      if (PRODUCT_LIST_IMAGE>0){
        $lc_text = '
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="productTable">
  <tr valign="top">
    <td class="productImageCell">';
        if (isset($HTTP_GET_VARS['manufacturers_id'])) {
          $lc_text .= '<a href="' . $product_link . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        } else {
          $lc_text .= '<a href="' . $product_link . '"'.$this->get_no_nollow().'>' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
        }
        $lc_text .= '
    </td>
    <td>
    
        <table width="100%" border="0" cellspacing="0" cellpadding="0">';
        $lc_text .= implode("\n", $product_contents);
        $lc_text .= '
        </table>
    
    </td>
  </tr>
</table>
        ';
      }else{
        $lc_text = '<table border="0" cellpadding="0" cellspacing="0" class="productTable" width="100%">';
        $lc_text .= implode("\n", $product_contents);
        $lc_text .= '</table>';
      }

      $list_box_contents[$row][$column] = array('params' => 'class="productColumnSell'.($column<1?' first':'').($column==LISTING_NUM_PRODUCTS_PER_ROW-1?' last':'').'"',
                                                'text'  => $lc_text);
      $column ++;
      if ($column >= LISTING_NUM_PRODUCTS_PER_ROW) {
        $row++;
        $list_box_contents[$row] = array('params' => ($row%2==0?'class="productEvenRow"':'class="productOddRow"'));
        $column = 0;
      }
    }
    while ($column>0 && $column < LISTING_NUM_PRODUCTS_PER_ROW){
      $list_box_contents[$row][$column] = array('params' => 'class="productColumnSell'.($column<1?' first':'').'" height=100%',
                                                'text'  => '<table border="0" cellpadding="0" cellspacing="0" class="productTable" width="100%"><tr><td height=100%>&nbsp;</td></tr></table>');
      $column++;
    } 
    return $list_box_contents;
    }
    
  function process_rol() { 
    global $column_list, $currencies, $HTTP_GET_VARS, $cPath, $PHP_SELF;
    $listing_query = tep_db_query($this->listing_split->sql_query);
       
    $row = 0;
    $list_box_contents[$row] = array('params' => 'class="productFirstRow"');
    $column = 0;
    while ($listing = tep_db_fetch_array($listing_query)) {

      $list_box_contents[$row][] = array('params' => 'class="productModelCell"',
                                         'text'  => highlight_text($listing['products_model'], $search_keywords) . '&nbsp;');

      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $list_box_contents[$row][] = array('params' => 'class="productNameCell"',
                                           'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '&nbsp;</a>');
      } else {
        $list_box_contents[$row][] = array('params' => 'class="productNameCell"',
                                           'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '&nbsp;</a>');
      }
      
      $list_box_contents[$row][] = array('params' => 'class="productDescriptionCell"',
                                         'text'  => $listing['products_description_short'] . '&nbsp;');
      
      $list_box_contents[$row][] = array('params' => 'class="productManufacturersCell"',
                                         'text'  => '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;');
      
      if (tep_get_products_special_price($listing['products_id'])) {
        $list_box_contents[$row][] = array('params' => 'class="productPriceCell"',
                                           'text'  => '<span class="productPriceOld">' .  $currencies->display_price(tep_get_products_price($listing['products_id'], 1, $listing['products_price']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="productPriceSpecial">' . $currencies->display_price(tep_get_products_special_price($listing['products_id']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;');
      } else {
        $list_box_contents[$row][] = array('params' => 'class="productPriceCell"',
                                           'text'  => '<span class="productPriceCurrent">' . $currencies->display_price(tep_get_products_price($listing['products_id'], 1, $listing['products_price']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;');
      }
      
      $list_box_contents[$row][] = array('params' => 'class="productQuantityCell"',
                                         'text'  => $listing['products_quantity'] . '&nbsp;');
      
      $list_box_contents[$row][] = array('params' => 'class="productWeightCell"',
                                         'text'  => $listing['products_weight'] . '&nbsp;');
                                         
      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $list_box_contents[$row][] = array('params' => 'class="productImageCell"',
                                           'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;');
      } else {
        $list_box_contents[$row][] = array('params' => 'class="productImageCell"',
                                           'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;');
      }
      
      $list_box_contents[$row][] = array('params' => 'class="productButtonCell"',
                                         'text'  => '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_template_image_button('button_buy_now.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BUY_NOW, 'class="transpng"') . '</a>&nbsp;');
      
      $list_box_contents[$row][] = array('params' => 'class="productButtonCell"',
                                         'text'  => tep_draw_form('buynow', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array()) . 'action="buy_now"'), 'get') . tep_hide_session_id() . '<table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tr>
                      <td><a href="' .((isset($HTTP_GET_VARS['manufacturers_id']))?tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']):tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id'])) . '">' . TEXT_PRODUCT_DETAILS . '</a></td>
                      <td>' . TABLE_HEADING_QUANTITY . ':&nbsp;</td>
                      <td>' . tep_draw_input_field('qty', 1, 'style="WIDTH:35px;text-align:center;"') . tep_draw_hidden_field('products_id', $listing['products_id']) . tep_draw_hidden_field('action', 'buy_now') .  '</td>
                      <td>' . tep_template_image_submit('button_buy_now.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BUY_NOW, 'class="transpng"') . '</td>
                    </tr>
                  </table></form>');


      $row++;
      $list_box_contents[$row] = array('params' => ($row%2==0?'class="productEvenRow"':'class="productOddRow"'));
    }
    return $list_box_contents;
    }
  
  }
?>