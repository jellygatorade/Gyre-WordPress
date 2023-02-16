<?php
/*
Plugin Name: NCMA Artwork Post Type Test 2
Description: Plugin to register the ncma-artwork-test-2 post type. Makes the ncma-artwork-test-2 post type available, creates its Advanced Custom Fields, and creates a custom endpoint for the REST API. Also, sets image uploaded with ACF to main_image field automatically as the featured image of a post.
Version: 2.0
Author: Kevin Kane
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function ncma_artwork_test_2_register_post_type() {

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

    register_post_type( 'ncma-artwork-test-2', $args );

}

add_action( 'init', 'ncma_artwork_test_2_register_post_type' );


/*
Edit title heading of post edit page
https://developer.wordpress.org/reference/hooks/edit_form_top/
https://wordpress.stackexchange.com/questions/120974/edit-form-after-editor-only-in-post-edit-pages
*/
function ncma_artwork_test_1_display_hello( $post ) {
    if ($post->post_type != 'ncma-artwork-test-2') return;
    echo __( 'The title below is for organizing the artwork information within the CMS only (i.e. it is used on the previous page and to assign the artwork to a digital label). The "Title (english)" and "Title (español)" fields will be published to the digital label.' );
}
add_action( 'edit_form_top', 'ncma_artwork_test_1_display_hello' );


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
                'value' => 'ncma-artwork-test-2',
            ),
        ),
    );

    /*Group for fields that do not change with language*/
    acf_add_local_field_group(array(
        'key' => 'artwork-universal',
        'title' => 'Artwork - Universal Data',
        'fields' => array (
            /*Universal fields*/
            array (
                'key' => 'field_artwork_0',
                'label' => 'Artist',
                'name' => 'artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_1',
                'label' => 'Main image',
                'name' => 'main_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_2',
                'label' => 'Detail image 1',
                'name' => 'detail_image_1',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_3',
                'label' => 'Detail image 2',
                'name' => 'detail_image_2',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 0,
    ));

    /*Field group for english*/
    acf_add_local_field_group(array(
        'key' => 'artwork-english',
        'title' => 'Artwork - English Data',
        'fields' => array (
            /*English fields*/
            array (
                'key' => 'field_artwork_4',
                'label' => 'Title (english)',
                'name' => 'en_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_5',
                'label' => 'Region, lifespan or time period (english)',
                'name' => 'en_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_6',
                'label' => 'Creation date (english)',
                'name' => 'en_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_7',
                'label' => 'Medium (english)',
                'name' => 'en_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_8',
                'label' => 'Credit line (english)',
                'name' => 'en_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_9',
                'label' => 'Chat text (english)',
                'name' => 'en_chat_text',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_10',
                'label' => 'Related video 1 Vimeo URL (english)',
                'name' => 'en_related_video_1_vimeo_url',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_11',
                'label' => 'Related video 1 title (english)',
                'name' => 'en_related_video_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_12',
                'label' => 'Related video 1 description (english)',
                'name' => 'en_related_video_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 1,
    ));

    /*Field group for spanish*/
    acf_add_local_field_group(array(
        'key' => 'artwork-spanish',
        'title' => 'Artwork - Spanish Data',
        'fields' => array (
            /*Spanish fields*/
            array (
                'key' => 'field_artwork_13',
                'label' => 'Title (español)',
                'name' => 'es_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_14',
                'label' => 'Region, lifespan or time period (español)',
                'name' => 'es_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_15',
                'label' => 'Creation date (español)',
                'name' => 'es_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_16',
                'label' => 'Medium (español)',
                'name' => 'es_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_17',
                'label' => 'Credit line (español)',
                'name' => 'es_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_18',
                'label' => 'Chat text (español)',
                'name' => 'es_chat_text',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_19',
                'label' => 'Related video 1 Vimeo URL (español)',
                'name' => 'es_related_video_1_vimeo_url',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_20',
                'label' => 'Related video 1 title (español)',
                'name' => 'es_related_video_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_artwork_21',
                'label' => 'Related video 1 description (español)',
                'name' => 'es_related_video_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 2,
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
function kkane_ncma_artwork_test_2_get_data() {
    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => 'ncma-artwork-test-2',
    );

    $posts = get_posts($args);

    $data = array();
    $i = 0;

    foreach($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['post_title'] = $post->post_title;
        $data[$i]['post_date_gmt'] = $post->post_date_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site
        $data[$i]['post_modified_gmt'] = $post->post_modified_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site

        $data[$i]['artist'] = get_field('artist', $post->ID);

        /* 
        I am not 100% about this code below for adding image fields URLs only to REST API.
        Image fields should return as an an array ("image object" in acf terms) by get_field().
        Need to access 'url' key of array and add that to the 

        On 1/26/2022 I saw a bug where get_field() was returning the image ID only by get_field() no matter the presence of the option '?acf_format=standard'
        I had to 'edit' and hit 'publish' button on each artwork post to get the images to return as expected.

        Conincidentally, WordPress 5.9 was released on 1/25/2022. And my admin page started getting the notification of update availability the next day (the day I saw this bug), so I have a suspiscion it is related to that.
        See about updating WordPress 5.8.3 to 5.9 here.
        https://wordpress.org/news/2022/01/wordpress-5-9-rc-1/
        */
        $image_field_names = array(
            'main_image',
            'detail_image_1',
            'detail_image_2'
        );
        for ($j = 0; $j < count($image_field_names); $j++)  {
            $k = $image_field_names[$j];
            // if statements needed because need to access 'url' key of array ("image object" in acf terms) returned by get_field()
            // Check first if get_field('field_name', $post->ID) returns null, and if it is the 'array' datatype.
            // Then check if the key 'url' exists
            // If so, make the REST field.
            $acf_field = get_field($k, $post->ID);
            if (null !== $acf_field and gettype($acf_field) == 'array') {
                if (array_key_exists('url', $acf_field))
                    $data[$i][$k] = $acf_field['url'];
            }
        }

        $data[$i]['en']['title'] = get_field('en_title', $post->ID);
        $data[$i]['en']['nationality_birth_death'] = get_field('en_region_time', $post->ID);
        $data[$i]['en']['creation_date'] = get_field('en_creation_date', $post->ID);
        $data[$i]['en']['medium'] = get_field('en_medium', $post->ID);
        $data[$i]['en']['credit_line'] = get_field('en_credit_line', $post->ID);
        $data[$i]['en']['chat_text'] = get_field('en_chat_text', $post->ID);
        $data[$i]['en']['related_video_1_vimeo_url'] = get_field('en_related_video_1_vimeo_url', $post->ID);
        $data[$i]['en']['related_video_1_title'] = get_field('en_related_video_1_title', $post->ID);
        $data[$i]['en']['related_video_1_description'] = get_field('en_related_video_1_description', $post->ID);

        $data[$i]['es']['title'] = get_field('es_title', $post->ID);
        $data[$i]['es']['nationality_birth_death'] = get_field('es_region_time', $post->ID);
        $data[$i]['es']['creation_date'] = get_field('es_creation_date', $post->ID);
        $data[$i]['es']['medium'] = get_field('es_medium', $post->ID);
        $data[$i]['es']['credit_line'] = get_field('es_credit_line', $post->ID);
        $data[$i]['es']['chat_text'] = get_field('es_chat_text', $post->ID);
        $data[$i]['es']['related_video_1_vimeo_url'] = get_field('es_related_video_1_vimeo_url', $post->ID);
        $data[$i]['es']['related_video_1_title'] = get_field('es_related_video_1_title', $post->ID);
        $data[$i]['es']['related_video_1_description'] = get_field('es_related_video_1_description', $post->ID);

        $i++;
    }

    return $data;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-artwork-test-2', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_artwork_test_2_get_data',
        // Adding a permissions callback
        // https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/#permissions-callback
        'permission_callback' => '__return_true',
    ));
});

/*
Set an image uploaded with ACF to main_image field automatically as the featured image of a post.
https://support.advancedcustomfields.com/forums/topic/set-image-as-featured-image/

acf/update_value/name={$field_name} - filter for a specific field based on it's name
So, this happens if the main_image field exists for any post type.
*/
function acf_set_featured_image( $value, $post_id, $field  ){
    
    if($value != ''){
	    //Add the value which is the image ID to the _thumbnail_id meta data for the current post
	    update_post_meta($post_id, '_thumbnail_id', $value);
    }
 
    return $value;
}
add_filter('acf/update_value/name=main_image', 'acf_set_featured_image', 10, 3);