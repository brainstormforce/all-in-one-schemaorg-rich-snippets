<?php
/**
 * Deactivation Survey Feedback.
 *
 * @package bsf-analytics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Deactivation_Survey_Feedback' ) ) {

	/**
	 * Class Deactivation_Survey_Feedback.
	 */
	class Deactivation_Survey_Feedback {

		/**
		 * Feedback URL.
		 *
		 * @var string
		 */
		private static $feedback_api_endpoint = 'api/plugin-deactivate';

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since 1.1.6
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.1.6
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_form_styles' ) );
			add_action( 'wp_ajax_uds_plugin_deactivate_feedback', array( $this, 'send_plugin_deactivate_feedback' ) );
		}

		/**
		 * Render feedback HTML on plugins.php admin page only.
		 *
		 * This function renders the feedback form HTML on the plugins.php admin page.
		 * It takes an optional string parameter $id for the form wrapper ID and an optional array parameter $args for customizing the form.
		 *
		 * @since 1.1.6
		 * @param array $args Optional. Custom arguments for the form. Defaults to an empty array.
		 * @return void
		 */
		public static function show_feedback_form( array $args = array() ) {

			// Return if not in admin.
			if ( ! is_admin() ) {
				return;
			}

			// Set default arguments for the feedback form.
			$defaults = array(
				'source'            => 'User Deactivation Survey',
				'popup_logo'        => '',
				'plugin_slug'       => 'user-deactivation-survey',
				'plugin_version'    => '',
				'popup_title'       => __( 'Quick Feedback' ),
				'support_url'       => 'https://brainstormforce.com/contact/',
				'popup_reasons'     => self::get_default_reasons(),
				'popup_description' => __( 'If you have a moment, please share why you are deactivating the plugin.' ),
				'show_on_screens'   => array( 'plugins' ),
			);

			// Parse the arguments with defaults.
			$args = wp_parse_args( $args, $defaults );
			$id   = '';

			// Set a default ID if none is provided.
			if ( empty( $args['id'] ) ) {
				$id = 'uds-feedback-form--wrapper';
			}

			$id = sanitize_text_field( $args['id'] );

			// Return if not on the allowed screen.
			if ( ! BSF_Analytics_Helper::is_allowed_screen() ) {
				return;
			}

			?>
			<div id="<?php echo esc_attr( $id ); ?>" class="uds-feedback-form--wrapper" style="display: none">
				<div class="uds-feedback-form--container">
					<div class="uds-form-header--wrapper">
						<div class="uds-form-title--icon-wrapper">
							<?php if ( ! empty( $args['popup_logo'] ) ) { ?>
								<img class="uds-icon" src="<?php echo esc_url( $args['popup_logo'] ); ?>" title="<?php echo esc_attr( $args['plugin_slug'] ); ?> <?php echo esc_attr( __( 'Icon' ) ); ?>" />
							<?php } ?>
							<h2 class="uds-title"><?php echo esc_html( $args['popup_title'] ); ?></h2>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="uds-close">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
						</svg>
					</div>
					<div class="uds-form-body--content">

						<?php if ( ! empty( $args['popup_description'] ) ) { ?>
							<p class="uds-form-description"><?php echo esc_html( $args['popup_description'] ); ?></p>
						<?php } ?>

						<form class="uds-feedback-form" id="uds-feedback-form" method="post">
							<?php foreach ( $args['popup_reasons'] as $key => $value ) { ?>
								<fieldset>
									<div class="reason">
										<input type="radio" class="uds-reason-input" name="uds_reason_input" id="uds_reason_input_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" data-placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>" data-show_cta="<?php echo esc_attr( $value['show_cta'] ); ?>" data-accept_feedback="<?php echo esc_attr( $value['accept_feedback'] ); ?>">
										<label class="uds-reason-label" for="uds_reason_input_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value['label'] ); ?></label>
									</div>
								</fieldset>
							<?php } ?>

							<fieldset>
								<textarea class="uds-options-feedback hide" id="uds-options-feedback" rows="3" name="uds_options_feedback" placeholder="<?php echo esc_attr( __( 'Please tell us more details.' ) ); ?>"></textarea>
								<?php
								if ( ! empty( $args['support_url'] ) ) {
									?>
										<p class="uds-option-feedback-cta hide">
											<?php
											echo wp_kses_post(
												sprintf(
												/* translators: %1$s: link html start, %2$s: link html end*/
													__( 'Need help from our experts? %1$sClick here to contact us.%2$s' ),
													'<a href="' . esc_url( $args['support_url'] ) . '" target="_blank">',
													'</a>'
												)
											);
											?>
										</p>
								<?php } ?>
							</fieldset>

							<div class="uds-feedback-form-sumbit--actions">
							<button class="button button-primary uds-feedback-submit" data-action="submit"><?php esc_html_e( 'Submit & Deactivate' ); ?></button>
							<button class="button button-secondary uds-feedback-skip" data-action="skip"><?php esc_html_e( 'Skip & Deactivate' ); ?></button>
								<input type="hidden" name="referer" value="<?php echo esc_url( get_site_url() ); ?>">
								<input type="hidden" name="version" value="<?php echo esc_attr( $args['plugin_version'] ); ?>">
								<input type="hidden" name="source" value="<?php echo esc_attr( $args['plugin_slug'] ); ?>">
							</div>
						</form>
					</div>

				</div>
			</div>
			<?php
		}

		/**
		 * Load form styles.
		 *
		 * This function loads the necessary styles for the feedback form.
		 *
		 * @since 1.1.6
		 * @return void
		 */
		public static function load_form_styles() {

			if ( ! BSF_Analytics_Helper::is_allowed_screen() ) {
				return;
			}

			$dir_path = BSF_ANALYTICS_URI . '/modules/deactivation-survey/';
			$file_ext = ! SCRIPT_DEBUG ? '.min' : '';

			wp_enqueue_script(
				'uds-feedback-script',
				$dir_path . 'assets/js/feedback' . $file_ext . '.js',
				array( 'jquery' ),
				BSF_ANALYTICS_VERSION,
				true
			);

			$data = apply_filters(
				'uds_survey_vars',
				array(
					'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
					'_ajax_nonce'    => wp_create_nonce( 'uds_plugin_deactivate_feedback' ),
					'_current_theme' => function_exists( 'wp_get_theme' ) ? wp_get_theme()->get_template() : '',
					'_plugin_slug'   => array(),
				)
			);

			// Add localize JS.
			wp_localize_script(
				'uds-feedback-script',
				'udsVars',
				$data
			);

			wp_enqueue_style( 'uds-feedback-style', $dir_path . 'assets/css/feedback' . $file_ext . '.css', array(), BSF_ANALYTICS_VERSION );
			wp_style_add_data( 'uds-feedback-style', 'rtl', 'replace' );
		}

		/**
		 * Sends plugin deactivation feedback to the server.
		 *
		 * This function checks the user's permission and verifies the nonce for the request.
		 * If the checks pass, it sends the feedback data to the server for processing.
		 *
		 * @return void
		 */
		public function send_plugin_deactivate_feedback() {

			$response_data = array( 'message' => __( 'Sorry, you are not allowed to do this operation.' ) );

			/**
			 * Check permission
			 */
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( $response_data );
			}

			/**
			 * Nonce verification
			 */
			if ( ! check_ajax_referer( 'uds_plugin_deactivate_feedback', 'security', false ) ) {
				$response_data = array( 'message' => __( 'Nonce validation failed' ) );
				wp_send_json_error( $response_data );
			}

			$feedback_data = array(
				'reason'      => isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '',
				'feedback'    => isset( $_POST['feedback'] ) ? sanitize_text_field( wp_unslash( $_POST['feedback'] ) ) : '',
				'domain_name' => isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '',
				'version'     => isset( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : '',
				'plugin'      => isset( $_POST['source'] ) ? sanitize_text_field( wp_unslash( $_POST['source'] ) ) : '',
			);

			$api_args = array(
				'body'    => wp_json_encode( $feedback_data ),
				'headers' => BSF_Analytics_Helper::get_api_headers(),
				'timeout' => 90, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			);

			$target_url = BSF_Analytics_Helper::get_api_url() . self::$feedback_api_endpoint;

			$response = wp_safe_remote_post( $target_url, $api_args );

			$has_errors = BSF_Analytics_Helper::is_api_error( $response );

			if ( $has_errors['error'] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $has_errors['error_message'],
					)
				);
			}

			wp_send_json_success();
		}

		/**
		 * Get the array of default reasons.
		 *
		 * @return array Default reasons.
		 */
		public static function get_default_reasons() {

			return apply_filters(
				'uds_default_deactivation_reasons',
				array(
					'temporary_deactivation' => array(
						'label'           => esc_html__( 'This is a temporary deactivation for testing.' ),
						'placeholder'     => esc_html__( 'How can we assist you?' ),
						'show_cta'        => 'false',
						'accept_feedback' => 'false',
					),
					'plugin_not_working'     => array(
						'label'           => esc_html__( 'The plugin isn\'t working properly.' ),
						'placeholder'     => esc_html__( 'Please tell us more about what went wrong?' ),
						'show_cta'        => 'true',
						'accept_feedback' => 'true',
					),
					'found_better_plugin'    => array(
						'label'           => esc_html__( 'I found a better alternative plugin.' ),
						'placeholder'     => esc_html__( 'Could you please specify which plugin?' ),
						'show_cta'        => 'false',
						'accept_feedback' => 'true',
					),
					'missing_a_feature'      => array(
						'label'           => esc_html__( 'It\'s missing a specific feature.' ),
						'placeholder'     => esc_html__( 'Please tell us more about the feature.' ),
						'show_cta'        => 'false',
						'accept_feedback' => 'true',
					),
					'other'                  => array(
						'label'           => esc_html__( 'Other' ),
						'placeholder'     => esc_html__( 'Please tell us more details.' ),
						'show_cta'        => 'false',
						'accept_feedback' => 'true',
					),
				)
			);
		}
	}

	Deactivation_Survey_Feedback::get_instance();
}
