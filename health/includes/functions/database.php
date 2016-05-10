<?php
/*
  $Id: database.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = @mysql_pconnect($server, $username, $password);
    } else {
      $$link = @mysql_connect($server, $username, $password);
    }
	mysql_set_charset('latin1', $$link);
    if ($$link) @mysql_select_db($database);
    return $$link;
  }

  function tep_db_close($link = 'db_link') {
    global $$link;

    return @mysql_close($$link);
  }

  function tep_db_error($query, $errno, $error) {
    global $mysql_errors, $mysql_error_dump;
    $degug_info = debug_backtrace();
    $file_name = '';
    $error_in_line = '';
    $fname = ''; $fline = '';
    if(is_array( $degug_info['1'])) {
      $file_name = str_replace(DIR_FS_CATALOG, '', $degug_info['1']['file']) . '<br>';
      $fname = str_replace(DIR_FS_CATALOG, '', $degug_info['1']['file']);
      $fline = $degug_info['1']['line'];
      $file_name = 'Filename: ' . $file_name;
      $error_in_line = 'Line: ' . $degug_info['1']['line'] . '<br><br>';
    }
    if(isset($mysql_error_dump)) {
      $mysql_error_dump[] = '<b>' . $errno . ' - ' . $error . '</b><br><br>' . $query . '<br><br>' . $file_name . $error_in_line;
    }
    else {
      $mysql_errors[] = '<b>' . $errno . ' - ' . $error . '</b><br><br>' . $query . '<br><br>' . $file_name . $error_in_line;
    }
    
    @error_log(
      '------'.date('Y-m-d H:i:s')."------\n".$fname.':'.$fline."\n\t".$errno.'-'.$error."\n".$query."\n\n",
      3,
      DIR_FS_CATALOG.(substr(DIR_FS_CATALOG,-1)=='/'?'':'/').'temp/db_errors.txt'
    );
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link, $trans_count;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      $start = microtime(true);

      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
    $result = @mysql_query($query, $$link) or tep_db_error($query, @mysql_errno(), @mysql_error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = @mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
       $time_end =  microtime(true);
       $parse_time = $time_end - $start;
       error_log( 'Query execution: ' . $parse_time . ' ms' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);       
    }
    $trans_count++;

    return $result;
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_fetch_array($db_query) {
    return @mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_db_num_rows($db_query) {
    return @mysql_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return @mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
    return @mysql_insert_id();
  }

  function tep_db_free_result($db_query) {
    return @mysql_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return @mysql_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;
   
    if (function_exists('mysql_real_escape_string')) {
      return @mysql_real_escape_string($string, $$link);
    } elseif (function_exists('mysql_escape_string')) {
      return @mysql_escape_string($string);
    }
   
    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
  
  function tep_db_insert_field($string){
    if (is_string($string)){
      if (get_magic_quotes_runtime()){
        if (PHP_VERSION > '4.3.0'){
          return @mysql_real_escape_string(stripslashes($string));
        }else{
          return $string;
        }
      }else{
        return addslashes($string);
      }
    }elseif (is_array($string)){
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_insert_field($value);
      }
      return $string;
    }else{
      return $string;
    }
  }
?>
