<?php
/* Google Site Map by Senia
   by Senia
   ver 20.12.2005 (1.00)
   ver 09.02.2006 (1.01)
   ver 23.03.2006 (1.02 froogle_addon)
   ver 10.08.2006 (1.03 google base txt addon)
*/

// google site map
  $google_site_map_enable = true;

// google base
  $google_base_txt_enable = true;
  $google_base_ftp_enable = true;
  $google_base_rss_enable = false;
  $google_base_has_german = false;

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  // test config
  if ( $google_base_ftp_enable && !defined('GOOGLE_BASE_FTP_SERVER') ) {
    tep_db_query("INSERT INTO ".TABLE_CONFIGURATION." (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Google base ftp server address', 'GOOGLE_BASE_FTP_SERVER', 'uploads.google.com', 'Google base ftp server address', 1, 1000, NULL, now(), NULL, NULL), ('Google base ftp server user', 'GOOGLE_BASE_FTP_USER', '', 'Google base ftp server user', 1, 1001, NULL, now(), NULL, NULL), ('Google base ftp server password', 'GOOGLE_BASE_FTP_PASSWORD', '', 'Google base ftp server password', 1, 1002, NULL, now(), ".( function_exists('tep_cfg_show_password')?"'tep_cfg_show_password'":'NULL' ).", ".( function_exists('tep_cfg_password')?"'tep_cfg_password('":'NULL' )."), ('Google base ftp file name', 'GOOGLE_BASE_FTP_FILENAME', 'google_base_export.txt', 'Google base ftp filename', 1, 1003, NULL, now(), NULL, NULL)");
  }
  if ( !$google_base_ftp_enable && defined('GOOGLE_BASE_FTP_SERVER') ) tep_db_query("delete from ".TABLE_CONFIGURATION." where configuration_key in ('GOOGLE_BASE_FTP_SERVER','GOOGLE_BASE_FTP_USER','GOOGLE_BASE_FTP_PASSWORD', 'GOOGLE_BASE_FTP_FILENAME')");

$files = array();

 function additional_data($index){
   global $files;
   if ($index<3) {
     if (GOOGLE_SITEMAP_COMPRESS == 'true' && $index>0) $files[$index]['name'] .= '.gz';
     $files[$index]['localfile'] = DIR_FS_CATALOG.$files[$index]['path'].$files[$index]['name'];
     if (is_file($files[$index]['localfile'])) {
       $files[$index]['date_update'] = strftime(DATE_TIME_FORMAT, filemtime($files[$index]['localfile']));
       $files[$index]['filesize'] = round((filesize($files[$index]['localfile'])/1024),2).'kb';
     }
   } else {
     $files[$index]['localfile'] = DIR_FS_CATALOG.$files[$index]['path'].$files[$index]['name'];
     if (is_file($files[$index]['localfile'])) {
       $files[$index]['date_update'] = strftime(DATE_TIME_FORMAT, filemtime($files[$index]['localfile']));
       $files[$index]['filesize'] = round((filesize($files[$index]['localfile'])/1024),2).'kb';
     }
   }
 }

 //// GSM
if ($google_site_map_enable === true) {

 $files[0] = array('type'=>'Index file',
                   'name'=>'sitemapindex.xml',
                   'filesize'=>' - ',
                   'path' => '',
                   'date_update'=>' - ');

 $files[1] = array('type'=>'Products file',
                   'name'=>'sitemapproducts.xml',
                   'filesize'=>' - ',
                   'path' => '',
                   'date_update'=>' - ');

 $files[2] = array('type'=>'Categories file',
                   'name'=>'sitemapcategories.xml',
                   'path' => '',
                   'filesize'=>' - ',
                   'date_update'=>' - ');

}

  //// Google Base
if ($google_base_txt_enable === true) {
 $files[3] = array('type'=>'Google base file',
                   'name'=>'google.txt',
                   'path' => 'feeds/',
                   'filesize'=>' - ',
                   'date_update'=>' - ');
}
  // 1 - ok
  // 2- error
  function return_msg($msg, $type=2) {
   if ($type==2) {
     return '<span style="FONT-SIZE: 10px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #ff0000;">'.$msg.'</span>';
   } elseif($type==1) {
     return '<span style="FONT-SIZE: 10px; FONT-FAMILY: Verdana, Arial, sans-serif; COLOR: #0000ff;">'.$msg.'</span>';
   } else {
     return $msg;
   }
  }

  function check_file($fname) {
    if (!is_file($fname)) return  return_msg(ERROR_FILE_NONEXISTENT);
    if (!is_writable($fname)) return  return_msg(ERROR_FILE_NOTWRITABLE);
    return return_msg(ERROR_FILE_OK,1);
  }



  if (tep_not_null($action)) {
    switch ($action) {
       case 'update':
         if (is_file(DIR_FS_CATALOG.'googlesitemap.php')) {
           // check files
           $handle = @fopen(tep_catalog_href_link('googlesitemap.php','do=silent'), "r");
           $messageStack->add_session(FILE_UPDATED,'success');
           fclose($handle);
         } else {
           $messageStack->add_session(ERROR_FILE_DOES_NOT_EXIST, 'error');
         }
         tep_redirect(FILENAME_GSM);
       break;
       case 'update_google_base':
         $data = file(tep_catalog_href_link('google_base.php','act=make'));
         if ( !empty($data) && preg_match('/--------(.*)--------/ims',implode('', $data),$m) ) {
           $res = unserialize(base64_decode($m[1]));
           foreach($res as $mess) {
             if ( is_array($mess) ) {
               $messageStack->add_session($mess[0],$mess[1]);
             }else{
               $messageStack->add_session($mess,'error');
             }
           }
         }else{
           $messageStack->add_session(FILE_NOT_UPDATED,'error');
         }
         tep_redirect(FILENAME_GSM);
       break;
       case 'upload_google_base':
         if ($google_base_ftp_enable) {
           $data = @file(tep_catalog_href_link('google_base.php','act=upload'));
           if ( !empty($data) && preg_match('/--------(.*)--------/ims',implode('', $data),$m) ) {
             $res = unserialize(base64_decode($m[1]));
             foreach($res as $mess) {
               if ( is_array($mess) ) {
                 $messageStack->add_session($mess[0],$mess[1]);
               }else{
                 $messageStack->add_session($mess,'error');
               }
             }
           }else{
            $messageStack->add_session(TEXT_FILE_UPLOAD_FAIL,'error');
           }
           tep_redirect(FILENAME_GSM);
         }       
       break;       
       case 'download':
         $file_type = intval($_GET['file']);
         $fname = $files[$file_type]['name'];
         if (GOOGLE_SITEMAP_COMPRESS == 'true' && $file_type>0) $fname .= '.gz';
         $local_fname = DIR_FS_CATALOG.$files[$file_type]['path'].$fname;

        if (is_file($local_fname)) {
          if ($fp = fopen($local_fname, 'rb')) {
            if (filesize($local_fname)>0) {
              $buffer = fread($fp, filesize($local_fname));
            }else{
              $buffer = '';
            }
            fclose($fp);

            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $fname);
            header("Pragma: none");
            header("Expires: 0");

            echo $buffer;

            exit;
          }
        } else {
          $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
       break;

    }
  }

