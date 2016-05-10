<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class proxyProduct {
  
  var $_skuList = array();

  function SkuToId( $SKU ){
    $pId = false;
    if ( is_array($this->_skuList) ) {
      $pId = array_search($SKU, $this->_skuList);
    }
    if ( empty($pId) ) {
      $rs = new RecordSetProxy();
      $pId = $rs->fetchOne("SELECT i.products_id ".
                           "FROM ".TABLE_INVENTORY." i ".
                           "WHERE if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id)='".$rs->esc($SKU)."'");
    }
    return $pId;
  }

  function getNewList(){
    $this->_skuList = array();
    $rs = new RecordSetProxy();
    $rs->query("SELECT i.products_id, if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id) as SKU ".
               "FROM ".TABLE_INVENTORY." i ".
               "LEFT JOIN ".TABLE_AMAZON_PRODUCTS." ap ".
                 "ON if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id)=ap.product_sku ".
               "WHERE i.datafeed_a=1 AND ap.product_sku IS NULL ".
                 "AND i.amazon_product_id<>''");
    while( $data = $rs->next() ) {
      $this->_skuList[ $data['products_id'] ] = $data['SKU'];
    }
    return $this->_skuList;
  }

  function getActiveList(){
    $this->_skuList = array();
    $rs = new RecordSetProxy();
    $rs->query("SELECT i.products_id, if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id) as SKU ".
               "FROM ".TABLE_INVENTORY." i, ".TABLE_AMAZON_PRODUCTS." ap ".
               "WHERE i.datafeed_a=1 ".
                 "AND if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id)=ap.product_sku ".
                 "AND i.amazon_product_id<>''");
    while( $data = $rs->next() ) {
      $this->_skuList[ $data['products_id'] ] = $data['SKU'];
    }
    return $this->_skuList;
  }

  function getNotActiveList(){
    $this->_skuList = array();
    $rs = new RecordSetProxy();
    $rs->query("SELECT ap.product_sku as SKU ".
               "FROM ".TABLE_AMAZON_PRODUCTS." ap ".
               "LEFT JOIN ".TABLE_INVENTORY." i ".
                 "ON if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id)=ap.product_sku ".
               "WHERE i.products_id IS NULL ");
    while( $data = $rs->next() ) {
      $this->_skuList[] = $data['SKU'];
    }
    return $this->_skuList;
  }

  /**
   * Make Amazon object with full info for add or update info
   * @param string $SKU
   * @return AmazonBaseProduct
   */
  function forAmazon($SKU){
    global $languages_id;
    $productId = $this->SkuToId($SKU);
    if ( !empty($productId) ) {
      $reader = new RecordSetProxy();
      $reader->query("SELECT i.amazon_product_id, i.amazon_product_idtype, ".
                       "i.amazon_browse_node1, i.amazon_browse_node2, ".
                       "i.amazon_product_subtype, ".
                       "i.amazon_note, ".
                       "if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id) AS SKU, ".
                       "i.products_quantity, i.amazon_price, i.amazon_note, ".
                       "IF(length(i.products_name)>0, i.products_name, pd.products_name) as products_name, ".
                       "m.manufacturers_name, pd.products_description ".
                     "FROM ".TABLE_INVENTORY." i, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS." p ".
                     "LEFT JOIN ".TABLE_MANUFACTURERS." m ON p.manufacturers_id=m.manufacturers_id ".
                     "WHERE i.products_id='".$reader->esc($productId)."' ".
                       "AND i.prid=p.products_id ".
                       "AND pd.products_id=p.products_id AND pd.language_id='".(int)$languages_id."' AND pd.affiliate_id=0");
      
      $product_info = $reader->next();
      if ( !is_array($product_info) ) return ''; 
      
      $_product_class_ = 'AmazonProductBeauty';
      if ( $product_info['amazon_product_subtype']=='Health-PersonalCareAppliances' || 
           $product_info['amazon_product_subtype']=='Health' ){
        $_product_class_ = 'AmazonProductHealth';
      }
      
      $amazonProduct = new $_product_class_;
      $amazonProduct->_subType = $product_info['amazon_product_subtype']; 
      $amazonProduct->SKU = $SKU;
      $amazonProduct->ProductType = $product_info['amazon_product_idtype'];
      $amazonProduct->ProductValue = $product_info['amazon_product_id'];

      $amazonProduct->ConditionType = 'New';
      $amazonProduct->ConditionNote = empty($product_info['amazon_note'])?
                                       AmazonConfig::getDefaultConditionNote():
                                       $product_info['amazon_note'];
      $amazonProduct->Title = $product_info['products_name'];
      $amazonProduct->Brand = $product_info['manufacturers_name'];
      $amazonProduct->Manufacturer = $product_info['manufacturers_name'];
      $amazonProduct->Description = $product_info['products_description'];
      $amazonProduct->Description = amazon_prepare_desc( $amazonProduct->Description );
      $amazonProduct->RecommendedBrowseNode1 = $product_info['amazon_browse_node1'];
      $amazonProduct->RecommendedBrowseNode2 = $product_info['amazon_browse_node2'];
      //$amazonProduct->BulletPoint = explode("\n", $product_info['amazon_bullet_points']);
      //$amazonProduct->SearchTerms = explode("\n", $product_info['amazon_keywords']);

      return $amazonProduct;
    }
  }

  /**
   * Make Amazon object for create price, inventory feeds
   * @param string $SKU
   * @return AmazonBaseProduct
   */
  function forAmazonPI($SKU){
    global $languages_id;
    $productId = $this->SkuToId($SKU);

    if ( !empty($productId) ) {
      $reader = new RecordSetProxy();
      $reader->query("SELECT i.amazon_product_id, i.amazon_product_idtype, ".
                       "if(length(i.amazon_sku)>0, i.amazon_sku, i.products_id) AS SKU, ".
                       "i.products_quantity, i.amazon_price, p.products_status, p.products_price, ".
                       "s.specials_new_products_price, s.expires_date ".
                     "FROM ".TABLE_INVENTORY." i, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS." p ".
                     "LEFT JOIN ".TABLE_SPECIALS." s ON p.products_id=s.products_id and s.status=1 ".
                     "WHERE i.products_id='".$reader->esc($productId)."' AND ".
                     "i.prid=p.products_id AND ".
                     "pd.products_id=p.products_id AND pd.language_id='".(int)$languages_id."' AND pd.affiliate_id=0");

      $product_info = $reader->next();
      if ( !is_array($product_info) ) return '';

      $amazonProduct = new AmazonBaseProduct();
      $amazonProduct->SKU = $SKU;
      $amazonProduct->Quantity = max(0,$product_info['products_quantity']);
      if ( $product_info['products_status']==0 ) {
        $amazonProduct->Quantity = 0;
      }
      if ( (float)$product_info['amazon_price']>0) {
        $amazonProduct->Price = new AmazonCurrency( $product_info['amazon_price'] );
      }else{
        $tax_rate = AmazonConfig::getTaxRate();
        $attributes_price = uprid_price_add( $productId, 0 );
        $price = tep_get_products_price( (int)$productId, 1, $product_info['products_price'] );
        $amazonProduct->Price = new AmazonCurrency( tep_add_tax($price+$attributes_price,$tax_rate) );
        if ( (float)$product_info['specials_new_products_price']>0 ) {
          $special_price = tep_get_products_special_price((int)$productId);
          if ( (float)$special_price>0 && $price>$special_price ) {
            $amazonProduct->SalePrice = new AmazonCurrency( tep_add_tax($special_price+$attributes_price, $tax_rate) );
            $amazonProduct->Sale_EndDate = date( 'Y-m-d', mktime(0, 0, 0, date("m")+1, date("d"), date("Y")) );
            if ( !empty($product_info['expires_date']) && substr($product_info['expires_date'],0,10)>date('Y-m-d') ) {
              $amazonProduct->Sale_EndDate = $product_info['expires_date'];
            }
          }
        }
      }
      return $amazonProduct;
    }else{
      $amazonProduct = new AmazonBaseProduct();
      $amazonProduct->SKU = $SKU;
      $amazonProduct->Quantity = 0;
      $amazonProduct->Price = new AmazonCurrency( 0 );

      return $amazonProduct;
    }
  }

  function _allowed_img_type( $file ) {
    // by ext
    if ( preg_match('/\.jpe?g$/i',$file) ) {
      return true;
    }elseif ( preg_match('/\.tiff?$/i',$file) ) {
      return true;
    }elseif ( preg_match('/\.gif$/i',$file) ) {
      return true;
    }
    return false;
  }

  function forAmazonIMG($SKU){
    $productId = $this->SkuToId($SKU);

    if ( !empty($productId) ) {
      $amazonProduct = new AmazonBaseProduct();
      $amazonProduct->SKU = $SKU;
      $reader = new RecordSetProxy();
      $extra = '';
      for($i=1; $i<7; $i++) {
        $extra .= "products_image_sm_{$i}, products_image_xl_{$i}, ";
      }
      $reader->query("SELECT {$extra} products_image, products_image_med, products_image_lrg ".
                     "FROM ".TABLE_PRODUCTS." ".
                     "WHERE products_id='".(int)$productId."'");
      $image_dir = DIR_FS_CATALOG.(substr(DIR_FS_CATALOG, -1)=='/'?'':'/').DIR_WS_IMAGES;
      while ( $img = $reader->next() ) {
        if ( !empty($img['products_image_lrg']) 
          && is_file( $image_dir.$img['products_image_lrg'])
          && $this->_allowed_img_type($img['products_image_lrg']) ) {
          $amazonProduct->Images[] = DIR_WS_IMAGES.$img['products_image_lrg'];
        }elseif ( !empty($img['products_image_med'])
          && is_file( $image_dir.$img['products_image_med'])
          && $this->_allowed_img_type($img['products_image_med']) ) {
          $amazonProduct->Images[] = DIR_WS_IMAGES.$img['products_image_med'];
        }elseif ( !empty($img['products_image'])
          && is_file( $image_dir.$img['products_image'])
          && $this->_allowed_img_type($img['products_image']) ) {
          $amazonProduct->Images[] = DIR_WS_IMAGES.$img['products_image'];
        }
        for($i=1; $i<7; $i++) {
          if ( !empty($img['products_image_xl_'.$i])
            && is_file( $image_dir.$img['products_image_xl_'.$i])
            && $this->_allowed_img_type($img['products_image_xl_'.$i]) ) {
            $amazonProduct->Images[] = DIR_WS_IMAGES.$img['products_image_xl_'.$i];
          }elseif ( !empty($img['products_image_sm_'.$i])
            && is_file( $image_dir.$img['products_image_sm_'.$i])
            && $this->_allowed_img_type($img['products_image_sm_'.$i]) ) {
            $amazonProduct->Images[] = DIR_WS_IMAGES.$img['products_image_sm_'.$i];
          }
        }
      }
      return $amazonProduct;
    }else{
      return false;
    }
  }

}

?>
