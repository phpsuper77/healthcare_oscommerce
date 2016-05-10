<?php
/*
  $Id: application_bottom.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if(isset($mysql_error_dump) && !empty($mysql_error_dump[0])) {
?>
<div id="id_mysql_error">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="heading">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="heading">You have an error in your SQL syntax</td>
          <td class="close"><a onclick="javascript: document.getElementById('id_mysql_error').style.display = 'none';" href="javascript: void(0);"><img src="../templates/Original/images/close.gif" border="0" /></a></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class="error">
<?php
  for($i = 0; $i <= count($mysql_error_dump); $i++) {
    echo $mysql_error_dump[$i];
    if($i < (count($mysql_error_dump) - 1) && count($mysql_error_dump > 1)) {
      echo '<hr>';
    }
  }
  tep_session_unregister('mysql_error_dump');
?>
    </td>
  </tr>
</table>
</div>
<?php  
}

// close session (store variables)
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
  }
?>
