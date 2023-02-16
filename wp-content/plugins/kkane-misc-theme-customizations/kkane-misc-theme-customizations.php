<?php
/*
Plugin Name: Miscellaneous Theme Customizations
Description: Allows author role to edit others' posts. Remove Dashboard Widges: "Activity" and "At a Glance".
Version: 1.0
Author: Kevin Kane
*/

/*
Allow author role to edit others' posts: https://www.isitwp.com/give-authors-capabilities-including-editing-other-authors-posts/
*/
function kkane_add_theme_caps() {
    $role = get_role( 'author' );
    $role->add_cap( 'edit_others_posts' ); 
 }
 add_action( 'admin_init', 'kkane_add_theme_caps');


/*
https://www.adamboother.com/blog/how-to-remove-widgets-from-wordpress-dashboard/
Remove Dashboard Widgets:
Activity
At a Glance
*/
function kkane_remove_dashboard_meta() {
    // remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
}
add_action('admin_init', 'kkane_remove_dashboard_meta');
