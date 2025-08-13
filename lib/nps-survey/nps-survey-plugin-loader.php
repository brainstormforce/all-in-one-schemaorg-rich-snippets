<?php
/**
 * Plugin Loader.
 *
 * @package {{package}}
 * @since {{since}}
 */

namespace NPS_Survey;

if ( ! class_exists( 'NPS_Survey_Plugin_Loader' ) ) {

	/**
	 * Plugin_Loader
	 *
	 * @since 1.0.0
	 */
	class NPS_Survey_Plugin_Loader {
		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			spl_autoload_register( [ $this, 'autoload' ] );
			add_action( 'wp_loaded', [ $this, 'load_files' ] );
		}

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Autoload classes.
		 *
		 * @param string $class class name.
		 * @return void
		 */
		public function autoload( $class ): void {
			if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
				return;
			}

			$class_to_load = $class;

			$filename = strtolower(
				strval(
					preg_replace(
						[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
						[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
						$class_to_load
					)
				)
			);

			$file = NPS_SURVEY_DIR . $filename . '.php';

			// if the file redable, include it.
			if ( is_readable( $file ) ) {
				// nosemgrep audit.php.lang.security.file.inclusion-arg - To allow the theme or plugin on WooCommerce Marketplace.
				require_once $file;
			}
		}

		/**
		 * Load Files
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function load_files(): void {
			require_once NPS_SURVEY_DIR . 'classes/nps-survey-script.php';
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	NPS_Survey_Plugin_Loader::get_instance();

}
