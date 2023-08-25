<?php
/*
Plugin Name: Awakening Teaching Notes Meta
Description: Register teaching notes metadata for use in Meta Field Block
Author: Reid Burke
Version: 1.0
Author URI: https://reidburke.com/
*/

register_meta('post', 'teaching-notes', array(
    'object_subtype' => 'teaching',
    'show_in_rest' => true
));

register_meta('post', 'teaching-date', array(
    'object_subtype' => 'teaching',
    'show_in_rest' => true
));

add_filter('meta_field_block_get_block_content', function ($block_content, $attributes, $block, $post_id) {
    $field_name = $attributes['fieldName'] ?? '';

    if ('teaching-notes' === $field_name) {
        $notes = get_post_meta($post_id, 'teaching-notes', true);

        return wpautop($notes);
    }

    if ('teaching-date' === $field_name) {
        $event_presented_date = get_post_meta($post_id, 'teaching-date', true);

        return date(get_option('date_format'), $event_presented_date);
    }

    return $block_content;
}, 10, 4);