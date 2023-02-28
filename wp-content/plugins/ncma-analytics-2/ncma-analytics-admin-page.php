<?php
/*****************************************************************************************
 * See kkane-wp-list-table.php for notes on original code references
 *****************************************************************************************/



/*****************************************************************************************
 * Config defined as php constant - https://www.php.net/manual/en/language.constants.php
 *****************************************************************************************/
const NCMA_ANALYTICS_TABLE_CONFIG = array(
    'menu_page' => array(
        'title' => 'User Analytics',
        'slug' => 'analytics-list-table',
        'capability' => 'edit_posts',
        'dashicon' => 'dashicons-analytics',
    ),
    'wp_list_table' => array(
        'columns' => array(
            'title' => 'Title',
            'count' => 'Engagements',
            //'id' => 'Post ID',
            //'artist_1_name' => 'Artist 1 Name',
        ),
        'hidden_columns' => array(),
        'sortable_columns' => array(
            'title' => array('title', 'desc'),
            'count' => array('count', 'desc'),
            //'id' => array('id', 'desc'),
            //'artist_1_name' => array('artist_1_name', 'desc')
        ),
        'user-defaults' => array(
            'rows' => 10,
        ),
    ),
);


/*****************************************************************
 * Makes the slug available to JavaScript as WPNCMAAnalytics.slug
 *****************************************************************/
function ncma_load_analytics_slug_script() {
    wp_register_script('ncma_siteurl_script', '' , array(), null, true);
    wp_enqueue_script('ncma_siteurl_script', '' );
    wp_localize_script('ncma_siteurl_script', 'WPNCMAAnalytics', array( 'slug' => NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['slug'] ));
}
add_action('admin_enqueue_scripts', 'ncma_load_analytics_slug_script');


/************************************************************
 * WP query to be used for selecting the table data
 ************************************************************/
function ncma_analytics_table_wp_query() {
    $args = array(
        'numberposts' => -1, // all
        'orderby' => 'title',
        'order' => 'ASC',
        'post_type' => 'ncma-analytics',
    );

    $WP_Query_data = array();

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {

        while ($the_query->have_posts()) {
            $the_query->the_post();
            $title = get_the_title();
            $id = get_the_ID();

            if (isset($_GET['filter_action']) && $_GET['filter_action'] !== $title) {
                // Continue to next iteration if current post title does not match filter_action query var
                continue;
            }

            // Get post meta here
            $attract_count = get_post_meta($id, 'attract', true);
            $artwork_count = get_post_meta($id, 'artwork', true);

            // $WP_Query_data[] = array(
            //     'title' => $title,
            //     'count' => null,
            // );

            if ($attract_count) {
                $WP_Query_data[] = array(
                    'title' => "{$title} - Attract",
                    'count' => $attract_count
                );
            }

            if ($artwork_count) {
                $WP_Query_data[] = array(
                    'title' => "{$title} - Artwork",
                    'count' => $artwork_count
                );
            }
        }
    }

    return $WP_Query_data;
}


/************************************************************
 * WP query used for creating the select options in tablenav
 * One option for each 'ncma-digital-label' post title
 ************************************************************/
