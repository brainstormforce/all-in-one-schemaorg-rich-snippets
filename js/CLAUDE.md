# Module: js

<!-- Auto-loads when Claude reads a file in js/. Keep under 40 lines. -->

`toggle.js` — client-side show/hide of schema-field groups on the post-edit screen, driven by the schema-type dropdowns. (Other files here are vendored/minified jQuery plugins.)

Read `architecture.md` in this folder before changing this module.

## Rules

- The integer→selector map in `toggle.js` (:54 and :66) must stay in sync with the `#_bsf_post_type` `<select>` option values in `meta-boxes.php:52-92`. Adding or renumbering a schema type in PHP requires updating this map **in both places** here, or the new type's fields never show.
- The item-review sub-type map (:75 and :83, e.g. `item_software`→`.soft_item_type`) must match the `_bsf_item_review_type` options in `meta-boxes.php:124-148`. Also duplicated in two places.
- The dropdown IDs `#_bsf_post_type` and `#_bsf_item_review_type` (:8-12,:59,:80) are load-bearing selectors; renaming the PHP field ids silently disables all toggling.

## Gotchas

- Map entries `'3':'.music'` and `'4':'.organization'` (:54,:66) have **no** matching `#_bsf_post_type` option — dead/orphan keys, proof this sync has drifted before. Misleading to editors; not wired to a real type.
- The post-type map is copy-pasted in two locations (`expand_default` at :54 and the `change` handler at :66); a one-sided edit produces asymmetric behavior (initial load works but the dropdown change does not, or vice-versa).
- Timing split: post-type toggling runs at `document.ready` (:13-16) but the initial item-review expansion is deferred to `jQuery(window).on('load')` (:17-23). `item_type` is captured once at ready (:12) and reused stale in the post-type `change` closure (:63-64), so changing the sub-type then toggling post type can re-expand with the old sub-type.

## After changes

- Boundary, rule, or reasoning changed? Update `architecture.md` in the same change.
- Not for internal refactors that change nothing a caller can observe.
- New architectural decision? Add an ADR under `docs/decisions/`.
- Verify: manually test field toggling on the post-edit screen for each schema type (no JS test suite / linter is configured).
