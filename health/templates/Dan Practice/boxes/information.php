<?php
  /*
  $Id: information.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  */

$sql=tep_db_query('SELECT i.information_id, i.languages_id, if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, i.page, i.page_type FROM ' . TABLE_INFORMATION .' i left join ' . TABLE_INFORMATION . ' i1 on i.information_id = i1.information_id and i1.languages_id = '. (int)$languages_id . ' and i1.affiliate_id = ' . (int)$HTTP_SESSION_VARS['affiliate_ref'] . ' WHERE i.visible=\'1\' and i.languages_id ='.(int)$languages_id.' and FIND_IN_SET(\'infobox\', i.scope) and i.affiliate_id = 0 ORDER BY i.v_order');

if ( tep_db_num_rows($sql)>0 ) {
?>
<!-- information //-->
          <tr>
            <td class="infoBoxCell">
<?php
if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
  new infoBoxImageHeading($info_box_contents);
}else{
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => BOX_HEADING_INFORMATION);
  $infoboox_class_heading = $infobox_class . 'Heading';
  if (class_exists($infoboox_class_heading)){
    new $infoboox_class_heading($info_box_contents, false, false);
  }else{
    new infoBoxHeading($info_box_contents, false, false);
  }    
  

}
// Retrieve information from Info table
$informationString = "";

$info_box_contents = array();
while($row=tep_db_fetch_array($sql)){
  $title_link = tep_not_null($row['page_title'])?$row['page_title']:$row['info_title'];
  if ($row['page'] == ''){
    $info_box_contents[] = array('text' => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_INFORMATION, 'info_id=' . $row['information_id']) . '" title="'. tep_output_string($title_link) .'">' . $row['info_title'] . '</a>');
  }else{
    $info_box_contents[] = array('text' => '<a class="infoBoxLink" href="' . tep_href_link($row['page'], '', $row['page_type']) . '" title="'. tep_output_string($title_link) .'">' . $row['info_title'] . '</a>');
  }
}
  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- information_eof //-->
<?php 
}
?>