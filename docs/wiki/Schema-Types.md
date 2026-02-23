# Schema Types

Schema - All In One Schema Rich Snippets supports 8 schema types plus one "Item Review" wrapper type. Each type is selected via a dropdown in the "Configure Rich Snippet" meta box when editing any post or page.

---

## Type Selection

The schema type is stored in post meta key `_bsf_post_type` with the following integer values:

| Value | Schema Type |
|---|---|
| `0` | None (disabled) |
| `1` | Item Review |
| `2` | Event |
| `5` | Person |
| `6` | Product |
| `7` | Recipe |
| `8` | Software Application |
| `9` | Video |
| `10` | Article |
| `11` | Service |

---

## 1. Item Review (`value = 1`)

**Schema.org type:** `https://schema.org/Review`

Displays a review summary box with star ratings. Supports sub-types for the item being reviewed.

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_item_reviewer` | Reviewer's Name | Person who wrote the review |
| `_bsf_item_name` | Item to be reviewed | Name of the subject of the review |
| `_bsf_item_review_type` | Item Review Type | Sub-type: `none`, `item_event`, `item_product`, `item_recipe`, `item_software`, `item_video` |
| `_bsf_rating` | Rating | Numeric score 1–5 |

### Item Review Sub-types

When `_bsf_item_review_type` is set, additional `itemReviewed` structured data is output:

| Sub-type Value | Schema.org itemReviewed Type | Additional Fields |
|---|---|---|
| `item_event` | `schema.org/Event` | `_bsf_item_event_name`, `_bsf_item_event_start_date`, `_bsf_item_event_organization`, `_bsf_item_event_street`, `_bsf_item_event_local`, `_bsf_item_event_region`, `_bsf_item_event_postal_code` |
| `item_product` | `schema.org/Product` | `_bsf_item_pro_name`, `_bsf_item_pro_image`, `_bsf_item_pro_price`, `_bsf_item_pro_cur`, `_bsf_item_pro_status` |
| `item_recipe` | `schema.org/Recipe` | `_bsf_item_recipes_name`, `_bsf_item_recipes_photo` |
| `item_software` | `schema.org/SoftwareApplication` | `_bsf_item_soft_name`, `_bsf_item_os_name`, `_bsf_item_app_name` |
| `item_video` | `schema.org/VideoObject` | `_bsf_item_video_title`, `_bsf_item_video_desc`, `_bsf_item_video_thumb`, `_bsf_item_video_date` |

### Option Labels (`bsf_review`)

`review_title`, `item_reviewer`, `review_date`, `item_name`, `item_rating`

---

## 2. Event (`value = 2`)

**Schema.org type:** `https://schema.org/Event`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_event_name` | Event Name | Name of the event |
| `_bsf_event_start_date` | Event Start Date | ISO 8601 date |
| `_bsf_event_end_date` | Event End Date | ISO 8601 date |
| `_bsf_event_description` | Description | Brief event description |
| `_bsf_event_organization` | Location Name | Venue or organizer name |
| `_bsf_event_street` | Street Address | Street address of venue |
| `_bsf_event_local` | Locality | City or town |
| `_bsf_event_region` | Region | State or province |
| `_bsf_event_postal_code` | Postal Code | ZIP/postal code |
| `_bsf_event_price` | Offer Price | Ticket price |
| `_bsf_event_currency` | Currency | Currency code (e.g. USD) |

### Option Labels (`bsf_event`)

`snippet_title`, `event_title`, `event_location`, `event_desc`, `start_time`, `end_time`, `events_price`

---

## 3. Person (`value = 5`)

**Schema.org type:** `https://schema.org/Person`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_person_name` | Name | Full name |
| `_bsf_person_nickname` | Nickname | Nickname or alias |
| `_bsf_person_job_title` | Job Title | Current job title |
| `_bsf_person_website` | Website | Personal or professional URL |
| `_bsf_person_company` | Company | Employer or organization |
| `_bsf_person_address` | Address | Physical address |

### Option Labels (`bsf_person`)

`snippet_title`, `person_name`, `person_nickname`, `person_job_title`, `person_website`, `person_company`, `person_address`

---

## 4. Product (`value = 6`)

**Schema.org type:** `https://schema.org/Product`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_product_name` | Product Name | Name of the product |
| `_bsf_product_brand` | Brand | Brand name |
| `_bsf_product_price` | Price | Price amount |
| `_bsf_product_currency` | Currency | Currency code |
| `_bsf_product_status` | Availability | `in_stock`, `out_of_stock`, `instore_only`, `preorder` |
| `_bsf_rating` | Author Rating | Star rating from author |
| `_bsf_aggregate_rating` | Aggregate Rating | Aggregate user rating |

### Option Labels (`bsf_product`)

`snippet_title`, `product_rating`, `product_brand`, `product_name`, `product_agr`, `product_price`, `product_avail`

---

## 5. Recipe (`value = 7`)

