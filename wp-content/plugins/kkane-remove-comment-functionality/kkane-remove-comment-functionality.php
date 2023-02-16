<?php
/*
Plugin Name: Remove Comments Functionality
Description: Plugin to remove the comments functionality completely. Removes comments from pages and posts, from the admin UI, and admin bar.
Version: 1.0
Author: Kevin Kane
*/

/*
Custom plugin following https://wordpress.stackexchange.com/questions/11222/is-there-any-way-to-remove-comments-function-and-section-totally
*/

// Removes from admin menu
function kkane_remove_comments_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'kkane_remove_comments_admin_menus' );

// Removes from post and pages
function kkane_remove_comment_support_pages_posts() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
add_action('init', 'kkane_remove_comment_support_pages_posts', 100);

// Removes from admin bar
function kkane_remove_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'kkane_remove_comments_admin_bar' );