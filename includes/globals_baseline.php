<?php

// error logging
ini_set( 'display_startup_errors', 1 );
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );
error_reporting( E_ALL );

// session start
session_start();

// start timer for page loaded var
$time = microtime();
$time = explode( ' ', $time );
$time = $time[1] + $time[0];
$start = $time;

// vars
ini_set("default_socket_timeout", 15);
ini_set("memory_limit", -1);
$globals['dev']			 		= true;
$globals['basedir']         	= '/home/jamiewhittingham/public_html/projects/coastline/';

// site vars
$globals['url']					= 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/';
$globals['platform_name']		= 'CoastLine';
$globals['platform_version']	= '1.0.0';
$globals['copyright']			= 'Written by Jamie Whittingham.';

