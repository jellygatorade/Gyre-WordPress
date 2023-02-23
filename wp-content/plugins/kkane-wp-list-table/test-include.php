<?php

// Generic function for writing data to wp-content/debug.log file
function test_include_write_log( $log ) {
    if ( true === WP_DEBUG ) {
        if ( is_array($log) || is_object($log) ) {
            error_log( print_r($log, true) );
        } else {
            error_log( $log );
        }
    }
}

test_include_write_log("hello world test include");