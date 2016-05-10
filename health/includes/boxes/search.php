<?php
/*
  $Id: search.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- search //-->
<?php
  $boxHeading = BOX_HEADING_SEARCH;
  $corner_left = 'square';
  $corner_right = 'square';
  $boxContent_attributes = ' align="center"';

  $boxContent = tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get');
  $boxContent .= tep_draw_input_field('keywords', '', 'size="10" maxlength="30" style="width: ' . (BOX_WIDTH-30) . 'px"') . '&nbsp;' . tep_hide_session_id() . tep_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '<br>' . BOX_SEARCH_TEXT . '<br><a href="' . tep_href_link(FILENAME_ADVANCED_SEARCH) . '"><b>' . BOX_SEARCH_ADVANCED_SEARCH . '</b></a>';
  $boxContent .= '</form>' .
                  '<br><a href="' . tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL') . '">' . BOX_INFORMATION_ALLPRODS . '</a><br>';

  require(DIR_WS_TEMPLATES . TEMPLATENAME_BOX);

  $boxContent_attributes = '';
?>
<!-- search_eof //-->
