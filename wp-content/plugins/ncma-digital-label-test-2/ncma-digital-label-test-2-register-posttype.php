<?php
/*
Plugin Name: NCMA Digital Label Post Type Test 2
Description: Plugin to register the ncma-digital-label-2 post type. Makes the ncma-digital-label-2 post type available, creates its Advanced Custom Fields, and creates a custom endpoint for the REST API. Requires validation that selection for 'digital_label_instance' field is unique.
Version: 2.0
Author: Kevin Kane
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function ncma_digital_label_test_2_register_post_type() {

    $labels = array(
        'name' => 'Digital Labels',
        'singular_name' => 'Digital Label',
        'add_new' => 'New Digital Label',
        'add_new_item' => 'Add New Digital Label',
        'edit_item' => 'Edit Digital Label',
        'new_item' => 'New Digital Label',
        'view_item' => 'View Digital Labels',
        'search_items' => 'Search Digital Labels',
        'not_found' =>  'No Digital Labels Found',
        'not_found_in_trash' => 'No Digital Labels found in Trash',
    );

    $args = array(
    'labels' => $labels,
    'has_archive' => false,
    'public' => true,
    'hierarchical' => false,
    'supports' => array(
        'title',
        // 'editor',
        'custom-fields',
        // 'thumbnail',
        // 'page-attributes'
    ),
    // 'taxonomies' => 'category',
    'rewrite'   => array( 'slug' => 'digital-label' ),
    'menu_icon' => 'dashicons-tagcloud',
    'menu_position' => 25, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true,

    /* Permissions Testing */
    // https://wordpress.stackexchange.com/questions/224749/custom-post-type-after-disable-add-new-i-cant-edit-and-delete-post
    // https://templateartist.com/2020/09/07/custom-post-type-capabilities/
    // 'capability_type' => 'post',
    // 'capabilities' => array(
    //     'create_posts' => 'do_not_allow', // Prior to Wordpress 4.5, this was false
    //     'edit_posts' => true,
    //     'edit_post' => true,
    //     'delete_post' => false,
    // ),
    // 'map_meta_cap' => true, //  With this set to true, users will still be able to edit & delete posts


    );

    register_post_type( 'ncma-digital-label-2', $args );

}

add_action( 'init', 'ncma_digital_label_test_2_register_post_type' );


/*
Edit title heading of post edit page to read ____
https://developer.wordpress.org/reference/hooks/edit_form_top/
https://wordpress.stackexchange.com/questions/120974/edit-form-after-editor-only-in-post-edit-pages
*/
function ncma_digital_label_test_2_display_hello( $post ) {
    if ($post->post_type != 'ncma-digital-label-2') return;
    echo __( 'The title below is for naming the label on the previous page only. It does not get published anywhere else.' );
}
add_action( 'edit_form_top', 'ncma_digital_label_test_2_display_hello' );

