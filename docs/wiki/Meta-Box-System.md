# Meta Box System

The plugin uses a custom lightweight meta box framework consisting of two classes defined in `init.php`: `Bsf_Meta_Box` and `Bsf_Meta_Box_Validate`.

---

## Overview

The meta box framework works as follows:

1. `meta-boxes.php` defines all field configurations via `bsf_metaboxes()`, hooked to the `bsf_meta_boxes` filter.
2. `functions.php` applies the filter and instantiates one `Bsf_Meta_Box` per configuration array.
3. Each `Bsf_Meta_Box` instance registers `admin_menu` (to add the box) and `save_post` (to persist values).
4. `init.php` is `require_once`'d on `init` at priority 9999 via `bsf_initialize_bsf_meta_boxes()`.

---

## `Bsf_Meta_Box` Class

**File:** `init.php`

### Constructor

```php
public function __construct( $meta_box )
```

Accepts a configuration array with these keys:

| Key | Type | Description |
|---|---|---|
| `id` | string | Unique meta box ID |
| `title` | string | Meta box heading |
| `pages` | array | Post types this meta box appears on |
| `context` | string | `normal`, `side`, or `advanced` |
| `priority` | string | `high`, `default`, or `low` |
| `show_names` | bool | Whether to show field labels on the left |
| `fields` | array | Array of field definition arrays |

### Methods

| Method | Registered Hook | Description |
|---|---|---|
| `add()` | `admin_menu` | Calls `add_meta_box()` for each post type in `pages` |
| `save( $post_id )` | `save_post` | Verifies nonce, checks autosave, iterates fields and saves |
| `show()` | (called by WordPress) | Renders the meta box HTML form |
| `add_for_id( $display, $meta_box )` | `bsf_show_on` | Conditional display by post ID |
| `add_for_page_template( $display, $meta_box )` | `bsf_show_on` | Conditional display by page template |

### Save Logic

On `save_post`, the meta box:

1. Verifies the `bsf_meta_box_nonce` nonce field.
2. Skips autosaves.
3. For each field in `$meta_box['fields']`:
   - Reads `$_POST[$field['id']]`
   - Runs through the field's `validate_func` if defined (references a method on `Bsf_Meta_Box_Validate`)
   - Calls `update_post_meta( $post_id, $field['id'], $new_value )`

---

## Field Types

The meta box system renders different HTML inputs depending on the `type` key in each field definition:

| Type | HTML Control | Description |
|---|---|---|
| `select` | `<select>` | Dropdown — options defined in `options` array |
| `text` | `<input type="text">` | Full-width text input |
| `text_medium` | `<input type="text">` | Medium-width text input |
| `text_date` | `<input type="text">` + datepicker | Date field (jQuery UI Datepicker) |
| `title` | `<h3>` | Section heading (no input) |
| `file` | `<input>` + media upload | Media library file picker |
| `image` | Media uploader | Image upload via `wp.media` |
| `textarea` | `<textarea>` | Multi-line text |
| `radio` | `<input type="radio">` | Radio buttons |
| `checkbox` | `<input type="checkbox">` | Checkbox toggle |
| `wysiwyg` | `wp_editor()` | Rich text editor |

---

## Meta Box Configuration

The single meta box registered by this plugin is defined in `bsf_metaboxes()` (`meta-boxes.php`):

```php
$meta_boxes[] = array(
    'id'         => 'review_metabox',
    'title'      => __( 'Configure Rich Snippet', 'rich-snippets' ),
    'pages'      => $required_post_type,  // All post types (filtered by WooCommerce setting)
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array( /* all schema type fields */ ),
);
```

All schema type fields are combined into this single meta box. The `toggle.js` script hides/shows relevant field groups dynamically based on the schema type dropdown (`#_bsf_post_type`).

---

## Dynamic Field Visibility

`js/toggle.js` controls which fields are visible:

- On page load: reads `#_bsf_post_type` and `#_bsf_item_review_type` values and shows matching field group
- On change of `#_bsf_post_type`: hides all groups, then shows the selected type's fields
- On change of `#_bsf_item_review_type`: hides all item sub-type groups, then shows the selected sub-type's fields

CSS classes used for toggling:

| Class | Schema Type |
|---|---|
| `.review` | Item Review |
| `.events` | Event |
| `.people` | Person |
| `.product` | Product |
| `.recipes` | Recipe |
| `.software` | Software Application |
| `.video` | Video |
| `.article` | Article |
| `.service` | Service |
| `.event_item_type` | Review → Event sub-type fields |
| `.product_item_type` | Review → Product sub-type fields |
| `.recipes_item_type` | Review → Recipe sub-type fields |
| `.soft_item_type` | Review → Software sub-type fields |
| `.video_item_type` | Review → Video sub-type fields |

---

## `Bsf_Meta_Box_Validate` Class

A utility class for field validation. Currently provides:

```php
public function check_text( $text ) {
    if ( 'hello' != $text ) {
        return false;
    }
    return true;
}
```

This is a stub validation method. Custom validators can be added here and referenced by the `validate_func` key in field definitions.

---

## `BSF_META_BOX_URL` Constant

Defined in `init.php`. Resolves to the absolute URL of the plugin directory:

- On **non-Windows** systems: replaces `WP_CONTENT_DIR` with `WP_CONTENT_URL` in the directory path.
- On **Windows** systems: handles `DIRECTORY_SEPARATOR` differences before conversion.

Can be filtered via `bsf_meta_box_url`.

---

## See Also

- [Schema Types](Schema-Types)
- [Plugin Hooks Reference](Plugin-Hooks-Reference)
- [Architecture Overview](Architecture-Overview)