**Schema.org type:** `https://schema.org/Recipe`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_recipes_name` | Recipe Name | Recipe title |
| `_bsf_recipes_photo` | Recipe Photo | Image URL |
| `_bsf_recipe_author` | Author Name | Recipe author |
| `_bsf_recipe_pub` | Published On | Publication date |
| `_bsf_recipe_prep` | Preparation Time | ISO 8601 duration |
| `_bsf_recipe_cook` | Cook Time | ISO 8601 duration |
| `_bsf_recipe_time` | Total Time | ISO 8601 duration |
| `_bsf_recipe_desc` | Description | Recipe description |
| `_bsf_recipe_nutrition` | Nutrition | Nutritional info |
| `_bsf_recipe_ingredient` | Ingredients | Ingredient list |
| `_bsf_rating` | Average Rating | Aggregate rating |

### Option Labels (`bsf_recipe`)

`snippet_title`, `recipe_name`, `author_name`, `recipe_pub`, `recipe_prep`, `recipe_cook`, `recipe_time`, `recipe_desc`, `recipe_nutrition`, `recipe_ingredient`, `recipe_rating`

---

## 6. Software Application (`value = 8`)

**Schema.org type:** `https://schema.org/SoftwareApplication`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_soft_name` | Software Name | Application name |
| `_bsf_os_name` | Operating System | Target OS |
| `_bsf_soft_image` | Software Image | Icon/screenshot URL |
| `_bsf_soft_price` | Price | Cost (0 for free) |
| `_bsf_soft_website` | Landing Page | URL to download/info page |
| `_bsf_rating` | Author Rating | Star rating |
| `_bsf_aggregate_rating` | Aggregate Rating | Aggregate user rating |

### Option Labels (`bsf_software`)

`snippet_title`, `software_rating`, `software_agr`, `software_price`, `software_name`, `software_os`, `software_website`

---

## 7. Video (`value = 9`)

**Schema.org type:** `https://schema.org/VideoObject`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_item_video_title` | Title | Video title |
| `_bsf_item_video_desc` | Description | Video description |
| `_bsf_item_video_thumb` | Thumbnail | Thumbnail image URL |
| `_bsf_item_video_date` | Upload Date | Date in `d-m-y` format (internally converted to ISO 8601 with timezone) |
| `_bsf_video_duration` | Duration | Video length |

### Option Labels (`bsf_video`)

`snippet_title`, `video_title`, `video_desc`, `video_time`, `video_date`

---

## 8. Article (`value = 10`)

**Schema.org type:** `https://schema.org/Article`

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_article_name` | Article Name | Article/blog post title |
| `_bsf_article_author` | Author | Author name |
| `_bsf_article_desc` | Description | Article summary |
| `_bsf_article_image` | Image | Featured image URL |
| `_bsf_article_publisher` | Publisher Name | Publishing organization |
| `_bsf_article_publisher_logo` | Publisher Logo | Logo image URL |

### Option Labels (`bsf_article`)

`snippet_title`, `article_name`, `article_author`, `article_desc`, `article_image`, `article_publisher`, `article_publisher_logo`

---

## 9. Service (`value = 11`)

**Schema.org type:** `https://schema.org/Service`

> Note: As of Google's 2019 update, Service schema no longer supports Rating. The plugin does not output rating fields for Service type.

### Meta Fields

| Meta Key | Label | Description |
|---|---|---|
| `_bsf_service_type` | Service Type | Type of service |
| `_bsf_service_area` | Area | Geographic area served |
| `_bsf_service_desc` | Description | Service description |
| `_bsf_service_channel` | URL | Service channel/website |
| `_bsf_service_url_link` | Click Here For More Info | Additional URL |
| `_bsf_service_provider_name` | Provider Name | Service provider organization |
| `_bsf_provider_location` | Location | Provider address |
| `_bsf_provider_location_image` | Location Image | Provider location photo |
| `_bsf_service_telephone` | Telephone | Contact phone number |

### Option Labels (`bsf_service`)

`snippet_title`, `service_type`, `service_area`, `service_desc`, `service_channel`, `service_url_link`, `service_rating`, `service_provider_name`, `provider_location`, `service_telephone`

---

## Front-End Rendering

Schema markup is rendered by the `display_rich_snippet()` function in `functions.php`, which is hooked into `the_content` filter. It outputs **Microdata** (inline HTML attributes like `itemscope`, `itemtype`, `itemprop`).

The snippet box appearance is controlled by the `bsf_custom` option (see [Admin Dashboard](Admin-Dashboard)).

---

## Validation

Use [Google's Rich Results Test](https://search.google.com/test/rich-results) or [schema.org validator](https://validator.schema.org/) to validate output. The plugin adds a "Test Rich Snippets" link in the WordPress admin bar.

---

## See Also

- [Database Schema](Database-Schema)
- [Meta Box System](Meta-Box-System)
- [Admin Dashboard](Admin-Dashboard)
