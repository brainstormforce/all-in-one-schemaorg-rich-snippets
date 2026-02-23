# Contributing Guide

Thank you for contributing to Schema - All In One Schema Rich Snippets!

The source code is hosted on GitHub: [brainstormforce/all-in-one-schemaorg-rich-snippets](https://github.com/brainstormforce/all-in-one-schemaorg-rich-snippets)

---

## Getting Started

### Fork and Clone

```bash
git clone https://github.com/brainstormforce/all-in-one-schemaorg-rich-snippets.git
cd all-in-one-schemaorg-rich-snippets
```

### Install Dependencies

```bash
# PHP dependencies (includes dev tools)
composer install

# Node dependencies (Grunt, i18n tools)
npm install

# Install git pre-commit hooks
vendor/bin/cghooks add --ignore-lock
```

---

## Development Workflow

### 1. Create a Branch

```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

### 2. Make Changes

- Write code following [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- All PHP files must have a `@package AIOSRS` docblock
- Add docblocks to all functions and classes
- Use `__( 'string', 'rich-snippets' )` for all translatable strings
- Sanitize all input, escape all output

### 3. Check Code Quality

```bash
# Auto-fix code style issues
composer format

# Check remaining PHPCS violations
composer lint

# Run static analysis
composer phpstan
```

All three commands must pass with no new errors before submitting a PR.

### 4. Commit

Pre-commit hook will:
- Echo your committer name
- Block commits containing unresolved merge conflict markers

```bash
git add .
git commit -m "Short, imperative description of the change"
```

### 5. Open a Pull Request

Push your branch and open a PR against `master` on GitHub.

---

## Coding Standards

### PHP

- Follow [WPCS (WordPress Coding Standards)](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- PHP compatibility: 7.4 through 8.3
- Use `snake_case` for function names (WordPress convention)
- Use `UpperCamelCase` for class names
- Prefix all functions and classes with `bsf_`, `aiosrs_`, `AIOSRS`, or `RichSnippets`
- Avoid forbidden functions listed in `phpcs.xml.dist` (`eval`, `exec`, `shell_exec`, etc.)

### Security Requirements

| Input type | Required function |
|---|---|
| Text fields | `sanitize_text_field()` |
| Email | `sanitize_email()` |
| URLs | `esc_url()` |
| HTML output | `esc_html()` or `wp_kses_post()` |
| Attribute output | `esc_attr()` |
| AJAX | `wp_verify_nonce()` before any processing |
| Capability checks | `current_user_can()` before any privileged action |

### JavaScript

- Use `jQuery` (not `$`) — WordPress ships jQuery in no-conflict mode
- All scripts are registered and enqueued through WordPress's `wp_register_script` / `wp_enqueue_script` API

---

## File Organization

| Files to modify | When |
|---|---|
| `meta-boxes.php` | Adding new schema fields or types |
| `settings.php` | Adding new option defaults |
| `functions.php` | Modifying front-end output/rendering |
| `init.php` | Changes to the meta box framework |
| `index.php` | Changes to plugin-level hooks |
| `admin/index.php` | Changes to the admin dashboard UI |
| `js/toggle.js` | Changes to dynamic field visibility |

---

## Reporting Issues

- **Security vulnerabilities:** Please report privately via [wpschema.com/contact/](https://wpschema.com/contact/) — do not open public issues for security bugs
- **Bugs:** Open a [GitHub issue](https://github.com/brainstormforce/all-in-one-schemaorg-rich-snippets/issues)
- **Support questions:** Use the WordPress.org support forum

---

## i18n Contributions

To contribute a translation:

1. Copy `languages/all-in-one-schemaorg-rich-snippets.pot` to `languages/all-in-one-schemaorg-rich-snippets-{locale}.po`
2. Translate the strings
3. Submit a PR with the `.po` file

Alternatively, translations can be contributed via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/all-in-one-schemaorg-rich-snippets/).

---

## Pre-commit Hook Details

The `bin/block-commits-with-merge-conflict.sh` script scans staged files for merge conflict markers:
- `<<<` + `<<<<` (seven `<` characters)
- `=======`
- `>>>` + `>>>>` (seven `>` characters)

If any are found, the commit is blocked with an error message. Resolve all merge conflicts before committing.

---

## See Also

- [Environment Configuration](Environment-Configuration)
- [Testing Guide](Testing-Guide)
- [Deployment Guide](Deployment-Guide)
