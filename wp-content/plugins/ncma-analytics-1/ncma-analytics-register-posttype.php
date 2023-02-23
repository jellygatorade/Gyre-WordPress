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
    $existing_ncma_digital_labels_titles = ncma_digital_labels_titles_wp_query();

    foreach ($existing_ncma_digital_labels_titles as $index=>$post) {
        // For each digital label title
        // If there is not an existing ncma-analytics post of the same name, create one
        if (!in_array($post, $existing_ncma_analytics_titles)) {
            ncma_analytics_write_log("{$post['title']} not found in array, creating new ncma-analytics post");

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
            ncma_analytics_write_log("{$post['title']} found");
        }
    }
}

add_action( 'init', 'ncma_analytics_create_posts' );
