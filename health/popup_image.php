<?php
/*
  $Id: popup_image.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $products_query = tep_db_query("select if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_image_lrg, p.products_image_xl_1, p.products_image_xl_2, p.products_image_xl_3, p.products_image_xl_4, p.products_image_xl_5, p.products_image_xl_6, products_image_alt_1, products_image_alt_2, products_image_alt_3, products_image_alt_4, products_image_alt_5, products_image_alt_6 from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$languages_id . "' left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_status = 1 and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and pd.products_id=p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id=0");
  }else{
    $products_query = tep_db_query("select if(length(pd1.products_name), pd1.products_name, pd.products_name) as products_name, p.products_image, p.products_image_lrg, p.products_image_xl_1, p.products_image_xl_2, p.products_image_xl_3, p.products_image_xl_4, p.products_image_xl_5, p.products_image_xl_6, products_image_alt_1, products_image_alt_2, products_image_alt_3, products_image_alt_4, products_image_alt_5, products_image_alt_6 from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd1 on pd1.products_id = p.products_id and pd1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "' and pd1.language_id = '" . (int)$languages_id . "' where p.products_status = 1 and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and pd.products_id=p.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id=0");  
  }
  $products = tep_db_fetch_array($products_query);
  
  if (!is_array($products) == true) {
  	header('HTTP/1.0 404 Not Found');
	$fp = @fopen( 'http://www.healthcare4all.co.uk/information.php?info_id=16', 'r' ) or die( 'Heh.' ); 
		while ( $line = @fgets( $fp, 1024 ) ) { 
			print $line; 
		} 
	fclose( $fp );	  	
	die;
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $products['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php
$style_sheet = get_affiliate_stylesheet();

if ($style_sheet == ''){
  $style_sheet = TEMPLATE_STYLE;
}
?>
<link rel="stylesheet" type="text/css" href="<? echo $style_sheet;?>">

<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60);
  self.focus();
}
//--></script>
</head>
<body onLoad="resize();">
<a href="javascript:;" onclick="javascript:top.window.close();"> 
<?php
  if (($HTTP_GET_VARS['image'] == 0) && ($products['products_image_lrg'] != '')) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_lrg'], $products['products_name'], 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 1) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_1'], (tep_not_null($products['products_image_alt_1'])?$products['products_image_alt_1']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 2) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_2'], (tep_not_null($products['products_image_alt_2'])?$products['products_image_alt_2']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 3) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_3'], (tep_not_null($products['products_image_alt_3'])?$products['products_image_alt_3']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 4) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_4'], (tep_not_null($products['products_image_alt_4'])?$products['products_image_alt_4']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 5) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_5'], (tep_not_null($products['products_image_alt_5'])?$products['products_image_alt_5']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } elseif ($HTTP_GET_VARS['image'] == 6) {
    echo tep_image(DIR_WS_IMAGES . $products['products_image_xl_6'], (tep_not_null($products['products_image_alt_6'])?$products['products_image_alt_6']:$products['products_name']), 0, 0, 'vspace="8" hspace="8"');
  } else {
    echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], 0, 0, 'vspace="5" hspace="5"');
  }
?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
