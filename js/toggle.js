jQuery(document).ready(function() {
	// Only run if the rich snippet meta box is on this screen. PHP decides
	// where the meta box and this script load (see bsf_get_excluded_post_types).
	if (!jQuery("#_bsf_post_type").length) {
		return;
	}

	// Scope to the meta box field rows only. Bare class selectors like
	// ".product" also match admin body classes and select options on
	// WooCommerce product screens, hiding the whole page.
	function section(cls) {
		return jQuery("#review_metabox tr" + cls);
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
	section(".review").hide().attr("aria-hidden", "true");
	section(".events").hide().attr("aria-hidden", "true");
	section(".music").hide().attr("aria-hidden", "true");
	section(".organization").hide().attr("aria-hidden", "true");
	section(".people").hide().attr("aria-hidden", "true");
	section(".product").hide().attr("aria-hidden", "true");
	section(".recipes").hide().attr("aria-hidden", "true");
	section(".software").hide().attr("aria-hidden", "true");
	section(".video").hide().attr("aria-hidden", "true");
	section(".article").hide().attr("aria-hidden", "true");
	section(".service").hide().attr("aria-hidden", "true");

}

//Function to hide all the snippet blocks
function item_hidden() {
	section(".soft_item_type").hide().attr("aria-hidden", "true");
	section(".event_item_type").hide().attr("aria-hidden", "true");
	section(".product_item_type").hide().attr("aria-hidden", "true");
	section(".recipes_item_type").hide().attr("aria-hidden", "true");
	section(".video_item_type").hide().attr("aria-hidden", "true");
}

//Function to expand the updated snippet block
function expand_default(selected) {
	hidden();
	item_hidden();
	var typeMap = {'1':'.review','2':'.events','3':'.music','4':'.organization','5':'.people','6':'.product','7':'.recipes','8':'.software','9':'.video','10':'.article','11':'.service'};
	if(typeMap[selected]) {
		section(typeMap[selected]).show(500).removeAttr("aria-hidden");
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
			section(typeMap[type]).show(500).removeAttr("aria-hidden");
		}
	});

//Function to expand the item review updated snippet block.
	function item_expand_default(item_type) {
		item_hidden();
		var itemMap = {'item_software':'.soft_item_type','item_event':'.event_item_type','item_product':'.product_item_type','item_recipe':'.recipes_item_type','item_video':'.video_item_type'};
		if(itemMap[item_type]) {
			section(itemMap[item_type]).show(500).removeAttr("aria-hidden");
		}
	}
    jQuery("#_bsf_item_review_type").change(function() {
		item_hidden();
		var type=jQuery(this).val();
		var itemMap = {'item_software':'.soft_item_type','item_event':'.event_item_type','item_product':'.product_item_type','item_recipe':'.recipes_item_type','item_video':'.video_item_type'};
		if(itemMap[type]) {
			section(itemMap[type]).show(500).removeAttr("aria-hidden");
		}
	});
});
