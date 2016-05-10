<?php
/*
  $Id: tell_a_friend.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (isset($HTTP_GET_VARS['products_id']) && (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) && tep_check_product((int)$HTTP_GET_VARS['products_id'])){
?>
<!-- tell_a_friend //-->
          <tr>
            <td class="infoBoxCell">
<?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_TELL_A_FRIEND);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }
  }

  $info_box_contents = array();
  $info_box_contents[] = array('form' => tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get'),
                               'align' => 'center',
                               'text' => tep_draw_input_field('to_email_address', '', 'size="10"') . '&nbsp;' . tep_template_image_submit('button_tell_a_friend.' . BUTTON_IMAGE_TYPE, BOX_HEADING_TELL_A_FRIEND, 'class="transpng"') . tep_draw_hidden_field('products_id', $HTTP_GET_VARS['products_id']) . tep_hide_session_id() . '<br>' . BOX_TELL_A_FRIEND_TEXT);

    if (class_exists($infobox_class)){
      new $infobox_class($info_box_contents);
    }else{
      new infoBox($info_box_contents);
    }
?>
            </td>
          </tr>
<?php
}
?>
<!-- tell_a_friend_eof //-->
