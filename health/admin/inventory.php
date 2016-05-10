<?php

  require('includes/application_top.php');

  require('../ebay/core.php');
  require_once(DIR_WS_CLASSES . 'JSON.php');
  $json = new Services_JSON();

  require_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

$disable_by_first_attrib = true;


  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
      case 'insert':

        $uprid = normalize_id(tep_get_uprid($HTTP_GET_VARS['pID'], $HTTP_POST_VARS['id']));
        $prid = $HTTP_GET_VARS['pID'];
        $products_name = tep_get_products_name($HTTP_GET_VARS['pID'], $languages_id) . ' ' . get_options($uprid);

        $amazon_product_subtype = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_subtype']);
        $amazon_product_id = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_id']);
        $amazon_product_idtype = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_idtype']);
        $amazon_browse_node1 = tep_db_prepare_input($HTTP_POST_VARS['amazon_browse_node1']);
        $amazon_browse_node2 = tep_db_prepare_input($HTTP_POST_VARS['amazon_browse_node2']);
        $amazon_sku = tep_db_prepare_input($HTTP_POST_VARS['amazon_sku']);
        $amazon_price = tep_db_prepare_input($HTTP_POST_VARS['amazon_price']);
        $amazon_note = tep_db_prepare_input($HTTP_POST_VARS['amazon_note']);
        $datafeed_a = (isset($HTTP_POST_VARS['datafeed_a']) && $HTTP_POST_VARS['datafeed_a']==1)?'1':'0';

        $datafeed_ebay = (isset($HTTP_POST_VARS['datafeed_ebay']) && $HTTP_POST_VARS['datafeed_ebay']==1)?'1':'0';
        $ebay_item_id = tep_db_prepare_input($HTTP_POST_VARS['ebay_item_id']);
        $ebay_category_id = tep_db_prepare_input($HTTP_POST_VARS['ebay_category_id']);
        $ebay_category_id2 = tep_db_prepare_input($HTTP_POST_VARS['ebay_category_id2']);
        $ebay_price = tep_db_prepare_input($HTTP_POST_VARS['ebay_price']);
        $ebay_product_title = tep_db_prepare_input($HTTP_POST_VARS['ebay_product_title']);
        
        $products_model= tep_db_prepare_input($HTTP_POST_VARS['products_model']);
        $quantity = tep_db_prepare_input($HTTP_POST_VARS['quantity']);
        $res = tep_db_query("select count(*) as total from " . TABLE_INVENTORY . " where products_id = '" . tep_db_input($uprid) . "'");
        $d = tep_db_fetch_array($res);
        if ($d['total']>0){
          $messageStack->add_session(ERROR_CANNOT_UPDATE_UPRID, 'error');
        } else {
          tep_db_query("insert into " . TABLE_INVENTORY . " set ".
                         "products_model='" . tep_db_input($products_model) . "', ".
                         "products_name = '" . tep_db_input($products_name) . "', ".
                         "amazon_product_subtype = '" . tep_db_input($amazon_product_subtype) . "', ".
                         "amazon_product_id = '" . tep_db_input($amazon_product_id) . "', ".
                         "amazon_product_idtype='".tep_db_input($amazon_product_idtype)."', ".
                         "amazon_browse_node1='".tep_db_input($amazon_browse_node1)."', ".
                         "amazon_browse_node2='".tep_db_input($amazon_browse_node2)."', ".
                         "amazon_sku = '" . tep_db_input($amazon_sku) . "', ".
                         "amazon_price = '" . tep_db_input($amazon_price) . "', ". 
                         "amazon_note = '" . tep_db_input($amazon_note) . "', ".
                         "datafeed_a = '" . (int)$datafeed_a . "',". 
                         "ebay_item_id='".tep_db_input($ebay_item_id)."', ".
                         "ebay_category_id='".tep_db_input($ebay_category_id)."', ".
                         "ebay_category_id2='".tep_db_input($ebay_category_id2)."', ".
                         "ebay_price='".tep_db_input($ebay_price)."', ".
                         "ebay_product_title='".tep_db_input($ebay_product_title)."', ".
                         "datafeed_ebay='".(int)$datafeed_ebay."', ".
                         "prid = '" . $HTTP_GET_VARS['pID'] . "', ".
                         "products_id = '" . tep_db_input($uprid) . "', ".
                         "products_quantity = products_quantity " . (($quantity>=0)?"+'" . tep_db_input($quantity) . "'":tep_db_input($quantity)) . ", ".
                         "inventory_id = '" . tep_db_input($inventory_id) . "'");
          $iID = tep_db_insert_id();
          $res = tep_db_query("update " . TABLE_INVENTORY . " set send_notification=1 where send_notification=0 and products_quantity >=" . STOCK_REORDER_LEVEL);
          if ($disable_by_first_attrib)
            $stock_query = tep_db_query("select min(products_quantity) as max_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'");
          else
            $stock_query = tep_db_query("select max(products_quantity) as max_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'");
          $d = tep_db_fetch_array($stock_query);
          if ( ($d['max_products_quantity'] < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $prid . "'");
          }
          $stock = tep_db_fetch_array(tep_db_query("select sum(products_quantity) as sum_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'"));
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '".(int)$stock['sum_products_quantity']."' where products_id = '" . $prid . "'");
        }
        tep_redirect(tep_href_link(FILENAME_INVENTORY, 'filter=' . urlencode($products_model) . '&iID=' . $iID));
        break;
      case 'save':
        $inventory_id = tep_db_prepare_input($HTTP_GET_VARS['iID']);
        $products_model= tep_db_prepare_input($HTTP_POST_VARS['products_model']);
        $uprid = normalize_id(tep_get_uprid($HTTP_GET_VARS['pID'], $HTTP_POST_VARS['id']));
        $prid = $HTTP_GET_VARS['pID'];
        $products_name = tep_get_products_name($HTTP_GET_VARS['pID'], $languages_id) . ' ' . get_options($uprid);

        $amazon_product_subtype = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_subtype']);
        $amazon_product_id = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_id']);
        $amazon_product_idtype = tep_db_prepare_input($HTTP_POST_VARS['amazon_product_idtype']);
        $amazon_browse_node1 = tep_db_prepare_input($HTTP_POST_VARS['amazon_browse_node1']);
        $amazon_browse_node2 = tep_db_prepare_input($HTTP_POST_VARS['amazon_browse_node2']);
        $amazon_sku = tep_db_prepare_input($HTTP_POST_VARS['amazon_sku']);
        $amazon_price = tep_db_prepare_input($HTTP_POST_VARS['amazon_price']);
        $amazon_note = tep_db_prepare_input($HTTP_POST_VARS['amazon_note']);
        $datafeed_a = (isset($HTTP_POST_VARS['datafeed_a']) && $HTTP_POST_VARS['datafeed_a']==1)?'1':'0';

        $datafeed_ebay = (isset($HTTP_POST_VARS['datafeed_ebay']) && $HTTP_POST_VARS['datafeed_ebay']==1)?'1':'0';
        $ebay_item_id = tep_db_prepare_input($HTTP_POST_VARS['ebay_item_id']);
        $ebay_category_id = tep_db_prepare_input($HTTP_POST_VARS['ebay_category_id']);
        $ebay_category_id2 = tep_db_prepare_input($HTTP_POST_VARS['ebay_category_id2']);
        $ebay_price = tep_db_prepare_input($HTTP_POST_VARS['ebay_price']);
        $ebay_product_title = tep_db_prepare_input($HTTP_POST_VARS['ebay_product_title']);
        $iebay_spec = $json->decode(tep_db_prepare_input($HTTP_POST_VARS['ebay_spec']));
        if ( !is_array($iebay_spec) ) $iebay_spec = array();
        $ebay_spec = serialize($iebay_spec);

        $quantity = tep_db_prepare_input($HTTP_POST_VARS['quantity']);
        $res = tep_db_query("select count(*) as total from " . TABLE_INVENTORY . " where  products_id = '" . tep_db_input($uprid) . "' and inventory_id <> '" . tep_db_input($inventory_id) . "'");
        $d = tep_db_fetch_array($res);
        if ($d['total']>0){
          $messageStack->add_session(ERROR_CANNOT_UPDATE_UPRID, 'error');
        } else {
          tep_db_query("update " . TABLE_INVENTORY . " set ".
                         "products_model='" . tep_db_input($products_model) . "', ".
                         "products_name = '" . tep_db_input($products_name) . "', ".
                         "products_id = '" . tep_db_input($uprid) . "', ".
                         "amazon_product_subtype = '" . tep_db_input($amazon_product_subtype) . "', ".
                         "amazon_product_id='".tep_db_input($amazon_product_id)."', ".
                         "amazon_product_idtype='".tep_db_input($amazon_product_idtype)."', ".
                         "amazon_browse_node1='".tep_db_input($amazon_browse_node1)."', ".
                         "amazon_browse_node2='".tep_db_input($amazon_browse_node2)."', ".
                         "amazon_sku='".tep_db_input($amazon_sku)."', ".
                         "amazon_price='".tep_db_input($amazon_price)."', ".
                         "amazon_note='".tep_db_input($amazon_note)."', ".
                         "datafeed_a='".(int)$datafeed_a."', ".
                         "ebay_item_id='".tep_db_input($ebay_item_id)."', ".
                         "ebay_category_id='".tep_db_input($ebay_category_id)."', ".
                         "ebay_category_id2='".tep_db_input($ebay_category_id2)."', ".
                         "ebay_price='".tep_db_input($ebay_price)."', ".
                         "ebay_product_title='".tep_db_input($ebay_product_title)."', ".
                         "ebay_spec='".tep_db_input($ebay_spec)."', ".
                         "datafeed_ebay='".(int)$datafeed_ebay."', ".
                         "products_quantity = products_quantity " . (($quantity>=0)?"+'" . tep_db_input($quantity) . "'":tep_db_input($quantity)) . " ".
                       "where inventory_id = '" . tep_db_input($inventory_id) . "'");
          $res = tep_db_query("update " . TABLE_INVENTORY . " set send_notification=1 where send_notification=0 and products_quantity >=" . STOCK_REORDER_LEVEL);

          if ($disable_by_first_attrib) {
            $stock_query = tep_db_query("select min(products_quantity) as max_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'");
          } else {
            $stock_query = tep_db_query("select max(products_quantity) as max_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'");
          }
          $d = tep_db_fetch_array($stock_query);
          if ( ($d['max_products_quantity'] < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $prid . "'");
          }
          if ( ($d['max_products_quantity'] > 0) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '1' where products_id = '" . $prid . "'");
          }
          $stock = tep_db_fetch_array(tep_db_query("select sum(products_quantity) as sum_products_quantity from " . TABLE_INVENTORY . " where prid = '" . $prid . "'"));
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '".(int)$stock['sum_products_quantity']."' where products_id = '" . $prid . "'");

        }
        if (isset($_GET['filter'])) {
          tep_redirect(tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('action','model')).'filter='.urlencode($products_model)));
        }else{
          tep_redirect(tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('action'))));
        }
        break;
      case 'deleteconfirm':
        $inventory_id = tep_db_prepare_input($HTTP_GET_VARS['iID']);

        tep_db_query("delete from " . TABLE_INVENTORY . " where inventory_id = '" . tep_db_input($inventory_id) . "'");
        tep_redirect(tep_href_link(FILENAME_INVENTORY, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'report': // check search date filter
        if(tep_not_null($HTTP_GET_VARS['begin_date'])&&tep_not_null($HTTP_GET_VARS['end_date'])){
          // $regs[1] - day
          // $regs[2] - month
          // $regs[3] - year
          if (ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $HTTP_GET_VARS['begin_date'], $regs)) {
            $bdate = mktime(0,0,0,$regs[2],$regs[1],$regs[3]);
          } else {
            $messageStack->add_session(ERROR_INVALID_BEGIN_DATE_FORMAT,'error');
            tep_redirect(tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('begin_date','end_date'))));
          }
          if (ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$", $HTTP_GET_VARS['end_date'], $regs)) {
            $edate = mktime(23,59,59,$regs[2],$regs[1],$regs[3]);
          } else {
            $messageStack->add_session(ERROR_INVALID_END_DATE_FORMAT,'error');
            tep_redirect(tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('begin_date','end_date'))));
          }
          if($bdate>$edate){
            $tmp = $bdate;
            $bdate = $edate;
            $edate = $tmp;
          }
        }
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
<script language="javascript" src="includes/general.js"></script>
<? if($HTTP_GET_VARS['action'] == 'report'){ ?>
<link rel="stylesheet" type="text/css" media="all" href="includes/javascript/calendar/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="includes/javascript/calendar/calendar.js"></script>
<script type="text/javascript" src="includes/javascript/calendar/calendar-en.js"></script>
<script type="text/javascript" src="includes/javascript/calendar/calendar-setup.js"></script>
<? } ?>
<?php if (isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'new' || $_GET['action'] == 'new_product')){ ?>
<script language="JavaScript" src="includes/javascript/jquery-1.3.2.min.js"></script>
<script language="JavaScript" src="includes/javascript/jquery.jmpopups-0.5.1.js"></script>
<script language="JavaScript" src="includes/javascript/jquery-ui-1.7.2.custom.min.js"></script>
<script language="JavaScript" src="includes/javascript/json2.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/jquery-ui-1.7.2.custom.css">
<style>
.popup{ background:#FFF; border:1px solid #333; padding:1px; font-family:verdana; font-size:11px; }
.popup-header{height:24px; padding:7px;font-weight:bold; text-align:center;}
.popup-header .close-link { float:right; padding:0 8px; }
.popup-body {padding:10px;}
.row { padding:2px 0px; clear:both; font-family:verdana; font-size:11px; }
.row label { display:block; float:left; line-height:1.5em; width:150px; }
.row ul{ 
 display:block;
 list-style-image:none;
 list-style-position:outside;
 list-style-type:none;
 margin:0 0 0 150px;
 padding:0px;
}
.row ul li{float:left;width:220px;}
ul.spec {
 font-family:verdana;
 font-size:11px;
}
</style>
<script type="text/javascript">
//<![CDATA[
	$.setupJMPopups({
  	screenLockerBackground: "#003366",
		screenLockerOpacity: "0.7"
	});

function callForm( cat_id, pid ){
    $.ajax({
     url: "../ebay/cat_form.php?cat_id="+cat_id+'&try='+pid,
     cache: false,
     beforeSend: function(){
       $('#popupLayer_myStaticPopup').find("div.charForm").html('<center><img src="images/loadingAnimation.gif"></center>');
     },
     success: function(html){
       $('#popupLayer_myStaticPopup').find("div.charForm").html(html);
       $(window).resize();
       
       $('div.charForm').find(':input[rel=enable_other]').each( function(){
         $(this).bind( 'change', function() { 
           if ( $(this).val()==-6 ) { 
             $(':input[rel='+this.name+']').show(); 
           }else{
             $(':input[rel='+this.name+']').hide();
           } 
         } );
         $(this).after('<input type="text" rel="'+this.name+'" style="display:none">');
       });
       
       var def = $('#ebay_3').find('input[name=ebay_spec]').val();
       var sets = [];
       try{
         eval("sets="+def+";");
         for( i=0; i<=sets.length-1; i++ ){
           var fset = sets[i][0];
           var others = sets[i][2];
           if ( typeof others == 'undefined' ) others={};
           var fsetc = $('#popupLayer_myStaticPopup').find('fieldset[rel='+fset+']');
           if ( fsetc ) {
             for( var cn in sets[i][1] ){
               var cval = sets[i][1][cn];
               if ( typeof cval == 'string' ) { 
                 $(':input[name='+cn+']',fsetc).val(cval);
                 $(':input[name='+cn+']',fsetc).change();
                 if ( cval==-6 ) {
                   if ( typeof others[cn] != 'undefined' ) {
                     $(':input[rel='+cn+']').show();
                     $(':input[rel='+cn+']').val(others[cn]).change();
                   }else{
                     $(':input[rel='+cn+']').show();
                   }
                 }
               }else{
                 for( j=0; j<=cval.length-1; j++ ) {
                   $(':input[value="'+cval[j]+'"]',fsetc).get(0).checked = true;
                 }
               }
             }
           }
         }
       }catch(e){}
     }
    });
}

function saveEbaySpec(popup){
  var sets=[];
  var subs=[];
  var others={};
  $('#popupLayer_myStaticPopup').find('fieldset').each(function(){
    if ( typeof this.attributes['rel'] != 'undefined' ) {
      subs={};
      others={};
      $(':input',this).each(function(){
        if ( this.name.length==0 ) return;
        var tn = '';
        var v = $(this).val();
        if (this.name.indexOf('[')!=-1) {
          tn = this.name.substring(0,this.name.length-2);
          if ( typeof subs[ tn ] == 'undefined' ) subs[ tn ]=[];
          if ( typeof this.checked != 'undefined' && this.checked==false ) return;
          subs[ ''+tn ][ subs[ tn ].length ] = v;
        }else{
          subs[ ''+this.name ] = v;
          if ( v==-6 && 
              (typeof this.attributes['rel'] != 'undefined') &&
               this.attributes['rel'].value=='enable_other'
             ) others[this.name] = $(':input[rel='+this.name+']').val();
        } 
      });
      sets[ sets.length ] = [this.attributes['rel'].value, subs, others];
    }
  });
  
  $('#ebay_3').find('input[name=ebay_spec]').val( ( typeof sets.toSource == 'function'? sets.toSource() : JSON.stringify(sets)) );
  $.closePopupLayer(popup);
}

function openEbay(ebayID) {
  var pop = $.openPopupLayer({
    name: "myStaticPopup",
    width: 700,
    target: "ebay_"+ebayID
  });
  var cval = $('#popupLayer_myStaticPopup').find('select[name=ecat_sub_id]').val();
  if ( cval!='' ) {
    callForm(cval);
  }
  $('#popupLayer_myStaticPopup').find("div[rel=tabs] > div").each( function(){
    if ( typeof this.attributes['rel'] != 'undefined' ) this.id = this.attributes['rel'].value; 
  } );
  $('#popupLayer_myStaticPopup').find("div[rel=tabs]").tabs();
  $('#popupLayer_myStaticPopup').find('select[name=ecat_sub_id]').change(function() {
    var vs = $('#popupLayer_myStaticPopup').find('select[name=ecat_sub_id]').val();
    $('#ebay_3').find('select[name=ecat_sub_id]').val(vs);
    $('input[name=ebay_category_id]').val(vs);
    callForm(vs);
  });
  
  $('#popupLayer_myStaticPopup').find('select[name=ecat_sub_id2]').change(function() {
    var vs = $('#popupLayer_myStaticPopup').find('select[name=ecat_sub_id2]').val();
    $('#ebay_3').find('select[name=ecat_sub_id2]').val(vs);
    $('input[name=ebay_category_id2]').val(vs);
  });
  
  window.location.hash = 'ebayuk';
}
function _openEbay(ebayID){
  $('#ebay_'+ebayID).simpleDialog();
  return false;
}

//]]>
</script>
<?php } ?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_CATALOG;
  $header_title_menu_link= tep_href_link(FILENAME_INVENTORY, 'selected_box=catalog');
  $header_title_submenu=HEADING_TITLE;
  if($HTTP_GET_VARS['action']!='report'){
    $header_title_additional = tep_draw_form('search', basename($PHP_SELF), '', 'get') . TEXT_SEARCH . '&nbsp;' . tep_draw_input_field('filter') . tep_draw_hidden_field(tep_session_name(), tep_session_id()) . '</form>';
  }else{


    if (empty($_GET['date_report_from']) || empty($_GET['date_report_to'])) {
        $_GET['date_report_from'] = date('d-m-Y',mktime(1,1,1,1,1,date("Y")));
        $_GET['date_report_to'] = date('d-m-Y');
    }

  ob_start();

      echo tep_draw_form('search', basename($PHP_SELF), '', 'get').tep_draw_hidden_field(tep_session_name(),tep_session_id());

      $ex_array = array('date_report_from','date_report_to', tep_session_name());
      foreach($_GET as $key=>$val) {
        if (!in_array($key,$ex_array)) echo tep_draw_hidden_field($key,$val);
      }
?>
              <table border="0" cellspacing="0" cellpadding="2" align="right">
                   <tr>
                     <td class="main" nowrap><b><?=TEXT_SEARCH?></b></td>
                     <td class="main" colspan="4" nowrap><? echo tep_draw_input_field('filter','','style="width:270px"') ?></td>
                   </tr>
                   <tr>
                     <td class="main" nowrap><b><?=TEXT_BEGIN_DATE?></b></td>
                     <td class="main" align="left" nowrap><? echo tep_draw_input_field('date_report_from',$_GET['date_report_from'],'id="date_report_from"  class="date"').tep_image(DIR_WS_IMAGES . 'icons/calendar.gif','',20,14,'id="date_report_from_but"  style="border: 0pt none ; cursor: pointer;" align="absmiddle"'); ?></td>
                     <td class="main" nowrap><b><?=TEXT_END_DATE?></b></td>
                     <td class="main" align="left" nowrap><? echo tep_draw_input_field('date_report_to',$_GET['date_report_to'],'id="date_report_to"  class="date"').tep_image(DIR_WS_IMAGES . 'icons/calendar.gif','',20,14,'id="date_report_to_but"  style="border: 0pt none ; cursor: pointer;" align="absmiddle"'); ?></td>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "date_report_from",     // id of the input field
      ifFormat       :    "%d-%m-%Y",      // format of the input field
      button         :    "date_report_from_but",  // trigger for the calendar (button ID)
      align          :    "Bl",           // alignment
      singleClick    :    true
    });
    Calendar.setup({
      inputField     :    "date_report_to",     // id of the input field
      ifFormat       :    "%d-%m-%Y",      // format of the input field
      button         :    "date_report_to_but",  // trigger for the calendar (button ID)
      align          :    "Bl",           // alignment
      singleClick    :    true
    });
