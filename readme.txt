=== Simple Export Import for ACF Data ===
Contributors: opcodespace
Tags: Export, Import, Page, Post, Custom Post Type, ACF, Advanced custom field
Requires at least: 5.4.0
Tested up to: 6.0.1
Requires PHP: 7.0
Stable tag: 1.2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

With this plugin you simply export and import page, post and custom post. This plugin supports ACF fields.

== Description ==
This "Simple Export Import for ACF Data" plugin is very helpful for developers who develop theme in staging website with ACF and deploy to live.

= Supported Post/Page data in FREE Plugin =
* Title
* Slug
* Content (Just copy editor content. It does not export, import files or images which are in post content )
* Featured Image
* Excerpt
* Status of Post / Page including password

= Supported ACF fields in FREE Plugin =
* Text
* Text Area
* Number
* Email
* Url
* Password
* Editor
* Choice (Select, Multi-select, Checkbox, Radio, Button Group, True / False)
* User
* Google Map
* Date Picker
* Date Time Picker
* Time Picker
* Group

= Supported ACF fields in Paid Plugin =
* Flexible Content
* Image
* Gallery
* File
* Bulk Export/Import
* ACF Options
* Taxonomy (Category, Tag, Custom Taxonomy) of Post / Custom Post Type.

[Simple Export Import PRO for ACF](https://opcodespace.com/product/simple-export-import-pro-for-acf/)
[Simple Export Import PRO for ACF – Unlimited](https://opcodespace.com/product/simple-export-import-pro-for-acf-unlimited/)

**Notes:** Image, Gallery will work if your website is public. From localhost to live website will not work as localhost is not publicly accessable. If you can set your website public, image, gallery fields will work.

**Notes for Taxonomy:** If you have already related terms of post, this plugin can import and attach terms to the post or custom post type. If you have hierarchical taxonomies, you must have taxonomies in your destination site. If slug of term is matched, it attaches to post. Otherwise, it creates a new term, but does not maintain hierarchy.

*If you want new features or have bugged, please email support@opcodespace.com or send a request [here](https://opcodespace.com/contact-us/)*

= Tutorial =
[youtube https://www.youtube.com/watch?v=VovrgBe3VJ0]

= Privacy Policy =
We are not disclosing or storing any data outside your website. Moreover, we are not storing data in browser cookie as well. So, it is safe to use. While you insert License key, we are storing only your domain in our system.

= Terms & Condition =
We strongly recommend that you should keep your website backup before importing data. If your site get broken due to importing data or using this plugin, we cannot compensate or fix your site. This plugin depends on [Adavnced Custom Fields plugin](https://www.advancedcustomfields.com/). If ACF updates its field structure, it could be affected.  If ACF gets terminated, "Simple export import for ACF data" will be terminated. While you are using this plugin, you agree to these terms & condition.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the `Settings->Simple Export Import` screen to export & Import data

== Frequently Asked Questions ==

= Does it support fields of `Advanced Custom Field` plugin? =
Yes, free plugin is supporting many fields including repeater, groups. Please see description in details.

== Screenshots ==
1. Export
2. Import

== Changelog ==
= 1.2.1 =
* Bug Fix: Fatal Error on importing featured Image

= 1.2.1 =
* Bug Fix: Meta data of media file

= 1.2.0 =
* New Feature: Taxonomy (Category, Tag, Custom Taxonomy) of Post / Custom Post Type

= 1.1.0 =
* New Feature: Flexible Content Layout supported
* New Feature: File field supported
* New Feature: Multiple selecte supported

= 1.0.1 =
* Bug Fix: Bulk Export

= 1.0.2 =
* New Feature: Featured Image added