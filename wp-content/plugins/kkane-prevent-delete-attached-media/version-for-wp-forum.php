<?php
// /*
// Plugin Name: TESTING - Prevent deletion of attached media - Version for WordPress forum
// Description: Not for actual usage
// Version: 1.0
// Author: Kevin Kane
// */

// // Prevent deletion of attached media
// function check_acf_image_field_usage( $attachment_id ) {

//     // Relevant meta keys are the image field names
//     $image_field_names = array(
//         'acf_image_field_name_example_1',
//         'acf_image_field_name_example_2',
//         'acf_image_field_name_example_3',
//     );

//     // Create a meta query from $image_field_names starting with 'relation' => 'OR'
//     $image_fields_meta_query = array('relation' => 'OR');
//     for ($i = 0; $i < count($image_field_names); $i++)  {
//         $newArray = array(
//             'key' => $image_field_names[$i],
//             'value' => $attachment_id,
//             'compare' => '=',
//         );
//         array_push($image_fields_meta_query, $newArray);
//     }

//     $args = array(
//         'post_type'  => 'custom-post-type',
//         'meta_query' => $image_fields_meta_query
//     );
//     $query = new WP_Query($args);

//     // If 0 posts were found in the meta_query
//     if (count($query->posts) === 0) {
//         // CHANGE THIS write_log FUNCTION INTO SOMETHING THAT WILL OUTPUT TO wp-admin?
//         write_log("The attachment that was requested to be deleted is included in " . count($query->posts) . " posts, so it will be deleted.");

//         // Proceed to delete the attachment.
//         return;
    
//     // If 1 or more posts were found in the meta_query
//     } else if (count($query->posts) > 0) {
//         // CHANGE THESE write_log FUNCTIONS INTO SOMETHING THAT WILL OUTPUT TO wp-admin?
//         write_log("The attachment that was requested to be deleted is included in " . count($query->posts) . " posts.");
//         write_log('It is currently attached as an image to:');

//         // Loop through meta query results to log each discovered post containing the attachment
//         for ($i = 0; $i < count($query->posts); $i++)  {
//             write_log($query->posts[$i]->ID . ' - ' . $query->posts[$i]->post_title);
//         }

//         // Stop WordPress execution so that the attachment is not deleted.
//         $message = 'Sorry, this attachment cannot be deleted.'; 
//         wp_die($message);
//     }
// }
// add_action( 'delete_attachment', 'check_acf_image_field_usage' );

// // Here are the requirements
// // I want to 
// // - Print results of a WP_Query to a wp-admin page (upload.php) that happens within the 'delete_attachment' hook?
// // ----> All that matters to me is that administrators see the reults of the query, I don't care much about how or where it appears.
// // - In other words, the content for the admin notice depends on the WP_Query results, which runs when an admin tries to delete an attachment
// // - What is the best way to print information from a WP_Query to a wp-admin page at the time the hook is called?
// // - Simply using at admin notice doesn't work because it shows the same data every time the page is loaded, and the WP_Query does not run when the page loads.

// // - Is there a better method then wp_die() to stop the attachment from being deleted?


// // Generic function for writing data to wp-content/debug.log file
// function write_log( $log ) {

//     if ( true === WP_DEBUG ) {

//         if ( is_array($log) || is_object($log) ) {
//             error_log( print_r($log, true) );
//         } else {
//             error_log( $log );
//         }
        
//     }

// }


// // Working on a wp-admin error notice here
// function prevent_delete_attached_media_error_notice() {
    ?>
    <!-- <div class="notice notice-error is-dismissible">
        <p><?php //_e( 'There has been an error. Bummer!', 'my_plugin_textdomain' ); ?></p>
    </div> -->
    <?php
// }
// add_action( 'admin_notices', 'prevent_delete_attached_media_error_notice' );