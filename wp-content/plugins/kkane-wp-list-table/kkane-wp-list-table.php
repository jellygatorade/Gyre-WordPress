<?php
/*
Plugin Name: TESTING New Page with WP_List_Table
Description: Hello world
Version: 1.0
Author: Kevin Kane
*/

// Following

// How To Create Native Admin Tables In WordPress The Right Way
// https://www.smashingmagazine.com/2011/11/native-admin-tables-wordpress/

// An example code of using the WP_List_Table class. With Pagination. 
// https://gist.github.com/paulund/7659452

// Adding "export csv" option to wp list table bulk actions
// https://stackoverflow.com/questions/70708264/adding-export-csv-option-to-wp-list-table-bulk-actions

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
        usort($data, array($this, 'sort_data'));

        $perPage = 5;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
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
        return array('id' => array('id', false));
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

        /**************************************************
         * Using WP_Query
         **************************************************/
        $args = array(
            'numberposts' => -1, //all
            'orderby' => 'modified',
            'order' => 'DESC',
            'post_type' => 'ncma-artwork',
        );
    
        $posts = get_posts($args);

        // Do your stuff, e.g.
        $the_query = new WP_Query($args);

        //write_log($the_query);

        if ($the_query->have_posts()) {

            while ($the_query->have_posts()) {
                $the_query->the_post();

                $id = get_the_ID();

                $artist_1_name = get_field('en_artist_1', $id);

                $WP_Query_data[] = array(
                    'id' => $id,
                    'artist_1_name' => $artist_1_name
                );
            }
        }

        /**************************************************
         * Using direct WordPress database query
         **************************************************/
        global $wpdb; 

        // Handle wpdb queries if using wp multisite
        // get_current_blog_id() returns just the int (7)
        // $wpdb->get_blog_prefix() returns complete prefix (wp_7_)
        $blog_prefix = $wpdb->get_blog_prefix();
        $posts_table = $blog_prefix . 'posts';

        $query = "SELECT * FROM `$posts_table` WHERE `post_type`='ncma-artwork'";
        $db_results = $wpdb->get_results( $query, OBJECT );

        foreach($db_results as $index=>$result) { // This is how to access the $index int within php foreach loop, $result holds current item
          $id = $result->ID;
          $artist_1_name = get_field('en_artist_1', $id);

          $db_data[] = array(
              'id' => $id,
              'artist_1_name' => $artist_1_name
          );
        }

        return $db_data;
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
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        //$orderby = 'event_date';
        $orderby = 'id';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }


        $result = strcmp($a[$orderby], $b[$orderby]);

        // ascending
        if ($order === 'asc') {
            return $result;
        }

        // descending
        return -$result;
    }
}

/**************************************************************************************************
* Create the page that displays our table
**************************************************************************************************/
function kkane_display_list_page() {
  //echo 'WP_List_Table!';

  $wp_list_table = new THE_TESTING_TABLE(); // instantiation of child class of WP_List_Table must wait until after the basic admin panel menu structure is in place, so it is called within 'admin_menu' action
  $wp_list_table->prepare_items();

  // echo '<div class="wrap">';
  // echo $wp_list_table->display(); // this is the syntax to call method on the class instance in php
  // echo '</div>';

  ?>
    <div class="wrap">
      <h2>User Analytics</h2>
      <?php $wp_list_table->display(); ?>
    </div>
  <?php

  //echo '<div class="wrap">' . $pagetable . '</div>';

  // global $wpdb;
  // $query = "SELECT * FROM $wpdb->links";
  // $this->items = $wpdb->get_results($query);
  // echo $this;
}

function kkane_list_page_admin_menu() {
  // https://developer.wordpress.org/reference/functions/add_menu_page/
  add_menu_page(
        'User Analytics', // page title
        'User Analytics', // menu title
        'manage_options', // capability
        'list-table', // menu slug
        'kkane_display_list_page', // callback function
        'dashicons-analytics', // icon https://developer.wordpress.org/resource/dashicons/
        28 // menu position, for ordering the wp-admin UI menu https://wpbeaches.com/moving-custom-post-types-higher-admin-menu-wordpress-dashboard/
    );
}

// When WP runs the 'admin_menu' action, run our function
add_action('admin_menu', 'kkane_list_page_admin_menu');