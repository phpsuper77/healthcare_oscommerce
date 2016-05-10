<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
		<div id="secondary" class="widget-area sidebar" role="complementary">
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			<br />
			<br />
			<!-- Start: OsCommerce Random Products -->
			<? 
			chdir("../");
			include ("includes/configure.php");
			include ("includes/filenames.php");
			include ("includes/functions/database.php");
			include ("includes/database_tables.php");
			include ("includes/functions/general.php");
			include ("includes/functions/html_output.php"); 
			tep_db_connect() or die('Unable to connect to database server!');
			
			define(SEO_URL_PARTS_LANGUAGE, True);
			define(SEARCH_ENGINE_FRIENDLY_URLS, true);
			define(SEARCH_ENGINE_UNHIDE, true);
			define(SEO_URL_PARTS_CATEGORIES, 'Full Categories Path');
			define(SEO_URL_ENCODING_METHOD, 'Standard URL Encode (%XX)');
			define(SEO_URL_PARTS_ID, "False");
			$search_engine_safe = true;
			global $languages_id;
			$languages_id = 1;			
			?>
			<?
				$sql = "SELECT
							products.products_id, 
							products.products_image,
							products.products_price,
							products_description.products_name,
							products_description.products_description_short
						FROM products 
						INNER JOIN products_description ON products.products_id = products_description.products_id
						WHERE products_status = 1
						ORDER BY RAND()
						LIMIT 2
						";
				$randomOsCommerceProducts = $wpdb->get_results($sql, OBJECT);
			?>
			
			<? foreach($randomOsCommerceProducts as $key=>$product): ?>
				<?
					$product_link = tep_href_link(FILENAME_PRODUCT_INFO, ($view_button?'':($cPath ? 'cPath=' . $cPath . '&' : '')) . 'products_id=' . $product->products_id);
					$product_link = str_replace('.co.uk//', '.co.uk/', $product_link);					
				?>
				<div class="product">
					<div class="image">
						<img src="/images/<?=$product->products_image;?>" width="50" />
					</div>
					<div class="info">
						<a href="<?=$product_link?>">
							<?=$product->products_name;?>
						</a><br />
						<? if (strlen($product->products_description_short) > 36) print substr($product->products_description_short,0,36)."...";?><br />
						<span class="price">
							&pound;<?=number_format($product->products_price, 2, '.', '');?>
						</span>
					</div>
					<div class="clear"></div>
				</div>					
			<? endforeach; ?>
			<!-- End: OsCommerce Random Products -->
			
			<!-- Feedburner -->
			<div class="feedburner right-box">
				<span class="title">Subscribe</span><br />
				<form style="border:0px solid #ccc;padding:3px;text-align:center;" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=Healthcare4allBlog', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
					<strong>Enter your email address for the latest offers and news:</strong><br />
					<input type="text" style="width:140px" name="email"/>
					<input type="hidden" value="Healthcare4allBlog" name="uri"/>
					<input type="hidden" name="loc" value="en_US"/>
					<input type="submit" value="Subscribe" />
				</form>			
			</div>			
			<!-- / FeedBurner -->
			
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>