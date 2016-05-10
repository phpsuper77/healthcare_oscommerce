<?php
// STD Button file

include_once(dirname(__FILE__).'/gcheckout_xml.php');
?>
<div align="right">
<?php 
  echo '<div style="width: 180px; text-align: center;"><b>' . MODULE_PAYMENT_GOOGLECHECKOUT_TEXT_OPTION . '</b></div>';
?>
</div>
<div align="right">
    <?php
    echo $Gcart->CheckoutButtonCode('large');
    ?>
    <?php
      foreach($Gwarnings as $Gwarning) {
        echo '<div style="font-size:11px; color: red; width: 180px; text-align: center;"> * ' . $Gwarning . '</div>';
      }
      if($shipping_config_errors != ''){
        echo '<div style="font-size:11px; color: red; width: 180px; text-align: center;"><b>' . GOOGLECHECKOUT_STRING_ERR_SHIPPING_CONFIG . '</b><br />';
        echo $shipping_config_errors;
        echo '</div>';
      }
    ?>
</div>
<?php
//echo $Gcart->CheckoutHTMLButtonCode();
//echo '<pre>'; var_dump($_SESSION); echo '</pre>';
//echo "<xmp>".$Gcart->GetXML()."</xmp>";
?>
<!-- ** END GOOGLE CHECKOUT ** -->