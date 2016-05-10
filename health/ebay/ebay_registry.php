<?php

class ebay_registry {
  var $_keep;
  var $use_connector_id = false;
  
  function ebay_registry(){
    $this->_keep = array();
    $this->setConnectorID( (int)EBAY_CONNECTOR_ID );
  }
  
  function setConnectorID( $connector_id ){
    $this->_keep = array();
    $this->use_connector_id = (int)$connector_id;
  }
  function getConnectorID(){
    return $this->use_connector_id;
  }
  
  function getValue($key){
    if ( !array_key_exists($key, $this->_keep) ) {
      $r_r = tep_db_query("select val1 from ".TABLE_EBAY_REGISTRY." where connector_id=".(int)$this->getConnectorID()." and key1='".tep_db_input($key)."'");
      if ( tep_db_num_rows($r_r)==0 ) {
        $this->_keep[$key] = false;
      }else{
        $r = tep_db_fetch_array( $r_r );
        $this->_keep[$key] = $r['val1']; 
      }
    }
    return $this->_keep[$key];
  }

  function setValue($key, $value){
    $old = $this->getValue($key);
    if ( $old===false ) {
      tep_db_query( "insert into ".TABLE_EBAY_REGISTRY." set val1='".tep_db_input($value)."', connector_id=".(int)$this->getConnectorID().", key1='".tep_db_input($key)."'" );
    }else{
      tep_db_query( "update ".TABLE_EBAY_REGISTRY." set val1='".tep_db_input($value)."' where connector_id=".(int)$this->getConnectorID()." and key1='".tep_db_input($key)."'" );
    }
    unset($this->_keep[$key]);
  }

  function getBlob($key){
    if ( !array_key_exists($key, $this->_keep) ) {
      $r_r = tep_db_query("select btext from ".TABLE_EBAY_REGISTRY." where connector_id=".(int)$this->getConnectorID()." and key1='".tep_db_input($key)."'");
      if ( tep_db_num_rows($r_r)==0 ) {
        $this->_keep[$key] = false;
      }else{
        $r = tep_db_fetch_array( $r_r );
        $this->_keep[$key] = $r['btext']; 
      }
    }
    return $this->_keep[$key];
  }

  function setBlob($key, $value){
    $old = $this->getValue($key);
    if ( $old===false ) {
      tep_db_query( "insert into ".TABLE_EBAY_REGISTRY." set btext='".tep_db_input($value)."', connector_id=".(int)$this->getConnectorID().", key1='".tep_db_input($key)."'" );
    }else{
      tep_db_query( "update ".TABLE_EBAY_REGISTRY." set btext='".tep_db_input($value)."' where connector_id=".(int)$this->getConnectorID()." and key1='".tep_db_input($key)."'" );
    }
    unset($this->_keep[$key]);
  }

}


?>