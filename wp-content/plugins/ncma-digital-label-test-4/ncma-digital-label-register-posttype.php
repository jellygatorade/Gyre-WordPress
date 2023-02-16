<?php
/*
Plugin Name: NCMA Digital Label Post Type - 4
Description: Plugin to register the ncma-digital-label post type for 5-dla-2. Makes the ncma-digital-label post type available, creates its Advanced Custom Fields, and creates a custom endpoint for the REST API. Requires validation that selection for 'digital_label_instance' field is unique.
Version: 4.0
Author: Kevin Kane
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function ncma_digital_label_register_post_type() {

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
        'custom-fields',
    ),
    'rewrite'   => array( 'slug' => 'digital-label' ),
    'menu_icon' => 'dashicons-tagcloud',
    'menu_position' => 25, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true,

    );

    register_post_type( 'ncma-digital-label', $args );

}

add_action( 'init', 'ncma_digital_label_register_post_type' );


/*
Edit title heading of post edit page to read ____
https://developer.wordpress.org/reference/hooks/edit_form_top/
https://wordpress.stackexchange.com/questions/120974/edit-form-after-editor-only-in-post-edit-pages
*/
function ncma_digital_label_display_hello( $post ) {
    if ($post->post_type != 'ncma-digital-label') return;
    echo __( 'The title below is for naming the label on the previous page only. It does not get published anywhere else.' );
}
add_action( 'edit_form_top', 'ncma_digital_label_display_hello' );

/*
Register ACF field group + fields for ncma-digital-label post type.
https://www.advancedcustomfields.com/resources/register-fields-via-php/

All 'key' values must be globally unique!
*/
if( function_exists('acf_add_local_field_group') ):

    /* Used to apply field groups below to the ncma-digital-label post type */
    $location = array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'ncma-digital-label',
            ),
        ),
    );

    /*Group for fields that do not change with language*/
    acf_add_local_field_group(array(
        'key' => 'digital-label-universal',
        'title' => 'Digital Label - Universal Data',
        'fields' => array (
            /*Universal fields*/
            array (
                'key' => 'field_digital_label_0',
                'label' => 'Digital Label Instance',
                'name' => 'digital_label_instance',
                'type' => 'select',
                'instructions' => 'Which digital label is this? \'American\', \'European\', \'Judaic\', etc. Select a \'Prototype\' option for prototyping outside of the gallery.',
                'required' => 1,
                'choices' => array(
                    'african-561' => 'African 561',
                    'american-233' => 'American 233',
                    'european-437' => 'European 437',
                    'judaic-case-j3' => 'Judaic Case J3',
                    'judaic-case-j4' => 'Judaic Case J4',
                    'judaic-case-j5' => 'Judaic Case J5',
                    'judaic-case-j6' => 'Judaic Case J6',
                    'judaic-case-j11' => 'Judaic Case J11',
                    'kunstkamer-348' => 'Kunstkamer 348',
                    'portraits-and-power' => 'Portraits & Power',
                    'prototype-1'	=> 'Prototype 1',
                    'prototype-2'	=> 'Prototype 2',
                    'prototype-3'	=> 'Prototype 3',
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
                'label' => 'Apply Artworks',
                'name' => 'apply_artworks',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'post_type' => 'ncma-artwork',
                'filters' => array('search'),
                'elements' => array('featured_image'),
                'return_format' => 'id',
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
    ));

    /*Field group for english*/
    acf_add_local_field_group(array(
        'key' => 'digital-label-english',
        'title' => 'Digital Label - English',
        'fields' => array (
            /*English fields*/
            array (
                'key' => 'field_digital_label_3',
                'label' => 'Introductory Heading (english)',
                'name' => 'en_introductory_heading',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_4',
                'label' => 'Introductory Text Body (english)',
                'name' => 'en_introductory_text_body',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
    ));
    
    /*Field group for spanish*/
    acf_add_local_field_group(array(
        'key' => 'digital-label-spanish',
        'title' => 'Digital Label - Spanish',
        'fields' => array (
            /*Spanish fields*/
            array (
                'key' => 'field_digital_label_5',
                'label' => 'Introductory Heading (español)',
                'name' => 'es_introductory_heading',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_digital_label_6',
                'label' => 'Introductory Text Body (español)',
                'name' => 'es_introductory_text_body',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
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
function kkane_ncma_digital_label_get_data() {
    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => 'ncma-digital-label',
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
        $data[$i]['apply_artworks'] = get_field('apply_artworks', $post->ID);

        $data[$i]['en']['introductory_heading'] = get_field('en_introductory_heading', $post->ID);
        $data[$i]['en']['introductory_text_body'] = get_field('en_introductory_text_body', $post->ID);

        $data[$i]['es']['introductory_heading'] = get_field('es_introductory_heading', $post->ID);
        $data[$i]['es']['introductory_text_body'] = get_field('es_introductory_text_body', $post->ID);

        $i++;
    }

    return $data;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-digital-label', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_digital_label_get_data',
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

/*
Modify the default WordPress post updated messages that are displayed when making changes to a post of the 'ncma-artwork' type.
https://ryanwelcher.com/2014/10/change-wordpress-post-updated-messages/
https://developer.wordpress.org/reference/hooks/post_updated_messages/
*/
function ncma_digital_label_post_updated_message($messages) {
    
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );
	
	$messages['ncma-digital-label'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Digital label updated.' ),
		2  => __( 'Custom field updated.' ),
		3  => __( 'Custom field deleted.'),
		4  => __( 'Digital label updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'My Post Type restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Digital label published.' ),
		7  => __( 'Digital label saved.' ),
		8  => __( 'Digital label submitted.' ),
		9  => sprintf(
			__( 'Digital label scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Digital label draft updated.' )
	);

    //you can also access items this way
    // $messages['post'][1] = "I just totally changed the Updated messages for standards posts";

    //return the new messaging 
	return $messages;
}
add_filter( 'post_updated_messages', 'ncma_digital_label_post_updated_message' );