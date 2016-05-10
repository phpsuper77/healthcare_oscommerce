<?php
/*
  $Id: information.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
<?php
  $boxHeading = BOX_HEADING_INFORMATION;
  $corner_left = 'square';
  $corner_right = 'square';

  $boxContent = '<a href="' . tep_href_link(FILENAME_SHIPPING) . '"> ' . BOX_INFORMATION_SHIPPING . '</a><br>' .
                '<a href="' . tep_href_link(FILENAME_PRIVACY) . '"> ' . BOX_INFORMATION_PRIVACY . '</a><br>' .
                '<a href="' . tep_href_link(FILENAME_CONDITIONS) . '"> ' . BOX_INFORMATION_CONDITIONS . '</a><br>' .
                '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '"> ' . BOX_INFORMATION_CONTACT . '</a><br>'.
                '<a href="' . tep_href_link(FILENAME_GV_FAQ, '', 'NONSSL') . '"> ' . BOX_INFORMATION_GV . '</a>';//ICW ORDER TOTAL CREDIT CLASS/GV

  require(DIR_WS_TEMPLATES . TEMPLATENAME_BOX);
?>
<!-- information_eof //-->