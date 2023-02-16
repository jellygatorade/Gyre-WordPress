<?php
/*
Plugin Name: NCMA Artwork Post Type - 4
Description: Plugin to register the ncma-artwork post type for 5-dla-2. Makes the ncma-artwork post type available, creates its Advanced Custom Fields, and creates a custom endpoint for the REST API. Also, sets image uploaded with ACF to main_image field automatically as the featured image of a post.
Version: 4.0
Author: Kevin Kane
*/

/*
3/24 changes needed:
Move artist name to english and spanish
"Universal data" will be changed to "Images"

Categories will then be
- Images (5x)
- Tombstone (En, Es)
- Related Videos (En, Es) (5x)
- - Vimeo URL, title, description
- Interchanges (En, Es) (5x)
- - 2nd Artwork Image, 6 fields of tombstone (no chat), description (text area)
- Conservation Stories (En, Es) (5x)
- - Main image, title, description, alternative image, caption for alternative image, each

https://docs.google.com/document/d/1zdG_H1UOzY9ertZdlvuSjSY0n7NhYzF-U0YdkJxiTkg/edit
*/

/*
Custom Post Type following https://kinsta.com/blog/wordpress-custom-post-types/
*/

function ncma_artwork_register_post_type() {

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
        'custom-fields',
        'thumbnail',
    ),
    'rewrite'   => array( 'slug' => 'artwork' ),
    'menu_icon' => 'dashicons-images-alt',
    'menu_position' => 27, // for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    'show_in_rest' => true
    );

    register_post_type( 'ncma-artwork', $args );

}

add_action( 'init', 'ncma_artwork_register_post_type' );


/*
Edit title heading of post edit page
https://developer.wordpress.org/reference/hooks/edit_form_top/
https://wordpress.stackexchange.com/questions/120974/edit-form-after-editor-only-in-post-edit-pages
*/
function ncma_artwork_display_hello( $post ) {
    if ($post->post_type != 'ncma-artwork') return;
    echo __( 'The title below is for organizing the artwork information within the CMS only (i.e. it is used on the previous page and to assign the artwork to a digital label). The "Title (english)" and "Title (español)" fields will be published to the digital label.' );
}
add_action( 'edit_form_top', 'ncma_artwork_display_hello' );


