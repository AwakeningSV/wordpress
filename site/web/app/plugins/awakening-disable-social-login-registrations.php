<?php
/*
Plugin Name: Awakening Disable Social Login Registrations
Description: Disable new users from signing up using OneAll Social Login.
Author: Reid Burke
Version: 1.0
Author URI: https://reidburke.com/
*/

function ac_social_login_disable_registrations($email) {
    if (!email_exists($email)) {
        wp_die('Access to this resource is denied.', 'Access denied', array(
            'response' => 403
        ));
    }

    return $email;
}

add_filter('oa_social_login_filter_new_user_email', 'ac_social_login_disable_registrations');