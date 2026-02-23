# Environment Configuration

## System Requirements

| Requirement | Minimum |
|---|---|
| PHP | 7.4.33 (also supports 8.0, 8.1, 8.2, 8.3) |
| WordPress | 3.7 |
| WordPress (tested up to) | 6.9 |

---

## Plugin Constants

Defined in `RichSnippets::define_constants()` (`index.php`) during every page load:

| Constant | Value | Description |
|---|---|---|
| `AIOSRS_PRO_FILE` | `__FILE__` (path to `index.php`) | Absolute path to the plugin entry point |
| `AIOSRS_PRO_BASE` | `plugin_basename(AIOSRS_PRO_FILE)` | Relative plugin identifier (e.g. `all-in-one-schemaorg-rich-snippets/index.php`) |
| `AIOSRS_PRO_DIR` | `plugin_dir_path(AIOSRS_PRO_FILE)` | Absolute directory path with trailing slash |
| `AIOSRS_PRO_URI` | `plugins_url('/', AIOSRS_PRO_FILE)` | URL to the plugin root |
| `AIOSRS_PRO_VER` | `'1.7.6'` | Current plugin version |
| `BSF_META_BOX_URL` | Computed in `init.php` | URL path to the plugin directory, OS-aware (handles Windows paths) |

---

## Development Environment Setup

### Prerequisites

- PHP 7.4+ with Composer
- Node.js with npm
- WordPress install (local or remote)

### Install PHP Dependencies

```bash
composer install
```

This installs:
- `phpunit/phpunit` ^9.5 — unit testing
- `wp-coding-standards/wpcs` ^2.2 — WordPress coding standards
- `phpcompatibility/php-compatibility` ^9.3 — PHP version compatibility checks
- `squizlabs/php_codesniffer` ^3.10 — code sniffer engine
- `pheromone/phpcs-security-audit` ^2.0 — security-focused PHPCS sniffs
- `phpstan/phpstan` ^1.12 — static analysis
- `szepeviktor/phpstan-wordpress` ^1.3 — WordPress stubs for PHPStan
- `php-stubs/wordpress-stubs` ^6.6 — WordPress function stubs
- `brainmaestro/composer-git-hooks` ^2.6 — pre-commit hook management

### Install Node Dependencies

```bash
npm install
```

This installs Grunt and plugins for i18n and packaging.

---

## Linting & Static Analysis

### PHP CodeSniffer (PHPCS)

Config file: `phpcs.xml.dist`

```bash
# Check code
composer lint
# or
vendor/bin/phpcs --standard=phpcs.xml.dist

# Auto-fix fixable issues
composer format
# or
vendor/bin/phpcbf --standard=phpcs.xml.dist
```

Standards applied:
- `PHPCompatibility`
- `WordPress-Core`
- `WordPress-Docs`
- `WordPress-Extra`

Exclusions from scan:
- `node_modules/`, `vendor/`, `stubs/`, `lib/`, `admin/bsf-analytics/`

Security-specific forbidden functions (e.g. `eval`, `exec`, `shell_exec`, `phpinfo`, `extract`) are configured directly in `phpcs.xml.dist`.

### PHPStan (Static Analysis)

Config file: `phpstan.neon`
Level: **9** (strictest)

```bash
composer phpstan
# or
vendor/bin/phpstan --memory-limit=2048M analyse
```

Bootstrap files used:
- `vendor/php-stubs/wordpress-stubs/wordpress-stubs.php`
- `tests/php/stubs/aiosrs-stubs.php` (plugin-specific stubs)

Analysed paths: `index.php`, `functions.php`, `init.php`, `meta-boxes.php`, `settings.php`, `languages/`, `admin/`

A baseline file (`phpstan-baseline.neon`) is maintained to suppress known false positives.

### Regenerate Stubs

```bash
composer gen-stubs
```

---

## Pre-commit Hooks

Managed via `brainmaestro/composer-git-hooks` (`cghooks.lock`).

```bash
# Install hooks after composer install
vendor/bin/cghooks add --ignore-lock
```

Pre-commit hook runs:
1. Echoes commit author name
2. Runs `bin/block-commits-with-merge-conflict.sh` to prevent committing unresolved merge conflicts

---

## i18n / Translations

Text domain: `rich-snippets`
Languages directory: `languages/`

```bash
# Generate .pot file
npm run i18n

# Update .po files from .pot
npm run i18n:po

# Compile .mo files
npm run i18n:mo

# Generate .json files for JS translations
npm run i18n:json
```

---

## Build / Package

```bash
# Generate README.md from readme.txt
npx grunt readme

# Create release zip
npx grunt zip
```

The zip excludes: `node_modules/`, `tests/`, `.git/`, `vendor/`, `bin/`, `phpstan-baseline.neon`, `phpstan.neon`, `stubs-generator.php`.

---

## See Also

- [Contributing Guide](Contributing-Guide)
- [Testing Guide](Testing-Guide)
- [Deployment Guide](Deployment-Guide)