function ncma_digital_labels_titles_wp_query() {
    $args = array(
        'numberposts' => -1, // all
        'orderby' => 'title',
        'order' => 'ASC',
        'post_type' => 'ncma-digital-label',
    );

    //$posts = get_posts($args);

    $WP_Query_data = array();

    $the_query = new WP_Query($args);

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

// WP_List_Table is not loaded automatically so we need to require it
if (!class_exists('WP_List_Table')) {
  //require_once(ABSPATH . 'wp-admin/includes/screen.php');
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


/************************************************************
 * The table class
 * WP_List_Table is provided by Wordpress and extended here
 ************************************************************/
class NCMA_Analytics_Table extends WP_List_Table
{   
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array($this, 'sort_data')); // usort() - https://www.php.net/manual/en/function.usort.php

        $perPage = $this->get_items_per_page('rows_per_page', NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['user-defaults']['rows']);
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        //$this->_column_headers = array($columns, $hidden, $sortable); // not using screen options
        $this->_column_headers = $this->get_column_info(); // using screen options

        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        return NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['columns'];
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['hidden_columns'];
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['sortable_columns'];
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        /**************************************************
         * Using dummy data (imported)
         **************************************************/
        // $dummy_data = array();

        // // Import dummy data
        // $dummy_array_films = require_once(ABSPATH . 'wp-content/plugins/kkane-wp-list-table/kkane-dummy-data-films.php');

        // foreach($dummy_array_films as $index=>$film) { // This is how to access the $index int within php foreach loop, $result holds current item
            
        //     // Testing using query string to filter table rows
        //     // if (isset($_GET['filter_action']) && $film['id'] > 4) {
        //     //     if ($_GET['filter_action'] == "Kunstkamer 348") {
        //     //         break;
        //     //     }
        //     // }
            
        //     $id = $film['id'];
        //     $artist_1_name = $film['director'];
  
        //     $dummy_data[] = array(
        //         'id' => $id,
        //         'artist_1_name' => $artist_1_name
        //     );
        // }

        // return $dummy_data;

        /**************************************************
         * Using WP_Query
         **************************************************/
        // $WP_Query_data = array();

        // $args = array(
        //     'numberposts' => -1, //all
        //     'orderby' => 'modified',
        //     'order' => 'DESC',
        //     'post_type' => 'ncma-artwork',
        // );
    
        // $posts = get_posts($args);

        // $the_query = new WP_Query($args);

        // if ($the_query->have_posts()) {

        //     while ($the_query->have_posts()) {
        //         $the_query->the_post();

        //         $id = get_the_ID();

        //         $artist_1_name = get_field('en_artist_1', $id);

        //         $WP_Query_data[] = array(
        //             'id' => $id,
        //             'artist_1_name' => $artist_1_name
        //         );
        //     }
        // }

        //return $WP_Query_data;
        return ncma_analytics_table_wp_query();

        /**************************************************
         * Using direct WordPress database query
         **************************************************/
        // $db_data = array();

        // global $wpdb; 

        // // Handle wpdb queries if using wp multisite
        // // get_current_blog_id() returns just the int (7)
        // // $wpdb->get_blog_prefix() returns complete prefix (wp_7_)
        // $blog_prefix = $wpdb->get_blog_prefix();
        // $posts_table = $blog_prefix . 'posts';

        // $query = "SELECT * FROM `$posts_table` WHERE `post_type`='ncma-artwork'";
        // $db_results = $wpdb->get_results( $query, OBJECT );

        // foreach($db_results as $index=>$result) { // This is how to access the $index int within php foreach loop, $result holds current item
        //   $id = $result->ID;
        //   $artist_1_name = get_field('en_artist_1', $id);

        //   $db_data[] = array(
        //       'id' => $id,
        //       'artist_1_name' => $artist_1_name
        //   );
        // }
        
        // return $db_data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        if (isset(NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['columns'][$column_name])) {
            return $item[$column_name];
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * $_GET is a reserved variable in php that represents the current query string
     * 
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = array_key_first(NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['columns']); // this is the property we will sort by default
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        // Depending on data type, comparison will be between strings or numberical values
        if (is_string($a[$orderby]) && is_string($b[$orderby])) {
            // sort strings
            $result = strcmp($a[$orderby], $b[$orderby]);
        }
        else if ((is_int($a[$orderby]) && is_int($b[$orderby])) || (is_float($a[$orderby]) && is_float($b[$orderby]))) {
            // sort integers or floats
            if ($a[$orderby] === $b[$orderby]) {
                $result = 0;
            } else {
                $result = ($a[$orderby] < $b[$orderby]) ? -1 : 1;
            }
        } 
        else {
            // data type not identified for sorting
            $error = new WP_Error( 'custom-sort-error', 'Error reading data type in attempt to sort table entries.' );
            if (is_wp_error($error)) {
                ?> 
                    <div><?php echo $error->get_error_message(); ?></div>
                <?php
                throw new Exception('WP_List_Table sorting data types');
            }
            $result = null;
        }

        // ascending
        if ($order === 'asc') {
            return $result;
        }

        // descending
        return -$result;
    }

    function extra_tablenav($which) {
        switch ( $which )
        {
            case 'top':
                ?>  
                    <style>
                        .custom-select {
                            margin-bottom: 4px;
                        }
                    </style>

                    <script>
                        // WPURLS.siteurl is defined for JavaScript use in functions.php (admin_enqueue_scripts)
                        // WPNCMAAnalytics.slug is defined for JavaScript at the top of this script (admin_enqueue_scripts)
                        function navigateFilter(event, exportcsv) {
                            event.preventDefault();
                            const select = document.getElementById("filter-select");

                            let downloadcsv = "";

                            if (exportcsv) {
                                downloadcsv = "&download_csv=true";
                            }

                            switch (select.value) {
                                case 'show-all':
                                    window.location.assign(`${WPURLS.siteurl}/wp-admin/admin.php?page=${WPNCMAAnalytics.slug}${downloadcsv}`);
                                    break;
                                default:
                                    window.location.assign(`${WPURLS.siteurl}/wp-admin/admin.php?page=${WPNCMAAnalytics.slug}&filter_action=${select.value}${downloadcsv}`);
                            }
                        }
                    </script>

                    <!-- onchange="navigateFilter(event, this.value)" -->
                    <select class="custom-select" id="filter-select">
                        <option value="show-all">Show all</option>
                        <?php
                            $digital_label_posts = ncma_digital_labels_titles_wp_query();

                            // Create a dropdown option for each digital label post
                            foreach( $digital_label_posts as $post ): ?>
                                <option 
                                    value="<?php echo $post['title']; ?>" 
                                    <?php 
                                        // Set selected dropdown per url querystring
                                        if (isset($_GET['filter_action'])) {
                                            if ($_GET['filter_action'] == $post['title']) { echo 'selected'; }
                                        }
                                    ?>
                                ><?php echo $post['title']; ?></option>
                            <?php endforeach; 
                        ?>
                    </select>
                    <input class="button" type="submit" name="filter_action" value="Filter" onclick="navigateFilter(event, false)">
                    <input class="button" type="submit" name="export_to_csv" value="Export to CSV" onclick="navigateFilter(event, true)">
                <?php

                break;

            case 'bottom':
                // Your html code to output to tablenav bottom footer
                break;
        }
    }
}

