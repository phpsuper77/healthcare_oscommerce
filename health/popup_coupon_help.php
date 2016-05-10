<?php
/*
  $Id: popup_coupon_help.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_COUPON_HELP);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="<? echo TEMPLATE_STYLE;?>">
</head>
<style type="text/css"><!--
BODY { margin-bottom: 10px; margin-left: 10px; margin-right: 10px; margin-top: 10px; }
//--></style>
<body>
<div style="padding:7px;position:relative;background-color:#FFFFFF;width:95%;">

<?php
  $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
  $coupon = tep_db_fetch_array($coupon_query);
  $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$HTTP_GET_VARS['cID'] . "' and language_id = '" . (int)$languages_id . "'");
  $coupon_desc = tep_db_fetch_array($coupon_desc_query);
  $text_coupon_help = TEXT_COUPON_HELP_HEADER;
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_NAME, $coupon_desc['coupon_name']);
  if (tep_not_null($coupon_desc['coupon_description'])) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DESC, $coupon_desc['coupon_description']);
  $coupon_amount = $coupon['coupon_amount'];
  switch ($coupon['coupon_type']) {
    case 'F':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, $currencies->format($coupon['coupon_amount']));
    break;
    case 'P':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, number_format($coupon['coupon_amount'],2). '%');
    break;
    case 'S':
    $text_coupon_help .= TEXT_COUPON_HELP_FREESHIP;
    break;
    default:
  }
  if ($coupon['coupon_minimum_order'] > 0 ) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_MINORDER, $currencies->format($coupon['coupon_minimum_order']));
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DATE, tep_date_short($coupon['coupon_start_date']),tep_date_short($coupon['coupon_expire_date']));
  $text_coupon_help .= '<b>' . TEXT_COUPON_HELP_RESTRICT . '</b>';
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_CATEGORIES;
  $coupon_get=tep_db_query("select restrict_to_categories, restrict_to_products from " . TABLE_COUPONS . " where coupon_id='".(int)$HTTP_GET_VARS['cID']."'");
  $get_result=tep_db_fetch_array($coupon_get);

  $cat_ids = split("[,]", $get_result['restrict_to_categories']);
  for ($i = 0; $i < count($cat_ids); $i++) {
    $cats .= '<br>' . tep_get_categories_name( $cat_ids[$i] );
  }
  if ($cats=='') $cats = '<br>NONE';
  $text_coupon_help .= $cats;
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_PRODUCTS;
  //$coupon_get=tep_db_query("select restrict_to_products from " . TABLE_COUPONS . "  where coupon_id='".$HTTP_GET_VARS['cID']."'");
  //$get_result=tep_db_fetch_array($coupon_get);

  $pr_ids = split("[,]", $get_result['restrict_to_products']);
  for ($i = 0; $i < count($pr_ids); $i++) {
    $prods .= '<br>' . tep_get_products_name( $pr_ids[$i] );
  }
  if ($prods=='') $prods = '<br>NONE';
  $text_coupon_help .= $prods;


  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_COUPON_HELP);


  new contentBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $text_coupon_help);

  new contentBox($info_box_contents);
?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>
</div>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
