<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class Scheduler {
  var $task_list;
  var $current_run;
  var $_lock_off_time = 1140;// sec = 19 min
  var $config_lock_var = 'AFWS_WEBS_RUNLOCK';
  var $config_count_var = 'AFWS_WEBS_RUNCOUNT';
  var $_lock = false;

  function Scheduler(){
    $this->init();
  } 
  function init(){
    $this->task_list = array();
    $rs = new RecordSetProxy();
    $rs->query("select configuration_value, configuration_key from ".TABLE_CONFIGURATION." where configuration_key in ('".$rs->esc($this->config_lock_var)."','".$rs->esc($this->config_count_var)."')");
    while( $data = $rs->next() ){
      if ( $data['configuration_key']==$this->config_lock_var ) {
        $this->_lock = ((int)$data['configuration_value']==1);
      }elseif ( $data['configuration_key']==$this->config_count_var ) {
        $this->current_run = (int)$data['configuration_value'];
      } 
    }

    $this->_add_task(
      'Poll status for sended', 
      1, 
      'Launcher::amazonPoll();' 
    );
    $this->_add_task(
      'Get amazon orders', 
      AmazonConfig::cronDividerGetOrders(), 
      'Launcher::orderImport();' 
    );
    $this->_add_task(
      'Run post inventory feed', 
      AmazonConfig::cronDividerPostInventory(), 
      'Launcher::postProductFeed(\'inventory\');'
    );
    $this->_add_task(
      'Run post price feed', 
      AmazonConfig::cronDividerPostPrice(), 
      'Launcher::postProductFeed(\'price\');'
    );
    $this->_add_task('Run post shipping feed', 
      AmazonConfig::cronDividerPostShipping(), 
      'Launcher::sendShipped();'
    );
    $this->_add_task(
      'Run post product feed', 
      AmazonConfig::cronDividerPostProducts(),
      'Launcher::postProductFeed(\'products\');'
    );
    $this->_add_task(
      'Run post image feed', 
      AmazonConfig::cronDividerPostImages(),
      'Launcher::postProductFeed(\'images\');'
    );
  }
  function _add_task( $name, $count, $func ){
    $this->task_list[] = array( 'name'=>$name, 'count'=>$count, 'function'=>$func );
  }
  function run( ){
    $this->current_run++;
    if ( !$this->is_locked() && count($this->task_list)>=0 ) {
      $this->do_lock();
      foreach( $this->task_list as $task ) {
        if ( $task['count']==0 ) continue;
        if ( $task['count']<=$this->current_run && ($this->current_run % $task['count'])==0 ) {
          $this->job_queue[] = $task;
          echo $task['function']."<br>";
          eval( $task['function'] );
        }
      }
      $rs = new RecordSetProxy();
      $rs->query("update ".TABLE_CONFIGURATION." set configuration_value='".$rs->esc($this->current_run)."' where configuration_key='".$rs->esc($this->config_count_var)."'");
      $this->do_unlock();
    }
  }
  
  function is_locked(){
    //$state = intval(constant( $this->config_lock_var ));
    //if ( $state==1 ) {
    $state = $this->_lock?1:0;
    if ( $this->_lock ) {
      // check script fault
      $rs = new RecordSetProxy();
      $last_m = $rs->fetchOne("select last_modified from ".TABLE_CONFIGURATION . " where configuration_key='".$rs->esc($this->config_lock_var)."'");

      preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$last_m, $t);
      if ( mktime($t[4], $t[5], $t[6]+$this->_lock_off_time, $t[2], $t[3], $t[1])<=mktime() ){
        //do_lock(); // fresh time on lock
        $state = 0;
      } 
    }
    return $state!=0;
  }
  function do_lock(){
    $rs = new RecordSetProxy();
    $rs->query("update ".TABLE_CONFIGURATION." set configuration_value=1, last_modified='".date('Y-m-d H:i:s')."' where configuration_key='".$rs->esc($this->config_lock_var)."'");
    $this->_lock = true;
  }
  function do_unlock(){
    $rs = new RecordSetProxy();
    $rs->query("update ".TABLE_CONFIGURATION." set configuration_value=0, last_modified='".date('Y-m-d H:i:s')."' where configuration_key='".$rs->esc($this->config_lock_var)."'");
    $this->_lock = false;
  }
}

?>
