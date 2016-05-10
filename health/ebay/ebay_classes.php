<?php

class ebay_tools{
  function UUID( $from=false ){
    if ( $from===false ) $from = mktime();
    $uuid = strtoupper(md5($from));
    $ret = substr($uuid, 0, 8).'-'.substr($uuid, 8, 4).'-'.substr($uuid, 12, 4).'-'.substr($uuid, 16);
    return $ret; 
  }
  function tax_add( $amount, $tax=EBAY_TAX_RATE ){
    $ret = $amount * ((100+$tax)/100);
    $ret = number_format($ret, 4, '.', '');
    return $ret;
  }
  function tax_value( $amount, $tax=EBAY_TAX_RATE ){
    if ($tax==0) return 0;
    $net_value = ebay_tools::tax_reduce( $amount, $tax );
    $ret = number_format($amount-$net_value, 4, '.', '');
    return $ret;
  }
  function tax_reduce( $amount, $tax=EBAY_TAX_RATE ){
    if ( $tax>0 ) {
      $ret = ($amount*100)/(100+$tax);
    }else{
      $ret = $amount;
    }
    $ret = number_format($ret, 4, '.', '');
    return $ret;
  }
  function dateTime( $sql_format, $utc=true ){
    if ( is_numeric($sql_format) ) {
      $timestamp = $sql_format;
    }elseif ( preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$sql_format,$match) ) {
      $timestamp = mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
    }elseif ( preg_match('/^(\d{4})-(\d{2})-(\d{2})/',$sql_format,$match) ){
      $timestamp = mktime(0, 0, 0, $match[2], $match[3], $match[1]);
    }else{
      return false;
    }

    $datestr = date('Y-m-d\TH:i:sO',$timestamp);
    //$datestr2 = gmdate('Y-m-d\TH:i:s',$timestamp).'Z';
    //return $datestr2;
    return $datestr;
    
    if($utc){
    	$eregStr =
    	'([0-9]{4})-'.	// centuries & years CCYY-
    	'([0-9]{2})-'.	// months MM-
    	'([0-9]{2})'.	// days DD
    	'T'.			// separator T
    	'([0-9]{2}):'.	// hours hh:
    	'([0-9]{2}):'.	// minutes mm:
    	'([0-9]{2})(\.[0-9]*)?'. // seconds ss.ss...
    	'(Z|[+\-][0-9]{2}:?[0-9]{2})?'; // Z to indicate UTC, -/+HH:MM:SS.SS... for local tz's

    	if(ereg($eregStr,$datestr,$regs)){
        return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ',$regs[1],$regs[2],$regs[3],$regs[4],$regs[5],$regs[6]);
    	}
    	return false;
    } else {
    	return $datestr;
    }
  }
  function _dateTime( $datestr ){
    $eregStr =
    '([0-9]{4})-'.	// centuries & years CCYY-
    '([0-9]{2})-'.	// months MM-
    '([0-9]{2})'.	// days DD
    'T'.			// separator T
    '([0-9]{2}):'.	// hours hh:
    '([0-9]{2}):'.	// minutes mm:
    '([0-9]{2})(\.[0-9]+)?'. // seconds ss.ss...
    '(Z|[+\-][0-9]{2}:?[0-9]{2})?'; // Z to indicate UTC, -/+HH:MM:SS.SS... for local tz's
    if(ereg($eregStr,$datestr,$regs)){
    	// not utc
    	if($regs[8] != 'Z'){
    		$op = substr($regs[8],0,1);
    		$h = substr($regs[8],1,2);
    		$m = substr($regs[8],strlen($regs[8])-2,2);
    		if($op == '-'){
    			$regs[4] = $regs[4] + $h;
    			$regs[5] = $regs[5] + $m;
    		} elseif($op == '+'){
    			$regs[4] = $regs[4] - $h;
    			$regs[5] = $regs[5] - $m;
    		}
    	}
    	//return gmmktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
    	$timestamp = gmmktime($regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1]);
    	//return strftime('%Y-%m-%d %H:%M:%S', $timestamp);
    	//return gmdate('Y-m-d H:i:s',$timestamp);
    	return date('Y-m-d H:i:s',$timestamp);
    } else {
    	return false;
    }
  }

}

class ebay_amount {
  var $value;
  var $currencyID;

  function ebay_amount( $value=0, $currencyID=false ){
    if ( is_object($value) &&  method_exists($value, 'getTypeValue' ) ) {
      $this->setAmount( $value->getTypeValue(), $value->getTypeAttribute('currencyID') );
    } else {
      $this->setAmount( $value, $currencyID );
    }
  }

  function setAmount( $value, $currencyID=false ) {
    if ( $currencyID===false ) $currencyID = ebay_config::defaultCurrency();
    if ( !is_numeric($value) && preg_match( '/^([\d,\. -]*\d)[,\.](\d{1,2})?$/', $value, $m ) ) {
      $value = str_replace( array('.',',',' '), array('','',''), $m[1] ).'.'.$m[2];
    }
    $this->value = $value;
    $this->currencyID = $currencyID;
  }

  function getConverted($toCurrency=false){
    if ( $toCurrency===false ) $toCurrency = ebay_config::defaultCurrency();
    global $currencies;
    if ( $toCurrency!=$this->currencyID ) {
      $tmp = $this->value/$currencies->currencies[$this->currencyID]['value'];
      return tep_round($tmp*$currencies->currencies[$toCurrency]['value'],4);
    }else{
      return $this->value;
    }
  }

  function summary(){
    global $currencies;
    return array('currency' => $this->currencyID,
                 'value' => $currencies->currencies[$this->currencyID]['value']);
  }

  function getFormated(){
    $raw_val = $this->value;
    switch ($this->currencyID) {
      case 'GBP':
      case 'USD':
        $ret = number_format($raw_val, 2, '.', ',') ;
        break;
      case 'EUR':
        $ret = number_format($raw_val, 2, ',', '') ;
        break;
      default:
        $ret = number_format($raw_val, 2, '.', '') ;
        break;
    }
    return $ret;
  }
}

?>