<?php
/**
 * UABB.
 *
 * @package UABB
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AIOSRS_Nps_Survey' ) ) :

	/**
	 * Admin
	 */
	class AIOSRS_Nps_Survey {
		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @var (Object) AIOSRS_Nps_Survey
		 */
		private static $instance = null;

		/**
		 * Get Instance
		 *
		 * @since 1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			$this->version_check();
			add_action( 'init', array( $this, 'load' ), 999 );
		}

		/**
		 * Version Check
		 *
		 * @return void
		 */
		public function version_check() {

			$file = realpath( dirname( __FILE__ ) . '/nps-survey/version.json' );

			// Is file exist?
			if ( is_file( $file ) ) {

				$file_data = json_decode( file_get_contents( $file ), true );

				global $nps_survey_version, $nps_survey_init;

				$path = realpath( dirname( __FILE__ ) . '/nps-survey/nps-survey.php' );

				$version = isset( $file_data['nps-survey'] ) ? $file_data['nps-survey'] : 0;

				if ( null === $nps_survey_version ) {
					$nps_survey_version = '1.0.0';
				}

				// Compare versions.
				if ( version_compare( $version, $nps_survey_version, '>=' ) ) {
					$nps_survey_version = $version;
					$nps_survey_init    = $path;
				}
			}
		}

		/**
		 * Load latest plugin
		 *
		 * @return void
		 */
		public function load() {

			global $nps_survey_version, $nps_survey_init;
			if ( is_file( realpath( $nps_survey_init ) ) ) {
				include_once realpath( $nps_survey_init );
			}
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	AIOSRS_Nps_Survey::get_instance();

endif;
