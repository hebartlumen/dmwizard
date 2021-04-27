<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Inkness
 */
?>
	</div>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer row" role="contentinfo">
	<div class="container">
	<?php /*if ( of_get_option('credit1', true) == 0 ) { ?>
		<div class="site-info col-md-4">
			<?php do_action( 'inkness_credits' ); ?>
			<?php printf( __( 'Inkness Theme by %1$s.', 'inkness' ), '<a href="http://inkhive.com/" rel="designer">InkHive</a>' ); ?>
		</div><!-- .site-info -->
	<?php }*/ ?>	
		<div id="footertext" class="col-md-4 col-md-offset-4">
        	<?php
			if ( (function_exists( 'of_get_option' ) && (of_get_option('footertext2', true) != 1) ) ) {
			 	echo of_get_option('footertext2', true); } ?>
        </div>
	</div>   
	</footer><!-- #colophon -->
	
</div><!-- #page -->

<?php wp_footer(); ?>


<script type="text/javascript">
jQuery(document).ready(function($){
	$("#enquire-now").click(function(){
	$("#enquire-form").hasClass("open-form")?
	$("#enquire-form").removeClass("open-form"):
		$("#enquire-form").addClass("open-form")})
		
	$("#form-close").click(function(){
		$("#enquire-form").hasClass("open-form")&&
		$("#enquire-form").removeClass("open-form")
	});
	});
</script>
    
</body>
</html>