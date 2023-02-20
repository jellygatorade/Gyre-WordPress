<?php
/*
Plugin Name: TESTING New Page with WP_List_Table
Description: Hello world
Version: 1.0
Author: Kevin Kane
*/

// To-do 2/17
// Compare code to this example below, and clean it up
// Last I did was add screen options stuff, which complicated the way the table gets added
// defined global variable within "add_options"
// 
// https://gist.github.com/Latz/7f923479a4ed135e35b2 - Sample plugin for usage of WP_List_Table class (complete version) 
//
// Figure out what is going on with losing the table data small widths with the permuatations of screen options

// Following

// How To Create Native Admin Tables In WordPress The Right Way
// https://www.smashingmagazine.com/2011/11/native-admin-tables-wordpress/

// An example code of using the WP_List_Table class. With Pagination. 
// https://gist.github.com/paulund/7659452

// Adding "export csv" option to wp list table bulk actions
// https://stackoverflow.com/questions/70708264/adding-export-csv-option-to-wp-list-table-bulk-actions

// Width of columns can be controlled by adding CSS to column classes
// https://stackoverflow.com/questions/41933545/can-we-resize-wp-list-table-column-size
/*
echo '<style type="text/css">';
echo '.wp-list-table .column-id { width: 5%; }';
echo '.wp-list-table .column-booktitle { width: 40%; }';
echo '.wp-list-table .column-author { width: 35%; }';
echo '.wp-list-table .column-isbn { width: 20%; }';
echo '</style>';
*/

// add_screen_option() may also be used to configure "admin screen options"
// https://www.wpbeginner.com/glossary/screen-options/
// https://developer.wordpress.org/reference/classes/wp_screen/
// https://developer.wordpress.org/reference/functions/add_screen_option/
// https://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/#screen-options

// WP_List_Table is not loaded automatically so we need to load it in our application
if (!class_exists('WP_List_Table')) {
  //require_once(ABSPATH . 'wp-admin/includes/screen.php');
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class THE_TESTING_TABLE extends WP_List_Table
{

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array($this, 'sort_data')); // usort() - https://www.php.net/manual/en/function.usort.php

        //$perPage = 5;
        $perPage = $this->get_items_per_page('rows_per_page', 5);
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        //$this->_column_headers = array($columns, $hidden, $sortable);
        $this->_column_headers = $this->get_column_info();
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id' => 'Post ID',
            'artist_1_name' => 'Artist 1 Name',
            // 'event_date' => 'Event Date',
            // 'artist' => 'Artist',
            // 'shows_event' => 'Show/Event Name',
            // 'tour_name' => 'Tour Name',
            // 'date_modified'  => 'Date Modified'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array(
            'id' => array('id', 'desc'),
            'artist_1_name' => array('artist_1_name', 'desc')
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {

        $WP_Query_data = array();
        $db_data = array();
        $dummy_data = array();

        /**************************************************
         * Using dummy data (defined at end of file)
         **************************************************/
        // Dummy data
        $dummy_array_films = require_once(ABSPATH . 'wp-content/plugins/kkane-wp-list-table/kkane-dummy-data-films.php');

        foreach($dummy_array_films as $index=>$film) { // This is how to access the $index int within php foreach loop, $result holds current item
            $id = $film['id'];
            $artist_1_name = $film['director'];
  
            $dummy_data[] = array(
                'id' => $id,
                'artist_1_name' => $artist_1_name
            );
        }

        return $dummy_data;

        /**************************************************
         * Using WP_Query
         **************************************************/
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

        // return $WP_Query_data;

        /**************************************************
         * Using direct WordPress database query
         **************************************************/
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
        switch ($column_name) {
            case 'id':
            case 'artist_1_name':
            // case 'event_date':
            // case 'artist':
            // case 'shows_event':
            // case 'tour_name':
            // case 'date_modified':
                return $item[$column_name];

            default:
                $item;
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
        $orderby = 'id'; // this is the property we will sort by default
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
}

/*

*/
function ncma_digital_labels_wp_query() {
    $WP_Query_data = array();

    $args = array(
        'numberposts' => -1, //all
        'orderby' => 'title',
        'order' => 'ASC',
        'post_type' => 'ncma-digital-label',
    );

    $posts = get_posts($args);

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

/**************************************************************************************************
* Create the page that displays our table
**************************************************************************************************/
function kkane_list_page_admin_menu() {
    // https://developer.wordpress.org/reference/functions/add_menu_page/
    $hook = add_menu_page(
        'User Analytics', // page title
        'User Analytics', // menu title
        'manage_options', // capability
        'list-table', // menu slug
        'kkane_display_list_page', // callback function
        'dashicons-analytics', // icon https://developer.wordpress.org/resource/dashicons/
        28 // menu position, for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    );

    function kkane_display_list_page() {
        //echo 'WP_List_Table!';
        global $wp_list_table;

        //$wp_list_table = new THE_TESTING_TABLE(); // instantiation of child class of WP_List_Table must wait until after the basic admin panel menu structure is in place, so it is called within 'admin_menu' action
        $wp_list_table->prepare_items();
      
        // echo '<div class="wrap">';
        // echo $wp_list_table->display(); // this is the syntax to call method on the class instance in php
        // echo '</div>';

        // Use php to insert page title here (abstract add_menu_page args out to array)
        // console.log(`${WPURLS.siteurl}/wp-admin/admin.php?page=list-table&filter_action=${this.value}`)
        ?>
          <style>
            .dropdown {
                margin: 6px 0px 0px 0px;
            }
          </style>

          <script>
            // WPURLS.siteurl is defined for JavaScript use in functions.php (admin_enqueue_scripts)
            function navigate(string) {
                window.location.assign(`${WPURLS.siteurl}/wp-admin/admin.php?page=list-table&filter_action=${string}`)
            }
          </script>

          <div class="wrap">
            <h2>User Analytics</h2>
            <div class="dropdown">
            <select onchange="navigate(this.value)">
                <option value="">Show all</option>
                <?php
                    $digital_label_posts = ncma_digital_labels_wp_query();
                    //write_log($_GET['filter_action']);

                    foreach( $digital_label_posts as $post ): ?>
                        <option 
                            value="<?php echo $post['title']; ?>" 
                            <?php if ($_GET['filter_action'] == $post['title']) { echo 'selected'; }?>
                        ><?php echo $post['title']; ?></option>
                    <?php endforeach; 
                ?>
            </select>
            <input class="button" type="submit" name="filter_action" value="Filter">
            </div>
            <?php $wp_list_table->display(); ?>
          </div>
        <?php
    }

    function add_options() {
        global $wp_list_table;

        $option = 'per_page';
        $args = array(
            'label' => 'Rows',
            'default' => 10,
            'option' => 'rows_per_page'
        );
        add_screen_option( $option, $args );

        $wp_list_table = new THE_TESTING_TABLE();

        //write_log($wp_list_table->get_primary_column());
    }

    // Add screen options
    add_action( "load-$hook", 'add_options' );
}

// When WP runs the 'admin_menu' action, run our function
add_action('admin_menu', 'kkane_list_page_admin_menu');

// Required for saving and loading the screen options data per user (options get stored in 'usermeta' table)
// https://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/#screen-options
function kkane_table_page_set_screen_option($status, $option, $value) {
    return $value;
}
add_filter('set-screen-option', 'kkane_table_page_set_screen_option', 10, 3);