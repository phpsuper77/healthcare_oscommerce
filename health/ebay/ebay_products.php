<?php

class ebay_products {
  var $_db_res=null;
  var $_db_data=false;
  var $stat = array('text'=>'',
                    'status_text'=>array(),
                    'skipped'=>0,
                    'downloaded'=>0,
                    'update_qty_price' => 0,
                    'end_item' => 0,
                    'update' => 0,
                    'activate' => 0,
                    'checked'=>0);

  function db_open( $sku=false ){
    global $languages_id;
    if ( empty($sku) ) {
      $aSKU = array();
    } elseif( is_array($sku) ) {
      $aSKU = $sku;
    } else {
      $aSKU = preg_split('/[,;]/', $sku, -1, PREG_SPLIT_NO_EMPTY);
    }

    if (EBAY_PRODUCT_LINK_TYPE=='uprid') {
      $_db_res = tep_db_query(
        "select ".
          "i.products_id, ".
          "i.inventory_id, ".
          "i.ebay_item_id, ".
          "i.ebay_category_id, ".
          "i.ebay_category_id2, ".
          "i.ebay_price, ".
          "i.ebay_spec, ".
          "IF( length(i.ebay_product_title), ".
              "i.ebay_product_title, ".
              "if( length(i.products_name), i.products_name, pd.products_name) ".
          ") as products_name, ".
          "pd.products_ebay_description, pd.products_description, ".
          "p.products_image, ".
          "i.products_quantity, ".
          "p.products_status, ".
          "p.products_weight ".
        "from ".TABLE_INVENTORY." i ".
        "left join ".TABLE_PRODUCTS." p on p.products_id=i.prid ".
        "left join ".TABLE_PRODUCTS_DESCRIPTION." pd on pd.products_id=p.products_id and pd.language_id='".(int)$languages_id."' ".
        "where i.datafeed_ebay=1 ".( count($aSKU)>0?" and i.products_id in ('".implode("','",$aSKU)."') ":" " ));
      if ( is_object($this) ) $this->_db_res = $_db_res;
      return $_db_res;
    }else{
      die(EBAY_PRODUCT_LINK_TYPE.' '.__FILE__.' '.__LINE__);
    }
  }

  function switchoff($sku, $reason=''){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->alert('ebay_products::switchoff( $sku='.$sku.', $reason='.$reason.' )');
/*
    tep_db_query("update ".TABLE_INVENTORY." set datafeed_ebay=0 where products_id='".tep_db_input($sku)."'");
*/
    tep_db_query("delete from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                           "connector_id='".ebay_config::getEbaySiteID()."' and ".
                           "epl_sku='".tep_db_input($sku)."'");
    if ( !empty($reason) ) $this->stat['text'] .= 'SKU='.$sku." marked as not in datafeed Reason='{$reason}'\n";
  }

  function db_fetch( $_db_res=false ){
    if ( is_object($this) ) $_db_res = $this->_db_res;
    if ( is_resource($_db_res) ) {
      $_db_data = tep_db_fetch_array( $_db_res );
      if ( is_object($this) ) {
        if ( !is_array($_db_data) ) {
          $this->_db_res = null ;
        }
        $this->_db_data = $_db_data;
      }
      return $_db_data;
    } else {
      return false;
    }
  }

