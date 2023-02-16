<?php
/*
Plugin Name: NCMA Artwork Post Type Test 1
Description: Plugin to register the ncma-artwork-test-1 post type. Makes the ncma-artwork-test-1 post type available and makes its custom fields available in REST API.
Version: 1.0
Author: Kevin Kane
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function ncma_artwork_test_1_register_post_type() {

    $labels = array(
        'name' => 'Artworks',
        'singular_name' => 'Artwork',
        'add_new' => 'New Artwork',
        'add_new_item' => 'Add New Artwork',
        'edit_item' => 'Edit Artwork',
        'new_item' => 'New Artwork',
        'view_item' => 'View Artworks',
        'search_items' => 'Search Artworks',
        'not_found' =>  'No Artworks Found',
        'not_found_in_trash' => 'No Artworks found in Trash',
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
        'thumbnail',
        // 'page-attributes'
    ),
    // 'taxonomies' => 'category',
    'rewrite'   => array( 'slug' => 'artwork' ),
    'menu_icon' => 'dashicons-images-alt',
    'menu_position' => 27, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true
    );

    register_post_type( 'ncma-artwork-test-1', $args );

}

add_action( 'init', 'ncma_artwork_test_1_register_post_type' );


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

// function create_api_posts_meta_field( $args ) {

//     register_rest_field(
//         'kkane-digital-label', // WordPress object type, this was named in register_post_type() and is included in the JSON RESPONSE at /wp-json/wp/v2/kkane-digital-label/
//         $args, // New Field Name in JSON RESPONSEs
//         array(
//             'get_callback'    => function( $object ) use ( $args ) { // $object is supplied by register_rest_field(), $args is a custom argument selected from $post_meta_keys in the for loop
//                 $post_id = $object['id'];
//                 return get_post_meta( $post_id, $args, true );
//             },
//             'update_callback' => null,
//             'schema'          => null,
//             )
//     );

// }

// $post_meta_keys = array(
//     'Artist',
//     'Artist Nationality, Birth Year - Death Year', // Problem with spaces ( ) and special characters (,-) not working with the ?_fields= filter. Moved onto Advanced Custom Fields before getting deep into this.
//     'Artist_Nationality_Birth_Year_Death_Year',
//     'Credit Line',
//     'Date',
//     'Medium',
//     'Main Image',
//     'Detail Image 1',
// );

// for ($i = 0; $i < count($post_meta_keys); $i++)  {

//     $j = $post_meta_keys[$i];

//     add_action('rest_api_init', function() use ( $j ) { 
//         create_api_posts_meta_field( $j ); });

// }

/*
Edit title heading of post edit page to read ____
https://developer.wordpress.org/reference/hooks/edit_form_top/
https://wordpress.stackexchange.com/questions/120974/edit-form-after-editor-only-in-post-edit-pages
*/
function ncma_artwork_test_1_display_hello( $post ) {
    if ($post->post_type != 'ncma-artwork-test-1') return;
    echo __( 'The title below is for organizing the artwork information within the CMS only (i.e. it is used on the previous page and to assign the artwork to a digital label). The "Title (english)" and "Title (español)" fields will be published to the digital label.' );
}
add_action( 'edit_form_top', 'ncma_artwork_test_1_display_hello' );

/*
Register Custom REST API Endpoint
Following:
WP REST API - Custom Endpoints - by Watch and Learn
https://www.youtube.com/watch?v=C2twS9ArdCI
WP REST API - Custom Post Types And Fields - by Watch and Learn
https://www.youtube.com/watch?v=76sJL9fd12Y
*/
/*
function kkane_ncma_artwork_test_1_get_data() {
    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => 'ncma-artwork-test-1',
    );

    $posts = get_posts($args);

    $data = array();
    $i = 0;

    foreach($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['title'] = $post->post_title;
        $data[$i]['main_image'] = get_field('main_image_acf', $post->ID);

        $data[$i]['en']['title'] = get_field('title_en', $post->ID);
        $data[$i]['en']['nationality_birth_death'] = get_field('artist_nationality_birth_date_-_death_date_en', $post->ID);
        $data[$i]['en']['creation_date'] = get_field('creation_date_en', $post->ID);
        $data[$i]['en']['medium'] = get_field('medium_en', $post->ID);
        $data[$i]['en']['credit_line'] = get_field('credit_line_en', $post->ID);
        $data[$i]['en']['chat_text'] = get_field('chat_text_en', $post->ID);

        $data[$i]['es']['title'] = get_field('title_es', $post->ID);
        $data[$i]['es']['nationality_birth_death'] = get_field('artist_nationality_birth_date_-_death_date_es', $post->ID);
        $data[$i]['es']['creation_date'] = get_field('creation_date_es', $post->ID);
        $data[$i]['es']['medium'] = get_field('medium_es', $post->ID);
        $data[$i]['es']['credit_line'] = get_field('credit_line_es', $post->ID);
        $data[$i]['es']['chat_text'] = get_field('chat_text_es', $post->ID);
        $i++;
    }

    return $data;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-artwork-test-1', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_artwork_test_1_get_data',
    ));
});
*/