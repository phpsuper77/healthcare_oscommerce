    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td>
			<? if ($results_found > 0) : ?>
				<?
					$infobox_contents = array();
					$infobox_contents[] = array(array('text' =>HEADING_TITLE_2), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE_2, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
					new contentPageHeading($infobox_contents);
				?>
			<? else:?>
				<span class="pageHeading">Products meeting the search criteria</span><br />
				<br />
				<strong>No results were found for the the term '<?=$_GET['keywords'];?>'.  Browse our best sellers below.</strong>.<br />
				<br />
				<h3>Best Sellers<h3>
			<? endif;?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else{
$header_text = HEADING_TITLE_2;
}

//========================= EXTRA PROPERTYES =================================
if (PRODUCTS_PROPERTIES == 'True'){
// get possible values
  $result_values = array();
  $chng = -1;
  
  $prop_q = tep_db_query("SELECT distinct prp.properties_id, prp.properties_type, p2p.set_value, prd.properties_name " . $from_str . ", " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p, " . TABLE_PROPERTIES . " prp, ".TABLE_PROPERTIES_DESCRIPTION . " prd " . $where_str . " and p2p.properties_id = prp.properties_id AND prp.mode LIKE '%filter%' AND prp.properties_type IN ( 0, 2, 3, 4 )  AND p2p.set_value <> '' AND p2p.products_id = p.products_id AND prd.properties_id=prp.properties_id AND prd.language_id ='".(int)$languages_id."' AND p2p.language_id ='".(int)$languages_id."' ORDER BY prp.sort_order, prp.properties_id, p2p.set_value");
  
  if ( tep_db_num_rows($prop_q)>0 ) {
    while ( $data=tep_db_fetch_array($prop_q) ) {
      if ( $chng!=$data['properties_id'] ) { 
        $chng=$data['properties_id'];
        $result_values[$chng] = array( 'name'=>$data['properties_name'], 'type'=>intval($data['properties_type']), 'values'=>array() );
      }     
      if ($data['properties_type']=='3' ) {
        $tmp_arr = split("\n",trim($data['set_value']));
        foreach( $tmp_arr as $ki=>$val3) {
         $val3 = trim($val3);
         $result_values[$chng]['values'][] = $val3;         
        }
      }elseif (intval($data['properties_type'])==2){
        if ( $data['set_value']=='true' ) { $b_prop = PROPERTY_TRUE; } else { $b_prop = PROPERTY_FALSE; }
        $result_values[$chng]['values'][] = array( 'id'=>$data['set_value'], 'text'=>$b_prop );
      }else{
          $tmptext = $data['set_value'];
          if (intval($data['properties_type'])==0 || intval($data['properties_type'])==1) $tmptext = tep_cut_text( $tmptext, 70 );        
          $result_values[$chng]['values'][] = array( 'id'=>$data['set_value'], 'text'=>$tmptext );
      };
    }
    // normalize type 3
    foreach($result_values as $prop_id=>$field_data){
      if ( $field_data['type']==3 ) {
       $dup_array = $field_data['values'];
       $dup_array = array_flip( array_flip($dup_array) );
       $result_values[$prop_id]['values'] = array();
       foreach ($dup_array as $key=>$value) { $result_values[$prop_id]['values'][] = array( 'id'=>$value, 'text'=>$value ); }
      }
    }    
  }
  //ok draw search form
  if (count($result_values)>0) {
?>
   <tr>
      <td>
<?
    echo '<table border="0" width="100%" cellspacing="2" cellpadding="5" class="productListing"><tr><td width="100%">&nbsp;&nbsp;<b>'.BOX_HEADING_PROPERTIES.'</b></td></tr>';
    echo tep_draw_form('adv_prop_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get').tep_hide_session_id();
    
    $select_all = array(array('id'=>'','text'=>PROPERTY_ALL));
    echo '<tr><td>';    
    foreach($result_values as $prop_id=>$field_data){
      echo '<table cellspacing="2" cellpadding="2" border="0" style="float:left;"><tr><td>&nbsp;'.$field_data['name'].'</td><td>';
      $ctrl_name = $prop_id.( (($field_data['type']==3)||($field_data['type']==4))?'[]':'' );
         $def = '';
         if (isset($GLOBALS[$prop_id]) && is_array($GLOBALS[$prop_id]) ) $def=$GLOBALS[$prop_id][0];
      echo tep_draw_pull_down_menu( $ctrl_name, array_merge($select_all,$field_data['values']),$def,'');
      echo '&nbsp;</td></tr></table>';
    }

    echo tep_get_all_get_params( array_merge(array_keys($result_values), array('error')),true);
    echo tep_draw_hidden_field('exact', 'on');
    echo '<table cellspacing="2" cellpadding="2" border="0" align="right"><tr><td colspan=2 align=right>'.tep_template_image_submit('button_search.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_SEARCH, 'class="transpng"').'</td></tr></table>';
    echo '</td></tr>';
    echo '</form></table>';
?>
     </td>
   </tr>
<?  
  }
  unset($result_values);
};
//\========================= EXTRA PROPERTYES =================================


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
			
			  if (PRODUCT_LISTING_MODE == 'Column'){
				$search_results_page = true;
				include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_COL); 
			  }else{
				include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); 
			  }
			
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
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
      <tr>
        <td class="main">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="left" class="main" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false) . '">' . tep_template_image_button('button_back.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_BACK, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?>
        </td>
      </tr>
    </table>
