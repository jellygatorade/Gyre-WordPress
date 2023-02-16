<?php
/*
Plugin Name: 'Hello World' admin menu page
Description: Adds a page and menu item to wp-admin called "Hello World"
Version: 1.0
Author: Kevin Kane
*/

function display_hello_world_page() {
  echo 'Hello World!';
}

function hello_world_admin_menu() {
  add_menu_page(
        'Hello World', // page title
        'Hello World', // menu title
        'manage_options', // capability
        'hello-world', // menu slug
        'display_hello_world_page' // callback function
    );
}

add_action('admin_menu', 'hello_world_admin_menu');