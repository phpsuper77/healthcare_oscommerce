<?php
/*
  $Id: tell_a_friend.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tell_a_friend //-->
<?php
  $boxHeading = BOX_HEADING_TELL_A_FRIEND;
  $corner_left = 'square';
  $corner_right = 'square';
  $boxContent_attributes = ' align="center"';

  $boxContent = tep_draw_form('tell_a_friend', tep_href_link(FILENAME_TELL_A_FRIEND, '', 'NONSSL', false), 'get');
  $boxContent .= tep_draw_input_field('to_email_address', '', 'size="10"') . '&nbsp;' . tep_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) . tep_draw_hidden_field('products_id', $HTTP_GET_VARS['products_id']) . tep_hide_session_id() . '<br>' . BOX_TELL_A_FRIEND_TEXT;
  $boxContent .= '</form>';
  require(DIR_WS_TEMPLATES . TEMPLATENAME_BOX);

  $boxContent_attributes = '';
?>
<!-- tell_a_friend_eof //-->
