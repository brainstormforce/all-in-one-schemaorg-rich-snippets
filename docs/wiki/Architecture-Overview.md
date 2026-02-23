# Architecture Overview

Schema - All In One Schema Rich Snippets is a WordPress plugin that adds structured data (JSON-LD / Microdata) to posts and pages via a meta box interface. It is a single-plugin, no-framework PHP project following WordPress coding standards.

---

## Directory Structure

```
all-in-one-schemaorg-rich-snippets/
├── index.php            # Plugin bootstrap — RichSnippets class, hooks registration
├── functions.php        # Front-end rendering, AJAX handlers, content filter hooks
├── init.php             # Bsf_Meta_Box & Bsf_Meta_Box_Validate classes
├── meta-boxes.php       # bsf_metaboxes() — field definitions for all schema types
├── settings.php         # Default option values for each schema type
├── admin/
│   ├── index.php        # Admin dashboard page (rich_snippet_dashboard)
│   ├── css/             # Admin-only CSS
│   ├── images/          # Admin images
│   └── bsf-analytics/   # BSF Analytics library (Composer package)
├── lib/
│   ├── notices/         # Astra Notices library
│   └── nps-survey/      # NPS Survey library (Composer package)
├── js/                  # Front-end and admin JavaScript
├── css/                 # Front-end CSS
├── languages/           # i18n .pot / .po / .mo files
├── tests/php/stubs/     # PHPStan stubs for this plugin
└── bin/                 # Git pre-commit hook scripts
```

---

## Core Classes

### `RichSnippets` (`index.php`)

The main plugin class. Instantiated at the bottom of `index.php` after all `require_once` calls.

| Method | Role |
|---|---|
| `__construct()` | Registers all WordPress action/filter hooks |
| `define_constants()` | Defines `AIOSRS_PRO_FILE`, `AIOSRS_PRO_BASE`, `AIOSRS_PRO_DIR`, `AIOSRS_PRO_URI`, `AIOSRS_PRO_VER` |
| `register_custom_menu_page()` | Adds "Rich Snippets" to WP Admin menu |
| `register_bsf_settings()` | Called on `register_activation_hook` — seeds default option values |
| `set_styles()` | Registers scripts and styles via `wp_register_*` |
| `post_enqueue()` / `post_new_enqueue()` | Enqueues assets on `post.php` / `post-new.php` |
| `submit_request()` | AJAX handler for the support contact form |
| `submit_color()` | AJAX handler for color/style customization |
| `aiosrs_admin_redirect()` | Redirects to settings tab on first activation |
| `aiosrs_maybe_migrate_analytics_tracking()` | Migrates old `bsf_analytics_optin` to `aiosrs_analytics_optin` |
| `is_schema_pro_installed()` | Returns `true` if Schema Pro plugin file exists |

### `Bsf_Meta_Box` (`init.php`)

Custom meta box framework used by the plugin. Reads a configuration array and renders admin form fields.

| Method | Role |
|---|---|
| `__construct($meta_box)` | Registers `admin_menu` and `save_post` hooks |
| `add()` | Adds the meta box to post editor screens |
| `save()` | Handles `save_post` — sanitises and persists meta values |
| `show()` | Renders meta box HTML |

### `Bsf_Meta_Box_Validate` (`init.php`)

Minimal validation helper — provides `check_text()` method for field-level validation.

---

## Data Flow

```
WordPress Init
    │
    ├─► RichSnippets::__construct()
    │       ├── register hooks (admin_menu, admin_init, plugins_loaded, etc.)
    │       └── define_constants()
    │
    ├─► bsf_initialize_bsf_meta_boxes()  [on 'init' priority 9999]
    │       └── new Bsf_Meta_Box($config) for each entry in bsf_meta_boxes filter
    │
Post Edit Screen (admin)
    │
    ├─► Bsf_Meta_Box::show()
    │       └── renders "Configure Rich Snippet" metabox with type dropdown + fields
    │
    ├─► User selects schema type → toggle.js hides/shows relevant field groups
    │
    └─► Bsf_Meta_Box::save()   [on save_post]
            └── sanitise & update_post_meta(_bsf_*, ...)

Front-End Page Load
    │
    ├─► aiosrs_check_snippet_existence()  [on 'wp']
    │       └── if _bsf_post_type meta exists → enqueue rating CSS/JS
    │
    └─► display_rich_snippet($content)   [on 'the_content' filter]
            ├── reads post meta (_bsf_post_type, _bsf_item_*, _bsf_rating, ...)
            ├── reads schema options (bsf_review, bsf_event, bsf_custom, ...)
            └── returns $content + HTML snippet box with Microdata markup
```

---

## Hook Registration Summary

| Hook | Method / Function | When |
|---|---|---|
| `register_activation_hook` | `register_bsf_settings()` | Plugin activation |
| `admin_init` | `aiosrs_admin_redirect()` | First-run redirect |
| `admin_init` | `set_styles()` | Register assets |
| `admin_init` | `bsf_color_scripts()` | Register color picker |
| `admin_init` | `aiosrs_maybe_migrate_analytics_tracking()` | Analytics migration |
| `admin_menu` | `register_custom_menu_page()` | Adds admin menu item |
| `admin_head` | `star_icons()` | Inline CSS for menu icon |
| `admin_enqueue_scripts` | `post_enqueue()` | Enqueue on post.php |
| `admin_enqueue_scripts` | `post_new_enqueue()` | Enqueue on post-new.php |
| `admin_bar_menu` | `aiosrs_admin_bar()` | Test Rich Snippets link |
| `plugins_loaded` | `rich_snippet_translation()` | Load text domain |
| `wp_ajax_bsf_submit_request` | `submit_request()` | Support form AJAX |
| `wp_ajax_bsf_submit_color` | `submit_color()` | Color settings AJAX |
| `wp_ajax_bsf_submit_rating` | `bsf_add_rating()` | Front-end rating (auth) |
| `wp_ajax_nopriv_bsf_submit_rating` | `bsf_add_rating()` | Front-end rating (noauth) |
| `wp_ajax_bsf_update_rating` | `bsf_update_rating()` | Rating update (auth) |
| `wp_ajax_nopriv_bsf_update_rating` | `bsf_update_rating()` | Rating update (noauth) |
| `wp` | `aiosrs_check_snippet_existence()` | Conditionally enqueue front-end assets |
| `init` (9999) | `bsf_initialize_bsf_meta_boxes()` | Register meta box instances |
| `bsf_meta_boxes` (filter) | `bsf_metaboxes()` | Inject field definitions |

---

## See Also

- [Schema Types](Schema-Types)
- [Meta Box System](Meta-Box-System)
- [Plugin Hooks Reference](Plugin-Hooks-Reference)
- [Database Schema](Database-Schema)
