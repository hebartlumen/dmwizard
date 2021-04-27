<?php
/**
 * @package WPSEO\Admin
 */

/** @noinspection PhpUnusedLocalVariableInspection */
$alerts_data = Yoast_Alerts::get_template_variables();


/* translators: %1$s expands to Yoast SEO. */
$wpseo_dashboard_header = sprintf( esc_html__( '%1$s Dashboard', 'wordpress-seo' ), 'Yoast SEO' );

?>
<div class="wrap yoast-alerts">

	<h2><?php echo esc_html( $wpseo_dashboard_header ); ?></h2>
	<div class="yoast-container yoast-container__alert">
		<?php include WPSEO_PATH . 'admin/views/partial-alerts-errors.php'; ?>
	</div>

	<div class="yoast-container yoast-container__warning">
		<?php include WPSEO_PATH . 'admin/views/partial-alerts-warnings.php'; ?>
	</div>

</div>
