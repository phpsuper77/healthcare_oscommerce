<?php
/*
  $Id: xml_backup.php,v 1.1.1.1 2005/12/03 21:36:02 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  require(DIR_WS_FUNCTIONS.FILENAME_BACKUP_XML_DATA);
  $currencies = new currencies();


  $pass_backup = false;

  $products_cookie = array();
  $customers_cookie = array();
  $categories_cookie = array();
  $orders_cookie = array();
  $orders = array();
  $customers_tml = array();
  $products_tml = array();
  $orders_tml = array();
  $categories_tml = array();


  switch($HTTP_GET_VARS["datatype"]) {

   case "categories":
      if ($HTTP_GET_VARS["action"] == "selected") {
        if (strlen($HTTP_COOKIE_VARS["xml_categories"]) == 0) {
          $messageStack->add(TEXT_WRONG_DATA, 'error');
        } else {
           $categories_tml = explode("_",$HTTP_COOKIE_VARS["xml_categories"]);
           for ($i=0;$i<sizeof($categories_tml);$i++) {
              if (tep_not_null($categories_tml[$i])&&is_numeric($categories_tml[$i])) {
                $categories_cookie[] = $categories_tml[$i];
              }
           }
           $pass_backup = true;
        }
      }
    break;

    case "products":

      if ($HTTP_GET_VARS["action"] == "all") {
         $pass_backup = true;
      } else {
        if (strlen($HTTP_COOKIE_VARS["xml_products"]) == 0) {
          $messageStack->add(TEXT_WRONG_DATA, 'error');
        } else {
           $products_tml = explode("_",$HTTP_COOKIE_VARS["xml_products"]);
           for ($i=0;$i<sizeof($products_tml);$i++) {
              if (tep_not_null($products_tml[$i])&&is_numeric($products_tml[$i])) {
                $products_cookie[] = $products_tml[$i];
              }
           }
           $pass_backup = true;
        }
      }
    break;
    case "orders":
    if ($HTTP_GET_VARS["action"] == "all") {
         $pass_backup = true;
      } else {
        if (strlen($HTTP_COOKIE_VARS["xml_orders"]) == 0) {
          $messageStack->add(TEXT_WRONG_DATA, 'error');
        } else {
           $orders_tml = explode("_",$HTTP_COOKIE_VARS["xml_orders"]);
            for ($i=0;$i<sizeof($orders_tml);$i++) {
              if (tep_not_null($orders_tml[$i])&&is_numeric($orders_tml[$i])) {
                $orders_cookie[] = $orders_tml[$i];
              }
           }
           $pass_backup = true;
        }
      }
    break;
    case "customers":
    if ($HTTP_GET_VARS["action"] == "all") {
         $pass_backup = true;
      } else {
        if (strlen($HTTP_COOKIE_VARS["xml_customers"]) == 0) {
          $messageStack->add(TEXT_WRONG_DATA, 'error');
        } else {
           $customers_tml = explode("_",$HTTP_COOKIE_VARS["xml_customers"]);
           for ($i=0;$i<sizeof($customers_tml);$i++) {
              if (tep_not_null($customers_tml[$i])&&is_numeric($customers_tml[$i])) {
                $customers_cookie[] = $customers_tml[$i];
              }
           }

           $pass_backup = true;
        }
      }
    break;
    default:
       $messageStack->add(TEXT_WRONG_DATATYPE, 'error');
    break;


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
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top">
            <?php if ($pass_backup) { ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo sprintf(TABLE_HEADING_BACKUP_STARTED,ucfirst($HTTP_GET_VARS["datatype"])); ?></td>
              </tr>
              <tr>
                <td class="smallText" colspan="7">
                   <?php
                     //dirname structure: backup type (products,customers,orders) + date
                     $dirname =  $HTTP_GET_VARS["datatype"]."-".date("d-m-y-H-i");


                       if ($HTTP_GET_VARS["datatype"] == "categories") {
                       //if we're trying to backup products
                         $dirname =  "products-".date("d-m-y-H-i");

                        if (@mkdir(DIR_FS_CATALOG_XML.$dirname) || @is_dir(DIR_FS_CATALOG_XML.$dirname)) {
                            //if directory exists or was created, chmod it and start backup
                            @chmod(DIR_FS_CATALOG_XML.$dirname,0777);

                            //reading languages into array for backuping multi-language attributes
                            $languages = array();
                            $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES);
                             while ($lang = tep_db_fetch_array($languages_query)) {
                                $languages[] = $lang;
                             }


                           echo "<ul>";
                           echo "<li><b>" . $dirname . "</b> " . TEXT_DIR_CREATED."</li><br><br><li>";

                           $nested = array();
                           function get_nested($cat_id) {
                             global $nested;
                             $nested[] = $cat_id;
                             $res = tep_db_query("select categories_id from ".TABLE_CATEGORIES." where parent_id=".(int)$cat_id);
                             if (tep_db_num_rows($res)>0) {
                                while ($row = tep_db_fetch_array($res)) {
                                   get_nested($row["categories_id"]);
                                }
                             }
                           }


                           for ($i=0;$i<sizeof($categories_cookie);$i++) {
                              get_nested($categories_cookie[$i]);
                           }

                           $nested = array_unique($nested);



                           $index = 0;
                           $index_nested = 0;
                           $categories_tables[$index]["parent"] = TABLE_CATEGORIES;
                           $add_condition_ = " and categories_id in (".join(",",$nested).")";
                           $categories_tables[$index]["condition"] = $add_condition_;
                           $categories_tables[$index]["nested"][$index_nested]["parent"] = TABLE_CATEGORIES_DESCRIPTION;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "categories", $languages, $categories_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $manufacturers_tables[$index]["parent"] = TABLE_MANUFACTURERS;
                           $manufacturers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_MANUFACTURERS_INFO;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "manufacturers", $languages, $manufacturers_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";


                           $index = 0;
                           $index_nested = 0;
                           $options_tables[$index]["parent"] = TABLE_PRODUCTS_OPTIONS;
                           $options_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products_options", $languages, $options_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $options_values_tables[$index]["parent"] = TABLE_PRODUCTS_OPTIONS_VALUES;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products_options_values", $languages, $options_values_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $products_tables[$index]["parent"] = TABLE_PRODUCTS;

                           $_ids = array();
                           $prods_ids_query = tep_db_query("select distinct(products_id) from ".TABLE_PRODUCTS_TO_CATEGORIES." where categories_id in (".join(",",$nested).")");
                            while($row = tep_db_fetch_array($prods_ids_query)) {
                               $_ids[] = $row["products_id"];
                            }
                            if (sizeof($_ids)==0) $_ids[] = -1;

                           $add_condition = " and products_id in (".join(",",$_ids).")";
                           $products_tables[$index]["condition"] = $add_condition;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_DESCRIPTION;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_TO_CATEGORIES;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_SPECIALS;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = "products_fixed_shipping";
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_XSELL;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_PRICES;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_ATTRIBUTES;
                           $products_tables[$index]["nested"][$index_nested]["nested"][0]["parent"] = TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD;



                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products", $languages, $products_tables);
                           echo join("<li>",$cat_res);

                           echo "</ul>";
                        }
                     }





                     if ($HTTP_GET_VARS["datatype"] == "products") {
                       //if we're trying to backup products


                        if (@mkdir(DIR_FS_CATALOG_XML.$dirname) || @is_dir(DIR_FS_CATALOG_XML.$dirname)) {
                            //if directory exists or was created, chmod it and start backup
                            @chmod(DIR_FS_CATALOG_XML.$dirname,0777);

                            //reading languages into array for backuping multi-language attributes
                            $languages = array();
                            $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES);
                             while ($lang = tep_db_fetch_array($languages_query)) {
                                $languages[] = $lang;
                             }


                           echo "<ul>";
                           echo "<li><b>" . $dirname . "</b> " . TEXT_DIR_CREATED."</li><br><br><li>";

                           $parents = array();
                           function get_parent($cat_id) {
                             global $parents;
                             $parents[] = $cat_id;
                             $res = tep_db_fetch_array(tep_db_query("select parent_id from ".TABLE_CATEGORIES." where categories_id=".(int)$cat_id));
                             if ($res["parent_id"] !=0 ) {
                               get_parent($res["parent_id"]);
                             }

                           }

                           $index = 0;
                           $index_nested = 0;
                           $categories_tables[$index]["parent"] = TABLE_CATEGORIES;
                           if ((sizeof($products_cookie) == 1) || (sizeof($products_cookie) > 1)) {
                               $dependencies = tep_db_query("select distinct(categories_id) from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id in (".join(",",$products_cookie).")");
                               while ($row_dep = tep_db_fetch_array($dependencies)) {
                                    get_parent($row_dep["categories_id"]);
                               }
                                $parents = array_unique($parents);
                                $categories_ids = $parents;

                               $add_condition_ = " and categories_id in (".join(",",$categories_ids).")";
                               $categories_tables[$index]["condition"] = $add_condition_;

                            }
                           $categories_tables[$index]["nested"][$index_nested]["parent"] = TABLE_CATEGORIES_DESCRIPTION;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "categories", $languages, $categories_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";


                           $index = 0;
                           $index_nested = 0;
                           if ((sizeof($products_cookie) == 1) || (sizeof($products_cookie) > 1)) {
                               $dependencies = tep_db_query("select distinct(manufacturers_id) from ".TABLE_PRODUCTS." where products_id in (".join(",",$products_cookie).")");
                               while ($row_dep = tep_db_fetch_array($dependencies)) {
                                  $manufacturers_ids[] = $row_dep["manufacturers_id"];
                               }
                               $add_condition_ = " and manufacturers_id in (".join(",",$manufacturers_ids).")";
                               $manufacturers_tables[$index]["condition"] = $add_condition_;

                            }
                           $manufacturers_tables[$index]["parent"] = TABLE_MANUFACTURERS;
                           $manufacturers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_MANUFACTURERS_INFO;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "manufacturers", $languages, $manufacturers_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";



                           $index = 0;
                           $index_nested = 0;
                           $options_tables[$index]["parent"] = TABLE_PRODUCTS_OPTIONS;
                           $options_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products_options", $languages, $options_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $options_values_tables[$index]["parent"] = TABLE_PRODUCTS_OPTIONS_VALUES;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products_options_values", $languages, $options_values_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $products_tables[$index]["parent"] = TABLE_PRODUCTS;
                           if ((sizeof($products_cookie) == 1) || (sizeof($products_cookie) > 1)) {
                               $add_condition = " and products_id in (".join(",",$products_cookie).")";
                            }
                           $products_tables[$index]["condition"] = $add_condition;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_DESCRIPTION;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_TO_CATEGORIES;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_SPECIALS;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = "products_fixed_shipping";
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_XSELL;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_PRICES;
                           $index_nested++;
                           $products_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_ATTRIBUTES;
                           $products_tables[$index]["nested"][$index_nested]["nested"][0]["parent"] = TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD;



                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "products", $languages, $products_tables);
                           echo join("<li>",$cat_res);

                           echo "</ul>";
                        }
                     }



                      if ($HTTP_GET_VARS["datatype"] == "customers") {
                       //if we're trying to backup products


                        if (@mkdir(DIR_FS_CATALOG_XML.$dirname) || @is_dir(DIR_FS_CATALOG_XML.$dirname)) {
                            //if directory exists or was created, chmod it and start backup
                            @chmod(DIR_FS_CATALOG_XML.$dirname,0777);

                            //reading languages into array for backuping multi-language attributes
                            $languages = array();
                            $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES);
                             while ($lang = tep_db_fetch_array($languages_query)) {
                                $languages[] = $lang;
                             }


                           echo "<ul>";
                           echo "<li><b>" . $dirname . "</b> " . TEXT_DIR_CREATED."</li><br><br><li>";


                           $index = 0;
                           $index_nested = 0;
                           $customers_tables[$index]["parent"] = TABLE_CUSTOMERS;
                           if ((sizeof($customers_cookie) == 1) || (sizeof($customers_cookie) > 1)) {
                               $add_condition = " and customers_id in (".join(",",$customers_cookie).")";
                            }
                           $customers_tables[$index]["condition"] = $add_condition;

                           $customers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_WISHLIST;
                           $index_nested++;
                           $customers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_ADDRESS_BOOK;
                           $index_nested++;
                           $customers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_CUSTOMERS_INFO;
                           $customers_tables[$index]["nested"][$index_nested]["field"] = 'customers_info_id';
                           $index_nested++;
                           $customers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_PRODUCTS_NOTIFICATIONS;
                           $index_nested++;
                           $customers_tables[$index]["nested"][$index_nested]["parent"] = TABLE_REVIEWS;
                           $customers_tables[$index]["nested"][$index_nested]["nested"][0]["parent"] = TABLE_REVIEWS_DESCRIPTION;

                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "customers", $languages, $customers_tables);
                           echo join("<li>",$cat_res);

                           echo "</ul>";
                        }
                     }


                   if ($HTTP_GET_VARS["datatype"] == "orders") {
                       //if we're trying to backup products


                        if (@mkdir(DIR_FS_CATALOG_XML.$dirname) || @is_dir(DIR_FS_CATALOG_XML.$dirname)) {
                            //if directory exists or was created, chmod it and start backup
                            @chmod(DIR_FS_CATALOG_XML.$dirname,0777);

                            //reading languages into array for backuping multi-language attributes
                            $languages = array();
                            $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES);
                             while ($lang = tep_db_fetch_array($languages_query)) {
                                $languages[] = $lang;
                             }


                           echo "<ul>";
                           echo "<li><b>" . $dirname . "</b> " . TEXT_DIR_CREATED."</li><br><br><li>";


                           $index = 0;
                           $index_nested = 0;
                           $orders_tables[$index]["parent"] = TABLE_ORDERS;
                           if ((sizeof($orders_cookie) == 1) || (sizeof($orders_cookie) > 1)) {
                               $add_condition = " and orders_id in (".join(",",$orders_cookie).")";
                            }
                           $orders_tables[$index]["condition"] = $add_condition;
                           $orders_tables[$index]["skiplangs"] = true;;
                           $orders_tables[$index]["nested"][$index_nested]["parent"] = TABLE_ORDERS_STATUS_HISTORY;
                           $index_nested++;
                           $orders_tables[$index]["nested"][$index_nested]["parent"] = TABLE_ORDERS_TOTAL;
                           $index_nested++;
                           $orders_tables[$index]["nested"][$index_nested]["parent"] = TABLE_ORDERS_PRODUCTS;
                           $orders_tables[$index]["nested"][$index_nested]["nested"][0]["parent"] = TABLE_ORDERS_PRODUCTS_ATTRIBUTES;
                           $orders_tables[$index]["nested"][$index_nested]["nested"][1]["parent"] = TABLE_ORDERS_PRODUCTS_DOWNLOAD;


                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "orders", $languages, $orders_tables);
                           echo join("<li>",$cat_res);
                           echo "<br><br><li>";

                           $index = 0;
                           $index_nested = 0;
                           $orders_status_tables[$index]["parent"] = TABLE_ORDERS_STATUS;
                           $cat_res = tep_make_xml(DIR_FS_CATALOG_XML.$dirname, "orders_status", $languages, $orders_status_tables);
                           echo join("<li>",$cat_res);



                           echo "</ul>";
                        }
                     }

                   ?>
                </td>
              </tr>
            </table>


            <? }?>
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