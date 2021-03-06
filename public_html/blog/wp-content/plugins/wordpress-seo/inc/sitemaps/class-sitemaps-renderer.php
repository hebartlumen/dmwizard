<?php
/**
 * @package WPSEO\XML_Sitemaps
 */

/**
 * Renders XML output for sitemaps.
 */
class WPSEO_Sitemaps_Renderer {

	/** @var string $stylesheet XSL stylesheet for styling a sitemap for web browsers. */
	protected $stylesheet = '';

	/** @var string $charset Holds the get_bloginfo( 'charset' ) value to reuse for performance. */
	protected $charset = 'UTF-8';

	/** @var string $output_charset Holds charset of output, might be converted. */
	protected $output_charset = 'UTF-8';

	/** @var bool $needs_conversion If data encoding needs to be converted for output. */
	protected $needs_conversion = false;

	/** @var WPSEO_Sitemap_Timezone $timezone */
	protected $timezone;

	/**
	 * Set up object properties.
	 */
	public function __construct() {

		$stylesheet_url       = preg_replace( '/(^http[s]?:)/', '', esc_url( home_url( 'main-sitemap.xsl' ) ) );
		$this->stylesheet     = '<?xml-stylesheet type="text/xsl" href="' . esc_url( $stylesheet_url ) . '"?>';
		$this->charset        = get_bloginfo( 'charset' );
		$this->output_charset = $this->charset;
		$this->timezone       = new WPSEO_Sitemap_Timezone();

		if (
			'UTF-8' !== $this->charset
			&& function_exists( 'mb_list_encodings' )
			&& in_array( $this->charset, mb_list_encodings(), true )
		) {
			$this->output_charset = 'UTF-8';
		}

		$this->needs_conversion = $this->output_charset !== $this->charset;
	}

	/**
	 * @param array $links Set of sitemaps index links.
	 *
	 * @return string
	 */
	public function get_index( $links ) {

		$xml = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

		foreach ( $links as $link ) {
			$xml .= $this->sitemap_index_url( $link );
		}

		/**
		 * Filter to append sitemaps to the index.
		 *
		 * @param string $index String to append to sitemaps index, defaults to empty.
		 */
		$xml .= apply_filters( 'wpseo_sitemap_index', '' );
		$xml .= '</sitemapindex>';

		return $xml;
	}

	/**
	 * @param array  $links        Set of sitemap links.
	 * @param string $type         Sitemap type.
	 * @param int    $current_page Current sitemap page number.
	 *
	 * @return string
	 */
	public function get_sitemap( $links, $type, $current_page ) {

		$urlset = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" '
			. 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" '
			. 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

		/**
		 * Filters the `urlset` for a sitemap by type.
		 *
		 * @api string $urlset The output for the sitemap's `urlset`.
		 */
		$xml = apply_filters( "wpseo_sitemap_{$type}_urlset", $urlset );

		foreach ( $links as $url ) {
			$xml .= $this->sitemap_url( $url );
		}

		/**
		 * Filter to add extra URLs to the XML sitemap by type.
		 *
		 * Only runs for the first page, not on all.
		 *
		 * @param string $content String content to add, defaults to empty.
		 */
		if ( $current_page === 1 ) {
			$xml .= apply_filters( "wpseo_sitemap_{$type}_content", '' );
		}

		$xml .= '</urlset>';

		return $xml;
	}

	/**
	 * Produce final XML output with debug information.
	 *
	 * @param string  $sitemap    Sitemap XML.
	 * @param boolean $transient  Transient cache flag.
	 *
	 * @return string
	 */
	public function get_output( $sitemap, $transient ) {

		$output = '<?xml version="1.0" encoding="' . esc_attr( $this->output_charset ) . '"?>';

		if ( $this->stylesheet ) {
			/**
			 * Filter the stylesheet URL for the XML sitemap.
			 *
			 * @param string $stylesheet Stylesheet URL.
			 */
			$output .= apply_filters( 'wpseo_stylesheet_url', $this->stylesheet ) . "\n";
		}

		$output .= $sitemap;
		$output .= "\n<!-- XML Sitemap generated by Yoast SEO -->";

		$debug = WP_DEBUG || ( defined( 'WPSEO_DEBUG' ) && true === WPSEO_DEBUG );

		if ( ! WP_DEBUG_DISPLAY || ! $debug ) {
			return $output;
		}

		$memory_used = number_format( ( memory_get_peak_usage() / 1048576 ), 2 );
		$queries_run = ( $transient ) ? 'Served from transient cache' : 'Queries executed ' . absint( $GLOBALS['wpdb']->num_queries );

		$output .= "\n<!-- {$memory_used}MB | {$queries_run} -->";

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {

			$queries = print_r( $GLOBALS['wpdb']->queries, true );
			$output .= "\n<!-- {$queries} -->";
		}

		return $output;
	}

	/**
	 * Get charset for the output.
	 *
	 * @return string
	 */
	public function get_output_charset() {
		return $this->output_charset;
	}

