---
module: core
owner: UNKNOWN
---

# core

<!--
Read on demand — root CLAUDE.md points here. This file answers "what is true
here, and why"; CLAUDE.md carries the things that must be obeyed. Never write
the same fact in both.
-->

## Responsibility

- The root-level PHP engine of the plugin: bootstrap + hook registration
  (`RichSnippets` in `index.php`), front-end Microdata/JSON-LD rendering plus
  the public rating AJAX (`display_rich_snippet()` and helpers in
  `functions.php`), the schema field catalog (`bsf_metaboxes()` in
  `meta-boxes.php`), default option seeds (`settings.php`), and the generic
  meta-box framework (`Bsf_Meta_Box` in `init.php`).
- Ownership stops at the admin settings screen (`admin/`), the client-side
  toggle/media scripts (`js/`), and the vendored libraries (`lib/`,
  `admin/bsf-analytics/`) — all required from here but owned elsewhere.

## Related ADRs

None yet.
