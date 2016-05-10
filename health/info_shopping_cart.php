<?php
/*
  $Id: info_shopping_cart.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<?php
// BOF: WebMakers.com Changed: Header Tag Controller v1.0
// Replaced by header_tags.php
if (SEARCH_ENGINE_UNHIDE == 'True' &&  file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?> 
  <title><?php echo TITLE ?></title>
<?php
}
?>
<?php
$style_sheet = get_affiliate_stylesheet();

if ($style_sheet == ''){
  $style_sheet = TEMPLATE_STYLE;
}
//$style_sheet = TEMPLATE_STYLE;
?>
<link rel="stylesheet" type="text/css" href="<? echo $style_sheet;?>">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<div style="background: #ffffff; border: 1px solid red;margin:5px;padding:5px;">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_TITLE);

  new contentBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<b><i>' . SUB_HEADING_TITLE_1 . '</i></b>');
  $info_box_contents[] = array('text' => SUB_HEADING_TEXT_1);
  $info_box_contents[] = array('text' => '<b><i>' . SUB_HEADING_TITLE_2 . '</i></b>');
  $info_box_contents[] = array('text' => SUB_HEADING_TEXT_2);
  $info_box_contents[] = array('text' => '<b><i>' . SUB_HEADING_TITLE_3 . '</i></b>');
  $info_box_contents[] = array('text' => SUB_HEADING_TEXT_3);  
  new contentBox($info_box_contents);
 

?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>
</div>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