// check if the backup directory exists
/*
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (is_writeable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php
$header_title_menu=BOX_HEADING_CATALOG;
$header_title_menu_link= tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
$header_title_submenu=HEADING_TITLE;
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
          </table>
        </td>
        <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
     <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent"><img src="images/spacer.gif" width="1" height="20" alt="" border="0"></td>
    </tr>
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan=6><?=tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
<?php
 //// index data for gsm ////
 if ($google_site_map_enable === true)  {
?>

              <tr>
                <td class="pageHeading" colspan=6><?php echo HEADING_TITLE_GSM ?></td>
              </tr>
              <tr>
                <td colspan=6><?=tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_WARNING; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
 $index_file_link = false;
 for ($i=0; $i<3; $i++) {
   additional_data($i);
   $key = $i;
   $files_data = $files[$i];
   if ( $i==0 && file_exists(DIR_FS_CATALOG.$files_data['path'].$files_data['name']) ) $index_file_link = HTTP_CATALOG_SERVER.DIR_WS_CATALOG.$files_data['path'].$files_data['name']; 
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent" align="left"><?php echo $files_data['type']?></td>
                <td class="dataTableContent" align="left"><?php echo $files_data['name']?></td>
                <td class="dataTableContent" align="center"><?php echo check_file(DIR_FS_CATALOG.$files_data['path'].$files_data['name'])?></td>
                <td class="dataTableContent" align="center"><?php echo $files_data['date_update']?></td>
                <td class="dataTableContent" align="right"><?php echo $files_data['filesize']?></td>
                <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GSM, 'action=download&file=' . $key) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
              </tr>
<?php
 }
 if ( $index_file_link!==false ) {
?>
              <tr>
                <td colspan="6" class="smallText"><?php echo TEXT_GSM_SITE_LINK.'<b>'.$index_file_link.'</b>'; ?></td>
              </tr>
<?php 
 }
?>
              <tr>
                <td colspan="6"><?php echo tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
              <tr>
                <td colspan="6" class="smallText" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GSM, 'action=update') . '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>' ?></td>
              </tr>

              <tr>
                <td colspan="6"><?php echo tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
<?php
  }
  if ($google_base_txt_enable === true) {
?>
              <tr>
                <td class="pageHeading" colspan=6><?php echo HEADING_TITLE_GB ?></td>
              </tr>
              <tr>
                <td colspan=6><?=tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_WARNING; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
 //// index data ////
 additional_data(3);
 $key = 3;
 $files_data = $files[3];
?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                <td class="dataTableContent" align="left"><?php echo $files_data['type']?></td>
                <td class="dataTableContent" align="left"><?php echo $files_data['name']?></td>
                <td class="dataTableContent" align="center"><?php echo check_file(DIR_FS_CATALOG.$files_data['path'].$files_data['name'])?></td>
                <td class="dataTableContent" align="center"><?php echo $files_data['date_update']?></td>
                <td class="dataTableContent" align="right"><?php echo $files_data['filesize']?></td>
                <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_GSM, 'action=download&file=' . $key) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
              </tr>

              <tr>
                <td colspan=6><?=tep_draw_separator('pixel_trans.gif',1,10)?></td>
              </tr>
              <tr>
                <td colspan=6 class="smallText" align="right"><?php if (file_exists(DIR_FS_CATALOG.$files[3]['path'].$files[3]['name'])&&$google_base_ftp_enable) { echo '<a href="' . tep_href_link(FILENAME_GSM, 'action=upload_google_base') . '">' . tep_image_button('button_upload.gif', IMAGE_UPLOAD) . '</a>&nbsp;'; }; echo '<a href="' . tep_href_link(FILENAME_GSM, 'action=update_google_base') . '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>' ?></td>
              </tr>
<?php
}
?>

            </table></td>
<?php
  $heading = array();
  $contents = array();

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
