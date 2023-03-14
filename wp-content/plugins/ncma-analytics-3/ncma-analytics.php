<?php
/*
Plugin Name: TESTING - NCMA Analytics Post Type - 3
Description: Plugin to register the ncma-analytics post type. Trying out custom SQL table.
Version: 3.0
Author: Kevin Kane
*/

// Require the scripts to execute them
// dirname(__FILE__) represents the current directory
require_once(dirname(__FILE__) . '/ncma-analytics-create-sql-table.php');
require_once(dirname(__FILE__) . '/ncma-analytics-register-posttype.php');
require_once(dirname(__FILE__) . '/ncma-analytics-admin-page.php');