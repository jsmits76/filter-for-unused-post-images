<?php
/**
 * Plugin Name:       Filter for Unused Post Images
 * Description:       Sets the default view in the 'Set Featured Image' window to show only images that are not already in use as a featured image.
 * Version:           1.5
 * Author:            Janus Smits
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Text Domain:       filter-for-unused-post-images
 */

// Security check: Prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Modifies the default query for the media library in the 'Media Modal' (Set Featured Image).
 *
 * This function's only job is to exclude images that are already in use as a standard
 * featured image (`_thumbnail_id`). It deliberately ignores the 'attachment' status (post_parent)
 * to ensure a smooth workflow when uploading multiple images to a single post.
 *
 * @param array $query The original query arguments.
 * @return array The modified query arguments.
 */
function jbs_filter_featured_image_query( $query ) {

    // --- SECURITY CHECKS as requested by the WordPress.org plugin review team ---
    // 1. Check if the user has the required permissions to be here.
    if ( ! current_user_can( 'upload_files' ) ) {
        return $query;
    }
    // 2. Verify the AJAX nonce to prevent CSRF attacks.
    check_ajax_referer( 'query-attachments', 'security' );


    $cache_key   = 'jbs_used_thumbnail_ids';
    $cache_group = 'jbs_filter_plugin';

    // First, try to get the list of IDs from the cache.
    $used_thumbnail_ids = wp_cache_get( $cache_key, $cache_group );

    // If the cache is empty (a "cache miss"), run the database query.
    if ( false === $used_thumbnail_ids ) {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- A direct query is the only efficient way to get this data.
        $used_thumbnail_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s",
                '_thumbnail_id'
            )
        );
        // Sanitize the array to ensure it only contains positive integers.
        $used_thumbnail_ids = array_filter( array_map( 'absint', $used_thumbnail_ids ) );

        // Save the fresh result to the cache for 5 minutes.
        wp_cache_set( $cache_key, $used_thumbnail_ids, $cache_group, 5 * MINUTE_IN_SECONDS );
    }

    if ( ! empty( $used_thumbnail_ids ) ) {
        // Exclude all images that are currently set as a featured image.
        $query['post__not_in'] = $used_thumbnail_ids;
    }

    return $query;
}
add_filter( 'ajax_query_attachments_args', 'jbs_filter_featured_image_query' );


/**
 * Clears our custom cache whenever a post is saved or meta is changed.
 * This ensures the list of used thumbnails is always up-to-date.
 */
function jbs_clear_thumbnail_cache() {
    wp_cache_delete( 'jbs_used_thumbnail_ids', 'jbs_filter_plugin' );
}
add_action( 'save_post', 'jbs_clear_thumbnail_cache' );
add_action( 'delete_post_meta', 'jbs_clear_thumbnail_cache' );
add_action( 'add_post_meta', 'jbs_clear_thumbnail_cache' );