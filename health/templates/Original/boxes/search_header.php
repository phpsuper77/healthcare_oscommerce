<?php
/*
  $Id: search.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  CUSTOM SEARCH Version 1.1-MS1

  Allows you to search in descriptions as well
  (through admin -> configuration -> my shop)
  Works on both Linux & Win , SEF Urls on or off.
  Or it should do anyway ;)


  Matthijs (mattice@xs4all.nl)
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Modified by pldtm  (very small modification)
*/
?>
<!-- search //-->
<?php
   /*if (ALLOW_QUICK_SEARCH_DESCRIPTION == 'true') {
      $param = '<input type="hidden" name="search_in_description" value="1">';
   } else {
      $param = '';
   }
    
   if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => "");
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }   
  }
  
  
  
  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get'),
                               'align' => 'center',
                               'text' => $param . tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: 160px"') . '&nbsp;' . tep_hide_session_id() . tep_template_image_submit('button_quick_find.' . BUTTON_IMAGE_TYPE, "Search", 'class="transpng"') . '' . BOX_SEARCH_TEXT . '<br><a class="infoBoxLink" href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><b>' . BOX_SEARCH_ADVANCED_SEARCH . '</b></a>');

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }*/
?>

<?=tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get') ?>
<?=tep_draw_input_field('keywords', '', 'placeholder="Search Healthcare4All"') . tep_hide_session_id();?>
<input type="submit" />
</form>

<!-- search_eof //-->