</script>


                     <td class="main" nowrap><? echo tep_image_submit('button_search.gif', IMAGE_SEARCH)?></td>
                   </tr>
                </table>
            </form>


<?

   $header_title_additional = ob_get_contents();
   ob_clean();
  }
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
<?php if($HTTP_GET_VARS['action']!='report'){ ?>
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('sort')) . 'sort=' . ($HTTP_GET_VARS['sort']=='1a'?'1d':'1a')) . '" class="menuBoxHeadingLink">' . TABLE_HEADING_INVENTORY_ID . sort_image($HTTP_GET_VARS['sort'], 1) . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('sort')) . 'sort=' . ($HTTP_GET_VARS['sort']=='2a'?'2d':'2a')) . '" class="menuBoxHeadingLink">' . TABLE_HEADING_PRODUCTS_MODEL . sort_image($HTTP_GET_VARS['sort'], 2) . '</a>'; ?></td>
                <td class="dataTableHeadingContent"><?php echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('sort')) . 'sort=' . ($HTTP_GET_VARS['sort']=='3a'?'3d':'3a')) . '" class="menuBoxHeadingLink">' . TABLE_HEADING_PRODUCT_NAME . sort_image($HTTP_GET_VARS['sort'], 3) . '</a>'; ?></td>
                <td class="dataTableHeadingContent" align="center" ><?php echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('sort')) . 'sort=' . ($HTTP_GET_VARS['sort']=='4a'?'4d':'4a')) . '" class="menuBoxHeadingLink">' . TABLE_HEADING_QTY . sort_image($HTTP_GET_VARS['sort'], 4) . '</a>'; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $inventory_query_raw = "select * from " . TABLE_INVENTORY . " where 1 ";
  if (tep_not_null($HTTP_GET_VARS['filter'])){
    $inventory_query_raw .= " and (products_name like '%" . tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['filter'])) . "%' or products_model like '%" . tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['filter'])) . "%')";
  }
  if ($HTTP_GET_VARS['products_id']>0){
    $inventory_query_raw .= " and prid='" . tep_db_prepare_input($HTTP_GET_VARS['products_id']) . "'";
  }
  switch ($HTTP_GET_VARS['sort']){
    case '1a':
      $inventory_query_raw .= " order by inventory_id ";
    break;
    case '1d':
      $inventory_query_raw .= " order by inventory_id desc ";
    break;
    case '2a':
      $inventory_query_raw .= " order by products_model ";
    break;
    case '2d':
      $inventory_query_raw .= " order by products_model desc ";
    break;
    case '4a':
      $inventory_query_raw .= " order by products_quantity ";
    break;
    case '4d':
      $inventory_query_raw .= " order by products_quantity desc ";
    break;
    case '3d':
      $inventory_query_raw .= " order by products_name desc ";
    break;
    case '3a':
    default:
      $inventory_query_raw .= " order by products_name ";
  }

