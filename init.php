<?php
/**
 * Init.
 *
 * @package Init.
 */

$meta_boxes = array();
$meta_boxes = apply_filters( 'bsf_meta_boxes', $meta_boxes );
foreach ( $meta_boxes as $meta_box ) {
	$my_box = new Bsf_Meta_Box( $meta_box );
}
/**
 * Validate value of meta fields.
 * Define ALL validation methods inside this class and use the names of these.
 * methods in the definition of meta boxes (key 'validate_func' of each field).
 */
/**
 * Bsf_Meta_Box_Validate
 */
class Bsf_Meta_Box_Validate {
	/**
	 * Check_text.
	 *
	 * @param string $text Text.
	 */
	public function check_text( $text ) {
		if ( 'hello' != $text ) {
			return false;
		}
		return true;
	}
}
/**
 * Defines the url to which is used to load local resources.
 * This may need to be filtered for local Window installations.
 * If resources do not load, please check the wiki for details.
 */
if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
	// winblows.
	define( 'BSF_META_BOX_URL', trailingslashit( str_replace( DIRECTORY_SEPARATOR, '/', str_replace( str_replace( '/', DIRECTORY_SEPARATOR, WP_CONTENT_DIR ), WP_CONTENT_URL, dirname( __FILE__ ) ) ) ) );
} else {
	define( 'BSF_META_BOX_URL', apply_filters( 'bsf_meta_box_url', trailingslashit( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ) ) );
}
/**
 * Create meta boxes.
 */
