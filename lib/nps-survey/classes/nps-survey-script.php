<?php
/**
 * NPS Survey Script
 * File to handle behaviour and content of NPS popup`
 *
 * @package {{package}}
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Prevent multiple inclusions of this file.
if ( defined( 'NPS_SURVEY_SCRIPT_LOADED' ) ) {
	return;
}
define( 'NPS_SURVEY_SCRIPT_LOADED', true );

/**
 * Nps_Survey
 */
class Nps_Survey {
	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class Instance.
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_route' ) );
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
	 * Render NPS Survey.
	 *
	 * @param string       $id ID of the root element, should start with nps-survey- .
	 * @param array<mixed> $vars Variables to be passed to the NPS.
	 * @since 1.0.0
	 * @return void
	 */
	public static function show_nps_notice( string $id, array $vars = [] ): void {

		if ( ! isset( $vars['plugin_slug'] ) || ! is_string( $vars['plugin_slug'] ) ) {
			return;
		}

		$plugin_slug   = $vars['plugin_slug'];
		$display_after = is_int( $vars['display_after'] ) ? $vars['display_after'] : 0;

		/**
		 * Filter to check if the NPS survey should be shown.
		 *
		 * @param bool   $status Whether to show the notice.
		 * @param string $plugin_slug Plugin slug.
		 * @since 1.0.13
		 */
		$show_notice = apply_filters(
			'nps_survey_show_notice',
			self::is_show_nps_survey_form( $plugin_slug, $display_after ),
			$plugin_slug
		);

		if ( ! $show_notice ) {
			return;
		}

		$show_on_screen = ! empty( $vars['show_on_screens'] ) && is_array( $vars['show_on_screens'] ) ? $vars['show_on_screens'] : [ 'dashboard' ];

		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}
		$current_screen = get_current_screen();

		$admin_only = self::is_nps_survey_enabled_for_admin_only();
		if ( $admin_only && $current_screen instanceof WP_Screen && ! in_array( $current_screen->id, $show_on_screen, true ) ) {
			return;
		}
		// Loading script here to confirm if the screen is allowed or not.
		self::editor_load_scripts( $show_on_screen );

