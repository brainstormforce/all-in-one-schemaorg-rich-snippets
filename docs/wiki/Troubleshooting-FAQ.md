# Troubleshooting & FAQ

## General Questions

### What is a Rich Snippet?

A rich snippet is a short summary of your page displayed in search results by Google, Bing, and Yahoo. It can include star ratings, images, prices, event dates, and more — helping your pages stand out from competitors and improving click-through rates.

### How do I add schema to a post?

1. Go to **Posts → Edit** (or create a new post)
2. Scroll down to the **Configure Rich Snippet** meta box
3. Select the schema type from the dropdown
4. Fill in the relevant fields
5. Publish or update the post

Google will display the rich snippet once it crawls your post.

### How do I test if my schema is working?

- Click **Test Rich Snippets** in the WordPress admin bar while viewing a post on the front end — this opens [validator.schema.org](https://validator.schema.org/)
- Paste your post URL into [Google's Rich Results Test](https://search.google.com/test/rich-results)

---

## Schema Not Displaying

### The snippet box doesn't appear below my post content

**Possible causes:**

1. **Schema type not selected** — Make sure `_bsf_post_type` is set to a value other than `0` in the meta box
2. **Theme overriding the_content filter** — Some themes or page builders intercept `the_content` filter and bypass it entirely. Check with a default theme (e.g. Twenty Twenty-Four)
3. **Page builder conflict** — Page builders (Elementor, Divi, Beaver Builder) may not use the standard `the_content` filter on builder-rendered pages. Schema will only appear on non-builder or Classic Editor posts
4. **WooCommerce product excluded** — If WooCommerce integration is disabled and you are editing a `product` post type, the meta box will not appear. Enable WooCommerce support in **Rich Snippets → Tab 5**

### The meta box doesn't appear on my post edit screen

1. Verify the post type is not in the exclusion list (see [WooCommerce Integration](WooCommerce-Integration))
2. Check if a third-party plugin is filtering `bsf_meta_boxes` or `bsf_exclude_custom_post_type`
3. Check browser console for JavaScript errors (the toggle.js file must load)

### Schema markup shows but Google doesn't display it

- Google's display of rich results is not guaranteed — it depends on content quality and compliance with Google's guidelines
- Verify there are no structured data errors via [Google Search Console → Enhancements](https://search.google.com/search-console)
- Ensure required fields for your schema type are filled in

---

## WooCommerce Issues

### Product pages show my schema meta box

This is expected when **WooCommerce Integration is enabled** in Tab 5 of the dashboard. If you don't want schema on products, disable the WooCommerce integration.

### Schema breaks WooCommerce checkout / AJAX

This is typically caused by the plugin's `add_ajax_library` action adding JavaScript to `wp_head` on non-post pages. Filter: check if `is_shop()` or `is_cart()` pages are affected and use the `bsf_exclude_custom_post_type` filter to exclude relevant post types.

---

## Color / Styling Issues

### Color changes aren't saving

1. Verify you are logged in as an administrator (`manage_options` capability is required)
2. Check that the AJAX request to `bsf_submit_color` is not blocked by a security plugin
3. Check browser developer tools → Network tab for a failed AJAX response

### Colors reset after update

Colors are stored in the `bsf_custom` WordPress option. Plugin updates do not overwrite existing options — if colors reset, another plugin may be clearing options. Check `options.php` in WordPress to verify the `bsf_custom` key exists.

---

## Translation Issues

### "Function _load_textdomain_just_in_time was called incorrectly" warning (WP 6.8+)

This was fixed in version **1.7.2**. The text domain is now loaded via the `plugins_loaded` hook in `RichSnippets::rich_snippet_translation()` rather than early in `functions.php`. Upgrade to v1.7.2 or later.

### Plugin strings not translating

1. Verify the text domain is `rich-snippets` (not `all-in-one-schemaorg-rich-snippets`)
2. Check that `.mo` files exist in `languages/`
3. Confirm your site's locale is set in **Settings → General → Site Language**

---

## JavaScript Issues

### Tabs in the admin dashboard break (WP 3.8 style conflict)

This was resolved in version 1.6.3 with an updated hashchange jQuery library and in version 1.6.1 for Astra theme compatibility.

### jQuery Deprecated `.load()` warning in console

Fixed in version 1.6.4. If you see this warning, please update to v1.6.4+.

### Admin bar "Test Rich Snippets" link doesn't work

The link points to `https://validator.schema.org/`. If it redirects incorrectly, clear browser cache. This was fixed in version 1.6.10.

---

## PHP Errors / Warnings

### Undefined array key warnings

Fixed in version **1.7.5**. The plugin now properly checks array key existence before accessing values. Upgrade to v1.7.5 or later.

### Fatal error on older PHP

The plugin requires PHP 7.4+. If you see fatal errors, check your PHP version in **Tools → Site Health → Server**.

### Recipe Author Type error in structured data testing

Fixed in version **1.6.11** — the recipe author type now correctly uses `Person` or `Organization`.

---

## Divi Theme Compatibility

### Ratings not visible on single product pages with Divi

Fixed in version **1.6.8**. If using Divi and ratings aren't showing, update to v1.6.8+.

---

## Visual Composer / WPBakery Conflict

The plugin checks for `vc_map()` before loading the jQuery UI CSS to avoid conflicts:

```php
if ( ! function_exists( 'vc_map' ) ) {
    wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/...' );
}
```

---

## Schema Pro Compatibility

When [Schema Pro](https://wpschema.com/) is installed:
- The "Get Schema Pro" link is hidden from the plugins list
- Both plugins can coexist — Schema Pro provides additional schema types and automation

---

## Getting Help

- **Support forum:** [WordPress.org support](https://wordpress.org/support/plugin/all-in-one-schemaorg-rich-snippets/)
- **Contact form:** Available in the plugin dashboard (Rich Snippets → Settings)
- **GitHub issues:** [github.com/brainstormforce/all-in-one-schemaorg-rich-snippets/issues](https://github.com/brainstormforce/all-in-one-schemaorg-rich-snippets/issues)
- **Pro support:** [wpschema.com/contact/](https://wpschema.com/contact/)

---

## See Also

- [Schema Types](Schema-Types)
- [WooCommerce Integration](WooCommerce-Integration)
- [Admin Dashboard](Admin-Dashboard)
