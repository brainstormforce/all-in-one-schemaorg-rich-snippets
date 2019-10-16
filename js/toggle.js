jQuery(document).ready(function() {
	var selected = jQuery("#_bsf_post_type").val();
	var item_type = jQuery("#_bsf_item_review_type").val();
	if(selected == "0")
		hidden();
	else
		expand_default(selected);

	if(item_type == "0")
		item_hidden();
	else
		item_expand_default(item_type);
	
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

//Function to hide all the item review snippet blocks
	function item_hidden() {
		jQuery(".pro_name").hide();
	}
// Function to display the all items
	function display_item_hidden() {
		jQuery(".pro_name").show();
	}

//Function to expand the updated snippet block
function expand_default(selected) {
	hidden();
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
		var type=jQuery(this).val();
		if(type == '1')
		{
			display_item_hidden();
			jQuery(".review").show(500);
		} 
		else if(type == '2')
		{
			item_hidden();
			jQuery(".events").show(500);
		}
		else if(type == '3')
		{
			item_hidden();
			jQuery(".music").show(500);
		}
		else if(type == '4')
		{
			item_hidden();
			jQuery(".organization").show(500);
		}
		else if(type == '5')
		{
			item_hidden();
			jQuery(".people").show(500);
		}
		else if(type == '6')
		{
			item_hidden();
			jQuery(".product").show(500);
		}
		else if(type == '7')
		{
			item_hidden();
			jQuery(".recipes").show(500);
		}
		else if(type == '8')
		{
			item_hidden();
			jQuery(".software").show(500);
		}
		else if(type == '9')
		{
			item_hidden();
			jQuery(".video").show(500);
		}
		else if(type == '10')
		{
			item_hidden();
			jQuery(".article").show(500);
		}
		else if(type == '11')
		{
			item_hidden();
			jQuery(".service").show(500);
		}
	});
	//Function to expand the item review updated snippet block
	function item_expand_default(item_type) {
		item_hidden();
		if(item_type == 'item_product')
		{
			jQuery(".pro_name").show(500);
		}
	}
    jQuery("#_bsf_item_review_type").change(function() {
		item_hidden();
		var type=jQuery(this).val();
		if(type == 'item_product')
		{
			jQuery(".pro_name").show(500);
		} 
	});
});