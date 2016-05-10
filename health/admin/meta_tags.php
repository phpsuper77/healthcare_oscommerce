<?
/*
  meta tags addon by Senia
  ver. 1.00 2006-02-10
*/

  require('includes/application_top.php');

  function update_value($inkey, $invalue, $inlang, $affiliate) {
    $inkey = tep_db_prepare_input($inkey);
    $invalue = strip_tags(tep_db_prepare_input($invalue));
    $inlang = intval($inlang);
    if ($inkey != 'x' && $inkey != 'y' && $inlang>0) {
      $ch_ex = tep_db_query("select meta_tags_key from ".TABLE_META_TAGS." where meta_tags_key='".tep_db_input($inkey)."' and language_id='".$inlang."' and affiliate_id = '" . (int)$affiliate . "'");
      if (tep_db_num_rows($ch_ex)>0) {
        // update
        tep_db_query("update ".TABLE_META_TAGS." set meta_tags_value='".tep_db_input($invalue)."' where meta_tags_key='".tep_db_input($inkey)."' and language_id='".$inlang."' and affiliate_id = '" . (int)$affiliate . "'");
      } else {
        // insert
        tep_db_query("insert into ".TABLE_META_TAGS." set meta_tags_value='".tep_db_input($invalue)."', meta_tags_key='".tep_db_input($inkey)."', language_id='".$inlang."', affiliate_id = '" . (int)$affiliate . "'");
      }
    }
  }

  $languages = tep_get_languages();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $in_data = tep_db_prepare_input($HTTP_POST_VARS);
        foreach($in_data as $inskey=>$values) {
         if (is_array($values) && sizeof($values)>0){
           foreach($values as $lang_id=>$insvalue) {
              if (is_array($insvalue) && sizeof($insvalue) > 0){
                foreach ($insvalue as $affiliate_id => $value){
                  update_value($inskey, $value, $lang_id, $affiliate_id);
                }
              }
           }
         }
        }
        tep_redirect(tep_href_link(FILENAME_META_TAGS));
     break;
    }
  }
  $affiliates = tep_get_affiliates();  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna2/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
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
      <tr>
        <td  height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"  height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><table border="0" cellspacing="2" cellpadding="2" width="100%">
                  <tr>
                   <td>
<?php
 // get existed falues
 $get_ex_values_q = tep_db_query("select * from ".TABLE_META_TAGS."");
 if (tep_db_num_rows($get_ex_values_q)>0) {
   while($get_ex_values = tep_db_fetch_array($get_ex_values_q)) {
      define($get_ex_values['meta_tags_key'].'_'.$get_ex_values['language_id'].'_'.$get_ex_values['affiliate_id'], $get_ex_values['meta_tags_value']);
   }
 }

?>

<div class="tab-pane" id="mainTabPane">
  <script type="text/javascript"><!--
    var mainTabPane = new WebFXTabPane( document.getElementById( "mainTabPane" ) );
  //-->
  </script>


