# Schema - All In One Schema Rich Snippets

WordPress plugin by Brainstorm Force that adds structured data (schema markup / Microdata) to posts and pages via a meta box interface.

- **Plugin slug:** `all-in-one-schemaorg-rich-snippets`
- **Version:** 1.7.7
- **Text domain:** `rich-snippets`
- **PHP:** 7.4 – 8.3 | **WP:** 3.7+ (tested to 6.9)
- **License:** GPL v2
- **Repo:** https://github.com/brainstormforce/all-in-one-schemaorg-rich-snippets

---

## Tech Stack

- **Language:** PHP (WordPress plugin — no framework)
- **Standards:** WordPress Coding Standards (WPCS) via PHPCS
- **Static analysis:** PHPStan level 9
- **Build:** Grunt (i18n, zip packaging, README generation)
- **Testing:** PHPUnit 9.x
- **Package manager:** Composer (PHP dev deps) + npm (Grunt/i18n tools)
- **Git hooks:** `brainmaestro/composer-git-hooks` (pre-commit merge-conflict guard)

---

## Key Files

| File | Purpose |
|---|---|
| `index.php` | Plugin bootstrap — `RichSnippets` class, all hook registrations |
| `functions.php` | Front-end rendering (`display_rich_snippet()`), AJAX rating handlers |
| `meta-boxes.php` | `bsf_metaboxes()` — all schema field definitions |
| `settings.php` | Default option seeds for each schema type |
| `init.php` | `Bsf_Meta_Box` class (meta box framework) |
| `admin/index.php` | Admin dashboard page (`rich_snippet_dashboard`) |
| `js/toggle.js` | Dynamic field show/hide based on schema type dropdown |
| `phpcs.xml.dist` | PHPCS ruleset (WPCS + security forbidden functions) |
| `phpstan.neon` | PHPStan config (level 9, WP stubs) |

---

## Commands

```bash
# Install all dependencies
composer install && npm install

# Install git pre-commit hooks
vendor/bin/cghooks add --ignore-lock

# Lint (PHPCS)
composer lint

# Auto-fix style issues (PHPCBF)
composer format

# Static analysis (PHPStan)
composer phpstan

# Run unit tests (PHPUnit)
vendor/bin/phpunit

# Regenerate PHPStan stubs
composer gen-stubs

# Generate .pot translation file
npm run i18n

# Compile .mo files from .po
npm run i18n:mo

# Regenerate README.md from readme.txt
npx grunt readme

# Build release ZIP
npx grunt zip
```

---

## Architecture

> Boundaries and rationale for the root engine live in [`architecture.md`](architecture.md). Submodules: [`admin/`](admin/CLAUDE.md), [`js/`](js/CLAUDE.md).

- **Single meta box** (`review_metabox`) covers all schema types — field visibility toggled by `js/toggle.js`
- **Post meta key prefix:** `_bsf_` (leading underscore hides from WP Custom Fields panel)
- **Type selector:** `_bsf_post_type` — integer 0/1/2/5/6/7/8/9/10/11
- **Options prefix:** `bsf_*` (e.g. `bsf_review`, `bsf_product`, `bsf_custom`)
- **Front-end output:** `display_rich_snippet()` on `the_content` filter — outputs Microdata HTML
- **AJAX handlers:** `bsf_submit_rating`, `bsf_update_rating` (public), `bsf_submit_color`, `bsf_submit_request` (admin)
- **Third-party libs:** BSF Analytics (`admin/bsf-analytics/`), NPS Survey (`lib/nps-survey/`), Astra Notices (`lib/notices/`)

## Schema Types

| Value | Type | Option Key |
|---|---|---|
| 1 | Item Review | `bsf_review` |
| 2 | Event | `bsf_event` |
| 5 | Person | `bsf_person` |
| 6 | Product | `bsf_product` |
| 7 | Recipe | `bsf_recipe` |
| 8 | Software Application | `bsf_software` |
| 9 | Video | `bsf_video` |
| 10 | Article | `bsf_article` |
| 11 | Service | `bsf_service` |

---

## Gotchas

