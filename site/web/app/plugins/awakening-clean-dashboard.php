<?php
/*
Plugin Name: Awakening Clean Dashboard
Description: Configure dashboard for admin users.
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