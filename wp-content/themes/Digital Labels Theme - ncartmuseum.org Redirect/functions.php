<?php


function load_stylesheets() {
    // wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap-4.3.1-dist/css/bootstrap.min.css', array(), false, 'all');
    // wp_enqueue_style('bootstrap');

    wp_register_style('style', get_template_directory_uri() . '/style.css', array(), false, 'all');
    wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'load_stylesheets');


function ncma_load_redirect_script() {
    wp_enqueue_script('ncma_redirect_script', get_template_directory_uri() . '/js/redirect.js', array(), 1, false);
}
add_action('wp_enqueue_scripts', 'ncma_load_redirect_script');


// Allows access to the site URL via JavaScript in wp-admin pages
// Creates a global JavaScript variable WPURLS, where the siteurl can be accessed as WPURLS.siteurl
// https://stackoverflow.com/questions/5221630/wordpress-path-url-in-js-script-file
function ncma_load_siteurl_script() {
    wp_register_script('ncma_siteurl_script', '' , array(), null, true);
    wp_enqueue_script('ncma_siteurl_script', '' );
    wp_localize_script('ncma_siteurl_script', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));
}
add_action('admin_enqueue_scripts', 'ncma_load_siteurl_script');


// function include_jquery() {
//     wp_deregister_script('jquery');
//     wp_enqueue_script('jquery', get_template_directory_uri() . '/jquery-3.6.0/jquery-3.6.0.min.js', '', 1, true);
//     add_action('wp_enqueue_scripts', 'jquery');
// }
// add_action('wp_enqueue_scripts', 'include_jquery');

// function ncma_loadjs() {
//     wp_register_script('customjs', get_template_directory_uri() . '/redirect.js', '', 1, true);
//     wp_enqueue_script('customjs');
// }
// add_action('wp_enqueue_scripts', 'ncma_loadjs');