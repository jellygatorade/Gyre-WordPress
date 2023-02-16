<?php
/*
Plugin Name: Disable large image scaling by WordPress
Description: Plugin to disable large image scaling on upload by WordPress. See https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/
Version: 1.0
Author: Kevin Kane
*/

add_filter( 'big_image_size_threshold', '__return_false' );