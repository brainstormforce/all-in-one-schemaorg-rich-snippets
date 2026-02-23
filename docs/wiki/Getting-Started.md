# Getting Started

## Installation

### Method 1 — WordPress Dashboard

1. Go to **WordPress Admin → Plugins → Add New**
2. Search for `All in One Schema Rich Snippets`
3. Click **Install Now**, then **Activate**

### Method 2 — Upload ZIP

1. Download the plugin ZIP from [WordPress.org](https://wordpress.org/plugins/all-in-one-schemaorg-rich-snippets/) or build it with `npx grunt zip`
2. Go to **Plugins → Add New → Upload Plugin**
3. Upload the ZIP and click **Install Now**, then **Activate**

### Method 3 — FTP

1. Upload the `all-in-one-schemaorg-rich-snippets` folder to `/wp-content/plugins/`
2. Go to **Plugins** in the WordPress Admin
3. Activate the plugin

---

## First-Time Setup

After activation:

1. You will be **automatically redirected** to the plugin settings page (**Rich Snippets → Tab 5 General Settings**)
2. Configure the **WooCommerce Integration** toggle if you use WooCommerce
3. Optionally customize the **snippet box colors** (box background, title background, border, text colors)
4. Save settings

---

## Adding Your First Rich Snippet

1. Go to **Posts → Add New** (or edit an existing post/page)
2. Scroll down to the **Configure Rich Snippet** meta box (below the editor)
3. From the dropdown, select the schema type that matches your content:
   - **Item Review** — for review/rating posts
   - **Event** — for events with dates and locations
   - **Person** — for author/person profiles
   - **Product** — for product pages
   - **Recipe** — for recipes with cook times and ingredients
   - **Software Application** — for app reviews
   - **Video** — for video posts
   - **Article** — for blog posts and news articles
   - **Service** — for business services

4. Fill in the fields that appear for your selected type
5. Publish or update the post

---

## Verifying Your Schema

### Quick Test (Admin Bar)

When viewing a post on the **front end** as an admin, click **Test Rich Snippets** in the WordPress admin bar. This opens [validator.schema.org](https://validator.schema.org/) to validate your page's structured data.

### Google Rich Results Test

1. Visit [search.google.com/test/rich-results](https://search.google.com/test/rich-results)
2. Enter your post URL
3. Check for valid structured data and any errors/warnings

### Google Search Console

Once Google crawls your site, structured data is reported under **Enhancements** in [Google Search Console](https://search.google.com/search-console).

---

## Configuring Display Labels

Each schema type has customisable display labels (shown in the front-end snippet box). Go to **Rich Snippets** in the admin menu and use the tabs to customise labels:

- **Tab 1** — Review labels
- **Tab 2** — Event labels
- **Tab 3** — Person labels
- **Tab 4** — Product labels
- **Tab 5** — General settings (WooCommerce toggle + colors)
- Additional tabs for Recipe, Software, Video, Article, Service

---

## WooCommerce Users

If you use WooCommerce:

- By default, the schema meta box **does not appear** on product posts
- To enable it: go to **Rich Snippets → Tab 5** and check **WooCommerce Integration**
- When enabled, `product` post types will show the schema meta box
- `shop_order`, `shop_coupon`, and `product_variation` types are always excluded

See [WooCommerce Integration](WooCommerce-Integration) for full details.

---

## What Happens Next

After publishing a post with schema markup:

- The snippet summary box is displayed **below your post content** on the front end
- Microdata attributes (`itemscope`, `itemtype`, `itemprop`) are embedded in the HTML
- Google will read the structured data on its next crawl and may display rich results in search

---

## Next Steps

- [Schema Types](Schema-Types) — detailed field reference for all 9 schema types
- [Admin Dashboard](Admin-Dashboard) — full settings reference
- [Troubleshooting FAQ](Troubleshooting-FAQ) — if something isn't working
