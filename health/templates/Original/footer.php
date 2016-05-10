<?php
/*
  $Id: footer.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/



// WebMakers.com Added: Down for Maintenance
// Hide footer.php if not to show
?>
<!-- footer -->
<div class="footer-seo-text">
	Healthcare4all are medical equipment suppliers in the UK, specialising in respiratory products such as nebulizer (nebuliser) machines and pulse oximeters, for conditions such as COPD and asthma. We also have a wide range of dental supplies such as irrigators and
	brushes, and equipment to help diabetics monitor their condition.
</div>

<table border="0" cellspacing="0" cellpadding="0" class="footer" align="center">
  <tr>
    <td><?php echo FOOTER_TEXT_BODY; ?></td>
    <td>

<?php
  $sql=tep_db_query('SELECT i.information_id, i.languages_id, if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, i.page, i.page_type FROM ' . TABLE_INFORMATION .' i left join ' . TABLE_INFORMATION . ' i1 on i.information_id = i1.information_id and i1.languages_id = '. $languages_id . ' and i1.affiliate_id = ' . (int)$HTTP_SESSION_VARS['affiliate_ref'] . ' WHERE i.visible=\'1\' and i.languages_id ='.$languages_id.' and FIND_IN_SET(\'footer\', i.scope) and i.affiliate_id = 0 ORDER BY i.v_order');

  $counter = 0;
  while($row=tep_db_fetch_array($sql)){
    if ($counter > 3) {
      echo '</td><td>';
      $counter=0;
    }
    $title_link = tep_not_null($row['page_title'])?$row['page_title']:$row['info_title'];
    if ($row['page'] == ''){
      echo '<a' . ($counter==0?' class="fnFirst"':'') . ' href="' . tep_href_link(FILENAME_INFORMATION, 'info_id=' . $row['information_id']) . '" title="'. tep_output_string($title_link) .'">'. $row['info_title']  .'</a><br>';
    }else{
      echo '<a' . ($counter==0?' class="fnFirst"':'') . ' href="' . tep_href_link($row['page'], '', $row['page_type']) . '" title="'. tep_output_string($title_link) .'">' . $row['info_title'] . '</a><br>';
    }
    $counter++ ;
  }
?>
		<a href="http://www.healthcare4all.co.uk/blog" title="Blog">Blog</a>

    </td>
    <td width="200"><?php echo TEXT_FOOTER_CONTACT; ?></td>
  </tr>
</table>
<!-- footer -->

<?php  
if (DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false' && false) {
  require(DIR_WS_INCLUDES . 'counter.php');
?>

<table cellspacing=0 cellpadding=0 width="100%" border=0 class="footer">
  <tr>
    <td width="28"><?php echo tep_draw_separator('spacer.gif', '28', '95');?></td>

    <td align="center" class="smallText">
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr class="footer">
    <td class="footer">&nbsp;&nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?>&nbsp;&nbsp;</td>
    <td align="right" class="footer">&nbsp;&nbsp;<?php echo $counter_now . ' ' . FOOTER_TEXT_REQUESTS_SINCE . ' ' . $counter_startdate_formatted; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

<?php 
}

if(tep_session_is_registered('last_order_products') && basename($_SERVER['SCRIPT_NAME']) == CONTENT_CHECKOUT_SUCCESS.'.php')
{
  foreach($last_order_products as $product)
  {
    $product_price = tep_add_tax($product['final_price'], $product['tax']) * $product['qty'];
    $porPD[] = '8995' . '::' . $product_price . '::' . $product['name'] . '::' .  $product['id'];
    $wgOrderValue += $product_price;
  }
//  $wgOrderValue = $last_order_total;

  //$wgOrderValue = '1';# total order value in the currency your program runs in (please do not include currency symbol)
  $wgOrderReference = rawurlencode($HTTP_GET_VARS['order_id']);
  $wgEventID=8995; # this identify's the commission type (in account under Program Setup (commission types))
  $wgComment= ''; #optional field
  $wgMultiple=1;
  $wgItems= implode('|',$porPD);
  $wgItems=rawurlencode($wgItems);
  $wgCustomerID= '';# please do not use without contacting us first
  $wgProductID= '';# please do not use without contacting us first
  $wgSLang = 'php';# string, used to identify the programming language of your online systems. Needed because url_encoding differs between platforms.
  $wgLang = 'en_GB';# string, used to identify the human language of the transaction
  $wgPin = 7958;# pin number provided by webgains (in account under Program Setup (program settings -> technical setup))
  $wgProgramID = 5743; # int, used to identify you to webgains systems
  $wgVoucherCode = rawurlencode(''); #string, used to store the voucher code used for transaction
  $wgVersion = '1.2';
  $wgSubDomain="track";
  $wgCheckString ="wgver=$wgVersion&wgsubdomain=$wgSubDomain&wglang=$wgLang&wgslang=$wgSLang&wgprogramid=$wgProgramID&wgeventid=$wgEventID&wgvalue=$wgOrderValue&wgorderreference=$wgOrderReference&wgcomment=$wgComment&wgmultiple=$wgMultiple&wgitems=$wgItems&wgcustomerid=$wgCustomerID&wgproductid=$wgProductID&wgvouchercode=$wgVoucherCode";
  $wgCheckSum=md5($wgPin.$wgCheckString); # make checksum
  $wgQueryString = $wgCheckString."&wgchecksum=$wgCheckSum";
  $wgUri = '://'.$wgSubDomain.".webgains.com/transaction.html?".$wgQueryString;
  ?>
<script language="javascript" type="text/javascript">
if(location.protocol.toLowerCase() == "https:") wgProtocol="https";
else wgProtocol="http";
wgUri = wgProtocol + "<?php echo($wgUri);?>" + "&wgprotocol=" + wgProtocol + "&wglocation=" + location.href;
document.write('<sc'+'ript language="JavaScript"  type="text/javascript" src="'+wgUri+'"></sc'+'ript>');
</script>

<noscript>
  <img src="https://<?php echo($wgSubDomain);?>.webgains.com/transaction.html?wgver=<?php echo($wgVersion);?>&wgrs=1&wgsubdomain=<?php echo($wgSubDomain);?>&wglang=<?php echo($wgLang);?>&wgslang=<?php echo($wgSLang);?>&wgprogramid=<?php echo($wgProgramID);?>&wgeventid=<?php echo($wgEventID);?>&wgvalue=<?php echo($wgOrderValue);?>&wgorderreference=<?php echo($wgOrderReference);?>&wgcomment=<?php echo($wgComment);?>&wgmultiple=<?php echo($wgMultiple);?>&wgitems=<?php echo($wgItems);?>&wgcustomerid=<?php echo($wgCustomerID);?>&wgproductid=<?php echo($wgProductID);?>&wgvouchercode=<?php echo $wgVoucherCode; ?>&wgchecksum=<?php echo($wgCheckSum);?>&wgprotocol=https" alt="" width="1" height="1"/>
</noscript>
  <?php
  tep_session_unregister('last_order_products');
  tep_session_unregister('last_order_total');
}

?>

<div class="social-icons relative">
	<img src="<?=DIR_WS_TEMPLATE_IMAGES;?>site/footer-social.png" class="absolute" />
	<a href="http://www.facebook.com/Healthcare4all" target="_blank" class="facebook absolute">facebook</a>
	<a href="https://twitter.com/healthcare4" target="_blank" class="twitter absolute">twitter</a>
</div>



<!-- footer_eof //-->
