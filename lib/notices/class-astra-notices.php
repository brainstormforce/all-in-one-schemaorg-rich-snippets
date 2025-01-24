<?php
/**
 * Astra Sites Notices
 *
 * Closing notice on click on `astra-notice-close` class.
 *
 * If notice has the data attribute `data-repeat-notice-after="%2$s"` then notice close for that SPECIFIC TIME.
 * If notice has NO data attribute `data-repeat-notice-after="%2$s"` then notice close for the CURRENT USER FOREVER.
 *
 * > Create custom close notice link in the notice markup. E.g.
 * `<a href="#" data-repeat-notice-after="<?php echo MONTH_IN_SECONDS; ?>" class="astra-notice-close">`
 * It close the notice for 30 days.
 *
 * @package Astra Sites
 * @since 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Astra_Notices' ) ) {

	/**
	 * Astra_Notices
	 *
	 * @since 1.4.0
	 */
	class Astra_Notices {
		/**
		 * Notices
		 *
		 * @access private
		 * @var array Notices.
		 * @since 1.4.0
		 */
		private static $version = '1.1.6';

		/**
		 * Notices
		 *
		 * @access private
		 * @var array Notices.
		 * @since 1.4.0
		 */
		private static $notices = [];

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since 1.4.0
		 */
		private static $instance;

		/**
		 * Constructor
		 *
		 * @since 1.4.0
		 */
		public function __construct() {
			add_action( 'admin_notices', [ $this, 'show_notices' ], 30 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_ajax_astra-notice-dismiss', [ $this, 'dismiss_notice' ] );
			add_filter( 'wp_kses_allowed_html', [ $this, 'add_data_attributes' ], 10, 2 );
		}

		/**
		 * Initiator
		 *
		 * @since 1.4.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Filters and Returns a list of allowed tags and attributes for a given context.
		 *
		 * @param Array  $allowedposttags Array of allowed tags.
		 * @param String $context Context type (explicit).
		 * @since 1.4.0
		 * @return Array
		 */
		public function add_data_attributes( $allowedposttags, $context ) {
			$allowedposttags['a']['data-repeat-notice-after'] = true;

			return $allowedposttags;
		}

		/**
		 * Add Notice.
		 *
		 * @since 1.4.0
		 * @param array $args Notice arguments.
		 * @return void
		 */
		public static function add_notice( $args = [] ): void {
			self::$notices[] = $args;
		}

		/**
		 * Dismiss Notice.
		 *
		 * @since 1.4.0
		 * @return void
		 */
		public function dismiss_notice(): void {

			if ( ! apply_filters( 'astra_notices_user_cap_check', current_user_can( 'manage_options' ) ) ) {
				return;
			}

			$notice_id           = isset( $_POST['notice_id'] ) ? sanitize_key( $_POST['notice_id'] ) : '';
			$repeat_notice_after = isset( $_POST['repeat_notice_after'] ) ? absint( $_POST['repeat_notice_after'] ) : '';
			$nonce               = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';

			if ( wp_verify_nonce( $nonce, 'astra-notices' ) === false ) {
				wp_send_json_error( esc_html_e( 'WordPress Nonce not validated.', 'all-in-one-schemaorg-rich-snippets' ) );
			}

			// Valid inputs?
			if ( ! empty( $notice_id ) ) {

				if ( ! empty( $repeat_notice_after ) ) {
					set_transient( $notice_id, true, $repeat_notice_after );
				} else {
					update_user_meta( get_current_user_id(), $notice_id, 'notice-dismissed' );
				}

				wp_send_json_success();
			}

			wp_send_json_error();
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.4.0
		 * @return void
		 */
		public function enqueue_scripts(): void {
			wp_register_script( 'astra-notices', self::_get_uri() . 'notices.js', [ 'jquery' ], self::$version, true );
			wp_localize_script(
				'astra-notices',
				'astraNotices',
				[
					'_notice_nonce' => wp_create_nonce( 'astra-notices' ),
				]
			);
		}

		/**
		 * Rating priority sort
		 *
		 * @since 1.5.2
		 * @param array $array1 array one.
		 * @param array $array2 array two.
		 * @return array
		 */
		public function sort_notices( $array1, $array2 ) {
			if ( ! isset( $array1['priority'] ) ) {
				$array1['priority'] = 10;
			}
			if ( ! isset( $array2['priority'] ) ) {
				$array2['priority'] = 10;
			}

			return $array1['priority'] - $array2['priority'];
		}

		/**
		 * Notice Types
		 *
		 * @since 1.4.0
		 * @return void
		 */
		public function show_notices(): void {

			$defaults = [
				'id'                         => '',      // Optional, Notice ID. If empty it set `astra-notices-id-<$array-index>`.
				'type'                       => 'info',  // Optional, Notice type. Default `info`. Expected [info, warning, notice, error].
				'message'                    => '',      // Optional, Message.
				'show_if'                    => true,    // Optional, Show notice on custom condition. E.g. 'show_if' => if( is_admin() ) ? true, false, .
				'repeat-notice-after'        => '',      // Optional, Dismiss-able notice time. It'll auto show after given time.
				'display-notice-after'       => false,      // Optional, Dismiss-able notice time. It'll auto show after given time.
				'class'                      => '',      // Optional, Additional notice wrapper class.
				'priority'                   => 10,      // Priority of the notice.
				'display-with-other-notices' => true,    // Should the notice be displayed if other notices  are being displayed from Astra_Notices.
				'is_dismissible'             => true,
			];

			// Count for the notices that are rendered.
			$notices_displayed = 0;

			// sort the array with priority.
			usort( self::$notices, [ $this, 'sort_notices' ] );

			foreach ( self::$notices as $key => $notice ) {

				$notice = wp_parse_args( $notice, $defaults );

				$notice['id'] = self::get_notice_id( $notice, $key );

				$notice['classes'] = self::get_wrap_classes( $notice );

				// Notices visible after transient expire.
				if ( isset( $notice['show_if'] ) && $notice['show_if'] === true ) {

					// don't display the notice if it is not supposed to be displayed with other notices.
					if ( $notices_displayed !== 0 && $notice['display-with-other-notices'] === false ) {
						continue;
					}

					if ( self::is_expired( $notice ) ) {

						self::markup( $notice );
						++$notices_displayed;
					}
				}
			}
		}

		/**
		 * Markup Notice.
		 *
		 * @since 1.4.0
		 * @param  array $notice Notice markup.
		 * @return void
		 */
		public static function markup( $notice = [] ): void {

			wp_enqueue_script( 'astra-notices' );

			do_action( 'astra_notice_before_markup' );

			do_action( "astra_notice_before_markup_{$notice['id']}" );

			?>
			<div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>" data-repeat-notice-after="<?php echo esc_attr( $notice['repeat-notice-after'] ); ?>">
				<div class="notice-container">
					<?php do_action( "astra_notice_inside_markup_{$notice['id']}" ); ?>
					<?php echo wp_kses_post( $notice['message'] ); ?>
				</div>
			</div>
			<?php

			do_action( "astra_notice_after_markup_{$notice['id']}" );

			do_action( 'astra_notice_after_markup' );
		}

		/**
		 * Get URI
		 *
		 * @return mixed URL.
		 */
		public static function _get_uri() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
			$path       = wp_normalize_path( dirname( __FILE__ ) );
			$theme_dir  = wp_normalize_path( get_template_directory() );
			$plugin_dir = wp_normalize_path( WP_PLUGIN_DIR );

			if ( strpos( $path, $theme_dir ) !== false ) {
				return trailingslashit( get_template_directory_uri() . str_replace( $theme_dir, '', $path ) );
			}
			if ( strpos( $path, $plugin_dir ) !== false ) {
				return plugin_dir_url( __FILE__ );
			}
			if ( strpos( $path, dirname( plugin_basename( __FILE__ ) ) ) !== false ) {
				return plugin_dir_url( __FILE__ );
			}

			return; // phpcs:ignore Squiz.PHP.NonExecutableCode.ReturnNotRequired
		}

		/**
		 * Notice classes.
		 *
		 * @since 1.4.0
		 *
		 * @param  array $notice Notice arguments.
		 * @return array       Notice wrapper classes.
		 */
		private static function get_wrap_classes( $notice ) {
			$classes = [ 'astra-notice', 'notice' ];

			if ( $notice['is_dismissible'] ) {
				$classes[] = 'is-dismissible';
			}

			$classes[] = $notice['class'];
			if ( isset( $notice['type'] ) && $notice['type'] !== '' ) {
				$classes[] = 'notice-' . $notice['type'];
			}

			return esc_attr( implode( ' ', $classes ) );
		}

		/**
		 * Get Notice ID.
		 *
		 * @since 1.4.0
		 *
		 * @param  array $notice Notice arguments.
		 * @param  int   $key     Notice array index.
		 * @return string       Notice id.
		 */
		private static function get_notice_id( $notice, $key ) {
			if ( isset( $notice['id'] ) && ! empty( $notice['id'] ) ) {
				return $notice['id'];
			}

			return 'astra-notices-id-' . $key;
		}

		/**
		 * Is notice expired?
		 *
		 * @since 1.4.0
		 *
		 * @param  array $notice Notice arguments.
		 * @return bool
		 */
		private static function is_expired( $notice ) {
			$transient_status = get_transient( $notice['id'] );

			if ( $transient_status === false ) {

				if ( isset( $notice['display-notice-after'] ) && $notice['display-notice-after'] !== false ) {

					if ( get_user_meta( get_current_user_id(), $notice['id'], true ) !== 'delayed-notice' &&
						get_user_meta( get_current_user_id(), $notice['id'], true ) !== 'notice-dismissed' ) {
						set_transient( $notice['id'], 'delayed-notice', $notice['display-notice-after'] );
						update_user_meta( get_current_user_id(), $notice['id'], 'delayed-notice' );

						return false;
					}
				}

				// Check the user meta status if current notice is dismissed or delay completed.
				$meta_status = get_user_meta( get_current_user_id(), $notice['id'], true );

				if ( empty( $meta_status ) || $meta_status === 'delayed-notice' ) {
					return true;
				}
			}

			return false;
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Notices::get_instance();

}
