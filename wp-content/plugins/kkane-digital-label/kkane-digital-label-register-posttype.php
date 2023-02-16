<?php
/*
Plugin Name: Digital Label Custom Post Type 1
Description: Plugin to register the kkane-digital-label post type. Makes the kkane-digital-label post type available and makes its custom fields available in REST API.
Version: 1.0
Author: Kevin Kane
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function kkane_digital_label_register_post_type() {

    // Commented out because not using internationalization
    // $labels = array(
    //     'name' => __( 'Digital Labels', 'kkane'),
    //     'singular_name' => __( 'Digital Label', 'kkane' ),
    //     'add_new' => __( 'New Digital Label', 'kkane' ),
    //     'add_new_item' => __( 'Add New Digital Label', 'kkane' ),
    //     'edit_item' => __( 'Edit Digital Label', 'kkane' ),
    //     'new_item' => __( 'New Digital Label', 'kkane' ),
    //     'view_item' => __( 'View Digital Labels', 'kkane' ),
    //     'search_items' => __( 'Search Digital Labels', 'kkane' ),
    //     'not_found' =>  __( 'No Digital Labels Found', 'kkane' ),
    //     'not_found_in_trash' => __( 'No Digital Labels found in Trash', 'kkane' ),
    //    );

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
        'editor',
        'custom-fields',
        'thumbnail',
        // 'page-attributes'
    ),
    // 'taxonomies' => 'category',
    'rewrite'   => array( 'slug' => 'digital-label' ),
    'menu_icon' => 'dashicons-tagcloud',
    //'menu_position' => 1520, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true
    );

    register_post_type( 'kkane-digital-label', $args );

}

add_action( 'init', 'kkane_digital_label_register_post_type' );


// Make custom field data available in REST API
// following Nate Finch: https://developer.wordpress.org/reference/functions/register_rest_field/#comment-2145

// function create_api_posts_meta_field() {

//     register_rest_field(
//         'kkane-digital-label', // WordPress object type, this is included in the JSON RESPONSE at /wp-json/wp/v2/kkane-digital-label/
//         'post-meta-custom-fields', // New Field Name in JSON RESPONSEs
//         //'Artist', // New Field Name in JSON RESPONSEs
//         array(
//             'get_callback'    => 'get_post_meta_for_api', // custom function name 
//             'update_callback' => null,
//             'schema'          => null,
//             )
//     );

// }

// function get_post_meta_for_api( $object ) {

//     $post_id = $object['id'];
//     //return get_post_meta( $post_id );
//     return get_post_meta( $post_id, '', true );
//     //return get_post_meta( $post_id, 'Artist', true );

// }

// add_action( 'rest_api_init', 'create_api_posts_meta_field' );

// Make custom field data available in REST API
// following Nate Finch: https://developer.wordpress.org/reference/functions/register_rest_field/#comment-2145

    /////////////////////////////////////////////////////
    // Register fields individually in a php for loop! //
    /////////////////////////////////////////////////////

function create_api_posts_meta_field( $args ) {

    register_rest_field(
        'kkane-digital-label', // WordPress object type, this was named in register_post_type() and is included in the JSON RESPONSE at /wp-json/wp/v2/kkane-digital-label/
        $args, // New Field Name in JSON RESPONSEs
        array(
            'get_callback'    => function( $object ) use ( $args ) { // $object is supplied by register_rest_field(), $args is a custom argument selected from $post_meta_keys in the for loop
                $post_id = $object['id'];
                return get_post_meta( $post_id, $args, true );
            },
            'update_callback' => null,
            'schema'          => null,
            )
    );

}

$post_meta_keys = array(
    'Artist',
    'Artist Nationality, Birth Year - Death Year', // Problem with spaces ( ) and special characters (,-) not working with the ?_fields= filter. Moved onto Advanced Custom Fields before getting deep into this.
    'Artist_Nationality_Birth_Year_Death_Year',
    'Credit Line',
    'Date',
    'Medium',
    'Main Image',
    'Detail Image 1',
);

for ($i = 0; $i < count($post_meta_keys); $i++)  {

    $j = $post_meta_keys[$i];

    add_action('rest_api_init', function() use ( $j ) { 
        create_api_posts_meta_field( $j ); });

}

