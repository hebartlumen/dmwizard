<?php
/**
 * @package WPSEO\Admin\Views
 */

if ( ! defined( 'WPSEO_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( WPSEO_Utils::is_api_available() ) :
	echo '<h2>' . esc_html__( 'Configuration wizard', 'wordpress-seo' ) . '</h2>';
	?>
	<p>
		<?php
			/* translators: %1$s expands to Yoast SEO */
			printf( esc_html__( 'Need help determining your settings? Configure %1$s step-by-step.', 'wordpress-seo' ), 'Yoast SEO' );
		?>
	</p>
<p>
	<a class="button"
	   href="<?php echo esc_url( admin_url( 'admin.php?page=' . WPSEO_Configuration_Page::PAGE_IDENTIFIER ) ); ?>"><?php _e( 'Open the configuration wizard', 'wordpress-seo' ); ?></a>
</p>

	<br/>
<?php
endif;

/**
 * Action: 'wpseo_internal_linking' - Hook to add the internal linking analyze interface to the interface.
 */
do_action( 'wpseo_internal_linking' );

echo '<h2>' . esc_html__( 'Credits', 'wordpress-seo' ) . '</h2>';
?>
<p>
	<?php
		/* translators: %1$s expands to Yoast SEO */
		printf( esc_html__( 'Take a look at the people that create %1$s.', 'wordpress-seo' ), 'Yoast SEO' );
	?>
</p>

<p>
	<a class="button"
	   href="<?php echo esc_url( admin_url( 'admin.php?page=' . WPSEO_Admin::PAGE_IDENTIFIER . '&intro=1' ) ); ?>"><?php _e( 'View Credits', 'wordpress-seo' ); ?></a>
</p>
<br/>
<?php
echo '<h2>' . esc_html__( 'Restore default settings', 'wordpress-seo' ) . '</h2>';
?>
<p>
	<?php
	/* translators: %s expands to Yoast SEO. */
	printf( esc_html__( 'If you want to restore a site to the default %s settings, press this button.', 'wordpress-seo' ), 'Yoast SEO' );
	?>
</p>

<p>
	<a onclick='if ( !confirm( <?php echo esc_attr( wp_json_encode( __( 'Are you sure you want to reset your SEO settings?', 'wordpress-seo' ) ) ); ?> ) ) return false;'
	   class="button"
	   href="<?php echo esc_url( add_query_arg( array( 'nonce' => wp_create_nonce( 'wpseo_reset_defaults' ) ), admin_url( 'admin.php?page=' . WPSEO_Admin::PAGE_IDENTIFIER . '&wpseo_reset_defaults=1' ) ) ); ?>"><?php _e( 'Restore Default Settings', 'wordpress-seo' ); ?></a>
</p>
