<?php
/*
Plugin Name: TESTING - NCMA Analytics Post Type - 1
Description: Plugin to register the ncma-analytics post type. Creates ncma-analytics post for each digital label, does not store timestamp information for ncma-analytics posts.
Version: 1.0
Author: Kevin Kane
*/

// Require the scripts to execute them
// dirname(__FILE__) represents the current directory
require_once(dirname(__FILE__) . '/ncma-analytics-admin-page.php');
require_once(dirname(__FILE__) . '/ncma-analytics-register-posttype.php');