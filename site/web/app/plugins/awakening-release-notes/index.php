<?php
/*
Plugin Name: Awakening Release Notes 
Description: Add release notes widget for logged in users 
Author: Reid Burke
Version: 1.0
Author URI: https://reidburke.com/
*/

function ac_admin_release_notes() {
    if (!is_user_logged_in()) return;
    wp_register_script('headwayapp', 'https://cdn.headwayapp.co/widget.js', array(), '', true);
    wp_add_inline_script('headwayapp', 'var HW_config = {selector: "#wp-admin-bar-top-secondary", account: "7QY697"}', 'before');
    wp_enqueue_script('headwayapp');
}

function ac_admin_release_notes_style() {
    if (!is_user_logged_in()) return;
    echo '<style>#HW_badge_cont { display: inline-block; position: absolute; right: 30px; z-index: 9999999; } #HW_badge_cont:before { display: inline-block; content: "What\'s New"; width: 100px; text-align: right; position: absolute; right: 30px }</style>';
}

add_action('wp_enqueue_scripts', 'ac_admin_release_notes');
add_action('admin_enqueue_scripts', 'ac_admin_release_notes');
add_action('wp_head', 'ac_admin_release_notes_style');
add_action('admin_head', 'ac_admin_release_notes_style');
?>