		?><div data-id="<?php echo esc_attr( $id ); ?>" class="nps-survey-root" data-vars="<?php echo esc_attr( strval( wp_json_encode( $vars ) ) ); ?>"></div>
		<?php
	}

	/**
	 * Load script.
	 *
	 * @param array<string> $show_on_screens An array of screen IDs where the scripts should be loaded.
	 * @since 1.0.0
	 * @return void
	 */
	public static function editor_load_scripts( $show_on_screens ): void {

		$admin_only = self::is_nps_survey_enabled_for_admin_only();
		if ( $admin_only && ! is_admin() ) {
			return;
		}

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( $admin_only && ! in_array( $screen_id, $show_on_screens, true ) ) {
			return;
		}

		$handle            = 'nps-survey-script';
		$build_path        = NPS_SURVEY_DIR . 'dist/';
		$default_build_url = NPS_SURVEY_URL . 'dist/';

		// Use a filter to allow $build_url to be modified externally.
		$build_url         = apply_filters( 'nps_survey_build_url', $default_build_url );
		$script_asset_path = $build_path . 'main.asset.php';

		$script_info = file_exists( $script_asset_path )
			? include $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => NPS_SURVEY_VER,
			);

		$script_dep = array_merge( $script_info['dependencies'], array( 'jquery' ) );

		wp_enqueue_script(
			$handle,
			$build_url . 'main.js',
			$script_dep,
			$script_info['version'],
			true
		);

		$data = apply_filters(
			'nps_survey_vars',
			[
				'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
				'_ajax_nonce'    => wp_create_nonce( 'nps-survey' ),
				'rest_api_nonce' => current_user_can( 'manage_options' ) ? wp_create_nonce( 'wp_rest' ) : '',
			]
		);

		// Add localize JS.
		wp_localize_script(
			'nps-survey-script',
			'nps_survey_data',
			$data
		);

		wp_enqueue_style( 'nps-survey-style', $build_url . '/style-main.css', array(), NPS_SURVEY_VER );
		wp_style_add_data( 'nps-survey-style', 'rtl', 'replace' );
		wp_enqueue_style( 'nps-survey-fonts', NPS_SURVEY_URL . 'assets/fonts/figtree.css', array(), NPS_SURVEY_VER );
	}

	/**
	 * Load all the required files in the importer.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function register_route(): void {

		register_rest_route(
			self::get_api_namespace(),
			'/rating/',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( self::class, 'submit_rating' ),
					'permission_callback' => array( self::class, 'get_item_permissions_check' ),
					'args'                => array(
						'nps_id'      => array(
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_key',
						),
						'rating'      => array(
							'type'              => 'integer',
							'required'          => true,
							'validate_callback' => static function ( $value ) {
								return is_numeric( $value ) && (int) $value >= 0 && (int) $value <= 10;
							},
							'sanitize_callback' => 'absint',
						),
						'comment'     => array(
							'type'              => 'string',
							'required'          => false,
							'default'           => '',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'plugin_slug' => array(
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_key',
						),
					),
				),
			)
		);

		register_rest_route(
			self::get_api_namespace(),
			'/dismiss-nps-survey/',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( self::class, 'dismiss_nps_survey_panel' ),
					'permission_callback' => array( self::class, 'get_item_permissions_check' ),
					'args'                => array(
						'nps_id'           => array(
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_key',
						),
						'plugin_slug'      => array(
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_key',
						),
						'dismiss_timespan' => array(
							'type'              => 'integer',
							'required'          => true,
							'sanitize_callback' => 'absint',
						),
						'current_step'     => array(
							'type'              => 'string',
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);
	}

	/**
	 * Get the API URL.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function get_api_domain() {
		return trailingslashit( defined( 'NPS_SURVEY_REMOTE_URL' ) ? NPS_SURVEY_REMOTE_URL : apply_filters( 'nps_survey_api_domain', 'https://metrics.brainstormforce.com/' ) );
	}

	/**
	 * Get api namespace
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_api_namespace() {
		return 'nps-survey/v1';
	}

	/**
	 * Get API headers
	 *
	 * @since 1.0.0
	 * @return array<string, string>
	 */
	public static function get_api_headers() {
		return array(
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json',
		);
	}

	/**
	 * Check whether a given request has permission to read notes.
	 *
	 * @param  object $request WP_REST_Request Full details about the request.
	 * @return object|bool
	 */
	public static function get_item_permissions_check( $request ) {
		/**
		 * Filter to disable the REST API permission check for NPS Survey endpoints.
		 *
		 * @security WARNING: Setting this filter to `true` removes all authentication
		 *   and capability checks from the NPS Survey REST API endpoints, making them
		 *   publicly accessible to any unauthenticated request. Only use this in
		 *   controlled environments where you explicitly intend to open these endpoints.
		 *
		 * @param bool $disable Whether to bypass the permission check. Default false.
		 * @since 1.0.13
		 */
		if ( apply_filters( 'nps_survey_api_disable_permission_check', false ) ) {
			return true;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'nps_survey_rest_cannot_access',
				__( 'Sorry, you are not allowed to do that.', 'nps-survey' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Method to determine if the NPS survey status update should be skipped for database option.
	 *
	 * @param string $nps_id NPS ID.
	 * @param string $type Type of action (e.g., 'submit', 'dismiss').
	 * @param array  $data Additional data related to the NPS survey.
	 *
	 * @since 1.0.13
	 * @return bool
	 * @phpstan-ignore-next-line
	 */
	public static function should_skip_status_update( $nps_id, $type, $data = array() ): bool {
		/**
		 * Filter to determine if the NPS survey status should be updated.
		 *
		 * @param bool  $update Default is true, can be modified by the filter.
		 * @param array $post_data Post data being sent.
		 * @since 1.0.13
		 */
		return apply_filters(
			'nps_survey_should_skip_status_update',
			false, // Default to false, can be modified by the filter.
			array_merge(
				$data,
				array(
					'nps_id'      => $nps_id,
					'action_type' => $type,
				)
			)
		);
	}

	/**
	 * Submit Ratings.
	 *
	 * @param \WP_REST_Request<array<string,mixed>> $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function submit_rating( $request ) {

		$nonce = $request->get_header( 'X-WP-Nonce' );

		// Verify the nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( (string) $nonce ), 'wp_rest' ) ) {
			return new \WP_Error(
				'nonce_verification_failed',
				__( 'Nonce verification failed.', 'nps-survey' ),
				array( 'status' => 403 )
			);
		}

		$current_user = wp_get_current_user();
		$raw_nps_id   = $request->get_param( 'nps_id' );
		$raw_rating   = $request->get_param( 'rating' );
		$raw_comment  = $request->get_param( 'comment' );
		$raw_slug     = $request->get_param( 'plugin_slug' );
		$nps_id       = sanitize_key( is_string( $raw_nps_id ) ? $raw_nps_id : '' );
		$rating       = absint( is_numeric( $raw_rating ) ? $raw_rating : 0 );
		$comment      = sanitize_text_field( is_string( $raw_comment ) ? $raw_comment : '' );
		$plugin_slug  = sanitize_key( is_string( $raw_slug ) ? $raw_slug : '' );

		/**
		 * Filter the post data.
		 * This can be used to modify the post data before sending it to the API.
		 *
		 * @param array<mixed> $post_data Post data.
		 * @param string       $nps_id    NPS ID.
		 * @return array<mixed>
		 */
		$post_data = apply_filters(
			'nps_survey_post_data',
			array(
				'rating'      => $rating,
				'comment'     => $comment,
				'email'       => $current_user->user_email,
				'first_name'  => ! empty( $current_user->first_name ) ? $current_user->first_name : $current_user->display_name,
				'last_name'   => ! empty( $current_user->last_name ) ? $current_user->last_name : '',
				'source'      => $plugin_slug,
				'plugin_slug' => $plugin_slug,
			),
			$nps_id
		);

		/**
		 * Filter the API endpoint.
		 *
		 * @param string       $api_endpoint API endpoint.
		 * @param array<mixed> $post_data    Post data.
		 * @param string       $nps_id       NPS ID.
		 *
		 * @return string
		 */
		$api_endpoint = apply_filters(
			'nps_survey_api_endpoint',
			self::get_api_domain() . 'wp-json/bsf-metrics-server/v1/nps-survey/',
			$post_data, // Pass the post data to the filter, so that the endpoint can be modified based on the data.
			$nps_id
		);

		$post_data_in_json = wp_json_encode( $post_data );
		$request_args      = array(
			'body'    => $post_data_in_json ? $post_data_in_json : '',
			'headers' => self::get_api_headers(),
			'timeout' => 60,
		);

		$response = wp_safe_remote_post( $api_endpoint, $request_args );

		if ( is_wp_error( $response ) ) {
			return new \WP_Error(
				'remote_request_failed',
				__( 'Remote request failed.', 'nps-survey' ),
				array( 'status' => 500 )
			);
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code || 201 === $response_code ) {

			// If the status update should be skipped, return success.
			if ( self::should_skip_status_update( $nps_id, 'submit', $post_data ) ) {
				return rest_ensure_response(
					array(
						'status' => true,
					)
				);
			}

			$nps_form_status = array(
				'dismiss_count'       => 0,
				'dismiss_permanently' => true,
				'dismiss_step'        => '',
			);

			update_option( self::get_nps_id( $plugin_slug ), $nps_form_status, false );

			return rest_ensure_response(
				array(
					'status' => true,
				)
			);

		} else {
			return new \WP_Error(
				'api_error',
				__( 'Request failed.', 'nps-survey' ),
				array( 'status' => 500 )
			);
		}
	}

	/**
	 * Dismiss NPS Survey.
	 *
	 * @param \WP_REST_Request<array<string,mixed>> $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function dismiss_nps_survey_panel( $request ) {

		$nonce = $request->get_header( 'X-WP-Nonce' );

		// Verify the nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( (string) $nonce ), 'wp_rest' ) ) {
			return new \WP_Error(
				'nonce_verification_failed',
				__( 'Nonce verification failed.', 'nps-survey' ),
				array( 'status' => 403 )
			);
		}

		// If the status update should be skipped, return success.
		$raw_nps_id       = $request->get_param( 'nps_id' );
		$raw_slug         = $request->get_param( 'plugin_slug' );
		$raw_timespan     = $request->get_param( 'dismiss_timespan' );
		$raw_step         = $request->get_param( 'current_step' );
		$nps_id           = sanitize_key( is_string( $raw_nps_id ) ? $raw_nps_id : '' );
		$plugin_slug      = sanitize_key( is_string( $raw_slug ) ? $raw_slug : '' );
		$dismiss_timespan = absint( is_numeric( $raw_timespan ) ? $raw_timespan : 0 );
		$current_step     = sanitize_text_field( is_string( $raw_step ) ? $raw_step : '' );

		if ( self::should_skip_status_update( $nps_id, 'dismiss' ) ) {
			return rest_ensure_response(
				array(
					'status' => true,
				)
			);
		}

		$nps_form_status = self::get_nps_survey_dismiss_status( $plugin_slug );

		// Add dismiss timespan.
		$nps_form_status['dismiss_timespan'] = $dismiss_timespan;

		// Add dismiss date.
		$nps_form_status['dismiss_time'] = time();

		// Update dismiss count.
		$nps_form_status['dismiss_count'] += 1;
		$nps_form_status['dismiss_step']   = $current_step;

		// Dismiss Permanantly.
		if ( $nps_form_status['dismiss_count'] >= 2 ) {
			$nps_form_status['dismiss_permanently'] = true;
		}

		update_option( self::get_nps_id( $plugin_slug ), $nps_form_status, false );

		return rest_ensure_response(
			array(
				'status' => true,
			)
		);
	}

	/**
	 * Get dismiss status of NPS Survey.
	 *
	 * @param  string $plugin_slug slug of unique NPS Survey.
	 * @return array<string, mixed>
	 */
	public static function get_nps_survey_dismiss_status( string $plugin_slug ) {

		$default_status = get_option(
			self::get_nps_id( $plugin_slug ),
			array(
				'dismiss_count'       => 0,
				'dismiss_permanently' => false,
				'dismiss_step'        => '',
				'dismiss_time'        => '',
				'dismiss_timespan'    => null,
				'first_render_time'   => null,
			)
		);

		if ( ! is_array( $default_status ) ) {
			return array();
		}

		return array(
			'dismiss_count'       => ! empty( $default_status['dismiss_count'] ) ? $default_status['dismiss_count'] : 0,
			'dismiss_permanently' => ! empty( $default_status['dismiss_permanently'] ) ? $default_status['dismiss_permanently'] : false,
			'dismiss_step'        => ! empty( $default_status['dismiss_step'] ) ? $default_status['dismiss_step'] : '',
			'dismiss_time'        => ! empty( $default_status['dismiss_time'] ) ? $default_status['dismiss_time'] : '',
			'dismiss_timespan'    => ! empty( $default_status['dismiss_timespan'] ) ? $default_status['dismiss_timespan'] : null,
			'first_render_time'   => ! empty( $default_status['first_render_time'] ) ? $default_status['first_render_time'] : null,
		);
	}

	/**
	 * Show status of NPS Survey.
	 *
	 * @param  string $plugin_slug slug of unique NPS Survey.
	 * @param  int    $display_after number of days after which NPS Survey should be displayed.
	 * @return bool
	 */
	public static function is_show_nps_survey_form( string $plugin_slug, int $display_after ) {

		$current_time = time();
		$status       = self::get_nps_survey_dismiss_status( $plugin_slug );

		if ( $status['dismiss_permanently'] ) {
			return false;
		}

		$first_render_time = $status['first_render_time'];

		if ( 0 !== $display_after ) {
			if ( null === $first_render_time ) {
				$status['first_render_time'] = $current_time;
				update_option( self::get_nps_id( $plugin_slug ), $status );
				$status = self::get_nps_survey_dismiss_status( $plugin_slug );
				return false;
			}
			if ( $display_after + $first_render_time > $current_time ) {
				return false;
			}
		}

		// Retrieve the stored date time stamp from wp_options.
		$stored_date_timestamp = $status['dismiss_time'];
		$dismiss_timespan      = $status['dismiss_timespan'];

		if ( $stored_date_timestamp ) {

			$current_time = time();

			// time difference of current time and the time user dismissed the nps.
			$time_difference = $current_time - $stored_date_timestamp;

			// Check if two weeks have passed.
			if ( $time_difference <= $dismiss_timespan ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if NPS Survey is enabled for admin only. Default is true.
	 *
	 * @since 1.0.13
	 * @return bool
	 */
	public static function is_nps_survey_enabled_for_admin_only() {
		/**
		 * Filter to check if NPS Survey is enabled for admin only.
		 *
		 * @since 1.0.13
		 */
		return apply_filters( 'nps_survey_enabled_for_admin_only', true );
	}

	/**
	 * Get NPS Dismiss Option Name.
	 *
	 * @param string $plugin_slug Plugin name.
	 * @return string
	 */
	public static function get_nps_id( $plugin_slug ) {
		return 'nps-survey-' . $plugin_slug;
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Nps_Survey::get_instance();
