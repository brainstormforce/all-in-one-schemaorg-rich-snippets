<?php
/**
 * Start object buffering to supress warning messages.
 *
 * @package Schema - All In One Schema Rich Snippets.
 */

ob_start();
if ( is_admin() ) {
	add_action( 'admin_footer', 'add_footer_script' );
}
/**
 * Enqueues the styles in admin dashboard.
 *
 * @return void
 */
function bsf_admin_styles() {
	wp_enqueue_style( 'star_style' );
	wp_enqueue_style( 'meta_style' );
	wp_enqueue_script( 'bsf_jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'bsf_jquery_star' );
}
/**
 * Enqueues the scripts in admin dashboard.
 *
 * @return void
 */
function add_the_script() {
	wp_enqueue_script( 'postbox' );
	wp_enqueue_style( 'admin_style' );
}
add_action( 'admin_print_scripts', 'add_the_script' );
/**
 * The Main Admin Dashboard for Rich Snippets Plugin.
 *
 * @return void
 */
function rich_snippet_dashboard() {
	$plugins_url = plugins_url();
	if ( ! get_option( 'bsf_woocom_init_setting' ) ) {
		$args_woocom = true;
	} else {
		$args_woocom = get_option( 'bsf_woocom_setting' );
	}

	$args_review  = get_option( 'bsf_review' );
	$args_event   = get_option( 'bsf_event' );
	$args_person  = get_option( 'bsf_person' );
	$args_product = get_option( 'bsf_product' );
	$args_recipe  = get_option( 'bsf_recipe' );
	$args_soft    = get_option( 'bsf_software' );
	$args_video   = get_option( 'bsf_video' );
	$args_article = get_option( 'bsf_article' );
	$args_service = get_option( 'bsf_service' );

	$woo_setting = '';
	if ( ! empty( $args_woocom ) ) {
		$woo_setting = 'Checked';
	}

	if ( isset( $args_event['event_desc'] ) ) {
		$event_desc = $args_event['event_desc'];
	} else {
		$event_desc = null;
	}

	if ( isset( $args_recipe['author_name'] ) ) {
		$author_name = $args_recipe['author_name'];
	} else {
		$author_name = null;
	}

	if ( isset( $args_recipe['recipe_desc'] ) ) {
		$recipe_desc = $args_recipe['recipe_desc'];
	} else {
		$recipe_desc = null;
	}

	if ( isset( $args_service['provider_location'] ) ) {
		$provider_location = $args_service['provider_location'];
	} else {
		$provider_location = null;
	}

	$args_color = get_option( 'bsf_custom' );
	echo '<div class="wrap">';
	echo '<div id="star-icons-32" class="icon32"></div><h2>' . esc_html__( 'All in One Schema.org Rich Snippets - Dashboard', 'all-in-one-schemaorg-rich-snippets' ) . '</h2>';
	echo '<div id="post-body" class="columns-2">';
	echo '<div class="clear"></div>';
	echo '<div id="bsf-postbox-container-2" class="postbox-container"><div id="tab-container" class="tab-container">';
	echo '<ul class="nav-tab-wrapper bsf-tab-wraper">
			<li><a href="#tab-1" class="nav-tab">' . esc_html__( 'Configuration', 'all-in-one-schemaorg-rich-snippets' ) . '</a></li>
			<li><a href="#tab-4" class="nav-tab">' . esc_html__( 'Customization', 'all-in-one-schemaorg-rich-snippets' ) . '</a></li>
			
			<li><a href="#tab-3" class="nav-tab">' . esc_html__( 'FAQs', 'all-in-one-schemaorg-rich-snippets' ) . '</a></li>
			<li><a href="#tab-5" class="nav-tab">' . esc_html__( 'Getting Started', 'all-in-one-schemaorg-rich-snippets' ) . '</a></li>
		 </ul>
		 <div class="clear"></div>
		 <div class="panel-container bsf-panel">
			 <div id="tab-1">
				<div id="poststuff">
					<div class="schema-notice-head">
					<p>' . esc_html__( 'Choose the schema markup and update the strings you want to display on the front-end.', 'all-in-one-schemaorg-rich-snippets' ) . '</p>
					</div>
					<div id="postbox-container-2" class="postbox-container">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Item Review', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Item Review Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_review_form" method="post">
										' . wp_nonce_field( 'snippet_review_form_action', 'snippet_review_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="review_title" value="' . esc_attr( stripslashes( $args_review['review_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Reviewer :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="item_reviewer" value="' . esc_attr( stripslashes( $args_review['item_reviewer'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Review Date :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="review_date" value="' . esc_attr( stripslashes( $args_review['review_date'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Item Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="item_name" value="' . esc_attr( stripslashes( $args_review['item_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Item Ratings :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="item_rating" value="' . esc_attr( stripslashes( $args_review['item_rating'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="item_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=review">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Events', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Events Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_event_form" method="post">
										' . wp_nonce_field( 'snippet_event_form_action', 'snippet_event_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. ' 
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_event['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Event Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="event_title" value="' . esc_attr( stripslashes( $args_event['event_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Event Location :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="event_location" value="' . esc_attr( stripslashes( $args_event['event_location'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Start Time :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="start_time" value="' . esc_attr( stripslashes( $args_event['start_time'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'End Time :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="end_time" value="' . esc_attr( stripslashes( $args_event['end_time'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Description :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="event_desc" value="' . esc_attr( stripslashes( $event_desc ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Ticket Promotion :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="events_price" value="' . esc_attr( stripslashes( $args_event['events_price'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="event_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=event">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Person', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( "Strings to be displayed on frontend for <strong>Person's Rich Snippets &mdash;</strong>", 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_person_form" method="post">
										' . wp_nonce_field( 'snippet_person_form_action', 'snippet_person_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_person['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( "Person's Name :", 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_name" value="' . esc_attr( stripslashes( $args_person['person_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Nick Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_nickname" value="' . esc_attr( stripslashes( $args_person['person_nickname'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Job Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_job_title" value="' . esc_attr( stripslashes( $args_person['person_job_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Home page :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_website" value="' . esc_attr( stripslashes( $args_person['person_website'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Company Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_company" value="' . esc_attr( stripslashes( $args_person['person_company'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Address :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="person_address" value="' . esc_attr( stripslashes( $args_person['person_address'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="person_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=person">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Product', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Product Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_product_form" method="post">
										' . wp_nonce_field( 'snippet_product_form_action', 'snippet_product_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_product['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Author Rating :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_rating" value="' . esc_attr( stripslashes( $args_product['product_rating'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Brand Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_brand" value="' . esc_attr( stripslashes( $args_product['product_brand'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Product Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_name" value="' . esc_attr( stripslashes( $args_product['product_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'User Rating :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_agr" value="' . esc_attr( stripslashes( $args_product['product_agr'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Price :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_price" value="' . esc_attr( stripslashes( $args_product['product_price'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Product Availability :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="product_avail" value="' . esc_attr( stripslashes( $args_product['product_avail'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="product_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=product">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Recipe', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Recipe Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_recipe_form" method="post">
										' . wp_nonce_field( 'snippet_recipe_form_action', 'snippet_recipe_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_recipe['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Recipe Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_name" value="' . esc_attr( stripslashes( $args_recipe['recipe_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Author Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="author_name" value="' . esc_attr( stripslashes( $author_name ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Published On : ', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_pub" value="' . esc_attr( stripslashes( $args_recipe['recipe_pub'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Preparation Time:', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_prep" value="' . esc_attr( stripslashes( $args_recipe['recipe_prep'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Cook Time :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_cook" value="' . esc_attr( stripslashes( $args_recipe['recipe_cook'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Total Time :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_time" value="' . esc_attr( stripslashes( $args_recipe['recipe_time'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Description :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_desc" value="' . esc_attr( stripslashes( $recipe_desc ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Average Rating:', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="recipe_rating" value="' . esc_attr( stripslashes( $args_recipe['recipe_rating'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="recipe_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=recipe">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Software Application', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Software Application Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_software_form" method="post">
										' . wp_nonce_field( 'snippet_soft_app_form_action', 'snippet_soft_app_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_soft['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Author Rating :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_rating" value="' . esc_attr( stripslashes( $args_soft['software_rating'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'User Rating :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_agr" value="' . esc_attr( stripslashes( $args_soft['software_agr'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Software Price :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_price" value="' . esc_attr( stripslashes( $args_soft['software_price'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Software Name:', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_name" value="' . esc_attr( stripslashes( $args_soft['software_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Operating System :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_os" value="' . esc_attr( stripslashes( $args_soft['software_os'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Landing Page:', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="software_website" value="' . esc_attr( stripslashes( $args_soft['software_website'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="software_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=software">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							
							
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Video', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Video Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_video_form" method="post">
										' . wp_nonce_field( 'snippet_video_form_action', 'snippet_video_nonce_field' )//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_video['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Video Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="video_title" value="' . esc_attr( stripslashes( $args_video['video_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Description :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="video_desc" value="' . esc_attr( stripslashes( $args_video['video_desc'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Video Duration :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="video_time" value="' . esc_attr( stripslashes( $args_video['video_time'] ) ) . '"/></td>
													</tr>													
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Video Upload Date :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="video_date" value="' . esc_attr( stripslashes( $args_video['video_date'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="video_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=video">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Article', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Article Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_article_form" method="post">
										' . wp_nonce_field( 'snippet_article_form_action', 'snippet_article_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_article['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Article Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_name" value="' . esc_attr( stripslashes( $args_article['article_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Author :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_author" value="' . esc_attr( stripslashes( $args_article['article_author'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Description :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_desc" value="' . esc_attr( stripslashes( $args_article['article_desc'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Image :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_image" value="' . esc_attr( stripslashes( $args_article['article_image'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Publisher :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_publisher" value="' . esc_attr( stripslashes( $args_article['article_publisher'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Publisher Logo :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="article_publisher_logo" value="' . esc_attr( stripslashes( $args_article['article_publisher_logo'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="article_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=article">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
							<div class="postbox closed">
								<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3 class="hndle"><span>' . esc_html__( 'Service', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<div class="table">								
										<p>' . wp_kses_post( __( 'Strings to be displayed on frontend for <strong>Service Rich Snippets &mdash;</strong>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<form id="bsf_service_form" method="post">
										' . wp_nonce_field( 'snippet_service_form_action', 'snippet_service_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
										. '
											<table class="bsf_metabox">
												<tbody>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Rich Snippet Title :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="snippet_title" value="' . esc_attr( stripslashes( $args_service['snippet_title'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Service Type :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_type" value="' . esc_attr( stripslashes( $args_service['service_type'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Area :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_area" value="' . esc_attr( stripslashes( $args_service['service_area'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Description :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_desc" value="' . esc_attr( stripslashes( $args_service['service_desc'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Provider Name :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_provider_name" value="' . esc_attr( stripslashes( $args_service['service_provider_name'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Provider Location :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="provider_location" value="' . esc_attr( stripslashes( $provider_location ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'URL :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_channel" value="' . esc_attr( stripslashes( $args_service['service_channel'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'URL Text :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_url_link" value="' . esc_attr( stripslashes( $args_service['service_url_link'] ) ) . '"/></td>
													</tr>
													<tr>
														<td align="right"><strong><label>' . esc_html__( 'Service Rating :', 'all-in-one-schemaorg-rich-snippets' ) . '</label></strong></td>
														<td><input class="bsf_text_medium" type="text" name="service_rating" value="' . esc_attr( stripslashes( $args_service['service_rating'] ) ) . '"/></td>
													</tr>
													<tr><td colspan="2"></td></tr>
													<tr>
														<td></td>
														<td><input type="submit" class="button-primary" name="service_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=service">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
								</div>
							</div>
						<!-- Post blox -->		
							
						</div>
					<div class="schema-notice">
						<p>' . wp_kses_post( __( "Need some more schema types with automation to implement schema markup? Get the latest and premium schema markup plugin to automate the process of adding schema markup on your entire website. <br><a href='https://wpschema.com/?utm_source=allinone&utm_campaign=repo&utm_medium=configure' target='_blank'> Know more about Schema Pro.</a>", 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
					</div>
					</div>
				</div>	
			 </div>
	
			 
			 <div id="tab-5">
				<div id="poststuff">
					<div id="postbox-container-17" class="postbox-container">
						<div class="meta-box-sortables ui-sortable bsf-even-even">
							<div class="bsf-postbox">
								<h3 class="bsf-hndle" style="margin-top:0;"><span>' . esc_html__( 'Welcome to All In One Schema Rich Snippets', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
								<div class="inside">
									<p>Thank you for choosing All-in-one Schema Rich Snippets - the most popular WordPress schema markup plugin!</p>

										<p>All-in-one Schema Rich Snippets helps you add different schema content types to your site so that you can communicate precise information about your web pages to search engines and get rich snippets.</p>
										<div class="bsf-xs-separator"></div>
										<h3>Supported types of Schemas:</h3>
										<ul class="schema-types">
											 <div class="schema-type-col-2">
												<li>Review</li>
												<li>Event</li>
												<li>Services</li>
											 </div>
											<div class="schema-type-col-2">
												<li>Person</li>
												<li>Product</li>
											</div>
											<div class="schema-type-col-2">
												<li>Video</li>
												<li>Articles</li>
											</div>
											<div class="schema-type-col-2">
												<li>Recipe</li>
												<li>Software Application</li>
											</div>
										</ul>
										<div class="bsf-xs-separator"></div>
								</div>
							</div>
						</div>
						<div class="meta-box-sortables ui-sortable bsf-even-odd">
									<h3 class="bsf-hndle"><span>' . esc_html__( 'How it works', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
									<div class="inside">
										<ol class="bsf-li-counter">
											<li>' . esc_html__( 'Configure The Settings', 'all-in-one-schemaorg-rich-snippets' ) . '
											<p>' . esc_html__( 'Go to the “Rich Snippets” option in your WordPress dashboard. Under the Configuration tab, select your desired schema type and update the strings you want to display on the front-end. You can use the Customization tab to manage how your rich snippet content box will look.', 'all-in-one-schemaorg-rich-snippets' ) . '</p></li>
											<li>' . esc_html__( 'Add Markup To Pages', 'all-in-one-schemaorg-rich-snippets' ) . '<p>' . esc_html__( 'Edit the posts or pages where you wish to add rich snippets and scroll down to the “Configure Rich Snippet” meta box to add schema markup.', 'all-in-one-schemaorg-rich-snippets' ) . '</p></li>
											<li>' . esc_html__( 'Test Your Rich Snippets', 'all-in-one-schemaorg-rich-snippets' ) . '<p>' . wp_kses_post( __( "Google Structured Data Testing is a widely used online tool to test structured data. Open the <a href='https://search.google.com/structured-data/testing-tool/u/0/' target='_blank'>Google Structured Testing Tool</a> and fetch your website URL to test the schema markup you’ve just implemented on your webpages.", 'all-in-one-schemaorg-rich-snippets' ) ) . '</p></li>
										</ol>
									</div>
						</div>
						<div class="meta-box-sortables ui-sortable bsf-even-even">
									<h3 class="bsf-hndle"><span>' . esc_html__( 'Want to Automate Your Schema Markup?', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
									<div class="inside">
										<h3>' . esc_html__( 'Consider Schema Pro', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<p>' . wp_kses_post( __( 'Schema Pro is an advanced schema markup plugin that automates the process of adding schema markup on multiple pages with just a few clicks. Schema Pro uses  JSON-LD markup, which is the latest technology recommended by Google. With it, you can kick those front-end content boxes to the curb and<b> get rich snippets without displaying any new human-readable content</b> on your site.', 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<h3></h3>
										<div class="bsf-schema-desc">
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/click.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Schema Markup Automation</h3>
													<p>Schema Pro automates the process of adding schema markup on your website. Just configure your markup one time and you can easily apply it to hundreds or thousands of pages.</p>
													</div>
												</div>
											</div>
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/seo.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Complete Schema Implementation</h3>
													<p>Schema Pro gives you the full benefits of schema markup with both organization level and content type schemas. Implement organization level markup site-wide and content type on specific pages.</p>
													</div>
												</div>
											</div>
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/website.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Advanced Targeting Rules</h3>
													<p>Schema Pro lets you use pinpoint inclusion/exclusion rules to apply different schema content types on both a post type or individual post level.</p>
													</div>
												</div>
											</div>
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/custom.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Custom Field Support<h3>
													<p>Schema Pro comes with all the necessary fields for each content type, as well as support for your own custom fields. It lets you map existing custom fields or create new ones to suit your needs.</p>
													</div>
												</div>
											</div>
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/quick.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Accuracy and Testing</h3>
													<p>Schema Pro lets you implement accurate markup and analyse your schema implementation instantly so you can rest assured that you’ve implemented it right.</p>
													</div>
												</div>
											</div>
											<div class="bsf-schema-features-1">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-features-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/acf.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<h3>Compatibility with Yoast SEO,  ACF, PODS </h3>
													<p>Schema Pro is compatible with popular third-party plugins. It can inherit the settings from Yoast SEO and fetch custom fields that you&#39;ve created using the ACF or PODS plugins.</p>
													</div>
												</div>
											</div>
										</div>
									</div>
						</div>
						<div class="meta-box-sortables ui-sortable bsf-even-odd">
									<h3 class="bsf-hndle"><span>' . esc_html__( 'With Schema Pro, you can…', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
									<div class="inside">
										<ol class="bsf-li-counter">
											<li>Automate schema markup for your entire website.</li>
											<li>Implement schema markup faster and more accurately.</li>
											<li>Target different post types with different Schema types.</li>
										</ol>
									</div>
						</div>
						<div class="meta-box-sortables ui-sortable bsf-even-even">
									<h3 class="bsf-hndle"><span>' . esc_html__( 'Testimonials', 'all-in-one-schemaorg-rich-snippets' ) . '</span></h3>
									<div class="inside">
										<div class="bsf-schema">
											<div class="bsf-testimonial-wrap">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-testimonial-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/adam-circle.jpg' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<p>I have used every Schema Plugin for WordPress over the last few years, hundreds of dollars invested, and Schema Pro blows them all out of the water. It’s the only schema plugin you need.</p>
													<b>Adam Preiser,</b> <span>Founder of WPCrafter.com</span>
													</div>
												</div>
											</div>
											<div class="bsf-ls-separator"></div>
											<div class="bsf-testimonial-wrap">
												<div class="bsf-schema-features-wrap">
													<div class="bsf-schema-testimonial-icon">
													<img src="' . esc_url( plugins_url( '/all-in-one-schemaorg-rich-snippets/images/kylevan.png' ) ) . '"/>
													</div>
													<div class="bsf-schema-features-cont">
													<p>Schema Pro has unlocked a powerful set of tools that produced results almost immediately. As a non-coder, a solution like this allows me to set up and stand out against the competition- and it couldn&#39;t be any easier to use!</p>
													<b>Kyle Van Deusen,</b> <span> Owner at ogalweb.com</span>
													</div>
												</div>
											</div>
										</div>
									</div>
						</div>
						<div class="meta-box-sortables ui-sortable bsf-even-odd testimonial-wraper">
									<div class="inside">
										<div class="bsf-schema">
											<div class="bsf-schema-button-wrap">
												<a href="https://wpschema.com/pricing/?utm_source=allinone&utm_campaign=repo&utm_medium=welcome" target="_blank" class="bsf-btn bsf-btn-lg btn-btn-purple">Get Schema Pro</a>
											</div>
											<div class="bsf-schema-button-wrap">
												<a href="https://wpschema.com/?utm_source=allinone&utm_campaign=repo&utm_medium=welcome" target="_blank" class="bsf-btn bsf-btn-lg btn-btn-grey">See All Features</a>
											</div>
										</div>
									</div>
						</div>
					</div>
				</div>
			</div>
	
			 <div id="tab-3">
				<div id="poststuff">
					<div id="postbox-container-5" class="postbox-container">
						<div class="meta-box-sortables ui-sortable">
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( 'Where can I test my schema markup implementation?', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>' . esc_html__( 'You can use the standard Google Structured Data Testing Tool to test your schema markup implementation. You can also take a look at the preview of how your search result might look.', 'all-in-one-schemaorg-rich-snippets' ) . ' <a href="http://www.google.com/webmasters/tools/richsnippets" target="_blank">Click Here.</a></p>
										</div>
								</div>
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( 'Do I have to fill in all the details?', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>' . esc_html__( 'No. But, every schema type has some fields that HAVE to be filled as stated by Google. Therefore, it is recommended to fill these required fields in the schema markup you are implementing.', 'all-in-one-schemaorg-rich-snippets' ) . '</p>
										</div>
								</div>
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( 'Why does the plugin create extra content in the frontend? Can I hide it?', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>We understand that you don&#39;t like the content that gets displayed on your page / post. However, as per the strong recommendation of Google, MicroData should be clearly visible to the user.</p>
										<p>Here is a reference link of what Google says. <a href="https://sites.google.com/site/webmasterhelpforum/en/faq-rich-snippets#display" target="_blank"> https://sites.google.com/site/webmasterhelpforum/en/faq-rich-snippets#display</a></p>
										<p> If you still do not want your schema markup to affect your frontend design, you can use <a href="https://wpschema.com/?utm_source=allinone&utm_campaign=repo&utm_medium=faq" target="_blank">Schema Pro</a> - our advanced Schema markup plugin that is built with the latest JSON- LD technology which does not require a content box to be displayed on the front-end.</p>
										</div>
								</div>
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( 'Does the plugin work with other plugins like WordPress SEO, WooCommerce, etc?', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>Well, the plugin works perfectly with most of the other plugins as the only thing "All in One Schema.org Rich Snippets" does is - it gives you power to add Rich Snippets MicroData to your pages and posts easily. <br><br>If you come across a conflict with any other plugin, please do not hesitate to report an issue.</p>
										</div>
								</div>
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( 'How long will it take to show up rich snippets for my search results?', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>We cannot assure the time it will take to display a rich snippet for your search results. This is completely dependent on when your website is crawled by the search engine. However, there are many more factors, such as your website authority that contribute to the time taken for your website to be crawled and a rich snippet displayed.</p>
										<p>If rich snippets are not appearing in your search results as of yet, most probably they might start appearing as soon as Google or other search engines find your website more authoritative.</p>
										<p>Meanwhile - you can validate and see the preview of your rich snippets on <a target="_blank" href="http://www.google.com/webmasters/tools/richsnippets">[ Google Structured Data Testing Tool ]</a> .</p>
										</div>
								</div>
								<div class="postbox closed">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . esc_html__( "I don't see the feature I want. How can I get it?", 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
										<div class="inside">
										<p>' . wp_kses_post( __( "<a href='https://wpschema.com/contact/' target='_blank'>Get in touch</a> with us to ask if this feature is in our development roadmap. If it is not in our roadmap, and if you still think this feature would make the plugin better, we have a couple of options for you:", 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
										<ol>
											<li>' . esc_html__( 'Code the new feature if you are a developer and submit your code. If we include this feature in our releases, credits will be given to you.', 'all-in-one-schemaorg-rich-snippets' ) . '</li>
											<li>' . esc_html__( 'Offer a sponsorship to get this feature done for all plugin users OR request a professional customisation service.', 'all-in-one-schemaorg-rich-snippets' ) . '</li>
										</ol>
										</div>
								</div>
							</div>
						</div>
					</div>
				 </div>
				<!-- Tab 4-->
				<div id="tab-4">
					<div id="poststuff">
						<div id="postbox-container-11" class="postbox-container">
							<div class="meta-box-sortables ui-sortable">
								<div class="postbox">
									<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
										<h3 class="hndle">' . wp_kses_post( __( '<span>Customize the look and feel of rich snippet box</span>', 'all-in-one-schemaorg-rich-snippets' ) ) . '</h3>
										<div class="inside">
											<form id="bsf_css_editor" method="post" onsubmit="return false;" action="">
											' . wp_nonce_field( 'snippet_color_form_action', 'snippet_color_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  
											. '
											<table class="bsf_metabox">
												<tr>
													<th> <label for="snippet_box_bg"> ' . esc_html__( 'Box Background ', 'all-in-one-schemaorg-rich-snippets' ) . ' </label> </th>
													<td> <input type="text" name="snippet_box_bg" id="snippet_box_bg" value="' . esc_attr( stripslashes( $args_color['snippet_box_bg'] ) ) . '"  class="snippet_box_bg" /> </td>
												</tr>
												<tr>
													<th> <label for="snippet_title_bg"> ' . esc_html__( 'Title Background', 'all-in-one-schemaorg-rich-snippets' ) . ' </label> </th>
													<td> <input type="text" name="snippet_title_bg" id="snippet_title_bg" value="' . esc_attr( stripslashes( $args_color['snippet_title_bg'] ) ) . '"  class="snippet_title_bg" /> </td>
												</tr>
												<tr>
													<th> <label for="snippet_border"> ' . esc_html__( 'Border Color', 'all-in-one-schemaorg-rich-snippets' ) . ' </label> </th>
													<td> <input type="text" name="snippet_border" id="snippet_border" value="' . esc_attr( stripslashes( $args_color['snippet_border'] ) ) . '"  class="snippet_border" /> </td>
												</tr>
												<tr>
													<th> <label for="snippet_title_color"> ' . esc_html__( 'Title Color', 'all-in-one-schemaorg-rich-snippets' ) . ' </label> </th>
													<td> <input type="text" name="snippet_title_color" id="snippet_title_color" value="' . esc_attr( stripslashes( $args_color['snippet_title_color'] ) ) . '"  class="snippet_title_color" /> </td>
												</tr>
												<tr>
													<th> <label for="snippet_box_color"> ' . esc_html__( 'Snippet Text Color', 'all-in-one-schemaorg-rich-snippets' ) . ' </label> </th>
													<td> <input type="text" name="snippet_box_color" id="snippet_box_color" value="' . esc_attr( stripslashes( $args_color['snippet_box_color'] ) ) . '"  class="snippet_box_color" /> </td>
												</tr>
												<tr>
													<td></td>
													<td><input id="submit_colors" class="button-primary" type="submit" value="Update Colors" />&nbsp;&nbsp;&nbsp;<a class="button-primary" href="?page=rich_snippet_dashboard&amp;action=reset&options=color">' . esc_html__( 'Reset ', 'all-in-one-schemaorg-rich-snippets' ) . '</a></td>
												</tr>
											</table>
											</form>
										</div>
									</div>
									<div class="schema-notice">
										<p>' . wp_kses_post( __( "Don&#39;t want to add a content box on the front-end? Get the latest and premium schema markup plugin that adds Google recommended JSON-LD structured data format without the content box. <a href='https://wpschema.com/?utm_source=allinone&utm_campaign=repo&utm_medium=customize' target='_blank'> Know more about Schema Pro.</a>", 'all-in-one-schemaorg-rich-snippets' ) ) . '</p>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
						
		 </div>
		 </div>
		<div class="postbox-container" id="bsf-postbox-container-1" >
		<div id="side-sortables" class="meta-box-sortables ui-sortable">
		<div class="postbox bsf-woocommerce-setting closed">
			<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h3 class="get_in_touch">' . esc_html__( 'WooCommerce Configuration', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
			<div class="inside">
			<form id="bsf_css_editor" method="post" action="">
			' . wp_nonce_field( 'snippet_woocommerce_form_action', 'snippet_woocommerce_nonce_field' ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			. '
				<p> ' . esc_html__( 'WooCommerce comes with Schema.org code by default and using our plugin on WooCommerce product pages does will add duplicate schema so it is not recommended. If you could still like to enable our plugin on WooCommerce products, please enable this option.', 'all-in-one-schemaorg-rich-snippets' ) . ' </p>
				<table class="bsf_metabox" > <input type="hidden" name="site_url" value="' . esc_url( site_url() ) . '" /> </p>
					<tr>
						<td>
							<input type="checkbox" name="woocommerce_option" id="woocommerce_option" value="1" ' . esc_attr( $woo_setting ) . ' />
							<label for="woocommerce_option">Enable schema on WooCommerce products</label>
						</td>
					</tr>
					<tr>
						<td>
							<input style="margin-top:10px;" type="submit" class="button-primary" name="setting_submit" value="' . esc_html__( 'Update ', 'all-in-one-schemaorg-rich-snippets' ) . '"/>
						</td>
					</tr>
				</table>
			</form>
			</div>
		</div>' . get_support( 1 ) //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		. '
	</div>';
	echo '
<script src="' . esc_url( plugin_dir_url( __FILE__ ) ) //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
	. 'js/jquery.easytabs.min.js"></script>
<script src="' . esc_url( plugin_dir_url( __FILE__ ) ) //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
	. 'js/jquery.hashchange.min.js"></script>
<script language="javascript">
	jQuery("#tab-container").easytabs();
	jQuery("#postbox-container-1").css({"width":"87%","padding-right":"2%"});
	jQuery("#postbox-container-2").css("width","35%");
	jQuery("#postbox-container-3").css({"width":"87%","padding-right":"2%"});
	jQuery("#postbox-container-4").css("width","35%");
	jQuery("#postbox-container-5").css({"width":"87%","padding-right":"2%"});
	jQuery("#postbox-container-6").css("width","35%");
	jQuery("#postbox-container-7").css("width","35%");
	jQuery("#postbox-container-8").css("width","35%");
	jQuery("#postbox-container-9").css("width","35%");
	jQuery("#postbox-container-10").css("width","35%");
	jQuery("#postbox-container-11").css({"width":"87%","padding-right":"2%"});
	jQuery(".postbox h3").click( function() {
   		jQuery(jQuery(this).parent().get(0)).toggleClass("closed");
   	});
	jQuery(".handlediv").click( function() {
   		jQuery(jQuery(this).parent().get(0)).toggleClass("closed");
   	});
</script>';
}
// Update options.
if ( isset( $_POST['setting_submit'] ) ) {
	if ( ! isset( $_POST['snippet_woocommerce_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_woocommerce_nonce_field'], 'snippet_woocommerce_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		$args = null;
		if ( isset( $_POST['woocommerce_option'] ) ) {
			$args = true;
		} else {
			$args = false;
		}
		update_option( 'bsf_woocom_init_setting', 'done' );
		$status = update_option( 'bsf_woocom_setting', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['item_submit'] ) ) {
	if ( ! isset( $_POST['snippet_review_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_review_nonce_field'], 'snippet_review_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'review_title', 'item_reviewer', 'review_date', 'item_name', 'item_rating' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_review', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['event_submit'] ) ) {
	if ( ! isset( $_POST['snippet_event_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_event_nonce_field'], 'snippet_event_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'event_title', 'event_location', 'event_performer', 'start_time', 'end_time', 'event_desc', 'events_price' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_event', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['person_submit'] ) ) {
	if ( ! isset( $_POST['snippet_person_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_person_nonce_field'], 'snippet_person_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'person_name', 'person_nickname', 'person_job_title', 'person_website', 'person_company', 'person_address' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_person', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['product_submit'] ) ) {
	if ( ! isset( $_POST['snippet_product_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_product_nonce_field'], 'snippet_product_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'product_rating', 'product_brand', 'product_name', 'product_agr', 'product_price', 'product_avail' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_product', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['recipe_submit'] ) ) {
	if ( ! isset( $_POST['snippet_recipe_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_recipe_nonce_field'], 'snippet_recipe_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'recipe_name', 'author_name', 'recipe_pub', 'recipe_prep', 'recipe_cook', 'recipe_time', 'recipe_desc', 'recipe_rating' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_recipe', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['software_submit'] ) ) {
	if ( ! isset( $_POST['snippet_soft_app_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_soft_app_nonce_field'], 'snippet_soft_app_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'software_rating', 'software_agr', 'software_price', 'software_name', 'software_os', 'software_website' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_software', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['video_submit'] ) ) {
	if ( ! isset( $_POST['snippet_video_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_video_nonce_field'], 'snippet_video_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'video_title', 'video_desc', 'video_time', 'video_date' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_video', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['article_submit'] ) ) {
	if ( ! isset( $_POST['snippet_article_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_article_nonce_field'], 'snippet_article_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'article_name', 'article_author', 'article_desc', 'article_image', 'article_publisher', 'article_publisher_logo' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_article', $args );
		display_status( $status );
	}
}
if ( isset( $_POST['service_submit'] ) ) {
	if ( ! isset( $_POST['snippet_service_nonce_field'] ) || ! wp_verify_nonce( $_POST['snippet_service_nonce_field'], 'snippet_service_form_action' )
		) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		foreach ( array( 'snippet_title', 'service_type', 'service_area', 'service_desc', 'service_provider_name', 'provider_location', 'service_rating', 'service_channel', 'service_url_link' ) as $option ) {
			if ( isset( $_POST[ $option ] ) ) {
				$args[ $option ] = sanitize_text_field( $_POST[ $option ] );
			}
		}
		$status = update_option( 'bsf_service', $args );
		display_status( $status );
	}
}
/**
 * Display status.
 *
 * @param  string $status .
 */
function display_status( $status ) {
	if ( $status ) {
		echo '<div class="updated"><p>' . esc_html__( 'Success! Your changes were successfully saved!', 'all-in-one-schemaorg-rich-snippets' ) . '</p></div>';
	} else {
		echo '<div class="error"><p>' . esc_html__( 'Sorry, Your changes are not saved!', 'all-in-one-schemaorg-rich-snippets' ) . '</p></div>';
	}
}
if ( isset( $_GET['action'] ) ) {
	if ( 'reset' == esc_attr( $_GET['action'] ) ) {
		$option_to_reset = esc_attr( $_GET['options'] );
		if ( 'review' == $option_to_reset ) {
			delete_option( 'bsf_review' );
		}
		if ( 'event' == $option_to_reset ) {
			delete_option( 'bsf_event' );
		}
		if ( 'person' == $option_to_reset ) {
			delete_option( 'bsf_person' );
		}

		if ( 'product' == $option_to_reset ) {
			delete_option( 'bsf_product' );
		}
		if ( 'recipe' == $option_to_reset ) {
			delete_option( 'bsf_recipe' );
		}
		if ( 'software' == $option_to_reset ) {
			delete_option( 'bsf_software' );
		}
		if ( 'video' == $option_to_reset ) {
			delete_option( 'bsf_video' );
		}

		if ( 'article' == $option_to_reset ) {
			delete_option( 'bsf_article' );
		}
		if ( 'service' == $option_to_reset ) {
			delete_option( 'bsf_service' );
		}

		if ( 'color' == $option_to_reset ) {
			delete_option( 'bsf_custom' );
		}

		bsf_reset_options( $option_to_reset );
	}
}
/**
 * BSF reset option.
 *
 * @param  string $option_to_reset .
 */
function bsf_reset_options( $option_to_reset ) {
	require_once AIOSRS_PRO_DIR . '/settings.php';
	if ( 'review' == $option_to_reset ) {
		add_review_option();
	}
	if ( 'event' == $option_to_reset ) {
		add_event_option();
	}
	if ( 'person' == $option_to_reset ) {
		add_person_option();
	}
	if ( 'product' == $option_to_reset ) {
		add_product_option();
	}
	if ( 'recipe' == $option_to_reset ) {
		add_recipe_option();
	}
	if ( 'software' == $option_to_reset ) {
		add_software_option();
	}
	if ( 'video' == $option_to_reset ) {
		add_video_option();
	}
	if ( 'article' == $option_to_reset ) {
		add_article_option();
	}
	if ( 'service' == $option_to_reset ) {
		add_service_option();
	}
	if ( 'color' == $option_to_reset ) {
		add_color_option();
	}

	header( 'location:?page=rich_snippet_dashboard' );
}
/**
 * Add footer script.
 */
function add_footer_script() {
	?>
	<script type="text/javascript">
		jQuery("#submit_colors").click(function()
		{
			var data = jQuery("#bsf_css_editor").serialize();
			var form_data = "action=bsf_submit_color&" + data;
		//alert(form_data);
			jQuery.post(ajaxurl, form_data,
				function (response) {
					alert(response);
				}
			);
		});
		jQuery("#support_form").submit(function()
		{
			var data = jQuery("#support_form").serialize();
			var form_data = "action=bsf_submit_request&" + data;
		// alert(form_data);
			jQuery.post(ajaxurl, form_data,
				function (response) {
					alert(response);
					jQuery("#support_form .bsf_text_medium, #support_form .bsf_textarea_small").val("");
				}
			);
		});
	</script>
<?php }
/**
 * Get support.
 */
function get_support() {

	$html = '
		<div class="postbox bsf-contact closed">
			<button type="button" class="handlediv" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Frontend Options</span><span class="toggle-indicator" aria-hidden="true"></span></button>
			<h3 class="get_in_touch">' . esc_html__( 'Get in touch with the Plugin Developers', 'all-in-one-schemaorg-rich-snippets' ) . '</h3>
			<div class="inside">
			<form name="support" id="support_form" action="" method="post" onsubmit="return false;">'
			. wp_nonce_field( 'aiosrs_support_form', 'aiosrs_support_form_nonce' ) . '
				<p> ' . esc_html__( 'Just fill out the form below and your message will be emailed to the Plugin Developers.', 'all-in-one-schemaorg-rich-snippets' ) . ' </p>
				<table class="bsf_metabox" > <input type="hidden" name="site_url" value="' . site_url() . '" /> </p>
					<tr><td><label for="name"><strong>' . esc_html__( 'Your Name:', 'all-in-one-schemaorg-rich-snippets' ) . '<span style="color:red;"> *</span></strong> </label></td>
						<td><input required="required" type="text" class="bsf_text_medium" name="name" /></td></tr>
					<tr><td><label for="email"><strong>' . esc_html__( 'Your Email:', 'all-in-one-schemaorg-rich-snippets' ) . '<span style="color:red;"> *</span></strong> </label></td>
						<td><input required="required" type="text" class="bsf_text_medium" name="email" /></td></tr>
					<tr><td><label for="post_url"><strong>' . esc_html__( 'Reference URL:', 'all-in-one-schemaorg-rich-snippets' ) . '<span style="color:red;"> *</span></strong> </label></td>
						<td><input required="required" type="text" class="bsf_text_medium" name="post_url" /></td></tr>
					<tr><td><label for="subject"><strong>' . esc_html__( 'Subject:', 'all-in-one-schemaorg-rich-snippets' ) . '</strong> </label></td>
						<td>
							<select class="select_full" name="subject"> 
								<option value="question"> I have a question </option>
								<option value="bug"> I found a bug </option>
								<option value="help"> I need help </option>
								<option value="professional">  I need professional service </option>
								<option value="contribute"> I want to contribute my code</option>
								<option value="other">  Other </option>								
							</select>
						</td><td></td></tr>
					<tr><td class="bsf_label"><label for="message"><strong>' . esc_html__( 'Your Query in Brief:', 'all-in-one-schemaorg-rich-snippets' ) . '</strong> </label></td>
						<td rowspan="4"><textarea class="bsf_textarea_small" name="message" required></textarea> </td></tr>
						<tr></tr> <tr></tr> <tr></tr>
					<tr><td></td>
						<td><input id="submit_request" class="button-primary" type="submit" value="Submit Request" /> <span id="status"></span></td></tr>
				</table>
			</form>
			</div>
		</div>
		</div>
		</div>
		</div>
	';
	return $html;
}
?>
