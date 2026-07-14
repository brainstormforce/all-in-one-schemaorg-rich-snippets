<?php
/**
 * BSF Analytics Events — reusable one-time milestone tracking.
 *
 * Tracks events temporarily, sends them once via BSF Analytics,
 * then cleans up. Only a minimal dedup flag remains.
 *
 * @package bsf-analytics
 * @since 1.1.21
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Analytics_Events' ) ) {

	/**
	 * BSF Analytics Events Class.
	 *
	 * @since 1.1.21
	 */
	class BSF_Analytics_Events {

		/**
		 * Plugin slug used as option key prefix in default storage.
		 *
		 * @var string
		 */
		private $slug;

		/**
		 * Option resolver callbacks.
		 *
		 * @var array{get: callable|null, update: callable|null}
		 */
		private $option_resolver;

		/**
		 * Constructor.
		 *
		 * @param string $slug            Plugin slug (e.g. 'sureforms', 'astra').
		 * @param array  $option_resolver Optional. Custom callbacks for option storage.
		 *                                 'get'    => callable( $key, $default ) — retrieve an option.
		 *                                 'update' => callable( $key, $value )   — persist an option.
		 *                                 When omitted, uses get_option( '{slug}_{key}' ) / update_option( '{slug}_{key}' ).
		 * @since 1.1.21
		 */
		public function __construct( $slug, $option_resolver = array() ) {
			$this->slug            = sanitize_key( $slug );
			$this->option_resolver = wp_parse_args(
				$option_resolver,
				array(
					'get'    => null,
					'update' => null,
				)
			);
		}

		/**
		 * Track an event. By default, skips if already tracked or pending (one-time semantics).
		 * When $force is true, the event is treated as retrackable — bypasses the post-send
		 * dedup check and overwrites any pending entry with the same name. Useful for
		 * recurring events like `plugin_updated` where the latest value should always win.
		 * Only stores temporary data — cleaned up after analytics send.
		 *
		 * @param string               $event_name  Event identifier.
		 * @param string               $event_value Primary value (version, form ID, mode, etc.).
		 * @param array<string, mixed> $properties  Additional context as key-value pairs. Values are stored as-is — sanitization is the caller's responsibility.
		 * @param bool                 $force       When true, bypass pushed dedup and overwrite pending entry. Default false.
		 * @since 1.1.21
		 * @since 1.1.25 Added the $force parameter.
		 * @return void
		 */
		public function track( $event_name, $event_value = '', $properties = array(), $force = false ) {
			// Sanitize inputs once upfront — ensures dedup comparisons match stored values.
			$event_name  = sanitize_text_field( $event_name );
			$event_value = sanitize_text_field( (string) $event_value );
			$properties  = is_array( $properties ) ? $properties : array();
			$force       = (bool) $force;

			// Check dedup flag — already sent in a previous cycle.
			// Force bypasses this check; pushed list will be refreshed on next flush_pending().
			if ( ! $force ) {
				$pushed = $this->get_option( 'usage_events_pushed', array() );
				$pushed = is_array( $pushed ) ? $pushed : array();
				if ( in_array( $event_name, $pushed, true ) ) {
					return;
				}
			}

			// Check if already queued in current cycle.
			$pending = $this->get_option( 'usage_events_pending', array() );
			$pending = is_array( $pending ) ? $pending : array();

			$new_event = array(
				'event_name'  => $event_name,
				'event_value' => $event_value,
				'properties'  => $properties,
				'date'        => current_time( 'mysql' ),
			);

			if ( ! $force ) {
				// Default path: cheap membership check — no need to locate the key.
				if ( in_array( $event_name, array_column( $pending, 'event_name' ), true ) ) {
					return;
				}
				$pending[] = $new_event;
			} else {
				// Force path: locate any existing entry by actual key to overwrite safely.
				$existing_key = null;
				foreach ( $pending as $key => $entry ) {
					if ( isset( $entry['event_name'] ) && $entry['event_name'] === $event_name ) {
						$existing_key = $key;
						break;
					}
				}

				if ( null !== $existing_key ) {
					// Skip the write when nothing material changed (only `date` would differ).
					$existing = $pending[ $existing_key ];
					if ( array_key_exists( 'event_value', $existing )
						&& array_key_exists( 'properties', $existing )
						&& $existing['event_value'] === $new_event['event_value']
						&& $existing['properties'] === $new_event['properties'] ) {
						return;
					}
					$pending[ $existing_key ] = $new_event;
				} else {
					$pending[] = $new_event;
				}
			}

			$this->update_option( 'usage_events_pending', $pending );
		}

		/**
		 * Flush pending events: returns them for the payload, then cleans up.
		 *
		 * After this call:
		 * - usage_events_pending is EMPTY (full event data deleted).
		 * - usage_events_pushed has event_name strings added (minimal dedup).
		 *
		 * @since 1.1.21
		 * @return array Pending events to include in payload. Empty if none.
		 */
		public function flush_pending() {
			$pending = $this->get_option( 'usage_events_pending', array() );
			if ( empty( $pending ) || ! is_array( $pending ) ) {
				return array();
			}

			// Add event names to dedup flag (minimal — just strings).
			$pushed = $this->get_option( 'usage_events_pushed', array() );
			$pushed = is_array( $pushed ) ? $pushed : array();
			$pushed = array_unique(
				array_merge( $pushed, array_column( $pending, 'event_name' ) )
			);
			$this->update_option( 'usage_events_pushed', $pushed );

			// DELETE all temporary event data.
			$this->update_option( 'usage_events_pending', array() );

			return $pending;
		}

		/**
		 * Remove specific event names from the pushed dedup flag, allowing them to be re-tracked.
		 *
		 * Pass an array of event names to remove only those entries.
		 * Pass an empty array (or omit) to clear all pushed events.
		 *
		 * @param array<string> $event_names Event names to remove. Empty = clear all.
		 * @since 1.1.21
		 * @return void
		 */
		public function flush_pushed( $event_names = array() ) {
			$pushed = $this->get_option( 'usage_events_pushed', array() );
			$pushed = is_array( $pushed ) ? $pushed : array();

			if ( empty( $event_names ) ) {
				$this->update_option( 'usage_events_pushed', array() );
				return;
			}

			$pushed = array_values( array_diff( $pushed, $event_names ) );
			$this->update_option( 'usage_events_pushed', $pushed );
		}

		/**
		 * Check if an event has already been tracked (sent or pending).
		 *
		 * @param string $event_name Event identifier.
		 * @since 1.1.21
		 * @return bool
		 */
		public function is_tracked( $event_name ) {
			$pushed = $this->get_option( 'usage_events_pushed', array() );
			$pushed = is_array( $pushed ) ? $pushed : array();
			if ( in_array( $event_name, $pushed, true ) ) {
				return true;
			}

			$pending = $this->get_option( 'usage_events_pending', array() );
			$pending = is_array( $pending ) ? $pending : array();
			return in_array( $event_name, array_column( $pending, 'event_name' ), true );
		}

		/**
		 * Get an option value using custom resolver or default WordPress option.
		 *
		 * @param string $key     Option key (e.g. 'usage_events_pending').
		 * @param mixed  $default Default value.
		 * @return mixed
		 */
		private function get_option( $key, $default = null ) {
			if ( is_callable( $this->option_resolver['get'] ) ) {
				return call_user_func( $this->option_resolver['get'], $key, $default );
			}
			return get_option( $this->slug . '_' . $key, $default );
		}

		/**
		 * Update an option value using custom resolver or default WordPress option.
		 *
		 * @param string $key   Option key (e.g. 'usage_events_pending').
		 * @param mixed  $value Value to store.
		 * @return void
		 */
		private function update_option( $key, $value ) {
			if ( is_callable( $this->option_resolver['update'] ) ) {
				call_user_func( $this->option_resolver['update'], $key, $value );
				return;
			}
			update_option( $this->slug . '_' . $key, $value );
		}
	}
}