- **WooCommerce exclusion:** By default, `product` post types are excluded from the meta box. Toggle in admin Tab 5 or use `bsf_exclude_custom_post_type` filter.
- **`BSF_META_BOX_URL` constant:** Defined in `init.php` with Windows path handling — don't redefine it.
- **Pre-commit hook blocks literal conflict markers** (7x `<`, 7x `=`, 7x `>`) in any committed file — including docs. Use alternative notation in documentation.
- **PHPCS excludes:** `lib/`, `vendor/`, `admin/bsf-analytics/`, `node_modules/`, `stubs/` — don't add production code there expecting it to be linted.
- **PHPStan baseline:** `phpstan-baseline.neon` suppresses known false positives — add new intentional patterns here rather than inline ignores.
- **Text domain is `rich-snippets`** (not the plugin slug `all-in-one-schemaorg-rich-snippets`).
- **`register_activation_hook`** is called in `index.php` and seeds options via `settings.php` — re-running `add_option()` is safe (won't overwrite existing values).
- **Schema Pro detection:** `is_schema_pro_installed()` checks for `wp-schema-pro/wp-schema-pro.php` file existence to conditionally show the "Get Schema Pro" upsell.
- **`_bsf_post_type` is dispatched as a *string*** — `functions.php` branches on `'1' === $type` (functions.php:75,313,476…). Adding a schema type requires BOTH a `<select>` option in `meta-boxes.php:52-93` AND an `elseif` branch in `functions.php`; a numeric-cast refactor silently breaks dispatch.
- **Public (`nopriv`) rating AJAX must keep the `bsf_is_rateable_post()` gate** — `bsf_add_rating`/`bsf_update_rating` (functions.php:18-22) verify nonce `bsf_rating`, then allow only *published* posts of type `6/7/8` (functions.php:1346-1354). Removing it re-opens unauthenticated meta writes to arbitrary post IDs.
- **`bsf_save_option()` returns a three-state string** — `'saved'` / `'unchanged'` / `false` (functions.php:1202-1226); callers must branch on the string, not a boolean.
- **`add_ajax_library()` prints an inline `<script>` defining `ajaxurl` in `wp_head`** (functions.php:1321-1328, phpcs-ignored) — load-bearing; removing it breaks front-end (`nopriv`) rating posts.
- **Dead duplicate initializer** — `RichSnippets::wp_initialize_bsf_meta_boxes()` (index.php:208) is never hooked; the live one is `bsf_initialize_bsf_meta_boxes()` (functions.php:58-62, hooked at :16). Editing the wrong one has no effect.
- **`bsf_meta_boxes` filter load order** — `init.php` applies the filter at file top level (init.php:10-14); the callback is added at `index.php:500` and `init.php` is required on `init` priority 9999. Requiring `init.php` before that filter is added registers zero meta boxes.

---

## Current Focus

<!-- Update this section with what is actively being worked on -->
- Ongoing maintenance and compatibility updates

---

## Development Notes

- Run `composer format` before every commit to avoid PHPCS failures
- PHPStan must pass with no new errors — add to baseline if suppression is intentional
- All user input must be sanitized; all output must be escaped (see WPCS rules)
- Nonce verification required on all AJAX handlers before processing
- `manage_options` capability check required for admin-only AJAX actions

---

<!-- codedna:start — managed section. Edit freely; keep the markers so a
     teammate running `setup` updates this block instead of duplicating it. -->
## CodeDNA

This repository keeps engineering context next to the code. Each module folder
has a `CLAUDE.md` (rules and gotchas, auto-loaded when you read files there)
and an `architecture.md` (what it owns, and why it is that way). Modules are
listed in `.codedna/modules.json`: **core** (root), **admin/**, **js/**.

### Before changing a module

1. Read that module's `architecture.md`. Its `CLAUDE.md` has already loaded.
2. Read the existing implementation, tests, and any linked ADRs or contracts.
3. Use only what those sources and the code show. If something is missing,
   ask. Do not invent APIs, modules, or dependencies.

**Never guess a shape.** Field names, payload shapes, enum values, and option
keys are written down somewhere in here (`meta-boxes.php`, `settings.php`) —
open them and use them rather than inventing.

### After changing code

Update the module's docs in the same change when knowledge shifts:

- a rule or a gotcha — something Claude would get wrong without it → `CLAUDE.md`
- why the module is shaped this way, or what it owns → `architecture.md`

Never write the same fact in both. Do not update docs for renames, formatting,
or internal refactors that change nothing a caller can observe. Do not add a
timestamp: git already records when a file changed.

New architectural decision? Add an ADR under `docs/decisions/`. Do not rewrite
existing ADRs — supersede them.

### Context budget

Load only the affected module's files. Do not read every module's docs for a
local change.
<!-- codedna:end -->
