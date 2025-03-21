<?php
/**
 * BSF analytics Helper Class File.
 *
 * @package bsf-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Analytics_Helper' ) ) {
	/**
	 * BSF analytics stat class.
	 */
	class BSF_Analytics_Helper {

		/**
		 * Check is error in the received response.
		 *
		 * @param object $response Received API Response.
		 * @return array $result Error result.
		 */
		public static function is_api_error( $response ) {

			$result = array(
				'error'         => false,
				'error_message' => __( 'Oops! Something went wrong. Please refresh the page and try again.' ),
				'error_code'    => 0,
			);

			if ( is_wp_error( $response ) ) {
				$result['error']         = true;
				$result['error_message'] = $response->get_error_message();
				$result['error_code']    = $response->get_error_code();
			} elseif ( ! empty( wp_remote_retrieve_response_code( $response ) ) && ! in_array( wp_remote_retrieve_response_code( $response ), array( 200, 201, 204 ), true ) ) {
				$result['error']         = true;
				$result['error_message'] = wp_remote_retrieve_response_message( $response );
				$result['error_code']    = wp_remote_retrieve_response_code( $response );
			}

			return $result;
		}

		/**
		 * Get API headers
		 *
		 * @since 1.1.6
		 * @return array<string, string>
		 */
		public static function get_api_headers() {
			return array(
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			);
		}

        /**
		 * Get API URL for sending analytics.
		 *
		 * @return string API URL.
		 * @since 1.0.0
		 */
		public static function get_api_url() {
			return defined( 'BSF_ANALYTICS_API_BASE_URL' ) ? BSF_ANALYTICS_API_BASE_URL : 'https://analytics.brainstormforce.com/';
		}
        
        /**
		 * Check if the current screen is allowed for the survey.
		 *
		 * This function checks if the current screen is one of the allowed screens for displaying the survey.
		 * It uses the `get_current_screen` function to get the current screen information and compares it with the list of allowed screens.
		 *
		 * @since 1.1.6
		 * @return bool True if the current screen is allowed, false otherwise.
		 */
		public static function is_allowed_screen() {

			// This filter allows to dynamically modify the list of allowed screens for the survey.
			$allowed_screens = apply_filters( 'uds_survey_allowed_screens', array( 'plugins' ) );

			$current_screen = get_current_screen();

			// Check if $current_screen is a valid object before accessing its properties.
			if ( ! is_object( $current_screen ) ) {
				return false; // Return false if current screen is not valid.
			}

			$screen_id = $current_screen->id;

			if ( ! empty( $screen_id ) && in_array( $screen_id, $allowed_screens, true ) ) {
				return true;
			}

			return false;
		}
	}
}