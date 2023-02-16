<?php


function load_stylesheets()
{
    wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap-4.3.1-dist/css/bootstrap.min.css', array(), false, 'all');
    wp_enqueue_style('bootstrap');

    wp_register_style('style', get_template_directory_uri() . '/style.css', array(), false, 'all');
    wp_enqueue_style('style');
}
add_action('wp_enqueue_scripts', 'load_stylesheets');


// function ncma_loadjs()
// {
//     wp_register_script('customjs', get_template_directory_uri() . '/redirect.js', '', 1, true);
//     wp_enqueue_script('customjs');
// }
// add_action('wp_enqueue_scripts', 'ncma_loadjs');

function ncma_load_redirect_script() {
    wp_enqueue_script('ncma_redirect_script', get_template_directory_uri() . '/js/redirect.js', array(), 1, false);
  }
add_action('wp_enqueue_scripts', 'ncma_load_redirect_script');


function include_jquery()
{

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/jquery-3.6.0/jquery-3.6.0.min.js', '', 1, true);
    add_action('wp_enqueue_scripts', 'jquery');

}
add_action('wp_enqueue_scripts', 'include_jquery');

/*
add_theme_support('menus');

register_nav_menus(

    array(
        'top-menu' => __('Top Menu', 'theme'),
        'footer-menu' => __('Footer Menu','theme')
    )

);
*/

/* feature below was moved to the plugin ncma-artwork-test-2-register-posttype.php*/
/*
Set an image uploaded with ACF to main_image field automatically as the featured image of a post.
https://support.advancedcustomfields.com/forums/topic/set-image-as-featured-image/

acf/update_value/name={$field_name} - filter for a specific field based on it's name
So, this happens if the main_image field exists for any post type.
*/
/*
function acf_set_featured_image( $value, $post_id, $field  ){
    
    if($value != ''){
	    //Add the value which is the image ID to the _thumbnail_id meta data for the current post
	    update_post_meta($post_id, '_thumbnail_id', $value);
    }
 
    return $value;
}
add_filter('acf/update_value/name=main_image', 'acf_set_featured_image', 10, 3);
*/
