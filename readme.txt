=== Filter for Unused Post Images ===
Contributors: jsmits
Tags: media, library, filter, featured image, workflow
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 7.4

A smart filter that cleans up your 'Set Featured Image' window by hiding images that are already in use as a featured image.

== Description ==

This plugin provides a simple, zero-configuration solution to a common WordPress frustration: a cluttered media library when trying to select a featured image. It enhances your content creation workflow by intelligently filtering the media view.

**This plugin solves one problem perfectly:**

It hides any image that is already set as a "Featured Image" on another post or page. This allows you to upload multiple images to one post, use one, and still see the others available for your next post.

This is a "set it and forget it" plugin. Once activated, it works automatically in the background.

== Installation ==

1.  Navigate to **Plugins > Add New** in your WordPress dashboard.
2.  Click the **"Upload Plugin"** button.
3.  Click **"Choose File"** and select the `.zip` file for this plugin.
4.  Click **"Install Now"** and then **"Activate Plugin"**.

== Frequently Asked Questions ==

= Does this plugin require any configuration? =

No. It works automatically right after activation.

= Will this delete any of my images? =

Absolutely not. This plugin is highly optimized and only changes the default view. It never modifies or deletes any of your media files.

= What about images attached to posts but not used as a featured image? =

This plugin correctly keeps those images visible, ensuring your workflow is not interrupted.

== Changelog ==

= 1.5 =
*   SECURITY: Hardened the main function by adding explicit `current_user_can` and `check_ajax_referer` checks, as requested by the WordPress.org plugin review team.
*   FIX: Corrected the contributor username in the readme file to match the plugin owner.

= 1.4 =
*   TWEAK: Added a `phpcs:ignore` comment to satisfy the automated plugin checker regarding the use of a direct database query.

= 1.3 =
*   PERFORMANCE: Implemented object caching for the database query to prevent repeated database calls.

= 1.2 =
*   FIX: Removed the `post_parent` check completely to permanently fix the core workflow issue.

= 1.1 =
*   FIX: Addressed multiple warnings from the WordPress.org plugin checker.

= 1.0 =
*   Initial public release.