//echo $inventory_query_raw;
  $inventory_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $inventory_query_raw, $inventory_query_numrows);
  $inventory_query = tep_db_query($inventory_query_raw);
  while ($inventory = tep_db_fetch_array($inventory_query)) {
    if (((!$HTTP_GET_VARS['iID']) || (@$HTTP_GET_VARS['iID'] == $inventory['inventory_id'])) && (!$iInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
      $iInfo = new objectInfo($inventory);
    }

    if ( (is_object($iInfo)) && ($inventory['inventory_id'] == $iInfo->inventory_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $iInfo->inventory_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $inventory['inventory_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $inventory['inventory_id']; ?></td>
                <td class="dataTableContent"><?php echo $inventory['products_model']; ?></td>
                <td class="dataTableContent"><?php echo $inventory['products_name']; ?></td>
                <td class="dataTableContent" align="center" width="40"><?php echo $inventory['products_quantity']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($iInfo)) && ($inventory['inventory_id'] == $iInfo->inventory_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $inventory['inventory_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $inventory_split->display_count($inventory_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ITEM); ?></td>
                    <td class="smallText" align="right"><?php echo $inventory_split->display_links($inventory_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'iID'))); ?></td>
                  </tr>
<?php
  if (!$HTTP_GET_VARS['action']) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('action')) . 'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_NEW_INVENTORY) . '</a>'; ?></td>
                  </tr>
<?php
  }
  if (tep_not_null($HTTP_GET_VARS['filter'])){
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('action', 'filter', 'products_id'))) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
<?php } else { // end of if(action!='report')... ?>
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COST; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QTY; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
              </tr>
<?php
/*  $inventory = tep_db_fetch_array(tep_db_query("select * from " . TABLE_INVENTORY . " where inventory_id='" . $HTTP_GET_VARS['iID'] . "'"));
  $iInfo = new objectInfo($inventory);*/
  //print_r($HTTP_GET_VARS);

 $filter_add = '';
 if (!empty($_GET['date_report_from'])) {
  $date_report_from = substr($_GET['date_report_from'],-4).'-'.substr($_GET['date_report_from'],3,2).'-'.substr($_GET['date_report_from'],0,2);
  $filter_add .= " and o.date_purchased > '".$date_report_from." 00:00:01' ";
 }

 if (!empty($_GET['date_report_to'])) {
  $date_report_to = substr($_GET['date_report_to'],-4).'-'.substr($_GET['date_report_to'],3,2).'-'.substr($_GET['date_report_to'],0,2);
  $filter_add .= " and o.date_purchased < '".$date_report_to." 23:23:59' ";
 }

 if (!empty($_GET['filter'])) {
  $filter = tep_db_prepare_input($_GET['filter']);
  $filter_add .= " and (o.orders_id like '%" . tep_db_input($filter) . "%' or o.customers_name like '%" . tep_db_input($filter) . "%' or os.orders_status_name like '%" . tep_db_input($filter) . "%')";
 }


  $report_query_raw = "select o.orders_id, o.customers_name as customer, o.date_purchased as date, ot.text as cost, op.products_quantity qty, os.orders_status_name status from " . TABLE_INVENTORY . " i, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o," . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " os where i.inventory_id='" . $HTTP_GET_VARS['iID'] . "' and i.products_id = op.uprid and o.orders_id = op.orders_id and ot.orders_id = o.orders_id and ot.class = 'ot_total' and os.orders_status_id = o.orders_status and os.language_id='" . $languages_id . "'" . $filter_add;

  $report_query = tep_db_query($report_query_raw);
  while($report = tep_db_fetch_array($report_query)){
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';" onmouseout="this.className='dataTableRow'">
                <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_ORDERS,'oID=' . $report['orders_id'] . '&action=edit')?>" target="_blank"><?php echo $report['orders_id']; ?></a></td>
                <td class="dataTableContent"><?php echo $report['customer']; ?></td>
                <td class="dataTableContent"><?php echo tep_datetime_short($report['date']); ?></td>
                <td class="dataTableContent" align="center"><?php echo $report['cost']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $report['qty']; ?></td>
                <td class="dataTableContent"><?php echo $report['status']; ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="6">&nbsp;</td>
              </tr>

              <tr>
                <td colspan="6"><table border="0" cellspacing="0" cellpadding="2" align="right">
                   <tr>


<?php
    if (isset($_GET['filter']) || isset($_GET['date_report_from']) || isset($_GET['date_report_to'])) {
?>
                    <td><?php echo '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('filter','x','y','date_report_from','date_report_to'))) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>&nbsp;&nbsp;'; ?></td>
<?php
    }
?>

                    <td><?php echo '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('action','filter','x','y','date_report_from','date_report_to'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              </tr>
<?php } ?>
            </table></td>
          </tr>
        </table></td>
