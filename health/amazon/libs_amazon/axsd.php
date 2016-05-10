<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class axsd{
  function safe($string){
    if ( function_exists('iconv') ) $string = iconv('ISO-8859-1', 'UTF-8', $string);
    return str_replace( array('&','"','<','>'), array('&amp;','&quot;','&lt;','&gt;'), $string );
    $string = str_replace('&', '&amp;', $string);
    //$string = str_replace("'", '&apos;', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    return $string;
  }
  function normalizedString($string, $minL, $maxL ){
    /*$string = str_replace("\t", " ", $string);
    $string = str_replace("\r", " ", $string);
    $string = str_replace("\n", " ", $string);*/
    $string = str_replace( array("\t","\r","\n"), array(' ',' ',' '), $string);
    $string = axsd::safe( $string );
    if ( strlen($string)==0 && $minL>0 ) $string = ' ';
    if ( strlen($string)>$maxL ) {
      $string = substr($string, 0, $maxL);
      $amp = strrpos($string,'&');
      if ( $amp!==false ) {
        $closed = strpos($string, ';', $amp);
        if ( $closed===false ) {
          $string = substr($string, 0, $amp);
        }
      }
    }
    // fix cuted utf
    if ( strlen($string)>3 ) {
      $do_cut = false;
      $now_len = strlen($string);
      for( $i=$now_len; $i>$now_len-5; $i-- ) {
        if ( ord($string[$i-1])>191 ) {
          $do_cut = $i-1;
          break;
        }
      }
      if( $do_cut!==false ) {
        $now_len = strlen($string);
        $cut_diff = $now_len-$do_cut;
        if ( (ord($string[$do_cut])>=240 && $cut_diff<4) ||
             (ord($string[$do_cut])>=224 && $cut_diff<3) ||
             (ord($string[$do_cut])>=192 && $cut_diff<2) 
           ) {
          $string = substr($string, 0, $do_cut);
        }
      }
    }
    return $string;
  }
  function SuperLongStringNotNull($string){
    return axsd::normalizedString( $string, 1, 1000 );
  }
  function LongStringNotNull($string){
    return axsd::normalizedString( $string, 1, 500 );
  }
  function StringNotNull($string){
    return axsd::normalizedString( $string, 1, 50 );
  }
  function TwentyStringNotNull($string){
    return axsd::normalizedString( $string, 1, 20 );
  }
  function FortyStringNotNull($string){
    return axsd::normalizedString( $string, 1, 40 );
  }
  function nonNegativeInteger( $string ){
    $string = (int)$string;
    return (string)$string;
  }
  function BaseCurrencyAmount( $amount ){
    return number_format($amount, 2, '.', '');
  }
  // TODO: Check time zones!!
  function date( $sql_format, $utc=true ){
    if ( is_numeric($sql_format) ) {
      $timestamp = $sql_format;
    }elseif ( preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$sql_format,$match) ) {
      $timestamp = mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
    }elseif ( preg_match('/^(\d{4})-(\d{2})-(\d{2})/',$sql_format,$match) ){
      $timestamp = mktime(0, 0, 0, $match[2], $match[3], $match[1]);
    }else{
      return false;
    }
    $datestr = date('Y-m-d',$timestamp);
    if(false && $utc){
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
    		return sprintf('%04d-%02d-%02d',$regs[1],$regs[2],$regs[3]);
    	}
    	return false;
    } else {
    	return $datestr;
    }
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
    $datestr = date('Y-m-d\TH:i:s',$timestamp).preg_replace('/(.*?)(\d{2})$/', '$1:$2', date('O'));
    
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


?>
