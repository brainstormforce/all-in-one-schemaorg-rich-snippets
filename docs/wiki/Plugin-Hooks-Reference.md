# Plugin Hooks Reference

This page documents all WordPress actions and filters registered by Schema - All In One Schema Rich Snippets. This is useful for developers extending or integrating with the plugin.

---

## Actions Registered

### Admin Hooks

| Hook | Callback | Priority | Arguments | Description |
|---|---|---|---|---|
| `admin_menu` | `RichSnippets::register_custom_menu_page()` | 10 | — | Adds "Rich Snippets" top-level menu item |
| `admin_menu` | `Bsf_Meta_Box::add()` | 10 | — | Registers meta box on post edit screens |
| `admin_init` | `RichSnippets::aiosrs_admin_redirect()` | 10 | — | Fires first-activation redirect |
| `admin_init` | `RichSnippets::set_styles()` | 10 | — | Registers all scripts and stylesheets |
| `admin_init` | `RichSnippets::bsf_color_scripts()` | 10 | — | Registers color picker dependencies |
| `admin_init` | `RichSnippets::aiosrs_maybe_migrate_analytics_tracking()` | 10 | — | Migrates old analytics opt-in key |
| `admin_head` | `RichSnippets::star_icons()` | 10 | — | Outputs inline CSS for the menu star icon |
| `admin_enqueue_scripts` | `RichSnippets::post_enqueue()` | 10 | `$hook` | Enqueues post-edit assets on `post.php` |
| `admin_enqueue_scripts` | `RichSnippets::post_new_enqueue()` | 10 | `$hook` | Enqueues post-edit assets on `post-new.php` |
| `admin_bar_menu` | `RichSnippets::aiosrs_admin_bar()` | 100 | `$wp_admin_bar` | Adds "Test Rich Snippets" link to admin bar (front-end only, super admins) |
| `admin_print_styles-{page}` | `bsf_admin_styles()` | 10 | — | Enqueues admin dashboard styles on the plugin's own admin page |
| `admin_print_scripts-{page}` | `RichSnippets::iris_enqueue_scripts()` | 10 | — | Enqueues color picker scripts on dashboard page |
| `admin_print_scripts` | `add_the_script()` | 10 | — | Enqueues `postbox` and admin styles on all admin pages |
| `admin_footer` | `add_footer_script()` | 10 | — | Outputs footer script on admin pages |

### Initialization Hooks

| Hook | Callback | Priority | Arguments | Description |
|---|---|---|---|---|
| `init` | `bsf_initialize_bsf_meta_boxes()` | 9999 | — | Instantiates `Bsf_Meta_Box` for each configured meta box |
| `plugins_loaded` | `RichSnippets::rich_snippet_translation()` | 10 | — | Loads the `rich-snippets` text domain |
| `save_post` | `Bsf_Meta_Box::save()` | 10 | `$post_id` | Saves meta box field values to post meta |

### Front-End Hooks

| Hook | Callback | Priority | Arguments | Description |
|---|---|---|---|---|
| `wp` | `aiosrs_check_snippet_existence()` | 10 | — | Checks if current post has schema data; conditionally enqueues front-end assets |
| `wp_enqueue_scripts` | `aiosrs_enque()` | 10 | — | Enqueues rating CSS/JS on posts that have schema configured |
| `wp_head` | `add_ajax_library()` | 10 | — | Outputs the WordPress AJAX URL for front-end JavaScript |

### Lifecycle Hooks

| Hook | Callback | Priority | Arguments | Description |
|---|---|---|---|---|
| `register_activation_hook` | `RichSnippets::register_bsf_settings()` | — | — | Seeds all default plugin options on first activation |

---

## AJAX Actions

### Authenticated (`wp_ajax_*`)

| Action | Callback | Nonce | Capability | Description |
|---|---|---|---|---|
| `bsf_submit_request` | `RichSnippets::submit_request()` | `aiosrs_support_form_nonce` / `aiosrs_support_form` | — | Sends support contact email |
| `bsf_submit_color` | `RichSnippets::submit_color()` | `snippet_color_nonce_field` / `snippet_color_form_action` | `manage_options` | Saves color customization settings |
| `bsf_submit_rating` | `bsf_add_rating()` | — | — | Adds a front-end user star rating |
| `bsf_update_rating` | `bsf_update_rating()` | — | — | Updates a previously submitted rating |

### Public (`wp_ajax_nopriv_*`)

| Action | Callback | Description |
|---|---|---|
| `bsf_submit_rating` | `bsf_add_rating()` | Allows non-logged-in users to submit ratings |
| `bsf_update_rating` | `bsf_update_rating()` | Allows non-logged-in users to update ratings |

---

## Filters Registered

| Filter | Callback | Priority | Arguments | Description |
|---|---|---|---|---|
| `plugin_action_links_{plugin}` | `RichSnippets::bsf_settings_link()` | 10 | `$links` | Adds "Settings" and "Get Schema Pro" links to the plugins list |
| `the_content` | `display_rich_snippet()` | 10 | `$content` | Appends the schema snippet box HTML after post content |
| `bsf_meta_boxes` | `bsf_metaboxes()` | 10 | `$meta_boxes` | Injects the meta box field definitions |
| `bsf_show_on` | `Bsf_Meta_Box::add_for_id()` | 10 | `$display, $meta_box` | Filters meta box visibility by post ID |
| `bsf_show_on` | `Bsf_Meta_Box::add_for_page_template()` | 10 | `$display, $meta_box` | Filters meta box visibility by page template |

---

## Extensibility Filters

These filters are intended for use by site developers or other plugins:

### `bsf_meta_boxes`

Modify the array of meta box configurations before instantiation.

```php
add_filter( 'bsf_meta_boxes', function( $meta_boxes ) {
    // Add custom fields or remove existing ones
    return $meta_boxes;
});
```

### `bsf_exclude_custom_post_type`

Filter which post types are excluded from showing the schema meta box.

```php
add_filter( 'bsf_exclude_custom_post_type', function( $exclusions ) {
    $exclusions[] = 'my_custom_post_type';
    return $exclusions;
});
```

Default exclusions (when WooCommerce support is **disabled**):
`product`, `shop_order`, `shop_coupon`, `product_variation`

Default exclusions (when WooCommerce support is **enabled**):
`shop_order`, `shop_coupon`, `product_variation`

### `bsf_meta_box_url`

Override the meta box URL (useful for Windows/unusual server setups).

```php
add_filter( 'bsf_meta_box_url', function( $url ) {
    return 'https://example.com/wp-content/plugins/all-in-one-schemaorg-rich-snippets/';
});
```

### `bsf_show_on`

Control meta box visibility per post or page template.

---

## See Also

- [Architecture Overview](Architecture-Overview)
- [Meta Box System](Meta-Box-System)
- [WooCommerce Integration](WooCommerce-Integration)
