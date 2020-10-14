<?php
/**
 * Plugin Name: Schema - All In One Schema Rich Snippets
 * Plugin URI: https://www.brainstormforce.com
 * Author: Brainstorm Force
 * Author URI: https://www.brainstormforce.com
 * Description: Welcome to the Schema - All In One Schema Rich Snippets! You can now easily add schema markup on various * pages and posts of your website. Implement schema types such as Review, Events, Recipes, Article, Products, Services * *etc.
 * Version: 1.6.4
 * Text Domain: rich-snippets
 * License: GPL2
 *
 * @package AIOSRS.
 * */

/*
Copyright 2013 Schema - All In One Schema Rich Snippets (email : info@bsf.io)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! class_exists( 'RichSnippets' ) ) {
	/**
	 * RichSnippets
	 */
	class RichSnippets {
		/**
		 * Initiator
		 */
		public function __construct() {
			// Constructor.
			register_activation_hook( __FILE__, array( $this, 'register_bsf_settings' ) );
			add_action( 'admin_init', array( $this, 'aiosrs_admin_redirect' ) );
			add_action( 'admin_head', array( $this, 'star_icons' ) );
			$this->define_constants();
			// Add Admin Menu.
			add_action( 'admin_menu', array( $this, 'register_custom_menu_page' ) );
			add_action( 'admin_init', array( $this, 'set_styles' ) );

			add_action( 'admin_init', array( $this, 'bsf_color_scripts' ) );

			add_filter( 'plugins_loaded', array( $this, 'rich_snippet_translation' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'post_enqueue' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'post_new_enqueue' ) );
			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", array( $this, 'bsf_settings_link' ) );
			add_action( 'wp_ajax_bsf_submit_request', array( $this, 'submit_request' ) );

			add_action( 'wp_ajax_bsf_submit_color', array( $this, 'submit_color' ) );
			// Admin bar menu.
			add_action( 'admin_bar_menu', array( $this, 'aiosrs_admin_bar' ), 100 );
		}

		/**
		 * Defines all constants
		 */
		public function define_constants() {
			define( 'AIOSRS_PRO_FILE', __FILE__ );
			define( 'AIOSRS_PRO_BASE', plugin_basename( AIOSRS_PRO_FILE ) );
			define( 'AIOSRS_PRO_DIR', plugin_dir_path( AIOSRS_PRO_FILE ) );
			define( 'AIOSRS_PRO_URI', plugins_url( '/', AIOSRS_PRO_FILE ) );
			define( 'AIOSRS_PRO_VER', '1.6.4' );
		}

		/**
		 * Admin bar menu.
		 */
		public function aiosrs_admin_bar() {
			global $wp_admin_bar;
			$actual_link = esc_url( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" );
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			if ( ! is_admin() ) {
				$wp_admin_bar->add_menu(
					array(
						'id'    => 'aiosrs',
						'title' => 'Test Rich Snippets',
						'href'  => 'https://search.google.com/structured-data/testing-tool#url=' . $actual_link,
						'meta'  => array( 'target' => '_blank' ),
					)
				);
			}
		}
		/**
		 * Register_custom_menu_page.
		 */
		public function register_custom_menu_page() {
			require_once plugin_dir_path( __FILE__ ) . 'admin/index.php';
			$page = add_menu_page( 'All in One Rich Snippets Dashboard', 'Rich Snippets', 'administrator', 'rich_snippet_dashboard', 'rich_snippet_dashboard', 'div' );
			// Call the function to print the stylesheets and javascripts in only this plugins admin area.
			add_action( 'admin_print_styles-' . $page, 'bsf_admin_styles' );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'iris_enqueue_scripts' ) );
		}
		/**
		 * Add settings link on plugin page.
		 *
		 * @param string $links Links.
		 */
		public function bsf_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=rich_snippet_dashboard">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
		/**
		 *  Print the star rating style on post edit page.
		 *
		 * @param string $hook Hook.
		 */
		public function post_enqueue( $hook ) {
			if ( 'post.php' != $hook ) {
				return;
			}
			$current_admin_screen     = get_current_screen();
			$exclude_custom_post_type = apply_filters( 'bsf_exclude_custom_post_type', array() );
			if ( in_array( $current_admin_screen->post_type, $exclude_custom_post_type ) ) {
				return;
			}
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'bsf_jquery_star' );
			wp_enqueue_script( 'bsf_toggle' );
			wp_enqueue_style( 'star_style' );
			wp_register_script( 'bsf-scripts', BSF_META_BOX_URL . 'js/cmb.js', '', '0.9.1' );
			wp_enqueue_script( 'bsf-scripts' );
			wp_enqueue_media();
			wp_register_script( 'bsf-scripts-media', BSF_META_BOX_URL . 'js/media.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'bsf-scripts-media' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			if ( ! function_exists( 'vc_map' ) ) {
				wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', null, '1.0' );
			}
		}
		/**
		 *  Post_new_enqueue.
		 *
		 * @param string $hook Hook.
		 */
		public function post_new_enqueue( $hook ) {
			if ( 'post-new.php' != $hook ) {
				return;
			}
			$current_admin_screen     = get_current_screen();
			$exclude_custom_post_type = apply_filters( 'bsf_exclude_custom_post_type', array() );
			if ( in_array( $current_admin_screen->post_type, $exclude_custom_post_type ) ) {
				return;
			}
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'bsf_jquery_star' );
			wp_enqueue_script( 'bsf_toggle' );
			wp_enqueue_style( 'star_style' );
			wp_register_script( 'bsf-scripts', BSF_META_BOX_URL . 'js/cmb.js', '', '0.9.1' );
			wp_enqueue_script( 'bsf-scripts' );
			wp_enqueue_media();
			wp_register_script( 'bsf-scripts-media', BSF_META_BOX_URL . 'js/media.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'bsf-scripts-media' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			if ( ! function_exists( 'vc_map' ) ) {
				wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', null, 1.0 );
			}
		}
		/**
		 * Initialize the metabox class.
		 */
		public function wp_initialize_bsf_meta_boxes() {
			if ( ! class_exists( 'Bsf_Meta_Box' ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'init.php';
			}
		}
		/**
		 * Set_styles.
		 */
		public function set_styles() {
			wp_register_style( 'star_style', plugins_url( '/css/jquery.rating.css', __FILE__ ), null, '1.0' );
			wp_register_style( 'meta_style', plugins_url( 'admin/css/style.css', __FILE__ ), null, '1.0' );

			wp_register_style( 'admin_style', plugins_url( 'admin/css/admin.css', __FILE__ ), null, '1.0' );
			wp_register_script( 'bsf_jquery_star', plugins_url( '/js/jquery.rating.min.js', __FILE__ ), null, '1.0' );
			wp_register_script( 'bsf_toggle', plugins_url( '/js/toggle.js', __FILE__ ), null, '1.0' );
		}
		/**
		 * Define icon styles for the custom post type.
		 */
		public function star_icons() {
			?>
		<style>
			#toplevel_page_rich_snippet_dashboard .wp-menu-image {
				background: url(<?php echo esc_url( plugins_url( '/images/star.png', __FILE__ ) ); ?>) no-repeat !important;
			}
			#toplevel_page_rich_snippet_dashboard:hover .wp-menu-image, #toplevel_page_rich_snippet_dashboard.wp-has-current-submenu .wp-menu-image {
				background: url(<?php echo esc_url( plugins_url( '/images/star.png', __FILE__ ) ); ?>) no-repeat 0 -32px !important;
			}
			#toplevel_page_rich_snippet_dashboard .current .wp-menu-image, #toplevel_page_rich_snippet_dashboard.wp-has-current-submenu .wp-menu-image {
				background: url(<?php echo esc_url( plugins_url( '/images/star.png', __FILE__ ) ); ?>) no-repeat 0 -32px !important;
			}
			#star-icons-32.icon32 {background: url(<?php echo esc_url( plugins_url( '/images/gray-32.png', __FILE__ ) ); ?>) no-repeat;}
		</style>
		<?php }
		/**
		 * Translation.
		 */
		public function rich_snippet_translation() {
			// Load Translation File.
			load_plugin_textdomain( 'rich-snippets', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}
		/**
		 * Register_bsf_settings.
		 */
		public function register_bsf_settings() {
			require_once plugin_dir_path( __FILE__ ) . 'settings.php';
			add_woo_commerce_option();
			add_review_option();
			add_event_option();
			add_person_option();
			add_product_option();
			add_recipe_option();
			add_software_option();
			add_video_option();
			add_article_option();
			add_service_option();
			add_color_option();
			add_option( 'aisrs_do_activation_redirect', true );

		}
		/**
		 * Aiosrs_admin_redirect.
		 */
		public function aiosrs_admin_redirect() {
			$main_url = esc_url( admin_url() );
			if ( get_option( 'aisrs_do_activation_redirect', false ) ) {
				delete_option( 'aisrs_do_activation_redirect' );
				wp_safe_redirect( $main_url . '/admin.php?page=rich_snippet_dashboard#tab-5' );
			}
		}
		/**
		 * Submit_request.
		 */
		public function submit_request() {
			$to       = 'Brainstorm Force <support@bsf.io>';
			$from     = '';
			$site     = '';
			$sub      = '';
			$message  = '';
			$post_url = '';
			$name     = '';
			if ( isset( $_POST['aiosrs_support_form_nonce'] ) && wp_verify_nonce( $_POST['aiosrs_support_form_nonce'], 'aiosrs_support_form' ) ) {

				$from     = sanitize_email( $_POST['email'] );
				$site     = esc_url( $_POST['site_url'] );
				$sub      = sanitize_text_field( $_POST['subject'] );
				$message  = esc_html( $_POST['message'] );
				$name     = sanitize_text_field( $_POST['name'] );
				$post_url = esc_url( $_POST['post_url'] );
			}

			if ( 'question' == $sub ) {
				$subject = '[AIOSRS] New question received from ' . $name;
			} elseif ( 'bug' == $sub ) {
				$subject = '[AIOSRS] New bug found by ' . $name;
			} elseif ( 'help' == $sub ) {
				$subject = '[AIOSRS] New help request received from ' . $name;
			} elseif ( 'professional' == $sub ) {
				$subject = '[AIOSRS] New service quote request received from ' . $name;
			} elseif ( 'contribute' == $sub ) {
				$subject = '[AIOSRS] New development contribution request by ' . $name;
			} elseif ( 'other' == $sub ) {
				$subject = '[AIOSRS] New contact request received from ' . $name;
			}

			$html     = '
			<html>
				<head>
				  <title>All in One Schema.org Rich Snippets</title>
				</head>
				<body>
					<table width="100%" cellpadding="10" cellspacing="10">
						<tr>
							<th colspan="2"> All in One Schema.org Rich Snippets Support</th>
						</tr>
						<tr>
							<td width="22%"> Name : </td>
							<td width="78%"> <strong>' . $name . ' </strong></td>
						</tr>
						<tr>
							<td> Email : </td>
							<td> <strong>' . $from . ' </strong></td>
						</tr>
						<tr>
							<td> Website : </td>
							<td> <strong>' . $site . ' </strong></td>
						</tr>
						<tr>
							<td> Ref. Post URL : </td>
							<td> <strong>' . $post_url . ' </strong></td>
						</tr>
						<tr>
							<td colspan="2"> Message : </td>
                        </tr>
                        <tr>
							<td colspan="2"> ' . $message . ' </td>
						</tr>
					</table>
				</body>
			</html>
			';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:' . $name . '<' . $from . '>' . "\r\n";
			echo mail( $to, $subject, $html, $headers ) ? esc_html_e( 'Thank you!', 'all-in-one-schemaorg-rich-snippets' ) : esc_html_e( 'Something went wrong!', 'all-in-one-schemaorg-rich-snippets' );

			die();
		}
		/**
		 * Submit_color.
		 */
		public function submit_color() {
			if ( ! current_user_can( 'manage_options' ) ) {
				// return if current user is not allowed to manage options.
				return;
			} else {
				if ( ! isset( $_POST['snippet_color_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_color_nonce_field'], 'snippet_color_form_action' )
				) {
					print esc_attr( 'Sorry, your nonce did not verify.' );
					exit;
				} else {
					$snippet_box_bg   = sanitize_text_field( $_POST['snippet_box_bg'] );
					$snippet_title_bg = sanitize_text_field( $_POST['snippet_title_bg'] );
					$border_color     = sanitize_text_field( $_POST['snippet_border'] );
					$title_color      = sanitize_text_field( $_POST['snippet_title_color'] );
					$box_color        = sanitize_text_field( $_POST['snippet_box_color'] );
					$color_opt        = array(
						'snippet_box_bg'      => $snippet_box_bg,
						'snippet_title_bg'    => $snippet_title_bg,
						'snippet_border'      => $border_color,
						'snippet_title_color' => $title_color,
						'snippet_box_color'   => $box_color,
					);
					echo update_option( 'bsf_custom', $color_opt ) ? esc_html_e( 'Settings saved !', 'all-in-one-schemaorg-rich-snippets' ) : esc_html_e( 'Error occured. Settings were not saved !', 'all-in-one-schemaorg-rich-snippets' );

					die();
				}
			}
		}
		/**
		 * Iris_enqueue_scripts.
		 */
		public function iris_enqueue_scripts() {
			wp_enqueue_script( 'wp-color-picker' );
				// load the minified version of custom script.
				wp_enqueue_script( 'cp_custom', plugins_url( 'js/cp-script.min.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '1.1', true );
				wp_enqueue_style( 'wp-color-picker' );
		}
		/**
		 * Bsf_color_scripts.
		 */
		public function bsf_color_scripts() {
			global $wp_version;
			$bsf_script_array = array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'media-upload', 'thickbox' );

			// styles required for cmb.
			$bsf_style_array = array( 'thickbox' );

			// if we're 3.5 or later, user wp-color-picker.
			if ( 3.5 <= $wp_version ) {

				$bsf_script_array[] = 'wp-color-picker';
				$bsf_style_array[]  = 'wp-color-picker';

			} else {

				// otherwise use the older 'farbtastic'.
				$bsf_script_array[] = 'farbtastic';
				$bsf_style_array[]  = 'farbtastic';

			}
		}
	}
}
	require_once plugin_dir_path( __FILE__ ) . 'functions.php';
if ( is_admin() ) {
	// Load Astra Notices library.
	require_once plugin_dir_path( __FILE__ ) . '/lib/notices/class-astra-notices.php';
}
			// BSF Analytics library.
if ( ! class_exists( 'BSF_Analytics_Loader' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/bsf-analytics/class-bsf-analytics-loader.php';
}

			$bsf_analytics = BSF_Analytics_Loader::get_instance();

			$bsf_analytics->set_entity(
				array(
					'bsf' => array(
						'product_name'    => 'All In One Schema Rich Snippets',
						'path'            => plugin_dir_path( __FILE__ ) . 'admin/bsf-analytics',
						'author'          => 'Brainstorm Force',
						'time_to_display' => '+24 hours',
					),
				)
			);
			add_filter( 'bsf_meta_boxes', 'bsf_metaboxes' );
			// Instantiating the Class.
			if ( class_exists( 'RichSnippets' ) ) {
				$richsnippets = new RichSnippets();
			}
			?>
