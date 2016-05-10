<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class RecordSetProxy{
  var $link;
  var $res = null;

  function RecordSetProxy(){

  }

  /**
   * Fetch data after query select
   * @return array|false 
   */
  function next(){
    if ( is_resource($this->res) ) {
      return tep_db_fetch_array( $this->res );
    }else{
      return false;
    }
  }

  /**
   * Execute sql query
   * @param string $sql
   */
  function query( $sql ){
    $this->res = tep_db_query( $sql );
    return $this;
  }

  function fetchOne($sql){
    $this->query($sql);
    if ($ret_ = $this->next()) {
      $ret_ = array_values($ret_);
      $ret = $ret_[0];
    }else{
      $ret = false;
    }
    return $ret;
  }

  /**
   * Get row count after query select
   * @return int
   */
  function count(){
    if ( is_resource($this->res) ) return tep_db_num_rows($this->res);
    return 0;
  }

  /**
   * Insert data into table
   * @param string $table
   * @param array $data
   * @return int autoincrement
   */
  function insert( $table, $data ){
    $query = "INSERT INTO `{$table}` (`".implode("`,`", array_keys($data))."`) VALUES (";
    $first = true;
    foreach( $data as $val ){
      if ( !$first ) $query .= ', ';
      if ( is_null($val) ) {
        $query .= 'NULL';
      }elseif( strtolower($val)=='now()' ) {
        $query .= 'NOW()';
      }else{
        $query .= "'".$this->esc($val)."'";
      }
      $first = false;
    }
    $query .= ')';
    $this->query($query);
    return tep_db_insert_id();
  }

  /**
   * Update data in table
   * @param string $table
   * @param array $data
   * @param array $criteria
   */
  function update( $table, $data, $criteria='' ){
    $query = "UPDATE `{$table}` SET ";
    $first = true;
    foreach( $data as $key=>$val ){
      if ( !$first ) $query .= ', ';
      if ( is_null($val) ) {
        $query .= "`{$key}`=NULL";
      }elseif( strtolower($val)=='now()' ) {
        $query .= "`{$key}`=NOW()";
      }else{
        $query .= "`{$key}`='".$this->esc($val)."'";
      }
      $first = false;
    }
    if (is_array($criteria)) {
      $first = true;
      foreach( $criteria as $key=>$val ) {
        if ( !$first ) $query .= ' AND ';
        $query .= "`{$key}`='".$this->esc($val)."'";
      }
    }elseif(!empty($criteria)){
      $query .= " WHERE ".$criteria;
    }
    $this->query($query);
  }

  function affected(){
    global $db_link;
    return intval( mysql_affected_rows($db_link) );
  }
  function esc($string) {
    return tep_db_input( $string );
  }

}

?>
