<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php
if (SEARCH_ENGINE_UNHIDE == 'True' && file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {
  require(DIR_WS_INCLUDES . 'header_tags.php');
} else {
?> 
  <title><?php echo TITLE; ?></title>
<?php
}
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="<? echo TEMPLATE_STYLE;?>">
<?php if ($javascript) {require(DIR_WS_JAVASCRIPT . $javascript);} ?>
</head>
<body>
<?php require(DIR_WS_CONTENT . $content . '.tpl.php'); ?>
</body>
</html>

