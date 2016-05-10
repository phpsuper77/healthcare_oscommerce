<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
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
<?php
$style_sheet = get_affiliate_stylesheet();

if ($style_sheet == '') {
  $style_sheet = TEMPLATE_STYLE;
}
?>
<LINK REL="Shortcut Icon" HREF="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_ICONS . 'favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<? echo $style_sheet;?>">
<?php 
  require(DIR_WS_JAVASCRIPT . "general.js.php");
?>
<?php if ($javascript) { require(DIR_WS_JAVASCRIPT . $javascript); } ?>
<?php if ($content == CONTENT_PRODUCT_INFO && PRODUCTS_IMAGES_LIGHTBOX == 'True') { ?>
<link rel="stylesheet" href="<?php echo DIR_WS_CATALOG; ?>lightbox/lightbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/prototype.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/lightbox.js"></script>
<?php } ?>
<style type="text/css">
#pageContent{
  width: <?=SITE_WIDTH!='100%'?SITE_WIDTH . 'px':'100%'?>;
  min-width:770px;
  /* *width:expression(document.body.clientWidth < 770? "770px": "<?=SITE_WIDTH!='100%'?SITE_WIDTH . 'px':'100%'?>" );*/
}
#left-container{
  margin-right:-<?=BOX_WIDTH_RIGHT?>px;
*  margin-right: <?=BOX_WIDTH_RIGHT?>px;
}
#left-content {
  margin-right: <?=BOX_WIDTH_RIGHT?>px;
*  margin-right: 0px;
}
#container{
  margin-left:-<?=BOX_WIDTH_LEFT?>px;
*  margin-left: <?=BOX_WIDTH_LEFT?>px;
}
#content {
  margin-left: <?=BOX_WIDTH_LEFT?>px;
*  margin-left: 0px;
*  margin-top:0px;
}
#left {
width: <?=BOX_WIDTH_LEFT?>px;
margin-right:-<?=BOX_WIDTH_LEFT?>px;
}
#right {
  width: <?=BOX_WIDTH_RIGHT?>px;
*  margin-left:-<?=BOX_WIDTH_RIGHT?>px;
}
#footer {
  width: <?=SITE_WIDTH!='100%'?SITE_WIDTH . 'px':'100%'?>;
}
</style>
<!--[if lt IE 7]>
<style type="text/css">
input.transpng {behavior: url("png.htc");} 
img.transpng {behavior: url("png.htc");}
</style>
<![endif]-->
</head>
<body>
<!-- warnings //-->

<?php require(DIR_WS_INCLUDES . 'warnings.php'); ?>
<!-- warning_eof //-->

<div id="pageContent">
  <div id="header">
    <!-- header //-->
<?php
  if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_HEADER_OFF =='false') {
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/header.php');
  }
?>
    <!-- header_eof //-->
  </div>
  <div id="box-wrap">
    <div id="box-inner-wrap">
      <div id="left-container">
        <div id="left-content">
          <div id="container">
            <div id="content">
              <!-- content //-->
<div class="center_bg">
<?php
if ($messageStack->size('download')){
  echo $messageStack->output('download');
}
  if ($content_template!==false) {
    require(DIR_WS_CONTENT . $content_template);
  } else {
    if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/content/' . $content . '.tpl.php')) {
      require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/content/' . $content . '.tpl.php');
    } else {
      require(DIR_WS_CONTENT . $content . '.tpl.php');
    }
  }
?>
</div>
              <!-- content_eof //-->
            </div>
          </div>
          <div id="left">
            <!-- left_navigation //-->
<?php
if (DISPLAY_COLUMN_LEFT == 'yes' && $content != 'catalog_checkout')  {
  // WebMakers.com Added: Down for Maintenance
  // Hide column_left.php if not to show
  if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false') {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="left_bg">
      <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_LEFT; ?>">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </table>
    </td>
  </tr>
</table>
<?php
  }
}
?>
            <!-- left_navigation_eof //-->
          </div>
        </div>
      </div>
      <div id="right">
        <!-- right_navigation //-->
<?php
// WebMakers.com Added: Down for Maintenance
// Hide column_right.php if not to show

if (DISPLAY_COLUMN_RIGHT == 'yes' && $content != 'catalog_checkout')  {
  if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF =='false') {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="right_bg">
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_RIGHT; ?>">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
      <tr>
        <td style="padding: 10px 0; text-align: center"><?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/payicons.gif','')?></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
<?php
  }
}
?>
        <!-- right_navigation_eof //-->
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="footer">
  <!-- footer //-->
<?php
if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/footer.php');
}
?>
  <!-- footer_eof //-->
</div><script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1654052-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>
<? if($show_direct_link==true)die; ?>