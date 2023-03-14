<?php

// needed for maybe_create_table()
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

function ncma_analytics_maybe_create_table() {
    global $wpdb;

    $tablename = $wpdb->prefix . 'ncma_analytics'; 
    $columns = '(id BIGINT(20) AUTO_INCREMENT, date DATE, title TEXT, ncma_digital_label_id BIGINT(20), ncma_digital_label_title TEXT, ncma_artwork_id BIGINT(20), ncma_artwork_title TEXT, clicks BIGINT(20), avg_duration FLOAT, PRIMARY KEY (id))';
    $main_sql_create = 'CREATE TABLE ' . $tablename . ' ' . $columns . ';';

    // maybe_create_table() creates a table in the database, if it doesn’t already exist
    // https://developer.wordpress.org/reference/functions/maybe_create_table/
    maybe_create_table( $tablename, $main_sql_create );
}

register_activation_hook( dirname(__FILE__) . '/ncma-analytics.php', 'ncma_analytics_maybe_create_table' );