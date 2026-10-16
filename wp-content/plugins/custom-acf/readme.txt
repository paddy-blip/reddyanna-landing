=== Custom ACF ===
Contributors: keval
Tags: custom fields, meta box, field group, repeater, flexible content, gallery, options page, blocks, import, export
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.0
Stable tag: 5.12.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom ACF is a fork of Advanced Custom Fields PRO 5.12.2 (GPL) with the license and update system removed. Everything else - UI, CSS, JS, every field type, options pages, blocks, local JSON and the Tools import/export page - is identical.

== Description ==

* All field types: text, textarea, number, range, email, url, password, wysiwyg, oembed, image, file, gallery, select, checkbox, radio, button group, true/false, link, post object, page link, relationship, taxonomy, user, google map, date/time/color pickers, message, accordion, tab, group, repeater, flexible content, clone.
* Location rules for posts, pages, taxonomies, users, attachments, comments, menus, widgets, blocks and options pages.
* Template functions: get_field(), the_field(), have_rows(), the_row(), get_sub_field(), update_field(), acf_add_options_page(), acf_register_block_type() ...
* Tools: export field groups to JSON or PHP, import JSON, Local JSON sync (theme /acf-json folder).
* No license key, no "Updates" page, no update checks (Update URI: false).

Custom ACF uses the same post types and meta format as ACF, so field groups and values created with ACF / ACF PRO keep working. Do not run it together with another copy of ACF: if one is active, Custom ACF deactivates it automatically and shows a notice.

== Installation ==

1. Upload the `custom-acf` folder to `/wp-content/plugins/` or upload `custom-acf.zip` via Plugins > Add New.
2. Activate the plugin. Any other active copy of Advanced Custom Fields is deactivated automatically.
3. Go to Custom Fields > Add New to create a field group, or Custom Fields > Tools to import a JSON export.

== Moving fields between projects ==

* Custom Fields > Tools > Export: select groups, click "Export File" (JSON) or "Generate PHP".
* Custom Fields > Tools > Import: upload the JSON file on the other site.
* Local JSON: create an `acf-json` folder in your theme; every saved group is written there and can be synced on another site from Tools > Sync (or the "Sync available" filter in the field group list).

== Changelog ==

= 5.12.2 =
* Forked from Advanced Custom Fields PRO 5.12.2.
* Removed includes/updates.php, pro/updates.php, pro/admin/admin-updates.php and the Updates settings view (license activation, update checks).
* Renamed to Custom ACF; Update URI disabled.
* Automatically deactivates any other active ACF copy.
