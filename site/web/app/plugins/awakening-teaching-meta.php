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
