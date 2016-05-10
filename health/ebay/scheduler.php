<?php

class Scheduler {
  var $task_list;
  var $current_run;
  var $_lock_off_time = 1140;// sec = 19 min
  var $config_lock_var = 'EBAY_UK_RUNLOCK';
  var $config_count_var = 'EBAY_UK_RUNCOUNT';
  function Scheduler(){
    $this->init();
  }
  function init(){
    $this->task_list = array();
    $this->current_run = intval(constant( $this->config_count_var ));

    $this->_add_task('Process orders', (int)EBAY_UK_SCHEDULE_GETORDERS, 'ebay_schedule_orders();' );
    $this->_add_task('Get ebay products', (int)EBAY_UK_SCHEDULE_GETPRODUCTS, 'ebay_schedule_getproducts();' );
    $this->_add_task('Process products', (int)EBAY_UK_SCHEDULE_PRODUCTS, 'ebay_schedule_products();' );

  }
  function _add_task( $name, $count, $func ){
    $this->task_list[] = array( 'name'=>$name, 'count'=>$count, 'function'=>$func );
  }
  function run( ){
    $idle = true;
    $this->current_run++;
    if ( !$this->is_locked() && count($this->task_list)>=0 ) {
      $this->do_lock();
      foreach( $this->task_list as $task ) {
        if ( $task['count']==0 ) continue;
        if ( $task['count']<=$this->current_run && ($this->current_run % $task['count'])==0 ) {
          $this->job_queue[] = $task;
          echo $task['function']."<br>";
          eval( $task['function'] );
          $idle = false;
        }
      }
      $this->do_unlock();
    }
    tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value='".(int)$this->current_run."' where configuration_key='".$this->config_count_var."'");
    if ( $idle ) ebay_log_purge();
  }

  function is_locked(){
    $state = intval(constant( $this->config_lock_var ));
    if ( $state==1 ) {
      // check script fault
      $data_r = tep_db_query("select last_modified from ".TABLE_CONFIGURATION." where configuration_key='".$this->config_lock_var."'");
      if( $data = tep_db_fetch_array($data_r) ){
        preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$data['last_modified'], $t);
        if ( mktime($t[4], $t[5], $t[6]+$this->_lock_off_time, $t[2], $t[3], $t[1])<=mktime() ){
          //do_lock(); // fresh time on lock
          $state = 0;
        }
      }
    }
    return $state!=0;
  }
  function do_lock(){
    tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value=1, last_modified='".date('Y-m-d H:i:s')."' where configuration_key='".$this->config_lock_var."'");
  }
  function do_unlock(){
    tep_db_query("update ".TABLE_CONFIGURATION." set configuration_value=0, last_modified='".date('Y-m-d H:i:s')."' where configuration_key='".$this->config_lock_var."'");
  }
}

?>