/**************************************************************************************************
* Create the page that displays our table
**************************************************************************************************/
function kkane_list_page_admin_menu() {
    global $ncma_analytics_wp_list_table;

    // https://developer.wordpress.org/reference/functions/add_menu_page/
    $hook = add_menu_page(
        NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['title'], // page title
        NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['title'], // menu title
        NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['capability'], // required user capability to access page
        NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['slug'], // menu slug
        'kkane_display_list_page', // callback function
        NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['dashicon'], // icon https://developer.wordpress.org/resource/dashicons/
        28 // menu position, for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    );

    function kkane_display_list_page() {
        global $ncma_analytics_wp_list_table;

        //$ncma_analytics_wp_list_table = new THE_TESTING_TABLE(); // instantiation of child class of WP_List_Table must wait until after the basic admin panel menu structure is in place, so it is called within 'admin_menu' action
        $ncma_analytics_wp_list_table->prepare_items();
      
        // echo '<div class="wrap">';
        // echo $ncma_analytics_wp_list_table->display(); // this is the syntax to call method on the class instance in php
        // echo '</div>';

        // Use php to insert page title here (abstract add_menu_page args out to array)
        ?>
          <style type="text/css">
            .wp-list-table .column-title { 
                /* width: 85%; */
            }

            .wp-list-table .column-count { 
                /* text-align: right; */
                width: 50%; 
            }
          </style>

          <div class="wrap">
            <h1><?php echo NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['title'] ?></h1>
            <?php $ncma_analytics_wp_list_table->display(); ?>
          </div>
        <?php
    }

    function add_options() {
        global $ncma_analytics_wp_list_table;

        $option = 'per_page';
        $args = array(
            'label' => 'Rows',
            'default' => NCMA_ANALYTICS_TABLE_CONFIG['wp_list_table']['user-defaults']['rows'],
            'option' => 'rows_per_page'
        );
        add_screen_option( $option, $args );

        $ncma_analytics_wp_list_table = new NCMA_Analytics_Table();

        //write_log($ncma_analytics_wp_list_table->get_primary_column());
    }

    // Add screen options
    add_action( "load-$hook", 'add_options' );
}

// Create our page when WordPress runs the 'admin_menu' action
add_action('admin_menu', 'kkane_list_page_admin_menu');


// Required for saving and loading the screen options data per user (options get stored in 'usermeta' table)
// https://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/#screen-options
function kkane_table_page_set_screen_option($status, $option, $value) {
    return $value;
}
add_filter('set-screen-option', 'kkane_table_page_set_screen_option', 10, 3);


// Generate and download CSV
// Added as action to 'admin_init' hook because headers cannot be reset if html has already been delievered
function kkane_csv_export() {
    if (
        isset($_GET['page']) && 
        $_GET['page'] === NCMA_ANALYTICS_TABLE_CONFIG['menu_page']['slug'] &&
        isset($_GET['download_csv']) && 
        $_GET['download_csv'] === 'true'
    ) {

        global $wpdb;
            
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv";');

        // clean out other output buffers
        ob_end_clean();

        $fp = fopen('php://output', 'w');

        // CSV/Excel header label
        $header_row = array(
            0 => 'id',
            1 => 'artist_1_name',
        );

        //write the header
        fputcsv($fp, $header_row);

        // Handle wpdb queries if using wp multisite
        // get_current_blog_id() returns just the int (7)
        // $wpdb->get_blog_prefix() returns complete prefix (wp_7_)
        $blog_prefix = $wpdb->get_blog_prefix();
        $posts_table = $blog_prefix . 'posts';

        $query = "SELECT * FROM `$posts_table` WHERE `post_type`='ncma-artwork'";
        $db_results = $wpdb->get_results( $query, OBJECT );

        if(!empty($db_results)) {
            foreach($db_results as $index=>$result) { // This is how to access the $index int within php foreach loop, $result holds current item
                $id = $result->ID;
                $artist_1_name = get_field('en_artist_1', $id);
  
                $OutputRecord = array(
                    'id' => $id,
                    'artist_1_name' => $artist_1_name
                );

                fputcsv($fp, $OutputRecord);
            }
        }

        fclose( $fp ); // could also use fpassthru( $fp );
        exit;  
    }
}
add_action('admin_init', 'kkane_csv_export');