<?php
  $amazon_product_idtype_array = array(
    array('id'=>'ASIN', 'text'=>'ASIN'),
    array('id'=>'UPC', 'text'=>'UPC'),
    array('id'=>'EAN', 'text'=>'EAN'),
  );
  $amazon_product_subtype_array = array(
    array('id'=>'Health', 'text'=>'Health'),
    array('id'=>'Health-PersonalCareAppliances', 'text'=>'Health - Personal Care Appliances'),
    array('id'=>'Beauty', 'text'=>'Beauty'),
  );
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
    case 'new':
    case 'new_filter':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INVENTORY . '</b>');
      $form_action = 'action=new_product';
      $filter = tep_db_input(tep_db_prepare_input((strlen($HTTP_GET_VARS['filter'])>0?$HTTP_GET_VARS['filter']:$HTTP_POST_VARS['filter'])));
      if (strlen($filter)){
        $featured_array = array();
        $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and (pd.products_name like '%" . $filter . "%' or p.products_model like '%" . $filter . "%' or p.products_price like '%" . $filter . "%' ) and pd.language_id = '" . (int)$languages_id . "' order by products_name");
        while ($products = tep_db_fetch_array($products_query)) {
          $featured_array[] = array('id' => $products['products_id'], 'text' => $products['products_name'] . ' ' . $products['products_model']);
        }
        $str = tep_draw_pull_down_menu('products_id', $featured_array);
      } else {
        $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p ");
        $d = tep_db_fetch_array($products_query);
        $featured_array = array();
        if ($d['total'] <= MAX_PRODUCTS_PULLDOWN_WO_FILTER){
          $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
          while ($products = tep_db_fetch_array($products_query)) {
            $featured_array[] = array('id' => $products['products_id'], 'text' => $products['products_name'] . ' ' . $products['products_model'] . '');
          }
          $str = tep_draw_pull_down_menu('products_id', $featured_array);
        } else {
          $form_action = 'action=new_filter';
          $str = TEXT_APPLY_FILTER . '<br>' . tep_draw_input_field('filter');
        }
      }
      $contents = array('form' => tep_draw_form('inventory', FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . $form_action));
      $contents[] = array('text' => '<br>' . $str );
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_continue.gif', IMAGE_CONTINUE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) .  'iID=' . $iInfo->inventory_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

      break;
    case 'new_product':
      $products_id = ($HTTP_POST_VARS['products_id']>0?$HTTP_POST_VARS['products_id']:$HTTP_GET_VARS['products_id']);
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INVENTORY . '</b>');
      $contents = array('form' => tep_draw_form('inventory', FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action', 'pID')) . 'action=insert&pID=' . $products_id));

      $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_NAME . '<br>' . tep_get_products_name($products_id, $languages_id) . '<br>' . get_options_selects($HTTP_POST_VARS['products_id']));
      $res = tep_db_query("select * from " . TABLE_PRODUCTS . " where products_id='" . $products_id . "'");
      if ($d = tep_db_fetch_array($res)){
        $products_model = $d['products_model'];
      }
      $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_MODEL . '<br>' . tep_draw_input_field('products_model', $products_model));
      $contents[] = array('text' => '<br>' . TEXT_INFO_QUANTITY_UPDATE . '<br>' . ' +/- ' . tep_draw_input_field('quantity', 0));
      $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_DATAFEED_STATUS . ' ' . tep_draw_checkbox_field('datafeed_a','1',  false ));
      
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_SUBTYPE . '<br>' . tep_draw_pull_down_menu('amazon_product_subtype',$amazon_product_subtype_array ) );
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_ID . '<br>' . tep_draw_input_field('amazon_product_id','','size="16" maxlength="16"').tep_draw_pull_down_menu('amazon_product_idtype',$amazon_product_idtype_array, 'ASIN' ) );
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE1 . '<br>' . tep_draw_input_field('amazon_browse_node1'));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE2 . '<br>' . tep_draw_input_field('amazon_browse_node2'));

      $contents[] = array('text' => '<br>' . TEXT_AMAZON_SKU . '<br>' . tep_draw_input_field('amazon_sku'));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRICE . '<br>' . tep_draw_input_field('amazon_price'));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_NOTE . '<br>' . tep_draw_input_field('amazon_note'));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) .  'iID=' . $iInfo->inventory_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_INVENTORY . '</b>');

      $contents = array('form' => tep_draw_form('inventory', FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'pID', 'action')) . 'iID=' . $iInfo->inventory_id . '&pID=' . $iInfo->prid . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_NAME . '<br>' . tep_get_products_name($iInfo->prid, $languages_id) . '<br>' . get_options_selects($iInfo->products_id));
      $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_MODEL . '<br>' . tep_draw_input_field('products_model', $iInfo->products_model));
      $contents[] = array('text' => '<br>' . TEXT_INFO_QUANTITY_UPDATE . '<br>' . $iInfo->products_quantity . ' +/- ' . tep_draw_input_field('quantity', 0));
      $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));
      $contents[] = array('text' => '<br><b>' . TEXT_SPECIAL_AMAZON_DATAFEED_DATA . '</b>');
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_DATAFEED_STATUS . ' ' . tep_draw_checkbox_field('datafeed_a','1',  ($iInfo->datafeed_a==1) ));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_SUBTYPE . '<br>' . tep_draw_pull_down_menu('amazon_product_subtype',$amazon_product_subtype_array, $iInfo->amazon_product_subtype ) );

      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_ID . '<br>' . tep_draw_input_field('amazon_product_id',$iInfo->amazon_product_id,'size="16" maxlength="16"').tep_draw_pull_down_menu('amazon_product_idtype',$amazon_product_idtype_array, $iInfo->amazon_product_idtype ) );
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE1 . '<br>' . tep_draw_input_field('amazon_browse_node1',$iInfo->amazon_browse_node1));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE2 . '<br>' . tep_draw_input_field('amazon_browse_node2',$iInfo->amazon_browse_node2));

      $contents[] = array('text' => '<br>' . TEXT_AMAZON_SKU . '<br>' . tep_draw_input_field('amazon_sku',($iInfo->amazon_sku=='' && $iInfo->datafeed_a==1?$iInfo->products_model:$iInfo->amazon_sku)));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRICE . '<br>' . tep_draw_input_field('amazon_price',$iInfo->amazon_price));
      $contents[] = array('text' => '<br>' . TEXT_AMAZON_NOTE . '<br>' . tep_draw_input_field('amazon_note',$iInfo->amazon_note));

     $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));
     $contents[] = array('text' => '<br><a name="ebayuk"></a><b>'.TEXT_EBAY_INFO.'</b>');
     $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));
     $contents[] = array('text' => '<br>' . '<a href="javascript:;" onclick="openEbay(3, \''.(int)$iInfo->products_id.'\' )">Attributes and Custom Item Specifics</a>');
     

     $contents[] = array('text' => '<br>'.TEXT_EBAY_DATAFEED_STATUS.' ' . tep_draw_checkbox_field('datafeed_ebay','1',  ($iInfo->datafeed_ebay==1)));

     $contents[] = array('text' => '<br>'.TEXT_EBAY_PRODUCT_TITLE.'<br>' . tep_draw_input_field('ebay_product_title', (strlen($iInfo->ebay_product_title)?$iInfo->ebay_product_title:$iInfo->products_name),(strlen($iInfo->ebay_product_title)>0?'':' disabled="disabled"').' maxlength="55"').tep_draw_checkbox_field('override_ebay_product_title','1',strlen($iInfo->ebay_product_title)>0, '', 'onclick="if(this.checked){document.inventory.ebay_product_title.disabled=false;}else{document.inventory.ebay_product_title.value=\''.addslashes($iInfo->products_name).'\'; document.inventory.ebay_product_title.disabled=true;}"') );
     
     $contents[] = array('text' => '<br>'.TEXT_EBAY_ITEM_ID.'<br>' . tep_draw_input_field('ebay_item_id', $iInfo->ebay_item_id) );
     $contents[] = array('text' => '<br>'.TEXT_EBAY_CATEGORY_ID.'<br>' . tep_draw_input_field('ebay_category_id', $iInfo->ebay_category_id));
     $contents[] = array('text' => '<br>'.TEXT_EBAY_CATEGORY_ID2.'<br>' . tep_draw_input_field('ebay_category_id2', $iInfo->ebay_category_id2));
     $auto_price = '';
     $price_info = tep_get_uprid_price_info( $iInfo->products_id );
     if ( $price_info!==false ){
       $auto_price = $price_info['final_gross'];
       $auto_price = $currencies->format($auto_price,true,'GBP');
       $auto_price = preg_replace('/[^0-9\.]/', '', $auto_price);
     }
     $contents[] = array('text' => '<br>'.TEXT_EBAY_PRICE.'<br>' . tep_draw_input_field('ebay_price', $iInfo->ebay_price>0?$iInfo->ebay_price:$auto_price, 'size="6"'.($iInfo->ebay_price>0?'':' disabled="disabled"')).tep_draw_checkbox_field('override_ebay_price','1',$iInfo->ebay_price>0, '', 'onclick="if(this.checked){document.inventory.ebay_price.disabled=false;}else{document.inventory.ebay_price.value=\''.$auto_price.'\'; document.inventory.ebay_price.disabled=true;}"'));
     $core = ebay_core::get();
     $cat = new ebay_categories();
     
     $start_cat = preg_split('/[, ]/', EBAY_UK_PIN_TOP_CATEGORIES, -1, PREG_SPLIT_NO_EMPTY);
     $_top_ids = $cat->get_tree( 0, 1 );
     $top_ids = array();
     $sub_ids = array( array('id'=>'','text'=>'') );
     foreach( $_top_ids as $_tcat_info ) { 
       if ( in_array($_tcat_info['id'], $start_cat) ) {
         $top_ids[]=$_tcat_info;
         $_tcat_info['group']='open';
         $sub_ids[] = $_tcat_info;
         $sub_cat_top = $cat->get_tree( $_tcat_info['id'], -1 );
         $sub_ids = array_merge( $sub_ids,$sub_cat_top);
       } 
     }
