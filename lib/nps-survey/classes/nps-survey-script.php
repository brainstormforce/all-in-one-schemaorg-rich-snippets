<?php
/**
 * NPS Survey Script
 * File to handle behaviour and content of NPS popup
 *
 * @package {{package}}
 */

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

		if ( ! self::is_show_nps_survey_form( $plugin_slug, $display_after ) ) {
			return;
		}

		$show_on_screen = ! empty( $vars['show_on_screens'] ) && is_array( $vars['show_on_screens'] ) ? $vars['show_on_screens'] : [ 'dashboard' ];

		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . '/wp-admin/includes/screen.php';
		}
		$current_screen = get_current_screen();

		if ( $current_screen instanceof WP_Screen && ! in_array( $current_screen->id, $show_on_screen, true ) ) {
			return;
		}
		// Loading script here to confirm if the screen is allowed or not.
		self::editor_load_scripts( $show_on_screen );

		?><div data-id="<?php echo esc_attr( $id ); ?>" class="nps-survey-root" data-vars="<?php echo esc_attr( strval( wp_json_encode( $vars ) ) ); ?>"></div>
		<?php
	}

	/**
	 * Generate and return the Google fonts url.
	 *
	 * @since 1.0.2
	 * @return string
	 */
	public static function google_fonts_url() {

		$font_families = array(
			'Figtree:400,500,600,700',
		);

		$query_args = array(
			'family' => rawurlencode( implode( '|', $font_families ) ),
			'subset' => rawurlencode( 'latin,latin-ext' ),
		);

		return add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}

	/**
	 * Load script.
	 *
	 * @param array<string> $show_on_screens An array of screen IDs where the scripts should be loaded.
	 * @since 1.0.0
	 * @return void
	 */
	public static function editor_load_scripts( $show_on_screens ): void {

		if ( ! is_admin() ) {
			return;
		}

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( ! in_array( $screen_id, $show_on_screens, true ) ) {
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
			'npsSurvey',
			$data
		);

		wp_enqueue_style( 'nps-survey-style', $build_url . '/style-main.css', array(), NPS_SURVEY_VER );
		wp_style_add_data( 'nps-survey-style', 'rtl', 'replace' );
		wp_enqueue_style( 'nps-survey-google-fonts', self::google_fonts_url(), array(), 'all' );
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
					'args'                => array(),
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
					'args'                => array(),
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
		return trailingslashit( defined( 'NPS_SURVEY_REMOTE_URL' ) ? NPS_SURVEY_REMOTE_URL : apply_filters( 'nps_survey_api_domain', 'https://websitedemos.net/' ) );
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

		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'gt_rest_cannot_access',
				__( 'Sorry, you are not allowed to do that.', 'nps-survey' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Submit Ratings.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return void
	 * @phpstan-ignore-next-line
	 */
	public static function submit_rating( $request ) {

		$nonce = $request->get_header( 'X-WP-Nonce' );

		// Verify the nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( (string) $nonce ), 'wp_rest' ) ) {
			wp_send_json_error(
				array(
					'data'   => __( 'Nonce verification failed.', 'nps-survey' ),
					'status' => false,

				)
			);
		}

		$current_user = wp_get_current_user();

		/**
		 * Filter the post data.
		 * This can be used to modify the post data before sending it to the API.
		 *
		 * @param array<mixed> $post_data Post data.
		 * @return array<mixed>
		 */
		$post_data = apply_filters(
			'nps_survey_post_data',
			array(
				'rating'      => ! empty( $request['rating'] ) ? sanitize_text_field( strval( $request['rating'] ) ) : '',
				'comment'     => ! empty( $request['comment'] ) ? sanitize_text_field( strval( $request['comment'] ) ) : '',
				'email'       => $current_user->user_email,
				'first_name'  => $current_user->first_name ?? $current_user->display_name,
				'last_name'   => $current_user->last_name ?? '',
				'source'      => ! empty( $request['plugin_slug'] ) ? sanitize_text_field( strval( $request['plugin_slug'] ) ) : '',
				'plugin_slug' => ! empty( $request['plugin_slug'] ) ? sanitize_text_field( strval( $request['plugin_slug'] ) ) : '',
			)
		);

		/**
		 * Filter the API endpoint.
		 *
		 * @param string       $api_endpoint API endpoint.
		 * @param array<mixed> $post_data    Post data.
		 *
		 * @return string
		 */
		$api_endpoint = apply_filters(
			'nps_survey_api_endpoint',
			self::get_api_domain() . 'wp-json/starter-templates/v1/nps-survey/',
			$post_data // Pass the post data to the filter, so that the endpoint can be modified based on the data.
		);

		$post_data_in_json = wp_json_encode( $post_data );
		$request_args      = array(
			'body'    => $post_data_in_json ? $post_data_in_json : '',
			'headers' => self::get_api_headers(),
			'timeout' => 60,
		);

		$response = wp_safe_remote_post( $api_endpoint, $request_args );

		if ( is_wp_error( $response ) ) {
			// There was an error in the request.
			wp_send_json_error(
				array(
					'data'   => 'Failed ' . $response->get_error_message(),
					'status' => false,

				)
			);
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {

			$nps_form_status = array(
				'dismiss_count'       => 0,
				'dismiss_permanently' => true,
				'dismiss_step'        => '',
			);

			update_option( self::get_nps_id( strval( $request['plugin_slug'] ) ), $nps_form_status, false );

			wp_send_json_success(
				array(
					'status' => true,
				)
			);

		} else {
			wp_send_json_error(
				array(
					'status' => false,

				)
			);
		}
	}

	/**
	 * Dismiss NPS Survey.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return void
	 * @phpstan-ignore-next-line
	 */
	public static function dismiss_nps_survey_panel( $request ) {

		$nonce = $request->get_header( 'X-WP-Nonce' );

		// Verify the nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( (string) $nonce ), 'wp_rest' ) ) {
			wp_send_json_error(
				array(
					'data'   => __( 'Nonce verification failed.', 'nps-survey' ),
					'status' => false,

				)
			);
		}

		$nps_form_status = self::get_nps_survey_dismiss_status( strval( $request['plugin_slug'] ) );

		// Add dismiss timespan.
		$nps_form_status['dismiss_timespan'] = $request['dismiss_timespan'];

		// Add dismiss date.
		$nps_form_status['dismiss_time'] = time();

		// Update dismiss count.
		$nps_form_status['dismiss_count'] += 1;
		$nps_form_status['dismiss_step']   = $request['current_step'];

		// Dismiss Permanantly.
		if ( $nps_form_status['dismiss_count'] >= 2 ) {
			$nps_form_status['dismiss_permanently'] = true;
		}

		update_option( self::get_nps_id( strval( $request['plugin_slug'] ) ), $nps_form_status );

		wp_send_json_success(
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
