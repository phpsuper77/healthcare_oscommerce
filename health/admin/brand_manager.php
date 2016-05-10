<?php
require('includes/application_top.php');

if (BRAND_MANAGER_DISPLAY != 'True') {
  exit();
}

require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

if (tep_not_null($action))
{
  switch ($action) 
  {

/////////////////////// from manufacturers.php
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['mID'])) $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);
        $manufacturers_name = tep_db_prepare_input($HTTP_POST_VARS['manufacturers_name']);

        $sql_data_array = array('manufacturers_name' => $manufacturers_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
          $manufacturers_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        $manufacturers_image = new upload('manufacturers_image');
        $manufacturers_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($manufacturers_image->parse() && $manufacturers_image->save()) {
          tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image->filename . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $manufacturers_url_array = $HTTP_POST_VARS['manufacturers_url'];
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('manufacturers_url' => tep_db_prepare_input($manufacturers_url_array[$language_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('manufacturers_id' => $manufacturers_id,
                                     'languages_id' => $language_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "' and languages_id = '" . (int)$language_id . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        tep_redirect(tep_href_link(FILENAME_BRAND_MANAGER, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'mID=' . $manufacturers_id));
        break;
      case 'deleteconfirm':
        $manufacturers_id = tep_db_prepare_input($HTTP_GET_VARS['mID']);

        if (isset($HTTP_POST_VARS['delete_image']) && ($HTTP_POST_VARS['delete_image'] == 'on')) {
          $manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          $manufacturer = tep_db_fetch_array($manufacturer_query);

          $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];

          if (file_exists($image_location)) @unlink($image_location);
        }

        tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturers_id . "'");

        if (isset($HTTP_POST_VARS['delete_products']) && ($HTTP_POST_VARS['delete_products'] == 'on')) {
          $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
          while ($products = tep_db_fetch_array($products_query)) {
            tep_remove_product($products['products_id']);
          }
        } else {
          tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('manufacturers');
        }

        tep_redirect(tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page']));
        break;
////////////////////////////end from manufacturers
    case 'delete_products_confirm':
      $product_id = tep_db_prepare_input($HTTP_GET_VARS['products_id']);
      if ($product_id > 0) tep_remove_product($product_id);
      
      tep_href_link(FILENAME_BRAND_MANAGER,'mID='.(int)$HTTP_GET_VARS['mID']);
    break;
    case 'update':
      for($i=0,$n=sizeof($HTTP_POST_VARS['id']);$i<$n;$i++)
      {
        $pprice = tep_db_prepare_input($HTTP_POST_VARS['price'][$i]);
        tep_db_query("UPDATE ".TABLE_PRODUCTS." SET ".
                       "products_price='".tep_db_input($pprice)."', ".
                       "products_quantity='".(int)$HTTP_POST_VARS['qty'][$i]."', ".
                       "products_status='".(int)$HTTP_POST_VARS['status'][$HTTP_POST_VARS['id'][$i]]."' ".
                     "WHERE products_id='".(int)$HTTP_POST_VARS['id'][$i]."'");

        if($specials=tep_db_fetch_array(tep_db_query("select specials_id from ".TABLE_SPECIALS." where products_id='".(int)$HTTP_POST_VARS['id'][$i]."'")) )
        {
        //updte 
          if($HTTP_POST_VARS['discount'][$i]!=0){
            $new_price=$HTTP_POST_VARS['price'][$i]-($HTTP_POST_VARS['discount'][$i]*$HTTP_POST_VARS['price'][$i]/100);
            tep_db_query("update ".TABLE_SPECIALS." set specials_new_products_price='".tep_db_input($new_price)."' where products_id='".(int)$HTTP_POST_VARS['id'][$i]."'");
          }else{
            tep_db_query("delete from ".TABLE_SPECIALS." where products_id='".(int)$HTTP_POST_VARS['id'][$i]."'");
          }
        }else{
        //insert
          if($HTTP_POST_VARS['discount'][$i]!=0){
            $new_price=$HTTP_POST_VARS['price'][$i]-($HTTP_POST_VARS['discount'][$i]*$HTTP_POST_VARS['price'][$i]/100);
            $products_id=$HTTP_POST_VARS['id'][$i];
            tep_db_query("INSERT INTO ".TABLE_SPECIALS." (products_id,specials_new_products_price) values ('".(int)$products_id."','".tep_db_input($new_price)."')");
          }
        }
      }
      tep_href_link(FILENAME_BRAND_MANAGER,'mID='.$HTTP_POST_VARS['mID']);
    break;
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
<?php
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog');
  $header_title_submenu=HEADING_TITLE;

  if( !empty($HTTP_GET_VARS['mID']) )
  {
    $brand1=tep_db_fetch_array( tep_db_query("SELECT manufacturers_name FROM " . TABLE_MANUFACTURERS . " WHERE manufacturers_id='".(int)$HTTP_GET_VARS['mID']."'"));
    $header_title_submenu .= ' -  <a href="'.tep_href_link(FILENAME_BRAND_MANAGER,'mID='.$HTTP_GET_VARS['mID']).'">'.$brand1['manufacturers_name'].'</a>';
  }
  if($HTTP_GET_VARS['search']!='')
  {
    $header_title_submenu .= ' - search results';  
  }
  $header_title_additional = '<form name="search" action="'.tep_href_link(FILENAME_BRAND_MANAGER).'" method="get"><strong>Search:</strong>&nbsp;<input type="text" name="search"></form><br>';

  if($HTTP_GET_VARS['filter']==1 || $HTTP_GET_VARS['filter']==''){
    $s1='selected';
  }elseif($HTTP_GET_VARS['filter']==2){
    $s2='selected';
  }elseif($HTTP_GET_VARS['filter']==3){
    $s3='selected';
  }elseif($HTTP_GET_VARS['filter']==4){
    $s4='selected';
  }
  $header_title_additional .= '<form name="filter" action="'.tep_href_link(FILENAME_BRAND_MANAGER).'" method="get">';
  $header_title_additional .= '<b>Filter:</b> <select name="filter" onchange="return this.form.submit()"><option value="1" '.$s1.'>Show Visible</options><option value="2" '.$s2.'>Show Invisible</options><option value="3" '.$s3.'>Products out of stock</options><option value="4" '.$s4.'>Show All</options></select>';
  $header_title_additional .= '<input type="hidden" name="mID" value="'.$HTTP_GET_VARS['mID'].'">';
  $header_title_additional .= '</form>';
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
      </table>
    </td>
<!-- body_text //-->
    <td width="75%" valign="top" height="100%">
      <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="200" valign="top">
            <table width="200" border="0" cellspacing="0" cellpadding="0">
              <tr class="dataTableHeadingRow">
                <td colspan=2 class="dataTableHeadingContent" ><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <?php
                  $manufacturers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
                  
                  $manufacturers_query = tep_db_query($manufacturers_query_raw);
                  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) 
                  {
                    if ((!isset($HTTP_GET_VARS['mID']) || (isset($HTTP_GET_VARS['mID']) && ($HTTP_GET_VARS['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
                      $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
                      $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

                      $mInfo_array = array_merge($manufacturers, $manufacturer_products);
                      $mInfo = new objectInfo($mInfo_array);
                    }
                    if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
                      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BRAND_MANAGER,'&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
                      if ( empty($HTTP_GET_VARS['mID']) ) $HTTP_GET_VARS['mID'] = $manufacturers['manufacturers_id'];
                    } else {
                      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BRAND_MANAGER,"&mID=" . $manufacturers['manufacturers_id']) . '\'">' . "\n";
                    }
                  ?>
                      <td class="dataTableContent"><a href="<?=tep_href_link(FILENAME_BRAND_MANAGER,"mID=".$manufacturers['manufacturers_id'])?>"><?php echo $manufacturers['manufacturers_name']; ?></a></td>
                      <td class="dataTableContent" align="right"><a href="<?=tep_href_link(FILENAME_BRAND_MANAGER,"mID=".$manufacturers['manufacturers_id']."&action=edit")?>">Edit</a></td>
                    </tr>
                    <!--
                    <tr>
                      <td height="1" colspan="7"><img src="images/spacer.gif" width="1" height="1"></td>
                    </tr>
                    -->
                <?php
                  }
                ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <?php
                if (empty($action)) {
              ?>
              <tr>
                <td align="center" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
              <?php
                }
              ?>
            </table>
          </td>
          <td width="3" bgcolor="#999999"><img src="images/spacer.gif" width="3" height="1"></td>

          <td valign="top" width="100%" height="100%">
          <?
            if($HTTP_GET_VARS['order']=='model'){ $order='p.products_model';
            }elseif($HTTP_GET_VARS['order']=='name'){ $order='pd.products_name';
            }elseif($HTTP_GET_VARS['order']=='price'){ $order='p.products_price';
            }elseif($HTTP_GET_VARS['order']=='stk'){ $order='p.products_quantity';
            }elseif($HTTP_GET_VARS['order']=='vis'){ $order='p.products_status';
            }else{ $order='p.products_id'; }

            if($HTTP_GET_VARS['filter']==1 || $HTTP_GET_VARS['filter']==''){
              $ff=' and p.products_status=1';
            }elseif($HTTP_GET_VARS['filter']==2){
              $ff=' and p.products_status=0';
            }elseif($HTTP_GET_VARS['filter']==3){
              $ff= ' and p.products_quantity<=0 ';
            }
            else
              $ff='';

            if (isset($HTTP_GET_VARS['search'])) 
            {
              $search = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
              $products_query_raw = "select * from ".TABLE_PRODUCTS." p left join ".TABLE_PRODUCTS_DESCRIPTION." pd on (p.products_id = pd.products_id and pd.language_id='".intval($languages_id)."') where pd.affiliate_id = 0 " . (tep_session_is_registered('login_vendor')?" and p.vendor_id = '" . $login_id . "'":''). " and manufacturers_id = '" . intval($mInfo->manufacturers_id) ."' and (pd.products_name like '%" . $search . "%' or p.products_model like '%" . $search . "%') " . $ff." group by p.products_id ORDER BY ".$order;
            }
            else
            {
              if(isset($mInfo))
              {
                $products_query_raw = "select * from ".TABLE_PRODUCTS." p left join ".TABLE_PRODUCTS_DESCRIPTION." pd on (p.products_id = pd.products_id and pd.language_id='".intval($languages_id)."') where pd.affiliate_id = 0 " . (tep_session_is_registered('login_vendor')?" and p.vendor_id = '" . $login_id . "'":''). " and manufacturers_id = '" . intval($mInfo->manufacturers_id) ."' " . $ff." group by p.products_id ORDER BY ".$order;
              }
            }
            if($products_query_raw!="")
            {
              $products_split = new splitPageResults($HTTP_GET_VARS['page'], /*MAX_DISPLAY_SEARCH_RESULTS*/1000, $products_query_raw, $products_query_numrows);
              $products_query = tep_db_query($products_query_raw);
            }

            if ($products_query_raw!="" && tep_db_num_rows($products_query)>0)
            {
          ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="dataTableHeadingRow">
                <td width=2 height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
                <td class="dataTableHeadingContent"><a href="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&order=model');?>">PRODUCT CODE</a></td>
                <td class="dataTableHeadingContent">
                  <a href="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&order=name');?>">NAME</a></td>
                <td class="dataTableHeadingContent">
                  <a href="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&order=price');?>">PRICE</a></td>
                <td class="dataTableHeadingContent" width="100">PRICE(gross)</td>
                <td class="dataTableHeadingContent"><a href="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&order=stk');?>">STK</a></td>
                <td class="dataTableHeadingContent"><a href="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&order=vis');?>">VIS</a></td>
                <td  class="dataTableHeadingContent">Action</td>
                <td width=2 height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
              </tr>
            <!--/table-->
            <form name="products" action="<?php echo tep_href_link(FILENAME_BRAND_MANAGER,'action=update');?>" method="post">
            <!--table width="700"  border="0" cellspacing="0" cellpadding="0"-->
              <? 
              $count=0;
              $HTTP_GET_VARS['page']!=''?$page=$HTTP_GET_VARS['page']:$page=1;
              $num_pages=tep_db_num_rows($products_query);
              
              //$products_split = new splitPageResults($HTTP_GET_VARS['page'], 20, $rs_products, $num_pages, 'products_id');
              
              while ($row = tep_db_fetch_array($products_query))
              {
                //echo "select specials_new_products_price from specials where products_id=".$row['products_id'];die;
                if($price_qwy=tep_db_query("select specials_new_products_price from ".TABLE_SPECIALS." where products_id=".$row['products_id']))
                {
                  if($price_sel=tep_db_fetch_array($price_qwy)){
                    if($row['products_price']>0){
                      $discount=100*($row['products_price'] - $price_sel['specials_new_products_price'])/$row['products_price'];
                    }
                  }
                }else{
                  $discount=0;
                }
                $price_gr=tep_add_tax($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
                ?>
              <tr bgcolor="#EEEEEE">
                <td width=4>&nbsp;</td>
                <td class="dataTableContent" width="25%"><?php echo '<input type="hidden" name="id[]" value="'.$row['products_id'].'">';?><?=$row['products_model']?></td>
                <td class="dataTableContent" width="35%"><?=$name_short=substr($row['products_name'],0,35)?></td>
                <td class="dataTableContent" width="10%"><?php echo '<input size="6" type="text" name="price[]" value="'.number_format($row['products_price'], 2).'">';?></td>
                <td class="dataTableContent" width="10%"><?php echo number_format($price_gr,2);?></td>
                <td class="dataTableContent" width="10%"><?php echo '<input size="5" type="text" name="qty[]" value="'.$row['products_quantity'].'">';?></td>
                <td class="dataTableContent" width="10%"><?php
                 $status=$row['products_status'];
                echo '<input type="checkbox" name="status['.$row['products_id'].']" value="1" '.($status==1?' checked':'').'>';
                ?></td>
                <td bgcolor="#EEEEEE" class="header" width="50">
                  <table border="0">
                    <tr>
                      <td class="dataTableContent"><a href="<?=tep_href_link(FILENAME_CATEGORIES,'pID='.$row['products_id'].'&bm=1&mID='.$mInfo->manufacturers_id.'&action=new_product')?>">EDIT</a></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><a href="<?=tep_href_link(FILENAME_BRAND_MANAGER,'action=delete_products&mID='.$mInfo->manufacturers_id.'&products_id='.$row['products_id'])?>">DELETE</a></td>
                    </tr>
                  </table>
                </td>
                <td width=4>&nbsp;</td>
              </tr>
              <tr>
                <td height="1" colspan="9"><img src="images/spacer.gif" width="1" height="1"></td>
              </tr>
              <?
                $count++;
              }
              ?>
              
              <tr>
                <td colspan="8">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText" align="right"><a href="<?=tep_href_link(FILENAME_CATEGORIES, 'bm=1&mID='.$mInfo->manufacturers_id.'&action=new_product')?>"><?=tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT)?></a> </td>
                      <td align="right" colspan="7"><?=tep_image_submit('button_update.gif', "Update")?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <?php 
                    echo '<input type="hidden" name="mID" value="'.$HTTP_GET_VARS['mID'].'">';
              ?>
              </form>
              <tr>
                <td colspan="8">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText">
                        <?php echo $products_split->display_count($products_query_numrows, /*MAX_DISPLAY_SEARCH_RESULTS*/1000, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, /*MAX_DISPLAY_SEARCH_RESULTS*/1000, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],"mID=".$mInfo->manufacturers_id."&order=".$HTTP_GET_VARS['order']); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="right">
                <?php
                if($action=="delete_products" && $HTTP_GET_VARS['products_id']!=''){
                  echo '<b>Are you sure you want to permanently delete this product? <a href="'.tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $HTTP_GET_VARS['mID'].'&action=delete_products_confirm&mID='.$mInfo->manufacturers_id.'&products_id='.$HTTP_GET_VARS['products_id']).'">YES</a>&nbsp;/&nbsp;<a href="'.tep_href_link(FILENAME_BRAND_MANAGER,'mID='. $mInfo->manufacturers_id).'">NO</a><b>';
                }
                ?>
                </td>
              </tr>
            </table>
              <?
                }
                else
                {
                ?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
                    </tr>
                  </table>
                <?
                }
                ?>
          </td>
        </table>
      </td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('manufacturers', FILENAME_BRAND_MANAGER, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name'));
      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . tep_draw_file_field('manufacturers_image'));

      $manufacturer_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $manufacturer_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $HTTP_GET_VARS['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('manufacturers', FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name', $mInfo->manufacturers_name));
      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . tep_draw_file_field('manufacturers_image') . '<br>' . $mInfo->manufacturers_image);

      $manufacturer_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $manufacturer_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', tep_get_manufacturer_url($mInfo->manufacturers_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

      $contents = array('form' => tep_draw_form('manufacturers', FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->manufacturers_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($mInfo->date_added));
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top" background="images/right_bg.gif">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
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