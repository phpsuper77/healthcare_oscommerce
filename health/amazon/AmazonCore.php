<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

 core class
 only this class must be globalized!
*/
class AmazonCore {
  var $log;
  var $soap;
  var $_starting_time;
  function AmazonCore(){
    $this->log = null;
    $this->soap = null;
    $this->_starting_time = 0;
  }
  function startup(){
    $this->say("core_amazon_fws::startup()\n");
    $this->_starting_time = explode(' ',  microtime());
  }
  function shutdown(){
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($this->_starting_time[1] + $this->_starting_time[0])), 4);
    $this->say("core_amazon_fws::shutdown()\n\tWorking time: $parse_time\n");
  }
  function say($string){
    if ( true ) {
      $string = str_replace("\t","&nbsp;&nbsp;",$string);
      echo nl2br($string);
    }else{
      echo $string;
    }
  }
  
  function getSoap(){
    if ( !is_object($this->soap) ) $this->soap = new MerchantInterface();
    $this->soap->clearAttachments();
    $this->say("core_amazon_fws::get_soap()\n");
    return $this->soap;
  }
  function getLog(){
    if ( !is_object($this->log) ) $this->log = new stdClass;
    return $this->log;
  }
}

?>