class Bsf_Meta_Box {
 //phpcs:ignore:Generic.Files.OneObjectStructurePerFile.MultipleFound
	/**
	 * Meta Box.
	 *
	 * @var $_meta_box.
	 */
	protected $_meta_box; //phpcs:ignore:PSR2.Classes.PropertyDeclaration.Underscore
	/**
	 * Initiator.
	 *
	 * @param string $meta_box Meta Box.
	 */
	public function __construct( $meta_box ) {
		if ( ! is_admin() ) {
			return;
		}
		$this->_meta_box = $meta_box;
		$upload          = false;
		foreach ( $meta_box['fields'] as $field ) {
			if ( 'file' == $field['type'] || 'file_list' == $field['type'] ) {
				$upload = true;
				break;
			}
		}
		global $pagenow;
		if ( $upload && in_array( $pagenow, array( 'page.php', 'page-new.php', 'post.php', 'post-new.php' ), true ) ) {
			add_action( 'admin_head', array( &$this, 'add_post_enctype' ) );
		}
		add_action( 'admin_menu', array( &$this, 'add' ) );
		add_action( 'save_post', array( &$this, 'save' ) );
		add_filter( 'bsf_show_on', array( &$this, 'add_for_id' ), 10, 2 );
		add_filter( 'bsf_show_on', array( &$this, 'add_for_page_template' ), 10, 2 );
	}
	/**
	 * Add_post_enctype.
	 */
	public function add_post_enctype() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#post").attr("enctype", "multipart/form-data");
			jQuery("#post").attr("encoding", "multipart/form-data");
		});
		</script>';
	}
	/**
	 * Add metaboxes.
	 */
	public function add() {
		$this->_meta_box['context']  = empty( $this->_meta_box['context'] ) ? 'normal' : $this->_meta_box['context'];
		$this->_meta_box['priority'] = empty( $this->_meta_box['priority'] ) ? 'high' : $this->_meta_box['priority'];
		$this->_meta_box['show_on']  = empty( $this->_meta_box['show_on'] ) ? array(
			'key'   => false,
			'value' => false,
		) : $this->_meta_box['show_on'];
		foreach ( $this->_meta_box['pages'] as $page ) {
			if ( apply_filters( 'bsf_show_on', true, $this->_meta_box ) ) {
				add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array( &$this, 'show' ), $page, $this->_meta_box['context'], $this->_meta_box['priority'] );
			}
		}
	}
	/**
	 * Show On Filters
	 * Use the 'bsf_show_on' filter to further refine the conditions under which a metabox is displayed.
	 * Below you can limit it by ID and page template.
	 *
	 * @param string $display Display.
	 * @param string $meta_box Meta Box.
	 */
	public function add_for_id( $display, $meta_box ) {
		if ( 'id' !== $meta_box['show_on']['key'] ) {
			return $display;
		}
		// If we're showing it based on ID, get the current ID.
		if ( isset( $_GET['post'] ) ) { //phpcs:ignore:WordPress.Security.NonceVerification.Recommended
			$post_id = sanitize_text_field( $_GET['post'] ); //phpcs:ignore:WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_POST['post_ID'] ) ) { //phpcs:ignore:WordPress.Security.NonceVerification.Missing
			$post_id = sanitize_text_field( $_POST['post_ID'] ); //phpcs:ignore:WordPress.Security.NonceVerification.Missing
		}
		if ( ! isset( $post_id ) ) {
			return false;
		}
		// If value isn't an array, turn it into one.
		$meta_box['show_on']['value'] = ! is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
		// If current page id is in the included array, display the metabox.
		if ( in_array( $post_id, $meta_box['show_on']['value'] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add for Page Template.
	 *
	 * @param string $display Display.
	 * @param string $meta_box Meta Box.
	 */
	public function add_for_page_template( $display, $meta_box ) {
		if ( 'page-template' !== $meta_box['show_on']['key'] ) {
			return $display;
		}
		// Get the current ID.
		if ( isset( $_GET['post'] ) ) { //phpcs:ignore:WordPress.Security.NonceVerification.Recommended
			$post_id = esc_attr( $_GET['post'] ); //phpcs:ignore:WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_POST['post_ID'] ) ) { //phpcs:ignore:WordPress.Security.NonceVerification.Missing
			$post_id = esc_attr( $_POST['post_ID'] ); //phpcs:ignore:WordPress.Security.NonceVerification.Missing
		}
		if ( ! ( isset( $post_id ) || is_page() ) ) {
			return false;
		}
		// Get current template.
		$current_template = get_post_meta( $post_id, '_wp_page_template', true );
		// If value isn't an array, turn it into one.
		$meta_box['show_on']['value'] = ! is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
		// See if there's a match.
		if ( in_array( $current_template, $meta_box['show_on']['value'] ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Show fields.
	 */
	public function show() {
		global $post;
		// Use nonce for verification.
		echo '<input type="hidden" name="wp_meta_box_nonce" value="', esc_attr( wp_create_nonce( basename( __FILE__ ) ) ), '" />';
		echo '<table class="form-table bsf_metabox">';
		foreach ( $this->_meta_box['fields'] as $field ) {
			// Set up blank or default values for empty ones.
			if ( ! isset( $field['name'] ) ) {
				$field['name'] = '';
			}
			if ( ! isset( $field['desc'] ) ) {
				$field['desc'] = '';
			}
			if ( ! isset( $field['std'] ) ) {
				$field['std'] = '';
			}
			if ( 'file' == $field['type'] && ! isset( $field['allow'] ) ) {
				$field['allow'] = array( 'url', 'attachment' );
			}
			if ( 'file' == $field['type'] && ! isset( $field['save_id'] ) ) {
				$field['save_id'] = false;
			}
			if ( 'multicheck' == $field['type'] ) {
				$field['multiple'] = true;
			}
			$meta = get_post_meta( $post->ID, esc_attr( $field['id'] ), 'multicheck' != $field['type'] /* If multicheck this can be multiple values */ );
			echo '<tr class="', esc_attr( $field['class'] ),'">';
			if ( 'title' == $field['type'] || ( 'select' == $field['type'] && '' == esc_html( $field['name'] ) ) ) {
				echo '<td colspan="2">';
			} else {
				if ( true == $this->_meta_box['show_names'] ) {
					echo '<th style="width:18%"><label class="', esc_attr( $field['class'] ),'" for="', esc_attr( $field['id'] ), '">', esc_html( $field['name'] ), '</label></th>';
				}
				echo '<td>';
			}

			switch ( $field['type'] ) {
				case 'text':
					echo '<input class="', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ),  '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" />','<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">',  wp_kses_post( $field['desc'] ) , '</p>';
					break;
				case 'text_small':
					echo '<input class="bsf_text_small ', esc_attr( $field['class'] ),'" type="text" name="',esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">',  wp_kses_post( $field['desc'] ), '</span>';
					break;
				case 'text_medium':
					echo '<input class="bsf_text_medium ', esc_attr( $field['class'] ),'" type="text" name="',esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'text_date':
					echo '<input class="bsf_text_small bsf_datepicker ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'text_date_timestamp':
					echo '<input class="bsf_text_small bsf_datepicker ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( gmdate( 'm\/d\/Y', $meta ) ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'text_datetime_timestamp':
					echo '<input class="bsf_text_small bsf_datepicker ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '[date]" id="', esc_attr( $field['id'] ), '_date" value="', '' !== $meta ? esc_attr( gmdate( 'm\/d\/Y', $meta ) ) : esc_attr( $field['std'] ), '" />';
					echo '<input class="bsf_timepicker text_time ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '[time]" id="', esc_attr( $field['id'] ), '_time" value="', '' !== $meta ? esc_attr( gmdate( 'h:i A', $meta ) ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'" >', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'text_time':
					echo '<input class="bsf_timepicker text_time ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'text_money':
					echo '$ <input class="bsf_text_money ', esc_attr( $field['class'] ),'" type="text" name="',esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'colorpicker':
					$meta      = '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] );
					$hex_color = '(([a-fA-F0-9]){3}){1,2}$';
					if ( preg_match( '/^' . $hex_color . '/i', $meta ) ) { // Value is just 123abc, so prepend #.
						$meta = '#' . $meta;
					} elseif ( ! preg_match( '/^#' . $hex_color . '/i', $meta ) ) { // Value doesn't match #123abc, so sanitize to just #.
						$meta = '#';
					}
					echo '<input class="bsf_colorpicker bsf_text_small ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', esc_attr( $meta ), '" /><span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'textarea':
					echo '<textarea class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="',esc_attr( $field['id'] ), '" cols="60" rows="10">', '' !== $meta ? esc_attr( htmlspecialchars_decode( $meta ) ) : esc_attr( $field['std'] ), '</textarea>','<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'textarea_small':
					echo '<textarea class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="',esc_attr( $field['id'] ), '" cols="60" rows="4">', '' !== $meta ? esc_attr( htmlspecialchars_decode( $meta ) ) : esc_attr( $field['std'] ), '</textarea>','<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'textarea_code':
					echo '<textarea class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="',esc_attr( $field['id'] ), '" cols="60" rows="10" class="bsf_textarea_code">', '' !== $meta ? esc_attr( htmlspecialchars_decode( $meta ) ) : esc_attr( $field['std'] ), '</textarea>','<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'select':
					if ( empty( $meta ) && ! empty( $field['std'] ) ) {
						$meta = esc_attr( $field['std'] );
					}
					echo '<select class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="',esc_attr( $field['id'] ), '">';
					foreach ( $field['options'] as $option ) {
						echo '<option class="', esc_attr( $field['class'] ),'" value="', esc_attr( $option['value'] ), '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', esc_attr( $option['name'] ) , '</option>';
					}
					echo '</select>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'radio_inline':
					if ( empty( $meta ) && ! empty( $field['std'] ) ) {
						$meta = esc_attr( $field['std'] );
					}
					echo '<div class="bsf_radio_inline ', esc_attr( $field['class'] ),'">';
					$i = 1;
					foreach ( $field['options'] as $option ) {
						echo '<div class="bsf_radio_inline_option ', esc_attr( $field['class'] ),'"><input class="', esc_attr( $field['class'] ),'" type="radio" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), esc_attr( $i ), '" value="', esc_attr( $option['value'] ), '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label class="', esc_attr( $field['class'] ),'" for="', esc_attr( $field['id'] ), esc_attr( $i ), '">', esc_attr( $option['name'] ) , '</label></div>';
						$i++;
					}
					echo '</div>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'radio':
					if ( empty( $meta ) && ! empty( $field['std'] ) ) {
						$meta = esc_attr( $field['std'] );
					}
					echo '<div class="', esc_attr( $field['class'] ),'"><ul>';
					$i = 1;
					foreach ( $field['options'] as $option ) {
						if ( esc_attr( $field['class'] ) == 'star review' || esc_attr( $field['class'] ) == 'star product' || esc_attr( $field['class'] ) == 'star software' || esc_attr( $field['class'] ) == 'star service' ) {
							$class = 'star';
						} else {
							$class = esc_attr( $field['class'] );
						}
						echo '<li class="', esc_attr( $field['class'] ),'">
					<input class="', $class,'" type="radio" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), esc_attr( $i ),'" value="', esc_attr( $option['value'] ), '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label class="', esc_attr( $field['class'] ),'" for="', esc_attr( $field['id'] ), esc_attr( $i ), '">', esc_attr( $option['name'] ) . '</label>				</li>';
						$i++;
					}
					echo '</ul></div>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '"', $meta ? ' checked="checked"' : '', ' />';
					echo '<span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'multicheck':
					echo '<ul class="', esc_attr( $field['class'] ),'">';
					$i = 1;
					foreach ( $field['options'] as $value => $name ) {
						// Append `[]` to the name to get multiple values.
						// Use in_array() to check whether the current option should be checked.
						echo '<li class="', esc_attr( $field['class'] ),'"><input type="checkbox" class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '[]" id="', esc_attr( $field['id'] ), esc_attr( $i ), '" value="', $value, '"', in_array( $value, $meta ) ? ' checked="checked"' : '', ' /><label class="', esc_attr( $field['class'] ),'" for="', esc_attr( $field['id'] ), esc_attr( $i ), '">', esc_html( $name ), '</label></li>';
						$i++;
					}
					echo '</ul>';
					echo '<span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'title':
					echo '<h5 class="bsf_metabox_title ', esc_attr( $field['class'] ),'">', esc_html( $field['name'] ), '</h5>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'wysiwyg':
					wp_editor( $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), esc_attr( $field['id'] ), isset( $field['options'] ) ? $field['options'] : array() );
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'taxonomy_select':
					echo '<select class="', esc_attr( $field['class'] ),'" name="', esc_attr( $field['id'] ), '" id="',esc_attr( $field['id'] ), '">';
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						if ( ! is_wp_error( $names ) && ! empty( $names ) && ! strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<option value="' . esc_attr( $term->slug ) . '" selected>' . esc_attr( $term->name ) . '</option>';
						} else {
							echo '<option value="' . $term->slug . '  ' , $meta == $term->slug ? esc_attr( $meta ) : ' ' ,'  ">' . esc_attr( $term->name ) . '</option>';
						}
					}
					echo '</select>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'taxonomy_radio':
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					echo '<ul class="', esc_attr( $field['class'] ),'">';
					foreach ( $terms as $term ) {
						if ( ! is_wp_error( $names ) && ! empty( $names ) && ! strcmp( $term->slug, $names[0]->slug ) ) {
							echo '<li class="', esc_attr( $field['class'] ),'"><input class="', esc_attr( $field['class'] ),'" type="radio" name="', esc_attr( $field['id'] ), '" value="' . esc_attr( $term->slug ) . '" checked>' . esc_attr( $term->name ) . '</li>';
						} else {
							echo '<li class="', esc_attr( $field['class'] ),'"><input class="', esc_attr( $field['class'] ),'" type="radio" name="', esc_attr( $field['id'] ), '" value="' . $term->slug . '  ' , $meta == $term->slug ? esc_attr( $meta ) : ' ' ,'  ">' . esc_attr( $term->name ) . '</li>';
						}
					}
					echo '</ul>';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					break;
				case 'taxonomy_multicheck':
					echo '<ul>';
					$names = wp_get_object_terms( $post->ID, $field['taxonomy'] );
					$terms = get_terms( $field['taxonomy'], 'hide_empty=0' );
					foreach ( $terms as $term ) {
						echo '<li><input class="', esc_attr( $field['class'] ),'" type="checkbox" name="', esc_attr( $field['id'] ), '[]" id="', esc_attr( $field['id'] ), '" value="', esc_attr( $term->name ) , '"';
						foreach ( $names as $name ) {
							if ( $term->slug == $name->slug ) {
								echo ' checked="checked" ';
							};
						}
						echo ' /><label>', esc_attr( $term->name ) , '</label></li>';
					}
					echo '</ul>';
					echo '<span class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</span>';
					break;
				case 'file_list':
					echo '<input class="bsf_upload_file ', esc_attr( $field['class'] ),'" type="text" size="36" name="', esc_attr( $field['id'] ), '" value="" />';
					echo '<input class="bsf_upload_button button ', esc_attr( $field['class'] ),'" type="button" value="Upload File" />';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
						$args            = array(
							'post_type'   => 'attachment',
							'numberposts' => null,
							'post_status' => null,
							'post_parent' => $post->ID,
						);
							$attachments = get_posts( $args );
						if ( $attachments ) {
							echo '<ul class="attach_list">';
							foreach ( $attachments as $attachment ) {
								echo '<li>' . wp_get_attachment_link( $attachment->ID, 'thumbnail', 0, 0, 'Download' );
								echo '<span>';
								echo apply_filters( 'the_title', '&nbsp;' . $attachment->post_title ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
								echo '</span></li>';
							}
							echo '</ul>';
						}
					break;
				case 'file':
					$input_type_url = 'hidden';
					if ( 'url' == $field['allow'] || ( is_array( $field['allow'] ) && in_array( 'url', $field['allow'] ) ) ) {
						$input_type_url = 'text';
					}
					echo '<input class="bsf_upload_file ', esc_attr( $field['class'] ),' ' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $input_type_url ) . '" size="45" id="', esc_attr( $field['id'] ), '" name="', esc_attr( $field['id'] ), '" value="', esc_attr( $meta ), '" />';
					echo '<input class="bsf_upload_button button ', esc_attr( $field['class'] ),'" id="', esc_attr( $field['id'] ), '_id" type="button" value="Upload File" />';
					echo '<input class="bsf_upload_file_id ', esc_attr( $field['class'] ),'" type="hidden" id="',esc_attr( $field['id'] ), '" name="', esc_attr( $field['id'] ), '_id" value="', esc_attr( get_post_meta( $post->ID, $field['id'] . '_id', true ) ), '" />';
					echo '<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', wp_kses_post( $field['desc'] ), '</p>';
					echo '<div id="', esc_attr( $field['id'] ), '_status" class="bsf_media_status ', esc_attr( $field['class'] ),'">';
					if ( '' != $meta ) {
						$check_image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $meta );
						if ( $check_image ) {
							echo '<div class="img_status">';
							echo '<img src="', esc_attr( $meta ), '" alt="" />';
							echo '<a href="#" class="bsf_remove_file_button ', esc_attr( $field['class'] ),'" rel="', esc_attr( $field['id'] ), '">Remove Image</a>';
							echo '</div>';
						} else {
							$parts       = explode( '/', $meta );
							$parts_count = count( $parts );
							for ( $i = 0; $i < $parts_count; ++$i ) {
								$title = $parts[ $i ];
							}
							echo 'File: <strong>', esc_attr( $title ), '</strong>&nbsp;&nbsp;&nbsp; (<a href="', esc_attr( $meta ), '" target="_blank" rel="external">Download</a> / <a href="#" class="bsf_remove_file_button" rel="', esc_attr( $field['id'] ), '">Remove</a>)';
						}
					}
					echo '</div>';
					break;
				case 'oembed':
					echo '<input class="bsf_oembed ', esc_attr( $field['class'] ),'" type="text" name="', esc_attr( $field['id'] ), '" id="', esc_attr( $field['id'] ), '" value="', '' !== $meta ? esc_attr( $meta ) : esc_attr( $field['std'] ), '" />','<p class="bsf_metabox_description ', esc_attr( $field['class'] ),'">', esc_attr( $field['desc'] ), '</p>';
					echo '<p class="bsf-spinner spinner ', esc_attr( $field['class'] ),'"></p>';
					echo '<div id="', esc_attr( $field['id'] ), '_status" class="bsf_media_status ui-helper-clearfix embed_wrap ', esc_attr( $field['class'] ),'">';
					if ( '' != $meta ) {
						$check_embed = $GLOBALS['wp_embed']->run_shortcode( '[embed]' . esc_url( $meta ) . '[/embed]' );
						if ( $check_embed ) {
							echo '<div class="embed_status ', esc_attr( $field['class'] ),'">';
							echo esc_html( $check_embed );
							echo '<a href="#" class="bsf_remove_file_button ', esc_attr( $field['class'] ),'" rel="', esc_attr( $field['id'] ), '">Remove Embed</a>';
							echo '</div>';
						} else {
							echo 'URL is not a valid oEmbed URL.';
						}
					}
					echo '</div>';
					break;
				default:
					do_action( 'bsf_render_' . $field['type'], $field, $meta );
			}
			echo '</td>','</tr>';
		}
		echo '<td></td>','<td class="bsf-table-data"><div class="bsf-tooltip"><span class="dashicons dashicons-info"></span><span class="bsf-tooltiptext">Don&#39;t want Schema data to be visible on your site&#39;s frontend? <a href="https://wpschema.com/?utm_source=allinone&utm_campaign=repo&utm_medium=editpage" target="_blank">Use Schema Pro</span></a></div></td>';
		echo '</table>';
	}
	/**
	 * Save data from metabox.
	 *
	 * @param string $post_id Post ID.
	 */
	public function save( $post_id ) {
		// verify nonce.
		if ( ! isset( $_POST['wp_meta_box_nonce'] ) || ! wp_verify_nonce( esc_attr( $_POST['wp_meta_box_nonce'] ), basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions.
		if ( 'page' == esc_attr( $_POST['post_type'] ) ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		foreach ( $this->_meta_box['fields'] as $field ) {
			$name = esc_attr( $field['id'] );
			if ( ! isset( $field['multiple'] ) ) {
				$field['multiple'] = ( 'multicheck' == $field['type'] ) ? true : false;
			}
			$old = get_post_meta( $post_id, $name, ! $field['multiple'] /* If multicheck this can be multiple values */ );
			$new = isset( $_POST[ esc_attr( $field['id'] ) ] ) ? esc_attr( $_POST[ esc_attr( $field['id'] ) ] ) : null;
			if ( in_array( $field['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ) ) ) {
				$new = wp_set_object_terms( $post_id, $new, $field['taxonomy'] );
			}
			if ( ( 'textarea' == $field['type'] ) || ( 'textarea_small' == $field['type'] ) ) {
				$new = htmlspecialchars( $new ); //phpcs:ignore:PHPCompatibility.ParameterValues.NewHTMLEntitiesEncodingDefault.NotSet
			}
			if ( ( 'textarea_code' == $field['type'] ) ) {
				$new = htmlspecialchars_decode( $new );
			}
			if ( 'text_date_timestamp' == $field['type'] ) {
				$new = strtotime( $new );
			}
			if ( 'text_datetime_timestamp' == $field['type'] ) {
				$string = $new['date'] . ' ' . $new['time'];
				$new    = strtotime( $string );
			}
			$new = apply_filters( 'bsf_validate_' . $field['type'], $new, $post_id, $field );
			// validate meta value.
			if ( isset( $field['validate_func'] ) ) {
				$ok = call_user_func( array( 'Bsf_Meta_Box_Validate', $field['validate_func'] ), $new );
				if ( false === $ok ) { // pass away when meta value is invalid.
					continue;
				}
			} elseif ( $field['multiple'] ) {
				delete_post_meta( $post_id, $name );
				if ( ! empty( $new ) ) {
					foreach ( $new as $add_new ) {
						add_post_meta( $post_id, $name, $add_new, false );
					}
				}
			} elseif ( '' !== $new && $new != $old ) {
				update_post_meta( $post_id, $name, $new );
			} elseif ( '' == $new ) {
				delete_post_meta( $post_id, $name );
			}
			if ( 'file' == $field['type'] ) {
				$name = esc_attr( $field['id'] ) . '_id';
				$old  = get_post_meta( $post_id, $name, ! $field['multiple'] /* If multicheck this can be multiple values */ );
				if ( isset( $field['save_id'] ) && $field['save_id'] ) {
					$new = isset( $_POST[ $name ] ) ? sanitize_text_field( $_POST[ $name ] ) : null;
				} else {
					$new = '';
				}
				if ( $new && $new != $old ) {
					update_post_meta( $post_id, $name, $new );
				} elseif ( '' == $new && $old ) {
					delete_post_meta( $post_id, $name, $old );
				}
			}
		}
	}
}
/**
 * Adding scripts and styles
 *
 * @param string $hook Hook.
 */
function bsf_scripts( $hook ) {
	global $wp_version;
	// only enqueue our scripts/styles on the proper pages.
	if ( 'post.php' == $hook || 'post-new.php' == $hook || 'page-new.php' == $hook || 'page.php' == $hook ) {
		// scripts required for cmb.
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
		wp_register_script( 'bsf-timepicker', BSF_META_BOX_URL . 'js/jquery.timePicker.min.js', false ); //phpcs:ignore:WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_register_script( 'bsf-scripts', BSF_META_BOX_URL . 'js/cmb.js', $bsf_script_array, '0.9.1', false );
		wp_localize_script(
			'bsf-scripts',
			'bsf_ajax_data',
			array(
				'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
				'post_id'    => get_the_ID(),
			)
		);
		wp_enqueue_script( 'bsf-timepicker' );
		wp_enqueue_script( 'bsf-scripts' );
		wp_register_style( 'bsf-styles', BSF_META_BOX_URL . 'admin/css/style.css', $bsf_style_array ); //phpcs:ignore:WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'bsf-styles' );
	}
}
add_action( 'admin_enqueue_scripts', 'bsf_scripts', 10 );
/**
 * Bsf_editor_footer_scripts.
 */
function bsf_editor_footer_scripts() { ?>
	<?php
	if ( isset( $_GET['bsf_force_send'] ) && isset( $_GET['bsf_file_upload_nonce'] ) && wp_verify_nonce( $_GET['bsf_file_upload_nonce'], 'ajax_nonce' ) && 'true' == esc_attr( $_GET['bsf_force_send'] ) ) {
		$label = esc_attr( $_GET['bsf_send_label'] );
		if ( empty( $label ) ) {
			$label = 'Select File';
		}
		?>
		<script type="text/javascript">
		jQuery(function($) {
			$('td.savesend input').val('<?php echo esc_attr( $label ); ?>');
		});
		</script>
		<?php
	}
}
add_action( 'admin_print_footer_scripts', 'bsf_editor_footer_scripts', 99 );
// Force 'Insert into Post' button from Media Library.
add_filter( 'get_media_item_args', 'bsf_force_send' );
/**
 * Bsf_force_send.
 *
 * @param array $args Arguments.
 */
function bsf_force_send( $args ) {

	if ( ! isset( $_GET['bsf_file_upload_nonce'] ) || ! wp_verify_nonce( $_GET['bsf_file_upload_nonce'], 'ajax_nonce' ) ) {
		return;
	}
	// if the Gallery tab is opened from a custom meta box field, add Insert Into Post button.
	if ( isset( $_GET['bsf_force_send'] ) && 'true' == esc_attr( $_GET['bsf_force_send'] ) ) {
		$args['send'] = true;
	}
	// if the From Computer tab is opened AT ALL, add Insert Into Post button after an image is uploaded.
	if ( isset( $_POST['attachment_id'] ) && '' != esc_attr( $_POST['attachment_id'] ) ) {
		$args['send'] = true;
		// TO DO: Are there any conditions in which we don't want the Insert Into Post.
		// button added? For example, if a post type supports thumbnails, does not support.
		// the editor, and does not have any cmb file inputs? If so, here's the first.
		// bits of code needed to check all that.
		// $attachment_ancestors = get_post_ancestors( $_POST["attachment_id"] );.
		// $attachment_parent_post_type = get_post_type( $attachment_ancestors[0] );.
		// $post_type_object = get_post_type_object( $attachment_parent_post_type );.
	}
	// change the label of the button on the From Computer tab.
	if ( isset( $_POST['attachment_id'] ) && '' != esc_attr( $_POST['attachment_id'] ) ) {
		echo '
			<script type="text/javascript">
				function cmbGetParameterByNameInline(name) {
					name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
					var regexS = "[\\?&]" + name + "=([^&#]*)";
					var regex = new RegExp(regexS);
					var results = regex.exec(window.location.href);
					if(results == null)
						return "";
					else
						return decodeURIComponent(results[1].replace(/\+/g, " "));
				}
				jQuery(function($) {
					if (cmbGetParameterByNameInline("bsf_force_send")=="true") {
						var bsf_send_label = cmbGetParameterByNameInline("bsf_send_label");
						$("td.savesend input").val(bsf_send_label);
					}
				});
			</script>
		';
	}
	return $args;
}
add_action( 'wp_ajax_bsf_oembed_handler', 'bsf_oembed_ajax_results' );
/**
 * Handles our oEmbed ajax request.
 */
function bsf_oembed_ajax_results() {
	// verify our nonce.
	if ( ! ( isset( $_REQUEST['bsf_ajax_nonce'], $_REQUEST['oembed_url'] ) && wp_verify_nonce( $_REQUEST['bsf_ajax_nonce'], 'ajax_nonce' ) ) ) {
		die();
	}
	// sanitize our search string.
	$oembed_string = sanitize_text_field( $_REQUEST['oembed_url'] );
	if ( empty( $oembed_string ) ) {
		$return = '<p class="ui-state-error-text">' . __( 'Please Try Again', 'all-in-one-schemaorg-rich-snippets' ) . '</p>';
		$found  = 'not found';
	} else {
		global $wp_embed;
		$oembed_url = esc_url( $oembed_string );
		// Post ID is needed to check for embeds.
		if ( isset( $_REQUEST['post_id'] ) ) {
			$GLOBALS['post'] = get_post( esc_attr( $_REQUEST['post_id'] ) ); //phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited
		}
		// ping WordPress for an embed.
		$check_embed = $wp_embed->run_shortcode( '[embed]' . $oembed_url . '[/embed]' );
		// fallback that WordPress creates when no oEmbed was found.
		$fallback = $wp_embed->maybe_make_link( $oembed_url );
		if ( $check_embed && $check_embed != $fallback ) {
			// Embed data.
			$return = '<div class="embed_status">' . $check_embed . '<a href="#" class="bsf_remove_file_button" rel="' . esc_attr( $_REQUEST['field_id'] ) . '">' . __( 'Remove Embed', 'all-in-one-schemaorg-rich-snippets' ) . '</a></div>';
			// set our response id.
			$found = 'found';
		} else {
			// error info when no oEmbeds were found.
			/* translators: %s: $fallback */
			$return = '<p class="ui-state-error-text">' . sprintf( __( 'No oEmbed Results Found for %s. View more info at', 'all-in-one-schemaorg-rich-snippets' ), $fallback ) . ' <a href="http://codex.wordpress.org/Embeds" target="_blank">codex.wordpress.org/Embeds</a>.</p>';
			// set our response id.
			$found = 'not found';
		}
	}
	// send back our encoded data.
	echo wp_json_encode(
		array(
			'result' => $return,
			'id'     => $found,
		)
	);
	die();
}
// End. That's it, folks! //.
?>
