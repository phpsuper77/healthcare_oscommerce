<?php
include("includes/application_top.php");

define('REMOTE_IMAGES_URL','http://www.healthcare4all.co.uk/images/');

$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
if (!$is_safe_mode && function_exists('set_time_limit')) set_time_limit(0);

define('LOCAL_IMAGES_DIR', preg_replace( '/\/$/', '', DIR_FS_CATALOG ). '/' . DIR_WS_IMAGES );

$data_r = tep_db_query("select * from ".TABLE_PRODUCTS."");
while( $data = tep_db_fetch_array($data_r) ){
  if ( !empty($data['products_image']) ) {
    grab_image($data['products_image']);
  }
  if ( !empty($data['products_image_med']) ) {
    grab_image($data['products_image_med']);
  }
  if ( !empty($data['products_image_lrg']) ) {
    grab_image($data['products_image_lrg']);
  }
}

$data_r = tep_db_query("select categories_image from ".TABLE_CATEGORIES."");
while( $data = tep_db_fetch_array($data_r) ){
  if ( !empty($data['categories_image']) ) {
    grab_image($data['categories_image']);
  }
}

$data_r = tep_db_query("select manufacturers_image from ".TABLE_MANUFACTURERS."");
while( $data = tep_db_fetch_array($data_r) ){
  if ( !empty($data['manufacturers_image']) ) {
    grab_image($data['manufacturers_image']);
  }
}


function grab_image($image_name){
  $image_name = preg_replace( '/^\//', '', $image_name );

  if ( !file_exists(LOCAL_IMAGES_DIR.$image_name) ) {
    echo 'Grab '.REMOTE_IMAGES_URL.$image_name.'<br>';
    $image_bin = '';
    if ($remote = fopen(REMOTE_IMAGES_URL.str_replace(' ','%20',$image_name), 'rb')){
      while (!feof($remote)) {
        $image_bin .= fread($remote, 8192);
      }
      fclose($remote);
    }
    if ( !empty($image_bin) ) {
      // check folders
      $all_parts = split('/',$image_name);
      $check_path = LOCAL_IMAGES_DIR;
      for( $i=0; $i<=count($all_parts)-2; $i++ ){
        if (!is_dir( $check_path.$all_parts[$i] )){
          @mkdir($check_path.$all_parts[$i], 0777);
        }
        $check_path .= $all_parts[$i].'/'; 
      } 
      //
      $local = fopen(LOCAL_IMAGES_DIR.$image_name, 'wb');
      fwrite($local, $image_bin);
      fclose($local);
      if ( file_exists(LOCAL_IMAGES_DIR.$image_name) ) @chmod(LOCAL_IMAGES_DIR.$image_name, 0666);
    }
  }
}
?>