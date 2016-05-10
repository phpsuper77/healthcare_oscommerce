<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->
	

	<footer id="colophon" role="contentinfo">

			<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>

			<div id="site-generator">
			</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="footer relative">
	<div class="social-icons absolute">
		<img src="/blog/wp-content/themes/healthcare/images/site/footer-social.png" class="absolute" />
		<a href="http://www.facebook.com/Healthcare4all" target="_blank" class="facebook absolute">facebook</a>
		<a href="https://twitter.com/healthcare4" target="_blank" class="twitter absolute">twitter</a>
	</div>
</div>


<?php wp_footer(); ?>
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
</body>
</html>