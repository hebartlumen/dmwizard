<?php
/**
 * @package WPSEO\Admin
 */

/**
 * Admin form class.
 *
 * @since 2.0
 */
class Yoast_Form {

	/**
	 * @var object    Instance of this class
	 * @since 2.0
	 */
	public static $instance;

	/**
	 * @var string
	 * @since 2.0
	 */
	public $option_name;

	/**
	 * @var array
	 * @since 2.0
	 */
	public $options;

	/**
	 * Get the singleton instance of this class
	 *
	 * @since 2.0
	 *
	 * @return Yoast_Form
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Generates the header for admin pages
	 *
	 * @since 2.0
	 *
	 * @param bool   $form             Whether or not the form start tag should be included.
	 * @param string $option           The short name of the option to use for the current page.
	 * @param bool   $contains_files   Whether the form should allow for file uploads.
	 * @param bool   $option_long_name Group name of the option.
	 */
	public function admin_header( $form = true, $option = 'wpseo', $contains_files = false, $option_long_name = false ) {
		if ( ! $option_long_name ) {
			$option_long_name = WPSEO_Options::get_group_name( $option );
		}
		?>
		<div class="wrap yoast wpseo-admin-page page-<?php echo $option; ?>">
		<?php
		/**
		 * Display the updated/error messages
		 * Only needed as our settings page is not under options, otherwise it will automatically be included
		 *
		 * @see settings_errors()
		 */
		require_once( ABSPATH . 'wp-admin/options-head.php' );
		?>
		<h1 id="wpseo-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<div class="wpseo_content_wrapper">
		<div class="wpseo_content_cell" id="wpseo_content_top">
		<?php
		if ( $form === true ) {
			$enctype = ( $contains_files ) ? ' enctype="multipart/form-data"' : '';
			echo '<form action="' . esc_url( admin_url( 'options.php' ) ) . '" method="post" id="wpseo-conf"' . $enctype . ' accept-charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">';
			settings_fields( $option_long_name );
		}
		$this->set_option( $option );
	}

	/**
	 * Set the option used in output for form elements
	 *
	 * @since 2.0
	 *
	 * @param string $option_name Option key.
	 */
	public function set_option( $option_name ) {
		$this->option_name = $option_name;
		$this->options     = $this->get_option();
	}

	/**
	 * Retrieve options based on whether we're on multisite or not.
	 *
	 * @since 1.2.4
	 * @since 2.0   Moved to this class.
	 *
	 * @return array
	 */
	private function get_option() {
		if ( is_network_admin() ) {
			return get_site_option( $this->option_name );
		}

		return get_option( $this->option_name );
	}

	/**
	 * Generates the footer for admin pages
	 *
	 * @since 2.0
	 *
	 * @param bool $submit       Whether or not a submit button and form end tag should be shown.
	 * @param bool $show_sidebar Whether or not to show the banner sidebar - used by premium plugins to disable it.
	 */
	public function admin_footer( $submit = true, $show_sidebar = true ) {
		if ( $submit ) {
			submit_button();

			echo '
			</form>';
		}

		/**
		 * Apply general admin_footer hooks
		 */
		do_action( 'wpseo_admin_footer' );

		/**
		 * Run possibly set actions to add for example an i18n box
		 */
		do_action( 'wpseo_admin_promo_footer' );

		echo '
			</div><!-- end of div wpseo_content_top -->';

		if ( $show_sidebar ) {
			$this->admin_sidebar();
		}

		echo '</div><!-- end of div wpseo_content_wrapper -->';


		if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ) {
			$xdebug = ( extension_loaded( 'xdebug' ) ? true : false );
			echo '
			<div id="poststuff">
			<div id="wpseo-debug-info" class="postbox">

				<h2 class="hndle"><span>' . esc_html__( 'Debug Information', 'wordpress-seo' ) . '</span></h2>
				<div class="inside">
					<h3 class="wpseo-debug-heading">' . esc_html( __( 'Current option:', 'wordpress-seo' ) ) . ' <span class="wpseo-debug">' . esc_html( $this->option_name ) . '</span></h3>
					' . ( ( $xdebug ) ? '' : '<pre>' );
			var_dump( $this->get_option() );
			echo '
					' . ( ( $xdebug ) ? '' : '</pre>' ) . '
				</div>
			</div>
			</div>';
		}

