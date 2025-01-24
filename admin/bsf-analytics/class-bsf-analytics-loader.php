<?php
/**
 * BSF analytics loader file.
 *
 * @version 1.0.0
 *
 * @package bsf-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BSF_Analytics_Loader.
 */
class BSF_Analytics_Loader {
	/**
	 * Analytics Entities.
	 *
	 * @access private
	 * @var array Entities array.
	 */
	private $entities = [];

	/**
	 * Analytics Version.
	 *
	 * @access private
	 * @var float analytics version.
	 */
	private $analytics_version = '';

	/**
	 * Analytics path.
	 *
	 * @access private
	 * @var string path array.
	 */
	private $analytics_path = '';

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'load_analytics' ] );
	}

	/**
	 * Get instace of class.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Set entity for analytics.
	 *
	 * @param string $data Entity attributes data.
	 * @return void
	 */
	public function set_entity( $data ): void {
		array_push( $this->entities, $data );
	}

	/**
	 * Load Analytics library.
	 *
	 * @return void
	 */
	public function load_analytics(): void {
		$unique_entities = [];

		if ( ! empty( $this->entities ) ) {
			foreach ( $this->entities as $entity ) {
				foreach ( $entity as $key => $data ) {

					if ( isset( $data['path'] ) ) {
						if ( file_exists( $data['path'] . '/version.json' ) ) {
							$file_contents     = file_get_contents( $data['path'] . '/version.json' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
							$analytics_version = json_decode( $file_contents, 1 );
							$analytics_version = $analytics_version['bsf-analytics-ver'];

							if ( version_compare( $analytics_version, $this->analytics_version, '>' ) ) {
								$this->analytics_version = $analytics_version;
								$this->analytics_path    = $data['path'];
							}
						}
					}

					if ( ! isset( $unique_entities[ $key ] ) ) {
						$unique_entities[ $key ] = $data;
					}
				}
			}

			if ( file_exists( $this->analytics_path ) && ! class_exists( 'BSF_Analytics' ) ) {
				require_once $this->analytics_path . '/class-bsf-analytics.php';
				new BSF_Analytics( $unique_entities, $this->analytics_path, $this->analytics_version );
			}
		}
	}
}
