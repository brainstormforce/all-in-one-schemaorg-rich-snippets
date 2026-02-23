# Admin Dashboard

The plugin admin dashboard is accessible at **WordPress Admin → Rich Snippets**.

Menu registration: `RichSnippets::register_custom_menu_page()` → `add_menu_page()` with slug `rich_snippet_dashboard`.

---

## Dashboard Tabs

The dashboard is rendered by `rich_snippet_dashboard()` in `admin/index.php`. It uses tab-based navigation (powered by `jquery.easytabs.min.js` and `jquery.hashchange.min.js`).

### Tab 1 — Review Settings

Configure display labels for the **Item Review** snippet box.

| Setting Key | Default Label | Stored in |
|---|---|---|
| `review_title` | Summary | `bsf_review` option |
| `item_reviewer` | Reviewer | `bsf_review` option |
| `review_date` | Review Date | `bsf_review` option |
| `item_name` | Reviewed Item | `bsf_review` option |
| `item_rating` | Author Rating | `bsf_review` option |

### Tab 2 — Event Settings

Configure display labels for the **Event** snippet box.

Labels stored in `bsf_event`: `snippet_title`, `event_title`, `event_location`, `event_desc`, `start_time`, `end_time`, `events_price`.

### Tab 3 — Person Settings

Labels stored in `bsf_person`: `snippet_title`, `person_name`, `person_nickname`, `person_job_title`, `person_website`, `person_company`, `person_address`.

### Tab 4 — Product Settings

Labels stored in `bsf_product`: `snippet_title`, `product_rating`, `product_brand`, `product_name`, `product_agr`, `product_price`, `product_avail`.

### Tab 5 — General Settings

Reached automatically after first activation via redirect (`aisrs_do_activation_redirect` option).

#### WooCommerce Integration Toggle

Controls whether WooCommerce product types are included in the schema meta box dropdown:

- **Checked** — WooCommerce product posts will show the schema meta box (only `shop_order`, `shop_coupon`, `product_variation` are excluded)
- **Unchecked** (default for fresh installs) — WooCommerce post types (`product`, `shop_order`, `shop_coupon`, `product_variation`) are fully excluded

Stored in: `bsf_woocom_setting` (bool)

#### Snippet Box Customization

Color pickers for the front-end snippet summary box:

| Field | CSS Property | Option Key | Default |
|---|---|---|---|
| Box Background | `background` | `snippet_box_bg` | `#F5F5F5` |
| Box Text Color | `color` | `snippet_box_color` | `#333333` |
| Title Background | `background` (title bar) | `snippet_title_bg` | `#E4E4E4` |
| Title Text Color | `color` (title bar) | `snippet_title_color` | `#333333` |
| Border Color | `border` | `snippet_border` | `#ACACAC` |

Colors are saved via the `bsf_submit_color` AJAX action (requires `manage_options` capability, nonce verified).

The WordPress Iris color picker is used (falls back to Farbtastic for WordPress versions < 3.5).

### Additional Settings Tabs

Further tabs cover Recipe, Software, Video, Article, and Service label customization — all follow the same pattern (editable display labels stored in their respective `bsf_*` options).

---

## Support / Contact Form

Located on the dashboard — submits to `support@bsf.io` via `wp_mail()`.

| Field | Validation |
|---|---|
| Name | `sanitize_text_field()` |
| Email | `sanitize_email()` |
| Subject | `sanitize_text_field()` — maps to one of: `question`, `bug`, `help`, `professional`, `contribute`, `other` |
| Message | `esc_html()` |
| Site URL | `esc_url()` |
| Post URL | `esc_url()` |

Protected by nonce: `aiosrs_support_form_nonce` / `aiosrs_support_form`.

---

## Plugin Action Links

On the Plugins list page the plugin shows:

- **Settings** → `admin.php?page=rich_snippet_dashboard`
- **Get Schema Pro** (only when Schema Pro is not installed) → external link to wpschema.com pricing page

---

## Admin Bar

When viewing a post on the **front end** as a super admin, a "Test Rich Snippets" link appears in the WP Admin Bar, pointing to `https://validator.schema.org/`.

---

## Scripts & Styles Loaded on Dashboard

| Handle | File | Type |
|---|---|---|
| `star_style` | `css/jquery.rating.css` | Style |
| `meta_style` | `admin/css/style.css` | Style |
| `admin_style` | `admin/css/admin.css` | Style |
| `wp-color-picker` | WordPress built-in | Style + Script |
| `cp_custom` | `js/cp-script.min.js` | Script |
| `jquery-ui-core` | WordPress built-in | Script |
| `jquery.easytabs.min.js` | `admin/js/` | Script |
| `jquery.hashchange.min.js` | `admin/js/` | Script |

---

## See Also

- [Schema Types](Schema-Types)
- [Database Schema](Database-Schema)
- [WooCommerce Integration](WooCommerce-Integration)
- [Plugin Hooks Reference](Plugin-Hooks-Reference)
