# Changelog

All notable changes to Schema - All In One Schema Rich Snippets are documented here. Source: `readme.txt`.

---

## 1.7.6
- **Improvement:** Updated readme.txt.

## 1.7.5
- **Improvement:** Compatibility with PHP version 8.3.
- **Fixed:** Resolved undefined array key warnings in plugin.

## 1.7.4
- **Fixed:** WooCommerce product editor compatibility — Added exclusions for WooCommerce post types.

## 1.7.3
- **Improvement:** Refactored and optimized the codebase to improve code quality.

## 1.7.2
- **Fixed:** Resolved the issue where function `_load_textdomain_just_in_time` was called incorrectly in WP 6.8.

## 1.7.1
- **Improved:** Security and updated the slug for better compatibility.
- This update includes important security fixes, improved accessibility compliance, and ensures a consistent text domain throughout the plugin.

## 1.7.0
- **New:** Added NPS Survey to gather valuable feedback for All In One Schema Rich Snippets.
- **Improvement:** Enhanced the codebase to strengthen security measures.

## 1.6.13
- **Improvement:** Compatibility with WordPress 6.7.
- **Improvement:** Updated the video uploadDate field to use the d-m-y format.

## 1.6.12
- **Improvement:** Added Product Image field in Review Schema.
- **Improvement:** Updated plugin metadata tags to improve search optimization.

## 1.6.11
- **Fixed:** Improved code quality syntax and security checks for better coding standards and practices.
- **Fixed:** Recipe — Author type fix.
- **Fixed:** Video Upload Date issue.

## 1.6.10
- **Fixed:** Corrected the uploadDate format as per ISO 8601 standards with timezone.
- **Fixed:** Resolved URL redirect issue for Test Rich Snippets button.

## 1.6.9
- **Improvement:** Improved plugin codebase for better security.

## 1.6.8
- **Fixed:** Ratings not visible on single product pages with Divi theme.

## 1.6.7
- **Fixed:** Customizer not loading when All In One Schema Rich Snippets plugin is active.

## 1.6.6
- Props to Patchstack for reporting security issues. Those are fixed in this release. Plus security has been hardened in other areas of the plugin.

## 1.6.5
- **Fixed:** Code updated according to coding standards.

## 1.6.4
- **Improvement:** Hardened the security of the plugin.
- **Fixed:** Reset functionality was not working in the backend settings.
- **Fixed:** Console warning `jquery-fn-load-is-deprecated`.

## 1.6.3
- **Improvement:** Compatibility with WordPress 5.5.
- **Improvement:** Updated the Hashchange jQuery library.
- **Fixed:** Tabs UI breaks in the backend.

## 1.6.2
- **New:** Users can now share non-personal usage data to help test and develop better products.

## 1.6.1
- **Improvement:** Compatibility with the latest WordPress PHP_CodeSniffer rules.
- **Improvement:** Updated the Schema URL to use HTTPS instead of HTTP.
- **Fixed:** Tabs conflict with the Astra theme.
- **Fixed:** Image field is required error showing in the Recipe schema.
- **Fixed:** Removed the support for Rating in the Service schema as per the new Google update.

## 1.6.0
- **New:** Added ItemReviewed types in Review schema.
- **Fixed:** Error "Thing is not a known valid target type for the item reviewed the property" in the review schema.

## 1.5.6
- **Improvement:** Updated plugin name — `All In One Schema Rich Snippets` to `Schema - All In One Schema Rich Snippets`.
- **Improvement:** Updated product availability strings according to Google requirements.
- **Improvement:** Added alt tag to the publisher image for SEMrush plugin compatibility.

## 1.5.5
- **Fixed:** Schema markup displayed before the post content or hidden when page content is built using a page builder plugin.

## 1.5.4
- **Improvement:** Dashboard UI Updated.
- **Fixed:** Removed publisher logo width-height meta tags.
- **Fixed:** Removed default border CSS for images in frontend.

## 1.5.3
- **Improvement:** Updated schema existing action and enqueue files function.

## 1.5.2
- **Fixed:** Frontend Summary box structure validation issue.
- **Fixed:** Editor object undefined issue lead JS issue in the page.

