<?php
/*
  $Id: mail.php,v 1.1.1.1 2005/12/03 21:36:04 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Sales Survave Email configuration');

define('TEXT_SUBJECT', 'Subject:');
define('TEXT_MESSAGE', 'Message:');

define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('ERROR_NO_SUBJECT_SELECTED', 'Error: Please input email subject.');
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('TEXT_EMAIL_BUTTON_TEXT', '<p><HR><b><font color="red">The Back Button has been DISABLE while HTML WYSIWG Editor is turned ON,</b></font> WHY? - Because if you click the back button to edit your HTML email, The PHP (php.ini - "Magic Quotes = On") will automatically add "\\\\\\\" backslashes everywhere Double Quotes " appear (HTML uses them in Links, Images and More) and this destorts the HTML and the pictures will dissapear once you submit the email again, If you turn OFF WYSIWYG Editor in Admin the HTML Ability of osCommerce is also turned OFF and the back button will re-appear. A fix for this HTML and PHP issue would be nice if someone knows a solution Iv\'e tried.<br><br><b>If you really need to Preview your emails before sending them, use the Preview Button located on the WYSIWYG Editor.<br><HR>');
define('TEXT_EMAIL_BUTTON_HTML', '<p><HR><b><font color="red">HTML is currently Disabled!</b></font><br><br>If you want to send HTML email, Enable WYSIWYG Editor for Email in: Admin-->Configuration-->WYSIWYG Editor-->Options<br>');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF
define('TEXT_YOU_TITLE_HERE', 'Your title here');
define('TEXT_YOU_CONTENT_HERE', 'Your content here');
define('TEXT_EMAIL_UPDATE','Post Sales Survave email was updated');
?>