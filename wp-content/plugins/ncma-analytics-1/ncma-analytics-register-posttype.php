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


// Create an ncma-analytics post for each ncma-digital-label
function ncma_analytics_create_posts() {

    // function ncma_digital_labels_titles_wp_query() {
    
    //     $args = array(
    //         'numberposts' => -1, // all
    //         'orderby' => 'title',
    //         'order' => 'ASC',
    //         'post_type' => 'ncma-digital-label',
    //     );
    
    //     $posts = get_posts($args);
    
    //     $the_query = new WP_Query($args);

    //     $WP_Query_data = array();

    //     if ($the_query->have_posts()) {
    //         while ($the_query->have_posts()) {
    //             $the_query->the_post();
    //             $title = get_the_title();
    //             $WP_Query_data[] = array(
    //                 'title' => $title,
    //             );
    //         }
    //     }
    
    //     return $WP_Query_data;
    // }

    function ncma_analytics_titles_wp_query() {
    
        $args = array(
            'numberposts' => -1, // all
            'orderby' => 'title',
            'order' => 'ASC',
            'post_type' => 'ncma-analytics',
        );
    
        //$posts = get_posts($args);
    
        $the_query = new WP_Query($args);

        $WP_Query_data = array();

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $title = get_the_title();
                $WP_Query_data[] = array(
                    'title' => $title,
                );
            }
        }
    
        return $WP_Query_data;
    }

    // $dummy_posts = array(
    //     0 => array(
    //         'title' => 'Kunstkamer 348',
    //     ),
    //     1 => array(
    //         'title' => 'Second post',
    //     ),
    //     2 => array(
    //         'title' => 'Third post',
    //     ),
    // );

    $existing_ncma_analytics_titles = ncma_analytics_titles_wp_query();
    $existing_ncma_digital_labels_titles = ncma_digital_labels_titles_wp_query(); // this is currently defined in ncma-analytics-admin-page.php

    foreach ($existing_ncma_digital_labels_titles as $index=>$post) {
        // For each digital label title
        // If there is not an existing ncma-analytics post of the same name, create one
        if (!in_array($post, $existing_ncma_analytics_titles)) {
            // ncma-digital-label post instance does not already have a matching ncma-analytics post instance (matching post titles were not identified)

            //ncma_analytics_write_log("{$post['title']} not found in array, creating new ncma-analytics post");

            $new_post = array(
                'post_title' => $post['title'],
                'post_status' => 'publish',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => 4, // User ID, 1 is administrator, 4 is apisubscriber
                'post_type' => 'ncma-analytics',
                'post_category' => array(0)
            );

            wp_insert_post($new_post);

        } else {
            // ncma-digital-label post instance already had a matching ncma-analytics post instance (matching post titles were identified)

            //ncma_analytics_write_log("{$post['title']} found");
        }
    }
}
//add_action( 'init', 'ncma_analytics_create_posts' );

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

        $request_analytics_updates = json_decode($request_body['analytics']);

        // $request_body contains
        //
        // Array (
        //     [digital_label_instance] => Kunstkamer 348
        //     [analytics] => {"attract":31,"artwork":52}
        // )

        // $request_analytics_updates contains
        // 
        // stdClass Object (
        //     [attract] => 31
        //     [artwork] => 52
        // )

        // Locate from appropriate ncma-analytics post from $request_body['digital_label_instance']
        $args = array(
            'numberposts' => -1, // all
            'post_type' => 'ncma-analytics',
        );
    
        $posts = get_posts($args);
        
        $analyticsPostID;

        foreach($posts as $post) {
            if ($post->post_title === $request_body['digital_label_instance']) {
                $analyticsPostID = $post->ID;
                break;
            }
        }

        // If an analytics post was located, update the post meta
        if ($analyticsPostID) {
            foreach ($request_analytics_updates as $key => $value) {
                $current_meta = get_post_meta($analyticsPostID, $key, true);
                if ($current_meta) {
                    update_post_meta( $analyticsPostID, $key, $current_meta + $value ); // increment existing totals
                } else {
                    update_post_meta( $analyticsPostID, $key, $value ); // or create new meta if not present
                }
            }
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