## 1.5.1
- **Fixed:** Plugin outputting extra output causing Ajax calls to break after last update.

## 1.5.0
- **Improvement:** Improved overall security of the plugin — sanitization and escaping, nonce and user capability checks.
- **Fixed:** XSS Vulnerability in the settings page (reported by Neven Biruski, DefenseCode).
- **Fixed:** Missing closing div tag in the generated schema markup breaking style for some themes.
- **Fixed:** Load external scripts without protocol to prevent breaking on HTTPS sites.

## 1.4.4
- **Fixed:** PHP fatal error on older version of PHP.

## 1.4.3
- **Fixed:** WooCommerce Support Added.

## 1.4.2
- **Improvement:** Added company/organization and address in People schema.
- **Improvement:** Added nutrition & ingredients in Recipe schema.
- **Improvement:** Added software image & operating system in Software Application schema.
- **Improvement:** Added video description in Software Application schema.
- **Improvement:** Added author, publisher organization and publisher logo in Article schema.
- **Improvement:** Added provider location, provider location image, and telephone in Service schema.
- **Improvement:** Changed admin bar test rich snippet redirect link to the Structured Data Testing Tool.
- **Fixed:** Removed all errors in schema according to Structured Data Testing Tool.

## 1.4.1
- **Fixed:** Compatibility Fix WordPress 4.7.

## 1.4.0
- **New:** Added new Service schema.
- Minor CSS fixes.

## 1.3.0
- **Improvement:** Updated markup data to meet Google Structured Data guidelines.
- **Fixed:** WordPress 4.4 compatibility.
- **Fixed:** Admin UI on small screens.

## 1.2.0
- **Improvement:** WordPress 4.0 compatibility.
- **Fixed:** Colorpicker breaking other plugins' colorpicker settings.

## 1.1.9
- **Fixed:** Image uploading in meta issue resolved.
- **Fixed:** Compatibility with WordPress 3.9.

## 1.1.8
- **Fixed:** CSS and JS now loads on the page/post where rich snippets are configured.

## 1.1.7
- **Improvement:** Added "Test Rich Snippets" menu in admin bar for testing rich snippets in Google Webmasters Tools.
- **Fixed:** retina.js issue resolved.
- Removed unnecessary code.

## 1.1.6
- **Improvement:** Compatibility with WordPress 3.8.
- **Fixed:** Admin CSS breaking tabs in WP 3.8.
- **Added:** Reference post URL field in "contact developers" form on settings page.

## 1.1.5
- **Improvement:** Replaced rating 'count' with 'votes' on products — as directed by Google.
- **Fixed:** Article snippet not displaying accurately when snippet title is blank.
- **Fixed:** Recipe string 'Published on' can be changed.

## 1.1.4
- **Fixed:** Illegal string offset `user_rating` warning.

## 1.1.3
- **Improvement:** Network Activation support.

## 1.1.2
- **Fixed:** Edit media functionality.

## 1.1.1
- **Added:** Article type.
- **Added:** Compatibility with WooThemes Plugins and themes.
- **Added:** New Media Manager for uploading images in metabox.

## 1.1.0
- **Added:** Admin options.
- **Fixed:** Ratings on recipe, products, and software application.
- **Improvement:** Admin options for customizing everything.
- **Improvement:** New snippet box design with responsive layout.

## 1.0.4
- **Fixed:** Rating on Comments.
- **Fixed:** On deleting any deactivated plugin.
- **Fixed:** Error message coming on commenting.
- **Fixed:** On post save draft.

## 1.0.3
- Clean up the code.
- **Fixed:** Plugin activation error.
- **Fixed:** Error on editing theme and plugin files.
- **Removed:** Breadcrumbs.

## 1.0.2
- **Added:** RDFa Breadcrumbs Plugin is now a part of All in One Schema.org Rich Snippets!
- **Added:** Star rating and review for recipe.
- **Fixed:** Recipe type.
- **Fixed:** Post update error.

## 1.0.1
- **Fixed:** Minor Bugs.

## 1.0
- Initial Release.

---

## See Also

- [Getting Started](Getting-Started)
- [Deployment Guide](Deployment-Guide)
