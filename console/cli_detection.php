<?php

function is_cli() {
    if( defined( 'STDIN' ) ) {
        return 'cli';
    }

    if( php_sapi_name() === 'cli' ) {
        return 'cli';
    }

    if( array_key_exists( 'SHELL' , $_ENV) ) {
        return 'cli';
    }

    if( empty( $_SERVER['REMOTE_ADDR'] ) and !isset( $_SERVER['HTTP_USER_AGENT'] ) and count( $_SERVER['argv'] ) > 0 ) {
        return 'cli';
    } 

    if( !array_key_exists( 'REQUEST_METHOD' , $_SERVER) ) {
        return 'cli';
    }

    return 'web';
}

echo is_cli();