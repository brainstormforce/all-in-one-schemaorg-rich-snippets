# Third-Party Libraries

The plugin bundles three Brainstorm Force libraries managed as Composer packages. They are installed to non-standard paths via `composer.json` `installer-paths` configuration.

---

## 1. BSF Analytics (`brainstormforce/bsf-analytics`)

**Install path:** `admin/bsf-analytics/`
**Entry point:** `admin/bsf-analytics/class-bsf-analytics-loader.php`

### Purpose

Tracks anonymised plugin usage data to help Brainstorm Force improve the product. Users must opt in — it is not enabled by default.

### Integration

Loaded in `index.php`:

```php
if ( ! class_exists( 'BSF_Analytics_Loader' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'admin/bsf-analytics/class-bsf-analytics-loader.php';
}
$bsf_analytics = BSF_Analytics_Loader::get_instance();
$bsf_analytics->set_entity( array(
    'aiosrs' => array(
        'product_name'        => 'All In One Schema Rich Snippets',
        'path'                => plugin_dir_path( __FILE__ ) . 'admin/bsf-analytics',
        'author'              => 'Brainstorm Force',
        'time_to_display'     => '+24 hours',
        'deactivation_survey' => array( /* ... */ ),
        'hide_optin_checkbox' => true,
    ),
) );
```

### Deactivation Survey

When a user deactivates the plugin, a quick feedback popup appears. Configuration:

| Key | Value |
|---|---|
| `id` | `deactivation-survey-all-in-one-schemaorg-rich-snippets` |
| `plugin_slug` | `all-in-one-schemaorg-rich-snippets` |
| `popup_title` | `Quick Feedback` |
| `support_url` | `https://wpschema.com/contact/` |
| `show_on_screens` | `plugins` page |

### Analytics Options Migration

On `admin_init`, the plugin migrates the old analytics opt-in key:

```php
// Migrates bsf_analytics_optin → aiosrs_analytics_optin
public function aiosrs_maybe_migrate_analytics_tracking()
```

| Old Option | New Option |
|---|---|
| `bsf_analytics_optin` | `aiosrs_analytics_optin` |
| `bsf_analytics_installed_time` | `aiosrs_analytics_installed_time` |

---

## 2. NPS Survey (`brainstormforce/nps-survey`)

**Install path:** `lib/nps-survey/`
**Entry point:** `lib/class-aiosrs-nps-survey.php`
**Class:** `AIOSRS_Nps_Survey`

### Purpose

Displays a Net Promoter Score (NPS) survey to collect user satisfaction feedback. Introduced in version 1.7.0.

### Integration

Loaded in `index.php`:

```php
if ( ! class_exists( 'AIOSRS_Nps_Survey' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'lib/class-aiosrs-nps-survey.php';
}
```

The `lib/class-aiosrs-nps-survey.php` wrapper instantiates the NPS Survey library with plugin-specific configuration.

### Library Structure (`lib/nps-survey/`)

```
nps-survey/
├── nps-survey.php           # Main library file
├── nps-survey-plugin-loader.php
├── classes/                 # Survey logic classes
├── dist/                    # Compiled JS/CSS assets
└── version.json
```

---

## 3. Astra Notices (`lib/notices/`)

**File:** `lib/notices/class-astra-notices.php`
**CSS:** `lib/notices/notices.css`
**JS:** `lib/notices/notices.js`

### Purpose

Displays admin notices (e.g. upgrade prompts, announcements) in a standardised Brainstorm Force style. Used across multiple BSF products.

### Integration

Loaded in `index.php` for admin screens only:

```php
if ( is_admin() ) {
    require_once plugin_dir_path( __FILE__ ) . '/lib/notices/class-astra-notices.php';
}
```

---

## JavaScript Libraries (Bundled)

These are bundled directly in the `js/` directory (not managed by npm/Composer):

| File | Description |
|---|---|
| `js/jquery.rating.min.js` | Star rating widget — used on front-end and post edit screen |
| `js/toggle.js` | Dynamic field visibility for the schema type dropdown |
| `js/cmb.js` | Meta box form helper (media upload, color picker integration) |
| `js/media.js` | WordPress media library integration for image fields |
| `js/cp-script.min.js` | Color picker initialization script |
| `js/jquery.timePicker.min.js` | Time picker widget for date/time fields |
| `js/jquery.js` | jQuery (legacy bundled copy) |
| `js/retina.js` | Retina display image substitution |
| `admin/js/jquery.easytabs.min.js` | Tab UI for the admin dashboard |
| `admin/js/jquery.hashchange.min.js` | URL hash change detection for tab persistence |

---

## Composer Dependencies (Dev Only)

These are used in development only and not shipped with the plugin:

| Package | Purpose |
|---|---|
| `phpunit/phpunit` ^9.5 | Unit testing |
| `wp-coding-standards/wpcs` ^2.2 | WordPress coding standards |
| `phpcompatibility/php-compatibility` ^9.3 | PHP version compatibility |
| `squizlabs/php_codesniffer` ^3.10 | Code style enforcement |
| `pheromone/phpcs-security-audit` ^2.0 | Security-focused linting |
| `phpstan/phpstan` ^1.12 | Static analysis |
| `szepeviktor/phpstan-wordpress` ^1.3 | WordPress stubs for PHPStan |
| `php-stubs/wordpress-stubs` ^6.6 | WordPress function stubs |
| `brainmaestro/composer-git-hooks` ^2.6 | Git hook management |
| `wp-cli/wp-cli` ^2.11 | WP-CLI for i18n commands |

---

## See Also

- [Architecture Overview](Architecture-Overview)
- [Environment Configuration](Environment-Configuration)
- [Contributing Guide](Contributing-Guide)
