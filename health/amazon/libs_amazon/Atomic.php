<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonCurrency{

  var $amount;
  var $currencyId = '';

  function AmazonCurrency($amount=0, $currency=''){
    $this->setAmount($amount);
    $this->setCurrencyId($currency);
  }

  function setAmount( $amount ){
    $this->amount = $amount;
  }

  function setCurrencyId($currency=''){
    if ( empty($currency) ) $currency = amazonConfig::getAmazonCurrency();
    $this->currencyId = $currency;
  }

  function getAmount(){
    return $this->amount;
  }
  
  function toXML($tag){
    return "<{$tag} currency=\"{$this->currencyId}\">".axsd::BaseCurrencyAmount($this->amount)."</{$tag}>";
  }

}

?>
