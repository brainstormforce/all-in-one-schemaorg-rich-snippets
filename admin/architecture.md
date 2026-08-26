---
module: admin
owner: UNKNOWN
---

# admin

<!--
Read on demand — CLAUDE.md in this folder points here. This file answers "what
is true here, and why"; CLAUDE.md carries the things that must be obeyed. Never
write the same fact in both.
-->

## Responsibility

- Renders the plugin's single settings screen (`rich_snippet_dashboard()`,
  `admin/index.php`) — a 4-tab UI with one settings form per schema type, plus
  the WooCommerce toggle, analytics opt-in, and support form.
- Owns the server-side processing of every settings-form submit and reset
  (the top-level `$_POST` / `$_GET` handlers in `admin/index.php`), and holds
  `admin/css`, `admin/js`, `admin/images`.
- Stops at: option persistence (`bsf_save_option()` lives in root
  `functions.php`), menu/handle registration and the AJAX handler bodies (root
  `index.php`), and the vendored `admin/bsf-analytics/`.

## Related ADRs

None yet.