<?php echo tep_draw_form('meta_tags', FILENAME_META_TAGS, tep_get_all_get_params(array('action')) . 'action=update', 'post'); ?>


  <div class="tab-page" id="tab_category_default_tags">
    <h2 class="tab"><?php echo CATEGORY_DEFAULT_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_default_tags" ) );
    //-->
    </script>

    <div class="tab-pane" id="category_default_tagsTabPane">
      <script type="text/javascript"><!--
        var category_default_tagsTabPane = new WebFXTabPane( document.getElementById( "category_default_tagsTabPane" ) );
      //-->
      </script>
      
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0) {
  
?>
      <div class="tab-page" id="category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_default_tagsTabPane.addTabPage( document.getElementById( "category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_default_tagsTabPane.addTabPage( document.getElementById( "category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
           <td class="smallText" valign="top"><?php echo HEAD_TITLE_TAG_ALL; ?></td>
           <td class="smallText"><?php echo tep_draw_input_field('HEAD_TITLE_TAG_ALL['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_ALL_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_ALL_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
        
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_ALL; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_ALL['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_ALL_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_ALL_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_ALL; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('HEAD_DESC_TAG_ALL['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_ALL_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_ALL_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_default_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
           <td class="smallText" valign="top"><? echo HEAD_TITLE_TAG_ALL ?></td>
           <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_ALL['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>

          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_ALL; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_ALL['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_ALL; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_ALL['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_ALL_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          </tr>
          
        </table>
      </div>        
<?    
  }
}else{

}
?>        
      </div>
<?php
}
?>
      </div>
 </div>

<!-- Tags for Index page -->
  <div class="tab-page" id="tab_category_index_tags">
    <h2 class="tab"><?php echo CATEGORY_INDEX_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_index_tags" ) );
    //-->
    </script>

    <div class="tab-pane" id="category_index_tagsTabPane">
      <script type="text/javascript"><!--
        var category_index_tagsTabPane = new WebFXTabPane( document.getElementById( "category_index_tagsTabPane" ) );
      //-->
      </script>
      
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_index_tagsTabPane.addTabPage( document.getElementById( "category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_index_tagsTabPane.addTabPage( document.getElementById( "category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_DEFAULT['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_DEFAULT_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_DEFAULT_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_DEFAULT['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_DEFAULT_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_DEFAULT_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_DEFAULT['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_DEFAULT_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_DEFAULT_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_index_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_DEFAULT['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_DEFAULT['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_DEFAULT; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_DEFAULT['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_DEFAULT_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          
        </table>
      </div>        
<?    
  }
?>
<?php
  
}else{
}
?>        
      </div>
<?php
}
?>

      </div>
 </div>
<!-- Tags for Index page off -->

<!-- Tags for product_info page -->
  <div class="tab-page" id="tab_category_product_info_tags">
    <h2 class="tab"><?php echo CATEGORY_PRODUCT_INFO_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_product_info_tags" ) );
    //--></script>

    <div class="tab-pane" id="category_product_info_tagsTabPane">
      <script type="text/javascript"><!--
        var category_product_info_tagsTabPane = new WebFXTabPane( document.getElementById( "category_product_info_tagsTabPane" ) );
      //--></script>

<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_product_info_tagsTabPane.addTabPage( document.getElementById( "category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_product_info_tagsTabPane.addTabPage( document.getElementById( "category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_product_info_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_PRODUCT_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_PRODUCT_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_PRODUCT_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          
        </table>
      </div>        
<?    
  }
?>
<?php
  
}else{
}
?>        
      </div>
<?php
}
?>
      </div>
 </div>
<!-- Tags for product_info page off -->



<!-- Tags for products_new page -->
  <div class="tab-page" id="tab_category_whats_new_tags">
    <h2 class="tab"><?php echo CATEGORY_WHATS_NEW_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_whats_new_tags" ) );
    //-->
    </script>

    <div class="tab-pane" id="category_whats_new_tagsTabPane">
      <script type="text/javascript"><!--
        var category_whats_new_tagsTabPane = new WebFXTabPane( document.getElementById( "category_whats_new_tagsTabPane" ) );
      //-->
      </script>
      
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_whats_new_tagsTabPane.addTabPage( document.getElementById( "category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_whats_new_tagsTabPane.addTabPage( document.getElementById( "category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_WHATS_NEW['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_WHATS_NEW['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_WHATS_NEW['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_WHATS_NEW_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_whats_new_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_WHATS_NEW['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_WHATS_NEW['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_WHATS_NEW; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_WHATS_NEW['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_WHATS_NEW_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
        </table>
      </div>        
<?    
  }
?>
<?php
  
}else{
}
?>        
      </div>
<?php
}
?>
      </div>
 </div>
<!-- Tags for products_new page off -->


<!-- Tags for specials page -->
  <div class="tab-page" id="tab_category_specials_tags">
    <h2 class="tab"><?php echo CATEGORY_SPECIALS_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_specials_tags" ) );
    //-->
    </script>

    <div class="tab-pane" id="category_specials_tagsTabPane">
      <script type="text/javascript"><!--
        var category_specials_tagsTabPane = new WebFXTabPane( document.getElementById( "category_specials_tagsTabPane" ) );
      //-->
      </script>
      
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_specials_tagsTabPane.addTabPage( document.getElementById( "category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_specials_tagsTabPane.addTabPage( document.getElementById( "category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_SPECIALS['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_SPECIALS_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_SPECIALS_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_SPECIALS['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_SPECIALS_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_SPECIALS_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_SPECIALS['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_SPECIALS_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_SPECIALS_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_specials_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_SPECIALS['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_SPECIALS['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_SPECIALS; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_SPECIALS['.$languages[$i]['id'].'][' .$affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_SPECIALS_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
        </table>
      </div>        
<?    
  }
?>
<?php
  
}else{
}
?>        
      </div>
<?php
}
?>

      </div>
 </div>
<!-- Tags for specials page off -->



<!-- Tags for product_reviews page -->
  <div class="tab-page" id="tab_category_product_reviews_tags">
    <h2 class="tab"><?php echo CATEGORY_PRODUCT_REVIEWS_TAGS; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tab_category_product_reviews_tags" ) );
    //-->
    </script>

    <div class="tab-pane" id="category_product_reviews_tagsTabPane">
      <script type="text/javascript"><!--
        var category_product_reviews_tagsTabPane = new WebFXTabPane( document.getElementById( "category_product_reviews_tagsTabPane" ) );
      //-->
      </script>
      
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    category_product_reviews_tagsTabPane.addTabPage( document.getElementById( "category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliatecategory_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_product_reviews_tagsTabPane.addTabPage( document.getElementById( "category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][0]',(defined('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][0]',(defined('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][0]','soft','70','3',(defined('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0')?constant('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_0'):''),'style="width:550px"') ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliatecategory_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        category_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliatecategory_product_reviews_tagsTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>
            <td class="smallText"><?php echo HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_input_field('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']',(defined('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO; ?></td>
            <td class="smallText"><? echo tep_draw_textarea_field('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO['.$languages[$i]['id'].'][' . $affiliates[$j]['id'] . ']','soft','70','3',(defined('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id'])?constant('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO_'.$languages[$i]['id']. '_' . $affiliates[$j]['id']):''),'style="width:550px"') ?></td>
          </tr>
        </table>
      </div>        
<?    
  }
?>
<?php
  
}else{
  ?>
    </div>
  <?php
}
?>        
      </div>
<?php
}
?>
      

      </div>
 </div>
<!-- Tags for product_reviews page off -->



</div>
               </td></tr></table>
              </td>
             </tr>
             <tr>
               <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
             </tr>

              <tr>
               <td><table border="0" cellspacing="2" cellpadding="2" width="95%" align="center">
                  <tr>
                    <td class="main" align="right"><? echo tep_image_submit('button_update.gif', IMAGE_UPDATE);?></td>
                  </tr>
               </table></td>
              </tr>


            </form></table></td>

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