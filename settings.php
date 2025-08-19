<?php
/**
 * Settings.
 *
 * @package AIOSRS.
 */

/**
 * Function to add review option for settings.
 */
function add_review_option() {
	$review_opt = array(
		'review_title'  => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'item_reviewer' => __( 'Reviewer', 'all-in-one-schemaorg-rich-snippets' ),
		'review_date'   => __( 'Review Date', 'all-in-one-schemaorg-rich-snippets' ),
		'item_name'     => __( 'Reviewed Item', 'all-in-one-schemaorg-rich-snippets' ),
		'item_rating'   => __( 'Author Rating', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_review', $review_opt );
}

/**
 * Function to add event option for settings.
 */
function add_event_option() {
	$event_opt = array(
		'snippet_title'  => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'event_title'    => __( 'Event', 'all-in-one-schemaorg-rich-snippets' ),
		'event_location' => __( 'Location', 'all-in-one-schemaorg-rich-snippets' ),
		'event_desc'     => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
		'start_time'     => __( 'Starting on', 'all-in-one-schemaorg-rich-snippets' ),
		'end_time'       => __( 'Ending on', 'all-in-one-schemaorg-rich-snippets' ),
		'events_price'   => __( 'Offer Price', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_event', $event_opt );
}

/**
 *  Function to add person option for settings.
 */
function add_person_option() {
	$person_opt = array(
		'snippet_title'    => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'person_name'      => __( 'Name', 'all-in-one-schemaorg-rich-snippets' ),
		'person_nickname'  => __( 'Nickname', 'all-in-one-schemaorg-rich-snippets' ),
		'person_job_title' => __( 'Job Title', 'all-in-one-schemaorg-rich-snippets' ),
		'person_website'   => __( 'Website', 'all-in-one-schemaorg-rich-snippets' ),
		'person_company'   => __( 'Company', 'all-in-one-schemaorg-rich-snippets' ),
		'person_address'   => __( 'Address', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_person', $person_opt );
}

/**
 *  Function to add product option for settings.
 */
function add_product_option() {
	$product_opt = array(
		'snippet_title'  => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'product_rating' => __( 'Author Rating', 'all-in-one-schemaorg-rich-snippets' ),
		'product_brand'  => __( 'Brand Name', 'all-in-one-schemaorg-rich-snippets' ),
		'product_name'   => __( 'Product Name', 'all-in-one-schemaorg-rich-snippets' ),
		'product_agr'    => __( 'Aggregate Rating', 'all-in-one-schemaorg-rich-snippets' ),
		'product_price'  => __( 'Price', 'all-in-one-schemaorg-rich-snippets' ),
		'product_avail'  => __( 'Product Availability', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_product', $product_opt );
}

/**
 *  Function to add recipe option for settings.
 */
function add_recipe_option() {
	$recipe_opt = array(
		'snippet_title'     => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_name'       => __( 'Recipe Name', 'all-in-one-schemaorg-rich-snippets' ),
		'author_name'       => __( 'Author Name', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_pub'        => __( 'Published On', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_prep'       => __( 'Preparation Time', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_cook'       => __( 'Cook Time', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_time'       => __( 'Total Time', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_desc'       => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_nutrition'  => __( 'Nutrition', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_ingredient' => __( 'Ingredients', 'all-in-one-schemaorg-rich-snippets' ),
		'recipe_rating'     => __( 'Average Rating', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_recipe', $recipe_opt );
}

/**
 *  Function to add software option for settings.
 */
function add_software_option() {
	$software_opt = array(
		'snippet_title'    => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'software_rating'  => __( 'Author Rating', 'all-in-one-schemaorg-rich-snippets' ),
		'software_agr'     => __( 'Aggregate Rating', 'all-in-one-schemaorg-rich-snippets' ),
		'software_price'   => __( 'Price', 'all-in-one-schemaorg-rich-snippets' ),
		'software_name'    => __( 'Software Name', 'all-in-one-schemaorg-rich-snippets' ),
		'software_os'      => __( 'Operating System', 'all-in-one-schemaorg-rich-snippets' ),
		'software_website' => __( 'Landing Page', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_software', $software_opt );
}

/**
 *  Function to add video option for settings.
 */
function add_video_option() {
	$video_opt = array(
		'snippet_title' => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'video_title'   => __( 'Title', 'all-in-one-schemaorg-rich-snippets' ),
		'video_desc'    => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
		'video_time'    => __( 'Duration', 'all-in-one-schemaorg-rich-snippets' ),
		'video_date'    => __( 'Upload Date', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_video', $video_opt );
}
/**
 *  Function to add article option for settings.
 */
function add_article_option() {
	$article_opt = array(
		'snippet_title'          => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'article_name'           => __( 'Article Name', 'all-in-one-schemaorg-rich-snippets' ),
		'article_author'         => __( 'Author', 'all-in-one-schemaorg-rich-snippets' ),
		'article_desc'           => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
		'article_image'          => __( 'Image', 'all-in-one-schemaorg-rich-snippets' ),
		'article_publisher'      => __( 'Publisher Name', 'all-in-one-schemaorg-rich-snippets' ),
		'article_publisher_logo' => __( 'Publisher Logo', 'all-in-one-schemaorg-rich-snippets' ),

	);
	add_option( 'bsf_article', $article_opt );
}
/**
 *  Function to add article option for settings.
 */
function add_service_option() {
	$service_opt = array(
		'snippet_title'         => __( 'Summary', 'all-in-one-schemaorg-rich-snippets' ),
		'service_type'          => __( 'Service Type', 'all-in-one-schemaorg-rich-snippets' ),
		'service_area'          => __( 'Area', 'all-in-one-schemaorg-rich-snippets' ),
		'service_desc'          => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
		'service_channel'       => __( 'URL', 'all-in-one-schemaorg-rich-snippets' ),
		'service_url_link'      => __( 'Click Here For More Info', 'all-in-one-schemaorg-rich-snippets' ),
		'service_rating'        => __( 'User Rating', 'all-in-one-schemaorg-rich-snippets' ),
		'service_provider_name' => __( 'Provider Name', 'all-in-one-schemaorg-rich-snippets' ),
		'provider_location'     => __( 'Location', 'all-in-one-schemaorg-rich-snippets' ),
		'service_telephone'     => __( 'Provider telephone number', 'all-in-one-schemaorg-rich-snippets' ),
	);
	add_option( 'bsf_service', $service_opt );
}
/**
 *  Function for customization
 */
function add_color_option() {
	$color_opt = array(
		'snippet_box_bg'      => '#F5F5F5',
		'snippet_title_bg'    => '#E4E4E4',
		'snippet_border'      => '#ACACAC',
		'snippet_title_color' => '#333333',
		'snippet_box_color'   => '#333333',
	);
	add_option( 'bsf_custom', $color_opt );
}

/**
 *  Function for customization
 */
function add_woo_commerce_option() {
	if ( ! get_option( 'bsf_woocom_init_setting' ) ) {

		$woo_opt = false;

		if ( get_option( 'bsf_custom' ) ) {
			$woo_opt = true;
		}

		add_option( 'bsf_woocom_setting', $woo_opt );
		add_option( 'bsf_woocom_init_setting', 'done' );
	}
}


