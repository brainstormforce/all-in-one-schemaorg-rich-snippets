# WooCommerce Integration

By default, the plugin excludes WooCommerce post types from showing the schema meta box, since WooCommerce manages its own product structured data. This behavior can be toggled from the admin dashboard.

---

## Default Behavior

When the plugin is **first activated**, the WooCommerce integration is set based on whether the plugin was previously configured:

```php
// add_woo_commerce_option() in settings.php
if ( ! get_option( 'bsf_woocom_init_setting' ) ) {
    $woo_opt = false;
    if ( get_option( 'bsf_custom' ) ) {
        // If color settings already exist, assume user has used the plugin before
        $woo_opt = true;
    }
    add_option( 'bsf_woocom_setting', $woo_opt );
    add_option( 'bsf_woocom_init_setting', 'done' );
}
```

---

## Options

| Option Key | Type | Description |
|---|---|---|
| `bsf_woocom_setting` | bool/string | The integration toggle value |
| `bsf_woocom_init_setting` | `'done'` | Guards against re-initialization on activation |

---

## Post Type Exclusions

The exclusion logic is applied in two places:

### 1. Meta Box Registration (`meta-boxes.php`)

```php
if ( empty( $woo_settings ) ) {
    // WooCommerce support OFF — exclude all WC post types
    $woocommerce_post_type = array( 'product', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_coupon', 'shop_webhook' );
    $required_post_type    = array_diff( $post_types, $woocommerce_post_type );
} else {
    // WooCommerce support ON — only exclude internal WC post types
    $exclude_custom_post_type = apply_filters( 'bsf_exclude_custom_post_type', array() );
    $required_post_type       = array_diff( $post_types, $exclude_custom_post_type );
}
```

### 2. Script Enqueuing (`index.php`)

Applied in both `post_enqueue()` and `post_new_enqueue()` to prevent scripts loading on excluded post types:

| WooCommerce OFF | WooCommerce ON |
|---|---|
| `product`, `shop_order`, `shop_coupon`, `product_variation` | `shop_order`, `shop_coupon`, `product_variation` |

When WooCommerce support is **ON**, the `product` post type is no longer excluded, allowing WooCommerce product posts to receive custom schema markup via the plugin.

---

## `bsf_exclude_custom_post_type` Filter

Developers can programmatically extend the exclusion list:

```php
add_filter( 'bsf_exclude_custom_post_type', function( $exclusions ) {
    // Exclude a custom post type from the schema meta box
    $exclusions[] = 'my_post_type';
    return $exclusions;
});
```

This filter is applied regardless of the WooCommerce setting:
- When **WooCommerce support is ON**: used as the full exclusion list (no WC types pre-added)
- When **WooCommerce support is OFF**: the filter is not applied; WC types are hardcoded

> Note: For `post_enqueue()` / `post_new_enqueue()`, the filter default value differs:
> - WooCommerce OFF: default exclusions are `['product', 'shop_order', 'shop_coupon', 'product_variation']`
> - WooCommerce ON: default exclusions are `['shop_order', 'shop_coupon', 'product_variation']`

---

## Enabling WooCommerce Support

1. Go to **WordPress Admin → Rich Snippets**
2. Navigate to **Tab 5 (General Settings)**
3. Check the **WooCommerce Integration** checkbox
4. Save settings

This stores `true` in `bsf_woocom_setting`.

---

## Compatibility Notes

- This integration only affects **where the meta box appears** and **where plugin scripts are loaded**
- WooCommerce's own schema markup is separate and unaffected by this plugin
- When WooCommerce support is enabled and a Product post type is configured via this plugin, the plugin's Microdata markup will be added alongside WooCommerce's own markup — verify via Rich Results Test to ensure no conflicts

---

## See Also

- [Schema Types](Schema-Types)
- [Admin Dashboard](Admin-Dashboard)
- [Plugin Hooks Reference](Plugin-Hooks-Reference)
