<?php
// {{ 
  reset($HTTP_POST_VARS); 
  while (list($key, $value) = each($HTTP_POST_VARS)) { 
    if (!is_array($HTTP_POST_VARS[$key])) { 
      if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key] = stripslashes($value); 
    } else { 
      while (list($k, $v) = each($value)) { 
        if (!is_array($HTTP_POST_VARS[$key][$k])) { 
          if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k] = stripslashes($v); 
        } else { 
          while (list($k1, $v1) = each($v)){ 
            if (!is_array($HTTP_POST_VARS[$key][$k][$k1])) { 
              if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k][$k1] = stripslashes($v1); 
            } else { 
              while (list($k2, $v2) = each($v1)) { 
                if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k][$k1][$k2] = stripslashes($v2); 
              } 
            } 
          } 
        } 
      } 
    } 
  } 
// }}
    if ( ($HTTP_GET_VARS['cID']) && (!$HTTP_POST_VARS) ) {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag,  c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, cd.direct_url from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $HTTP_GET_VARS['cID'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.affiliate_id = 0 order by c.sort_order, cd.categories_name");
      $category = tep_db_fetch_array($categories_query);

      $cInfo = new objectInfo($category);
    } elseif ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];
      $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
      $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
      $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];
      $categories_url = $HTTP_POST_VARS['categories_url'];
      $direct_url = $HTTP_POST_VARS['direct_url'];
    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();

    $text_new_or_edit = ($HTTP_GET_VARS['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
    if (!isset($cInfo->categories_status)) $cInfo->categories_status = '1';
    switch ($cInfo->categories_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
require('includes/classes/directory_listing.php');
$osC_Dir_Images = new osC_DirectoryListing('../images');
$osC_Dir_Images->setExcludeEntries(array('CVS', '.svn'));
$osC_Dir_Images->setIncludeFiles(false);
$osC_Dir_Images->setRecursive(true);
$osC_Dir_Images->setAddDirectoryToFilename(true);
$files = $osC_Dir_Images->getFiles();

$image_directories = array(array('id' => '', 'text' => 'images/'));
foreach ($files as $file) {
  $image_directories[] = array('id' => $file['name'], 'text' => 'images/' . $file['name']);
}
?>

    <table valign=top border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="20" cellpadding="5">
        <tr>
          <td>
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
<?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>  
<div class="tab-pane" id="mainTabPane">
  <script type="text/javascript"><!--
    var mainTabPane = new WebFXTabPane( document.getElementById( "mainTabPane" ) );
  //-->
  </script>
  <div class="tab-page" id="tabDescription">
    <h2 class="tab"><?php echo TAB_GENERAL; ?></h2>

    <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "tabDescription" ) );
    //-->
    </script>
    <table valign=top border="0" width="100%" cellspacing="0" cellpadding="2">    
      <tr>
        <td class="smallText"><?php echo TEXT_CATEGORIES_STATUS; ?></td>
        <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('categories_status', '1', $in_status) . '&nbsp;' . TEXT_ACTIVE . '&nbsp;' . tep_draw_radio_field('categories_status', '0', $out_status) . '&nbsp;' . TEXT_INACTIVE; ?></td>
      </tr>
      <tr>
        <td class="smallText"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
        <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('categories_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_image . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
      </tr>
<?php
if (tep_not_null($cInfo->categories_image)){
?>
      <tr>
        <td class="smallText">&nbsp;</td>
        <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;<input type="checkbox" name="unlink_categories_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;<input type="checkbox" name="delete_categories_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT;?></td>
      </tr>
<?  
}
?>
      <tr>
        <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
        <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('categories_image_location', $image_directories, dirname($cInfo->categories_image)); ?></td>
      </tr>
      <tr>
        <td class="smallText"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
        <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
      </tr>
    </table>
    
    <div class="tab-pane" id="descriptionTabPane">
      <script type="text/javascript"><!--
        var descriptionTabPane = new WebFXTabPane( document.getElementById( "descriptionTabPane" ) );
      //-->
      </script>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
<?php
$affiliates = tep_get_affiliates();
if (count($affiliates) > 0){
  
?>
      <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    descriptionTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var descriptionTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        
        <div class="tab-pane" id="affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>">
        <script type="text/javascript"><!--
        var affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
      <div class="tab-page" id="affiliateDescriptionTabPane_0_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_0_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
<?php  
}else{
  ?>
      <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" style="width:20%"><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
          
          <tr>
            <td class="smallText"><?php echo TEXT_DIRECT_URL; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('direct_url[' . $languages[$i]['id'] . ']', (isset($direct_url[$languages[$i]['id']]) ? $direct_url[$languages[$i]['id']] : tep_get_cat_direct_url($cInfo->categories_id, $languages[$i]['id'])), 'size="50"'); ?></td>
          </tr>
          
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
          </tr>
          <tr>
            <td class="smallText" colspan="2"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_category\',\'categories_description[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id']))); ?></fieldset></td>
          </tr>

