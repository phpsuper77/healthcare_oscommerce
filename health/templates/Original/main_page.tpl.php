<!DOCTYPE html>
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

<? if ($product_canonical != "" && !isset($controller->canonical_tag) ) : ?>
	<link rel="canonical" href="<?=$product_canonical;?>"/>
<? endif; ?>

<? if (isset($controller->canonical_tag)) : ?>
	<link rel="canonical" href="<?=$controller->canonical_tag;?>"/>
<? endif; ?>


<?php
$style_sheet = get_affiliate_stylesheet();

if ($style_sheet == '') {
  $style_sheet = TEMPLATE_STYLE;
}
?>
<LINK REL="Shortcut Icon" HREF="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . DIR_WS_ICONS . 'favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<? echo $style_sheet;?>">

<!-- Musaffar -- Added jQuery -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php 
  require(DIR_WS_JAVASCRIPT . "general.js.php");
?>
<?php if ($javascript) { require(DIR_WS_JAVASCRIPT . $javascript); } ?>
<?php if ($content == CONTENT_PRODUCT_INFO && PRODUCTS_IMAGES_LIGHTBOX == 'True') { ?>
<link rel="stylesheet" href="<?php echo DIR_WS_CATALOG; ?>lightbox/lightbox.css" type="text/css" media="screen" />
<!--
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/prototype.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="<?php echo DIR_WS_CATALOG; ?>lightbox/lightbox.js"></script>-->
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

    <!-- header //-->
<?php
  if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_HEADER_OFF =='false') {
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/header.php');
  }
?>
    <!-- header_eof //-->
<div id="pageContent">
	
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
	  
		<!-- Place this tag where you want the +1 button to render. -->
		<div class="plusone">
			<div class="g-plusone" data-href="http://www.healthcare4all.co.uk"></div>
			
			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/platform.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>	  
		</div>
	  
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
        <td style="padding: 10px 0; text-align: center">
			<?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/payicons.gif','')?><br>
			<?php require(DIR_WS_INCLUDES . 'blogfeed.php'); ?>
		</td>
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
	<div class="page-width">
		  <!-- footer //-->
		<?php
		if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_FOOTER_OFF =='false') {
		  require(DIR_WS_TEMPLATES . TEMPLATE_NAME .'/footer.php');
		}
		?>		  
	</div>
</div>

<?php
// google analitycs module
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1654052-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php
if(basename($PHP_SELF) == FILENAME_CHECKOUT_SUCCESS)
{
  // Get order id
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
  $orders = tep_db_fetch_array($orders_query);
  $order_id = $orders['orders_id'];

// Get order info for Analytics "Transaction line" (affiliation, city, state, country, total, tax and shipping)

// Set value for  "affiliation"

  $analytics_affiliation = '';

// Get info for "city", "state", "country"
  $orders_query = tep_db_query("select customers_city, customers_state, customers_country from " . TABLE_ORDERS . " where orders_id = '" . $order_id . "' AND customers_id = '" . (int)$customer_id . "'");
  $orders = tep_db_fetch_array($orders_query);

  $totals_query = tep_db_query("select value, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
// Set values for "total", "tax" and "shipping"
  $analytics_total = '';
  $analytics_tax = '';
  $analytics_shipping = '';

  while ($totals = tep_db_fetch_array($totals_query))
  {
    if ($totals['class'] == 'ot_total')
    {
      $analytics_total = number_format($totals['value'], 2, ".", "");
      $total_flag = 'true';
    }
    else if ($totals['class'] == 'ot_tax')
    {
      $analytics_tax = number_format($totals['value'], 2, ".", "");
      $tax_flag = 'true';
    }
    else if ($totals['class'] == 'ot_shipping')
    {
      $analytics_shipping = number_format($totals['value'], 2, ".", "");
      $shipping_flag = 'true';
    }
  }

  ?>
<script type="text/javascript">
  _gaq.push(['_addTrans', "<?=$order_id?>", "<?=$analytics_affiliation?>", "<?=$analytics_total?>", "<?=$analytics_tax?>", "<?=$analytics_shipping?>", "<?=$orders['customers_city']?>", "<?=$orders['customers_state']?>", "<?=$orders['customers_country']?>"]);
 <?
  $items_query = tep_db_query("select products_id, products_model, products_name, final_price, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $order_id . "' order by products_name");
  while ($items = tep_db_fetch_array($items_query))
  {
    $category_query = tep_db_query("select p2c.categories_id, cd.categories_name from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p2c.products_id = '" . $items['products_id'] . "' AND cd.categories_id = p2c.categories_id AND cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);
  ?>
  _gaq.push(['_addItem', "<?=$order_id?>","<?=$items['products_id']?>","<?=$items['products_name']?>","<?=$category['categories_name']?>","<?=number_format($items['final_price'], 2, ".", "")?>","<?=$items['products_quantity']?>"]);
  <?
  }
  ?>
  _gaq.push(['_trackTrans']);
</script>
<?php
}
// End for google analitycs module
?>

</body>
</html>
<? if($show_direct_link==true)die; ?>