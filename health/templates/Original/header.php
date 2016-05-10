<?php
/*
  $Id: header.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// WebMakers.com Added: Down for Maintenance
// Hide header if not to show
if (DOWN_FOR_MAINTENANCE_HEADER_OFF =='false') {

?>
<!-- header -->

<div id="header">	
	<div id="top-strapline">
		<div class="page-width">
			<h1>Medical Equipment, Oximeters, ECG Monitors, Nebulisers and more</h1>
			<div class="flags">
				<img src="/templates/images/site/header-flag-UK.png" alt="Worldwide Delivery" />
				<img src="/templates/images/site/header-flag-USA.png" alt="Worldwide Delivery" />
				<span>Worldwide Delivery</span>
				<img src="/templates/images/site/header-flag-AU.png" alt="Worldwide Delivery" />
				<img src="/templates/images/site/header-flag-EUR.png" alt="Worldwide Delivery" />
			</div>
			
			<div class="call-us">
				Call Us:
				<span class="telephone swatch-blue">0113 350 5432</span>
			</div>			
		</div>
	</div>
	
	<div id="header-content">
		<div class="page-width">
			<a href="/" id="logo"><img src="/templates/images/site/logo.png" alt="Healthcare4All" /></a>
		</div>
		
		<div id="search">
			<? require_once(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/search_header.php'); ?>
		</div>
		<a id="login-button" href="/login.php">Login</a>
	</div>
	
</div>

<div id="header-nav">	
	<div class="page-width">
		<?php
			$header_query = tep_db_query('SELECT i.information_id, i.languages_id, if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, i.page, i.page_type FROM ' . TABLE_INFORMATION .' i left join ' . TABLE_INFORMATION . ' i1 on i.information_id = i1.information_id and i1.languages_id = '. $languages_id . ' and i1.affiliate_id = ' . (int)$HTTP_SESSION_VARS['affiliate_ref'] . ' WHERE i.visible=\'1\' and i.languages_id ='.$languages_id.' and FIND_IN_SET(\'header\', i.scope) and i.affiliate_id = 0 ORDER BY i.v_order');
			$col=0;
			while($header_info = tep_db_fetch_array($header_query)){
				$title_link = tep_not_null($header_info['page_title'])?$header_info['page_title']:$header_info['info_title'];
				if ($col!=0) echo '';
					if ($header_info['page'] == ''){
						echo '<a' . ($col==0?' class=" hnFirst"':'') . ' href="' . tep_href_link(FILENAME_INFORMATION, 'info_id=' . $header_info['information_id']) . '" title="'. tep_output_string($title_link) .'"><span>'. $header_info['info_title']  .'</span></a>';
					} else {
						echo '<a' . ($col==0?' class=" hnFirst"':'') . ' href="' . tep_href_link($header_info['page'], '', $header_info['page_type']) . '" title="'. tep_output_string($title_link) .'"><span>' . $header_info['info_title'] . '</span></a>';
					}
					$col++;				
				}
		?>	

		<? if ($banner = tep_banner_exists('dynamic', 'chat')) : ?>
			<div class="chat">
				<?=tep_display_banner('static', $banner);?>
			</div>
		<? endif; ?>
		
	</div>
</div>
<!-- header_eof //-->
<?php
}
?>
