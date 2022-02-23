<?php
/*
Plugin Name: Awakening Cleanup
Description: Cleanup admin dashboard and clean various unused WordPress features.
Author: Reid Burke
Version: 1.0
Author URI: https://reidburke.com/
*/

function ac_remove_dashboard_meta() {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // WordPress News
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('powerpress_dashboard_news', 'dashboard', 'normal'); // PowerPress
}

add_action('admin_init', 'ac_remove_dashboard_meta');

// Disable Emojis.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('emoji_svg_url', '__return_false');

// Disable Comments Feed.
add_filter('feed_links_show_comments_feed', '__return_false');

// Disable Comments CSS.
add_filter('show_recent_comments_widget_style', '__return_false');

// Disable XML-RPC.
add_filter('xmlrpc_enabled', '__return_false');

// Remove X-Pingback header.
add_action('wp', function() {
    header_remove('X-Pingback');
}, 1000);

// Add PWA short name.
add_filter('web_app_manifest', function($manifest) {
    $manifest['short_name'] = 'Awakening';
    return $manifest;
});

// Hide password protected posts unless given a direct link or editing in admin.
// https://wordpress.org/support/article/using-password-protection/#hiding-password-protected-posts
add_action('pre_get_posts', function($query) {
    if(!is_single() && !is_admin()) {
        add_filter('posts_where', function ($where) {
            global $wpdb;
            return $where .= " AND {$wpdb->posts}.post_password = '' ";
        });
    }
});

// Hide password protected posts from search engines.
add_action('wp', function() {
    if (post_password_required()) {
        header('X-Robots-Tag: noindex, nofollow', true);
    }
}, 1000);

// Disable SEO Framework HTML comments.
// https://github.com/sybrew/The-SEO-Framework-Extension-Manager/blob/c5e901cc1eebf1e869053f798c8ea4fdfd3fc8ba/extensions/free/incognito/trunk/incognito.php
add_filter('the_seo_framework_indicator', '__return_false');
add_filter('the_seo_framework_title_fixed_indicator', '__return_false');
add_filter('the_seo_framework_indicator_sitemap', '__return_false');

// https://make.wordpress.org/core/2019/04/25/site-health-check-in-5-2/
add_filter('site_status_tests', function($tests) {
    unset($tests['async']['background_updates']);
    return $tests;
});