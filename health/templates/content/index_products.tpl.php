    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td>
<?php
    // Get the category name and description
    $category_query = tep_db_query("select if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as categories_name, if(length(cd1.categories_heading_title), cd1.categories_heading_title, cd.categories_heading_title) as categories_heading_title, if(length(cd1.categories_description), cd1.categories_description, cd.categories_description) as categories_description, c.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "' and cd.affiliate_id = 0");
    $category = tep_db_fetch_array($category_query);

   if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
     $str = $category['categories_heading_title'];
   } else {
     $str = $category['categories_name'];
   }

  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>$str), array('params'=> 'align=right', 'text' => ''));
  new contentPageHeading($infobox_contents);
?>         
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?php
// optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($HTTP_GET_VARS['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, if(length(cd1.categories_name), cd1.categories_name, cd.categories_name) as name from " . TABLE_CATEGORIES_DESCRIPTION . " cd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . ",  " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd1 on cd1.categories_id = c.categories_id and cd1.language_id='" . (int)$languages_id ."' and cd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' where p.products_status = 1 and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and cd.affiliate_id = 0 and c.categories_status = 1 order by name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" LEFT join " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":'') . " where p.products_status = 1 and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id  " . ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'') . "  and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
        if (isset($HTTP_GET_VARS['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo tep_hide_session_id();
        echo '</form></td>' . "\n";
      }
    }

// Get the right image for the top-right
    $image = DIR_WS_TEMPLATE_IMAGES . 'spacer.gif';
    if (isset($HTTP_GET_VARS['manufacturers_id'])) {
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <td align="right"><?php if (is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $image)) echo tep_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          <?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_description'])) ) { ?>
          <tr>
            <td align="left" colspan="2" class="category_desc"><?php echo $category['categories_description']; ?></td>
          </tr>
          <?php } ?>
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


  
//========================= EXTRA PROPERTYES =================================
if (PRODUCTS_PROPERTIES == 'True'){
  $pid_sql = preg_replace('/select .*? from/i','select p.products_id from',$listing_sql);
  $pid_sql = preg_replace('/ order by .*$/i','',$pid_sql);
  $pids_array = array();
  $pids_r = tep_db_query($pid_sql);
  while ( $data=tep_db_fetch_array($pids_r) ) {
    $pids_array[] = $data['products_id'];
  }

// get possible values
  $result_values = array();
  $chng = -1;

  if ( count($pids_array)>0 ) {
    $prop_q = tep_db_query("SELECT distinct prp.properties_id, prp.properties_type, p2p.set_value, prd.properties_name FROM " . TABLE_PROPERTIES_TO_PRODUCTS . " p2p, " . TABLE_PROPERTIES . " prp, ".TABLE_PROPERTIES_DESCRIPTION . " prd WHERE p2p.products_id IN ('" . implode('\',\'',$pids_array) . "') and p2p.properties_id = prp.properties_id AND prp.mode LIKE '%filter%' AND prp.properties_type IN ( 0, 2, 3, 4 )  AND p2p.set_value <> '' AND prd.properties_id=prp.properties_id AND prd.language_id ='".(int)$languages_id."' AND p2p.language_id ='".(int)$languages_id."' ORDER BY prp.sort_order, prp.properties_id, p2p.set_value");
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
<?php
    echo '<table border="0" width="100%" cellspacing="2" cellpadding="5" class="productListing"><tr><td width="100%">&nbsp;&nbsp;<b>'.BOX_HEADING_PROPERTIES.'</b></td></tr>';
    echo tep_draw_form('adv_prop_find', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get').tep_hide_session_id();

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
    echo tep_get_all_get_params( array_merge(array_keys($result_values), array('error','exact','page')),true);
    echo tep_draw_hidden_field('exact', 'on');
    echo '<table cellspacing="2" cellpadding="2" border="0" align="right"><tr><td align=right>'.tep_template_image_submit('button_search.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_SEARCH, 'class="transpng"').'</td></tr></table>';
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
        <td><?php 
        if (PRODUCT_LISTING_MODE == 'Column'){
          include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING_COL); 
        }else{
          include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); 
        }
        ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
<?php
if (SUPPLEMENT_STATUS == 'True'){
?>

<?php 
  include(DIR_WS_MODULES . 'xsell_cat_products.php'); 
?>
<?php
}
?>

    </table>
