---
module: js
owner: UNKNOWN
---

# js

<!--
Read on demand — CLAUDE.md in this folder points here. This file answers "what
is true here, and why"; CLAUDE.md carries the things that must be obeyed. Never
write the same fact in both.
-->

## Responsibility

- `js/toggle.js` owns the meta-box's client-side field visibility on the
  post-edit screen: it shows/hides schema-field groups (by CSS class) in
  response to the `#_bsf_post_type` and `#_bsf_item_review_type` dropdowns. It
  does not save, validate, or render schema — only toggles DOM visibility.
- The other files in this folder (`cmb.js`, `media.js`, `retina.js`,
  `cp-script.min.js`, `jquery*.js`) are vendored or minified third-party
  scripts, not first-party logic.

## Related ADRs

None yet.
