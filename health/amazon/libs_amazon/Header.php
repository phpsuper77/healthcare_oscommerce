<?php
/*
Copyright (c) 2005-2009 Holbi

http://www.holbi.co.uk

Author: Alexandr Tkach
e-mail: info@holbi.co.uk

*/

class AmazonHeader {
  function toXML(){
    return 
      '<Header>'.
		    '<DocumentVersion>'.axsd::StringNotNull(AmazonConfig::getDocVersion()).'</DocumentVersion>'.
		    '<MerchantIdentifier>'.axsd::StringNotNull(AmazonConfig::getMerchantId()).'</MerchantIdentifier>'.
	    '</Header>'."\n";
  }
}

?>
