<?php
        require_once("includes/application_top.php");

include('admin/includes/functions/image_resize.php');

$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
if (!$is_safe_mode && function_exists('set_time_limit')) set_time_limit(0);


        function image_resize($in_file, $out_file, $width, $height) {
           $rr =  tep_image_resize(
             DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $in_file,
             DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $out_file,
             $width,
             $height
           );
           return;
           $cmd = "convert -resize " . $width . "x" . $height . " '".DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $in_file."' '".DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $out_file."'";
           exec($cmd);
           @chmod(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $out_file,0666);
        }

                $pquery = tep_db_query("
                        select          products_id,
                                        if (length(products_image_lrg)=0 || products_image_lrg is null,products_image, products_image_lrg) as products_image,
                                        products_image_xl_1, products_image_sm_1,
                                        products_image_xl_2, products_image_sm_2,
                                        products_image_xl_3, products_image_sm_3,
                                        products_image_xl_4, products_image_sm_4,
                                        products_image_xl_5, products_image_sm_5,
                                        products_image_xl_6, products_image_sm_6
                        from        " . TABLE_PRODUCTS
                                     );

        $done = $skiped = $notexist = 0;
        while($pinfo = tep_db_fetch_array($pquery)) {
//           if ( !preg_match('/\s/',$pinfo['products_image']) ) continue;
           if (tep_not_null($pinfo['products_image'])) {
              if (file_exists(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $pinfo['products_image'] )) {
                  preg_match('/([^\.]+)\.([a-z]+)$/i', $pinfo['products_image'], $matches);
                  if (is_array($matches) && isset($matches[1]) && isset($matches[2])) {

                    $sm_file =  $matches[1].'_sm.'.$matches[2];
                    $med_file = $matches[1].'_med.'.$matches[2];
                    $big_file = $pinfo['products_image'];

                    image_resize($pinfo['products_image'],$sm_file, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
                    image_resize($pinfo['products_image'],$med_file, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT);

                    $upd_array = array('products_image' => (file_exists(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $sm_file)?$sm_file:$big_file),
                                       'products_image_med' => (file_exists(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $med_file)?$med_file:$big_file),
                                       'products_image_lrg' => $big_file,
                                       );

                    tep_db_perform(TABLE_PRODUCTS,$upd_array,'update',"products_id='".intval($pinfo['products_id'])."'");

                    $done++;
                  } else {
                     echo 'Incorrect file name ['.$pinfo['products_id'].']: '.$pinfo['products_image'].'<br>';
                  }

              } else {
                 echo 'File do not exist ['.$pinfo['products_id'].']: '.$pinfo['products_image'].'<br>';
                 $notexist++;
              }
           } else {
             echo 'Empty file name ['.$pinfo['products_id'].']: '.$pinfo['products_image'].'<br>';
             $skiped++;
           }

           for ($i=1; $i<7; $i++) {
               if (tep_not_null($pinfo['products_image_xl_'.$i]) && file_exists(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $pinfo['products_image_xl_'.$i] )) {
                  preg_match('/([^\.]+)\.([a-z]+)$/i', $pinfo['products_image_xl_'.$i], $matches);

                  if (is_array($matches) && isset($matches[1]) && isset($matches[2])) {
                     $sm_ex_name = $matches[1].'_sm.'.$matches[2];

                     image_resize($pinfo['products_image_xl_'.$i],$sm_ex_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT);

                     if (file_exists(DIR_FS_CATALOG .'/'. DIR_WS_IMAGES . $sm_ex_name)) {
                       tep_db_query("update products set products_image_sm_".$i."='".$sm_ex_name."' where products_id='".intval($pinfo['products_id'])."'");
                     }
                  } else {
                     echo 'Incorrect ex ['.$i.'] file name ['.$pinfo['products_id'].']: '.$pinfo['products_image_xl_'.$i].'<br>';
                  }
               }
           }

           flush();

        }


        echo '<hr><ul><li>DONE:'."\t".$done.'</li><li>SKIPPED:'."\t".$skiped.'</li><li>NON EXIST:'."\t".$notexist.'</li></ul>';
?>