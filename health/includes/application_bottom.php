<?php
/*
  $Id: application_bottom.php,v 1.1.1.1 2005/12/03 21:36:10 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)

if(isset($mysql_error_dump) && !empty($mysql_error_dump[0])) {
?>
<div id="id_mysql_error">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="heading">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="heading">You have an error in your SQL syntax</td>
          <td class="close"><a onclick="javascript: document.getElementById('id_mysql_error').style.display = 'none';" href="javascript: void(0);"><img src="templates/Original/images/close.gif" border="0" /></a></td>
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
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    if(STORE_PAGE_PARSE_IP == '*') {
      error_log('Trans total: ' . $trans_count . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
    elseif(ip2long(STORE_PAGE_PARSE_IP) !== false && STORE_PAGE_PARSE_IP == tep_get_ip_address()) {
      error_log('Trans total: ' . $trans_count . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
    else {
      error_log(STORE_PAGE_PARSE_IP . TEXT_INVALID_IP . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
    
    

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
      echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
    }
  }

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }
?>
