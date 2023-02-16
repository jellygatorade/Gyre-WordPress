<?php
/*
Plugin Name: Remove Posts from admin UI
Description: Plugin to remove the posts from admin UI. Removes posts from the admin UI left menu, and admin menu bar, and the quick draft dashboard widget, as well as most features from the post edit page.
Version: 1.0
Author: Kevin Kane
*/

/*
Remove Posts links from side menu, new post in Admin menu bar, and post Quick Draft Dashboard widget.
https://www.mitostudios.com/blog/how-to-remove-posts-blog-post-type-from-wordpress/
*/
// ************* Remove default Posts type since no blog *************
// Remove side menu
function kkane_remove_default_post_type() {
    remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'kkane_remove_default_post_type' );

// Remove +New post in top Admin Menu Bar
function kkane_remove_default_post_type_menu_bar( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'new-post' );
}
add_action( 'admin_bar_menu', 'kkane_remove_default_post_type_menu_bar', 999 );

// Remove Quick Draft Dashboard Widget
function kkane_remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'kkane_remove_draft_widget', 999 );


/*
Remove support for the following on the post edit page so that post titles, content, etc cannot be input or changed.
https://www.isitwp.com/remove-support-for-specific-post-type-features/
https://developer.wordpress.org/reference/functions/remove_post_type_support/
*/
    //'title'  (Post Title)
    //'editor' (content)
    //'author'  (Author controls)
    //'thumbnail' (featured image) (current theme must also support Post Thumbnails)
    //'excerpt'   (Excerpt functionality)
    //'trackbacks' (Options)
    //'custom-fields' (Custom Fields)
    //'comments' (also will see comment count balloon on edit screen)
    //'revisions' (will store revisions)
    //'page-attributes' (template and menu order) (hierarchical must be true)
 
function kkane_remove_post_type_post_support() {
    remove_post_type_support( 'post', 'title' );
    remove_post_type_support( 'post', 'editor' );
    remove_post_type_support( 'post', 'author' );
    remove_post_type_support( 'post', 'thumbnail' );
    remove_post_type_support( 'post', 'excerpt' );
    remove_post_type_support( 'post', 'trackbacks' );
    remove_post_type_support( 'post', 'custom-fields') ;
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'revisions' );
    remove_post_type_support( 'post', 'page-attributes' );
}
add_action( 'admin_init', 'kkane_remove_post_type_post_support' );

/*
Remove the Categories and Tags meta box (widgets) from the post edit page.
Erik K:
https://wordpress.stackexchange.com/questions/110782/remove-categories-tags-from-admin-menu
*/
function kkane_remove_post_metaboxes() {
    remove_meta_box( 'categorydiv','post','normal' ); // Categories Metabox
    remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox
}
add_action('admin_menu','kkane_remove_post_metaboxes');

/*
Remove theme support for post formats, and the Format meta box from the post edit page.
*/
// Higher value on the priority then the default of 10 makes sure this is run after the initial removal.
function kkane_remove_post_format() {
    remove_theme_support('post-formats');
}
add_action('after_setup_theme', 'kkane_remove_post_format', 15);

// End remove post type