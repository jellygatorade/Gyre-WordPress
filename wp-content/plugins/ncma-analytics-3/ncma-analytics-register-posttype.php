<?php

// Generic function for writing data to wp-content/debug.log file
function ncma_analytics_write_log( $log ) {
    if ( true === WP_DEBUG ) {
        if ( is_array($log) || is_object($log) ) {
            error_log( print_r($log, true) );
        } else {
            error_log( $log );
        }
    }
}


// Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
function ncma_analytics_register_post_type() {
    $labels = array(
        'name' => 'NCMA Analytics',
        'singular_name' => 'NCMA Analytics',
        'add_new' => 'New NCMA Analytics',
        'add_new_item' => 'Add New NCMA Analytics',
        'edit_item' => 'Edit NCMA Analytics',
        'new_item' => 'New NCMA Analytics',
        'view_item' => 'View NCMA Analytics',
        'search_items' => 'Search NCMA Analytics',
        'not_found' =>  'No NCMA Analytics Found',
        'not_found_in_trash' => 'No NCMA Analytics found in Trash',
    );

    $args = array(
    'labels' => $labels,
    'has_archive' => false,
    'public' => false, // implies the settings of 'exclude_from_search', 'publicly_queryable', 'show_in_nav_menus', and 'show_ui'
    'show_ui' => true, // do not show in admin UI
    // 'show_in_nav_menus' => false,
    // 'show_in_menu' => false,
    'hierarchical' => false,
    'supports' => array(
        'title',
        'custom-fields',
    ),
    'rewrite'   => array( 'slug' => 'ncma-analytics' ),
    'menu_icon' => 'dashicons-tagcloud',
    'menu_position' => 28, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true,

    );

    register_post_type( 'ncma-analytics', $args );

}

add_action( 'init', 'ncma_analytics_register_post_type' );

/*******************************
 * Add Advanced Custom Fields
 *******************************/
/*
Register ACF field group + fields for ncma-digital-label post type.
https://www.advancedcustomfields.com/resources/register-fields-via-php/

All 'key' values must be globally unique!
*/
if( function_exists('acf_add_local_field_group') ):

    /* Used to apply field groups below to the ncma-analytics post type */
    $location = array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'ncma-analytics',
            ),
        ),
    );

    /*Group for fields that do not change with language*/
    acf_add_local_field_group(array(
        'key' => 'ncma-analytics-field-group',
        'title' => 'NCMA Analytics Fields',
        'fields' => array (
            array (
                'key' => 'field_ncma_analytics_ncma_digital_label_relationship',
                'label' => 'Relation to digital label',
                'name' => 'ncma_analytics_ncma_digital_label_relationship',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 1,
                'post_type' => 'ncma-digital-label',
                'filters' => array('search'),
                'elements' => array(),
                'max' => 1,
                'return_format' => 'id',
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_analytics_ncma_artwork_relationship',
                'label' => 'Relation to artwork',
                'name' => 'ncma_analytics_ncma_artwork_relationship',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'post_type' => 'ncma-artwork',
                'filters' => array('search'),
                'elements' => array('featured_image'),
                'max' => 1,
                'return_format' => 'id',
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_analytics_clicks',
                'label' => 'Engagements (clicks)',
                'name' => 'ncma_analytics_clicks',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_analytics_average_duration',
                'label' => 'Average duration (seconds)',
                'name' => 'ncma_analytics_average_duration',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
    ));

endif;


/*******************************
 * REST API GET Endpoint
 *******************************/
function kkane_ncma_analytics_get_data() {
    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => 'ncma-analytics',
    );

    $posts = get_posts($args);

    $data = array();
    $i = 0;

    foreach($posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['post_title'] = $post->post_title;
        $data[$i]['post_date_gmt'] = $post->post_date_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site
        $data[$i]['post_modified_gmt'] = $post->post_modified_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site

        // Retrieve post meta here?

        $i++;
    }

    return $data;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-analytics', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_analytics_get_data',
    ));
});

/*******************************
 * REST API POST Endpoint
 *******************************/
function kkane_ncma_analytics_post_data($request) {
    // $request is an object of type WP_REST_Request
    // https://developer.wordpress.org/reference/classes/wp_rest_request/

    if ($request->get_content_type()['value'] === 'application/x-www-form-urlencoded') {
        // Get the body formatted as x-www-form-urlencoded

        // $request_body = $request->get_body(); // returns keys:values as querystring
        $request_body = $request->get_body_params(); // returns keys:values as array

        //$request_analytics_updates = json_decode($request_body['analytics']);

        // $request_body contains
        //
        // Array (
        //     [ncma_digital_label_post_id] => 6,
        //     [ncma_artwork_post_id] => null,
        //     [title] => "some engagements", 
        //     [data] => "test data", 
        //     [analytics] => {"attract":31,"artwork":52}
        // )

        // Create new post.
        $post_data = array(
            'post_title'    => $request_body['title'],
            'post_type'     => 'ncma-analytics',
            'post_status'   => 'publish',
            //'post_author' => 4, // User ID, 1 is administrator, 4 is apisubscriber
            //'post_date' => date('Y-m-d H:i:s'),
        );
        $post_id = wp_insert_post( $post_data );

        // update digital label relationship field
        if ($request_body['ncma_digital_label_post_id']) {
            $field_key = "field_ncma_analytics_ncma_digital_label_relationship";
            $value = $request_body['ncma_digital_label_post_id'];
            update_field( $field_key, $value, $post_id );
        }

        // update artwork relationship field
        if ($request_body['ncma_artwork_post_id']) {
            $field_key = "field_ncma_analytics_ncma_artwork_relationship";
            $value = $request_body['ncma_artwork_post_id'];
            update_field( $field_key, $value, $post_id );
        }

        // update clicks field
        if ($request_body['clicks']) {
            $field_key = "field_ncma_analytics_clicks";
            $value = $request_body['clicks'];
            update_field( $field_key, $value, $post_id );
        }

        // update average duration field
        if ($request_body['average_duration']) {
            $field_key = "field_ncma_analytics_data";
            $value = $request_body['average_duration'];
            update_field( $field_key, $value, $post_id );
        }
        

    } else {
        // The request body content was not of type 'application/x-www-form-urlencoded'
        // return status not ok?
    }
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-analytics', array(
        'methods' => 'POST',
        'callback' => 'kkane_ncma_analytics_post_data',
        'permission_callback' => function () {
            // Needs to be changed after apiauthor account added
            return true;
            //return current_user_can( 'edit_others_posts' );
        }
    ));
});