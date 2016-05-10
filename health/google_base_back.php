<?php
include('includes/application_top.php');
include(DIR_WS_CLASSES.'feed_base.php');

/*
$re = common_feed::glob('./', array('!\.htc$!','!\.css$!','!\.xml$!'), 'size');
var_dump( $re ); die;
*/

class googlebase_feed extends feed_base_txt{
  
  function googlebase_feed(){
    global $languages_id, $HTTP_SESSION_VARS, $customer_groups_id;
    parent::feed_base();
    $this->columns( array(
                      'title' => array( 'field'=>'products_name', 'limit'=>80 ),
                      'description' => array( 'field'=>'products_description', 'limit'=>9900 ),
                      'link' => '_product_link_',
                      'image_link' => '_image_link_',
                      'id' => 'products_id',
                      'expiration_date' => array('value'=>strftime("%Y-%m-%d", strtotime("+28 day"))),
                      'price' => '_price_',
                      'manufacturer' => 'manufacturers_name',
                      'model_number' => 'products_model',
                      'quantity' => 'products_quantity',
                      'weight' => 'products_weight',
                      'color' => '',
                      'size' => array('value'=>'small'),
                      'tech_spec_link' => 'products_url',
                      'upc' => 'products_upc',
                      'ean' => 'products_ean',
                      'MPN' => 'products_mpn',
                      'brand' => 'manufacturers_name',
                      'condition' => array('value'=>'new'),
                    ) );

    $sqlJoinAdd = '';
    $sqlWhereAdd = '';
    if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
      $sqlJoinAdd = "LEFT JOIN " . TABLE_PRODUCTS_PRICES . " pp ON ".
                      "p.products_id = pp.products_id AND ".
                      "pp.groups_id = '" . (int)$customer_groups_id . "' and ".
                      "pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:0) ."' ";
      $sqlWhereAdd = "AND IF(pp.products_group_price IS NULL, 1, pp.products_group_price != -1) ";
    }
    $pQuery = "SELECT DISTINCT p.products_id, p.products_model, ".
                "p.products_weight, m.manufacturers_name, ".
                "if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, ".
                "if(length(pd1.products_description), pd1.products_description, pd.products_description) as products_description, ".
                "pd.products_url, p.products_quantity, ".
                "p.products_weight, p.products_price, p.products_tax_class_id, ".
                "s.specials_new_products_price AS spec_price, ".
                "p.products_mpn, p.products_ean, p.products_ean, ".
                "p.products_image, p.products_image_med, p.products_image_lrg ".
              "FROM ".
                TABLE_CATEGORIES." c, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".
                TABLE_PRODUCTS_TO_CATEGORIES." p2c, ".TABLE_CATEGORIES_DESCRIPTION." cd, ".
                TABLE_PRODUCTS." p ".
                  $sqlJoinAdd .
                  ($HTTP_SESSION_VARS['affiliate_ref']>0?
                    "LEFT JOIN " . TABLE_PRODUCTS_TO_AFFILIATES . " p2a on p.products_id = p2a.products_id  and p2a.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ":
                    ''
                  ) .
                  "LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd1 ON ".
                    "pd1.products_id = p.products_id AND ".
                    "pd1.language_id='" . (int)$languages_id ."' AND ".
                    "pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' ".
                  "LEFT JOIN ".TABLE_MANUFACTURERS." m ON ".
                    "m.manufacturers_id = p.manufacturers_id ".
                  "LEFT JOIN ".TABLE_SPECIALS." s ON ".
                    "s.products_id = p.products_id AND s.status = 1 ".
              "WHERE p.products_id=pd.products_id AND p.products_id=p2c.products_id ".
                "AND p.products_status = 1 AND c.categories_status = 1 ".
                $sqlWhereAdd .
                "AND p2c.categories_id=c.categories_id AND c.categories_id=cd.categories_id ".
                "AND pd.affiliate_id=0 AND cd.affiliate_id=0 ".
                "AND pd.language_id='".(int)$languages_id."' AND cd.language_id='".(int)$languages_id."' ".
                ($HTTP_SESSION_VARS['affiliate_ref']>0?" and p2a.affiliate_id is not null ":'').
              "ORDER BY p.products_id ASC";

