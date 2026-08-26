# Module: admin

<!-- Auto-loads when Claude reads a file in admin/. Keep under 40 lines. -->

Admin settings screen (`rich_snippet_dashboard()`) — 4-tab UI with one form per schema type, plus WooCommerce/analytics/support forms and their submit+reset processing.

Read `architecture.md` in this folder before changing this module.

## Rules

- Every settings-form POST handler must pair a nonce check with `manage_options`: `! wp_verify_nonce(...) || ! current_user_can('manage_options')` → `wp_die()` (admin/index.php:985-1132). A new form without this triple breaks the security contract.
- A form's hidden nonce-field name must match its verifier action string (e.g. `snippet_review_nonce_field` ↔ `snippet_review_form_action`, :117 ↔ :1013). Resets use *separate* GET nonces `aiosrs_*_nonce` (:143-833 ↔ :1170-1209) — note `review` reset is gated by `aiosrs_item_nonce`, not `aiosrs_review_nonce`.
- The color and support forms save via **AJAX, not** the POST handlers: both use `onsubmit="return false"` (:808,:1303) and footer JS posts `action=bsf_submit_color` / `bsf_submit_request` to handlers in root `index.php:58,60`. Editing these forms without updating those handlers breaks saving silently.
- `get_support()` output passes through `wp_kses()` with an explicit allowlist (:901-955). New tags/attributes in the support markup must be added there or they are stripped.

## Gotchas

- `ob_start()` at file scope (:10) hides PHP warnings from the many unguarded `get_option()['key']` reads (:122,:277,:813). Removing it surfaces undefined-index notices into output; field guarding is inconsistent (some `isset ? :`, most direct).
- `bsf_reset_options()` builds its include from a possibly-empty `AIOSRS_PRO_DIR`: `require_once AIOSRS_PRO_DIR . '/settings.php'` (:1222-1225) resolves to filesystem `/settings.php` if the constant is undefined.
- DOM id `bsf_css_editor` is reused on the color/woo/analytics forms (:808,:856,:881); footer JS `serialize()` (:1268) grabs only the first — reordering that id sends the wrong form's data to `bsf_submit_color`.
- Tab panels are in DOM order 1,5,3,4 (not numeric); nav anchor IDs (:94-100) and inline width JS keyed to `#postbox-container-1..11` (:964-974) must stay in sync with the markup.

## After changes

- Boundary, rule, or reasoning changed? Update `architecture.md` in the same change.
- Not for internal refactors that change nothing a caller can observe.
- New architectural decision? Add an ADR under `docs/decisions/`.
- Run: `composer lint && composer phpstan`