<?php
if (SEARCH_ENGINE_UNHIDE == 'True'){
?>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_PAGE_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', (isset($categories_head_title_tag[$languages[$i]['id']]) ? $categories_head_title_tag[$languages[$i]['id']] : tep_get_categories_head_title_tag($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_HEADER_DESCRIPTION; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? $categories_head_desc_tag[$languages[$i]['id']] : tep_get_categories_head_desc_tag($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_KEYWORDS; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? $categories_head_keywords_tag[$languages[$i]['id']] : tep_get_categories_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
}
?>       
   
        </table>
<?PHP
if (count($affiliates) > 0){
?>
      </div>
      
<?php

  for($j=0;$j<sizeof($affiliates);$j++){
?>        
        <div class="tab-page" id="affiliateDescriptionTabPane_<?php echo $affiliates[$j]['id']?>_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_<?php echo $affiliates[$j]['id']?>_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" style="width:20%"><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_name_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (($categories_name_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? stripslashes($categories_name_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_heading_title_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (isset($categories_heading_title_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? stripslashes($categories_heading_title_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          
          <tr>
            <td class="smallText"><?php echo TEXT_DIRECT_URL; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('direct_url[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (isset($direct_url[$languages[$i]['id']][$affiliates[$j]['id']]) ? $direct_url[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_cat_direct_url($cInfo->categories_id, $languages[$i]['id'])), 'size="50"'); ?></td>
          </tr>
          
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
          </tr>
          <tr>
            <td class="smallText" colspan="2"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_category\',\'categories_description_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('categories_description_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '15', (($categories_description_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? stripslashes($categories_description_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></fieldset></td>
          </tr>


<?php
if (SEARCH_ENGINE_UNHIDE == 'True'){
?>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_PAGE_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('categories_head_title_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (isset($categories_head_title_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $categories_head_title_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_categories_head_title_tag($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_HEADER_DESCRIPTION; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('categories_head_desc_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '5', (isset($categories_head_desc_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $categories_head_desc_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_categories_head_desc_tag($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_CATEGORIES_KEYWORDS; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('categories_head_keywords_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '5', (isset($categories_head_keywords_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $categories_head_keywords_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_categories_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
<?php
}
?>       
        </table>        
        </div>  
<?    
  }
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
<?php
if (SUPPLEMENT_STATUS == 'True'){
?>  
    <div class="tab-page" id="xSell">
    <h2 class="tab"><?php echo TEXT_XSELL; ?></h2>
      <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "xSell" ) );
    //-->
    </script>
<script language="JavaScript">
  var counter = 0;

  function morexSell() {
    if (document.new_category.xsell_select.options.selectedIndex < 0) return;
    var existingFields = document.new_category.getElementsByTagName('input');
    var attributeExists = false;

    var val = document.new_category.xsell_select.options[document.new_category.xsell_select.options.selectedIndex].value;
    if (val.indexOf('prod_') != -1){
      val = val.replace('prod_', '');
      for (i=0; i<existingFields.length; i++) {
        if (existingFields[i].value == val && existingFields[i].name == 'xsell_product_id[]') {
          attributeExists = true;
          break;
        }
      }
      if (attributeExists == false) {
        counter++;
        var newFields = document.getElementById('readproducts').cloneNode(true);
        newFields.id = '';
        newFields.style.display = 'block';
  
        var inputFields = newFields.getElementsByTagName('input');
  
        for (y=0; y<inputFields.length; y++) {
          if (inputFields[y].type != 'button') {
            inputFields[y].name = inputFields[y].name.substr(4);
            if (inputFields[y].type != 'hidden'){
              if (inputFields[y].value != '0'){
                inputFields[y].value = document.new_category.xsell_select.options[document.new_category.xsell_select.options.selectedIndex].text;
              }
            }else{
              inputFields[y].value = val;
            }
            inputFields[y].disabled = false;
          }
        }
  
        var insertHere = document.getElementById('writeproducts');
        insertHere.parentNode.insertBefore(newFields,insertHere);
      }

    }

  }

  function moreupSell() {
    if (document.new_category.upsell_select.options.selectedIndex < 0) return;
    var existingFields = document.new_category.getElementsByTagName('input');
    var attributeExists = false;

    var val = document.new_category.upsell_select.options[document.new_category.upsell_select.options.selectedIndex].value;
    if (val.indexOf('cat_') != -1){
      val = val.replace("cat_", "");
      for (i=0; i<existingFields.length; i++) {
        if (existingFields[i].value == val && existingFields[i].name == 'upsell_categories_id[]') {
          attributeExists = true;
          break;
        }
      }
      if (attributeExists == false) {
        counter++;
        var newFields = document.getElementById('readcategoriesupsell').cloneNode(true);
        newFields.id = '';
        newFields.style.display = 'block';
  
        var inputFields = newFields.getElementsByTagName('input');
  
        for (y=0; y<inputFields.length; y++) {
          if (inputFields[y].type != 'button') {
            inputFields[y].name = inputFields[y].name.substr(4);
            if (inputFields[y].type != 'hidden'){
              if (inputFields[y].value != '0'){
                inputFields[y].value = document.new_category.upsell_select.options[document.new_category.upsell_select.options.selectedIndex].text;
              }
            }else{
              inputFields[y].value = val;
            }
            inputFields[y].disabled = false;
          }
        }
  
  
        var insertHere = document.getElementById('writecategoriesupsell');
        insertHere.parentNode.insertBefore(newFields,insertHere);
      }

    }else{
      val = val.replace('prod_', '');
      for (i=0; i<existingFields.length; i++) {
        if (existingFields[i].value == val && existingFields[i].name == 'upsell_product_id[]') {
          attributeExists = true;
          break;
        }
      }
      if (attributeExists == false) {
        counter++;
        var newFields = document.getElementById('readproductsupsell').cloneNode(true);
        newFields.id = '';
        newFields.style.display = 'block';
  
        var inputFields = newFields.getElementsByTagName('input');
  
        for (y=0; y<inputFields.length; y++) {
          if (inputFields[y].type != 'button') {
            inputFields[y].name = inputFields[y].name.substr(4);
            if (inputFields[y].type != 'hidden'){
              if (inputFields[y].value != '0'){
                inputFields[y].value = document.new_category.upsell_select.options[document.new_category.upsell_select.options.selectedIndex].text;
              }
            }else{
              inputFields[y].value = val;
            }
            inputFields[y].disabled = false;
          }
        }
  
  
        var insertHere = document.getElementById('writeproductsupsell');
        insertHere.parentNode.insertBefore(newFields,insertHere);
      }

    }

  }

  
  function toggleXSellStatus(xsellID) {
    var row = document.getElementById(xsellID);
    var rowButton = document.getElementById(xsellID + '-button');
    var inputFields = row.getElementsByTagName('input');

    if (rowButton.value == '-') {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = true;
        }
      }

      row.className = 'attributeRemove';
      rowButton.value = '+';
    } else {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = false;
        }
      }


      row.className = '';
      rowButton.value = '-';
    }
  }

  function toggleUpSellStatus(upsellID) {
    var row = document.getElementById(upsellID);
    var rowButton = document.getElementById(upsellID + '-button');
    var inputFields = row.getElementsByTagName('input');

    if (rowButton.value == '-') {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = true;
        }
      }

      row.className = 'attributeRemove';
      rowButton.value = '+';
    } else {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = false;
        }
      }
      row.className = '';
      rowButton.value = '-';
    }
  }

</script>
     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="xsell_select" size="20" style="width: 100%;">
<?php
  $categories_array = tep_get_full_category_tree();
  for ($i=0,$n=sizeof($categories_array);$i<$n;$i++){
    if ($categories_array[$i]['category'] == 1){
      echo '<option id="' .$categories_array[$i]['id']. '" value="cat_' .$categories_array[$i]['id']. '" style="COLOR:#0046D5" disabled>' . $categories_array[$i]['text'] . '</option>';
    }else{
      echo '<option id="' .$categories_array[$i]['id']. '" value="prod_' .$categories_array[$i]['id']. '" style="COLOR:#555555">' . $categories_array[$i]['text'] . '</option>';
    }
  }
?>        
        </select>
        </td>
        <td align="center" width="10%" class="smallText">
          <input type="button" value=">>" onClick="morexSell()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_XSELL_PRODUCTS; ?></legend>
           

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS) || !is_array($HTTP_POST_VARS['xsell_product_id'])){
  $query = tep_db_query("select cpxs.xsell_products_id, cpxs.sort_order, pd.products_name from  " . TABLE_CATS_PRODUCTS_XSELL . " cpxs, " . TABLE_PRODUCTS_DESCRIPTION . " pd where cpxs.xsell_products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and pd.affiliate_id = 0 and cpxs.categories_id = '" . $cInfo->categories_id . "'");
  while ($data = tep_db_fetch_array($query)){
    echo '      <tr  id="xsell-' . $data['xsell_products_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('xsell_product_name[]', $data['products_name'], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('xsell_products_sort_order[]', $data['sort_order']) . tep_draw_hidden_field('xsell_product_id[]', $data['xsell_products_id'], '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="xsell-' . $data['xsell_products_id']  . '-button" onClick="toggleXSellStatus(\'xsell-' . $data['xsell_products_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}else{

  foreach($HTTP_POST_VARS['xsell_product_id'] as $key => $value){
    echo '      <tr  id="xsell-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('xsell_product_name[]', $HTTP_POST_VARS['xsell_product_name'][$key], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('xsell_products_sort_order[]', $HTTP_POST_VARS['xsell_products_sort_order'][$key])  . tep_draw_hidden_field('xsell_product_id[]', $value, '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="xsell-' . $value  . '-button" onClick="toggleXSellStatus(\'xsell-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>
            </table>
            <span id="writeproducts"></span>
             <div id="readproducts" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_xsell_product_name[]', '', 'disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_xsell_products_sort_order[]', '0', 'disabled') . tep_draw_hidden_field('new_xsell_product_id[]', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);" class="infoBoxButton"></td>
                </tr>                
              </table>
            </div>
          </fieldset>            
          </td>
        </tr>
        
     </table>

    </div>
    <div class="tab-page" id="upSell">
    <h2 class="tab"><?php echo TEXT_UPSELL; ?></h2>
      <script type="text/javascript"><!--
      mainTabPane.addTabPage( document.getElementById( "upSell" ) );
    //-->
    </script>

     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="upsell_select" size="20" style="width: 100%;">
<?php
  for ($i=0,$n=sizeof($categories_array);$i<$n;$i++){
    if ($categories_array[$i]['category'] == 1){
      echo '<option id="' .$categories_array[$i]['id']. '" value="cat_' .$categories_array[$i]['id']. '" style="COLOR:#0046D5" disabled>' . $categories_array[$i]['text'] . '</option>';
    }else{
      echo '<option id="' .$categories_array[$i]['id']. '" value="prod_' .$categories_array[$i]['id']. '" style="COLOR:##FF8A00">' . $categories_array[$i]['text'] . '</option>';
    }
  }
?>        
        </select>
        </td>
        <td align="center" width="10%" class="smallText">
          <input type="button" value=">>" onClick="moreupSell()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_UPSELL_CATEGORIES; ?></legend>
           

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS) || !is_array($HTTP_POST_VARS['upsell_category_id'])){
  $query = tep_db_query("select cpus.upsell_id, cd.categories_name, cpus.sort_order from  " . TABLE_CATEGORIES_UPSELL . " cpus, " . TABLE_CATEGORIES_DESCRIPTION. " cd where cpus.upsell_id = cd.categories_id and cd.language_id = '" . $languages_id . "' and cd.affiliate_id = 0 and cpus.categories_id = '" . $cInfo->categories_id . "'");
  while ($data = tep_db_fetch_array($query)){
    echo '      <tr id="upsell-categories-' . $data['upsell_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_categories_name[]', $data['categories_name'], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_category_sort_order[]', $data['sort_order'])  . tep_draw_hidden_field('upsell_category_id[]', $data['upsell_id'], '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-categories-' . $data['upsell_id']  . '-button" onClick="toggleUpSellStatus(\'upsell-categories-' . $data['upsell_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}else{

  foreach($HTTP_POST_VARS['upsell_category_id'] as $key => $value){
    echo '      <tr id="upsell-categories-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_categories_name[]', $HTTP_POST_VARS['upsell_categories_name'][$key], ' readonly') . '</td>
                  <td class="smallText"><nobr>'  . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_category_sort_order[]', $HTTP_POST_VARS['xsell_category_sort_order'][$key])  . tep_draw_hidden_field('upsell_category_id[]', $value, '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-categories-' . $value  . '-button" onClick="toggleUpSellStatus(\'upsell-categories-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>              
            </table>
            <span id="writecategoriesupsell"></span>
             <div id="readcategoriesupsell" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_upsell_category_name[]', '', 'disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_upsell_category_sort_order[]', '0', 'disabled') . tep_draw_hidden_field('new_upsell_category_id[]', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);" class="infoBoxButton"></td>
                </tr>                
              </table>
            </div>
          </fieldset>            
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_UPSELL_PRODUCTS; ?></legend>
           

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS) || !is_array($HTTP_POST_VARS['upsell_product_id'])){
  $query = tep_db_query("select cpus.upsell_products_id, pd.products_name, cpus.sort_order from  " . TABLE_CATS_PRODUCTS_UPSELL . " cpus, " . TABLE_PRODUCTS_DESCRIPTION . " pd where cpus.upsell_products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and pd.affiliate_id = 0 and cpus.categories_id = '" . $cInfo->categories_id . "'");
  while ($data = tep_db_fetch_array($query)){
    echo '      <tr id="upsell-' . $data['upsell_products_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_product_name[]', $data['products_name'], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_products_sort_order[]', $data['sort_order'])  .  tep_draw_hidden_field('upsell_product_id[]', $data['upsell_products_id'], '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-' . $data['upsell_products_id']  . '-button" onClick="toggleUpSellStatus(\'upsell-' . $data['upsell_products_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}else{

  foreach($HTTP_POST_VARS['upsell_product_id'] as $key => $value){
    echo '      <tr id="upsell-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_product_name[]', $HTTP_POST_VARS['upsell_product_name'][$key], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_products_sort_order[]', $HTTP_POST_VARS['upsell_products_sort_order'][$key])  . tep_draw_hidden_field('upsell_product_id[]', $value, '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-' . $value  . '-button" onClick="toggleUpSellStatus(\'upsell-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>
            </table>
            <span id="writeproductsupsell"></span>
             <div id="readproductsupsell" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_upsell_product_name[]', '', 'disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_upsell_products_sort_order[]', '0', 'disabled')  . tep_draw_hidden_field('new_upsell_product_id[]', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);" class="infoBoxButton"></td>
                </tr>                
              </table>
            </div>
          </fieldset>            
          </td>
        </tr>
     </table>
    </div>
<?php
}
?>
</div>  
   </td>
   </tr>
   </table>
   </td>
   </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
    </table>