    $this->set_source( $pQuery );

  }

  function before_out(){
    // _price_
    if ($new_price = tep_get_products_special_price($this->_data['products_id'])) {
      $products_price = $new_price;
    } else {
      $products_price = tep_get_products_price($this->_data['products_id'], 1, $this->_data['products_price']);
    }
    $products_price = tep_add_tax($products_price, tep_get_tax_rate($this->_data['products_tax_class_id']));
    $this->_data['_price_'] = number_format($products_price, 2, '.', '');
    $this->_data['_product_link_'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$this->_data['products_id'],'NONSSL', false);
    //_image_link_
    $image_file = '';
    $images_path = DIR_FS_CATALOG.(substr(DIR_FS_CATALOG,-1)=='/'?'':'/').DIR_WS_IMAGES;
    if (
      !empty($this->_data['products_image_lrg']) &&
      is_file($images_path.$this->_data['products_image_lrg'])
    ) {
      $image_file = $this->_data['products_image_lrg'];
    }elseif (
      !empty($this->_data['products_image_med']) &&
      is_file($images_path.$this->_data['products_image_med'])
    ) {
      $image_file = $this->_data['products_image_med'];
    }elseif (
      !empty($this->_data['products_image']) &&
      is_file($images_path.$this->_data['products_image'])
    ) {
      $image_file = $this->_data['products_image'];
    }
    if ( !empty($image_file) ) {
      $this->_data['_image_link_'] = HTTP_SERVER.DIR_WS_HTTP_CATALOG.DIR_WS_IMAGES.$image_file;
    }

    return parent::before_out();
  }

  function field_prepare($value, $column) {
    static $trans = array();
    static $clean = array();

    if ( count($trans)==0 ) {
      $trans = get_html_translation_table(HTML_ENTITIES);
      unset($trans['&']);
    }
    if ( count($clean)==0 ) {
      $clean = array("\r\n", "\n", "\r", "\t");
      for($i=0;$i<=31;$i++){
        $clean[] = chr($i);
      }
      for($i=128;$i<=156;$i++){
       $clean[] = chr($i);
      }
    }

    $value = strip_tags( str_replace( '<', ' <', $value ) );
    $value = str_replace($clean, " ", $value);
    $value = strtr( $value, $trans );
    $value = trim(preg_replace( '/\s{2,}/' ,' ',$value));
    $info = $this->column_info( $column );
    if ( is_array($info) && !empty($info['limit']) ) {
      if ( strlen($value) > $info['limit'] ) {
        $value = substr($value,0,$info['limit']);
      }
    }
    return parent::field_prepare($value, $column);
  }

}

class googlebase{
  var $results = array();
  var $fileLocation = 'feeds/google.txt';

  function googlebase(){
    $this->results = array();
  }
  
  function getResult(){
    echo "--------\n".base64_encode(serialize($this->results))."\n--------\n";
  }
  
  function process(){
    $feed = new googlebase_feed();
    if ( $feed->open( $this->fileLocation ) ){
      $count = $feed->lines_count();
      $feed->process();
      $feed->close();
      $this->results[] = array('Update success','success');
      //echo 'Count: '.$count;
      return true;
    }else{
      $this->results[] = 'Update error';
      //echo 'cant create file';
    }
    return false;
  }
  
  function upload(){
    if ( !defined('GOOGLE_BASE_FTP_SERVER') || !defined('GOOGLE_BASE_FTP_USER') || !defined('GOOGLE_BASE_FTP_PASSWORD') ) return false;
    if ( GOOGLE_BASE_FTP_SERVER=='' || GOOGLE_BASE_FTP_USER=='' || GOOGLE_BASE_FTP_PASSWORD=='' ) {
      $this->results[] = 'Check settings';
      return false;
    }

    $error=true;
    @set_time_limit(0);
    // set up basic connection
    if ( !function_exists('ftp_connect') ) {
      $this->results[] = 'FTP upload not available.';
    }elseif(is_file($this->fileLocation)) {
      $dest_file = (defined('GOOGLE_BASE_FTP_FILENAME') && GOOGLE_BASE_FTP_FILENAME!='' )?GOOGLE_BASE_FTP_FILENAME:"google_base_export.txt";
      if( $f = fopen($this->fileLocation, "r") ) {
        $conn_id = @ftp_connect(GOOGLE_BASE_FTP_SERVER); 
        $login_result = @ftp_login($conn_id, GOOGLE_BASE_FTP_USER, GOOGLE_BASE_FTP_PASSWORD); 
      // check connection 
        if($conn_id && $login_result) { 
          @ftp_pasv($conn_id, true); // turn on passive mode
          $upload = @ftp_fput($conn_id, $dest_file, $f, FTP_BINARY); 
          if (!$upload) {
            $this->results[] = 'File has not uploaded to google';
          } else { 
            // put log messages here
            $error=false;
            $this->results[] = array('Upload success','success');
          }
        } else {
          $this->results[] = $conn_id?'Login failed':'Connection to '.GOOGLE_BASE_FTP_SERVER.' failed';
        }
        @ftp_close($conn_id);
        fclose($f);
      } else {
        $this->results[] = 'File can\'t be open';
      }
    } else {
      $this->results[] = 'File doesn\'t exists';
    }
    return $error;
  }
}

if ( !empty($_GET['act']) ) {
  $gbase = new googlebase();
  switch( $_GET['act'] ) {
    case 'make':
      $gbase->process();
    break;
    case 'upload':
      $gbase->upload();
    break;
    case 'makeupload':
      if ( $gbase->process() ) {
        $gbase->upload();
      }
    break;
  }
  echo $gbase->getResult();
}

?>