  function maintain(){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::maintain()');
    tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set epl_check=1 where ".
                 "connector_id='".ebay_config::getEbaySiteID()."'");
    $this->db_open();
    while( $this->db_fetch() ) {
      $this->stat['checked']++;
      $diff_state = 'check';
      $list_r = tep_db_query("select * from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                             "connector_id='".ebay_config::getEbaySiteID()."' and ".
                             "epl_sku = '".tep_db_input($this->_db_data['products_id'])."'");
      if( $list = tep_db_fetch_array($list_r) ) {
        if ($list['epl_state']=='exclusive'){
          $logger->notice('Found SKU='.$this->_db_data['products_id'].'; marked as "exclusive", '.
                          'but now active in shop');
        } elseif ( $list['epl_state']=='hold' ) {
          // not active on ebay
          if ( (int)$this->_db_data['products_quantity']>0 &&
               (int)$this->_db_data['products_status']==1 ) {
            // but active in shop
            //$diff_state = 'revise';
            $diff_state = 'relist';
          }
        } elseif ($list['epl_state']=='active'){
          // active on ebay
          if ( (int)$this->_db_data['products_quantity']<1 ||
               (int)$this->_db_data['products_status']==0 ) {
            // but not sellabe qty in stock or switched off by status
            $diff_state = 'hold';
          } else {
            // active product on both side, check full update and if not check qty & price sync
            $Item = $this->_makeItem( $this->_db_data['products_id'], $this->_db_data, 'maintain' );
            $itemHash = $this->makeItemHash($Item);
            // normalize items w/o hash - new one or created before this update
            if ( empty($list['data_hash']) && $itemHash!==false ) {
              $list['data_hash'] = $itemHash;
              tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set data_hash='".tep_db_input($itemHash)."' where epl_id='".(int)$list['epl_id']."'");
            }
            if ( $itemHash!==false && $list['data_hash']!=$itemHash ) {
              $diff_state = 'revise';
            }else{
              if ( $this->_db_data['products_quantity']!=$list['epl_quantity'] ) {
                $diff_state = 'reviseinventory';
              }else{
                $_price = new ebay_amount($Item->StartPrice);
                if ( number_format($list['epl_price'],2,'.','') != number_format($_price->value,2,'.','') ||
                     $list['epl_price_currency'] != $_price->currencyID ){
                  // last ebay price differ then generated
                  $diff_state = 'reviseinventory';
                }
              }
            }
          }
        }elseif($list['epl_state']=='local'){
          // leave alone for a while
          $diff_state = $list['epl_delta_state'];
        }else{
          $logger->err('Unknown state SKU='.$this->_db_data['products_id'].'; epl_state='.$list['epl_state'].';');
        }
        //'active', 'hold', 'exclusive'
        //'check', 'revise', 'reviseinventory', 'relist', 'hold'
        $logger->info('Result Check for SKU='.$this->_db_data['products_id'].'; ['.$diff_state.']');
        if ( !empty($diff_state) ) {
          tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set ".
                         "epl_check=0, epl_delta_state='".tep_db_input($diff_state)."' ".
                       "where ".
                         "connector_id='".ebay_config::getEbaySiteID()."' and ".
                         "epl_sku = '".tep_db_input($this->_db_data['products_id'])."'");
        }
      } else {
        // item not found in product list - not added or not linked
        if ( !empty($this->_db_data['ebay_category_id']) ) {
          $logger->info('Found SKU='.$this->_db_data['products_id'].'; '.
                        'with CategoryID='.$this->_db_data['ebay_category_id'].' set state "verifyadd"');
          $sql_array = array( 'connector_id'=>ebay_config::getEbaySiteID(),
                              'epl_sku' => $this->_db_data['products_id'],
                              'epl_state' => 'local',
                              'epl_delta_state' => 'verifyadd' );
          tep_db_perform(TABLE_EBAY_PRODUCTS_LIST, $sql_array);
        }else{
          $logger->info('Skip SKU='.$this->_db_data['products_id'].'; not added');
        }
      }
    }
    // make something with not checked products (on ebay side products)
    // 1. products active on ebay but in shop datafeed flag off
      // skip all hard action
    /*
    $postcheck_r = tep_db_query("select * from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                                 "connector_id='".ebay_config::getEbaySiteID()."' and ".
                                 "epl_check=1 and ".
                                 "epl_state='active'");
    while( $postcheck = tep_db_fetch_array($postcheck_r) ) {

    }
    */
      // and just mark them as exclusive - out of control
      //TODO: logic for exclusive (only in ebay listing) products
    tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set ".
                   "epl_check=0, epl_state='exclusive', epl_delta_state='check' ".
                 "where ".
                   "epl_check=1 and ".
                   "epl_state='active' and ".
                   "connector_id='".ebay_config::getEbaySiteID()."'");
    if ( function_exists('tep_db_affected_rows') ) {
      $applied_rows = tep_db_affected_rows();
    } else {
      $applied_rows = mysql_affected_rows();
    }
    if ( (int)$applied_rows>0 ) {
      $logger->notice('Some ['.$applied_rows.'] of "active" item on ebay switched to "exclusive" state. Marked as not datafeed products');
    }
    // 2. rest "spam list"  - out of control
    tep_db_query("update ".TABLE_EBAY_PRODUCTS_LIST." set ".
                   "epl_check=0, epl_delta_state='check' ".
                 "where ".
                   "epl_check=1 and ".
                   "connector_id='".ebay_config::getEbaySiteID()."'");
    if ( function_exists('tep_db_affected_rows') ) {
      $applied_rows = tep_db_affected_rows();
    } else {
      $applied_rows = mysql_affected_rows();
    }
    if ( (int)$applied_rows>0 ) {
      $logger->notice('Out of control ("exclusive") products ['.$applied_rows.']');
    }

    $logger->info('Exit ebay_products::maintain()');
  }

  function process_diffs(){
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::process_diffs()');
    $list_r = tep_db_query("select * from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                           "connector_id='".ebay_config::getEbaySiteID()."' and ".
                           "epl_delta_state!='check'");
    if ( tep_db_num_rows($list_r)==0 ) {
      //$this->stat['text'] .= 'No changes found'."\n";
    } else {
      while( $list = tep_db_fetch_array($list_r) ) {
        switch ($list['epl_delta_state']) {
          case 'reviseinventory':
            $this->ReviseInventoryStatus( $list['epl_sku'] );
            // call not support status yet
            $this->stat['update_qty_price']++;
            break;
          case 'hold':
            $status = $this->EndItem( '' /*$list['epl_item_id']*/, $list['epl_sku'] );
            if ( $status==EBAY_PRODUCT_STATUS_OK ) {
              $this->stat['end_item']++;
            }
            break;
          case 'revise':
            $status = $this->ReviseItem( $list['epl_sku'] );
            if ( $status==EBAY_PRODUCT_STATUS_OK ) {
              $this->stat['update']++;
            }elseif( $status==EBAY_PRODUCT_STATUS_SKIPPED ){
              $this->stat['skipped']++;
            }
            break;
          case 'relist':
            $status = $this->RelistItem( $list['epl_sku'] );
            if ( $status==EBAY_PRODUCT_STATUS_OK ) {
              $this->stat['activate']++;
            }elseif( $status==EBAY_PRODUCT_STATUS_SKIPPED ){
              $this->stat['skipped']++;
            }
            break;
          case 'verifyadd':
            $status = $this->VerifyAdd( $list['epl_sku'] );
            if ( $status==EBAY_PRODUCT_STATUS_OK ) {
              $status = $this->Add( $list['epl_sku'] );
              if ( $status==EBAY_PRODUCT_STATUS_OK ) {
                $sql_array = array('epl_delta_state' => 'check',
                                   'epl_state' => 'active');
                tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                               $sql_array,
                               'update',
                                 "connector_id='".ebay_config::getEbaySiteID()."' and ".
                                 "epl_sku='".tep_db_input($list['epl_sku'])."'"
                               );
                $this->stat['activate']++;
              }else{
                $this->stat['skipped']++;
                $this->switchoff( $list['epl_sku'], 'Add Product Call - FAIL' );
              }
            }elseif( $status==EBAY_PRODUCT_STATUS_FAIL ){
              $this->switchoff( $list['epl_sku'], 'VerifyAdd Product Call - FAIL' );
            }else{
              $this->stat['skipped']++;
              // skip - silent switch off - errors in other array
            }
            break;
          default:
            $logger->err('Teach me handle '.$list['epl_delta_state']);
            break;
        }
      }
    }
    $logger->info('Exit ebay_products::process_diffs()');
  }

  function VerifyAdd( $SKU ){
    $status = EBAY_PRODUCT_STATUS_SKIPPED;
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::VerifyAdd( SKU='.$SKU.' )');

    require_once EBAY_DIR_EBATLIB.'/VerifyAddFixedPriceItemRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/VerifyAddFixedPriceItemResponseType.php';

    $data = ebay_products::db_fetch( ebay_products::db_open( $SKU ) );
    if ( is_array($data) ){
      $item = $this->_makeItem( $SKU, $data );
      if ( !is_object($item) ) return EBAY_PRODUCT_STATUS_SKIPPED;
      
      $skip_next = false;
      if ( $item->Quantity<1 ) {
        $item->Quantity = 1;
        $skip_next = true;
        /* skip call verify, this item not for add */
        return EBAY_PRODUCT_STATUS_SKIPPED;
      }

      $req = new VerifyAddFixedPriceItemRequestType();
      $req->Item = $item;
      $proxy = $core->get_proxy();
      $res = $proxy->VerifyAddFixedPriceItem($req);

      if ( $res->Ack=='Success' || $res->Ack=='Warning' ) {
        $logger->info('VerifyAddFixedPriceItem ['.$res->Ack.'].');
        $status = EBAY_PRODUCT_STATUS_OK;
        if ( $skip_next ) $status = EBAY_PRODUCT_STATUS_SKIPPED; // dead code
      }else{
        $status = EBAY_PRODUCT_STATUS_FAIL;
        $logger->notice('VerifyAddFixedPriceItem Item.SKU='.$item->SKU.' ['.$res->Ack.']');
        $this->stat['text'] .= 'VerifyAddFixedPriceItem Item.SKU='.$item->SKU.' ['.$res->Ack.']'."\n";
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
            $this->stat['text'] .= ' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                                   '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage."\n";
          }
        }
      }
    }else{
      $logger->info('Empty data set');
    }

    $logger->info('Exit from ebay_products::VerifyAdd status='.$status);
    return $status;
  }
  
  function Add( $SKU ){
    $status = EBAY_PRODUCT_STATUS_SKIPPED;
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::VerifyAdd( SKU='.$SKU.' )');

    require_once EBAY_DIR_EBATLIB.'/AddFixedPriceItemRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/AddFixedPriceItemResponseType.php';

    $data = ebay_products::db_fetch( ebay_products::db_open( $SKU ) );
    if ( is_array($data) ){
      $item = $this->_makeItem( $SKU, $data );
      if ( !is_object($item) ) return EBAY_PRODUCT_STATUS_SKIPPED;

      $logger->info('Call AddFixedPriceItem. SKU='.$item->SKU.'; ItemID='.$item->ItemID.';');
      $req = new AddFixedPriceItemRequestType();
      $req->setItem($item);
      $proxy = $core->get_proxy();
      $res = $proxy->AddFixedPriceItem($req);
      if ( $res->Ack=='Success' || $res->Ack=='Warning' ) {
        $logger->info('AddFixedPriceItem ['.$res->Ack.']. Got map SKU='.$res->SKU.'; ItemID='.$res->ItemID.';');

        $logger->info('Set Inventory.ebay_item_id for products_id='.$data['products_id'].' new ItemID='.$res->ItemID.'; '.
                           'old ItemID='.$data['ebay_item_id'].';');
        tep_db_query("update ".TABLE_INVENTORY." set ebay_item_id='".tep_db_input($res->ItemID)."' where products_id='".tep_db_input($item->SKU)."'");
// update TABLE_EBAY_PRODUCTS_LIST after add - prevent ReviseInventoryStatus call
// if this not help - call GetSellerList - full or limited by sku
        $CurrentPrice = new ebay_amount($item->StartPrice);

        $itemHash = $this->makeItemHash($item);
        $sql_array = array(
          'epl_item_id' => $res->ItemID,
          'data_hash' => (empty($itemHash)?'':$itemHash),
          'epl_quantity' => $item->getQuantity(),
          'epl_price' => $CurrentPrice->value,
          'epl_price_currency' => $CurrentPrice->currencyID,
          'epl_shop_price' => $CurrentPrice->getConverted(),
          'epl_shop_price_currency' => ebay_config::defaultCurrency(),
          'epl_state'=>'active',
          'epl_delta_state'=>'check'
        );
        if ( is_object($item->PrimaryCategory) && !is_null($item->PrimaryCategory->CategoryID) ) {
          $sql_array['epl_category_id']=$item->PrimaryCategory->CategoryID;
        }else{
          $sql_array['epl_category_id']='';
        }
        if ( is_object($item->SecondaryCategory) && !is_null($item->SecondaryCategory->CategoryID) ) {
          $sql_array['epl_category_id2']=$item->SecondaryCategory->CategoryID;
        }else{
          $sql_array['epl_category_id2']='';
        }

        tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                       $sql_array,
                       'update',
                         "epl_sku='".tep_db_input($item->SKU)."' and ".
                         "connector_id='".ebay_config::getEbaySiteID()."'"
                       );
        $status = EBAY_PRODUCT_STATUS_OK;
      }else{
        $status = EBAY_PRODUCT_STATUS_FAIL;
        $logger->err('AddFixedPriceItem ['.$res->Ack.']. Item.SKU='.$item->SKU);
        $this->stat['text'] .= 'AddFixedPriceItem Item.SKU='.$item->SKU.' ['.$res->Ack.']'."\n";
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->err(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                           '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
            $this->stat['text'] .= ' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                                   '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage."\n";
          }
        }
      }
    }else{
      $logger->info('Empty data set');
    }

    $logger->info('Exit from ebay_products::VerifyAdd status='.$status);
    return $status;
  }

  function EndItem( $ItemID='', $SKU='' ){
    $status = EBAY_PRODUCT_STATUS_SKIPPED;
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::EndItem( ItemID='.$ItemID.', SKU='.$SKU.' )');

    if ( !empty($SKU) || !empty($ItemID) ) {
      require_once(EBAY_DIR_EBATLIB.'/EndFixedPriceItemRequestType.php');
      require_once(EBAY_DIR_EBATLIB.'/EndFixedPriceItemResponseType.php');

      $req = new EndFixedPriceItemRequestType();
      if (!empty($SKU)) $req->setSKU($SKU);
      if (!empty($ItemID)) $req->setItemID($ItemID);
      $req->setEndingReason('NotAvailable');

      $proxy = $core->get_proxy();
      $res = $proxy->EndFixedPriceItem( $req );
      if ( $res->Ack=='Success' || $res->Ack=='Warning' ) {
        $status = EBAY_PRODUCT_STATUS_OK;
        $logger->info('EndFixedPriceItem ['.$res->Ack.'].');

        tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                       array('epl_state'=>'hold', 'epl_delta_state'=>'check'),
                       'update',
                         (!empty($SKU)?"epl_sku='".tep_db_input($SKU)."' and ":'').
                         (!empty($ItemID)?"epl_item_id='".tep_db_input($ItemID)."' and ":'').
                         "connector_id='".ebay_config::getEbaySiteID()."'"
                       );
      }else{
        $status = EBAY_PRODUCT_STATUS_FAIL;
        $logger->notice('EndFixedPriceItem SKU='.$req->getSKU().'; ItemID='.$req->getItemID().'; ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
          }
        }
      }
    }

    $logger->info('Exit from ebay_products::EndItem');
    return $status;
  }
  
  function ReviseItem($SKU){
    $status = EBAY_PRODUCT_STATUS_SKIPPED;
    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::ReviseItem( SKU='.var_export($SKU,true).' )');

    require_once(EBAY_DIR_EBATLIB.'/ReviseFixedPriceItemRequestType.php');
    require_once(EBAY_DIR_EBATLIB.'/ReviseFixedPriceItemResponseType.php');

    $data = ebay_products::db_fetch( ebay_products::db_open( $SKU ) );
    if ( is_array($data) ){
      $item = $this->_makeItem( $SKU, $data );
      if ( !is_object($item) ) return EBAY_PRODUCT_STATUS_SKIPPED;
      
      $req = new ReviseFixedPriceItemRequestType();
      $req->setItem($item);
      $proxy = $core->get_proxy();
      $res = $proxy->ReviseFixedPriceItem($req);
      if ( $res->Ack=='Success' || $res->Ack=='Warning' ) {
        $status = EBAY_PRODUCT_STATUS_OK;
        $normalize_shadow = array();
        $newItemID = $res->ItemID;
        $oldItemID = $item->getItemID();
        if( !empty($newItemID) && $oldItemID!=$newItemID ) {
          tep_db_query("update ".TABLE_INVENTORY." set ebay_item_id='".tep_db_input($newItemID)."' where products_id='".tep_db_input($item->getSKU())."'");
          $normalize_shadow['epl_item_id'] = $newItemID; 
        }

        $itemHash = $this->makeItemHash($item);
        $normalize_shadow['data_hash'] = (empty($itemHash)?'':$itemHash);
        tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                       $normalize_shadow,
                       'update',
                         "epl_sku='".tep_db_input($SKU)."' and ".
                         ( !empty($oldItemID)?"epl_item_id='".tep_db_input($oldItemID)."' and ":'').
                         "connector_id='".ebay_config::getEbaySiteID()."'"
                       );

        $logger->info('ReviseFixedPriceItem ['.$res->Ack.']. newItemID='.$newItemID.'; Old ItemID='.$oldItemID.';');
      }else{
        $status = EBAY_PRODUCT_STATUS_FAIL;
        $logger->notice('ReviseFixedPriceItem SKU='.$item->getSKU().'; ItemID='.$item->getItemID().'; ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
          }
        }
      }
    }
    $logger->info('Exit ebay_products::ReviseItem()');
    return $status;
  }
  
  function RelistItem($SKU){
    $status = EBAY_PRODUCT_STATUS_SKIPPED;

    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::RelistItem( SKU='.var_export($SKU,true).' )');

    require_once(EBAY_DIR_EBATLIB.'/RelistFixedPriceItemRequestType.php');
    require_once(EBAY_DIR_EBATLIB.'/RelistFixedPriceItemResponseType.php');

    $data = ebay_products::db_fetch( ebay_products::db_open( $SKU ) );
    if ( is_array($data) ){
      $item = $this->_makeItem( $SKU, $data );
      if ( !is_object($item) ) return EBAY_PRODUCT_STATUS_SKIPPED;
      
      $item->RelistLink = true;
      $req = new RelistFixedPriceItemRequestType();
      $req->setItem($item);
      $proxy = $core->get_proxy();
      $res = $proxy->RelistFixedPriceItem($req);
      if ( $res->Ack=='Success' || $res->Ack=='Warning' ) {
        $status = EBAY_PRODUCT_STATUS_OK;
        $normalize_shadow = array();
        $newItemID = $res->ItemID;
        $oldItemID = $item->getItemID();
        if( !empty($newItemID) && $oldItemID!=$newItemID ) {
          tep_db_query("update ".TABLE_INVENTORY." set ebay_item_id='".tep_db_input($newItemID)."' where products_id='".tep_db_input($item->getSKU())."'");
          $normalize_shadow['epl_item_id'] = $newItemID; 
        }

        $itemHash = $this->makeItemHash($item);
        $normalize_shadow['data_hash'] = (empty($itemHash)?'':$itemHash);
        $normalize_shadow['epl_state'] = 'active';
        $normalize_shadow['epl_delta_state'] = 'check';
        if ( is_object($item->PrimaryCategory) && !is_null($item->PrimaryCategory->CategoryID) ) {
          $normalize_shadow['epl_category_id']=$item->PrimaryCategory->CategoryID;
        }else{
          $normalize_shadow['epl_category_id']='';
        }
        if ( is_object($item->SecondaryCategory) && !is_null($item->SecondaryCategory->CategoryID) ) {
          $normalize_shadow['epl_category_id2']=$item->SecondaryCategory->CategoryID;
        }else{
          $normalize_shadow['epl_category_id2']='';
        }

        tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                       $normalize_shadow,
                       'update',
                         "epl_sku='".tep_db_input($SKU)."' and ".
                         ( !empty($oldItemID)?"epl_item_id='".tep_db_input($oldItemID)."' and ":'').
                         "connector_id='".ebay_config::getEbaySiteID()."'"
                       );

        $logger->info('RelistFixedPriceItem ['.$res->Ack.']. newItemID='.$newItemID.'; Old ItemID='.$oldItemID.';');
      }else{
        $status = EBAY_PRODUCT_STATUS_FAIL;
        $logger->notice('RelistFixedPriceItem SKU='.$item->getSKU().'; ItemID='.$item->getItemID().'; ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
          }
        }
      }
    }
    $logger->info('Exit ebay_products::RelistItem()');
    return $status;
  }

  function ReviseInventoryStatus( $sku ) {
    if ( empty($sku) ) {
      $aSKU = array();
    } elseif( is_array($sku) ) {
      $aSKU = $sku;
    } else {
      $aSKU = preg_split('/[,;]/', $sku, -1, PREG_SPLIT_NO_EMPTY);
    }

    $core = ebay_core::get();
    $logger = $core->get_logger();
    $logger->info('Call ebay_products::ReviseInventoryStatus('.var_export($aSKU,true).')');

    require_once EBAY_DIR_EBATLIB.'/ReviseInventoryStatusRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/ReviseInventoryStatusResponseType.php';
    require_once EBAY_DIR_EBATLIB.'/InventoryStatusType.php';

    $proxy = $core->get_proxy();
    $revise_list_r = ebay_products::db_open( $aSKU );
    while( $revise_list = ebay_products::db_fetch($revise_list_r) ) {
      $Item = $this->_makeItem( $revise_list['products_id'], $revise_list, 'maintain' );
      $revise = array();
      $InventoryStatus = new InventoryStatusType();
      $InventoryStatus->setSKU( $Item->getSKU() );
      $InventoryStatus->setItemID( $Item->getItemID() );
      $InventoryStatus->setQuantity( $Item->getQuantity() );
      $InventoryStatus->setStartPrice( $Item->getStartPrice() );
      $revise[] = $InventoryStatus;
      //$logger->info('Count items in call '.count($revise));

      $req = new ReviseInventoryStatusRequestType();
      $req->setInventoryStatus( $revise );
      $res = $proxy->ReviseInventoryStatus( $req );
      if ( $res->Ack=='Success' ) {
        $logger->info('ReviseInventoryStatus ['.$res->Ack.']');
        if ( is_array($res->InventoryStatus) ) {
          foreach( $res->InventoryStatus as $InventoryStatusType ) {
            $objPrice = $InventoryStatusType->getStartPrice();
            $logger->info("\tSKU=".$InventoryStatusType->getSKU().
                          "\tItemID=".$InventoryStatusType->getItemID().
                          "\tQuantity=".$InventoryStatusType->getQuantity().
                          "\tStartPrice=".(is_object($InventoryStatusType->getStartPrice())?
                                      $objPrice->getTypeValue()
                                      :'')
                          );
          }
        }
        // update local mirror
        // trust success response and use request values, response qty-looks like initial (on addItem value ?)
        // and response price not contain currency attribute (ebay site default value ?)
        $sql_array = array(
          'epl_quantity' => $Item->getQuantity(),
          'epl_delta_state' => 'check'
        );
        if ( is_object($InventoryStatusType->getStartPrice()) ) {
          //$CurrentPrice = $InventoryStatusType->getStartPrice();
          $CurrentPrice = $Item->getStartPrice();
          $CurrentPrice = new ebay_amount( $CurrentPrice );
          $sql_array_2 = array(
            'epl_price' => $CurrentPrice->value,
            'epl_price_currency' => $CurrentPrice->currencyID,
            'epl_shop_price' => $CurrentPrice->getConverted(),
            'epl_shop_price_currency' => ebay_config::defaultCurrency(),
          );
          $sql_array = array_merge($sql_array, $sql_array_2);
        }
        tep_db_perform(TABLE_EBAY_PRODUCTS_LIST,
                       $sql_array,
                       'update',
                         "epl_sku='".tep_db_input($InventoryStatusType->getSKU())."' and ".
                         "connector_id='".ebay_config::getEbaySiteID()."'"
                       );
      }else{
        $logger->err('ReviseInventoryStatus ['.$res->Ack.']');
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $sku_info = '';
            if ( is_array($ErrorType->ErrorParameters) ) {
              foreach( $ErrorType->ErrorParameters as $ErrorParameterType ) {
                if ( !empty($sku_info) ) $sku_info.=';';
                $sku_info .= $ErrorParameterType->getValue();
              }
            }
            $logger->err($sku_info.' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                                  '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
          }
        }
      }
    }


    $logger->info('Exit ebay_products::ReviseInventoryStatus()');
    return EBAY_PRODUCT_STATUS_OK;
  }

  function GetSellerList( $sku='' ){
    $core = ebay_core::get();

    $logger = $core->get_logger();
    $logger->info('Start ebay_products::GetSellerList()');

    $proxy = $core->get_proxy();

    require_once EBAY_DIR_EBATLIB.'/GetSellerListRequestType.php';
    require_once EBAY_DIR_EBATLIB.'/GetSellerListResponseType.php';
    require_once EBAY_DIR_EBATLIB.'/PaginationType.php';
    require_once EBAY_DIR_EBATLIB.'/SKUArrayType.php';

    if ( empty($sku) ) {
      $aSKU = array();
    } elseif( is_array($sku) ) {
      $aSKU = $sku;
    } else {
      $aSKU = preg_split('/[,;]/', $sku, -1, PREG_SPLIT_NO_EMPTY);
    }

    //$GranularityLevel = 'Medium';
    $GranularityLevel = 'Coarse';
    $req = new GetSellerListRequestType();
    $req->setDetailLevel('ReturnAll');
    $req->setGranularityLevel($GranularityLevel);
    if ( count($aSKU)>0 ) {
      $SKUArray = new SKUArrayType();
      $SKUArray->setSKU($aSKU);
      $req->setSKUArray($SKUArray);
    }
    $pagination = new PaginationType();
    $pagination->setPageNumber(1);
    $req->setPagination( $pagination );
    
    $req->setEndTimeFrom( date('Y-m-d',strtotime("-1 month")).' 23:59:00Z' );
    $req->setEndTimeTo( date('Y-m-d',strtotime("+2 month")).' 23:59:00Z' );

    do{
      $objPadination = $req->getPagination();
      $logger->info('GetSellerListRequest GranularityLevel='.$GranularityLevel.'; Pagination.PageNumber='.$objPadination->getPageNumber().'; SKUArray='.var_export($aSKU,true).';');
      $res = $proxy->GetSellerList($req);
      if ($res->Ack=='Warning' || $res->Ack=='Success' ) {
        $objPadinationResp = $res->getPaginationResult();
        $logger->info('GetSellerListResponse ['.$res->Ack.'] PageNumber='.$res->getPageNumber().'; PaginationResult.TotalNumberOfPages='.$objPadinationResp->getTotalNumberOfPages().';');
        $ItemArray = $res->getItemArray();
        if ( !is_array($ItemArray) ) $ItemArray = array();
        foreach( $ItemArray as $ItemType ) {
          $_item_info = array();
          $_item_info['Revised'] = 0;
          $_item_info['ItemID'] = $ItemType->getItemID();
          $_item_info['SKU'] = $ItemType->getSKU();
          $_item_info['Quantity'] = $ItemType->getQuantity();
          $PrimaryCategory = $ItemType->getPrimaryCategory();
          $_item_info['CategoryID'] = $PrimaryCategory->getCategoryID();
          $_item_info['CategoryID2'] = '';
          $SecondaryCategory = $ItemType->getSecondaryCategory();
          if ( is_object($SecondaryCategory) ) $_item_info['CategoryID2'] = $SecondaryCategory->getCategoryID();
          $SellingStatus = $ItemType->getSellingStatus();
          //i get quantity 4 and sold 1 => ebay listing availble 3
          $_sold_qty = $SellingStatus->getQuantitySold();
          $_item_info['Quantity'] -= (int)$_sold_qty;
          $_item_info['ListingStatus'] = $SellingStatus->getListingStatus();
          $CurrentPrice = $SellingStatus->getCurrentPrice(); // | ConvertedCurrentPrice
          $CurrentPrice = new ebay_amount($CurrentPrice);
          $_item_info['eCurrentPrice_v'] = $CurrentPrice->value;
          $_item_info['eCurrentPrice_c'] = $CurrentPrice->currencyID;
          $_item_info['sCurrentPrice_v'] = $CurrentPrice->getConverted();
          $_item_info['sCurrentPrice_c'] = ebay_config::defaultCurrency();
          $ReviseStatus = $ItemType->getReviseStatus();
          if ( is_object($ReviseStatus) ) {
            if ( $ReviseStatus->ItemRevised ) $_item_info['Revised'] = 1;
          }
          $ListingDetails = $ItemType->getListingDetails();
          $_item_info['RelistedItemID'] = $ListingDetails->getRelistedItemID();
          // ok for now
          /*
           *  save in db ListingStatus = Active (active listing)
           *  and others (Completed, Ended)
           *  http://developer.ebay.com/devzone/shopping/docs/CallRef/types/ListingStatusCodeType.html
           *  check for Relisted only empty RelistedItemID need
           *  (ItemRevised=true with not "valid" ItemID )
           */
          if ( !empty($_item_info['RelistedItemID']) ) {
            $logger->info('Item SKU='.$_item_info['SKU'].'; ItemID='.$_item_info['ItemID'].'; skiped RelistedItemID='.$_item_info['RelistedItemID'].';');
            //clean local mirror
            tep_db_query("delete from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                "epl_item_id='".tep_db_input($_item_info['ItemID'])."' and ".
                "connector_id='".ebay_config::getEbaySiteID()."'");
                //"epl_sku='".tep_db_input($_item_info['SKU'])."' and ".
                // no matter SKU only itemID we need
            continue;
          }
          $this->stat['downloaded']++;

          $sql_array = array(
            'connector_id' => ebay_config::getEbaySiteID(),
            'epl_item_id' => $_item_info['ItemID'],
            'epl_category_id' => $_item_info['CategoryID'],
            'epl_category_id2' => $_item_info['CategoryID2'],
            'epl_quantity' => $_item_info['Quantity'],
            'epl_sku' => $_item_info['SKU'],
            'epl_price' => $_item_info['eCurrentPrice_v'],
            'epl_price_currency' => $_item_info['eCurrentPrice_c'],
            'epl_shop_price' => $_item_info['sCurrentPrice_v'],
            'epl_shop_price_currency' => $_item_info['sCurrentPrice_c'],
          );
          if ($_item_info['ListingStatus']=='Active'){
            $sql_array['epl_state'] = 'active';
          }else{
            $sql_array['epl_state'] = 'hold';
          }
          
          $inv_r = tep_db_query("select datafeed_ebay, products_name, ebay_category_id, ebay_item_id from ".TABLE_INVENTORY." where ".
                                  "products_id='".tep_db_input($_item_info['SKU'])."'");
          // skip product from ebay with empty SKU - NOT FROM SHOP!!! shop USE SKU
          if ( empty($_item_info['SKU']) || tep_db_num_rows($inv_r)==0 ) {
            $sql_array['epl_state'] = 'exclusive';
            $logger->notice('Item SKU='.$_item_info['SKU'].'; '.
                            'ItemID='.$_item_info['ItemID'].'; '.
                            'Title="'. $ItemType->getTitle().'"; '.
                            'not found in inventory');
            $this->stat['text'] .= 'Item SKU='.$_item_info['SKU'].'; '.
                                   'ItemID='.$_item_info['ItemID'].'; '.
                                   'Title="'. $ItemType->getTitle().'"; '.
                                   'not found in inventory'."\n";
          } else {
            $inv = tep_db_fetch_array($inv_r);
            if ( (int)$inv['datafeed_ebay']==0 ) {
              $logger->notice('Item SKU='.$_item_info['SKU'].'; '.
                              'ItemID='.$_item_info['ItemID'].'; '.
                              'Title="'. $ItemType->getTitle().'"; '.
                              'found in inventory as "'.$inv['products_name'].'"');
              $this->stat['text'] .= 'Item SKU='.$_item_info['SKU'].'; '.
                                     'ItemID='.$_item_info['ItemID'].'; '.
                                     'Title="'. $ItemType->getTitle().'"; '.
                                     'found in inventory as "'.$inv['products_name'].'"'."\n";
              $sql_array['epl_state'] = 'exclusive';
            }else{
              // if we found matched ebay SKU and shop UPRID : make sync - ebay can change some data 
              $local_normalize = array();
              if ( !empty($sql_array['epl_item_id']) && $inv['ebay_item_id']!=$sql_array['epl_item_id'] ) {
                $local_normalize['ebay_item_id'] = $list['epl_item_id'];
                $logger->info('Found diff [fix] Item SKU='.$_item_info['SKU'].'; '.
                              'Remote ItemID='.$sql_array['epl_item_id'].'; '.
                              'Inventory ItemID='.$inv['ebay_item_id'].';');
              }
              if ( !empty($sql_array['epl_category_id']) && $inv['ebay_category_id']!=$sql_array['epl_category_id'] ) {
                $local_normalize['ebay_category_id'] = $sql_array['epl_category_id'];
                $logger->info('Found diff [fix] Item SKU='.$_item_info['SKU'].'; '.
                              'Remote CategoryID='.$sql_array['epl_category_id'].'; '.
                              'Inventory CategoryID='.$inv['ebay_category_id'].';');
              }
              if ( !empty($sql_array['epl_category_id2']) && $inv['ebay_category_id2']!=$sql_array['epl_category_id2'] ) {
                $local_normalize['ebay_category_id2'] = $sql_array['epl_category_id2'];
                $logger->info('Found diff [fix] Item SKU='.$_item_info['SKU'].'; '.
                              'Remote CategoryID2='.$sql_array['epl_category_id2'].'; '.
                              'Inventory CategoryID2='.$inv['ebay_category_id2'].';');
              }
              if ( count($local_normalize)>0 ) {
                tep_db_perform(TABLE_INVENTORY,
                               $local_normalize,
                               'update',
                               "products_id='".tep_db_input($_item_info['SKU'])."'"
                             );
              }
            }
          }


          $logger->info('Item SKU='.$_item_info['SKU'].'; '.
                        'ItemID='.$_item_info['ItemID'].'; '.
                        'epl_state='.$sql_array['epl_state'].';');
          // TODO: i'm not sure about IF? maybe select with both condition ItemID & SKU 
          if ( empty($_item_info['SKU']) ) {
            $chk_r = tep_db_query("select epl_id, epl_delta_state, epl_state ".
                                  "from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                                    "epl_item_id='".tep_db_input($_item_info['ItemID'])."' and ".
                                    "connector_id='".ebay_config::getEbaySiteID()."'");
          } else {
            $chk_r = tep_db_query("select epl_id, epl_delta_state, epl_state ".
                                  "from ".TABLE_EBAY_PRODUCTS_LIST." where ".
                                    "epl_sku='".tep_db_input($_item_info['SKU'])."' and ".
                                    "connector_id='".ebay_config::getEbaySiteID()."'");
          }
          $list_warning = false;
          if ( tep_db_num_rows($chk_r)==0 ) {
            $sql_array['epl_delta_state'] = 'check';
            tep_db_perform(TABLE_EBAY_PRODUCTS_LIST, $sql_array);
          }else{
            $chk = tep_db_fetch_array( $chk_r );
            tep_db_perform(TABLE_EBAY_PRODUCTS_LIST, 
                           $sql_array, 
                           'update',
                           "epl_id='".tep_db_input($chk['epl_id'])."'");
          }
        }

        $objPagin = $req->getPagination();
        $next_page = intval( $objPagin->getPageNumber() ) + 1;
        if ( $next_page==1 ) {
          $logger->warning('Try set next page to 1, look like inf. loop, escape now');
          break;
        }
        $objPagin->setPageNumber($next_page);
      }else{
        $logger->notice('Exit action GetSellerList by bad ack ['.$res->Ack.']');
        $this->stat['text'] .= 'GetSellerList bad ack ['.$res->Ack.']'."\n";
        if ( is_array($res->Errors) ) {
          foreach( $res->Errors as $ErrorType ) {
            $logger->notice(' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage);
            $this->stat['text'] .= ' '.$ErrorType->SeverityCode.'['.$ErrorType->ErrorCode.'] '.
                              '"'.$ErrorType->ShortMessage.'" '.$ErrorType->LongMessage."\n";
          }
        }
        break;
      }
    } while ( $res->getHasMoreItems() );

    $logger->info('Exit ebay_products::GetSellerList()');

  }
  
  /* used for discover full update - revise */
  function makeItemHash( $ItemIn ){
    $ret = false;
    if ( is_object($ItemIn) ) {
      if (PHP_VERSION < 5) {
        $Item = serialize($ItemIn);
        $Item = unserialize($Item);
      }else{
        $Item = clone($ItemIn);
      }
      //remove qty & price block - processed by InventoryStatus
      if ( isset($Item->ItemID) ) $Item->ItemID=null;
      if ( isset($Item->Quantity) ) $Item->Quantity = null;
      if ( isset($Item->StartPrice) ) $Item->StartPrice = null;
      if ( isset($Item->RelistLink) ) $Item->RelistLink = null;
      $ret = md5( serialize($Item) );
    }
    return $ret; 
  }

  function _add_fault( $sku, $text ){
    $hash = $sku.'_'.md5($text);
    if ( isset($this->stat['status_text'][ $hash ]) ) return;
    $this->stat['status_text'][ $hash ] = $text; 
  }

  function _makeItem( $SKU, $data=false, $profile='verify_add' ){
    if ( !is_array($data) ) {
      die('Teach me fetch $data from SKU '.__LINE__.':'.__FILE__);
    }
    require_once EBAY_DIR_EBATLIB.'/CategoryType.php';
    require_once EBAY_DIR_EBATLIB.'/ItemType.php';

// common error check stub {{
    if ($profile=='verify_add') {
      $check_fail = false;
      // title length
      if ( strlen($data['products_name'])>55 ) {
        $this->_add_fault( $data['products_id'],
                           'Title too long. Listing titles are limited to 55 characters. '.
                           '<b>'.preg_replace('/^(.{55})(.*)/', 
                                              '$1<font color="red">$2</font>', 
                                              $data['products_name']).'</b>'.
                           ' <a href="inventory.php?filter='. urlencode($data['products_id']).'&iID='.$data['inventory_id'].'&action=edit#ebayuk">Fix</a>');
        $check_fail = true;
      }
      //leaf category select
      if ( !empty($data['ebay_category_id']) && !ebay_categories::isLeaf( $data['ebay_category_id'] ) ){
        $this->_add_fault( $data['products_id'],
                           'Invalid Primary category ['.$data['ebay_category_id'].']. The category selected is not a leaf category '.
                           ' <a href="inventory.php?filter='. urlencode($data['products_id']).'&iID='.$data['inventory_id'].'&action=edit#ebayuk">Fix</a>');
        $check_fail = true;
      }
      if ( !empty($data['ebay_category_id2']) && !ebay_categories::isLeaf( $data['ebay_category_id2'] ) ){
        $this->_add_fault( $data['products_id'],
                           'Invalid Secondary category ['.$data['ebay_category_id2'].']. The category selected is not a leaf category '.
                           ' <a href="inventory.php?filter='. urlencode($data['products_id']).'&iID='.$data['inventory_id'].'&action=edit#ebayuk">Fix</a>');
        $check_fail = true;
      }
      if ($check_fail) return false;
    }
// }} common error check stub    

    $item = new ItemType();
    if (!empty($data['ebay_item_id'])) $item->ItemID = $data['ebay_item_id'];
    $item->SKU = $data['products_id'];
    
    $spec = unserialize($data['ebay_spec']);
    if ( is_array($spec) && count($spec)>0 ) {
      $AttributeSetArray = new AttributeSetArrayType();
      $arr = array();
      foreach( $spec as $attr_s ) {
        $attribs_array = array();
        //$attr_s[0]
        //$attr_s[1]
        if ( is_object($attr_s[1]) ) foreach( $attr_s[1] as $k=>$v ) {
          $Attribute = new AttributeType();
          $Attribute->setTypeAttribute('attributeID', $k );
          $values_a = array();
          if ( is_array($v) ) {
            if (count($v)==0) continue;
            foreach( $v as $v_a ) {
              $Val = new ValType();
              if ( is_numeric($v_a) ) {
                $Val->setValueID($v_a);
              }else{
                $Val->setValueLiteral($v_a);
              }
              $values_a[] = $Val;
            }
          }else{
            $Val = new ValType();
            if ( is_numeric($v) ) {
              $Val->setValueID($v);
              $php5feat_fix = (array)$attr_s[2];
              if ( $v==-6 && isset($php5feat_fix[$k]) ) {
                $Val->setValueLiteral( $php5feat_fix[$k] );
              }
            }else{
              $Val->setValueLiteral($v);
            }
            $values_a[] = $Val;
          }
          $Attribute->setValue( $values_a );
          $attribs_array[] = $Attribute;
        }
        
        $AttributeSet = new AttributeSetType();
        $AttributeSet->setTypeAttribute('attributeSetID', $attr_s[0]);
        //$AttributeSet->setTypeAttribute('attributeSetVersion', $attr_s[0]);
        $AttributeSet->setAttribute( $attribs_array );
        
        $arr[] = $AttributeSet;
      }
      $AttributeSetArray->setAttributeSet( $arr );
      if ( count($arr)>0 ) {
        $item->setAttributeSetArray($AttributeSetArray);
      }
    }
    
    
    $item->Title = $data['products_name'];
    if ( tep_not_null($data['products_ebay_description']) ) {
      $data['Description'] = $data['products_ebay_description'];
    }else{
      $data['Description'] = ebay_item_prepeare_description($data['products_description']);
    }

    $item->Description = ebay_item_template_description( $data, ebay_config::getDescriptionTemplate() );
    $item->PrimaryCategory = new CategoryType();
    $item->PrimaryCategory->CategoryID = $data['ebay_category_id'];
    if ( !empty($data['ebay_category_id2']) ) {
      $item->SecondaryCategory = new CategoryType();
      $item->SecondaryCategory->CategoryID = $data['ebay_category_id2'];
    }
    
    $item->Country = 'GB';
    $item->Currency = 'GBP';
/* The listing upgrade called International Site Visibility enables you to make your listing available in the default search results of another site. */
    //$item->CrossBorderTrade = 'North America'; // ebay.com and ebay.ca
    $item->ListingType = 'FixedPriceItem';
    //$item->ListingDuration = 'GTC';
    $item->ListingDuration = ebay_config::getDefaultListingDuration();
    /* images */
    $product_image = $data['products_image_lrg'];
    if ( empty($product_image) ) $product_image = $data['products_image_med'];
    if ( empty($product_image) ) $product_image = $data['products_image'];
    if ( !empty($product_image) ) {
      $PictureDetails = new PictureDetailsType();
      $PictureDetails->setPictureURL( array( ebay_config::getProductImageOrigin().$product_image ) );
      $item->PictureDetails = $PictureDetails;
    }
    
    $item->Quantity = $data['products_quantity'];
    if ( (int)$data['products_status']==0 ) {
      $item->Quantity = 0;
    }
    $price = ebay_uprid_price($data['products_id']);

    //$item->ScheduleTime = ??
    //$item->StartPrice = ??

    //ProductListingDetails

    $item->InventoryTrackingMethod = 'SKU';

    /* shipping */
    $av_shippings = shipping_quote( $data['products_id'], array('price_gross'=>$price['final_gross'] ) );
    // calculate shipping on actual product price & if isset override price switch now
    if ( !empty($data['ebay_price']) && floatval($data['ebay_price'])>0 ) {
      $price['final_gross'] = $data['ebay_price'];
      $price['final'] = ebay_tools::tax_reduce($data['ebay_price']);
    }
    
    $shippings = array();
    if ( ebay_config::forceFreeShipping() ) {
      foreach( $av_shippings as $service_code=>$shipping_data ) {
        $price['final_gross'] += $shipping_data['cost_gross'];
        $price['final'] += $shipping_data['cost'];
        $shipping_data['cost'] = $shipping_data['cost_gross'] = '0.00';
        $_tmp = new ShippingServiceOptionsType();
        $_tmp->setShippingService($service_code);
        $shipping_cost = new AmountType();
        $shipping_cost->setTypeValue( $shipping_data['cost_gross'] );
        $shipping_cost->setTypeAttribute('currencyID', 'GBP');
        $_tmp->setShippingServiceCost( $shipping_cost );
        $shipping_additional_cost = new AmountType();
        $shipping_additional_cost->setTypeValue( $shipping_data['additional_cost'] );
        $shipping_additional_cost->setTypeAttribute('currencyID', 'GBP');
        $_tmp->setShippingServiceAdditionalCost( $shipping_additional_cost );
        $shippings[] = $_tmp;
        break;
      }
    }else{
      //TODO: need check shipping per category feature
      foreach( $av_shippings as $service_code=>$shipping_data ) {
        $_tmp = new ShippingServiceOptionsType();
        $_tmp->setShippingService($service_code);
        $shipping_cost = new AmountType();
        $shipping_cost->setTypeValue( tep_round($shipping_data['cost_gross'],2) );
        $shipping_cost->setTypeAttribute('currencyID', 'GBP');
        $_tmp->setShippingServiceCost( $shipping_cost );
        $shipping_additional_cost = new AmountType();
        $shipping_additional_cost->setTypeValue( tep_round($shipping_data['additional_cost'],2) );
        $shipping_additional_cost->setTypeAttribute('currencyID', 'GBP');
        $_tmp->setShippingServiceAdditionalCost( $shipping_additional_cost );
        $shippings[] = $_tmp;
      }
    }

    $item->Location = ebay_config::getItemLocation();
    $item->DispatchTimeMax = 1; // use DispatchTimeMaxDetails in GeteBayDetails Valid for flat and calculated shipping.
    $item->ShippingDetails = new ShippingDetailsType();
    $item->ShippingDetails->setShippingType('Flat');
    $item->ShippingDetails->setShippingServiceOptions( $shippings );

    $item->StartPrice = new AmountType();
    $item->StartPrice->setTypeValue( tep_round(EBAY_TAX_IN_PRICE=='true'?$price['final_gross']:$price['final'], 2) );
    $item->StartPrice->setTypeAttribute('currencyID', 'GBP');

    $sale_tax = new SalesTaxType();
    $sale_tax->setSalesTaxPercent( 15.00 );
    $sale_tax->setShippingIncludedInTax( true );
    $item->ShippingDetails->setSalesTax( $sale_tax );

    $return_policy = new ReturnPolicyType();
    $return_profile = ebay_config::getReturnsProfile();
    $return_policy->setReturnsAcceptedOption($return_profile['accept']);
    if (!empty($return_profile['refund'])) $return_policy->setRefundOption($return_profile['refund']);
    if (!empty($return_profile['within'])) $return_policy->setReturnsWithinOption($return_profile['within']);
    if (!empty($return_profile['description']))
      $return_policy->setDescription($return_profile['description']);
    if (!empty($return_profile['shippingcostpaidby']))
      $return_policy->setShippingCostPaidByOption($return_profile['shippingcostpaidby']);
    $item->setReturnPolicy( $return_policy );
    /* /shipping */
    /* payment */
    $item->AutoPay = true;
    $item->PaymentMethods[] = 'PayPal';
    $item->PayPalEmailAddress = ebay_config::getPaypalEmail();
    /* /payment */

    return $item;
  }

}

?>
