<?php
class pricesRecalc {
  
  function pricesRecalc($cCode = 'EUR') {
      $this->_cCode = $cCode;
      $this->_exchangeRate = $this->_calcRate();
  }
function _calcRate() {
      global $currencies;
      if (empty($this->_cCode) || empty($currencies->currencies[$this->_cCode]['value'])) return 1;
      return $currencies->currencies[$this->_cCode]['value'];
  }
  
  function getExchangeRate() {
      return $this->_exchangeRate;
  }                                

  function calcDefRate($price) {
    if ($this->_exchangeRate == 1) return $price;
    return tep_round($price / $this->_exchangeRate, 4);        
  }
  
  function calcExchangeRate($price) {
    if ($this->_exchangeRate == 1) return $price;
    return tep_round($price * $this->_exchangeRate, 4);          
  }      
  
}

$affiliate = (!empty($_COOKIE['affiliate_tracker']) ? tep_db_prepare_input($_COOKIE['affiliate_tracker']) : false);
  function string_gco_prepare($str) {
      return preg_replace("/[^a-zA-Z0-9\.\,()]+/"," ",strip_tags($str));
  }  

class affiliate_gc{
 
  function xml() {
      global $cart, $isTest;
      
        $products = $cart->get_products();
            
$affiliate_xml = array();

// ----------- WebGains ------------- //    
global $affiliate;

if (!empty($affiliate)) {
    $affiliate_xml[$affiliate] = self::generateWebgains($affiliate, $products);
} else {
  //  $affiliate_xml['webgains_de'] = self::generateWebgains('webgains_de', $products);
    //$affiliate_xml['webgains_fr'] = self::generateWebgains('webgains_fr', $products);
    //$affiliate_xml['webgains_us'] = self::generateWebgains('webgains_us', $products);
}    
   return $affiliate_xml;
 }


 function prepare_aw_price($price) {
    $result = number_format($price, 2, '.', '');
    if ($result < 10) $result = '0' . $result;
    return $result;
}
 // ----------- WebGains ------------- //    
 function generateWebgains($affsource = '', $products = array()) {
    $wgProducts = '';
    $porPTotal = 0;
            $recalcP = new pricesRecalc('GBP');
            $wgLang = 'en_GB';# string, used to identify the human language of the transaction
            $wgProgramID = 5743; # int, used to identify you to webgains systems
            $wgEventID=8995; # this identify's the commission type (in account under Program Setup (commission types))
            $wgPin = 7958;# pin number provided by webgains (in account under Program Setup (program settings -> technical setup))
    if (sizeof($products) > 0)  {
        $porPD = array();
        foreach($products as $porP) {
            $porPrice = number_format($recalcP->calcExchangeRate(tep_add_tax($porP['final_price'],tep_get_tax_rate($porP['tax_class_id']))*$porP['quantity']),2,'.','');

            $porPD[] = '8995' . '::' . $porPrice . '::' . string_gco_prepare($porP['name']) . '::' . $porP['id'];
            
            $porPTotal +=$porPrice;
        }
        
        $wgProducts = rawurlencode(implode('|', $porPD));
    } 
    return array(                                                                                                                                                              
                'url' => 'https://track.webgains.com/transaction.html?wgver=1.2&amp;wgrs=1&amp;wgsubdomain=track&amp;wglang='.$wgLang.'&amp;wgeventid='.$wgEventID.'&amp;wgprogramid='.$wgProgramID.'&amp;wgvalue='.$porPTotal.'&amp;wgitems='.$wgProducts,
                'params' => array(
                                   'wgorderreference' => 'order-id',
                                  )
                ); 
 }   
 

 function show_this() {
    global $affiliate;
    
    $affiliate_gc = array('webgains');

/*    if (!empty($affiliate)) {
        $affiliate_gc[] = $affiliate;
    }else{
        //
      $affiliate_gc[] = 'eperfect';
    }*/
      return $affiliate_gc;
 }
}
?>