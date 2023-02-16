<?php
/*
Plugin Name: TESTING - Prevent deletion of attached media
Description: Disables the ability to delete media attached to posts from the media library.
Version: 1.0
Author: Kevin Kane
*/

/**************************************************************************************************
* Prevent wp-admin users from deleting images from the media library that are currently used in ACF image fields
* 
* This is accomplished using the 'delete_attachment' hook.
*
* Code that adds an "ACF Image field use" column to the media library list view is also included below
* So that wp-admin users can easily see which images are being used in which Artworks.
**************************************************************************************************/

// Get WP_Query of all posts of ncma-artwork type that use a passed attachment id within an ACF image field
function wp_query_metaquery_check_acf_image_field_usage( $attachment_id ) {
    // Relevant meta keys are the image field names
    $image_field_names = array(
        'artwork_main_image',
        'artwork_alt_image_1',
        'artwork_alt_image_2',
        'artwork_alt_image_3',
        'artwork_alt_image_4',
        'artwork_alt_image_5',
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

    // Create a meta query 
    // Note 'relation' => 'OR'
    // See 
    // https://developer.wordpress.org/reference/classes/wp_query/
    // and
    // Get posts by meta value
    // https://wordpress.stackexchange.com/questions/144078/get-posts-by-meta-value
    $image_fields_meta_query = array('relation' => 'OR');
    for ($i = 0; $i < count($image_field_names); $i++)  {
        $newArray = array(
            'key' => $image_field_names[$i],
            'value' => $attachment_id,
            'compare' => '=',
        );
        array_push($image_fields_meta_query, $newArray);
    }

    $args = array(
        'post_type'  => 'ncma-artwork',
        'meta_query' => $image_fields_meta_query
    );
    $query = new WP_Query($args);

    return $query;
}

// Prevent deletion of media used in acf image fields
function prevent_delete_if_acf_image_field_usage( $attachment_id ) {
    
    $query = wp_query_metaquery_check_acf_image_field_usage( $attachment_id );

    // If 0 posts were found in the meta_query
    if (count($query->posts) === 0) {
        write_log("The attachment that was requested to be deleted is included in " . count($query->posts) . " posts, so it will be deleted.");

        // Proceed to delete the attachment.
        return;
    
    // If 1 or more posts were found in the meta_query
    } else if (count($query->posts) > 0) {
        write_log("The attachment that was requested to be deleted is included in " . count($query->posts) . " posts.");
        write_log('It is currently attached as an image to:');

        // Loop through meta query results to log each discovered post containing the attachment
        for ($i = 0; $i < count($query->posts); $i++)  {
            write_log($query->posts[$i]->ID . ' - ' . $query->posts[$i]->post_title);
        }

        // Stop wordpress so that the attachment is not deleted.
        // Is there a better method then wp_die() to stop the attachment from being deleted?
        //
        // * If viewing the media library grid view, the wp_die($message) does not show.
        // * If viewing the media library in list view, the wp_die($message) shows.
        //
        $message = 'Sorry, this media cannot be deleted because it is currently used in one or more ACF image fields.'; 
        wp_die($message);
    }
}
add_action( 'delete_attachment', 'prevent_delete_if_acf_image_field_usage' );

// Generic function for writing data to wp-content/debug.log file
function write_log( $log ) {
    if ( true === WP_DEBUG ) {
        if ( is_array($log) || is_object($log) ) {
            error_log( print_r($log, true) );
        } else {
            error_log( $log );
        }
    }
}

/**************************************************************************************************
* Begin media library list view column customization
* 
* Add column to the media library list view to display which ACF image fields media is attached to
* 
* Following
* https://awhitepixel.com/blog/modify-add-custom-columns-post-list-wordpress-admin/
* 
**************************************************************************************************/
add_filter('manage_media_columns', function($columns) {
    // Remove the default 'comments' column
    unset($columns['comments']);
    
    // Add the custom column 'ACF Image field usage' under column key 'ncma_acf_image_field_usage'
    return array_merge($columns, ['ncma_acf_image_field_usage' => __('ACF Image field usage', 'textdomain')]);
});

 
add_action('manage_media_custom_column', function($column_key, $attachment_id) {

    if ($column_key == 'ncma_acf_image_field_usage') {

        $query = wp_query_metaquery_check_acf_image_field_usage( $attachment_id );

        // If 0 posts were found in the meta_query
        if (count($query->posts) === 0) {
            echo '<span style="color:inherit;">'; _e("None", 'textdomain');
            echo '</span>';
            
        // If 1 or more posts were found in the meta_query
        } else if (count($query->posts) > 0) {
            // Loop through meta query results to log each discovered post containing the attachment
            for ($i = 0; $i < count($query->posts); $i++)  {
                echo '<span style="color:inherit;">'; _e($query->posts[$i]->ID . ' - ' . $query->posts[$i]->post_title, 'textdomain'); 
                echo '</span>';
                echo '<br>';
            }
        }

    }

}, 10, 2);
/**************************************************************************************************
* End media library list view column customization
**************************************************************************************************/