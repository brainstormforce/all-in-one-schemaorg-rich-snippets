jQuery(document).ready(function() {
	// Safety check: Don't run on WooCommerce product pages
	if (jQuery('body').hasClass('post-type-product') || jQuery('#product-type').length > 0) {
		return;
	}
	
	// Additional safety check: Only run if schema elements exist
	if (!jQuery("#_bsf_post_type").length) {
		return;
	}
		var selected = jQuery("#_bsf_post_type").val();
	var item_type = jQuery("#_bsf_item_review_type").val();
	if(selected == "0")
		hidden();
	else
		expand_default(selected);
	jQuery(window).on('load',function () {
		if(item_type == "none" || (selected != "1" && item_type != "none")) {
			item_hidden();
		} else {
			item_expand_default(item_type);
		}
	});

//Function to hide all the snippet blocks
function hidden() {
	jQuery(".review").hide().attr("aria-hidden", "true");
	jQuery(".events").hide().attr("aria-hidden", "true");
	jQuery(".music").hide().attr("aria-hidden", "true");
	jQuery(".organization").hide().attr("aria-hidden", "true");
	jQuery(".people").hide().attr("aria-hidden", "true");
	jQuery(".product").hide().attr("aria-hidden", "true");
	jQuery(".recipes").hide().attr("aria-hidden", "true");
	jQuery(".software").hide().attr("aria-hidden", "true");
	jQuery(".video").hide().attr("aria-hidden", "true");
	jQuery(".article").hide().attr("aria-hidden", "true");
	jQuery(".service").hide().attr("aria-hidden", "true");

}

//Function to hide all the snippet blocks
function item_hidden() {
	jQuery(".soft_item_type").hide().attr("aria-hidden", "true");
	jQuery(".event_item_type").hide().attr("aria-hidden", "true");
	jQuery(".product_item_type").hide().attr("aria-hidden", "true");
	jQuery(".recipes_item_type").hide().attr("aria-hidden", "true");
	jQuery(".video_item_type").hide().attr("aria-hidden", "true");
}

//Function to expand the updated snippet block
function expand_default(selected) {
	hidden();
	item_hidden();
	var typeMap = {'1':'.review','2':'.events','3':'.music','4':'.organization','5':'.people','6':'.product','7':'.recipes','8':'.software','9':'.video','10':'.article','11':'.service'};
	if(typeMap[selected]) {
		jQuery(typeMap[selected]).show(500).removeAttr("aria-hidden");
	}
}
    jQuery("#_bsf_post_type").change(function() {
		hidden();
		item_hidden();
		var type=jQuery(this).val();
		if(type == '1' && 'none' != item_type ){
			item_expand_default(item_type);
		}
		var typeMap = {'1':'.review','2':'.events','3':'.music','4':'.organization','5':'.people','6':'.product','7':'.recipes','8':'.software','9':'.video','10':'.article','11':'.service'};
		if(typeMap[type]) {
			jQuery(typeMap[type]).show(500).removeAttr("aria-hidden");
		}
	});

//Function to expand the item review updated snippet block.
	function item_expand_default(item_type) {
		item_hidden();
		var itemMap = {'item_software':'.soft_item_type','item_event':'.event_item_type','item_product':'.product_item_type','item_recipe':'.recipes_item_type','item_video':'.video_item_type'};
		if(itemMap[item_type]) {
			jQuery(itemMap[item_type]).show(500).removeAttr("aria-hidden");
		}
	}
    jQuery("#_bsf_item_review_type").change(function() {
		item_hidden();
		var type=jQuery(this).val();
		var itemMap = {'item_software':'.soft_item_type','item_event':'.event_item_type','item_product':'.product_item_type','item_recipe':'.recipes_item_type','item_video':'.video_item_type'};
		if(itemMap[type]) {
			jQuery(itemMap[type]).show(500).removeAttr("aria-hidden");
		}
	});
});
