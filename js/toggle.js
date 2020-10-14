jQuery(document).ready(function() {
	var selected = jQuery("#_bsf_post_type").val();
	var item_type = jQuery("#_bsf_item_review_type").val();
	if(selected == "0")
		hidden();
	else
		expand_default(selected);
	jQuery(window).on('load',function () {
		if(item_type == "none")
			item_hidden();
		else if(selected != "1" && item_type != "none")
			item_hidden();
		else
			item_expand_default(item_type);
	});

//Function to hide all the snippet blocks
function hidden() {
	jQuery(".review").hide();	
	jQuery(".events").hide();
	jQuery(".music").hide();
	jQuery(".organization").hide();
	jQuery(".people").hide();
	jQuery(".product").hide();
	jQuery(".recipes").hide();
	jQuery(".software").hide();
	jQuery(".video").hide();
	jQuery(".article").hide();
	jQuery(".service").hide();

}

//Function to hide all the snippet blocks
function item_hidden() {
	jQuery(".soft_item_type").hide();
	jQuery(".event_item_type").hide();
	jQuery(".product_item_type").hide();
	jQuery(".recipes_item_type").hide();
	jQuery(".video_item_type").hide();
}

//Function to expand the updated snippet block
function expand_default(selected) {
	hidden();
	item_hidden();
	if(selected == '1')
	{
		jQuery(".review").show(500);
	} 
	else if(selected == '2')
	{
		jQuery(".events").show(500);
	}
	else if(selected == '3')
	{
		jQuery(".music").show(500);
	}
	else if(selected == '4')
	{
		jQuery(".organization").show(500);
	}
	else if(selected == '5')
	{
		jQuery(".people").show(500);
	}
	else if(selected == '6')
	{
		jQuery(".product").show(500);
	}
	else if(selected == '7')
	{
		jQuery(".recipes").show(500);
	}
	else if(selected == '8')
	{
		jQuery(".software").show(500);
	}
	else if(selected == '9')
	{
		jQuery(".video").show(500);
	}
	else if(selected == '10')
	{
		jQuery(".article").show(500);
	}
	else if(selected == '11')
	{
		jQuery(".service").show(500);
	}
}
    jQuery("#_bsf_post_type").change(function() {
		hidden();
		item_hidden();
		var type=jQuery(this).val();
		if(type == '1' && 'none' != item_type ){
			item_expand_default(item_type);
		}
		if(type == '1')
		{
			jQuery(".review").show(500);
		} 
		else if(type == '2')
		{
			jQuery(".events").show(500);
		}
		else if(type == '3')
		{
			jQuery(".music").show(500);
		}
		else if(type == '4')
		{
			jQuery(".organization").show(500);
		}
		else if(type == '5')
		{
			jQuery(".people").show(500);
		}
		else if(type == '6')
		{
			jQuery(".product").show(500);
		}
		else if(type == '7')
		{
			jQuery(".recipes").show(500);
		}
		else if(type == '8')
		{
			jQuery(".software").show(500);
		}
		else if(type == '9')
		{
			jQuery(".video").show(500);
		}
		else if(type == '10')
		{
			jQuery(".article").show(500);
		}
		else if(type == '11')
		{
			jQuery(".service").show(500);
		}
	});

//Function to expand the item review updated snippet block.
	function item_expand_default(item_type) {
		item_hidden();
		if(item_type == 'item_software')
		{
			jQuery(".soft_item_type").show(500);
		}
		if(item_type == 'item_event')
		{
			jQuery(".event_item_type").show(500);
		}
		if(item_type == 'item_product')
		{
			jQuery(".product_item_type").show(500);
		}
		if(item_type == 'item_recipe')
		{
			jQuery(".recipes_item_type").show(500);
		}
		if(item_type == 'item_video')
		{
			jQuery(".video_item_type").show(500);
		}
	}
    jQuery("#_bsf_item_review_type").change(function() {
		item_hidden();
		var type=jQuery(this).val();
		if(type == 'item_software')
		{
			jQuery(".soft_item_type").show(500);
		}
		if(type == 'item_event')
		{
			jQuery(".event_item_type").show(500);
		}
		if(type == 'item_product')
		{
			jQuery(".product_item_type").show(500);
		}
		if(type == 'item_recipe')
		{
			jQuery(".recipes_item_type").show(500);
		}
		if(type == 'item_video')
		{
			jQuery(".video_item_type").show(500);
		}
	});
});