<?php


function load_stylesheets()
{
    wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap-4.3.1-dist/css/bootstrap.min.css', array(), false, 'all');
    wp_enqueue_style('bootstrap');

    wp_register_style('style', get_template_directory_uri() . '/style.css', array(), false, 'all');
    wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'load_stylesheets');


function loadjs()
{
    wp_register_script('customjs', get_template_directory_uri() . '/scripts.js', '', 1, true);
    wp_enqueue_script('customjs');
}
add_action('wp_enqueue_scripts', 'loadjs');


function include_jquery()
{

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/jquery-3.6.0/jquery-3.6.0.min.js', '', 1, true);
    add_action('wp_enqueue_scripts', 'jquery');

}
add_action('wp_enqueue_scripts', 'include_jquery');

add_theme_support('menus');

register_nav_menus(

    array(
        'top-menu' => __('Top Menu', 'theme'),
        'footer-menu' => __('Footer Menu','theme')
    )

);