		echo '
			</div><!-- end of wrap -->';
	}

	/**
	 * Generates the sidebar for admin pages.
	 *
	 * @since 2.0
	 */
	public function admin_sidebar() {

		// No banners in Premium.
		if ( class_exists( 'WPSEO_Product_Premium' ) ) {
			$license_manager = new Yoast_Plugin_License_Manager( new WPSEO_Product_Premium() );
			if ( $license_manager->license_is_valid() ) {
				return;
			}
		}

		$sidebar_renderer = new WPSEO_Admin_Banner_Sidebar_Renderer( new WPSEO_Admin_Banner_Spot_Renderer() );

		$banner_renderer = new WPSEO_Admin_Banner_Renderer;
		$banner_renderer->set_base_path( plugins_url( 'images/banner/', WPSEO_FILE ) );

		$sidebar = new WPSEO_Admin_Banner_Sidebar( sprintf( '%1s recommendations', 'Yoast' ), $banner_renderer );
		$sidebar->initialize( new WPSEO_Features() );

		echo $sidebar_renderer->render( $sidebar );

	}

	/**
	 * Output a label element
	 *
	 * @since 2.0
	 *
	 * @param string $text Label text string.
	 * @param array  $attr HTML attributes set.
	 */
	public function label( $text, $attr ) {
		$attr = wp_parse_args( $attr, array(
				'class' => 'checkbox',
				'close' => true,
				'for'   => '',
			)
		);
		echo "<label class='" . esc_attr( $attr['class'] ) . "' for='" . esc_attr( $attr['for'] ) . "'>$text";
		if ( $attr['close'] ) {
			echo '</label>';
		}
	}

	/**
	 * Output a legend element.
	 *
	 * @since 3.4
	 *
	 * @param string $text Legend text string.
	 * @param array  $attr HTML attributes set.
	 */
	public function legend( $text, $attr ) {
		$attr = wp_parse_args( $attr, array(
				'id' => '',
				'class' => '',
			)
		);
		$id = ( '' === $attr['id'] ) ? '' : ' id="' . esc_attr( $attr['id'] ) . '"';
		echo '<legend class="yoast-form-legend ' . esc_attr( $attr['class'] ) . '"' . $id . '>' . $text . '</legend>';
	}

	/**
	 * Create a Checkbox input field.
	 *
	 * @since 2.0
	 *
	 * @param string $var        The variable within the option to create the checkbox for.
	 * @param string $label      The label to show for the variable.
	 * @param bool   $label_left Whether the label should be left (true) or right (false).
	 */
	public function checkbox( $var, $label, $label_left = false ) {
		if ( ! isset( $this->options[ $var ] ) ) {
			$this->options[ $var ] = false;
		}

		if ( $this->options[ $var ] === true ) {
			$this->options[ $var ] = 'on';
		}

		$class = '';
		if ( $label_left !== false ) {
			if ( ! empty( $label_left ) ) {
				$label_left .= ':';
			}
			$this->label( $label_left, array( 'for' => $var ) );
		}
		else {
			$class = 'double';
		}

		echo '<input class="checkbox ', esc_attr( $class ), '" type="checkbox" id="', esc_attr( $var ), '" name="', esc_attr( $this->option_name ), '[', esc_attr( $var ), ']" value="on"', checked( $this->options[ $var ], 'on', false ), '/>';

		if ( ! empty( $label ) ) {
			$this->label( $label, array( 'for' => $var ) );
		}

		echo '<br class="clear" />';
	}

	/**
	 * Create a light switch input field.
	 *
	 * @since 3.1
	 *
	 * @param string  $var        The variable within the option to create the checkbox for.
	 * @param string  $label      The label to show for the variable.
	 * @param array   $buttons    Array of two labels for the buttons (defaults Off/On).
	 * @param boolean $reverse    Reverse order of buttons (default true).
	 */
	public function light_switch( $var, $label, $buttons = array(), $reverse = true ) {

		if ( ! isset( $this->options[ $var ] ) ) {
			$this->options[ $var ] = false;
		}

		if ( $this->options[ $var ] === true ) {
			$this->options[ $var ] = 'on';
		}

		$class = 'switch-light switch-candy switch-yoast-seo';
		$aria_labelledby = esc_attr( $var ) . '-label';

		if ( $reverse ) {
			$class .= ' switch-yoast-seo-reverse';
		}

		if ( empty( $buttons ) ) {
			$buttons = array( __( 'Disabled', 'wordpress-seo' ), __( 'Enabled', 'wordpress-seo' ) );
		}

		list( $off_button, $on_button ) = $buttons;

		echo '<div class="switch-container">',
		'<label class="', esc_attr( $class ), '"><b class="switch-yoast-seo-jaws-a11y">&nbsp;</b>',
		'<input type="checkbox" aria-labelledby="', $aria_labelledby, '" id="', esc_attr( $var ), '" name="', esc_attr( $this->option_name ), '[', esc_attr( $var ), ']" value="on"', checked( $this->options[ $var ], 'on', false ), '/>',
		"<b class='label-text' id='{$aria_labelledby}'>{$label}</b>",
		'<span aria-hidden="true">
			<span>', esc_html( $off_button ) ,'</span>
			<span>', esc_html( $on_button ) ,'</span>
			<a></a>
		 </span>
		 </label><div class="clear"></div></div>';
	}

	/**
	 * Create a Text input field.
	 *
	 * @since 2.0
	 * @since 2.1 Introduced the `$attr` parameter.
	 *
	 * @param string       $var   The variable within the option to create the text input field for.
	 * @param string       $label The label to show for the variable.
	 * @param array|string $attr  Extra class to add to the input field.
	 */
	public function textinput( $var, $label, $attr = array() ) {
		if ( ! is_array( $attr ) ) {
			$attr = array(
				'class' => $attr,
			);
		}
		$attr = wp_parse_args( $attr, array(
			'placeholder' => '',
			'class'       => '',
		) );
		$val  = ( isset( $this->options[ $var ] ) ) ? $this->options[ $var ] : '';

		$this->label( $label . ':', array( 'for' => $var, 'class' => 'textinput' ) );
		echo '<input class="textinput ' . esc_attr( $attr['class'] ) . ' " placeholder="' . esc_attr( $attr['placeholder'] ) . '" type="text" id="', esc_attr( $var ), '" name="', esc_attr( $this->option_name ), '[', esc_attr( $var ), ']" value="', esc_attr( $val ), '"/>', '<br class="clear" />';
	}

	/**
	 * Create a textarea.
	 *
	 * @since 2.0
	 *
	 * @param string $var   The variable within the option to create the textarea for.
	 * @param string $label The label to show for the variable.
	 * @param array  $attr  The CSS class to assign to the textarea.
	 */
	public function textarea( $var, $label, $attr = array() ) {
		if ( ! is_array( $attr ) ) {
			$attr = array(
				'class' => $attr,
			);
		}
		$attr = wp_parse_args( $attr, array(
			'cols'  => '',
			'rows'  => '',
			'class' => '',
		) );
		$val  = ( isset( $this->options[ $var ] ) ) ? $this->options[ $var ] : '';

		$this->label( $label . ':', array( 'for' => $var, 'class' => 'textinput' ) );
		echo '<textarea cols="' . esc_attr( $attr['cols'] ) . '" rows="' . esc_attr( $attr['rows'] ) . '" class="textinput ' . esc_attr( $attr['class'] ) . '" id="' . esc_attr( $var ) . '" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $var ) . ']">' . esc_textarea( $val ) . '</textarea>' . '<br class="clear" />';
	}

	/**
	 * Create a hidden input field.
	 *
	 * @since 2.0
	 *
	 * @param string $var The variable within the option to create the hidden input for.
	 * @param string $id  The ID of the element.
	 */
	public function hidden( $var, $id = '' ) {
		$val = ( isset( $this->options[ $var ] ) ) ? $this->options[ $var ] : '';
		if ( is_bool( $val ) ) {
			$val = ( $val === true ) ? 'true' : 'false';
		}

		if ( '' === $id ) {
			$id = 'hidden_' . $var;
		}

		echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $var ) . ']" value="' . esc_attr( $val ) . '"/>';
	}

	/**
	 * Create a Select Box.
	 *
	 * @since 2.0
	 *
	 * @param string $field_name     The variable within the option to create the select for.
	 * @param string $label          The label to show for the variable.
	 * @param array  $select_options The select options to choose from.
	 */
	public function select( $field_name, $label, array $select_options ) {

		if ( empty( $select_options ) ) {
			return;
		}

		$this->label( $label . ':', array( 'for' => $field_name, 'class' => 'select' ) );

		$select_name   = esc_attr( $this->option_name ) . '[' . esc_attr( $field_name ) . ']';
		$active_option = ( isset( $this->options[ $field_name ] ) ) ? $this->options[ $field_name ] : '';

		$select = new Yoast_Input_Select( $field_name, $select_name, $select_options, $active_option );
		$select->add_attribute( 'class', 'select' );
		$select->output_html();

		echo '<br class="clear"/>';
	}

	/**
	 * Create a File upload field.
	 *
	 * @since 2.0
	 *
	 * @param string $var   The variable within the option to create the file upload field for.
	 * @param string $label The label to show for the variable.
	 */
	public function file_upload( $var, $label ) {
		$val = '';
		if ( isset( $this->options[ $var ] ) && is_array( $this->options[ $var ] ) ) {
			$val = $this->options[ $var ]['url'];
		}

		$var_esc = esc_attr( $var );
		$this->label( $label . ':', array( 'for' => $var, 'class' => 'select' ) );
		echo '<input type="file" value="' . esc_attr( $val ) . '" class="textinput" name="' . esc_attr( $this->option_name ) . '[' . $var_esc . ']" id="' . $var_esc . '"/>';

		// Need to save separate array items in hidden inputs, because empty file inputs type will be deleted by settings API.
		if ( ! empty( $this->options[ $var ] ) ) {
			$this->hidden( 'file', $this->option_name . '_file' );
			$this->hidden( 'url', $this->option_name . '_url' );
			$this->hidden( 'type', $this->option_name . '_type' );
		}
		echo '<br class="clear"/>';
	}

	/**
	 * Media input
	 *
	 * @since 2.0
	 *
	 * @param string $var   Option name.
	 * @param string $label Label message.
	 */
	public function media_input( $var, $label ) {
		$val = '';
		if ( isset( $this->options[ $var ] ) ) {
			$val = $this->options[ $var ];
		}

		$var_esc = esc_attr( $var );

		$this->label( $label . ':', array( 'for' => 'wpseo_' . $var, 'class' => 'select' ) );
		echo '<input class="textinput" id="wpseo_', $var_esc, '" type="text" size="36" name="', esc_attr( $this->option_name ), '[', $var_esc, ']" value="', esc_attr( $val ), '" />';
		echo '<input id="wpseo_', $var_esc, '_button" class="wpseo_image_upload_button button" type="button" value="', esc_attr__( 'Upload Image', 'wordpress-seo' ), '" />';
		echo '<br class="clear"/>';
	}

	/**
	 * Create a Radio input field.
	 *
	 * @since 2.0
	 *
	 * @param string $var         The variable within the option to create the radio button for.
	 * @param array  $values      The radio options to choose from.
	 * @param string $legend      Optional. The legend to show for the field set, if any.
	 * @param array  $legend_attr Optional. The attributes for the legend, if any.
	 */
	public function radio( $var, $values, $legend = '', $legend_attr = array() ) {
		if ( ! is_array( $values ) || $values === array() ) {
			return;
		}
		if ( ! isset( $this->options[ $var ] ) ) {
			$this->options[ $var ] = false;
		}

		$var_esc = esc_attr( $var );

		echo '<fieldset class="yoast-form-fieldset wpseo_radio_block" id="' . $var_esc . '">';

		if ( is_string( $legend ) && '' !== $legend ) {

			$legend_attr = wp_parse_args( $legend_attr, array(
				'id'    => '',
				'class' => 'radiogroup',
			) );

			$this->legend( $legend, $legend_attr );
		}

		foreach ( $values as $key => $value ) {
			$key_esc = esc_attr( $key );
			echo '<input type="radio" class="radio" id="' . $var_esc . '-' . $key_esc . '" name="' . esc_attr( $this->option_name ) . '[' . $var_esc . ']" value="' . $key_esc . '" ' . checked( $this->options[ $var ], $key_esc, false ) . ' />';
			$this->label( $value, array( 'for' => $var_esc . '-' . $key_esc, 'class' => 'radio' ) );
		}
		echo '</fieldset>';
	}


	/**
	 * Create a toggle switch input field.
	 *
	 * @since 3.1
	 *
	 * @param string $var    The variable within the option to create the file upload field for.
	 * @param array  $values The radio options to choose from.
	 * @param string $label  The label to show for the variable.
	 */
	public function toggle_switch( $var, $values, $label ) {
		if ( ! is_array( $values ) || $values === array() ) {
			return;
		}
		if ( ! isset( $this->options[ $var ] ) ) {
			$this->options[ $var ] = false;
		}
		if ( $this->options[ $var ] === true ) {
			$this->options[ $var ] = 'on';
		}
		if ( $this->options[ $var ] === false ) {
			$this->options[ $var ] = 'off';
		}

		$var_esc = esc_attr( $var );

		echo '<div class="switch-container">';
		echo '<fieldset id="', $var_esc, '" class="fieldset-switch-toggle"><legend>', $label, '</legend>
		<div class="switch-toggle switch-candy switch-yoast-seo">';

		foreach ( $values as $key => $value ) {
			$key_esc = esc_attr( $key );
			$for     = $var_esc . '-' . $key_esc;
			echo '<input type="radio" id="' . $for . '" name="' . esc_attr( $this->option_name ) . '[' . $var_esc . ']" value="' . $key_esc . '" ' . checked( $this->options[ $var ], $key_esc, false ) . ' />',
			'<label for="', $for, '">', esc_html( $value ), '</label>';
		}

		echo '<a></a></div></fieldset><div class="clear"></div></div>' . "\n\n";
	}

	/**
	 * Returns two random selected service banners.
	 *
	 * @since 3.9
	 *
	 * @return WPSEO_Admin_Banner_Spot
	 */
	private function get_service_banners() {

		$service_banner_spot = new WPSEO_Admin_Banner_Spot(
			__( 'Services', 'wordpress-seo' ),
			sprintf(
				/* translators: %1$s expands to a link start tag to the Yoast Services page, %2$s to Yoast, %3$s is the link closing tag.  */
				__( 'Don\'t want to dive into SEO yourself? %1$sLet team %2$s help you!%3$s', 'wordpress-seo' ),
				'<a href="https://yoa.st/">',
				'Yoast',
				'</a>'
			)
		);

		$service_banner_spot->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/hire-us/website-review/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=website-review-banner',
				'banner-website-review.png',
				261,
				190,
				__( 'Order a Website Review and we will tell you what to improve to attract more visitors!', 'wordpress-seo' )
			)
		);

		$service_banner_spot->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/hire-us/yoast-seo-configuration/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=configuration-service-banner',
				'banner-configuration-service.png',
				261,
				190,
				sprintf(
					/* translators: %1$s expands to Yoast SEO Premium. */
					__( 'Let our experts set up your %1$s plugin!', 'wordpress-seo' ),
					'Yoast SEO Premium'
				)
			)
		);

		$service_banner_spot->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/academy/course/seo-copywriting-training/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=seo-copywriting-training-banner',
				'banner-seo-copywriting-training.png',
				261,
				190,
				__( 'Take the online SEO Copywriting Training course and learn how to write awesome copy that ranks!', 'wordpress-seo' )
			)
		);

		$service_banner_spot->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/academy/course/basic-seo-training/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=basic-seo-training-banner',
				'banner-basic-seo-training.png',
				261,
				190,
				__( 'Take the online Basic SEO Training course and learn the fundamentals of SEO!', 'wordpress-seo' )
			)
		);

		$service_banner_spot->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/academy/course/yoast-seo-wordpress-training/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=yoast-seo-plugin-training-banner',
				'banner-yoast-seo-for-wordpress-training.png',
				261,
				190,
				sprintf(
					/* translators: %1$s expands to Yoast SEO for WordPress Training, %2$s to Yoast SEO for WordPress. */
					__( 'Take the %1$s course and become a certified %2$s expert!', 'wordpress-seo' ),
					'Yoast SEO for WordPress Training',
					'Yoast SEO for WordPress'
				)
			)
		);

		return $service_banner_spot;
	}

	/**
	 * Returns two random selected plugin banners.
	 *
	 * @since 3.9
	 *
	 * @return WPSEO_Admin_Banner_Spot
	 */
	private function get_plugin_banners() {

		$plugin_banners = new WPSEO_Admin_Banner_Spot(
			__( 'Extensions', 'wordpress-seo' ),
			sprintf(
				/* translators: %1$s expands to Yoast SEO, %2$s to a link start tag to the Yoast plugin page, %3$s is the link closing tag. */
				__( 'Extend your %1$s plugin with our %2$sSEO plugins%3$s.', 'wordpress-seo' ),
				'Yoast SEO',
				'<a href="https://yoa.st/">',
				'</a>'
			)
		);


		$plugin_banners->add_banner(
			new WPSEO_Admin_Banner(
				'https://yoast.com/wordpress/plugins/seo-premium/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=premium-seo-banner',
				'banner-premium-seo.png',
				261,
				152,
				sprintf(
					/* translators: %1$s expands to Yoast SEO Premium. */
					__( 'Buy the %1$s plugin now and get access to extra features and 24/7 support!', 'wordpress-seo' ),
					'Yoast SEO Premium'
				)
			)
		);

		if ( ! class_exists( 'wpseo_Video_Sitemap' ) ) {
			$plugin_banners->add_banner(
				new WPSEO_Admin_Banner(
					'https://yoast.com/wordpress/plugins/video-seo/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=video-seo-banner',
					'banner-video-seo.png',
					261,
					152,
					sprintf(
						/* translators: %1$s expands to Yoast Video SEO. */
						__( 'Buy the %1$s plugin now and optimize your videos for video search results and social media!', 'wordpress-seo' ),
						'Yoast Video SEO'
					)
				)
			);
		}

		if ( class_exists( 'Woocommerce' ) && ! class_exists( 'Yoast_WooCommerce_SEO' ) ) {
			$plugin_banners->add_banner(
				new WPSEO_Admin_Banner(
					'https://yoast.com/wordpress/plugins/yoast-woocommerce-seo/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=woocommerce-seo-banner',
					'banner-woocommerce-seo.png',
					261,
					152,
					sprintf(
						/* translators: %1$s expands to Yoast WooCommerce SEO. */
						__( 'Buy the %1$s plugin now and optimize your shop today to improve your product promotion!', 'wordpress-seo' ),
						'Yoast WooCommerce SEO'
					)
				)
			);
		}

		if ( ! defined( 'WPSEO_LOCAL_VERSION' ) ) {
			$plugin_banners->add_banner(
				new WPSEO_Admin_Banner(
					'https://yoast.com/wordpress/plugins/local-seo/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=local-seo-banner',
					'banner-local-seo.png',
					261,
					152,
					sprintf(
						/* translators: %1$s expands to Yoast Local SEO. */
						__( 'Buy the %1$s plugin now to improve your site&#8217;s Local SEO and ranking in Google Maps!', 'wordpress-seo' ),
						'Yoast Local SEO'
					)
				)
			);
		}

		if ( ! class_exists( 'WPSEO_News' ) ) {
			$plugin_banners->add_banner(
				new WPSEO_Admin_Banner(
					'https://yoast.com/wordpress/plugins/news-seo/#utm_source=wordpress-seo-config&utm_medium=banner&utm_campaign=news-seo-banner',
					'banner-news-seo.png',
					261,
					152,
					sprintf(
						/* translators: %1$s expands to Yoast News SEO. */
						__( 'Buy the %1$s plugin now and start optimizing to get your site featured in Google News!', 'wordpress-seo' ),
						'Yoast News SEO'
					)
				)
			);
		}

		return $plugin_banners;
	}
}