	/**
	 * Set a custom stylesheet for this sitemap. Set to empty to just remove the default stylesheet.
	 *
	 * @param string $stylesheet Full xml-stylesheet declaration.
	 */
	public function set_stylesheet( $stylesheet ) {
		$this->stylesheet = $stylesheet;
	}

	/**
	 * Build the `<sitemap>` tag for a given URL.
	 *
	 * @param array $url Array of parts that make up this entry.
	 *
	 * @return string
	 */
	protected function sitemap_index_url( $url ) {

		$date = null;

		if ( ! empty( $url['lastmod'] ) ) {
			$date = $this->timezone->format_date( $url['lastmod'] );
		}

		$url['loc'] = htmlspecialchars( $url['loc'] );

		$output = "\t<sitemap>\n";
		$output .= "\t\t<loc>" . $url['loc'] . "</loc>\n";
		$output .= empty( $date ) ? '' : "\t\t<lastmod>" . htmlspecialchars( $date ) . "</lastmod>\n";
		$output .= "\t</sitemap>\n";

		return $output;
	}

	/**
	 * Build the `<url>` tag for a given URL.
	 *
	 * Public access for backwards compatibility reasons.
	 *
	 * @param array $url Array of parts that make up this entry.
	 *
	 * @return string
	 */
	public function sitemap_url( $url ) {

		$date = null;


		if ( ! empty( $url['mod'] ) ) {
			// Create a DateTime object date in the correct timezone.
			$date = $this->timezone->format_date( $url['mod'] );
		}

		$url['loc'] = htmlspecialchars( $url['loc'] );

		$output = "\t<url>\n";
		$output .= "\t\t<loc>" . $this->encode_url_rfc3986( $url['loc'] ) . "</loc>\n";
		$output .= empty( $date ) ? '' : "\t\t<lastmod>" . htmlspecialchars( $date ) . "</lastmod>\n";

		if ( empty( $url['images'] ) ) {
			$url['images'] = array();
		}

		foreach ( $url['images'] as $img ) {

			if ( empty( $img['src'] ) ) {
				continue;
			}

			$output .= "\t\t<image:image>\n";
			$output .= "\t\t\t<image:loc>" . esc_html( $this->encode_url_rfc3986( $img['src'] ) ) . "</image:loc>\n";

			if ( ! empty( $img['title'] ) ) {

				$title = $img['title'];

				if ( $this->needs_conversion ) {
					$title = mb_convert_encoding( $title, $this->output_charset, $this->charset );
				}

				$title = _wp_specialchars( html_entity_decode( $title, ENT_QUOTES, $this->output_charset ) );
				$output .= "\t\t\t<image:title><![CDATA[{$title}]]></image:title>\n";
			}

			if ( ! empty( $img['alt'] ) ) {

				$alt = $img['alt'];

				if ( $this->needs_conversion ) {
					$alt = mb_convert_encoding( $alt, $this->output_charset, $this->charset );
				}

				$alt = _wp_specialchars( html_entity_decode( $alt, ENT_QUOTES, $this->output_charset ) );
				$output .= "\t\t\t<image:caption><![CDATA[{$alt}]]></image:caption>\n";
			}

			$output .= "\t\t</image:image>\n";
		}
		unset( $img, $title, $alt );

		$output .= "\t</url>\n";

		/**
		 * Filters the output for the sitemap url tag.
		 *
		 * @api   string $output The output for the sitemap url tag.
		 *
		 * @param array  $url The sitemap url array on which the output is based.
		 */
		return apply_filters( 'wpseo_sitemap_url', $output, $url );
	}

	/**
	 * Apply some best effort conversion to comply with RFC3986.
	 *
	 * @param string $url URL to encode.
	 *
	 * @return string
	 */
	protected function encode_url_rfc3986( $url ) {

		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			return $url;
		}

		$path = parse_url( $url, PHP_URL_PATH );

		if ( ! empty( $path ) && '/' !== $path ) {

			$encoded_path = explode( '/', $path );
			$encoded_path = array_map( 'rawurlencode', $encoded_path );
			$encoded_path = implode( '/', $encoded_path );
			$encoded_path = str_replace( '%7E', '~', $encoded_path ); // PHP <5.3.

			$url = str_replace( $path, $encoded_path, $url );
		}

		$query = parse_url( $url, PHP_URL_QUERY );

		if ( ! empty( $query ) ) {

			parse_str( $query, $parsed_query );

			if ( defined( 'PHP_QUERY_RFC3986' ) ) { // PHP 5.4+.
				$parsed_query = http_build_query( $parsed_query, null, '&amp;', PHP_QUERY_RFC3986 );
			}
			else {
				$parsed_query = http_build_query( $parsed_query, null, '&amp;' );
				$parsed_query = str_replace( '+', '%20', $parsed_query );
				$parsed_query = str_replace( '%7E', '~', $parsed_query );
			}

			$url = str_replace( $query, $parsed_query, $url );
		}

		return $url;
	}
}