/*
Register ACF field group + fields for ncma-artwork-test-2 post type.
https://www.advancedcustomfields.com/resources/register-fields-via-php/

All 'key' values must be globally unique!
*/
if( function_exists('acf_add_local_field_group') ):

    /* Used to apply field groups below to the ncma-artwork-test-2 post type */
    $location = array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'ncma-digital-label-2',
            ),
        ),
    );

    /*Group for fields that do not change with language*/
    acf_add_local_field_group(array(
        'key' => 'digital-label-group',
        'title' => 'Digital Label',
        'fields' => array (
            /*Universal fields*/
            array (
                'key' => 'field_digital_label_0',
                'label' => 'Digital Label Instance',
                'name' => 'digital_label_instance',
                'type' => 'select',
                'instructions' => 'Which digital label is this? \'American\', \'European\', \'Judaic\', etc. Select \'Pilot\' for the prototyping equipment we have set up.',
                'required' => 1,
                'choices' => array(
                    'prototype'	=> 'Prototype',
                    'american' => 'American',
                    'european' => 'European',
                    'judaic' => 'Judaic',
                    'none' => 'None'
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,

                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_1',
                'label' => 'Attract Video Vimeo URL',
                'name' => 'attract_video_vimeo_url',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_2',
                'label' => 'Introductory Heading',
                'name' => 'introductory_heading',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_3',
                'label' => 'Introductory Text Body',
                'name' => 'introductory_text_body',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_4',
                'label' => 'Apply Artworks',
                'name' => 'apply_artworks',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'post_type' => 'ncma-artwork-test-2',
                'filters' => array('search'),
                'elements' => array('featured_image'),
                'return_format' => 'id',
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
    ));
    
endif;

/*
Register Custom REST API Endpoint
Following:
WP REST API - Custom Endpoints - by Watch and Learn
https://www.youtube.com/watch?v=C2twS9ArdCI
WP REST API - Custom Post Types And Fields - by Watch and Learn
https://www.youtube.com/watch?v=76sJL9fd12Y
*/
function kkane_ncma_digital_label_2_get_data() {
    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => 'ncma-digital-label-2',
    );

    $posts = get_posts($args);

    $data = array();
    $i = 0;

    foreach($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['post_title'] = $post->post_title;
        $data[$i]['post_date_gmt'] = $post->post_date_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site
        $data[$i]['post_modified_gmt'] = $post->post_modified_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site
        
        $data[$i]['digital_label_instance'] = get_field('digital_label_instance', $post->ID);
        $data[$i]['attract_video_vimeo_url'] = get_field('attract_video_vimeo_url', $post->ID);
        $data[$i]['introductory_heading'] = get_field('introductory_heading', $post->ID);
        $data[$i]['introductory_text_body'] = get_field('introductory_text_body', $post->ID);
        $data[$i]['apply_artworks'] = get_field('apply_artworks', $post->ID);

        $i++;
    }

    return $data;
}

/**
 * This is our callback function that embeds our resource in a WP_REST_Response
 */
function kkane_ncma_get_private_data_permissions_check() {
    // Restrict endpoint to only users who have the edit_posts capability.
    if ( ! current_user_can( 'edit_posts' ) ) {
        return new WP_Error( 'rest_forbidden', esc_html__( 'OMG you can not view private data.', 'my-text-domain' ), array( 'status' => 401 ) );
    }
 
    // This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
    return true;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-digital-label-2', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_digital_label_2_get_data',
        // Adding a permissions callback
        // https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/#permissions-callback
        'permission_callback' => '__return_true',
        //'permission_callback' => 'kkane_ncma_get_private_data_permissions_check',
    ));
});


/*
Require validation that selection for 'digital_label_instance' field is unique among input for that field in other Digital Label posts.
This is necessary so that someone does not accidentally publish 2 "judaic" Digital Labels for example.
The front end software will be designed to download data for a particular digital label instance per this field's input.

Following John Huebner @ November 4, 2016 at 10:30 pm
https://support.advancedcustomfields.com/forums/topic/accept-only-unique-values/#post-45359
*/
function acf_unique_value_field($valid, $value, $field, $input) {
if (!$valid || (!isset($_POST['post_ID']) && !isset($_POST['post_id']))) {
    return $valid;
}
if (isset($_POST['post_ID'])) {
    $post_id = intval($_POST['post_ID']);
} else {
    $post_id = intval($_POST['post_id']);
}
if (!$post_id) {
    return $valid;
}
$post_type = get_post_type($post_id);
$field_name = $field['name'];
$args = array(
    'post_type' => $post_type,
    'post_status' => 'publish, draft, trash',
    'post__not_in' => array($post_id),
    'meta_query' => array(
    array(
        'key' => $field_name,
        'value' => $value
    )
    )
);
$query = new WP_Query($args);
if (count($query->posts)){
    return 'This selection has already been applied to another Digital Label Instance. Please select a unique '.$field['label'].'.';
}
return true;
}

$field_name = 'digital_label_instance';
add_filter('acf/validate_value/name='.$field_name, 'acf_unique_value_field', 10, 4);