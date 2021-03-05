<?php
/*
Plugin Name: Awakening Cleanup
Description: Cleanup admin dashboard and clean various unused WordPress features.
Author: Reid Burke
Version: 1.0
Author URI: https://reidburke.com/
*/

function remove_dashboard_meta() {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // WordPress News
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('powerpress_dashboard_news', 'dashboard', 'normal'); // PowerPress
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Site Health
}

add_action('admin_init', 'remove_dashboard_meta');

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