/*
Register ACF field group + fields for ncma-artwork post type.
https://www.advancedcustomfields.com/resources/register-fields-via-php/

All 'key' values must be globally unique!
*/
if( function_exists('acf_add_local_field_group') ):

    /* Used to apply field groups below to the ncma-artwork post type */
    $location = array (
        array (
            array (
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'ncma-artwork',
            ),
        ),
    );

    /*Group for fields that do not change with language*/
    acf_add_local_field_group(array(
        'key' => 'artwork-images',
        'title' => 'Artwork - Main Images',
        'fields' => array (
            /*Image fields*/
            array (
                'key' => 'field_ncma_artwork_images_main',
                'label' => 'Main image',
                'name' => 'artwork_main_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_images_alt_1',
                'label' => 'Alternative image 1',
                'name' => 'artwork_alt_image_1',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_images_alt_2',
                'label' => 'Alternative image 2',
                'name' => 'artwork_alt_image_2',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_images_alt_4',
                'label' => 'Alternative image 3',
                'name' => 'artwork_alt_image_3',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_images_alt_5',
                'label' => 'Alternative image 4',
                'name' => 'artwork_alt_image_4',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 0,
    ));

    /* Field group for tombstone - english, spanish */
    acf_add_local_field_group(array(
        'key' => 'artwork-tombstone-english-spanish',
        'title' => 'Artwork - Tombstone - English, Spanish',
        'fields' => array (
            /* English fields */
            array (
                'key' => 'field_ncma_artwork_en_tombstone_artist',
                'label' => 'Artist (english)',
                'name' => 'en_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_title',
                'label' => 'Title (english)',
                'name' => 'en_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_region_time',
                'label' => 'Region, lifespan or time period (english)',
                'name' => 'en_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_creation_date',
                'label' => 'Creation date (english)',
                'name' => 'en_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_medium',
                'label' => 'Medium (english)',
                'name' => 'en_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_credit_line',
                'label' => 'Credit line (english)',
                'name' => 'en_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_tombstone_chat_text',
                'label' => 'Chat text (english)',
                'name' => 'en_chat_text',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Spanish fields*/
            array (
                'key' => 'field_ncma_artwork_es_tombstone_artist',
                'label' => 'Artist (español)',
                'name' => 'es_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_title',
                'label' => 'Title (español)',
                'name' => 'es_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_region_time',
                'label' => 'Region, lifespan or time period (español)',
                'name' => 'es_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_creation_date',
                'label' => 'Creation date (español)',
                'name' => 'es_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_medium',
                'label' => 'Medium (español)',
                'name' => 'es_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_credit_line',
                'label' => 'Credit line (español)',
                'name' => 'es_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_tombstone_chat_text',
                'label' => 'Chat text (español)',
                'name' => 'es_chat_text',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 1,
    ));

    /* Field group for related videos - english, spanish */
    acf_add_local_field_group(array(
        'key' => 'artwork-related-videos-english-spanish',
        'title' => 'Artwork - Related Videos - English, Spanish',
        'fields' => array (
            /* Related Video 1 english */
            array (
                'key' => 'field_ncma_artwork_en_related_video_1_vimeo_url',
                'label' => 'Related video 1 Vimeo URL (english)',
                'name' => 'en_related_video_1_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_1_title',
                'label' => 'Related video 1 title (english)',
                'name' => 'en_related_video_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_1_description',
                'label' => 'Related video 1 description (english)',
                'name' => 'en_related_video_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 1 spanish */
            array (
                'key' => 'field_ncma_artwork_es_related_video_1_vimeo_url',
                'label' => 'Related video 1 Vimeo URL (español)',
                'name' => 'es_related_video_1_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_1_title',
                'label' => 'Related video 1 title (español)',
                'name' => 'es_related_video_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_1_description',
                'label' => 'Related video 1 description (español)',
                'name' => 'es_related_video_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 2 english */
            array (
                'key' => 'field_ncma_artwork_en_related_video_2_vimeo_url',
                'label' => 'Related video 2 Vimeo URL (english)',
                'name' => 'en_related_video_2_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_2_title',
                'label' => 'Related video 2 title (english)',
                'name' => 'en_related_video_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_2_description',
                'label' => 'Related video 2 description (english)',
                'name' => 'en_related_video_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 2 spanish */
            array (
                'key' => 'field_ncma_artwork_es_related_video_2_vimeo_url',
                'label' => 'Related video 2 Vimeo URL (español)',
                'name' => 'es_related_video_2_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_2_title',
                'label' => 'Related video 2 title (español)',
                'name' => 'es_related_video_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_2_description',
                'label' => 'Related video 2 description (español)',
                'name' => 'es_related_video_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 3 english */
            array (
                'key' => 'field_ncma_artwork_en_related_video_3_vimeo_url',
                'label' => 'Related video 3 Vimeo URL (english)',
                'name' => 'en_related_video_3_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_3_title',
                'label' => 'Related video 3 title (english)',
                'name' => 'en_related_video_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_3_description',
                'label' => 'Related video 3 description (english)',
                'name' => 'en_related_video_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 3 spanish */
            array (
                'key' => 'field_ncma_artwork_es_related_video_3_vimeo_url',
                'label' => 'Related video 3 Vimeo URL (español)',
                'name' => 'es_related_video_3_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_3_title',
                'label' => 'Related video 3 title (español)',
                'name' => 'es_related_video_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_3_description',
                'label' => 'Related video 3 description (español)',
                'name' => 'es_related_video_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 4 english */
            array (
                'key' => 'field_ncma_artwork_en_related_video_4_vimeo_url',
                'label' => 'Related video 4 Vimeo URL (english)',
                'name' => 'en_related_video_4_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_4_title',
                'label' => 'Related video 4 title (english)',
                'name' => 'en_related_video_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_4_description',
                'label' => 'Related video 4 description (english)',
                'name' => 'en_related_video_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 4 spanish */
            array (
                'key' => 'field_ncma_artwork_es_related_video_4_vimeo_url',
                'label' => 'Related video 4 Vimeo URL (español)',
                'name' => 'es_related_video_4_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_4_title',
                'label' => 'Related video 4 title (español)',
                'name' => 'es_related_video_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_4_description',
                'label' => 'Related video 4 description (español)',
                'name' => 'es_related_video_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 5 english */
            array (
                'key' => 'field_ncma_artwork_en_related_video_5_vimeo_url',
                'label' => 'Related video 5 Vimeo URL (english)',
                'name' => 'en_related_video_5_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_5_title',
                'label' => 'Related video 5 title (english)',
                'name' => 'en_related_video_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_related_video_5_description',
                'label' => 'Related video 5 description (english)',
                'name' => 'en_related_video_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Related Video 5 spanish */
            array (
                'key' => 'field_ncma_artwork_es_related_video_5_vimeo_url',
                'label' => 'Related video 5 Vimeo URL (español)',
                'name' => 'es_related_video_5_vimeo_url',
                'type' => 'text',
                'instructions' => 'Must be in the format of \'https://vimeo.com/[video id number]\'',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_5_title',
                'label' => 'Related video 5 title (español)',
                'name' => 'es_related_video_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_related_video_5_description',
                'label' => 'Related video 5 description (español)',
                'name' => 'es_related_video_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 2,
        ));

    /*Field group for interchanges - english, spanish*/
    acf_add_local_field_group(array(
        'key' => 'artwork-interchanges-english-spanish',
        'title' => 'Artwork - Interchanges - English, Spanish',
        'fields' => array (

            /*Interchange 1 images*/
            array (
                'key' => 'field_ncma_artwork_interchange_1_image',
                'label' => 'Interchange 1 artwork image',
                'name' => 'interchange_1_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 1 english*/
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_artist',
                'label' => 'Interchange 1 artist (english)',
                'name' => 'en_interchange_1_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_title',
                'label' => 'Interchange 1 artwork title (english)',
                'name' => 'en_interchange_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_region_time',
                'label' => 'Interchange 1 region, lifespan or time period (english)',
                'name' => 'en_interchange_1_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_creation_date',
                'label' => 'Interchange 1 creation date (english)',
                'name' => 'en_interchange_1_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_medium',
                'label' => 'Interchange 1 medium (english)',
                'name' => 'en_interchange_1_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_credit_line',
                'label' => 'Interchange 1 credit line (english)',
                'name' => 'en_interchange_1_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_en_description',
                'label' => 'Interchange 1 description (english)',
                'name' => 'en_interchange_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 1 spanish*/
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_artist',
                'label' => 'Interchange 1 artist (español)',
                'name' => 'es_interchange_1_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_title',
                'label' => 'Interchange 1 artwork title (español)',
                'name' => 'es_interchange_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_region_time',
                'label' => 'Interchange 1 region, lifespan or time period (español)',
                'name' => 'es_interchange_1_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_creation_date',
                'label' => 'Interchange 1 creation date (español)',
                'name' => 'es_interchange_1_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_medium',
                'label' => 'Interchange 1 medium (español)',
                'name' => 'es_interchange_1_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_credit_line',
                'label' => 'Interchange 1 credit line (español)',
                'name' => 'es_interchange_1_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_1_es_description',
                'label' => 'Interchange 1 description (español)',
                'name' => 'es_interchange_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /*Interchange 2 images*/
            array (
                'key' => 'field_ncma_artwork_interchange_2_image',
                'label' => 'Interchange 2 artwork image',
                'name' => 'interchange_2_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 2 english*/
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_artist',
                'label' => 'Interchange 2 artist (english)',
                'name' => 'en_interchange_2_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_title',
                'label' => 'Interchange 2 artwork title (english)',
                'name' => 'en_interchange_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_region_time',
                'label' => 'Interchange 2 region, lifespan or time period (english)',
                'name' => 'en_interchange_2_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_creation_date',
                'label' => 'Interchange 2 creation date (english)',
                'name' => 'en_interchange_2_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_medium',
                'label' => 'Interchange 2 medium (english)',
                'name' => 'en_interchange_2_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_credit_line',
                'label' => 'Interchange 2 credit line (english)',
                'name' => 'en_interchange_2_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_en_description',
                'label' => 'Interchange 2 description (english)',
                'name' => 'en_interchange_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 2 spanish*/
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_artist',
                'label' => 'Interchange 2 artist (español)',
                'name' => 'es_interchange_2_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_title',
                'label' => 'Interchange 2 artwork title (español)',
                'name' => 'es_interchange_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_region_time',
                'label' => 'Interchange 2 region, lifespan or time period (español)',
                'name' => 'es_interchange_2_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_creation_date',
                'label' => 'Interchange 2 creation date (español)',
                'name' => 'es_interchange_2_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_medium',
                'label' => 'Interchange 2 medium (español)',
                'name' => 'es_interchange_2_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_credit_line',
                'label' => 'Interchange 2 credit line (español)',
                'name' => 'es_interchange_2_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_2_es_description',
                'label' => 'Interchange 2 description (español)',
                'name' => 'es_interchange_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /*Interchange 3 images*/
            array (
                'key' => 'field_ncma_artwork_interchange_3_image',
                'label' => 'Interchange 3 artwork image',
                'name' => 'interchange_3_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 3 english*/
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_artist',
                'label' => 'Interchange 3 artist (english)',
                'name' => 'en_interchange_3_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_title',
                'label' => 'Interchange 3 artwork title (english)',
                'name' => 'en_interchange_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_region_time',
                'label' => 'Interchange 3 region, lifespan or time period (english)',
                'name' => 'en_interchange_3_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_creation_date',
                'label' => 'Interchange 3 creation date (english)',
                'name' => 'en_interchange_3_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_medium',
                'label' => 'Interchange 3 medium (english)',
                'name' => 'en_interchange_3_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_credit_line',
                'label' => 'Interchange 3 credit line (english)',
                'name' => 'en_interchange_3_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_en_description',
                'label' => 'Interchange 3 description (english)',
                'name' => 'en_interchange_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 3 spanish*/
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_artist',
                'label' => 'Interchange 3 artist (español)',
                'name' => 'es_interchange_3_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_title',
                'label' => 'Interchange 3 artwork title (español)',
                'name' => 'es_interchange_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_region_time',
                'label' => 'Interchange 3 region, lifespan or time period (español)',
                'name' => 'es_interchange_3_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_creation_date',
                'label' => 'Interchange 3 creation date (español)',
                'name' => 'es_interchange_3_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_medium',
                'label' => 'Interchange 3 medium (español)',
                'name' => 'es_interchange_3_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_credit_line',
                'label' => 'Interchange 3 credit line (español)',
                'name' => 'es_interchange_3_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_3_es_description',
                'label' => 'Interchange 3 description (español)',
                'name' => 'es_interchange_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /*Interchange 4 images*/
            array (
                'key' => 'field_ncma_artwork_interchange_4_image',
                'label' => 'Interchange 4 artwork image',
                'name' => 'interchange_4_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 4 english*/
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_artist',
                'label' => 'Interchange 4 artist (english)',
                'name' => 'en_interchange_4_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_title',
                'label' => 'Interchange 4 artwork title (english)',
                'name' => 'en_interchange_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_region_time',
                'label' => 'Interchange 4 region, lifespan or time period (english)',
                'name' => 'en_interchange_4_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_creation_date',
                'label' => 'Interchange 4 creation date (english)',
                'name' => 'en_interchange_4_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_medium',
                'label' => 'Interchange 4 medium (english)',
                'name' => 'en_interchange_4_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_credit_line',
                'label' => 'Interchange 4 credit line (english)',
                'name' => 'en_interchange_4_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_en_description',
                'label' => 'Interchange 4 description (english)',
                'name' => 'en_interchange_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 4 spanish*/
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_artist',
                'label' => 'Interchange 4 artist (español)',
                'name' => 'es_interchange_4_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_title',
                'label' => 'Interchange 4 artwork title (español)',
                'name' => 'es_interchange_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_region_time',
                'label' => 'Interchange 4 region, lifespan or time period (español)',
                'name' => 'es_interchange_4_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_creation_date',
                'label' => 'Interchange 4 creation date (español)',
                'name' => 'es_interchange_4_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_medium',
                'label' => 'Interchange 4 medium (español)',
                'name' => 'es_interchange_4_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_credit_line',
                'label' => 'Interchange 4 credit line (español)',
                'name' => 'es_interchange_4_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_4_es_description',
                'label' => 'Interchange 4 description (español)',
                'name' => 'es_interchange_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /*Interchange 5 images*/
            array (
                'key' => 'field_ncma_artwork_interchange_5_image',
                'label' => 'Interchange 5 artwork image',
                'name' => 'interchange_5_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 5 english*/
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_artist',
                'label' => 'Interchange 5 artist (english)',
                'name' => 'en_interchange_5_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_title',
                'label' => 'Interchange 5 artwork title (english)',
                'name' => 'en_interchange_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_region_time',
                'label' => 'Interchange 5 region, lifespan or time period (english)',
                'name' => 'en_interchange_5_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_creation_date',
                'label' => 'Interchange 5 creation date (english)',
                'name' => 'en_interchange_5_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_medium',
                'label' => 'Interchange 5 medium (english)',
                'name' => 'en_interchange_5_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_credit_line',
                'label' => 'Interchange 5 credit line (english)',
                'name' => 'en_interchange_5_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_en_description',
                'label' => 'Interchange 5 description (english)',
                'name' => 'en_interchange_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /*Interchange 5 spanish*/
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_artist',
                'label' => 'Interchange 5 artist (español)',
                'name' => 'es_interchange_5_artist',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_title',
                'label' => 'Interchange 5 artwork title (español)',
                'name' => 'es_interchange_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_region_time',
                'label' => 'Interchange 5 region, lifespan or time period (español)',
                'name' => 'es_interchange_5_region_time',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_creation_date',
                'label' => 'Interchange 5 creation date (español)',
                'name' => 'es_interchange_5_creation_date',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_medium',
                'label' => 'Interchange 5 medium (español)',
                'name' => 'es_interchange_5_medium',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_credit_line',
                'label' => 'Interchange 5 credit line (español)',
                'name' => 'es_interchange_5_credit_line',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_interchange_5_es_description',
                'label' => 'Interchange 5 description (español)',
                'name' => 'es_interchange_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

        ),
        'location' => $location,
        'menu_order' => 3,
    ));

    /* Field group for conservation stories - english, spanish */
    acf_add_local_field_group(array(
        'key' => 'artwork-conservation-stories-english-spanish',
        'title' => 'Artwork - Conservation Stories - English, Spanish',
        'fields' => array (
            /* Conservation Story 1 - title and description - english */
            array (
                'key' => 'field_ncma_artwork_en_conservation_1_title',
                'label' => 'Conservation Story 1 title (english)',
                'name' => 'en_conservation_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_1_description',
                'label' => 'Conservation Story 1 description (english)',
                'name' => 'en_conservation_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 1 - title and description - spanish */
            array (
                'key' => 'field_ncma_artwork_es_conservation_1_title',
                'label' => 'Conservation Story 1 title (español)',
                'name' => 'es_conservation_1_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_1_description',
                'label' => 'Conservation Story 1 description (español)',
                'name' => 'es_conservation_1_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 1 - image 1 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_1_primary_image',
                'label' => 'Conservation Story 1 primary image',
                'name' => 'conservation_1_primary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_1_primary_image_caption',
                'label' => 'Conservation Story 1 primary image caption (english)',
                'name' => 'en_conservation_1_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_1_primary_image_caption',
                'label' => 'Conservation Story 1 primary image caption (español)',
                'name' => 'es_conservation_1_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 1 - image 2 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_1_secondary_image',
                'label' => 'Conservation Story 1 secondary image',
                'name' => 'conservation_1_secondary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_1_secondary_image_caption',
                'label' => 'Conservation Story 1 secondary image caption (english)',
                'name' => 'en_conservation_1_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_1_secondary_image_caption',
                'label' => 'Conservation Story 1 secondary image caption (español)',
                'name' => 'es_conservation_1_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /* Conservation Story 2 - title and description - english */
            array (
                'key' => 'field_ncma_artwork_en_conservation_2_title',
                'label' => 'Conservation Story 2 title (english)',
                'name' => 'en_conservation_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_2_description',
                'label' => 'Conservation Story 2 description (english)',
                'name' => 'en_conservation_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 2 - title and description - spanish */
            array (
                'key' => 'field_ncma_artwork_es_conservation_2_title',
                'label' => 'Conservation Story 2 title (español)',
                'name' => 'es_conservation_2_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_2_description',
                'label' => 'Conservation Story 2 description (español)',
                'name' => 'es_conservation_2_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 2 - image 1 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_2_primary_image',
                'label' => 'Conservation Story 2 primary image',
                'name' => 'conservation_2_primary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_2_primary_image_caption',
                'label' => 'Conservation Story 2 primary image caption (english)',
                'name' => 'en_conservation_2_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_2_primary_image_caption',
                'label' => 'Conservation Story 2 primary image caption (español)',
                'name' => 'es_conservation_2_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 2 - image 2 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_2_secondary_image',
                'label' => 'Conservation Story 2 secondary image',
                'name' => 'conservation_2_secondary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_2_secondary_image_caption',
                'label' => 'Conservation Story 2 secondary image caption (english)',
                'name' => 'en_conservation_2_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_2_secondary_image_caption',
                'label' => 'Conservation Story 2 secondary image caption (español)',
                'name' => 'es_conservation_2_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /* Conservation Story 3 - title and description - english */
            array (
                'key' => 'field_ncma_artwork_en_conservation_3_title',
                'label' => 'Conservation Story 3 title (english)',
                'name' => 'en_conservation_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_3_description',
                'label' => 'Conservation Story 3 description (english)',
                'name' => 'en_conservation_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 3 - title and description - spanish */
            array (
                'key' => 'field_ncma_artwork_es_conservation_3_title',
                'label' => 'Conservation Story 3 title (español)',
                'name' => 'es_conservation_3_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_3_description',
                'label' => 'Conservation Story 3 description (español)',
                'name' => 'es_conservation_3_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 3 - image 1 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_3_primary_image',
                'label' => 'Conservation Story 3 primary image',
                'name' => 'conservation_3_primary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_3_primary_image_caption',
                'label' => 'Conservation Story 3 primary image caption (english)',
                'name' => 'en_conservation_3_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_3_primary_image_caption',
                'label' => 'Conservation Story 3 primary image caption (español)',
                'name' => 'es_conservation_3_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 3 - image 2 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_3_secondary_image',
                'label' => 'Conservation Story 3 secondary image',
                'name' => 'conservation_3_secondary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_3_secondary_image_caption',
                'label' => 'Conservation Story 3 secondary image caption (english)',
                'name' => 'en_conservation_3_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_3_secondary_image_caption',
                'label' => 'Conservation Story 3 secondary image caption (español)',
                'name' => 'es_conservation_3_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /* Conservation Story 4 - title and description - english */
            array (
                'key' => 'field_ncma_artwork_en_conservation_4_title',
                'label' => 'Conservation Story 4 title (english)',
                'name' => 'en_conservation_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_4_description',
                'label' => 'Conservation Story 4 description (english)',
                'name' => 'en_conservation_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 4 - title and description - spanish */
            array (
                'key' => 'field_ncma_artwork_es_conservation_4_title',
                'label' => 'Conservation Story 4 title (español)',
                'name' => 'es_conservation_4_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_4_description',
                'label' => 'Conservation Story 4 description (español)',
                'name' => 'es_conservation_4_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 4 - image 1 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_4_primary_image',
                'label' => 'Conservation Story 4 primary image',
                'name' => 'conservation_4_primary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_4_primary_image_caption',
                'label' => 'Conservation Story 4 primary image caption (english)',
                'name' => 'en_conservation_4_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_4_primary_image_caption',
                'label' => 'Conservation Story 4 primary image caption (español)',
                'name' => 'es_conservation_4_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 4 - image 2 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_4_secondary_image',
                'label' => 'Conservation Story 4 secondary image',
                'name' => 'conservation_4_secondary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_4_secondary_image_caption',
                'label' => 'Conservation Story 4 secondary image caption (english)',
                'name' => 'en_conservation_4_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_4_secondary_image_caption',
                'label' => 'Conservation Story 4 secondary image caption (español)',
                'name' => 'es_conservation_4_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),

            /* Conservation Story 5 - title and description - english */
            array (
                'key' => 'field_ncma_artwork_en_conservation_5_title',
                'label' => 'Conservation Story 5 title (english)',
                'name' => 'en_conservation_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_5_description',
                'label' => 'Conservation Story 5 description (english)',
                'name' => 'en_conservation_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 5 - title and description - spanish */
            array (
                'key' => 'field_ncma_artwork_es_conservation_5_title',
                'label' => 'Conservation Story 5 title (español)',
                'name' => 'es_conservation_5_title',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_5_description',
                'label' => 'Conservation Story 5 description (español)',
                'name' => 'es_conservation_5_description',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 5 - image 1 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_5_primary_image',
                'label' => 'Conservation Story 5 primary image',
                'name' => 'conservation_5_primary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_5_primary_image_caption',
                'label' => 'Conservation Story 5 primary image caption (english)',
                'name' => 'en_conservation_5_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_5_primary_image_caption',
                'label' => 'Conservation Story 5 primary image caption (español)',
                'name' => 'es_conservation_5_primary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            /* Conservation Story 5 - image 2 and caption */
            array (
                'key' => 'field_ncma_artwork_conservation_5_secondary_image',
                'label' => 'Conservation Story 5 secondary image',
                'name' => 'conservation_5_secondary_image',
                'type' => 'image',
                /*minimum dimensions in px*/
                'min_width' => 50, /*minimum width in px*/
                'min_height' => 50, /*minimum height in px*/
                'instructions' => 'Resolution must be at least 50x50 pixels.',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_en_conservation_5_secondary_image_caption',
                'label' => 'Conservation Story 5 secondary image caption (english)',
                'name' => 'en_conservation_5_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
            array (
                'key' => 'field_ncma_artwork_es_conservation_5_secondary_image_caption',
                'label' => 'Conservation Story 5 secondary image caption (español)',
                'name' => 'es_conservation_5_secondary_image_caption',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
            ),
        ),
        'location' => $location,
        'menu_order' => 4,
        ));
    
endif;

/*
Register Custom REST API Endpoint
Following:
WP REST API - Custom Endpoints - by Watch and Learn
https://www.youtube.com/watch?v=C2twS9ArdCI
WP REST API - Custom Post Types And Fields - by Watch and Learn
https://www.youtube.com/watch?v=76sJL9fd12Y
WP REST API - Custom Filters - by Watch and Learn
https://www.youtube.com/watch?v=5rSfAkLO5eo
*/
function kkane_ncma_artwork_get_data($params) {
    
    // for filtering by post id passed in as a URL query string in the form of ?postids=[1,2,3,etc]
    //
    // this assigns the querystring 'postids' value to the $postIDsToGet variable
    // for example the querystring 'postids' below is ?postids=[9]
    // http://localhost/wordpress/4-dla-1/wp-json/ncma/v1/ncma-artwork?postids=[9]
    // $postIDsToGet will equal and array(9)
    // json_decode() makes the syntax [] read as an array
    $postIDsToGet = json_decode($params->get_param('postids'));

    //return $postIDsToGet; //for debugging, will print the value of this variable at the endpoint http://localhost/wordpress/4-dla-1/wp-json/ncma/v1/ncma-artwork
    
    //The 'p' parameter takes a single post ID, as an integer.
    //To pass an array of posts, you need to use 'post__in':
    $args = array(
        //'p' => $postIDsToGet,
        'post__in' => $postIDsToGet,
        'posts_per_page' => -1, //all
        //'orderby' => 'modified', //order by the date last modified
        'orderby' => 'post__in', //order by the order of $postIDsToGet array of IDs
        'order' => 'DESC',
        'post_type' => 'ncma-artwork',
    );

    $posts = new WP_Query($args);

    //return $posts; //for debugging, will print the value of this variable at the endpoint http://localhost/wordpress/4-dla-1/wp-json/ncma/v1/ncma-artwork

    $data = array();
    $i = 0;

    foreach($posts->posts as $post) {
        $data[$i]['id'] = $post->ID;
        $data[$i]['post_title'] = $post->post_title;
        $data[$i]['post_date_gmt'] = $post->post_date_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site
        $data[$i]['post_modified_gmt'] = $post->post_modified_gmt; //Greenwich Mean Time, not normalized to timezone of WordPress site

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
        /*
        3/25
        If I want to split the interchanges and conservation images out under a separate key ->
        I should just move them to a new array and for loop.
        */
        $image_field_names = array(
            'artwork_main_image',
            'artwork_alt_image_1',
            'artwork_alt_image_2',
            'artwork_alt_image_3',
            'artwork_alt_image_4',
            'interchange_1_image',
            'interchange_2_image',
            'interchange_3_image',
            'interchange_4_image',
            'interchange_5_image',
            'conservation_1_primary_image',
            'conservation_1_secondary_image',
            'conservation_2_primary_image',
            'conservation_2_secondary_image',
            'conservation_3_primary_image',
            'conservation_3_secondary_image',
            'conservation_4_primary_image',
            'conservation_4_secondary_image',
            'conservation_5_primary_image',
            'conservation_5_secondary_image',
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
                    //$data[$i][$k] = $acf_field['url'];
                    // $k = 'main_image', or 'alt_image_1', etc 
                    $data[$i]['images'][$k . '_original'] = $acf_field['url'];
                    $data[$i]['images'][$k . '_wp_sizes'] = $acf_field['sizes'];
                    //$data[$i][$k . '_all_acf_image_data'] = $acf_field; //use to grab all image field data returned by get_field($fieldname)
            }
        }

        /* English tombstone */
        $data[$i]['en']['tombstone']['artist'] = get_field('en_artist', $post->ID);
        $data[$i]['en']['tombstone']['title'] = get_field('en_title', $post->ID);
        $data[$i]['en']['tombstone']['region_time_lifespan'] = get_field('en_region_time', $post->ID);
        $data[$i]['en']['tombstone']['creation_date'] = get_field('en_creation_date', $post->ID);
        $data[$i]['en']['tombstone']['medium'] = get_field('en_medium', $post->ID);
        $data[$i]['en']['tombstone']['credit_line'] = get_field('en_credit_line', $post->ID);
        $data[$i]['en']['tombstone']['chat_text'] = get_field('en_chat_text', $post->ID);

        /* Spanish tombstone */
        $data[$i]['es']['tombstone']['artist'] = get_field('es_artist', $post->ID);
        $data[$i]['es']['tombstone']['title'] = get_field('es_title', $post->ID);
        $data[$i]['es']['tombstone']['region_time_lifespan'] = get_field('es_region_time', $post->ID);
        $data[$i]['es']['tombstone']['creation_date'] = get_field('es_creation_date', $post->ID);
        $data[$i]['es']['tombstone']['medium'] = get_field('es_medium', $post->ID);
        $data[$i]['es']['tombstone']['credit_line'] = get_field('es_credit_line', $post->ID);
        $data[$i]['es']['tombstone']['chat_text'] = get_field('es_chat_text', $post->ID);

        /* Apply videos as an array of object under key 'related_videos'  */
        $data[$i]['en']['related_videos'] = array();
        $data[$i]['es']['related_videos'] = array();
        $related_video_field_names = array(
            'vimeo_url',
            'title',
            'description'
        );
        // $j < 5 for 5 related video fields
        for ($j = 0; $j < 5; $j++)  {
            $fieldsEn = array();
            $fieldsEs = array();
            for ($k = 0; $k < count($related_video_field_names); $k++)  {
                $fieldsEn[$related_video_field_names[$k]] = get_field('en_related_video_' . $j + 1 . '_' . $related_video_field_names[$k], $post->ID);
                $fieldsEs[$related_video_field_names[$k]] = get_field('es_related_video_' . $j + 1 . '_' . $related_video_field_names[$k], $post->ID);
            }
            array_push($data[$i]['en']['related_videos'], $fieldsEn); 
            array_push($data[$i]['es']['related_videos'], $fieldsEs); 
        }

        /* Apply videos as an array of object under key 'related_videos'  */
        /* English and Spanish */
        $data[$i]['en']['interchanges'] = array();
        $data[$i]['es']['interchanges'] = array();
        $interchanges_field_names = array(
            'artist',
            'title',
            'region_time',
            'creation_date',
            'medium',
            'credit_line',
            'description',
        );
        // $j < 5 for 5 interchanges
        for ($j = 0; $j < 5; $j++)  {
            $fieldsEn = array();
            $fieldsEs = array();
            for ($k = 0; $k < count($interchanges_field_names); $k++)  {
                $fieldsEn[$interchanges_field_names[$k]] = get_field('en_interchange_' . $j + 1 . '_' . $interchanges_field_names[$k], $post->ID);
                $fieldsEs[$interchanges_field_names[$k]] = get_field('es_interchange_' . $j + 1 . '_' . $interchanges_field_names[$k], $post->ID);
            }
            array_push($data[$i]['en']['interchanges'], $fieldsEn); 
            array_push($data[$i]['es']['interchanges'], $fieldsEs); 
        }

        /* Apply conservation stories as an array of object under key 'conservation_stories'  */
        /* English and Spanish */
        $data[$i]['en']['conservation_stories'] = array();
        $data[$i]['es']['conservation_stories'] = array();
        $conservation_stories_field_names = array(
            'title',
            'description',
            'primary_image_caption',
            'secondary_image_caption',
        );
        // $j < 5 for 5 conservation stories
        for ($j = 0; $j < 5; $j++)  {
            $fieldsEn = array();
            $fieldsEs = array();
            for ($k = 0; $k < count($conservation_stories_field_names); $k++)  {
                $fieldsEn[$conservation_stories_field_names[$k]] = get_field('en_conservation_' . $j + 1 . '_' . $conservation_stories_field_names[$k], $post->ID);
                $fieldsEs[$conservation_stories_field_names[$k]] = get_field('es_conservation_' . $j + 1 . '_' . $conservation_stories_field_names[$k], $post->ID);
            }
            array_push($data[$i]['en']['conservation_stories'], $fieldsEn); 
            array_push($data[$i]['es']['conservation_stories'], $fieldsEs); 
        }

        $i++;
    }

    return $data;
}

add_action('rest_api_init', function() {
    register_rest_route('ncma/v1', 'ncma-artwork', array(
        'methods' => 'GET',
        'callback' => 'kkane_ncma_artwork_get_data',
    ));
});

/*
Set an image uploaded with ACF to artwork_main_image field automatically as the featured image of a post.
https://support.advancedcustomfields.com/forums/topic/set-image-as-featured-image/

acf/update_value/name={$field_name} - filter for a specific field based on it's name
So, this happens if the artwork_main_image field exists for any post type.
*/
function acf_set_featured_image( $value, $post_id, $field  ){
    
    if($value != ''){
	    //Add the value which is the image ID to the _thumbnail_id meta data for the current post
	    update_post_meta($post_id, '_thumbnail_id', $value);
    }
 
    return $value;
}
add_filter('acf/update_value/name=artwork_main_image', 'acf_set_featured_image', 10, 3);

/*
Modify the default WordPress post updated messages that are displayed when making changes to a post of the 'ncma-artwork' type.
https://ryanwelcher.com/2014/10/change-wordpress-post-updated-messages/
https://developer.wordpress.org/reference/hooks/post_updated_messages/
*/
function ncma_artwork_post_updated_message($messages) {

    // $messages['post'] = array(
    //     0  => '', // Unused. Messages start at index 1.
    //     1  => __( 'Post updated.' ) . $view_post_link_html,
    //     2  => __( 'Custom field updated.' ),
    //     3  => __( 'Custom field deleted.' ),
    //     4  => __( 'Post updated.' ),
    //     /* translators: %s: date and time of the revision */
    //     5  => isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s.' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    //     6  => __( 'Post published.' ) . $view_post_link_html,
    //     7  => __( 'Post saved.' ),
    //     8  => __( 'Post submitted.' ) . $preview_post_link_html,
    //     9  => sprintf( __( 'Post scheduled for: %s.' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_post_link_html,
    //     10 => __( 'Post draft updated.' ) . $preview_post_link_html,
    // );
    
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );
	
	$messages['ncma-artwork'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Artwork updated.' ),
		2  => __( 'Custom field updated.' ),
		3  => __( 'Custom field deleted.'),
		4  => __( 'Artwork updated.' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'My Post Type restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Artwork published.' ),
		7  => __( 'Artwork saved.' ),
		8  => __( 'Artwork submitted.' ),
		9  => sprintf(
			__( 'Artwork scheduled for: <strong>%1$s</strong>.' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Artwork draft updated.' )
	);

    //you can also access items this way
    // $messages['post'][1] = "I just totally changed the Updated messages for standards posts";

    //return the new messaging 
	return $messages;
}
add_filter( 'post_updated_messages', 'ncma_artwork_post_updated_message' );