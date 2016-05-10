<?php
require_once(EBAY_DIR_EBATLIB . '/GetCategoriesRequestType.php' );
require_once(EBAY_DIR_EBATLIB . '/GetCategoriesResponseType.php' );
require_once(EBAY_DIR_EBATLIB . '/CategoryType.php' );
	
class ebay_categories{
  var $_version;

  function getRemoteVersion(){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_categories::getRemoteVersion()');
    $proxy = $core->get_proxy();

    $req = new GetCategoriesRequestType();
    $req->CategorySiteID = ebay_config::getEbaySiteID();
    $req->LevelLimit = 1;
    $req->DetailLevel = 'ReturnAll';

    $res = $proxy->GetCategories($req);
    if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
      $logger->info('Exit getRemoteVersion() ['.$res->Ack.'] got version '.$res->CategoryVersion);
      return $res->CategoryVersion;
    }else{
      $logger->notice('Exit getRemoteVersion() ['.$res->Ack.']');
      return false;
    }
  }
  
  function db_remove_version( $version ){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call getRemoteVersion::db_remove_version('.(int)$version.')');
    tep_db_query( "delete from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and version='".(int)$version."'");
  }

  function GetCategoryFeatures( $cat_ids ){
    require_once(EBAY_DIR_EBATLIB . '/GetCategoryFeaturesRequestType.php' );
    require_once(EBAY_DIR_EBATLIB . '/GetCategoryFeaturesResponseType.php' );

    $core = ebay_core::get();
    $logger = $core->get_logger();
    $proxy = $core->get_proxy();

    $working_cat = array();
    if ( is_array($cat_ids) ) {
      $working_cat = $cat_ids;
    }else{
      if (strpos($cat_ids, ',')===false) {
        $working_cat = array($cat_ids);
      }else{
        $working_cat = preg_split('/\s?,/', $cat_ids, -1, PREG_SPLIT_NO_EMPTY);
      }
    }
    $registry = $core->get_registry();
    $version = $registry->getValue('categories_version');
//TODO: need check outdate version! current in shop & version in $res->CategoryVersion :(. UPD. Oops this not same CategoryVersion... maybe this Features version? 
    $logger->info('Retrieve Categories Features for ['.implode(',',$working_cat).']');
    foreach($working_cat as $rCatID){
      $req = new GetCategoryFeaturesRequestType();
      $req->setCategoryID($rCatID);
      $req->setViewAllNodes(true);
      $req->setLevelLimit(10);
      $req->setDetailLevel('ReturnAll');
      $logger->info('Call GetCategoryFeatures CategoryID='.$rCatID.';');
      
      $res = $proxy->GetCategoryFeatures($req);
      if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
        $logger->info('GetCategoryFeatures ['.$res->Ack.']');
        $categories_array = $res->getCategory();
        $SiteDefaults = $res->getSiteDefaults();

        $def_MaxFlatShippingCost_v = 'null';
        $def_MaxFlatShippingCost = $SiteDefaults->getMaxFlatShippingCost();
        if ( is_object($def_MaxFlatShippingCost) ) {
          $def_MaxFlatShippingCost_v = $def_MaxFlatShippingCost->value;
        }

        $def_ReturnPolicyEnabled_v = 'null';
        $def_ReturnPolicyEnabled = $SiteDefaults->getReturnPolicyEnabled();
        if ( !is_null($def_ReturnPolicyEnabled) ) $def_ReturnPolicyEnabled_v = $def_ReturnPolicyEnabled?'1':'0';

        if ( is_array($categories_array) ) {
          foreach( $categories_array as $cat_info ) {
            $sql_arr = array( 'features_sync'=>1, 
                              'MaxFlatShippingCost'=>$def_MaxFlatShippingCost_v, 
                              'ReturnPolicyEnabled'=>$def_ReturnPolicyEnabled_v );
            $CategoryID = $cat_info->getCategoryID();

            $MaxFlatShippingCost = $cat_info->getMaxFlatShippingCost();
            if ( is_object($MaxFlatShippingCost) ) $sql_arr['MaxFlatShippingCost'] = $MaxFlatShippingCost->value;
            $ReturnPolicyEnabled = $cat_info->getReturnPolicyEnabled();
            if ( !is_null($ReturnPolicyEnabled) ) $sql_arr['ReturnPolicyEnabled'] = $ReturnPolicyEnabled?'1':'0';
            tep_db_perform( TABLE_EBAY_CATEGORIES, 
                            $sql_arr, 
                            'update',
                            "connector_id = ".(int)EBAY_CONNECTOR_ID." and version='".(int)$version."' and category_id='".(int)$CategoryID."'");
          }
        }
      }else{
        $logger->notice('GetCategoryFeatures ['.$res->Ack.']');
      }
    }
  }
  
  function GetCategory2CS($cat_ids){
    require_once(EBAY_DIR_EBATLIB . '/GetCategory2CSRequestType.php' );
    require_once(EBAY_DIR_EBATLIB . '/GetCategory2CSResponseType.php' );

    $core = ebay_core::get();
    $logger = $core->get_logger();
    $proxy = $core->get_proxy();

    $working_cat = array();
    if ( is_array($cat_ids) ) {
      $working_cat = $cat_ids;
    }else{
      if (strpos($cat_ids, ',')===false) {
        $working_cat = array($cat_ids);
      }else{
        $working_cat = preg_split('/\s?,/', $cat_ids, -1, PREG_SPLIT_NO_EMPTY);
      }
    }
    $registry = $core->get_registry();
    $version = $registry->getValue('categories_version');
    $logger->info('Retrieve Category To Characteristics mapping for ['.implode(',',$working_cat).']');
    foreach($working_cat as $rCatID){
      $req = new GetCategory2CSRequestType();
      $req->setCategoryID($rCatID);
      $req->setDetailLevel('ReturnAll');
      $logger->info('Call GetCategory2CS CategoryID='.$rCatID.';');
      
      $res = $proxy->GetCategory2CS($req);

      $logger->info('GetCategory2CS ['.$res->Ack.']');
      if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
        $MappedCategoryArray = $res->getMappedCategoryArray();
        if ( is_array($MappedCategoryArray) ) {
          foreach( $MappedCategoryArray as $CategoryType ) {
            $CategoryID = $CategoryType->getCategoryID();
            $char_map = array();
            $CharacteristicsSets = $CategoryType->getCharacteristicsSets();
            if ( is_array($CharacteristicsSets) ) foreach( $CharacteristicsSets as $SetType ) {
              $char_map[] = array( 'name'       => $SetType->getName(),
                                   'SetID'      => $SetType->getAttributeSetID(),
                                   'SetVersion' => $SetType->getAttributeSetVersion() );
            }
            
            tep_db_perform( TABLE_EBAY_CATEGORIES, 
                            array( 'CharacteristicsSets'=>serialize($char_map) ), 
                            'update',
                            "connector_id = ".(int)EBAY_CONNECTOR_ID." and version='".(int)$version."' and category_id='".(int)$CategoryID."'");
          }
        }
      }
    }
  }

  function do_update( $force=false ){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $proxy = $core->get_proxy();

    $logger->info('Call ebay_categories::do_update()'.($force?' FORCE':''));
    $remote_version = $this->getRemoteVersion();
    if ( $remote_version!=false ) {
      $registry = $core->get_registry();
      $current_version = $registry->getValue('categories_version');

      if ( $force==false && $current_version!==false && $current_version==$remote_version ){
        $logger->info('Have same version');
      }else{
        if ( $force ) {
          $logger->notice('Forced Categories tree reload: local='.$current_version.'; remote='.$remote_version);
        }else{
          $logger->notice('Categories tree version differ: local='.$current_version.'; remote='.$remote_version);
        }

        $this->db_remove_version($remote_version);
        $this->_version = $remote_version;

        $proxy = $core->get_proxy();
        $proxy->setHandler('CategoryType', array(& $this, 'db_save')); 
        $req = new GetCategoriesRequestType();
        $req->CategorySiteID = ebay_config::getEbaySiteID();
        $req->DetailLevel = 'ReturnAll';
			
			  $logger->info('Start download');
        $res = $proxy->GetCategories($req);
        if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
          $logger->info('Exit do_update() ['.$res->Ack.']: version '.$res->CategoryVersion.'; count '.$res->CategoryCount.'; update time='.$res->UpdateTime);
          $registry->setValue('categories_version', $res->CategoryVersion );
          $registry->setValue('categories_update_time', $res->UpdateTime );
          return $res->CategoryVersion;
        }else{
          $logger->notice('Exit do_update() ['.$res->Ack.']');
          return false;
        }
			  $proxy->setHandler('CategoryType', array(& $core, 'db_null_save'));
        
        $main_cat = ebay_config::getMainCategories();
        if ( count($main_cat)>0 ) {
          $logger->info('Retrieve Categories Features for ['.implode(',',$main_cat).']');
          foreach($main_cat as $rCatID){
            $req = new GetCategoryFeaturesRequestType();
            $req->setCategoryID($rCatID);
            $req->setViewAllNodes(true);
            $logger->info('Call GetCategoryFeatures CategoryID='.$rCatID.';');
            $proxy->setHandler('Category', array(& $this, 'db_save_feat'));
            $res = $proxy->GetCategoryFeatures($req);
            $logger->notice('GetCategoryFeatures ['.$res->Ack.']');
            if ($res->Ack=='Warning' || $res->Ack=='Success' ) {

            }
          }
          $proxy->setHandler('Category', array(& $core, 'db_null_save'));
        }
        

      }
    }
    $logger->info('Exit ebay_categories::do_update()');
  }

  function db_save( $type, & $Category ){
    $parent_id = $Category->getCategoryParentID();
    if ( is_array($parent_id) ) $parent_id = $parent_id[0];
    
    $sql_array = array( 'connector_id' => EBAY_CONNECTOR_ID,
                        'category_id' => $Category->CategoryID,
                        'parent_id' => (int)$parent_id,
                        'level' => intval( $Category->getCategoryLevel() ),
                        'leaf' => $Category->getLeafCategory()===true?1:0,
                        'version' => $this->_version,
                        'category_name' => $Category->getCategoryName(), 
                        'best_offer_enabled' => $Category->getBestOfferEnabled()===true?1:0,
                        'auto_pay_enabled' => $Category->getAutoPayEnabled()===true?1:0 );
    if ( $sql_array['parent_id'] == $sql_array['category_id'] ) $sql_array['parent_id']=0;
    tep_db_perform( TABLE_EBAY_CATEGORIES, $sql_array );
  }

  function get_tree( $from_id=0, $deep=1, $c_level=0, $ret=false ){
    if ( !is_array($ret) ) $ret = array();
    if ( $deep==0 ) return;
    if ( !is_array($from_id) ) $from_id = array($from_id);
    
    $core = ebay_core::get();
    $registry = $core->get_registry();
    $current_version = $registry->getValue('categories_version');

    $data_r = tep_db_query("select category_id, parent_id, level, leaf, category_name from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and parent_id in ('".implode("','",$from_id)."') and version='".(int)$current_version."' order by category_name");
    $next_deep = $deep-1;
    $next_level = $c_level+1;
    while( $data = tep_db_fetch_array($data_r) ){
      $ret[] = array( 'id'=> $data['category_id'], 'disabled'=>($data['leaf']==0), 'text'=> str_repeat(chr(160).chr(160), $c_level).$data['category_name'] );
      if ( $next_deep!=0 ) $ret = $this->get_tree( $data['category_id'], $next_deep, $next_level, $ret );
    }
    
    return $ret;
  }
  
  function _path_to_top( $cat_id ){
    $ret = array();
    $core = ebay_core::get();
    $registry = $core->get_registry();
    $current_version = $registry->getValue('categories_version');
    $data_r = tep_db_query("select category_id, parent_id, category_name from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and category_id='".(int)$cat_id."' and version='".(int)$current_version."' order by category_name");
    if ( tep_db_num_rows($data_r)!=0 ) {
      $ret_ = tep_db_fetch_array($data_r);
      if ( !empty($ret_['parent_id']) ) {
        $ret_2 = ebay_categories::_path_to_top( $ret_['parent_id'] );
        if ( is_array($ret_2) ) {
          foreach( $ret_2 as $ct ) $ret[] = $ct;
        }
      }
      $ret[] = $ret_;
    }
    return $ret;
  }
  
  function isLeaf( $cat_id ){
    $cat_info = ebay_categories::get_cat_info( $cat_id, false );
    return ($cat_info['leaf']==1);
  }
  
  function get_cat_info( $cat_id, $withParents=true ) {
    $core = ebay_core::get();
    $registry = $core->get_registry();
    $current_version = $registry->getValue('categories_version');
    $data_r = tep_db_query("select category_id, parent_id, level, leaf, category_name from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and category_id='".(int)$cat_id."' and version='".(int)$current_version."' order by category_name");
    if ( tep_db_num_rows($data_r)==0 ) {
      return false;
    }else{
      $ret = tep_db_fetch_array($data_r);
      if ($withParents) $ret['parents'] = ebay_categories::_path_to_top( $ret['parent_id'] );
    }
    return $ret;
  }
  
  function Category2CS( $catID ) {
    $core = ebay_core::get();
    $registry = $core->get_registry();
    $current_version = $registry->getValue('categories_version');
    $data = tep_db_fetch_array(tep_db_query("select CharacteristicsSets from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and category_id='".(int)$catID."' and version='".(int)$current_version."'"));
    $chars = unserialize($data['CharacteristicsSets']);
    if ( empty($data['CharacteristicsSets']) || $chars===false ) {
      $this->GetCategory2CS( $catID );
    }
    if ( $chars===false ) {
      $data = tep_db_fetch_array(tep_db_query("select CharacteristicsSets from ".TABLE_EBAY_CATEGORIES." where connector_id = ".(int)EBAY_CONNECTOR_ID." and category_id='".(int)$catID."' and version='".(int)$current_version."'"));
      $chars = unserialize($data['CharacteristicsSets']);
    } 
    return $chars;
  }

  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    $open_group = false;
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {

      $txt_output = tep_output_string($values[$i]['text'], array('&amp;' => '&', '"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;'));
      if ( isset($values[$i]['group']) && $values[$i]['group']=='open' ) {
        if ( $open_group ) $field.='</optgroup>';
        $field.='<optgroup label="'.$txt_output.'">';
        continue;
      } 
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
      if ( isset($values[$i]['disabled']) && $values[$i]['disabled']) {
        $field .= ' DISABLED';
      }

      $field .= '>' . $txt_output . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

}

?>