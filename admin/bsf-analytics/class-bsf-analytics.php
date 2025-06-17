<?php
/**
 * BSF analytics class file.
 *
 * @version 1.0.0
 *
 * @package bsf-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Analytics' ) ) {

	/**
	 * BSF analytics
	 */
	class BSF_Analytics {

		/**
		 * Member Variable
		 *
		 * @var array Entities data.
		 */
		private $entities;

		/**
		 * Member Variable
		 *
		 * @var string Usage tracking document URL
		 */
		public $usage_doc_link = 'https://store.brainstormforce.com/usage-tracking/?utm_source=wp_dashboard&utm_medium=general_settings&utm_campaign=usage_tracking';

		/**
		 * Setup actions, load files.
		 *
		 * @param array  $args entity data for analytics.
		 * @param string $analytics_path directory path to analytics library.
		 * @param float  $analytics_version analytics library version.
		 * @since 1.0.0
		 */
		public function __construct( $args, $analytics_path, $analytics_version ) {

			// Bail when no analytics entities are registered.
			if ( empty( $args ) ) {
				return;
			}

			$this->entities = $args;

			define( 'BSF_ANALYTICS_VERSION', $analytics_version );
			define( 'BSF_ANALYTICS_URI', $this->get_analytics_url( $analytics_path ) );

			add_action( 'admin_init', array( $this, 'handle_optin_optout' ) );
			add_action( 'admin_init', array( $this, 'option_notice' ) );
			add_action( 'init', array( $this, 'maybe_track_analytics' ), 99 );

			$this->set_actions();

			add_action( 'admin_init', array( $this, 'register_usage_tracking_setting' ) );

			$this->includes();

			$this->load_deactivation_survey_actions();
		}

		/**
		 * Function to load the deactivation survey form actions.
		 *
		 * @since 1.1.6
		 * @return void
		 */
		public function load_deactivation_survey_actions() {

			// If not in a admin area then return it.
			if ( ! is_admin() ) {
				return;
			}

			add_filter( 'uds_survey_vars', array( $this, 'add_slugs_to_uds_vars' ) );
			add_action( 'admin_footer', array( $this, 'load_deactivation_survey_form' ) );
		}

		/**
		 * Setup actions for admin notice style and analytics cron event.
		 *
		 * @since 1.0.4
		 */
		public function set_actions() {

			foreach ( $this->entities as $key => $data ) {
				add_action( 'astra_notice_before_markup_' . $key . '-optin-notice', array( $this, 'enqueue_assets' ) );
				add_action( 'update_option_' . $key . '_analytics_optin', array( $this, 'update_analytics_option_callback' ), 10, 3 );
				add_action( 'add_option_' . $key . '_analytics_optin', array( $this, 'add_analytics_option_callback' ), 10, 2 );
			}
		}

		/**
		 * BSF Analytics URL
		 *
		 * @param string $analytics_path directory path to analytics library.
		 * @return String URL of bsf-analytics directory.
		 * @since 1.0.0
		 */
		public function get_analytics_url( $analytics_path ) {

			$content_dir_path = wp_normalize_path( WP_CONTENT_DIR );

			$analytics_path = wp_normalize_path( $analytics_path );

			return str_replace( $content_dir_path, content_url(), $analytics_path );
		}

		/**
		 * Enqueue Scripts.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function enqueue_assets() {

			/**
			 * Load unminified if SCRIPT_DEBUG is true.
			 *
			 * Directory and Extensions.
			 */
			$dir_name = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
			$file_rtl = ( is_rtl() ) ? '-rtl' : '';
			$css_ext  = ( SCRIPT_DEBUG ) ? '.css' : '.min.css';

			$css_uri = BSF_ANALYTICS_URI . '/assets/css/' . $dir_name . '/style' . $file_rtl . $css_ext;

			wp_enqueue_style( 'bsf-analytics-admin-style', $css_uri, false, BSF_ANALYTICS_VERSION, 'all' );
		}

		/**
		 * Send analytics API call.
		 *
		 * @since 1.0.0
		 */
		public function send() {

			$api_url = BSF_Analytics_Helper::get_api_url();

			wp_remote_post(
				$api_url . 'api/analytics/',
				array(
					'body'     => BSF_Analytics_Stats::instance()->get_stats(),
					'timeout'  => 5,
					'blocking' => false,
				)
			);
		}

		/**
		 * Check if usage tracking is enabled.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_tracking_enabled() {

			foreach ( $this->entities as $key => $data ) {

				$is_enabled = get_site_option( $key . '_analytics_optin' ) === 'yes' ? true : false;
				$is_enabled = $this->is_white_label_enabled( $key ) ? false : $is_enabled;

				if ( apply_filters( $key . '_tracking_enabled', $is_enabled ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check if WHITE label is enabled for BSF products.
		 *
		 * @param string $source source of analytics.
		 * @return bool
		 * @since 1.0.0
		 */
		public function is_white_label_enabled( $source ) {

			$options    = apply_filters( $source . '_white_label_options', array() );
			$is_enabled = false;

			if ( is_array( $options ) ) {
				foreach ( $options as $option ) {
					if ( true === $option ) {
						$is_enabled = true;
						break;
					}
				}
			}

			return $is_enabled;
		}

		/**
		 * Display admin notice for usage tracking.
		 *
		 * @since 1.0.0
		 */
		public function option_notice() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if( $this->is_tracking_enabled() ) {
				return; // Don't need to display notice if any of our plugin already have the permission.
			}

			// If the user has opted out of tracking, don't show the notice till 7 days. 
			if ( get_site_option( 'bsf_analytics_last_displayed_time' ) > time() -  ( 7 * DAY_IN_SECONDS ) ) {
				return; // Don't display the notice if it was displayed recently.
			}

			foreach ( $this->entities as $key => $data ) {

				$time_to_display = isset( $data['time_to_display'] ) ? $data['time_to_display'] : '+24 hours';
				$usage_doc_link  = isset( $data['usage_doc_link'] ) ? $data['usage_doc_link'] : $this->usage_doc_link;

				// Don't display the notice if tracking is disabled or White Label is enabled for any of our plugins.
				if ( false !== get_site_option( $key . '_analytics_optin', false ) || $this->is_white_label_enabled( $key ) ) {
					continue;
				}

				// Show tracker consent notice after 24 hours from installed time.
				if ( strtotime( $time_to_display, $this->get_analytics_install_time( $key ) ) > time() ) {
					continue;
				}

				/* translators: %s product name */
				$notice_string = sprintf(
					__(
						'Help us improve %1$s and our other products!<br><br>With your permission, we\'d like to collect <strong>non-sensitive information</strong> from your website — like your PHP version and which features you use — so we can fix bugs faster, make smarter decisions, and build features that actually matter to you. <em>No personal info. Ever.</em>'
					),
					'<strong>' . esc_html( $data['product_name'] ) . '</strong>'
				);
				
				if ( is_multisite() ) {
					$notice_string .= __( 'This will be applicable for all sites from the network.' );
				}

				$language_dir = is_rtl() ? 'rtl' : 'ltr';

				Astra_Notices::add_notice(
					array(
						'id'                         => $key . '-optin-notice',
						'type'                       => '',
						'message'                    => sprintf(
							'<div class="notice-content">
									<div class="notice-heading">
										%1$s
									</div>
									<div class="astra-notices-container">
										<a href="%2$s" class="astra-notices button-primary">
										%3$s
										</a>
										<a href="%4$s" data-repeat-notice-after="%5$s" class="astra-notices button-secondary">
										%6$s
										</a>
									</div>
								</div>',
							/* translators: %s usage doc link */
							sprintf( $notice_string . '<span dir="%1s"><a href="%2s" target="_blank" rel="noreferrer noopener">%3s</a><span><br><br>', $language_dir, esc_url( $usage_doc_link ), __( ' Know More.' ) ),
							esc_url(
								add_query_arg(
									array(
										$key . '_analytics_optin' => 'yes',
										$key . '_analytics_nonce' => wp_create_nonce( $key . '_analytics_optin' ),
										'bsf_analytics_source' => $key,
									)
								)
							),
							__( 'Yes! Allow it' ),
							esc_url(
								add_query_arg(
									array(
										$key . '_analytics_optin' => 'no',
										$key . '_analytics_nonce' => wp_create_nonce( $key . '_analytics_optin' ),
										'bsf_analytics_source' => $key,
									)
								)
							),
							MONTH_IN_SECONDS,
							__( 'No Thanks' )
						),
						'show_if'                    => true,
						'repeat-notice-after'        => false,
						'priority'                   => 18,
						'display-with-other-notices' => true,
					)
				);

				return;
			}
		}

		/**
		 * Process usage tracking opt out.
		 *
		 * @since 1.0.0
		 */
		public function handle_optin_optout() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$source = isset( $_GET['bsf_analytics_source'] ) ? sanitize_text_field( wp_unslash( $_GET['bsf_analytics_source'] ) ) : '';

			if ( ! isset( $_GET[ $source . '_analytics_nonce' ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET[ $source . '_analytics_nonce' ] ) ), $source . '_analytics_optin' ) ) {
				return;
			}

			$optin_status = isset( $_GET[ $source . '_analytics_optin' ] ) ? sanitize_text_field( wp_unslash( $_GET[ $source . '_analytics_optin' ] ) ) : '';

			if ( 'yes' === $optin_status ) {
				$this->optin( $source );
			} elseif ( 'no' === $optin_status ) {
				$this->optout( $source );
			}

			wp_safe_redirect(
				esc_url_raw(
					remove_query_arg(
						array(
							$source . '_analytics_optin',
							$source . '_analytics_nonce',
							'bsf_analytics_source',
						)
					)
				)
			);
		}

		/**
		 * Opt in to usage tracking.
		 *
		 * @param string $source source of analytics.
		 * @since 1.0.0
		 */
		private function optin( $source ) {
			update_site_option( $source . '_analytics_optin', 'yes' );
		}

		/**
		 * Opt out to usage tracking.
		 *
		 * @param string $source source of analytics.
		 * @since 1.0.0
		 */
		private function optout( $source ) {
			update_site_option( $source . '_analytics_optin', 'no' );
			update_site_option( 'bsf_analytics_last_displayed_time', time() );
		}

		/**
		 * Load analytics stat class.
		 *
		 * @since 1.0.0
		 */
		private function includes() {
			require_once __DIR__ . '/classes/class-bsf-analytics-helper.php';
			require_once __DIR__ . '/class-bsf-analytics-stats.php';

			// Loads all the modules.
			require_once __DIR__ . '/modules/deactivation-survey/classes/class-deactivation-survey-feedback.php';
			require_once __DIR__ . '/modules/utm-analytics.php';
		}

		/**
		 * Register usage tracking option in General settings page.
		 *
		 * @since 1.0.0
		 */
		public function register_usage_tracking_setting() {

			foreach ( $this->entities as $key => $data ) {

				if ( ! apply_filters( $key . '_tracking_enabled', true ) || $this->is_white_label_enabled( $key ) ) {
					return;
				}

				/**
				 * Introducing a new key 'hide_optin_checkbox, which allows individual plugin  to hide optin checkbox
				 * If they are providing providing in-plugin option to manage this option.
				 * from General > Settings page.
				 * 
				 * @since 1.1.14
				 */
				if( ! empty( $data['hide_optin_checkbox'] ) && true === $data['hide_optin_checkbox'] ) {
					continue;
				}

				$usage_doc_link = isset( $data['usage_doc_link'] ) ? $data['usage_doc_link'] : $this->usage_doc_link;
				$author         = isset( $data['author'] ) ? $data['author'] : 'Brainstorm Force';

				register_setting(
					'general',             // Options group.
					$key . '_analytics_optin',      // Option name/database.
					array( 'sanitize_callback' => array( $this, 'sanitize_option' ) ) // sanitize callback function.
				);

				add_settings_field(
					$key . '-analytics-optin',       // Field ID.
					__( 'Usage Tracking' ),       // Field title.
					array( $this, 'render_settings_field_html' ), // Field callback function.
					'general',
					'default',                   // Settings page slug.
					array(
						'type'           => 'checkbox',
						'title'          => $author,
						'name'           => $key . '_analytics_optin',
						'label_for'      => $key . '-analytics-optin',
						'id'             => $key . '-analytics-optin',
						'usage_doc_link' => $usage_doc_link,
					)
				);
			}
		}

		/**
		 * Sanitize Callback Function
		 *
		 * @param bool $input Option value.
		 * @since 1.0.0
		 */
		public function sanitize_option( $input ) {

			if ( ! $input || 'no' === $input ) {
				return 'no';
			}

			return 'yes';
		}

		/**
		 * Print settings field HTML.
		 *
		 * @param array $args arguments to field.
		 * @since 1.0.0
		 */
		public function render_settings_field_html( $args ) {
			?>
			<fieldset>
			<label for="<?php echo esc_attr( $args['label_for'] ); ?>">
				<input id="<?php echo esc_attr( $args['id'] ); ?>" type="checkbox" value="1" name="<?php echo esc_attr( $args['name'] ); ?>" <?php checked( get_site_option( $args['name'], 'no' ), 'yes' ); ?>>
				<?php
				/* translators: %s Product title */
				echo esc_html( sprintf( __( 'Allow %s products to track non-sensitive usage tracking data.' ), $args['title'] ) );// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText

				if ( is_multisite() ) {
					esc_html_e( ' This will be applicable for all sites from the network.' );
				}
				?>
			</label>
			<?php
			echo wp_kses_post( sprintf( '<a href="%1s" target="_blank" rel="noreferrer noopener">%2s</a>', esc_url( $args['usage_doc_link'] ), __( 'Learn More.' ) ) );
			?>
			</fieldset>
			<?php
		}

		/**
		 * Set analytics installed time in option.
		 *
		 * @param string $source source of analytics.
		 * @return string $time analytics installed time.
		 * @since 1.0.0
		 */
		private function get_analytics_install_time( $source ) {

			$time = get_site_option( $source . '_analytics_installed_time' );

			if ( ! $time ) {
				$time = time();
				update_site_option( $source . '_analytics_installed_time', time() );
			}

			return $time;
		}

		/**
		 * Schedule/unschedule cron event on updation of option.
		 *
		 * @param string $old_value old value of option.
		 * @param string $value value of option.
		 * @param string $option Option name.
		 * @since 1.0.0
		 */
		public function update_analytics_option_callback( $old_value, $value, $option ) {
			if ( is_multisite() ) {
				$this->add_option_to_network( $option, $value );
			}
		}

		/**
		 * Analytics option add callback.
		 *
		 * @param string $option Option name.
		 * @param string $value value of option.
		 * @since 1.0.0
		 */
		public function add_analytics_option_callback( $option, $value ) {
			if ( is_multisite() ) {
				$this->add_option_to_network( $option, $value );
			}
		}

		/**
		 * Send analytics track event if tracking is enabled.
		 *
		 * @since 1.0.0
		 */
		public function maybe_track_analytics() {

			if ( ! $this->is_tracking_enabled() ) {
				return;
			}

			$analytics_track = get_site_transient( 'bsf_analytics_track' );

			// If the last data sent is 2 days old i.e. transient is expired.
			if ( ! $analytics_track ) {
				$this->send();
				set_site_transient( 'bsf_analytics_track', true, 2 * DAY_IN_SECONDS );
			}
		}

		/**
		 * Save analytics option to network.
		 *
		 * @param string $option name of option.
		 * @param string $value value of option.
		 * @since 1.0.0
		 */
		public function add_option_to_network( $option, $value ) {

			// If action coming from general settings page.
			if ( isset( $_POST['option_page'] ) && 'general' === $_POST['option_page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing

				if ( get_site_option( $option ) ) {
					update_site_option( $option, $value );
				} else {
					add_site_option( $option, $value );
				}
			}
		}

		/**
		 * Function to load the deactivation survey form on the admin footer.
		 *
		 * This function checks if the Deactivation_Survey_Feedback class exists and if so, it loads the deactivation survey form.
		 * The form is configured with specific settings for plugin. Example: For CartFlows, including the source, logo, plugin slug, title, support URL, description, and the screen on which to show the form.
		 *
		 * @since 1.1.6
		 * @return void
		 */
		public function load_deactivation_survey_form() {

			if ( class_exists( 'Deactivation_Survey_Feedback' ) ) {
				foreach ( $this->entities as $key => $data ) {
					// If the deactivation_survey info in available then only add the form.
					if ( ! empty( $data['deactivation_survey'] ) && is_array( $data['deactivation_survey'] ) ) {
						foreach ( $data['deactivation_survey'] as $key => $survey_args ) {
							Deactivation_Survey_Feedback::show_feedback_form(
								$survey_args
							);
						}
					}
				}
			}
		}

		/**
		 * Function to add plugin slugs to Deactivation Survey vars for JS operations.
		 *
		 * @param array $vars UDS vars array.
		 * @return array Modified UDS vars array with plugin slugs.
		 * @since 1.1.6
		 */
		public function add_slugs_to_uds_vars( $vars ) {
			foreach ( $this->entities as $key => $data ) {
				if ( ! empty( $data['deactivation_survey'] ) && is_array( $data['deactivation_survey'] ) ) {
					foreach ( $data['deactivation_survey'] as $key => $survey_args ) {
						$vars['_plugin_slug'] = isset( $vars['_plugin_slug'] ) ? array_merge( $vars['_plugin_slug'], array( $survey_args['plugin_slug'] ) ) : array( $survey_args['plugin_slug'] );
					}
				}
			}

			return $vars;
		}
	}
}
