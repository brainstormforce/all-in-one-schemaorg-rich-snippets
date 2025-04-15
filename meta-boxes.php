<?php
/**
 * Meta Boxes.
 *
 * @package AIOSRS.
 */

/**
 * Metabox for review.
 *
 * @param array $meta_boxes Meta Boxes.
 */
function bsf_metaboxes( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list.
	$prefix     = '_bsf_';
	$post_types = get_post_types( '', 'names' );

	if ( ! get_option( 'bsf_woocom_init_setting' ) ) {
		$woo_settings = true;
	} else {
		$woo_settings = get_option( 'bsf_woocom_setting' );
	}

	if ( empty( $woo_settings ) ) {

		$woocommerce_post_type = [ 'product', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_coupon', 'shop_webhook' ];
		$required_post_type    = array_diff( $post_types, $woocommerce_post_type );

	} else {

		$exclude_custom_post_type = apply_filters( 'bsf_exclude_custom_post_type', [] );
		$required_post_type       = array_diff( $post_types, $exclude_custom_post_type );

	}

	$meta_boxes[] = [
		'id'         => 'review_metabox',
		'title'      => __( 'Configure Rich Snippet', 'rich-snippets' ),
		'pages'      => $required_post_type,
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left.
		'fields'     => [
			[
				'name'    => '',
				'desc'    => '',
				'id'      => $prefix . 'post_type',
				'class'   => 'snippet-type',
				'type'    => 'select',
				'options' => [
					[
						'name'  => __( 'Select what this post is about', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '0',
					],
					[
						'name'  => __( 'Item Review', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '1',
					],
					[
						'name'  => __( 'Event', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '2',
					],
					[
						'name'  => __( 'Person', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '5',
					],
					[
						'name'  => __( 'Product', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '6',
					],
					[
						'name'  => __( 'Recipe', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '7',
					],
					[
						'name'  => __( 'Software Application', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '8',
					],
					[
						'name'  => __( 'Video', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '9',
					],
					[
						'name'  => __( 'Article', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '10',
					],
					[
						'name'  => __( 'Service', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '11',
					],

				],
			],
			// Meta Settings for Item Review.
			[
				'name'  => __( 'Rich Snippets - Item Review', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'review_title',
				'class' => 'review',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Reviewer&rsquo;s Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the name of Item Reviewer or The Post Author.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_reviewer',
				'class' => 'review',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Item to be reviewed', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the name of the item, you are writing review about.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_name',
				'class' => 'review',
				'type'  => 'text',
			],
			[
				'name'    => __( 'Item Review Type', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( 'Select the item to be reviewed.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'item_review_type',
				'class'   => 'item_type review',
				'type'    => 'select',
				'options' => [
					[
						'name'  => __( 'Select Item type', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'none',
					],
					[
						'name'  => __( 'Event', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'item_event',
					],
					[
						'name'  => __( 'Product', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'item_product',
					],
					[
						'name'  => __( 'Recipe', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'item_recipe',
					],
					[
						'name'  => __( 'Software Application', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'item_software',
					],
					[
						'name'  => __( 'Video', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'item_video',
					],
				],
			],
			[
				'name'  => __( 'Event Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Event name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_event_name',
				'class' => 'event_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Event Start Date ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Provide the Event Start Date.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_event_start_date',
				'class' => 'event_item_type',
				'type'  => 'text_date',
			],
			[
				'name'  => __( 'Event Location ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Location Name Here', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_event_organization',
				'class' => 'event_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Street Address', 'rich-snippets' ),
				'id'    => $prefix . 'item_event_street',
				'class' => 'event_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Locality', 'rich-snippets' ),
				'id'    => $prefix . 'item_event_local',
				'class' => 'event_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Region', 'rich-snippets' ),
				'id'    => $prefix . 'item_event_region',
				'class' => 'event_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Postal Code', 'rich-snippets' ),
				'id'    => $prefix . 'item_event_postal_code',
				'class' => 'event_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Product Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Product name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_pro_name',
				'class' => 'product_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Product Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload the product image or select from library. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_pro_image',
				'class' => 'product_item_type',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Product Price', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the product Price.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_pro_price',
				'class' => 'product_item_type',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Currency ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Currency Code(e.g USD, INR, AUD, EUR, GBP). <a href="http://www.science.co.il/International/Currency-Codes.asp" target="_blank"> Know you currency code</a>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_pro_cur',
				'class' => 'product_item_type',
				'type'  => 'text_small',
			],
			[
				'name'    => __( 'Availability', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( 'Select the products availability.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'item_pro_status',
				'class'   => 'product_item_type',
				'type'    => 'select',
				'options' => [
					[
						'name'  => __( '__ Please Select __', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '',
					],
					[
						'name'  => __( 'Out of Stock', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'out_of_stock',
					],
					[
						'name'  => __( 'In Stock', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'in_stock',
					],
					[
						'name'  => __( 'In Store Only', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'instore_only',
					],
					[
						'name'  => __( 'Pre-Order', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'preorder',
					],
				],
			],
			[
				'name'  => __( 'Recipe Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the recipe name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_recipes_name',
				'class' => 'recipes_item_type',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Recipe Photo', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or Select recipe photo. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_recipes_photo',
				'class' => 'recipes_item_type',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Software Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Software name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_soft_name',
				'class' => 'soft_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Software Operating System', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Operating System name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_os_name',
				'class' => 'soft_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Software Application Category', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Application Category name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_app_name',
				'class' => 'soft_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Video Title', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the title for this video', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_video_title',
				'class' => 'video_item_type',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Video Description', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the brief description for this video', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_video_desc',
				'class' => 'video_item_type',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Video Thumbnail', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select video thumbnail from gallery. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_video_thumb',
				'class' => 'video_item_type',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Upload Date', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Provide the date when the video is uploaded', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'item_video_date',
				'class' => 'video_item_type',
				'type'  => 'text_date',
			],
			[
				'name'    => __( 'Your Rating', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( '&nbsp;&nbsp;Rate this item (1-5)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'rating',
				'class'   => 'star review',
				'type'    => 'radio',
				'options' => [
					[
						'name'  => '',
						'value' => '1',
					],
					[
						'name'  => '',
						'value' => '2',
					],
					[
						'name'  => '',
						'value' => '3',
					],
					[
						'name'  => '',
						'value' => '4',
					],
					[
						'name'  => '',
						'value' => '5',
					],
				],
			],
			// Meta Settings for Events.
			[
				'name'  => __( 'Rich Snippets - Events', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'events',
				'class' => 'events',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Event Title ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'What would be the name of the event?', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_title',
				'class' => 'events',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Location ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Location Name Here', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_organization',
				'class' => 'events',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Street Address', 'rich-snippets' ),
				'id'    => $prefix . 'event_street',
				'class' => 'events',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Locality', 'rich-snippets' ),
				'id'    => $prefix . 'event_local',
				'class' => 'events',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Region', 'rich-snippets' ),
				'id'    => $prefix . 'event_region',
				'class' => 'events',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Postal Code', 'rich-snippets' ),
				'id'    => $prefix . 'event_postal_code',
				'class' => 'events',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Description ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Describe the event in short.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_desc',
				'class' => 'events',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Start Date ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Provide the Event Start Date.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_start_date',
				'class' => 'events',
				'type'  => 'text_date',
			],
			[
				'name'  => __( 'End Date ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Provide the Event End Date.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_end_date',
				'class' => 'events',
				'type'  => 'text_date',
			],
			[
				'name'  => __( 'Offer Price', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the ticket Price.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_price',
				'class' => 'events',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Currency ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Currency Code(e.g USD, INR, AUD, EUR, GBP). <a href="http://www.science.co.il/International/Currency-Codes.asp" target="_blank"> Know you currency code</a>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_cur',
				'class' => 'events',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Url ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Url of buy ticket page', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'event_ticket_url',
				'class' => 'events',
				'type'  => 'text',
			],
			// Meta Settings for Music.
			[
				'name'  => __( 'Rich Snippets - Music', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'music',
				'class' => 'music',
				'type'  => 'title',
			],
			// Meta Settings for Organization.
			[
				'name'  => __( 'Rich Snippets - Organization', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization',
				'class' => 'organization',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Organization Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Company or Organization Name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization_name',
				'class' => 'organization',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Website URL ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Organization homepage url.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization_url',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Contact No. ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Telephone No.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization_tel',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Address ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Street Name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization_street',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Locality.', 'rich-snippets' ),
				'id'    => $prefix . 'organization_local',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Region.', 'rich-snippets' ),
				'id'    => $prefix . 'organization_region',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Postal Code', 'rich-snippets' ),
				'id'    => $prefix . 'organization_zip',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Country Name', 'rich-snippets' ),
				'id'    => $prefix . 'organization_country',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'GEO Location', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Latitude. <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">Find Here.</a>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'organization_latitide',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Longitude.', 'rich-snippets' ),
				'id'    => $prefix . 'organization_longitude',
				'class' => 'organization',
				'type'  => 'text_medium',
			],
			// Meta Settings for People.
			[
				'name'  => __( 'Rich Snippets - Person', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people',
				'class' => 'people',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Person&lsquo;s Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the relative person&lsquo;s name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_fn',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Nickname', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the nickname (if any).', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_nickname',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Photograph', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload the photo or select from media library. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_photo',
				'class' => 'people',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Job Title ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter job title.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_job_title',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Homepage URL', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter homepage URL(if any).', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_website',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Company / Organization', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter Company or Organization name in affiliation.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_company',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Address', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter Street', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'people_street',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Enter Locality', 'rich-snippets' ),
				'id'    => $prefix . 'people_local',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Region', 'rich-snippets' ),
				'id'    => $prefix . 'people_region',
				'class' => 'people',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Postal Code', 'rich-snippets' ),
				'id'    => $prefix . 'people_postal',
				'class' => 'people',
				'type'  => 'text_medium',
			],

			// Meta Settings for Products.
			[
				'name'  => __( 'Rich Snippets - Products', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product',
				'class' => 'product',
				'type'  => 'title',
			],
			[
				'name'    => __( 'Your Rating', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( 'Rate this product or aggregate rating.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'product_rating',
				'class'   => 'star product',
				'type'    => 'radio',
				'options' => [
					[
						'name'  => '',
						'value' => '1',
					],
					[
						'name'  => '',
						'value' => '2',
					],
					[
						'name'  => '',
						'value' => '3',
					],
					[
						'name'  => '',
						'value' => '4',
					],
					[
						'name'  => '',
						'value' => '5',
					],
				],
			],
			[
				'name'  => __( 'Brand Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the products brand name', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product_brand',
				'class' => 'product',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Product Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the product name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product_name',
				'class' => 'product',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Product Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload the product image or select from library. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product_image',
				'class' => 'product',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Product Price', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the product Price.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product_price',
				'class' => 'product',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Currency ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Currency Code(e.g USD, INR, AUD, EUR, GBP). <a href="http://www.science.co.il/International/Currency-Codes.asp" target="_blank"> Know you currency code</a>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'product_cur',
				'class' => 'product',
				'type'  => 'text_small',
			],
			[
				'name'    => __( 'Availability', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( 'Select the products availability.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'product_status',
				'class'   => 'product',
				'type'    => 'select',
				'options' => [
					[
						'name'  => __( '__ Please Select __', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => '',
					],
					[
						'name'  => __( 'Out of Stock', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'out_of_stock',
					],
					[
						'name'  => __( 'In Stock', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'in_stock',
					],
					[
						'name'  => __( 'In Store Only', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'instore_only',
					],
					[
						'name'  => __( 'Pre-Order', 'all-in-one-schemaorg-rich-snippets' ),
						'value' => 'preorder',
					],
				],
			],          // Meta Settings for Recipes.
			[
				'name'  => __( 'Rich Snippets - Recipes', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes',
				'class' => 'recipes',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Recipe Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the recipe name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_name',
				'class' => 'recipes',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Author Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Author name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'authors_name',
				'class' => 'recipes',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Time Required ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Preperation time  (Format: 1H30M. H - Hours, M - Minutes )', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_preptime',
				'class' => 'recipes',
				'type'  => 'text_small',
			],
			[
				'name'  => '',
				'desc'  => __( 'Cook Time. (Format: 1H30M. H - Hours, M - Minutes )', 'rich-snippets' ),
				'id'    => $prefix . 'recipes_cooktime',
				'class' => 'recipes',
				'type'  => 'text_small',
			],
			[
				'name'  => '',
				'desc'  => __( 'Total Time  (Format: 1H30M. H - Hours, M - Minutes )', 'rich-snippets' ),
				'id'    => $prefix . 'recipes_totaltime',
				'class' => 'recipes',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Description', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Describe the recipe in short.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_desc',
				'class' => 'recipes',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Nutrition', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Nutrition', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_nutrition',
				'class' => 'recipes',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Ingredients', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the ingredients used', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_ingredient',
				'class' => 'recipes',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Recipe Photo', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or Select recipe photo. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'recipes_photo',
				'class' => 'recipes',
				'type'  => 'file',
			],
			// Meta Settings for Software Application.
			[
				'name'  => __( 'Rich Snippets - Software Application', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software',
				'class' => 'software',
				'type'  => 'title',
			],
			[
				'name'    => __( 'Software Rating', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'    => __( 'Rate this software.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'      => $prefix . 'software_rating',
				'class'   => 'star software',
				'type'    => 'radio',
				'options' => [
					[
						'name'  => '',
						'value' => '1',
					],
					[
						'name'  => '',
						'value' => '2',
					],
					[
						'name'  => '',
						'value' => '3',
					],
					[
						'name'  => '',
						'value' => '4',
					],
					[
						'name'  => '',
						'value' => '5',
					],
				],
			],
			[
				'name'  => __( 'Price', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Price of Software', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_price',
				'class' => 'software',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Currency', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Currency Code(e.g USD, INR, AUD, EUR, GBP). <a href="http://www.science.co.il/International/Currency-Codes.asp" target="_blank"> Know you currency code</a>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_cur',
				'class' => 'software',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Software Name ', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the Software Name.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_name',
				'class' => 'software',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Operating System', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the software Operating System.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_os',
				'class' => 'software',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Application Category', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Like Game, Multimedia', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_cat',
				'class' => 'software',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Software Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select image of software. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_image',
				'class' => 'software',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Landing Page', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the landing page url for software', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'software_landing',
				'class' => 'software',
				'type'  => 'text',
			],          // Meta Settings for Video.
			[
				'name'  => __( 'Rich Snippets - Videos', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video',
				'class' => 'video',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Video Title', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the title for this video', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_title',
				'class' => 'video',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Video Description', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the brief description for this video', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_desc',
				'class' => 'video',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Video Thumbnail', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select video thumbnail from gallery. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_thumb',
				'class' => 'video',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Video', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload Video or enter the video file url<br>Example: <br> http://www.example.com/video123.flv<br>A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .avi, .flv, or other video file format', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_url',
				'class' => 'video',
				'type'  => 'file',
			],

			[
				'name'  => __( 'Embed Video', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'A URL pointing to a player for the specific video. Usually this is the information in the src element of an &lt;embed&gt;tag. <br>Example: <br>Youtube: https://www.youtube.com/embed/CibazcCevOk <br>Dailymotion: http://www.dailymotion.com/swf/x1o2g<br><div class="bsf_vd_note"><h3>Add only one url either in "Video" or "Embed Video" field. Dont use both.</h3></div>', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_emb_url',
				'class' => 'video',
				'type'  => 'text',
			],

			[
				'name'  => __( 'Video Duration', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the duration for this video', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_duration',
				'class' => 'video',
				'type'  => 'text_small',
			],
			[
				'name'  => __( 'Upload Date', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Provide the date when the video is uploaded', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'video_date',
				'class' => 'video',
				'type'  => 'text_date',
			],
			// Meta Settings for Article.
			[
				'name'  => __( 'Rich Snippets - Article', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article',
				'class' => 'article',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Article Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select image from gallery. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_image',
				'class' => 'article',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Article Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the name for this article', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_name',
				'class' => 'article',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Short Description', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the brief description about this article (About 30 Words)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_desc',
				'class' => 'article',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Author', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the author name for this article', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_author',
				'class' => 'article',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Publisher - Orgnization', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the publisher name for this article', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_publisher',
				'class' => 'article',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Publisher Logo', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select image from gallery. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'article_publisher_logo',
				'class' => 'article',
				'type'  => 'file',
			],

			// Meta Settings for Service.
			[
				'name'  => __( 'Rich Snippets - Service', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Please provide the following information.', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service',
				'class' => 'service',
				'type'  => 'title',
			],
			[
				'name'  => __( 'Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload or select image from gallery. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_image',
				'class' => 'service',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Service Type', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the service type', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_type',
				'class' => 'service',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Service Served Area', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the area where service is available', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_area',
				'class' => 'service',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Short Description', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the description about service (About 30 Words)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_desc',
				'class' => 'service',
				'type'  => 'textarea_small',
			],
			[
				'name'  => __( 'Provider Name', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Enter the service provider name', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_provider',
				'class' => 'service',
				'type'  => 'text',
			],
			[
				'name'  => __( 'Location', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Street Address', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_street',
				'class' => 'service',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Locality', 'rich-snippets' ),
				'id'    => $prefix . 'service_local',
				'class' => 'service',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Region', 'rich-snippets' ),
				'id'    => $prefix . 'service_region',
				'class' => 'service',
				'type'  => 'text_medium',
			],
			[
				'name'  => '',
				'desc'  => __( 'Postal Code', 'rich-snippets' ),
				'id'    => $prefix . 'service_postal_code',
				'class' => 'service',
				'type'  => 'text_medium',
			],
			[
				'name'  => __( 'Provider Location Image', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Upload the provider location image or select from library. Medium size is recommended (300px X 300px)', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'provider_location_image',
				'class' => 'service',
				'type'  => 'file',
			],
			[
				'name'  => __( 'Telephone', 'all-in-one-schemaorg-rich-snippets' ),
				'desc'  => __( 'Telephone number', 'all-in-one-schemaorg-rich-snippets' ),
				'id'    => $prefix . 'service_telephone',
				'class' => 'service',
				'type'  => 'text_medium',
			],
		],
	];
	// Add other metaboxes as needed.
	return $meta_boxes;
}
