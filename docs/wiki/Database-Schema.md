# Database Schema

This plugin stores all data in the standard WordPress options table (`wp_options`) and post meta table (`wp_postmeta`). There are no custom database tables.

---

## WordPress Options (`wp_options`)

These options are seeded by `settings.php` during plugin activation (`register_bsf_settings()`).

### Schema Display Labels

Each option is an associative array of label strings that controls what heading text appears in the front-end snippet box. All values are translatable.

| Option Key | Keys (label names) | Description |
|---|---|---|
| `bsf_review` | `review_title`, `item_reviewer`, `review_date`, `item_name`, `item_rating` | Labels for Item Review snippets |
| `bsf_event` | `snippet_title`, `event_title`, `event_location`, `event_desc`, `start_time`, `end_time`, `events_price` | Labels for Event snippets |
| `bsf_person` | `snippet_title`, `person_name`, `person_nickname`, `person_job_title`, `person_website`, `person_company`, `person_address` | Labels for Person snippets |
| `bsf_product` | `snippet_title`, `product_rating`, `product_brand`, `product_name`, `product_agr`, `product_price`, `product_avail` | Labels for Product snippets |
| `bsf_recipe` | `snippet_title`, `recipe_name`, `author_name`, `recipe_pub`, `recipe_prep`, `recipe_cook`, `recipe_time`, `recipe_desc`, `recipe_nutrition`, `recipe_ingredient`, `recipe_rating` | Labels for Recipe snippets |
| `bsf_software` | `snippet_title`, `software_rating`, `software_agr`, `software_price`, `software_name`, `software_os`, `software_website` | Labels for Software Application snippets |
| `bsf_video` | `snippet_title`, `video_title`, `video_desc`, `video_time`, `video_date` | Labels for Video snippets |
| `bsf_article` | `snippet_title`, `article_name`, `article_author`, `article_desc`, `article_image`, `article_publisher`, `article_publisher_logo` | Labels for Article snippets |
| `bsf_service` | `snippet_title`, `service_type`, `service_area`, `service_desc`, `service_channel`, `service_url_link`, `service_rating`, `service_provider_name`, `provider_location`, `service_telephone` | Labels for Service snippets |

### Snippet Box Styling

| Option Key | Type | Description |
|---|---|---|
| `bsf_custom` | array | Color settings: `snippet_box_bg` (#F5F5F5), `snippet_title_bg` (#E4E4E4), `snippet_border` (#ACACAC), `snippet_title_color` (#333333), `snippet_box_color` (#333333) |

### WooCommerce Integration

| Option Key | Type | Description |
|---|---|---|
| `bsf_woocom_setting` | bool/string | Whether WooCommerce product schema integration is enabled |
| `bsf_woocom_init_setting` | string (`'done'`) | Set to `'done'` once WooCommerce option has been initialized |

### Analytics & Tracking

| Option Key | Type | Description |
|---|---|---|
| `aiosrs_analytics_optin` | string (`'yes'`/`false`) | User's opt-in preference for BSF Analytics |
| `aiosrs_analytics_installed_time` | int | Unix timestamp of when analytics was first activated |
| `bsf_analytics_optin` | string | Legacy key — migrated to `aiosrs_analytics_optin` |
| `bsf_analytics_installed_time` | int | Legacy key — migrated to `aiosrs_analytics_installed_time` |

### Activation Flow

| Option Key | Type | Description |
|---|---|---|
| `aisrs_do_activation_redirect` | bool | Set to `true` on activation; deleted after redirect fires |

---

## Post Meta (`wp_postmeta`)

All meta keys use the `_bsf_` prefix. The leading underscore hides them from WordPress's native "Custom Fields" panel.

### Universal Field

| Meta Key | Description |
|---|---|
| `_bsf_post_type` | Schema type selector. Values: `0` (none), `1` (Review), `2` (Event), `5` (Person), `6` (Product), `7` (Recipe), `8` (Software Application), `9` (Video), `10` (Article), `11` (Service) |

### Item Review Fields (`_bsf_post_type = 1`)

| Meta Key | Description |
|---|---|
| `_bsf_item_reviewer` | Reviewer's name |
| `_bsf_item_name` | Name of the item being reviewed |
| `_bsf_item_review_type` | Sub-type: `none`, `item_event`, `item_product`, `item_recipe`, `item_software`, `item_video` |
| `_bsf_rating` | Numeric review rating (0–5) |

### Event Fields (`_bsf_post_type = 2`)

| Meta Key | Description |
|---|---|
| `_bsf_event_name` | Event name |
| `_bsf_event_start_date` | Start date (ISO 8601 compatible) |
| `_bsf_event_end_date` | End date |
| `_bsf_event_description` | Event description |
| `_bsf_event_organization` | Venue/organizer name |
| `_bsf_event_street` | Street address |
| `_bsf_event_local` | Locality |
| `_bsf_event_region` | Region/state |
| `_bsf_event_postal_code` | Postal code |
| `_bsf_event_price` | Ticket/offer price |
| `_bsf_event_currency` | Price currency code |

### Item Review Sub-fields — Event

| Meta Key | Description |
|---|---|
| `_bsf_item_event_name` | Event name (within review context) |
| `_bsf_item_event_start_date` | Event start date |
| `_bsf_item_event_organization` | Event organizer |
| `_bsf_item_event_street` | Street address |
| `_bsf_item_event_local` | Locality |
| `_bsf_item_event_region` | Region |
| `_bsf_item_event_postal_code` | Postal code |

### Item Review Sub-fields — Product

| Meta Key | Description |
|---|---|
| `_bsf_item_pro_name` | Product name |
| `_bsf_item_pro_image` | Product image URL |
| `_bsf_item_pro_price` | Product price |
| `_bsf_item_pro_cur` | Currency code |
| `_bsf_item_pro_status` | Availability: `in_stock`, `out_of_stock`, `instore_only`, `preorder` |

### Item Review Sub-fields — Recipe

| Meta Key | Description |
|---|---|
| `_bsf_item_recipes_name` | Recipe name |
| `_bsf_item_recipes_photo` | Recipe image URL |

### Item Review Sub-fields — Software / Video

| Meta Key | Description |
|---|---|
| `_bsf_item_soft_name` | Software name |
| `_bsf_item_os_name` | Operating system |
| `_bsf_item_app_name` | App/application name |
| `_bsf_item_video_title` | Video title |
| `_bsf_item_video_desc` | Video description |
| `_bsf_item_video_thumb` | Video thumbnail URL |
| `_bsf_item_video_date` | Video upload date |

---

## Notes

- All post meta is stored via `update_post_meta()` and retrieved via `get_post_meta()`.
- Options are seeded with `add_option()` — they will not overwrite existing values.
- Color settings are saved via the `bsf_submit_color` AJAX action using `update_option()`.

---

## See Also

- [Schema Types](Schema-Types)
- [Architecture Overview](Architecture-Overview)
- [Admin Dashboard](Admin-Dashboard)