//     $start_cat = 293;
//     $sub_ids = $cat->get_tree( $start_cat, -1 );
//     $sub_ids = array_merge( array(array('id'=>'','text'=>'')),$sub_ids);

     $iebay_spec = unserialize($iInfo->ebay_spec);
     if ( !is_array($iebay_spec) ) $iebay_spec = array(); 
     if ( function_exists('json_encode') ) {
       $iebay_spec = json_encode($iebay_spec);
     }else{
       $iebay_spec = $json->encode($iebay_spec);
     }
     $contents[] = array('text' => '<br>'.'<div id="ebay_3" style="display:none;">'.tep_draw_hidden_field('ebay_spec',$iebay_spec).'<div class="popup">
     <div class="popup-header">'.TEXT_EBAY_INFO.'<a class="close-link" title="Close" onclick="$.closePopupLayer(\'myStaticPopup\')" href="javascript:;">Cancel</a><a class="close-link" title="Close" onclick="saveEbaySpec(\'myStaticPopup\')" href="javascript:;">Save</a><p>'.(strlen($iInfo->ebay_product_title)?$iInfo->ebay_product_title:$iInfo->products_name).'</div>
     <div class="popup-body">
       <!--div class="row"><label>Top categories</label> '.tep_draw_pull_down_menu('ecat_top_id',$top_ids, $start_cat, 'onchange="reloadSubCat(this,)"').'</div-->
       <div class="row"><label>Category ID 1</label> '.$cat->tep_draw_pull_down_menu('ecat_sub_id',$sub_ids, $iInfo->ebay_category_id).'</div>
       <div class="row"><label>Category ID 2</label> '.$cat->tep_draw_pull_down_menu('ecat_sub_id2',$sub_ids, $iInfo->ebay_category_id2).'</div>
       <div rel="tabs">
         <ul>
           <li><a href="#tabs-1">Ebay Options</a></li>
           <!--li><a href="#tabs-2">Product Info</a></li-->
         </ul>
         <div rel="tabs-1">
		        <div class="row charForm"></div>
         </div>
         <!--div rel="tabs-2">
           '.$specification.'
         </div-->
       </div>
     </div>
     </div></div>');


      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $iInfo->inventory_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_INVENTORY . '</b>');

      $contents = array('form' => tep_draw_form('inventory', FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $iInfo->inventory_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $iInfo->ro_products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_INVENTORY, 'page=' . $HTTP_GET_VARS['page'] . '&iID=' . $iInfo->inventory_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($iInfo)) {
        $heading[] = array('text' => '<b>' . $iInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $iInfo->inventory_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('iID', 'action')) . 'iID=' . $iInfo->inventory_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_INVENTORY, tep_get_all_get_params(array('filter','x','y')) . 'iID=' . $iInfo->inventory_id . '&action=report') . '">' . tep_image_button('button_report.gif', IMAGE_REPORT) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_NAME . ' ' . tep_get_products_name($iInfo->prid, $languages_id) . '');
        $contents[] = array('text' => '' . TEXT_INFO_PRODUCTS_MODEL . ' ' . $iInfo->products_model );
        $contents[] = array('text' => '' . TABLE_HEADING_QTY . ': ' . $iInfo->products_quantity);

        $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));
        $contents[] = array('text' => '<br><b>' . TEXT_SPECIAL_AMAZON_DATAFEED_DATA . '</b>');
        if ( intval($iInfo->datafeed_a)==1 ){
          $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_SUBTYPE . ' ' .$iInfo->amazon_product_subtype );

          $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRODUCT_ID . $iInfo->amazon_product_id.' - '.$iInfo->amazon_product_idtype);
          if ( $iInfo->amazon_browse_node1>0 ) {
            $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE1 . ' ' . $iInfo->amazon_browse_node1);
          }
          if ( $iInfo->amazon_browse_node2>0 ) {
            $contents[] = array('text' => '<br>' . TEXT_AMAZON_BROWSE_NODE2 . ' ' . $iInfo->amazon_browse_node2);
          }
          $contents[] = array('text' => '<br>' . TEXT_AMAZON_SKU . $iInfo->amazon_sku);
          $contents[] = array('text' => '<br>' . TEXT_AMAZON_PRICE . $iInfo->amazon_price);
          $contents[] = array('text' => '<br>' . TEXT_AMAZON_NOTE . $iInfo->amazon_note);
        }else{
          $contents[] = array('text' => '<br>' . TEXT_NOT_IN_AMAZON);
        }

     $contents[] = array('text' => '<br><a name="ebayuk"></a><br>' . tep_draw_separator('pixel_black.gif','100%',1));
     $contents[] = array('text' => '<br><b>'.TEXT_EBAY_INFO.'</b>');
     $contents[] = array('text' => '<br>' . tep_draw_separator('pixel_black.gif','100%',1));

     $ebay_product_title = (!empty($iInfo->ebay_product_title)?$iInfo->ebay_product_title:$iInfo->products_name);
     $contents[] = array('text' => '<br>'.TEXT_EBAY_PRODUCT_TITLE.': ' .$ebay_product_title.( strlen($ebay_product_title)>55?'<br><span style="color:red;font-weight:bold;">Title exceed max length - 55 characters</span>':'' ) );
     $view_item = '';
     if ( !empty($iInfo->ebay_item_id) && class_exists('ebay_core') ) {
       $core = ebay_core::get();
       $reg = $core->get_registry();
       $reg->setConnectorId(3); // UK PART
       $url = $reg->getValue('ViewItemURL');
       if ( !empty($url) ) {
         $view_item = '&nbsp;<a href="'.$url.urlencode($iInfo->ebay_item_id).'" target="_blank">'.tep_image(DIR_WS_ICONS . 'external.png', '').'</a>';
       }
     }

     $contents[] = array('text' => '<br>'.TEXT_EBAY_ITEM_ID.': ' . $iInfo->ebay_item_id . $view_item );
     $contents[] = array('text' => '<br>'.TEXT_EBAY_CATEGORY_ID.': ' . $iInfo->ebay_category_id);
     $contents[] = array('text' => '<br>'.TEXT_EBAY_CATEGORY_ID2.': ' . $iInfo->ebay_category_id2);
     $str_ebay_price = '';
     if ( $iInfo->ebay_price>0 ) {
       $str_ebay_price = $currencies->format($iInfo->ebay_price,false,'GBP');
     }else{
       $price_info = tep_get_uprid_price_info( $iInfo->products_id );
       if ( $price_info!==false ){
         $auto_price = $price_info['final_gross'];
         $str_ebay_price = $currencies->format($auto_price,true,'GBP');
       }
     }
     $contents[] = array('text' => '<br>'.TEXT_EBAY_PRICE.': ' . $str_ebay_price);

