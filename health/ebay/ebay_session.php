<?php

  class oscEbatNs_Session extends EbatNs_Session{

    function EbatNs_Session(){
      parent::EbatNs_Session();
    }

    function Configure(){
      $current_mode = ebay_config::getEbayMode();
      switch ($current_mode) {
        case 0: // production
         $this->_keys['prod'] = array( ebay_config::getAppID(), ebay_config::getDevID(), ebay_config::getCertID() );
         $this->setAppMode($current_mode);
        break;
        case 1: // sandbox
         $this->_keys['test'] = array( ebay_config::getAppID(), ebay_config::getDevID(), ebay_config::getCertID() );
         $this->setAppMode($current_mode);
        break;
      default:
      	die( "UNK Session MODE [$current_mode]" );
      	break;
      }
      $this->setSiteId( ebay_config::getEbaySiteID() );
      
      $this->setCompatibilityLevel( ebay_config::getCompatibilityLevel() ); //?
      $this->setTokenMode(1);
    }
    
    function ReadTokenFile(){
      $this->_props['RequestToken'] = ebay_config::getToken();
    }
    
    function WriteTokenFile() {
    }
    
  }

?>