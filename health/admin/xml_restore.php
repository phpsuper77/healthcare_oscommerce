<?php
/*
  $Id: xml_restore.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
/*
  if (version_compare(PHP_VERSION,'5','>='))
    require_once('includes/domxml-php4-to-php5.php');
*/

  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_CLASSES . 'xml_restore.php');
  require(DIR_WS_CLASSES . 'xmlparser.php');
  require(DIR_WS_FUNCTIONS.FILENAME_BACKUP_XML_DATA);
  $currencies = new currencies();


  $valid_files["products"] = array("categories.xml","products_options.xml","products_options_values.xml","products.xml","manufacturers.xml");
  $valid_files["customers"] = array("customers.xml");
  $valid_files["orders"] = array("orders_status.xml","orders.xml");


  $pass_backup = false;
  switch($HTTP_GET_VARS["action"]) {

    case "products":
                         $fulldir = "products".$HTTP_GET_VARS["checkpoint"];
                         $valid_backup = true;
                         $matches = 0;
                         $d = dir(DIR_FS_CATALOG_XML."/".$fulldir);
                         while($entry=$d->read()) {
                            if (in_array($entry,$valid_files["products"])) $matches++;
                         }
                         $d->close();

                         if ($matches >= sizeof($valid_files["products"])) {
                          $pass_backup = true;
                         } else {
                           $messageStack->add($fulldir . " - " . TEXT_INVALID_BACKUP, 'error');
                         }
    break;

    case "orders":
                         $fulldir = "orders".$HTTP_GET_VARS["checkpoint"];
                         $valid_backup = true;
                         $matches = 0;
                         $d = dir(DIR_FS_CATALOG_XML."/".$fulldir);
                         while($entry=$d->read()) {
                            if (in_array($entry,$valid_files["orders"])) $matches++;
                         }
                         $d->close();

                         if ($matches >= sizeof($valid_files["orders"])) {
                          $pass_backup = true;
                         } else {
                           $messageStack->add($fulldir . " - " . TEXT_INVALID_BACKUP, 'error');
                         }
    break;
    case "customers":
                         $fulldir = "customers".$HTTP_GET_VARS["checkpoint"];
                         $valid_backup = true;
                         $matches = 0;
                         $d = dir(DIR_FS_CATALOG_XML."/".$fulldir);
                         while($entry=$d->read()) {
                            if (in_array($entry,$valid_files["customers"])) $matches++;
                         }
                         $d->close();

                         if ($matches >= sizeof($valid_files["customers"])) {
                          $pass_backup = true;
                         } else {
                           $messageStack->add($fulldir . " - " . TEXT_INVALID_BACKUP, 'error');
                         }
    break;
    case "delete":
        if (is_dir(DIR_FS_CATALOG_XML.$HTTP_GET_VARS["backup"])) {

             $d = dir(DIR_FS_CATALOG_XML.$HTTP_GET_VARS["backup"]);
              while($entry=$d->read()) {
                if ($entry != "." && $entry != "..") {
                  unlink(DIR_FS_CATALOG_XML.$HTTP_GET_VARS["backup"] . "/" . $entry);
                  $messageStack->add(DIR_FS_CATALOG_XML.$HTTP_GET_VARS["backup"] . "/" . $entry . " - " . TEXT_WAS_DELETED, 'success');
                }
              }
             $d->close();
            rmdir(DIR_FS_CATALOG_XML.$HTTP_GET_VARS["backup"]);
           $messageStack->add($HTTP_GET_VARS["backup"] . " - " . TEXT_WAS_DELETED, 'success');
        }

    break;
  }


 if ($xml_dmp) {
  $can_backup_xml = true;
  if (is_dir(DIR_FS_CATALOG_XML)) {
    if (!is_readable(DIR_FS_CATALOG_XML)) {
      $messageStack->add(ERROR_CATALOG_XML_DIRECTORY_NOT_READABLE, 'error');
      $can_backup_xml = false;
    }

  } else {
    $messageStack->add(ERROR_CATALOG_XML_DIRECTORY_DOES_NOT_EXIST, 'error');
    $can_backup_xml = false;
  }



  }

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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_TOOLS;
  $header_title_submenu=XML_HEADING_TITLE;
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
    <td width="100%" valign="top" height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top">
             <?php if ($can_backup_xml) { ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_RESTORE_STARTED; ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="7">
                <?php if (!isset($HTTP_GET_VARS["action"]) || !$pass_backup ) {
                    $dirtypes = array();
                    $d = dir(DIR_FS_CATALOG_XML);
                    while($entry=$d->read()) {
                        if (!strstr($entry,".")) {
                            $entry = explode("-",$entry);
                            $tsmp = array();
                            for ($i=1;$i<sizeof($entry);$i++) {
                               $tsmp[] = $entry[$i];
                            }
                            $dirtypes[$entry[0]][] = join("-",$tsmp);
                        }

                    }
                    $d->close();

                    echo "<br><center>".TABLE_HEADING_RESTORE_ALL."</center>";

                    foreach ($dirtypes as $key => $val) {
                       echo "<ul>";
                       echo "<li><b>".ucfirst($key)."</b><br><ol>";
                       for ($i=0;$i<sizeof($val);$i++) {
                         $_tmp = explode("-",$val[$i]);
                         $c_time = mktime($_tmp[3],$_tmp[4],0,$_tmp[1],$_tmp[0],$_tmp[2]);
                         $fulldir = $key."-".$val[$i];
                         $valid_backup = true;
                         $matches = 0;
                         $d = dir(DIR_FS_CATALOG_XML."/".$fulldir);
                         while($entry=$d->read()) {
                            if (in_array($entry,$valid_files[$key])) $matches++;
                         }
                         $d->close();



                         if ($matches < sizeof($valid_files[$key])) {
                          $valid_backup = false;
                         }

                         if ($valid_backup) {
                            $valid_backup = tep_check_xml_structure($valid_files[$key],$fulldir);
                         }

                         if ($valid_backup) {
                           echo '<li><a href="'.tep_href_link(FILENAME_RESTORE_XML_DATA,'action='.$key.'&checkpoint=-'.$val[$i]).'">'.date(PHP_DATE_TIME_FORMAT,$c_time).'</a><br><br>';
                         } else {
                           echo '<li><a>'.date(PHP_DATE_TIME_FORMAT,$c_time).'</a> - <b><font color=#FF0000>'.TEXT_INVALID_BACKUP.'</font> <a href="'.tep_href_link(FILENAME_RESTORE_XML_DATA,'action=delete&backup='.$key."-".$val[$i]).'"> '.TEXT_DELETE_DUMP.' </a></b><br><br>';
                         }
                       }
                       echo "</ol></ul>";
                       echo "<br><br>";
                    }






                 } else {

                      //reading languages into array for restoring multi-language attributes
                            $languages = array();
                            $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES);
                             while ($lang = tep_db_fetch_array($languages_query)) {
                                $languages[] = $lang;
                             }

                   $restore_data = tep_restore_xml_backup($HTTP_GET_VARS["action"].$HTTP_GET_VARS["checkpoint"],$valid_files[$HTTP_GET_VARS["action"]],$languages);
                   echo "<br><br><ul><li>";
                   $updates = array();
                   $inserted = 0;
                   $updated = 0;
                   $noeffect = 0;

                   for ($i=0;$i<sizeof($restore_data);$i++) {
                     $res = tep_db_query($restore_data[$i]);
                   }

                   echo "<b>".sizeof($restore_data)."</b> ".TEXT_QUERIES_EXECUTED;
                   echo "<li>".TEXT_RESTORE_FINISHED;
                   //echo join("<br><br><li>",$restore_data);
                   echo "</ul>";

                 }


                 ?>

                </td>
              </tr>
            </table>
             <?php }?>
            </td>
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