<?php

class common_feed{
  
  function common_feed(){

  }

  function admin_ui(){

  }

  function before_process(){

  }

  function process(){

  }

  function after_process(){

  }

  function glob( $path, $pattern='', $sort_key='filename' ){
    $ret = array();
    if (is_dir($path)) {
      if (substr($path,-1)!='/' ) $path .= '/';
      if ($dh = @opendir($path)) {
        while (($file = readdir($dh)) !== false) {
          if ( strpos($file,'.')===0 || is_dir($path.$file) ) continue; //skip hidden files and root dirs & dirs
          if (!empty($pattern)) {
            if ( is_array($pattern) ) {
              $pass = false;
              foreach( $pattern as $pattern_str ) {
                if (preg_match($pattern_str, $file)) { $pass=true; break;};
              }
              if ( !$pass ) continue;
            }elseif (!preg_match($pattern, $file)) continue;
          }
          $ret[] = array(
            'filename' => $file,
            'size' => filesize($path.$file),
            'modifed' => filemtime($path.$file),
            'created' => filectime($path.$file),
            'is_writable' => is_writable($path.$file),
          );
        }
        closedir($dh);
      }
    }
    if ( count($ret)>1 ) {
      $sort = array();
      $is_string = null;
      foreach( $ret as $sidx=>$sarr ) {
        if (!array_key_exists($sort_key, $sarr)) break;
        $is_string = is_string($sarr[$sort_key]);
        $sort[$sidx] = ($is_string?strtolower($sarr[$sort_key]):$sarr[$sort_key]);

      }
      if ( count($sort)>0 ) {
        array_multisort($sort, $is_string?SORT_STRING:SORT_NUMERIC, $ret);
      }
    }
    return $ret;
  }

}

class feed_base_txt extends common_feed {
  var $_fields_delimiter = "\t";
  var $_line_delimeter = "\n";
  var $_have_header = true;
  var $_header_rewrite = false;
  var $_hide_columns = false;
  
  var $_out_to = 'memory'; // memory | file
  var $_data;
  var $_datasource;
  var $_file_handle;
  var $_buffer = '';
  var $_file_name = '';
  var $_aprox_lines = 0;

  function feed_base(){
  
  }

  function columns( $new_columns ){
    if ( is_array($new_columns) ) {
      $this->_header_rewrite = $new_columns; 
    }
  }
  function column_info( $column ){
    if ( isset($this->_header_rewrite[$column]) ) {
      return $this->_header_rewrite[$column];
    }
    return false;
  }

  function hide( $hide_columns ){
    if ( is_string($hide_columns) ) $hide_columns = array($hide_columns);
    if ( is_array($hide_columns) ) {
      if ( !is_array($this->_hide_columns) ) $this->_hide_columns = array();
      $this->_hide_columns = array_merge($this->_hide_columns, $hide_columns);
    }
  }

  function before_out_header(){}

  function out_header(){
    $line = implode( $this->_fields_delimiter, array_keys($this->_data) ).$this->_line_delimeter;
    switch ( $this->_out_to ) {
      case 'memory':
        $this->_buffer .= $line;
      break;
      case 'file':
        @fwrite($this->_file_handle, $line);
      break; 
    }
  }

  function before_out(){
    if ( is_array($this->_hide_columns) ) {
      foreach( $this->_hide_columns as $unset_column ) {
        if ( array_key_exists( $unset_column, $this->_data ) ) unset($this->_data[$unset_column]);
      }
    }
    if ( is_array($this->_header_rewrite) ) {
      $_rewrite = array();
      foreach( $this->_header_rewrite as $new_column_name=>$rewritedata ) {
        $default = $value = '';
        $data_column = false;
        if ( is_array($rewritedata) ) {
          $default = (!empty($rewritedata['default'])?$rewritedata['default']:'');
          $value = (!empty($rewritedata['value'])?$rewritedata['value']:'');
          $data_column = (!empty($rewritedata['field'])?$rewritedata['field']:false);
        }elseif( is_string($new_column_name) && is_string($rewritedata) ){
          $data_column = $rewritedata;
        }else{
          $new_column_name = $rewritedata;
        }
        $_rewrite[$new_column_name] = ($data_column!==false && isset($this->_data[$data_column]))?$this->_data[$data_column]:$default;
        if (!empty($value)) $_rewrite[$new_column_name] = $value;
      }
      $this->_data = $_rewrite; 
    }
    return true;
  }

  function after_out(){
    return true;
  }

  function field_prepare( $value, $column ){
    $value = str_replace(array("\r","\n","\t"), array(' ',' ',' '), $value);
    if ( strpos($value,'"')!==false ) $value = '"'.str_replace('"', '""', $value).'"';
    return $value;
  }

  function out(){
    foreach( $this->_data as $idx=>$value ){
      $this->_data[$idx] = $this->field_prepare($value, $idx);
    }
    $line = implode( $this->_fields_delimiter, $this->_data ).$this->_line_delimeter;
    switch ( $this->_out_to ) {
      case 'memory':
        $this->_buffer .= $line;
      break;
      case 'file':
        fwrite($this->_file_handle, $line);
      break; 
    }
    $this->after_out();
  }

  function lines_count(){
    return $this->_aprox_lines;
  }

  function set_source( $variant ){
    $this->_aprox_lines = 0;
    if ( is_array($variant) ) {
      $this->_datasource = $variant;
      $this->_aprox_lines = count($this->_datasource); 
    }elseif( is_resource($variant) ) {
      $this->_datasource = $variant;
      $this->_aprox_lines = tep_db_num_rows($this->_datasource);
    }elseif( is_string($variant) && preg_match("/^select/i",$variant) ){
      $this->_datasource = tep_db_query($variant);
      $this->_aprox_lines = tep_db_num_rows($this->_datasource);
    }
  }

  function process(){
    $header_out = $this->_have_header;
    if ( is_resource($this->_datasource) ) {
      while( $this->_data = tep_db_fetch_array($this->_datasource) ) {
        if ( !$this->before_out() ) continue;
        if ( $header_out ) {
          $this->before_out_header();
          $this->out_header();
          $header_out = false;
        }
        $this->out();
      }
    }elseif( is_array($this->_datasource) ) {
      foreach( $this->_datasource as $_tmp_data ) {
        $this->_data = $_tmp_data;
        if ( !$this->before_out() ) continue;
        if ( $header_out ) {
          $this->before_out_header();
          $this->out_header();
          $header_out = false;
        }
        $this->out();
      }
    }
  }

  function open( $file_name = '' ){
    if ( !empty($file_name) ) {
      if ( $this->_file_handle = @fopen($file_name, 'w') ){
        $this->_file_name = $file_name;
        $this->_out_to = 'file';
        return true;
      }else{
        return false;
      }
    }else{
      $this->_out_to = 'memory';
      $this->_buffer = '';
      return true;
    }
  }

  function close(){
    if ( $this->_out_to=='memory' ) {
      return true;
    }elseif( $this->_out_to=='file' ) {
      if ($this->_file_handle) {
        @fclose($this->_file_handle);
        @chmod($this->_file_name, 0666);
        return true;
      }else{
        return false;
      }
    }
  }

  function get_buffer(){
    return $this->_buffer;
  }

}


?>