//        $contents[] = array('text' => '<br>' . $iInfo->products_name);

        /*$res = tep_db_query("select * from " . TABLE_INVENTORY_ORDERS . " io, " . TABLE_INVENTORY_ORDERS_PRODUCTS . " iop left join " . TABLE_ADMIN . " a on io.admin_id = a.admin_id where io.inventory_orders_id=iop.inventory_orders_id and iop.products_id='" . $iInfo->products_id . "' order by date_purchased desc limit 1 ");
        if ($d = tep_db_fetch_array($res)){
          $contents[] = array('text' => '<br>' . TEXT_ORDER_NUMBER . ' <a href="' . tep_href_link(FILENAME_INVENTORY_ORDERS, 'search=' . urlencode($d['order_num']) . '&dstart=' . urlencode(tep_date_short($d['date_purchased'])) . '&dend=' . urlencode(tep_date_short($d['date_purchased']))) . '">' . $d['order_num']);

          $contents[] = array('text' => '<br>' . TEXT_DELIVERY . ' ' . ($d['delivered']==1?tep_date_short($d['date_delivered']):'<font color=red>' . tep_date_short($d['date_delivered']) . '</a>'));
          $contents[] = array('text' => '<br>' . TEXT_ORDER_QTY . ' ' . $d['products_quantity']);
          $contents[] = array('text' => '<br>' . TEXT_PERSON . ' ' . $d['admin_lastname'] . ' ' . $d['admin_firstname'] . ' ' . $d['individual_id']);
          $contents[] = array('text' => '<br>' . TEXT_ORDER_DATE . ' ' . tep_date_short($d['date_purchased']));
        }*/


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