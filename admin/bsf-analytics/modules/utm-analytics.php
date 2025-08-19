<?php
/**
 * UTM Analytics class
 *
 * @package bsf-analytics
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_UTM_Analytics' ) ) {

	
	if ( ! defined( 'BSF_UTM_ANALYTICS_REFERER' ) ) {
		define( 'BSF_UTM_ANALYTICS_REFERER', 'bsf_product_referers' );
	}

	/**
	 * UTM Analytics class
	 *
	 * @since 1.1.10
	 */
	class BSF_UTM_Analytics {
	
		/**
		 * List of slugs of all the bsf products that will be referer, referring another product.
		 *
		 * @var array<string>
		 * @since 1.1.10
		 */
		private static $bsf_product_slugs = [
			'all-in-one-schemaorg-rich-snippets',
			'astra',
			'astra-portfolio',
			'astra-sites',
			'bb-ultimate-addon',
			'cartflows',
			'checkout-paypal-woo',
			'checkout-plugins-stripe-woo',
			'convertpro',
			'header-footer-elementor',
			'latepoint',
			'presto-player',
			'surecart',
			'sureforms',
			'suremails',
			'surerank',
			'suretriggers',
			'ultimate-addons-for-beaver-builder-lite',
			'ultimate-addons-for-gutenberg',
			'ultimate-elementor',
			'Ultimate_VC_Addons',
			'variation-swatches-woo',
			'woo-cart-abandonment-recovery',
			'wp-schema-pro',
			'zipwp'
		];
	
	
		/**
		 * This function will help to determine if provided slug is a valid bsf product or not,
		 * This way we will maintain consistency through out all our products.
		 *
		 * @param string $slug unique slug of the product which can be used for referer, product.
		 * @since 1.1.10
		 * @return boolean
		 */
		public static function is_valid_bsf_product_slug( $slug ) {
			if ( empty( $slug ) || ! is_string( $slug ) ) {
				return false;
			}
	
			return in_array( $slug, self::$bsf_product_slugs, true );
		}
	
		/**
		 * This function updates value of referer and product in option
		 * bsf_product_referer in form of key value pair as 'product' => 'referer'
		 *
		 * @param string $referer slug of the product which is refering another product.
		 * @param string $product slug of the product which is refered.
		 * @since 1.1.10
		 * @return void
		 */
		public static function update_referer( $referer, $product ) {
	
			$slugs       = [
				'referer' => $referer,
				'product' => $product,
			];
			$error_count = 0;
	
			foreach ( $slugs as $type => $slug ) {
				if ( ! self::is_valid_bsf_product_slug( $slug ) ) {
					error_log( sprintf( 'Invalid %1$s slug provided "%2$s", does not match bsf_product_slugs', $type, $slug ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- adding logs in case of failure will help in debugging.
					$error_count++;
				}
			}
	
			if ( $error_count > 0 ) {
				return;
			}
	
			$slugs = array_map( 'sanitize_text_field', $slugs );
	
			$bsf_product_referers = get_option( BSF_UTM_ANALYTICS_REFERER, [] );
			if ( ! is_array( $bsf_product_referers ) ) {
				$bsf_product_referers = [];
			}
	
			$bsf_product_referers[ $slugs['product'] ] = $slugs['referer'];
	
			update_option( BSF_UTM_ANALYTICS_REFERER, $bsf_product_referers );
		}
	
		/**
		 * This function will  add utm_args to pro link or purchase link
		 * added utm_source by default additional utm_args such as utm_medium etc can be provided to generate location specific links
		 *
		 * @param string $link Ideally this should be product site link where utm_params can be tracked.
		 * @param string $product Product slug whose utm_link need to be created.
		 * @param mixed  $utm_args additional args to be passed ex: [ 'utm_medium' => 'dashboard'].
		 * @since 1.1.10
		 * @return string
		 */
		public static function get_utm_ready_link( $link, $product, $utm_args = [] ) {
	
			if ( false === wp_http_validate_url( $link ) ) {
				error_log( 'Invalid url passed to get_utm_ready_link function' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- adding logs in case of failure will help in debugging.
				return $link;
			}
	
			if ( empty( $product ) || ! is_string( $product ) || ! self::is_valid_bsf_product_slug( $product ) ) {
				error_log( sprintf( 'Invalid product slug provided "%1$s", does not match bsf_product_slugs', $product ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- adding logs in case of failure will help in debugging.
				return $link;
			}
	
			$bsf_product_referers = get_option( BSF_UTM_ANALYTICS_REFERER, [] );
	
			if ( ! is_array( $bsf_product_referers ) || empty( $bsf_product_referers[ $product ] ) ) {
				return $link;
			}
	
			if ( ! self::is_valid_bsf_product_slug( $bsf_product_referers[ $product ] ) ) {
				return $link;
			}
	
			if ( ! is_array( $utm_args ) ) {
				$utm_args = [];
			}
	
			$utm_args['utm_source'] = $bsf_product_referers[ $product ];
	
			$link = add_query_arg(
				$utm_args,
				$link
			);
	
			return $link;
		}
	}
}


